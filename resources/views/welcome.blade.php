<x-layouts::site title="Highblossom | Precision Automotive Glass">
    <!-- Hero Section - Cinematic Dark -->
    <header class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden bg-[#0A0A0F]">
        {{-- Background Image with Overlay --}}
        <div class="absolute inset-0 z-0">
            <img alt="Premium automotive glass installation" class="w-full h-full object-cover opacity-40"
                src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1920&q=80" loading="eager">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0A0A0F] via-[#0A0A0F]/80 to-[#0A0A0F]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0F] via-transparent to-[#0A0A0F]/50"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 flex-grow flex items-center justify-center w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12 text-center">
            <div class="max-w-4xl mx-auto">
                {{-- Trust Badge --}}
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-6 sm:mb-8 animate-fade-up">
                    <span class="w-2 h-2 rounded-full bg-[#DC2626] animate-pulse"></span>
                    <span class="text-[#FAFAFA] text-xs sm:text-sm font-medium">{{ __('site.home.hero_trust_badge') }}</span>
                </div>

                {{-- Animated Headline - Focus Blur Resolve --}}
                <div id="hero-headline-container" class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight leading-[1.2] sm:leading-[1.1] mb-4 sm:mb-6 mx-auto break-words hyphens-auto"
                    style="perspective: 900px; min-height: 2.4em;">
                </div>

                {{-- Subhead --}}
                <p class="text-base sm:text-lg md:text-xl text-[#FAFAFA] leading-relaxed max-w-2xl mx-auto mb-8 sm:mb-10 animate-fade-up"
                    style="animation-delay: 200ms;">
                    {{ __('site.home.hero_subheadline') }}
                </p>

                {{-- CTAs --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-up" style="animation-delay: 300ms;">
                    <a href="{{ route('quote') }}" class="btn-premium-md glow-red-subtle">
                        <span>{{ __('site.home.hero_get_quote') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="{{ route('gallery') }}" class="btn-ghost-md">
                        <span>{{ __('site.home.hero_view_work') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div x-data="{ 
             activeCard: null,
             steps: [
                {
                    title: 'Free Quote',
                    description: 'Request instant quote online or call',
                    details: 'Get a free, no-obligation quote within minutes. Simply provide your vehicle details and glass type, or call our team directly for immediate assistance.',
                    icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path></svg>`
                },
                {
                    title: 'Schedule',
                    description: 'Choose convenient appointment time',
                    details: 'Select a time that works for you. We offer flexible scheduling including same-day service for urgent repairs to get you back on the road.',
                    icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>`
                },
                {
                    title: 'Mobile Service',
                    description: 'We come to your location',
                    details: 'Our mobile technicians come to your home or office. Fully equipped vehicles for on-site glass replacement and high-precision calibration.',
                    icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z\'></path><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 11a3 3 0 11-6 0 3 3 0 016 0z\'></path></svg>`
                },
                {
                    title: 'Quality Check',
                    description: 'Final inspection and warranty',
                    details: 'Every installation passes rigorous safety and quality inspections. Backed by our lifetime workmanship warranty for complete peace of mind.',
                    icon: `<svg class=\'w-5 h-5 text-[#DC2626]\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z\'></path></svg>`
                }
             ]
        }"
             class="relative z-10 w-full border-t border-white/5 bg-[#0A0A0F]/50 backdrop-blur-sm py-6 md:py-6">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 items-start">
                    <template x-for="(step, index) in steps" :key="index">
                        <div @click="activeCard = index"
                             class="glass-card rounded-xl p-4 md:p-6 cursor-pointer group transition-all duration-300 hover:scale-[1.03] active:scale-[0.97] hover:shadow-[#DC2626]/20 animate-fade-up"
                             :style="`animation-delay: ${index * 100}ms`">
                            <div class="flex items-start justify-between">
                                <div class="w-10 h-10 rounded-lg bg-[#DC2626]/10 flex items-center justify-center mb-3 group-hover:bg-[#DC2626]/20 transition-colors" x-html="step.icon">
                                </div>
                                <svg class="w-4 h-4 text-[#A1A1AA] opacity-50 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-[#FAFAFA] font-semibold text-sm md:text-base mb-1" x-text="step.title"></h3>
                            <p class="text-[#A1A1AA] text-xs md:text-sm leading-relaxed line-clamp-1" x-text="step.description"></p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Modal Overlay --}}
            <template x-teleport="body">
                <div x-show="activeCard !== null" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#0A0A0F]/80 backdrop-blur-md">
                    
                    <div x-show="activeCard !== null"
                         @click.away="activeCard = null"
                         @keydown.escape.window="activeCard = null"
                         x-transition:enter="transition cubic-bezier(0.32, 0.72, 0, 1) duration-500"
                         x-transition:enter-start="opacity-0 scale-95 translateY(20px)"
                         x-transition:enter-end="opacity-100 scale-100 translateY(0)"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="w-full max-w-lg glass-card rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border border-white/10">
                        
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-[#DC2626]/10 flex items-center justify-center" x-html="activeCard !== null ? steps[activeCard].icon.replace('w-5 h-5', 'w-7 h-7') : ''">
                                </div>
                                <button @click="activeCard = null" class="p-2 rounded-full hover:bg-white/5 transition-colors text-[#A1A1AA] hover:text-[#FAFAFA]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <h2 class="text-2xl font-bold text-[#FAFAFA] font-headline mb-2" x-text="activeCard !== null ? steps[activeCard].title : ''"></h2>
                            <p class="text-[#DC2626] font-medium mb-6" x-text="activeCard !== null ? steps[activeCard].description : ''"></p>
                            
                            <div class="space-y-4">
                                <p class="text-[#A1A1AA] text-lg leading-relaxed" x-text="activeCard !== null ? steps[activeCard].details : ''"></p>
                            </div>

                            <div class="mt-8 flex gap-4">
                                <button @click="activeCard = null" class="flex-1 py-4 rounded-xl bg-[#DC2626] hover:bg-[#B91C1C] text-[#FAFAFA] font-bold transition-all active:scale-[0.98]">
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
    <section id="services" class="py-24 lg:py-32 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-16">
                <div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">{{ __('site.home.services_section_label') }}</div>
                    <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight">
                        {{ __('site.home.services_title') }}
                    </h2>
                </div>
                <a href="{{ route('services') }}" class="btn-ghost">
                    <span>{{ __('site.home.services_view_all') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            {{-- Services Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Windscreens --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div
                        class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">{{ __('site.home.windscreens') }}</h3>
                    <p class="text-[#FAFAFA] text-sm leading-relaxed mb-6">
                        {{ __('site.home.windscreens_description') }}
                    </p>
                    <a href="{{ route('services') }}"
                        class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        {{ __('site.learn_more') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                {{-- Side & Rear --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div
                        class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">{{ __('site.home.side_rear') }}</h3>
                    <p class="text-[#FAFAFA] text-sm leading-relaxed mb-6">
                        {{ __('site.home.side_rear_description') }}
                    </p>
                    <a href="{{ route('services') }}"
                        class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        {{ __('site.learn_more') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                {{-- Heavy Machinery --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div
                        class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">{{ __('site.home.heavy_machinery') }}</h3>
                    <p class="text-[#FAFAFA] text-sm leading-relaxed mb-6">
                        {{ __('site.home.heavy_machinery_description') }}
                    </p>
                    <a href="{{ route('services') }}"
                        class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        {{ __('site.learn_more') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                {{-- Fleet Services --}}
                <div class="glass-card glass-card-hover rounded-2xl p-8 group">
                    <div
                        class="w-14 h-14 rounded-xl bg-[#DC2626]/10 flex items-center justify-center mb-6 group-hover:bg-[#DC2626]/20 transition-colors">
                        <svg class="w-7 h-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] font-headline mb-3">{{ __('site.home.fleet_services') }}</h3>
                    <p class="text-[#FAFAFA] text-sm leading-relaxed mb-6">
                        {{ __('site.home.fleet_services_description') }}
                    </p>
                    <a href="{{ route('services') }}"
                        class="inline-flex items-center gap-2 text-[#DC2626] font-semibold text-sm group-hover:gap-3 transition-all">
                        {{ __('site.learn_more') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Teaser Section -->
    <section id="gallery" class="py-24 lg:py-32 bg-[#121218]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-12">
                <div>
                    <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">{{ __('site.home.gallery_section_label') }}</div>
                    <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight">
                        {{ __('site.home.gallery_title') }}
                    </h2>
                </div>
                <a href="{{ route('gallery') }}" class="btn-ghost">
                    <span>{{ __('site.home.gallery_view_full') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            {{-- Gallery Grid --}}
            @if($featuredGalleryImages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Featured Large Item (first item) --}}
                    @if($featuredGalleryImages->first())
                        <div class="lg:col-span-2 lg:row-span-2 relative group overflow-hidden rounded-2xl">
                            <img alt="{{ $featuredGalleryImages->first()->title }}"
                                class="w-full h-full object-cover min-h-[400px] lg:min-h-full transition-transform duration-700 group-hover:scale-105"
                                src="{{ $featuredGalleryImages->first()->image_url }}" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-[#0A0A0F]/30 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/20 border border-[#DC2626]/30 mb-4">
                                    <span class="text-[#DC2626] text-xs font-semibold uppercase">{{ str_replace('_', ' ', $featuredGalleryImages->first()->category->name) }}</span>
                                </div>
                                <h3 class="text-xl lg:text-2xl font-bold text-[#FAFAFA] font-headline mb-2">{{ $featuredGalleryImages->first()->title }}</h3>
                                @if($featuredGalleryImages->first()->description)
                                    <p class="text-[#FAFAFA] text-sm">{{ $featuredGalleryImages->first()->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Small Items (remaining items) --}}
                    @foreach($featuredGalleryImages->slice(1, 2) as $image)
                        <div class="relative group overflow-hidden rounded-2xl">
                            <img alt="{{ $image->title }}"
                                class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-105"
                                src="{{ $image->image_url }}" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 mb-3">
                                    <span class="text-[#FAFAFA] text-xs font-semibold uppercase">{{ str_replace('_', ' ', $image->category->name) }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-[#FAFAFA] font-headline">{{ $image->title }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card rounded-2xl p-12 text-center">
                    <div class="text-[#FAFAFA] mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-[#FAFAFA]">{{ __('site.home.gallery_no_items') }}</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-24 lg:py-32 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">{{ __('site.home.why_choose_label') }}</div>
                <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                    {{ __('site.home.why_choose_title') }}
                </h2>
            </div>
            
            {{-- Process Section --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
                <div class="text-center p-8 glass-card rounded-2xl">
                    <div class="w-16 h-16 rounded-full bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6 text-[#DC2626] font-bold text-2xl">1</div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] mb-3">Consultation</h3>
                    <p class="text-[#FAFAFA] text-sm">We assess your vehicle needs with precision and offer a transparent, free, no-obligation quote.</p>
                </div>
                <div class="text-center p-8 glass-card rounded-2xl">
                    <div class="w-16 h-16 rounded-full bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6 text-[#DC2626] font-bold text-2xl">2</div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] mb-3">Precision Fabrication</h3>
                    <p class="text-[#FAFAFA] text-sm">Our expert technicians prepare your high-quality glass using state-of-the-art tools and materials.</p>
                </div>
                <div class="text-center p-8 glass-card rounded-2xl">
                    <div class="w-16 h-16 rounded-full bg-[#DC2626]/10 flex items-center justify-center mx-auto mb-6 text-[#DC2626] font-bold text-2xl">3</div>
                    <h3 class="text-xl font-bold text-[#FAFAFA] mb-3">Quality Installation</h3>
                    <p class="text-[#FAFAFA] text-sm">We provide expert mobile installation, ensuring your vehicle's safety with a lifetime workmanship warranty.</p>
                </div>
            </div>

            {{-- Trusted By Section --}}
            @php $partners = \App\Models\Partner::where('is_active', true)->orderBy('order')->get(); @endphp
            @if($partners->isNotEmpty())
            <div class="mt-32 border-t border-white/5 pt-20">
                <p class="text-center text-[#A1A1AA] text-[10px] font-bold uppercase tracking-[0.2em] mb-12">Proudly Trusted By</p>
                <div class="flex flex-wrap justify-center items-center gap-x-16 gap-y-12">
                    @foreach($partners as $partner)
                        <div class="group relative">
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" 
                                 class="h-8 md:h-10 object-contain opacity-50 grayscale transition-all duration-500 group-hover:opacity-100 group-hover:grayscale-0 group-hover:scale-105">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 lg:py-32 bg-[#121218]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="mb-12">
                <div class="text-[#DC2626] text-sm font-semibold uppercase tracking-wider mb-3">{{ __('site.home.testimonials_label') }}</div>
                <h2 class="text-4xl md:text-5xl font-bold text-[#FAFAFA] font-headline tracking-tight">
                    {{ __('site.home.testimonials_title') }}
                </h2>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Main Carousel Area --}}
                <div class="lg:col-span-2">
                    @if($otherTestimonials->count() > 0)
                        <div class="glass-card rounded-2xl p-8 relative overflow-hidden" id="testimonialCarousel">
                            {{-- Carousel Container --}}
                            <div class="relative" id="carouselSlides">
                                @foreach($otherTestimonials as $index => $testimonial)
                                    <div class="carousel-slide absolute inset-0 transition-all duration-300 {{ $index === 0 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8' }}" data-index="{{ $index }}">
                                        {{-- Stars --}}
                                        <div class="flex gap-1 mb-6">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-[#DC2626]' : 'text-[#D4D4D8]' }}"
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>

                                        {{-- Quote --}}
                                        <blockquote class="text-xl text-[#FAFAFA] leading-relaxed mb-6">
                                            "{{ $testimonial->content }}"
                                        </blockquote>

                                        {{-- Author --}}
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full bg-[#DC2626]/20 flex items-center justify-center">
                                                <span class="text-[#DC2626] font-bold text-lg">{{ substr($testimonial->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-[#FAFAFA] font-semibold">{{ $testimonial->name }}</div>
                                                @if($testimonial->role)
                                                    <div class="text-[#FAFAFA] text-sm">{{ $testimonial->role }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Carousel Indicators --}}
                            <div class="flex justify-center gap-2 mt-8" id="carouselIndicators">
                                @foreach($otherTestimonials as $index => $testimonial)
                                    <button data-slide="{{ $index }}"
                                            class="carousel-indicator w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-[#DC2626] w-6' : 'bg-[#A1A1AA]' }}">
                                    </button>
                                @endforeach
                            </div>

                            {{-- Navigation Arrows --}}
                            <button id="prevSlide" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 rounded-full bg-[#DC2626]/20 hover:bg-[#DC2626]/30 flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button id="nextSlide" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 rounded-full bg-[#DC2626]/20 hover:bg-[#DC2626]/30 flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                            {{-- Spacer for absolute positioned content --}}
                            <div class="h-[280px]"></div>
                        </div>
                    @else
                        <div class="glass-card rounded-2xl p-8 text-center">
                            <div class="text-[#FAFAFA] mb-4">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-[#FAFAFA]">{{ __('site.home.testimonials_no_testimonials') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Right Column: Featured Testimonial + Stats --}}
                <div class="space-y-6">
                    {{-- Featured Testimonial Card --}}
                    @if($featuredTestimonial)
                        <div class="glass-card rounded-2xl p-6">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DC2626]/20 border border-[#DC2626]/30 mb-4">
                                <svg class="w-4 h-4 text-[#DC2626]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-[#DC2626] text-xs font-semibold uppercase">{{ __('site.home.testimonials_featured') }}</span>
                            </div>

                            <div class="flex gap-1 mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $featuredTestimonial->rating ? 'text-[#DC2626]' : 'text-[#D4D4D8]' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>

                            <blockquote class="text-[#FAFAFA] text-sm leading-relaxed mb-4 line-clamp-3">
                                "{{ $featuredTestimonial->content }}"
                            </blockquote>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#DC2626]/20 flex items-center justify-center">
                                    <span class="text-[#DC2626] font-bold">{{ substr($featuredTestimonial->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-[#FAFAFA] font-semibold text-sm">{{ $featuredTestimonial->name }}</div>
                                    @if($featuredTestimonial->role)
                                        <div class="text-[#FAFAFA] text-xs">{{ $featuredTestimonial->role }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-1">{{ $otherTestimonials->count() + ($featuredTestimonial ? 1 : 0) }}+
                            </div>
                            <div class="text-[#FAFAFA] text-xs">{{ __('site.home.happy_clients') }}</div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-1">4.9</div>
                            <div class="text-[#FAFAFA] text-xs">{{ __('site.home.average_rating') }}</div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-1">98%</div>
                            <div class="text-[#FAFAFA] text-xs">{{ __('site.home.recommend_us') }}</div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-[#FAFAFA] font-headline mb-1">24h</div>
                            <div class="text-[#FAFAFA] text-xs">{{ __('site.home.response_time') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('testimonialCarousel');
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.carousel-slide');
            const indicators = carousel.querySelectorAll('.carousel-indicator');
            const prevBtn = document.getElementById('prevSlide');
            const nextBtn = document.getElementById('nextSlide');

            if (slides.length === 0) return;

            let currentIndex = 0;
            let interval = null;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    if (i === index) {
                        slide.classList.remove('opacity-0', 'translate-x-8');
                        slide.classList.add('opacity-100', 'translate-x-0');
                    } else {
                        slide.classList.remove('opacity-100', 'translate-x-0');
                        slide.classList.add('opacity-0', 'translate-x-8');
                    }
                });

                indicators.forEach((indicator, i) => {
                    if (i === index) {
                        indicator.classList.remove('bg-[#A1A1AA]');
                        indicator.classList.add('bg-[#DC2626]', 'w-6');
                    } else {
                        indicator.classList.remove('bg-[#DC2626]', 'w-6');
                        indicator.classList.add('bg-[#A1A1AA]');
                    }
                });

                currentIndex = index;
            }

            function nextSlide() {
                const nextIndex = (currentIndex + 1) % slides.length;
                showSlide(nextIndex);
            }

            function prevSlide() {
                const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
                showSlide(prevIndex);
            }

            function startAutoSlide() {
                if (slides.length > 1) {
                    interval = setInterval(nextSlide, 5000);
                }
            }

            function resetAutoSlide() {
                if (interval) {
                    clearInterval(interval);
                }
                startAutoSlide();
            }

            // Event listeners
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    prevSlide();
                    resetAutoSlide();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    nextSlide();
                    resetAutoSlide();
                });
            }

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    showSlide(index);
                    resetAutoSlide();
                });
            });

            // Start auto-slide
            startAutoSlide();
        });
    </script>

    <!-- CTA Section -->
    <section class="py-24 lg:py-32 bg-gradient-to-b from-[#121218] to-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-12 lg:p-16 text-center relative overflow-hidden">
                {{-- Background Glow --}}
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#DC2626]/10 rounded-full blur-[100px]">
                </div>

                <div class="relative z-10">
                    <h2
                        class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#FAFAFA] font-headline tracking-tight mb-6">
                        Ready for Crystal<br>Clear Vision?
                    </h2>
                    <p class="text-lg text-[#FAFAFA] max-w-2xl mx-auto mb-10">
                        Get a free quote today. Our team is ready to help with all your automotive glass needs.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('quote') }}" class="btn-premium glow-red text-lg px-8 py-4">
                            <span>Get Your Free Quote</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        @if($primaryPhone)
                            <a href="tel:{{ $primaryPhone }}" class="btn-ghost text-lg px-8 py-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
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
    <section class="py-12 border-t border-white/5 bg-[#0A0A0F]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-[#DC2626]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[#FAFAFA] font-semibold text-sm">{{ $companyAddress }}</div>
                        <div class="text-[#FAFAFA] text-xs">
                            @php
                                try {
                                    $dayOrder = ['monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun'];
                                    $openDays = [];
                                    $closedDays = [];
                                    
                                    if (isset($workingHours) && is_array($workingHours)) {
                                        foreach ($dayOrder as $key => $abbr) {
                                            if (isset($workingHours[$key]) && !($workingHours[$key]['is_closed'] ?? false)) {
                                                $format = ($timeFormatDisplay ?? '12') === '24' ? 'H:i' : 'g:i A';
                                                $time = date($format, strtotime($workingHours[$key]['open'] ?? '07:30')) . ' – ' . date($format, strtotime($workingHours[$key]['close'] ?? '17:00'));
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
                                            if (!isset($openDays[$key])) continue;
                                            
                                            $dayData = $openDays[$key];
                                            $time = $dayData['time'];
                                            
                                            // Check if consecutive and same time
                                            $isConsecutive = $prevKey !== null && array_search($key, $dayKeys) === array_search($prevKey, $dayKeys) + 1;
                                            
                                            if ($time === $currentTime && $isConsecutive) {
                                                $currentGroup[] = $dayData['abbr'];
                                            } else {
                                                if (!empty($currentGroup)) {
                                                    $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                                }
                                                $currentGroup = [$dayData['abbr']];
                                                $currentTime = $time;
                                            }
                                            $prevKey = $key;
                                        }
                                        
                                        if (!empty($currentGroup)) {
                                            $groupedDays[] = ['days' => $currentGroup, 'time' => $currentTime];
                                        }
                                        
                                        // Modern professional format
                                        $formatted = [];
                                        foreach ($groupedDays as $group) {
                                            $dayLabel = count($group['days']) > 2 
                                                ? $group['days'][0] . '–' . end($group['days']) 
                                                : implode(' & ', $group['days']);
                                            $formatted[] = $dayLabel . ' · ' . $group['time'];
                                        }
                                        
                                        if (!empty($closedDays)) {
                                            $closedLabel = count($closedDays) > 2 
                                                ? $closedDays[0] . '–' . end($closedDays) 
                                                : implode(' & ', $closedDays);
                                            $formatted[] = $closedLabel . ' · Closed';
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

                @if($primaryPhone)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#DC2626]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[#FAFAFA] font-semibold text-sm">{{ $primaryPhone }}</div>
                            <div class="text-[#FAFAFA] text-xs">Call for immediate assistance</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Soft Blur In Animation Script --}}
    <script>
        (function() {
            const phrases = @json(__('site.home.hero_headline_animated'));
            const container = document.getElementById('hero-headline-container');
            
            if (!container || !phrases || phrases.length === 0) return;

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
                title.style.cssText = 'display: inline-block; transform-style: preserve-3d; backface-visibility: hidden; will-change: transform, opacity, filter; width: 100%;';
                
                // Split text into individual characters for per-character animation
                const characters = Array.from(text);
                const units = [];
                
                characters.forEach((char, index) => {
                    const unit = document.createElement('span');
                    unit.className = 'text-animation-unit';
                    unit.textContent = char;
                    unit.style.cssText = 'display: inline-block; backface-visibility: hidden; will-change: transform, opacity, filter; white-space: pre; transform-origin: 50% 55%;';
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
                            filter: 'blur(12px)'
                        },
                        { 
                            opacity: 1, 
                            transform: 'translate3d(0, 0, 0)',
                            filter: 'blur(0px)'
                        }
                    ];

                    const animation = element.animate(keyframes, {
                        delay: delay,
                        duration: ENTER_DURATION,
                        easing: ENTER_EASING,
                        fill: 'forwards'
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
                            filter: 'blur(0px)'
                        },
                        { 
                            opacity: 0, 
                            transform: `translate3d(0, ${yEnd}px, 0)`,
                            filter: 'blur(12px)'
                        }
                    ];

                    const animation = element.animate(keyframes, {
                        delay: delay,
                        duration: EXIT_DURATION,
                        easing: EXIT_EASING,
                        fill: 'forwards'
                    });

                    activeAnimations.push(animation);
                    return animation.finished;
                });

                await Promise.all(promises);
                activeAnimations = [];
            }

            function sleep(ms) {
                return new Promise(resolve => {
                    activeTimeout = setTimeout(resolve, ms);
                });
            }

            async function runAnimationLoop() {
                if (!isRunning) return;

                // Initial delay
                await sleep(INITIAL_DELAY_MS);

                // Create and animate first phrase
                let { title, units } = createPhrase(phrases[currentIndex]);
                units.forEach(unit => applyEnterFrom(unit));
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
                    nextPhrase.units.forEach(unit => applyEnterFrom(unit));

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
                activeAnimations.forEach(animation => {
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
</x-layouts::site>