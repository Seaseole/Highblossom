<?php

declare(strict_types=1);

namespace Tests\Unit;

use Highblossom\ContentBlocks\Support\MediaUrl;
use Tests\TestCase;

/**
 * Media reference resolution across the shapes persisted in the database.
 *
 * Covers the four value shapes a media column or block attribute can hold,
 * which is what lets the public disk move between the local filesystem and
 * object storage without rewriting rows.
 */
class MediaUrlTest extends TestCase
{
    /**
     * The URL prefix the local public disk resolves to.
     */
    private string $base;

    protected function setUp(): void
    {
        parent::setUp();

        $this->base = rtrim(config('app.url'), '/').'/storage';
    }

    public function test_it_resolves_a_disk_relative_path(): void
    {
        $this->assertSame($this->base.'/gallery/a.webp', MediaUrl::asset('gallery/a.webp'));
    }

    public function test_it_resolves_a_leading_slash_storage_path(): void
    {
        $this->assertSame($this->base.'/videos/b.mp4', MediaUrl::asset('/storage/videos/b.mp4'));
        $this->assertSame($this->base.'/videos/b.mp4', MediaUrl::asset('storage/videos/b.mp4'));
    }

    public function test_it_re_resolves_a_legacy_absolute_storage_url(): void
    {
        // Rows written before object storage carry the host that created them.
        $this->assertSame(
            $this->base.'/gallery/a.webp',
            MediaUrl::asset('https://some-old-host.test/storage/gallery/a.webp'),
        );
    }

    public function test_it_leaves_genuinely_external_urls_untouched(): void
    {
        $this->assertSame('https://cdn.example.test/x.png', MediaUrl::asset('https://cdn.example.test/x.png'));
    }

    public function test_it_passes_through_a_url_already_pointing_at_the_bucket(): void
    {
        $url = 'https://bucket.laravel.cloud/uploads/images/a.webp';

        $this->assertSame($url, MediaUrl::asset($url));
    }

    public function test_it_returns_null_for_blank_values(): void
    {
        $this->assertNull(MediaUrl::asset(null));
        $this->assertNull(MediaUrl::asset(''));
        $this->assertNull(MediaUrl::asset('   '));
    }

    public function test_path_normalises_every_own_storage_shape(): void
    {
        $this->assertSame('gallery/a.webp', MediaUrl::path('gallery/a.webp'));
        $this->assertSame('gallery/a.webp', MediaUrl::path('/storage/gallery/a.webp'));
        $this->assertSame('gallery/a.webp', MediaUrl::path('https://x.test/storage/gallery/a.webp'));
        $this->assertNull(MediaUrl::path('https://cdn.example.test/x.png'));
        $this->assertNull(MediaUrl::path(null));
    }

    public function test_asset_resolves_through_the_public_disk_url(): void
    {
        config(['filesystems.disks.public.url' => 'https://bucket.laravel.cloud']);

        $this->assertSame(
            'https://bucket.laravel.cloud/gallery/a.webp',
            MediaUrl::asset('gallery/a.webp'),
        );
    }

    public function test_is_local_media_distinguishes_our_files_from_foreign_urls(): void
    {
        $this->assertTrue(MediaUrl::isLocalMedia('gallery/a.webp'));
        $this->assertTrue(MediaUrl::isLocalMedia('https://x.test/storage/gallery/a.webp'));
        $this->assertFalse(MediaUrl::isLocalMedia('https://cdn.example.test/x.png'));
        $this->assertFalse(MediaUrl::isLocalMedia(null));
    }
}
