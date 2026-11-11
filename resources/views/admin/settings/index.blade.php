<x-layouts::admin title="Company Settings">
    @php
        // The repeaters below are Alpine state rather than Blade markup, so each one is seeded
        // as a plain array here. Rejected input wins over the stored row: a save that failed
        // validation has to redisplay what the person just typed, not what is still in the
        // database behind it.
        //
        // Only the fields a person edits go into a slide. The derived widths, the dimensions and
        // the placeholder colour are the server's to recompute from the photograph on every save,
        // so echoing them into the form would only invite them to be posted back.
        $heroSlidesState = collect(old('home_hero_slides', $settings['home_hero_slides'] ?? []))
            ->filter('is_array')
            ->map(fn (array $slide, int $index): array => [
                'key' => 'hero-slide-'.$index,
                'word' => (string) ($slide['word'] ?? ''),
                'headline' => (string) ($slide['headline'] ?? ''),
                'tagline' => (string) ($slide['tagline'] ?? ''),
                'alt' => (string) ($slide['alt'] ?? ''),
                'background' => (string) ($slide['background'] ?? ''),
                'backgroundUrl' => filled($slide['background'] ?? null) ? media_url($slide['background']) : '',
                'cutout' => (string) ($slide['cutout'] ?? ''),
                'cutoutUrl' => filled($slide['cutout'] ?? null) ? media_url($slide['cutout']) : '',
                'buttonLabel' => (string) ($slide['button_label'] ?? ''),
                'buttonUrl' => (string) ($slide['button_url'] ?? ''),
                'cutoutError' => (string) $errors->first("home_hero_slides.{$index}.cutout"),
            ])
            ->values()
            ->all();

        $heroStatsState = collect(old('home_hero_stats', $settings['home_hero_stats'] ?? []))
            ->filter('is_array')
            ->map(fn (array $stat, int $index): array => [
                'key' => 'hero-stat-'.$index,
                'label' => (string) ($stat['label'] ?? ''),
                'value' => (string) ($stat['value'] ?? ''),
                'suffix' => (string) ($stat['suffix'] ?? ''),
            ])
            ->values()
            ->all();

        // The groups of the sheet, in the order the page reads them. Declared once here rather
        // than inside the markup so the rail, the field count in the masthead and the hidden
        // `tab` value the controller reads back all come from the same list.
        $tabs = [
            'general' => 'General',
            'hours' => 'Hours',
            'assets' => 'Branding',
            'homepage' => 'Homepage',
            'localization' => 'Locale',
            'social' => 'Social',
            'notifications' => 'Notifications',
            'announcements' => 'Announcements',
        ] + ($envConfig !== [] ? ['system_config' => 'System Config'] : []);

        // Which group the server is about to paint. Every pane carries an inline `display: none`
        // except this one, because Alpine's `x-show` only exists once Alpine boots — and a sheet
        // that arrives blank and then reveals itself is where a 0.58 cumulative layout shift comes
        // from. Deciding it here means the first frame is the finished frame, with or without JS.
        // An unknown group falls back to the first one rather than painting nothing at all.
        $requestedTab = (string) request()->query('tab', 'general');
        $activeTab = array_key_exists($requestedTab, $tabs) ? $requestedTab : 'general';

        // When the shop last set any of this. A real column on a real row, not a timer: the
        // bench says what is on the table, so it also says when the table was last cleared.
        $lastSetAt = \App\Models\CompanySetting::query()->max('updated_at');
        $lastSet = filled($lastSetAt) ? \Carbon\Carbon::parse($lastSetAt) : null;
    @endphp
    <div
        class="hb-sheet mx-auto max-w-[82rem] space-y-5 py-6 sm:py-8"
        x-data="{
        tab: '{{ $activeTab }}',
        whatsappNumbers: {{ \Illuminate\Support\Js::from($settings['whatsapp_additional_numbers'] ?? []) }},
        workingHours: {{ \Illuminate\Support\Js::from($settings['working_hours'] ?? []) }},
        announcements: {{ \Illuminate\Support\Js::from($settings['announcements'] ?? []) }},
        factTiles: {{ \Illuminate\Support\Js::from($settings['home_fact_tiles'] ?? []) }},
        warrantyHighlights: {{ \Illuminate\Support\Js::from($settings['home_warranty_highlights'] ?? []) }},
        processSteps: {{ \Illuminate\Support\Js::from($settings['home_process_steps'] ?? []) }},
        faqs: {{ \Illuminate\Support\Js::from($settings['home_faqs'] ?? []) }},
        heroSlides: {{ \Illuminate\Support\Js::from($heroSlidesState) }},
        heroStats: {{ \Illuminate\Support\Js::from($heroStatsState) }},
        heroStyle: {{ \Illuminate\Support\Js::from(old('home_hero_style', $settings['home_hero_style'] ?? 'static')) }},
        heroDrag: null,
        heroPreview: '{{ $settings['hero_image'] ? media_url($settings['hero_image']) : null }}',
        init() {
            // The group you are in belongs in the browser tab, not only in the page: this is one
            // URL with nine groups, and a stack of them in the history is otherwise
            // indistinguishable. Written on load as well as on change, because a watcher only
            // fires when something moves and the deep link already knows where you are.
            const groupLabels = {{ \Illuminate\Support\Js::from($tabs) }};
            const nameTab = (value) => {
                document.title = (groupLabels[value] || 'General') + ' - Company Settings - {{ config('app.name', 'Laravel') }}';
            };

            nameTab(this.tab);

            this.$watch('tab', value => {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', value);
                window.history.replaceState({}, '', url.toString());
                nameTab(value);
            });
        },
        addNumber() { this.whatsappNumbers.push({ label: '', number: '' }); },
        removeNumber(index) { this.whatsappNumbers.splice(index, 1); },
        addAnnouncement() { this.announcements.push({ text: '', link: '', expires_at: '' }); },
        removeAnnouncement(index) { this.announcements.splice(index, 1); },
        addFactTile() { this.factTiles.push({ label: '', value: '', suffix: '' }); },
        removeFactTile(index) { this.factTiles.splice(index, 1); },
        addHighlight() { this.warrantyHighlights.push({ title: '', text: '' }); },
        removeHighlight(index) { this.warrantyHighlights.splice(index, 1); },
        addStep() { this.processSteps.push({ title: '', text: '' }); },
        removeStep(index) { this.processSteps.splice(index, 1); },
        addFaq() { this.faqs.push({ question: '', answer: '' }); },
        removeFaq(index) { this.faqs.splice(index, 1); },
        addHeroSlide() {
            if (this.heroSlides.length >= 6) return;
            this.heroSlides.push({
                key: `hero-slide-${Date.now()}`, word: '', headline: '', tagline: '', alt: '',
                background: '', backgroundUrl: '', cutout: '', cutoutUrl: '',
                buttonLabel: '', buttonUrl: '', cutoutError: '',
            });
        },
        removeHeroSlide(index) { this.heroSlides.splice(index, 1); },
        // The order a slide plays in is its position in the array, so moving it is the whole of
        // the sort control — there is no number to type and nothing to get out of step.
        moveHeroSlide(index, delta) {
            const target = index + delta;
            if (target < 0 || target >= this.heroSlides.length) return;
            const [row] = this.heroSlides.splice(index, 1);
            this.heroSlides.splice(target, 0, row);
        },
        dropHeroSlide(target) {
            if (this.heroDrag === null || this.heroDrag === target) return;
            const [row] = this.heroSlides.splice(this.heroDrag, 1);
            this.heroSlides.splice(target, 0, row);
            this.heroDrag = null;
        },
        pickHeroImage(index, slot) {
            this.$dispatch('open-media-picker', { field: `slide-${index}-${slot}`, multiple: false });
        },
        onHeroImage(detail) {
            const picked = /^slide-(\d+)-(background|cutout)$/.exec(detail.field ?? '');
            if (!picked) return;
            const row = this.heroSlides[Number(picked[1])];
            if (!row) return;
            const slot = picked[2];
            row[slot] = detail.path ?? '';
            row[`${slot}Url`] = detail.url ?? '';
            // A fresh cutout is a different file, so the shape complaint that was about the old
            // one no longer belongs on the screen. The server decides again on save.
            if (slot === 'cutout') row.cutoutError = '';
        },
        addHeroStat() {
            if (this.heroStats.length >= 3) return;
            this.heroStats.push({ key: `hero-stat-${Date.now()}`, label: '', value: '', suffix: '' });
        },
        removeHeroStat(index) { this.heroStats.splice(index, 1); }
    }"
    >
        <header class="hb-sheet-head">
            <div>
                <h1 class="hb-sheet-title">Company Settings</h1>
                <p class="hb-sheet-lede">
                    What the shop is called, when it opens, how long a job holds a bay, and what the
                    public homepage says about all of it. A value here is only an intention until the
                    sheet is set, and one press sets the whole of it.
                </p>
            </div>

            <dl class="hb-sheet-facts">
                <div class="hb-sheet-fact">
                    <dt>Last set</dt>
                    <dd>
                        <time
                            class="hb-num"
                            datetime="{{ $lastSet?->toIso8601String() }}"
                            data-at="{{ $lastSet?->toIso8601String() }}"
                            data-sheet-elapsed
                        >{{ $lastSet?->format('j M Y') ?? 'not recorded' }}</time>
                    </dd>
                </div>
                <div class="hb-sheet-fact">
                    <dt>Groups</dt>
                    <dd class="hb-num">{{ count($tabs) }}</dd>
                </div>
                <div class="hb-sheet-fact">
                    <dt>Fields</dt>
                    <dd class="hb-num" data-sheet-fields></dd>
                </div>
                <div class="hb-sheet-fact">
                    <dt>Marks</dt>
                    <dd class="hb-num" data-sheet-total>0</dd>
                </div>
            </dl>
        </header>

        <form
            action="{{ route('admin.settings.update') }}"
            method="POST"
            enctype="multipart/form-data"
            data-sheet-form
        >
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" :value="tab" />

            <div class="hb-sheet-body">
                <nav class="hb-index" aria-label="Settings groups">
                    @foreach ($tabs as $key => $label)
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
                {{-- General Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="general"
                    x-show="tab === 'general'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'general') style="display: none" @endif
                >
                    <x-sheet.block title="General Information" note="These are the words the shop is known by, and the numbers a customer dials.">
                        <x-sheet.input
                            label="Business Name"
                            name="company_name"
                            key="company_name"
                            :value="old('company_name', $settings['company_name'])"
                        />
                        <x-sheet.input
                            label="Logo Text"
                            name="logo_text"
                            key="logo_text"
                            hint="Shown beside the mark in the header when there is no image, and in the tab title."
                            :value="old('logo_text', $settings['logo_text'])"
                        />
                        <x-sheet.input
                            label="Primary Email"
                            name="primary_email"
                            key="primary_email"
                            type="email"
                            :value="old('primary_email', $settings['primary_email'])"
                        />
                        <x-sheet.input
                            label="Primary Phone"
                            name="primary_phone"
                            key="primary_phone"
                            :value="old('primary_phone', $settings['primary_phone'])"
                        />
                        <x-sheet.input
                            label="Secondary Phone"
                            name="secondary_phone"
                            key="secondary_phone"
                            hint="Optional second line. Shown on the contact page and in the footer when filled in."
                            placeholder="+267 ..."
                            :value="old('secondary_phone', $settings['secondary_phone'])"
                        />
                        <x-sheet.textarea
                            label="Business Address"
                            name="address"
                            key="address"
                            :value="old('address', $settings['address'])"
                        />
                        <x-sheet.input
                            label="Google Maps API Key"
                            name="google_maps_api_key"
                            key="google_maps_api_key"
                            placeholder="AIzaSy..."
                            hint="This key is sent to every visitor's browser, so restrict it in Google Cloud to your site domains and the Maps JavaScript API only. Store server-only keys in .env instead."
                            :value="old('google_maps_api_key', $settings['google_maps_api_key'])"
                        />
                        <x-sheet.input
                            label="Map Directions Link"
                            name="map_directions_link"
                            key="map_directions_link"
                            type="url"
                            placeholder="https://maps.app.goo.gl/..."
                            :value="old('map_directions_link', $settings['map_directions_link'])"
                        />
                    </x-sheet.block>
                    <x-sheet.block title="Account Security" note="Three switches that decide who gets an account, and for how long a session stays open.">
                        <x-sheet.field
                            label="Require verified email addresses"
                            for="require_email_verification"
                            key="require_email_verification"
                            hint="When on, users whose email is not verified are sent to the verification page before they can access the admin panel or settings. Registration emails a verification link."
                        >
                            <label class="hb-switch">
                                <input type="hidden" name="require_email_verification" value="0" />
                                <input
                                    type="checkbox"
                                    id="require_email_verification"
                                    name="require_email_verification"
                                    value="1"
                                    {{ $settings['require_email_verification'] ? 'checked' : '' }}
                                />
                                <span class="hb-switch__track" aria-hidden="true"></span>
                            </label>
                        </x-sheet.field>

                        <x-sheet.field
                            label="Enable registration"
                            for="enable_registration"
                            key="enable_registration"
                            hint="Allow new users to create an account from the site. When off, the register page and sign-up links are hidden and direct visits to /register return Not Found."
                        >
                            <label class="hb-switch">
                                <input type="hidden" name="enable_registration" value="0" />
                                <input
                                    type="checkbox"
                                    name="enable_registration"
                                    value="1"
                                    {{ $settings['enable_registration'] ? 'checked' : '' }}
                                    id="enable_registration"
                                />
                                <span class="hb-switch__track" aria-hidden="true"></span>
                            </label>
                        </x-sheet.field>

                        <x-sheet.field
                            label="Session idle timeout (minutes)"
                            for="session_idle_timeout_minutes"
                            key="session_idle_timeout_minutes"
                        >
                            <x-slot:meta>
                                <p class="hb-field__hint">
                                    Close a signed-in session after this many minutes without activity, even while it is still
                                    inside the {{ config('session.lifetime') }}-minute lifetime. Set to 0 to leave the lifetime in
                                    charge. The hourly prune applies it, so an idle session can outlive this by up to an hour.
                                </p>
                            </x-slot:meta>
                            <input
                                type="number"
                                id="session_idle_timeout_minutes"
                                name="session_idle_timeout_minutes"
                                min="0"
                                max="4320"
                                step="1"
                                value="{{ old('session_idle_timeout_minutes', $settings['session_idle_timeout_minutes']) }}"
                                class="hb-input hb-input--num"
                            />
                            @error('session_idle_timeout_minutes')
                                <p class="hb-error">{{ $message }}</p>
                            @enderror
                        </x-sheet.field>

                        <x-sheet.field
                            label="Default admin layout"
                            for="admin_nav_layout"
                            key="admin_nav_layout"
                        >
                            <x-slot:meta>
                                <p class="hb-field__hint">
                                    Which navigation an account gets before anyone chooses for itself. Anyone who has
                                    saved a layout on their own profile keeps that choice, and phones and tablets use
                                    the slide-out menu either way — this only decides the wide screen.
                                </p>
                            </x-slot:meta>
                            <select
                                id="admin_nav_layout"
                                name="admin_nav_layout"
                                class="hb-select"
                                style="max-width: 16rem"
                            >
                                @foreach (\App\Enums\AdminNavLayout::options() as $layout)
                                    <option
                                        value="{{ $layout->value }}"
                                        {{ old('admin_nav_layout', $settings['admin_nav_layout']) === $layout->value ? 'selected' : '' }}
                                    >{{ $layout->label() }}</option>
                                @endforeach
                            </select>
                            @error('admin_nav_layout')
                                <p class="hb-error">{{ $message }}</p>
                            @enderror
                        </x-sheet.field>
                    </x-sheet.block>
                    @if (config('ai.enabled'))
                        <x-sheet.block title="AI Writing" note="The voice AI copy suggestions start with. Writers can still pick another voice on each suggestion panel.">
                            <x-sheet.field
                                label="Default suggestion voice"
                                for="ai_tone"
                                key="ai_tone"
                                hint="Applies to excerpt, SEO meta, block and About Us suggestions. The text itself is always reviewed by a person before it is saved."
                            >
                                <select
                                    id="ai_tone"
                                    name="ai_tone"
                                    class="hb-select"
                                    style="max-width: 16rem"
                                >
                                    @foreach ((array) config('ai.tones', []) as $toneKey => $tone)
                                        <option
                                            value="{{ $toneKey }}"
                                            {{ old('ai_tone', $settings['ai_tone']) === $toneKey ? 'selected' : '' }}
                                        >{{ $tone['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('ai_tone')
                                    <p class="hb-error">{{ $message }}</p>
                                @enderror
                            </x-sheet.field>
                        </x-sheet.block>
                    @endif
                </div>

                {{-- Business Hours Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="hours"
                    x-show="tab === 'hours'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'hours') style="display: none" @endif
                >
                    @php
                        $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
                    @endphp

                    <x-sheet.block title="Weekly Business Hours" note="The week the booking calendar is drawn from. A day with no hours has nothing to offer.">
                        @foreach ($days as $key => $dayLabel)
                            <x-sheet.field :label="$dayLabel" :key="'working_hours['.$key.']'">
                                <div class="hb-cluster hb-cluster--hours">
                                    <x-ui.checkbox
                                        name="working_hours[{{ $key }}][is_closed]"
                                        value="1"
                                        :checked="($settings['working_hours'][$key]['is_closed'] ?? false)"
                                        label="Closed"
                                    />
                                    <span class="hb-cluster__pair">
                                        <span class="hb-cluster__unit">from</span>
                                        <input
                                            type="time"
                                            name="working_hours[{{ $key }}][open]"
                                            value="{{ $settings['working_hours'][$key]['open'] ?? '' }}"
                                            aria-label="{{ $dayLabel }} opens"
                                            class="hb-input hb-input--num"
                                        />
                                        <span class="hb-cluster__unit">to</span>
                                        <input
                                            type="time"
                                            name="working_hours[{{ $key }}][close]"
                                            value="{{ $settings['working_hours'][$key]['close'] ?? '' }}"
                                            aria-label="{{ $dayLabel }} closes"
                                            class="hb-input hb-input--num"
                                        />
                                    </span>
                                </div>
                            </x-sheet.field>
                        @endforeach
                    </x-sheet.block>

                    <x-sheet.block title="Booking Rules" note="Customers are only offered slots on days that are open above. A day marked Closed shows no slots at all.">
                        <x-sheet.input
                            label="Minimum Booking Notice (hours)"
                            name="booking_lead_time_hours"
                            key="booking_lead_time_hours"
                            hint="How far ahead of the current time a customer must book. Set to 0 to allow same-day booking up to closing."
                            type="number"
                            min="0"
                            max="24"
                            step="1"
                            measure="num"
                            :value="old('booking_lead_time_hours', $settings['booking_lead_time_hours'])"
                        />
                        <x-sheet.input
                            label="Slot Interval (minutes)"
                            name="booking_slot_interval_minutes"
                            key="booking_slot_interval_minutes"
                            hint="Gap between the start of one offered time and the next. A job must still finish by closing time."
                            type="number"
                            min="15"
                            max="240"
                            step="15"
                            measure="num"
                            :value="old('booking_slot_interval_minutes', $settings['booking_slot_interval_minutes'])"
                        />
                        <x-sheet.input
                            label="Workshop Bays"
                            name="booking_capacity_workshop"
                            key="booking_capacity_workshop"
                            hint="How many workshop jobs can overlap at once. Each staff absence you record removes one from this."
                            type="number"
                            min="0"
                            max="20"
                            step="1"
                            measure="num"
                            :value="old('booking_capacity_workshop', $settings['booking_capacity_workshop'])"
                        />
                        <x-sheet.input
                            label="Mobile Units"
                            name="booking_capacity_mobile"
                            key="booking_capacity_mobile"
                            hint="How many mobile fittings can run at the same time. Mobile and workshop pools are counted separately."
                            type="number"
                            min="0"
                            max="20"
                            step="1"
                            measure="num"
                            :value="old('booking_capacity_mobile', $settings['booking_capacity_mobile'])"
                        />
                    </x-sheet.block>

                    <x-sheet.block title="Job Duration (minutes)" note="How long each service holds a bay or a van. This is what decides the last slot of the day.">
                        @foreach (app(App\Services\Settings\SettingsManager::class)->getDefaults()['booking_service_durations'] ?? [] as $serviceKey => $defaultDuration)
                            <x-sheet.input
                                :label="\Illuminate\Support\Str::headline((string) $serviceKey)"
                                :id="'booking_service_durations_'.$serviceKey"
                                :name="'booking_service_durations['.$serviceKey.']'"
                                :key="'booking_service_durations['.$serviceKey.']'"
                                type="number"
                                min="15"
                                max="480"
                                step="15"
                                measure="num"
                                :error="'booking_service_durations.'.$serviceKey"
                                :value="old('booking_service_durations.'.$serviceKey, $settings['booking_service_durations'][$serviceKey] ?? $defaultDuration)"
                            />
                        @endforeach
                    </x-sheet.block>

                    <x-sheet.block title="Closed Dates" foot="Public holidays and one-off closures. These override the weekly hours above.">
                        <x-sheet.field label="Where they are kept" hint="Closures are dated records, not a value on this sheet, so they have their own page.">
                            <a
                                href="{{ route('admin.bookings.closures.index') }}"
                                class="hb-btn hb-btn--wide"
                            >Manage closed dates</a>
                        </x-sheet.field>
                    </x-sheet.block>
                </div>

                {{-- Branding Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="assets"
                    x-show="tab === 'assets'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'assets') style="display: none" @endif
                    x-data="{
                        logoPreview: '{{ $settings['business_logo'] ? media_url($settings['business_logo']) : null }}',
                        faviconPreview: '{{ $settings['favicon'] ? media_url($settings['favicon']) : (file_exists(public_path('favicon.ico')) ? '/favicon.ico' : null) }}',
                        handleFileSelect(event, previewKey) {
                            const file = event.target.files[0];
                            if (file) this[previewKey] = URL.createObjectURL(file);
                        }
                     }"
                >
                    <x-sheet.block title="Branding" note="Two files the whole site is painted with. What is on the table is what every page is using right now.">
                        <x-sheet.field
                            label="Business Logo"
                            key="business_logo"
                            stack="true"
                            hint="Used in the header, the sidebar, the invoice block and the structured data. A square file reads best at the small sizes."
                        >
                            <div x-data="{ removeLogo: false }" class="hb-asset">
                                <div class="hb-asset__tile">
                                    <div class="hb-asset__frame" :data-pending="removeLogo ? 'remove' : null">
                                        <template x-if="logoPreview && ! removeLogo">
                                            <img :src="logoPreview" alt="The logo the site is using now" class="max-h-full" />
                                        </template>
                                        <template x-if="! logoPreview || removeLogo">
                                            <span class="hb-asset__word">No logo set</span>
                                        </template>
                                    </div>

                                    <input type="hidden" name="remove_business_logo" :value="removeLogo ? 1 : 0" />

                                    <input
                                        type="file"
                                        name="business_logo"
                                        x-ref="logoInput"
                                        class="hidden"
                                        accept="image/*"
                                        @change="
                                            handleFileSelect($event, 'logoPreview');
                                            removeLogo = false;
                                        "
                                    />

                                    <div class="hb-repeat-bar">
                                        <button
                                            type="button"
                                            class="hb-btn"
                                            @click="if (! removeLogo) $refs.logoInput.click();"
                                        >
                                            <span x-show="logoPreview && ! removeLogo">Replace logo</span>
                                            <span x-cloak x-show="! logoPreview || removeLogo">Choose a logo</span>
                                        </button>
                                        <button
                                            type="button"
                                            class="hb-btn"
                                            @click="
                                                removeLogo = true;
                                                logoPreview = null;
                                            "
                                            x-show="logoPreview && ! removeLogo"
                                        >
                                            Remove logo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </x-sheet.field>

                        <x-sheet.field
                            label="Favicon"
                            key="favicon"
                            stack="true"
                            hint="The tab icon, and the image a phone puts on a home screen. Saving here regenerates the whole icon set."
                        >
                            <div x-data="{ removeFavicon: false }" class="hb-asset">
                                <div class="hb-asset__tile">
                                    <div class="hb-asset__frame" :data-pending="removeFavicon ? 'remove' : null">
                                        <template x-if="faviconPreview && ! removeFavicon">
                                            <img :src="faviconPreview" alt="The favicon the site is using now" class="max-h-full" />
                                        </template>
                                        <template x-if="! faviconPreview || removeFavicon">
                                            <span class="hb-asset__word">No favicon set</span>
                                        </template>
                                    </div>

                                    <input type="hidden" name="remove_favicon" :value="removeFavicon ? 1 : 0" />

                                    <input
                                        type="file"
                                        name="favicon"
                                        x-ref="faviconInput"
                                        class="hidden"
                                        accept="image/*"
                                        @change="
                                            handleFileSelect($event, 'faviconPreview');
                                            removeFavicon = false;
                                        "
                                    />

                                    <div class="hb-repeat-bar">
                                        <button
                                            type="button"
                                            class="hb-btn"
                                            @click="if (! removeFavicon) $refs.faviconInput.click();"
                                        >
                                            <span x-show="faviconPreview && ! removeFavicon">Replace favicon</span>
                                            <span x-cloak x-show="! faviconPreview || removeFavicon">Choose a favicon</span>
                                        </button>
                                        <button
                                            type="button"
                                            class="hb-btn"
                                            @click="
                                                removeFavicon = true;
                                                faviconPreview = null;
                                            "
                                            x-show="faviconPreview && ! removeFavicon"
                                        >
                                            Remove favicon
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>
                </div>

                {{-- Locale Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="localization"
                    x-show="tab === 'localization'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'localization') style="display: none" @endif
                >
                    <x-sheet.block title="Locale Settings" note="How a date, a time and a price are written everywhere the shop speaks, including the emails.">
                        <x-sheet.input
                            label="Timezone"
                            name="timezone"
                            key="timezone"
                            hint="A name from the tz database, like Africa/Gaborone. This is what turns a stored UTC moment into the hour a customer sees."
                            mono="true"
                            :value="old('timezone', $settings['timezone'])"
                        />
                        <x-sheet.input
                            label="Currency Symbol"
                            name="currency_symbol"
                            key="currency_symbol"
                            mono="true"
                            :value="old('currency_symbol', $settings['currency_symbol'])"
                        />
                        <x-sheet.select
                            label="Date Format"
                            name="date_format"
                            key="date_format"
                            :value="old('date_format', $settings['date_format'])"
                            style="max-width: 22rem"
                        >
                            @foreach (['d/m/Y', 'm/d/Y', 'Y-m-d', 'd.m.Y', 'j M Y', 'D, j M Y'] as $format)
                                <option
                                    value="{{ $format }}"
                                    {{ old('date_format', $settings['date_format']) === $format ? 'selected' : '' }}
                                >
                                    {{ date($format) }} ({{ $format }})
                                </option>
                            @endforeach
                        </x-sheet.select>

                        <x-sheet.select
                            label="Time Format"
                            name="time_format"
                            key="time_format"
                            :value="old('time_format', $settings['time_format'])"
                            style="max-width: 22rem"
                        >
                            @foreach (['H:i', 'h:i A', 'H:i:s', 'h:i:s A'] as $format)
                                <option
                                    value="{{ $format }}"
                                    {{ old('time_format', $settings['time_format']) === $format ? 'selected' : '' }}
                                >
                                    {{ date($format) }} ({{ $format }})
                                </option>
                            @endforeach
                        </x-sheet.select>

                        <x-sheet.select
                            label="Time Display"
                            name="time_format_display"
                            key="time_format_display"
                            :value="old('time_format_display', $settings['time_format_display'])"
                            style="max-width: 22rem"
                        >
                            <option
                                value="12"
                                {{ old('time_format_display', $settings['time_format_display']) === '12' ? 'selected' : '' }}
                            >
                                12-hour (AM/PM)
                            </option>
                            <option
                                value="24"
                                {{ old('time_format_display', $settings['time_format_display']) === '24' ? 'selected' : '' }}
                            >
                                24-hour
                            </option>
                        </x-sheet.select>

                        <x-sheet.input
                            label="Locale"
                            name="locale"
                            key="locale"
                            mono="true"
                            placeholder="en_GB"
                            :value="old('locale', $settings['locale'])"
                        />
                    </x-sheet.block>
                </div>

                {{-- Social Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="social"
                    x-show="tab === 'social'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'social') style="display: none" @endif
                >
                    <x-sheet.block title="Social Links" note="An address left empty is a row nobody can click. The footer and the contact page both read these.">
                        <x-sheet.input
                            label="Facebook"
                            name="facebook_url"
                            key="facebook_url"
                            type="url"
                            placeholder="https://facebook.com/..."
                            :value="old('facebook_url', $settings['facebook_url'])"
                        />
                        <x-sheet.input
                            label="Instagram"
                            name="instagram_url"
                            key="instagram_url"
                            type="url"
                            placeholder="https://instagram.com/..."
                            :value="old('instagram_url', $settings['instagram_url'])"
                        />
                        <x-sheet.input
                            label="LinkedIn"
                            name="linkedin_url"
                            key="linkedin_url"
                            type="url"
                            placeholder="https://linkedin.com/company/..."
                            :value="old('linkedin_url', $settings['linkedin_url'])"
                        />
                    </x-sheet.block>

                    <x-sheet.block title="WhatsApp Settings" note="The number a quote request and the floating chat button both open.">
                        <x-sheet.input
                            label="Default WhatsApp Number"
                            name="whatsapp_number_default"
                            key="whatsapp_number_default"
                            hint="In international format. Spaces and dashes are stripped before the link is built."
                            placeholder="+267 ..."
                            mono="true"
                            :value="old('whatsapp_number_default', $settings['whatsapp_number_default'])"
                        />

                        <x-sheet.field
                            label="Additional WhatsApp Numbers"
                            key="whatsapp_additional_numbers"
                            stack="true"
                            hint="Each one is added to the list a customer is offered. Their order is the order they are shown in."
                        >
                            <div class="hb-repeat">
                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(number, index) in whatsappNumbers" :key="index">
                                        <li class="hb-repeat-item">
                                            <x-sheet.field label="Name">
                                                <input
                                                    type="text"
                                                    :name="`whatsapp_additional_numbers[${index}][label]`"
                                                    x-model="number.label"
                                                    aria-label="Additional number name"
                                                    class="hb-input"
                                                    placeholder="e.g. Sales, Support"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Number">
                                                <input
                                                    type="text"
                                                    :name="`whatsapp_additional_numbers[${index}][number]`"
                                                    x-model="number.number"
                                                    aria-label="Additional number"
                                                    class="hb-input hb-input--mono"
                                                    placeholder="+267 ..."
                                                />
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeNumber(index)"
                                                >
                                                    Remove this number
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="whatsappNumbers.length === 0">
                                    No additional numbers. Only the default line is offered.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="addNumber()">
                                        Add another number
                                    </button>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>
                </div>

                {{-- Notifications Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="notifications"
                    x-show="tab === 'notifications'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'notifications') style="display: none" @endif
                >
                    <x-sheet.block title="Notifications" note="Who hears about a quote request the moment it lands.">
                        <x-sheet.textarea
                            label="Quote Notification Emails"
                            name="quote_notification_emails"
                            key="quote_notification_emails"
                            hint="One address per line, or separated by commas. Each one is sent the full request."
                            :value="(string) old('quote_notification_emails', $settings['quote_notification_emails'] ?? '')"
                        />
                    </x-sheet.block>
                </div>

                <!-- Homepage Tab -->
                <div
                    class="hb-pane"
                    data-sheet-pane="homepage"
                    x-show="tab === 'homepage'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'homepage') style="display: none" @endif
                >
                    <x-sheet.block title="Homepage Content" note="Every heading, number and band on the public homepage. A section with no content hides itself.">
                        <x-sheet.input
                            label="Tagline"
                            name="home_tagline"
                            key="home_tagline"
                            hint="Used as the page title and the hero heading."
                            :value="old('home_tagline', $settings['home_tagline'])"
                        />
                        <x-sheet.input
                            label="Years Of Experience"
                            name="home_years_experience"
                            key="home_years_experience"
                            hint="Shown in the hero badge. Leave blank to hide the badge."
                            placeholder="20+"
                            :value="old('home_years_experience', $settings['home_years_experience'])"
                        />

                        <x-sheet.field
                            label="Hero Image"
                            key="hero_image"
                            stack="true"
                            hint="Cropped to 1920×1080 and served as WebP. Without one, the hero shows its dark gradient backdrop."
                        >
                            <div x-data="{ removeHero: false }" class="hb-asset">
                                <div class="hb-asset__tile">
                                    <div class="hb-asset__frame" :data-pending="removeHero ? 'remove' : null">
                                        <template x-if="heroPreview && ! removeHero">
                                            <img :src="heroPreview" alt="The banner the homepage is using now" />
                                        </template>
                                        <template x-if="! heroPreview || removeHero">
                                            <span class="hb-asset__word">No banner set</span>
                                        </template>
                                    </div>

                                    <input type="hidden" name="remove_hero_image" :value="removeHero ? 1 : 0" />

                                    <input
                                        type="file"
                                        name="hero_image"
                                        x-ref="heroInput"
                                        class="hidden"
                                        accept="image/jpeg,image/png,image/webp"
                                        @change="
                                            if ($event.target.files[0]) heroPreview = URL.createObjectURL($event.target.files[0]);
                                            removeHero = false;
                                        "
                                    />

                                    <div class="hb-repeat-bar">
                                        <button
                                            type="button"
                                            class="hb-btn"
                                            @click="if (! removeHero) $refs.heroInput.click();"
                                        >
                                            <span x-show="heroPreview && ! removeHero">Replace banner</span>
                                            <span x-cloak x-show="! heroPreview || removeHero">Click to upload a hero banner</span>
                                        </button>
                                        <button
                                            type="button"
                                            class="hb-btn"
                                            @click="removeHero = true; heroPreview = null;"
                                            x-show="heroPreview && ! removeHero"
                                        >
                                            Remove Hero Image
                                        </button>
                                    </div>

                                    @error('hero_image')
                                        <p class="hb-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>

                    {{-- Cinematic hero: the layered card, its slides and its counters. --}}
                    @php
                        // A rejected row is named with its position, because "the background
                        // field is required" means nothing on a screen holding six of them.
                        // The cutout complaint is not listed here — it belongs on the row it
                        // is about, and it is already rendered there.
                        $heroRowErrors = [];

                        foreach ($errors->getMessages() as $key => $messages) {
                            if (preg_match('/^home_hero_(slides|stats)\.(\d+)\.(?!cutout)\w+$/', (string) $key, $matched) === 1) {
                                $label = $matched[1] === 'slides' ? 'Slide' : 'Counter';

                                foreach ($messages as $message) {
                                    $heroRowErrors[] = $label.' '.((int) $matched[2] + 1).': '.$message;
                                }
                            }
                        }
                    @endphp

                    <x-sheet.block title="Hero Layout" note="Which hero the homepage opens with. Cinematic only takes effect once at least one slide has a photograph.">
                        {{-- The marker that tells the server this screen owns the hero. Removing
                             the last slide leaves no inputs behind, and "no inputs" would
                             otherwise read as "this form never mentioned slides". --}}
                        <input type="hidden" name="hero_settings_submitted" value="1" />

                        <x-sheet.field
                            label="Hero style"
                            key="home_hero_style"
                            hint="Classic draws the hero the site has always had. Cinematic replaces the contents of the page header only, and only while a slide holds a photograph."
                            stack="true"
                        >
                            <div class="hb-samples">
                                @foreach (\App\Enums\HeroStyle::options() as $style)
                                    <label class="hb-sample">
                                        <input
                                            type="radio"
                                            name="home_hero_style"
                                            value="{{ $style->value }}"
                                            x-model="heroStyle"
                                        />
                                        <span class="hb-sample__face">
                                            <span class="block w-full text-gray-300 dark:text-white/20" aria-hidden="true">
                                                @if ($style === \App\Enums\HeroStyle::Cinematic)
                                                    {{-- A card with a word behind a subject and a bar
                                                         of counters under it. --}}
                                                    <svg class="h-16 w-full" fill="none" viewBox="0 0 120 56" preserveAspectRatio="none">
                                                        <rect class="stroke-current" x="0.75" y="0.75" width="118.5" height="54.5" rx="8" stroke-width="1.5" />
                                                        <text class="fill-current" x="60" y="34" font-size="22" font-weight="800" text-anchor="middle" fill-opacity=".55">Aa</text>
                                                        <circle class="fill-current" cx="78" cy="30" r="14" fill-opacity=".8" />
                                                        <rect class="fill-current" x="10" y="42" width="22" height="4" rx="2" fill-opacity=".7" />
                                                        <rect class="fill-current" x="38" y="42" width="22" height="4" rx="2" fill-opacity=".7" />
                                                        <rect class="fill-current" x="92" y="42" width="18" height="6" rx="3" fill-opacity=".7" />
                                                    </svg>
                                                @else
                                                    {{-- One band of text over one photograph. --}}
                                                    <svg class="h-16 w-full" fill="none" viewBox="0 0 120 56" preserveAspectRatio="none">
                                                        <rect class="stroke-current" x="0.75" y="0.75" width="118.5" height="54.5" rx="8" stroke-width="1.5" />
                                                        <rect class="fill-current" x="26" y="18" width="68" height="6" rx="3" fill-opacity=".8" />
                                                        <rect class="fill-current" x="36" y="28" width="48" height="4" rx="2" fill-opacity=".5" />
                                                        <rect class="fill-current" x="44" y="38" width="32" height="8" rx="4" fill-opacity=".8" />
                                                    </svg>
                                                @endif
                                            </span>
                                            <span class="hb-sample__word">{{ $style->label() }}</span>
                                            <span class="hb-sample__sub">{{ $style->description() }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            @error('home_hero_style')
                                <p class="hb-error">{{ $message }}</p>
                            @enderror
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block
                        title="Hero Slides"
                        note="Up to six, played in the order they are listed. Drag a slide by the handle at its foot, or move it with the arrows."
                        x-show="heroStyle === 'cinematic'"
                    >
                        <x-sheet.field label="Slides" stack="true">
                            <div class="hb-repeat" @image-selected.window="onHeroImage($event.detail)">
                                @foreach ($heroRowErrors as $message)
                                    <p class="hb-error">{{ $message }}</p>
                                @endforeach

                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(slide, index) in heroSlides" :key="slide.key">
                                        <li
                                            class="hb-repeat-item"
                                            data-sheet-order
                                            @dragover.prevent
                                            @drop.prevent="dropHeroSlide(index)"
                                        >
                                            {{-- Live preview: the same three layers the homepage stacks, at
                                                 thumbnail size, so the word can be judged against the photo
                                                 before anyone saves. The number the row plays at is printed
                                                 by the list's own counter, not by a typed index. --}}
                                            <div
                                                class="relative aspect-[16/9] overflow-hidden rounded-xl border bg-gray-900"
                                                :class="heroDrag === index ? 'border-gray-900 dark:border-white' : 'border-gray-100 dark:border-white/5'"
                                            >
                                                <template x-if="slide.backgroundUrl">
                                                    <img :src="slide.backgroundUrl" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover" />
                                                </template>
                                                <template x-if="slide.word">
                                                    <span class="absolute inset-0 flex items-center justify-center text-3xl font-extrabold tracking-tight text-white/30 uppercase" x-text="slide.word"></span>
                                                </template>
                                                <template x-if="slide.cutoutUrl">
                                                    <img :src="slide.cutoutUrl" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover" />
                                                </template>
                                                <template x-if="slide.headline">
                                                    <p class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 to-transparent p-2 text-xs font-semibold text-white" x-text="slide.headline"></p>
                                                </template>
                                            </div>

                                            <x-sheet.field label="Word" hint="Painted behind the subject. Leave blank for no word.">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_slides[${index}][word]`"
                                                    x-model="slide.word"
                                                    aria-label="Slide word"
                                                    class="hb-input"
                                                    maxlength="24"
                                                    placeholder="GLASS"
                                                />
                                            </x-sheet.field>

                                            <x-sheet.field label="Headline" hint="This becomes the page heading on that slide. Blank uses the site tagline.">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_slides[${index}][headline]`"
                                                    x-model="slide.headline"
                                                    aria-label="Slide headline"
                                                    class="hb-input"
                                                    maxlength="80"
                                                />
                                            </x-sheet.field>

                                            <x-sheet.field label="Tagline">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_slides[${index}][tagline]`"
                                                    x-model="slide.tagline"
                                                    aria-label="Slide tagline"
                                                    class="hb-input"
                                                    maxlength="160"
                                                />
                                            </x-sheet.field>

                                            <x-sheet.field label="Photograph" hint="Chosen from the media library. A slide without one is refused on save.">
                                                <div class="hb-cluster">
                                                    <input type="hidden" :name="`home_hero_slides[${index}][background]`" x-model="slide.background" />
                                                    <button
                                                        type="button"
                                                        class="hb-btn"
                                                        aria-label="Choose a photograph for this slide from the media library"
                                                        @click="pickHeroImage(index, 'background')"
                                                    >
                                                        <span x-text="slide.background ? 'Change photograph' : 'Choose a photograph'">Choose a photograph</span>
                                                    </button>
                                                </div>
                                            </x-sheet.field>

                                            <x-sheet.field label="Foreground cutout" hint="The subject with its background erased, exported at the same pixel size as the photograph.">
                                                <div class="hb-cluster">
                                                    <input type="hidden" :name="`home_hero_slides[${index}][cutout]`" x-model="slide.cutout" />
                                                    <button
                                                        type="button"
                                                        class="hb-btn"
                                                        aria-label="Choose a foreground cutout for this slide from the media library"
                                                        @click="pickHeroImage(index, 'cutout')"
                                                    >
                                                        <span x-text="slide.cutout ? 'Change cutout' : 'Choose a foreground cutout'">Choose a foreground cutout</span>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="hb-btn"
                                                        x-show="slide.cutout"
                                                        @click="slide.cutout = ''; slide.cutoutUrl = ''; slide.cutoutError = ''"
                                                    >
                                                        Remove the cutout
                                                    </button>
                                                </div>

                                                <p x-show="slide.cutoutError" x-text="slide.cutoutError" class="hb-error"></p>
                                            </x-sheet.field>

                                            <x-sheet.field label="Photo description" hint="Read out instead of the photograph. Required, because the photograph carries meaning.">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_slides[${index}][alt]`"
                                                    x-model="slide.alt"
                                                    aria-label="Slide photo description"
                                                    class="hb-input"
                                                    maxlength="160"
                                                />
                                            </x-sheet.field>

                                            <x-sheet.field label="Button label">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_slides[${index}][button_label]`"
                                                    x-model="slide.buttonLabel"
                                                    aria-label="Slide button label"
                                                    class="hb-input"
                                                    maxlength="40"
                                                />
                                            </x-sheet.field>

                                            <x-sheet.field label="Button link">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_slides[${index}][button_url]`"
                                                    x-model="slide.buttonUrl"
                                                    aria-label="Slide button link"
                                                    class="hb-input hb-input--mono"
                                                    maxlength="500"
                                                    placeholder="/quote"
                                                />
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <span
                                                    class="cursor-grab select-none"
                                                    draggable="true"
                                                    title="Drag to reorder"
                                                    aria-hidden="true"
                                                    @dragstart="heroDrag = index"
                                                    @dragend="heroDrag = null"
                                                >⠿</span>
                                                <button
                                                    type="button"
                                                    class="hb-btn"
                                                    aria-label="Move this slide earlier"
                                                    :disabled="index === 0"
                                                    @click="moveHeroSlide(index, -1)"
                                                >↑</button>
                                                <button
                                                    type="button"
                                                    class="hb-btn"
                                                    aria-label="Move this slide later"
                                                    :disabled="index === heroSlides.length - 1"
                                                    @click="moveHeroSlide(index, 1)"
                                                >↓</button>
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeHeroSlide(index)"
                                                >
                                                    Remove this slide
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="heroSlides.length === 0">
                                    No slides yet. Add one and pick a photograph — everything else on a slide is optional.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="addHeroSlide()">Add another slide</button>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block
                        title="Hero Counters"
                        note="Up to three, bottom-left of the card. A number counts up from zero on the first view; anything else is shown exactly as typed."
                        x-show="heroStyle === 'cinematic'"
                    >
                        <x-sheet.field label="Counters" stack="true">
                            <div class="hb-repeat">
                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(stat, index) in heroStats" :key="stat.key">
                                        <li class="hb-repeat-item">
                                            <x-sheet.field label="Label">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_stats[${index}][label]`"
                                                    x-model="stat.label"
                                                    aria-label="Counter label"
                                                    class="hb-input"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Value">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_stats[${index}][value]`"
                                                    x-model="stat.value"
                                                    aria-label="Counter value"
                                                    class="hb-input hb-input--mono"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Suffix">
                                                <input
                                                    type="text"
                                                    :name="`home_hero_stats[${index}][suffix]`"
                                                    x-model="stat.suffix"
                                                    aria-label="Counter suffix"
                                                    class="hb-input hb-input--mono"
                                                    placeholder="+"
                                                />
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeHeroStat(index)"
                                                >
                                                    Remove this counter
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="heroStats.length === 0">
                                    No counters. The hero simply shows no numbers — nothing here is invented.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="addHeroStat()">Add another counter</button>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block
                        title="Hero Defaults"
                        note="The button a slide falls back to, and whether the deck advances by itself."
                        x-show="heroStyle === 'cinematic'"
                    >
                        <x-sheet.input
                            label="Default Button Label"
                            name="home_hero_button_label"
                            key="home_hero_button_label"
                            hint="Used by any slide that does not name its own button."
                            :placeholder="__('site.home.hero_get_quote')"
                            :value="old('home_hero_button_label', $settings['home_hero_button_label'] ?? '')"
                        />
                        <x-sheet.input
                            label="Default Button Link"
                            name="home_hero_button_url"
                            key="home_hero_button_url"
                            placeholder="/quote"
                            :value="old('home_hero_button_url', $settings['home_hero_button_url'] ?? '')"
                        />
                        <x-sheet.field
                            label="Advance slides automatically"
                            for="home_hero_autoplay"
                            key="home_hero_autoplay"
                            hint="Every seven seconds, pausing on hover, on focus and on a hidden tab. Switched off, the visitor drives — and anyone who has reduced motion set in their system never sees it move at all."
                        >
                            <label class="hb-switch">
                                <input
                                    type="checkbox"
                                    id="home_hero_autoplay"
                                    name="home_hero_autoplay"
                                    value="1"
                                    {{ old('home_hero_autoplay', $settings['home_hero_autoplay'] ?? true) ? 'checked' : '' }}
                                />
                                <span class="hb-switch__track" aria-hidden="true"></span>
                            </label>
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block title="Fact Tiles" note="Up to four. Replaces the rating counters until real reviews back them.">
                        <x-sheet.field label="Tiles" stack="true" hint="A tile is a claim and the number that backs it. The order here is the order the band reads in.">
                            <div class="hb-repeat">
                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(tile, index) in factTiles" :key="index">
                                        <li class="hb-repeat-item">
                                            <x-sheet.field label="Label">
                                                <input
                                                    type="text"
                                                    :name="`home_fact_tiles[${index}][label]`"
                                                    x-model="tile.label"
                                                    aria-label="Fact tile label"
                                                    class="hb-input"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Value">
                                                <input
                                                    type="text"
                                                    :name="`home_fact_tiles[${index}][value]`"
                                                    x-model="tile.value"
                                                    aria-label="Fact tile value"
                                                    class="hb-input hb-input--mono"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Suffix">
                                                <input
                                                    type="text"
                                                    :name="`home_fact_tiles[${index}][suffix]`"
                                                    x-model="tile.suffix"
                                                    aria-label="Fact tile suffix"
                                                    class="hb-input hb-input--mono"
                                                />
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeFactTile(index)"
                                                >
                                                    Remove this tile
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="factTiles.length === 0">
                                    No fact tiles. The homepage shows no numbers of its own.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="factTiles.length < 4 && addFactTile()">
                                        Add another tile
                                    </button>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block title="Warranty Band" note="The band that says what a job is covered by, and the three cards that hold it up.">
                        <x-sheet.input
                            label="Heading"
                            name="home_warranty_heading"
                            key="home_warranty_heading"
                            :value="old('home_warranty_heading', $settings['home_warranty_heading'])"
                        />
                        <x-sheet.textarea
                            label="Description"
                            name="home_warranty_text"
                            key="home_warranty_text"
                            :value="old('home_warranty_text', $settings['home_warranty_text'])"
                        />
                        <x-sheet.field label="Highlight cards" stack="true" hint="Up to three highlight cards.">
                            <div class="hb-repeat">
                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(highlight, index) in warrantyHighlights" :key="index">
                                        <li class="hb-repeat-item">
                                            <x-sheet.field label="Title">
                                                <input
                                                    type="text"
                                                    :name="`home_warranty_highlights[${index}][title]`"
                                                    x-model="highlight.title"
                                                    aria-label="Highlight title"
                                                    class="hb-input"
                                                    placeholder="Title"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Benefit">
                                                <textarea
                                                    :name="`home_warranty_highlights[${index}][text]`"
                                                    x-model="highlight.text"
                                                    aria-label="Highlight benefit"
                                                    rows="2"
                                                    class="hb-textarea"
                                                    placeholder="One-line benefit"
                                                ></textarea>
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeHighlight(index)"
                                                >
                                                    Remove this highlight
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="warrantyHighlights.length === 0">
                                    No highlight cards. The band states its heading and its sentence only.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="warrantyHighlights.length < 3 && addHighlight()">
                                        Add another highlight
                                    </button>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block title="Urgency Band" note="Sits between services and the process steps, with call and WhatsApp buttons.">
                        <x-sheet.field label="Show the band" for="home_urgency_enabled" key="home_urgency_enabled">
                            <label class="hb-switch">
                                <input
                                    type="checkbox"
                                    id="home_urgency_enabled"
                                    name="home_urgency_enabled"
                                    value="1"
                                    {{ $settings['home_urgency_enabled'] ? 'checked' : '' }}
                                />
                                <span class="hb-switch__track" aria-hidden="true"></span>
                            </label>
                        </x-sheet.field>
                        <x-sheet.input
                            label="Message"
                            name="home_urgency_text"
                            key="home_urgency_text"
                            :value="old('home_urgency_text', $settings['home_urgency_text'])"
                        />
                    </x-sheet.block>

                    <x-sheet.block title="Call-To-Action Bands" note="The two bands the page closes with, and the message a WhatsApp link arrives carrying.">
                        <x-sheet.input
                            label="Repeated Band Heading"
                            name="home_cta_heading"
                            key="home_cta_heading"
                            :value="old('home_cta_heading', $settings['home_cta_heading'])"
                        />
                        <x-sheet.input
                            label="Final CTA Heading"
                            name="home_final_cta_heading"
                            key="home_final_cta_heading"
                            :value="old('home_final_cta_heading', $settings['home_final_cta_heading'])"
                        />
                        <x-sheet.textarea
                            label="Repeated Band Text"
                            name="home_cta_text"
                            key="home_cta_text"
                            :value="old('home_cta_text', $settings['home_cta_text'])"
                        />
                        <x-sheet.textarea
                            label="Final CTA Text"
                            name="home_final_cta_text"
                            key="home_final_cta_text"
                            :value="old('home_final_cta_text', $settings['home_final_cta_text'])"
                        />
                        <x-sheet.input
                            label="WhatsApp Pre-Filled Message"
                            name="home_whatsapp_message"
                            key="home_whatsapp_message"
                            hint="Attached to every WhatsApp link on the site, including the bubble and the mobile bar."
                            :value="old('home_whatsapp_message', $settings['home_whatsapp_message'])"
                        />
                    </x-sheet.block>

                    <x-sheet.block title="Where We Fit" note="The two places the shop reaches. The workshop address and its directions link come from the General group.">
                        <x-sheet.textarea
                            label="Service Area"
                            name="home_service_area"
                            key="home_service_area"
                            :value="old('home_service_area', $settings['home_service_area'])"
                        />
                        <x-sheet.textarea
                            label="Mobile Coverage"
                            name="home_mobile_coverage"
                            key="home_mobile_coverage"
                            hint="The workshop address and directions link come from General and Social settings."
                            :value="old('home_mobile_coverage', $settings['home_mobile_coverage'])"
                        />
                    </x-sheet.block>

                    <x-sheet.block title="Process Steps" note="Up to four. The page numbers them in the order they are listed here.">
                        <x-sheet.field label="Steps" stack="true">
                            <div class="hb-repeat">
                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(step, index) in processSteps" :key="index">
                                        <li class="hb-repeat-item">
                                            <x-sheet.field label="Title">
                                                <input
                                                    type="text"
                                                    :name="`home_process_steps[${index}][title]`"
                                                    x-model="step.title"
                                                    aria-label="Step title"
                                                    class="hb-input"
                                                    placeholder="Title"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Description">
                                                <textarea
                                                    :name="`home_process_steps[${index}][text]`"
                                                    x-model="step.text"
                                                    aria-label="Step description"
                                                    rows="2"
                                                    class="hb-textarea"
                                                    placeholder="Description"
                                                ></textarea>
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeStep(index)"
                                                >
                                                    Remove this step
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="processSteps.length === 0">
                                    No steps. The page leaves out the process band.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="processSteps.length < 4 && addStep()">
                                        Add another step
                                    </button>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block title="Frequently Asked Questions" note="Rendered as accordions and emitted as FAQPage structured data. Up to eight.">
                        <x-sheet.field label="Questions" stack="true">
                            <div class="hb-repeat">
                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(faq, index) in faqs" :key="index">
                                        <li class="hb-repeat-item">
                                            <x-sheet.field label="Question">
                                                <input
                                                    type="text"
                                                    :name="`home_faqs[${index}][question]`"
                                                    x-model="faq.question"
                                                    aria-label="Question"
                                                    class="hb-input"
                                                    placeholder="Question"
                                                />
                                            </x-sheet.field>
                                            <x-sheet.field label="Answer">
                                                <textarea
                                                    :name="`home_faqs[${index}][answer]`"
                                                    x-model="faq.answer"
                                                    aria-label="Answer"
                                                    rows="3"
                                                    class="hb-textarea"
                                                    placeholder="Answer"
                                                ></textarea>
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeFaq(index)"
                                                >
                                                    Remove this question
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="faqs.length === 0">
                                    No questions. The page shows no accordion, and no FAQ data is emitted.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="faqs.length < 8 && addFaq()">
                                        Add another question
                                    </button>
                                </div>

                                @error('home_faqs.*.answer')
                                    <p class="hb-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>
                </div>

                {{-- Announcements Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="announcements"
                    x-show="tab === 'announcements'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'announcements') style="display: none" @endif
                >
                    <x-sheet.block title="Announcements" note="Manage the scrolling marquee messages. A message nobody can finish reading is a message that should not be here.">
                        <x-sheet.field
                            label="Show the marquee"
                            for="announcement_active"
                            key="announcement_active"
                            hint="The band is drawn while this is on and at least one message has not expired."
                        >
                            <label class="hb-switch">
                                <input
                                    type="checkbox"
                                    id="announcement_active"
                                    name="announcement_active"
                                    value="1"
                                    {{ $settings['announcement_active'] ? 'checked' : '' }}
                                />
                                <span class="hb-switch__track" aria-hidden="true"></span>
                            </label>
                        </x-sheet.field>
                    </x-sheet.block>

                    <x-sheet.block title="Marquee Content" note="One message per row, scrolled in the order they are listed here.">
                        <x-sheet.field label="Messages" stack="true">
                            <div class="hb-repeat">
                                <ul class="hb-repeat-list" data-sheet-list>
                                    <template x-for="(announcement, index) in announcements" :key="index">
                                        <li class="hb-repeat-item">
                                            <x-sheet.field label="Message text">
                                                <input
                                                    type="text"
                                                    :name="`announcements[${index}][text]`"
                                                    x-model="announcement.text"
                                                    aria-label="Announcement message text"
                                                    class="hb-input"
                                                    placeholder="Type message..."
                                                />
                                            </x-sheet.field>

                                            <x-sheet.field label="Destination" hint="A page of the site, or a link you type. Left on No link, the message is only ever read.">
                                                <div class="hb-cluster">
                                                    <select
                                                        :name="`announcements[${index}][link]`"
                                                        x-model="announcement.link"
                                                        aria-label="Announcement destination"
                                                        class="hb-select"
                                                    >
                                                        <option value="">No Link</option>
                                                        <optgroup label="System Routes">
                                                            @foreach ($availableRoutes as $value => $label)
                                                                <option value="{{ $value }}">{{ $label }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        <option value="custom">Custom Link...</option>
                                                        <template x-if="announcement.link && ! [{{ implode(',', array_map(fn($r) => "'$r'", array_keys($availableRoutes))) }}].includes(announcement.link) && announcement.link !== 'custom'">
                                                            <optgroup label="Selected Custom Link">
                                                                <option
                                                                    :value="announcement.link"
                                                                    x-text="announcement.link"
                                                                    selected
                                                                ></option>
                                                            </optgroup>
                                                        </template>
                                                    </select>
                                                    <input
                                                        type="text"
                                                        x-show="announcement.link === 'custom' || (! [{{ implode(',', array_map(fn($r) => "'$r'", array_keys($availableRoutes))) }}].includes(announcement.link) && announcement.link !== '' && announcement.link !== 'custom')"
                                                        x-model="announcement.link"
                                                        aria-label="Custom announcement link"
                                                        class="hb-input hb-input--mono"
                                                        placeholder="Enter URL (e.g. https://...)"
                                                    />
                                                </div>
                                            </x-sheet.field>

                                            <x-sheet.field label="Active until" hint="Leave blank to never expire. The message hides automatically after this date and time.">
                                                <input
                                                    type="datetime-local"
                                                    :name="`announcements[${index}][expires_at]`"
                                                    x-model="announcement.expires_at"
                                                    aria-label="Announcement expiry date and time"
                                                    class="hb-input hb-input--mono"
                                                />
                                            </x-sheet.field>

                                            <div class="hb-repeat-bar">
                                                <button
                                                    type="button"
                                                    class="hb-btn hb-repeat-bar__push"
                                                    @click="removeAnnouncement(index)"
                                                >
                                                    Remove this announcement
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                </ul>

                                <p class="hb-repeat__empty" x-show="announcements.length === 0">
                                    No messages. The marquee has nothing to scroll.
                                </p>

                                <div class="hb-repeat-bar">
                                    <button type="button" class="hb-btn" @click="addAnnouncement()">
                                        Add another message
                                    </button>
                                </div>
                            </div>
                        </x-sheet.field>
                    </x-sheet.block>
                </div>

                @if ($envConfig !== [])
                    {{-- System Config Tab --}}
                <div
                    class="hb-pane"
                    data-sheet-pane="system_config"
                    x-show="tab === 'system_config'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    @if ($activeTab !== 'system_config') style="display: none" @endif
                >
                    <x-sheet.block
                        title="System Config"
                        note="Allowlisted environment variables. Secret values are never shown here, and nothing outside this list is offered for editing. A value is read-only until you click it, and a save writes to the environment file rather than to a settings row."
                    >
                        @foreach ($envConfig as $key => $value)
                            <x-sheet.field
                                :label="$key"
                                for="env_{{ $key }}"
                                x-data="{ editing: false }"
                            >
                                <input
                                    type="text"
                                    id="env_{{ $key }}"
                                    name="env[{{ $key }}]"
                                    value="{{ old('env.'.$key, $value) }}"
                                    :readonly="! editing"
                                    @click="editing = true"
                                    @click.away="editing = false"
                                    class="hb-input"
                                />
                            </x-sheet.field>
                        @endforeach
                    </x-sheet.block>
                </div>
                @endif
                </div>
            </div>

            <div class="hb-foot" data-sheet-foot @if (session('success')) data-just-set @endif>
                <p class="hb-foot__count" data-sheet-count>Nothing marked. Everything on this sheet is set.</p>
                <div class="hb-foot__actions">
                    <span class="hb-foot__live" data-sheet-live aria-live="polite" role="status"></span>
                    <button
                        type="button"
                        class="hb-btn"
                        data-sheet-filter
                        aria-pressed="false"
                        data-on-word="Showing marks only"
                        data-off-word="Show only marks"
                    ><span data-sheet-filter-word>Show only marks</span></button>
                    <x-sheet.commit label="Save All Changes" data-sheet-save />
                </div>
            </div>
        </form>

        <footer class="hb-colophon">
            <p>Written to <span class="hb-mono">company_settings</span>. A save clears the settings cache, so what you set here is what the site serves on the next request.</p>
            <p><a class="hb-link hb-link--utility" href="{{ url('/') }}">The homepage</a> shows the Hero, Homepage, Locale and Announcements groups.</p>
            <p><a class="hb-link hb-link--utility" href="{{ url('/contact') }}">The contact page</a> shows the General and Social groups.</p>
        </footer>
    </div>
</x-layouts::admin>
