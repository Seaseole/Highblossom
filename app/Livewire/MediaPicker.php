<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\GalleryImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Pick or upload media images via a modal interface.
 */
final class MediaPicker extends Component
{
    use WithFileUploads;

    /** The name of the form field being populated. */
    #[Locked]
    public string $fieldName = 'image';

    public ?string $selectedImageUrl = null;

    public ?int $selectedImageId = null;

    public string $altText = '';

    public string $search = '';

    public string $category = 'all';

    /** The uploaded image file. */
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

    /**
     * Initialize the component with the target field name and optional current value.
     */
    public function mount(string $fieldName = 'image', ?string $currentValue = null): void
    {
        $this->fieldName = $fieldName;
        $this->selectedImageUrl = $currentValue;
    }

    /**
     * Open the media picker modal.
     */
    public function open(): void
    {
        $this->isOpen = true;
    }

    /**
     * Close the media picker modal and reset upload state.
     */
    public function close(): void
    {
        $this->isOpen = false;
        $this->upload = null;
        $this->uploadTitle = '';
    }

    /**
     * Select an image and dispatch the media-selected event.
     *
     *
     * @throws ModelNotFoundException
     */
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

    /**
     * Validate and upload a new image to the gallery, then select it.
     */
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

    /**
     * Delete an image from the gallery and its storage disk.
     *
     *
     * @throws ModelNotFoundException
     */
    public function deleteImage(int $imageId): void
    {
        $image = GalleryImage::findOrFail($imageId);

        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        $this->dispatch('notify', message: 'Image deleted successfully', type: 'success');
    }

    /**
     * Reset pagination when the search term changes.
     */
    public function updatedSearch(): void
    {
        // Reset pagination when search changes
    }

    /**
     * Get the filtered, paginated images for the media picker gallery.
     *
     * @return LengthAwarePaginator
     */
    public function getImagesProperty()
    {
        return GalleryImage::query()
            ->when($this->category !== 'all', fn ($q) => $q->where('category', $this->category))
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(12);
    }

    /**
     * Render the media picker component.
     */
    public function render(): View
    {
        return view('livewire.media-picker');
    }
}
