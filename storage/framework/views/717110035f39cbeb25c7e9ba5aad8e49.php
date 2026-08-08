<?php
    /**
     * Reusable button/link with unified look across pages.
     *
     * Props:
     *   - variant: primary|secondary|danger|warning|brand|ghost (default: primary)
     *   - size:    sm|md (default: md → h-9 px-4; sm → h-8 px-3)
     *   - icon:    lucide icon name (optional)
     *   - label:   visible text
     *   - as:      button|submit|link (default: button)
     *   - href:    required when as === 'link'
     *   - attrs:   raw HTML attributes string (already escaped)
     */
    $variant = $variant ?? 'primary';
    $size    = $size ?? 'md';
    $as      = $as ?? 'button';
    $icon    = $icon ?? null;
    $attrs   = $attrs ?? '';
    $label   = $label ?? '';

    $variants = [
        'primary'   => 'bg-primary text-primary-foreground hover:bg-primary/90 shadow-xs',
        'secondary' => 'border border-border bg-card text-foreground hover:bg-accent hover:text-accent-foreground shadow-xs',
        'danger'    => 'bg-destructive text-destructive-foreground hover:bg-destructive/90 shadow-xs',
        'warning'   => 'bg-warning text-warning-foreground hover:bg-warning/90 shadow-xs',
        'brand'     => 'border border-brand/30 bg-brand/10 text-brand hover:bg-brand/15 hover:border-brand/40',
        'ghost'     => 'text-muted-foreground hover:bg-accent hover:text-foreground',
    ];
    $sizes = [
        'sm' => 'h-8 px-3 text-xs',
        'md' => 'h-9 px-4 text-sm',
    ];

    $class = 'inline-flex items-center gap-1.5 rounded-md font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring '
        .($sizes[$size] ?? $sizes['md']).' '
        .($variants[$variant] ?? $variants['primary']);
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($as === 'link'): ?>
    <a href="<?php echo e($href ?? '#'); ?>" class="<?php echo e($class); ?>" <?php echo $attrs; ?>>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($icon): ?><i data-lucide="<?php echo e($icon); ?>" class="text-[14px]"></i><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo e($label); ?>

    </a>
<?php else: ?>
    <button type="<?php echo e($as === 'submit' ? 'submit' : 'button'); ?>" class="<?php echo e($class); ?>" <?php echo $attrs; ?>>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($icon): ?><i data-lucide="<?php echo e($icon); ?>" class="text-[14px]"></i><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo e($label); ?>

    </button>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\button.blade.php ENDPATH**/ ?>