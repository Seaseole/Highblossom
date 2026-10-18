<?php ($custom = filled($faviconSet['v'] ?? null)); ?>
<?php ($iconVersion = $custom ? $faviconSet['v'] : config('icons.version', '1')); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $custom && ($favicon ?? false)): ?>
    <link rel="icon" href="<?php echo e(media_url($favicon)); ?>" sizes="any" />
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<link rel="icon" href="<?php echo e(asset('favicon.ico?v='.$iconVersion)); ?>" sizes="any" />
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $custom): ?>
    <link rel="icon" href="<?php echo e(asset('favicon.svg?v='.$iconVersion)); ?>" type="image/svg+xml" />
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<link rel="icon" href="<?php echo e(asset('favicon-48x48.png?v='.$iconVersion)); ?>" type="image/png" sizes="48x48" />
<link rel="icon" href="<?php echo e(asset('favicon-96x96.png?v='.$iconVersion)); ?>" type="image/png" sizes="96x96" />
<link rel="apple-touch-icon" href="<?php echo e(asset('apple-touch-icon.png?v='.$iconVersion)); ?>" />
<link rel="manifest" href="<?php echo e(route('manifest')); ?>" />
<meta name="theme-color" content="#b01828" />
<?php /**PATH C:\laragon\www\Highblossom\resources\views/partials/favicons.blade.php ENDPATH**/ ?>