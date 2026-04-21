@props([
    'columns' => '3',
    'gap' => 'medium',
    'images' => []
])

@php
$images = is_array($images) ? $images : [];
@endphp

@php
$gridClass = match($columns) {
    '2' => 'grid-cols-1 md:grid-cols-2',
    '4' => 'grid-cols-2 md:grid-cols-4',
    default => 'grid-cols-2 md:grid-cols-3',
};

$gapClass = match($gap) {
    'small' => 'gap-2',
    'large' => 'gap-6',
    default => 'gap-4',
};
@endphp

<figure class="my-8">
    <div class="grid {{ $gridClass }} {{ $gapClass }}">
        @foreach($images as $image)
            <div class="relative group">
                <div class="aspect-square overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
                    @if(!empty($image['url']))
                        <img
                            src="{{ $image['url'] }}"
                            alt="{{ $image['alt'] ?? '' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
                @if(!empty($image['caption']))
                    <figcaption class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
                        {{ $image['caption'] }}
                    </figcaption>
                @endif
            </div>
        @endforeach
    </div>
</figure>
