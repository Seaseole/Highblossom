{{--
    The measured row: the unit of the scoring bench.

    Three columns — a 3px marking channel that is always there, the measure that
    names the value, and the control. The channel is a real grid column rather
    than a pseudo-element so that drawing a score cannot move a pixel of anything,
    which on a form this long would mean a layout shift on every keypress.

    `data-error` is painted by the server, and it does two jobs: it draws the
    score, and it tells the marks engine that the value in this box is rejected
    input rather than stored input, so the row must not be treated as clean.
--}}
@props([
    'label' => null,
    'key' => null,
    'hint' => null,
    'error' => null,
    'for' => null,
    'state' => null,
    'stack' => false,
])

@php
    $refused = filled($error) && $errors->has($error);
@endphp

<div
    {{ $attributes->merge(['class' => 'hb-field'.($stack ? ' hb-field--stack' : '')]) }}
    @if ($refused) data-error @endif
>
    <span class="hb-field__score" aria-hidden="true"></span>

    <div class="hb-field__meta">
        @if (filled($label))
            {{-- A row is labelled by a real `<label>` when there is a control to
                 point at. A row whose text is only the name of a state ("Require
                 verified email addresses") gets a paragraph, because a label with
                 no control is a thing a screen reader announces and nothing more. --}}
            @if (filled($for))
                <label class="hb-field__label" for="{{ $for }}">{{ $label }}</label>
            @else
                <p class="hb-field__label">{{ $label }}</p>
            @endif
        @endif

        @if (filled($key))
            <span class="hb-field__key">{{ $key }}</span>
        @endif

        {{ $meta ?? '' }}
    </div>

    <div class="hb-field__ctl">
        {{-- The explanation sits under the thing it explains, not across the measure
             from it. A three-line sentence in a 14rem column wraps into a paragraph
             nobody connects to the box beside it. --}}
        {{ $slot }}

        @if (filled($hint))
            <p class="hb-field__hint">{{ $hint }}</p>
        @endif

        @if (filled($error))
            @error($error)
                <p class="hb-error" id="{{ $error }}-error">{{ $message }}</p>
            @enderror
        @endif

        <p class="hb-field__state" data-sheet-state>{{ $state }}</p>
    </div>
</div>
