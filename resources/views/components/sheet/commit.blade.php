{{--
    The commit control.

    It is the only button on the page whose job is the page's whole idea, so it
    performs it: the field floods to ink, the label inverts, and the arrow moves
    to where it is going. A colour swap alone would make the most important
    action on a sheet of a hundred and five fields the quietest thing on it.
--}}
@props([
    'label',
])

<button type="submit" class="hb-btn hb-btn--commit hb-btn--wide" {{ $attributes }}>
    <span>{{ $label }}</span>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.75"
        class="hb-btn__arrow"
        aria-hidden="true"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
    </svg>
</button>
