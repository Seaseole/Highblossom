@props([
    'href' => null,
    'prefetch' => false,
    'spa' => true,
])

@php
    // Livewire already refuses cross-origin, non-http and target/downloaded links
    // natively, so the only thing that has to be filtered here is a hand-typed href
    // that was never meant to be swapped in place: a bare fragment, or a tel:/mailto:.
    $scheme = $href !== null && $href !== '' ? parse_url($href, PHP_URL_SCHEME) : null;
    $host = $href !== null && $href !== '' ? parse_url($href, PHP_URL_HOST) : null;

    $navigable = $spa
        && filled($href)
        && !str_starts_with($href, '#')
        && ($scheme === null || in_array(strtolower($scheme), ['http', 'https'], true))
        && ($host === null || $host === parse_url((string) config('app.url'), PHP_URL_HOST))
        && !$attributes->has('target')
        && !$attributes->has('download');
@endphp

<a
    href="{{ $href }}"
    @if ($navigable) wire:navigate{{ $prefetch ? '.hover' : '' }} @endif
    {{ $attributes }}
>{{ $slot }}</a>
