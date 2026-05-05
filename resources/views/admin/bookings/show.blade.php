<x-layouts::admin title="Booking Details">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-colors duration-200 group">
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Bookings
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">
                Booking <span class="text-admin-accent">#{{ $booking->id }}</span>
            </h1>
            <p class="text-admin-text-muted text-sm mt-2">Created on {{ $booking->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Client Information --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                    <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                            <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        Client Information
                    </h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Name</dt>
                            <dd class="text-sm font-medium text-admin-text">{{ $booking->client_name }}</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Email</dt>
                            <dd class="text-sm font-medium text-admin-text">{{ $booking->client_email ?? '—' }}</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Phone</dt>
                            <dd class="text-sm font-medium text-admin-text">{{ $booking->client_phone ?? '—' }}</dd>
                        </div>
                        @if($booking->user)
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">User Account</dt>
                                <dd class="text-sm font-medium text-admin-text flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    {{ $booking->user->email }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Vehicle Details --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                    <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                            <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </span>
                        Vehicle Details
                    </h2>
                    <div class="text-sm font-medium text-admin-text p-4 bg-admin-surface-alt/50 rounded-2xl border border-admin-border-subtle">
                        {{ $booking->vehicle_details }}
                    </div>
                </div>
            </div>

            {{-- Right Column - Meta & Actions --}}
            <div class="space-y-6">
                {{-- Status Card --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                    <h2 class="font-headline text-sm font-semibold text-admin-text uppercase tracking-wide mb-4">Status</h2>
                    <div class="mb-5">
                        @if($booking->status === 'completed')
                            <span class="admin-badge admin-badge-active w-full justify-center py-2 text-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        @elseif($booking->status === 'pending')
                            <span class="admin-badge admin-badge-warning w-full justify-center py-2 text-sm">
                                <span class="w-2 h-2 rounded-full bg-amber-400 mr-2"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        @elseif($booking->status === 'confirmed')
                            <span class="admin-badge admin-badge-info w-full justify-center py-2 text-sm">
                                <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 animate-pulse"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        @else
                            <span class="admin-badge admin-badge-inactive w-full justify-center py-2 text-sm">
                                <span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        @endif
                    </div>

                    {{-- Status Update Form --}}
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="admin-form-input w-full text-sm">
                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="admin-action-btn admin-action-btn-primary w-full text-sm">
                            Update Status
                        </button>
                    </form>
                </div>

                {{-- Pricing Card --}}
                <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                    <h2 class="font-headline text-sm font-semibold text-admin-text uppercase tracking-wide mb-4">Pricing</h2>
                    <div class="flex items-center justify-between p-4 bg-admin-surface-alt/50 rounded-2xl border border-admin-border-subtle">
                        <span class="text-admin-text-muted text-sm">Total Price</span>
                        <span class="font-headline text-2xl font-bold text-admin-text">${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>

                {{-- Delete Action --}}
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 border border-admin-accent/30 rounded-2xl text-sm font-medium text-admin-accent hover:bg-admin-accent/10 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>
