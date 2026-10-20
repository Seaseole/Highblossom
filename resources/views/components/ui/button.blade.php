{{--
    Button. `variant` and `size` are the only two axes; something that looks like a
    button but navigates passes `href` and renders an <a>.

    Look and feel live in `.ui-btn` (resources/css/auth.css) so the Alpine-driven
    screens can reuse the same styling on a plain element.

    Pass a `loading` label and the button wires itself to the vanilla pending
    mechanism in resources/js/submit-pending.js: it opts into
    data-submit-pending-button, marks its own idle content, and swaps to the
    spinner + label while the browser navigates. That script — not wire:loading —
    is what the auth forms use, because they are plain POST forms with no Livewire
    component behind them.
--}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'block' => false,
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'loading' => null,
    'icon' => null,
])

@php
    $variants = ['primary', 'secondary', 'ghost'];
    $variant = in_array($variant, $variants, true) ? $variant : 'primary';

    $classes = 'ui-btn ui-btn-'.$variant
       .(in_array($size, ['sm', 'lg'], true) ? ' ui-btn-'.$size : '')
       .($block ? ' ui-btn-block' : '');

    $spinner = '<svg class="size-[1.05em] shrink-0 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">'
        .'<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>'
        .'<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>'
        .'</svg>';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)
            <x-ui.icon :name="$icon" size="sm" class="shrink-0" />
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->class($classes) }}
        @if ($disabled) disabled @endif
        @if ($loading) data-submit-pending-button @endif
    >
        <span data-idle-state class="contents">
            @if ($icon)
                <x-ui.icon :name="$icon" size="sm" class="shrink-0" />
            @endif
            {{ $slot }}
        </span>

        @if ($loading)
            <span data-pending-state class="hidden">
                <span class="inline-flex items-center gap-2">
                    {!! $spinner !!}
                    {{ $loading }}
                </span>
            </span>
        @endif
    </button>
@endif
