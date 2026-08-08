<x-layouts::admin title="{{ __('admin-dashboard.title') }}">
    <div class="space-y-8">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">
                {{ __('admin-dashboard.title') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400">{{ __('admin-dashboard.welcome') }}</p>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['label' => __('admin-dashboard.bookings'), 'value' => $totalBookings, 'sub' => __('admin-dashboard.total_bookings'), 'icon' => 'calendar'],
                ['label' => __('admin-dashboard.inspections'), 'value' => $pendingInspections, 'sub' => __('admin-dashboard.pending_inspections'), 'icon' => 'check-circle'],
                ['label' => __('admin-dashboard.users'), 'value' => $totalUsers, 'sub' => __('admin-dashboard.active_users'), 'icon' => 'users'],
            ] as $metric)
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                    <div class="mb-4 flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-900 dark:bg-white/5 dark:text-white">
                            <x-ui.icon :name="$metric['icon']" class="h-5 w-5" />
                        </div>
                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">{{ $metric['label'] }}</span>
                    </div>
                    <h3 class="mb-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $metric['value'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $metric['sub'] }}</p>
                </div>
            @endforeach

            <!-- Quote Requests Card -->
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <div class="mb-4 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-900 dark:bg-white/5 dark:text-white">
                        <x-ui.icon name="document" class="h-5 w-5" />
                    </div>
                    <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">{{ __('admin-dashboard.quotes') }}</span>
                </div>
                <div class="space-y-3">
                    @foreach (['pending' => $pendingQuotes, 'contacted' => $contactedQuotes, 'completed' => $completedQuotes] as $key => $val)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-300">{{ __('admin-dashboard.' . $key) }}</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('admin-dashboard.recent_activity') }}
                </h2>
                <a
                    href="#"
                    class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                >
                    {{ __('admin-dashboard.view_all') }}
                </a>
            </div>
            <div class="rounded-2xl border-2 border-dashed border-gray-100 py-16 text-center dark:border-white/5">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin-dashboard.no_recent_activity') }}</p>
            </div>
        </div>
    </div>
</x-layouts::admin>
