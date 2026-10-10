<?php

declare(strict_types=1);

namespace Tests\Feature\Content;

use App\Actions\Content\RelocateTempUploadsAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Cover relocation of temporary content-block uploads, including the ordered
 * image set carried by a single image block.
 */
class RelocateImageSetUploadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_relocates_a_temporary_upload_in_an_image_set(): void
    {
        Storage::fake('temp');
        Storage::fake('public');

        Storage::disk('temp')->put('uploads/images/first.jpg', 'first');
        Storage::disk('temp')->put('uploads/images/second.jpg', 'second');

        $blocks = [[
            'type' => 'image',
            'attributes' => [
                'src' => '',
                'images' => [
                    ['src' => 'temp://uploads/images/first.jpg', 'alt' => 'First', 'caption' => null],
                    ['src' => 'temp://uploads/images/second.jpg', 'alt' => 'Second', 'caption' => 'Pair'],
                ],
            ],
        ]];

        $relocated = app(RelocateTempUploadsAction::class)->execute($blocks);

        $this->assertStringContainsString('storage/uploads/images/first.jpg', $relocated[0]['attributes']['images'][0]['src']);
        $this->assertStringContainsString('storage/uploads/images/second.jpg', $relocated[0]['attributes']['images'][1]['src']);
        $this->assertTrue(Storage::disk('public')->exists('uploads/images/first.jpg'));
        $this->assertTrue(Storage::disk('public')->exists('uploads/images/second.jpg'));
        $this->assertFalse(Storage::disk('temp')->exists('uploads/images/first.jpg'));
        $this->assertSame('Pair', $relocated[0]['attributes']['images'][1]['caption']);
    }

    /**
     * Library-sourced images are already permanent and must survive untouched.
     */
    public function test_it_leaves_permanent_image_set_urls_alone(): void
    {
        Storage::fake('temp');
        Storage::fake('public');

        $url = 'http://localhost/storage/gallery/keeps-me.jpg';

        $blocks = [[
            'type' => 'image',
            'attributes' => ['src' => '', 'images' => [['src' => $url, 'alt' => 'Library', 'caption' => null]]],
        ]];

        $relocated = app(RelocateTempUploadsAction::class)->execute($blocks);

        $this->assertSame($url, $relocated[0]['attributes']['images'][0]['src']);
    }

    public function test_it_relocates_the_legacy_single_image_source(): void
    {
        Storage::fake('temp');
        Storage::fake('public');

        Storage::disk('temp')->put('uploads/images/legacy.jpg', 'legacy');

        $blocks = [['type' => 'image', 'attributes' => ['src' => 'temp://uploads/images/legacy.jpg']]];

        $relocated = app(RelocateTempUploadsAction::class)->execute($blocks);

        $this->assertStringContainsString('storage/uploads/images/legacy.jpg', $relocated[0]['attributes']['src']);
    }
}
