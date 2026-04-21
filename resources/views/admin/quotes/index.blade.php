<x-layouts::admin title="Quotes">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Quote Requests</h1>
        </div>

        <!-- Status Filter -->
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.quotes.index') }}" class="flex gap-3">
                <select name="status" class="admin-form-input">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="contacted" {{ $status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="admin-action-btn admin-action-btn-secondary">
                    Filter
                </button>
                @if($status)
                    <a href="{{ route('admin.quotes.index') }}" class="text-[#A1A1AA] hover:text-[#FAFAFA] text-sm transition-colors">Clear</a>
                @endif
            </form>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-white/5">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Service</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Received</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($quotes as $quote)
                        <tr class="{{ $quote->status === 'pending' ? 'bg-[#DC2626]/5' : '' }} transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-[#FAFAFA]">{{ $quote->name }}</div>
                                <div class="text-sm text-[#A1A1AA]">{{ $quote->phone }}</div>
                                @if($quote->email)
                                    <div class="text-sm text-[#71717A] text-xs">{{ $quote->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-[#FAFAFA]">{{ $quote->vehicle_type }}</div>
                                @if($quote->make_model)
                                    <div class="text-sm text-[#A1A1AA]">{{ $quote->make_model }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-[#FAFAFA]">{{ $quote->glassType->name ?? 'N/A' }}</div>
                                <div class="text-sm text-[#A1A1AA]">{{ $quote->serviceType->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($quote->status === 'pending')
                                    <span class="admin-badge" style="background: rgba(59, 130, 246, 0.2); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.3);">
                                        Pending
                                    </span>
                                @elseif($quote->status === 'contacted')
                                    <span class="admin-badge" style="background: rgba(234, 179, 8, 0.2); color: #EAB308; border: 1px solid rgba(234, 179, 8, 0.3);">
                                        Contacted
                                    </span>
                                @elseif($quote->status === 'completed')
                                    <span class="admin-badge admin-badge-active">
                                        Completed
                                    </span>
                                @elseif($quote->status === 'cancelled')
                                    <span class="admin-badge admin-badge-inactive">
                                        Cancelled
                                    </span>
                                @endif
                                @if($quote->mobile_service)
                                    <span class="ml-1 admin-badge" style="background: rgba(168, 85, 247, 0.2); color: #A855F7; border: 1px solid rgba(168, 85, 247, 0.3);">Mobile</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA]">
                                {{ $quote->created_at->format('M j, Y g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.quotes.show', $quote) }}" class="admin-action-btn admin-action-btn-secondary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#A1A1AA]">
                                No quotes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $quotes->links() }}
        </div>
    </div>
</x-layouts::admin>
