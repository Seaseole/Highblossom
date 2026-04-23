<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\VideoSourceType;

final class VideoSourceDetector
{
    /**
     * Detect the source type from a video src string.
     */
    public function detect(string $src): VideoSourceType
    {
        if (empty($src)) {
            return VideoSourceType::UNKNOWN;
        }

        // Check for local file paths first
        if ($this->isLocalFilePath($src)) {
            return VideoSourceType::LOCAL_FILE;
        }

        // Check for YouTube URLs
        if ($this->isYouTubeUrl($src)) {
            return VideoSourceType::YOUTUBE;
        }

        // Check for Vimeo URLs
        if ($this->isVimeoUrl($src)) {
            return VideoSourceType::VIMEO;
        }

        // Check for Dailymotion URLs
        if ($this->isDailymotionUrl($src)) {
            return VideoSourceType::DAILYMOTION;
        }

        // Check for Facebook URLs
        if ($this->isFacebookUrl($src)) {
            return VideoSourceType::FACEBOOK;
        }

        // Check for direct video file URLs
        if ($this->isDirectVideoUrl($src)) {
            return VideoSourceType::DIRECT_URL;
        }

        return VideoSourceType::UNKNOWN;
    }

    /**
     * Extract video ID from a URL based on source type.
     */
    public function extractVideoId(string $src, VideoSourceType $type): ?string
    {
        return match ($type) {
            VideoSourceType::YOUTUBE => $this->extractYouTubeId($src),
            VideoSourceType::VIMEO => $this->extractVimeoId($src),
            VideoSourceType::DAILYMOTION => $this->extractDailymotionId($src),
            VideoSourceType::FACEBOOK => $this->extractFacebookId($src),
            default => null,
        };
    }

    /**
     * Get the embed URL for iframe-based platforms.
     */
    public function getEmbedUrl(string $src, VideoSourceType $type): ?string
    {
        $videoId = $this->extractVideoId($src, $type);

        if ($videoId === null) {
            return null;
        }

        return match ($type) {
            VideoSourceType::YOUTUBE => "https://www.youtube.com/embed/{$videoId}",
            VideoSourceType::VIMEO => "https://player.vimeo.com/video/{$videoId}",
            VideoSourceType::DAILYMOTION => "https://www.dailymotion.com/embed/video/{$videoId}",
            VideoSourceType::FACEBOOK => "https://www.facebook.com/plugins/video.php?href=" . urlencode($src) . "&show_text=0",
            default => null,
        };
    }

    /**
     * Get the full URL for a local file path.
     */
    public function getFullUrl(string $src): string
    {
        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
            return $src;
        }

        // Remove leading slash if present for asset() helper
        $path = ltrim($src, '/');

        return asset($path);
    }

    /**
     * Check if src is a local file path.
     */
    private function isLocalFilePath(string $src): bool
    {
        return str_starts_with($src, '/') ||
               str_starts_with($src, 'storage/') ||
               str_starts_with($src, 'videos/') ||
               str_starts_with($src, 'uploads/');
    }

    /**
     * Check if src is a YouTube URL.
     */
    private function isYouTubeUrl(string $src): bool
    {
        return str_contains($src, 'youtube.com') ||
               str_contains($src, 'youtu.be');
    }

    /**
     * Extract YouTube video ID.
     */
    private function extractYouTubeId(string $src): ?string
    {
        // youtube.com/watch?v=VIDEO_ID
        if (preg_match('/youtube\.com\/watch\?v=([^&\s]+)/', $src, $matches)) {
            return $matches[1];
        }

        // youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([^&\s?]+)/', $src, $matches)) {
            return $matches[1];
        }

        // youtube.com/embed/VIDEO_ID
        if (preg_match('/youtube\.com\/embed\/([^&\s?]+)/', $src, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if src is a Vimeo URL.
     */
    private function isVimeoUrl(string $src): bool
    {
        return str_contains($src, 'vimeo.com');
    }

    /**
     * Extract Vimeo video ID.
     */
    private function extractVimeoId(string $src): ?string
    {
        // vimeo.com/VIDEO_ID
        if (preg_match('/vimeo\.com\/(\d+)/', $src, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Check if src is a Dailymotion URL.
     */
    private function isDailymotionUrl(string $src): bool
    {
        return str_contains($src, 'dailymotion.com');
    }

    /**
     * Extract Dailymotion video ID.
     */
    private function extractDailymotionId(string $src): ?string
    {
        // dailymotion.com/video/VIDEO_ID
        if (preg_match('/dailymotion\.com\/video\/([a-zA-Z0-9]+)/', $src, $matches)) {
            return $matches[1];
        }

        // dai.ly/VIDEO_ID
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
    private function isFacebookUrl(string $src): bool
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
     * Extract Facebook video ID (returns full URL for embed).
     * Facebook embeds work best with the complete original URL.
     */
    private function extractFacebookId(string $src): ?string
    {
        // Facebook uses the full URL for embedding via plugins/video.php
        return $src;
    }

    /**
     * Check if src is a direct video file URL.
     */
    private function isDirectVideoUrl(string $src): bool
    {
        $videoExtensions = ['.mp4', '.webm', '.mov', '.ogg', '.ogv', '.m4v', '.mkv'];
        $lowerSrc = strtolower($src);

        foreach ($videoExtensions as $ext) {
            if (str_ends_with($lowerSrc, $ext)) {
                return true;
            }
        }

        // Check for video content type in URL patterns
        if (preg_match('/\.(mp4|webm|mov|ogg|ogv|m4v|mkv)(\?.*)?$/i', $src)) {
            return true;
        }

        return false;
    }
}
