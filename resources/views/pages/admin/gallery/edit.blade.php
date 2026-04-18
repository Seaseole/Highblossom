<?php

use App\Domains\Content\Models\GalleryImage;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ?int $imageId = null;
    public string $title = '';
    public string $description = '';
    public string $category = 'other';
    public bool $isFeatured = false;
    public bool $isActive = true;
    public int $sortOrder = 0;
    public $image = null;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->imageId = $id;
            $image = GalleryImage::findOrFail($id);
            $this->title = $image->title;
            $this->description = $image->description ?? '';
            $this->category = $image->category;
            $this->isFeatured = $image->is_featured;
            $this->isActive = $image->is_active;
            $this->sortOrder = $image->sort_order;
        }
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:automotive,heavy_machinery,fleet,other',
            'isFeatured' => 'boolean',
            'isActive' => 'boolean',
            'sortOrder' => 'integer',
        ];

        if (!$this->imageId) {
            $rules['image'] = 'required|image|max:5120'; // 5MB max
        } else {
            $rules['image'] = 'nullable|image|max:5120';
        }

        $this->validate($rules);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('gallery', 'public');
        }

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'is_featured' => $this->isFeatured,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];

        if ($imagePath) {
            $data['image_path'] = $imagePath;
        }

        if ($this->imageId) {
            GalleryImage::find($this->imageId)->update($data);
            $message = 'Image updated successfully!';
        } else {
            GalleryImage::create($data);
            $message = 'Image uploaded successfully!';
        }

        $this->dispatch('notify', ['message' => $message, 'type' => 'success']);
        $this->redirect(route('admin.gallery.index'));
    }
}; ?>

<flux:main>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <flux:heading size="xl" level="1" class="mb-6">{{ $imageId ? __('Edit Gallery Image') : __('Upload Gallery Image') }}</flux:heading>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="title" :label="__('Title')" />

                <flux:select wire:model="category" :label="__('Category')">
                    <flux:select.option value="automotive">{{ __('Automotive') }}</flux:select.option>
                    <flux:select.option value="heavy_machinery">{{ __('Heavy Machinery') }}</flux:select.option>
                    <flux:select.option value="fleet">{{ __('Fleet') }}</flux:select.option>
                    <flux:select.option value="other">{{ __('Other') }}</flux:select.option>
                </flux:select>

                <div class="md:col-span-2">
                    <flux:textarea wire:model="description" :label="__('Description')" rows="3" />
                </div>

                <flux:input type="number" wire:model="sortOrder" :label="__('Sort Order')" />

                <div class="flex flex-col gap-4 justify-center">
                    <flux:checkbox wire:model="isFeatured" :label="__('Featured')" />
                    <flux:checkbox wire:model="isActive" :label="__('Active')" />
                </div>

                <div class="md:col-span-2">
                    <flux:input type="file" wire:model="image" :label="__('Image')" accept="image/*" />

                    <div wire:loading wire:target="image" class="mt-2 text-sm text-zinc-500">{{ __('Uploading...') }}</div>

                    @if ($image)
                        <div class="mt-4">
                            <flux:label>{{ __('Preview:') }}</flux:label>
                            <img src="{{ $image->temporaryUrl() }}" class="mt-2 max-h-48 rounded shadow-sm border border-zinc-200">
                        </div>
                    @elseif ($imageId)
                        @php
                            $existingImage = \App\Domains\Content\Models\GalleryImage::find($imageId);
                        @endphp
                        @if ($existingImage)
                            <div class="mt-4">
                                <flux:label>{{ __('Current Image:') }}</flux:label>
                                <img src="{{ $existingImage->image_url }}" class="mt-2 max-h-48 rounded shadow-sm border border-zinc-200">
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                <flux:button :href="route('admin.gallery.index')" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                    {{ $imageId ? __('Update Image') : __('Upload Image') }}
                </flux:button>
            </div>
        </form>
    </div>
</flux:main>
