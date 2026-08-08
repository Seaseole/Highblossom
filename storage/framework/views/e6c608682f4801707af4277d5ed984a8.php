<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => null,
    'id' => null,
    'value' => 1,
    'checked' => false,
    'label' => null,
    'wireModel' => null,
    'xModel' => null,
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
    'name' => null,
    'id' => null,
    'value' => 1,
    'checked' => false,
    'label' => null,
    'wireModel' => null,
    'xModel' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $id ?? ($name ? str_replace(['[', ']'], ['_', ''], $name) : uniqid('cbx_'));
    $hasXModel = $xModel || $attributes->has('x-model');
    $xModelValue = $xModel ?? $attributes->get('x-model');
    $inputAttributes = $attributes->except(['class', 'wire:model', 'x-model', 'xModel']);
    $hasSlot = ! empty(trim($slot ?? ''));
?>

<label class="ui-checkbox-wrapper group inline-flex cursor-pointer items-center gap-3">
    <input
        type="checkbox"
        id="<?php echo e($id); ?>"
        name="<?php echo e($name); ?>"
        value="<?php echo e($value); ?>"
        <?php if($checked): ?> checked <?php endif; ?>
        <?php if($wireModel): ?> wire:model="<?php echo e($wireModel); ?>" <?php endif; ?>
        <?php if($hasXModel): ?> x-model="<?php echo e($xModelValue); ?>" <?php endif; ?>
        <?php echo e($inputAttributes); ?>

        class="ui-checkbox-input"
    />
    <span class="ui-checkbox-check">
        <svg width="18px" height="18px" viewBox="0 0 18 18">
            <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path>
            <polyline points="1 9 7 14 15 4"></polyline>
        </svg>
    </span>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSlot): ?>
        <span class="ui-checkbox-label"><?php echo e($slot); ?></span>
    <?php elseif($label): ?>
        <span class="ui-checkbox-label"><?php echo e($label); ?></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</label>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/ui/checkbox.blade.php ENDPATH**/ ?>