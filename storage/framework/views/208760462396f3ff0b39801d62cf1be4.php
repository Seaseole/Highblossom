<?php
    /**
     * Reusable "three-dots" actions menu.
     *
     * Action item shapes:
     *   - ['type' => 'link',    'url' => ..., 'icon' => ..., 'iconColor' => ..., 'label' => ..., 'danger' => bool]
     *   - ['type' => 'form',    'url' => ..., 'method' => 'POST', 'icon' => ..., 'label' => ...]
     *   - ['type' => 'confirm', 'url' => ..., 'icon' => ..., 'label' => ...,
     *      'confirm' => ['title' => ..., 'body' => ..., 'submitLabel' => 'Delete', 'variant' => 'danger']]
     *
     * @var array $actions
     * @var string|null $emptyLabel  placeholder rendered when $actions is empty
     */
    $actions = $actions ?? [];
    $emptyLabel = $emptyLabel ?? null;
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($actions) === 0): ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($emptyLabel): ?>
        <span class="text-[11px] text-muted-foreground italic"><?php echo e($emptyLabel); ?></span>
    <?php else: ?>
        <span class="text-xs text-muted-foreground">—</span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php else: ?>
    <div class="relative inline-block text-left" data-jm-kebab>
        <button type="button"
                class="inline-flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-ring"
                title="Actions"
                onclick="__jmToggleKebab(this)">
            <i data-lucide="more-horizontal" class="text-[16px]"></i>
        </button>
        <div class="hidden absolute right-0 z-20 mt-1 w-52 origin-top-right rounded-lg bg-popover text-popover-foreground shadow-lg ring-1 ring-border focus:outline-none animate-slide-down"
             data-jm-kebab-dropdown>
            <div class="p-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $type = $a['type'] ?? 'link';
                        $rowClass = ($a['danger'] ?? false)
                            ? 'text-destructive hover:bg-destructive/10'
                            : 'hover:bg-accent hover:text-accent-foreground';
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type === 'form'): ?>
                        <form method="<?php echo e($a['method'] ?? 'POST'); ?>" action="<?php echo e($a['url']); ?>" class="block">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center gap-2 px-2.5 py-1.5 text-sm rounded-md <?php echo e($rowClass); ?>">
                                <i data-lucide="<?php echo e($a['icon']); ?>" class="text-[14px] <?php echo e($a['iconColor'] ?? ''); ?>"></i>
                                <?php echo e($a['label']); ?>

                            </button>
                        </form>
                    <?php elseif($type === 'confirm'): ?>
                        <?php
                            $confirm = $a['confirm'] ?? [];
                            $variant = $confirm['variant'] ?? (($a['danger'] ?? false) ? 'danger' : 'primary');
                        ?>
                        <button type="button"
                                class="w-full flex items-center gap-2 px-2.5 py-1.5 text-sm rounded-md <?php echo e($rowClass); ?>"
                                data-jm-confirm-trigger
                                data-jm-action="<?php echo e($a['url']); ?>"
                                data-jm-method="<?php echo e($a['method'] ?? 'POST'); ?>"
                                data-jm-title="<?php echo e($confirm['title'] ?? 'Confirm'); ?>"
                                data-jm-body="<?php echo e($confirm['body'] ?? 'Are you sure?'); ?>"
                                data-jm-submit="<?php echo e($confirm['submitLabel'] ?? 'Confirm'); ?>"
                                data-jm-icon="<?php echo e($a['icon']); ?>"
                                data-jm-variant="<?php echo e($variant); ?>">
                            <i data-lucide="<?php echo e($a['icon']); ?>" class="text-[14px] <?php echo e($a['iconColor'] ?? ''); ?>"></i>
                            <?php echo e($a['label']); ?>

                        </button>
                    <?php else: ?>
                        <a href="<?php echo e($a['url']); ?>" class="flex items-center gap-2 px-2.5 py-1.5 text-sm rounded-md <?php echo e($rowClass); ?>">
                            <i data-lucide="<?php echo e($a['icon']); ?>" class="text-[14px] <?php echo e($a['iconColor'] ?? ''); ?>"></i>
                            <?php echo e($a['label']); ?>

                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\kebab-actions.blade.php ENDPATH**/ ?>