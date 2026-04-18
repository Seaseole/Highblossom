@props([
    'schema_type' => 'BlogPosting',
    'faq_items' => [],
    'post' => null
])

@php
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
    if ($schema_type === 'FAQPage' && !empty($faq_items)) {
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
@endphp

@if($post)
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endif

@if($schema_type === 'FAQPage' && !empty($faq_items))
    <section class="my-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Frequently Asked Questions') }}</h2>
        <div class="space-y-4">
            @foreach($faq_items as $item)
                <details class="group bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <summary class="flex items-center justify-between p-4 cursor-pointer">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $item['question'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-open:rotate-180 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <div class="px-4 pb-4 text-gray-600 dark:text-gray-300">
                        {{ $item['answer'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </section>
@endif
