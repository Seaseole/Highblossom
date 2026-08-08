<x-layouts::admin title="Inspection Details">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <a
                href="{{ route('admin.inspections.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                ← Back to Inspections
            </a>
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                Inspection #{{ $inspection->id }}
            </h1>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Schedule Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Schedule</h2>
                <dl class="space-y-4">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Scheduled At</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $inspection->scheduled_at->format('F j, Y g:i A') }}
                        </dd>
                    </div>
                    @if ($inspection->ended_at)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Ended At</dt>
                            <dd class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                {{ $inspection->ended_at->format('F j, Y g:i A') }}
                            </dd>
                        </div>
                    @endif
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Location</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $inspection->location }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Type</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ ucfirst($inspection->type) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Assignment Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Assignment</h2>
                <dl class="space-y-4">
                    @if ($inspection->staff)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Staff</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $inspection->staff->name }}
                            </dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">{{ $inspection->staff->email }}</dd>
                        </div>
                    @else
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Staff</dt>
                            <dd class="text-sm font-medium text-gray-500 italic dark:text-gray-400">Not assigned</dd>
                        </div>
                    @endif
                    @if ($inspection->booking)
                        <div class="space-y-1 border-t border-gray-100 pt-4 dark:border-white/10">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                Related Booking
                            </dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <a
                                    href="{{ route('admin.bookings.show', $inspection->booking) }}"
                                    class="text-blue-600 hover:underline dark:text-blue-400"
                                >
                                    Booking #{{ $inspection->booking->id }}
                                </a>
                            </dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $inspection->booking->client_name }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Update Form Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Update Inspection</h2>
                <form action="{{ route('admin.inspections.update', $inspection) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Assign Staff</label>
                        <select
                            name="staff_id"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="">Select Staff</option>
                            @foreach ($staffMembers as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ $inspection->staff_id === $user->id ? 'selected' : '' }}
                                >
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Scheduled At</label>
                        <input
                            type="datetime-local"
                            name="scheduled_at"
                            value="{{ $inspection->scheduled_at->format('Y-m-d\TH:i') }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Ended At (Completion Time)</label>
                        <input
                            type="datetime-local"
                            name="ended_at"
                            value="{{ $inspection->ended_at ? $inspection->ended_at->format('Y-m-d\TH:i') : '' }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Location</label>
                        <input
                            type="text"
                            name="location"
                            value="{{ $inspection->location }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Type</label>
                        <select
                            name="type"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="mobile" {{ $inspection->type === 'mobile' ? 'selected' : '' }}>
                                Mobile
                            </option>
                            <option value="workshop" {{ $inspection->type === 'workshop' ? 'selected' : '' }}>
                                Workshop
                            </option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update Inspection
                    </button>
                </form>

                <!-- Delete Action -->
                <form
                    action="{{ route('admin.inspections.destroy', $inspection) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this inspection?');"
                    class="border-t border-gray-100 pt-4 dark:border-white/10"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                    >
                        Delete Inspection
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>
