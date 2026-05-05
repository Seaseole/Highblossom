<x-layouts::admin title="Staff Absence Details">
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.absences.index') }}" class="inline-flex items-center gap-2 text-sm text-admin-text-muted hover:text-admin-accent transition-colors duration-200 group">
                <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Staff Absences
            </a>
        </div>

        {{-- Title Section --}}
        <div class="mb-8">
            <h1 class="font-headline text-3xl font-bold text-admin-text tracking-tight">
                Staff Absence <span class="text-admin-accent">#{{ $absence->id }}</span>
            </h1>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Schedule Card --}}
            <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                        <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    Schedule
                </h2>
                <dl class="space-y-4">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Start Date</dt>
                        <dd class="text-sm font-medium text-admin-text">{{ $absence->starts_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">End Date</dt>
                        <dd class="text-sm font-medium text-admin-text">{{ $absence->ends_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Duration</dt>
                        <dd class="text-sm font-medium text-admin-text">{{ $absence->starts_at->diffInDays($absence->ends_at) }} days</dd>
                    </div>
                </dl>
            </div>

            {{-- Staff Information Card --}}
            <div class="admin-glass-card rounded-3xl shadow-black/20 p-6">
                <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                        <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    Staff Information
                </h2>
                <dl class="space-y-4">
                    @if($absence->staff)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Staff Member</dt>
                            <dd class="text-sm font-medium text-admin-text">{{ $absence->staff->name }}</dd>
                            <dd class="text-sm text-admin-text-muted">{{ $absence->staff->email }}</dd>
                        </div>
                    @else
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-admin-text-muted uppercase tracking-wider">Staff Member</dt>
                            <dd class="text-sm font-medium text-admin-text-muted italic">Not assigned</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Reason Card --}}
        <div class="admin-glass-card rounded-3xl shadow-black/20 p-6 mt-6">
            <h2 class="font-headline text-lg font-semibold text-admin-text uppercase tracking-wide mb-5 flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-admin-surface-alt flex items-center justify-center">
                    <svg class="w-4 h-4 text-admin-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                Reason
            </h2>
            <p class="text-sm text-admin-text p-4 bg-admin-surface-alt/50 rounded-2xl border border-admin-border-subtle">{{ $absence->reason }}</p>
        </div>
    </div>
</x-layouts::admin>
