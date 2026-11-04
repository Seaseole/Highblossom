{{--
    The two session lists, in the bench's voice.

    A session is not a value you can mark, so it is not a row on the sheet: it is
    a record the shop can end. The device is the fact, the timestamps are its
    measure, and the only control is the one that signs it out.
--}}
@php
    /* The count that matters here is the count of *other* machines. Saying "2 sessions are signed
       in to your account" above a list where one of those is the screen being read is a sentence
       about nothing; the bulk control under it works on the other one only. */
    $otherSessions = $activeSessions->reject(fn ($session) => $session->isCurrent());
@endphp
<x-sheet.block
    title="Active devices"
    note="{{ match (true) {
        $activeSessions->isEmpty() => 'Nothing is recorded as signed in, not even this screen.',
        $otherSessions->isEmpty() => 'Only this device is signed in.',
        default => $otherSessions->count().' other '.($otherSessions->count() === 1 ? 'device is' : 'devices are').' signed in besides this one.',
    } }}"
>
    @if ($otherSessions->isNotEmpty())
        {{-- An action that ends every other session at once deserves the same ask as the single
             row under it. Both were one click from taking effect, at different scales. --}}
        <div class="hb-repeat-bar hb-repeat-bar--top">
            <form
                action="{{ route('admin.profile.sessions.revoke-others') }}"
                method="POST"
                data-confirm="This signs out {{ $otherSessions->count() }} {{ $otherSessions->count() === 1 ? 'device' : 'devices' }} other than this one. Whatever they are in the middle of stops, and each has to sign in again."
                data-confirm-title="Sign out other devices"
                data-confirm-yes="Sign them out"
                data-confirm-danger="1"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="hb-btn hb-btn--danger">Sign out other devices</button>
            </form>
        </div>
    @endif

    <ul class="hb-list">
        @forelse ($activeSessions as $session)
            @php $isCurrent = $session->isCurrent(); @endphp
            <li class="hb-list__row">
                <span class="hb-list__main">
                    {{-- The state and the machine are one statement. On its own line above the name,
                         the first thing you read was "Active" and the second was the thing that is
                         active, and the pair took three lines where two say the same thing. --}}
                    <span class="hb-device">
                        <span class="hb-device__name">
                            <x-admin.session-status :session="$session" :is-current="$isCurrent" />

                            {{ $session->deviceSummary() }}

                            <span class="hb-device__method">
                                {{ $session->device_type?->label() ?? 'Unknown device' }}
                                @if ($session->last_ip_address)
                                    <span class="hb-sep" aria-hidden="true">·</span>
                                    <span class="hb-mono">{{ $session->last_ip_address }}</span>
                                @endif
                            </span>
                        </span>

                        <span class="hb-list__when">
                            Signed in <span class="hb-num">{{ $session->login_at->diffForHumans() }}</span>
                            <span class="hb-sep" aria-hidden="true">·</span>
                            Last seen <span class="hb-num">{{ $session->last_seen_at->diffForHumans() }}</span>
                            {{-- The separator used to be printed whether or not there was a method to
                                 put after it, so a row whose sign-in was never attributed ended in a
                                 floating dot. --}}
                            @if ($session->login_method?->label())
                                <span class="hb-sep" aria-hidden="true">·</span>
                                {{ $session->login_method->label() }}
                            @endif
                        </span>
                    </span>
                </span>

                @unless ($isCurrent)
                    <span class="hb-list__end">
                        <form
                            action="{{ route('admin.profile.sessions.destroy', $session) }}"
                            method="POST"
                            data-confirm="{{ $session->deviceSummary() }} will be signed out, and whoever is on it will have to sign in again."
                            data-confirm-title="Sign out this device"
                            data-confirm-yes="Sign it out"
                            data-confirm-danger="1"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="hb-btn hb-btn--danger">Sign out</button>
                        </form>
                    </span>
                @endunless
            </li>
        @empty
            <li class="hb-repeat__empty">No active sessions to show.</li>
        @endforelse
    </ul>
</x-sheet.block>

<x-sheet.block
    title="Previously active"
    note="{{ $sessionHistory->isEmpty() ? 'Nothing has signed out of this account yet.' : 'The newest '.$sessionHistory->count().' of the sessions closed in the last 90 days.' }}"
>
    <ul class="hb-list">
        @forelse ($sessionHistory as $session)
            <li class="hb-list__row">
                <span class="hb-list__main">
                    <x-admin.session-device :session="$session" />
                    <span class="hb-list__when">
                        {{-- The plate on the right already carries the reason, in the same words:
                             `session-status` falls through to `end_reason?->label() ?? 'Ended'` for
                             any row that is not live. Printing it on the line as well made every row
                             say the same word twice, once as a fact and once as a badge. --}}
                        @if ($session->ended_at)
                            Closed <span class="hb-num">{{ $session->ended_at->diffForHumans() }}</span>
                        @else
                            Closed
                        @endif
                    </span>
                </span>

                <span class="hb-list__end">
                    <x-admin.session-status :session="$session" />
                </span>
            </li>
        @empty
            <li class="hb-repeat__empty">No closed sessions recorded yet.</li>
        @endforelse
    </ul>
</x-sheet.block>
