@php
    // Same rule as the settings sheet: the group the server paints is decided here, so the
    // first frame is the finished frame and Alpine's `x-show` only has to hide the others.
    // It has to sit above the layout tag, because Blade evaluates a component's opening
    // attributes before it runs the slot body, and the title is one of them.
    $profileTabs = [
        'profile' => 'Profile',
        'appearance' => 'Appearance',
        'security' => 'Security',
        'passkeys' => 'Passkeys',
        'sessions' => 'Sessions',
    ];
    $requestedTab = (string) request()->query('tab', 'profile');
    $activeTab = array_key_exists($requestedTab, $profileTabs) ? $requestedTab : 'profile';
@endphp

{{-- The group is in the served title, not only in `document.title` after Alpine runs.
     Five URLs used to ship one title, so a deep link to the security pane arrived
     labelled "Profile" to a crawler, to a screen reader reading the tab strip, and to
     anybody looking at browser history. The runtime watcher writes the same string, so
     there is no swap on first paint. --}}
<x-layouts::admin :title="$profileTabs[$activeTab] . ' - My account'">
    <div class="hb-sheet mx-auto max-w-[72rem] space-y-5 py-6 sm:py-8">
        <header class="hb-sheet-head">
            <div class="hb-id">
                <div class="hb-id__mark">
                    @if ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar of {{ $user->name }}" class="hb-id__img" />
                    @endif
                    <span class="hb-id__initials">{{ $user->initials() }}</span>
                </div>

                <div class="hb-id__text">
                    <h1 class="hb-sheet-title">{{ $user->name }}</h1>

                    <p class="hb-id__line">
                        <span class="hb-mono">{{ $user->email }}</span>
                        @if ($user->phone)
                            <span class="hb-sep" aria-hidden="true">·</span>
                            <span class="hb-mono">{{ $user->phone }}</span>
                        @endif
                    </p>

                    <p class="hb-id__roles">
                        @forelse ($user->roles as $role)
                            <span class="hb-plate">{{ $role->name }}</span>
                        @empty
                            <span class="hb-muted">No roles assigned</span>
                        @endforelse
                    </p>
                </div>
            </div>

            <dl class="hb-sheet-facts">
                <div class="hb-sheet-fact">
                    <dt>Status</dt>
                    <dd>
                        @if ($user->email_verified_at)
                            <span class="hb-id__state"><span class="hb-dot hb-dot--ok" aria-hidden="true"></span> Email verified</span>
                        @else
                            <span class="hb-id__state"><span class="hb-dot hb-dot--warn" aria-hidden="true"></span> Email not verified</span>
                        @endif
                    </dd>
                </div>
                <div class="hb-sheet-fact">
                    <dt>Member since</dt>
                    <dd>
                        <time class="hb-num" datetime="{{ $user->created_at->toIso8601String() }}">
                            {{ $user->created_at->format('M j, Y') }}
                        </time>
                    </dd>
                </div>
                <div class="hb-sheet-fact">
                    <dt>Marks</dt>
                    <dd class="hb-num" data-sheet-total>0</dd>
                </div>
                <div class="hb-sheet-fact">
                    <dt>Consent</dt>
                    <dd class="hb-id__consent">
                        <span><span class="hb-label">Terms</span> {{ $user->terms_accepted_at?->format('M j, Y') ?? 'Not recorded' }}</span>
                        <span><span class="hb-label">Privacy</span> {{ $user->privacy_accepted_at?->format('M j, Y') ?? 'Not recorded' }}</span>
                    </dd>
                </div>
            </dl>
        </header>

        <div
            x-data="{ 
            tab: '{{ $activeTab }}',
            showDeleteModal: {{ $errors->has('password') ? 'true' : 'false' }},
            showEnableModal: false,
            verifyingPassword: false,
            enablePasswordError: '',
            showRecoveryCodesModal: false,
            recoveryCodes: {{ json_encode(session('recovery_codes') ?? []) }},
            loadingCodes: false,
            confirmCode: '',

            init() {
                const groupLabels = {{ \Illuminate\Support\Js::from($profileTabs) }};
                const nameTab = (value) => {
                    document.title = (groupLabels[value] || 'Profile') + ' - My account - {{ config('app.name', 'Laravel') }}';
                };

                nameTab(this.tab);

                this.$watch('tab', value => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', value);
                    window.history.replaceState({}, '', url.toString());
                    nameTab(value);
                });

                if (this.recoveryCodes.length > 0) {
                    this.showRecoveryCodesModal = true;
                }
            },

            /* The desk already has one place a message goes, and it does not stop the page: the
               toaster. `alert()` was used here for both a failure and a success, which is a browser
               dialog sitting over this design, dismissed by the same key as the sheet's own copy,
               and on a phone it is a system panel nobody styled. */
            notify(type, message) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { type, message } }));
            },

            /* One clipboard answer for the two things on this sheet worth copying. The API can be
               absent — a plain-http origin, an older browser, an embedded webview — and reaching for
               `writeText` on undefined throws before a promise exists, which is a click that does
               nothing and says nothing. A refusal after the promise is the milder case, and it was
               the only one handled. */
            copyText(text, label) {
                if (! navigator.clipboard || typeof navigator.clipboard.writeText !== 'function') {
                    this.notify('warning', 'This browser cannot copy for you. Select the text and copy by hand.');

                    return;
                }

                navigator.clipboard.writeText(text).then(() => {
                    this.notify('success', label);
                }).catch(() => {
                    this.notify('warning', 'The browser would not copy it. Select the text and copy by hand.');
                });
            },

            /* One code path for both reads of the recovery codes: fetching them and regenerating
               them differ only in the verb and in what has to be said afterwards. */
            async postRecoveryCodes(url, method, headers) {
                this.loadingCodes = true;

                try {
                    const response = await fetch(url, {
                        method,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...headers,
                        },
                    });

                    const data = await response.json().catch(() => ({}));

                    if (! response.ok) {
                        throw new Error(data.message || 'The codes could not be read from the record.');
                    }

                    if (! Array.isArray(data.recovery_codes)) {
                        throw new Error('The response carried no codes.');
                    }

                    this.recoveryCodes = data.recovery_codes;
                    this.showRecoveryCodesModal = true;

                    return true;
                } catch (error) {
                    this.notify('error', error.message);

                    return false;
                } finally {
                    this.loadingCodes = false;
                }
            },

            showCodes() {
                this.postRecoveryCodes('{{ route('admin.profile.two-factor.recovery-codes') }}', 'GET', {});
            },

            async regenerateCodes() {
                const proceed = await window.adminConfirm({
                    title: 'Regenerate recovery codes',
                    message: 'The codes you have now stop working the moment these are written, so anything '
                        + 'you saved somewhere else will no longer open this account.',
                    confirm: 'Regenerate',
                    danger: true,
                });

                if (! proceed) {
                    return;
                }

                await this.postRecoveryCodes('{{ route('admin.profile.two-factor.regenerate-recovery-codes') }}', 'POST', {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                });
            },

            copyRecoveryCodes() {
                /* One code per line, which is the shape a password manager wants. The separator is
                   spelled out rather than escaped because this lives inside an HTML attribute, where
                   a backslash has already passed through Blade once. */
                this.copyText(
                    this.recoveryCodes.join(String.fromCharCode(10)),
                    this.recoveryCodes.length + ' codes copied.'
                );
            },

            submitEnablePassword() {
                this.enablePasswordError = '';
                this.verifyingPassword = true;

                fetch('{{ route('admin.profile.two-factor.confirm-password') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ password: this.$refs.enable2faPassword.value })
                })
                .then(async r => {
                    if (! r.ok) {
                        const data = await r.json().catch(() => ({}));
                        const errors = data.errors && data.errors.password;
                        throw new Error((errors && errors[0]) || data.message || 'Unable to verify your password.');
                    }
                    return r.json();
                })
                .then(() => {
                    this.$refs.enable2faForm.submit();
                })
                .catch(e => {
                    this.enablePasswordError = e.message;
                    this.verifyingPassword = false;

                    /* The sentence is read by the dialog, so the caret has to go back to the box it
                       is about. A disabled input could not hold it. */
                    this.$nextTick(() => this.$refs.enable2faPassword.focus());
                });
            }
        }"
        >
            <div class="hb-sheet-body">
                <nav class="hb-index" aria-label="Profile groups">
                    @foreach ($profileTabs as $key => $label)
                        <button
                            type="button"
                            class="hb-index-item"
                            data-sheet-index="{{ $key }}"
                            @click="tab = '{{ $key }}'"
                            :aria-current="tab === '{{ $key }}' ? 'true' : 'false'"
                        >
                            <span>{{ $label }}</span>
                            <span class="hb-index-count hb-num" data-sheet-count></span>
                            <span class="hb-index-mark hb-num" data-sheet-marks hidden></span>
                        </button>
                    @endforeach
                </nav>

                <!-- Tab Contents -->
                <div class="hb-panes">
                {{-- Profile Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="profile"
                    x-show="tab === 'profile'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'profile') style="display: none" @endif
                >
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" data-sheet-form>
                        @csrf
                        @method('PUT')

                        <x-sheet.block title="Profile Information" note="Your own name, the address a reset link goes to, and the number a fitter can call.">
                            <x-sheet.input
                                label="Name"
                                name="name"
                                key="users.name"
                                :value="$user->name"
                            />
                            <x-sheet.input
                                label="Username"
                                name="username"
                                key="users.username"
                                autocomplete="username"
                                :hint="\App\Support\Username::formatHint().'. You can sign in with this or your email address.'"
                                :value="old('username', $user->username)"
                            />
                            <x-sheet.input
                                label="Email"
                                name="email"
                                key="users.email"
                                type="email"
                                :value="$user->email"
                            />
                            <x-sheet.input
                                label="Phone"
                                name="phone"
                                key="users.phone"
                                type="tel"
                                placeholder="+1 555 000 1234"
                                :value="$user->phone"
                            />
                            <x-sheet.input
                                label="Avatar"
                                name="avatar"
                                key="users.avatar"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                hint="JPG, PNG or WebP, up to 2 MB. Leave empty to keep the current avatar."
                            />
                        </x-sheet.block>

                        <div class="hb-foot" data-sheet-foot @if (session('success')) data-just-set @endif>
                            <p class="hb-foot__count" data-sheet-count>Nothing marked. Everything on this sheet is set.</p>

                            <div class="hb-foot__actions">
                                {{-- No "show only marks" here. This sheet has five fields on screen at a
                                     time; filtering to the changed ones would hide four to save nothing.
                                     The settings sheet has a hundred and five, where it is the whole
                                     point. --}}
                                <span class="hb-foot__live" data-sheet-live aria-live="polite" role="status"></span>
                                <x-sheet.commit label="Save Profile" />
                            </div>
                        </div>
                    </form>

                    <x-sheet.block title="Activity" note="What this account has done in the shop, counted from the records themselves.">
                        <dl class="hb-ledger">
                            @foreach ([
                                'Bookings' => $user->bookings_count,
                                'Milestones logged' => $user->booking_events_count,
                                'Inspection notes' => $user->inspection_notes_count,
                                'Blog posts' => $user->posts_count,
                            ] as $label => $count)
                                <div class="hb-ledger-cell">
                                    <dt>{{ $label }}</dt>
                                    <dd><span class="hb-ledger-value hb-num">{{ $count }}</span></dd>
                                </div>
                            @endforeach
                        </dl>
                    </x-sheet.block>

                    <x-sheet.block title="Recent bookings">
                        @if ($recentBookings->isEmpty())
                            <p class="hb-repeat__empty">No bookings linked to this account yet.</p>
                        @else
                            <ul class="hb-list">
                                @foreach ($recentBookings as $booking)
                                    <li class="hb-list__row">
                                        <span class="hb-list__main">
                                            <span class="hb-list__title">{{ $booking->client_name }}</span>
                                            <time class="hb-list__when hb-num" datetime="{{ ($booking->scheduled_at ?? $booking->created_at)->toIso8601String() }}">
                                                {{ $booking->scheduled_at?->format('M j, Y H:i') ?? $booking->created_at->format('M j, Y') }}
                                            </time>
                                        </span>
                                        <span class="hb-list__end"><span class="hb-plate">{{ $booking->status }}</span></span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </x-sheet.block>

                    <x-sheet.block title="Recent blog posts">
                        <x-slot:head>
                            <a href="{{ route('admin.posts.index', ['user' => $user->id]) }}" class="hb-link hb-link--utility">View all</a>
                        </x-slot:head>

                        @if ($recentPosts->isEmpty())
                            <p class="hb-repeat__empty">No blog posts yet.</p>
                        @else
                            <ul class="hb-list">
                                @foreach ($recentPosts as $post)
                                    <li class="hb-list__row">
                                        <span class="hb-list__main">
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="hb-list__title">{{ $post->title }}</a>
                                            <time class="hb-list__when hb-num" datetime="{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}">
                                                {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}
                                            </time>
                                        </span>
                                        <span class="hb-list__end"><span class="hb-plate">{{ $post->status }}</span></span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </x-sheet.block>

                    <x-sheet.block title="Delete Account" id="delete-account" note="Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.">
                        <x-slot:head>
                            <span class="hb-plate">irreversible</span>
                        </x-slot:head>

                        <button
                            type="button"
                            @click="showDeleteModal = true"
                            class="hb-btn hb-btn--danger"
                        >
                            Delete account and everything on it
                        </button>
                    </x-sheet.block>
                </div>

                {{-- Appearance Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="appearance"
                    x-show="tab === 'appearance'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'appearance') style="display: none" @endif
                >
                    <form action="{{ route('admin.profile.appearance.update') }}" data-sheet-form>
                        @csrf
                        @method('PUT')

                        <x-sheet.block title="Appearance Settings" note="Two choices about how the panel looks at you. Neither changes what you can reach.">
                            <x-sheet.field label="Theme Preference" key="users.theme" stack="true">
                                <div class="hb-samples">
                                    @foreach (['light' => ['Light', 'White faces, ink text'], 'dark' => ['Dark', 'The black the shop is photographed in'], 'auto' => ['Auto', 'Follows this device, day or night']] as $value => [$themeLabel, $themeSub])
                                        <label class="hb-sample" data-face="{{ $value }}">
                                            <input
                                                type="radio"
                                                name="theme"
                                                value="{{ $value }}"
                                                {{ $user->theme?->value === $value || ($value === 'auto' && ! $user->theme?->value) ? 'checked' : '' }}
                                            />
                                            <span class="hb-sample__face">
                                                <span class="hb-sample__bars" aria-hidden="true">
                                                    <span class="hb-sample__bar"></span>
                                                    <span class="hb-sample__bar"></span>
                                                </span>
                                            </span>
                                            <span class="hb-sample__word">{{ $themeLabel }}</span>
                                            <span class="hb-sample__sub">{{ $themeSub }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </x-sheet.field>

                            {{-- Desktop navigation, chosen the same way and saved by the same
                                 button. The two diagrams are the whole argument for putting a
                                 control here at all: a person can see which shape they are
                                 picking before the panel rearranges itself. --}}
                            <x-sheet.field
                                label="Desktop navigation"
                                key="users.admin_nav_layout"
                                error="admin_nav_layout"
                                stack="true"
                                hint="Which holds the menu on a wide screen. Phones and tablets use the slide-out menu either way, so this is a desk preference and not a change to what you can reach."
                            >
                                <div class="hb-samples">
                                    @foreach (\App\Enums\AdminNavLayout::options() as $layout)
                                        @php
                                            // Old input wins after a rejected submit, or the form would
                                            // redisplay the saved value and quietly discard what was
                                            // just typed. `$current` is what the person is actually
                                            // looking at, which is the only thing worth ticking.
                                            $current = old('admin_nav_layout', \App\Support\AdminNav::for($user)->value);
                                        @endphp

                                        <label class="hb-sample" data-face="nav">
                                            <input
                                                type="radio"
                                                name="admin_nav_layout"
                                                value="{{ $layout->value }}"
                                                {{ $current === $layout->value ? 'checked' : '' }}
                                            />
                                            <span class="hb-sample__face hb-sample__face--diagram">
                                                <span class="hb-sample__diagram" aria-hidden="true">
                                                    @if ($layout === \App\Enums\AdminNavLayout::Sidebar)
                                                        {{-- A rail down the left edge, a bar across the top,
                                                             and content as lines beside both. --}}
                                                        <svg class="h-16 w-full" fill="none" viewBox="0 0 120 56" preserveAspectRatio="none">
                                                            <rect class="stroke-current" x="0.75" y="0.75" width="118.5" height="54.5" rx="4" stroke-width="1.5" />
                                                            <rect class="fill-current text-gray-400 dark:text-white/30" x="2" y="2" width="30" height="52" rx="3" />
                                                            <line class="stroke-current" x1="32" y1="14" x2="119" y2="14" stroke-width="1.5" />
                                                            <rect class="fill-current text-gray-400 dark:text-white/30" x="4" y="6" width="26" height="4" rx="2" />
                                                            <rect class="fill-current text-gray-300 dark:text-white/15" x="40" y="24" width="60" height="4" rx="2" />
                                                            <rect class="fill-current text-gray-300 dark:text-white/15" x="40" y="34" width="44" height="4" rx="2" />
                                                            <rect class="fill-current text-gray-300 dark:text-white/15" x="40" y="44" width="52" height="4" rx="2" />
                                                        </svg>
                                                    @else
                                                        {{-- The same parts with the menu turned sideways:
                                                             a filled band carrying the items, and the
                                                             content below it at full width. --}}
                                                        <svg class="h-16 w-full" fill="none" viewBox="0 0 120 56" preserveAspectRatio="none">
                                                            <rect class="stroke-current" x="0.75" y="0.75" width="118.5" height="54.5" rx="4" stroke-width="1.5" />
                                                            <rect class="fill-current text-gray-400 dark:text-white/30" x="2" y="2" width="116" height="14" rx="3" />
                                                            <rect class="fill-current text-white dark:text-[#0A0A0F]" x="6" y="7" width="18" height="4" rx="2" />
                                                            <rect class="fill-current text-white dark:text-[#0A0A0F]" x="28" y="7" width="18" height="4" rx="2" />
                                                            <rect class="fill-current text-white dark:text-[#0A0A0F]" x="50" y="7" width="14" height="4" rx="2" />
                                                            <circle class="fill-current text-white dark:text-[#0A0A0F]" cx="110" cy="9" r="4" />
                                                            <rect class="fill-current text-gray-300 dark:text-white/15" x="8" y="24" width="90" height="4" rx="2" />
                                                            <rect class="fill-current text-gray-300 dark:text-white/15" x="8" y="34" width="70" height="4" rx="2" />
                                                            <rect class="fill-current text-gray-300 dark:text-white/15" x="8" y="44" width="78" height="4" rx="2" />
                                                        </svg>
                                                    @endif
                                                </span>
                                            </span>

                                            <span class="hb-sample__word">{{ $layout->label() }}</span>
                                            <span class="hb-sample__sub">{{ $layout->description() }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </x-sheet.field>
                        </x-sheet.block>

                        <div class="hb-foot" data-sheet-foot @if (session('success')) data-just-set @endif>
                            <p class="hb-foot__count" data-sheet-count>Nothing marked. Everything on this sheet is set.</p>

                            <div class="hb-foot__actions">
                                <x-sheet.commit label="Save Appearance" />
                            </div>
                        </div>
                    </form>
                </div>


                {{-- Security Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="security"
                    x-show="tab === 'security'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'security') style="display: none" @endif
                >
                    <form
                        action="{{ route('admin.profile.password.update') }}"
                        method="POST"
                        data-sheet-form
                        x-data="{
                            /* A password this box is about to hand to the shop has to come from the
                               CSPRNG. `Math.random()` is not one: it is a predictable generator, and
                               the button says Generate Secure beside a field whose whole job is the
                               account's key. A byte is only used when it falls inside the largest
                               multiple of the charset size, so no character is likelier than any
                               other and nothing here leans on `%` of a biased draw. */
                            generatePassword() {
                                const length = 16;
                                const charset =
                                    'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
                                const limit = Math.floor(256 / charset.length) * charset.length;
                                const byte = new Uint8Array(1);
                                let retVal = '';

                                while (retVal.length < length) {
                                    window.crypto.getRandomValues(byte);

                                    if (byte[0] < limit) {
                                        retVal += charset.charAt(byte[0] % charset.length);
                                    }
                                }

                                const apply = (ref) => {
                                    ref.value = retVal;

                                    /* The policy list under the box listens for `input`, and a value
                                       written by hand fires nothing. Without this the checklist goes
                                       on describing the empty box that was there a moment ago, next to
                                       a hint that says it ticks as you type. */
                                    ref.dispatchEvent(new Event('input', { bubbles: true }));
                                };

                                apply($refs.passwordInput);
                                apply($refs.passwordConfirmInput);
                            },
                            showPassword: false,
                            showConfirmPassword: false,
                        }"
                    >
                        @csrf
                        @method('PUT')

                        <x-sheet.block
                            title="Update Password"
                            note="Changes the password this account signs in with, and nothing else. The rules a new one has to clear are written on the security page, not on this form."
                        >
                            <x-sheet.input
                                label="Current Password"
                                name="current_password"
                                type="password"
                                autocomplete="current-password"
                            />

                            {{-- The two boxes that carry the reveal toggle stay raw: an Alpine
                                 `:type` binding cannot live on a component tag, and the checklist
                                 below finds this box by `document.getElementById('password')`. --}}
                            <x-sheet.field
                                label="New Password"
                                key="users.password"
                                for="password"
                                error="password"
                                stack="true"
                                hint="The list under the box is the policy the shop enforces, and it ticks as you type. Generate Secure fills both boxes with a random one."
                            >
                                <div class="relative hb-inline">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        id="password"
                                        name="password"
                                        x-ref="passwordInput"
                                        autocomplete="new-password"
                                        class="hb-input"
                                        passwordrules="{{ \Illuminate\Validation\Rules\Password::default()->toPasswordRulesString() }}"
                                        @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = ! showPassword"
                                        aria-controls="password"
                                        :aria-expanded="showPassword ? 'true' : 'false'"
                                        :aria-label="showPassword ? '{{ __('auth.login.hide_password') }}' : '{{ __('auth.login.show_password') }}'"
                                        class="hb-btn hb-btn--reveal"
                                    >
                                        <svg x-show="! showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.77 9.77 0 012.804-3.704M15.48 15.48l2.58 2.58M12 9a3 3 0 013 3m-3-3a3 3 0 00-3 3m0 0a3 3 0 013-3m0 0l-2.58-2.58M21 21l-9-9m0 0L3 3" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="hb-cluster">
                                    <button
                                        type="button"
                                        @click="generatePassword()"
                                        class="hb-btn hb-btn--small"
                                    >
                                        Generate Secure
                                    </button>
                                </div>

                                <x-ui.password-rules input="password" />
                            </x-sheet.field>

                            <x-sheet.field
                                label="Confirm Password"
                                for="password_confirmation"
                                stack="true"
                                hint="Type the new one twice. The two boxes are compared before anything is written, which is the only reason this row exists."
                            >
                                <div class="relative hb-inline">
                                    <input
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        x-ref="passwordConfirmInput"
                                        autocomplete="new-password"
                                        class="hb-input"
                                        passwordrules="{{ \Illuminate\Validation\Rules\Password::default()->toPasswordRulesString() }}"
                                    />
                                    <button
                                        type="button"
                                        @click="showConfirmPassword = ! showConfirmPassword"
                                        aria-controls="password_confirmation"
                                        :aria-expanded="showConfirmPassword ? 'true' : 'false'"
                                        :aria-label="showConfirmPassword ? '{{ __('auth.login.hide_password') }}' : '{{ __('auth.login.show_password') }}'"
                                        class="hb-btn hb-btn--reveal"
                                    >
                                        <svg x-show="! showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.77 9.77 0 012.804-3.704M15.48 15.48l2.58 2.58M12 9a3 3 0 013 3m-3-3a3 3 0 00-3 3m0 0a3 3 0 013-3m0 0l-2.58-2.58M21 21l-9-9m0 0L3 3" />
                                        </svg>
                                    </button>
                                </div>
                            </x-sheet.field>
                        </x-sheet.block>

                        <div class="hb-foot" data-sheet-foot @if (session('success')) data-just-set @endif>
                            <p class="hb-foot__count" data-sheet-count>Nothing marked. Everything on this sheet is set.</p>

                            <div class="hb-foot__actions">
                                <x-sheet.commit label="Update Password" />
                            </div>
                        </div>
                    </form>

                    <x-sheet.block
                        title="Two-Factor Authentication"
                        note="One more thing to bring to a sign-in besides the password. It changes this account only, and it asks at every door the account can be used at."
                    >
                        @if (! $user->two_factor_secret)
                            {{-- Step 1: Enable. The modal submits this form through
                                 `this.$refs.enable2faForm.submit()` once its fetch has
                                 verified the password, so it stays a plain POST and stays
                                 hidden. It commits on its own, so it gets no marks and no
                                 foot. --}}
                            <form action="{{ route('admin.profile.two-factor.enable') }}" method="POST" x-ref="enable2faForm" class="hidden">
                                @csrf
                            </form>

                            <div class="hb-state">
                                <p class="hb-state__head">
                                    <span class="hb-state__dot" aria-hidden="true"></span>
                                    <span class="hb-state__word">Two-factor authentication is off.</span>
                                </p>

                                <p class="hb-state__body">
                                    Signing in asks for the password alone. Turn it on and every sign-in
                                    wants a six-digit code as well. The secret only reaches your record
                                    once you have confirmed a code from the app.
                                </p>

                                <div class="hb-repeat-bar">
                                    {{-- Focus is the dialog's job now; asking for it from here was a
                                         race with x-show that usually lost. --}}
                                    <button
                                        type="button"
                                        @click="showEnableModal = true"
                                        class="hb-btn hb-btn--primary"
                                    >
                                        Enable Two-Factor
                                    </button>
                                </div>
                            </div>
                        @elseif ($user->two_factor_secret && ! $user->two_factor_confirmed_at)
                            {{-- Step 2: Setup (Unconfirmed). A secret exists and is not live, which
                                 is what the amber dot is for. The plate keeps a white face in both
                                 themes because an inverted QR does not scan.

                                 The code and the thing it comes from are two halves of one job, so
                                 they are set side by side: nobody should have to hold a phone in one
                                 hand and scroll for the box in the other. --}}
                            <div class="hb-setup">
                                {{-- The state is a sentence about the whole job, not about the left
                                   half of it. It spans both columns so the two panes underneath are
                                   plainly the two halves of one thing. --}}
                                <div class="hb-state hb-state--warn hb-setup__state">
                                    <p class="hb-state__head">
                                        <span class="hb-state__dot" aria-hidden="true"></span>
                                        <span class="hb-state__word">Waiting for the first code.</span>
                                    </p>

                                    <p class="hb-state__body">
                                        The secret is on this record but not yet live. Nothing about
                                        signing in changes until six digits come back from the app.
                                    </p>
                                </div>

                                <div class="hb-setup__pane">
                                    <div class="hb-setup__qr">
                                        <div class="hb-asset__frame hb-qr">{!! $qrCodeSvg !!}</div>
                                        <p class="hb-setup__say">Scan this QR code with your authenticator app.</p>
                                    </div>

                                    @if ($manualSetup)
                                        {{-- The typed half of the same key. Every authenticator takes
                                             an account and a key by hand, and a person setting this up
                                             on the machine they sign in from has no camera pointed at
                                             its own screen. --}}
                                        <div x-data="{ showKey: false }">
                                            <button
                                                type="button"
                                                @click="showKey = ! showKey"
                                                :aria-expanded="showKey ? 'true' : 'false'"
                                                aria-controls="two-factor-manual-key"
                                                class="hb-btn hb-btn--small"
                                            >
                                                <span x-show="! showKey">Cannot scan it? Type the key</span>
                                                <span x-cloak x-show="showKey">Hide the key</span>
                                            </button>

                                            <dl
                                                id="two-factor-manual-key"
                                                class="hb-manual-key"
                                                x-show="showKey"
                                                x-cloak
                                            >
                                                <div>
                                                    <dt>Account</dt>
                                                    <dd class="hb-mono">{{ $manualSetup['account'] }}</dd>
                                                </div>

                                                <div>
                                                    <dt>Issuer</dt>
                                                    <dd>{{ $manualSetup['issuer'] }}</dd>
                                                </div>

                                                <div>
                                                    <dt>Key</dt>
                                                    <dd>
                                                        <span class="hb-mono" data-secret="{{ $manualSetup['secret'] }}">{{ $manualSetup['secret'] }}</span>

                                                        <button
                                                            type="button"
                                                            @click="copyText($el.previousElementSibling.dataset.secret, 'Setup key copied.')"
                                                            class="hb-btn hb-btn--small"
                                                        >
                                                            Copy key
                                                        </button>
                                                    </dd>
                                                </div>

                                                <p class="hb-manual-key__say">
                                                    Time-based, six digits, SHA-1, thirty seconds. Those are
                                                    Fortify's defaults, and every mainstream app already uses them.
                                                </p>
                                            </dl>
                                        </div>
                                    @endif
                                </div>

                                <div class="hb-setup__pane">
                                    <form
                                        id="two-factor-confirm-form"
                                        action="{{ route('admin.profile.two-factor.confirm') }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <x-sheet.input
                                            label="Authentication code"
                                            name="code"
                                            key="users.two_factor_confirmed_at"
                                            error="code"
                                            mono="true"
                                            inputmode="numeric"
                                            autocomplete="one-time-code"
                                            placeholder="Enter authentication code"
                                            hint="The six digits the app shows right now. Not the key above, and not a code that has just rolled past."
                                            required
                                        />
                                    </form>

                                    {{-- `data-confirm` is the whole ask: admin.js catches the submit,
                                         puts the question through the desk's own panel, and lets the
                                         form go only once it is answered. An inline
                                         `onsubmit="return confirm(...)"` used to do this, which froze
                                         the page mid-scroll for a question about a QR code. --}}
                                    <form
                                        id="two-factor-cancel-form"
                                        action="{{ route('admin.profile.two-factor.cancel') }}"
                                        method="POST"
                                        data-confirm="Cancelling throws away the key this QR code and the typed one were built from. Nothing about signing in changes, and you can start again whenever you like."
                                        data-confirm-title="Cancel two-factor setup"
                                        data-confirm-yes="Discard the key"
                                        data-confirm-danger="1"
                                    >
                                        @csrf
                                    </form>

                                    {{-- One bar for the answers to the question the pane is asking,
                                         and the milder of the two destructive controls gets its own
                                         band below a rule that says why. The forms stay separate
                                         elements because they post to different endpoints with
                                         different CSRF tokens and one of them carries the delegated
                                         ask; `form=` is what lets the buttons sit where they read
                                         best instead of where the markup happens to put them. --}}
                                    <div class="hb-repeat-bar">
                                        <button
                                            type="submit"
                                            form="two-factor-confirm-form"
                                            class="hb-btn hb-btn--primary"
                                        >
                                            Confirm
                                        </button>
                                    </div>

                                    <div class="hb-repeat-bar hb-repeat-bar--split">
                                        <button
                                            type="submit"
                                            form="two-factor-cancel-form"
                                            class="hb-btn hb-btn--danger"
                                        >
                                            Cancel setup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Step 3: Confirmed. --}}
                            <div class="hb-state hb-state--ok">
                                <p class="hb-state__head">
                                    <span class="hb-state__dot" aria-hidden="true"></span>
                                    <span class="hb-state__word">Two-factor authentication is enabled.</span>
                                </p>

                                <p class="hb-state__body">
                                    Signing in now needs the code from your app as well as the password.
                                    The codes below are the way back in when that device is gone.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button
                                        type="button"
                                        @click="showCodes()"
                                        :disabled="loadingCodes"
                                        :aria-busy="loadingCodes ? 'true' : 'false'"
                                        class="hb-btn"
                                    >
                                        <span x-show="! loadingCodes">Show Recovery Codes</span>
                                        <span x-cloak x-show="loadingCodes">Loading...</span>
                                    </button>

                                    <button
                                        type="button"
                                        @click="regenerateCodes()"
                                        :disabled="loadingCodes"
                                        :aria-busy="loadingCodes ? 'true' : 'false'"
                                        class="hb-btn"
                                    >
                                        Regenerate Recovery Codes
                                    </button>
                                </div>

                                {{-- The one control here that cannot be undone by pressing another
                                     button afterwards, so it does not sit in the row with the two
                                     that can. Its own band, its own rule, and the words that say
                                     why it is on its own. --}}
                                <div class="hb-repeat-bar hb-repeat-bar--split">
                                    <form
                                        action="{{ route('admin.profile.two-factor.disable') }}"
                                        method="POST"
                                        data-confirm="Switching two-factor off means the next sign-in wants only the password, on every device, until you set it up again."
                                        data-confirm-title="Disable two-factor authentication"
                                        data-confirm-yes="Switch it off"
                                        data-confirm-danger="1"
                                    >
                                        @csrf
                                        <button type="submit" class="hb-btn hb-btn--danger">Disable TFA</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </x-sheet.block>
                </div>

                {{-- Passkeys Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="passkeys"
                    x-show="tab === 'passkeys'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'passkeys') style="display: none" @endif
                >
                    {{-- The island brings its own block, head and sentence, so the pane gives it
                         no frame of its own — a card inside a card would put two headings on one
                         subject. --}}
                    @livewire('passkeys')
                </div>

                {{-- Sessions Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="sessions"
                    x-show="tab === 'sessions'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'sessions') style="display: none" @endif
                >
                    {{-- The partial already carries two titled blocks of its own, each with a
                         sentence about what it holds. Wrapping them in a third would put a card
                         inside a card and give the pane two headings where it has one subject. --}}
                    @include('admin.profile.sessions-tab')
                </div>
                </div>
            </div>

            {{-- Enable Two-Factor Password Modal.
                 `x-dialog` owns what an overlay owes the page: Tab stays inside it, Escape and the
                 backdrop put the flag back, the desk's scroller stops behind it, and focus lands on
                 the box and returns to the button that asked. Before that was one `x-show`, and the
                 only one of those four it did was the hiding. --}}
            <div
                class="hb-modal"
                x-cloak
                x-show="showEnableModal"
                x-transition:enter="hb-x-enter"
                x-transition:enter-start="hb-x-enter-from"
                x-transition:enter-end="hb-x-enter-to"
                x-transition:leave="hb-x-leave"
                x-transition:leave-start="hb-x-leave-from"
                x-transition:leave-end="hb-x-leave-to"
            >
                <div
                    class="hb-modal__panel"
                    x-dialog="showEnableModal"
                    x-show="showEnableModal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="enable-2fa-title"
                    aria-describedby="enable-2fa-lede"
                    x-transition:enter="hb-x-enter"
                    x-transition:enter-start="hb-x-enter-from"
                    x-transition:enter-end="hb-x-enter-to"
                    x-transition:leave="hb-x-leave"
                    x-transition:leave-start="hb-x-leave-from"
                    x-transition:leave-end="hb-x-leave-to"
                    @click.outside="showEnableModal = false"
                >
                    <h3 id="enable-2fa-title" class="hb-modal__title">Confirm your password</h3>

                    <p id="enable-2fa-lede" class="hb-modal__body">
                        For your security, this asks for the password again rather than trusting the
                        session you are already in. Turning two-factor on means every sign-in wants a
                        six-digit code from an app as well as the password, and the app is being given
                        the key to your account.
                    </p>

                    <form @submit.prevent="submitEnablePassword()" class="hb-modal__form">
                        <label class="hb-field__label" for="two_factor_enable_password">Password</label>

                        {{-- Read-only rather than disabled while it works: a disabled box drops
                             focus, and a refused password has to say so somewhere the caret can
                             go back to. --}}
                        <input
                            type="password"
                            id="two_factor_enable_password"
                            x-ref="enable2faPassword"
                            data-dialog-initial
                            name="password"
                            autocomplete="current-password"
                            :read-only="verifyingPassword"
                            :aria-invalid="enablePasswordError ? 'true' : 'false'"
                            :aria-describedby="enablePasswordError ? 'enable-2fa-error' : 'enable-2fa-lede'"
                            class="hb-input"
                        />

                        <p
                            id="enable-2fa-error"
                            x-show="enablePasswordError"
                            x-cloak
                            x-text="enablePasswordError"
                            role="alert"
                            class="hb-error"
                        ></p>

                        <div class="hb-modal__foot">
                            <button
                                type="button"
                                @click="showEnableModal = false"
                                :disabled="verifyingPassword"
                                class="hb-btn"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="verifyingPassword"
                                class="hb-btn hb-btn--primary"
                            >
                                <span x-show="! verifyingPassword">Verify</span>
                                <span x-cloak x-show="verifyingPassword">Verifying...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Delete Account. This one used to be the only overlay on the page that was not a
                 dialog: no `x-dialog`, no `role`, no label, so Escape did nothing, Tab walked out
                 into the sheet behind it, the desk kept scrolling under the backdrop, and the caret
                 never arrived in the box. The other two had all of that. Now all three do.

                 It also reopens itself when the password is refused. The form is a plain POST, so a
                 wrong answer comes back as a redirect, and a modal whose flag lives only in Alpine
                 came back *closed* with one red sentence somewhere on a page of forty fields. The
                 `password` key is safe to read here: the password update writes `current_password`,
                 and the two-factor check answers as JSON and never reaches the session bag. --}}
            <div
                class="hb-modal"
                x-cloak
                x-show="showDeleteModal"
                x-transition:enter="hb-x-enter"
                x-transition:enter-start="hb-x-enter-from"
                x-transition:enter-end="hb-x-enter-to"
                x-transition:leave="hb-x-leave"
                x-transition:leave-start="hb-x-leave-from"
                x-transition:leave-end="hb-x-leave-to"
            >
                <div
                    class="hb-modal__panel"
                    x-dialog="showDeleteModal"
                    x-show="showDeleteModal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="delete-account-title"
                    aria-describedby="delete-account-lede"
                    x-transition:enter="hb-x-enter"
                    x-transition:enter-start="hb-x-enter-from"
                    x-transition:enter-end="hb-x-enter-to"
                    x-transition:leave="hb-x-leave"
                    x-transition:leave-start="hb-x-leave-from"
                    x-transition:leave-end="hb-x-leave-to"
                    @click.outside="showDeleteModal = false"
                >
                    <h3 id="delete-account-title" class="hb-modal__title">Delete this account</h3>

                    <p id="delete-account-lede" class="hb-modal__body">
                        Your staff record is removed and you are signed out on every device. There is
                        no undo and no grace period: the only way back is for another Super Admin to
                        create the account again, and it will not be this one.
                    </p>

                    <form action="{{ route('admin.profile.destroy') }}" method="POST" class="hb-modal__form">
                        @csrf
                        {{-- `admin.profile.destroy` is registered as a DELETE route. This form posted
                             POST with no `@method`, so every answer to this dialog was a 405 and the
                             account was never deleted, from either side. The two session forms on the
                             Sessions pane already carry the override; this one was missed. --}}
                        @method('DELETE')

                        <label class="hb-field__label" for="delete_account_password">Password</label>

                        <input
                            type="password"
                            id="delete_account_password"
                            name="password"
                            autocomplete="current-password"
                            data-dialog-initial
                            @if ($errors->has('password')) aria-invalid="true" aria-describedby="delete-account-error" @endif
                            class="hb-input"
                        />

                        @error('password')
                            <p id="delete-account-error" class="hb-error" role="alert">{{ $message }}</p>
                        @enderror

                        <div class="hb-modal__foot">
                            <button
                                type="button"
                                @click="showDeleteModal = false"
                                class="hb-btn"
                            >
                                Keep the account
                            </button>
                            <button
                                type="submit"
                                class="hb-btn hb-btn--danger"
                            >
                                Delete it
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Recovery Codes. The X and the footer used to both be named "Close", so the first
                 thing a screen reader read out was a duplicate, and the caret landed on the X
                 rather than on the one control worth pressing here. The X is now named for what it
                 closes, the footer says Done, and Copy takes focus. --}}
            <div
                class="hb-modal"
                x-cloak
                x-show="showRecoveryCodesModal"
                x-transition:enter="hb-x-enter"
                x-transition:enter-start="hb-x-enter-from"
                x-transition:enter-end="hb-x-enter-to"
                x-transition:leave="hb-x-leave"
                x-transition:leave-start="hb-x-leave-from"
                x-transition:leave-end="hb-x-leave-to"
            >
                <div
                    class="hb-modal__panel hb-modal__panel--wide"
                    x-dialog="showRecoveryCodesModal"
                    x-show="showRecoveryCodesModal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="recovery-codes-title"
                    aria-describedby="recovery-codes-lede"
                    x-transition:enter="hb-x-enter"
                    x-transition:enter-start="hb-x-enter-from"
                    x-transition:enter-end="hb-x-enter-to"
                    x-transition:leave="hb-x-leave"
                    x-transition:leave-start="hb-x-leave-from"
                    x-transition:leave-end="hb-x-leave-to"
                    @click.outside="showRecoveryCodesModal = false"
                >
                    <div class="hb-modal__head">
                        <h3 id="recovery-codes-title" class="hb-modal__title">Recovery codes</h3>

                        <button
                            type="button"
                            @click="showRecoveryCodesModal = false"
                            aria-label="Close recovery codes"
                            class="hb-modal__x"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <p id="recovery-codes-lede" class="hb-modal__body">
                        Each one is used once, by itself, when the device is gone. Copy them whole into
                        a password manager rather than photographing the screen. They stay the same
                        until you regenerate them, so this dialog can be opened again from the
                        Security group at any time.
                    </p>

                    <ol class="hb-codes hb-modal__codes">
                        <template x-for="(code, index) in recoveryCodes" :key="code">
                            <li class="hb-code">
                                <span class="hb-code__n" aria-hidden="true" x-text="String(index + 1).padStart(2, '0')"></span>
                                <span x-text="code"></span>
                            </li>
                        </template>
                    </ol>

                    <div class="hb-modal__foot">
                        <button
                            type="button"
                            @click="copyRecoveryCodes()"
                            data-dialog-initial
                            class="hb-btn hb-btn--primary"
                        >
                            Copy all
                        </button>
                        <button
                            type="button"
                            @click="showRecoveryCodesModal = false"
                            class="hb-btn"
                        >
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="hb-colophon">
            <p>These are your own settings. The company-wide ones live on <a class="hb-link hb-link--utility" href="{{ route('admin.settings.index') }}">Company Settings</a>.</p>
            <p>Password rules are set by <a class="hb-link hb-link--utility" href="{{ route('admin.password-policy.index') }}">the security page</a>, not here.</p>
        </footer>
    </div>
</x-layouts::admin>
