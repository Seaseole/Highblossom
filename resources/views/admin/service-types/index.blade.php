<x-layouts::admin title="Service Types">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Service Types</h1>
            <a href="{{ route('admin.service-types.create') }}" class="admin-action-btn admin-action-btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Service Type</span>
            </a>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-white/5">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($serviceTypes as $serviceType)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-[#FAFAFA]">{{ $serviceType->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA]">
                                {{ $serviceType->slug }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA]">
                                {{ $serviceType->sort_order ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($serviceType->is_active)
                                    <span class="admin-badge admin-badge-active">
                                        Active
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-inactive">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.service-types.edit', $serviceType) }}" class="admin-action-btn admin-action-btn-secondary">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-[#A1A1AA]">
                                No service types found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $serviceTypes->links() }}
        </div>
    </div>
</x-layouts::admin>
