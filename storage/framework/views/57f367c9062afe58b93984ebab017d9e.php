<nav
    id="public-main-nav"
    class="sticky top-0 z-40 flex w-full flex-col border-b border-white/5 bg-[#0A0A0F]/90 backdrop-blur-xl"
>
    <?php
        $isAnnounceActive = $announcementActive ?? $settings->get('announcement_active', false);
        $announceList = $announcements ?? $settings->get('announcements', []);
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAnnounceActive && ! empty($announceList)): ?>
        <style>
            .announcement-gradient {
                background: linear-gradient(90deg, #73081d 0%, #dc2626 50%, #73081d 100%);
            }
            .ticker-container {
                position: relative;
                display: flex;
                overflow: hidden;
                width: 100%;
            }
            .ticker-wrapper {
                display: flex;
                white-space: nowrap;
                animation: marquee-scroll 30s linear infinite;
                will-change: transform;
            }
            .ticker-wrapper:hover {
                animation-play-state: paused;
            }
            .ticker-item {
                display: inline-flex;
                align-items: center;
            }
            @keyframes marquee-scroll {
                0% {
                    transform: translate3d(0, 0, 0);
                }
                100% {
                    transform: translate3d(-33.333%, 0, 0);
                }
            }
            .shimmer-glow::after {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08) 50%, transparent);
                transform: translateX(-100%);
                animation: shimmer-swipe 8s infinite;
            }
            @keyframes shimmer-swipe {
                0% {
                    transform: translateX(-100%);
                }
                35%,
                100% {
                    transform: translateX(100%);
                }
            }
        </style>
        <div
            id="announcement-ticker-bar"
            class="announcement-gradient shimmer-glow relative flex w-full items-center overflow-hidden border-b border-white/10 px-4 py-1.5 text-white transition-all duration-300 ease-out select-none"
            style="height: 34px; min-height: 34px"
        >
            <div class="z-10 mx-auto flex w-full max-w-[1400px] items-center justify-between gap-4">
                <!-- Label on left -->
                <div class="flex flex-shrink-0 items-center gap-1.5">
                    <span class="material-symbols-outlined animate-pulse text-[15px] text-white/90">campaign</span>
                    <span class="font-headline rounded border border-white/20 bg-white/10 px-1.5 py-0.5 text-[9px] font-black tracking-widest text-white uppercase">Notice</span>
                </div>

                <!-- Scrolling marquee container -->
                <div class="relative mx-2 flex h-5 flex-1 items-center overflow-hidden">
                    <div class="ticker-container">
                        <div class="ticker-wrapper">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 0; $i < 3; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $announceList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php
                                        $link = $announcement['link'] ?? '';
                                        if (! empty($link) && ! str_starts_with($link, 'http') && Route::has($link)) {
                                            $link = route($link);
                                        }
                                    ?>
                                    <div class="ticker-item mx-4 text-[11px] font-medium tracking-wide text-white">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($link)): ?>
                                            <a
                                                href="<?php echo e($link); ?>"
                                                class="flex items-center gap-1 transition-colors hover:text-white/80"
                                            >
                                                <span><?php echo e($announcement['text']); ?></span>
                                                <span class="material-symbols-outlined text-[12px] opacity-80">arrow_forward</span>
                                            </a>
                                        <?php else: ?>
                                            <span><?php echo e($announcement['text']); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Close Button on right -->
                <button
                    type="button"
                    id="close-announcement-btn"
                    class="group relative flex-shrink-0 p-0.5 text-white/60 transition-colors hover:text-white"
                    aria-label="Close announcement"
                >
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="mx-auto flex w-full max-w-[1400px] items-center justify-between px-6 py-4 lg:px-8">
        
        <a href="<?php echo e(route('home')); ?>" class="transition-opacity hover:opacity-80">
            <?php if (isset($component)) { $__componentOriginalec02c762cb8fdc609b31e0340efa6a92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalec02c762cb8fdc609b31e0340efa6a92 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo-trust-badge','data' => ['businessName' => $logoText,'iconSrc' => $businessLogo ? Storage::url($businessLogo) : null,'fontSize' => 'text-lg lg:text-xl font-bold font-headline','badgeSize' => 10,'gap' => 'gap-1.5','badgeTop' => -2,'badgeRight' => -16]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo-trust-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['business-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logoText),'icon-src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($businessLogo ? Storage::url($businessLogo) : null),'font-size' => 'text-lg lg:text-xl font-bold font-headline','badge-size' => 10,'gap' => 'gap-1.5','badge-top' => -2,'badge-right' => -16]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalec02c762cb8fdc609b31e0340efa6a92)): ?>
