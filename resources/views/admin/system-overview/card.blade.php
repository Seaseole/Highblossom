@php
    $recalculate = $recalculate ?? false;
@endphp

<section
    id="system-overview-{{ $section->key }}"
    class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
>
    <header class="flex items-start justify-between gap-4 border-b border-gray-100 px-6 py-4 dark:border-white/10">
        <div class="flex items-start gap-3">
            <x-ui.icon :name="$section->icon" size="sm" class="mt-0.5 text-gray-500 dark:text-gray-400" />
            <div>
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ $section->label }}</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $section->description }}</p>
            </div>
        </div>
        <span
            @class([
                'inline-block h-2.5 w-2.5 shrink-0 rounded-full mt-2',
                'bg-emerald-500' => $section->status->value === 'ok',
                'bg-gray-400' => $section->status->value === 'info',
                'bg-amber-500' => $section->status->value === 'warn',
                'bg-red-500' => $section->status->value === 'fail',
                'bg-blue-500' => $section->status->value === 'pending',
            ])
            title="{{ ucfirst($section->status->value) }}"
        ></span>
    </header>

    <dl>
        @foreach ($section->items as $item)
            <div class="flex items-start justify-between gap-6 border-t border-gray-50 px-6 py-3 first:border-t-0 dark:border-white/5">
                <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $item->label }}</dt>
                <dd class="min-w-0 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if ($item->status !== null)
                            <span
                                @class([
                                    'inline-block h-1.5 w-1.5 shrink-0 rounded-full',
                                    'bg-emerald-500' => $item->status->value === 'ok',
                                    'bg-gray-400' => $item->status->value === 'info',
                                    'bg-amber-500' => $item->status->value === 'warn',
                                    'bg-red-500' => $item->status->value === 'fail',
                                    'bg-blue-500' => $item->status->value === 'pending',
                                ])
                            ></span>
                        @endif

                        @if ($item->value === null)
                            <span class="text-sm text-gray-400 dark:text-gray-500">&mdash;</span>
                        @elseif ($item->route !== null && Route::has($item->route))
                            <a
                                href="{{ route($item->route) }}"
                                class="text-sm font-medium text-blue-600 underline-offset-4 hover:underline dark:text-blue-400"
                            >{{ $item->value }}</a>
                        @else
                            <span class="break-words text-sm font-medium text-gray-900 dark:text-white">{{ $item->value }}</span>
                        @endif
                    </div>

                    @if ($item->hint !== null)
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $item->hint }}</p>
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>

    <footer class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 px-6 py-3 text-xs text-gray-400 dark:border-white/10 dark:text-gray-500">
        <span>
            @if ($section->warmAt === null)
                {{ __('messages.system_overview_never_measured') }}
            @else
                {{ __('messages.system_overview_measured', [
                    'ms' => $section->elapsedMs,
                    'when' => \Illuminate\Support\Carbon::createFromTimestamp($section->warmAt)->diffForHumans(),
                ]) }}
            @endif
        </span>

        @if (! empty($recalculate))
            <button
                type="button"
                wire:click="measure"
                wire:loading.attr="disabled"
                wire:target="measure"
                class="rounded-lg border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 disabled:opacity-60 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/5"
            >
                <span wire:loading.remove wire:target="measure">{{ __('messages.system_overview_measure_now') }}</span>
                <span wire:loading wire:target="measure">{{ __('messages.system_overview_measuring') }}</span>
            </button>
        @endif
    </footer>
</section>
