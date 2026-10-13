<?php

declare(strict_types=1);

use Highblossom\ContentBlocks\Support\MediaUrl;

if (! function_exists('media_url')) {
    /**
     * Resolve a stored media reference into a URL for the active public disk.
     *
     * Accepts a disk-relative path, a legacy "/storage/..." reference, a legacy
     * absolute URL pointing at our own storage, or a genuinely external URL.
     * Use this instead of asset('storage/...') so media keeps resolving when the
     * public disk moves between the local filesystem and object storage.
     *
     * @param string|null $value Path or URL persisted in the database
     *
     * @return string|null The resolved URL, or null when the value is blank
     */
    function media_url(?string $value): ?string
    {
        return MediaUrl::asset($value);
    }
}
