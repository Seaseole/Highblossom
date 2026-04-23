<x-layouts::admin title="SEO Static Routes">
    <div class="p-6">
        <div class="admin-section-header">
            <h1 class="admin-section-title">Static Route SEO Management</h1>
        </div>

        <div class="admin-table">
            <table class="min-w-full divide-y divide-admin-border-subtle">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Route</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Meta Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-admin-text uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-admin-text uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-border-subtle">
                    @forelse($routes as $index => $route)
                        <tr class="transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="font-medium text-admin-text">{{ $route['route_label'] }}</div>
                                <div class="text-sm text-admin-text-muted">{{ $route['route_name'] }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($route['meta_title'])
                                    <div class="text-sm text-admin-text truncate max-w-xs">{{ $route['meta_title'] }}</div>
                                @else
                                    <span class="text-sm text-admin-text-muted italic">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($route['no_index'])
                                    <span class="admin-badge admin-badge-inactive">
                                        No Index
                                    </span>
                                @elseif($route['exists'])
                                    <span class="admin-badge admin-badge-active">
                                        Configured
                                    </span>
                                @else
                                    <span class="admin-badge" style="background: rgba(113, 113, 122, 0.2); color: #A1A1AA; border: 1px solid rgba(113, 113, 122, 0.3);">
                                        Default
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-admin-text">{{ $route['priority'] }}</span>
                                <span class="text-sm text-admin-text-muted">({{ $route['changefreq'] }})</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($route['exists'])
                                    <a href="{{ route('admin.seo.edit', $route['id']) }}" class="admin-action-btn admin-action-btn-secondary">
                                        Edit
                                    </a>
                                @else
                                    <a href="{{ route('admin.seo.create', ['route_name' => $route['route_name']]) }}" class="admin-action-btn admin-action-btn-secondary">
                                        Configure
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-admin-text-muted">
                                No static routes configured. Check config/seo.php to define routes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::admin>
