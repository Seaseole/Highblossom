<x-layouts::admin title="Inspection Details">
    <div class="p-6">
        <div class="mb-6">
            <a href="{{ route('admin.inspections.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                &larr; Back to Inspections
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">Inspection #{{ $inspection->id }}</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Scheduled At</dt>
                                <dd class="text-sm text-gray-900">{{ $inspection->scheduled_at->format('F j, Y g:i A') }}</dd>
                            </div>
                            @if($inspection->ended_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ended At</dt>
                                    <dd class="text-sm text-gray-900">{{ $inspection->ended_at->format('F j, Y g:i A') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                <dd class="text-sm text-gray-900">{{ $inspection->location }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($inspection->type) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment</h3>
                        <dl class="space-y-2">
                            @if($inspection->staff)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Staff</dt>
                                    <dd class="text-sm text-gray-900">{{ $inspection->staff->name }}</dd>
                                    <dd class="text-sm text-gray-500">{{ $inspection->staff->email }}</dd>
                                </div>
                            @else
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Staff</dt>
                                    <dd class="text-sm text-gray-900">Not assigned</dd>
                                </div>
                            @endif
                            @if($inspection->booking)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Related Booking</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="{{ route('admin.bookings.show', $inspection->booking) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Booking #{{ $inspection->booking->id }}
                                        </a>
                                    </dd>
                                    <dd class="text-sm text-gray-500">{{ $inspection->booking->client_name }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::admin>