<?php $attributes = $__attributesOriginalec02c762cb8fdc609b31e0340efa6a92; ?>
<?php unset($__attributesOriginalec02c762cb8fdc609b31e0340efa6a92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalec02c762cb8fdc609b31e0340efa6a92)): ?>
<?php $component = $__componentOriginalec02c762cb8fdc609b31e0340efa6a92; ?>
<?php unset($__componentOriginalec02c762cb8fdc609b31e0340efa6a92); ?>
<?php endif; ?>
        </a>

        
        <div class="hidden items-center gap-8 md:flex">
            <a
                href="<?php echo e(route('home')); ?>"
                class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?> font-headline font-semibold text-sm tracking-tight"
            >
                Home
            </a>
            <a
                href="<?php echo e(route('about-us')); ?>"
                class="nav-link <?php echo e(request()->routeIs('about-us') ? 'active' : ''); ?> font-headline font-semibold text-sm tracking-tight"
            >
                About Us
            </a>
            <a
                href="<?php echo e(route('services')); ?>"
                class="nav-link <?php echo e(request()->routeIs('services') ? 'active' : ''); ?> font-headline font-semibold text-sm tracking-tight"
            >
                Services
            </a>
            <a
                href="<?php echo e(route('gallery')); ?>"
                class="nav-link <?php echo e(request()->routeIs('gallery') ? 'active' : ''); ?> font-headline font-semibold text-sm tracking-tight"
            >
                Gallery
            </a>
            <a
                href="<?php echo e(route('blog')); ?>"
                class="nav-link <?php echo e(request()->routeIs('blog') ? 'active' : ''); ?> font-headline font-semibold text-sm tracking-tight"
            >
                Blog
            </a>
            <a href="<?php echo e(route('quote')); ?>" class="btn-premium px-5 py-2.5 text-sm"> Get Quote </a>
            <a href="<?php echo e(route('bookings.create')); ?>" class="btn-glass px-5 py-2.5 text-sm"> Book Inspection </a>
        </div>

        
        <div class="flex items-center gap-4">
            
            <div class="hidden items-center gap-3 md:flex">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facebookUrl): ?>
                    <a
                        href="<?php echo e($facebookUrl); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                        title="Facebook"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($instagramUrl): ?>
                    <a
                        href="<?php echo e($instagramUrl); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                        title="Instagram"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($linkedinUrl): ?>
                    <a
                        href="<?php echo e($linkedinUrl); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-[#A1A1AA] transition-colors hover:text-[#DC2626]"
                        title="LinkedIn"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a
                        href="<?php echo e(route('dashboard')); ?>"
                        class="font-headline hidden text-sm font-semibold text-[#A1A1AA] transition-colors hover:text-[#FAFAFA] lg:block"
                    >
                        Dashboard
                    </a>
                <?php else: ?>
                    <a
                        href="<?php echo e(route('login')); ?>"
                        class="font-headline hidden text-sm font-semibold text-[#A1A1AA] transition-colors hover:text-[#FAFAFA] lg:block"
                    >
                        Log in
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <button
                id="mobile-menu-btn"
                class="p-2 text-[#FAFAFA] transition-colors hover:text-[#DC2626] md:hidden"
                aria-label="Toggle menu"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</nav>


<div
    id="mobile-menu"
    class="mobile-menu fixed top-0 right-0 z-[60] h-[100dvh] h-screen w-full max-w-sm border-l border-white/10 bg-[#121218]"
    aria-hidden="true"
    aria-label="Mobile navigation menu"
