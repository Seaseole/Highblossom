<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        <?php echo e(filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel')); ?>

    </title>
    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />

    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />

    
    <style>
        .safe-area-top {
            padding-top: env(safe-area-inset-top, 0px);
        }
        .safe-area-bottom {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        .safe-area-left {
            padding-left: env(safe-area-inset-left, 0px);
        }
        .safe-area-right {
            padding-right: env(safe-area-inset-right, 0px);
        }
    </style>

    
    <script>
        (function () {
            const theme = <?php echo json_encode($theme, 15, 512) ?>;
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            // Apply class immediately to prevent FOUT
            if (theme === 'dark' || (theme === 'auto' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            // Sync both our key and Flux's key so they stay in agreement
            localStorage.setItem('theme', theme);
            localStorage.setItem('flux.appearance', theme === 'auto' ? 'system' : theme);
        })();
    </script>

    <?php echo $__env->make('partials.cloak', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/admin.js']); ?>
    <script src="<?php echo e(asset('vendor/ckeditor/ckeditor.js')); ?>"></script>

    <style>
        /* Base background colors to prevent flash */
        html {
            background-color: #ffffff; /* White */
        }
        html.dark {
            background-color: #0a0a0f; /* Dark Background */
        }
    </style>
</head>
<body
    class="admin-panel min-h-[100dvh] bg-white font-sans text-gray-900 antialiased dark:bg-[#0A0A0F] dark:text-gray-100"
    x-data
    @keydown.escape.window="$store.mobileMenu.close()"
>
    
    <div class="safe-area-top fixed inset-x-0 top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white/95 px-4 backdrop-blur-sm lg:hidden dark:border-white/10 dark:bg-[#0A0A0F]/95">
        <button
            id="mobile-menu-btn"
            @click="$store.mobileMenu.toggle()"
            class="-ml-2 flex size-11 items-center justify-center text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            aria-label="Toggle navigation"
            :aria-expanded="$store.mobileMenu.open.toString()"
            aria-controls="sidebar-panel"
        >
            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <span class="truncate text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($title ?? config('app.name')); ?></span>
        <div class="size-11"></div>
    </div>

    <div class="flex min-h-[100dvh] pt-16 lg:pt-0">
        <!-- Livewire Admin Sidebar -->
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin-sidebar', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1423432684-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

        <!-- Main Content -->
        <main class="admin-main flex-1 overflow-auto bg-white dark:bg-[#0A0A0F]">
            <div class="p-8"><?php echo e($slot); ?></div>
        </main>
    </div>

    </div>

    
    <?php
        $environment = config('app.env');
        $envColors = [
            'production' => 'bg-red-500/20 border-red-500/30 text-red-600',
            'staging' => 'bg-yellow-500/20 border-yellow-500/30 text-yellow-600',
            'development' => 'bg-blue-500/20 border-blue-500/30 text-blue-600',
            'local' => 'bg-emerald-500/20 border-emerald-500/30 text-emerald-600',
        ];
        $envColor = $envColors[$environment] ?? $envColors['development'];
        $envDotColors = [
            'production' => 'bg-red-500',
            'staging' => 'bg-yellow-500',
            'development' => 'bg-blue-500',
            'local' => 'bg-emerald-500',
        ];
        $envDotColor = $envDotColors[$environment] ?? $envDotColors['development'];
    ?>

    <div class="fixed top-6 right-6 z-[8000]">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border backdrop-blur-sm <?php echo e($envColor); ?>">
            <span class="relative flex items-center justify-center">
                <span class="absolute inline-flex h-2 w-2 rounded-full <?php echo e($envDotColor); ?> animate-pulse-dot"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full <?php echo e($envDotColor); ?> opacity-75"></span>
            </span>
            <span class="text-xs font-semibold tracking-wide uppercase"><?php echo e($environment); ?></span>
        </div>
    </div>

    <style>
        @keyframes pulse-dot {
            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.25);
            }
        }

        .animate-pulse-dot {
            animation: pulse-dot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    
    <?php if (isset($component)) { $__componentOriginal9cfc3beab136e34fee0ad082864e0174 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9cfc3beab136e34fee0ad082864e0174 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.toaster','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.toaster'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9cfc3beab136e34fee0ad082864e0174)): ?>
<?php $attributes = $__attributesOriginal9cfc3beab136e34fee0ad082864e0174; ?>
<?php unset($__attributesOriginal9cfc3beab136e34fee0ad082864e0174); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9cfc3beab136e34fee0ad082864e0174)): ?>
<?php $component = $__componentOriginal9cfc3beab136e34fee0ad082864e0174; ?>
<?php unset($__componentOriginal9cfc3beab136e34fee0ad082864e0174); ?>
<?php endif; ?>

    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('mobileMenu', {
                open: false,
                toggle() {
                    this.open = !this.open;
                },
                close() {
                    this.open = false;
                },
            });
        });
    </script>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php app('livewire')->forceAssetInjection(); ?>
<?php echo app('flux')->scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/layouts/admin.blade.php ENDPATH**/ ?>