<?php
    $aliveExtraParams = [
        'spage' => $vm->silentPage,
        'dpage' => $vm->deadPage,
        'ppage' => $vm->coveragePage,
    ];
    $silentExtraParams = [
        'page' => $vm->alivePage,
        'dpage' => $vm->deadPage,
        'ppage' => $vm->coveragePage,
    ];
    $deadExtraParams = [
        'page' => $vm->alivePage,
        'spage' => $vm->silentPage,
        'ppage' => $vm->coveragePage,
    ];
    $coverageExtraParams = [
        'page' => $vm->alivePage,
        'spage' => $vm->silentPage,
        'dpage' => $vm->deadPage,
    ];

    $statusBadges = [
        'alive' => 'bg-success/10 text-success border-success/20',
        'silent' => 'bg-warning/10 text-warning border-warning/20',
        'dead' => 'bg-destructive/10 text-destructive border-destructive/20',
    ];

    $coverageBadges = [
        'ok' => 'bg-success/10 text-success border-success/20',
        'degraded' => 'bg-warning/10 text-warning border-warning/20',
        'down' => 'bg-destructive/10 text-destructive border-destructive/20',
    ];

    $formatAge = static function (\DateTimeImmutable $seen, \DateTimeImmutable $now): string {
        $elapsed = max(0, $now->getTimestamp() - $seen->getTimestamp());
        if ($elapsed < 60) {
            return $elapsed.'s ago';
        }
        if ($elapsed < 3600) {
            return (int) floor($elapsed / 60).'m ago';
        }
        if ($elapsed < 86400) {
            return (int) floor($elapsed / 3600).'h ago';
        }

        return (int) floor($elapsed / 86400).'d ago';
    };
?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
    <div class="rounded-xl border border-border bg-card p-4 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase tracking-wide text-muted-foreground">Alive</span>
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-success/10 text-success"><i data-lucide="heart-pulse" class="text-[14px]"></i></span>
        </div>
        <div class="mt-2 text-2xl font-bold tracking-tight tabular-nums <?php echo e($vm->aliveTotal > 0 ? 'text-success' : 'text-foreground'); ?>"><?php echo e(number_format($vm->aliveTotal)); ?></div>
        <p class="mt-1 text-xs text-muted-foreground">Heartbeat received within the threshold.</p>
    </div>
    <div class="rounded-xl border border-border bg-card p-4 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase tracking-wide text-muted-foreground">Silent</span>
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-warning/10 text-warning"><i data-lucide="bell-off" class="text-[14px]"></i></span>
        </div>
        <div class="mt-2 text-2xl font-bold tracking-tight tabular-nums <?php echo e($vm->silentTotal > 0 ? 'text-warning' : 'text-foreground'); ?>"><?php echo e(number_format($vm->silentTotal)); ?></div>
        <p class="mt-1 text-xs text-muted-foreground">Last heartbeat older than the threshold.</p>
    </div>
    <div class="rounded-xl border border-border bg-card p-4 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase tracking-wide text-muted-foreground">Dead</span>
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-destructive/10 text-destructive"><i data-lucide="skull" class="text-[14px]"></i></span>
        </div>
        <div class="mt-2 text-2xl font-bold tracking-tight tabular-nums <?php echo e($vm->deadTotal > 0 ? 'text-destructive' : 'text-foreground'); ?>"><?php echo e(number_format($vm->deadTotal)); ?></div>
        <p class="mt-1 text-xs text-muted-foreground">Stopped or offline far past the threshold.</p>
    </div>
    <div class="rounded-xl border border-border bg-card p-4 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase tracking-wide text-muted-foreground">Coverage</span>
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-info/10 text-info"><i data-lucide="layers" class="text-[14px]"></i></span>
        </div>
        <div class="mt-2 text-2xl font-bold tracking-tight tabular-nums <?php echo e($vm->coverageTotal > 0 ? 'text-info' : 'text-foreground'); ?>"><?php echo e(number_format($vm->coverageTotal)); ?></div>
        <p class="mt-1 text-xs text-muted-foreground">Queues with configured expectations.</p>
    </div>
