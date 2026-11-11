@php
    $noteStyles = [
        'added' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
        'changed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        'fixed' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
        'security' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400',
    ];

    // Node identity per release type: ring colour (like the coloured avatar rings), tinted core, icon.
    // Full class strings on purpose so Tailwind's scanner picks them up.
    $kinds = [
        'added' => [
            'icon' => 'M12 4.5v15m7.5-7.5h-15',
            'label' => 'New features',
            'ring' => 'border-emerald-500 dark:border-emerald-400',
            'core' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-300',
        ],
        'changed' => [
            'icon' => 'M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5',
            'label' => 'Changes',
            'ring' => 'border-blue-500 dark:border-blue-400',
            'core' => 'bg-blue-50 text-blue-600 dark:bg-blue-400/10 dark:text-blue-300',
        ],
        'fixed' => [
            'icon' => 'M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z',
            'label' => 'Fixes',
            'ring' => 'border-amber-500 dark:border-amber-400',
            'core' => 'bg-amber-50 text-amber-600 dark:bg-amber-400/10 dark:text-amber-300',
        ],
        'security' => [
            'icon' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z',
            'label' => 'Security',
            'ring' => 'border-rose-500 dark:border-rose-400',
            'core' => 'bg-rose-50 text-rose-600 dark:bg-rose-400/10 dark:text-rose-300',
        ],
    ];

    // The most important note type decides the node: security > added > fixed > changed.
    $dominantKind = function ($notes) use ($kinds): string {
        $notes = collect($notes ?? []);

        foreach (['security', 'added', 'fixed', 'changed'] as $type) {
            if ($notes->contains('type', $type)) {
                return $type;
            }
        }

        return 'changed';
    };

    // Inline Heroicons (outline) so the view has no icon-package dependency.
    $tagPath = 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3ZM6 6h.008v.008H6V6Z';
    $chevronPath = 'm19.5 8.25-7.5 7.5-7.5-7.5';
    $icon = fn (string $path, string $class) => new \Illuminate\Support\HtmlString(
        '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" class="'.e($class).'"><path stroke-linecap="round" stroke-linejoin="round" d="'.e($path).'" /></svg>'
    );

    $spine = 'border-cyan-500/50 dark:border-cyan-400/60';
    $surface = 'bg-white dark:bg-[#0A0A0F]';
@endphp

{{-- The swapped region: kept free of Alpine bindings so re-rendered nodes need no re-initialisation. --}}
{{--
    Geometry (all px, so the spine, connectors and nodes line up exactly):
    spine centre x = 24 · root node 48 · branch node 36 at x = 48 (centre y = 18) · terminal node 24
--}}
<ol
    id="release-timeline"
    class="relative mt-4"
    data-pages="{{ $historyPages }}"
    data-shown="{{ $releases->count() }}"
    data-total="{{ $historyTotal }}"
    data-has-more="{{ $historyHasMore ? 'true' : 'false' }}"
