<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\VideoSourceType;
use App\Services\VideoSourceDetector;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Highblossom\ContentBlocks\Services\BlockRegistry;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
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

    public array $availableBlockTypes = [];

    public function mount(string $name = 'content', $value = null): void
    {
        $this->name = $name;
        $blocks = is_array($value) ? array_values($value) : [];

        // Ensure all blocks have a unique ID and sequential keys
        $this->blocks = array_map(function ($block) {
            if (!isset($block['id'])) {
                $block['id'] = uniqid('block_', true);
            }
            return $block;
        }, $blocks);

        $this->loadAvailableBlocks();
    }

    /**
     * Load available block types from the registry.
     */
    protected function loadAvailableBlocks(): void
    {
        $registry = app(BlockRegistry::class);
        $types = $registry->types();
        
        Log::debug('BlockBuilder: Loading available blocks', [
            'count' => count($types),
            'types' => $types
        ]);

        $this->availableBlockTypes = collect($types)
            ->mapWithKeys(fn ($type) => [$type => ucfirst(str_replace('_', ' ', $type))])
            ->toArray();
    }

    // Block manipulation methods have been moved to Alpine.js for client-side performance.

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
            Log::error('uploadImageForBlock called with empty imageUpload');

            return;
        }

        $this->validate([
            'imageUpload' => 'required|image|max:61440|mimes:jpg,jpeg,png,webp,gif',
        ]);

        // Store in temp disk - will be relocated to permanent on post save
        $path = $this->imageUpload->store('uploads/images', 'temp');

        if (empty($path)) {
            Log::error('Image store returned empty path');

            return;
        }

        $url = 'temp://'.$path;

        Log::debug('uploadImageForBlock', [
            'blockIndex' => $blockIndex,
            'path' => $path,
            'url' => $url,
            'blocks_count' => count($this->blocks),
        ]);

        $this->dispatch('image-uploaded', [
            'index' => $blockIndex,
            'url' => $url,
        ]);

        $this->imageUpload = null;
        $this->uploadProgress = 0;
        $this->activeImageUploadIndex = null;
        $this->uploadingBlockIndex = null;

        $this->dispatch('notify', message: 'Image uploaded successfully', type: 'success');
    }
    public function uploadVideo(): void
    {
        $this->validate([
            'videoUpload' => 'required|file|max:61440|mimes:mp4,webm,mov,avi',
        ]);

        // Store video file in temp disk - will be relocated to permanent on post save
        $path = $this->videoUpload->store('uploads/videos', 'temp');
        $videoUrl = 'temp://'.$path;
        $thumbnailUrl = null;

        // Generate thumbnail using FFmpeg directly from temp location
        $fullPath = storage_path('app/temp/'.$path);
        if (file_exists($fullPath)) {
            try {
                $ffmpeg = FFMpeg::create([
                    'ffmpeg.binaries' => 'C:\\ffmpeg\\bin\\ffmpeg.exe',
                    'ffprobe.binaries' => 'C:\\ffmpeg\\bin\\ffprobe.exe',
                    'timeout' => 3600,
                ]);
                $video = $ffmpeg->open($fullPath);

                // Create thumbnail directory in temp
                $thumbnailDir = storage_path('app/temp/uploads/videos/thumbnails');
                if (! is_dir($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }

                // Generate thumbnail filename
                $thumbnailFilename = 'thumb_'.pathinfo($path, PATHINFO_FILENAME).'.jpg';
                $thumbnailFullPath = $thumbnailDir.'/'.$thumbnailFilename;

                // Extract frame at 1 second mark
                $frame = $video->frame(TimeCode::fromSeconds(1));
                $frame->save($thumbnailFullPath);

                $thumbnailUrl = 'temp://uploads/videos/thumbnails/'.$thumbnailFilename;
            } catch (\Exception $e) {
                Log::warning('Video thumbnail generation failed: '.$e->getMessage());
                $thumbnailUrl = null;
            }
        }

        if ($this->uploadingVideoBlockIndex !== null) {
            $this->dispatch('video-uploaded', [
                'index' => $this->uploadingVideoBlockIndex,
                'url' => $videoUrl,
                'poster' => $thumbnailUrl,
            ]);
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

    /**
     * Detect video URL and return preview data for live embed.
     */
    public function detectVideoUrl(string $url): array
    {
        $detector = app(VideoSourceDetector::class);
        $sourceType = $detector->detect($url);

        if ($sourceType === VideoSourceType::UNKNOWN) {
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

    public function render(): View
    {
        $this->loadAvailableBlocks();

        return view('livewire.block-builder');
    }
}
