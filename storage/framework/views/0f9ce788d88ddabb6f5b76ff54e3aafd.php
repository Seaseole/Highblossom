



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => null,
    'name' => null,
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
    'icon' => null,
    'name' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $icon = $name ?? $icon;
?>

<?php if (!Flux::componentExists($name = 'icon.'.$icon)) throw new \Exception("Flux component [{$name}] does not exist."); ?><?php if (isset($component)) { $__componentOriginal9e7774cbff0fdf106106e628b6207c59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e7774cbff0fdf106106e628b6207c59 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve([
    'view' => (app()->version() >= 12 ? hash('xxh128', 'flux') : md5('flux')) . '::' . 'icon.'.$icon,
    'data' => $__env->getCurrentComponentData(),
] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::' . 'icon.'.$icon); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes($attributes->getAttributes()); ?><?php echo e($slot); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9e7774cbff0fdf106106e628b6207c59)): ?>
<?php $attributes = $__attributesOriginal9e7774cbff0fdf106106e628b6207c59; ?>
<?php unset($__attributesOriginal9e7774cbff0fdf106106e628b6207c59); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9e7774cbff0fdf106106e628b6207c59)): ?>
<?php $component = $__componentOriginal9e7774cbff0fdf106106e628b6207c59; ?>
<?php unset($__componentOriginal9e7774cbff0fdf106106e628b6207c59); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\flux\icon\index.blade.php ENDPATH**/ ?>