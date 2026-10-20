{{--
    Public theme toggle: Light / Dark / System.
    State comes from window.highblossomTheme (see partials/theme-script); Alpine only
    drives the popup. Icons: sun = light, moon = dark, monitor = system.
--}}
<div x-data="themeToggle" class="relative" @keydown.escape="close()">
    <button
        type="button"
        @click="toggle()"
        :aria-expanded="open ? 'true' : 'false'"
        aria-haspopup="true"
        :aria-label="'Colour theme: ' + labelFor(mode) + '. Change theme'"
        class="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full border border-site-line-strong bg-site-fill p-2.5 text-site-muted transition-colors hover:border-site-accent/40 hover:text-site-text focus:outline-none focus-visible:ring-2 focus-visible:ring-site-accent/60"
    >
        {{-- Icon mirrors the chosen mode: sun = light, moon = dark, monitor = system --}}
        <svg x-show="mode === 'light'" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2M5.22 5.22l1.42 1.42m10.72 10.72l1.42 1.42M3 12h2m14 0h2M5.22 18.78l1.42-1.42M17.36 6.64l1.42-1.42M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg x-show="mode === 'dark'" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
        </svg>
        <svg x-show="mode === 'system'" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.outside="close()"
        role="radiogroup"
        aria-label="Colour theme"
        class="absolute right-0 z-50 mt-2 w-40 overflow-hidden rounded-xl border border-site-line-strong bg-site-surface p-1 shadow-site-raised"
    >
        <template x-for="(option, index) in options" :key="option">
            <button
                type="button"
                role="radio"
                :aria-checked="mode === option ? 'true' : 'false'"
                :tabindex="mode === option ? 0 : -1"
                :data-index="index"
                @click="choose(option)"
                @keydown="onKeydown($event, index)"
                class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors"
                :class="mode === option ? 'bg-site-accent-soft text-site-text' : 'text-site-muted hover:bg-site-fill-hover hover:text-site-text'"
            >
                <svg x-show="option === 'light'" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2M5.22 5.22l1.42 1.42m10.72 10.72l1.42 1.42M3 12h2m14 0h2M5.22 18.78l1.42-1.42M17.36 6.64l1.42-1.42M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="option === 'dark'" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                </svg>
                <svg x-show="option === 'system'" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span x-text="labelFor(option)"></span>
            </button>
        </template>
    </div>
</div>
