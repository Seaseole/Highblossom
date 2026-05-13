<x-layouts::admin title="Staff Absences">
    <div class="p-6">
        {{-- Header --}}
        <div class="admin-section-header mb-6">
            <h1 class="admin-section-title">Staff Absences</h1>
        </div>

        {{-- Table --}}
        <div class="admin-glass-card rounded-3xl shadow-black/20 overflow-hidden">
            <table class='w-full min-w-[800px] divide-y divide-admin-border-subtle'>
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-admin-text uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($absences as $absence)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-admin-text">{{ $absence->staff?->name ?? '-' }}</div>
                                <div class="text-sm text-admin-text-muted">{{ $absence->staff?->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ $absence->starts_at->format('F j, Y g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ $absence->ends_at->format('F j, Y g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ $absence->reason }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.absences.show', $absence) }}" class="admin-action-btn admin-action-btn-secondary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-admin-text-muted mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-admin-text-muted">No staff absences found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $absences->links() }}
        </div>
    </div>
</x-layouts::admin>
