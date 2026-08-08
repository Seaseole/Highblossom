<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-end justify-between gap-3 flex-wrap">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
                <i data-lucide="bell-ring" class="text-[18px]"></i>
            </span>
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Alerts</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Proactive notifications when failure thresholds are crossed.
                </p>
            </div>
        </div>
        <a href="<?php echo e(route('jobs-monitor.settings')); ?>"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
            <i data-lucide="arrow-left" class="text-[14px]"></i>
            Back to settings
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('jobs_monitor_status')): ?>
        <div class="flex items-start gap-3 rounded-lg border border-success/25 bg-success/10 text-success px-4 py-3 text-sm">
            <i data-lucide="check-circle-2" class="text-[16px] mt-0.5"></i>
            <div><?php echo e(session('jobs_monitor_status')); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo $__env->make('jobs-monitor::settings.alerts._toggle', ['alerts' => $alerts], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alerts->enabled): ?>
        <?php echo $__env->make('jobs-monitor::settings.alerts._scalars', ['alerts' => $alerts], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('jobs-monitor::settings.alerts._channels', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('jobs-monitor::settings.alerts._recipients', ['alerts' => $alerts], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('jobs-monitor::settings.alerts._rules', [
            'rulesOverview' => $rulesOverview,
            'editing' => $editing,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
        <div class="rounded-xl border border-border bg-card px-6 py-10 text-center">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-muted text-muted-foreground mb-3">
                <i data-lucide="bell-off" class="text-xl"></i>
            </div>
            <p class="text-sm font-medium">Alerts are currently disabled</p>
            <p class="text-xs text-muted-foreground mt-1">Enable them above to configure source name, monitor URL, recipients and rules.</p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php echo $__env->make('jobs-monitor::partials.kebab-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('jobs-monitor::partials.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('jobs-monitor::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\alerts\index.blade.php ENDPATH**/ ?>