<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => 'Error']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => 'Error']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($title); ?> - <?php echo e($settings->get('company_name', 'Highblossom')); ?></title>
    <link rel="icon" href="/favicon.ico" sizes="any" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet"
    />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        @keyframes error-entrance {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-error-entrance {
            animation: error-entrance 250ms cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }
    </style>
</head>
<body class="font-body flex min-h-screen flex-col bg-[#0A0A0F] text-[#FAFAFA] antialiased">
    <!-- Logo -->
    <div class="px-6 py-8">
        <a href="/" class="group inline-flex items-center gap-3">
            <?php
                $businessLogo = $settings->get('business_logo', '');
                $logoText = $settings->get('logo_text', 'Highblossom');
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($businessLogo): ?>
                <img
                    src="<?php echo e(Storage::url($businessLogo)); ?>"
                    alt="<?php echo e($logoText); ?>"
                    class="h-10 w-auto rounded-lg object-contain transition-transform duration-200 group-hover:scale-105"
                />
            <?php else: ?>
                <span class="font-headline text-2xl font-bold text-[#FAFAFA] transition-transform duration-200 group-hover:scale-105">
                    <?php echo e($logoText); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </a>
    </div>

    <!-- Content -->
    <main class="flex flex-1 items-center justify-center px-6 pb-8"><?php echo e($slot); ?></main>

    <!-- Footer -->
    <footer class="px-6 py-6 text-center">
        <p class="text-sm text-[#A1A1AA]">
            &copy; <?php echo e(date('Y')); ?> <?php echo e($settings->get('company_name', 'Highblossom Pty Ltd')); ?>. All rights reserved.
        </p>
    </footer>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/error-layout.blade.php ENDPATH**/ ?>