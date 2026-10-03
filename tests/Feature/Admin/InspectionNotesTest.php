<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Inspection;
use App\Models\InspectionNote;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Internal, timestamped inspection notes: who wrote them, that they persist, and
 * that they never leak onto the customer's status page.
 */
class InspectionNotesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed permissions and return a user who can edit inspections.
     */
    private function actingAsInspectionAdmin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));
        $this->actingAs($user);

        return $user;
    }

    private function inspection(): Inspection
    {
        return Inspection::factory()->create(['started_at' => null, 'ended_at' => null]);
    }

    public function test_an_admin_can_add_a_note_that_is_attributed_and_stamped(): void
    {
        $admin = $this->actingAsInspectionAdmin();
        $inspection = $this->inspection();

        $this->post(route('admin.inspections.notes.store', $inspection), [
            'body' => 'Rear passenger window mechanism sticking.',
        ])->assertRedirect();

        $note = $inspection->notes()->firstOrFail();
        $this->assertSame('Rear passenger window mechanism sticking.', $note->body);
        $this->assertSame($admin->id, $note->user_id);
        $this->assertNotNull($note->created_at);
    }

    public function test_a_blank_note_is_rejected(): void
    {
        $this->actingAsInspectionAdmin();
        $inspection = $this->inspection();

        $this->from(route('admin.inspections.show', $inspection))
            ->post(route('admin.inspections.notes.store', $inspection), ['body' => ''])
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('inspection_notes', 0);
    }

    public function test_an_action_item_can_be_closed_and_reopened(): void
    {
        $this->actingAsInspectionAdmin();
        $inspection = $this->inspection();
        $note = InspectionNote::create([
            'inspection_id' => $inspection->id,
            'body' => 'Order a replacement seal.',
            'is_action' => true,
        ]);

        $this->patch(route('admin.inspections.notes.done', [$inspection, $note]))->assertRedirect();

        $this->assertNotNull($note->fresh()->done_at);
        $this->assertFalse($note->fresh()->isOpen());

        $this->patch(route('admin.inspections.notes.done', [$inspection, $note]))->assertRedirect();

        $this->assertNull($note->fresh()->done_at);
        $this->assertTrue($note->fresh()->isOpen());
    }

    public function test_a_note_is_visible_to_admins_with_its_timestamp(): void
    {
        $this->actingAsInspectionAdmin();
        $inspection = $this->inspection();
        $inspection->notes()->create(['body' => 'Seal ordered, fitting Thursday.', 'is_action' => true]);

        $this->get(route('admin.inspections.show', $inspection))
            ->assertSee('Seal ordered, fitting Thursday.')
            ->assertSee('Action open');
    }

    public function test_a_note_never_reaches_the_customer_status_page(): void
    {
        $booking = Booking::factory()->create();
        $inspection = Inspection::factory()->create(['booking_id' => $booking->id]);
        $inspection->notes()->create(['body' => 'Customer owes for a second panel.']);

        $this->get($url = URL::signedRoute('bookings.confirmation', $booking))
            ->assertOk()
            ->assertDontSee('Customer owes for a second panel.');
    }

    public function test_a_note_cannot_be_reached_through_another_inspection(): void
    {
        $this->actingAsInspectionAdmin();
        $other = $this->inspection();
        $note = $this->inspection()->notes()->create(['body' => 'Mine.']);

        $this->delete(route('admin.inspections.notes.destroy', [$other, $note]))->assertNotFound();

        $this->assertDatabaseHas('inspection_notes', ['id' => $note->id]);
    }

    public function test_a_user_without_inspection_permissions_cannot_add_notes(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $visitor = User::factory()->create();
        $visitor->assignRole(Role::findOrCreate('Customer', 'web'));
        $this->actingAs($visitor);

        $inspection = $this->inspection();

        $this->post(route('admin.inspections.notes.store', $inspection), ['body' => 'Nope.'])
            ->assertForbidden();
    }
}
