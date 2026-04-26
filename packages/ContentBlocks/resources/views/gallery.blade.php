@php
    $images = $images ?? [];
    $columns = $columns ?? 3;
    $columns = max(1, min(6, $columns));
@endphp

<div class="cb-gallery" data-cb-gallery-columns="{{ $columns }}">
    <div class="cb-gallery__grid" style="--cb-gallery-columns: {{ $columns }};">
        @foreach($images as $index => $image)
            <div class="cb-gallery__item">
                <figure class="cb-gallery__figure">
                    <img
                        src="{{ $image['src'] ?? '' }}"
                        alt="{{ $image['alt'] ?? '' }}"
                        loading="lazy"
                        class="cb-gallery__image"
                    >
                    @if(isset($image['caption']) && $image['caption'])
                        <figcaption class="cb-gallery__caption">{{ $image['caption'] }}</figcaption>
                    @endif
                </figure>
            </div>
        @endforeach
    </div>
</div>
