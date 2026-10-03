@php
    $noteStyles = [
        'added' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
        'changed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        'fixed' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
        'security' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400',
    ];
    $bumpCaptions = [
        'patch' => 'Bug fixes',
        'minor' => 'Features',
        'major' => 'Breaking',
    ];
    $initialNotes = old('notes', [['type' => 'added', 'text' => '']]);
    $bumpOnLoad = old('bump_choice', 'patch');
    $overrideOnLoad = old('version') !== null && old('version') !== ($nextVersions[$bumpOnLoad] ?? '');
@endphp

<x-layouts::admin title="Versions">
    <div
        class="mx-auto max-w-4xl space-y-8 py-6 sm:py-10"
        x-data="{
            modal: false,
            bump: '{{ $bumpOnLoad }}',
            override: {{ $overrideOnLoad ? 'true' : 'false' }},
            nextVersions: {{ \Illuminate\Support\Js::from($nextVersions) }},
            version: '{{ old('version', $nextVersions['patch']) }}',
            notes: {{ \Illuminate\Support\Js::from($initialNotes) }},
            init() {
                this.modal = {{ $errors->any() ? 'true' : 'false' }};
            },
            open(bump) {
                this.bump = bump;
                this.version = this.nextVersions[bump];
                this.modal = true;
            },
            pickBump(bump) {
                this.bump = bump;
                if (! this.override) {
                    this.version = this.nextVersions[bump];
                }
            },
            stopOverride() {
                if (! this.override) {
                    this.version = this.nextVersions[this.bump];
                }
            },
            addNote() { this.notes.push({ type: 'changed', text: '' }); },
            removeNote(index) { this.notes.splice(index, 1); }
        }"
    >
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Application Versions</h1>
                <p class="text-gray-500 dark:text-gray-400">Semantic release history — <span class="font-mono text-xs">MAJOR.MINOR.PATCH</span>.</p>
            </div>
            <button
                type="button"
                @click="open('patch')"
                class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
            >
                Record Release
            </button>
        </div>

        <!-- Current version hero -->
        <div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 md:p-8 dark:border-white/10 dark:bg-[#0A0A0F]">
            <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Current release</p>

            @if ($current)
                <div class="mt-4 flex flex-wrap items-end gap-x-8 gap-y-4">
                    @foreach (['major' => 'Breaking', 'minor' => 'Feature', 'patch' => 'Fix'] as $segment => $caption)
                        <div class="text-center">
                            <div class="font-mono text-5xl leading-none font-semibold text-gray-900 tabular-nums dark:text-white">
                                {{ $current->{$segment} }}
                            </div>
                            <div class="mt-2 text-[10px] font-bold tracking-widest text-gray-400 uppercase">{{ strtoupper($segment) }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">{{ $caption }}</div>
                        </div>
                        @unless ($loop->last)
                            <div class="pb-8 font-mono text-3xl text-gray-300 dark:text-gray-600">.</div>
                        @endunless
                    @endforeach
                    <span class="mb-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Live</span>
                </div>

                <div class="mt-6 space-y-1 border-t border-gray-100 pt-4 dark:border-white/10">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $current->summary }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Released {{ $current->released_at->format('M j, Y') }}
                        @if ($current->creator) · recorded by {{ $current->creator->name }} @endif
                    </p>
                </div>
            @else
                <p class="mt-4 text-sm text-gray-500">No releases recorded yet.</p>
            @endif
        </div>

        <!-- Bump strip -->
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            @foreach (['patch' => $nextVersions['patch'], 'minor' => $nextVersions['minor'], 'major' => $nextVersions['major']] as $type => $suggested)
                <button
                    type="button"
                    @click="open('{{ $type }}')"
                    class="group flex flex-col items-start gap-1 rounded-2xl border border-gray-200 bg-white p-4 text-left transition-all hover:border-gray-900 active:scale-[0.99] dark:border-white/10 dark:bg-[#0A0A0F] dark:hover:border-white"
                >
                    <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">{{ $bumpCaptions[$type] }}</span>
                    <span class="font-mono text-xl font-semibold text-gray-900 dark:text-white">
                        → {{ $suggested }}
                    </span>
                </button>
            @endforeach
        </div>

        <!-- Release timeline -->
        <div class="space-y-1">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Release history</h2>
                <p class="text-xs text-gray-400">Append-only — releases cannot be edited or deleted.</p>
            </div>

            <ol class="relative mt-4 space-y-6 border-l border-gray-200 pl-6 dark:border-white/10 sm:pl-8">
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
        </div>

        <!-- Record release modal -->
        <div
            x-show="modal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4 backdrop-blur-sm dark:bg-black/60"
            style="display: none"
            @keydown.escape.window="modal = false"
        >
            <div
                x-show="modal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="max-h-[90dvh] w-full max-w-lg overflow-y-auto rounded-3xl border border-gray-200 bg-white p-4 shadow-2xl sm:p-6 dark:border-white/10 dark:bg-[#0A0A0F]"
                role="dialog"
                aria-modal="true"
                aria-label="Record a new release"
            >
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Record Release</h3>
                    <button type="button" @click="modal = false" class="rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Close">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('admin.versions.store') }}" method="POST" class="space-y-5">
                    @csrf

                    @error('version')
                        <p class="rounded-xl bg-red-50 px-4 py-2 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-300">{{ $message }}</p>
                    @enderror
                    @error('summary')
                        <p class="rounded-xl bg-red-50 px-4 py-2 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-300">{{ $message }}</p>
                    @enderror

                    <!-- Bump segmented control -->
                    <div class="space-y-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">What kind of change is this?</span>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (['patch' => 'Fix', 'minor' => 'Feature', 'major' => 'Breaking'] as $type => $caption)
                                <label class="cursor-pointer">
                                    <input type="radio" name="bump_choice" value="{{ $type }}" x-model="bump" @change="pickBump('{{ $type }}')" class="peer sr-only" />
                                    <span class="block rounded-xl border-2 border-gray-100 p-3 text-center transition-all peer-checked:border-gray-900 peer-checked:bg-gray-50 dark:border-white/5 dark:peer-checked:border-white dark:peer-checked:bg-white/5">
                                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $type }}</span>
                                        <span class="block text-[10px] tracking-wide text-gray-400 uppercase">{{ $caption }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Version -->
                    <div class="space-y-2">
                        <label for="version" class="text-sm font-medium text-gray-700 dark:text-gray-300">Version</label>
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                id="version"
                                name="version"
                                x-model="version"
                                :readonly="! override"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 font-mono text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 read-only:cursor-not-allowed read-only:text-gray-500 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                                placeholder="1.2.0"
                                required
                            />
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <input type="checkbox" x-model="override" @change="stopOverride()" class="size-4 rounded border-gray-300" />
                            Override the suggested number
                        </label>
                        <p class="text-[10px] text-gray-400" aria-live="polite">
                            Must be strictly newer than the current release.
                        </p>
                    </div>

                    <!-- Summary -->
                    <div class="space-y-2">
                        <label for="summary" class="text-sm font-medium text-gray-700 dark:text-gray-300">Release summary</label>
                        <input
                            type="text"
                            id="summary"
                            name="summary"
                            value="{{ old('summary') }}"
                            maxlength="255"
                            required
                            placeholder="Mobile admin responsiveness + passkey error handling"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <!-- Released at -->
                    <div class="space-y-2">
                        <label for="released_at" class="text-sm font-medium text-gray-700 dark:text-gray-300">Release date</label>
                        <input
                            type="date"
                            id="released_at"
                            name="released_at"
                            value="{{ old('released_at', now()->toDateString()) }}"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <!-- Notes -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Change notes</span>
                            <button type="button" @click="addNote()" class="text-sm font-medium text-gray-900 hover:underline dark:text-white">+ Add note</button>
                        </div>

                        <template x-for="(note, index) in notes" :key="index">
                            <div class="flex flex-col gap-2 rounded-2xl border border-gray-100 bg-gray-50 p-3 dark:border-white/5 dark:bg-white/5 sm:flex-row sm:items-center">
                                <select :name="`notes[${index}][type]`" x-model="note.type" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-white/10 dark:bg-[#0A0A0F] sm:w-32">
                                    <option value="added">Added</option>
                                    <option value="changed">Changed</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="security">Security</option>
                                </select>
                                <input type="text" :name="`notes[${index}][text]`" x-model="note.text" placeholder="What changed?" maxlength="500" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]" />
                                <button type="button" @click="removeNote(index)" x-show="notes.length > 1" class="self-end rounded-full px-3 py-1.5 text-xs font-medium text-red-500 transition-colors hover:bg-red-50 dark:hover:bg-red-500/10 sm:self-auto">Remove</button>
                            </div>
                        </template>

                        @error('notes.*.text')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Record v<span x-text="version"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>
