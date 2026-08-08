<?php echo $__env->make('Decomposer::components.report_component', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="flex flex-col lg:flex-row gap-6 mt-6">

    <!-- Package & Dependency column -->
    <?php echo $__env->make('Decomposer::components.packages_datatable', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- / Package & Dependency column -->

    <!-- Server Environment column -->
    <div class="w-full lg:w-1/3 space-y-6">

        <!-- Laravel Environment -->
        <?php echo $__env->make('Decomposer::components.laravel_env', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Server Environment -->
        <?php echo $__env->make('Decomposer::components.server_env', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($extraStats)): ?>
        <!-- Extra Stats -->
        <?php echo $__env->make('Decomposer::components.extra_stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div> <!-- / Server Environment column -->

</div><?php /**PATH C:\laragon\www\Highblossom\vendor\lubusin\laravel-decomposer\src\views\index.blade.php ENDPATH**/ ?>