</div>


<section class="rounded-xl border border-border bg-card overflow-hidden">
    <div class="flex items-center gap-3 px-5 py-3.5 border-b border-border bg-success/5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-success/15 text-success ring-1 ring-inset ring-success/20">
            <i data-lucide="heart-pulse" class="text-[16px]"></i>
        </span>
        <div class="flex-1">
            <h2 class="text-sm font-semibold">Alive workers</h2>
            <p class="text-xs text-muted-foreground"><?php echo e(number_format($vm->aliveTotal)); ?> worker(s) checked in within threshold</p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($vm->alive) === 0): ?>
        <p class="px-5 py-6 text-sm text-muted-foreground">No alive workers observed.</p>
    <?php else: ?>
        <table class="w-full text-sm table-fixed">
            <colgroup>
                <col>
                <col class="hidden md:table-column w-[140px]">
                <col class="hidden lg:table-column w-[220px]">
                <col class="w-[110px]">
                <col class="w-[100px]">
                <col class="w-12">
            </colgroup>
            <thead class="bg-muted/40 text-xs uppercase tracking-wider text-muted-foreground">
                <tr>
                    <th class="px-5 py-2.5 text-left font-medium">Worker</th>
                    <th class="hidden md:table-cell px-5 py-2.5 text-left font-medium">Queue</th>
                    <th class="hidden lg:table-cell px-5 py-2.5 text-left font-medium">Host / PID</th>
                    <th class="px-5 py-2.5 text-left font-medium">Last seen</th>
                    <th class="px-5 py-2.5 text-left font-medium">Status</th>
                    <th class="px-3 py-2.5 text-right font-medium"></th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vm->alive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $worker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $hb = $worker->heartbeat(); ?>
                    <tr class="border-t border-border">
                        <td class="px-5 py-2.5 font-mono text-xs truncate"><?php echo e($hb->workerId->value); ?></td>
                        <td class="hidden md:table-cell px-5 py-2.5 truncate"><?php echo e($hb->queueKey()); ?></td>
                        <td class="hidden lg:table-cell px-5 py-2.5 text-muted-foreground tabular-nums truncate"><?php echo e($hb->host); ?> <span class="text-muted-foreground/60">· <?php echo e($hb->pid); ?></span></td>
                        <td class="px-5 py-2.5 tabular-nums"><?php echo e($formatAge($hb->lastSeenAt, $vm->now)); ?></td>
                        <td class="px-5 py-2.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium border <?php echo e($statusBadges['alive']); ?>">
                                <i data-lucide="check-circle-2" class="text-[12px]"></i>
                                Alive
                            </span>
                        </td>
                        <td class="px-3 py-2.5" onclick="event.stopPropagation()">
                            <?php echo $__env->make('jobs-monitor::partials.kebab-actions', [
                                'actions' => [],
                                'emptyLabel' => 'no actions',
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>

        <?php echo $__env->make('jobs-monitor::partials.pagination', [
            'routeName' => 'jobs-monitor.workers',
            'currentPage' => $vm->alivePage,
            'lastPage' => $vm->aliveLastPage,
            'pageParam' => 'page',
            'extraParams' => $aliveExtraParams,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</section>


<section class="rounded-xl border border-border bg-card overflow-hidden" id="workers-silent">
    <div class="flex items-center gap-3 px-5 py-3.5 border-b border-border bg-warning/5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-warning/15 text-warning ring-1 ring-inset ring-warning/20">
            <i data-lucide="bell-off" class="text-[16px]"></i>
        </span>
        <div class="flex-1">
            <h2 class="text-sm font-semibold">Silent workers</h2>
            <p class="text-xs text-muted-foreground"><?php echo e(number_format($vm->silentTotal)); ?> worker(s) missed the <?php echo e($vm->silentAfterSeconds); ?>s threshold</p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($vm->silent) === 0): ?>
        <p class="px-5 py-6 text-sm text-muted-foreground">No silent workers — every worker is alive or intentionally stopped.</p>
    <?php else: ?>
        <table class="w-full text-sm table-fixed">
            <colgroup>
                <col>
                <col class="hidden md:table-column w-[140px]">
                <col class="hidden lg:table-column w-[220px]">
                <col class="w-[110px]">
                <col class="w-[100px]">
            </colgroup>
            <thead class="bg-muted/40 text-xs uppercase tracking-wider text-muted-foreground">
                <tr>
                    <th class="px-5 py-2.5 text-left font-medium">Worker</th>
                    <th class="hidden md:table-cell px-5 py-2.5 text-left font-medium">Queue</th>
                    <th class="hidden lg:table-cell px-5 py-2.5 text-left font-medium">Host / PID</th>
                    <th class="px-5 py-2.5 text-left font-medium">Last seen</th>
                    <th class="px-5 py-2.5 text-left font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vm->silent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $worker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $hb = $worker->heartbeat(); ?>
                    <tr class="border-t border-border">
                        <td class="px-5 py-2.5 font-mono text-xs truncate"><?php echo e($hb->workerId->value); ?></td>
                        <td class="hidden md:table-cell px-5 py-2.5 truncate"><?php echo e($hb->queueKey()); ?></td>
                        <td class="hidden lg:table-cell px-5 py-2.5 text-muted-foreground tabular-nums truncate"><?php echo e($hb->host); ?> <span class="text-muted-foreground/60">· <?php echo e($hb->pid); ?></span></td>
                        <td class="px-5 py-2.5 tabular-nums text-warning"><?php echo e($formatAge($hb->lastSeenAt, $vm->now)); ?></td>
                        <td class="px-5 py-2.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium border <?php echo e($statusBadges['silent']); ?>">
                                <i data-lucide="bell-off" class="text-[12px]"></i>
                                Silent
                            </span>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>

        <?php echo $__env->make('jobs-monitor::partials.pagination', [
            'routeName' => 'jobs-monitor.workers',
            'currentPage' => $vm->silentPage,
            'lastPage' => $vm->silentLastPage,
            'pageParam' => 'spage',
            'extraParams' => $silentExtraParams,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</section>


<section class="rounded-xl border border-border bg-card overflow-hidden" id="workers-dead">
    <div class="flex items-center gap-3 px-5 py-3.5 border-b border-border bg-destructive/5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-destructive/15 text-destructive ring-1 ring-inset ring-destructive/20">
            <i data-lucide="skull" class="text-[16px]"></i>
        </span>
        <div class="flex-1">
            <h2 class="text-sm font-semibold">Dead workers</h2>
            <p class="text-xs text-muted-foreground"><?php echo e(number_format($vm->deadTotal)); ?> worker(s) stopped or offline far past the threshold</p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($vm->dead) === 0): ?>
        <p class="px-5 py-6 text-sm text-muted-foreground">No dead workers — nothing has been stopped or crashed beyond the dead multiplier.</p>
    <?php else: ?>
        <table class="w-full text-sm table-fixed">
            <colgroup>
                <col>
                <col class="hidden md:table-column w-[140px]">
                <col class="hidden lg:table-column w-[220px]">
                <col class="w-[110px]">
                <col class="hidden xl:table-column w-[160px]">
                <col class="w-[100px]">
            </colgroup>
            <thead class="bg-muted/40 text-xs uppercase tracking-wider text-muted-foreground">
                <tr>
                    <th class="px-5 py-2.5 text-left font-medium">Worker</th>
                    <th class="hidden md:table-cell px-5 py-2.5 text-left font-medium">Queue</th>
                    <th class="hidden lg:table-cell px-5 py-2.5 text-left font-medium">Host / PID</th>
                    <th class="px-5 py-2.5 text-left font-medium">Last seen</th>
                    <th class="hidden xl:table-cell px-5 py-2.5 text-left font-medium">Stopped at</th>
                    <th class="px-5 py-2.5 text-left font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vm->dead; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $worker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $hb = $worker->heartbeat(); ?>
                    <tr class="border-t border-border">
                        <td class="px-5 py-2.5 font-mono text-xs truncate"><?php echo e($hb->workerId->value); ?></td>
                        <td class="hidden md:table-cell px-5 py-2.5 truncate"><?php echo e($hb->queueKey()); ?></td>
                        <td class="hidden lg:table-cell px-5 py-2.5 text-muted-foreground tabular-nums truncate"><?php echo e($hb->host); ?> <span class="text-muted-foreground/60">· <?php echo e($hb->pid); ?></span></td>
                        <td class="px-5 py-2.5 tabular-nums text-destructive"><?php echo e($formatAge($hb->lastSeenAt, $vm->now)); ?></td>
                        <td class="hidden xl:table-cell px-5 py-2.5 tabular-nums text-muted-foreground">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worker->stoppedAt()): ?>
                                <?php echo e($worker->stoppedAt()->format('Y-m-d H:i:s')); ?>

                            <?php else: ?>
                                <span class="text-destructive/60">crashed</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-5 py-2.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium border <?php echo e($statusBadges['dead']); ?>">
                                <i data-lucide="skull" class="text-[12px]"></i>
                                Dead
                            </span>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>

        <?php echo $__env->make('jobs-monitor::partials.pagination', [
            'routeName' => 'jobs-monitor.workers',
            'currentPage' => $vm->deadPage,
            'lastPage' => $vm->deadLastPage,
            'pageParam' => 'dpage',
            'extraParams' => $deadExtraParams,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</section>


<section class="rounded-xl border border-border bg-card overflow-hidden" id="workers-coverage">
    <div class="flex items-center gap-3 px-5 py-3.5 border-b border-border bg-info/5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-info/15 text-info ring-1 ring-inset ring-info/20">
            <i data-lucide="layers" class="text-[16px]"></i>
        </span>
        <div class="flex-1">
            <h2 class="text-sm font-semibold">Queue coverage</h2>
            <p class="text-xs text-muted-foreground">Observed vs expected alive workers per queue</p>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($vm->coverage) === 0): ?>
        <div class="px-5 py-6 text-sm text-muted-foreground">
            No expectations configured. Add a <code class="text-xs bg-muted px-1 py-0.5 rounded">workers.expected</code> map in <code class="text-xs bg-muted px-1 py-0.5 rounded">config/jobs-monitor.php</code> to enable under-provisioned alerts.
        </div>
    <?php else: ?>
        <table class="w-full text-sm table-fixed">
            <colgroup>
                <col>
                <col class="w-[120px]">
                <col class="w-[120px]">
                <col class="w-[140px]">
            </colgroup>
            <thead class="bg-muted/40 text-xs uppercase tracking-wider text-muted-foreground">
                <tr>
                    <th class="px-5 py-2.5 text-left font-medium">Queue</th>
                    <th class="px-5 py-2.5 text-left font-medium">Observed</th>
                    <th class="px-5 py-2.5 text-left font-medium">Expected</th>
                    <th class="px-5 py-2.5 text-left font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $vm->coverage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="border-t border-border">
                        <td class="px-5 py-2.5 font-mono text-xs truncate"><?php echo e($row['queue_key']); ?></td>
                        <td class="px-5 py-2.5 tabular-nums"><?php echo e($row['observed']); ?></td>
                        <td class="px-5 py-2.5 tabular-nums"><?php echo e($row['expected']); ?></td>
                        <td class="px-5 py-2.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium border <?php echo e($coverageBadges[$row['status']]); ?>">
                                <?php echo e(strtoupper($row['status'])); ?>

                            </span>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>

        <?php echo $__env->make('jobs-monitor::partials.pagination', [
            'routeName' => 'jobs-monitor.workers',
            'currentPage' => $vm->coveragePage,
            'lastPage' => $vm->coverageLastPage,
            'pageParam' => 'ppage',
            'extraParams' => $coverageExtraParams,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</section>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\workers-content.blade.php ENDPATH**/ ?>