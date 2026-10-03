@php
    $noteStyles = [
        'added' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
        'changed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        'fixed' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
        'security' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400',
    ];
@endphp

{{-- The swapped region: kept free of Alpine bindings so re-rendered nodes need no re-initialisation. --}}
<ol
    id="release-timeline"
    class="relative mt-4 space-y-6 border-l border-gray-200 pl-6 dark:border-white/10 sm:pl-8"
    data-pages="{{ $historyPages }}"
    data-shown="{{ $releases->count() }}"
    data-total="{{ $historyTotal }}"
    data-has-more="{{ $historyHasMore ? 'true' : 'false' }}"
>
    @forelse ($releases as $release)
        <li class="relative">
            <span
                @class([
                    'absolute -left-[31px] top-1.5 size-3 rounded-full border-2 bg-white dark:bg-[#0A0A0F] sm:-left-[39px]',
                    'border-gray-900 dark:border-white' => $loop->first,
                    'border-gray-300 dark:border-white/20' => ! $loop->first,
                ])
            ></span>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-white/10 dark:bg-[#0A0A0F]">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span @class([
                        'rounded-full px-3 py-1 font-mono text-sm font-semibold',
                        'bg-gray-900 text-white dark:bg-white dark:text-gray-900' => $loop->first,
                        'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-gray-300' => ! $loop->first,
                    ])>v{{ $release->version }}</span>
                    @if ($loop->first)
                        <span class="text-[10px] font-bold tracking-widest text-emerald-600 uppercase dark:text-emerald-400">Current</span>
                    @endif
                    <span class="ml-auto text-xs text-gray-400 dark:text-gray-500">{{ $release->released_at->format('M j, Y') }}</span>
                </div>

                <p class="mt-3 text-sm font-medium text-gray-900 dark:text-white">{{ $release->summary }}</p>

                @if (! empty($release->notes))
                    <ul class="mt-3 space-y-2">
                        @foreach ($release->notes as $note)
                            <li class="flex flex-wrap items-start gap-2">
                                <span class="rounded px-2 py-0.5 text-[10px] font-bold tracking-wide uppercase {{ $noteStyles[$note['type']] ?? $noteStyles['changed'] }}">
                                    {{ $note['type'] }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $note['text'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($release->creator)
                    <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">Recorded by {{ $release->creator->name }}</p>
                @endif
            </div>
        </li>
    @empty
        <li class="rounded-2xl border-2 border-dashed border-gray-100 p-8 text-center text-sm text-gray-500 dark:border-white/5">
            No releases recorded yet.
        </li>
    @endforelse
</ol>
