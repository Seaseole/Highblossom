<?php $__env->startSection('content'); ?>
    <div class="mb-4">
        <a href="<?php echo e(route('jobs-monitor.dlq')); ?>"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
            <i data-lucide="arrow-left" class="text-[14px]"></i>
            Back to DLQ
        </a>
    </div>

    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-xs">
        <div class="px-6 py-4 border-b border-border flex items-start gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
                <i data-lucide="file-json-2" class="text-[16px]"></i>
            </span>
            <div>
                <h1 class="text-lg font-semibold tracking-tight">Edit payload and retry</h1>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Fix the data, then re-dispatch. The retried job gets a new UUID and shows up as a fresh run in the dashboard.
                </p>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="mx-6 mt-4 flex items-start gap-3 rounded-lg border border-destructive/25 bg-destructive/10 text-destructive px-4 py-3 text-sm">
                <i data-lucide="alert-circle" class="text-[16px] mt-0.5"></i>
                <div><?php echo e(session('error')); ?></div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $retryEnabled): ?>
            <div class="mx-6 mt-4 flex items-start gap-3 rounded-lg border border-warning/25 bg-warning/10 text-warning px-4 py-3 text-sm">
                <i data-lucide="alert-triangle" class="text-[16px] mt-0.5"></i>
                <div>
                    Retry is disabled because payloads are not stored.
                    Set <code class="px-1.5 py-0.5 rounded bg-card border border-border text-xs font-mono">JOBS_MONITOR_STORE_PAYLOAD=true</code> in the host app to enable re-dispatch.
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 mb-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                    ['Job class',  $jobClass,   true],
                    ['UUID (original)', $uuid,  true],
                    ['Connection', $connection, false],
                    ['Queue',      $queue,      false],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $mono]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div>
                        <dt class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider"><?php echo e($label); ?></dt>
                        <dd class="mt-1 text-sm <?php echo e($mono ? 'font-mono break-all' : ''); ?>"><?php echo e($val); ?></dd>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </dl>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payload === null): ?>
                <div class="flex items-start gap-3 rounded-lg border border-warning/25 bg-warning/10 text-warning px-4 py-3 text-sm">
                    <i data-lucide="alert-triangle" class="text-[16px] mt-0.5"></i>
                    <div>This job has no stored payload. Nothing to edit.</div>
                </div>
            <?php else: ?>
                <?php
                    $editValue = $previousInput ?? json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                ?>
                <form method="POST" action="<?php echo e(route('jobs-monitor.dlq.retry', ['uuid' => $uuid])); ?>">
                    <?php echo csrf_field(); ?>
                    <label for="payload" class="block text-[10px] font-medium text-muted-foreground uppercase tracking-wider mb-2">Payload (JSON)</label>
                    <textarea id="payload" name="payload" rows="20" spellcheck="false"
                              class="w-full rounded-md border border-input bg-card px-3 py-2 text-xs font-mono text-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring disabled:opacity-60"
                              <?php if(! $retryEnabled): ?> disabled <?php endif; ?>><?php echo e($editValue); ?></textarea>

                    <p class="mt-2 text-xs text-muted-foreground">
                        The <code class="font-mono px-1 py-0.5 bg-muted rounded">uuid</code> field is overwritten automatically on dispatch. Everything else you edit is kept.
                    </p>

                    <div class="mt-4 flex items-center gap-2">
                        <button type="submit"
                                <?php if(! $retryEnabled): ?> disabled <?php endif; ?>
                                class="inline-flex items-center gap-1.5 h-9 px-4 text-sm font-semibold rounded-md bg-primary text-primary-foreground hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-xs">
                            <i data-lucide="send" class="text-[14px]"></i>
                            Retry with this payload
                        </button>
                        <a href="<?php echo e(route('jobs-monitor.dlq')); ?>"
                           class="inline-flex items-center h-9 px-3 text-sm font-medium text-muted-foreground hover:text-foreground">Cancel</a>
                    </div>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('jobs-monitor::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\dlq-edit.blade.php ENDPATH**/ ?>