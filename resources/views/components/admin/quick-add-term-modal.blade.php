@props([
    'kind' => 'category',
    'title' => 'New term',
    'description' => false,
])

@php
    $isCategory = $kind === 'category';
    $dialogRef = $isCategory ? 'categoryDialog' : 'tagDialog';
    $inputRef = $isCategory ? 'categoryInput' : 'tagInput';
    $nameId = 'quick-add-'.$kind.'-name';
    $descriptionId = 'quick-add-'.$kind.'-description';
    $field = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]';
@endphp

{{-- Shared accessible shell for the two quick-add dialogs. Both read the single
     taxonomy() Alpine state on the enclosing element, so opening either one
     keeps every unsaved post field exactly where the author left it. --}}
<div
    x-show="kind === '{{ $kind }}'"
    x-cloak
    x-on:keydown.escape.window="close()"
    x-on:keydown.tab.window="trap($event)"
    class="fixed inset-0 z-[100]"
    role="dialog"
    aria-modal="true"
    aria-labelledby="quick-add-{{ $kind }}-title"
>
    <div class="fixed inset-0 bg-[#0A0A0F]/70 backdrop-blur-sm" x-on:click="close()" aria-hidden="true"></div>

    <div class="fixed inset-0 z-[101] overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-ref="{{ $dialogRef }}"
                x-show="kind === '{{ $kind }}'"
                x-transition:enter="transition duration-300 ease-out"
                x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                class="w-full max-w-md rounded-3xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-white/10 dark:bg-[#16161D]"
            >
                <h3 id="quick-add-{{ $kind }}-title" class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">The URL slug is generated from the name.</p>

                <label for="{{ $nameId }}" class="mt-5 mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input
                    id="{{ $nameId }}"
                    x-ref="{{ $inputRef }}"
                    type="text"
                    x-model="name"
                    maxlength="255"
                    x-on:keydown.enter.prevent="save()"
                    class="{{ $field }}"
                />

                @if ($description)
                    <label for="{{ $descriptionId }}" class="mt-4 mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Description <span class="font-normal text-gray-400 dark:text-gray-500">(optional)</span>
                    </label>
                    <textarea
                        id="{{ $descriptionId }}"
                        x-model="description"
                        rows="3"
                        maxlength="1000"
                        class="{{ $field }}"
                    ></textarea>
                @endif

                <div x-show="errors.length" x-cloak class="mt-3 space-y-1" role="alert">
                    <template x-for="message in errors" :key="message">
                        <p class="text-sm font-medium text-red-600 dark:text-red-400" x-text="message"></p>
                    </template>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        x-on:click="close()"
                        class="rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 dark:border-white/10 dark:text-gray-300 dark:hover:border-white"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        x-on:click="save()"
                        :disabled="saving || ! name.trim()"
                        class="rounded-full bg-gray-900 px-5 py-2 text-sm font-medium text-white transition-all hover:bg-gray-800 disabled:opacity-50 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        <span x-show="! saving">{{ $isCategory ? 'Add category' : 'Add tag' }}</span>
                        <span x-show="saving" x-cloak>Saving…</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
