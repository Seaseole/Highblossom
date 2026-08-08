<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'image' => null,
    'alt' => '',
    'caption' => '',
    'alignment' => 'center',
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
    'image' => null,
    'alt' => '',
    'caption' => '',
    'alignment' => 'center',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $containerClass = match ($alignment) {
        'left' => 'text-left',
        'right' => 'text-right',
        'full' => 'w-full',
        default => 'text-center',
    };

    $imgClass = match ($alignment) {
        'left', 'right' => 'inline-block max-w-lg',
        'full' => 'w-full h-auto',
        default => 'inline-block max-w-full h-auto',
    };
?>

<figure class="<?php echo e($containerClass); ?> my-8">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
        <img src="<?php echo e($image); ?>" alt="<?php echo e($alt); ?>" class="<?php echo e($imgClass); ?> rounded-lg shadow-md" />
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($caption): ?>
        <figcaption class="mt-3 text-sm text-gray-600 italic dark:text-gray-400"><?php echo e($caption); ?></figcaption>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</figure>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\image.blade.php ENDPATH**/ ?>