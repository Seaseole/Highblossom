<?php

namespace Highblossom\ContentBlocks\Blocks;

use Highblossom\ContentBlocks\Services\AbstractBlock;

class VideoBlock extends AbstractBlock
{
    public function getType(): string
    {
        return 'video';
    }

    public function getValidationRules(): array
    {
        return [
            'src' => 'nullable|string',
            'type' => 'nullable|string',
            'poster' => 'nullable|string',
            'autoplay' => 'nullable|boolean',
            'controls' => 'nullable|boolean',
            'class' => 'nullable|string',
        ];
    }

    public function getDefaultAttributes(): array
    {
        return [
            'src' => '',
            'type' => '',
            'poster' => '',
            'autoplay' => false,
            'controls' => true,
            'class' => '',
        ];
    }

    protected function getAttributeCasts(): array
    {
        return [
            'src' => 'string',
            'type' => 'string',
            'poster' => 'string',
            'autoplay' => 'boolean',
            'controls' => 'boolean',
            'class' => 'string',
        ];
    }

    /**
     * Get data to pass to the view, including detected source type.
     */
    protected function getViewData(array $attributes): array
    {
        $src = $attributes['src'] ?? '';

        return array_merge($attributes, [
            'source_type' => $this->detectSourceType($src),
            'video_id' => $this->extractVideoId($src),
            'embed_url' => $this->getEmbedUrl($src),
            'full_url' => $this->getFullUrl($src),
        ]);
    }

    /**
     * Detect the source type from the src string.
     */
    protected function detectSourceType(string $src): string
    {
        if (empty($src)) {
            return 'unknown';
        }

        if ($this->isLocalFilePath($src)) {
            return 'local_file';
        }

        if ($this->isYouTubeUrl($src)) {
            return 'youtube';
        }

        if ($this->isVimeoUrl($src)) {
            return 'vimeo';
        }

        if ($this->isDailymotionUrl($src)) {
            return 'dailymotion';
        }

        if ($this->isFacebookUrl($src)) {
            return 'facebook';
        }

        if ($this->isDirectVideoUrl($src)) {
            return 'direct_url';
        }

        return 'unknown';
    }

    /**
     * Extract video ID for embeddable platforms.
     */
    protected function extractVideoId(string $src): ?string
    {
        $type = $this->detectSourceType($src);

        return match ($type) {
            'youtube' => $this->extractYouTubeId($src),
            'vimeo' => $this->extractVimeoId($src),
            'dailymotion' => $this->extractDailymotionId($src),
            'facebook' => $src,
            default => null,
        };
    }

    /**
     * Get embed URL for iframe-based platforms.
     */
    protected function getEmbedUrl(string $src): ?string
    {
        $type = $this->detectSourceType($src);
        $videoId = $this->extractVideoId($src);

        if ($videoId === null) {
            return null;
        }

        return match ($type) {
            'youtube' => "https://www.youtube.com/embed/{$videoId}",
            'vimeo' => "https://player.vimeo.com/video/{$videoId}",
            'dailymotion' => "https://www.dailymotion.com/embed/video/{$videoId}",
            'facebook' => "https://www.facebook.com/plugins/video.php?href=" . urlencode($src) . "&show_text=0",
            default => null,
        };
    }

    /**
     * Get full URL for local file paths.
     */
    protected function getFullUrl(string $src): string
    {
        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
            return $src;
        }

