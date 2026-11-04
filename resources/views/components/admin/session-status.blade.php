@props(['session', 'isCurrent' => false])

@php
    $active = $session->isActive();

    $label = match (true) {
        $isCurrent => 'This device',
        $active => 'Active',
        default => $session->end_reason?->label() ?? 'Ended',
    };

    /* A state is a shape and a word, never a colour. `current` carries a rule as
       well as a tint because it is the one row a person is standing in. */
    $tone = match (true) {
        $isCurrent => 'hb-tag hb-tag--current',
        $active => 'hb-tag hb-tag--active',
        default => 'hb-tag hb-tag--ended',
    };
@endphp

<span {{ $attributes->merge(['class' => $tone]) }}>
    <span class="hb-tag__dot" aria-hidden="true"></span>{{ $label }}
</span>
