@props([
    'url' => '',
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

$videoId = null;
$platform = null;

// Extract video ID from YouTube or Vimeo URLs
if ($url && preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\s]+)/', $url, $matches)) {
    $videoId = $matches[1];
    $platform = 'youtube';
} elseif ($url && preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
    $videoId = $matches[1];
    $platform = 'vimeo';
}
@endphp

<figure class="{{ $containerClass }} my-8">
    @if($videoId && $platform === 'youtube')
        <div class="aspect-video {{ $alignment === 'full' ? 'w-full' : 'max-w-3xl mx-auto' }}">
            <iframe
                src="https://www.youtube.com/embed/{{ $videoId }}"
                title="YouTube video"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="w-full h-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif($videoId && $platform === 'vimeo')
        <div class="aspect-video {{ $alignment === 'full' ? 'w-full' : 'max-w-3xl mx-auto' }}">
            <iframe
                src="https://player.vimeo.com/video/{{ $videoId }}"
                title="Vimeo video"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                class="w-full h-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @else
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400">
            {{ __('Invalid video URL') }}
        </div>
    @endif

    @if($caption)
        <figcaption class="mt-3 text-sm text-gray-600 dark:text-gray-400 italic">
            {{ $caption }}
        </figcaption>
    @endif
</figure>
