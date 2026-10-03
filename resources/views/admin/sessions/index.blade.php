<x-layouts::admin title="{{ __('admin-sessions.title') }}">
    <div class="mx-auto max-w-6xl space-y-8 py-6 sm:py-10">
        <!-- Header -->
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ __('admin-sessions.heading') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Every session recorded across the application, active and closed.
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white px-5 py-3 text-sm shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <span class="font-semibold text-gray-900 dark:text-white">{{ $activeCount }}</span>
                <span class="text-gray-500 dark:text-gray-400">{{ __('admin-sessions.status_active') }}</span>
            </div>
        </div>

        @include('admin.sessions.partials.list')
    </div>
</x-layouts::admin>
