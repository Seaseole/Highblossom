<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

final class BlockBuilder extends Component
{
    use WithFileUploads;

    #[Locked]
    public string $name = 'content';

    public array $blocks = [];

    public $imageUpload = null;

    public int $uploadProgress = 0;

    public ?int $uploadingBlockIndex = null;

    public $videoUpload = null;

    public int $videoUploadProgress = 0;

    public ?int $uploadingVideoBlockIndex = null;

    public array $availableBlockTypes = [
        'paragraph' => 'Paragraph',
        'heading' => 'Heading',
        'image' => 'Image',
        'quote' => 'Quote',
        'code' => 'Code',
        'list' => 'List',
        'cta' => 'Call to Action',
        'video' => 'Video',
    ];

    public function mount(string $name = 'content', $value = null): void
    {
        $this->name = $name;
        $this->blocks = is_array($value) ? $value : [];
    }

    public function addBlock(string $type): void
    {
        $this->blocks[] = [
            'id' => uniqid('block_', true),
            'type' => $type,
            'attributes' => $this->getDefaultAttributesForType($type),
        ];

        $this->dispatchBlocksUpdated();
    }

    public function removeBlock(int $index): void
    {
        unset($this->blocks[$index]);
        $this->blocks = array_values($this->blocks);
        $this->dispatchBlocksUpdated();
    }

    public function moveBlock(int $fromIndex, int $toIndex): void
    {
        if ($fromIndex === $toIndex) {
            return;
        }

        $block = $this->blocks[$fromIndex];
        unset($this->blocks[$fromIndex]);
        $this->blocks = array_values($this->blocks);

        array_splice($this->blocks, $toIndex, 0, [$block]);
        $this->dispatchBlocksUpdated();
    }

    public function updateBlock(int $index, array $attributes): void
    {
        if (isset($this->blocks[$index])) {
            $this->blocks[$index]['attributes'] = $attributes;
            $this->dispatchBlocksUpdated();
        }
    }

    protected function getDefaultAttributesForType(string $type): array
    {
        return match ($type) {
            'paragraph' => ['content' => '', 'class' => ''],
            'heading' => ['content' => '', 'level' => 'h2', 'class' => ''],
            'image' => ['src' => '', 'alt' => '', 'caption' => ''],
            'quote' => ['content' => '', 'author' => '', 'cite' => '', 'class' => ''],
            'code' => ['content' => '', 'class' => ''],
            'list' => ['items' => [], 'type' => 'ul', 'class' => ''],
            'cta' => ['title' => '', 'description' => '', 'button_text' => '', 'button_url' => '', 'class' => ''],
            'video' => ['src' => '', 'poster' => '', 'type' => 'video/mp4', 'controls' => true, 'class' => ''],
            default => [],
        };
    }

    protected function dispatchBlocksUpdated(): void
    {
        $this->dispatch('blocks-updated', blocks: $this->blocks);
    }

    public function uploadImage(): void
    {
        $this->validate([
            'imageUpload' => 'required|image|max:10240|mimes:jpg,jpeg,png,webp,gif',
        ]);

        $path = $this->imageUpload->store('uploads/images', 'public');
        $url = asset('storage/' . $path);

        if ($this->uploadingBlockIndex !== null && isset($this->blocks[$this->uploadingBlockIndex])) {
            $this->blocks[$this->uploadingBlockIndex]['attributes']['src'] = $url;
            $this->dispatchBlocksUpdated();
        }

        $this->imageUpload = null;
        $this->uploadProgress = 0;
        $this->uploadingBlockIndex = null;

        $this->dispatch('notify', message: 'Image uploaded successfully', type: 'success');
    }

    public function startImageUpload(int $blockIndex): void
    {
        $this->uploadingBlockIndex = $blockIndex;
    }

    public function removeUploadedImage(int $blockIndex): void
    {
        $this->blocks[$blockIndex]['attributes']['src'] = '';
        $this->imageUpload = null;
        $this->dispatchBlocksUpdated();
    }

    public function uploadVideo(): void
    {
        $this->validate([
            'videoUpload' => 'required|file|max:30720|mimes:mp4,webm,mov,avi',
        ]);

        // Store video file using Livewire's file upload
        $path = $this->videoUpload->store('uploads/videos', 'public');
        $videoUrl = asset('storage/' . $path);
        $thumbnailPath = null;

        // Generate thumbnail using FFmpeg directly
        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            try {
                $ffmpeg = \FFMpeg\FFMpeg::create();
                $video = $ffmpeg->open($fullPath);

                // Create thumbnail directory
                $thumbnailDir = public_path('videos/thumbnails');
                if (!is_dir($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }

                // Generate thumbnail filename
                $thumbnailFilename = 'thumb_' . pathinfo($path, PATHINFO_FILENAME) . '.jpg';
                $thumbnailPath = $thumbnailDir . '/' . $thumbnailFilename;

                // Extract frame at 1 second mark
                $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
                $frame->save($thumbnailPath);

                $thumbnailPath = 'videos/thumbnails/' . $thumbnailFilename;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Video thumbnail generation failed: ' . $e->getMessage());
                $thumbnailPath = null;
            }
        }

        if ($this->uploadingVideoBlockIndex !== null && isset($this->blocks[$this->uploadingVideoBlockIndex])) {
            $this->blocks[$this->uploadingVideoBlockIndex]['attributes']['src'] = $videoUrl;
            if ($thumbnailPath) {
                $this->blocks[$this->uploadingVideoBlockIndex]['attributes']['poster'] = asset($thumbnailPath);
            }
            $this->dispatchBlocksUpdated();
        }

        $this->videoUpload = null;
        $this->videoUploadProgress = 0;
        $this->uploadingVideoBlockIndex = null;

        $this->dispatch('notify', message: 'Video uploaded successfully', type: 'success');
    }

    public function startVideoUpload(int $blockIndex): void
    {
        $this->uploadingVideoBlockIndex = $blockIndex;
    }

    public function removeUploadedVideo(int $blockIndex): void
    {
        $this->blocks[$blockIndex]['attributes']['src'] = '';
        $this->blocks[$blockIndex]['attributes']['poster'] = '';
        $this->videoUpload = null;
        $this->dispatchBlocksUpdated();
    }

    /**
     * Detect video URL and return preview data for live embed.
     */
    public function detectVideoUrl(string $url): array
    {
        $detector = app(\App\Services\VideoSourceDetector::class);
        $sourceType = $detector->detect($url);

        if ($sourceType === \App\Enums\VideoSourceType::UNKNOWN) {
            return [
                'valid' => false,
                'error' => 'Unrecognized video URL. Supported: YouTube, Vimeo, Dailymotion, Facebook, or direct video files.',
            ];
        }

        $embedUrl = $detector->getEmbedUrl($url, $sourceType);
        $videoId = $detector->extractVideoId($url, $sourceType);

        return [
            'valid' => true,
            'source_type' => $sourceType->value,
            'source_label' => $sourceType->label(),
            'embed_url' => $embedUrl,
            'video_id' => $videoId,
            'uses_iframe' => $sourceType->usesIframe(),
            'full_url' => $detector->getFullUrl($url),
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.block-builder');
    }
}
