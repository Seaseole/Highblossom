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
    @endphp
    <div
        class="mx-auto max-w-5xl space-y-10 py-6 sm:py-10"
        x-data="{
        tab: '{{ request()->query('tab', 'general') }}',
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
            this.$watch('tab', value => {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', value);
                window.history.replaceState({}, '', url.toString());
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
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Company Settings</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your business information and dynamic variables.</p>
        </div>

        <form
            action="{{ route('admin.settings.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-8"
        >
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" :value="tab" />

            <!-- Tabs Navigation -->
            <div class="no-scrollbar flex gap-x-5 overflow-x-auto border-b border-gray-200 dark:border-white/10">
                @foreach ([
                    'general' => 'General',
                    'hours' => 'Hours',
                    'assets' => 'Branding',
                    'homepage' => 'Homepage',
                    'localization' => 'Locale',
                    'social' => 'Social',
                    'notifications' => 'Notifications',
                    'announcements' => 'Announcements',
                ] + ($envConfig !== [] ? ['system_config' => 'System Config'] : []) as $key => $label)
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
                <!-- General Tab -->
                <div
                    x-show="tab === 'general'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                >
                    <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">General Information</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Name</label>
                            <input
                                type="text"
                                name="company_name"
                                value="{{ old('company_name', $settings['company_name']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Logo Text</label>
                            <input
                                type="text"
                                name="logo_text"
                                value="{{ old('logo_text', $settings['logo_text']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Email</label>
                            <input
                                type="email"
                                name="primary_email"
                                value="{{ old('primary_email', $settings['primary_email']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Phone</label>
                            <input
                                type="text"
                                name="primary_phone"
                                value="{{ old('primary_phone', $settings['primary_phone']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Secondary Phone</label>
                            <input
                                type="text"
                                name="secondary_phone"
                                value="{{ old('secondary_phone', $settings['secondary_phone']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="+267 ..."
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Optional second line. Shown on the contact page and in the footer when filled in.
                            </p>
                            @error('secondary_phone')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Address</label>
                            <textarea
                                name="address"
                                rows="3"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            >{{ old('address', $settings['address']) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Google Maps API Key</label>
                            <input
                                type="text"
                                name="google_maps_api_key"
                                value="{{ old('google_maps_api_key', $settings['google_maps_api_key']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="AIzaSy..."
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                This key is sent to every visitor's browser, so restrict it in Google Cloud to your
                                site domains and the Maps JavaScript API only. Store server-only keys in
                                <code>.env</code> instead.
                            </p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Map Directions Link</label>
                            <input
                                type="url"
                                name="map_directions_link"
                                value="{{ old('map_directions_link', $settings['map_directions_link']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                placeholder="https://maps.app.goo.gl/..."
                            />
                        </div>
                        <div class="space-y-3 border-t border-gray-100 pt-6 md:col-span-2 dark:border-white/10">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Account Security</label>
                            <div class="flex items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Require verified email addresses</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        When on, users whose email is not verified are sent to the verification page before they can
                                        access the admin panel or settings. Registration emails a verification link.
                                    </p>
                                </div>
                                <label class="group relative inline-flex shrink-0 cursor-pointer items-center">
                                    <input type="hidden" name="require_email_verification" value="0" />
                                    <input
                                        type="checkbox"
                                        name="require_email_verification"
                                        value="1"
                                        {{ $settings['require_email_verification'] ? 'checked' : '' }}
                                        class="peer sr-only"
                                    />
                                    <div class="peer h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-gray-900 peer-focus:outline-none after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-white/10 dark:peer-checked:bg-white dark:after:bg-gray-400"></div>
                                    <span class="ml-3 text-xs font-bold tracking-widest text-gray-500 uppercase transition-colors group-hover:text-gray-900 dark:group-hover:text-white">Enforce</span>
                                </label>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Enable registration</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Allow new users to create an account from the site. When off, the register page and
                                        sign-up links are hidden and direct visits to /register return Not Found.
                                    </p>
                                </div>
                                <label class="group relative inline-flex shrink-0 cursor-pointer items-center">
                                    <input type="hidden" name="enable_registration" value="0" />
                                    <input
                                        type="checkbox"
                                        name="enable_registration"
                                        value="1"
                                        {{ $settings['enable_registration'] ? 'checked' : '' }}
                                        class="peer sr-only"
                                    />
                                    <div class="peer h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-gray-900 peer-focus:outline-none after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-white/10 dark:peer-checked:bg-white dark:after:bg-gray-400"></div>
                                    <span class="ml-3 text-xs font-bold tracking-widest text-gray-500 uppercase transition-colors group-hover:text-gray-900 dark:group-hover:text-white">Allow</span>
                                </label>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-t border-gray-100 pt-5 dark:border-white/10">
                                <div class="space-y-1">
                                    <label for="session_idle_timeout_minutes" class="text-sm text-gray-700 dark:text-gray-300">
                                        Session idle timeout (minutes)
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Close a signed-in session after this many minutes without activity, even while it is still
                                        inside the {{ config('session.lifetime') }}-minute lifetime. Set to 0 to leave the lifetime in
                                        charge. The hourly prune applies it, so an idle session can outlive this by up to an hour.
                                    </p>
                                    @error('session_idle_timeout_minutes')
                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <input
                                    type="number"
                                    id="session_idle_timeout_minutes"
                                    name="session_idle_timeout_minutes"
                                    min="0"
                                    max="4320"
                                    step="1"
                                    value="{{ old('session_idle_timeout_minutes', $settings['session_idle_timeout_minutes']) }}"
                                    class="w-24 shrink-0 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                />
                            </div>
                            <div class="flex items-center justify-between gap-4 border-t border-gray-100 pt-5 dark:border-white/10">
                                <div class="space-y-1">
                                    <label for="admin_nav_layout" class="text-sm text-gray-700 dark:text-gray-300">
                                        Default admin layout
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Which navigation an account gets before anyone chooses for itself. Anyone who has
                                        saved a layout on their own profile keeps that choice, and phones and tablets use
                                        the slide-out menu either way — this only decides the wide screen.
                                    </p>
                                    @error('admin_nav_layout')
                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <select
                                    id="admin_nav_layout"
                                    name="admin_nav_layout"
                                    class="w-40 shrink-0 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                >
                                    @foreach (\App\Enums\AdminNavLayout::options() as $layout)
                                        <option
                                            value="{{ $layout->value }}"
                                            {{ old('admin_nav_layout', $settings['admin_nav_layout']) === $layout->value ? 'selected' : '' }}
                                        >{{ $layout->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Hours Tab -->
                <div
                    x-show="tab === 'hours'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <h3 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Weekly Business Hours</h3>
                    <div class="space-y-4">
                        @php
                            $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
                        @endphp
                        @foreach ($days as $key => $label)
                            <div class="flex flex-col items-start gap-3 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-center sm:gap-4">
                                <div class="sm:w-32">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-ui.checkbox
                                        name="working_hours[{{ $key }}][is_closed]"
                                        value="1"
                                        :checked="($settings['working_hours'][$key]['is_closed'] ?? false)"
                                        label="Closed"
                                    />
                                </div>
                                <div class="grid w-full flex-1 grid-cols-2 gap-2 sm:w-auto sm:gap-4">
                                    <input
                                        type="time"
                                        name="working_hours[{{ $key }}][open]"
                                        value="{{ $settings['working_hours'][$key]['open'] ?? '' }}"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                    />
                                    <input
                                        type="time"
                                        name="working_hours[{{ $key }}][close]"
                                        value="{{ $settings['working_hours'][$key]['close'] ?? '' }}"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 border-t border-gray-100 pt-6 dark:border-white/5">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Booking Rules</h3>
                        <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                            Customers are only offered slots on days that are open above. A day marked Closed shows no slots at all.
                        </p>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    for="booking_lead_time_hours"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Minimum Booking Notice (hours)</label>
                                <input
                                    type="number"
                                    id="booking_lead_time_hours"
                                    name="booking_lead_time_hours"
                                    min="0"
                                    max="24"
                                    step="1"
                                    value="{{ old('booking_lead_time_hours', $settings['booking_lead_time_hours']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    How far ahead of the current time a customer must book. Set to 0 to allow same-day booking up to closing.
                                </p>
                                @error('booking_lead_time_hours')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label
                                    for="booking_slot_interval_minutes"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Slot Interval (minutes)</label>
                                <input
                                    type="number"
                                    id="booking_slot_interval_minutes"
                                    name="booking_slot_interval_minutes"
                                    min="15"
                                    max="240"
                                    step="15"
                                    value="{{ old('booking_slot_interval_minutes', $settings['booking_slot_interval_minutes']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Gap between the start of one offered time and the next. A job must still finish by closing time.
                                </p>
                                @error('booking_slot_interval_minutes')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label
                                    for="booking_capacity_workshop"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Workshop Bays</label>
                                <input
                                    type="number"
                                    id="booking_capacity_workshop"
                                    name="booking_capacity_workshop"
                                    min="0"
                                    max="20"
                                    step="1"
                                    value="{{ old('booking_capacity_workshop', $settings['booking_capacity_workshop']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    How many workshop jobs can overlap at once. Each staff absence you record removes one from this.
                                </p>
                                @error('booking_capacity_workshop')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label
                                    for="booking_capacity_mobile"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Mobile Units</label>
                                <input
                                    type="number"
                                    id="booking_capacity_mobile"
                                    name="booking_capacity_mobile"
                                    min="0"
                                    max="20"
                                    step="1"
                                    value="{{ old('booking_capacity_mobile', $settings['booking_capacity_mobile']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    How many mobile fittings can run at the same time. Mobile and workshop pools are counted separately.
                                </p>
                                @error('booking_capacity_mobile')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Job Duration (minutes)</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                How long each service holds a bay or a van. This is what decides the last slot of the day.
                            </p>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach (app(App\Services\Settings\SettingsManager::class)->getDefaults()['booking_service_durations'] ?? [] as $serviceKey => $defaultDuration)
                                    <div class="space-y-2">
                                        <label
                                            for="booking_service_durations_{{ $serviceKey }}"
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >{{ \Illuminate\Support\Str::headline((string) $serviceKey) }}</label>
                                        <input
                                            type="number"
                                            id="booking_service_durations_{{ $serviceKey }}"
                                            name="booking_service_durations[{{ $serviceKey }}]"
                                            min="15"
                                            max="480"
                                            step="15"
                                            value="{{ old('booking_service_durations.'.$serviceKey, $settings['booking_service_durations'][$serviceKey] ?? $defaultDuration) }}"
                                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                        />
                                        @error('booking_service_durations.'.$serviceKey)
                                            <p class="text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Closed Dates</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Public holidays and one-off closures. These override the weekly hours above.
                                </p>
                            </div>
                            <a
                                href="{{ route('admin.bookings.closures.index') }}"
                                class="inline-flex min-h-[40px] items-center rounded-xl border border-gray-200 bg-white px-4 text-sm font-medium text-gray-900 transition-colors hover:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:text-white"
                            >
                                Manage closed dates
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Branding Tab -->
                <div
                    x-show="tab === 'assets'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                    x-data="{
                        logoPreview: '{{ $settings['business_logo'] ? media_url($settings['business_logo']) : null }}',
                        faviconPreview: '{{ $settings['favicon'] ? media_url($settings['favicon']) : (file_exists(public_path('favicon.ico')) ? '/favicon.ico' : null) }}',
                        handleFileSelect(event, previewKey) {
                            const file = event.target.files[0];
                            if (file) this[previewKey] = URL.createObjectURL(file);
                        }
                     }"
                >
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Branding</h3>
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="space-y-4" x-data="{ removeLogo: false }">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Business Logo</label>
                            <input type="hidden" name="remove_business_logo" :value="removeLogo ? 1 : 0" />
                            <div
                                class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                                @click="if (! removeLogo) $refs.logoInput.click();"
                            >
                                <template x-if="logoPreview && ! removeLogo">
                                    <img :src="logoPreview" class="max-h-[140px] object-contain" />
                                </template>
                                <template x-if="! logoPreview || removeLogo">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload logo</span>
                                </template>
                            </div>
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
                            <button
                                type="button"
                                @click="
                                    removeLogo = true;
                                    logoPreview = null;
                                "
                                x-show="logoPreview && ! removeLogo"
                                class="inline-flex items-center gap-2 rounded-full bg-red-500 px-6 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Remove Logo
                            </button>
                        </div>
                        <div class="space-y-4" x-data="{ removeFavicon: false }">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
                            <input type="hidden" name="remove_favicon" :value="removeFavicon ? 1 : 0" />
                            <div
                                class="relative flex min-h-[160px] w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                                @click="if (! removeFavicon) $refs.faviconInput.click();"
                            >
                                <template x-if="faviconPreview && ! removeFavicon">
                                    <img :src="faviconPreview" class="max-h-[140px] object-contain" />
                                </template>
                                <template x-if="! faviconPreview || removeFavicon">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload favicon</span>
                                </template>
                            </div>
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
                            <button
                                type="button"
                                @click="
                                    removeFavicon = true;
                                    faviconPreview = null;
                                "
                                x-show="faviconPreview && ! removeFavicon"
                                class="inline-flex items-center gap-2 rounded-full bg-red-500 px-6 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18 0.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Remove Favicon
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Locale Tab -->
                <div
                    x-show="tab === 'localization'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Locale Settings</h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                            <input
                                type="text"
                                name="timezone"
                                value="{{ old('timezone', $settings['timezone']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Currency Symbol</label>
                            <input
                                type="text"
                                name="currency_symbol"
                                value="{{ old('currency_symbol', $settings['currency_symbol']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Format</label>
                            <select
                                name="date_format"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            >
                                @foreach (['d/m/Y', 'm/d/Y', 'Y-m-d', 'd.m.Y', 'j M Y', 'D, j M Y'] as $format)
                                    <option
                                        value="{{ $format }}"
                                        {{ old('date_format', $settings['date_format']) === $format ? 'selected' : '' }}
                                    >
                                        {{ date($format) }} ({{ $format }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Format</label>
                            <select
                                name="time_format"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            >
                                @foreach (['H:i', 'h:i A', 'H:i:s', 'h:i:s A'] as $format)
                                    <option
                                        value="{{ $format }}"
                                        {{ old('time_format', $settings['time_format']) === $format ? 'selected' : '' }}
                                    >
                                        {{ date($format) }} ({{ $format }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Time Display</label>
                            <select
                                name="time_format_display"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
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
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Locale</label>
                            <input
                                type="text"
                                name="locale"
                                value="{{ old('locale', $settings['locale']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                placeholder="en_GB"
                            />
                        </div>
                    </div>
                </div>

                <!-- Social Tab -->
                <div
                    x-show="tab === 'social'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Social Links</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Facebook</label>
                                <input
                                    type="url"
                                    name="facebook_url"
                                    value="{{ old('facebook_url', $settings['facebook_url']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Instagram</label>
                                <input
                                    type="url"
                                    name="instagram_url"
                                    value="{{ old('instagram_url', $settings['instagram_url']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">LinkedIn</label>
                                <input
                                    type="url"
                                    name="linkedin_url"
                                    value="{{ old('linkedin_url', $settings['linkedin_url']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none dark:border-white/10 dark:bg-white/5"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">WhatsApp Settings</h3>
                            <button
                                type="button"
                                @click="addNumber()"
                                class="text-sm font-medium text-gray-900 hover:underline dark:text-white"
                            >
                                + Add Additional Number
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Default WhatsApp Number</label>
                                <input
                                    type="text"
                                    name="whatsapp_number_default"
                                    value="{{ old('whatsapp_number_default', $settings['whatsapp_number_default']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                    placeholder="+267 ..."
                                />
                            </div>

                            <div class="space-y-4">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Additional WhatsApp Numbers</label>
                                <div class="grid gap-4">
                                    <template x-for="(number, index) in whatsappNumbers" :key="index">
                                        <div class="flex flex-col gap-3 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-start sm:gap-4">
                                            <div class="grid flex-1 grid-cols-1 gap-4 md:grid-cols-2">
                                                <div class="space-y-1">
                                                    <input
                                                        type="text"
                                                        :name="`whatsapp_additional_numbers[${index}][label]`"
                                                        x-model="number.label"
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                                        placeholder="e.g. Sales, Support"
                                                    />
                                                </div>
                                                <div class="space-y-1">
                                                    <input
                                                        type="text"
                                                        :name="`whatsapp_additional_numbers[${index}][number]`"
                                                        x-model="number.number"
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                                        placeholder="+267 ..."
                                                    />
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                @click="removeNumber(index)"
                                                class="inline-flex items-center justify-center gap-2 self-end rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98] sm:self-auto sm:px-6"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>

                                                Delete
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div
                    x-show="tab === 'notifications'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Quote Notification Emails</label>
                        <textarea
                            name="quote_notification_emails"
                            rows="3"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        >{{ (string) old('quote_notification_emails', $settings['quote_notification_emails'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Homepage Tab -->
                <div
                    x-show="tab === 'homepage'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-10 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <div class="space-y-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Homepage Content</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Every heading, number and band on the public homepage. A section with no content hides itself.
                        </p>
                    </div>

                    {{-- Hero --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Hero</h4>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tagline</label>
                                <input
                                    type="text"
                                    name="home_tagline"
                                    value="{{ old('home_tagline', $settings['home_tagline']) }}"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">Used as the page title and the hero heading.</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Years Of Experience</label>
                                <input
                                    type="text"
                                    name="home_years_experience"
                                    value="{{ old('home_years_experience', $settings['home_years_experience']) }}"
                                    placeholder="20+"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">Shown in the hero badge. Leave blank to hide the badge.</p>
                            </div>
                        </div>

                        <div class="space-y-4" x-data="{ removeHero: false }">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Hero Image</label>
                            <input type="hidden" name="remove_hero_image" :value="removeHero ? 1 : 0" />
                            <div
                                class="relative flex min-h-[200px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 transition-all hover:border-gray-900 dark:border-white/10 dark:bg-white/5 dark:hover:border-white"
                                @click="if (! removeHero) $refs.heroInput.click();"
                            >
                                <template x-if="heroPreview && ! removeHero">
                                    <img :src="heroPreview" class="max-h-[220px] w-full object-cover" />
                                </template>
                                <template x-if="! heroPreview || removeHero">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Click to upload a hero banner</span>
                                </template>
                            </div>
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
                            <button
                                type="button"
                                @click="removeHero = true; heroPreview = null;"
                                x-show="heroPreview && ! removeHero"
                                class="inline-flex items-center gap-2 rounded-full bg-red-500 px-6 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98]"
                            >
                                Remove Hero Image
                            </button>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Cropped to 1920×1080 and served as WebP. Without one, the hero shows its dark gradient backdrop.</p>
                            @error('hero_image')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

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

                        <div class="space-y-8 rounded-2xl border border-gray-100 bg-gray-50 p-4 sm:p-6 dark:border-white/10 dark:bg-white/5">
                            {{-- The marker that tells the server this screen owns the hero. Removing
                                 the last slide leaves no inputs behind, and "no inputs" would
                                 otherwise read as "this form never mentioned slides". --}}
                            <input type="hidden" name="hero_settings_submitted" value="1" />

                            <div class="space-y-1">
                                <h5 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Hero Layout</h5>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Which hero the homepage opens with. Cinematic only takes effect once at least one slide has a photograph.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach (\App\Enums\HeroStyle::options() as $style)
                                    <label class="relative cursor-pointer">
                                        <input
                                            type="radio"
                                            name="home_hero_style"
                                            value="{{ $style->value }}"
                                            x-model="heroStyle"
                                            class="peer sr-only"
                                        />
                                        <div class="flex h-full flex-col gap-4 rounded-xl border-2 border-gray-200 p-4 transition-all peer-checked:border-gray-900 peer-checked:bg-white dark:border-white/10 dark:peer-checked:border-white dark:peer-checked:bg-white/10">
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
                                            <span>
                                                <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $style->label() }}</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $style->description() }}</span>
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('home_hero_style')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror

                            <div x-show="heroStyle === 'cinematic'" class="space-y-10 border-t border-gray-200 pt-8 dark:border-white/10">
                                {{-- Slides --}}
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <h5 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Slides</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Up to six, played in the order they are listed. Drag a slide, or use the arrows.</p>
                                        </div>
                                        <button type="button" @click="addHeroSlide()" class="text-sm font-medium text-gray-900 hover:underline dark:text-white">+ Add Slide</button>
                                    </div>

                                    <p x-show="heroSlides.length === 0" class="rounded-xl border border-dashed border-gray-300 p-6 text-center text-xs text-gray-500 dark:border-white/15 dark:text-gray-400">
                                        No slides yet. Add one and pick a photograph — everything else on a slide is optional.
                                    </p>

                                    @foreach ($heroRowErrors as $message)
                                        <p class="text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @endforeach

                                    <div class="space-y-4" @image-selected.window="onHeroImage($event.detail)">
                                        <template x-for="(slide, index) in heroSlides" :key="slide.key">
                                            <div
                                                class="grid grid-cols-1 gap-4 rounded-2xl border bg-white p-4 sm:p-6 dark:bg-white/5"
                                                :class="heroDrag === index ? 'border-gray-900 dark:border-white' : 'border-gray-100 dark:border-white/5'"
                                                @dragover.prevent
                                                @drop.prevent="dropHeroSlide(index)"
                                            >
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="cursor-grab text-gray-400 select-none" draggable="true" title="Drag to reorder" @dragstart="heroDrag = index" @dragend="heroDrag = null" aria-hidden="true">⠿</span>
                                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-[10px] font-bold text-white dark:bg-white dark:text-gray-900" x-text="index + 1">1</span>
                                                    </div>

                                                    {{-- Live preview: the same three layers the homepage stacks, at
                                                         thumbnail size, so the word can be judged against the photo
                                                         before anyone saves. --}}
                                                    <div class="relative aspect-[16/9] overflow-hidden rounded-xl bg-gray-900">
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

                                                    <div class="flex items-center gap-2">
                                                        <button type="button" @click="moveHeroSlide(index, -1)" :disabled="index === 0" class="rounded-lg border border-gray-200 px-2 py-1 text-xs disabled:opacity-40 dark:border-white/10" aria-label="Move this slide earlier">↑</button>
                                                        <button type="button" @click="moveHeroSlide(index, 1)" :disabled="index === heroSlides.length - 1" class="rounded-lg border border-gray-200 px-2 py-1 text-xs disabled:opacity-40 dark:border-white/10" aria-label="Move this slide later">↓</button>
                                                        <button type="button" @click="removeHeroSlide(index)" class="ml-auto rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600">Delete</button>
                                                    </div>
                                                </div>

                                                <div class="flex-1 space-y-4">
                                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Display Word</label>
                                                            <input type="text" :name="`home_hero_slides[${index}][word]`" x-model="slide.word" maxlength="24" placeholder="GLASS" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Painted behind the subject. Leave blank for no word.</p>
                                                        </div>
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Headline</label>
                                                            <input type="text" :name="`home_hero_slides[${index}][headline]`" x-model="slide.headline" maxlength="80" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">This becomes the page heading on that slide. Blank uses the site tagline.</p>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Tagline</label>
                                                        <input type="text" :name="`home_hero_slides[${index}][tagline]`" x-model="slide.tagline" maxlength="160" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                    </div>

                                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Photograph</label>
                                                            <input type="hidden" :name="`home_hero_slides[${index}][background]`" x-model="slide.background" />
                                                            <button type="button" @click="pickHeroImage(index, 'background')" class="flex w-full items-center justify-between rounded-xl border border-dashed border-gray-300 px-4 py-3 text-xs font-medium text-gray-600 transition-colors hover:border-gray-900 dark:border-white/15 dark:text-gray-300 dark:hover:border-white">
                                                                <span x-text="slide.background ? 'Change photograph' : 'Choose a photograph'">Choose a photograph</span>
                                                                <span class="text-gray-400">Media Library</span>
                                                            </button>
                                                        </div>
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Foreground Cutout</label>
                                                            <input type="hidden" :name="`home_hero_slides[${index}][cutout]`" x-model="slide.cutout" />
                                                            <button type="button" @click="pickHeroImage(index, 'cutout')" class="flex w-full items-center justify-between rounded-xl border border-dashed border-gray-300 px-4 py-3 text-xs font-medium text-gray-600 transition-colors hover:border-gray-900 dark:border-white/15 dark:text-gray-300 dark:hover:border-white">
                                                                <span x-text="slide.cutout ? 'Change cutout' : 'Choose a foreground cutout'">Choose a foreground cutout</span>
                                                                <span class="text-gray-400">Media Library</span>
                                                            </button>
                                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">The subject with its background erased, exported at the same pixel size as the photograph.</p>
                                                            <p x-show="slide.cutoutError" x-text="slide.cutoutError" class="text-[10px] font-medium text-red-600 dark:text-red-400"></p>
                                                            <button type="button" x-show="slide.cutout" @click="slide.cutout = ''; slide.cutoutUrl = ''; slide.cutoutError = ''" class="text-[10px] font-medium text-gray-500 underline hover:text-red-500 dark:text-gray-400">Remove the cutout</button>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Photo Description</label>
                                                        <input type="text" :name="`home_hero_slides[${index}][alt]`" x-model="slide.alt" maxlength="160" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                        <p class="text-[10px] text-gray-500 dark:text-gray-400">Read out instead of the photograph. Required, because the photograph carries meaning.</p>
                                                    </div>

                                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Button Label</label>
                                                            <input type="text" :name="`home_hero_slides[${index}][button_label]`" x-model="slide.buttonLabel" maxlength="40" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                        </div>
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Button Link</label>
                                                            <input type="text" :name="`home_hero_slides[${index}][button_url]`" x-model="slide.buttonUrl" maxlength="500" placeholder="/quote" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Counters --}}
                                <div class="space-y-4 border-t border-gray-200 pt-8 dark:border-white/10">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <h5 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Hero Counters</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Up to three, bottom-left of the card. A number counts up from zero on the first view; anything else is shown exactly as typed.</p>
                                        </div>
                                        <button type="button" @click="addHeroStat()" class="text-sm font-medium text-gray-900 hover:underline dark:text-white">+ Add Counter</button>
                                    </div>

                                    <p x-show="heroStats.length === 0" class="rounded-xl border border-dashed border-gray-300 p-4 text-center text-xs text-gray-500 dark:border-white/15 dark:text-gray-400">
                                        No counters. The hero simply shows no numbers — nothing here is invented.
                                    </p>

                                    <div class="space-y-4">
                                        <template x-for="(stat, index) in heroStats" :key="stat.key">
                                            <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-white p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-start sm:p-6">
                                                <div class="grid flex-1 grid-cols-1 gap-4 sm:grid-cols-3">
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Label</label>
                                                        <input type="text" :name="`home_hero_stats[${index}][label]`" x-model="stat.label" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                    </div>
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Value</label>
                                                        <input type="text" :name="`home_hero_stats[${index}][value]`" x-model="stat.value" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                    </div>
                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Suffix</label>
                                                        <input type="text" :name="`home_hero_stats[${index}][suffix]`" x-model="stat.suffix" placeholder="+" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                                    </div>
                                                </div>
                                                <button type="button" @click="removeHeroStat(index)" class="self-end rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600 sm:self-auto sm:px-6">Delete</button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Defaults and behaviour --}}
                                <div class="space-y-6 border-t border-gray-200 pt-8 dark:border-white/10">
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Default Button Label</label>
                                            <input
                                                type="text"
                                                name="home_hero_button_label"
                                                value="{{ old('home_hero_button_label', $settings['home_hero_button_label'] ?? '') }}"
                                                placeholder="{{ __('site.home.hero_get_quote') }}"
                                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                            />
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Used by any slide that does not name its own button.</p>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Default Button Link</label>
                                            <input
                                                type="text"
                                                name="home_hero_button_url"
                                                value="{{ old('home_hero_button_url', $settings['home_hero_button_url'] ?? '') }}"
                                                placeholder="/quote"
                                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                            />
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Advance slides automatically</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Every seven seconds, pausing on hover, on focus and on a hidden tab. Switched off, the visitor drives — and anyone who has reduced motion set in their system never sees it move at all.</p>
                                        </div>
                                        <label class="group inline-flex shrink-0 cursor-pointer items-center">
                                            <input type="checkbox" name="home_hero_autoplay" value="1" {{ old('home_hero_autoplay', $settings['home_hero_autoplay'] ?? true) ? 'checked' : '' }} class="peer sr-only" />
                                            <div class="peer h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-gray-900 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full dark:bg-white/10 dark:peer-checked:bg-white dark:after:bg-gray-400"></div>
                                            <span class="ml-3 text-xs font-bold tracking-widest text-gray-500 uppercase dark:text-gray-400">Autoplay</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fact tiles --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Fact Tiles</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Up to four. Replaces the rating counters until real reviews back them.</p>
                            </div>
                            <button
                                type="button"
                                @click="factTiles.length < 4 && addFactTile()"
                                class="text-sm font-medium text-gray-900 hover:underline dark:text-white"
                            >
                                + Add Tile
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(tile, index) in factTiles" :key="index">
                                <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-start sm:p-6">
                                    <div class="grid flex-1 grid-cols-1 gap-4 sm:grid-cols-3">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Label</label>
                                            <input type="text" :name="`home_fact_tiles[${index}][label]`" x-model="tile.label" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Value</label>
                                            <input type="text" :name="`home_fact_tiles[${index}][value]`" x-model="tile.value" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Suffix</label>
                                            <input type="text" :name="`home_fact_tiles[${index}][suffix]`" x-model="tile.suffix" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                        </div>
                                    </div>
                                    <button type="button" @click="removeFactTile(index)" class="self-end rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600 sm:self-auto sm:px-6">Delete</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Warranty --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Warranty Band</h4>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Heading</label>
                            <input
                                type="text"
                                name="home_warranty_heading"
                                value="{{ old('home_warranty_heading', $settings['home_warranty_heading']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea
                                name="home_warranty_text"
                                rows="3"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            >{{ old('home_warranty_text', $settings['home_warranty_text']) }}</textarea>
                        </div>

                        <div class="space-y-4 border-t border-gray-100 pt-6 dark:border-white/10">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Up to three highlight cards.</p>
                                <button type="button" @click="warrantyHighlights.length < 3 && addHighlight()" class="text-sm font-medium text-gray-900 hover:underline dark:text-white">+ Add Highlight</button>
                            </div>
                            <template x-for="(highlight, index) in warrantyHighlights" :key="index">
                                <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-start sm:p-6">
                                    <div class="flex-1 space-y-3">
                                        <input type="text" :name="`home_warranty_highlights[${index}][title]`" x-model="highlight.title" placeholder="Title" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                        <textarea :name="`home_warranty_highlights[${index}][text]`" x-model="highlight.text" rows="2" placeholder="One-line benefit" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5"></textarea>
                                    </div>
                                    <button type="button" @click="removeHighlight(index)" class="self-end rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600 sm:self-auto sm:px-6">Delete</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Urgency band --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Urgency Band</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Sits between services and the process steps, with call and WhatsApp buttons.</p>
                            </div>
                            <label class="group relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="home_urgency_enabled" value="1" {{ $settings['home_urgency_enabled'] ? 'checked' : '' }} class="peer sr-only" />
                                <div class="peer h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-gray-900 peer-focus:outline-none after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-white/10 dark:peer-checked:bg-white dark:after:bg-gray-400"></div>
                                <span class="ml-3 text-xs font-bold tracking-widest text-gray-500 uppercase group-hover:text-gray-900 dark:group-hover:text-white">Active</span>
                            </label>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                            <input
                                type="text"
                                name="home_urgency_text"
                                value="{{ old('home_urgency_text', $settings['home_urgency_text']) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                            />
                        </div>
                    </div>

                    {{-- Call to action bands --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Call-To-Action Bands</h4>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Repeated Band Heading</label>
                                <input type="text" name="home_cta_heading" value="{{ old('home_cta_heading', $settings['home_cta_heading']) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Final CTA Heading</label>
                                <input type="text" name="home_final_cta_heading" value="{{ old('home_final_cta_heading', $settings['home_final_cta_heading']) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Repeated Band Text</label>
                                <textarea name="home_cta_text" rows="3" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white">{{ old('home_cta_text', $settings['home_cta_text']) }}</textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Final CTA Text</label>
                                <textarea name="home_final_cta_text" rows="3" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white">{{ old('home_final_cta_text', $settings['home_final_cta_text']) }}</textarea>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp Pre-Filled Message</label>
                                <input type="text" name="home_whatsapp_message" value="{{ old('home_whatsapp_message', $settings['home_whatsapp_message']) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white" />
                                <p class="text-xs text-gray-500 dark:text-gray-400">Attached to every WhatsApp link on the site, including the bubble and the mobile bar.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Where we fit --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Where We Fit</h4>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Service Area</label>
                                <textarea name="home_service_area" rows="3" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white">{{ old('home_service_area', $settings['home_service_area']) }}</textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Coverage</label>
                                <textarea name="home_mobile_coverage" rows="3" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white">{{ old('home_mobile_coverage', $settings['home_mobile_coverage']) }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400">The workshop address and directions link come from General and Social settings.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Process steps --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Process Steps</h4>
                            <button type="button" @click="processSteps.length < 4 && addStep()" class="text-sm font-medium text-gray-900 hover:underline dark:text-white">+ Add Step</button>
                        </div>
                        <template x-for="(step, index) in processSteps" :key="index">
                            <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-start sm:p-6">
                                <div class="flex-1 space-y-3">
                                    <input type="text" :name="`home_process_steps[${index}][title]`" x-model="step.title" placeholder="Title" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                    <textarea :name="`home_process_steps[${index}][text]`" x-model="step.text" rows="2" placeholder="Description" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5"></textarea>
                                </div>
                                <button type="button" @click="removeStep(index)" class="self-end rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600 sm:self-auto sm:px-6">Delete</button>
                            </div>
                        </template>
                    </div>

                    {{-- FAQs --}}
                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Frequently Asked Questions</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rendered as accordions and emitted as FAQPage structured data.</p>
                            </div>
                            <button type="button" @click="faqs.length < 8 && addFaq()" class="text-sm font-medium text-gray-900 hover:underline dark:text-white">+ Add Question</button>
                        </div>
                        <template x-for="(faq, index) in faqs" :key="index">
                            <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-start sm:p-6">
                                <div class="flex-1 space-y-3">
                                    <input type="text" :name="`home_faqs[${index}][question]`" x-model="faq.question" placeholder="Question" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5" />
                                    <textarea :name="`home_faqs[${index}][answer]`" x-model="faq.answer" rows="3" placeholder="Answer" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5"></textarea>
                                </div>
                                <button type="button" @click="removeFaq(index)" class="self-end rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600 sm:self-auto sm:px-6">Delete</button>
                            </div>
                        </template>
                        @error('home_faqs.*.answer')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Announcements Tab -->
                <div
                    x-show="tab === 'announcements'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-8 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Announcements</h3>
                            <p class="text-sm text-gray-500">Manage the scrolling marquee messages.</p>
                        </div>
                        <label class="group relative inline-flex cursor-pointer items-center">
                            <input
                                type="checkbox"
                                name="announcement_active"
                                value="1"
                                {{ $settings['announcement_active'] ? 'checked' : '' }}
                                class="peer sr-only"
                            />
                            <div class="peer h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-gray-900 peer-focus:outline-none after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-white/10 dark:peer-checked:bg-white dark:after:bg-gray-400"></div>
                            <span class="ml-3 text-xs font-bold tracking-widest text-gray-500 uppercase transition-colors group-hover:text-gray-900 dark:group-hover:text-white">Active</span>
                        </label>
                    </div>

                    <div class="space-y-6 border-t border-gray-100 pt-8 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase">Marquee Content</h4>
                            <button
                                type="button"
                                @click="addAnnouncement()"
                                class="text-sm font-medium text-gray-900 hover:underline dark:text-white"
                            >
                                + Add Message
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(announcement, index) in announcements" :key="index">
                                <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-start sm:p-6">
                                    <div class="flex-1 space-y-4">
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div class="space-y-1.5">
                                                <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Message Text</label>
                                                <input
                                                    type="text"
                                                    :name="`announcements[${index}][text]`"
                                                    x-model="announcement.text"
                                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                                    placeholder="Type message..."
                                                />
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Destination</label>
                                                <div class="space-y-2">
                                                    <select
                                                        :name="`announcements[${index}][link]`"
                                                        x-model="announcement.link"
                                                        class="admin-select w-full rounded-xl border border-gray-200 px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:focus:ring-[var(--color-admin-accent)]"
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
                                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                                                        placeholder="Enter URL (e.g. https://...)"
                                                    />
                                                </div>
                                            </div>
                                            <div class="space-y-1.5 md:col-span-2">
                                                <label class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Active Until</label>
                                                <input
                                                    type="datetime-local"
                                                    :name="`announcements[${index}][expires_at]`"
                                                    x-model="announcement.expires_at"
                                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                                />
                                                <p class="text-[11px] text-gray-400">Leave blank to never expire. The message hides automatically after this date &amp; time.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeAnnouncement(index)"
                                        class="inline-flex items-center justify-center gap-2 self-end rounded-full bg-red-500 px-4 py-2 text-xs font-medium text-white shadow-sm transition-all hover:bg-red-600 active:scale-[0.98] sm:self-auto sm:px-6"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                        Delete
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                @if ($envConfig !== [])
                <!-- System Config Tab -->
                <div
                    x-show="tab === 'system_config'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                    style="display: none"
                >
                    <div class="mb-6 space-y-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Config</h3>
                        <p class="text-sm text-gray-500">
                            Allowlisted environment variables. Secret values are never shown here.
                        </p>
                    </div>

                    <div class="space-y-4">
                        @foreach ($envConfig as $key => $value)
                            <div class="space-y-2" x-data="{ editing: false }">
                                <label class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">{{ $key }}</label>
                                <input
                                    type="text"
                                    name="env[{{ $key }}]"
                                    value="{{ old('env.'.$key, $value) }}"
                                    :readonly="! editing"
                                    @click="editing = true"
                                    @click.away="editing = false"
                                    :class="editing
                                        ? 'bg-white dark:bg-white/5 border-gray-900 dark:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white'
                                        : 'bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 cursor-pointer text-gray-500'"
                                    class="w-full rounded-xl border px-4 py-2.5 text-sm transition-all outline-none"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="border-t border-gray-100 pt-6 dark:border-white/10">
                    <button
                        type="submit"
                        class="rounded-full bg-gray-900 px-6 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Save All Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
