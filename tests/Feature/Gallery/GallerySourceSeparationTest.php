<?php

declare(strict_types=1);

namespace Tests\Feature\Gallery;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the split between the media library and the curated public gallery.
 *
 * Both surfaces write to `gallery_images`, so a content block's library picker
 * must never publish its uploads onto /gallery, /gallery/{id} or sitemap.xml.
 */
class GallerySourceSeparationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Keep fake uploads off the developer's real storage disk.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /**
     * Seed permissions and act as a user that can manage the gallery.
     */
    private function actingAsAdmin(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);
    }

    /**
     * Create a gallery category the admin form requires.
     */
    private function makeCategory(string $slug = 'automotive'): GalleryCategory
    {
        return GalleryCategory::create([
            'name' => ucfirst($slug),
            'slug' => $slug,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * Upload an image through the picker's media library endpoint.
     */
    private function uploadToLibrary(string $title = 'Chip macro'): GalleryImage
    {
        $this->post(route('admin.media-library.upload'), [
            'upload' => UploadedFile::fake()->image('chip.jpg'),
            'title' => $title,
            'category' => 'other',
        ])->assertOk();

        return GalleryImage::where('title', $title)->firstOrFail();
    }

    public function test_a_library_upload_is_recorded_as_a_hidden_library_row(): void
    {
        $this->actingAsAdmin();

        $image = $this->uploadToLibrary();

        $this->assertSame(GalleryImage::SOURCE_LIBRARY, $image->source);
        $this->assertFalse($image->is_active);
    }

    public function test_a_library_upload_does_not_reach_the_public_gallery(): void
    {
        $this->actingAsAdmin();

        $image = $this->uploadToLibrary('Windscreen close up');

        $this->get('/gallery')
            ->assertOk()
            ->assertDontSee('Windscreen close up', false);

        $this->get('/gallery/'.$image->id)->assertNotFound();
    }

    public function test_a_library_upload_is_left_out_of_the_sitemap(): void
    {
        $this->actingAsAdmin();

        $image = $this->uploadToLibrary();

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringNotContainsString(route('gallery.show', ['galleryImage' => $image]), $xml);
    }

    public function test_the_picker_can_still_browse_library_rows(): void
    {
        $this->actingAsAdmin();

        $image = $this->uploadToLibrary('Browseable asset');

        $this->getJson(route('admin.media-library.index'))
            ->assertOk()
            ->assertJsonPath('images.0.name', 'Browseable asset')
            ->assertJsonPath('images.0.id', $image->id);
    }

    public function test_an_admin_gallery_entry_is_published_publicly(): void
    {
        $this->actingAsAdmin();
        $category = $this->makeCategory();

        $this->post(route('admin.gallery.store'), [
            'title' => 'Salon install',
            'description' => 'A finished windscreen replacement.',
            'image' => UploadedFile::fake()->image('install.jpg'),
            'gallery_category_id' => $category->id,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ])->assertRedirect(route('admin.gallery.index'));

        $image = GalleryImage::firstWhere('title', 'Salon install');

        $this->assertSame(GalleryImage::SOURCE_GALLERY, $image->source);
        $this->get('/gallery')->assertOk()->assertSee('Salon install', false);
        $this->get('/gallery/'.$image->id)->assertOk();
        $this->get('/sitemap.xml')->assertOk()
            ->assertSee(route('gallery.show', ['galleryImage' => $image]), false);
    }

    public function test_the_admin_gallery_list_hides_library_rows(): void
    {
        $this->actingAsAdmin();

        $this->uploadToLibrary('Library only entry');

        $this->get(route('admin.gallery.index'))
            ->assertOk()
            ->assertDontSee('Library only entry', false);
    }
}
