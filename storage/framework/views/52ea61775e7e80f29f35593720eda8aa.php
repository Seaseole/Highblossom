<div>
    <label class="text-admin-text-muted mb-2 block text-sm font-medium">Content</label>
    <textarea
        id="paragraph-editor-<?php echo e($index); ?>"
        name="content"
        wire:model.live="blocks.<?php echo e($index); ?>.attributes.content"
        rows="6"
        class="bg-admin-surface-alt border-admin-border text-admin-text placeholder-admin-text-muted focus:ring-admin-accent w-full rounded-xl border px-4 py-3 focus:border-transparent focus:ring-2"
        placeholder="Enter paragraph text..."
    ></textarea>
</div>

<?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['wire:model.live' => 'blocks.'.e($index).'.attributes.class','label' => 'CSS Classes','placeholder' => 'Additional CSS classes...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'blocks.'.e($index).'.attributes.class','label' => 'CSS Classes','placeholder' => 'Additional CSS classes...']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\paragraph-editor.blade.php ENDPATH**/ ?>