<x-layouts::admin title="Service Types">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-4xl font-bold tracking-tight text-admin-text font-headline leading-none">
                    Service Types
                </h1>
                <p class="text-admin-text-muted text-sm max-w-lg">
                    Manage the specialized service categories for glass and aluminum installations.
                </p>
            </div>
            
            <a href="{{ route('admin.service-types.create') }}" class="admin-action-btn admin-action-btn-primary group">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Service Type</span>
            </a>
        </div>

        <div class='admin-table admin-glass-card shadow-2xl shadow-black/20 overflow-x-auto'>
            <table class='w-full min-w-[800px] divide-y divide-admin-border-subtle'>
                <thead class="bg-admin-accent/5">
                    <tr>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Name</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Slug</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Order</th>
                        <th scope="col" class="px-8 py-5 text-left text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Status</th>
                        <th scope="col" class="px-8 py-5 text-right text-[10px] font-bold text-admin-text-muted uppercase tracking-[0.2em] font-body">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($serviceTypes as $serviceType)
                        <tr class="group hover:bg-admin-accent/5 transition-all duration-300 ease-out-expo">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-base font-bold text-admin-text font-headline tracking-tight">{{ $serviceType->name }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-mono text-admin-text-muted lowercase tracking-tighter">{{ $serviceType->slug }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-mono text-admin-text-muted">{{ $serviceType->sort_order ?? 0 }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($serviceType->is_active)
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                        <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest font-body">Active</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 opacity-50">
                                        <span class="w-2 h-2 rounded-full bg-admin-text-muted"></span>
                                        <span class="text-[10px] font-bold text-admin-text-muted uppercase tracking-widest font-body">Inactive</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <a href="{{ route('admin.service-types.edit', $serviceType) }}" class="admin-action-btn admin-action-btn-ghost group/edit">
                                    <svg class="w-4 h-4 transition-colors group-hover/edit:text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="font-bold tracking-tight text-sm">Edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-20 h-20 rounded-full bg-admin-surface-alt flex items-center justify-center border border-admin-border-subtle">
                                        <svg class="w-10 h-10 text-admin-text-muted/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-admin-text-muted font-body text-sm">No service types found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($serviceTypes->hasPages())
            <div class="pt-4">
                {{ $serviceTypes->links() }}
            </div>
        @endif
    </div>
</x-layouts::admin>

