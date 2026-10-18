@props([
    'module',
    'value' => null,
    'currentId' => null,
    'scope' => [],
    'scopeInputs' => [],
    'auto' => false,
    'label' => 'Sort Order',
])

@php
    $modelClass = App\Support\SortOrderRegistry::model($module);
    $column = (new $modelClass)->sortOrderColumn();
    $previous = old($column);
    $next = $modelClass::nextSortOrder($scope);
    $display = $auto ? '' : (filled($previous) ? $previous : ($value ?? $next));
    $checkUrl = route('admin.sort-order.availability', array_filter(
        ['module' => $module, 'scope' => $scope],
        fn ($value) => $value !== [] && $value !== null
    ));
@endphp

{{-- One ordering field for every admin module: prefilled with the next free number,
     checked against the server on change, and blocked again by the FormRequest on
     submit. Leaving it blank is never a mistake - the model assigns the number.
     scopeInputs names the form fields that hold this record's numbering scope, so a
     parent chosen in the same form is part of the check. --}}
<div data-sort-order-field>
    <label
        for="{{ $column }}-input"
        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
    >{{ $label }}</label>

    <input
        type="number"
        id="{{ $column }}-input"
        name="{{ $column }}"
        value="{{ $display }}"
        min="1"
        step="1"
        inputmode="numeric"
        data-sort-check-url="{{ $checkUrl }}"
        data-sort-current="{{ $currentId }}"
        data-sort-scope-inputs="{{ implode(',', array_map(
            fn (string $field, string $column) => "{$column}:{$field}",
            $scopeInputs,
            array_keys($scopeInputs),
        )) }}"
        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
    />

    @if ($auto)
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Left blank, the next free number for the parent selected above is assigned
            automatically. Deactivating a record never frees its number.
        </p>
    @else
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Next free number is {{ $next }}. Leave the field blank to take it automatically;
            deactivating a record never frees its number.
        </p>
    @endif

    <p
        data-sort-error
        class="mt-2 text-sm font-medium text-red-600 dark:text-red-400"
        @if(! $errors->has($column)) hidden @endif
    >{{ $errors->first($column) }}</p>
</div>
