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
        
        <div class="absolute inset-0 z-0">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($heroImage)): ?>
                <img
                    alt="<?php echo e($homeTagline); ?>"
                    class="h-full w-full object-cover opacity-40"
                    src="<?php echo e($heroImage); ?>"
                    width="1920"
                    height="1080"
                    fetchpriority="high"
                    loading="eager"
                    decoding="async"
                />
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-b from-[#0A0A0F] via-[#0A0A0F]/80 to-[#0A0A0F]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#0A0A0F] via-transparent to-[#0A0A0F]/50"></div>
            <div class="grain-overlay pointer-events-none absolute inset-0" aria-hidden="true"></div>
        </div>

        
        <div class="relative z-10 mx-auto flex w-full max-w-[1400px] flex-grow items-center justify-center px-4 pt-32 pb-12 text-center sm:px-6 sm:pt-40 lg:px-8">
            <div class="mx-auto max-w-4xl">
                
                <div class="animate-fade-up mb-6 flex flex-wrap items-center justify-center gap-2 sm:mb-8">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2">
                        <span class="h-2 w-2 animate-pulse rounded-full bg-[#DC2626]" aria-hidden="true"></span>
                        <span class="text-xs font-medium text-[#FAFAFA] sm:text-sm"><?php echo e(__('site.home.hero_trust_badge')); ?></span>
                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($homeYearsExperience)): ?>
                        <span class="inline-flex items-center gap-2 rounded-full border border-[#DC2626]/25 bg-[#DC2626]/10 px-4 py-2">
                            <span class="text-xs font-bold text-[#FAFAFA] sm:text-sm">
                                <?php echo e(__('site.home.hero_years_badge', ['years' => $homeYearsExperience])); ?>

                            </span>
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <h1 id="hero-heading" class="sr-only"><?php echo e($homeTagline ?: __('site.home.hero_headline_fallback')); ?></h1>
                <div
                    id="hero-headline-container"
                    aria-labelledby="hero-heading"
                    class="font-headline mx-auto mb-4 text-2xl leading-[1.2] font-bold tracking-tight break-words hyphens-auto text-[#FAFAFA] sm:mb-6 sm:text-4xl sm:leading-[1.1] md:text-5xl lg:text-6xl"
                    style="perspective: 900px; min-height: 2.4em"
                ><span data-hero-seed><?php echo e($homeTagline ?: __('site.home.hero_headline_animated')[0]); ?></span></div>

                
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
                    <a href="<?php echo e(route('quote')); ?>" wire:navigate class="btn-premium-md glow-red-subtle">
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
                        wire:navigate
                        class="btn-glass px-8 py-4 text-lg"
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

    
    <?php if (isset($component)) { $__componentOriginal2144a51a21aa22d636def9b978cdcca9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2144a51a21aa22d636def9b978cdcca9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.fact-tiles','data' => ['stats' => $homeStats,'tiles' => $factTiles]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.fact-tiles'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeStats),'tiles' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($factTiles)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2144a51a21aa22d636def9b978cdcca9)): ?>
