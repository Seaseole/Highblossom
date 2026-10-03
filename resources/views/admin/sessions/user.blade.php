<x-layouts::admin title="{{ __('admin-sessions.title') }} — {{ $account->name }}">
    <div class="mx-auto max-w-6xl space-y-8 py-6 sm:py-10">
        <!-- Header -->
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-gray-900 text-sm font-bold text-white dark:bg-white dark:text-gray-900">
                    @if ($account->avatar_url)
                        <img src="{{ $account->avatar_url }}" alt="Avatar of {{ $account->name }}" class="h-full w-full object-cover" />
                    @else
                        {{ $account->initials() }}
                    @endif
                </div>
                <div class="space-y-1">
                    <h1 class="font-headline text-2xl font-semibold text-gray-900 dark:text-white sm:text-3xl">
                        {{ $account->name }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $account->email }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $activeCount }} {{ __('admin-sessions.status_active') }} ·
                        <a href="{{ route('admin.users.edit', $account) }}" class="transition-opacity hover:opacity-75">{{ __('admin-users.edit_button') }}</a>
                    </p>
                </div>
            </div>

            @if ($activeCount > 0)
                <form method="POST" action="{{ route('admin.sessions.user.revoke', $account) }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full rounded-full border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition-all hover:border-red-300 hover:bg-red-50 hover:text-red-700 dark:border-white/10 dark:text-gray-300 dark:hover:border-red-500/40 dark:hover:bg-red-500/10 dark:hover:text-red-400 md:w-auto"
                    >
                        {{ __('admin-sessions.revoke_all') }}
                    </button>
                </form>
            @endif
        </div>

        @include('admin.sessions.partials.list')
    </div>
</x-layouts::admin>