>
    @forelse ($releases as $release)
        @php
            $kind = $kinds[$dominantKind($release->notes)];
            $isTerminal = $loop->last && ! $historyHasMore;
        @endphp

        @if ($loop->first)
            {{-- Root: the current release, like the original post in a thread --}}
            <li @class(['relative pl-16', 'pb-8' => ! $isTerminal])>
                @unless ($isTerminal)
                    <span aria-hidden="true" class="absolute top-12 bottom-0 left-[23px] border-l-2 border-dashed {{ $spine }}"></span>

                    {{-- Anchor dot where the thread starts --}}
                    <span aria-hidden="true" class="absolute top-[54px] left-3.5 z-10 grid size-5 place-items-center rounded-full border-2 border-cyan-500 {{ $surface }} dark:border-cyan-400">
                        <span class="size-2 rounded-full bg-cyan-500 dark:bg-cyan-400"></span>
                    </span>
                @endunless

                <span aria-hidden="true" class="absolute top-0 left-0 z-10 grid size-12 place-items-center rounded-full border-2 border-cyan-500 p-0.5 {{ $surface }} dark:border-cyan-400">
                    <span class="grid size-full place-items-center rounded-full bg-cyan-50 text-cyan-600 dark:bg-cyan-400/10 dark:text-cyan-300">
                        {!! $icon($tagPath, 'size-5') !!}
                    </span>
                </span>

                <div class="min-w-0 pt-0.5">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="font-mono text-base font-semibold text-gray-900 dark:text-white">v{{ $release->version }}</span>

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-400">
                            <span class="relative flex size-1.5" aria-hidden="true">
                                <span class="absolute inline-flex size-full rounded-full bg-emerald-500 opacity-60 motion-safe:animate-ping"></span>
                                <span class="relative inline-flex size-1.5 rounded-full bg-emerald-500"></span>
                            </span>
                            Current
                        </span>

                        <time datetime="{{ $release->released_at->toDateString() }}" class="text-xs text-gray-400 sm:ml-auto dark:text-gray-500">
                            {{ $release->released_at->format('M j, Y') }}
                        </time>
                    </div>

                    <p class="mt-1.5 max-w-prose text-sm font-medium text-gray-900 dark:text-white">{{ $release->summary }}</p>

                    @if (! empty($release->notes))
                        <ul class="mt-3 max-w-prose space-y-2">
                            @foreach ($release->notes as $note)
                                <li class="flex flex-wrap items-start gap-2">
                                    <span class="rounded px-2 py-0.5 text-xs font-semibold capitalize {{ $noteStyles[$note['type']] ?? $noteStyles['changed'] }}">
                                        {{ $note['type'] }}
                                    </span>
                                    <span class="min-w-0 flex-1 text-sm text-gray-600 dark:text-gray-300">{{ $note['text'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($release->creator)
                        <p class="mt-3 flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                            <span aria-hidden="true" class="grid size-5 place-items-center rounded-full bg-gray-100 text-[10px] font-semibold text-gray-600 dark:bg-white/10 dark:text-gray-300">
                                {{ \Illuminate\Support\Str::of($release->creator->name)->substr(0, 1)->upper() }}
                            </span>
                            Recorded by {{ $release->creator->name }}
                        </p>
                    @endif
                </div>
            </li>
        @else
            {{-- Branch: an older release, hanging off the spine --}}
            <li @class(['group relative pl-[92px]', 'pb-6' => ! $isTerminal])>
                <span
                    aria-hidden="true"
                    @class([
                        'absolute top-0 left-[23px] border-l-2 border-dashed',
                        $spine,
                        'bottom-0' => ! $isTerminal,
                        'h-[18px]' => $isTerminal,
                    ])
                ></span>

                {{-- Dashed elbow from the spine to the node --}}
                <span aria-hidden="true" class="absolute top-[17px] left-6 w-6 border-t-2 border-dashed {{ $spine }}"></span>

                {{-- Node: the ring colour tells you what kind of release this was --}}
                <span aria-hidden="true" class="absolute top-0 left-12 z-10 grid size-9 place-items-center rounded-full border-2 p-0.5 transition-transform motion-safe:group-hover:scale-110 {{ $kind['ring'] }} {{ $surface }}">
                    <span class="grid size-full place-items-center rounded-full {{ $kind['core'] }}">
                        {!! $icon($kind['icon'], 'size-4') !!}
                    </span>
                </span>

                <div class="min-w-0 rounded-2xl border border-gray-200 {{ $surface }} p-4 shadow-sm transition-colors group-hover:border-gray-300 sm:p-5 dark:border-white/10 dark:group-hover:border-white/20">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 font-mono text-sm font-semibold text-gray-700 dark:bg-white/5 dark:text-gray-300">v{{ $release->version }}</span>
                        <span class="sr-only">{{ $kind['label'] }}</span>

                        <time datetime="{{ $release->released_at->toDateString() }}" class="ml-auto text-xs text-gray-400 dark:text-gray-500">
                            {{ $release->released_at->format('M j, Y') }}
                        </time>
                    </div>

                    <p class="mt-2.5 text-sm font-medium text-gray-900 dark:text-white">{{ $release->summary }}</p>

                    @if (! empty($release->notes))
                        <ul class="mt-3 space-y-2">
                            @foreach ($release->notes as $note)
                                <li class="flex flex-wrap items-start gap-2">
                                    <span class="rounded px-2 py-0.5 text-xs font-semibold capitalize {{ $noteStyles[$note['type']] ?? $noteStyles['changed'] }}">
                                        {{ $note['type'] }}
                                    </span>
                                    <span class="min-w-0 flex-1 text-sm text-gray-600 dark:text-gray-300">{{ $note['text'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($release->creator)
                        <p class="mt-3 flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                            <span aria-hidden="true" class="grid size-5 place-items-center rounded-full bg-gray-100 text-[10px] font-semibold text-gray-600 dark:bg-white/10 dark:text-gray-300">
                                {{ \Illuminate\Support\Str::of($release->creator->name)->substr(0, 1)->upper() }}
                            </span>
                            Recorded by {{ $release->creator->name }}
                        </p>
                    @endif
                </div>
            </li>
        @endif
    @empty
        <li class="rounded-2xl border-2 border-dashed border-gray-100 p-8 text-center text-sm text-gray-500 dark:border-white/5">
            No releases recorded yet.
        </li>
    @endforelse
</ol>