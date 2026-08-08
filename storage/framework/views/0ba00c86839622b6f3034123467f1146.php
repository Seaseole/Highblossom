

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'default',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'variant' => 'default',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (!Flux::componentExists($name = 'radio.group.variants.'.$variant)) throw new \Exception("Flux component [{$name}] does not exist."); ?><?php if (isset($component)) { $__componentOriginalf8ad5a7354c8d12670bbe9c417f52b03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8ad5a7354c8d12670bbe9c417f52b03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve([
    'view' => (app()->version() >= 12 ? hash('xxh128', 'flux') : md5('flux')) . '::' . 'radio.group.variants.'.$variant,
    'data' => $__env->getCurrentComponentData(),
] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::' . 'radio.group.variants.'.$variant); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes($attributes->getAttributes()); ?><?php echo e($slot); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8ad5a7354c8d12670bbe9c417f52b03)): ?>
<?php $attributes = $__attributesOriginalf8ad5a7354c8d12670bbe9c417f52b03; ?>
<?php unset($__attributesOriginalf8ad5a7354c8d12670bbe9c417f52b03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8ad5a7354c8d12670bbe9c417f52b03)): ?>
<?php $component = $__componentOriginalf8ad5a7354c8d12670bbe9c417f52b03; ?>
<?php unset($__componentOriginalf8ad5a7354c8d12670bbe9c417f52b03); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\flux\radio\group\index.blade.php ENDPATH**/ ?>