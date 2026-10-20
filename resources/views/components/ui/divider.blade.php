{{--
    Rule, optionally interrupted by a label. The labelled form is what splits a
    password sign-in from a passkey one.

    A separator element must not carry text, and the word "or" is purely visual —
    the button label already says what it does — so the labelled rule is hidden
    from the accessibility tree instead of given a role.
--}}
@props(['label' => null])

@if ($label)
    <div {{ $attributes->class('flex items-center gap-4') }} aria-hidden="true">
        <span class="h-px flex-1 bg-ui-line"></span>
        <span class="text-ui-xs font-medium tracking-[0.08em] text-ui-faint uppercase">{{ $label }}</span>
        <span class="h-px flex-1 bg-ui-line"></span>
    </div>
@else
    <hr {{ $attributes->class('h-px border-0 bg-ui-line m-0') }} />
@endif
