@if ($src)
    @php
        $sourceType = $source_type ?? 'unknown';
        $embedUrl = $embed_url ?? null;
        $fullUrl = $full_url ?? $src;
        $containerClass = $class ?? '';
        $posterUrl = $poster ?? null;
    @endphp

    @if ($sourceType === 'youtube' && $embedUrl)
        {{-- Click-to-play facade: the player iframe is only created on the click,
             so the platform thumbnail paints immediately and no YouTube consent
             cookies are requested before the visitor asks to watch. --}}
        <div class="aspect-video {{ $containerClass }}" x-data="{ playing: false }">
            <a
                href="{{ $watch_url }}"
                x-show="! playing"
                @click.prevent="playing = true"
                aria-label="{{ __('Play video') }}"
                class="group relative block h-full w-full overflow-hidden rounded-lg shadow-md"
            >
                <img
                    src="{{ $thumbnail_url }}"
                    alt=""
                    loading="lazy"
                    class="h-full w-full object-cover"
                />
                <span class="absolute inset-0 flex items-center justify-center bg-black/30 transition group-hover:bg-black/45">
                    <svg viewBox="0 0 24 24" class="h-14 w-14 fill-white/90" aria-hidden="true" focusable="false">
                        <path d="M8 5.5v13l11-6.5z" />
                    </svg>
                </span>
            </a>
            <template x-if="playing">
                <div class="h-full w-full">
                    <iframe
                        src="{{ $embedUrl }}&amp;autoplay=1"
                        title="YouTube video"
                        frameborder="0"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="h-full w-full rounded-lg shadow-md"
                    ></iframe>
                </div>
            </template>
        </div>
    @elseif ($sourceType === 'vimeo' && $embedUrl)
        <div class="aspect-video {{ $containerClass }}">
            <iframe
                src="{{ $embedUrl }}"
                title="Vimeo video"
                frameborder="0"
                referrerpolicy="strict-origin-when-cross-origin"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif ($sourceType === 'dailymotion' && $embedUrl)
        <div class="aspect-video {{ $containerClass }}">
            <iframe
                src="{{ $embedUrl }}"
                title="Dailymotion video"
                frameborder="0"
                referrerpolicy="strict-origin-when-cross-origin"
                allow="autoplay; fullscreen"
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif ($sourceType === 'facebook' && $embedUrl)
        <div class="aspect-video {{ $containerClass }}">
            <iframe
                src="{{ $embedUrl }}"
                title="Facebook video"
                frameborder="0"
                referrerpolicy="strict-origin-when-cross-origin"
                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                allowfullscreen
                class="h-full w-full rounded-lg shadow-md"
            ></iframe>
        </div>
    @elseif ($sourceType === 'local_file' || $sourceType === 'direct_url' || $sourceType === 'unknown')
        <video
            @if ($containerClass) class="{{ $containerClass }}" @endif
            @if (! empty($posterUrl)) poster="{{ $posterUrl }}" @endif
            @if (! empty($autoplay)) autoplay @endif
            @if (! empty($controls) || ! isset($controls)) controls @endif
            playsinline
        >
            <source src="{{ $fullUrl }}" @if (! empty($type)) type="{{ $type }}" @endif />
            Your browser does not support the video tag.
        </video>
    @else
        <div class="rounded-lg bg-gray-100 p-4 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
            {{ __('Invalid video source') }}
        </div>
    @endif
@endif
