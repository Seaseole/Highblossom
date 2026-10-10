@php
    // A block holds either a single image or an ordered set; a populated set wins.
    $set = array_values(array_filter(
        is_array($images ?? null) ? $images : [],
        fn ($image): bool => is_array($image) && ! empty($image['src'])
    ));

    $total = count($set);
    $gridCls = match (true) {
        $total === 2 => 'grid-cols-1 sm:grid-cols-2',
        $total === 3 => 'grid-cols-1 sm:grid-cols-3',
        $total > 3 => 'grid-cols-2 sm:grid-cols-3',
        default => 'grid-cols-1',
    };
@endphp

@if ($total > 1)
    <div class="cb-image-set my-6 grid gap-3 {{ $gridCls }} {{ $class ?? '' }}" data-lightbox-group>
        @foreach ($set as $index => $image)
            <figure class="cb-image-set__item overflow-hidden rounded-2xl">
                <button
                    type="button"
                    data-lightbox-item
                    aria-label="Open image {{ $index + 1 }} of {{ $total }}"
                    class="block w-full cursor-zoom-in"
                >
                    <img
                        src="{{ $image['src'] }}"
                        alt="{{ $image['alt'] ?? '' }}"
                        loading="{{ $index < 2 ? 'eager' : 'lazy' }}"
                        decoding="async"
                        class="aspect-[4/3] h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                    />
                </button>
                @if (! empty($image['caption']))
                    <figcaption class="cb-image-set__caption px-1 pt-2 text-sm text-[#A1A1AA]">
                        {{ $image['caption'] }}
                    </figcaption>
                @endif
            </figure>
        @endforeach
    </div>
@elseif ($total === 1)
    <figure class="{{ $class ?? '' }}">
        <img
            src="{{ $set[0]['src'] }}"
            alt="{{ $set[0]['alt'] ?? '' }}"
            decoding="async"
            @if (! empty($width)) width="{{ $width }}" @endif
            @if (! empty($height)) height="{{ $height }}" @endif
        />
        @if (! empty($set[0]['caption']))
            <figcaption>{{ $set[0]['caption'] }}</figcaption>
        @endif
    </figure>
@elseif ($src ?? false)
    <figure @if ($class) class="{{ $class }}" @endif>
        <img
            src="{{ $src }}"
            alt="{{ $alt ?? '' }}"
            decoding="async"
            @if ($width) width="{{ $width }}" @endif
            @if ($height) height="{{ $height }}" @endif
        />
        @if ($caption)
            <figcaption>{{ $caption }}</figcaption>
        @endif
    </figure>
@endif
