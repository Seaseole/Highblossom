{{--
    Text link. Rounded so the focus ring reads as an intended shape rather than a
    box clipped around inline text.
--}}
@props([
    'href' => null,
    'size' => 'sm',
    'tone' => 'accent',
    'underline' => false,
    'external' => false,
])

@php
    $sizes = [
        'xs' => 'text-ui-xs',
        'sm' => 'text-ui-sm',
        'md' => 'text-ui-md',
    ];

    $tones = [
        'accent' => 'text-ui-link hover:text-ui-link-hover',
        'muted' => 'text-ui-muted hover:text-ui-text',
        'strong' => 'text-ui-text hover:text-ui-link',
    ];

    $classes = 'inline-flex items-center gap-1.5 rounded-ui-xs font-medium transition-colors '
        .($sizes[$size] ?? $sizes['sm']).' '
        .($tones[$tone] ?? $tones['accent']).' '
        .($underline ? 'underline decoration-ui-line underline-offset-4 hover:decoration-current' : '');
@endphp

<a
    @if ($href) href="{{ $href }}" @endif
    @if ($external) target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class($classes) }}
>{{ $slot }}</a>
