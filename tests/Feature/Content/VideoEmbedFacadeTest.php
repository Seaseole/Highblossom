<?php

declare(strict_types=1);

namespace Tests\Feature\Content;

use Highblossom\ContentBlocks\Services\BlockRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * YouTube embeds are rendered behind a click-to-play facade so the platform
 * thumbnail paints without requesting third-party consent cookies, which a
 * growing share of browsers refuse and then loop the embed. Every embedded
 * provider additionally carries the referrer policy YouTube's own embed
 * snippet emits.
 */
class VideoEmbedFacadeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Render one block through the same path the site uses.
     *
     * @param array<string, mixed> $attributes Block attributes
     */
    private function render(array $attributes): string
    {
        return app(BlockRenderer::class)->renderMany([
            ['type' => 'video', 'attributes' => $attributes],
        ]);
    }

    public function test_youtube_block_defers_the_iframe_behind_a_thumbnail_facade(): void
    {
        $html = $this->render(['src' => 'https://youtu.be/9SDkU5VDQEQ?si=4s0wdJXSzQC2iW4u']);

        $this->assertStringContainsString('src="https://i.ytimg.com/vi/9SDkU5VDQEQ/hqdefault.jpg"', $html);
        $this->assertStringContainsString('href="https://www.youtube.com/watch?v=9SDkU5VDQEQ"', $html);
        $this->assertStringContainsString('<template x-if="playing">', $html);
        $this->assertStringContainsString(
            'src="https://www.youtube.com/embed/9SDkU5VDQEQ?feature=oembed&amp;autoplay=1"',
            $html
        );
    }

    public function test_youtube_tracking_parameters_never_reach_the_embed_url(): void
    {
        $html = $this->render(['src' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&si=abcdef']);

        $this->assertStringContainsString('https://www.youtube.com/embed/dQw4w9WgXcQ?feature=oembed', $html);
        $this->assertStringNotContainsString('si=abcdef', $html);
        $this->assertStringNotContainsString('abcdef', $html);
    }

    public function test_every_embedded_provider_declares_a_referrer_policy(): void
    {
        $providers = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://vimeo.com/76979871',
            'https://www.dailymotion.com/video/x6eyla',
            'https://www.facebook.com/reel/1234567890123456',
        ];

        foreach ($providers as $url) {
            $this->assertStringContainsString(
                'referrerpolicy="strict-origin-when-cross-origin"',
                $this->render(['src' => $url]),
                $url.' embed is missing its referrer policy'
            );
        }
    }

    public function test_non_youtube_providers_still_embed_their_player_immediately(): void
    {
        $vimeo = $this->render(['src' => 'https://vimeo.com/76979871']);

        $this->assertStringContainsString('<iframe', $vimeo);
        $this->assertStringNotContainsString('<template x-if="playing">', $vimeo);
        $this->assertStringNotContainsString('i.ytimg.com', $vimeo);
    }

    public function test_uploaded_videos_render_a_plain_player_without_the_facade(): void
    {
        $html = $this->render(['src' => 'uploads/videos/clip.mp4']);

        $this->assertStringContainsString('src="'.rtrim(config('app.url'), '/').'/storage/uploads/videos/clip.mp4"', $html);
        $this->assertStringNotContainsString('x-data', $html);
    }
}
