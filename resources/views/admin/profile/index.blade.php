<x-layouts::admin title="Profile">
    <div class="mx-auto max-w-4xl space-y-10 py-6 sm:py-10">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Profile Settings</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your account settings and preferences.</p>
        </div>

        <!-- Account Summary -->
        <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-gray-100 dark:bg-white/10">
                    @if ($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar of {{ $user->name }}" class="h-full w-full object-cover" />
                    @else
                        <span class="text-2xl font-semibold text-gray-500 dark:text-gray-300">{{ $user->initials() }}</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        @if ($user->email_verified_at)
                            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">Email verified</span>
                        @else
                            <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Email not verified</span>
                        @endif
                    </div>
                    <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    @if ($user->phone)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</p>
                    @endif
                    <div class="flex flex-wrap gap-1.5">
                        @forelse ($user->roles as $role)
                            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">{{ $role->name }}</span>
                        @empty
                            <span class="text-xs text-gray-400 dark:text-gray-500">No roles assigned</span>
                        @endforelse
                    </div>
                </div>
                <div class="space-y-1 text-xs text-gray-500 dark:text-gray-400 sm:pt-1 sm:text-right">
                    <p>Member since {{ $user->created_at->format('M j, Y') }}</p>
                    <p>Terms: {{ $user->terms_accepted_at?->format('M j, Y') ?? 'Not recorded' }}</p>
                    <p>Privacy: {{ $user->privacy_accepted_at?->format('M j, Y') ?? 'Not recorded' }}</p>
                </div>
            </div>
        </div>

        <div
            x-data="{ 
            tab: '{{ request()->query('tab', 'profile') }}',
            showDeleteModal: false,
            showEnableModal: false,
            verifyingPassword: false,
            enablePasswordError: '',
            showRecoveryCodesModal: false,
            recoveryCodes: {{ json_encode(session('recovery_codes') ?? []) }},
            loadingCodes: false,
            confirmCode: '',

            init() {
                this.$watch('tab', value => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', value);
                    window.history.replaceState({}, '', url.toString());
                });

                if (this.recoveryCodes.length > 0) {
                    this.showRecoveryCodesModal = true;
                }
            },

            showCodes() {
                this.loadingCodes = true;
                fetch('{{ route('admin.profile.two-factor.recovery-codes') }}', {
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async r => {
                    if (! r.ok) {
                        const data = await r.json().catch(() => ({}));
                        throw new Error(data.message || 'Failed to fetch recovery codes');
                    }
                    return r.json();
                })
                .then(data => {
                    if (data.recovery_codes && Array.isArray(data.recovery_codes)) {
                        this.recoveryCodes = data.recovery_codes;
                        this.showRecoveryCodesModal = true;
                    }
                })
                .catch(e => {
                    console.error('TFA Error:', e);
                    alert(e.message);
                })
                .finally(() => this.loadingCodes = false);
            },

            regenerateCodes() {
                if (! confirm('Regenerate recovery codes? Old ones will stop working.')) return;
                
                this.loadingCodes = true;
                fetch('{{ route('admin.profile.two-factor.regenerate-recovery-codes') }}', {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(async r => {
                    if (! r.ok) {
                        const data = await r.json().catch(() => ({}));
                        throw new Error(data.message || 'Failed to regenerate codes');
                    }
                    return r.json();
                })
                .then(data => {
                    if (data.recovery_codes && Array.isArray(data.recovery_codes)) {
                        this.recoveryCodes = data.recovery_codes;
                        this.showRecoveryCodesModal = true;
                    }
                })
                .catch(e => {
                    console.error('TFA Error:', e);
                    alert(e.message);
                })
                .finally(() => this.loadingCodes = false);
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
                });
            }
        }"
        >
            <!-- Tabs Navigation -->
            <div class="no-scrollbar mb-8 flex gap-x-5 overflow-x-auto border-b border-gray-200 dark:border-white/10">
                @foreach (['profile' => 'Profile', 'appearance' => 'Appearance', 'security' => 'Security', 'passkeys' => 'Passkeys', 'sessions' => 'Sessions'] as $key => $label)
                    <button
                        type="button"
                        @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'border-gray-900 dark:border-white text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="shrink-0 whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition-colors"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Tab Contents -->
            <div class="space-y-8">
                <!-- Profile Tab -->
                <div
                    x-show="tab === 'profile'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8"
                >
                    <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ $user->name }}"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ $user->email }}"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                    <input
                                        type="tel"
                                        name="phone"
                                        value="{{ $user->phone }}"
                                        placeholder="+1 555 000 1234"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Avatar</label>
                                    <input
                                        type="file"
                                        name="avatar"
                                        accept="image/jpeg,image/png,image/webp"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm transition-all outline-none file:mr-3 file:rounded-full file:border-0 file:bg-gray-900 file:px-4 file:py-1.5 file:text-xs file:font-medium file:text-white focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:file:bg-white dark:file:text-gray-900 dark:focus:ring-white"
                                    />
                                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG or WebP, up to 2 MB. Leave empty to keep the current avatar.</p>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Save Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Activity -->
                    <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Activity</h3>

                        <dl class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                            @foreach ([
                                'Bookings' => $user->bookings_count,
                                'Milestones logged' => $user->booking_events_count,
                                'Inspection notes' => $user->inspection_notes_count,
                            ] as $label => $count)
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $count }}</dd>
                                </div>
                            @endforeach
                        </dl>

                        <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Recent bookings</h4>
                        @if ($recentBookings->isEmpty())
                            <p class="text-sm text-gray-500 dark:text-gray-400">No bookings linked to this account yet.</p>
                        @else
                            <ul class="divide-y divide-gray-100 dark:divide-white/5">
                                @foreach ($recentBookings as $booking)
                                    <li class="flex items-center justify-between gap-4 py-3 text-sm">
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-gray-900 dark:text-white">{{ $booking->client_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->scheduled_at?->format('M j, Y H:i') ?? $booking->created_at->format('M j, Y') }}
                                            </p>
                                        </div>
                                        <span class="shrink-0 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium capitalize text-gray-700 dark:bg-white/10 dark:text-gray-300">
                                            {{ $booking->status }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Delete Account -->
                    <div class="rounded-3xl border border-red-100 bg-red-50 p-4 dark:border-red-900/30 dark:bg-red-950/10 sm:p-6 md:p-8">
                        <h3 class="mb-2 text-lg font-semibold text-red-700 dark:text-red-400">Delete Account</h3>
                        <p class="mb-6 max-w-lg text-sm text-red-600/80 dark:text-red-400/70">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm you would like to permanently delete your account.
                        </p>
                        <button
                            type="button"
                            @click="showDeleteModal = true"
                            class="rounded-full bg-red-600 px-5 py-2.5 text-sm font-medium text-white transition-all hover:bg-red-700 active:scale-[0.98]"
                        >
                            Delete Account
                        </button>
                    </div>
                </div>

                <!-- Appearance Tab -->
                <div
                    x-show="tab === 'appearance'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8"
                    style="display: none"
                >
                    <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Appearance Settings</h3>
                        <form action="{{ route('admin.profile.appearance.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Theme Preference</label>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    @foreach (['light' => 'Light', 'dark' => 'Dark', 'auto' => 'Auto'] as $value => $label)
                                        <label class="relative cursor-pointer">
                                            <input
                                                type="radio"
                                                name="theme"
                                                value="{{ $value }}"
                                                {{ $user->theme?->value === $value || ($value === 'auto' && !$user->theme?->value) ? 'checked' : '' }}
                                                class="peer sr-only"
                                            />
                                            <div class="rounded-xl border-2 border-gray-100 p-4 transition-all peer-checked:border-gray-900 peer-checked:bg-gray-50 dark:border-white/5 dark:peer-checked:border-white dark:peer-checked:bg-white/5">
                                                <div class="text-center text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $label }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Save Appearance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Tab -->
                <div
                    x-show="tab === 'security'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8"
                    style="display: none"
                >
                    <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Update Password</h3>
                        <form
                            action="{{ route('admin.profile.password.update') }}"
                            method="POST"
                            class="space-y-6"
                            x-data="{
                                generatePassword() {
                                    const length = 16;
                                    const charset =
                                        'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
                                    let retVal = '';
                                    for (let i = 0; i < length; ++i) {
                                        retVal += charset.charAt(Math.floor(Math.random() * charset.length));
                                    }
                                    $refs.passwordInput.value = retVal;
                                    $refs.passwordConfirmInput.value = retVal;
                                },
                                showPassword: false,
                                showConfirmPassword: false,
                                minLen: 8,
                                init() {
                                    const passInput = $refs.passwordInput;
                                    if (passInput && passInput.dataset.rules) {
                                        const minMatch = passInput.dataset.rules.match(/min:(\d+)/);
                                        if (minMatch) this.minLen = parseInt(minMatch[1]);
                                    }
                                },
                            }"
                        >
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                                    <input
                                        type="password"
                                        name="current_password"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    />
                                </div>
                                <div></div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label for="password" class="text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                                        <button
                                            type="button"
                                            @click="generatePassword()"
                                            class="text-xs text-blue-600 hover:underline dark:text-blue-400"
                                        >
                                            Generate Secure
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            id="password"
                                            name="password"
                                            x-ref="passwordInput"
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-14 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                            data-rules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                                        />
                                        <button
                                            type="button"
                                            @click="showPassword = ! showPassword"
                                            aria-controls="password"
                                            :aria-expanded="showPassword ? 'true' : 'false'"
                                            :aria-label="showPassword ? '{{ __('auth.login.hide_password') }}' : '{{ __('auth.login.show_password') }}'"
                                            class="absolute inset-y-0 right-0 flex items-center pr-5 text-[#A1A1AA] transition-colors hover:text-[#71717A] focus:text-[#71717A] focus:outline-none"
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
                                    <p class="text-xs text-gray-500" x-text="`Min ${minLen} characters`"></p>
                                </div>
                                <div class="space-y-2">
                                    <label for="password_confirmation" class="text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                    <div class="relative">
                                        <input
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            x-ref="passwordConfirmInput"
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-14 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                            data-rules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                                        />
                                        <button
                                            type="button"
                                            @click="showConfirmPassword = ! showConfirmPassword"
                                            aria-controls="password_confirmation"
                                            :aria-expanded="showConfirmPassword ? 'true' : 'false'"
                                            :aria-label="showConfirmPassword ? '{{ __('auth.login.hide_password') }}' : '{{ __('auth.login.show_password') }}'"
                                            class="absolute inset-y-0 right-0 flex items-center pr-5 text-[#A1A1AA] transition-colors hover:text-[#71717A] focus:text-[#71717A] focus:outline-none"
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
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                >
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TFA Component -->
                    <div class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                            Two-Factor Authentication
                        </h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                            Secure your account with an extra layer of protection.
                        </p>

                        @if (! $user->two_factor_secret)
                            {{-- Step 1: Enable (password confirmed through the modal) --}}
                            <form action="{{ route('admin.profile.two-factor.enable') }}" method="POST" x-ref="enable2faForm" class="hidden">
                                @csrf
                            </form>
                            <button
                                type="button"
                                @click="showEnableModal = true; $nextTick(() => $refs.enable2faPassword.focus())"
                                class="rounded-full bg-gray-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                            >
                                Enable Two-Factor
                            </button>
                        @elseif ($user->two_factor_secret && ! $user->two_factor_confirmed_at)
                            {{-- Step 2: Setup (Unconfirmed) --}}
                            <div class="space-y-6">
                                <div class="inline-block max-w-full rounded-xl border border-gray-100 bg-gray-50 p-4 [&>svg]:mx-auto [&>svg]:block [&>svg]:h-auto [&>svg]:max-w-[220px] dark:border-white/5 dark:bg-white/5">
                                    {!! $qrCodeSvg !!}
                                </div>

                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Scan this QR code with your authenticator app.
                                </p>

                                <form
                                    action="{{ route('admin.profile.two-factor.confirm') }}"
                                    method="POST"
                                    class="space-y-4"
                                >
                                    @csrf
                                    <input
                                        type="text"
                                        name="code"
                                        required
                                        inputmode="numeric"
                                        autocomplete="one-time-code"
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm dark:border-white/10 dark:bg-white/5"
                                        placeholder="Enter authentication code"
                                    />
                                    @error('code')
                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <div class="flex flex-wrap items-center gap-4">
                                        <button
                                            type="submit"
                                            class="rounded-full bg-gray-900 px-6 py-2 text-sm font-medium text-white dark:bg-white dark:text-gray-900"
                                        >
                                            Confirm
                                        </button>
                                    </div>
                                </form>
                                <form action="{{ route('admin.profile.two-factor.cancel') }}" method="POST" onsubmit="return confirm('Cancel two-factor setup? The QR code will be discarded.')">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="text-sm text-gray-500 underline-offset-4 hover:text-gray-700 hover:underline dark:text-gray-400 dark:hover:text-gray-200"
                                    >
                                        Cancel setup
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- Step 3: Confirmed --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 font-medium text-emerald-600 dark:text-emerald-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    <span>Two-factor authentication is enabled.</span>
                                </div>

                                <div class="flex flex-wrap gap-4 pt-2">
                                    <button
                                        type="button"
                                        @click="showCodes()"
                                        :disabled="loadingCodes"
                                        class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                                    >
                                        <span x-show="! loadingCodes">Show Recovery Codes</span>
                                        <span x-show="loadingCodes">Loading...</span>
                                    </button>

                                    <button
                                        type="button"
                                        @click="regenerateCodes()"
                                        :disabled="loadingCodes"
                                        class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                                    >
                                        Regenerate Recovery Codes
                                    </button>

                                    <form action="{{ route('admin.profile.two-factor.disable') }}" method="POST">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="rounded-full bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition-all hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400"
                                        >
                                            Disable TFA
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Passkeys Tab -->
                <div
                    x-show="tab === 'passkeys'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6"
                    style="display: none"
                >
                    @livewire('passkeys')
                </div>

                <!-- Sessions Tab -->
                <div
                    x-show="tab === 'sessions'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6"
                    style="display: none"
                >
                    @include('admin.profile.sessions-tab')
                </div>
            </div>

            <!-- Enable Two-Factor Password Modal -->
            <div
                x-show="showEnableModal"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-sm dark:bg-black/60"
                style="display: none"
                @keydown.escape.window="showEnableModal = false"
            >
                <div
                    x-show="showEnableModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="max-h-[90dvh] w-full max-w-md overflow-y-auto rounded-3xl border border-gray-200 bg-white p-4 shadow-2xl sm:p-8 dark:border-white/10 dark:bg-[#0A0A0F]"
                >
                    <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Confirm Password</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        For your security, enter your password to enable two-factor authentication for this account.
                    </p>

                    <form @submit.prevent="submitEnablePassword()" class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input
                                type="password"
                                x-ref="enable2faPassword"
                                name="password"
                                autocomplete="current-password"
                                :disabled="verifyingPassword"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                            <p x-show="enablePasswordError" x-text="enablePasswordError" class="text-xs text-red-600 dark:text-red-400"></p>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button
                                type="button"
                                @click="showEnableModal = false"
                                :disabled="verifyingPassword"
                                class="flex-1 rounded-full bg-gray-100 px-4 py-2.5 font-medium text-gray-700 transition-all hover:bg-gray-200 disabled:opacity-50 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="verifyingPassword"
                                class="flex-1 rounded-full bg-gray-900 px-4 py-2.5 font-medium text-white transition-all hover:bg-gray-800 disabled:opacity-50 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                            >
                                <span x-show="! verifyingPassword">Verify</span>
                                <span x-show="verifyingPassword">Verifying...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account Modal -->
            <div
                x-show="showDeleteModal"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-sm dark:bg-black/60"
                style="display: none"
            >
                <div
                    x-show="showDeleteModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="max-h-[90dvh] w-full max-w-md overflow-y-auto rounded-3xl border border-gray-200 bg-white p-4 shadow-2xl sm:p-8 dark:border-white/10 dark:bg-[#0A0A0F]"
                >
                    <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Delete Account</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete your account? All of your data will be permanently removed. This
                        action cannot be undone.
                    </p>

                    <form action="{{ route('admin.profile.destroy') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button
                                type="button"
                                @click="showDeleteModal = false"
                                class="flex-1 rounded-full bg-gray-100 px-4 py-2.5 font-medium text-gray-700 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="flex-1 rounded-full bg-red-600 px-4 py-2.5 font-medium text-white transition-all hover:bg-red-700"
                            >
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recovery Codes Modal -->
            <div
                x-show="showRecoveryCodesModal"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-sm dark:bg-black/60"
                style="display: none"
                @keydown.escape.window="showRecoveryCodesModal = false"
            >
                <div
                    x-show="showRecoveryCodesModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="max-h-[90dvh] w-full max-w-md overflow-y-auto rounded-3xl border border-gray-200 bg-white p-4 shadow-2xl sm:p-8 dark:border-white/10 dark:bg-[#0A0A0F]"
                >
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Recovery Codes</h3>
                        <button
                            @click="showRecoveryCodesModal = false"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        Store these recovery codes in a secure password manager. They can be used to recover access to
                        your account if your two-factor authentication device is lost.
                    </p>

                    <div class="mb-6 grid grid-cols-1 gap-2 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                        <template x-for="code in recoveryCodes" :key="code">
                            <div
                                class="rounded-lg border border-gray-100 bg-white py-2 text-center font-mono text-sm text-gray-900 dark:border-white/5 dark:bg-[#16161D] dark:text-gray-100"
                                x-text="code"
                            ></div>
                        </template>
                    </div>

                    <div class="flex gap-4">
                        <button
                            type="button"
                            @click="
                                const text = recoveryCodes.join('\n');
                                navigator.clipboard.writeText(text).then(() => alert('Copied!'));
                            "
                            class="flex-1 rounded-full bg-gray-100 px-4 py-2.5 font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                        >
                            Copy
                        </button>
                        <button
                            type="button"
                            @click="showRecoveryCodesModal = false"
                            class="flex-1 rounded-full bg-gray-900 px-4 py-2.5 font-medium text-white transition-all hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
