<x-layouts::admin title="Edit Category">
    <div class="mx-auto max-w-xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Edit Category</h1>
                <p class="text-gray-500 dark:text-gray-400">Modify category details.</p>
            </div>
            <a
                href="{{ route('admin.categories.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                Back to Categories
            </a>
        </div>

        <form
            method="POST"
            action="{{ route('admin.categories.update', $category) }}"
            class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
        >
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-white"
                />
            </div>

            <div class="flex items-center justify-between border-t border-gray-100 pt-6 dark:border-white/5">
                <form
                    action="{{ route('admin.categories.destroy', $category) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure?');"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                    >
                        Delete Category
                    </button>
                </form>

                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >Cancel</a>
                    <button
                        type="submit"
                        class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>