>
    <div class="flex h-full flex-col">
        
        <div class="flex flex-shrink-0 items-center justify-between border-b border-white/10 px-6 py-4">
            <span class="font-headline text-lg font-bold text-[#FAFAFA]">Menu</span>
            <button
                id="mobile-menu-close"
                class="p-2 text-[#A1A1AA] transition-colors hover:text-[#FAFAFA]"
                aria-label="Close menu"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        
        <div class="flex flex-1 flex-col gap-6 overflow-y-auto px-6 py-8">
            <a
                href="<?php echo e(route('home')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('home') ? 'text-[#DC2626]' : ''); ?>"
            >
                Home
            </a>
            <a
                href="<?php echo e(route('about-us')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('about-us') ? 'text-[#DC2626]' : ''); ?>"
            >
                About Us
            </a>
            <a
                href="<?php echo e(route('services')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('services') ? 'text-[#DC2626]' : ''); ?>"
            >
                Services
            </a>
            <a
                href="<?php echo e(route('gallery')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('gallery') ? 'text-[#DC2626]' : ''); ?>"
            >
                Gallery
            </a>
            <a
                href="<?php echo e(route('blog')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('blog') ? 'text-[#DC2626]' : ''); ?>"
            >
                Blog
            </a>
            <a
                href="<?php echo e(route('quote')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('quote') ? 'text-[#DC2626]' : ''); ?>"
            >
                Get Quote
            </a>
            <a
                href="<?php echo e(route('bookings.create')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('bookings.create') ? 'text-[#DC2626]' : ''); ?>"
            >
                Book Inspection
            </a>
            <a
                href="<?php echo e(route('contact')); ?>"
                onclick="closeMobileMenu()"
                class="text-2xl font-headline font-bold text-[#FAFAFA] hover:text-[#DC2626] transition-colors <?php echo e(request()->routeIs('contact') ? 'text-[#DC2626]' : ''); ?>"
            >
                Contact
            </a>
        </div>

        
        <div class="flex-shrink-0 border-t border-white/10 px-6 py-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a
                        href="<?php echo e(route('dashboard')); ?>"
                        onclick="closeMobileMenu()"
                        class="flex items-center gap-3 text-[#A1A1AA] transition-colors hover:text-[#FAFAFA]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-headline font-semibold">Dashboard</span>
                    </a>
                <?php else: ?>
                    <a
                        href="<?php echo e(route('login')); ?>"
                        onclick="closeMobileMenu()"
                        class="flex items-center gap-3 text-[#A1A1AA] transition-colors hover:text-[#FAFAFA]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="font-headline font-semibold">Log in</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>


<div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>

<?php $__env->startPush('scripts'); ?>
    <script>
        (function () {
            'use strict';

            const TRANSITION_DURATION = 400; // ms — matches CSS .mobile-menu transition

            let isOpen = false;
            let closeTimer = null;

            function openMobileMenu() {
                if (isOpen) return;
                isOpen = true;

                const mobileMenu = document.getElementById('mobile-menu');
                const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');

                if (closeTimer) {
                    clearTimeout(closeTimer);
                    closeTimer = null;
                }

                if (mobileMenu) {
                    mobileMenu.removeAttribute('aria-hidden');
                    // Slight defer so the browser paints the element before we add .open
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => mobileMenu.classList.add('open'));
                    });
                }
                if (mobileMenuOverlay) mobileMenuOverlay.classList.add('open');
                if (mobileMenuBtn) mobileMenuBtn.setAttribute('aria-expanded', 'true');

                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                if (!isOpen) return;
                isOpen = false;

                const mobileMenu = document.getElementById('mobile-menu');
                const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');

                if (mobileMenu) mobileMenu.classList.remove('open');
                if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('open');
                if (mobileMenuBtn) mobileMenuBtn.setAttribute('aria-expanded', 'false');

                document.body.style.overflow = '';

                // Re-apply aria-hidden after the slide-out transition finishes
                closeTimer = setTimeout(() => {
                    const menu = document.getElementById('mobile-menu');
                    if (menu && !isOpen) menu.setAttribute('aria-hidden', 'true');
                    closeTimer = null;
                }, TRANSITION_DURATION);
            }

            // Expose globally for onclick handlers on anchor tags
            window.closeMobileMenu = closeMobileMenu;

            document.addEventListener('DOMContentLoaded', function () {
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileMenuClose = document.getElementById('mobile-menu-close');
                const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

                if (mobileMenuBtn) {
                    mobileMenuBtn.setAttribute('aria-expanded', 'false');
                    mobileMenuBtn.setAttribute('aria-controls', 'mobile-menu');
                    mobileMenuBtn.addEventListener('click', openMobileMenu);
                }
                if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeMobileMenu);
                if (mobileMenuOverlay) mobileMenuOverlay.addEventListener('click', closeMobileMenu);

                // Close on Escape key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && isOpen) closeMobileMenu();
                });

                // Close Announcement Bar
                const closeAnnouncementBtn = document.getElementById('close-announcement-btn');
                if (closeAnnouncementBtn) {
                    closeAnnouncementBtn.addEventListener('click', function () {
                        const bar = document.getElementById('announcement-ticker-bar');
                        if (bar) {
                            bar.style.height = '0px';
                            bar.style.paddingTop = '0px';
                            bar.style.paddingBottom = '0px';
                            bar.style.opacity = '0';
                            bar.style.borderBottomWidth = '0px';
                            sessionStorage.setItem('dismissed_announcement', 'true');
                        }
                    });
                }
            });
        })();
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/partials/site-nav.blade.php ENDPATH**/ ?>