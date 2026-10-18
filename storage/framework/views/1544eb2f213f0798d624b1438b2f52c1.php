<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'faqs' => null,
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
    'faqs' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $faqs = collect($faqs ?? [])
        ->filter(fn (array $faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
        ->values();
?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($faqs->isNotEmpty()): ?>
    <section id="faq" class="bg-[#0A0A0F] py-20 lg:py-28">
        <div class="mx-auto max-w-[1400px] px-6 lg:px-8">
            <div class="mx-auto mb-12 max-w-2xl text-center">
                <div class="mb-3 text-sm font-semibold tracking-wider text-[#DC2626] uppercase">
                    <?php echo e(__('site.home.faq_label')); ?>

                </div>
                <h2 class="font-headline text-3xl font-bold tracking-tight text-[#FAFAFA] md:text-4xl">
                    <?php echo e(__('site.home.faq_title')); ?>

                </h2>
            </div>

            <div
                class="mx-auto max-w-3xl space-y-3"
                x-data="{ open: 0 }"
            >
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php $panelId = 'faq-panel-'.$index; $buttonId = 'faq-button-'.$index; ?>
                    <div class="glass-card overflow-hidden rounded-2xl">
                        <h3>
                            <button
                                type="button"
                                id="<?php echo e($buttonId); ?>"
                                aria-expanded="false"
                                :aria-expanded="open === <?php echo e($index); ?> ? 'true' : 'false'"
                                aria-controls="<?php echo e($panelId); ?>"
                                @click="open = open === <?php echo e($index); ?> ? null : <?php echo e($index); ?>"
                                class="flex min-h-[52px] w-full items-center justify-between gap-4 px-5 py-4 text-left transition-colors hover:bg-white/[0.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DC2626]/60 lg:px-6"
                            >
                                <span class="font-headline text-base font-semibold text-[#FAFAFA] lg:text-lg">
                                    <?php echo e($faq['question']); ?>

                                </span>
                                <svg
                                    class="h-5 w-5 flex-shrink-0 text-[#DC2626] transition-transform duration-300"
                                    :class="open === <?php echo e($index); ?> ? 'rotate-180' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </h3>

                        <div
                            id="<?php echo e($panelId); ?>"
                            role="region"
                            aria-labelledby="<?php echo e($buttonId); ?>"
                            x-show="open === <?php echo e($index); ?>"
                            x-collapse
                            class="px-5 pb-5 lg:px-6"
                        >
                            <p class="text-sm leading-relaxed text-[#A1A1AA] lg:text-base">
                                <?php echo e($faq['answer']); ?>

                            </p>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>

    <script type="application/ld+json">
        <?php echo json_encode([
            '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs
                ->map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ])
                ->values()
                ->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP); ?>

    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/site/faq.blade.php ENDPATH**/ ?>