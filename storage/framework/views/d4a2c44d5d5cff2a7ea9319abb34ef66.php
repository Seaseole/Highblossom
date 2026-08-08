<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'heading' => '',
    'text' => '',
    'button_text' => '',
    'button_url' => '#',
    'style' => 'primary',
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
    'heading' => '',
    'text' => '',
    'button_text' => '',
    'button_url' => '#',
    'style' => 'primary',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $containerClass = match ($style) {
        'dark' => 'bg-gray-900 text-white',
        'primary' => 'bg-indigo-600 text-white',
        default => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
    };

    $buttonClass = match ($style) {
        'dark' => 'bg-white text-gray-900 hover:bg-gray-100',
        'primary' => 'bg-white text-indigo-600 hover:bg-gray-100',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700',
    };

    $textClass = $style === 'default' ? 'text-gray-600 dark:text-gray-300' : 'text-white/90';
?>

<section class="<?php echo e($containerClass); ?> py-12 px-6 rounded-xl my-8">
    <div class="mx-auto max-w-3xl text-center">
        <h3 class="mb-4 text-2xl font-bold md:text-3xl"><?php echo e($heading); ?></h3>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($text): ?>
            <p class="text-lg <?php echo e($textClass); ?> mb-6"><?php echo e($text); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <a
            href="<?php echo e($button_url); ?>"
            class="inline-flex items-center px-6 py-3 text-base font-medium rounded-lg shadow-sm <?php echo e($buttonClass); ?> transition-colors"
        >
            <?php echo e($button_text); ?>

        </a>
    </div>
</section>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\cta.blade.php ENDPATH**/ ?>