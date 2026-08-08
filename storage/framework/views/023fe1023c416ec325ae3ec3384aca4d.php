<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-md"><?php echo e($slot); ?></div>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\layouts\auth\simple-simple.blade.php ENDPATH**/ ?>