<?php $attributes = $__attributesOriginal2144a51a21aa22d636def9b978cdcca9; ?>
<?php unset($__attributesOriginal2144a51a21aa22d636def9b978cdcca9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2144a51a21aa22d636def9b978cdcca9)): ?>
<?php $component = $__componentOriginal2144a51a21aa22d636def9b978cdcca9; ?>
<?php unset($__componentOriginal2144a51a21aa22d636def9b978cdcca9); ?>
<?php endif; ?>

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
                    wire:navigate
                    class="btn-glass px-8 py-4 text-lg"
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

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredServices->isNotEmpty()): ?>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $featuredServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal1f20d46271b7be2f701768a10eea6799 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f20d46271b7be2f701768a10eea6799 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.service-tile','data' => ['service' => $service]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.service-tile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['service' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f20d46271b7be2f701768a10eea6799)): ?>
<?php $attributes = $__attributesOriginal1f20d46271b7be2f701768a10eea6799; ?>
<?php unset($__attributesOriginal1f20d46271b7be2f701768a10eea6799); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f20d46271b7be2f701768a10eea6799)): ?>
<?php $component = $__componentOriginal1f20d46271b7be2f701768a10eea6799; ?>
<?php unset($__componentOriginal1f20d46271b7be2f701768a10eea6799); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    
    <?php if (isset($component)) { $__componentOriginal4e04261dd11c705425007cb1fade9367 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e04261dd11c705425007cb1fade9367 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.cta-band','data' => ['heading' => $homeCtaHeading,'text' => $homeCtaText]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.cta-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeCtaHeading),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeCtaText)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4e04261dd11c705425007cb1fade9367)): ?>
<?php $attributes = $__attributesOriginal4e04261dd11c705425007cb1fade9367; ?>
<?php unset($__attributesOriginal4e04261dd11c705425007cb1fade9367); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4e04261dd11c705425007cb1fade9367)): ?>
<?php $component = $__componentOriginal4e04261dd11c705425007cb1fade9367; ?>
<?php unset($__componentOriginal4e04261dd11c705425007cb1fade9367); ?>
<?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($homeUrgencyEnabled): ?>
        <?php if (isset($component)) { $__componentOriginal25e4432fdf0ce19a340c1efaf2de0c3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25e4432fdf0ce19a340c1efaf2de0c3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.urgency-band','data' => ['text' => $homeUrgencyText]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.urgency-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeUrgencyText)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25e4432fdf0ce19a340c1efaf2de0c3a)): ?>
<?php $attributes = $__attributesOriginal25e4432fdf0ce19a340c1efaf2de0c3a; ?>
<?php unset($__attributesOriginal25e4432fdf0ce19a340c1efaf2de0c3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25e4432fdf0ce19a340c1efaf2de0c3a)): ?>
<?php $component = $__componentOriginal25e4432fdf0ce19a340c1efaf2de0c3a; ?>
<?php unset($__componentOriginal25e4432fdf0ce19a340c1efaf2de0c3a); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredGalleryImages->isNotEmpty()): ?>
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
                        wire:navigate
                        class="btn-glass min-h-[44px] px-6 py-3 text-base sm:px-8 sm:py-4 sm:text-lg"
                    >
                        <span><?php echo e(__('site.home.gallery_view_full')); ?></span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"
                            ></path>
                        </svg>
                    </a>
                </div>

                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $featuredGalleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a
                            href="<?php echo e(route('gallery')); ?>"
                            wire:navigate
                            class="group relative block overflow-hidden rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626] focus-visible:ring-offset-2 focus-visible:ring-offset-[#121218]"
                            aria-label="<?php echo e(__('site.home.gallery_view_full')); ?> — <?php echo e($image->title); ?>"
                        >
                            <img
                                alt="<?php echo e($image->title); ?>"
                                class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-[1.05]"
                                src="<?php echo e($image->image_url); ?>"
                                width="800"
                                height="600"
                                loading="lazy"
                                decoding="async"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-[#0A0A0F]/25 to-transparent"></div>
                            <div class="absolute right-0 bottom-0 left-0 p-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($image->category?->name)): ?>
                                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1">
                                        <span class="text-xs font-semibold text-[#FAFAFA] uppercase"><?php echo e(str_replace('_', ' ', $image->category->name)); ?></span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <h3 class="font-headline text-lg font-bold text-[#FAFAFA]"><?php echo e($image->title); ?></h3>
                            </div>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginaleb2e3447b08bc4949da22a0506da9a71 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleb2e3447b08bc4949da22a0506da9a71 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.warranty-band','data' => ['heading' => $homeWarrantyHeading,'text' => $homeWarrantyText,'highlights' => $warrantyHighlights]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.warranty-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeWarrantyHeading),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeWarrantyText),'highlights' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($warrantyHighlights)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaleb2e3447b08bc4949da22a0506da9a71)): ?>
<?php $attributes = $__attributesOriginaleb2e3447b08bc4949da22a0506da9a71; ?>
<?php unset($__attributesOriginaleb2e3447b08bc4949da22a0506da9a71); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaleb2e3447b08bc4949da22a0506da9a71)): ?>
<?php $component = $__componentOriginaleb2e3447b08bc4949da22a0506da9a71; ?>
<?php unset($__componentOriginaleb2e3447b08bc4949da22a0506da9a71); ?>
<?php endif; ?>

    <!-- Process / Why Choose Us Section -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($processSteps->isNotEmpty()): ?>
        <section id="process" class="bg-[#0A0A0F] py-24 lg:py-32">
            <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
                <div class="mx-auto mb-16 max-w-2xl text-center">
                    <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                        <?php echo e(__('site.home.why_choose_label')); ?>

                    </div>
                    <h2 class="font-headline text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                        <?php echo e(__('site.home.why_choose_title')); ?>

                    </h2>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $processSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="glass-card rounded-2xl p-8 text-center">
                            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#DC2626]/10 text-2xl font-bold text-[#DC2626]">
                                <?php echo e($index + 1); ?>

                            </div>
                            <h3 class="font-headline mb-3 text-xl font-bold text-[#FAFAFA]"><?php echo e($step['title']); ?></h3>
                            <p class="text-sm leading-relaxed text-[#A1A1AA]"><?php echo e($step['text']); ?></p>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </section>

        
        <?php if (isset($component)) { $__componentOriginal4e04261dd11c705425007cb1fade9367 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e04261dd11c705425007cb1fade9367 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.cta-band','data' => ['heading' => $homeCtaHeading,'text' => $homeCtaText]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.cta-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeCtaHeading),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeCtaText)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4e04261dd11c705425007cb1fade9367)): ?>
<?php $attributes = $__attributesOriginal4e04261dd11c705425007cb1fade9367; ?>
<?php unset($__attributesOriginal4e04261dd11c705425007cb1fade9367); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4e04261dd11c705425007cb1fade9367)): ?>
<?php $component = $__componentOriginal4e04261dd11c705425007cb1fade9367; ?>
<?php unset($__componentOriginal4e04261dd11c705425007cb1fade9367); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginald2b9b8765defe9880d7b873fc9c0fe31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald2b9b8765defe9880d7b873fc9c0fe31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.partner-strip','data' => ['partners' => $partners]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.partner-strip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['partners' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($partners)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald2b9b8765defe9880d7b873fc9c0fe31)): ?>
<?php $attributes = $__attributesOriginald2b9b8765defe9880d7b873fc9c0fe31; ?>
<?php unset($__attributesOriginald2b9b8765defe9880d7b873fc9c0fe31); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald2b9b8765defe9880d7b873fc9c0fe31)): ?>
<?php $component = $__componentOriginald2b9b8765defe9880d7b873fc9c0fe31; ?>
<?php unset($__componentOriginald2b9b8765defe9880d7b873fc9c0fe31); ?>
<?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasTestimonials): ?>
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
                    
                    <div class="grid gap-6 md:grid-cols-2 lg:col-span-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $otherTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.testimonial-card','data' => ['testimonial' => $testimonial]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.testimonial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonial' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($testimonial)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50)): ?>
