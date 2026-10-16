<div class="mt-4 overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
    <header class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 px-6 py-4 dark:border-white/10">
        <div class="flex items-center gap-2">
            <x-ui.icon name="arrow-path" size="xs" class="animate-spin text-blue-500" />
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.system_overview_packages_loading') }}</span>
        </div>
        <div class="hidden h-9 w-64 animate-pulse rounded-xl bg-gray-100 sm:block dark:bg-white/5"></div>
    </header>

    <div class="divide-y divide-gray-50 dark:divide-white/5">
        @for ($i = 1; $i <= 6; $i++)
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="h-4 w-40 animate-pulse rounded bg-gray-100 dark:bg-white/5"></div>
                <div class="h-4 w-16 animate-pulse rounded bg-gray-100 dark:bg-white/5"></div>
                <div class="ml-auto hidden h-4 w-56 animate-pulse rounded bg-gray-100 lg:block dark:bg-white/5"></div>
            </div>
        @endfor
    </div>
</div>
