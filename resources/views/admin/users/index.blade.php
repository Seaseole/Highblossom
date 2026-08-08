<x-layouts::admin title="{{ __('admin-users.title') }}">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ __('admin-users.title') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">Manage user accounts and permissions.</p>
            </div>

            <div class="flex items-center gap-4">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="{{ __('admin-users.search_placeholder') }}"
                        class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm transition-all outline-none focus:ring-2 focus:ring-gray-900 dark:border-white/10 dark:bg-[#0A0A0F] dark:focus:ring-white"
                    />
                </form>
                <a
                    href="{{ route('admin.users.create') }}"
                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    {{ __('admin-users.create') }}
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-users.user') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-users.email') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-users.roles') }}
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-users.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @foreach ($users as $user)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-900 text-sm font-bold text-white dark:bg-white dark:text-gray-900">
                                        {{ $user->initials() }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($user->roles as $role)
                                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-white/10 dark:text-gray-300">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500 italic dark:text-gray-400">{{ __('admin-users.no_roles') }}</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >
                                    {{ __('admin-users.edit_button') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-layouts::admin>
