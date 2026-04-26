@php
    $url = $url ?? '';
    $title = $title ?? null;
    $embedHtml = $embed_html ?? null;
    $embedTitle = $embed_title ?? null;
    $embedThumbnail = $embed_thumbnail ?? null;
    $embedProvider = $embed_provider ?? null;
@endphp

<div class="cb-embed">
    @if($embedHtml)
        <div class="cb-embed__iframe-wrapper">
            {!! $embedHtml !!}
        </div>
    @elseif($url)
        <div class="cb-embed__fallback">
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="cb-embed__link">
                @if($embedThumbnail)
                    <img src="{{ $embedThumbnail }}" alt="{{ $embedTitle ?? $title ?? 'Embedded content' }}" class="cb-embed__thumbnail">
                @endif
                <div class="cb-embed__meta">
                    @if($embedProvider)
                        <span class="cb-embed__provider">{{ $embedProvider }}</span>
                    @endif
                    <span class="cb-embed__url">{{ $url }}</span>
                </div>
            </a>
        </div>
    @endif
</div>
