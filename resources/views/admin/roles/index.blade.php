<x-layouts::admin title="Roles">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Roles & Permissions</h1>
                <p class="text-gray-500 dark:text-gray-400">Manage user roles and their associated permissions.</p>
            </div>
            <a
                href="{{ route('admin.roles.create') }}"
                class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
            >
                Create Role
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Permissions
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @foreach ($roles as $role)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($role->permissions as $permission)
                                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-white/10 dark:text-gray-300">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="flex items-center justify-end gap-3 px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.roles.edit', $role) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >Edit</a>
                                @if ($role->name !== 'Super Admin')
                                    <form
                                        action="{{ route('admin.roles.destroy', $role) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this role?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::admin>
