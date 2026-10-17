<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * services.icon was NOT NULL without a default while nothing above the database
 * treated it as required, so every admin service create died on MySQL error 1364.
 * The column is now gone; testimonials.role stays, but nullable, because its
 * form offers it as optional and a blank submit used to die on error 1048.
 */
class OptionalContentColumnTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): self
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        return $this->actingAs($user);
    }

    public function test_service_can_be_created_with_no_icon_field(): void
    {
        Storage::fake('public');

        $response = $this->actingAsAdmin()->post('/admin/services', [
            'title' => 'Windscreen Repair and Replacement',
            'short_description' => 'OEM-quality windscreen installations for all vehicle makes and models.',
            'full_description' => 'We provide professional windscreen repair and replacement services for all vehicle types.',
            'features' => "Same-day service available\nOEM and aftermarket options",
            'is_active' => '1',
            'sort_order' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $service = Service::firstWhere('slug', 'windscreen-repair-and-replacement');
        $this->assertNotNull($service);
        $this->assertSame('placeholder.gif', $service->image_path);
    }

    public function test_services_table_has_no_icon_column(): void
    {
        $this->assertFalse(Schema::hasColumn('services', 'icon'));
    }

    public function test_service_list_renders_without_an_icon_column(): void
    {
        Storage::fake('public');

        Service::create([
            'title' => 'Structural Glass',
            'slug' => 'structural-glass',
            'short_description' => 'A high level summary of services',
            'features' => [],
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAsAdmin()->get('/admin/services')
            ->assertOk()
            ->assertSee('Structural Glass')
            ->assertDontSee('no_icon');

        $this->get('/services')->assertOk();
        $this->get('/')->assertOk();
    }

    public function test_testimonial_can_be_created_with_a_blank_role(): void
    {
        Storage::fake('public');

        $response = $this->actingAsAdmin()->post('/admin/testimonials', [
            'name' => 'Naledi K.',
            'role' => '',
            'content' => 'They had my windscreen replaced the same afternoon.',
            'rating' => '5',
            'is_published' => '1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $testimonial = Testimonial::firstWhere('name', 'Naledi K.');
        $this->assertNotNull($testimonial);
        $this->assertNull($testimonial->role);
    }

    public function test_pages_render_a_testimonial_without_a_role(): void
    {
        Testimonial::create([
            'name' => 'Anonymous Reader',
            'role' => null,
            'content' => 'Workmanship guarantee held up after a month of rain.',
            'rating' => 5,
            'is_published' => true,
        ]);

        $this->actingAsAdmin()->get('/admin/testimonials')->assertOk();
        $this->get('/')->assertOk();
    }
}
