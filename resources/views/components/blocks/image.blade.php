@props([
    'image' => null,
    'alt' => '',
    'caption' => '',
    'alignment' => 'center'
])

@php
$containerClass = match($alignment) {
    'left' => 'text-left',
    'right' => 'text-right',
    'full' => 'w-full',
    default => 'text-center',
};

$imgClass = match($alignment) {
    'left', 'right' => 'inline-block max-w-lg',
    'full' => 'w-full h-auto',
    default => 'inline-block max-w-full h-auto',
};
@endphp

<figure class="{{ $containerClass }} my-8">
    @if($image)
        <img src="{{ $image }}" alt="{{ $alt }}" class="{{ $imgClass }} rounded-lg shadow-md">
    @endif

    @if($caption)
        <figcaption class="mt-3 text-sm text-gray-600 dark:text-gray-400 italic">
            {{ $caption }}
        </figcaption>
    @endif
</figure>
