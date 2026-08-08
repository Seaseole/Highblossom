<?php $__env->startSection('content'); ?>
<?php
    $monitorIsSource = $monitorStatus && $monitorStatus->rowCount > $defaultStatus->rowCount;
    $fromStatus      = $monitorIsSource ? $monitorStatus : $defaultStatus;
    $toStatus        = $monitorIsSource ? $defaultStatus : $monitorStatus;
?>

<div class="space-y-6">

    
    <div class="flex items-end justify-between gap-3 flex-wrap">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
                <i data-lucide="database" class="text-[18px]"></i>
            </span>
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Database Connection</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    View connection status and transfer monitoring data between databases.
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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('jobs_monitor_error')): ?>
        <div class="flex items-start gap-3 rounded-lg border border-destructive/25 bg-destructive/10 text-destructive px-4 py-3 text-sm">
            <i data-lucide="alert-circle" class="text-[16px] mt-0.5"></i>
            <div><?php echo e(session('jobs_monitor_error')); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="flex items-start gap-3 rounded-lg border border-destructive/25 bg-destructive/10 text-destructive px-4 py-3 text-sm">
            <i data-lucide="alert-circle" class="text-[16px] mt-0.5"></i>
            <ul class="list-disc list-inside">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li><?php echo e($error); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transferDone): ?>
        <div class="flex items-start gap-3 rounded-lg border border-success/25 bg-success/10 text-success px-4 py-3 text-sm">
            <i data-lucide="check-circle-2" class="text-[16px] mt-0.5"></i>
            <div><?php echo e($transferDone); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transferFailed): ?>
        <div class="flex items-start gap-3 rounded-lg border border-destructive/25 bg-destructive/10 text-destructive px-4 py-3 text-sm">
            <i data-lucide="alert-circle" class="text-[16px] mt-0.5"></i>
            <div><?php echo e($transferFailed); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transferPending): ?>
        <div id="jm-transfer-progress"
             class="flex items-center gap-3 rounded-lg border border-brand/25 bg-brand/10 text-brand px-4 py-3 text-sm">
            <svg class="animate-spin h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span>
                Transferring data from <strong><?php echo e($transferPending['from']); ?></strong>
                to <strong><?php echo e($transferPending['to']); ?></strong>… This may take a while for large datasets.
            </span>
        </div>
        <script>
        (function () {
            var url = '<?php echo e(route('jobs-monitor.settings.database.transfer-status')); ?>';
            function poll() {
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (r) {
                        if (! r.ok) { window.location.reload(); return null; }
                        return r.json();
                    })
                    .then(function (data) {
                        if (! data) { return; }
                        if (data.status === 'done' || data.status === 'failed' || data.status === 'idle') {
                            window.location.reload();
                        } else {
                            setTimeout(poll, 2000);
                        }
                    })
                    .catch(function () { setTimeout(poll, 5000); });
            }
            setTimeout(poll, 2000);
        })();
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="grid gap-4 md:grid-cols-2">

        
        <?php echo $__env->make('jobs-monitor::settings.database._connection-card', [
            'label'  => 'Default Connection',
            'status' => $defaultStatus,
            'active' => $monitorStatus === null,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitorStatus): ?>
            <?php echo $__env->make('jobs-monitor::settings.database._connection-card', [
                'label'  => 'Monitor Connection',
                'status' => $monitorStatus,
                'active' => true,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            
            <div class="rounded-xl border border-dashed border-border bg-card/50 text-card-foreground p-5 flex flex-col gap-3">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                        <i data-lucide="plug-zap" class="text-[16px]"></i>
                    </span>
                    <div>
                        <h2 class="text-base font-semibold tracking-tight text-muted-foreground">Monitor Connection</h2>
                        <p class="mt-1 text-sm text-muted-foreground">Not configured</p>
                    </div>
                    <span class="ml-auto inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium ring-1 ring-inset bg-muted text-muted-foreground ring-border">
                        <i data-lucide="power-off" class="text-[12px]"></i>
                        Not configured
                    </span>
                </div>
                <div class="rounded-lg bg-muted/50 px-4 py-3 text-xs text-muted-foreground space-y-1">
                    <p class="font-medium">To use a dedicated database:</p>
                    <p>1. Add a connection to <code class="px-1 rounded bg-muted">config/database.php</code></p>
                    <p>2. Set <code class="px-1 rounded bg-muted">JOBS_MONITOR_DB_CONNECTION=&lt;name&gt;</code> in <code class="px-1 rounded bg-muted">.env</code></p>
                    <p>3. Return to this page to transfer existing data</p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitorStatus): ?>
    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-xs">
        <div class="p-5">
            <div class="flex items-start gap-3 mb-5">
                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
                    <i data-lucide="arrow-right-left" class="text-[16px]"></i>
                </span>
                <div>
                    <h2 class="text-base font-semibold tracking-tight">Transfer Data</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Move all monitoring rows to the other database. Source tables are removed after transfer.
                    </p>
                </div>
            </div>

            
            <div class="flex items-center gap-3 rounded-lg border border-border bg-muted/30 px-4 py-3 text-sm">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-muted text-muted-foreground text-[11px] font-bold ring-1 ring-border">
                    <?php echo e(strtoupper(substr($fromStatus->driver, 0, 2))); ?>

                </span>
                <span class="font-mono font-medium"><?php echo e($fromStatus->name); ?></span>
                <span class="text-xs text-muted-foreground tabular-nums">(<?php echo e(number_format($fromStatus->rowCount)); ?> rows)</span>
                <i data-lucide="arrow-right" class="text-[14px] text-muted-foreground mx-1 shrink-0"></i>
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-brand/15 text-brand text-[11px] font-bold ring-1 ring-brand/20">
                    <?php echo e(strtoupper(substr($toStatus->driver, 0, 2))); ?>

                </span>
                <span class="font-mono font-medium"><?php echo e($toStatus->name); ?></span>
            </div>
        </div>

        <div class="flex justify-end px-5 pb-5">
            <form id="jm-transfer-form"
                  method="POST"
                  action="<?php echo e(route('jobs-monitor.settings.database.transfer')); ?>"
                  class="hidden">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="from"          value="<?php echo e($fromStatus->name); ?>">
                <input type="hidden" name="to"            value="<?php echo e($toStatus->name); ?>">
                <input type="hidden" name="delete_source" value="1">
            </form>
            <?php echo $__env->make('jobs-monitor::partials.button', [
                'variant' => 'brand',
                'icon'    => 'arrow-right-left',
                'label'   => 'Transfer Data',
                'attrs'   => 'onclick="document.getElementById(\'jm-transfer-modal\').classList.remove(\'hidden\')"',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($monitorStatus): ?>
<div id="jm-transfer-modal"
     class="hidden fixed inset-0 z-50 overflow-y-auto"
     role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="fixed inset-0 bg-background/80 backdrop-blur-sm"
             onclick="document.getElementById('jm-transfer-modal').classList.add('hidden')"></div>
        <div class="relative w-full max-w-md rounded-xl bg-popover text-popover-foreground shadow-2xl ring-1 ring-border animate-slide-down">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-warning/10 text-warning ring-1 ring-inset ring-warning/20">
                        <i data-lucide="arrow-right-left" class="text-[18px]"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold">Transfer monitoring data?</h3>
                        <p class="mt-2 text-sm text-muted-foreground">
                            All rows from <strong><?php echo e($fromStatus->name); ?></strong> will be moved to
                            <strong><?php echo e($toStatus->name); ?></strong>.
                        </p>
                        <p class="mt-2 text-sm text-destructive font-medium">
                            <code class="px-1 rounded bg-muted text-xs">jobs_monitor_*</code> tables in
                            <strong><?php echo e($fromStatus->name); ?></strong> will be <strong>permanently deleted</strong> after transfer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-muted/40 px-6 py-3 flex justify-end gap-2 border-t border-border">
                <?php echo $__env->make('jobs-monitor::partials.button', [
                    'variant' => 'secondary',
                    'label'   => 'Cancel',
                    'attrs'   => 'onclick="document.getElementById(\'jm-transfer-modal\').classList.add(\'hidden\')"',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('jobs-monitor::partials.button', [
                    'variant' => 'brand',
                    'icon'    => 'arrow-right-left',
                    'label'   => 'Yes, transfer',
                    'attrs'   => 'type="button" onclick="document.getElementById(\'jm-transfer-form\').submit()"',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('jm-transfer-modal')?.classList.add('hidden');
        }
    });
})();
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('jobs-monitor::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\database\index.blade.php ENDPATH**/ ?>