        return asset(ltrim($src, '/'));
    }

    /**
     * Check if src is a local file path.
     */
    protected function isLocalFilePath(string $src): bool
    {
        // Check for relative paths
        if (str_starts_with($src, '/') ||
            str_starts_with($src, 'storage/') ||
            str_starts_with($src, 'videos/') ||
            str_starts_with($src, 'uploads/')) {
            return true;
        }

        // Check for absolute URLs from the current app's storage
        if (str_contains($src, '/storage/')) {
            return true;
        }

        // Check if it's a URL from the current domain
        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
            $host = parse_url($src, PHP_URL_HOST);
            return $host === request()->getHost();
        }

        return false;
    }

    /**
     * Check if src is a YouTube URL.
     */
    protected function isYouTubeUrl(string $src): bool
    {
        return str_contains($src, 'youtube.com') || str_contains($src, 'youtu.be');
    }

    /**
     * Extract YouTube video ID.
     */
    protected function extractYouTubeId(string $src): ?string
    {
        if (preg_match('/youtube\.com\/watch\?v=([^&\s]+)/', $src, $matches)) {
            return $matches[1];
        }

        if (preg_match('/youtu\.be\/([^&\s?]+)/', $src, $matches)) {
            return $matches[1];
        }

        if (preg_match('/youtube\.com\/embed\/([^&\s?]+)/', $src, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if src is a Vimeo URL.
     */
    protected function isVimeoUrl(string $src): bool
    {
        return str_contains($src, 'vimeo.com');
    }

    /**
     * Extract Vimeo video ID.
     */
    protected function extractVimeoId(string $src): ?string
    {
        if (preg_match('/vimeo\.com\/(\d+)/', $src, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if src is a Dailymotion URL.
     */
    protected function isDailymotionUrl(string $src): bool
    {
        return str_contains($src, 'dailymotion.com') || str_contains($src, 'dai.ly');
    }

    /**
     * Extract Dailymotion video ID.
     */
    protected function extractDailymotionId(string $src): ?string
    {
        if (preg_match('/dailymotion\.com\/video\/([a-zA-Z0-9]+)/', $src, $matches)) {
            return $matches[1];
        }

        if (preg_match('/dai\.ly\/([a-zA-Z0-9]+)/', $src, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if src is a Facebook video URL.
     * Supports: Reels (/reel/), share links (/share/r/, fb.watch),
     * watch URLs, video.php, photo.php, profile videos, and legacy formats.
     */
    protected function isFacebookUrl(string $src): bool
    {
        $lowerSrc = strtolower($src);

        // Modern Reels format: facebook.com/reel/123456789012345
        if (preg_match('/facebook\.com\/reel\/\d+/', $src)) {
            return true;
        }

        // Share links: facebook.com/share/r/xxxxx
        if (preg_match('/facebook\.com\/share\/r\//', $src)) {
            return true;
        }

        // Short URL format: fb.watch/xxxxx
        if (str_contains($lowerSrc, 'fb.watch/')) {
            return true;
        }

        // Standard watch URL: facebook.com/watch?v=123456789012345
        if (preg_match('/facebook\.com\/watch\?v=\d+/', $src)) {
            return true;
        }

        // Watch URL with path: facebook.com/watch/123456789012345
        if (preg_match('/facebook\.com\/watch\/\d+/', $src)) {
            return true;
        }

        // Legacy video.php: facebook.com/video.php?v=123456789012345
        if (preg_match('/facebook\.com\/video\.php\?v=\d+/', $src)) {
            return true;
        }

        // Legacy photo.php with video: facebook.com/photo.php?v=123456789012345
        if (preg_match('/facebook\.com\/photo\.php\?v=\d+/', $src)) {
            return true;
        }

        // Profile videos: facebook.com/username/videos/123456789012345
        if (preg_match('/facebook\.com\/[^\/]+\/videos\/\d+/', $src)) {
            return true;
        }

        // Old VB format: facebook.com/username/videos/vb.123456789012345/987654321098765
        if (preg_match('/facebook\.com\/[^\/]+\/videos\/vb\.\d+\/\d+/', $src)) {
            return true;
        }

        return false;
    }

    /**
     * Check if src is a direct video file URL.
     */
    protected function isDirectVideoUrl(string $src): bool
    {
        $extensions = ['.mp4', '.webm', '.mov', '.ogg', '.ogv', '.m4v', '.mkv'];
        $lowerSrc = strtolower($src);

        foreach ($extensions as $ext) {
            if (str_ends_with($lowerSrc, $ext)) {
                return true;
            }
        }

        return preg_match('/\.(mp4|webm|mov|ogg|ogv|m4v|mkv)(\?.*)?$/i', $src) === 1;
    }
}
