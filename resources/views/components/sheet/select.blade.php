{{-- A choice, and the only control on this page that can be wrong without being
     typed: an option that no longer exists must still be visible, or the row
     would silently change the value the person is looking at. --}}
@props([
    'name',
    'label' => null,
    'key' => null,
    'hint' => null,
    'error' => null,
    'value' => null,
    'id' => null,
    'options' => null,
    'placeholder' => null,
])

@php
    $errorKey = filled($error) ? $error : $name;
    $fieldId = filled($id) ? $id : str_replace(['[', ']', '.'], '_', $name);
    $refused = $errors->has($errorKey);
@endphp

<x-sheet.field :label="$label" :key="$key" :hint="$hint" :error="$errorKey" :for="$fieldId">
    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        @if ($refused) aria-invalid="true" aria-describedby="{{ $errorKey }}-error" @endif
        {{ $attributes->merge(['class' => 'hb-select']) }}
    >
        @if (filled($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif

        @if (filled($options))
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
</x-sheet.field>
