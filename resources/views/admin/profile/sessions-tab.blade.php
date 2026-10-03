{{-- Active devices --}}
<div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-white/10 dark:bg-[#0A0A0F]">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="space-y-1">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active devices</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                @if ($activeSessions->count() === 1)
                    1 session is signed in to your account.
                @else
                    {{ $activeSessions->count() }} sessions are signed in to your account.
                @endif
            </p>
        </div>

        @if ($activeSessions->reject(fn ($session) => $session->isCurrent())->isNotEmpty())
            <form action="{{ route('admin.profile.sessions.revoke-others') }}" method="POST">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:border-red-300 hover:bg-red-50 hover:text-red-700 dark:border-white/10 dark:text-gray-300 dark:hover:border-red-500/40 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                >
                    Sign out other devices
                </button>
            </form>
        @endif
    </div>

    <ul class="mt-6 space-y-3">
        @forelse ($activeSessions as $session)
            @php $isCurrent = $session->isCurrent(); @endphp
            <li class="rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-white/5 dark:bg-white/5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0 space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-admin.session-status :session="$session" :is-current="$isCurrent" />
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $session->login_method?->label() }}</span>
                        </div>
                        <x-admin.session-device :session="$session" />
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Signed in {{ $session->login_at->diffForHumans() }}
                            · Last seen {{ $session->last_seen_at->diffForHumans() }}
                        </p>
                    </div>

                    @unless ($isCurrent)
                        <form action="{{ route('admin.profile.sessions.destroy', $session) }}" method="POST" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="rounded-full px-4 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10"
                            >
                                Sign out
                            </button>
                        </form>
                    @endunless
                </div>
            </li>
        @empty
            <li class="rounded-2xl border-2 border-dashed border-gray-100 p-8 text-center text-sm text-gray-500 dark:border-white/5">
                No active sessions to show.
            </li>
        @endforelse
    </ul>
</div>

{{-- Previously active devices --}}
<div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-white/10 dark:bg-[#0A0A0F]">
    <div class="space-y-1">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Previously active</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Devices that signed out or were signed out, kept for 90 days.</p>
    </div>

    <ul class="mt-6 space-y-3">
        @forelse ($sessionHistory as $session)
            <li class="flex flex-col gap-2 rounded-2xl border border-gray-100 p-4 dark:border-white/5 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0 space-y-1">
                    <x-admin.session-device :session="$session" />
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $session->end_reason?->label() ?? 'Ended' }}
                        {{ $session->ended_at?->diffForHumans() }}
                    </p>
                </div>
                <x-admin.session-status :session="$session" class="self-start sm:self-auto" />
            </li>
        @empty
            <li class="rounded-2xl border-2 border-dashed border-gray-100 p-8 text-center text-sm text-gray-500 dark:border-white/5">
                No closed sessions recorded yet.
            </li>
        @endforelse
    </ul>
</div>
