<x-layouts::admin title="Quotes">
    <div class="max-w-7xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Quote Requests</h1>
                <p class="text-gray-500 dark:text-gray-400">Monitor and manage incoming requests for solutions.</p>
            </div>
            
            <form method="GET" action="{{ route('admin.quotes.index') }}" class="flex items-center gap-3">
                <select name="status" class="bg-white dark:bg-[#0A0A0F] border border-gray-200 dark:border-white/10 rounded-full px-4 py-2 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'contacted', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white font-medium py-2 px-6 rounded-full text-sm transition-all">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Project/Vehicle</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Received</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($quotes as $quote)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $quote->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $quote->phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $quote->vehicle_type }} <br>
                                <span class="text-xs text-gray-500">{{ $quote->make_model }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $quote->glassType->name ?? 'N/A' }} <br>
                                <span class="text-xs text-gray-500">{{ $quote->serviceType->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'contacted' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400'
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
                                <a href="{{ route('admin.quotes.show', $quote) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No quote requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotes->hasPages())
            <div class="mt-4">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</x-layouts::admin>
