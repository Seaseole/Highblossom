<x-layouts::admin title="SEO Static Routes">
    <div class="max-w-5xl mx-auto space-y-8 py-10">
        <!-- Header -->
        <div class="space-y-1">
            <h1 class="text-3xl font-semibold text-gray-900 dark:text-white font-headline">SEO Static Routes</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage SEO meta tags for static routes.</p>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#0A0A0F] rounded-3xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Route</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Meta Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($routes as $route)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $route['route_label'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $route['route_name'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $route['meta_title'] ?: 'Not set' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = $route['no_index'] ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' : ($route['exists'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400');
                                    $statusText = $route['no_index'] ? 'No Index' : ($route['exists'] ? 'Configured' : 'Default');
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $route['priority'] }} <span class="text-xs text-gray-500">({{ $route['changefreq'] }})</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route($route['exists'] ? 'admin.seo.edit' : 'admin.seo.create', $route['exists'] ? $route['id'] : ['route_name' => $route['route_name']]) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:opacity-75 transition-opacity">
                                    {{ $route['exists'] ? 'Edit' : 'Configure' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No static routes configured.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::admin>