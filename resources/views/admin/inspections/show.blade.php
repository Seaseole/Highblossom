<x-layouts::admin title="Appointment Details">
    <div class="mx-auto max-w-5xl space-y-8 py-6 sm:py-10">
        <!-- Header -->
        <div class="space-y-1">
            <a
                href="{{ route('admin.inspections.index') }}"
                class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            >
                ← Back to Appointments
            </a>
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                Appointment #{{ $inspection->id }}
            </h1>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Schedule Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Schedule</h2>
                <dl class="space-y-4">
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Scheduled At</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $inspection->scheduled_at->format('F j, Y g:i A') }}
                        </dd>
                    </div>
                    @if ($inspection->started_at)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Started At</dt>
                            <dd class="text-sm font-medium text-amber-600 dark:text-amber-400">
                                {{ $inspection->started_at->format('F j, Y g:i A') }}
                            </dd>
                        </div>
                    @endif
                    @if ($inspection->ended_at)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Ended At</dt>
                            <dd class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                {{ $inspection->ended_at->format('F j, Y g:i A') }}
                            </dd>
                        </div>
                    @endif
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Location</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $inspection->location }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Type</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ ucfirst($inspection->type) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Assignment Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Assignment</h2>
                <dl class="space-y-4">
                    @if ($inspection->staff)
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Staff</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $inspection->staff->name }}
                            </dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">{{ $inspection->staff->email }}</dd>
                        </div>
                    @else
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Staff</dt>
                            <dd class="text-sm font-medium text-gray-500 italic dark:text-gray-400">Not assigned</dd>
                        </div>
                    @endif
                    @if ($inspection->booking)
                        <div class="space-y-1 border-t border-gray-100 pt-4 dark:border-white/10">
                            <dt class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                Related Booking
                            </dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <a
                                    href="{{ route('admin.bookings.show', $inspection->booking) }}"
                                    class="text-blue-600 hover:underline dark:text-blue-400"
                                >
                                    Booking #{{ $inspection->booking->id }}
                                </a>
                            </dd>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $inspection->booking->client_name }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Update Form Card -->
            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Update Appointment</h2>
                <form action="{{ route('admin.inspections.update', $inspection) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Assign Staff</label>
                        <select
                            name="staff_id"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="">Select Staff</option>
                            @foreach ($staffMembers as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ $inspection->staff_id === $user->id ? 'selected' : '' }}
                                >
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Scheduled At</label>
                        <input
                            type="datetime-local"
                            name="scheduled_at"
                            value="{{ $inspection->scheduled_at->format('Y-m-d\TH:i') }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Started At (On-site Time)</label>
                        <input
                            type="datetime-local"
                            name="started_at"
                            value="{{ $inspection->started_at ? $inspection->started_at->format('Y-m-d\TH:i') : '' }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Leave blank to keep it not started. Recording a start time moves the booking to in progress.
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Ended At (Completion Time)</label>
                        <input
                            type="datetime-local"
                            name="ended_at"
                            value="{{ $inspection->ended_at ? $inspection->ended_at->format('Y-m-d\TH:i') : '' }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Client Summary (Optional)</label>
                        <textarea
                            name="client_summary"
                            rows="3"
                            maxlength="500"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                            placeholder="What the customer should know about the finished appointment."
                        >{{ old('client_summary') }}</textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Goes into the completion email the moment you set an end time. Internal notes are never emailed.
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Location</label>
                        <input
                            type="text"
                            name="location"
                            value="{{ $inspection->location }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        />
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Type</label>
                        <select
                            name="type"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-white/5 dark:focus:ring-[var(--color-admin-accent)]"
                        >
                            <option value="mobile" {{ $inspection->type === 'mobile' ? 'selected' : '' }}>
                                Mobile
                            </option>
                            <option value="workshop" {{ $inspection->type === 'workshop' ? 'selected' : '' }}>
                                Workshop
                            </option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Update Appointment
                    </button>
                </form>

                <!-- Delete Action -->
                <form
                    action="{{ route('admin.inspections.destroy', $inspection) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this appointment?');"
                    class="border-t border-gray-100 pt-4 dark:border-white/10"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                    >
                        Delete Appointment
                    </button>
                </form>
            </div>
        </div>

        <!-- Internal Notes -->
        <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-4 sm:p-6 md:p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Internal Notes</h2>
                <span class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                    Never sent to the client
                </span>
            </div>

            @php
                $outstanding = $inspection->notes->filter(fn ($note) => $note->isOpen())->count();
            @endphp
            @if ($outstanding > 0)
                <p class="text-sm font-medium text-amber-600 dark:text-amber-400">
                    {{ $outstanding === 1 ? '1 outstanding action' : $outstanding.' outstanding actions' }}
                </p>
            @endif

            <form
                action="{{ route('admin.inspections.notes.store', $inspection) }}"
                method="POST"
                class="space-y-3 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5"
            >
                @csrf
                <div class="space-y-1">
                    <label class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Add Note</label>
                    <textarea
                        name="body"
                        rows="3"
                        maxlength="2000"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm transition-all outline-none focus:ring-1 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-[var(--color-admin-accent)]"
                        placeholder="Findings, follow-ups, what the next visit needs to cover."
                    >{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input
                            type="checkbox"
                            name="is_action"
                            value="1"
                            class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900 dark:border-white/20"
                        />
                        Track as an action item
                    </label>
                    <button
                        type="submit"
                        class="rounded-full bg-gray-900 px-5 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >
                        Save Note
                    </button>
                </div>
            </form>

            @if ($inspection->notes->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No notes yet.</p>
            @else
                <ul class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($inspection->notes as $note)
                        <li class="flex flex-col gap-3 py-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 space-y-1">
                                <p class="text-sm whitespace-pre-line text-gray-900 dark:text-white">{{ $note->body }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $note->author?->name ?? 'System' }} · added
                                    <span title="{{ $note->created_at->format('F j, Y g:i A') }}">{{ $note->created_at->diffForHumans() }}</span>
                                    @if ($note->is_action && $note->done_at)
                                        · done {{ $note->done_at->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                @if ($note->is_action)
                                    <span
                                        class="rounded-full px-2.5 py-1 text-xs font-medium {{ $note->isOpen() ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' }}"
                                    >
                                        {{ $note->isOpen() ? 'Action open' : 'Action done' }}
                                    </span>
                                    <form action="{{ route('admin.inspections.notes.done', [$inspection, $note]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="rounded-full border border-gray-200 px-3 py-1 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-white/10 dark:text-gray-300 dark:hover:bg-white/5"
                                        >
                                            {{ $note->isOpen() ? 'Mark done' : 'Reopen' }}
                                        </button>
                                    </form>
                                @endif
                                <form
                                    action="{{ route('admin.inspections.notes.destroy', [$inspection, $note]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this note?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="text-xs font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts::admin>
