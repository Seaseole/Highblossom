

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

<?php if (!Flux::componentExists($name = 'checkbox.group.variants.'.$variant)) throw new \Exception("Flux component [{$name}] does not exist."); ?><?php if (isset($component)) { $__componentOriginal5184eeb88bea035151b8fabf38ffd22d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5184eeb88bea035151b8fabf38ffd22d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve([
    'view' => (app()->version() >= 12 ? hash('xxh128', 'flux') : md5('flux')) . '::' . 'checkbox.group.variants.'.$variant,
    'data' => $__env->getCurrentComponentData(),
] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::' . 'checkbox.group.variants.'.$variant); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes($attributes->getAttributes()); ?><?php echo e($slot); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5184eeb88bea035151b8fabf38ffd22d)): ?>
<?php $attributes = $__attributesOriginal5184eeb88bea035151b8fabf38ffd22d; ?>
<?php unset($__attributesOriginal5184eeb88bea035151b8fabf38ffd22d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5184eeb88bea035151b8fabf38ffd22d)): ?>
<?php $component = $__componentOriginal5184eeb88bea035151b8fabf38ffd22d; ?>
<?php unset($__componentOriginal5184eeb88bea035151b8fabf38ffd22d); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\flux\checkbox\group\index.blade.php ENDPATH**/ ?>