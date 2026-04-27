@props([
    'name' => null,
    'id' => null,
    'value' => 1,
    'checked' => false,
    'label' => null,
    'wireModel' => null,
    'xModel' => null,
])

@php
$id = $id ?? ($name ? str_replace(['[', ']'], ['_', ''], $name) : uniqid('cbx_'));
$hasXModel = $xModel || $attributes->has('x-model');
$xModelValue = $xModel ?? $attributes->get('x-model');
$inputAttributes = $attributes->except(['class', 'wire:model', 'x-model', 'xModel']);
$hasSlot = !empty(trim($slot ?? ''));
@endphp

<label class="ui-checkbox-wrapper cursor-pointer inline-flex items-center gap-3 group">
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @if($checked) checked @endif
        @if($wireModel) wire:model="{{ $wireModel }}" @endif
        @if($hasXModel) x-model="{{ $xModelValue }}" @endif
        {{ $inputAttributes }}
        class="ui-checkbox-input"
    >
    <span class="ui-checkbox-check">
        <svg width="18px" height="18px" viewBox="0 0 18 18">
            <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path>
            <polyline points="1 9 7 14 15 4"></polyline>
        </svg>
    </span>
    @if($hasSlot)
        <span class="ui-checkbox-label">{{ $slot }}</span>
    @elseif($label)
        <span class="ui-checkbox-label">{{ $label }}</span>
    @endif
</label>
