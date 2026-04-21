<x-layouts::admin title="Booking Details">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.bookings.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                &larr; Back to Bookings
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->client_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->client_email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->client_phone ?? '-' }}</dd>
                            </div>
                            @if($booking->user)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">User Account</dt>
                                    <dd class="text-sm text-gray-900">{{ $booking->user->email }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Details</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Vehicle Details</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->vehicle_details }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Price</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($booking->total_price, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="text-sm text-gray-900">{{ $booking->created_at->format('F j, Y g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
