<x-layouts::admin title="Blog Posts">
    @php
        $canUpdate = auth()->user()->can('update blog');

        // Shared by the row menu and the bulk bar so both offer the same transitions.
        $statusActions = [
            'published' => [
                'label' => 'Publish',
                'class' => 'hover:bg-emerald-50 hover:text-emerald-700 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-400',
            ],
            'draft' => [
                'label' => 'Move to draft',
                'class' => 'hover:bg-gray-100 dark:hover:bg-white/5',
            ],
            'archived' => [
                'label' => 'Archive',
                'class' => 'hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-500/10 dark:hover:text-amber-400',
            ],
        ];

        // Mirrors postsTable.pillClass() so the read-only badge matches the live pill.
        $pillStyles = [
            'published' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'archived' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
            'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
        ];
    @endphp

    <div class="mx-auto max-w-5xl space-y-8 py-6 sm:py-10">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="space-y-1">
                <h1 class="font-headline text-3xl font-semibold text-gray-900 dark:text-white">Blog Posts</h1>
                <p class="text-gray-500 dark:text-gray-400">Manage your blog content. Right-click a row, or click its status, to publish.</p>
            </div>
            @can('create blog')
                <a
                    href="{{ route('admin.posts.create') }}"
                    class="rounded-full bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-[0.98] dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                >
                    Create Post
                </a>
            @endcan
        </div>

        @if ($authorFilter)
            @php $filteredAuthor = \App\Models\User::find($authorFilter); @endphp
            <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F] dark:text-gray-300">
                <span>
                    Filtered by author: <span class="font-medium text-gray-900 dark:text-white">{{ $filteredAuthor?->name ?? 'User #' . $authorFilter }}</span>
                </span>
                <a href="{{ route('admin.posts.index') }}" class="font-medium text-gray-500 underline-offset-2 hover:underline dark:text-gray-400">Clear filter</a>
            </div>
        @endif

        <div x-data="postsTable('{{ route('admin.posts.bulk-status') }}')">
            <!-- Bulk action bar -->
            <div
                x-show="selected.length"
                x-cloak
                x-transition:enter="transition duration-300 ease-out"
                x-transition:enter-start="-translate-y-1 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]"
                role="region"
                aria-label="Bulk actions"
            >
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    <span x-text="selected.length"></span> selected
                </p>

                <div class="flex items-center gap-2">
                    @foreach ($statusActions as $value => $action)
                        <button
                            type="button"
                            x-on:click="bulkSetStatus('{{ $value }}')"
                            :disabled="busy"
                            class="rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:border-gray-900 disabled:opacity-50 dark:border-white/10 dark:text-gray-300 dark:hover:border-white"
                        >
                            {{ $action['label'] }}
                        </button>
                    @endforeach

                    <button
                        type="button"
                        x-on:click="selected = []"
                        class="px-2 py-2 text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        Clear
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0A0A0F]">
                <table class="w-full min-w-[900px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/10">
                            @if ($canUpdate)
                                <th class="w-10 px-6 py-4">
                                    <span class="sr-only">Select all</span>
                                    <input
                                        type="checkbox"
                                        x-on:change="toggleAll($event)"
                                        :checked="allSelected"
                                        class="h-4 w-4 cursor-pointer rounded border-gray-300 accent-gray-900 dark:border-white/20"
                                    />
                                </th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                Title
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                Published by
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                Published
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                        @forelse ($posts as $post)
                            <tr
                                data-post-row
                                data-post-id="{{ $post->id }}"
                                data-status="{{ $post->isScheduled() ? 'scheduled' : $post->status }}"
                                @if ($canUpdate)
                                    x-on:contextmenu.prevent="openMenu($event, {{ $post->id }}, '{{ route('admin.posts.status', $post) }}')"
                                @endif
                                class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-white/5"
                            >
                                @if ($canUpdate)
                                    <td class="w-10 px-6 py-4">
                                        <span class="sr-only">Select {{ $post->title }}</span>
                                        <input
                                            type="checkbox"
                                            value="{{ $post->id }}"
                                            x-model="selected"
                                            class="h-4 w-4 cursor-pointer rounded border-gray-300 accent-gray-900 dark:border-white/20"
                                        />
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->title }}</div>
                                    <div class="font-mono text-xs text-gray-500 dark:text-gray-400">{{ $post->slug }}</div>

                                    @if ($post->categories->isNotEmpty() || $post->tags->isNotEmpty())
                                        <div class="mt-2 flex flex-wrap gap-1.5">
                                            @foreach ($post->categories as $category)
                                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                            @foreach ($post->tags as $tag)
                                                <span class="rounded-full border border-gray-200 px-2 py-0.5 text-[11px] text-gray-500 dark:border-white/10 dark:text-gray-400">
                                                    #{{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($canUpdate)
                                        <button
                                            type="button"
                                            data-status-trigger
                                            x-on:click="openMenu($event, {{ $post->id }}, '{{ route('admin.posts.status', $post) }}')"
                                            class="rounded-full px-2.5 py-0.5 text-xs font-medium transition-opacity hover:opacity-80"
                                            :class="pillClass('{{ $post->id }}')"
                                            x-text="label('{{ $post->id }}')"
                                        ></button>
                                    @else
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $pillStyles[$post->isScheduled() ? 'scheduled' : $post->status] }}">
                                            {{ $post->isScheduled() ? 'Scheduled' : ucfirst($post->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($post->author)
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-700 dark:bg-white/10 dark:text-gray-200">
                                                {{ $post->author->initials() }}
                                            </span>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $post->author->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-600">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $post->published_at?->format('M j, Y') ?? '-' }}
                                </td>
                                <td class="flex items-center justify-end gap-3 px-6 py-4 text-right">
                                    @can('update blog')
                                        <a
                                            href="{{ route('admin.posts.edit', $post) }}"
                                            class="text-sm font-medium text-gray-900 transition-opacity hover:opacity-75 dark:text-white"
                                        >Edit</a>
                                    @endcan
                                    @can('delete blog')
                                        <form
                                            action="{{ route('admin.posts.destroy', $post) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this post?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-sm font-medium text-red-600 transition-opacity hover:opacity-75 dark:text-red-400">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canUpdate ? 6 : 5 }}" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No posts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($canUpdate)
                <!-- Row status menu, positioned at the click because the table clips overflow -->
                <div
                    x-show="menu.open"
                    x-cloak
                    data-status-menu
                    x-transition:enter="transition duration-150 ease-out"
                    x-transition:enter-start="scale-95 opacity-0"
                    x-transition:enter-end="scale-100 opacity-100"
                    :style="'left:' + menu.x + 'px; top:' + menu.y + 'px'"
                    class="fixed z-50 w-48 rounded-2xl border border-gray-200 bg-white p-1.5 shadow-xl dark:border-white/10 dark:bg-[#14141C]"
                    role="menu"
                >
                    <p class="px-3 py-2 text-[10px] font-semibold tracking-wider text-gray-400 uppercase">Set status</p>

                    @foreach ($statusActions as $value => $action)
                        <template x-if="offers('{{ $value }}')">
                            <button
                                type="button"
                                role="menuitem"
                                x-on:click="setStatus('{{ $value }}')"
                                class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm text-gray-700 transition-colors {{ $action['class'] }} dark:text-gray-300"
                            >
                                {{ $action['label'] }}
                            </button>
                        </template>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-4">{{ $posts->links() }}</div>
    </div>
</x-layouts::admin>
