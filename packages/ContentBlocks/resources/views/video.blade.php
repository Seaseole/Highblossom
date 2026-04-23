@if($src)
    @php
    $sourceType = $source_type ?? 'unknown';
    $embedUrl = $embed_url ?? null;
    $fullUrl = $full_url ?? $src;
    $containerClass = $class ?? '';
    $posterUrl = $poster ?? null;
    @endphp

    @if($sourceType === 'youtube' && $embedUrl)
        <div class="aspect-video {{ $containerClass }}">
            <iframe
                src="{{ $embedUrl }}"
                title="YouTube video"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="w-full h-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif($sourceType === 'vimeo' && $embedUrl)
        <div class="aspect-video {{ $containerClass }}">
            <iframe
                src="{{ $embedUrl }}"
                title="Vimeo video"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                class="w-full h-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif($sourceType === 'dailymotion' && $embedUrl)
        <div class="aspect-video {{ $containerClass }}">
            <iframe
                src="{{ $embedUrl }}"
                title="Dailymotion video"
                frameborder="0"
                allow="autoplay; fullscreen"
                allowfullscreen
                class="w-full h-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif($sourceType === 'facebook' && $embedUrl)
        <div class="aspect-video {{ $containerClass }}">
            <iframe
                src="{{ $embedUrl }}"
                title="Facebook video"
                frameborder="0"
                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                allowfullscreen
                class="w-full h-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif($sourceType === 'local_file' || $sourceType === 'direct_url' || $sourceType === 'unknown')
        <video
            @if($containerClass) class="{{ $containerClass }}" @endif
            @if(!empty($posterUrl)) poster="{{ $posterUrl }}" @endif
            @if(!empty($autoplay)) autoplay @endif
            @if(!empty($controls) || !isset($controls)) controls @endif
            playsinline
        >
            <source src="{{ $fullUrl }}" @if(!empty($type)) type="{{ $type }}" @endif>
            Your browser does not support the video tag.
        </video>
    @else
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg text-gray-600 dark:text-gray-400">
            {{ __('Invalid video source') }}
        </div>
    @endif
@endif
