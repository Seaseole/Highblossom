@php
    // $list: JS expression that evaluates to the array of nested blocks, e.g. block.attributes.columns[ci].
    $inputCls = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white';
    $iconBtnCls = 'rounded-lg p-1 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 disabled:opacity-30 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white';
@endphp

<div class="space-y-2 rounded-xl border border-dashed border-gray-300 p-3 dark:border-white/10">
    <template x-if="{{ $list }}.length === 0">
        <p class="text-xs italic text-gray-400 dark:text-gray-500">Empty. Add a block below.</p>
    </template>

    <template x-for="(nb, ni) in {{ $list }}" :key="nb.id">
        <div
            class="rounded-xl border border-gray-200 bg-gray-50/50 p-3 dark:border-white/10 dark:bg-white/5"
            :class="isInvalid(nb) ? 'border-red-300 dark:border-red-500/40' : ''"
        >
            <div class="mb-2 flex items-center justify-between gap-2">
                <div class="flex min-w-0 items-center gap-2">
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-gray-900 dark:bg-white/10 dark:text-gray-100" x-text="label(nb.type)"></span>
                    <span class="text-[11px] text-gray-400" x-text="'#' + (ni + 1)"></span>
                    <template x-if="isInvalid(nb)">
                        <span class="truncate rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-medium text-red-600 dark:bg-red-900/20 dark:text-red-300" x-text="invalidHint(nb)"></span>
                    </template>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" @click="moveNestedBlock({{ $list }}, ni, -1)" :disabled="ni === 0" class="{{ $iconBtnCls }}" title="Move up" aria-label="Move nested block up">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                    </button>
                    <button type="button" @click="moveNestedBlock({{ $list }}, ni, 1)" :disabled="ni === {{ $list }}.length - 1" class="{{ $iconBtnCls }}" title="Move down" aria-label="Move nested block down">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <button type="button" @click="removeNestedBlock({{ $list }}, ni)" class="{{ $iconBtnCls }} hover:bg-red-50 hover:text-red-500" title="Delete" aria-label="Delete nested block">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>

            @include('livewire.block-builder.fields', ['b' => 'nb', 'nesting' => false])
        </div>
    </template>

    @include('livewire.block-builder.add-menu', [
        'types' => 'nestedEditableTypes()',
        'onAdd' => $list.'.push(makeNestedBlock(type))',
        'buttonLabel' => 'Add Block',
        'buttonClass' => 'flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10',
    ])
</div>
