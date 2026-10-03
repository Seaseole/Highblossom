<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\BookingCancelledClientMail;
use App\Mail\BookingCompletedClientMail;
use App\Mail\BookingConfirmationMail;
use App\Mail\BookingConfirmedClientMail;
use App\Mail\BookingRescheduledClientMail;
use App\Models\Booking;
use App\Models\BookingEvent;
use App\Models\Inspection;
use App\Models\User;
use App\Services\BookingTimeline;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Covers the single milestone authority behind booking progress: what gets
 * recorded, what the customer is emailed, and what stays silent.
 */
class BookingTimelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

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

    /**
     * Create a not-yet-started inspection attached to a booking.
     */
    private function scheduledInspection(): Inspection
    {
        return Inspection::factory()->create([
            'ended_at' => null,
            'started_at' => null,
            'scheduled_at' => now()->addDay()->setHour(9)->setMinute(0)->setSecond(0),
        ]);
    }

    public function test_a_received_milestone_emails_the_client_once(): void
    {
        $booking = Booking::factory()->create(['client_email' => 'client@example.com']);

        app(BookingTimeline::class)->record($booking, BookingEvent::TYPE_RECEIVED);

        Mail::assertQueued(BookingConfirmationMail::class, 1);
        $this->assertDatabaseHas('booking_events', [
            'booking_id' => $booking->id,
            'type' => BookingEvent::TYPE_RECEIVED,
        ]);
    }

    public function test_a_repeated_confirmation_is_collapsed_into_a_single_email(): void
    {
        $booking = Booking::factory()->create();
        $timeline = app(BookingTimeline::class);

        $timeline->record($booking, BookingEvent::TYPE_CONFIRMED);
        $timeline->record($booking->fresh(), BookingEvent::TYPE_CONFIRMED);

        Mail::assertQueued(BookingConfirmedClientMail::class, 1);
        $this->assertSame(1, $booking->events()->count());
    }

    public function test_a_milestone_moves_the_booking_status(): void
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        app(BookingTimeline::class)->record($booking, BookingEvent::TYPE_CONFIRMED);

        $this->assertSame('confirmed', $booking->fresh()->status);
    }

    public function test_an_unknown_milestone_is_rejected(): void
    {
        $booking = Booking::factory()->create();

        $this->expectException(\InvalidArgumentException::class);

        app(BookingTimeline::class)->record($booking, 'teleported');
    }

    public function test_starting_an_inspection_records_progress_without_emailing(): void
    {
        $admin = $this->actingAsInspectionAdmin();
        $inspection = $this->scheduledInspection();
        $startedAt = now()->addDay()->setHour(9)->setMinute(15)->setSecond(0);

        $this->put(route('admin.inspections.update', $inspection), [
            'started_at' => $startedAt->format('Y-m-d\TH:i'),
        ])->assertRedirect();

        $this->assertSame('in_progress', $inspection->fresh()->status);
        $this->assertDatabaseHas('booking_events', [
            'booking_id' => $inspection->booking_id,
            'type' => BookingEvent::TYPE_STARTED,
            'user_id' => $admin->id,
        ]);
        Mail::assertNothingQueued();
    }

    public function test_completing_an_inspection_emails_the_client_with_the_summary(): void
    {
        $this->actingAsInspectionAdmin();
        $inspection = $this->scheduledInspection();
        $inspection->update(['started_at' => now()->addDay()->setHour(9)->setMinute(10)]);
        Mail::fake();

        $this->put(route('admin.inspections.update', $inspection), [
            'ended_at' => now()->addDay()->setHour(10)->setMinute(30)->format('Y-m-d\TH:i'),
            'client_summary' => 'Stone chips cleared, glass cured and ready to drive.',
        ])->assertRedirect();

        Mail::assertQueued(BookingCompletedClientMail::class, function (BookingCompletedClientMail $mail): bool {
            return $mail->summary === 'Stone chips cleared, glass cured and ready to drive.';
        });
    }

    public function test_moving_an_appointment_notifies_the_customer_of_the_old_slot(): void
    {
        $this->actingAsInspectionAdmin();
        $inspection = $this->scheduledInspection();
        $original = $inspection->scheduled_at;

        $this->put(route('admin.inspections.update', $inspection), [
            'scheduled_at' => $original->addDays(2)->format('Y-m-d\TH:i'),
        ])->assertRedirect();

        Mail::assertQueued(BookingRescheduledClientMail::class, function (BookingRescheduledClientMail $mail) use ($original): bool {
            return $mail->previousSlot?->equalTo($original) ?? false;
        });
    }

    public function test_a_cancelled_booking_stops_further_customer_email(): void
    {
        $booking = Booking::factory()->create();
        $timeline = app(BookingTimeline::class);

        $timeline->record($booking, BookingEvent::TYPE_CANCELLED);
        $late = $timeline->record($booking->fresh(), BookingEvent::TYPE_COMPLETED, summary: 'Late entry');

        $this->assertNull($late);
        Mail::assertQueued(BookingCancelledClientMail::class, 1);
        Mail::assertNotQueued(BookingCompletedClientMail::class);
        $this->assertSame('cancelled', $booking->fresh()->status);
        $this->assertSame(1, $booking->events()->count());
    }

    public function test_an_admin_cannot_move_a_cancelled_booking_on(): void
    {
        $this->actingAsInspectionAdmin();
        $booking = Booking::factory()->create(['status' => 'cancelled']);

        $this->patch(route('admin.bookings.update-status', $booking), ['status' => 'completed'])
            ->assertSessionHas('error');

        $this->assertSame('cancelled', $booking->fresh()->status);
        Mail::assertNothingQueued();
    }

    public function test_reverting_a_cancelled_booking_to_pending_sends_no_email(): void
    {
        $this->actingAsInspectionAdmin();
        $booking = Booking::factory()->create(['status' => 'cancelled']);

        $this->patch(route('admin.bookings.update-status', $booking), ['status' => 'pending'])
            ->assertSessionHas('success');

        $this->assertSame('pending', $booking->fresh()->status);
        Mail::assertNothingQueued();
    }

    public function test_the_cancellation_itself_still_reaches_the_customer(): void
    {
        $booking = Booking::factory()->create(['status' => 'confirmed']);

        app(BookingTimeline::class)->record($booking, BookingEvent::TYPE_CANCELLED);

        Mail::assertQueued(BookingCancelledClientMail::class, 1);
        $this->assertSame('cancelled', $booking->fresh()->status);
    }

    public function test_the_status_page_shows_the_customer_their_progress(): void
    {
        $booking = Booking::factory()->create();
        $timeline = app(BookingTimeline::class);
        $timeline->record($booking, BookingEvent::TYPE_RECEIVED);
        $timeline->record($booking->fresh(), BookingEvent::TYPE_CONFIRMED);
        $timeline->record(
            $booking->fresh(),
            BookingEvent::TYPE_COMPLETED,
            summary: 'Windscreen replaced and calibrated.'
        );

        $this->get(URL::signedRoute('bookings.confirmation', $booking))
            ->assertOk()
            ->assertSee('Progress')
            ->assertSee('Booking received')
            ->assertSee('Appointment completed')
            ->assertSee('Windscreen replaced and calibrated.');
    }
}
