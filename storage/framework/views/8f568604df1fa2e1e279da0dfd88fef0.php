<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assetsPublished): ?>
        <link rel="shortcut icon" href="<?php echo e(asset(mix('img/log-viewer-32.png', config('log-viewer.assets_path')))); ?>">
    <?php else: ?>
        <?php echo \Opcodes\LogViewer\Facades\LogViewer::favicon(); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <title>Log Viewer<?php echo e(config('app.name') ? ' - ' . config('app.name') : ''); ?></title>

    <!-- Style sheets-->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assetsPublished): ?>
        <link href="<?php echo e(asset(mix('app.css', config('log-viewer.assets_path')))); ?>" rel="stylesheet" onerror="alert('app.css failed to load. Please refresh the page, re-publish Log Viewer assets, or fix routing for vendor assets.')">
    <?php else: ?>
        <?php echo \Opcodes\LogViewer\Facades\LogViewer::css(); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>

<body class="h-full px-3 lg:px-5 bg-gray-100 dark:bg-gray-900">
<div id="log-viewer" class="flex h-full max-h-screen max-w-full">
    <router-view></router-view>
</div>

<!-- Global LogViewer Object -->
<script>
    window.LogViewer = <?php echo json_encode($logViewerScriptVariables, 15, 512) ?>;

    // Add additional headers for LogViewer requests like so:
    // window.LogViewer.headers['Authorization'] = 'Bearer xxxxxxx';
</script>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assetsPublished): ?>
    <script src="<?php echo e(asset(mix('app.js', config('log-viewer.assets_path')))); ?>" onerror="alert('app.js failed to load. Please refresh the page, re-publish Log Viewer assets, or fix routing for vendor assets.')"></script>
<?php else: ?>
    <?php echo \Opcodes\LogViewer\Facades\LogViewer::js(); ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\vendor\opcodesio\log-viewer\resources\views\index.blade.php ENDPATH**/ ?>