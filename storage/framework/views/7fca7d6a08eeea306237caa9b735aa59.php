<?php if (isset($component)) { $__componentOriginal52b6740a4059545a9135423805a466b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52b6740a4059545a9135423805a466b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::site','data' => ['title' => 'About Us']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts::site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'About Us']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <section class="bg-[#0A0A0F] pt-32 pb-24 lg:pb-32">
        <div class="mx-auto max-w-350 px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-10">
                <!-- Left Column (70%) - Title, Hero Image, Body -->
                <div class="lg:col-span-7">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->hero_image): ?>
                        <div class="about-hero js-scroll-with-image relative mb-8 overflow-hidden rounded-[2.5rem]">
                            <img
                                src="<?php echo e(Storage::url($content->hero_image)); ?>"
                                alt="About Highblossom"
                                class="h-[420px] w-full object-cover transition-transform duration-1000 ease-out hover:scale-[1.02]"
                            />
                            <div class="absolute inset-0 bg-linear-to-t from-[#0A0A0F]/90 via-[#0A0A0F]/20 to-transparent"></div>
                            <div class="feather-overlay pointer-events-none absolute inset-0"></div>
                            <div class="absolute inset-x-0 bottom-[2px] px-6 lg:px-12">
                                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-45 bg-linear-to-t from-white/91 via-white/40 to-transparent"></div>
                                <div class="relative max-w-3xl transform space-y-5 text-[#0B0B0F] transition-transform duration-300 ease-out">
                                    <div class="text-admin-accent text-sm font-semibold tracking-wider uppercase">
                                        About Us
                                    </div>
                                    <h1 class="font-headline text-4xl font-bold tracking-tight text-[#0B0B0F] md:text-5xl lg:text-6xl">
                                        <?php echo nl2br(e($content->title)); ?>

                                    </h1>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->subtitle): ?>
                                        <p class="max-w-2xl text-lg leading-relaxed text-[#0a0a0f]">
                                            <?php echo e($content->subtitle); ?>

                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-admin-accent mb-4 text-sm font-semibold tracking-wider uppercase">
                            About Us
                        </div>
                        <h1 class="font-headline mb-6 text-4xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl lg:text-6xl">
                            <?php echo nl2br(e($content->title)); ?>

                        </h1>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->subtitle): ?>
                            <p class="mb-8 text-lg leading-relaxed text-[#A1A1AA]"><?php echo e($content->subtitle); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="prose prose-invert prose-lg mb-20 max-w-none leading-relaxed text-[#A1A1AA]">
                        <?php echo $content->body; ?>

                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->isNotEmpty()): ?>
                        <div class="mt-20 border-t border-white/5 pt-20">
                            <div class="mb-12">
                                <div class="mb-3 text-sm font-semibold tracking-[0.2em] text-[#DC2626] uppercase">
                                    Excellence in Motion
                                </div>
                                <h2 class="font-headline text-3xl font-bold tracking-tight text-[#FAFAFA] md:text-5xl">
                                    Our Master Craftsmen
                                </h2>
                                <p class="mt-4 max-w-xl text-[#A1A1AA]">
                                    Meet the dedicated professionals who bring precision and care to every installation,
                                    ensuring your vehicle remains safe and beautiful.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="group relative overflow-hidden rounded-[2.5rem] border border-white/5 bg-[#121218] shadow-2xl transition-all duration-500 hover:border-[#DC2626]/20">
                                        
                                        <div class="relative h-[400px] overflow-hidden">
                                            <img
                                                src="<?php echo e($member->photo_url); ?>"
                                                alt="<?php echo e($member->name); ?>"
                                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            />
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0F] via-transparent to-transparent opacity-60 transition-opacity group-hover:opacity-40"></div>
                                        </div>

                                        
                                        <div class="relative p-8">
                                            <div class="mb-4">
                                                <h3 class="font-headline text-2xl font-bold text-[#FAFAFA] transition-colors group-hover:text-[#DC2626]">
                                                    <?php echo e($member->name); ?>

                                                </h3>
                                                <div class="mt-1 text-xs font-bold tracking-widest text-[#DC2626] uppercase">
                                                    <?php echo e($member->role); ?>

                                                </div>
                                            </div>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($member->bio): ?>
                                                <p class="line-clamp-3 text-sm leading-relaxed text-[#A1A1AA] transition-all duration-300 group-hover:line-clamp-none">
                                                    <?php echo e($member->bio); ?>

                                                </p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <div class="mt-6 flex gap-3">
                                                <div class="h-[2px] w-8 rounded-full bg-[#DC2626] transition-all group-hover:w-12"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Right Column (30%) - Vision & Mission -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->vision || $content->mission): ?>
                    <div class="space-y-6 lg:col-span-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->vision): ?>
                            <div class="glass-card rounded-2xl p-8">
                                <div class="text-admin-accent mb-4 text-sm font-semibold tracking-wider uppercase">
                                    Our Vision
                                </div>
                                <div class="leading-relaxed text-[#A1A1AA]"><?php echo $content->vision; ?></div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($content->mission): ?>
                            <div class="glass-card rounded-2xl p-8">
                                <div class="text-admin-accent mb-4 text-sm font-semibold tracking-wider uppercase">
                                    Our Mission
                                </div>
                                <div class="leading-relaxed text-[#A1A1AA]"><?php echo $content->mission; ?></div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH C:\laragon\www\Highblossom\resources\views/site/about-us.blade.php ENDPATH**/ ?>