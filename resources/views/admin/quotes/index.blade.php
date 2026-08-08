<x-layouts::admin title="Quotes">
    <div class="mx-auto max-w-7xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Quote Requests</h1>
                <p class="text-gray-500 dark:text-gray-400">Monitor and manage incoming requests for solutions.</p>
            </div>

            <form method="GET" action="{{ route('admin.quotes.index') }}" class="flex items-center gap-3">
                <select
                    name="status"
                    class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]"
                >
                    <option value="">All Statuses</option>
                    @foreach (['pending', 'contacted', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
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
                            Customer
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Project/Vehicle
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Service
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Received
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($quotes as $quote)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $quote->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $quote->phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $quote->vehicle_type }} <br />
                                <span class="text-xs text-gray-500">{{ $quote->make_model }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $quote->glassType->name ?? 'N/A' }} <br />
                                <span class="text-xs text-gray-500">{{ $quote->serviceType->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'contacted' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$quote->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($quote->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                {{ $quote->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.quotes.show', $quote) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No quote requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($quotes->hasPages())
            <div class="mt-4">{{ $quotes->links() }}</div>
        @endif
    </div>
</x-layouts::admin>
