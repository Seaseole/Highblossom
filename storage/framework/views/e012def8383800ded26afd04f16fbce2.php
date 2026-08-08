<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'schema_type' => 'BlogPosting',
    'faq_items' => [],
    'post' => null,
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
    'schema_type' => 'BlogPosting',
    'faq_items' => [],
    'post' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $schema_type,
    ];

    if ($post) {
        $schema['headline'] = $post->title;
        $schema['description'] = $post->excerpt;
        $schema['url'] = route('blog.show', $post);
        $schema['datePublished'] = $post->published_at?->toIso8601String();
        $schema['dateModified'] = $post->updated_at?->toIso8601String();

        if ($post->author) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $post->author->name,
            ];
        }

        if ($post->featured_image) {
            $schema['image'] = $post->featured_image;
        }

        // FAQ schema
        if ($schema_type === 'FAQPage' && ! empty($faq_items)) {
            $schema['mainEntity'] = collect($faq_items)->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'],
                ],
            ])->toArray();
        }
    }
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post): ?>
    <script type="application/ld+json">
        <?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>

    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schema_type === 'FAQPage' && ! empty($faq_items)): ?>
    <section class="my-8">
        <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white"><?php echo e(__('Frequently Asked Questions')); ?></h2>
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $faq_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <details class="group rounded-lg bg-gray-50 dark:bg-gray-800">
                    <summary class="flex cursor-pointer items-center justify-between p-4">
                        <span class="font-medium text-gray-900 dark:text-white"><?php echo e($item['question']); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <div class="px-4 pb-4 text-gray-600 dark:text-gray-300"><?php echo e($item['answer']); ?></div>
                </details>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views\components\blocks\seo.blade.php ENDPATH**/ ?>