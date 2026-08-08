<?php
    $map = [
        'transient' => ['cls' => 'bg-warning/10 text-warning ring-warning/25',        'icon' => 'refresh-cw'],
        'permanent' => ['cls' => 'bg-destructive/10 text-destructive ring-destructive/25', 'icon' => 'x-octagon'],
        'critical'  => ['cls' => 'bg-fuchsia-500/10 text-fuchsia-500 ring-fuchsia-500/25', 'icon' => 'zap'],
        'unknown'   => ['cls' => 'bg-muted text-muted-foreground ring-border',        'icon' => 'help-circle'],
    ];
    $cfg = $map[$value] ?? $map['unknown'];
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($value): ?>
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium ring-1 ring-inset <?php echo e($cfg['cls']); ?>">
        <i data-lucide="<?php echo e($cfg['icon']); ?>" class="text-[12px]"></i>
        <?php echo e($label); ?>

    </span>
<?php else: ?>
    <span class="text-muted-foreground text-sm">—</span>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\failure-category-badge.blade.php ENDPATH**/ ?>