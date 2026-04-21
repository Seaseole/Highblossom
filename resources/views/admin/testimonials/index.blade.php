<x-layouts::admin title="Testimonials">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Testimonials</h1>
            <a href="{{ route('admin.testimonials.create') }}" class="admin-action-btn admin-action-btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Testimonial</span>
            </a>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-white/5">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($testimonials as $testimonial)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-[#FAFAFA]">{{ $testimonial->name }}</div>
                                <div class="text-sm text-[#A1A1AA] truncate max-w-xs">{{ $testimonial->content }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#FAFAFA]">
                                {{ $testimonial->role ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($testimonial->is_featured)
                                    <span class="admin-badge" style="background: rgba(234, 179, 8, 0.2); color: #EAB308; border: 1px solid rgba(234, 179, 8, 0.3); margin-right: 0.5rem;">
                                        Featured
                                    </span>
                                @endif
                                @if($testimonial->is_published)
                                    <span class="admin-badge admin-badge-active">
                                        Published
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-inactive">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="admin-action-btn admin-action-btn-secondary">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-[#A1A1AA]">
                                No testimonials found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $testimonials->links() }}
        </div>
    </div>
</x-layouts::admin>
