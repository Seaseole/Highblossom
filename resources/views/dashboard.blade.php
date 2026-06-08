<x-layouts::admin title="{{ __('admin-dashboard.title') }}">
    <div class="space-y-8">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">{{ __('admin-dashboard.title') }}</h1>
            <p class="text-gray-500 dark:text-gray-400">{{ __('admin-dashboard.welcome') }}</p>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['label' => __('admin-dashboard.bookings'), 'value' => $totalBookings, 'sub' => __('admin-dashboard.total_bookings'), 'icon' => 'calendar'],
                ['label' => __('admin-dashboard.inspections'), 'value' => $pendingInspections, 'sub' => __('admin-dashboard.pending_inspections'), 'icon' => 'check-circle'],
                ['label' => __('admin-dashboard.users'), 'value' => $totalUsers, 'sub' => __('admin-dashboard.active_users'), 'icon' => 'users'],
            ] as $metric)
                <div class="bg-white dark:bg-[#0A0A0F] border border-gray-200 dark:border-white/10 p-6 rounded-3xl shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-900 dark:text-white">
                             <x-ui.icon :name="$metric['icon']" class="w-5 h-5"/>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $metric['label'] }}</span>
                    </div>
                    <h3 class="text-3xl font-semibold text-gray-900 dark:text-white mb-1">{{ $metric['value'] }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $metric['sub'] }}</p>
                </div>
            @endforeach

            <!-- Quote Requests Card -->
            <div class="bg-white dark:bg-[#0A0A0F] border border-gray-200 dark:border-white/10 p-6 rounded-3xl shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-900 dark:text-white">
                        <x-ui.icon name="document" class="w-5 h-5"/>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('admin-dashboard.quotes') }}</span>
                </div>
                <div class="space-y-3">
                    @foreach(['pending' => $pendingQuotes, 'contacted' => $contactedQuotes, 'completed' => $completedQuotes] as $key => $val)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-300">{{ __('admin-dashboard.' . $key) }}</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="bg-white dark:bg-[#0A0A0F] border border-gray-200 dark:border-white/10 p-8 rounded-3xl shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin-dashboard.recent_activity') }}</h2>
                <a href="#" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">
                    {{ __('admin-dashboard.view_all') }}
                </a>
            </div>
            <div class="text-center py-16 border-2 border-dashed border-gray-100 dark:border-white/5 rounded-2xl">
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('admin-dashboard.no_recent_activity') }}</p>
            </div>
        </div>
    </div>
</x-layouts::admin>
