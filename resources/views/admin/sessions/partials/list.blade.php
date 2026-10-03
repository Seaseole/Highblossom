@php
    $statuses = ['active' => __('admin-sessions.status_active'), 'ended' => __('admin-sessions.status_ended')];
    $showOwner = $account === null;
@endphp

<!-- Filters -->
<form method="GET" action="{{ $showOwner ? route('admin.sessions.index') : route('admin.sessions.user', $account) }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
    @if ($showOwner)
        <div class="space-y-1 lg:col-span-2">
            <label for="search" class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('admin-sessions.user') }}</label>
            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="{{ __('admin-sessions.search_placeholder') }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-white"
            />
        </div>
    @endif

    <div class="space-y-1">
        <label for="status" class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('admin-sessions.status') }}</label>
        <select id="status" name="status" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-white">
            <option value="">{{ __('admin-sessions.all_statuses') }}</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1">
        <label for="device" class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('admin-sessions.device') }}</label>
        <select id="device" name="device" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-white">
            <option value="">{{ __('admin-sessions.all_devices') }}</option>
            @foreach ($deviceTypes as $type)
                <option value="{{ $type->value }}" @selected(request('device') === $type->value)>{{ $type->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-1">
        <label for="from" class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('admin-sessions.from_label') }}</label>
        <input type="date" id="from" name="from" value="{{ request('from') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-white" />
    </div>

    <div class="space-y-1">
        <label for="to" class="text-xs font-medium text-gray-500 uppercase dark:text-gray-400">{{ __('admin-sessions.to_label') }}</label>
        <input type="date" id="to" name="to" value="{{ request('to') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-white" />
    </div>

    <div class="flex items-end gap-3">
        <button
            type="submit"
            class="w-full rounded-full bg-gray-900 px-5 py-2 text-sm font-medium text-white transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
        >
            {{ __('admin-sessions.filter') }}
        </button>
        @if ($filters !== [])
            <a
                href="{{ $showOwner ? route('admin.sessions.index') : route('admin.sessions.user', $account) }}"
                class="text-sm font-medium text-gray-500 transition-opacity hover:opacity-75 dark:text-gray-400"
            >
                {{ __('admin-sessions.reset') }}
            </a>
        @endif
    </div>
</form>

@error('session')
    <p class="rounded-xl bg-red-50 px-4 py-2 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-300">{{ $message }}</p>
@enderror

<!-- Sessions -->
<div class="overflow-x-auto rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
    <table class="w-full min-w-[920px]">
        <thead>
            <tr class="border-b border-gray-100 dark:border-white/10">
                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">{{ __('admin-sessions.device') }}</th>
                @if ($showOwner)
                    <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">{{ __('admin-sessions.user') }}</th>
                @endif
                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">{{ __('admin-sessions.method') }}</th>
                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">{{ __('admin-sessions.signed_in') }}</th>
                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">{{ __('admin-sessions.last_seen') }}</th>
                <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">{{ __('admin-sessions.status') }}</th>
                <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">{{ __('admin-sessions.actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-white/10">
            @forelse ($sessions as $session)
                @php $isCurrent = $session->isCurrent(); @endphp
                <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="px-6 py-4">
                        <x-admin.session-device :session="$session" />
                    </td>
                    @if ($showOwner)
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.sessions.user', $session->user) }}" class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white">
                                {{ $session->user->name }}
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $session->user->email }}</p>
                        </td>
                    @endif
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $session->login_method?->label() }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $session->login_at->format('M j, Y') }}<span class="block text-xs text-gray-400">{{ $session->login_at->format('H:i') }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                        @if ($session->isActive())
                            {{ $session->last_seen_at->diffForHumans() }}
                        @else
                            {{ $session->ended_at?->diffForHumans() ?? '—' }}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <x-admin.session-status :session="$session" :is-current="$isCurrent" />
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if ($session->isActive() && ! $isCurrent)
                            <form method="POST" action="{{ route('admin.sessions.revoke', $session) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400"
                                >
                                    {{ __('admin-sessions.revoke') }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $showOwner ? 8 : 7 }}" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ $account ? __('admin-sessions.no_user_sessions') : __('admin-sessions.no_sessions_found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $sessions->links() }}</div>
