<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\VideoSourceType;
use App\Services\VideoSourceDetector;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Highblossom\ContentBlocks\Contracts\BlockInterface;
use Highblossom\ContentBlocks\Services\BlockRegistry;
use Highblossom\ContentBlocks\Services\BlockRenderer;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Build and manage content blocks with image and video upload support.
 */
final class BlockBuilder extends Component
{
    use WithFileUploads;

    /**
     * Block types that have an inline editor in the builder UI.
     *
     * Types registered in the block registry without an entry here are still
     * rendered on the frontend and preserved on save, but are not offered in
     * the "Add Block" menu because the builder cannot produce valid attributes
     * for them yet.
     *
     * @var list<string>
     */
    private const EDITABLE_TYPES = [
        'paragraph',
        'heading',
        'image',
        'quote',
        'code',
        'list',
        'cta',
        'video',
        'divider',
        'alert',
        'html',
        'embed',
        'countdown',
        'poll',
        'gallery',
        'table',
        'form',
        'carousel',
        'columns',
        'tabs',
        'accordion',
    ];

    /** The field name used for storing block data. */
    #[Locked]
    public string $name = 'content';

    public array $blocks = [];

    /** The uploaded image file. */
    #[Validate(['nullable', 'image', 'max:61440'])]
    public $imageUpload = null;

    /** The block id targeted by the next image upload. */
    public ?string $activeImageUploadId = null;

    /** The attribute path, relative to the target block, receiving the uploaded image. */
    public string $activeImageUploadPath = 'src';

    /** The uploaded video file. */
    #[Validate(['nullable', 'file', 'max:61440'])]
    public $videoUpload = null;

    public ?string $uploadingVideoBlockId = null;

    /**
     * Per-type builder metadata: label, addable flag, server defaults and required fields.
     *
     * @var array<string, array{label: string, editable: bool, defaults: array<string, mixed>, required: list<string>}>
     */
    public array $blockMeta = [];

    /**
     * Server-side validation messages grouped by the block id they belong to.
     *
     * @var array<string, list<string>>
     */
    public array $blockErrors = [];

    /**
     * Initialize the block builder with the field name and existing block data.
     *
     * @param array<string, mixed>|string|null $value Block array, or the JSON string echoed back by old()
     */
    public function mount(string $name = 'content', $value = null): void
    {
        $this->name = $name;
        $blocks = $this->decodeBlocks($value);

        // Ensure all blocks have a unique ID and sequential keys
        $this->blocks = array_values(array_map(function ($block) {
            if (! isset($block['id'])) {
                $block['id'] = uniqid('block_', true);
            }

            return $block;
        }, $blocks));

        $this->loadBlockMeta();
    }

    /**
     * Normalise the incoming block value, which may be an array or a JSON string.
     *
     * @param array<string, mixed>|string|null $value
     *
     * @return list<array<string, mixed>>
     */
    private function decodeBlocks($value): array
    {
        if (is_string($value) && $value !== '') {
            $value = json_decode($value, true);
        }

        return is_array($value) ? array_values($value) : [];
    }

    /**
     * Load per-type metadata from the block registry.
     */
    protected function loadBlockMeta(): void
    {
        if ($this->blockMeta !== []) {
            return;
        }

        $registry = app(BlockRegistry::class);

        $this->blockMeta = collect($registry->types())
            ->mapWithKeys(function (string $type) use ($registry): array {
                $block = $registry->get($type);

                if (! $block instanceof BlockInterface) {
                    return [$type => ['label' => ucfirst($type), 'editable' => false, 'defaults' => [], 'required' => []]];
                }

                return [$type => [
                    'label' => ucfirst(str_replace('_', ' ', $type)),
                    'editable' => in_array($type, self::EDITABLE_TYPES, true),
                    'defaults' => $block->getDefaultAttributes(),
                    'required' => $this->requiredFields($block),
                ]];
            })
            ->toArray();
    }

    /**
     * Extract the top-level required attribute names from a block's validation rules.
     *
     * @return list<string>
     */
    private function requiredFields(BlockInterface $block): array
    {
        $required = [];

        foreach ($block->getValidationRules() as $field => $rules) {
            if (is_string($field) && ! str_contains($field, '.') && $this->ruleListHas($rules, 'required')) {
                $required[] = $field;
            }
        }

        return $required;
    }

