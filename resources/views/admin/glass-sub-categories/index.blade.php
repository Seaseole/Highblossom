<x-layouts::admin title="Glass Sub-Categories">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Glass Sub-Categories</h1>
                <p class="text-gray-500 dark:text-gray-400">Manage detailed sub-categories for glass types.</p>
            </div>
            <a href="{{ route('admin.glass-sub-categories.create') }}" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                Create Sub-Category
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Glass Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($glassSubCategories as $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $sub->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-gray-300">
                                    {{ $sub->glassType->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-gray-400">{{ $sub->slug }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $sub->sort_order ?? 0 }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sub->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $sub->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.glass-sub-categories.edit', $sub) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No glass sub-categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($glassSubCategories->hasPages())
            <div class="mt-4">
                {{ $glassSubCategories->links() }}
            </div>
        @endif
    </div>
</x-layouts::admin>