<x-layouts::admin title="SEO Static Routes">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Static Route SEO Management</h1>
                <p class="mt-2 text-gray-600">Manage SEO metadata for non-content pages like home, services list, gallery, etc.</p>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($routes as $index => $route)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-900">{{ $route['route_label'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $route['route_name'] }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($route['meta_title'])
                                        <div class="text-sm text-gray-900 truncate max-w-xs">{{ $route['meta_title'] }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Not set</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($route['no_index'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            No Index
                                        </span>
                                    @elseif($route['exists'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Configured
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Default
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-sm text-gray-900">{{ $route['priority'] }}</span>
                                    <span class="text-sm text-gray-500">({{ $route['changefreq'] }})</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    @if($route['exists'])
                                        <a href="{{ route('admin.seo.edit', $route['id']) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Edit
                                        </a>
                                    @else
                                        <a href="{{ route('admin.seo.create', ['route_name' => $route['route_name']]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Configure
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    No static routes configured. Check config/seo.php to define routes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::admin>
