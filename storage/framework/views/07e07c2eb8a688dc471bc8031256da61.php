<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'user' => null,
    'size' => 'sm',
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
    'user' => null,
    'size' => 'sm',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // One source of truth for a user's picture: the same avatar_url accessor the
    // profile page uses, so a disk or path change can never fix one surface and
    // leave another showing initials.
    $url = $user?->avatar_url;

    $sizes = [
        'xs' => ['box' => 'size-6', 'text' => 'text-[10px]'],
        'sm' => ['box' => 'size-8', 'text' => 'text-xs'],
        'md' => ['box' => 'size-10', 'text' => 'text-sm'],
    ];
    $size = $sizes[$size] ?? $sizes['sm'];
?>

<span
    <?php echo e($attributes->class([
        'relative flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-gray-200 dark:bg-white/10',
        $size['box'],
    ])); ?>

>
    
    <span
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([$size['text'], 'font-bold text-gray-700 dark:text-gray-300']); ?>"
        <?php if($url !== null): ?> aria-hidden="true" <?php endif; ?>
    ><?php echo e($user?->initials() ?? '?'); ?></span>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($url !== null): ?>
        <img
            src="<?php echo e($url); ?>"
            alt="Avatar of <?php echo e($user?->name); ?>"
            class="absolute inset-0 size-full rounded-full object-cover"
            onerror="this.remove()"
        />
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</span>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/user-avatar.blade.php ENDPATH**/ ?>