<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'quote' => '',
    'author' => '',
    'title' => '',
    'avatar' => null,
    'style' => 'default',
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
    'quote' => '',
    'author' => '',
    'title' => '',
    'avatar' => null,
    'style' => 'default',
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
        'large' => 'py-12 border-l-4 border-indigo-500 pl-8',
        'testimonial' => 'bg-gray-50 dark:bg-gray-800 p-8 rounded-xl shadow-sm',
        default => 'py-8 border-l-4 border-gray-300 dark:border-gray-600 pl-6',
    };

    $quoteClass = match ($style) {
        'large' => 'text-2xl md:text-3xl font-light text-gray-900 dark:text-white italic',
        'testimonial' => 'text-lg text-gray-700 dark:text-gray-300',
        default => 'text-xl text-gray-700 dark:text-gray-300 italic',
    };
?>

<blockquote class="<?php echo e($containerClass); ?>">
    <p class="<?php echo e($quoteClass); ?>">"<?php echo e($quote); ?>"</p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($author || $title || $avatar): ?>
        <footer class="mt-6 flex items-center gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($avatar): ?>
                <img src="<?php echo e($avatar); ?>" alt="<?php echo e($author); ?>" class="h-12 w-12 rounded-full object-cover" />
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($author): ?>
                    <cite class="font-semibold text-gray-900 not-italic dark:text-white"> <?php echo e($author); ?> </cite>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($title): ?>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($title); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </footer>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</blockquote>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\quote.blade.php ENDPATH**/ ?>