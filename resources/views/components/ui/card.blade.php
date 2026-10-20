{{--
    Surface container. Elevation in this language comes from a hairline border
    plus one soft shadow; dark mode flattens the shadow via the token, so the
    surface step is what separates the card from the page.
--}}
@props([
    'tone' => 'surface',
    'padding' => 'md',
    'as' => 'div',
])

@php
    $tones = [
        'surface' => 'bg-ui-surface border-ui-line shadow-ui-card',
        'raised' => 'bg-ui-surface-raised border-ui-line shadow-ui-xs',
        'plain' => 'border-transparent bg-transparent',
    ];

    $paddings = [
        'none' => '',
        'sm' => 'p-5',
        'md' => 'p-6 sm:p-8',
        'lg' => 'p-8 sm:p-10',
    ];
@endphp

<{{ $as }} {{ $attributes->class('rounded-ui-lg border '.($tones[$tone] ?? $tones['surface']).' '.($paddings[$padding] ?? $paddings['md'])) }}>
    {{ $slot }}
</{{ $as }}>
