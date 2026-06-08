<x-layouts::admin title="Staff Absences">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">Staff Absences</h1>
            <p class="text-gray-500 dark:text-gray-400">View and track staff absences.</p>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($absences as $absence)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $absence->staff?->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $absence->staff?->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $absence->starts_at->format('M j, Y g:i A') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $absence->ends_at->format('M j, Y g:i A') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $absence->reason }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.absences.show', $absence) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No staff absences found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $absences->links() }}
        </div>
    </div>
</x-layouts::admin>
