@php
    $bumpOnLoad = old('bump_choice', 'patch');
    $noteDefaults = ['patch' => 'fixed', 'minor' => 'added', 'major' => 'changed'];
    $initialNotes = old('notes', [['type' => $noteDefaults[$bumpOnLoad] ?? 'changed', 'text' => '']]);
    $overrideOnLoad = old('version') !== null && old('version') !== ($nextVersions[$bumpOnLoad] ?? '');

    // Plain-language names first, semver term second.
    $bumps = [
        'patch' => ['label' => 'Fix', 'hint' => 'Bug fixes that change nothing for users.'],
        'minor' => ['label' => 'Feature', 'hint' => 'New functionality that keeps existing behaviour working.'],
        'major' => ['label' => 'Breaking change', 'hint' => 'Changes that alter or remove existing behaviour.'],
    ];

    $legend = [
        'bg-emerald-500' => 'New features',
        'bg-blue-500' => 'Changes',
        'bg-amber-500' => 'Fixes',
        'bg-rose-500' => 'Security',
    ];

    $fieldBase = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900/80 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]';
@endphp

<x-layouts::admin title="Versions">
    <div
        class="mx-auto max-w-4xl space-y-8 py-6 sm:py-10"
        @pageshow.window="submitting = false"
        x-data="{
            modal: false,
            submitting: false,
            returnFocus: null,
            bump: '{{ $bumpOnLoad }}',
            override: {{ $overrideOnLoad ? 'true' : 'false' }},
            nextVersions: {{ \Illuminate\Support\Js::from($nextVersions) }},
            currentVersion: '{{ $current?->version }}',
            version: '{{ old('version', $nextVersions['patch']) }}',
            notes: {{ \Illuminate\Support\Js::from($initialNotes) }},
            noteDefaults: {{ \Illuminate\Support\Js::from($noteDefaults) }},
            bumpHints: {{ \Illuminate\Support\Js::from(collect($bumps)->map->hint) }},
            historyPages: {{ $historyPages }},
            historyShown: {{ $releases->count() }},
            historyTotal: {{ $historyTotal }},
            historyHasMore: {{ $historyHasMore ? 'true' : 'false' }},
            historyLoading: false,
            historyError: false,
            init() {
                this.modal = {{ $errors->any() ? 'true' : 'false' }};

                this.$watch('modal', (open) => {
                    document.documentElement.classList.toggle('overflow-hidden', open);

                    if (open) {
                        this.$nextTick(() => this.$refs.summary?.focus());
                    } else if (this.returnFocus) {
                        this.returnFocus.focus();
                        this.returnFocus = null;
                    }
                });

                if (this.modal) {
                    this.$nextTick(() => this.$refs.summary?.focus());
                }
            },
            open(bump) {
                this.returnFocus = document.activeElement;
                this.pickBump(bump);
                this.modal = true;
            },
            pickBump(bump) {
                this.bump = bump;

                if (! this.override) {
                    this.version = this.nextVersions[bump];
                }

                // Keep the starter note's type in step with the kind of change, until the person has typed in it.
                if (this.notes.length === 1 && ! this.notes[0].text.trim()) {
                    this.notes[0].type = this.noteDefaults[bump];
                }
            },
            stopOverride() {
                if (! this.override) {
                    this.version = this.nextVersions[this.bump];
                }
            },
            parse(value) {
                const match = /^(\d+)\.(\d+)\.(\d+)$/.exec(String(value).trim());
                return match ? [Number(match[1]), Number(match[2]), Number(match[3])] : null;
            },
            isNewer(a, b) {
                for (let i = 0; i < 3; i++) {
                    if (a[i] !== b[i]) {
                        return a[i] > b[i];
                    }
                }
                return false;
            },
            get versionError() {
                if (! this.override) {
                    return '';
                }

                const next = this.parse(this.version);

                if (! next) {
                    return 'Use the format MAJOR.MINOR.PATCH, for example 1.2.0.';
                }

                const current = this.parse(this.currentVersion);

                if (current && ! this.isNewer(next, current)) {
                    return `Must be newer than v${this.currentVersion}.`;
                }

                return '';
            },
            addNote() {
                this.notes.push({ type: this.noteDefaults[this.bump], text: '' });

                this.$nextTick(() => {
                    const fields = this.$refs.dialog.querySelectorAll('[data-note-text]');
                    fields[fields.length - 1]?.focus();
                });
            },
            removeNote(index) { this.notes.splice(index, 1); },
            trapTab(event) {
                const focusable = [...this.$refs.dialog.querySelectorAll('a[href], button, input, select, textarea')]
                    .filter((el) => ! el.disabled && el.type !== 'hidden' && el.offsetParent !== null);

                if (! focusable.length) {
                    return;
                }

                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (! event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            },
            async loadMore() {
                if (this.historyLoading) {
                    return;
                }

                this.historyLoading = true;
                this.historyError = false;

                try {
                    const response = await fetch('{{ route('admin.versions.history') }}?pages=' + (this.historyPages + 1), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (! response.ok) {
                        throw new Error('Unexpected response');
                    }

                    const fragment = new DOMParser()
                        .parseFromString(await response.text(), 'text/html')
                        .getElementById('release-timeline');

                    if (! fragment) {
                        throw new Error('Missing timeline fragment');
                    }

                    document.getElementById('release-timeline').replaceWith(fragment);

                    this.historyPages = Number(fragment.dataset.pages);
                    this.historyShown = Number(fragment.dataset.shown);
                    this.historyTotal = Number(fragment.dataset.total);
                    this.historyHasMore = fragment.dataset.hasMore === 'true';

                    history.replaceState(null, '', '?pages=' + this.historyPages);
                } catch (error) {
                    this.historyError = true;
                } finally {
                    this.historyLoading = false;
                }
            }
        }"
    >
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Application versions</h1>
                <p class="text-gray-500 dark:text-gray-400">Semantic release history, numbered <span class="font-mono text-xs">MAJOR.MINOR.PATCH</span>.</p>
            </div>
            <button
                type="button"
                @click="open('patch')"
                class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 dark:focus-visible:outline-white"
            >
                Record release
            </button>
        </div>

        {{-- Confirmation after the store redirect. Adjust the session key to whatever your controller flashes. --}}
        @if (session('status') || session('success'))
            <div role="status" class="rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
                {{ session('status') ?? session('success') }}
            </div>
        @endif

        <!-- Current version hero -->
        <div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 md:p-8 dark:border-white/10 dark:bg-[#0A0A0F]">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Current release</h2>

            @if ($current)
                <p class="sr-only">Version {{ $current->version }}</p>

                <div class="mt-4 flex flex-wrap items-end gap-x-8 gap-y-4" aria-hidden="true">
                    @foreach (['major' => 'Breaking', 'minor' => 'Feature', 'patch' => 'Fix'] as $segment => $caption)
                        <div class="text-center">
                            <div class="font-mono text-5xl leading-none font-semibold text-gray-900 tabular-nums dark:text-white">
                                {{ $current->{$segment} }}
                            </div>
                            <div class="mt-2 text-xs font-medium text-gray-500 capitalize dark:text-gray-400">{{ $segment }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">{{ $caption }}</div>
                        </div>
                        @unless ($loop->last)
                            <div class="pb-8 font-mono text-3xl text-gray-300 dark:text-gray-600">.</div>
                        @endunless
                    @endforeach
                    <span class="mb-1 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                        Live
                    </span>
                </div>

                <div class="mt-6 space-y-1 border-t border-gray-100 pt-4 dark:border-white/10">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $current->summary }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Released {{ $current->released_at->format('M j, Y') }}
                        ({{ $current->released_at->isToday() ? 'today' : $current->released_at->diffForHumans() }})
                        @if ($current->creator), recorded by {{ $current->creator->name }} @endif
                    </p>
                </div>
            @else
                <div class="mt-4 space-y-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nothing has been released yet. Your first entry becomes the baseline for every version after it.</p>
                    <button
                        type="button"
                        @click="open('patch')"
                        class="rounded-full border border-gray-200 px-5 py-2 text-sm font-medium text-gray-900 transition-all hover:border-gray-900 active:scale-[0.98] dark:border-white/10 dark:text-white dark:hover:border-white"
                    >
                        Record the first release
                    </button>
                </div>
            @endif
        </div>

        <!-- Bump strip -->
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            @foreach ($bumps as $type => $bump)
                <button
                    type="button"
                    @click="open('{{ $type }}')"
                    class="group flex flex-col items-start gap-1.5 rounded-2xl border border-gray-200 bg-white p-4 text-left transition-all hover:border-gray-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 active:scale-[0.99] dark:border-white/10 dark:bg-[#0A0A0F] dark:hover:border-white dark:focus-visible:outline-white"
                >
                    <span class="flex w-full items-baseline justify-between gap-3">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $bump['label'] }}</span>
                        <span class="font-mono text-sm text-gray-500 transition-colors group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">v{{ $nextVersions[$type] }}</span>
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $bump['hint'] }}</span>
                </button>
            @endforeach
        </div>

        <!-- Release timeline -->
        <div class="space-y-1">
            <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Release history</h2>
                <p class="text-xs text-gray-400 dark:text-gray-500">Append-only: releases can't be edited or deleted.</p>
            </div>

            {{-- Decodes the node colours in the timeline. --}}
            <ul class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-gray-400" aria-label="Timeline colour key">
                @foreach ($legend as $dot => $label)
                    <li class="flex items-center gap-1.5">
                        <span class="size-2 rounded-full {{ $dot }}" aria-hidden="true"></span>
                        {{ $label }}
                    </li>
                @endforeach
            </ul>

            @include('admin.versions.timeline')

            {{-- Terminal node: sits on the spine and loads the next page. Lives outside the swapped list so Alpine owns its loading state. --}}
            <div
                x-show="historyHasMore"
                @style(['display: none' => ! $historyHasMore])
                class="group relative pl-16"
            >
                <span aria-hidden="true" class="absolute top-0 left-[23px] h-3 border-l-2 border-dashed border-cyan-500/50 dark:border-cyan-400/60"></span>

                <span aria-hidden="true" class="absolute top-0 left-3 z-10 grid size-6 place-items-center rounded-full border-2 border-gray-400 bg-white p-[3px] transition-colors group-hover:border-gray-600 dark:border-gray-500 dark:bg-[#0A0A0F] dark:group-hover:border-gray-300">
                    <span class="size-full rounded-full bg-gray-400 dark:bg-gray-500"></span>
                </span>

                <a
                    href="?pages={{ $historyPages + 1 }}"
                    :href="`?pages=${historyPages + 1}`"
                    @click.prevent="loadMore()"
                    :aria-busy="historyLoading.toString()"
                    :class="historyLoading && 'pointer-events-none cursor-wait opacity-60'"
                    class="-my-2 inline-flex min-h-10 items-center gap-2 rounded-md py-2 text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-cyan-500 dark:text-gray-400 dark:hover:text-white"
                >
                    <svg x-show="historyLoading" style="display: none" class="size-4 motion-safe:animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span x-text="historyLoading ? 'Loading…' : `Show ${Math.min(historyTotal - historyShown, 5)} older releases`">Show older releases</span>
                </a>
            </div>

            <div class="space-y-2 pt-4 pl-16">
                <p class="text-xs text-gray-400 dark:text-gray-500" aria-live="polite">
                    <span x-text="historyHasMore ? `Showing ${historyShown} of ${historyTotal} releases` : `All ${historyTotal} releases shown`">Showing {{ $releases->count() }} of {{ $historyTotal }} releases</span>
                </p>

                <p x-show="historyError" x-cloak role="alert" class="text-xs text-red-600 dark:text-red-400">
                    Couldn't load more releases. <button type="button" @click="loadMore()" class="font-medium underline">Try again</button>
                </p>
            </div>
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
            @keydown.escape.window="modal && (modal = false)"
            @mousedown.self="modal = false"
        >
            <div
                x-ref="dialog"
                x-show="modal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                @keydown.tab="trapTab($event)"
                class="max-h-[90dvh] w-full max-w-lg overflow-y-auto rounded-3xl border border-gray-200 bg-white p-4 shadow-2xl sm:p-6 dark:border-white/10 dark:bg-[#0A0A0F]"
                role="dialog"
                aria-modal="true"
                aria-labelledby="record-release-title"
            >
                <div class="mb-5 flex items-center justify-between">
                    <h3 id="record-release-title" class="text-lg font-semibold text-gray-900 dark:text-white">Record release</h3>
                    <button type="button" @click="modal = false" class="rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Close">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('admin.versions.store') }}" method="POST" class="space-y-5" @submit="submitting = true">
                    @csrf

                    @if ($errors->any())
                        <div role="alert" class="space-y-1 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-300">
                            @foreach (collect(['version', 'summary', 'released_at', 'notes'])->flatMap(fn ($key) => $errors->get($key)) as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Bump segmented control -->
                    <fieldset class="space-y-2">
                        <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">What kind of change is this?</legend>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach ($bumps as $type => $bump)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="bump_choice" value="{{ $type }}" x-model="bump" @change="pickBump('{{ $type }}')" class="peer sr-only" />
                                    <span class="block rounded-xl border-2 border-gray-100 p-3 text-center transition-all peer-checked:border-gray-900 peer-checked:bg-gray-50 peer-focus-visible:ring-2 peer-focus-visible:ring-gray-900 peer-focus-visible:ring-offset-2 dark:border-white/5 dark:peer-checked:border-white dark:peer-checked:bg-white/5 dark:peer-focus-visible:ring-white dark:peer-focus-visible:ring-offset-[#0A0A0F]">
                                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $bump['label'] }}</span>
                                        <span class="block font-mono text-xs text-gray-400" x-text="'v' + nextVersions['{{ $type }}']">v{{ $nextVersions[$type] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="bumpHints[bump]" aria-live="polite"></p>
                    </fieldset>

                    <!-- Version -->
                    <div class="space-y-2">
                        <label for="version" class="text-sm font-medium text-gray-700 dark:text-gray-300">Version</label>
                        <input
                            type="text"
                            id="version"
                            name="version"
                            x-model="version"
                            :readonly="! override"
                            :aria-invalid="versionError ? 'true' : 'false'"
                            aria-describedby="version-hint"
                            inputmode="decimal"
                            autocomplete="off"
                            :class="versionError && 'border-red-400 focus:ring-red-500 dark:border-red-400/60'"
                            class="{{ $fieldBase }} font-mono read-only:cursor-not-allowed read-only:text-gray-500"
                            placeholder="1.2.0"
                            required
                        />
                        <label class="inline-flex cursor-pointer items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <input type="checkbox" x-model="override" @change="stopOverride()" class="size-4 rounded border-gray-300" />
                            Type my own version number
                        </label>
                        <p id="version-hint" class="text-xs" aria-live="polite">
                            <span x-show="versionError" x-text="versionError" class="text-red-600 dark:text-red-400"></span>
                            <span x-show="! versionError" class="text-gray-400">Must be strictly newer than the current release.</span>
                        </p>
                    </div>

                    <!-- Summary -->
                    <div class="space-y-2">
                        <label for="summary" class="text-sm font-medium text-gray-700 dark:text-gray-300">Release summary</label>
                        <input
                            type="text"
                            id="summary"
                            name="summary"
                            x-ref="summary"
                            value="{{ old('summary') }}"
                            maxlength="255"
                            required
                            placeholder="Mobile admin responsiveness + passkey error handling"
                            class="{{ $fieldBase }}"
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
                            class="{{ $fieldBase }}"
                        />
                    </div>

                    <!-- Notes -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Change notes</span>
                            <button type="button" @click="addNote()" class="rounded text-sm font-medium text-gray-900 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 dark:text-white dark:focus-visible:outline-white">+ Add note</button>
                        </div>

                        <template x-for="(note, index) in notes" :key="index">
                            <div class="flex flex-col gap-2 rounded-2xl border border-gray-100 bg-gray-50 p-3 sm:flex-row sm:items-center dark:border-white/5 dark:bg-white/5">
                                <select :name="`notes[${index}][type]`" x-model="note.type" :aria-label="`Type of note ${index + 1}`" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-900/80 sm:w-32 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]">
                                    <option value="added">Added</option>
                                    <option value="changed">Changed</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="security">Security</option>
                                </select>
                                <input
                                    type="text"
                                    data-note-text
                                    :name="`notes[${index}][text]`"
                                    x-model="note.text"
                                    :aria-label="`Note ${index + 1}`"
                                    @keydown.enter.prevent="note.text.trim() && addNote()"
                                    placeholder="What changed?"
                                    maxlength="500"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-gray-900/80 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]"
                                />
                                <button type="button" @click="removeNote(index)" x-show="notes.length > 1" :aria-label="`Remove note ${index + 1}`" class="self-end rounded-full px-3 py-1.5 text-xs font-medium text-red-500 transition-colors hover:bg-red-50 sm:self-auto dark:hover:bg-red-500/10">Remove</button>
                            </div>
                        </template>

                        <p class="text-xs text-gray-400">Press Enter in a note to start the next one.</p>

                        @error('notes.*.text')
                            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('notes.*.type')
                            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sticky so the primary action stays reachable however many notes there are. --}}
                    <div class="sticky bottom-0 -mx-4 -mb-4 space-y-2 border-t border-gray-100 bg-white/95 px-4 pt-3 pb-4 backdrop-blur sm:-mx-6 sm:-mb-6 sm:px-6 sm:pb-6 dark:border-white/10 dark:bg-[#0A0A0F]/95">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Releases are permanent. Once recorded, this one can't be edited or deleted.
                        </p>
                        <button
                            type="submit"
                            :disabled="submitting || versionError"
                            class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 dark:focus-visible:outline-white"
                        >
                            <span x-show="! submitting">Record v<span x-text="version"></span></span>
                            <span x-show="submitting" style="display: none">Recording…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::admin>