{{--
    A text-family control and its row, in one tag.

    `name` is the whole identity of the thing: it derives the `id`, the
    validation key, the `aria-invalid` state and the `aria-describedby` link to
    the sentence under it. The key printed beside the label is the same string,
    because it is the name the value has in the code and the name a staff member
    uses when something is wrong.
--}}
@props([
    'name',
    'label' => null,
    'key' => null,
    'hint' => null,
    'error' => null,
    'type' => 'text',
    'value' => null,
    'id' => null,
    'mono' => false,
    'measure' => null,
])

@php
    $errorKey = filled($error) ? $error : $name;
    $fieldId = filled($id) ? $id : str_replace(['[', ']', '.'], '_', $name);
    $refused = $errors->has($errorKey);
@endphp

<x-sheet.field
    :label="$label"
    :key="$key"
    :hint="$hint"
    :error="$errorKey"
    :for="$fieldId"
>
    <input
        type="{{ $type }}"
        id="{{ $fieldId }}"
        name="{{ $name }}"
        @if (! in_array($type, ['file', 'password'], true))
            value="{{ $value }}"
        @endif
        @if ($refused) aria-invalid="true" aria-describedby="{{ $errorKey }}-error" @endif
        {{ $attributes->merge([
            'class' => $type === 'file'
                ? 'hb-file'
                : 'hb-input'
                    .($mono ? ' hb-input--mono' : '')
                    .($measure === 'num' ? ' hb-input--num' : ''),
        ]) }}
    />
</x-sheet.field>
