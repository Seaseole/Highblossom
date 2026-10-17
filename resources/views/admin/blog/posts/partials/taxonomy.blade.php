@php
    /** @var \App\Models\Post|null $post */
    $selectedCategories = collect(old('categories', $post?->categories->pluck('id')->all() ?? []));
    $selectedTags = collect(old('tags', $post?->tags->pluck('id')->all() ?? []));

    $chip = 'inline-flex cursor-pointer items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-700 transition-all hover:border-gray-900 peer-focus-visible:ring-2 peer-focus-visible:ring-gray-900/30 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white';
    $chipSelected = 'peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white dark:peer-checked:border-white dark:peer-checked:bg-white dark:peer-checked:text-gray-900';
    $field = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]';
@endphp

<div
    x-data="taxonomy({ category: '{{ route('admin.categories.store') }}', tag: '{{ route('admin.tags.store') }}' })"
    class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 md:p-8 dark:border-white/10 dark:bg-[#0A0A0F]"
>
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Categories &amp; Tags</h2>

    {{-- Categories --}}
    <div class="space-y-3">
        <div class="flex items-baseline justify-between gap-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categories</label>
            <span class="font-mono text-xs text-gray-400 dark:text-gray-500">
                <span x-text="categoryCount"></span> selected<span x-show="status === 'published' &amp;&amp; ! categoryCount" class="text-red-500"> — required to publish</span>
            </span>
        </div>

        <div id="category-chip-list" class="flex flex-wrap gap-2">
            @foreach ($categories as $category)
                <label>
                    <input
                        type="checkbox"
                        name="categories[]"
                        value="{{ $category->id }}"
                        @checked($selectedCategories->contains($category->id))
                        class="peer sr-only"
                    />
                    <span class="{{ $chip }} {{ $chipSelected }}">{{ $category->name }}</span>
                </label>
            @endforeach
        </div>

        @if ($categories->isEmpty())
            <p id="category-chip-empty" class="text-sm text-gray-500 dark:text-gray-400">
                No categories yet — add one below to organise your posts.
            </p>
        @endif

        @error('categories')
            <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror

        @can('create blog')
            <button
                type="button"
                x-on:click="open('category')"
                class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900 underline decoration-gray-300 underline-offset-4 transition-colors hover:decoration-gray-900 dark:text-white dark:decoration-white/20"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add category
            </button>
        @endcan

        <template id="category-chip-template">
            <label>
                <input type="checkbox" class="peer sr-only" />
                <span class="{{ $chip }} {{ $chipSelected }}"></span>
            </label>
        </template>
    </div>

    {{-- Tags --}}
    <div class="space-y-3 border-t border-gray-100 pt-6 dark:border-white/10">
        <div class="flex items-baseline justify-between gap-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags</label>
            <span class="font-mono text-xs text-gray-400 dark:text-gray-500">
                <span x-text="tagCount"></span> selected
            </span>
        </div>

        <div id="tag-chip-list" class="flex flex-wrap gap-2">
            @foreach ($tags as $tag)
                <label>
                    <input
                        type="checkbox"
                        name="tags[]"
                        value="{{ $tag->id }}"
                        @checked($selectedTags->contains($tag->id))
                        class="peer sr-only"
                    />
                    <span class="{{ $chip }} {{ $chipSelected }}">#{{ $tag->name }}</span>
                </label>
            @endforeach
        </div>

        @if ($tags->isEmpty())
            <p id="tag-chip-empty" class="text-sm text-gray-500 dark:text-gray-400">
                No tags yet — add one below to label topics across posts.
            </p>
        @endif

        @error('tags')
            <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror

        @can('create blog')
            <button
                type="button"
                x-on:click="open('tag')"
                class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900 underline decoration-gray-300 underline-offset-4 transition-colors hover:decoration-gray-900 dark:text-white dark:decoration-white/20"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add tag
            </button>
        @endcan

        <template id="tag-chip-template">
            <label>
                <input type="checkbox" class="peer sr-only" />
                <span class="{{ $chip }} {{ $chipSelected }}"></span>
            </label>
        </template>
    </div>

    {{-- Quick-add modal --}}
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
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">The URL slug is generated from the name.</p>

                    <label class="mt-5 mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input
                        x-ref="input"
                        type="text"
                        x-model="name"
                        maxlength="255"
                        x-on:keydown.enter.prevent="save()"
                        class="{{ $field }}"
                    />

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
                            <span x-show="! saving">Add</span>
                            <span x-show="saving" x-cloak>Saving…</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
