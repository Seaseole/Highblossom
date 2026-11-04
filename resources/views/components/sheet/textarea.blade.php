{{-- A multi-line control. The value goes between the tags, which is the only
     reason this is not the input component. --}}
@props([
    'name',
    'label' => null,
    'key' => null,
    'hint' => null,
    'error' => null,
    'value' => null,
    'id' => null,
    'rows' => 3,
])

@php
    $errorKey = filled($error) ? $error : $name;
    $fieldId = filled($id) ? $id : str_replace(['[', ']', '.'], '_', $name);
    $refused = $errors->has($errorKey);
@endphp

<x-sheet.field :label="$label" :key="$key" :hint="$hint" :error="$errorKey" :for="$fieldId">
    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($refused) aria-invalid="true" aria-describedby="{{ $errorKey }}-error" @endif
        {{ $attributes->merge(['class' => 'hb-textarea']) }}
    >{{ $value }}</textarea>
</x-sheet.field>
