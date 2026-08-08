<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php if (isset($component)) { $__componentOriginal5d0a24dd43287eafaf3e24ec153646b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.meta','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.meta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3)): ?>
<?php $attributes = $__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3; ?>
<?php unset($__attributesOriginal5d0a24dd43287eafaf3e24ec153646b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d0a24dd43287eafaf3e24ec153646b3)): ?>
<?php $component = $__componentOriginal5d0a24dd43287eafaf3e24ec153646b3; ?>
<?php unset($__componentOriginal5d0a24dd43287eafaf3e24ec153646b3); ?>
<?php endif; ?>
    <link rel="icon" href="/favicon.ico" sizes="any" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <script src="/vendor/ckeditor/ckeditor.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js', 'resources/js/site-booking.js', 'resources/js/site-alpine.js']); ?>
    <?php echo $__env->make('partials.cloak', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .glass-card {
            background: #16161d;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }
        .primary-gradient {
            background: linear-gradient(135deg, #73081d 0%, #a93440 100%);
        }
        .swiper {
            width: 100%;
            padding-bottom: 40px;
        }
        .swiper-pagination-bullet-active {
            background-color: #73081d;
        }
        .text-wrap-balance {
            text-wrap: balance;
        }
        .text-wrap-pretty {
            text-wrap: pretty;
        }
        /* Base background color to prevent flash */
        html {
            background-color: #0a0a0f;
        }
    </style>
</head>
<body class="font-body bg-[#0A0A0F] text-[#FAFAFA] antialiased selection:bg-[#DC2626] selection:text-white">
    <?php echo $__env->make('partials.site-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main><?php echo e($slot); ?></main>

    <?php echo $__env->make('partials.site-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.whatsapp-fab', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.cookie-consent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Custom Toast Notification -->
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

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\layouts\site.blade.php ENDPATH**/ ?>