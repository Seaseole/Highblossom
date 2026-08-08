<?php
    $isEditing = $editing === $b->key;
    $channelIcon = [
        'slack' => 'slack',
        'mail' => 'mail',
        'pagerduty' => 'siren',
        'opsgenie' => 'shield-alert',
        'webhook' => 'webhook',
    ];

    $rowActions = [
        [
            'type' => 'form',
            'url' => route('jobs-monitor.settings.alerts.built-in.toggle', ['key' => $b->key]),
            'icon' => $b->effectivelyEnabled ? 'bell-off' : 'bell-ring',
            'iconColor' => 'text-brand',
            'label' => $b->effectivelyEnabled ? 'Disable' : 'Enable',
        ],
        [
            'type' => 'link',
            'url' => route('jobs-monitor.settings.alerts', ['editing' => $b->key]).'#rule-'.$b->key,
            'icon' => 'pencil',
            'iconColor' => 'text-brand',
            'label' => 'Edit',
        ],
    ];
    if ($b->hasOverride) {
        $rowActions[] = [
            'type' => 'confirm',
            'url' => route('jobs-monitor.settings.alerts.built-in.reset', ['key' => $b->key]),
            'icon' => 'rotate-ccw',
            'label' => 'Reset to default',
            'danger' => true,
            'confirm' => [
                'title' => 'Reset alert rule?',
                'body' => 'Discard overrides for '.$b->key.' and return to the shipped default.',
                'submitLabel' => 'Reset',
                'variant' => 'danger',
            ],
        ];
    }
?>

<tr class="<?php echo e($isEditing ? 'bg-brand/5' : 'hover:bg-muted/40 transition-colors'); ?>">
    <td class="px-4 py-3 align-top">
        <code class="text-xs bg-muted px-1.5 py-0.5 rounded font-mono"><?php echo e($b->key); ?></code>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->hasOverride): ?>
            <div class="mt-1 inline-flex items-center gap-1 text-[11px] text-brand">
                <i data-lucide="pencil" class="text-[10px]"></i>
                customized
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="hidden md:table-cell px-4 py-3 align-top">
        <span class="font-medium"><?php echo e($b->trigger->label()); ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->triggerValue !== null): ?>
            <span class="text-muted-foreground">: <?php echo e($b->triggerValue); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="hidden lg:table-cell px-4 py-3 align-top tabular-nums"><?php echo e($b->threshold); ?></td>
    <td class="hidden lg:table-cell px-4 py-3 align-top tabular-nums text-muted-foreground"><?php echo e($b->window ?? '—'); ?></td>
    <td class="hidden xl:table-cell px-4 py-3 align-top">
        <div class="flex flex-wrap gap-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $b->channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[11px] rounded-md bg-muted text-muted-foreground ring-1 ring-inset ring-border">
                    <i data-lucide="<?php echo e($channelIcon[$ch] ?? 'radio'); ?>" class="text-[11px]"></i>
                    <?php echo e($ch); ?>

                </span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </td>
    <td class="px-4 py-3 align-top">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->effectivelyEnabled): ?>
            <span class="inline-flex items-center gap-1 rounded-md bg-success/10 text-success ring-1 ring-inset ring-success/25 px-2 py-0.5 text-xs font-medium">
                <i data-lucide="check-circle-2" class="text-[12px]"></i>
                Enabled
            </span>
        <?php else: ?>
            <span class="inline-flex items-center gap-1 rounded-md bg-muted text-muted-foreground ring-1 ring-inset ring-border px-2 py-0.5 text-xs font-medium">
                <i data-lucide="power-off" class="text-[12px]"></i>
                Disabled
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="px-3 py-3 align-top text-right">
        <?php echo $__env->make('jobs-monitor::partials.kebab-actions', ['actions' => $rowActions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </td>
</tr>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditing): ?>
<tr class="bg-brand/5" id="rule-<?php echo e($b->key); ?>">
    <td colspan="7" class="px-4 py-4">
        <?php echo $__env->make('jobs-monitor::settings.alerts._built-in-edit-form', ['b' => $b], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </td>
</tr>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\settings\alerts\_built-in-row.blade.php ENDPATH**/ ?>