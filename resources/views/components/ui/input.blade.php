@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'id' => null,
    'name' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
])

@php
$id = $id ?? 'input-' . md5((string) $name);
$name = $name ?? $id;
$baseClasses = 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm';
$disabledClasses = $disabled ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '';
@endphp

<div {{ $attributes->class('space-y-1') }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    
    <input
        {{ $attributes->except(['label', 'placeholder', 'error'])->merge([
            'type' => $type,
            'id' => $id,
            'name' => $name,
            'value' => $value,
            'placeholder' => $placeholder,
            'required' => $required,
            'disabled' => $disabled,
            'class' => "$baseClasses $disabledClasses"
        ]) }}
    >
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
