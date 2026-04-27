<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\VideoSourceType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class VideoSourceDetector
{
    /** @var array<string, string> Cache of resolved Facebook share URLs */
    private array $resolvedFacebookUrls = [];

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
            VideoSourceType::FACEBOOK => 'https://www.facebook.com/plugins/video.php?href='.urlencode($src).'&show_text=0',
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

        // Share links: facebook.com/share/r/xxxxx - try to resolve first
        if (preg_match('/facebook\.com\/share\/r\//', $src)) {
            $resolved = $this->resolveFacebookShareUrl($src);
            if ($resolved !== null) {
                $this->resolvedFacebookUrls[$src] = $resolved;
            }
            return true;
        }

        // Short URL format: fb.watch/xxxxx - try to resolve first
        if (str_contains($lowerSrc, 'fb.watch/')) {
            $resolved = $this->resolveFacebookShareUrl($src);
            if ($resolved !== null) {
                $this->resolvedFacebookUrls[$src] = $resolved;
            }
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
     * Resolve Facebook share/short URLs (fb.watch, facebook.com/share/r/) to actual video URLs.
     * Uses browser user-agent spoofing to bypass Facebook's bot detection.
     */
    private function resolveFacebookShareUrl(string $url): ?string
    {
        // Check cache first
        if (isset($this->resolvedFacebookUrls[$url])) {
            return $this->resolvedFacebookUrls[$url];
        }

        // Browser user-agent to spoof
        $browserUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

        try {
            // Make HEAD request with browser user-agent to get redirect location
            $response = Http::withHeaders([
                'User-Agent' => $browserUserAgent,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'DNT' => '1',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'none',
                'Sec-Fetch-User' => '?1',
                'Cache-Control' => 'max-age=0',
            ])
                ->withOptions([
                    'allow_redirects' => false,
                    'verify' => false,
                ])
                ->timeout(10)
                ->head($url);

            // Check for redirect (302 or 301)
            if ($response->redirect()) {
                $location = $response->header('Location');
                if ($location) {
                    // Handle relative URLs
                    if (str_starts_with($location, '/')) {
                        $parsedUrl = parse_url($url);
                        $location = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $location;
                    }

                    Log::debug('Facebook share URL resolved', [
                        'original' => $url,
                        'resolved' => $location,
                    ]);

                    return $location;
                }
            }

            // If no redirect, try GET request and follow redirects
            $response = Http::withHeaders([
                'User-Agent' => $browserUserAgent,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
            ])
                ->withOptions([
                    'allow_redirects' => true,
                    'verify' => false,
                ])
                ->timeout(10)
                ->get($url);

            if ($response->successful()) {
                $effectiveUrl = $response->effectiveUri();
                if ($effectiveUrl && $effectiveUrl !== $url) {
                    Log::debug('Facebook share URL resolved via redirect chain', [
                        'original' => $url,
                        'resolved' => (string) $effectiveUrl,
                    ]);

                    return (string) $effectiveUrl;
                }
            }

            return null;
        } catch (\Throwable $e) {
            Log::warning('Failed to resolve Facebook share URL', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Extract Facebook video ID (returns full URL for embed).
     * Facebook embeds work best with the complete original URL.
     * Uses resolved URL if the original was a share/short link.
     */
    private function extractFacebookId(string $src): ?string
    {
        // Use resolved URL if available (for share links like fb.watch)
        if (isset($this->resolvedFacebookUrls[$src])) {
            $resolvedUrl = $this->resolvedFacebookUrls[$src];

            // Extract video ID from resolved URL if possible
            $extractedId = $this->extractVideoIdFromFacebookUrl($resolvedUrl);
            if ($extractedId !== null) {
                return $extractedId;
            }

            return $resolvedUrl;
        }

        // For direct URLs, try to extract video ID
        $extractedId = $this->extractVideoIdFromFacebookUrl($src);
        if ($extractedId !== null) {
            return $extractedId;
        }

        // Fall back to using the full URL for embedding via plugins/video.php
        return $src;
    }

    /**
     * Extract video ID from a Facebook URL.
     */
    private function extractVideoIdFromFacebookUrl(string $url): ?string
    {
        // Reel format: facebook.com/reel/123456789012345
        if (preg_match('/facebook\.com\/reel\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        // Watch URL: facebook.com/watch?v=123456789012345
        if (preg_match('/facebook\.com\/watch\?v=(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        // Watch URL with path: facebook.com/watch/123456789012345
        if (preg_match('/facebook\.com\/watch\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        // Legacy video.php: facebook.com/video.php?v=123456789012345
        if (preg_match('/facebook\.com\/video\.php\?v=(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        // Legacy photo.php with video: facebook.com/photo.php?v=123456789012345
        if (preg_match('/facebook\.com\/photo\.php\?v=(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        // Profile videos: facebook.com/username/videos/123456789012345
        if (preg_match('/facebook\.com\/[^\/]+\/videos\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
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
