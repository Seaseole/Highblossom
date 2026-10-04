<?php

declare(strict_types=1);

namespace Tests\Feature\Uploads;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Exercises the upload-path adoption rules through the real admin routes, so a
 * regression in the form wiring (not just the rule) is caught.
 */
final class Phase4PathAdoptionHttpTest extends TestCase
{
    use RefreshDatabase;

    private const HASH = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));
        $this->actingAs($user);
    }

    public function test_service_update_keeps_a_stored_path_and_rejects_a_foreign_one(): void
    {
        $service = Service::create([
            'title' => 'Windscreen Replacement',
            'slug' => 'windscreen-replacement',
            'icon' => 'fa-car',
            'short_description' => 'Replacement',
            'image_path' => 'services/'.self::HASH.'.webp',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->put(route('admin.services.update', $service), $this->servicePayload([
            'image_path' => 'https://evil.test/'.self::HASH.'.webp',
        ]))->assertSessionHasErrors('image_path');

        $this->assertSame('services/'.self::HASH.'.webp', $service->fresh()->image_path);

        // The edit form must hand back the relative key, never the asset() URL,
        // otherwise the update it posts can never satisfy the rule.
        $this->get(route('admin.services.edit', $service))
            ->assertOk()
            ->assertSee('value="services/'.self::HASH.'.webp"', false);

        $this->put(route('admin.services.update', $service), $this->servicePayload([
            'image_path' => 'services/'.self::HASH.'.webp',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('services/'.self::HASH.'.webp', $service->fresh()->image_path);
    }

    public function test_gallery_update_accepts_the_placeholder_sentinel_and_rejects_traversal(): void
    {
        $category = GalleryCategory::create([
            'name' => 'Fleet',
            'slug' => 'fleet',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $item = GalleryImage::create([
            'title' => 'Fleet shot',
            'image_path' => 'gallery/'.self::HASH.'.webp',
            'gallery_category_id' => $category->id,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->put(route('admin.gallery.update', $item), $this->galleryPayload($category, [
            'image_path' => 'gallery/../../../../.env',
        ]))->assertSessionHasErrors('image_path');

        $this->assertSame('gallery/'.self::HASH.'.webp', $item->fresh()->image_path);

        $this->put(route('admin.gallery.update', $item), $this->galleryPayload($category, [
            'image_path' => 'placeholder.gif',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('placeholder.gif', $item->fresh()->image_path);
    }

    public function test_company_settings_reject_a_logo_path_outside_the_upload_folders(): void
    {
        DB::table('company_settings')->insert([
            'key' => 'business_logo',
            'value' => 'settings/'.self::HASH.'.webp',
            'type' => 'text',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->put(route('admin.settings.update'), $this->settingsPayload([
            'business_logo_path' => 'settings/../../../bootstrap/cache/shell.webp',
        ]))->assertSessionHasErrors('business_logo_path');

        $this->assertSame('settings/'.self::HASH.'.webp', $this->rawSetting('business_logo'));

        $this->put(route('admin.settings.update'), $this->settingsPayload([
            'business_logo_path' => 'settings/'.str_repeat('b', 40).'.webp',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('settings/'.str_repeat('b', 40).'.webp', $this->rawSetting('business_logo'));
    }

    public function test_post_update_rejects_a_foreign_featured_image_path(): void
    {
        $post = Post::create([
            'title' => 'Chip on the windscreen',
            'slug' => 'chip-on-the-windscreen',
            'excerpt' => 'Short',
            'content' => [],
            'featured_image_path' => 'uploads/blog/'.self::HASH.'.webp',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->put(route('admin.posts.update', $post), [
            'title' => 'Chip on the windscreen',
            'status' => 'published',
            'featured_image_path' => 'uploads/blog/../../../config/database.webp',
        ])->assertSessionHasErrors('featured_image_path');

        $this->assertSame('uploads/blog/'.self::HASH.'.webp', $post->fresh()->featured_image_path);
    }

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function servicePayload(array $overrides): array
    {
        return array_merge([
            'title' => 'Windscreen Replacement',
            'short_description' => 'Replacement',
            'is_active' => true,
            'sort_order' => 0,
            'remove_image' => false,
        ], $overrides);
    }

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function galleryPayload(GalleryCategory $category, array $overrides): array
    {
        return array_merge([
            'title' => 'Fleet shot',
            'gallery_category_id' => $category->id,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
            'remove_image' => false,
        ], $overrides);
    }

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private function settingsPayload(array $overrides): array
    {
        return array_merge([
            'company_name' => 'Highblossom PTY LTD',
            'primary_email' => 'jseaseole@highblossom.net',
            'address' => 'Plot 123, Broadhurst, Gaborone',
            'primary_phone' => '+267 123 4567',
            'whatsapp_number_default' => '+267 123 4567',
            'timezone' => 'Africa/Gaborone',
            'locale' => 'en_GB',
            'date_format' => 'd/M/Y',
            'time_format' => 'H:i',
            'time_format_display' => '12',
            'booking_lead_time_hours' => 2,
            'currency_symbol' => 'P',
            'announcement_active' => false,
        ], $overrides);
    }

    /**
     * Read the stored row directly; the settings cache can be stale within a request.
     */
    private function rawSetting(string $key): ?string
    {
        return DB::table('company_settings')->where('key', $key)->value('value');
    }
}
