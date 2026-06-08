<x-layouts::admin title="Create Glass Sub-Category">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Create Sub-Category</h1>
                <p class="text-gray-500 dark:text-gray-400">Add a new detailed glass sub-category.</p>
            </div>
            <a href="{{ route('admin.glass-sub-categories.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Back to Sub-Categories
            </a>
        </div>

        <form action="{{ route('admin.glass-sub-categories.store') }}" method="POST" 
              class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Glass Type</label>
                <select name="glass_type_id" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                    <option value="">Select a glass type...</option>
                    @foreach($glassTypes as $glassType)
                        <option value="{{ $glassType->id }}" {{ request('glass_type_id') == $glassType->id ? 'selected' : '' }}>{{ $glassType->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sub-Category Name</label>
                <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="e.g., Left Hand Side - Rear Door Glass">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL Slug</label>
                <input type="text" name="slug" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white" placeholder="e.g., left-hand-side-rear-door-glass">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="0" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 dark:border-white/20 text-gray-900 focus:ring-gray-900 dark:focus:ring-white">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
            </label>

            <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-end gap-3">
                <a href="{{ route('admin.glass-sub-categories.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                    Create Sub-Category
                </button>
            </div>
        </form>
    </div>
</x-layouts::admin>