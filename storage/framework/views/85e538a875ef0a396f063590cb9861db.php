<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight flex items-center gap-2">
                <i data-lucide="cpu" class="text-brand text-[22px]"></i>
                Workers
            </h1>
            <p class="text-sm text-muted-foreground mt-1">
                Live heartbeat view. Silent after <?php echo e($vm->silentAfterSeconds); ?>s of no pulse.
                Auto-refreshes every <?php echo e($vm->silentAfterSeconds); ?>s.
            </p>
        </div>
    </div>

    <div id="workers-live" class="space-y-6">
        <?php echo $__env->make('jobs-monitor::partials.workers-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<?php echo $__env->make('jobs-monitor::partials.kebab-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('jobs-monitor::partials.workers-auto-refresh', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('jobs-monitor::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\workers.blade.php ENDPATH**/ ?>