    /**
     * Determine whether a rule definition, in string or array form, contains the given rule.
     */
    private function ruleListHas(array|string $rules, string $name): bool
    {
        foreach ((array) $rules as $rule) {
            if (is_string($rule) && in_array($name, explode('|', $rule), true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Map the flashed content.{index}.attributes errors onto the matching block ids.
     *
     * @return array<string, list<string>>
     */
    private function blockErrorMessages(): array
    {
        $errors = session('errors');

        if ($errors === null || $errors->count() === 0) {
            return [];
        }

        $mapped = [];

        foreach ($this->blocks as $index => $block) {
            $messages = $errors->get("content.{$index}.attributes");

            if ($messages !== []) {
                $mapped[$block['id']] = $messages;
            }
        }

        return $mapped;
    }

    // Block manipulation methods have been moved to Alpine.js for client-side performance.

    /**
     * Mark a block, by id, and its attribute path as the target for the next image upload.
     */
    public function setActiveImageUpload(string $blockId, string $path = 'src'): void
    {
        $this->activeImageUploadId = $blockId;
        $this->activeImageUploadPath = $path;
    }

    /**
     * Auto-upload when imageUpload property changes.
     */
    public function updatedImageUpload(): void
    {
        if ($this->imageUpload && $this->activeImageUploadId !== null) {
            $this->uploadImageForBlock();
        }
    }

    /**
     * Upload an image for the targeted block attribute and notify the frontend.
     */
    protected function uploadImageForBlock(): void
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

        $this->dispatch('image-uploaded', [
            'id' => $this->activeImageUploadId,
            'attribute' => $this->activeImageUploadPath,
            'url' => 'temp://'.$path,
        ]);

        $this->imageUpload = null;
        $this->activeImageUploadId = null;
        $this->activeImageUploadPath = 'src';

        $this->dispatch('notify', message: 'Image uploaded successfully', type: 'success');
    }

    /**
     * Validate and upload a video file, generate a thumbnail, and notify the frontend.
     */
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

        if ($this->uploadingVideoBlockId !== null) {
            $this->dispatch('video-uploaded', [
                'id' => $this->uploadingVideoBlockId,
                'url' => $videoUrl,
                'poster' => $thumbnailUrl,
            ]);
        }

        $this->videoUpload = null;
        $this->uploadingVideoBlockId = null;

        $this->dispatch('notify', message: 'Video uploaded successfully', type: 'success');
    }

    /**
     * Mark a block, by id, as ready for video upload.
     */
    public function startVideoUpload(string $blockId): void
    {
        $this->uploadingVideoBlockId = $blockId;
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
     * Detect the video source type from a URL and return preview data for live embed.
     *
     * @return array{valid: bool, error?: string, source_type?: string, source_label?: string, embed_url?: string, video_id?: string, uses_iframe?: bool, full_url?: string}
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

    /**
     * Render unsaved blocks through the production renderer for a draft preview.
     *
     * @return string HTML fragment; safe to inject into the preview modal.
     */
    public function renderPreview(string $content): string
    {
        if (strlen($content) > 2_000_000) {
            return '<p class="text-sm text-red-500">Content is too large to preview.</p>';
        }

        $blocks = json_decode($content, true);

        if (! is_array($blocks)) {
            return '<p class="text-sm text-red-500">Content is not a valid block list.</p>';
        }

        $blocks = array_values(array_filter(
            $blocks,
            fn ($block) => is_array($block) && isset($block['type']) && is_string($block['type'])
        ));

        try {
            return app(BlockRenderer::class)->renderMany($blocks);
        } catch (\Throwable $e) {
            Log::warning('Block preview rendering failed: '.$e->getMessage());

            return '<p class="text-sm text-red-500">Preview could not be rendered.</p>';
        }
    }

    /**
     * Render the block builder component.
     */
    public function render(): View
    {
        $this->loadBlockMeta();
        $this->blockErrors = $this->blockErrorMessages();

        return view('livewire.block-builder');
    }
}
