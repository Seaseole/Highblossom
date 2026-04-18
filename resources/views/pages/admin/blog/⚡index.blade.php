<?php

declare(strict_types=1);

use App\Domains\Content\Models\Post;
use App\Domains\Content\Actions\Posts\DeletePost;
use App\Domains\Content\Actions\Posts\DuplicatePost;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Blog Posts')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public ?int $category_id = null;
    public string $sort = 'latest';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function deletePost(int $postId): void
    {
        $this->authorize('delete', Post::class);

        $post = Post::findOrFail($postId);
        app(DeletePost::class)->execute($post);

        $this->dispatch('post-deleted');
        Flux::toast('Post deleted successfully.');
    }

    public function duplicatePost(int $postId): void
    {
        $this->authorize('create', Post::class);

        $post = Post::findOrFail($postId);
        app(DuplicatePost::class)->execute($post);

        Flux::toast('Post duplicated successfully.');
    }

    public function getPostsProperty()
    {
        return Cache::flexible("admin.posts.list.{$this->search}.{$this->status}.{$this->category_id}.{$this->sort}." . $this->getPage(), [30, 60], function () {
            return Post::query()
                ->with(['author', 'category'])
                ->when($this->search, function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%');
                })
                ->when($this->status, function ($query) {
                    match ($this->status) {
                        'published' => $query->published(),
                        'draft' => $query->where('is_published', false),
                        'featured' => $query->featured(),
                        default => $query,
                    };
                })
                ->when($this->category_id, function ($query) {
                    $query->where('category_id', $this->category_id);
                })
                ->when($this->sort === 'latest', function ($query) {
                    $query->latest('updated_at');
                })
                ->when($this->sort === 'oldest', function ($query) {
                    $query->oldest('updated_at');
                })
                ->when($this->sort === 'published', function ($query) {
                    $query->latest('published_at');
                })
                ->paginate(15);
        });
    }
}; ?>

<flux:main>
    <section class="w-full">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Blog Posts') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Create and manage blog content') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        <!-- Filters & Actions -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="flex-1">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search posts...') }}"
                    icon="magnifying-glass"
                />
            </div>

            <div class="flex gap-2">
                <flux:select wire:model.live="status" placeholder="{{ __('All Status') }}">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="published">{{ __('Published') }}</option>
                    <option value="draft">{{ __('Draft') }}</option>
                    <option value="featured">{{ __('Featured') }}</option>
                </flux:select>

                <flux:select wire:model.live="sort">
                    <option value="latest">{{ __('Latest Updated') }}</option>
                    <option value="oldest">{{ __('Oldest Updated') }}</option>
                    <option value="published">{{ __('Recently Published') }}</option>
                </flux:select>

                <flux:button
                    href="{{ route('admin.blog.create') }}"
                    variant="primary"
                    icon="plus"
                >
                    {{ __('New Post') }}
                </flux:button>
            </div>
        </div>

        <!-- Posts Table -->
        <div class="relative min-h-[400px] flex-1 rounded-xl border border-zinc-200 dark:border-zinc-700">
            @if($this->posts->isEmpty())
                <div class="p-8 text-center">
                    <flux:heading>{{ __('No posts found') }}</flux:heading>
                    <flux:subheading>{{ __('Create your first blog post to get started.') }}</flux:subheading>
                    <flux:button
                        href="{{ route('admin.blog.create') }}"
                        variant="primary"
                        class="mt-4"
                    >
                        {{ __('Create Post') }}
                    </flux:button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">{{ __('Title') }}</th>
                                <th class="px-6 py-3">{{ __('Author') }}</th>
                                <th class="px-6 py-3">{{ __('Status') }}</th>
                                <th class="px-6 py-3">{{ __('Date') }}</th>
                                <th class="px-6 py-3 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->posts as $post)
                                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($post->featured_image)
                                                <img src="{{ $post->featured_image }}" alt="" class="w-10 h-10 rounded object-cover">
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $post->title }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    /blog/{{ $post->slug }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $post->author?->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($post->is_published && $post->published_at <= now())
                                            <flux:badge variant="success" size="sm">{{ __('Published') }}</flux:badge>
                                        @else
                                            <flux:badge variant="warning" size="sm">{{ __('Draft') }}</flux:badge>
                                        @endif
                                        @if($post->is_featured)
                                            <flux:badge variant="primary" size="sm" class="ml-1">{{ __('Featured') }}</flux:badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            @if($post->is_published)
                                                {{ $post->published_at?->format('M j, Y') }}
                                            @else
                                                {{ $post->updated_at?->format('M j, Y') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <flux:button
                                                href="{{ route('blog.show', $post) }}"
                                                target="_blank"
                                                size="sm"
                                                variant="ghost"
                                                icon="eye"
                                                title="{{ __('View') }}"
                                            />

                                            <flux:button
                                                href="{{ route('admin.blog.edit', $post) }}"
                                                size="sm"
                                                variant="ghost"
                                                icon="pencil"
                                                title="{{ __('Edit') }}"
                                            />

                                            <flux:button
                                                wire:click="duplicatePost({{ $post->id }})"
                                                size="sm"
                                                variant="ghost"
                                                icon="document-duplicate"
                                                title="{{ __('Duplicate') }}"
                                            />

                                            <flux:button
                                                wire:click="deletePost({{ $post->id }})"
                                                wire:confirm="{{ __('Are you sure you want to delete this post?') }}"
                                                size="sm"
                                                variant="ghost"
                                                icon="trash"
                                                title="{{ __('Delete') }}"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $this->posts->links() }}
                </div>
            @endif
        </div>
    </section>
</flux:main>
