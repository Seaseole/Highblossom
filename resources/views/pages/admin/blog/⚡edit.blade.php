<?php

declare(strict_types=1);

use App\Domains\Content\Actions\Blocks\AddBlock;
use App\Domains\Content\Actions\Blocks\RemoveBlock;
use App\Domains\Content\Actions\Blocks\ReorderBlocks;
use App\Domains\Content\Actions\Blocks\UpdateBlock;
use App\Domains\Content\Actions\Posts\CreatePost;
use App\Domains\Content\Actions\Posts\UpdatePost;
use App\Domains\Content\Models\Category;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Services\BlockRegistry;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Edit Post')] class extends Component
{
    public ?Post $post = null;
    public bool $isEditing = false;

    // Post fields
    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public ?string $featured_image = null;
    public ?int $category_id = null;
    public array $selectedTags = [];
    public bool $is_published = false;
    public bool $is_featured = false;
    public array $seo_metadata = [];

    // Block editing
    public ?int $editingBlockId = null;
    public array $editingBlockData = [];
    public bool $showAddBlockModal = false;
    public ?string $selectedBlockType = null;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->post = Post::with(['contentBlocks', 'tags', 'category'])->findOrFail($id);
            $this->isEditing = true;

            $this->title = $this->post->title;
            $this->slug = $this->post->slug;
            $this->excerpt = $this->post->excerpt ?? '';
            $this->featured_image = $this->post->featured_image;
            $this->category_id = $this->post->category_id;
            $this->selectedTags = $this->post->tags->pluck('id')->toArray();
            $this->is_published = $this->post->is_published;
            $this->is_featured = $this->post->is_featured;
            $this->seo_metadata = $this->post->seo_metadata ?? [];
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . ($this->post?->id ?? 'NULL') . ',id',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = [
            ...$validated,
            'tags' => $this->selectedTags,
            'seo_metadata' => $this->seo_metadata,
        ];

        if ($this->isEditing && $this->post) {
            app(UpdatePost::class)->execute($this->post, $data);
            Flux::toast('Post updated successfully.');
        } else {
            $this->post = app(CreatePost::class)->execute($data);
            $this->isEditing = true;
            Flux::toast('Post created successfully.');
        }

        // Clear cache
        Cache::forget('admin.posts.list');
    }

    public function publish(): void
    {
        if (!$this->post) {
            $this->save();
        }

        if ($this->post) {
            $this->post->update([
                'is_published' => true,
                'published_at' => now(),
            ]);
            $this->is_published = true;
            Flux::toast('Post published successfully.');
        }
    }

    public function addBlock(string $blockTypeId): void
    {
        if (!$this->post) {
            $this->save();
        }

        if ($this->post) {
            app(AddBlock::class)->execute($this->post, $blockTypeId);
            $this->showAddBlockModal = false;
            $this->selectedBlockType = null;
            Flux::toast('Block added.');
        }
    }

    public function editBlock(int $blockId): void
    {
        $block = $this->post?->contentBlocks()->find($blockId);

        if ($block) {
            $this->editingBlockId = $blockId;
            $this->editingBlockData = $block->content ?? [];
        }
    }

    public function updateBlock(): void
    {
        if (!$this->editingBlockId || !$this->post) {
            return;
        }

        $block = $this->post->contentBlocks()->find($this->editingBlockId);

        if ($block) {
            app(UpdateBlock::class)->execute($block, $this->editingBlockData);
            $this->editingBlockId = null;
            $this->editingBlockData = [];
            Flux::toast('Block updated.');
        }
    }

    public function removeBlock(int $blockId): void
    {
        if (!$this->post) return;

        $block = $this->post->contentBlocks()->find($blockId);

        if ($block) {
            app(RemoveBlock::class)->execute($block);
            Flux::toast('Block removed.');
        }
    }

    public function moveBlockUp(int $blockId): void
    {
        if (!$this->post) return;

        $block = $this->post->contentBlocks()->find($blockId);

        if ($block && $block->sort_order > 0) {
            app(ReorderBlocks::class)->moveToPosition($block, $block->sort_order - 1);
        }
    }

    public function moveBlockDown(int $blockId): void
    {
        if (!$this->post) return;

        $block = $this->post->contentBlocks()->find($blockId);

        if ($block) {
            app(ReorderBlocks::class)->moveToPosition($block, $block->sort_order + 1);
        }
    }

    public function getBlockRegistryProperty(): BlockRegistry
    {
        return app(BlockRegistry::class);
    }

    public function getCategoriesProperty()
    {
        return Category::forPosts()->orderBy('name')->get();
    }

    public function getTagsProperty()
    {
        return \App\Domains\Content\Models\Tag::orderBy('name')->get();
    }
}; ?>

