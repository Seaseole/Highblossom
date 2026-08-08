<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">

<head>
    <?php echo $__env->make('Decomposer::layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body class="mx-8 my-4">
    <?php echo $__env->make('Decomposer::index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('Decomposer::layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html><?php /**PATH C:\laragon\www\Highblossom\vendor\lubusin\laravel-decomposer\src\views\app.blade.php ENDPATH**/ ?>