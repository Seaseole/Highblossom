<x-layouts::admin title="{{ __('admin-bookings.title') }}">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">{{ __('admin-bookings.title') }}</h1>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="status" class="admin-form-input text-sm py-2">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="admin-action-btn admin-action-btn-secondary text-sm">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-admin-border-subtle">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-bookings.client') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-bookings.contact') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-bookings.vehicle') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-bookings.price') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-bookings.status') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-bookings.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($bookings as $booking)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-admin-text">{{ $booking->client_name }}</div>
                                @if($booking->user)
                                    <div class="text-sm text-admin-text-muted">{{ $booking->user->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text-muted">
                                {{ $booking->client_phone ?? '-' }}
                                <br>
                                {{ $booking->client_email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ $booking->vehicle_details }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text">
                                {{ number_format($booking->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->status === 'completed')
                                    <span class="admin-badge admin-badge-active">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                                        {{ __('admin-bookings.completed') }}
                                    </span>
                                @elseif($booking->status === 'pending')
                                    <span class="admin-badge admin-badge-warning">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-2"></span>
                                        {{ __('admin-bookings.pending') }}
                                    </span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="admin-badge admin-badge-info">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-2 animate-pulse"></span>
                                        {{ __('admin-bookings.confirmed') }}
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-inactive">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-2"></span>
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-action-btn admin-action-btn-secondary">
                                    {{ __('admin-bookings.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-admin-text-muted">
                                {{ __('admin-bookings.no_bookings_found') }}
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
