<x-layouts::admin title="{{ __('admin-services.title') }}">
    <div class="mx-auto max-w-5xl space-y-8 py-10">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ __('admin-services.title') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Manage the architectural glass and aluminum services showcased on the platform.
                </p>
            </div>

            <a
                href="{{ route('admin.services.create') }}"
                class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
            >
                {{ __('admin-services.create') }}
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-services.image') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-services.title_header') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-services.icon') }}
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-services.status') }}
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            {{ __('admin-services.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($services as $service)
                        <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-6 py-4">
                                @if ($service->image)
                                    <div class="h-16 w-16 overflow-hidden rounded-xl border border-gray-200 dark:border-white/10">
                                        <img
                                            src="{{ $service->image }}"
                                            alt="{{ $service->title }}"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-400 dark:bg-white/5">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $service->title }}
                                </div>
                                <div class="max-w-xs truncate text-sm text-gray-500 dark:text-gray-400">
                                    {{ $service->short_description }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-lg bg-gray-100 px-2 py-1 font-mono text-xs text-gray-600 dark:bg-white/5 dark:text-gray-400">
                                    {{ $service->icon ?? 'no_icon' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $service->is_active ? __('admin-services.active_status') : __('admin-services.inactive_status') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.services.edit', $service) }}"
                                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                >
                                    {{ __('admin-services.edit_button') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                {{ __('admin-services.no_services_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($services->hasPages())
            <div class="mt-4">{{ $services->links() }}</div>
        @endif
    </div>
</x-layouts::admin>
