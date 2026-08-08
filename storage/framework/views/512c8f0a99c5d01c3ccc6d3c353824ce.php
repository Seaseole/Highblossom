<?php
    /** @var \Yammi\JobsMonitor\Domain\Scheduler\Entity\ScheduledTaskRun $run */
?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
        ['Mutex', $run->mutex, true],
        ['Command', $run->command ?? '—', true],
        ['Cron', $run->expression, true],
        ['Timezone', $run->timezone ?? '—', false],
        ['Started', $run->startedAt->format('Y-m-d H:i:s'), false],
        ['Finished', $run->finishedAt() ? $run->finishedAt()->format('Y-m-d H:i:s') : '—', false],
        ['Exit code', $run->exitCode() === null ? '—' : (string) $run->exitCode(), false],
        ['Host', $run->host ?? '—', false],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $mono]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div>
            <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider"><?php echo e($label); ?></span>
            <p class="text-sm <?php echo e($mono ? 'font-mono break-all' : ''); ?>"><?php echo e($val); ?></p>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($run->output()): ?>
    <div class="mb-3">
        <span class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">Output</span>
        <pre class="mt-1 bg-card border border-border rounded-lg p-3 text-xs overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e($run->output()); ?></pre>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($run->exception()): ?>
    <div>
        <span class="text-[10px] font-medium text-destructive uppercase tracking-wider">Exception</span>
        <pre class="mt-1 bg-destructive/10 border border-destructive/20 rounded-lg p-3 text-xs text-destructive overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e($run->exception()); ?></pre>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\scheduled-detail.blade.php ENDPATH**/ ?>