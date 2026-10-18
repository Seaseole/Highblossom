<?php

declare(strict_types=1);

namespace Highblossom\ContentBlocks\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Resolves persisted media references into URLs for the active public disk.
 *
 * Media is stored on the "public" disk, which is driver-switchable: a local
 * folder in development and an object-storage bucket in production. Values
 * persisted in the database predate that split, so a single stored field can
 * legitimately hold any of four shapes, and this class is the one place that
 * understands all of them:
 *
 *  - disk-relative path  "gallery/a.jpg"
 *  - legacy storage path "/storage/gallery/a.jpg" or "storage/gallery/a.jpg"
 *  - legacy absolute URL "https://example.test/storage/gallery/a.jpg"
 *  - external URL        "https://img.cdn.test/a.jpg" (left untouched)
 *
 * Prefer storing the disk-relative shape; the legacy absolute shapes are
 * tolerated on read so historical rows keep working after a host or driver
 * change. Callers that persist media must store a path, never a URL.
 */
final class MediaUrl
{
    /**
     * Marker that separates our own storage URLs from genuinely external ones.
     */
    private const STORAGE_SEGMENT = '/storage/';

    /**
     * Disk the public media lives on.
     */
    private const DISK = 'public';

    /**
     * Bundled files stored in the database as bare filenames that live in the
     * app's public root (shipped with every deploy via git), not on the
     * driver-switchable public disk. Resolving them through the disk would
     * point at object storage, where they were never uploaded.
     *
     * @var list<string>
     */
    private const ROOT_ASSETS = ['placeholder.gif'];

    /**
     * Resolve any stored media value into a displayable URL.
     *
     * @param string|null $value Path or URL persisted in the database
     *
     * @return string|null Absolute URL for the active disk, the value unchanged
     *                     when it is an external URL, or null when empty
     */
    public static function asset(?string $value): ?string
    {
        if (self::isBlank($value)) {
            return null;
        }

        $path = self::path($value);

        if ($path === null) {
            // A genuinely external URL (CDN, YouTube, another host): pass through.
            return $value;
        }

        if (in_array($path, self::ROOT_ASSETS, true)) {
            return asset($path);
        }

        return Storage::disk(self::DISK)->url($path);
    }

    /**
     * Normalise any stored media value to a disk-relative path.
     *
     * @param string|null $value Path or URL persisted in the database
     *
     * @return string|null Disk-relative path, or null when the value is blank
     *                     or points at a host this application does not own
     */
    public static function path(?string $value): ?string
    {
        if (self::isBlank($value)) {
            return null;
        }

        $value = trim($value);

        if (self::looksLikeUrl($value)) {
            return self::pathFromOwnStorageUrl($value);
        }

        return self::stripStoragePrefix($value);
    }

    /**
     * Determine whether a stored value already points at our own public disk.
     *
     * @param string|null $value Path or URL persisted in the database
     */
    public static function isLocalMedia(?string $value): bool
    {
        return self::path($value) !== null;
    }

    /**
     * Extract the disk-relative path from a URL served by our own storage.
     *
     * The /storage/ marker is what identifies the value as ours, so this works
     * across every host the app has ever run under (localhost, the .test dev
     * domain, production) without a host allowlist that would need updating.
     *
     * @param string $url An absolute http(s) URL
     *
     * @return string|null Disk-relative path, or null for foreign URLs
     */
    private static function pathFromOwnStorageUrl(string $url): ?string
    {
        $position = strrpos($url, self::STORAGE_SEGMENT);

        if ($position === false) {
            return null;
        }

        $path = substr($url, $position + strlen(self::STORAGE_SEGMENT));

        return self::isBlank($path) ? null : self::stripStoragePrefix($path);
    }

    /**
     * Remove leading slashes and a leading "storage/" segment.
     *
     * @param string $path Raw path, possibly prefixed with a slash or "storage"
     *
     * @return string|null Clean disk-relative path, or null when nothing is left
     */
    private static function stripStoragePrefix(string $path): ?string
    {
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return $path === '' ? null : $path;
    }

    /**
     * Determine whether the value is an absolute URL.
     *
     * @param string $value Raw stored value
     */
    private static function looksLikeUrl(string $value): bool
    {
        return str_starts_with($value, 'http://') || str_starts_with($value, 'https://');
    }

    /**
     * @param string|null $value Raw stored value
     */
    private static function isBlank(?string $value): bool
    {
        return $value === null || trim($value) === '';
    }
}
