<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'status' => 500,
    'title' => 'Server Error',
    'description' => 'Something went wrong on our end. Please try again later.',
    'actionText' => 'Go Home',
    'actionUrl' => '/',
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
    'status' => 500,
    'title' => 'Server Error',
    'description' => 'Something went wrong on our end. Please try again later.',
    'actionText' => 'Go Home',
    'actionUrl' => '/',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (isset($component)) { $__componentOriginalae5c3ca666306b3b2dcb109e55417bc9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae5c3ca666306b3b2dcb109e55417bc9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.error-layout','data' => ['title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('error-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="mx-auto w-full max-w-lg">
        <!-- Glass Card -->
        <div class="animate-error-entrance rounded-2xl border border-white/10 bg-[#16161D]/80 p-8 text-center shadow-2xl shadow-[#0A0A0F]/50 backdrop-blur-xl md:p-12">
            <?php
                $businessLogo = $settings->get('business_logo', '');
                $logoText = $settings->get('logo_text', 'Highblossom');
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($businessLogo): ?>
                <div class="mb-6 flex justify-center">
                    <img
                        src="<?php echo e(Storage::url($businessLogo)); ?>"
                        alt="<?php echo e($logoText); ?>"
                        class="h-16 w-auto rounded-lg object-contain shadow-lg"
                    />
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Status Code -->
            <h1 class="font-headline mb-4 text-7xl font-bold text-[#DC2626] md:text-8xl"><?php echo e($status); ?></h1>

            <!-- Title -->
            <h2 class="font-headline mb-4 text-2xl font-semibold text-[#FAFAFA] md:text-3xl"><?php echo e($title); ?></h2>

            <!-- Description -->
            <p class="mb-8 text-base leading-relaxed text-[#eeeef3] md:text-lg"><?php echo e($description); ?></p>

            <!-- Action Button -->
            <a
                href="<?php echo e($actionUrl); ?>"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#DC2626] px-8 py-3 font-semibold text-white shadow-lg shadow-[#DC2626]/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#B91C1C] hover:shadow-xl hover:shadow-[#DC2626]/30 active:scale-[0.97]"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <?php echo e($actionText); ?>

            </a>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae5c3ca666306b3b2dcb109e55417bc9)): ?>
<?php $attributes = $__attributesOriginalae5c3ca666306b3b2dcb109e55417bc9; ?>
<?php unset($__attributesOriginalae5c3ca666306b3b2dcb109e55417bc9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae5c3ca666306b3b2dcb109e55417bc9)): ?>
<?php $component = $__componentOriginalae5c3ca666306b3b2dcb109e55417bc9; ?>
<?php unset($__componentOriginalae5c3ca666306b3b2dcb109e55417bc9); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/minimal.blade.php ENDPATH**/ ?>