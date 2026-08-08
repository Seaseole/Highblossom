<?php if (isset($component)) { $__componentOriginal52b6740a4059545a9135423805a466b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52b6740a4059545a9135423805a466b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::site','data' => ['title' => $galleryImage->title.' | Project Details']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($galleryImage->title.' | Project Details')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <!-- Project Hero Section -->
    <section class="relative bg-[#0A0A0F] pt-32 pb-20">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="flex flex-col gap-12 lg:flex-row">
                <!-- Image Column -->
                <div class="lg:w-2/3">
                    <div class="group glass-card relative overflow-hidden rounded-3xl border border-white/5">
                        <img
                            src="<?php echo e($galleryImage->image_url); ?>"
                            alt="<?php echo e($galleryImage->title); ?>"
                            class="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/60 via-transparent to-transparent"></div>
                    </div>
                </div>

                <!-- Info Column -->
                <div class="flex flex-col justify-center lg:w-1/3">
                    <nav class="mb-8 flex items-center gap-2 text-sm text-[#A1A1AA]">
                        <a
                            href="<?php echo e(route('gallery')); ?>"
                            class="transition-colors hover:text-[#DC2626]"
                        ><?php echo e(__('gallery-show.gallery')); ?></a>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-[#DC2626]"><?php echo e($galleryImage->category->name ?? '-'); ?></span>
                    </nav>

                    <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                        <?php echo e($galleryImage->title); ?>

                    </h1>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($galleryImage->description): ?>
                        <div class="prose prose-invert mb-10 max-w-none">
                            <p class="text-lg leading-relaxed text-[#A1A1AA]"><?php echo e($galleryImage->description); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="mb-10 grid grid-cols-1 gap-6">
                        <div class="glass-card rounded-2xl border border-white/5 p-6">
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                    <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">
                                        <?php echo e(__('gallery-show.installation_site')); ?>

                                    </div>
                                    <div class="font-medium text-[#FAFAFA]">
                                        <?php echo e($galleryImage->location_address ?? __('gallery-show.default_location')); ?>

                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($galleryImage->google_maps_url): ?>
                                        <a
                                            href="<?php echo e($galleryImage->google_maps_url); ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="mt-2 inline-flex items-center text-sm text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                                        >
                                            <span><?php echo e(__('gallery-show.open_in_maps')); ?></span>
                                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card rounded-2xl border border-white/5 p-6">
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#DC2626]/10">
                                    <svg class="h-5 w-5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="mb-1 text-xs font-semibold tracking-wider text-[#DC2626] uppercase">
                                        <?php echo e(__('gallery-show.quality_assurance')); ?>

                                    </div>
                                    <div class="font-medium text-[#FAFAFA]"><?php echo e(__('gallery-show.warranty_text')); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="<?php echo e(route('quote')); ?>" class="btn-premium w-full justify-center">
                        <span><?php echo e(__('gallery-show.request_similar')); ?></span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Projects Section -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($relatedImages->count() > 0): ?>
        <section class="border-t border-white/5 bg-[#0A0A0F] py-24">
            <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
                <div class="mb-12 flex items-end justify-between">
                    <div>
                        <div class="mb-4 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                            <?php echo e(__('gallery-show.portfolio_label')); ?>

                        </div>
                        <h2 class="font-headline text-3xl font-bold tracking-tight text-[#FAFAFA] md:text-4xl">
                            <?php echo e(__('gallery-show.related_projects')); ?>

                        </h2>
                    </div>
                    <a
                        href="<?php echo e(route('gallery', ['category' => $galleryImage->category])); ?>"
                        class="hidden items-center gap-2 font-semibold text-[#A1A1AA] transition-colors hover:text-[#DC2626] md:flex"
                    >
                        <span><?php echo e(__('gallery-show.view_all')); ?> <?php echo e(str_replace('_', ' ', ucfirst($galleryImage->category->name))); ?></span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $relatedImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route('gallery.show', $related)); ?>" class="group block">
                            <div class="glass-card relative mb-4 aspect-[4/3] overflow-hidden rounded-2xl border border-white/5">
                                <img
                                    src="<?php echo e($related->image_url); ?>"
                                    alt="<?php echo e($related->title); ?>"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F]/80 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            </div>
                            <h3 class="font-headline font-bold text-[#FAFAFA] transition-colors group-hover:text-[#DC2626]">
                                <?php echo e($related->title); ?>

                            </h3>
                            <p class="mt-1 text-sm text-[#A1A1AA]">
                                <?php echo e(str_replace('_', ' ', ucfirst($related->category->name))); ?>

                            </p>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- CTA Section -->
    <section class="border-t border-white/5 bg-gradient-to-b from-[#0A0A0F] to-[#121218] py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="glass-card relative overflow-hidden rounded-[2.5rem] border border-white/5 p-12 text-center md:p-20">
                <div class="absolute top-0 left-0 h-full w-full bg-[radial-gradient(circle_at_center,rgba(220,38,38,0.05)_0,transparent_70%)]"></div>

                <div class="relative z-10 mx-auto max-w-3xl">
                    <h2 class="font-headline mb-8 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                        <?php echo __('gallery-show.cta_title'); ?>

                    </h2>
                    <p class="mb-12 text-xl leading-relaxed text-[#A1A1AA]"><?php echo e(__('gallery-show.cta_description')); ?></p>
                    <div class="flex flex-col items-center justify-center gap-6 sm:flex-row">
                        <a href="<?php echo e(route('quote')); ?>" class="btn-premium w-full sm:w-auto">
                            <span><?php echo e(__('gallery-show.get_free_quote')); ?></span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="<?php echo e(route('contact')); ?>" class="btn-ghost w-full sm:w-auto">
                            <span><?php echo e(__('gallery-show.contact_team')); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views\site\gallery-show.blade.php ENDPATH**/ ?>