<?php

declare(strict_types=1);

use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\PostView;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Post Analytics')] class extends Component
{
    public int $postId;
    public ?Post $post = null;

    public function mount(int $id): void
    {
        $this->postId = $id;
        $this->post = Post::findOrFail($id);
    }

    public function getStatsProperty(): array
    {
        return PostView::statsForPost($this->post);
    }

    public function getViewsByDayProperty(): array
    {
        return PostView::where('post_id', $this->postId)
            ->where('viewed_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}; ?>

<flux:main>
    <section class="w-full">
        <div class="relative mb-6 w-full">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ route('admin.blog.edit', $post) }}" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <flux:heading size="xl" level="1">{{ __('Post Analytics') }}</flux:heading>
                    </div>
                    <flux:subheading size="lg">{{ $post->title }}</flux:subheading>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $this->stats['total'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Views') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $this->stats['today'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Today') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $this->stats['week'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('This Week') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $this->stats['month'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('This Month') }}</div>
            </div>
        </div>

        <!-- Daily Views Chart (Simple Bar Chart) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 mb-8">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('Views Last 30 Days') }}</h3>

            @if(empty($this->viewsByDay))
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No views recorded yet.') }}</p>
            @else
                <div class="space-y-2">
                    @foreach($this->viewsByDay as $day)
                        @php($maxViews = max(array_column($this->viewsByDay, 'views')))
                        @php($percentage = $maxViews > 0 ? ($day['views'] / $maxViews) * 100 : 0)
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($day['date'])->format('M j') }}
                            </div>
                            <div class="flex-1 h-8 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden">
                                <div
                                    class="h-full bg-indigo-500 transition-all"
                                    style="width: {{ $percentage }}%"
                                ></div>
                            </div>
                            <div class="w-12 text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $day['views'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Post Info -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('Post Details') }}</h3>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Published:') }}</span>
                    <span class="ml-2">{{ $post->published_at?->format('M j, Y g:i a') ?? 'Not published' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Author:') }}</span>
                    <span class="ml-2">{{ $post->author?->name ?? 'Unknown' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Category:') }}</span>
                    <span class="ml-2">{{ $post->category?->name ?? 'Uncategorized' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">{{ __('Reading Time:') }}</span>
                    <span class="ml-2">{{ $post->reading_time_minutes ?? 0 }} min</span>
                </div>
            </div>
        </div>
    </section>
</flux:main>
