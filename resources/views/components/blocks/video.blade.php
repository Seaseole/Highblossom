@props([
    'url' => '',
    'src' => '',
    'caption' => '',
    'alignment' => 'center',
    'poster' => '',
    'autoplay' => false,
    'controls' => true,
    'class' => '',
])

@php
use App\Services\VideoSourceDetector;

$videoUrl = $url ?: $src;
$detector = app(VideoSourceDetector::class);
$sourceType = $detector->detect($videoUrl);
$videoId = $detector->extractVideoId($videoUrl, $sourceType);
$embedUrl = $detector->getEmbedUrl($videoUrl, $sourceType);
$fullUrl = $detector->getFullUrl($videoUrl);

$containerClass = match($alignment) {
    'left' => 'text-left',
    'right' => 'text-right',
    'full' => 'w-full',
    default => 'text-center',
};

$wrapperClass = $alignment === 'full' ? 'w-full' : 'max-w-3xl mx-auto';
@endphp

<figure class="{{ $containerClass }} my-8">
    @if($sourceType->usesIframe() && $embedUrl)
        {{-- Platform embeds (YouTube, Vimeo, Dailymotion, Facebook) --}}
        <div class="aspect-video {{ $wrapperClass }} {{ $class }}">
            <iframe
                src="{{ $embedUrl }}"
                title="{{ $sourceType->label() }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                allowfullscreen
                class="w-full h-full rounded-lg shadow-md"
                loading="lazy"
            ></iframe>
        </div>
    @elseif($sourceType->value === 'local_file' || $sourceType->value === 'direct_url' || $sourceType->value === 'unknown')
        {{-- Native video player for local files and direct URLs --}}
        <div class="{{ $wrapperClass }}">
            <video
                @if($class) class="{{ $class }}" @endif
                @if($poster) poster="{{ $poster }}" @endif
                @if($autoplay) autoplay @endif
                @if($controls) controls @endif
                playsinline
                class="w-full rounded-lg shadow-md"
            >
                <source src="{{ $fullUrl }}">
                Your browser does not support the video tag.
            </video>
        </div>
    @else
        <div class="p-4 bg-admin-surface border border-admin-border rounded-lg text-admin-text-muted">
            {{ __('Invalid video source') }}
        </div>
    @endif

    @if($caption)
        <figcaption class="mt-3 text-sm text-admin-text-muted italic">
            {{ $caption }}
        </figcaption>
    @endif
</figure>
