<x-layouts::admin title="Closed Dates">
    <div class="mx-auto max-w-4xl space-y-8 py-6 sm:py-10">
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Closed Dates</h1>
            <p class="text-gray-500 dark:text-gray-400">
                Public holidays and one-off closures. These override the weekly business hours for that specific day only.
            </p>
        </div>

        <form
            action="{{ route('admin.bookings.closures.store') }}"
            method="POST"
            class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F] sm:p-6"
        >
            @csrf
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="space-y-2 sm:w-48">
                    <label for="date" class="text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old('date') }}"
                        min="{{ now()->toDateString() }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                    />
                    @error('date')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex-1 space-y-2">
                    <label for="reason" class="text-sm font-medium text-gray-700 dark:text-gray-300">Reason</label>
                    <input
                        type="text"
                        id="reason"
                        name="reason"
                        value="{{ old('reason') }}"
                        placeholder="e.g. Sir Ketumile Masire Heritage Day"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                    />
                    @error('reason')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="min-h-[44px] rounded-xl bg-gray-900 px-6 text-sm font-medium text-white transition-all hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                >
                    Close this date
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">Reason</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse ($upcoming as $closure)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $closure->date->format('D d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $closure->reason ?: '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form
                                    action="{{ route('admin.bookings.closures.destroy', $closure) }}"
                                    method="POST"
                                    onsubmit="return confirm('Re-open {{ $closure->date->format('d M Y') }} for bookings?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="min-h-[36px] rounded-lg border border-gray-200 px-3 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-100 dark:border-white/10 dark:text-gray-300 dark:hover:bg-white/5"
                                    >
                                        Re-open
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                No dates are closed ahead. Customers can book every open weekday.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($recent->isNotEmpty())
            <div class="space-y-3">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Recently closed</h2>
                <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    @foreach ($recent as $closure)
                        <li>
                            {{ $closure->date->format('D d M Y') }}
                            @if ($closure->reason)
                                · {{ $closure->reason }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-layouts::admin>
