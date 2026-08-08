<x-layouts::admin title="Gallery">
    <div class="mx-auto max-w-7xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Gallery</h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Manage your showcase of architectural glass and aluminum installations.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.gallery-settings.index') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    Gallery Settings
                </a>
                <a
                    href="{{ route('admin.gallery.create') }}"
                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Create Gallery Item
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Image
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Details
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Category
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Location
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Status
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($items as $item)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                @if ($item->image_path)
                                    <div class="h-16 w-16 overflow-hidden rounded-xl border border-gray-200 dark:border-white/10">
                                        <img
                                            src="{{ $item->image_url }}"
                                            alt="{{ $item->title }}"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-400 dark:bg-white/5">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title }}</div>
                                @if ($item->description)
                                    <div class="max-w-xs truncate text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $item->category->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $item->location_address ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    @if ($item->is_featured)
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">Featured</span>
                                    @endif
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.gallery.edit', $item) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No gallery items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="mt-4">{{ $items->links() }}</div>
        @endif
    </div>
</x-layouts::admin>
