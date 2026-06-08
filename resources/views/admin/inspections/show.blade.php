<x-layouts::admin title="Inspection Details">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <a href="{{ route('admin.inspections.index') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                ← Back to Inspections
            </a>
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">
                Inspection #{{ $inspection->id }}
            </h1>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Schedule Card -->
            <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Schedule</h2>
                <dl class="space-y-4">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Scheduled At</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $inspection->scheduled_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    @if($inspection->ended_at)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ended At</dt>
                            <dd class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                {{ $inspection->ended_at->format('F j, Y g:i A') }}
                            </dd>
                        </div>
                    @endif
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $inspection->location }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($inspection->type) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Assignment Card -->
            <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Assignment</h2>
                <dl class="space-y-4">
                    @if($inspection->staff)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $inspection->staff->name }}</dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">{{ $inspection->staff->email }}</dd>
                        </div>
                    @else
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff</dt>
                            <dd class="text-sm font-medium text-gray-500 dark:text-gray-400 italic">Not assigned</dd>
                        </div>
                    @endif
                    @if($inspection->booking)
                        <div class="space-y-1 pt-4 border-t border-gray-100 dark:border-white/10">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Related Booking</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('admin.bookings.show', $inspection->booking) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    Booking #{{ $inspection->booking->id }}
                                </a>
                            </dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">{{ $inspection->booking->client_name }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</x-layouts::admin>