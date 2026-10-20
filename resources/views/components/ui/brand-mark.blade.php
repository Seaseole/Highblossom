{{--
    Brand mark, resolved from company settings.

    Same precedence the error layout already uses: the uploaded logo when an
    admin has set one, otherwise the vector mark in the public root. No artwork
    is generated or inlined here, so the one mark stays the source of truth in
    both themes.
--}}
@props([
    'size' => 'md',
    'class' => '',
])

@php
    $stored = $settings->get('business_logo', '');

    $src = $stored ? media_url($stored) : asset('favicon.svg');

    $sizes = [
        'sm' => 'size-7',
        'md' => 'size-9',
        'lg' => 'size-12',
        'xl' => 'size-14',
    ];
@endphp

<img
    src="{{ $src }}"
    alt=""
    role="presentation"
    width="56"
    height="56"
    class="shrink-0 object-contain {{ $sizes[$size] ?? $sizes['md'] }} {{ $class }}"
    {{ $attributes->except(['size', 'class']) }}
/>
