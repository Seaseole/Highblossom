<x-layouts::admin title="Bookings">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Bookings</h1>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-white/5">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-[#FAFAFA] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($bookings as $booking)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-[#FAFAFA]">{{ $booking->client_name }}</div>
                                @if($booking->user)
                                    <div class="text-sm text-[#A1A1AA]">{{ $booking->user->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA]">
                                {{ $booking->client_phone ?? '-' }}
                                <br>
                                {{ $booking->client_email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#FAFAFA]">
                                {{ $booking->vehicle_details }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#FAFAFA]">
                                {{ number_format($booking->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->status === 'completed')
                                    <span class="admin-badge admin-badge-active">
                                        Completed
                                    </span>
                                @elseif($booking->status === 'pending')
                                    <span class="admin-badge" style="background: rgba(234, 179, 8, 0.2); color: #EAB308; border: 1px solid rgba(234, 179, 8, 0.3);">
                                        Pending
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-inactive">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-action-btn admin-action-btn-secondary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#A1A1AA]">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</x-layouts::admin>
