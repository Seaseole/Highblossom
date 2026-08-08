<?php $__env->startSection('content'); ?>
    <div class="mb-4">
        <a href="<?php echo e(route('jobs-monitor.dashboard', request()->only(['period', 'search', 'page']))); ?>"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
            <i data-lucide="arrow-left" class="text-[14px]"></i>
            Back to Dashboard
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="mb-4 flex items-start gap-3 rounded-lg border border-success/25 bg-success/10 text-success px-4 py-3 text-sm">
            <i data-lucide="check-circle-2" class="text-[16px] mt-0.5"></i>
            <div><?php echo e(session('status')); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="mb-4 flex items-start gap-3 rounded-lg border border-destructive/25 bg-destructive/10 text-destructive px-4 py-3 text-sm">
            <i data-lucide="alert-circle" class="text-[16px] mt-0.5"></i>
            <div><?php echo e(session('error')); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php
        $hasPayload = $record->payload() !== null;
        $showRetry = $retryEnabled && $canRetry && $hasPayload;
        $showKebab = $showRetry || $canDelete;
    ?>

    <div class="rounded-xl border border-border bg-card text-card-foreground shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-border flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand/10 text-brand ring-1 ring-inset ring-brand/20">
                    <i data-lucide="briefcase" class="text-[18px]"></i>
                </span>
                <div>
                    <h1 class="text-lg font-semibold tracking-tight">
                        <?php echo e(class_basename($record->jobClass)); ?>

                    </h1>
                    <div class="mt-1">
                        <?php echo $__env->make('jobs-monitor::partials.status-badge', ['value' => $record->status()->value], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showKebab): ?>
                <div class="relative inline-block text-left" data-dlq-menu>
                    <button type="button"
                            class="inline-flex h-9 px-3 items-center gap-1.5 rounded-md border border-border bg-card text-sm font-medium text-foreground hover:bg-accent hover:text-accent-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-ring"
                            title="Actions"
                            onclick="toggleDetailMenu(this)">
                        Actions
                        <i data-lucide="chevron-down" class="text-[14px]"></i>
                    </button>
                    <div class="hidden absolute right-0 z-10 mt-1 w-52 origin-top-right rounded-lg bg-popover text-popover-foreground shadow-lg ring-1 ring-border focus:outline-none animate-slide-down"
                         data-dlq-menu-dropdown>
                        <div class="p-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showRetry): ?>
                                <form method="POST"
                                      action="<?php echo e(route('jobs-monitor.dlq.retry', ['uuid' => $record->id->value])); ?>"
                                      class="block">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full flex items-center gap-2 px-2.5 py-1.5 text-sm rounded-md hover:bg-accent hover:text-accent-foreground">
                                        <i data-lucide="refresh-cw" class="text-[14px] text-brand"></i>
                                        Retry
                                    </button>
                                </form>
                                <a href="<?php echo e(route('jobs-monitor.dlq.edit', ['uuid' => $record->id->value])); ?>"
                                   class="flex items-center gap-2 px-2.5 py-1.5 text-sm rounded-md hover:bg-accent hover:text-accent-foreground">
                                    <i data-lucide="pencil" class="text-[14px] text-brand"></i>
                                    Edit &amp; retry
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showRetry && $canDelete): ?>
                                <div class="h-px bg-border my-1"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canDelete): ?>
                                <button type="button"
                                        class="w-full flex items-center gap-2 px-2.5 py-1.5 text-sm text-destructive rounded-md hover:bg-destructive/10"
                                        onclick="openDetailDeleteConfirm()">
                                    <i data-lucide="trash-2" class="text-[14px]"></i>
                                    Delete
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif(! $retryEnabled): ?>
                <span class="text-xs text-muted-foreground italic">
                    Enable <code class="font-mono px-1 py-0.5 bg-muted rounded">store_payload</code> to retry
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <?php
                    $rows = [
                        ['UUID',        $record->id->value, true],
                        ['Attempt',     (string) $record->attempt->value, false],
                        ['Job Class',   $record->jobClass, true],
                        ['Connection',  $record->connection, false],
                        ['Queue',       $record->queue->value, false],
                        ['Duration',    $record->duration() ? number_format($record->duration()->milliseconds).'ms' : '—', false],
                        ['Started At',  $record->startedAt->format('Y-m-d H:i:s.u'), false],
                        ['Finished At', $record->finishedAt()?->format('Y-m-d H:i:s.u') ?? '—', false],
                    ];
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $mono]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div>
                        <dt class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider"><?php echo e($label); ?></dt>
                        <dd class="mt-1 text-sm <?php echo e($mono ? 'font-mono break-all' : ''); ?>"><?php echo e($val); ?></dd>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->failureCategory()): ?>
                    <div>
                        <dt class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">Failure Category</dt>
                        <dd class="mt-1">
                            <?php echo $__env->make('jobs-monitor::partials.failure-category-badge', [
                                'value' => $record->failureCategory()->value,
                                'label' => $record->failureCategory()->label(),
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </dd>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </dl>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($redactedPayload): ?>
            <div class="border-t border-border px-6 py-5">
                <h2 class="text-sm font-semibold mb-3 flex items-center gap-2">
                    <i data-lucide="braces" class="text-[14px] text-brand"></i>
                    Payload
                </h2>
                <pre class="bg-muted/40 border border-border rounded-lg p-4 text-xs overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e(json_encode($redactedPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->exception()): ?>
            <div class="border-t border-border px-6 py-5">
                <h2 class="text-sm font-semibold text-destructive mb-3 flex items-center gap-2">
                    <i data-lucide="alert-octagon" class="text-[14px]"></i>
                    Exception
                </h2>
                <pre class="bg-destructive/10 border border-destructive/20 rounded-lg p-4 text-sm text-destructive overflow-x-auto whitespace-pre-wrap break-words font-mono"><?php echo e($record->exception()); ?></pre>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($attempts) > 1): ?>
            <div class="border-t border-border px-6 py-5">
                <div class="flex items-baseline justify-between mb-4">
                    <h2 class="text-sm font-semibold flex items-center gap-2">
                        <i data-lucide="history" class="text-[14px] text-brand"></i>
                        Retry Timeline
                    </h2>
                    <span class="text-xs text-muted-foreground"><?php echo e(count($attempts)); ?> attempts</span>
                </div>
                <ol class="relative space-y-2 pl-4 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-border">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_reverse($attempts); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php
                            $isCurrent = $att->attempt->value === $currentAttempt;
                            $statusVal = $att->status()->value;
                            $dotBg = match($statusVal) {
                                'processed'  => 'bg-success',
                                'failed'     => 'bg-destructive',
                                'processing' => 'bg-warning animate-pulse-soft',
                                default      => 'bg-muted-foreground',
                            };
                        ?>
                        <li class="relative">
                            <span class="absolute -left-4 top-3.5 flex h-[10px] w-[10px] items-center justify-center rounded-full ring-2 ring-card <?php echo e($dotBg); ?>"></span>
                            <a href="<?php echo e(route('jobs-monitor.detail', ['uuid' => $att->id->value, 'attempt' => $att->attempt->value])); ?>"
                               class="block ml-3 rounded-lg border px-4 py-3 transition-all
                                      <?php echo e($isCurrent
                                          ? 'border-brand ring-2 ring-brand/20 bg-brand/5'
                                          : 'border-border bg-card hover:border-border hover:bg-muted/40'); ?>">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center justify-center min-w-7 h-6 px-2 rounded-md bg-muted text-muted-foreground text-xs font-bold tabular-nums">
                                        #<?php echo e($att->attempt->value); ?>

                                    </span>
                                    <?php echo $__env->make('jobs-monitor::partials.status-badge', ['value' => $att->status()->value], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($att->failureCategory()): ?>
                                        <?php echo $__env->make('jobs-monitor::partials.failure-category-badge', [
                                            'value' => $att->failureCategory()->value,
                                            'label' => $att->failureCategory()->label(),
                                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="text-xs text-muted-foreground font-mono tabular-nums"><?php echo e($att->startedAt->format('Y-m-d H:i:s')); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($att->duration()): ?>
                                        <span class="text-xs text-muted-foreground tabular-nums"><?php echo e(number_format($att->duration()->milliseconds)); ?>ms</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isCurrent): ?>
                                        <span class="ml-auto inline-flex items-center gap-1 text-xs font-semibold text-brand">
                                            <i data-lucide="eye" class="text-[12px]"></i>
                                            viewing
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($att->exception()): ?>
                                    <p class="mt-2 text-xs text-destructive font-mono truncate"><?php echo e(\Illuminate\Support\Str::limit($att->exception(), 120)); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </a>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ol>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canDelete): ?>
        <div id="detail-delete-modal"
             class="hidden fixed inset-0 z-50 overflow-y-auto"
             role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4">
                <div class="fixed inset-0 bg-background/80 backdrop-blur-sm" onclick="closeDetailDeleteConfirm()"></div>
                <div class="relative w-full max-w-md transform overflow-hidden rounded-xl bg-popover text-popover-foreground shadow-2xl ring-1 ring-border animate-slide-down">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-destructive/10 text-destructive ring-1 ring-inset ring-destructive/20">
                                <i data-lucide="alert-triangle" class="text-[18px]"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold">Delete this job record?</h3>
                                <p class="mt-2 text-sm text-muted-foreground">
                                    Removes every stored attempt for
                                    <span class="font-mono text-foreground"><?php echo e(class_basename($record->jobClass)); ?></span>
                                    (UUID <code class="font-mono text-xs px-1 py-0.5 bg-muted rounded"><?php echo e($record->id->value); ?></code>).
                                    This can't be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-muted/40 px-6 py-3 flex justify-end gap-2 border-t border-border">
                        <button type="button"
                                class="inline-flex items-center h-9 px-4 text-sm font-medium rounded-md border border-border bg-card hover:bg-accent hover:text-accent-foreground transition-colors"
                                onclick="closeDetailDeleteConfirm()">Cancel</button>
                        <form method="POST" action="<?php echo e(route('jobs-monitor.dlq.delete', ['uuid' => $record->id->value])); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 h-9 px-4 text-sm font-semibold rounded-md bg-destructive text-destructive-foreground hover:bg-destructive/90 transition-colors shadow-xs">
                                <i data-lucide="trash-2" class="text-[14px]"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showKebab): ?>
        <script>
            function toggleDetailMenu(button) {
                const dropdown = button.nextElementSibling;
                document.querySelectorAll('[data-dlq-menu-dropdown]').forEach(d => {
                    if (d !== dropdown) d.classList.add('hidden');
                });
                dropdown.classList.toggle('hidden');
                if (window.__jmRefreshIcons) window.__jmRefreshIcons();
            }

            function openDetailDeleteConfirm() {
                document.getElementById('detail-delete-modal')?.classList.remove('hidden');
                document.querySelectorAll('[data-dlq-menu-dropdown]').forEach(d => d.classList.add('hidden'));
                if (window.__jmRefreshIcons) window.__jmRefreshIcons();
            }

            function closeDetailDeleteConfirm() {
                document.getElementById('detail-delete-modal')?.classList.add('hidden');
            }

            document.addEventListener('click', function (e) {
                document.querySelectorAll('[data-dlq-menu]').forEach(menu => {
                    if (! menu.contains(e.target)) {
                        menu.querySelector('[data-dlq-menu-dropdown]')?.classList.add('hidden');
                    }
                });
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('[data-dlq-menu-dropdown]').forEach(d => d.classList.add('hidden'));
                    closeDetailDeleteConfirm();
                }
            });
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('jobs-monitor::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\detail.blade.php ENDPATH**/ ?>