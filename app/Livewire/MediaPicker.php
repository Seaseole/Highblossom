<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Content\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

final class MediaPicker extends Component
{
    use WithFileUploads;

    #[Locked]
    public string $fieldName = 'image';

    public ?string $selectedImageUrl = null;

    public ?int $selectedImageId = null;

    public string $altText = '';

    public string $search = '';

    public string $category = 'all';

    public $upload;

    public string $uploadTitle = '';

    public string $uploadCategory = 'other';

    public bool $isOpen = false;

    public array $categories = [
        'all' => 'All Categories',
        'automotive' => 'Automotive',
        'heavy_machinery' => 'Heavy Machinery',
        'fleet' => 'Fleet',
        'other' => 'Other',
    ];

    public function mount(string $fieldName = 'image', ?string $currentValue = null): void
    {
        $this->fieldName = $fieldName;
        $this->selectedImageUrl = $currentValue;
    }

    public function open(): void
    {
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->upload = null;
        $this->uploadTitle = '';
    }

    public function selectImage(int $imageId): void
    {
        $image = GalleryImage::findOrFail($imageId);
        $this->selectedImageUrl = $image->image_url;
        $this->selectedImageId = $imageId;
        $this->dispatch('media-selected', [
            'field' => $this->fieldName,
            'url' => $image->image_url,
            'id' => $imageId,
            'alt' => $this->altText,
        ]);
        $this->close();
    }

    public function uploadImage(): void
    {
        $this->validate([
            'upload' => 'required|image|max:5120',
            'uploadTitle' => 'required|string|max:255',
            'uploadCategory' => 'required|string|in:automotive,heavy_machinery,fleet,other',
        ]);

        $path = $this->upload->store('gallery', 'public');

        $image = GalleryImage::create([
            'title' => $this->uploadTitle,
            'image_path' => $path,
            'category' => $this->uploadCategory,
            'is_active' => true,
            'sort_order' => GalleryImage::max('sort_order') + 1,
        ]);

        $this->selectImage($image->id);
        $this->upload = null;
        $this->uploadTitle = '';
        $this->dispatch('notify', message: 'Image uploaded successfully', type: 'success');
    }

    public function deleteImage(int $imageId): void
    {
        $image = GalleryImage::findOrFail($imageId);
        
        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $image->delete();
        
        $this->dispatch('notify', message: 'Image deleted successfully', type: 'success');
    }

    public function updatedSearch(): void
    {
        // Reset pagination when search changes
    }

    public function getImagesProperty()
    {
        return GalleryImage::query()
            ->when($this->category !== 'all', fn ($q) => $q->where('category', $this->category))
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(12);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.media-picker');
    }
}
