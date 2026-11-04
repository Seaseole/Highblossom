{{--
    A group: one face on the sheet, with a head that measures it.

    The two numbers in the head are not typed by anybody. The field count is read
    out of the page by the marks engine, and so is the count of things still to
    set — which is why a group can be three screens up and still tell you it holds
    the change you just made.
--}}
@props([
    'title',
    'note' => null,
    'id' => null,
    'foot' => null,
])

<section
    {{ $attributes->merge(['class' => 'hb-block']) }}
    @if (filled($id)) id="{{ $id }}" @endif
>
    <div class="hb-block__head">
        <h2 class="hb-block__title">{{ $title }}</h2>

        <p class="hb-block__meta">
            <span class="hb-num" data-sheet-count></span>
            <span class="hb-num" data-sheet-marks></span>
            {{ $head ?? '' }}
        </p>

        @if (filled($note))
            <p class="hb-block__note">{{ $note }}</p>
        @endif
    </div>

    <div class="hb-block__body">
        {{ $slot }}
    </div>

    @if (filled($foot))
        <p class="hb-block__foot">{{ $foot }}</p>
    @endif
</section>
