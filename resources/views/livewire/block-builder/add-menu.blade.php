@php
    // $types: JS expression returning the list of type keys.
    // $onAdd: JS statement executed with `type` in scope when an entry is picked.
    $types ??= 'editableTypes()';
    $onAdd ??= 'addBlock(type)';
    $buttonLabel ??= 'Add Block';
    $buttonClass ??= 'flex w-full items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 font-medium text-gray-900 transition-colors hover:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:text-gray-100 dark:hover:bg-white/10';
@endphp

<div class="relative" x-data="{ open: false }">
    <button type="button" @click="open = ! open" class="{{ $buttonClass }}">
        {{ $buttonLabel }}
    </button>
    <div
        x-show="open"
        @click.outside="open = false"
        x-cloak
        class="absolute z-20 mt-2 w-full overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl dark:border-white/10 dark:bg-[#16161D]"
    >
        <div class="max-h-64 overflow-y-auto p-2">
            <template x-for="type in {{ $types }}" :key="type">
                <button
                    type="button"
                    @click="{{ $onAdd }}; open = false;"
                    class="w-full rounded-lg px-3 py-2 text-left text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5"
                    x-text="label(type)"
                ></button>
            </template>
            <div x-show="({{ $types }} || []).length === 0" class="p-4 text-center text-xs text-gray-500">
                No blocks registered in registry.
            </div>
        </div>
    </div>
</div>
