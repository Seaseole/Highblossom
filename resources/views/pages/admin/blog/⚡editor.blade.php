<?php

declare(strict_types=1);

use App\Domains\Content\Actions\Blocks\AddBlock;
use App\Domains\Content\Actions\Blocks\DuplicateBlock;
use App\Domains\Content\Actions\Blocks\RemoveBlock;
use App\Domains\Content\Actions\Blocks\ReorderBlocks;
use App\Domains\Content\Actions\Blocks\UpdateBlock;
use App\Domains\Content\Actions\Posts\CreateRevision;
use App\Domains\Content\Actions\Posts\PublishPost;
use App\Domains\Content\Actions\Posts\SyncTags;
use App\Domains\Content\Actions\Posts\UpdatePost;
use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Tag;
use App\Domains\Content\Services\BlockRegistry;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Edit Post')] class extends Component
{
    public int $postId;
    public ?Post $post = null;

    // Post fields
    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public ?int $categoryId = null;
    public array $selectedTags = [];
    public ?string $publishedAt = null;
    public bool $isPublished = false;
    public bool $isFeatured = false;

    // Block management
    public array $blocks = [];
    public ?int $editingBlockId = null;
    public ?string $addingBlockType = null;
    public array $blockFormData = [];

    // Block reordering
    public array $blockOrder = [];

    // Available blocks from registry
    public function getBlockRegistryProperty(): BlockRegistry
    {
        return app(BlockRegistry::class);
    }

    public function mount(int $id): void
    {
        $this->postId = $id;
        $this->loadPost();
    }

    public function loadPost(): void
    {
        $this->post = Post::with(['category', 'tags', 'contentBlocks'])->findOrFail($this->postId);

        $this->title = $this->post->title;
        $this->slug = $this->post->slug;
        $this->excerpt = $this->post->excerpt ?? '';
        $this->categoryId = $this->post->category_id;
        $this->selectedTags = $this->post->tags->pluck('slug')->toArray();
        $this->publishedAt = $this->post->published_at?->format('Y-m-d\TH:i');
        $this->isPublished = $this->post->is_published;
        $this->isFeatured = $this->post->is_featured;

        // Load blocks with their data
        $this->blocks = $this->post->contentBlocks->map(fn ($block) => [
            'id' => $block->id,
            'type' => $block->type,
            'content' => $block->content,
            'is_visible' => $block->is_visible,
            'sort_order' => $block->sort_order,
        ])->toArray();

        $this->blockOrder = collect($this->blocks)->pluck('id')->toArray();
    }

    public function getCategoriesProperty()
    {
        return Category::forPosts()->orderBy('name')->get();
    }

    public function getTagsProperty()
    {
        return Tag::orderBy('name')->get();
    }

    public function updatedTitle(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function savePost(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'categoryId' => 'nullable|exists:categories,id',
        ]);

        // Create revision before saving
        $createRevision = new CreateRevision();
        $createRevision->execute($this->post, auth()->user(), 'Auto-saved before update');

        $action = new UpdatePost();
        $this->post = $action->execute($this->post, [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'category_id' => $this->categoryId,
            'seo_metadata' => $this->post->seo_metadata,
        ]);

        // Sync tags
        $tagIds = Tag::whereIn('slug', $this->selectedTags)->pluck('id')->toArray();
        $this->post->tags()->sync($tagIds);

        Flux::toast('Post updated successfully. Revision saved.');
    }

    public function publish(): void
    {
        $action = new PublishPost();
        $this->post = $action->execute($this->post);
        $this->isPublished = true;
        $this->publishedAt = now()->format('Y-m-d\TH:i');

        Flux::toast('Post published successfully.');
    }

    public function addBlock(string $type): void
    {
        $blockType = $this->blockRegistry->find($type);
        if (! $blockType) {
            Flux::toast('Invalid block type.', 'error');
            return;
        }

        $action = new AddBlock();
        $block = $action->execute($this->post, [
            'type' => $type,
            'content' => $blockType::defaultData(),
            'sort_order' => count($this->blocks),
        ]);

        $this->blocks[] = [
            'id' => $block->id,
            'type' => $block->type,
            'content' => $block->content,
            'is_visible' => true,
            'sort_order' => $block->sort_order,
        ];

        $this->blockOrder[] = $block->id;
        $this->addingBlockType = null;

        // Auto-open the new block for editing
        $this->editBlock($block->id);

        Flux::toast('Block added.');
    }

    public function editBlock(int $blockId): void
    {
        $this->editingBlockId = $blockId;
        $block = collect($this->blocks)->firstWhere('id', $blockId);
        if ($block) {
            $this->blockFormData = $block['content'];
        }
    }

    public function saveBlock(): void
    {
        if (! $this->editingBlockId) {
            return;
        }

        $block = collect($this->blocks)->firstWhere('id', $this->editingBlockId);
        if (! $block) {
            return;
        }

        $blockType = $this->blockRegistry->find($block['type']);
        if (! $blockType) {
            return;
        }

        // Validate block data
        $validated = $this->validate([
            'blockFormData' => 'array',
        ]);

        $action = new UpdateBlock();
        $updatedBlock = $action->execute(
            $this->post->contentBlocks()->find($this->editingBlockId),
            $this->blockFormData
        );

        // Update local state
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $this->editingBlockId);
        if ($index !== false) {
            $this->blocks[$index]['content'] = $updatedBlock->content;
        }

        $this->editingBlockId = null;
        $this->blockFormData = [];

        Flux::toast('Block updated.');
    }

    public function cancelEditBlock(): void
    {
        $this->editingBlockId = null;
        $this->blockFormData = [];
    }

    public function removeBlock(int $blockId): void
    {
        $block = $this->post->contentBlocks()->find($blockId);
        if (! $block) {
            return;
        }

        $action = new RemoveBlock();
        $action->execute($block);

        $this->blocks = collect($this->blocks)->filter(fn ($b) => $b['id'] !== $blockId)->values()->toArray();
        $this->blockOrder = collect($this->blockOrder)->filter(fn ($id) => $id !== $blockId)->values()->toArray();

        // Re-sort remaining blocks
        $this->reorderBlocksInternal();

        Flux::toast('Block removed.');
    }

    public function duplicateBlock(int $blockId): void
    {
        $block = $this->post->contentBlocks()->find($blockId);
        if (! $block) {
            return;
        }

        $action = new DuplicateBlock();
        $newBlock = $action->execute($block);

        // Insert after the original block
        $originalIndex = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        $newBlockData = [
            'id' => $newBlock->id,
            'type' => $newBlock->type,
            'content' => $newBlock->content,
            'is_visible' => true,
            'sort_order' => $newBlock->sort_order,
        ];

        array_splice($this->blocks, $originalIndex + 1, 0, [$newBlockData]);
        $this->reorderBlocksInternal();

        Flux::toast('Block duplicated.');
    }

    public function toggleBlockVisibility(int $blockId): void
    {
        $index = collect($this->blocks)->search(fn ($b) => $b['id'] === $blockId);
        if ($index === false) {
            return;
        }

        $this->blocks[$index]['is_visible'] = ! $this->blocks[$index]['is_visible'];

        // Update in database
        $block = $this->post->contentBlocks()->find($blockId);
        if ($block) {
            $block->update(['is_visible' => $this->blocks[$index]['is_visible']]);
        }
    }

    public function updateBlockOrder(array $orderedIds): void
    {
        $this->blockOrder = $orderedIds;
        $this->reorderBlocksInternal();

        // Update database order
        $action = new ReorderBlocks();
        $action->execute($this->post, $orderedIds);

        Flux::toast('Blocks reordered.');
    }

    private function reorderBlocksInternal(): void
    {
        // Re-sort blocks array based on blockOrder
        $ordered = [];
        foreach ($this->blockOrder as $id) {
            $block = collect($this->blocks)->firstWhere('id', $id);
            if ($block) {
                $block['sort_order'] = count($ordered);
                $ordered[] = $block;
            }
        }
        $this->blocks = $ordered;
    }

    public function getBlockTypeName(string $type): string
    {
        $blockType = $this->blockRegistry->find($type);
        return $blockType ? $blockType::name() : $type;
    }

    public function getBlockTypeIcon(string $type): string
    {
        $blockType = $this->blockRegistry->find($type);
        return $blockType ? $blockType::icon() : 'square';
    }

    public function renderBlockPreview(array $block): string
    {
        $blockType = $this->blockRegistry->find($block['type']);
        if (! $blockType) {
            return 'Unknown block';
        }

        // Return a simple preview based on block type
        return match($block['type']) {
            'rich-text' => Str::limit(strip_tags($block['content']['content'] ?? ''), 100),
            'hero' => 'Hero: ' . ($block['content']['heading'] ?? 'No heading'),
            'image' => 'Image: ' . ($block['content']['alt'] ?? 'No caption'),
            'quote' => 'Quote: ' . Str::limit($block['content']['quote'] ?? '', 50),
            'spacer' => 'Spacer (' . ($block['content']['height'] ?? 'medium') . ')',
            'video' => 'Video: ' . Str::limit($block['content']['url'] ?? '', 40),
            'cta' => 'CTA: ' . ($block['content']['heading'] ?? 'No heading'),
            'features' => 'Features: ' . ($block['content']['heading'] ?? 'No heading'),
            default => $blockType::name(),
        };
    }
}; ?>

