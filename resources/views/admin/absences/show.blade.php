<x-layouts::admin title="Staff Absence Details">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.absences.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                &larr; Back to Staff Absences
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">Staff Absence #{{ $absence->id }}</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                <dd class="text-sm text-gray-900">{{ $absence->starts_at->format('F j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                                <dd class="text-sm text-gray-900">{{ $absence->ends_at->format('F j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                <dd class="text-sm text-gray-900">{{ $absence->starts_at->diffInDays($absence->ends_at) }} days</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Staff Information</h3>
                        <dl class="space-y-2">
                            @if($absence->staff)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Staff Member</dt>
                                    <dd class="text-sm text-gray-900">{{ $absence->staff->name }}</dd>
                                    <dd class="text-sm text-gray-500">{{ $absence->staff->email }}</dd>
                                </div>
                            @else
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Staff Member</dt>
                                    <dd class="text-sm text-gray-900">Not assigned</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reason</h3>
                    <p class="text-sm text-gray-900">{{ $absence->reason }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
