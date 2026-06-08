<x-layouts::admin title="Staff Absence Details">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <a href="{{ route('admin.absences.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                ← Back to Staff Absences
            </a>
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">
                Staff Absence #{{ $absence->id }}
            </h1>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Schedule Card -->
            <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Schedule</h2>
                <dl class="space-y-4">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Start Date</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $absence->starts_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">End Date</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $absence->ends_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $absence->starts_at->diffInDays($absence->ends_at) }} days</dd>
                    </div>
                </dl>
            </div>

            <!-- Staff Information Card -->
            <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Staff Information</h2>
                <dl class="space-y-4">
                    @if($absence->staff)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff Member</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $absence->staff->name }}</dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">{{ $absence->staff->email }}</dd>
                        </div>
                    @else
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff Member</dt>
                            <dd class="text-sm font-medium text-gray-500 dark:text-gray-400 italic">Not assigned</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Reason Card -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Reason</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/5 leading-relaxed">
                {{ $absence->reason }}
            </p>
        </div>
    </div>
</x-layouts::admin>
