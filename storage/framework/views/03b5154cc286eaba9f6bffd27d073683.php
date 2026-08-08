<?php
    $currentRoute = request()->route()?->getName() ?? '';
    $user = auth()->user();

    $group = [
        'dashboard' => ['label' => 'Overview', 'icon' => 'home', 'routes' => ['dashboard']],
        'bookings' => ['label' => 'Bookings', 'icon' => 'calendar', 'routes' => ['admin.bookings', 'admin.inspections', 'admin.quotes']],
        'content' => ['label' => 'Content', 'icon' => 'document', 'routes' => ['admin.about-us', 'admin.testimonials', 'admin.services', 'admin.gallery', 'admin.gallery-categories', 'admin.partners', 'admin.staff', 'admin.glass-types', 'admin.service-types', 'admin.contact-messages']],
        'blog' => ['label' => 'Blog', 'icon' => 'newspaper', 'routes' => ['admin.posts', 'admin.categories', 'admin.tags']],
        'access' => ['label' => 'Team & Access', 'icon' => 'users', 'routes' => ['admin.users', 'admin.roles']],
        'media' => ['label' => 'Media', 'icon' => 'image', 'routes' => ['admin.media-library']],
        'system' => ['label' => 'System', 'icon' => 'cog', 'routes' => ['admin.settings', 'admin.seo']],
    ];

    $isRouteActive = fn ($route) => str_starts_with($currentRoute, $route.'.') || $currentRoute === $route || ($route === 'dashboard' && $currentRoute === 'dashboard');
    $isGroupActive = fn ($groupRoutes) => collect($groupRoutes)->contains(fn ($r) => $isRouteActive($r));
    $getRouteName = fn ($route) => route($route === 'dashboard' ? 'dashboard' : ($route === 'admin.about-us' ? 'admin.about-us.edit' : ($route === 'admin.seo' ? 'admin.seo.static-routes' : $route.'.index')));
    $getRouteLabel = fn ($route) => [
        'dashboard' => 'Dashboard', 'admin.bookings' => 'Bookings', 'admin.inspections' => 'Inspections', 'admin.quotes' => 'Quotes',
        'admin.about-us' => 'About Us', 'admin.testimonials' => 'Testimonials', 'admin.services' => 'Services', 'admin.gallery' => 'Gallery',
        'admin.gallery-categories' => 'Gallery Categories', 'admin.partners' => 'Partners', 'admin.staff' => 'Staff',
        'admin.glass-types' => 'Glass Types', 'admin.service-types' => 'Service Types', 'admin.contact-messages' => 'Messages',
        'admin.posts' => 'Posts', 'admin.categories' => 'Categories', 'admin.tags' => 'Tags', 'admin.users' => 'Users',
        'admin.roles' => 'Roles', 'admin.media-library' => 'Media', 'admin.settings' => 'Settings', 'admin.seo' => 'SEO',
    ][$route] ?? ucfirst(str_replace(['admin.', '-'], ['', ' '], $route));
    $badges = [
        'admin.users' => $userCount,
    ];
?>

