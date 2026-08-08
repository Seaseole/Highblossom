<?php
    /** @var \Yammi\JobsMonitor\Infrastructure\Persistence\Eloquent\JobRecordModel $job */
?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
        ['Job class', $job->job_class, true],
        ['UUID', $job->uuid, true],
        ['Attempt', (string) $job->attempt, false],
        ['Status', $job->status, false],
        ['Connection', $job->connection, false],
        ['Queue', $job->queue, false],
        ['Started at', optional($job->started_at)->format('Y-m-d H:i:s.v'), false],
        ['Finished at', optional($job->finished_at)->format('Y-m-d H:i:s.v') ?? '—', false],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $mono]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div>
            <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider"><?php echo e($label); ?></span>
            <p class="text-sm <?php echo e($mono ? 'font-mono break-all' : ''); ?>"><?php echo e($val); ?></p>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job->outcome_status !== null || $job->outcome_processed !== null): ?>
    <div class="rounded-lg border border-border bg-card/50 p-3 mb-3">
        <p class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider mb-2 flex items-center gap-1.5">
            <i data-lucide="package-check" class="text-[12px] text-success"></i>
            Outcome (from ReportsOutcome)
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm tabular-nums">
            <div><span class="text-[10px] text-muted-foreground uppercase">Status</span><p><?php echo e($job->outcome_status ?? '—'); ?></p></div>
            <div><span class="text-[10px] text-muted-foreground uppercase">Processed</span><p><?php echo e($job->outcome_processed ?? '—'); ?></p></div>
            <div><span class="text-[10px] text-muted-foreground uppercase">Skipped</span><p><?php echo e($job->outcome_skipped ?? '—'); ?></p></div>
            <div><span class="text-[10px] text-muted-foreground uppercase">Warnings</span><p><?php echo e($job->outcome_warnings_count ?? '—'); ?></p></div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job->progress_current !== null): ?>
    <div class="rounded-lg border border-border bg-card/50 p-3 mb-3">
        <p class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider mb-2 flex items-center gap-1.5">
            <i data-lucide="activity" class="text-[12px] text-info"></i>
            Progress (from ReportsProgress)
        </p>
        <p class="text-sm tabular-nums">
            <?php echo e(number_format($job->progress_current)); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job->progress_total !== null): ?> / <?php echo e(number_format($job->progress_total)); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job->progress_description): ?> — <?php echo e($job->progress_description); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($job->progress_updated_at): ?>
                <span class="text-xs text-muted-foreground ml-2">last update <?php echo e($job->progress_updated_at->format('Y-m-d H:i:s')); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($job->payload)): ?>
    <div class="mb-3">
        <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">Payload</span>
        <pre class="mt-1 bg-card border border-border rounded-lg p-3 text-xs overflow-x-auto whitespace-pre-wrap break-words font-mono max-h-80"><?php echo e(json_encode($job->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?></pre>
    </div>
<?php else: ?>
    <div class="mb-3 rounded-lg border border-border bg-muted/30 px-3 py-2 text-xs text-muted-foreground flex items-center gap-2">
        <i data-lucide="info" class="text-[14px]"></i>
        No payload stored. Set <code class="px-1 py-0.5 rounded bg-muted">JOBS_MONITOR_STORE_PAYLOAD=true</code> to enable retry on future runs.
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($job->exception)): ?>
    <div class="mb-3">
        <span class="text-[10px] font-medium text-destructive uppercase tracking-wider">Exception</span>
        <pre class="mt-1 bg-destructive/10 border border-destructive/20 rounded-lg p-3 text-xs text-destructive overflow-x-auto whitespace-pre-wrap break-words font-mono max-h-80"><?php echo e($job->exception); ?></pre>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="flex justify-end">
    <a href="<?php echo e(route('jobs-monitor.detail', ['uuid' => $job->uuid, 'attempt' => $job->attempt])); ?>"
       class="inline-flex items-center gap-1.5 h-8 px-3 rounded-md bg-primary text-primary-foreground text-xs font-semibold hover:bg-primary/90 transition-colors shadow-xs">
        View full job detail &amp; retry timeline
        <i data-lucide="arrow-right" class="text-[13px]"></i>
    </a>
</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\silent-job-detail.blade.php ENDPATH**/ ?>