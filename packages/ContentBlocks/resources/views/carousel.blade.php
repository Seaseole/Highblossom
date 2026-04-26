@php
    $slides = $slides ?? [];
    $autoplay = $autoplay ?? false;
    $interval = $interval ?? 5;
    $blockRenderer = $blockRenderer ?? null;
    $uniqueId = 'cb-carousel-' . uniqid();
@endphp

<div class="cb-carousel" x-data="{ activeSlide: 0, autoplay: {{ $autoplay ? 'true' : 'false' }}, interval: {{ $interval * 1000 }}, timer: null }" x-init="if (autoplay) { timer = setInterval(() => { activeSlide = (activeSlide + 1) % {{ count($slides) }}; }, interval); }" @mouseenter="if (timer) clearInterval(timer)" @mouseleave="if (autoplay) { timer = setInterval(() => { activeSlide = (activeSlide + 1) % {{ count($slides) }}; }, interval); }">
    <div class="cb-carousel__track">
        @foreach($slides as $index => $slide)
            <div
                class="cb-carousel__slide"
                :class="{ 'cb-carousel__slide--active': activeSlide === {{ $index }} }"
                x-show="activeSlide === {{ $index }}"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-full"
            >
                @if($blockRenderer)
                    @foreach($slide as $block)
                        {!! $blockRenderer->render($block['type'], $block['attributes']) !!}
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>

    <div class="cb-carousel__nav">
        @foreach($slides as $index => $slide)
            <button
                class="cb-carousel__nav-item"
                :class="{ 'cb-carousel__nav-item--active': activeSlide === {{ $index }} }"
                @click="activeSlide = {{ $index }}; if (timer) { clearInterval(timer); timer = setInterval(() => { activeSlide = (activeSlide + 1) % {{ count($slides) }}; }, interval); }"
                :aria-label="'Slide ' + ({{ $index }} + 1)"
            >
                <span class="sr-only">Slide {{ $index + 1 }}</span>
            </button>
        @endforeach
    </div>
</div>
