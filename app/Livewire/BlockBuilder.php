<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

final class BlockBuilder extends Component
{
    use WithFileUploads;

    #[Locked]
    public string $name = 'content';

    public array $blocks = [];

    #[Validate(['nullable', 'image', 'max:61440'])]
    public $imageUpload = null;

    // Track which block index is being uploaded to (persists better than uploadingBlockIndex)
    public ?int $activeImageUploadIndex = null;

    public int $uploadProgress = 0;

    public ?int $uploadingBlockIndex = null;

    #[Validate(['nullable', 'file', 'max:61440'])]
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

    /**
     * Auto-upload when imageUpload property changes.
     */
    public function updatedImageUpload(): void
    {
        // Use activeImageUploadIndex which is set by Alpine before upload
        $blockIndex = $this->activeImageUploadIndex ?? $this->uploadingBlockIndex;

        if ($this->imageUpload && $blockIndex !== null) {
            $this->uploadImageForBlock($blockIndex);
        }
    }

    public function uploadImageForBlock(int $blockIndex): void
    {
        if (empty($this->imageUpload)) {
            \Illuminate\Support\Facades\Log::error('uploadImageForBlock called with empty imageUpload');
            return;
        }

        $this->validate([
            'imageUpload' => 'required|image|max:61440|mimes:jpg,jpeg,png,webp,gif',
        ]);

        // Store in temp disk - will be relocated to permanent on post save
        $path = $this->imageUpload->store('uploads/images', 'temp');

        if (empty($path)) {
            \Illuminate\Support\Facades\Log::error('Image store returned empty path');
            return;
        }

        $url = 'temp://' . $path;

        \Illuminate\Support\Facades\Log::debug('uploadImageForBlock', [
            'blockIndex' => $blockIndex,
            'path' => $path,
            'url' => $url,
            'blocks_count' => count($this->blocks),
        ]);

        if (isset($this->blocks[$blockIndex])) {
            $this->blocks[$blockIndex]['attributes']['src'] = $url;
            $this->dispatchBlocksUpdated();
            \Illuminate\Support\Facades\Log::debug('Image src set successfully', [
                'index' => $blockIndex,
                'src' => $url,
            ]);
        } else {
            \Illuminate\Support\Facades\Log::error('Failed to set image src - invalid block index', [
                'blockIndex' => $blockIndex,
                'blocks' => $this->blocks,
            ]);
        }

        $this->imageUpload = null;
        $this->uploadProgress = 0;
        $this->activeImageUploadIndex = null;
        $this->uploadingBlockIndex = null;

        $this->dispatch('notify', message: 'Image uploaded successfully', type: 'success');
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
            'videoUpload' => 'required|file|max:61440|mimes:mp4,webm,mov,avi',
        ]);
        
        // Store video file in temp disk - will be relocated to permanent on post save
        $path = $this->videoUpload->store('uploads/videos', 'temp');
        $videoUrl = 'temp://' . $path;
        $thumbnailUrl = null;

        // Generate thumbnail using FFmpeg directly from temp location
        $fullPath = storage_path('app/temp/' . $path);
        if (file_exists($fullPath)) {
            try {
                $ffmpeg = \FFMpeg\FFMpeg::create([
                    'ffmpeg.binaries' => 'C:\\ffmpeg\\bin\\ffmpeg.exe',
                    'ffprobe.binaries' => 'C:\\ffmpeg\\bin\\ffprobe.exe',
                    'timeout' => 3600,
                ]);
                $video = $ffmpeg->open($fullPath);

                // Create thumbnail directory in temp
                $thumbnailDir = storage_path('app/temp/uploads/videos/thumbnails');
                if (!is_dir($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }

                // Generate thumbnail filename
                $thumbnailFilename = 'thumb_' . pathinfo($path, PATHINFO_FILENAME) . '.jpg';
                $thumbnailFullPath = $thumbnailDir . '/' . $thumbnailFilename;

                // Extract frame at 1 second mark
                $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
                $frame->save($thumbnailFullPath);

                $thumbnailUrl = 'temp://uploads/videos/thumbnails/' . $thumbnailFilename;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Video thumbnail generation failed: ' . $e->getMessage());
                $thumbnailUrl = null;
            }
        }

        if ($this->uploadingVideoBlockIndex !== null && isset($this->blocks[$this->uploadingVideoBlockIndex])) {
            $this->blocks[$this->uploadingVideoBlockIndex]['attributes']['src'] = $videoUrl;
            if ($thumbnailUrl) {
                $this->blocks[$this->uploadingVideoBlockIndex]['attributes']['poster'] = $thumbnailUrl;
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

    /**
     * Auto-upload when videoUpload property changes.
     */
    public function updatedVideoUpload(): void
    {
        if ($this->videoUpload) {
            $this->uploadVideo();
        }
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
