<x-layouts::admin title="Partners">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Manage Partners</h1>
                <p class="text-gray-500 dark:text-gray-400">View and manage business partners.</p>
            </div>
            <a
                href="{{ route('admin.partners.create') }}"
                class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
            >
                Add Partner
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Logo
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Name
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($partners as $partner)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                <img src="{{ $partner->logo_url }}" class="h-10 w-16 object-contain" />
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $partner->name }}
                            </td>
                            <td class="flex items-center justify-end gap-3 px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.partners.edit', $partner) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >Edit</a>
                                <form
                                    action="{{ route('admin.partners.destroy', $partner) }}"
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
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No partners found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::admin>
