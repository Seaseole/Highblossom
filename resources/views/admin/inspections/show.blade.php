<x-layouts::admin title="Inspection Details">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.inspections.index') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-colors duration-200 group">
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Inspections
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">
                Inspection <span class="text-admin-accent">#{{ $inspection->id }}</span>
            </h1>
            <p class="text-admin-text-muted text-sm mt-2">Scheduled for {{ $inspection->scheduled_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Schedule Card --}}
            <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                        <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Schedule
                </h2>
                <dl class="space-y-4">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Scheduled At</dt>
                        <dd class="text-sm font-medium text-admin-text">{{ $inspection->scheduled_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    @if($inspection->ended_at)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Ended At</dt>
                            <dd class="text-sm font-medium text-admin-text flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                {{ $inspection->ended_at->format('F j, Y g:i A') }}
                            </dd>
                        </div>
                    @endif
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Location</dt>
                        <dd class="text-sm font-medium text-admin-text">{{ $inspection->location }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Type</dt>
                        <dd class="text-sm font-medium text-admin-text">{{ ucfirst($inspection->type) }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Assignment Card --}}
            <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                        <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    Assignment
                </h2>
                <dl class="space-y-4">
                    @if($inspection->staff)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Staff</dt>
                            <dd class="text-sm font-medium text-admin-text">{{ $inspection->staff->name }}</dd>
                            <dd class="text-sm text-admin-text-muted">{{ $inspection->staff->email }}</dd>
                        </div>
                    @else
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Staff</dt>
                            <dd class="text-sm font-medium text-admin-text-muted italic">Not assigned</dd>
                        </div>
                    @endif
                    @if($inspection->booking)
                        <div class="space-y-1 pt-4 border-t border-admin-border-subtle">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Related Booking</dt>
                            <dd class="text-sm font-medium text-admin-text">
                                <a href="{{ route('admin.bookings.show', $inspection->booking) }}" class="text-admin-accent hover:underline">
                                    Booking #{{ $inspection->booking->id }}
                                </a>
                            </dd>
                            <dd class="text-sm text-admin-text-muted">{{ $inspection->booking->client_name }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</x-layouts::admin>