<div>
    
    <div
        x-show="$store.mobileMenu.open"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        @click="$store.mobileMenu.close()"
        aria-hidden="true"
    ></div>

    
    <div
        id="sidebar-panel"
        x-show="$store.mobileMenu.open"
        x-transition:enter="transition-transform ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 lg:hidden"
        @click.outside="$store.mobileMenu.close()"
    >
        <div class="flex h-full flex-col border-r border-gray-200 bg-white shadow-2xl dark:border-white/5 dark:bg-[#0A0A0F]">
            
            <div class="flex h-16 items-center border-b border-gray-100 px-6 dark:border-white/5">
                <a href="/" class="flex items-center gap-3" aria-label="Go to homepage">
                    <div class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-xl bg-gray-900 text-white dark:bg-white dark:text-gray-900">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($companyName); ?>" class="size-full object-cover" />
                        <?php else: ?>
                            <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($companyName); ?></span>
                </a>
                <button
                    @click="$store.mobileMenu.close()"
                    x-ref="closeBtn"
                    class="ml-auto flex size-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 lg:hidden dark:hover:bg-white/5 dark:hover:text-white"
                    aria-label="Close navigation"
                >
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            
            <nav class="flex-1 space-y-2 overflow-y-auto p-4" role="navigation" aria-label="Admin navigation">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $active = $isGroupActive($groupData['routes']); ?>
                    <div x-data="{ open: <?php echo \Illuminate\Support\Js::from($active)->toHtml() ?> }">
                        <button
                            @click="open = ! open"
                            class="flex w-full items-center justify-between px-3 py-2 text-[0.7rem] font-bold tracking-wider text-gray-400 uppercase hover:text-gray-900 dark:text-gray-500 dark:hover:text-white"
                            :aria-expanded="open.toString()"
                        >
                            <?php echo e($groupData['label']); ?>

                            <svg class="size-3 transition-transform duration-200" :class="open
                                    ? 'rotate-180'
                                    : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" x-collapse.duration.200ms class="space-y-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groupData['routes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <a
                                    href="<?php echo e($getRouteName($route)); ?>"
                                    @click="$store.mobileMenu.close()"
                                    <?php if(isset($badges[$route])): ?>
                                        aria-label="<?php echo e($getRouteLabel($route)); ?>, <?php echo e($badges[$route]); ?> total"
                                    <?php endif; ?>
                                    class="flex items-center justify-between gap-3 px-3 py-2 min-h-[44px] rounded-xl text-sm font-medium transition-all <?php echo e($isRouteActive($route) ? 'bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>"
                                >
                                    <span><?php echo e($getRouteLabel($route)); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($badges[$route])): ?>
                                        <span
                                            aria-hidden="true"
                                            :class="isDark()
                                                ? 'bg-white/15 text-gray-100'
                                                : 'bg-gray-100 text-gray-700'"
                                            class="min-w-[1.5rem] rounded-full px-2 py-0.5 text-center text-[0.7rem] font-semibold"
                                        >
                                            <?php echo e(\Illuminate\Support\Number::abbreviate($badges[$route], maxPrecision: 1)); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <p class="px-3 py-6 text-center text-sm text-gray-400 dark:text-gray-500">
                        No navigation items available.
                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>

            
            <div class="space-y-2 border-t border-gray-100 p-4 dark:border-white/5">
                <button
                    wire:click="toggleTheme"
                    class="flex min-h-[44px] w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5"
                >
                    Theme
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs capitalize dark:bg-white/10"><?php echo e($theme); ?></span>
                </button>
                <a
                    href="<?php echo e(route('admin.profile.index')); ?>"
                    class="flex min-h-[44px] items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5"
                >
                    <div class="flex size-8 items-center justify-center rounded-full bg-gray-200 text-xs font-bold text-gray-700 dark:bg-white/10 dark:text-gray-300">
                        <?php echo e($user?->initials() ?? '?'); ?>

                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate"><?php echo e($user?->name ?? 'Guest'); ?></p>
                    </div>
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button
                        type="submit"
                        class="min-h-[44px] w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/20"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="hidden border-r border-gray-200 bg-white lg:flex lg:h-full lg:w-64 lg:flex-col dark:border-white/5 dark:bg-[#0A0A0F]">
        
        <div class="flex h-16 items-center border-b border-gray-100 px-6 dark:border-white/5">
            <a href="/" class="flex items-center gap-3" aria-label="Go to homepage">
                <div class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-xl bg-gray-900 text-white dark:bg-white dark:text-gray-900">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoUrl): ?>
                        <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($companyName); ?>" class="size-full object-cover" />
                    <?php else: ?>
                        <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <span class="font-semibold text-gray-900 dark:text-white"><?php echo e($companyName); ?></span>
            </a>
        </div>

        
        <nav class="flex-1 space-y-2 overflow-y-auto p-4" role="navigation" aria-label="Admin navigation">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php $active = $isGroupActive($groupData['routes']); ?>
                <div x-data="{ open: <?php echo \Illuminate\Support\Js::from($active)->toHtml() ?> }">
                    <button
                        @click="open = ! open"
                        class="flex w-full items-center justify-between px-3 py-2 text-[0.7rem] font-bold tracking-wider text-gray-400 uppercase hover:text-gray-900 dark:text-gray-500 dark:hover:text-white"
                        :aria-expanded="open.toString()"
                    >
                        <?php echo e($groupData['label']); ?>

                        <svg class="size-3 transition-transform duration-200" :class="open
                                ? 'rotate-180'
                                : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-collapse.duration.200ms class="space-y-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groupData['routes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a
                                href="<?php echo e($getRouteName($route)); ?>"
                                <?php if(isset($badges[$route])): ?>
                                    aria-label="<?php echo e($getRouteLabel($route)); ?>, <?php echo e($badges[$route]); ?> total"
                                <?php endif; ?>
                                class="flex items-center justify-between gap-3 px-3 py-2 min-h-[44px] rounded-xl text-sm font-medium transition-all <?php echo e($isRouteActive($route) ? 'bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>"
                            >
                                <span><?php echo e($getRouteLabel($route)); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($badges[$route])): ?>
                                    <span
                                        aria-hidden="true"
                                        :class="isDark() ? 'bg-white/15 text-gray-100' : 'bg-gray-100 text-gray-700'"
                                        class="min-w-[1.5rem] rounded-full px-2 py-0.5 text-center text-[0.7rem] font-semibold"
                                    >
                                        <?php echo e(\Illuminate\Support\Number::abbreviate($badges[$route], maxPrecision: 1)); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <p class="px-3 py-6 text-center text-sm text-gray-400 dark:text-gray-500">
                    No navigation items available.
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </nav>

        
        <div class="space-y-2 border-t border-gray-100 p-4 dark:border-white/5">
            <button
                wire:click="toggleTheme"
                class="flex min-h-[44px] w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5"
            >
                Theme
                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs capitalize dark:bg-white/10"><?php echo e($theme); ?></span>
            </button>
            <a
                href="<?php echo e(route('admin.profile.index')); ?>"
                class="flex min-h-[44px] items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-white/5"
            >
                <div class="flex size-8 items-center justify-center rounded-full bg-gray-200 text-xs font-bold text-gray-700 dark:bg-white/10 dark:text-gray-300">
                    <?php echo e($user?->initials() ?? '?'); ?>

                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate"><?php echo e($user?->name ?? 'Guest'); ?></p>
                </div>
            </a>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button
                    type="submit"
                    class="min-h-[44px] w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/20"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/livewire/admin-sidebar.blade.php ENDPATH**/ ?>