<flux:main>
    <section class="w-full">
        <!-- Header -->
        <div class="relative mb-6 w-full">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" level="1">
                        {{ $isEditing ? __('Edit Post') : __('New Post') }}
                    </flux:heading>
                    <flux:subheading size="lg" class="mb-6">
                        {{ $isEditing ? __('Update your blog post content') : __('Create a new blog post') }}
                    </flux:subheading>
                </div>
                <div class="flex gap-2">
                    @if($isEditing && $post)
                        <flux:button
                            href="{{ route('blog.show', $post) }}"
                            target="_blank"
                            variant="ghost"
                            icon="eye"
                        >
                            {{ __('Preview') }}
                        </flux:button>
                    @endif

                    @if(!$is_published)
                        <flux:button
                            wire:click="publish"
                            variant="primary"
                            icon="paper-airplane"
                        >
                            {{ __('Publish') }}
                        </flux:button>
                    @endif

                    <flux:button
                        wire:click="save"
                        variant="{{ $is_published ? 'primary' : 'outline' }}"
                        icon="check"
                    >
                        {{ __('Save') }}
                    </flux:button>
                </div>
            </div>
            <flux:separator variant="subtle" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Post Details -->
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                    <flux:heading size="lg" class="mb-4">{{ __('Post Details') }}</flux:heading>

                    <div class="space-y-4">
                        <flux:input
                            wire:model="title"
                            label="{{ __('Title') }}"
                            placeholder="{{ __('Enter post title...') }}"
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input
                                wire:model="slug"
                                label="{{ __('Slug') }}"
                                placeholder="{{ __('post-url-slug') }}"
                            />

                            <flux:select wire:model="category_id" label="{{ __('Category') }}">
                                <option value="">{{ __('No Category') }}</option>
                                @foreach($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </flux:select>
                        </div>

                        <flux:textarea
                            wire:model="excerpt"
                            label="{{ __('Excerpt') }}"
                            placeholder="{{ __('Brief summary for SEO and previews...') }}"
                            rows="3"
                        />

                        <flux:input
                            wire:model="featured_image"
                            label="{{ __('Featured Image URL') }}"
                            placeholder="{{ __('https://example.com/image.jpg') }}"
                        />
                    </div>
                </div>

                <!-- Content Blocks -->
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading size="lg">{{ __('Content Blocks') }}</flux:heading>
                        <flux:button
                            wire:click="$set('showAddBlockModal', true)"
                            variant="outline"
                            size="sm"
                            icon="plus"
                        >
                            {{ __('Add Block') }}
                        </flux:button>
                    </div>

                    @if($post && $post->contentBlocks->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($post->contentBlocks as $block)
                                <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    @php
                                        $blockType = $this->blockRegistry->find($block->type);
                                    @endphp

                                    <div class="flex-shrink-0">
                                        @if($blockType)
                                            <flux:icon name="{{ $blockType::icon() }}" class="w-5 h-5 text-gray-500" />
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm">
                                            {{ $blockType ? $blockType::name() : $block->type }}
                                        </div>
                                        <div class="text-xs text-gray-500 truncate">
                                            @if(is_array($block->content))
                                                {{ json_encode($block->content, JSON_THROW_ON_ERROR) }}
                                            @else
                                                {{ Str::limit($block->content, 50) }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <flux:button
                                            wire:click="moveBlockUp({{ $block->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="chevron-up"
                                            :disabled="$loop->first"
                                        />

                                        <flux:button
                                            wire:click="moveBlockDown({{ $block->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="chevron-down"
                                            :disabled="$loop->last"
                                        />

                                        <flux:button
                                            wire:click="editBlock({{ $block->id }})"
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        />

                                        <flux:button
                                            wire:click="removeBlock({{ $block->id }})"
                                            wire:confirm="{{ __('Remove this block?') }}"
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                        />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <flux:icon name="document" class="w-12 h-12 mx-auto mb-3 opacity-50" />
                            <p>{{ __('No blocks yet. Add your first block to get started.') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publishing -->
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                    <flux:heading size="lg" class="mb-4">{{ __('Publishing') }}</flux:heading>

                    <div class="space-y-4">
                        <flux:checkbox wire:model="is_published" label="{{ __('Published') }}" />
                        <flux:checkbox wire:model="is_featured" label="{{ __('Featured Post') }}" />

                        @if($post)
                            <div class="text-sm text-gray-500 pt-2 border-t">
                                <p>{{ __('Created:') }} {{ $post->created_at?->format('M j, Y') }}</p>
                                @if($post->published_at)
                                    <p>{{ __('Published:') }} {{ $post->published_at->format('M j, Y') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tags -->
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                    <flux:heading size="lg" class="mb-4">{{ __('Tags') }}</flux:heading>

                    <div class="space-y-2">
                        @foreach($this->tags as $tag)
                            <flux:checkbox
                                wire:model="selectedTags"
                                value="{{ $tag->id }}"
                                label="{{ $tag->name }}"
                            />
                        @endforeach
                    </div>
                </div>

                <!-- SEO -->
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                    <flux:heading size="lg" class="mb-4">{{ __('SEO') }}</flux:heading>

                    <div class="space-y-4">
                        <flux:input
                            wire:model="seo_metadata.meta_title"
                            label="{{ __('Meta Title') }}"
                            placeholder="{{ $title }}"
                        />

                        <flux:textarea
                            wire:model="seo_metadata.meta_description"
                            label="{{ __('Meta Description') }}"
                            rows="3"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Block Modal -->
    <flux:modal wire:model="showAddBlockModal" title="{{ __('Add Content Block') }}">
        <div class="grid grid-cols-2 gap-3">
            @foreach($this->blockRegistry->groupedByCategory() as $category => $blocks)
                <div class="col-span-2 mt-2 first:mt-0">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                        {{ $this->blockRegistry->categories()[$category]['name'] ?? $category }}
                    </h4>
                </div>

                @foreach($blocks as $blockClass)
                    <button
                        wire:click="addBlock('{{ $blockClass::id() }}')"
                        class="flex items-center gap-3 p-4 text-left rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                    >
                        <flux:icon name="{{ $blockClass::icon() }}" class="w-5 h-5 text-gray-500" />
                        <div>
                            <div class="font-medium">{{ $blockClass::name() }}</div>
                            <div class="text-xs text-gray-500">{{ $blockClass::description() }}</div>
                        </div>
                    </button>
                @endforeach
            @endforeach
        </div>
    </flux:modal>

    <!-- Edit Block Modal -->
    <flux:modal wire:model="editingBlockId" title="{{ __('Edit Block') }}">
        @if($editingBlockId && $post)
            @php
                $editingBlock = $post->contentBlocks()->find($editingBlockId);
                $blockType = $editingBlock ? $this->blockRegistry->find($editingBlock->type) : null;
            @endphp

            @if($blockType)
                <div class="space-y-4">
                    @foreach($blockType::schema() as $field)
                        @switch($field['type'])
                            @case('textarea')
                                <flux:textarea
                                    wire:model="editingBlockData.{{ $field['name'] }}"
                                    label="{{ $field['label'] }}"
                                    rows="4"
                                />
                                @break

                            @case('rich-text')
                                <flux:textarea
                                    wire:model="editingBlockData.{{ $field['name'] }}"
                                    label="{{ $field['label'] }}"
                                    rows="8"
                                />
                                @break

                            @case('select')
                                <flux:select wire:model="editingBlockData.{{ $field['name'] }}" label="{{ $field['label'] }}">
                                    @foreach($field['options'] as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </flux:select>
                                @break

                            @default
                                <flux:input
                                    wire:model="editingBlockData.{{ $field['name'] }}"
                                    label="{{ $field['label'] }}"
                                    type="{{ $field['type'] }}"
                                />
                        @endswitch
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <flux:button wire:click="$set('editingBlockId', null)" variant="ghost">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button wire:click="updateBlock" variant="primary">
                        {{ __('Update Block') }}
                    </flux:button>
                </div>
            @endif
        @endif
    </flux:modal>
</flux:main>
