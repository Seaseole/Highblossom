<x-layouts::admin title="{{ __('admin-users.title') }}">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">{{ __('admin-users.title') }}</h1>
                <p class="text-gray-500 dark:text-gray-400">Manage user accounts and permissions.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin-users.search_placeholder') }}" class="bg-white dark:bg-[#0A0A0F] border border-gray-200 dark:border-white/10 rounded-full px-4 py-2 text-sm outline-none transition-all focus:ring-2 focus:ring-gray-900 dark:focus:ring-white">
                </form>
                <a href="{{ route('admin.users.create') }}" class="bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium py-2.5 px-6 rounded-full text-sm transition-all shadow-sm active:scale-[0.98]">
                    {{ __('admin-users.create') }}
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin-users.user') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin-users.email') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin-users.roles') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin-users.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-gray-900 dark:bg-white flex items-center justify-center text-white dark:text-gray-900 font-bold text-sm">
                                        {{ $user->initials() }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-gray-300">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('admin-users.no_roles') }}</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">
                                    {{ __('admin-users.edit_button') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-layouts::admin>
