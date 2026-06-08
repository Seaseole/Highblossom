<x-layouts::admin title="Edit Glass Type">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Edit Glass Type</h1>
                <p class="text-gray-500 dark:text-gray-400">Modify the architectural glass variant details.</p>
            </div>
            <a href="{{ route('admin.glass-types.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Back to Glass Types
            </a>
        </div>

        <form method="POST" action="{{ route('admin.glass-types.update', $glassType) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <!-- Details Card -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name', $glassType->name) }}" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                    </div>
                </div>

                <!-- Sub-Categories Management -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
                    <div class="px-8 py-6 border-b border-gray-100 dark:border-white/10 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sub-Categories</h3>
                        <a href="{{ route('admin.glass-sub-categories.create') }}?glass_type_id={{ $glassType->id }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">
                            + Add Sub-Category
                        </a>
                    </div>
                    <div class="p-8 space-y-3">
                        @forelse($glassSubCategories as $subCategory)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subCategory->name }}</span>
                                <a href="{{ route('admin.glass-sub-categories.edit', $subCategory) }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Edit</a>
                            </div>
                        @empty
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">No sub-categories added.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Config Card -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $glassType->sort_order) }}" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $glassType->is_active) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Visible</span>
                    </label>

                    <button type="submit" class="w-full bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                        Update Glass Type
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::admin>