<?php $attributes = $__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50; ?>
<?php unset($__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50)): ?>
<?php $component = $__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50; ?>
<?php unset($__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($otherTestimonials->isEmpty() && $featuredTestimonial): ?>
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.testimonial-card','data' => ['testimonial' => $featuredTestimonial,'featured' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.testimonial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonial' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredTestimonial),'featured' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50)): ?>
<?php $attributes = $__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50; ?>
<?php unset($__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50)): ?>
<?php $component = $__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50; ?>
<?php unset($__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50); ?>
<?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featuredTestimonial && $otherTestimonials->isNotEmpty()): ?>
                        <div class="space-y-6">
                            <?php if (isset($component)) { $__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.testimonial-card','data' => ['testimonial' => $featuredTestimonial,'featured' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.testimonial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonial' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featuredTestimonial),'featured' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50)): ?>
<?php $attributes = $__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50; ?>
<?php unset($__attributesOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50)): ?>
<?php $component = $__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50; ?>
<?php unset($__componentOriginal5c0bd46b2c6b09c8e9eb6a3014ef1c50); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal246c83128c7bea3ffe7c43a2d2888c82 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal246c83128c7bea3ffe7c43a2d2888c82 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.service-area','data' => ['area' => $homeServiceArea,'coverage' => $homeMobileCoverage]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.service-area'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['area' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeServiceArea),'coverage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($homeMobileCoverage)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal246c83128c7bea3ffe7c43a2d2888c82)): ?>
<?php $attributes = $__attributesOriginal246c83128c7bea3ffe7c43a2d2888c82; ?>
<?php unset($__attributesOriginal246c83128c7bea3ffe7c43a2d2888c82); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal246c83128c7bea3ffe7c43a2d2888c82)): ?>
<?php $component = $__componentOriginal246c83128c7bea3ffe7c43a2d2888c82; ?>
<?php unset($__componentOriginal246c83128c7bea3ffe7c43a2d2888c82); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal7073e2c1ecf60a5064d36e22ed85649b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7073e2c1ecf60a5064d36e22ed85649b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.faq','data' => ['faqs' => $faqs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.faq'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['faqs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($faqs)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7073e2c1ecf60a5064d36e22ed85649b)): ?>
<?php $attributes = $__attributesOriginal7073e2c1ecf60a5064d36e22ed85649b; ?>
<?php unset($__attributesOriginal7073e2c1ecf60a5064d36e22ed85649b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7073e2c1ecf60a5064d36e22ed85649b)): ?>
<?php $component = $__componentOriginal7073e2c1ecf60a5064d36e22ed85649b; ?>
<?php unset($__componentOriginal7073e2c1ecf60a5064d36e22ed85649b); ?>
<?php endif; ?>


    <!-- Final CTA Section -->
    <section class="bg-gradient-to-b from-[#121218] to-[#0A0A0F] py-24 lg:py-32">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="glass-card relative overflow-hidden rounded-3xl p-8 text-center sm:p-12 lg:p-16">
                
                <div class="absolute top-1/2 left-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#DC2626]/10 blur-[100px]" aria-hidden="true"></div>

                <div class="relative z-10">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($homeFinalCtaHeading)): ?>
                        <h2 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                            <?php echo e($homeFinalCtaHeading); ?>

                        </h2>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($homeFinalCtaText)): ?>
                        <p class="mx-auto mb-10 max-w-2xl text-lg text-[#A1A1AA]">
                            <?php echo e($homeFinalCtaText); ?>

                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal1346536acca2f562295db5577845269d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1346536acca2f562295db5577845269d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.contact-buttons','data' => ['quoteLabel' => __('site.home.cta_band_quote'),'size' => 'lg','class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.contact-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['quote-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('site.home.cta_band_quote')),'size' => 'lg','class' => 'mb-6']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1346536acca2f562295db5577845269d)): ?>
<?php $attributes = $__attributesOriginal1346536acca2f562295db5577845269d; ?>
<?php unset($__attributesOriginal1346536acca2f562295db5577845269d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1346536acca2f562295db5577845269d)): ?>
<?php $component = $__componentOriginal1346536acca2f562295db5577845269d; ?>
<?php unset($__componentOriginal1346536acca2f562295db5577845269d); ?>
<?php endif; ?>

                    <a
                        href="<?php echo e(route('bookings.create')); ?>"
                        wire:navigate
                        class="btn-glass inline-flex min-h-[44px] items-center px-6 py-3 text-base sm:px-8 sm:py-4 sm:text-lg"
                    >
                        <span><?php echo e(__('site.book_appointment')); ?></span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Info Section -->
    <section class="border-t border-white/5 bg-[#0A0A0F] py-12">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#DC2626]/10">
                        <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($contact->address)): ?>
                            <div class="text-sm font-semibold text-[#FAFAFA]"><?php echo e($contact->address); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contact->openingHours()->isNotEmpty()): ?>
                            <div class="text-xs text-[#A1A1AA]">
                                <?php echo e($contact->openingHours()->implode(' | ')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contact->hasPhone()): ?>
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#DC2626]/10">
                            <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <a
                                href="<?php echo e($contact->telHref($contact->primaryPhone)); ?>"
                                class="text-sm font-semibold text-[#FAFAFA] transition-colors hover:text-[#DC2626] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                            ><?php echo e($contact->primaryPhone); ?></a>
                            <div class="text-xs text-[#A1A1AA]"><?php echo e(__('site.home.quick_info_call_hint')); ?></div>
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

            // This IIFE re-runs on every view of the home page, so the teardown itself is
            // bound once per document and always disposes the newest copy. pagehide alone
            // is not enough: wire:navigate never fires it.
            window.__heroAnimationCleanup = cleanup;

            if (! window.__heroTeardownBound) {
                window.__heroTeardownBound = true;
                window.addEventListener('beforeunload', () => window.__heroAnimationCleanup?.());
                window.addEventListener('pagehide', () => window.__heroAnimationCleanup?.());
                document.addEventListener('livewire:navigate', () => window.__heroAnimationCleanup?.());
            }
        })();
    </script>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contact->hasWhatsapp() || $contact->hasPhone()): ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contact->hasPhone()): ?>
                    <a
                        href="<?php echo e($contact->telHref($contact->primaryPhone)); ?>"
                        class="flex min-h-[44px] flex-1 items-center justify-center gap-2 rounded-full bg-[#DC2626] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-[#DC2626]/30 transition-all hover:bg-[#B91C1C] active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                    >
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span><?php echo e(__('site.home.sticky_call')); ?></span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contact->hasWhatsapp()): ?>
                    <a
                        href="<?php echo e($contact->whatsappHref()); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex min-h-[44px] flex-1 items-center justify-center gap-2 rounded-full border border-emerald-400/25 bg-white/5 px-4 py-3 text-sm font-bold text-[#FAFAFA] transition-all hover:border-emerald-400/50 hover:bg-emerald-400/10 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400/70"
                    >
                        <svg class="h-5 w-5 flex-shrink-0 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.182-.008-.391-.011-.6-.011-.207 0-.544.079-.829.396-.286.319-1.095 1.068-1.095 2.617 0 1.55 1.095 3.05 1.248 3.26.153.21 2.153 3.3 5.223 4.63.73.315 1.3.483 1.743.618.753.225 1.438.193 1.983.117.604-.09 1.84-.752 2.103-1.479.262-.727.262-1.349.183-1.479-.079-.13-.296-.203-.594-.351m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.398 0 .16 5.237.157 11.716c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.504 0 11.79-5.286 11.793-11.793a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span><?php echo e(__('site.home.sticky_whatsapp')); ?></span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

            let heroObserver = null;

            if (hero && 'IntersectionObserver' in window) {
                heroObserver = new IntersectionObserver(
                    (entries) => {
                        heroVisible = entries[0].isIntersecting;
                        evaluate();
                    },
                    { threshold: 0 }
                );
                heroObserver.observe(hero);
            } else {
                heroVisible = false;
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll, { passive: true });
            evaluate();

            // Re-runs on every view of the home page, so one document-level teardown is
            // bound and handed the newest disposer each time. pagehide never fires on a
            // wire:navigate, so the observer and listeners would otherwise leak per visit.
            window.__stickyBarCleanup = function cleanup() {
                window.removeEventListener('scroll', onScroll);
                window.removeEventListener('resize', onScroll);
                heroObserver?.disconnect();
            };

            if (! window.__stickyBarTeardownBound) {
                window.__stickyBarTeardownBound = true;
                window.addEventListener('pagehide', () => window.__stickyBarCleanup?.());
                document.addEventListener('livewire:navigate', () => window.__stickyBarCleanup?.());
            }
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