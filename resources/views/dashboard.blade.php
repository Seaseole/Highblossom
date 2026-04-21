<x-layouts::admin title="{{ __('admin-dashboard.title') }}">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-[#39393c] mb-2">{{ __('admin-dashboard.title') }}</h1>
        <p class="text-zinc-600">{{ __('admin-dashboard.welcome') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Bookings Card -->
        <div class="bg-white/5 border border-zinc-200/10 p-1.5 rounded-[1.5rem]">
            <div class="bg-white rounded-[calc(1.5rem-0.375rem)] p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-[#dc2626]/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#dc2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('admin-dashboard.bookings') }}</span>
                </div>
                <h3 class="text-3xl font-bold text-[#39393c] mb-1">{{ $totalBookings }}</h3>
                <p class="text-zinc-500 text-sm">{{ __('admin-dashboard.total_bookings') }}</p>
            </div>
        </div>

        <!-- Pending Inspections Card -->
        <div class="bg-white/5 border border-zinc-200/10 p-1.5 rounded-[1.5rem]">
            <div class="bg-white rounded-[calc(1.5rem-0.375rem)] p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-[#dc2626]/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#dc2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('admin-dashboard.inspections') }}</span>
                </div>
                <h3 class="text-3xl font-bold text-[#39393c] mb-1">{{ $pendingInspections }}</h3>
                <p class="text-zinc-500 text-sm">{{ __('admin-dashboard.pending_inspections') }}</p>
            </div>
        </div>

        <!-- Active Users Card -->
        <div class="bg-white/5 border border-zinc-200/10 p-1.5 rounded-[1.5rem]">
            <div class="bg-white rounded-[calc(1.5rem-0.375rem)] p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-[#dc2626]/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#dc2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('admin-dashboard.users') }}</span>
                </div>
                <h3 class="text-3xl font-bold text-[#39393c] mb-1">{{ $totalUsers }}</h3>
                <p class="text-zinc-500 text-sm">{{ __('admin-dashboard.active_users') }}</p>
            </div>
        </div>

        <!-- Quote Requests Card -->
        <div class="bg-white/5 border border-zinc-200/10 p-1.5 rounded-[1.5rem]">
            <div class="bg-white rounded-[calc(1.5rem-0.375rem)] p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-[#dc2626]/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#dc2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('admin-dashboard.quotes') }}</span>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-zinc-600">{{ __('admin-dashboard.pending') }}</span>
                        <span class="text-lg font-semibold text-[#39393c]">{{ $pendingQuotes }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-zinc-600">{{ __('admin-dashboard.contacted') }}</span>
                        <span class="text-lg font-semibold text-[#39393c]">{{ $contactedQuotes }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-zinc-600">{{ __('admin-dashboard.completed') }}</span>
                        <span class="text-lg font-semibold text-[#39393c]">{{ $completedQuotes }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-zinc-600">{{ __('admin-dashboard.cancelled') }}</span>
                        <span class="text-lg font-semibold text-[#39393c]">{{ $cancelledQuotes }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Card - Spans full width -->
    <div class="bg-white/5 border border-zinc-200/10 p-1.5 rounded-[1.5rem]">
        <div class="bg-white rounded-[calc(1.5rem-0.375rem)] p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-[#39393c]">{{ __('admin-dashboard.recent_activity') }}</h2>
                <button class="px-4 py-2 text-sm font-medium text-[#dc2626] hover:bg-[#dc2626]/5 rounded-lg transition-all duration-200">
                    {{ __('admin-dashboard.view_all') }}
                </button>
            </div>
            <div class="text-center py-12">
                <div class="w-16 h-16 rounded-full bg-zinc-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <p class="text-zinc-500">{{ __('admin-dashboard.no_recent_activity') }}</p>
                <p class="text-zinc-400 text-sm mt-1">{{ __('admin-dashboard.activity_will_appear') }}</p>
            </div>
        </div>
    </div>
</x-layouts::admin>
