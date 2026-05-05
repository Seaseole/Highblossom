<x-layouts::admin title="Gallery">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Gallery
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Manage the showcase of architectural glass and aluminum installations.
                </p>
            </div>
            
            <a href="{{ route('admin.gallery.create') }}" class="admin-action-btn admin-action-btn-primary group">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Gallery Item</span>
            </a>
        </div>

        <div class="admin-table admin-glass-card shadow-2xl shadow-black/20 overflow-x-auto">
            <table class="min-w-full divide-y divide-admin-border-subtle">
                <thead class="bg-admin-accent/5">
                    <tr>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Image</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Details</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Category</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Location</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Status</th>
                        <th scope="col" class="px-8 py-5 text-right text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($items as $item)
                        <tr class="group hover:bg-admin-accent/5 transition-all duration-300 ease-out-expo">
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($item->image_path)
                                    <div class="relative w-16 h-16 group/img">
                                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover rounded-xl border border-admin-border-subtle grayscale group-hover/img:grayscale-0 transition-all duration-500">
                                        <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-white/10"></div>
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-admin-surface-alt rounded-xl border border-admin-border-subtle flex items-center justify-center">
                                        <svg class="w-8 h-8 text-admin-text-muted/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <span class="text-base font-bold text-admin-text font-headline tracking-tight">{{ $item->title }}</span>
                                    @if($item->description)
                                        <span class="text-xs text-admin-text-muted font-body leading-relaxed max-w-[200px] line-clamp-2">{{ $item->description }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-xs font-bold text-admin-text-muted uppercase tracking-widest font-body">{{ $item->category->name ?? '-' }}</span>
                            </td>
                            <td class="px-8 py-6">
                                @if($item->location_address)
                                    <div class="flex items-center gap-2 text-admin-text-muted group/loc">
                                        <svg class="w-3.5 h-3.5 group-hover/loc:text-admin-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="text-xs font-body truncate max-w-[150px]">{{ $item->location_address }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-admin-text-muted">-</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    @if($item->is_featured)
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-amber-500/10 border border-amber-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                                            <span class="text-[9px] font-bold text-amber-500 uppercase tracking-widest font-body">Featured</span>
                                        </div>
                                    @endif
                                    @if($item->is_active)
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-green-500/10 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                            <span class="text-[9px] font-bold text-green-500 uppercase tracking-widest font-body">Active</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-admin-text-muted/10 border border-admin-text-muted/20 opacity-50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-admin-text-muted"></span>
                                            <span class="text-[9px] font-bold text-admin-text-muted uppercase tracking-widest font-body">Inactive</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <a href="{{ route('admin.gallery.edit', $item) }}" class="admin-action-btn admin-action-btn-ghost group/edit">
                                    <svg class="w-4 h-4 transition-colors group-hover/edit:text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="font-bold tracking-tight text-sm">Edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-20 h-20 rounded-full bg-admin-surface-alt flex items-center justify-center border border-admin-border-subtle">
                                        <svg class="w-10 h-10 text-admin-text-muted/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-admin-text-muted font-body text-sm">No gallery items found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="pt-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-layouts::admin>

