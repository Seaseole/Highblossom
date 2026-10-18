<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'area' => null,
    'coverage' => null,
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
    'area' => null,
    'coverage' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $area = trim((string) $area);
    $coverage = trim((string) $coverage);
    $address = trim($contact->address);
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($area !== '' || $coverage !== '' || $address !== ''): ?>
    <section id="service-area" class="bg-[#121218] py-20 lg:py-24">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                <?php echo e(__('site.home.service_area_label')); ?>

            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($area !== ''): ?>
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="font-headline mb-2 text-lg font-bold text-[#FAFAFA]"><?php echo e(__('site.home.service_area_label')); ?></h3>
                        <p class="text-sm leading-relaxed text-[#A1A1AA]"><?php echo e($area); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coverage !== ''): ?>
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="font-headline mb-2 text-lg font-bold text-[#FAFAFA]"><?php echo e(__('site.home.service_area_mobile')); ?></h3>
                        <p class="text-sm leading-relaxed text-[#A1A1AA]"><?php echo e($coverage); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($address !== ''): ?>
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="font-headline mb-2 text-lg font-bold text-[#FAFAFA]"><?php echo e(__('site.home.service_area_workshop')); ?></h3>
                        <p class="text-sm leading-relaxed text-[#A1A1AA]"><?php echo e($address); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mapDirectionsLink): ?>
                            <a
                                href="<?php echo e($mapDirectionsLink); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-4 inline-flex min-h-[44px] items-center gap-2 text-sm font-semibold text-[#DC2626] transition-colors hover:text-[#B91C1C] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60"
                            >
                                <span><?php echo e(__('site.home.service_area_directions')); ?></span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/site/service-area.blade.php ENDPATH**/ ?>