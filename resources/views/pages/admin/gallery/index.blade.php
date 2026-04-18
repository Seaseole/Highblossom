<?php

use App\Domains\Content\Models\GalleryImage;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public ?string $categoryFilter = null;

    public function deleteImage(int $id): void
    {
        $image = GalleryImage::find($id);
        if ($image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        $this->dispatch('notify', ['message' => 'Image deleted successfully!', 'type' => 'success']);
    }

    public function toggleActive(int $id): void
    {
        $image = GalleryImage::find($id);
        if ($image) {
            $image->update(['is_active' => !$image->is_active]);
            $this->dispatch('notify', ['message' => 'Image updated!', 'type' => 'success']);
        }
    }

    public function with(): array
    {
        return [
            'images' => GalleryImage::when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(12),
            'categories' => ['automotive', 'heavy_machinery', 'fleet', 'other'],
        ];
    }
}; ?>

<flux:main>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="xl" level="1">{{ __('Gallery') }}</flux:heading>
            <flux:button :href="route('admin.gallery.create')" variant="primary" icon="plus">
                {{ __('Add Image') }}
            </flux:button>
        </div>

        <div class="mb-6 flex gap-4">
            <div class="flex-1">
                <flux:input wire:model.live="search" :placeholder="__('Search images...')" icon="magnifying-glass" />
            </div>
            <flux:select wire:model.live="categoryFilter" class="w-48">
                <flux:select.option value="">{{ __('All Categories') }}</flux:select.option>
                @foreach ($categories as $category)
                    <flux:select.option value="{{ $category }}">{{ ucfirst(str_replace('_', ' ', $category)) }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($images as $image)
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden flex flex-col">
                    <div class="aspect-square bg-zinc-100 dark:bg-zinc-800">
                        <img src="{{ $image->image_url }}" alt="{{ $image->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="mb-2">
                            <flux:heading size="sm" class="truncate">{{ $image->title }}</flux:heading>
                            <flux:subheading size="xs">{{ ucfirst(str_replace('_', ' ', $image->category)) }}</flux:subheading>
                        </div>
                        
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-zinc-100 dark:border-zinc-800">
                            <flux:button variant="ghost" size="sm" wire:click="toggleActive({{ $image->id }})" 
                                class="{{ $image->is_active ? 'text-green-600' : 'text-zinc-400' }}">
                                {{ $image->is_active ? __('Active') : __('Inactive') }}
                            </flux:button>
                            
                            <div class="flex gap-1">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.gallery.edit', $image->id)" />
                                <flux:button variant="ghost" size="sm" icon="trash" wire:click="deleteImage({{ $image->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-700" />
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-zinc-50 dark:bg-zinc-900/50 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
                    <flux:subheading>{{ __('No images found.') }}</flux:subheading>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $images->links() }}
        </div>
    </div>
</flux:main>