<flux:main>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: true }">
        <!-- Top Bar -->
        <div class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.blog.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <flux:heading size="lg">{{ __('Edit Post') }}</flux:heading>
                    @if($isPublished)
                        <flux:badge variant="success" size="sm">{{ __('Published') }}</flux:badge>
                    @else
                        <flux:badge variant="warning" size="sm">{{ __('Draft') }}</flux:badge>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <flux:button
                        wire:click="savePost"
                        variant="ghost"
                        icon="check"
                    >
                        {{ __('Save') }}
                    </flux:button>

                    @if(! $isPublished)
                        <flux:button
                            wire:click="publish"
                            variant="primary"
                            icon="check-circle"
                        >
                            {{ __('Publish') }}
                        </flux:button>
                    @endif

                    <a href="{{ route('admin.blog.revisions', $post) }}" class="ml-2">
                        <flux:button variant="ghost" icon="clock">
                            {{ __('Revisions') }}
                        </flux:button>
                    </a>

                    <a href="{{ route('admin.blog.analytics', $post) }}" class="ml-2">
                        <flux:button variant="ghost" icon="chart-bar">
                            {{ __('Analytics') }}
                        </flux:button>
                    </a>

                    <a href="{{ route('blog.show', $post) }}" target="_blank" class="ml-2">
                        <flux:button variant="ghost" icon="eye">
                            {{ __('Preview') }}
                        </flux:button>
                    </a>
                </div>
            </div>
        </div>

        <div class="flex">
            <!-- Left Sidebar: Post Settings -->
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-[calc(100vh-4rem)] p-4 space-y-6"
            >
                <!-- Post Details -->
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">{{ __('Post Details') }}</h3>
                    <div class="space-y-3">
                        <flux:input wire:model="title" label="{{ __('Title') }}" required />
                        <flux:input wire:model="slug" label="{{ __('Slug') }}" />
                        <flux:textarea wire:model="excerpt" label="{{ __('Excerpt') }}" rows="3" />
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">{{ __('Category') }}</h3>
                    <flux:select wire:model="categoryId">
                        <option value="">{{ __('No Category') }}</option>
                        @foreach($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Tags -->
                <div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">{{ __('Tags') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($this->tags as $tag)
                            <label class="inline-flex items-center px-3 py-1 rounded-full text-xs cursor-pointer transition-colors {{ in_array($tag->slug, $selectedTags) ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                                <input
                                    type="checkbox"
                                    wire:model="selectedTags"
                                    value="{{ $tag->slug }}"
                                    class="sr-only"
                                >
                                {{ $tag->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Featured -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="isFeatured"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Featured Post') }}</span>
                    </label>
                </div>

                <!-- Toggle Sidebar Button -->
                <button
                    @click="sidebarOpen = false"
                    class="w-full py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    {{ __('Hide Settings') }} →
                </button>
            </div>

            <!-- Main Content: Visual Editor -->
            <div class="flex-1 p-4">
                <!-- Show Sidebar Toggle when hidden -->
                <div x-show="!sidebarOpen" class="mb-4">
                    <button
                        @click="sidebarOpen = true"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        ← {{ __('Show Settings') }}
                    </button>
                </div>

                <!-- Blocks Canvas -->
                <div
                    x-data="{ 
                        dragEnabled: true,
                        initSortable() {
                            const el = this.$refs.blocksContainer;
                            if (!el) return;
                            
                            new Sortable(el, {
                                animation: 200,
                                handle: '.drag-handle',
                                ghostClass: 'sortable-ghost',
                                chosenClass: 'sortable-chosen',
                                onEnd: (evt) => {
                                    const newOrder = Array.from(el.children).map(child => parseInt(child.dataset.blockId));
                                    $wire.updateBlockOrder(newOrder);
                                }
                            });
                        }
                    }"
                    x-init="initSortable()"
                    class="space-y-4"
                >
                    <!-- Empty State -->
                    @if(empty($blocks))
                        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('Start building your post') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('Add blocks from the sidebar to create your content.') }}</p>
                        </div>
                    @endif

                    <!-- Blocks Container -->
                    <div x-ref="blocksContainer" class="space-y-4">
                        @foreach($blocks as $block)
                            <div
                                data-block-id="{{ $block['id'] }}"
                                class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden {{ $editingBlockId === $block['id'] ? 'ring-2 ring-indigo-500' : '' }} {{ ! $block['is_visible'] ? 'opacity-50' : '' }}"
                            >
                                <!-- Block Header -->
                                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <!-- Drag Handle -->
                                        <button class="drag-handle cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <!-- Block Icon & Name -->
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-500">
                                                @if($this->getBlockTypeIcon($block['type']) === 'type')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </span>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $this->getBlockTypeName($block['type']) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Block Actions -->
                                    <div class="flex items-center gap-1">
                                        <!-- Visibility Toggle -->
                                        <button
                                            wire:click="toggleBlockVisibility({{ $block['id'] }})"
                                            class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded"
                                            title="{{ $block['is_visible'] ? __('Hide') : __('Show') }}"
                                        >
                                            @if($block['is_visible'])
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                                </svg>
                                            @endif
                                        </button>

                                        <!-- Edit Button -->
                                        <button
                                            wire:click="editBlock({{ $block['id'] }})"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded"
                                            title="{{ __('Edit') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>

                                        <!-- Duplicate Button -->
                                        <button
                                            wire:click="duplicateBlock({{ $block['id'] }})"
                                            class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded"
                                            title="{{ __('Duplicate') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                                                <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h6a2 2 0 00-2-2H5z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button
                                            wire:click="removeBlock({{ $block['id'] }})"
                                            wire:confirm="{{ __('Delete this block?') }}"
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded"
                                            title="{{ __('Delete') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Block Content Preview -->
                                @if($editingBlockId !== $block['id'])
                                    <div class="p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                            {{ $this->renderBlockPreview($block) }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Block Edit Form -->
                                @if($editingBlockId === $block['id'])
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/30">
                                        @php($blockType = $this->blockRegistry->find($block['type']))
                                        @if($blockType)
                                            <div class="space-y-4">
                                                @foreach($blockType::schema() as $field)
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                            {{ $field['label'] }}
                                                            @if($field['required'] ?? false)
                                                                <span class="text-red-500">*</span>
                                                            @endif
                                                        </label>

                                                        @switch($field['type'])
                                                            @case('textarea')
                                                                <textarea
                                                                    wire:model="blockFormData.{{ $field['name'] }}"
                                                                    rows="4"
                                                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                                                ></textarea>
                                                                @break

                                                            @case('select')
                                                                <select
                                                                    wire:model="blockFormData.{{ $field['name'] }}"
                                                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                                                >
                                                                    @foreach($field['options'] as $option)
                                                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @break

                                                            @default
                                                                <input
                                                                    type="text"
                                                                    wire:model="blockFormData.{{ $field['name'] }}"
                                                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                                                >
                                                        @endswitch

                                                        @if($field['help'] ?? false)
                                                            <p class="mt-1 text-xs text-gray-500">{{ $field['help'] }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                <div class="flex justify-end gap-2 pt-2">
                                                    <flux:button wire:click="cancelEditBlock" variant="ghost" size="sm">
                                                        {{ __('Cancel') }}
                                                    </flux:button>
                                                    <flux:button wire:click="saveBlock" variant="primary" size="sm">
                                                        {{ __('Save Block') }}
                                                    </flux:button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Block Library -->
            <div class="w-72 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 min-h-[calc(100vh-4rem)] p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">{{ __('Add Blocks') }}</h3>

                @foreach($this->blockRegistry->categories() as $key => $category)
                    <div class="mb-4">
                        <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                            {{ $category['name'] }}
                        </h4>
                        <div class="space-y-1">
                            @foreach($this->blockRegistry->all()->filter(fn($b) => $b::category() === $key) as $blockClass)
                                <button
                                    wire:click="addBlock('{{ $blockClass::id() }}')"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                >
                                    <span class="text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span class="flex-1">{{ $blockClass::name() }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @script
    <script>
        // Load SortableJS from CDN
        if (typeof Sortable === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js';
            script.onload = function() {
                window.dispatchEvent(new Event('sortable-loaded'));
            };
            document.head.appendChild(script);
        }
    </script>
    @endscript
</flux:main>
