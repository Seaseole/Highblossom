<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-gray-100">
    <div class="flex min-h-screen">
        <div class="hidden items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800 p-12 lg:flex lg:w-1/2">
            <div class="text-white">
                <h1 class="mb-4 text-4xl font-bold"><?php echo e(config('app.name')); ?></h1>
                <p class="text-lg opacity-90">Welcome back</p>
            </div>
        </div>
        <div class="flex w-full items-center justify-center p-8 lg:w-1/2">
            <div class="w-full max-w-md"><?php echo e($slot); ?></div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\layouts\auth\split-simple.blade.php ENDPATH**/ ?>