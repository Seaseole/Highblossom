<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'id' => null,
    'name' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
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
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'id' => null,
    'name' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $id ?? 'input-'.md5((string) $name);
    $name = $name ?? $id;
    $baseClasses = 'block w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#FAFAFA] text-sm placeholder-[#71717A] transition-all duration-300 ease-out-expo focus:outline-none focus:border-[#DC2626]/50 focus:ring-4 focus:ring-[#DC2626]/10 focus:bg-white/8';
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
    $errorClasses = $error ? 'border-red-500 focus:border-red-500 focus:ring-red-500/10' : '';
?>

<div <?php echo e($attributes->except(['type', 'id', 'name', 'value', 'placeholder', 'required', 'disabled'])->class('space-y-2')); ?>>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label for="<?php echo e($id); ?>" class="ml-1 block text-sm font-semibold text-[#FAFAFA]/70">
            <?php echo e($label); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?>
                <span class="ml-0.5 text-[#DC2626]">*</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="relative">
        <input <?php echo e($attributes->only(['type', 'id', 'name', 'value', 'placeholder', 'required', 'disabled'])->merge([
                'type' => $type,
                'id' => $id,
                'name' => $name,
                'value' => $value,
                'placeholder' => $placeholder,
                'required' => $required,
                'disabled' => $disabled,
                'class' => "$baseClasses $disabledClasses $errorClasses",
            ])); ?> />

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
        <p class="animate-in fade-in slide-in-from-top-1 mt-1.5 ml-1 text-xs font-medium text-red-500 duration-200">
            <?php echo e($error); ?>

        </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\ui\input.blade.php ENDPATH**/ ?>