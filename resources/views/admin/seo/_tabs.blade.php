@php
    $seoTab = $seoTab ?? 'settings';

    // Add a new tab here and the pill, animation and scrolling pick it up automatically.
    $tabs = [
        'settings' => ['label' => 'General Settings', 'route' => 'admin.seo.settings'],
        'static-routes' => ['label' => 'Static Routes', 'route' => 'admin.seo.static-routes'],
    ];
@endphp

<nav
    aria-label="SEO sections"
    class="max-w-full overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
>
    <div
        x-data="{
            storageKey: 'admin-seo-last-tab',
            init() {
                const root = this.$el;
                const pill = this.$refs.pill;
                const active = root.querySelector('[data-tab][aria-current=page]');
                if (! active) return;

                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const place = (el) => {
                    pill.style.width = el.offsetWidth + 'px';
                    pill.style.transform = 'translateX(' + el.offsetLeft + 'px)';
                };

                // Each tab is a normal page load, so remember which tab we came from
                // and slide the pill from there to the current one.
                let from = null;
                try { from = sessionStorage.getItem(this.storageKey); } catch (e) {}
                const previous = from && from !== active.dataset.tab
                    ? root.querySelector('[data-tab=\'' + from + '\']')
                    : null;

                pill.style.transition = 'none';
                place(previous && ! reduceMotion ? previous : active);
                pill.style.opacity = '1';

                if (previous && ! reduceMotion) {
                    pill.getBoundingClientRect(); // force reflow so the start position is painted
                    requestAnimationFrame(() => {
                        pill.style.transition = 'transform 380ms cubic-bezier(0.34, 1.2, 0.64, 1), width 380ms cubic-bezier(0.4, 0, 0.2, 1)';
                        place(active);
                    });
                }

                try { sessionStorage.setItem(this.storageKey, active.dataset.tab); } catch (e) {}

                // Keep the active pill visible when the tab strip scrolls on small screens.
                active.scrollIntoView({ inline: 'center', block: 'nearest' });

                window.addEventListener('resize', () => {
                    pill.style.transition = 'none';
                    place(active);
                });
            },
        }"
        class="relative inline-flex gap-1 rounded-full border border-gray-200 bg-gray-100 p-1 dark:border-white/10 dark:bg-white/5"
    >
        {{-- Sliding pill --}}
        <span
            x-ref="pill"
            aria-hidden="true"
            class="pointer-events-none absolute bottom-1 left-0 top-1 rounded-full bg-gray-900 opacity-0 shadow-sm will-change-transform dark:bg-white"
        ></span>

        @foreach ($tabs as $key => $tab)
            @php $isActive = $seoTab === $key; @endphp
            <a
                href="{{ route($tab['route']) }}"
                data-tab="{{ $key }}"
                @if ($isActive) aria-current="page" @endif
                class="relative z-10 inline-flex min-h-[40px] items-center whitespace-nowrap rounded-full px-4 text-sm font-medium transition-colors duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#0A0A0F] {{ $isActive ? 'text-white dark:text-gray-900' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}"
            >
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</nav>