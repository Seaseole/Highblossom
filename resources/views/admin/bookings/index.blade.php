<x-layouts::admin title="{{ __('admin-bookings.title') }}">
    <div class="mx-auto max-w-7xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ __('admin-bookings.title') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">Manage and track customer bookings.</p>
            </div>

            <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex items-center gap-3">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search client..."
                    class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]"
                />
                <select
                    name="status"
                    class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]"
                >
                    <option value="">All Statuses</option>
                    @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                <button
                    type="submit"
                    class="rounded-full bg-gray-100 px-6 py-2 text-sm font-medium text-gray-900 transition-all hover:bg-gray-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                >
                    Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-bookings.client') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Scheduled
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-bookings.contact') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-bookings.vehicle') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-bookings.price') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-bookings.status') }}
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-bookings.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($bookings as $booking)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $booking->client_name }}
                                </div>
                                @if ($booking->user)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $booking->user->email }}
                                    </div>
                                @endif
                                @if ($booking->inspection)
                                    <div class="mt-1">
                                        <span class="rounded bg-indigo-100 px-2 py-0.5 text-[10px] font-medium text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">Has Inspection</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $booking->scheduled_at ? $booking->scheduled_at->format('M j, Y g:i A') : 'TBC' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $booking->client_phone ?? '-' }}<br />
                                {{ $booking->client_email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $booking->vehicle_details }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ number_format($booking->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.bookings.show', $booking) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >
                                    {{ __('admin-bookings.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                {{ __('admin-bookings.no_bookings_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</x-layouts::admin>
