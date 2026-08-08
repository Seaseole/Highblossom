<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'heading' => '',
    'subheading' => '',
    'image' => null,
    'cta_text' => '',
    'cta_url' => '#',
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
    'heading' => '',
    'subheading' => '',
    'image' => null,
    'cta_text' => '',
    'cta_url' => '#',
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
    $alignClass = match ($alignment) {
        'left' => 'text-left items-start',
        'right' => 'text-right items-end',
        default => 'text-center items-center',
    };
?>

<section class="relative py-16 lg:py-24">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo e($image); ?>" alt="" class="h-full w-full object-cover" />
            <div class="absolute inset-0 bg-black/50"></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col <?php echo e($alignClass); ?> max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold <?php echo e($image ? 'text-white' : 'text-gray-900 dark:text-white'); ?>">
                <?php echo e($heading); ?>

            </h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subheading): ?>
                <p class="mt-4 text-xl <?php echo e($image ? 'text-gray-200' : 'text-gray-600 dark:text-gray-300'); ?>">
                    <?php echo e($subheading); ?>

                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cta_text): ?>
                <div class="mt-8">
                    <a
                        href="<?php echo e($cta_url); ?>"
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    >
                        <?php echo e($cta_text); ?>

                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\hero.blade.php ENDPATH**/ ?>