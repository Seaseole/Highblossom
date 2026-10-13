
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['reason', 'title', 'message', 'cta' => null]));

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

foreach (array_filter((['reason', 'title', 'message', 'cta' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    data-passkey-recovery="<?php echo e($reason); ?>"
    role="alert"
    <?php echo e($attributes->class('hidden rounded-2xl border border-[#E4E4E7] bg-[#F9FAFB] px-5 py-4')); ?>

>
    <p class="mb-1 text-sm font-bold text-[#18181B]"><?php echo e($title); ?></p>
    <p class="text-sm text-[#71717A]"><?php echo e($message); ?></p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cta): ?>
        <button
            type="button"
            data-passkey-signin
            class="mt-3 rounded-xl border-2 border-[#E4E4E7] bg-white px-4 py-2 text-sm font-bold text-[#18181B] transition-colors hover:border-[#DC2626]/30 focus:ring-4 focus:ring-[#DC2626]/10 focus:outline-none"
        ><?php echo e($cta); ?></button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/passkey-recovery-panel.blade.php ENDPATH**/ ?>