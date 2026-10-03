<x-layouts::site title="Highblossom | Precision Automotive Glass">
    <!-- Hero Section - Cinematic Dark -->
    <header id="page-hero" class="relative flex min-h-dvh flex-col items-center justify-center overflow-hidden bg-[#0A0A0F]">
        <style>
            @media (prefers-reduced-motion: reduce) {
                .animate-fade-up,
                .animate-pulse {
                    animation: none !important;
                    transition: none !important;
                }
            }
        </style>
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            @php $heroImage = $featuredGalleryImages->first()?->image_url; @endphp
            <img
                alt="{{ $featuredGalleryImages->first()?->title ?? 'Automotive glass installation in progress' }}"
                class="h-full w-full object-cover opacity-40"
                src="{{ $heroImage ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1920&q=80' }}"
                width="1920"
                height="1080"
                fetchpriority="high"
                loading="eager"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-[#0A0A0F] via-[#0A0A0F]/80 to-[#0A0A0F]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0F] via-transparent to-[#0A0A0F]/50"></div>
            <div class="grain-overlay pointer-events-none absolute inset-0" aria-hidden="true"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 mx-auto flex w-full max-w-[1400px] flex-grow items-center justify-center px-4 pt-32 pb-12 text-center sm:px-6 sm:pt-40 lg:px-8">
            <div class="mx-auto max-w-4xl">
                {{-- Trust Badge --}}
                <div class="animate-fade-up mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 sm:mb-8">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-[#DC2626]"></span>
                    <span class="text-xs font-medium text-[#FAFAFA] sm:text-sm">{{ __('site.home.hero_trust_badge') }}</span>
                </div>

                {{-- Animated Headline - Focus Blur Resolve --}}
                <h1 id="hero-heading" class="sr-only">{{ __('site.home.hero_headline_fallback') }}</h1>
                <div
                    id="hero-headline-container"
                    aria-labelledby="hero-heading"
                    class="font-headline mx-auto mb-4 text-2xl leading-[1.2] font-bold tracking-tight break-words hyphens-auto text-[#FAFAFA] sm:mb-6 sm:text-4xl sm:leading-[1.1] md:text-5xl lg:text-6xl"
                    style="perspective: 900px; min-height: 2.4em"
                ><span data-hero-seed>{{ __('site.home.hero_headline_animated')[0] ?? __('site.home.hero_headline_fallback') }}</span></div>

                {{-- Subhead --}}
                <p
                    class="animate-fade-up mx-auto mb-8 max-w-2xl text-base leading-relaxed text-[#FAFAFA] sm:mb-10 sm:text-lg md:text-xl"
                    style="animation-delay: 200ms"
                >
                    {{ __('site.home.hero_subheadline') }}
                </p>

                {{-- CTAs --}}
                <div
                    class="animate-fade-up flex flex-col items-center justify-center gap-4 sm:flex-row"
                    style="animation-delay: 300ms"
                >
                    <a href="{{ route('quote') }}" class="btn-premium-md glow-red-subtle">
                        <span>{{ __('site.home.hero_get_quote') }}</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"
                            ></path>
                        </svg>
                    </a>
                    <a
                        href="{{ route('gallery') }}"
                        class="btn-glass px-8 py-4 text-lg"
                    >
                        <span>{{ __('site.home.hero_view_work') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div
            x-data="{
                activeCard: null,
                steps: [
                    {
                        title: 'Free Quote',
                        description: 'Request instant quote online or call',
                        details:
                            'Get a free, no-obligation quote within minutes. Simply provide your vehicle details and glass type, or call our team directly for immediate assistance.',
                        icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>`,
                    },
                    {
                        title: 'Schedule',
                        description: 'Choose convenient appointment time',
                        details:
                            'Select a time that works for you. We offer flexible scheduling including same-day service for urgent repairs to get you back on the road.',
                        icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>`,
                    },
                    {
                        title: 'Mobile Service',
                        description: 'We come to your location',
                        details:
                            'Our mobile technicians come to your home or office. Fully equipped vehicles for on-site glass replacement and high-precision calibration.',
                        icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z\'></path><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 11a3 3 0 11-6 0 3 3 0 016 0z\'></path></svg>`,
                    },
                    {
                        title: 'Quality Check',
                        description: 'Final inspection and warranty',
                        details:
                            'Every installation passes rigorous safety and quality inspections. Backed by our lifetime workmanship warranty for complete peace of mind.',
                        icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z\'></path></svg>`,
                    },
                ],
            }"
            class="relative z-10 w-full border-t border-white/5 bg-[#0A0A0F]/50 py-6 backdrop-blur-sm md:py-6"
        >
            <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-start gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-4">
                    <template x-for="(step, index) in steps" :key="index">
                        <button
                            type="button"
                            @click="activeCard = index"
                            class="glass-card group animate-fade-up cursor-pointer rounded-xl p-4 transition-all duration-300 hover:scale-[1.03] hover:shadow-[#DC2626]/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60 active:scale-[0.97] md:p-6"
                            :style="`animation-delay: ${index * 100}ms`"
                        >
                            <div class="flex items-start justify-between">
                                <div
                                    class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20"
                                    x-html="step.icon"
                                ></div>
                                <svg class="h-4 w-4 text-[#A1A1AA] opacity-50 transition-opacity group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <h3 class="mb-1 text-sm font-semibold text-[#FAFAFA] md:text-base" x-text="step.title"></h3>
                            <p
                                class="line-clamp-1 text-xs leading-relaxed text-[#A1A1AA] md:text-sm"
                                x-text="step.description"
                            ></p>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Modal Overlay --}}
            <template x-teleport="body">
                <div
                    x-show="activeCard !== null"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0A0A0F]/80 p-4 backdrop-blur-md"
                >
                    <div
                        x-show="activeCard !== null"
                        @click.away="activeCard = null"
                        @keydown.escape.window="activeCard = null"
                        x-transition:enter="transition cubic-bezier(0.32, 0.72, 0, 1) duration-500"
                        x-transition:enter-start="opacity-0 scale-95 translateY(20px)"
                        x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="modal-title"
                        aria-describedby="modal-desc"
                        x-ref="dialog"
                        tabindex="-1"
                        x-effect="activeCard !== null && $nextTick(() =&gt; $refs.dialog.focus())"
                        class="glass-card w-full max-w-lg overflow-hidden rounded-2xl border border-white/10 shadow-2xl shadow-black/50"
                    >
                        <div class="p-8">
                            <div class="mb-6 flex items-start justify-between">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#DC2626]/10"
                                    x-html="
                                        activeCard !== null ? steps[activeCard].icon.replace('w-5 h-5', 'w-7 h-7') : ''
                                    "
                                ></div>
                                <button
                                    @click="activeCard = null"
                                    aria-label="Close"
                                    type="button"
                                    class="rounded-full p-2 text-[#A1A1AA] transition-colors hover:bg-white/5 hover:text-[#FAFAFA] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                                >
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <h2
                                id="modal-title"
                                class="font-headline mb-2 text-2xl font-bold text-[#FAFAFA]"
                                x-text="activeCard !== null ? steps[activeCard].title : ''"
                            ></h2>
                            <p
                                id="modal-desc"
                                class="mb-6 font-medium text-[#DC2626]"
                                x-text="activeCard !== null ? steps[activeCard].description : ''"
                            ></p>

                            <div class="space-y-4">
                                <p
                                    class="text-lg leading-relaxed text-[#A1A1AA]"
                                    x-text="activeCard !== null ? steps[activeCard].details : ''"
                                ></p>
                            </div>

                            <div class="mt-8 flex gap-4">
                                <button
                                    @click="activeCard = null"
                                    class="flex-1 rounded-xl bg-[#DC2626] py-4 font-bold text-[#FAFAFA] transition-all hover:bg-[#B91C1C] active:scale-[0.98]"
                                >
                                    Got it
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </header>

    <!-- Services Preview Section -->
    <section id="services" class="bg-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="mb-16 flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        {{ __('site.home.services_section_label') }}
                    </div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                        {{ __('site.home.services_title') }}
                    </h2>
                </div>
                <a
                    href="{{ route('services') }}"
                    class="btn-glass px-8 py-4 text-lg"
                >
                    <span>{{ __('site.home.services_view_all') }}</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"
                        ></path>
                    </svg>
                </a>
            </div>

            {{-- Services Grid: lead card + compact rows (asymmetric) --}}
            <div class="grid gap-6 lg:grid-cols-12">
                {{-- Windscreens — lead --}}
                <div class="glass-card glass-card-hover group flex flex-col rounded-3xl p-8 lg:col-span-5 lg:p-12">
                    <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-8 w-8 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="font-headline mb-4 text-2xl font-bold tracking-tight text-[#FAFAFA] lg:text-3xl">
                        {{ __('site.home.windscreens') }}
                    </h3>
                    <p class="mb-8 max-w-prose text-base leading-relaxed text-[#A1A1AA]">
                        {{ __('site.home.windscreens_description') }}
                    </p>
                    <a
                        href="{{ route('services') }}"
                        class="mt-auto inline-flex items-center gap-2 text-sm font-semibold text-[#DC2626] transition-all group-hover:gap-3"
                    >
                        {{ __('site.learn_more') }}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                {{-- Side & Rear / Heavy Machinery / Fleet — compact rows --}}
                <div class="flex flex-col gap-4 lg:col-span-7">
                    @foreach ([
                        ['key' => 'side_rear', 'path' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'],
                        ['key' => 'heavy_machinery', 'path' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ['key' => 'fleet_services', 'path' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    ] as $service)
                        <div class="glass-card glass-card-hover group flex flex-1 items-start gap-5 rounded-2xl p-5 lg:p-6">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                                <svg class="h-6 w-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['path'] }}"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-headline mb-1 text-lg font-bold text-[#FAFAFA]">
                                    {{ __('site.home.' . $service['key']) }}
                                </h3>
                                <p class="text-sm leading-relaxed text-[#A1A1AA]">
                                    {{ __('site.home.' . $service['key'] . '_description') }}
                                </p>
                            </div>
                            <a
                                href="{{ route('services') }}"
                                aria-label="{{ __('site.learn_more') }} — {{ __('site.home.' . $service['key']) }}"
                                class="self-center text-[#DC2626] opacity-60 transition-all group-hover:translate-x-1 group-hover:opacity-100"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Teaser Section -->
    <section id="gallery" class="bg-[#121218] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="mb-12 flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        {{ __('site.home.gallery_section_label') }}
                    </div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                        {{ __('site.home.gallery_title') }}
                    </h2>
                </div>
                <a
                    href="{{ route('gallery') }}"
                    class="btn-glass px-8 py-4 text-lg"
                >
                    <span>{{ __('site.home.gallery_view_full') }}</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"
                        ></path>
                    </svg>
                </a>
            </div>

            {{-- Gallery Grid --}}
            @if ($featuredGalleryImages->count() > 0)
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {{-- Featured Large Item (first item) --}}
                    @if ($featuredGalleryImages->first())
                        <div class="group relative overflow-hidden rounded-2xl lg:col-span-2 lg:row-span-2">
                            <img
                                alt="{{ $featuredGalleryImages->first()->title }}"
                                class="h-full min-h-[400px] w-full object-cover transition-transform duration-700 group-hover:scale-105 lg:min-h-full"
                                src="{{ $featuredGalleryImages->first()->image_url }}"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-[#0A0A0F]/30 to-transparent"></div>
                            <div class="absolute right-0 bottom-0 left-0 p-6 lg:p-8">
                                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/20 px-3 py-1">
                                    <span class="text-xs font-semibold text-[#DC2626] uppercase">{{ str_replace('_', ' ', $featuredGalleryImages->first()->category->name) }}</span>
                                </div>
                                <h3 class="font-headline mb-2 text-xl font-bold text-[#FAFAFA] lg:text-2xl">
                                    {{ $featuredGalleryImages->first()->title }}
                                </h3>
                                @if ($featuredGalleryImages->first()->description)
                                    <p class="text-sm text-[#FAFAFA]">
                                        {{ $featuredGalleryImages->first()->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Small Items (remaining items) --}}
                    @foreach ($featuredGalleryImages->slice(1, 2) as $image)
                        <div class="group relative overflow-hidden rounded-2xl">
                            <img
                                alt="{{ $image->title }}"
                                class="h-64 w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                src="{{ $image->image_url }}"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent"></div>
                            <div class="absolute right-0 bottom-0 left-0 p-6">
                                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1">
                                    <span class="text-xs font-semibold text-[#FAFAFA] uppercase">{{ str_replace('_', ' ', $image->category->name) }}</span>
                                </div>
                                <h3 class="font-headline text-lg font-bold text-[#FAFAFA]">{{ $image->title }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card rounded-2xl p-12 text-center">
                    <div class="mb-4 text-[#FAFAFA]">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <p class="text-[#FAFAFA]">{{ __('site.home.gallery_no_items') }}</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="bg-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto mb-16 max-w-2xl text-center">
                <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    {{ __('site.home.why_choose_label') }}
                </div>
                <h2 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                    {{ __('site.home.why_choose_title') }}
                </h2>
            </div>

            {{-- Process Section --}}
            <div class="mb-24 grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#DC2626]/10 text-2xl font-bold text-[#DC2626]">
                        1
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-[#FAFAFA]">Consultation</h3>
                    <p class="text-sm text-[#FAFAFA]">
                        We assess your vehicle needs with precision and offer a transparent, free, no-obligation quote.
                    </p>
                </div>
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#DC2626]/10 text-2xl font-bold text-[#DC2626]">
                        2
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-[#FAFAFA]">Precision Fabrication</h3>
                    <p class="text-sm text-[#FAFAFA]">
                        Our expert technicians prepare your high-quality glass using state-of-the-art tools and
                        materials.
                    </p>
                </div>
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#DC2626]/10 text-2xl font-bold text-[#DC2626]">
                        3
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-[#FAFAFA]">Quality Installation</h3>
                    <p class="text-sm text-[#FAFAFA]">
                        We provide expert mobile installation, ensuring your vehicle's safety with a lifetime
                        workmanship warranty.
                    </p>
                </div>
            </div>

            {{-- Trusted By Section --}}
            @php $partners = \App\Models\Partner::where('is_active', true)->orderBy('order')->get(); @endphp
            @if ($partners->isNotEmpty())
                <div class="mt-32 border-t border-white/5 pt-20">
                    <p class="mb-12 text-center text-[10px] font-bold tracking-[0.2em] text-[#A1A1AA] uppercase">
                        Proudly Trusted By
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-x-16 gap-y-12">
                        @foreach ($partners as $partner)
                            <div class="group relative">
                                <img
                                    src="{{ $partner->logo_url }}"
                                    alt="{{ $partner->name }}"
                                    class="h-8 object-contain opacity-50 grayscale transition-all duration-500 group-hover:scale-105 group-hover:opacity-100 group-hover:grayscale-0 md:h-10"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-[#121218] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="mb-12">
                <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    {{ __('site.home.testimonials_label') }}
                </div>
                <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                    {{ __('site.home.testimonials_title') }}
                </h2>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
{{-- Quote wall --}}
                <div class="grid gap-6 md:grid-cols-2 lg:col-span-2">
                    @forelse ($otherTestimonials as $testimonial)
                        <div class="glass-card flex flex-col rounded-2xl p-6 lg:p-8">
                            <div class="mb-4 flex gap-1" role="img" aria-label="{{ $testimonial->rating }} out of 5 stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $testimonial->rating ? 'text-[#DC2626]' : 'text-[#3F3F46]' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <blockquote class="mb-6 flex-1 text-base leading-relaxed text-[#FAFAFA]">"{{ $testimonial->content }}"</blockquote>
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#DC2626]/20">
                                    <span class="text-sm font-bold text-[#DC2626]">{{ substr($testimonial->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-[#FAFAFA]">{{ $testimonial->name }}</div>
                                    @if ($testimonial->role)
                                        <div class="text-xs text-[#A1A1AA]">{{ $testimonial->role }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card rounded-2xl p-8 text-center md:col-span-2">
                            <div class="mb-4 text-[#FAFAFA]">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-[#FAFAFA]">{{ __('site.home.testimonials_no_testimonials') }}</p>
                        </div>
                    @endforelse
                </div>
                {{-- Right Column: Featured Testimonial + Stats --}}
                <div class="space-y-6">
                    {{-- Featured Testimonial Card --}}
                    @if ($featuredTestimonial)
                        <div class="glass-card rounded-2xl p-6">
                            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/20 px-3 py-1">
                                <svg class="h-4 w-4 text-[#DC2626]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-xs font-semibold text-[#DC2626] uppercase">{{ __('site.home.testimonials_featured') }}</span>
                            </div>

                            <div class="mb-3 flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg
                                        class="w-4 h-4 {{ $i <= $featuredTestimonial->rating ? 'text-[#DC2626]' : 'text-[#D4D4D8]' }}"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>

                            <blockquote class="mb-4 line-clamp-3 text-sm leading-relaxed text-[#FAFAFA]">
                                "{{ $featuredTestimonial->content }}"
                            </blockquote>

                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#DC2626]/20">
                                    <span class="font-bold text-[#DC2626]">{{ substr($featuredTestimonial->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-[#FAFAFA]">
                                        {{ $featuredTestimonial->name }}
                                    </div>
                                    @if ($featuredTestimonial->role)
                                        <div class="text-xs text-[#FAFAFA]">{{ $featuredTestimonial->role }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Stats Grid --}}
                    @php
                        $ratings = $otherTestimonials->pluck('rating')->reject(fn ($r) => $r === null);
                        if ($featuredTestimonial?->rating) {
                            $ratings->push($featuredTestimonial->rating);
                        }
                        $avgRating = $ratings->isNotEmpty() ? number_format($ratings->avg(), 1) : null;
                    @endphp
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">
                                {{ $otherTestimonials->count() + ($featuredTestimonial ? 1 : 0) }}+
                            </div>
                            <div class="text-xs text-[#FAFAFA]">{{ __('site.home.happy_clients') }}</div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">{{ $avgRating ?? '4.8' }}</div>
                            <div class="text-xs text-[#FAFAFA]">{{ __('site.home.average_rating') }}</div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">98%</div>
                            <div class="text-xs text-[#FAFAFA]">{{ __('site.home.recommend_us') }}</div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">24h</div>
                            <div class="text-xs text-[#FAFAFA]">{{ __('site.home.response_time') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Section -->
    <section class="bg-gradient-to-b from-[#121218] to-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="glass-card relative overflow-hidden rounded-3xl p-12 text-center lg:p-16">
                {{-- Background Glow --}}
                <div class="absolute top-1/2 left-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#DC2626]/10 blur-[100px]"></div>

                <div class="relative z-10">
                    <h2 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                        Ready for Crystal<br />Clear Vision?
                    </h2>
                    <p class="mx-auto mb-10 max-w-2xl text-lg text-[#FAFAFA]">
                        Get a free quote today. Our team is ready to help with all your automotive glass needs.
                    </p>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <a href="{{ route('quote') }}" class="btn-premium glow-red px-8 py-4 text-lg">
                            <span>Get Your Free Quote</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                ></path>
                            </svg>
                        </a>
                        <a href="{{ route('bookings.create') }}" class="btn-glass px-8 py-4 text-lg">
                            <span>Book an Appointment</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                        </a>
                        @if ($primaryPhone)
                            <a href="tel:{{ $primaryPhone }}" class="btn-glass px-8 py-4 text-lg">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    ></path>
                                </svg>
                                <span>{{ $primaryPhone }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Info Section -->
    <section class="border-t border-white/5 bg-[#0A0A0F] py-12">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#DC2626]/10">
                        <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            ></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-[#FAFAFA]">{{ $companyAddress }}</div>
                        <div class="text-xs text-[#FAFAFA]">
                            @php
                                try {
                                    $dayOrder = ['monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun'];
                                    $openDays = [];
                                    $closedDays = [];

                                    if (isset($workingHours) && is_array($workingHours)) {
                                        foreach ($dayOrder as $key => $abbr) {
                                            if (isset($workingHours[$key]) && ! ($workingHours[$key]['is_closed'] ?? false)) {
                                                $format = ($timeFormatDisplay ?? '12') === '24' ? 'H:i' : 'g:i A';
                                                $time = date($format, strtotime($workingHours[$key]['open'] ?? '07:30')).' – '.date($format, strtotime($workingHours[$key]['close'] ?? '17:00'));
                                                $openDays[$key] = ['abbr' => $abbr, 'time' => $time];
                                            } else {
                                                $closedDays[] = $abbr;
                                            }
                                        }

                                        // Group consecutive days with same hours
                                        $groupedDays = [];
                                        $currentGroup = [];
                                        $currentTime = null;
                                        $prevKey = null;
                                        $dayKeys = array_keys($dayOrder);

                                        foreach ($dayKeys as $key) {
                                            if (! isset($openDays[$key])) {
                                                continue;
                                            }

                                            $dayData = $openDays[$key];
                                            $time = $dayData['time'];

                                            // Check if consecutive and same time
                                            $isConsecutive = $prevKey !== null && array_search($key, $dayKeys) === array_search($prevKey, $dayKeys) + 1;

                                            if ($time === $currentTime && $isConsecutive) {
                                                $currentGroup[] = $dayData['abbr'];
                                            } else {
                                                if (! empty($currentGroup)) {
                                                    $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                                }
                                                $currentGroup = [$dayData['abbr']];
                                                $currentTime = $time;
                                            }
                                            $prevKey = $key;
                                        }

                                        if (! empty($currentGroup)) {
                                            $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                        }

                                        // Modern professional format
                                        $formatted = [];
                                        foreach ($groupedDays as $group) {
                                            $dayLabel = count($group['days']) > 2
                                                ? $group['days'][0].'–'.end($group['days'])
                                                : implode(' & ', $group['days']);
                                            $formatted[] = $dayLabel.' · '.$group['time'];
                                        }

                                        if (! empty($closedDays)) {
                                            $closedLabel = count($closedDays) > 2
                                                ? $closedDays[0].'–'.end($closedDays)
                                                : implode(' & ', $closedDays);
                                            $formatted[] = $closedLabel.' · Closed';
                                        }

                                        echo implode(' | ', $formatted);
                                    } else {
                                        echo 'Mon–Fri · 7:30 AM – 5:00 PM | Sat · 8:00 AM – 1:00 PM | Sun · Closed';
                                    }
                                } catch (\Exception $e) {
                                    echo 'Mon–Fri · 7:30 AM – 5:00 PM | Sat · 8:00 AM – 1:00 PM | Sun · Closed';
                                }
                            @endphp
                        </div>
                    </div>
                </div>

                @if ($primaryPhone)
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#DC2626]/10">
                            <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-[#FAFAFA]">{{ $primaryPhone }}</div>
                            <div class="text-xs text-[#FAFAFA]">Call for immediate assistance</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Soft Blur In Animation Script --}}
    <script>
        (function () {
            const phrases = @json(__('site.home.hero_headline_animated'));
            const container = document.getElementById('hero-headline-container');

            if (!container || !phrases || phrases.length === 0) return;

            // Respect reduced motion: keep the server-rendered headline static.
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            // Animation parameters from soft-blur-in spec
            const ENTER_DURATION = 648; // scaled from 900ms * 0.72
            const ENTER_STAGGER = 18; // scaled from 25ms * 0.72
            const EXIT_DURATION = 432; // scaled from 600ms * 0.72
            const EXIT_STAGGER = 11; // scaled from 15ms * 0.72
            const HOLD_MS = 550;
            const GAP_MS = 320;
            const MICRO_DELAY_MS = 0;
            const Y_TRAVEL_MULTIPLIER = 0.58;
            const INITIAL_DELAY_MS = Math.random() * 400;

            const ENTER_EASING = 'cubic-bezier(0.22, 1, 0.36, 1)';
            const EXIT_EASING = 'cubic-bezier(0.64, 0, 0.78, 0)';

            let currentIndex = 0;
            let activeAnimations = [];
            let activeTimeout = null;
            let isRunning = true;

            function createPhrase(text) {
                const title = document.createElement('h1');
                title.className = 'text-animation-title';
                title.style.cssText =
                    'display: inline-block; transform-style: preserve-3d; backface-visibility: hidden; will-change: transform, opacity, filter; width: 100%;';

                // Split text into individual characters for per-character animation
                const characters = Array.from(text);
                const units = [];

                characters.forEach((char, index) => {
                    const unit = document.createElement('span');
                    unit.className = 'text-animation-unit';
                    unit.textContent = char;
                    unit.style.cssText =
                        'display: inline-block; backface-visibility: hidden; will-change: transform, opacity, filter; white-space: pre; transform-origin: 50% 55%;';
                    title.appendChild(unit);
                    units.push(unit);
                });

                return { title, units };
            }

            function applyEnterFrom(element) {
                const yStart = 16 * Y_TRAVEL_MULTIPLIER;
                element.style.opacity = '0';
                element.style.transform = `translate3d(0, ${yStart}px, 0)`;
                element.style.filter = 'blur(12px)';
            }

            function applyEnterTo(element) {
                element.style.opacity = '1';
                element.style.transform = 'translate3d(0, 0, 0)';
                element.style.filter = 'blur(0px)';
            }

            function applyExitFrom(element) {
                element.style.opacity = '1';
                element.style.transform = 'translate3d(0, 0, 0)';
                element.style.filter = 'blur(0px)';
            }

            function applyExitTo(element) {
                const yEnd = -16 * Y_TRAVEL_MULTIPLIER;
                element.style.opacity = '0';
                element.style.transform = `translate3d(0, ${yEnd}px, 0)`;
                element.style.filter = 'blur(12px)';
            }

            async function enterAnimation(elements) {
                const promises = elements.map((element, index) => {
                    const delay = index * ENTER_STAGGER;
                    const yStart = 16 * Y_TRAVEL_MULTIPLIER;
                    const keyframes = [
                        {
                            opacity: 0,
                            transform: `translate3d(0, ${yStart}px, 0)`,
                            filter: 'blur(12px)',
                        },
                        {
                            opacity: 1,
                            transform: 'translate3d(0, 0, 0)',
                            filter: 'blur(0px)',
                        },
                    ];

                    const animation = element.animate(keyframes, {
                        delay: delay,
                        duration: ENTER_DURATION,
                        easing: ENTER_EASING,
                        fill: 'forwards',
                    });

                    activeAnimations.push(animation);
                    return animation.finished;
                });

                await Promise.all(promises);
                activeAnimations = [];
            }

            async function exitAnimation(elements) {
                const promises = elements.map((element, index) => {
                    const delay = index * EXIT_STAGGER;
                    const yEnd = -16 * Y_TRAVEL_MULTIPLIER;
                    const keyframes = [
                        {
                            opacity: 1,
                            transform: 'translate3d(0, 0, 0)',
                            filter: 'blur(0px)',
                        },
                        {
                            opacity: 0,
                            transform: `translate3d(0, ${yEnd}px, 0)`,
                            filter: 'blur(12px)',
                        },
                    ];

                    const animation = element.animate(keyframes, {
                        delay: delay,
                        duration: EXIT_DURATION,
                        easing: EXIT_EASING,
                        fill: 'forwards',
                    });

                    activeAnimations.push(animation);
                    return animation.finished;
                });

                await Promise.all(promises);
                activeAnimations = [];
            }

            function sleep(ms) {
                return new Promise((resolve) => {
                    activeTimeout = setTimeout(resolve, ms);
                });
            }

            async function runAnimationLoop() {
                if (!isRunning) return;

                // Initial delay
                await sleep(INITIAL_DELAY_MS);

                // Create and animate first phrase, replacing the static seed
                let { title, units } = createPhrase(phrases[currentIndex]);
                units.forEach((unit) => applyEnterFrom(unit));
                container.querySelector('[data-hero-seed]')?.remove();
                container.appendChild(title);
                await enterAnimation(units);

                // Loop
                while (isRunning) {
                    await sleep(HOLD_MS);

                    if (!isRunning) break;

                    // Exit current phrase
                    await exitAnimation(units);

                    if (!isRunning) break;

                    // Prepare next phrase
                    currentIndex = (currentIndex + 1) % phrases.length;
                    const nextPhrase = createPhrase(phrases[currentIndex]);
                    nextPhrase.units.forEach((unit) => applyEnterFrom(unit));

                    await sleep(MICRO_DELAY_MS);

                    if (!isRunning) break;

                    // Replace and enter next phrase
                    container.replaceChild(nextPhrase.title, title);
                    title = nextPhrase.title;
                    units = nextPhrase.units;
                    await enterAnimation(units);

                    await sleep(GAP_MS);
                }
            }

            // Cleanup on page navigation
            function cleanup() {
                isRunning = false;
                activeAnimations.forEach((animation) => {
                    animation.cancel();
                });
                activeAnimations = [];
                if (activeTimeout) {
                    clearTimeout(activeTimeout);
                    activeTimeout = null;
                }
            }

            // Start animation when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', runAnimationLoop);
            } else {
                runAnimationLoop();
            }

            // Cleanup on page unload
            window.addEventListener('beforeunload', cleanup);
            window.addEventListener('pagehide', cleanup);
        })();
    </script>
    {{-- Mobile Sticky Conversion Bar --}}
    @php
        $waNumber = preg_replace('/\D/', '', (string) ($whatsappDefault ?? ''));
        $stickyContactUrl = $waNumber !== ''
            ? 'https://wa.me/'.$waNumber.'?text='.urlencode('I would like a quote for automotive glass work.')
            : (($primaryPhone ?? '') !== '' ? 'tel:'.preg_replace('/[\s()]/', '', $primaryPhone) : null);
    @endphp
    <div class="h-[88px] md:hidden" aria-hidden="true"></div>
    <div
        id="mobile-sticky-bar"
        class="pointer-events-none fixed bottom-0 left-1/2 z-40 w-full max-w-md -translate-x-1/2 px-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] md:hidden"
        aria-hidden="true"
    >
        <div
            id="mobile-sticky-inner"
            class="flex translate-y-3 items-center gap-2 rounded-full border border-white/10 bg-[#0A0A0F]/85 p-1.5 opacity-0 shadow-2xl shadow-black/50 backdrop-blur-xl transition-all duration-300 ease-out"
        >
            <a
                href="{{ route('quote') }}"
                class="flex flex-[1.4] items-center justify-center gap-2 rounded-full bg-[#DC2626] px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#DC2626]/30 transition-all hover:bg-[#B91C1C] active:scale-[0.98]"
            >
                <span>{{ __('site.home.hero_get_quote') }}</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            @if ($stickyContactUrl)
                <a
                    href="{{ $stickyContactUrl }}"
                    @if ($waNumber !== '') target="_blank" rel="noopener noreferrer" @endif
                    class="flex flex-1 items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-bold text-[#FAFAFA] transition-all hover:border-emerald-400/40 hover:bg-emerald-400/10 active:scale-[0.98]"
                >
                    @if ($waNumber !== '')
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    @else
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    @endif
                    <span>{{ $waNumber !== '' ? 'WhatsApp' : 'Call Us' }}</span>
                </a>
            @endif
        </div>
    </div>

    <script>
        (function () {
            'use strict';

            const bar = document.getElementById('mobile-sticky-bar');
            const inner = document.getElementById('mobile-sticky-inner');
            const hero = document.getElementById('page-hero');
            if (!bar || !inner) return;

            const HIDDEN = ['pointer-events-none', 'opacity-0', 'translate-y-3'];
            const SHOWN = ['pointer-events-auto', 'opacity-100', 'translate-y-0'];

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                inner.classList.remove('duration-300', 'ease-out', 'translate-y-3');
            }

            let heroVisible = true;
            let lastY = window.scrollY;
            let ticking = false;
            let visible = false;

            function setState(show) {
                if (show === visible) return;
                visible = show;
                inner.classList.remove(...(show ? HIDDEN : SHOWN));
                inner.classList.add(...(show ? SHOWN : HIDDEN));
                bar.setAttribute('aria-hidden', show ? 'false' : 'true');
            }

            function evaluate() {
                ticking = false;
                const y = window.scrollY;
                const doc = document.documentElement;
                const nearBottom = y + window.innerHeight >= doc.scrollHeight - 120;

                if (nearBottom) {
                    lastY = y;
                    setState(true);
                    return;
                }
                if (heroVisible) {
                    lastY = y;
                    setState(false);
                    return;
                }

                const delta = y - lastY;
                if (Math.abs(delta) < 6) return;
                lastY = y;
                setState(delta < 0);
            }

            function onScroll() {
                if (!ticking) {
                    ticking = true;
                    requestAnimationFrame(evaluate);
                }
            }

            if (hero && 'IntersectionObserver' in window) {
                new IntersectionObserver(
                    (entries) => {
                        heroVisible = entries[0].isIntersecting;
                        evaluate();
                    },
                    { threshold: 0 }
                ).observe(hero);
            } else {
                heroVisible = false;
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll, { passive: true });
            evaluate();

            window.addEventListener('pagehide', function cleanup() {
                window.removeEventListener('scroll', onScroll);
                window.removeEventListener('resize', onScroll);
                window.removeEventListener('pagehide', cleanup);
            });
        })();
    </script>
</x-layouts::site>
