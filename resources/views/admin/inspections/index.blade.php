<x-layouts::admin title="Inspections">
    <div class="p-6">
        {{-- Header --}}
        <div class="admin-section-header mb-6">
            <h1 class="admin-section-title">Inspections</h1>
        </div>

        {{-- Table --}}
        <div class="admin-glass-card rounded-3xl shadow-black/20 overflow-hidden">
            <table class='w-full min-w-[800px] divide-y divide-admin-border-subtle'>
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Scheduled</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-admin-text uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($inspections as $inspection)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-admin-text">{{ $inspection->scheduled_at->format('F j, Y g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ $inspection->location }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ ucfirst($inspection->type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ $inspection->staff?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($inspection->ended_at)
                                    <span class="admin-badge admin-badge-active">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2"></span>
                                        Completed
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-warning">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-2"></span>
                                        Scheduled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.inspections.show', $inspection) }}" class="admin-action-btn admin-action-btn-secondary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-admin-text-muted mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                <p class="text-admin-text-muted">No inspections found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $inspections->links() }}
        </div>
    </div>
</x-layouts::admin>
