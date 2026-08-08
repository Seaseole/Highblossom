<?php
    /** @var string $name */
    /** @var array<string|int, string> $options map of value => label */
    /** @var string|null $value */
    /** @var string|null $placeholder */
    $placeholder = $placeholder ?? 'Select…';
    $value = $value ?? '';
    $triggerLabel = $options[$value] ?? ($value === '' ? ($options[''] ?? $placeholder) : $value);
    $isActive = $value !== '' && $value !== null;
    $id = 'jm-sel-' . bin2hex(random_bytes(4));
?>

<div class="relative" data-jm-select data-jm-select-id="<?php echo e($id); ?>">
    <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" data-jm-select-input>

    <button type="button"
            class="inline-flex items-center justify-between gap-2 h-9 min-w-[9rem] rounded-md border bg-card text-sm px-3 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring
                   <?php echo e($isActive
                        ? 'border-brand/40 ring-2 ring-brand/15 bg-brand/5 text-foreground'
                        : 'border-input text-foreground hover:bg-accent/40 hover:border-ring/40'); ?>"
            data-jm-select-trigger
            aria-haspopup="listbox"
            aria-expanded="false">
        <span class="truncate <?php echo e($isActive ? 'font-medium' : 'text-muted-foreground/90'); ?>" data-jm-select-label><?php echo e($triggerLabel); ?></span>
        <i data-lucide="chevron-down" class="text-[14px] text-muted-foreground shrink-0 transition-transform" data-jm-select-caret></i>
    </button>

    <div class="hidden absolute z-30 mt-1 left-0 min-w-full w-max max-w-[min(20rem,90vw)] rounded-md border border-border bg-popover text-popover-foreground shadow-lg ring-1 ring-black/5 dark:ring-white/5 animate-slide-down overflow-hidden"
         data-jm-select-dropdown
         role="listbox">
        <ul class="p-1 max-h-60 overflow-y-auto overscroll-contain" data-jm-select-list>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optValue => $optLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php $selected = (string) $value === (string) $optValue; ?>
                <li role="option"
                    tabindex="-1"
                    aria-selected="<?php echo e($selected ? 'true' : 'false'); ?>"
                    data-jm-select-option
                    data-value="<?php echo e($optValue); ?>"
                    data-label="<?php echo e($optLabel); ?>"
                    class="flex items-center gap-2 px-2 py-1.5 text-sm rounded-sm cursor-pointer
                           <?php echo e($selected ? 'bg-brand/10 text-foreground font-medium' : 'text-foreground hover:bg-accent hover:text-accent-foreground'); ?>">
                    <span class="w-4 inline-flex justify-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selected): ?>
                            <i data-lucide="check" class="text-[14px] text-brand"></i>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                    <span class="truncate"><?php echo e($optLabel); ?></span>
                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    </div>
</div>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\select.blade.php ENDPATH**/ ?>