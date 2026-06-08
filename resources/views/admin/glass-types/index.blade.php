<x-layouts::admin title="Glass Types">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Glass Types</h1>
                <p class="text-gray-500 dark:text-gray-400">Manage the specialized architectural glass variants.</p>
            </div>
            
            <a href="{{ route('admin.glass-types.create') }}" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                Create Glass Type
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sub-Categories</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($glassTypes as $glassType)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $glassType->name }}</td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-500 dark:text-gray-400">{{ $glassType->slug }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-gray-300">
                                        {{ $glassType->subCategories->count() }}
                                    </span>
                                    @if($glassType->subCategories->count() > 0)
                                        <a href="{{ route('admin.glass-sub-categories.index') }}?glass_type_id={{ $glassType->id }}" class="text-xs font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $glassType->sort_order ?? 0 }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $glassType->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $glassType->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.glass-types.edit', $glassType) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No glass types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($glassTypes->hasPages())
            <div class="mt-4">
                {{ $glassTypes->links() }}
            </div>
        @endif
    </div>
</x-layouts::admin>