<x-layouts::admin title="Booking Details">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    ← Back to Bookings
                </a>
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">
                    Booking #{{ $booking->id }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">Created on {{ $booking->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Client Information -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Client Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->client_name }}</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->client_email ?? '—' }}</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->client_phone ?? '—' }}</dd>
                        </div>
                        @if($booking->user)
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">User Account</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->user->email }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Vehicle Details -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Vehicle Details</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100 dark:border-white/5">
                        {{ $booking->vehicle_details }}
                    </p>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Status Card -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status</h2>
                    @php
                        $statusColors = [
                            'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400'
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                    
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="space-y-4 pt-4 border-t border-gray-100 dark:border-white/10">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                            @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Pricing Card -->
                <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pricing</h2>
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Price</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>

                <!-- Delete Action -->
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-sm font-medium text-red-600 dark:text-red-400 hover:opacity-75 transition-opacity">
                        Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>
