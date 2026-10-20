{{--
    Label + control + hint + inline errors, in the one arrangement every field in
    this language uses.

    Pass `field` with the validation key and the component owns the whole error
    presentation: page-level messages stay in x-ui.alert-banner, field messages
    land here, so a sentence is never shown twice.
--}}
@props([
    'label' => null,
    'for' => null,
    'field' => null,
    'required' => false,
    'hint' => null,
])

@php
    $actionSlot = $action ?? null;
    $hasAction = $actionSlot && ! $actionSlot->isEmpty();
@endphp

<div {{ $attributes->class('space-y-2') }}>
    @if ($label || $hasAction)
        <div class="flex items-baseline justify-between gap-3">
            @if ($label)
                <x-ui.label :for="$for ?? $field" :required="$required">{{ $label }}</x-ui.label>
            @endif
            @if ($hasAction)
                <div class="shrink-0">{{ $actionSlot }}</div>
            @endif
        </div>
    @endif

    {{ $slot }}

    @if ($hint)
        <p class="text-ui-xs text-ui-muted">{{ $hint }}</p>
    @endif

    @if ($field)
        <x-ui.field-errors :field="$field" />
    @endif
</div>
