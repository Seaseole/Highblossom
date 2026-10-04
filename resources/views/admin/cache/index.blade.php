<x-layouts::admin title="Cache Management">
    <div class="mx-auto max-w-5xl space-y-8 py-6 sm:py-10">
        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="circle-stack" size="sm" class="text-gray-500 dark:text-gray-400" />
                    <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Cache Management</h1>
                </div>
                <p class="text-gray-500 dark:text-gray-400">Inspect, optimize, and clear the application's cache groups.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('admin.cache.optimize-all') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700"
                    >
                        <x-ui.icon name="arrow-path" size="sm" />
                        Optimize All
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.cache.clear-all') }}" onsubmit="return confirm('Clear ALL caches? This cannot be undone and may temporarily slow down the site.');">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition-colors hover:bg-red-50 dark:border-red-900/50 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-950/20"
                    >
                        <x-ui.icon name="trash" size="sm" />
                        Clear All
                    </button>
                </form>
            </div>
        </div>

        {{-- Groups table --}}
        <div class="overflow-x-auto rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">Cache Group</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">Entries</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">Size</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @foreach ($groups as $group)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $group['label'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $group['description'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-600 dark:text-gray-300">
                                {{ $group['entries'] === null ? '—' : number_format($group['entries']) }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-600 dark:text-gray-300">
                                {{ $group['size_bytes'] === null ? '—' : \Illuminate\Support\Number::fileSize($group['size_bytes'], precision: 1) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    @if ($group['optimizable'])
                                        <form method="POST" action="{{ route('admin.cache.optimize', $group['key']) }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/5"
                                            >
                                                Optimize
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.cache.clear', $group['key']) }}" onsubmit="return confirm('Clear the {{ $group['label'] }} cache?');">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition-colors hover:bg-red-50 dark:border-red-900/50 dark:text-red-400 dark:hover:bg-red-950/20"
                                        >
                                            Clear
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Notes --}}
        <div class="rounded-3xl border border-blue-100 bg-blue-50/50 p-6 dark:border-blue-900/40 dark:bg-blue-950/20">
            <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">How groups work</h2>
            <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-300">
                <li>&bull; <strong>Optimize</strong> warms or rebuilds a group so the next visitors get cached responses immediately.</li>
                <li>&bull; <strong>Clear</strong> removes a group's entries; they are rebuilt on the next request.</li>
                <li>&bull; Framework Optimize caches config, routes, events, and views. Use Framework Clear after deploying changes to env config.</li>
                <li>&bull; User sessions are stored separately and are never removed by cache clearing.</li>
            </ul>
        </div>
    </div>
</x-layouts::admin>
