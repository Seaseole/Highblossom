<?php if (isset($component)) { $__componentOriginal52b6740a4059545a9135423805a466b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52b6740a4059545a9135423805a466b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::site','data' => ['title' => 'Highblossom | Precision Automotive Glass']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Highblossom | Precision Automotive Glass']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <!-- Hero Section - Cinematic Dark -->
    <header class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-[#0A0A0F]">
        <style>
            @media (prefers-reduced-motion: reduce) {
                .animate-fade-up,
                .animate-pulse {
                    animation: none !important;
                    transition: none !important;
                }
            }
        </style>
        
        <div class="absolute inset-0 z-0">
            <img
                alt="Premium automotive glass installation"
                class="h-full w-full object-cover opacity-40"
                src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1920&q=80"
                width="1920"
                height="1080"
                fetchpriority="high"
                loading="eager"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-[#0A0A0F] via-[#0A0A0F]/80 to-[#0A0A0F]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0F] via-transparent to-[#0A0A0F]/50"></div>
        </div>

        
        <div class="relative z-10 mx-auto flex w-full max-w-[1400px] flex-grow items-center justify-center px-4 pt-32 pb-12 text-center sm:px-6 sm:pt-40 lg:px-8">
            <div class="mx-auto max-w-4xl">
                
                <div class="animate-fade-up mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 sm:mb-8">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-[#DC2626]"></span>
                    <span class="text-xs font-medium text-[#FAFAFA] sm:text-sm"><?php echo e(__('site.home.hero_trust_badge')); ?></span>
                </div>

                
                <h1 id="hero-heading" class="sr-only"><?php echo e(__('site.home.hero_headline_fallback')); ?></h1>
                <div
                    id="hero-headline-container"
                    aria-labelledby="hero-heading"
                    class="font-headline mx-auto mb-4 text-2xl leading-[1.2] font-bold tracking-tight break-words hyphens-auto text-[#FAFAFA] sm:mb-6 sm:text-4xl sm:leading-[1.1] md:text-5xl lg:text-6xl"
                    style="perspective: 900px; min-height: 2.4em"
                ></div>

                
                <p
                    class="animate-fade-up mx-auto mb-8 max-w-2xl text-base leading-relaxed text-[#FAFAFA] sm:mb-10 sm:text-lg md:text-xl"
                    style="animation-delay: 200ms"
                >
                    <?php echo e(__('site.home.hero_subheadline')); ?>

                </p>

                
                <div
                    class="animate-fade-up flex flex-col items-center justify-center gap-4 sm:flex-row"
                    style="animation-delay: 300ms"
                >
                    <a href="<?php echo e(route('quote')); ?>" class="btn-premium-md glow-red-subtle">
                        <span><?php echo e(__('site.home.hero_get_quote')); ?></span>
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
                        href="<?php echo e(route('gallery')); ?>"
                        class="btn-glass btn-glass--accent glow-red-subtle pulse px-8 py-4 text-lg"
                    >
                        <span><?php echo e(__('site.home.hero_view_work')); ?></span>
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
            
            <div class="mb-16 flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        <?php echo e(__('site.home.services_section_label')); ?>

                    </div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                        <?php echo e(__('site.home.services_title')); ?>

                    </h2>
                </div>
                <a
                    href="<?php echo e(route('services')); ?>"
                    class="btn-glass btn-glass--accent glow-red-subtle pulse px-8 py-4 text-lg"
                >
                    <span><?php echo e(__('site.home.services_view_all')); ?></span>
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

            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                
                <div class="glass-card glass-card-hover group rounded-2xl p-8">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-7 w-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]">
                        <?php echo e(__('site.home.windscreens')); ?>

                    </h3>
                    <p class="mb-6 text-sm leading-relaxed text-[#FAFAFA]">
                        <?php echo e(__('site.home.windscreens_description')); ?>

                    </p>
                    <a
                        href="<?php echo e(route('services')); ?>"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-[#DC2626] transition-all group-hover:gap-3"
                    >
                        <?php echo e(__('site.learn_more')); ?>

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                
                <div class="glass-card glass-card-hover group rounded-2xl p-8">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-7 w-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]"><?php echo e(__('site.home.side_rear')); ?></h3>
                    <p class="mb-6 text-sm leading-relaxed text-[#FAFAFA]">
                        <?php echo e(__('site.home.side_rear_description')); ?>

                    </p>
                    <a
                        href="<?php echo e(route('services')); ?>"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-[#DC2626] transition-all group-hover:gap-3"
                    >
                        <?php echo e(__('site.learn_more')); ?>

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                
                <div class="glass-card glass-card-hover group rounded-2xl p-8">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-7 w-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]">
                        <?php echo e(__('site.home.heavy_machinery')); ?>

                    </h3>
                    <p class="mb-6 text-sm leading-relaxed text-[#FAFAFA]">
                        <?php echo e(__('site.home.heavy_machinery_description')); ?>

                    </p>
                    <a
                        href="<?php echo e(route('services')); ?>"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-[#DC2626] transition-all group-hover:gap-3"
                    >
                        <?php echo e(__('site.learn_more')); ?>

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                
                <div class="glass-card glass-card-hover group rounded-2xl p-8">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-[#DC2626]/10 transition-colors group-hover:bg-[#DC2626]/20">
                        <svg class="h-7 w-7 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]">
                        <?php echo e(__('site.home.fleet_services')); ?>

                    </h3>
                    <p class="mb-6 text-sm leading-relaxed text-[#FAFAFA]">
                        <?php echo e(__('site.home.fleet_services_description')); ?>

                    </p>
                    <a
                        href="<?php echo e(route('services')); ?>"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-[#DC2626] transition-all group-hover:gap-3"
                    >
                        <?php echo e(__('site.learn_more')); ?>

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Teaser Section -->
    <section id="gallery" class="bg-[#121218] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            
            <div class="mb-12 flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-end">
                <div>
                    <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        <?php echo e(__('site.home.gallery_section_label')); ?>

                    </div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                        <?php echo e(__('site.home.gallery_title')); ?>

                    </h2>
                </div>
                <a
                    href="<?php echo e(route('gallery')); ?>"
                    class="btn-glass btn-glass--accent glow-red-subtle pulse px-8 py-4 text-lg"
                >
                    <span><?php echo e(__('site.home.gallery_view_full')); ?></span>
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

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredGalleryImages->count() > 0): ?>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredGalleryImages->first()): ?>
                        <div class="group relative overflow-hidden rounded-2xl lg:col-span-2 lg:row-span-2">
                            <img
                                alt="<?php echo e($featuredGalleryImages->first()->title); ?>"
                                class="h-full min-h-[400px] w-full object-cover transition-transform duration-700 group-hover:scale-105 lg:min-h-full"
                                src="<?php echo e($featuredGalleryImages->first()->image_url); ?>"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-[#0A0A0F]/30 to-transparent"></div>
                            <div class="absolute right-0 bottom-0 left-0 p-6 lg:p-8">
                                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/20 px-3 py-1">
                                    <span class="text-xs font-semibold text-[#DC2626] uppercase"><?php echo e(str_replace('_', ' ', $featuredGalleryImages->first()->category->name)); ?></span>
                                </div>
                                <h3 class="font-headline mb-2 text-xl font-bold text-[#FAFAFA] lg:text-2xl">
                                    <?php echo e($featuredGalleryImages->first()->title); ?>

                                </h3>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredGalleryImages->first()->description): ?>
                                    <p class="text-sm text-[#FAFAFA]">
                                        <?php echo e($featuredGalleryImages->first()->description); ?>

                                    </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $featuredGalleryImages->slice(1, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="group relative overflow-hidden rounded-2xl">
                            <img
                                alt="<?php echo e($image->title); ?>"
                                class="h-64 w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                src="<?php echo e($image->image_url); ?>"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent"></div>
                            <div class="absolute right-0 bottom-0 left-0 p-6">
                                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1">
                                    <span class="text-xs font-semibold text-[#FAFAFA] uppercase"><?php echo e(str_replace('_', ' ', $image->category->name)); ?></span>
                                </div>
                                <h3 class="font-headline text-lg font-bold text-[#FAFAFA]"><?php echo e($image->title); ?></h3>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php else: ?>
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
                    <p class="text-[#FAFAFA]"><?php echo e(__('site.home.gallery_no_items')); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="bg-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto mb-16 max-w-2xl text-center">
                <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('site.home.why_choose_label')); ?>

                </div>
                <h2 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                    <?php echo e(__('site.home.why_choose_title')); ?>

                </h2>
            </div>

            
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

            
            <?php $partners = \App\Models\Partner::where('is_active', true)->orderBy('order')->get(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($partners->isNotEmpty()): ?>
                <div class="mt-32 border-t border-white/5 pt-20">
                    <p class="mb-12 text-center text-[10px] font-bold tracking-[0.2em] text-[#A1A1AA] uppercase">
                        Proudly Trusted By
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-x-16 gap-y-12">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="group relative">
                                <img
                                    src="<?php echo e($partner->logo_url); ?>"
                                    alt="<?php echo e($partner->name); ?>"
                                    class="h-8 object-contain opacity-50 grayscale transition-all duration-500 group-hover:scale-105 group-hover:opacity-100 group-hover:grayscale-0 md:h-10"
                                />
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-[#121218] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            
            <div class="mb-12">
                <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('site.home.testimonials_label')); ?>

                </div>
                <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                    <?php echo e(__('site.home.testimonials_title')); ?>

                </h2>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                
                <div class="lg:col-span-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($otherTestimonials->count() > 0): ?>
                        <div class="glass-card relative overflow-hidden rounded-2xl p-8" id="testimonialCarousel">
                            
                            <div class="relative" id="carouselSlides">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $otherTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div
                                        class="carousel-slide absolute inset-0 transition-all duration-300 <?php echo e($index === 0 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'); ?>"
                                        data-index="<?php echo e($index); ?>"
                                    >
                                        
                                        <div class="mb-6 flex gap-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <svg
                                                    class="w-5 h-5 <?php echo e($i <= $testimonial->rating ? 'text-[#DC2626]' : 'text-[#D4D4D8]'); ?>"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>

                                        
                                        <blockquote class="mb-6 text-xl leading-relaxed text-[#FAFAFA]">
                                            "<?php echo e($testimonial->content); ?>"
                                        </blockquote>

                                        
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#DC2626]/20">
                                                <span class="text-lg font-bold text-[#DC2626]"><?php echo e(substr($testimonial->name, 0, 1)); ?></span>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-[#FAFAFA]"><?php echo e($testimonial->name); ?></div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimonial->role): ?>
                                                    <div class="text-sm text-[#FAFAFA]"><?php echo e($testimonial->role); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            
                            <div class="mt-8 flex justify-center gap-2" id="carouselIndicators">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $otherTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <button
                                        data-slide="<?php echo e($index); ?>"
                                        class="carousel-indicator w-2 h-2 rounded-full transition-all duration-300 <?php echo e($index === 0 ? 'bg-[#DC2626] w-6' : 'bg-[#A1A1AA]'); ?>"
                                    ></button>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            
                            <button
                                id="prevSlide"
                                class="absolute top-1/2 left-0 flex h-10 w-10 -translate-x-4 -translate-y-1/2 items-center justify-center rounded-full bg-[#DC2626]/20 transition-colors hover:bg-[#DC2626]/30"
                            >
                                <svg class="h-5 w-5 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button
                                id="nextSlide"
                                class="absolute top-1/2 right-0 flex h-10 w-10 translate-x-4 -translate-y-1/2 items-center justify-center rounded-full bg-[#DC2626]/20 transition-colors hover:bg-[#DC2626]/30"
                            >
                                <svg class="h-5 w-5 text-[#FAFAFA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            
                            <div class="h-[280px]"></div>
                        </div>
                    <?php else: ?>
                        <div class="glass-card rounded-2xl p-8 text-center">
                            <div class="mb-4 text-[#FAFAFA]">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    ></path>
                                </svg>
                            </div>
                            <p class="text-[#FAFAFA]"><?php echo e(__('site.home.testimonials_no_testimonials')); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="space-y-6">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredTestimonial): ?>
                        <div class="glass-card rounded-2xl p-6">
                            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#DC2626]/30 bg-[#DC2626]/20 px-3 py-1">
                                <svg class="h-4 w-4 text-[#DC2626]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-xs font-semibold text-[#DC2626] uppercase"><?php echo e(__('site.home.testimonials_featured')); ?></span>
                            </div>

                            <div class="mb-3 flex gap-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <svg
                                        class="w-4 h-4 <?php echo e($i <= $featuredTestimonial->rating ? 'text-[#DC2626]' : 'text-[#D4D4D8]'); ?>"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            <blockquote class="mb-4 line-clamp-3 text-sm leading-relaxed text-[#FAFAFA]">
                                "<?php echo e($featuredTestimonial->content); ?>"
                            </blockquote>

                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#DC2626]/20">
                                    <span class="font-bold text-[#DC2626]"><?php echo e(substr($featuredTestimonial->name, 0, 1)); ?></span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-[#FAFAFA]">
                                        <?php echo e($featuredTestimonial->name); ?>

                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredTestimonial->role): ?>
                                        <div class="text-xs text-[#FAFAFA]"><?php echo e($featuredTestimonial->role); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">
                                <?php echo e($otherTestimonials->count() + ($featuredTestimonial ? 1 : 0)); ?>+
                            </div>
                            <div class="text-xs text-[#FAFAFA]"><?php echo e(__('site.home.happy_clients')); ?></div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">4.9</div>
                            <div class="text-xs text-[#FAFAFA]"><?php echo e(__('site.home.average_rating')); ?></div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">98%</div>
                            <div class="text-xs text-[#FAFAFA]"><?php echo e(__('site.home.recommend_us')); ?></div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 text-center">
                            <div class="font-headline mb-1 text-2xl font-bold text-[#FAFAFA]">24h</div>
                            <div class="text-xs text-[#FAFAFA]"><?php echo e(__('site.home.response_time')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                prevBtn.addEventListener('click', function () {
                    prevSlide();
                    resetAutoSlide();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    nextSlide();
                    resetAutoSlide();
                });
            }

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function () {
                    showSlide(index);
                    resetAutoSlide();
                });
            });

            // Start auto-slide
            startAutoSlide();
        });
    </script>

    <!-- CTA Section -->
    <section class="bg-gradient-to-b from-[#121218] to-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="glass-card relative overflow-hidden rounded-3xl p-12 text-center lg:p-16">
                
                <div class="absolute top-1/2 left-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#DC2626]/10 blur-[100px]"></div>

                <div class="relative z-10">
                    <h2 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                        Ready for Crystal<br />Clear Vision?
                    </h2>
                    <p class="mx-auto mb-10 max-w-2xl text-lg text-[#FAFAFA]">
                        Get a free quote today. Our team is ready to help with all your automotive glass needs.
                    </p>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <a href="<?php echo e(route('quote')); ?>" class="btn-premium glow-red px-8 py-4 text-lg">
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
                        <a href="<?php echo e(route('bookings.create')); ?>" class="btn-glass px-8 py-4 text-lg">
                            <span>Book an Inspection</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                        </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryPhone): ?>
                            <a href="tel:<?php echo e($primaryPhone); ?>" class="btn-glass px-8 py-4 text-lg">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    ></path>
                                </svg>
                                <span><?php echo e($primaryPhone); ?></span>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <div class="text-sm font-semibold text-[#FAFAFA]"><?php echo e($companyAddress); ?></div>
                        <div class="text-xs text-[#FAFAFA]">
                            <?php
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
                            ?>
                        </div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryPhone): ?>
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
                            <div class="text-sm font-semibold text-[#FAFAFA]"><?php echo e($primaryPhone); ?></div>
                            <div class="text-xs text-[#FAFAFA]">Call for immediate assistance</div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    
    <script>
        (function () {
            const phrases = <?php echo json_encode(__('site.home.hero_headline_animated'), 15, 512) ?>;
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

                // Create and animate first phrase
                let { title, units } = createPhrase(phrases[currentIndex]);
                units.forEach((unit) => applyEnterFrom(unit));
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52b6740a4059545a9135423805a466b9)): ?>
<?php $attributes = $__attributesOriginal52b6740a4059545a9135423805a466b9; ?>
<?php unset($__attributesOriginal52b6740a4059545a9135423805a466b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52b6740a4059545a9135423805a466b9)): ?>
<?php $component = $__componentOriginal52b6740a4059545a9135423805a466b9; ?>
<?php unset($__componentOriginal52b6740a4059545a9135423805a466b9); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/welcome.blade.php ENDPATH**/ ?>