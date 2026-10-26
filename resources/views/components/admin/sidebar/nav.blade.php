@props([
    'mobile' => false,
])

<div
    class="admin-nav-scroll flex-1 space-y-2 overflow-y-auto overscroll-contain p-4"
    role="navigation"
    aria-label="Admin navigation"
>
    @forelse (\App\Support\AdminSidebar::render(request()) as $groupData)
        <x-admin.sidebar.group
            :label="$groupData['label']"
            :icon="$groupData['icon']"
            :open="$groupData['open']"
            :has-active="$groupData['hasActive']"
            :mobile="$mobile"
            :urls="collect($groupData['items'])->pluck('url')->all()"
        >
            @foreach ($groupData['items'] as $item)
                <x-admin.sidebar.item
                    :label="$item['label']"
                    :url="$item['url']"
                    :icon="$item['icon']"
                    :child="count($groupData['items']) > 1"
                    :server-active="$item['active']"
                    :badge-count="$item['badge']"
                />
            @endforeach
        </x-admin.sidebar.group>
    @empty
        <p class="px-3 py-6 text-center text-sm text-gray-400 dark:text-gray-500">
            No navigation items available.
        </p>
    @endforelse
</div>
