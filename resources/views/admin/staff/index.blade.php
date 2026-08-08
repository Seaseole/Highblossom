<x-layouts::admin title="Staff">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Manage Staff</h1>
                <p class="text-gray-500 dark:text-gray-400">View and manage staff members.</p>
            </div>
            <a
                href="{{ route('admin.staff.create') }}"
                class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
            >
                Add Staff Member
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Photo
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Role
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($staff as $member)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <img
                                    src="{{ $member->photo_url }}"
                                    class="h-12 w-12 rounded-full border border-gray-200 object-cover dark:border-white/10"
                                />
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $member->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $member->role }}</td>
                            <td class="flex items-center justify-end gap-3 px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.staff.edit', $member) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >Edit</a>
                                <form
                                    action="{{ route('admin.staff.destroy', $member) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No staff members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::admin>
