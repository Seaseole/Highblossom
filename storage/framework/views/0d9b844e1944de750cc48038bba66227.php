<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'text' => null,
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
    'text' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $text = trim((string) $text);
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($text !== ''): ?>
    <section class="border-y border-[#DC2626]/20 bg-gradient-to-r from-[#73081d]/25 via-[#0A0A0F] to-[#73081d]/25 py-8" aria-label="<?php echo e(__('site.home.urgency_call')); ?>">
        <div class="mx-auto flex max-w-[1400px] flex-col items-center gap-5 px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3 flex-shrink-0" aria-hidden="true">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#DC2626] opacity-75"></span>
                    <span class="relative inline-flex h-3 w-3 rounded-full bg-[#DC2626]"></span>
                </span>
                <h2 class="font-headline text-center text-xl font-bold tracking-tight text-[#FAFAFA] sm:text-left sm:text-2xl">
                    <?php echo e($text); ?>

                </h2>
            </div>

            <?php if (isset($component)) { $__componentOriginal1346536acca2f562295db5577845269d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1346536acca2f562295db5577845269d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site.contact-buttons','data' => ['quoteLabel' => null,'callLabel' => __('site.home.urgency_call'),'whatsappLabel' => __('site.home.urgency_whatsapp'),'showQuote' => false,'class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site.contact-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['quote-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'call-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('site.home.urgency_call')),'whatsapp-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('site.home.urgency_whatsapp')),'show-quote' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => 'w-full sm:w-auto']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1346536acca2f562295db5577845269d)): ?>
<?php $attributes = $__attributesOriginal1346536acca2f562295db5577845269d; ?>
<?php unset($__attributesOriginal1346536acca2f562295db5577845269d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1346536acca2f562295db5577845269d)): ?>
<?php $component = $__componentOriginal1346536acca2f562295db5577845269d; ?>
<?php unset($__componentOriginal1346536acca2f562295db5577845269d); ?>
<?php endif; ?>
        </div>
    </section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/site/urgency-band.blade.php ENDPATH**/ ?>