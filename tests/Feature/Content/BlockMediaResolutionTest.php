<?php

declare(strict_types=1);

namespace Tests\Feature\Content;

use Highblossom\ContentBlocks\Services\BlockRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Content blocks store disk-relative media paths, so every media attribute has
 * to be resolved against the public disk at render time. These cover the block
 * types that carry media, including the legacy absolute-URL shapes still stored
 * in historical rows.
 */
class BlockMediaResolutionTest extends TestCase
{
    use RefreshDatabase;

    private string $base;

    protected function setUp(): void
    {
        parent::setUp();

        $this->base = rtrim(config('app.url'), '/').'/storage';
    }

    /**
     * Render one block through the same path the site uses.
     *
     * @param array $attributes Block attributes
     */
    private function render(string $type, array $attributes): string
    {
        return app(BlockRenderer::class)->renderMany([
            ['type' => $type, 'attributes' => $attributes],
        ]);
    }

    public function test_image_block_resolves_a_relative_src(): void
    {
        $html = $this->render('image', ['src' => 'uploads/images/a.webp', 'alt' => 'A']);

        $this->assertStringContainsString('src="'.$this->base.'/uploads/images/a.webp"', $html);
    }

    public function test_image_block_resolves_every_entry_in_an_image_set(): void
    {
        $html = $this->render('image', [
            'src' => '',
            'images' => [
                ['src' => 'uploads/images/one.webp', 'alt' => 'One', 'caption' => null],
                ['src' => 'uploads/images/two.webp', 'alt' => 'Two', 'caption' => null],
            ],
        ]);

        $this->assertStringContainsString('src="'.$this->base.'/uploads/images/one.webp"', $html);
        $this->assertStringContainsString('src="'.$this->base.'/uploads/images/two.webp"', $html);
    }

    public function test_image_block_re_resolves_a_legacy_absolute_storage_url(): void
    {
        $html = $this->render('image', [
            'src' => '',
            'images' => [['src' => 'https://old-host.test/storage/gallery/keep.jpg', 'alt' => 'K', 'caption' => null]],
        ]);

        $this->assertStringContainsString('src="'.$this->base.'/gallery/keep.jpg"', $html);
        $this->assertStringNotContainsString('old-host.test', $html);
    }

    public function test_image_block_leaves_an_external_url_untouched(): void
    {
        $html = $this->render('image', ['src' => 'https://cdn.example.test/x.png', 'alt' => 'Ext']);

        $this->assertStringContainsString('src="https://cdn.example.test/x.png"', $html);
    }

    public function test_gallery_block_resolves_relative_sources(): void
    {
        $html = $this->render('gallery', [
            'columns' => 3,
            'images' => [
                ['src' => 'gallery/one.webp', 'alt' => 'One', 'caption' => null],
                ['src' => 'gallery/two.webp', 'alt' => 'Two', 'caption' => null],
            ],
        ]);

        $this->assertStringContainsString('src="'.$this->base.'/gallery/one.webp"', $html);
        $this->assertStringContainsString('src="'.$this->base.'/gallery/two.webp"', $html);
    }

    public function test_video_block_resolves_relative_source_and_poster(): void
    {
        $html = $this->render('video', [
            'src' => 'uploads/videos/clip.mp4',
            'poster' => 'uploads/videos/thumbnails/clip.jpg',
        ]);

        $this->assertStringContainsString('src="'.$this->base.'/uploads/videos/clip.mp4"', $html);
        $this->assertStringContainsString('poster="'.$this->base.'/uploads/videos/thumbnails/clip.jpg"', $html);
    }

    public function test_video_block_re_resolves_a_legacy_absolute_source(): void
    {
        $html = $this->render('video', ['src' => 'http://localhost/storage/videos/old.mp4']);

        $this->assertStringContainsString('src="'.$this->base.'/videos/old.mp4"', $html);
    }

    public function test_video_block_still_embeds_external_platforms(): void
    {
        $html = $this->render('video', ['src' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);

        $this->assertStringContainsString('<iframe', $html);
        $this->assertStringNotContainsString($this->base, $html);
    }

    public function test_columns_block_resolves_media_in_nested_blocks(): void
    {
        $html = $this->render('columns', [
            'column_widths' => [12],
            'columns' => [[
                ['type' => 'image', 'attributes' => ['src' => 'uploads/images/nested.webp', 'alt' => 'N']],
            ]],
        ]);

        $this->assertStringContainsString('src="'.$this->base.'/uploads/images/nested.webp"', $html);
    }
}
