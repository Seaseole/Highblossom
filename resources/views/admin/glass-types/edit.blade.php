<x-layouts::admin title="Edit Glass Type">
    @php
        $field = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]';
    @endphp

    <div
        class="mx-auto max-w-5xl space-y-8 py-6 sm:py-10"
        x-data="subCategories('{{ url('admin/glass-sub-categories') }}', {{ $glassType->id }}, {{ $glassSubCategories->count() }}, {{ $nextSortOrder }})"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Glass Type</h1>
                <p class="text-gray-500 dark:text-gray-400">Modify the architectural glass variant details.</p>
            </div>
            <a
                href="{{ route('admin.glass-types.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Glass Types
            </a>
        </div>

        <form
            method="POST"
            action="{{ route('admin.glass-types.update', $glassType) }}"
            class="grid grid-cols-1 gap-8 lg:grid-cols-3"
        >
            @csrf
            @method('PUT')

            <!-- Details Card -->
            <div class="space-y-8 lg:col-span-2">
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $glassType->name) }}"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                        />
                    </div>
                </div>

                <!-- Sub-Categories Management -->
                <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div class="flex items-center justify-between border-b border-gray-100 px-8 py-6 dark:border-white/10">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sub-Categories</h3>
                        <button
                            type="button"
                            x-on:click="openCreate()"
                            class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                        >
                            + Add Sub-Category
                        </button>
                    </div>
                    <div id="sub-category-list" class="space-y-3 p-8">
                        @foreach ($glassSubCategories as $subCategory)
                            <div
                                data-sub-category
                                data-id="{{ $subCategory->id }}"
                                data-name="{{ $subCategory->name }}"
                                data-slug="{{ $subCategory->slug }}"
                                data-sort-order="{{ $subCategory->sort_order }}"
                                data-active="{{ $subCategory->is_active ? '1' : '0' }}"
                                class="flex items-center justify-between gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5"
                            >
                                <div class="min-w-0">
                                    <span class="block truncate text-sm font-medium text-gray-900 dark:text-white" data-row-name>{{ $subCategory->name }}</span>
                                    <span class="mt-0.5 block font-mono text-xs text-gray-400 dark:text-gray-500" data-row-slug>{{ $subCategory->slug }}</span>
                                </div>
                                <div class="flex shrink-0 items-center gap-4">
                                    <span
                                        data-row-inactive
                                        @class([
                                            'rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300' => ! $subCategory->is_active,
                                            'hidden' => $subCategory->is_active,
                                        ])
                                    >Inactive</span>
                                    <button
                                        type="button"
                                        x-on:click="openEdit($el.closest('[data-sub-category]'))"
                                        class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                    >Edit</button>
                                    <button
                                        type="button"
                                        x-on:click="remove($el.closest('[data-sub-category]'))"
                                        class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                    >Delete</button>
                                </div>
                            </div>
                        @endforeach

                        <p
                            x-show="count === 0"
                            x-cloak
                            class="py-4 text-center text-sm text-gray-500 dark:text-gray-400"
                        >
                            No sub-categories added.
                        </p>

                        {{-- Inert clone source: kept out of the glass-type form's payload. --}}
                        <template id="sub-category-row-template">
                            <div
                                data-sub-category
                                class="flex items-center justify-between gap-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5"
                            >
                                <div class="min-w-0">
                                    <span class="block truncate text-sm font-medium text-gray-900 dark:text-white" data-row-name></span>
                                    <span class="mt-0.5 block font-mono text-xs text-gray-400 dark:text-gray-500" data-row-slug></span>
                                </div>
                                <div class="flex shrink-0 items-center gap-4">
                                    <span class="hidden rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300" data-row-inactive>Inactive</span>
                                    <button
                                        type="button"
                                        x-on:click="openEdit($el.closest('[data-sub-category]'))"
                                        class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                    >Edit</button>
                                    <button
                                        type="button"
                                        x-on:click="remove($el.closest('[data-sub-category]'))"
                                        class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                    >Delete</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Config Card -->
            <div class="space-y-6">
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div>
                        <x-admin.sort-order-field
                            module="glass-types"
                            :value="$glassType->sort_order"
                            :current-id="$glassType->id"
                        />
                    </div>
                    <x-ui.checkbox name="is_active" value="1" :checked="old('is_active', $glassType->is_active)" label="Visible" />

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update Glass Type
                    </button>
                </div>
            </div>
        </form>

        {{-- Shared modal for both creating and editing a sub-category. Deliberately outside
             the glass-type form so its fields can never join that payload. --}}
        <div
            x-show="isOpen"
            x-cloak
            x-on:keydown.escape.window="close()"
            x-on:keydown.tab.window="trap($event)"
            class="fixed inset-0 z-[100]"
            role="dialog"
            aria-modal="true"
        >
            <div class="fixed inset-0 bg-[#0A0A0F]/70 backdrop-blur-sm" x-on:click="close()" aria-hidden="true"></div>

            <div class="fixed inset-0 z-[101] overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div
                        x-ref="dialog"
                        x-show="isOpen"
                        x-transition:enter="transition duration-300 ease-out"
                        x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                        x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                        class="w-full max-w-md rounded-3xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-white/10 dark:bg-[#16161D]"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="title"></h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Sub-category of <span class="font-medium text-gray-700 dark:text-gray-300">{{ $glassType->name }}</span>. The URL slug is generated from the name unless you edit it.
                        </p>

                        <label class="mt-5 mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input
                            x-ref="input"
                            type="text"
                            x-model="name"
                            maxlength="255"
                            x-on:input="autoSlug()"
                            class="{{ $field }}"
                        />

                        <label class="mt-4 mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">URL Slug</label>
                        <input
                            type="text"
                            x-model="slug"
                            maxlength="255"
                            x-on:input="slugEdited = true"
                            class="{{ $field }} font-mono"
                        />

                        <div class="mt-4 grid grid-cols-2 items-end gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                                <input
                                    type="number"
                                    min="1"
                                    step="1"
                                    x-model.number="sortOrder"
                                    class="{{ $field }}"
                                />
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Next free number for this glass type is
                                    <span x-text="nextSortOrder"></span>. Leave blank to take it automatically.
                                </p>
                            </div>
                            <label class="flex min-h-[42px] cursor-pointer items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <input type="checkbox" x-model="isActive" class="size-4 rounded border-gray-300 dark:border-white/10" />
                                Active
                            </label>
                        </div>

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
                                <span x-show="! saving" x-text="isCreate ? 'Add' : 'Save'"></span>
                                <span x-show="saving" x-cloak>Saving…</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
