@php
    $packages = $this->packages;
    $totals = $this->totals;
@endphp

<div
    wire:key="system-overview-packages"
    class="mt-4 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
>
    <header class="border-b border-gray-100 px-6 py-4 dark:border-white/10">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-start gap-3">
                <x-ui.icon name="document-text" size="sm" class="mt-0.5 text-gray-500 dark:text-gray-400" />
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ __('messages.system_overview_packages_title') }}
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('messages.system_overview_packages_hint') }}
                    </p>
                </div>
            </div>

            <div class="relative w-full sm:max-w-xs">
                <x-ui.icon name="magnifying-glass" size="xs" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-gray-400" />
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('messages.system_overview_packages_search_placeholder') }}"
                    aria-label="{{ __('messages.system_overview_packages_search_placeholder') }}"
                    class="w-full rounded-xl border border-gray-200 bg-white py-2 pr-10 pl-9 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white"
                />

                <span wire:loading wire:target="search" class="absolute top-1/2 right-8 -translate-y-1/2">
                    <x-ui.icon name="arrow-path" size="xs" class="animate-spin text-blue-500" />
                </span>

                @if ($search !== '')
                    <button
                        type="button"
                        wire:click="clearSearch"
                        wire:loading.attr="disabled"
                        wire:target="search"
                        class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 transition-colors hover:text-gray-600 dark:hover:text-gray-200"
                    >
                        <x-ui.icon name="x-mark" size="xs" />
                        <span class="sr-only">{{ __('messages.system_overview_packages_clear') }}</span>
                    </button>
                @endif
            </div>
        </div>

        <div
            class="mt-4 flex flex-wrap gap-2"
            role="group"
            aria-label="{{ __('messages.system_overview_packages_source_label') }}"
        >
            @foreach ($sources as $key => $label)
                <button
                    type="button"
                    wire:click="setSource('{{ $key }}')"
                    wire:loading.attr="disabled"
                    aria-pressed="{{ $source === $key ? 'true' : 'false' }}"
                    @class([
                        'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition-colors',
                        'bg-blue-600 text-white' => $source === $key,
                        'border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-white/10 dark:text-gray-300 dark:hover:bg-white/5' => $source !== $key,
                    ])
                >
                    {{ $label }}
                    <span @class([
                        'tabular-nums',
                        'text-blue-200' => $source === $key,
                        'text-gray-400 dark:text-gray-500' => $source !== $key,
                    ])>{{ $totals[$key] }}</span>
                </button>
            @endforeach
        </div>
    </header>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-white/10">
            <thead>
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">Package</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">Version</th>
                    <th scope="col" class="hidden px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase md:table-cell dark:text-gray-400">Type</th>
                    <th scope="col" class="hidden px-6 py-3 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase lg:table-cell dark:text-gray-400">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                @forelse ($packages as $package)
                    <tr>
                        <td class="max-w-[14rem] px-6 py-3 align-top break-words">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $package['name'] }}</span>
                                @if ($package['dev'])
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold tracking-wide text-amber-800 uppercase dark:bg-amber-950/40 dark:text-amber-300">dev</span>
                                @endif
                            </div>
                            @if ($package['homepage'] !== null)
                                <a
                                    href="{{ $package['homepage'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-1 inline-block text-xs text-blue-600 underline-offset-4 hover:underline dark:text-blue-400"
                                >Homepage</a>
                            @endif
                        </td>
                        <td class="px-6 py-3 align-top font-mono text-xs whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $package['version'] }}</td>
                        <td class="hidden px-6 py-3 align-top text-xs whitespace-nowrap text-gray-500 md:table-cell dark:text-gray-400">{{ $package['type'] }}</td>
                        <td class="hidden max-w-sm px-6 py-3 align-top text-xs text-gray-500 lg:table-cell dark:text-gray-400">{{ $package['description'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ __('messages.system_overview_packages_empty') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($packages->hasPages())
        <footer class="flex justify-center border-t border-gray-100 px-6 py-3 dark:border-white/10">
            {{ $packages->links() }}
        </footer>
    @endif
</div>
