<x-layouts::admin title="Create Glass Sub-Category">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Create Sub-Category</h1>
                <p class="text-gray-500 dark:text-gray-400">Add a new detailed glass sub-category.</p>
            </div>
            <a
                href="{{ route('admin.glass-sub-categories.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Sub-Categories
            </a>
        </div>

        <form
            action="{{ route('admin.glass-sub-categories.store') }}"
            method="POST"
            class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
        >
            @csrf

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Glass Type</label>
                <select
                    name="glass_type_id"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                >
                    <option value="">Select a glass type...</option>
                    @foreach ($glassTypes as $glassType)
                        <option
                            value="{{ $glassType->id }}"
                            {{ request('glass_type_id') == $glassType->id ? 'selected' : '' }}
                        >
                            {{ $glassType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sub-Category Name</label>
                <input
                    type="text"
                    name="name"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                    placeholder="e.g., Left Hand Side - Rear Door Glass"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">URL Slug</label>
                <input
                    type="text"
                    name="slug"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                    placeholder="e.g., left-hand-side-rear-door-glass"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                <input
                    type="number"
                    name="sort_order"
                    value="0"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                />
            </div>

            <label class="flex cursor-pointer items-center gap-2">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    checked
                    class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20 dark:focus:ring-white"
                />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
            </label>

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6 dark:border-white/5">
                <a
                    href="{{ route('admin.glass-sub-categories.index') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >Cancel</a>
                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Create Sub-Category
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>
