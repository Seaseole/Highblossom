<?php
    $driverIcons = ['mysql' => 'database', 'pgsql' => 'database', 'sqlite' => 'hard-drive', 'unknown' => 'server'];
    $icon        = $driverIcons[$status->driver] ?? 'server';
    $isActive    = $active ?? false;
?>

<div class="rounded-xl border p-5 flex flex-col gap-4 shadow-xs transition-colors
            <?php echo e($isActive
                ? 'border-brand bg-brand/5 ring-1 ring-inset ring-brand/20'
                : 'border-border bg-card text-card-foreground'); ?>">

    
    <div class="flex items-start gap-3">
        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg
                     <?php echo e($isActive ? 'bg-brand/15 text-brand ring-1 ring-inset ring-brand/25' : 'bg-brand/10 text-brand ring-1 ring-inset ring-brand/20'); ?>">
            <i data-lucide="<?php echo e($icon); ?>" class="text-[16px]"></i>
        </span>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-semibold tracking-tight"><?php echo e($label); ?></h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isActive): ?>
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-brand/15 text-brand ring-1 ring-inset ring-brand/20">
                        <i data-lucide="zap" class="text-[10px]"></i>
                        Active
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <p class="mt-0.5 text-sm font-mono text-muted-foreground truncate"><?php echo e($status->name); ?></p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status->reachable): ?>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium ring-1 ring-inset bg-success/10 text-success ring-success/25">
                <i data-lucide="wifi" class="text-[12px]"></i>
                Reachable
            </span>
        <?php else: ?>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium ring-1 ring-inset bg-destructive/10 text-destructive ring-destructive/25">
                <i data-lucide="wifi-off" class="text-[12px]"></i>
                Unreachable
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm border border-border rounded-lg px-4 py-3 bg-muted/30">

        <dt class="text-muted-foreground">Driver</dt>
        <dd class="font-mono font-medium">
            <span class="inline-flex items-center gap-1">
                <span class="inline-flex h-5 w-5 items-center justify-center rounded bg-muted text-muted-foreground text-[10px] font-bold ring-1 ring-border">
                    <?php echo e(strtoupper(substr($status->driver, 0, 2))); ?>

                </span>
                <?php echo e($status->driver); ?>

            </span>
        </dd>

        <dt class="text-muted-foreground">Database</dt>
        <dd class="font-mono font-medium truncate"><?php echo e($status->database); ?></dd>

        <dt class="text-muted-foreground">Migrations</dt>
        <dd>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$status->reachable): ?>
                <span class="text-muted-foreground">—</span>
            <?php elseif($status->migrated): ?>
                <span class="inline-flex items-center gap-1 text-success">
                    <i data-lucide="check-circle-2" class="text-[13px]"></i>
                    Applied
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1 text-warning">
                    <i data-lucide="alert-triangle" class="text-[13px]"></i>
                    Not migrated
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </dd>

        <dt class="text-muted-foreground">Rows</dt>
        <dd class="font-medium tabular-nums">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status->reachable && $status->migrated): ?>
                <?php echo e(number_format($status->rowCount)); ?>

            <?php else: ?>
                <span class="text-muted-foreground">—</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </dd>

    </dl>

</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\database\_connection-card.blade.php ENDPATH**/ ?>