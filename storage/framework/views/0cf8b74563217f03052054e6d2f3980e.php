<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'heading' => 'Stay Updated',
    'description' => '',
    'button_text' => 'Subscribe',
    'style' => 'inline',
    'background' => 'primary',
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
    'heading' => 'Stay Updated',
    'description' => '',
    'button_text' => 'Subscribe',
    'style' => 'inline',
    'background' => 'primary',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $containerClass = match ($background) {
        'dark' => 'bg-gray-900 text-white',
        'primary' => 'bg-indigo-600 text-white',
        default => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
    };

    $formClass = $style === 'inline'
        ? 'flex flex-col sm:flex-row gap-3'
        : 'flex flex-col gap-3';

    $inputClass = match ($background) {
        'dark', 'primary' => 'bg-white/10 border-white/20 text-white placeholder:text-white/60 focus:bg-white/20',
        default => 'border-gray-300 dark:border-gray-600 dark:bg-gray-700',
    };

    $buttonClass = match ($background) {
        'dark' => 'bg-white text-gray-900 hover:bg-gray-100',
        'primary' => 'bg-white text-indigo-600 hover:bg-gray-100',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700',
    };
?>

<section class="<?php echo e($containerClass); ?> py-12 px-6 rounded-xl my-8">
    <div class="mx-auto max-w-2xl text-center">
        <h3 class="mb-3 text-2xl font-bold"><?php echo e($heading); ?></h3>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($description): ?>
            <p class="mb-6 text-lg opacity-90"><?php echo e($description); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form action="<?php echo e(route('contact')); ?>" method="GET" class="<?php echo e($formClass); ?>">
            <input
                type="email"
                name="email"
                placeholder="<?php echo e(__('Your email address')); ?>"
                required
                class="flex-1 px-4 py-3 rounded-lg border <?php echo e($inputClass); ?> focus:outline-none focus:ring-2 focus:ring-white/30 transition-all"
            />
            <button
                type="submit"
                class="px-6 py-3 rounded-lg font-semibold <?php echo e($buttonClass); ?> transition-all hover:shadow-lg"
            >
                <?php echo e($button_text); ?>

            </button>
        </form>

        <p class="mt-4 text-xs opacity-70"><?php echo e(__('We respect your privacy. Unsubscribe at any time.')); ?></p>
    </div>
</section>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\newsletter.blade.php ENDPATH**/ ?>