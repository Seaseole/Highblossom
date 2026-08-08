<x-layouts::admin title="Booking Details">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <a
                    href="{{ route('admin.bookings.index') }}"
                    class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    ← Back to Bookings
                </a>
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    Booking #{{ $booking->id }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Created on {{ $booking->created_at->format('F j, Y \a\t g:i A') }}
                </p>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                <!-- Client Information -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Client Information</h2>
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->client_name }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Email</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->client_email ?? '—' }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Phone</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->client_phone ?? '—' }}
                            </dd>
                        </div>
                        @if ($booking->location === 'mobile' && $booking->client_address)
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    Client Address
                                </dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $booking->client_address }}
                                </dd>
                            </div>
                        @endif
                        @if ($booking->user)
                            <div class="space-y-1">
                                <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    User Account
                                </dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $booking->user->email }}
                                </dd>
                            </div>
                        @endif
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Scheduled At</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->scheduled_at ? $booking->scheduled_at->format('F j, Y g:i A') : 'TBC' }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Location</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $booking->location === 'mobile' ? 'Mobile Service' : ($booking->location ? 'Workshop' : 'TBC') }}
                                @if ($booking->location === 'mobile' && $booking->client_address)
                                    <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">{{ $booking->client_address }}</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Vehicle Details -->
                <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="mb-6 text-lg font-semibold text-gray-900 dark:text-white">Vehicle Details</h2>
                    <p class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-600 dark:border-white/5 dark:bg-white/5 dark:text-gray-300">
                        {{ $booking->vehicle_details }}
                    </p>
                </div>

                <!-- Inspection Panel -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Inspection</h2>
                    @if ($booking->inspection)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        Inspection #{{ $booking->inspection->id }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Assigned to: {{ $booking->inspection->staff->name ?? 'Unassigned' }}
                                    </p>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->inspection->ended_at ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ $booking->inspection->ended_at ? 'Completed' : 'Scheduled' }}
                                </span>
                            </div>
                            <a
                                href="{{ route('admin.inspections.show', $booking->inspection) }}"
                                class="inline-block w-full rounded-full bg-gray-900 px-6 py-2.5 text-center text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                            >
                                View Inspection
                            </a>
                        </div>
                    @else
                        <form action="{{ route('admin.inspections.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}" />
                            <input type="hidden" name="scheduled_at" value="{{ $booking->scheduled_at }}" />
                            <input type="hidden" name="location" value="{{ $booking->location ?? 'mobile' }}" />
                            <input type="hidden" name="type" value="mobile" />
                            <!-- Default to mobile, you could add a selector if needed -->

                            <div class="space-y-1">
                                <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Assign Staff</label>
                                <select
                                    name="staff_id"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                >
                                    <option value="">Select Staff</option>
                                    @foreach (\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-full bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-blue-700"
                            >
                                Schedule Inspection
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="space-y-8">
                <!-- Status Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status</h2>
                    @php
                        $statusColors = [
                            'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($booking->status) }}
                    </span>

                    <form
                        action="{{ route('admin.bookings.update-status', $booking) }}"
                        method="POST"
                        class="space-y-4 border-t border-gray-100 pt-4 dark:border-white/10"
                    >
                        @csrf
                        @method('PATCH')
                        <select
                            name="status"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <button
                            type="submit"
                            class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                        >
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Pricing & Notes Card -->
                <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pricing & Notes</h2>
                    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-1">
                            <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Total Price ($)</label>
                            <input
                                type="number"
                                step="0.01"
                                name="total_price"
                                value="{{ old('total_price', $booking->total_price) }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            />
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Internal Notes</label>
                            <!-- Note: The notes column might not exist in the database, this is handled gracefully if not fillable -->
                            <textarea
                                name="notes"
                                rows="3"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            >{{ old('notes', $booking->notes ?? '') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-full bg-gray-100 px-6 py-2.5 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/10 dark:text-white dark:hover:bg-white/20"
                        >
                            Save Changes
                        </button>
                    </form>
                </div>

                <!-- Delete Action -->
                <form
                    action="{{ route('admin.bookings.destroy', $booking) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this booking?');"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                    >
                        Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>
