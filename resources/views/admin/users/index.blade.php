<x-layouts::admin title="{{ __('admin-users.title') }}">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">{{ __('admin-users.title') }}</h1>
            <div class="flex items-center gap-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="w-64">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="{{ __('admin-users.search_placeholder') }}"
                        class="admin-form-input"
                    >
                </form>
                <a href="{{ route('admin.users.create') }}" class="admin-action-btn admin-action-btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('admin-users.create') }}</span>
                </a>
            </div>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-admin-border-subtle">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-users.user') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-users.email') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-users.roles') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-admin-text uppercase tracking-wider">{{ __('admin-users.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @foreach($users as $user)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-[#DC2626] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-[#DC2626]/20">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-admin-text">{{ $user->name }}</div>
                                        <div class="text-sm text-admin-text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-admin-text-muted">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="admin-badge admin-badge-role">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-admin-text-muted italic">{{ __('admin-users.no_roles') }}</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user) }}" class="admin-action-btn admin-action-btn-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('admin-users.edit_button') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</x-layouts::admin>
