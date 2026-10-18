<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\BookingCreatedEvent;
use App\Models\Booking;
use App\Models\BookingEvent;
use App\Models\User;
use App\Notifications\NewBookingStaffNotification;
use App\Services\BookingTimeline;
use App\Services\Contracts\AvailabilityServiceInterface;
use App\Services\IdempotencyService;
use Illuminate\Database\DeadlockException;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Handle the submission of a public booking request.
 *
 * Processes incoming booking requests with idempotency protection,
 * availability checks, creates the booking record, and triggers
 * email notifications.
 */
class StoreBookingAction
{
    /**
     * A slot contest that loses to a concurrent writer is retried rather than
     * reported as a failure, because the customer did nothing wrong. One retry is
     * enough: a second collision means the slot is genuinely being taken.
     */
    private const LOCK_ATTEMPTS = 2;

    public function __construct(
        protected Booking $booking,
        protected IdempotencyService $idempotencyService,
        protected AvailabilityServiceInterface $availabilityService,
        protected BookingTimeline $timeline
    ) {}

    /**
     * Execute the full booking submission flow.
     *
     * @return array<string, mixed>
     */
    public function execute(Request $request): array
    {
        $token = (string) $request->input('_idempotency_token');

        if ($this->idempotencyService->isProcessed($token)) {
            return $this->duplicateResult($token);
        }

        try {
            $booking = $this->reserve($request, $token);

            if ($booking === null) {
                return [
                    'success' => false,
                    'slot_taken' => true,
                    'message' => __('booking.slot_already_taken'),
                ];
            }

            $this->notify($booking);

            return [
                'success' => true,
                'message' => 'Your booking has been submitted successfully. We will contact you shortly.',
                'booking' => $booking,
            ];
        } catch (\Exception $e) {
            Log::error('Booking submission error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'There was an error submitting your booking. Please try again.',
            ];
        }
    }

    /**
     * Invoke the action as a callable.
     *
     * @return array<string, mixed>
     */
    public function __invoke(Request $request): array
    {
        return $this->execute($request);
    }

    /**
     * Claim the slot and write the booking, or return null when someone beat us to it.
     *
     * The availability re-check and the insert share one transaction, and the check
     * takes a row lock, so two customers cannot both be told there is room. A
     * deadlock — which is what a lost race looks like to InnoDB — is retried.
     */
    private function reserve(Request $request, string $token): ?Booking
    {
        for ($attempt = 1; $attempt <= self::LOCK_ATTEMPTS; $attempt++) {
            try {
                return DB::transaction(function () use ($request, $token): ?Booking {
                    if (! $this->availabilityService->isSlotAvailableForUpdate(
                        (string) $request->input('scheduled_at'),
                        $this->nullableString($request->input('service_type')),
                        $this->nullableString($request->input('location')),
                    )) {
                        return null;
                    }

                    $booking = $this->createBooking($request);

                    $this->idempotencyService->markProcessed($token, $booking->id);

                    return $booking;
                });
            } catch (DeadlockException $e) {
                if ($attempt === self::LOCK_ATTEMPTS) {
                    Log::warning('Booking slot contest exhausted its retries: '.$e->getMessage());

                    return null;
                }

                usleep(120_000);
            }
        }

        return null;
    }

    /**
     * Build the booking, including the two values the customer did not choose.
     *
     * The duration is a snapshot of what the service took at booking time, and the
     * reference is derived from the id, which only exists after the insert.
     */
    private function createBooking(Request $request): Booking
    {
        $scheduledAt = (string) $request->input('scheduled_at');
        $serviceType = $this->nullableString($request->input('service_type'));

        $booking = $this->booking->create([
            'client_name' => $request->input('client_name'),
            'client_email' => $request->input('client_email'),
            'client_phone' => $request->input('client_phone'),
            'prefers_whatsapp' => (bool) $request->input('prefers_whatsapp'),
            'service_type' => $serviceType,
            'damage_type' => $this->nullableString($request->input('damage_type')),
            'vehicle_make' => $request->input('vehicle_make'),
            'vehicle_model' => $request->input('vehicle_model'),
            'vehicle_year' => $request->input('vehicle_year'),
            'vehicle_details' => $request->input('vehicle_details'),
            'damage_photo_path' => $this->storeDamagePhoto($request),
            'scheduled_at' => $scheduledAt,
            'duration_minutes' => $this->availabilityService->durationFor($serviceType),
            'location' => $request->input('location'),
            'client_address' => $request->input('client_address'),
            'status' => 'pending',
            'total_price' => 0,
        ]);

        $booking->forceFill(['reference' => $this->referenceFor($booking->id)])->save();

        return $booking;
    }

    /**
     * The customer-facing reference, in the shape every booking email already uses.
     */
    private function referenceFor(int $id): string
    {
        return 'HB-'.str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Shrink and store the damage photo, or null.
     *
     * A photo the shop cannot decode must not cost the customer their appointment,
     * so a failure here is logged and the booking carries on without it.
     */
    private function storeDamagePhoto(Request $request): ?string
    {
        $file = $request->file('damage_photo');

        if ($file === null || ! $file->isValid()) {
            return null;
        }

        try {
            return Image::fromUpload($file)
                ->scale(width: 1600, height: 1600)
                ->toWebp()
                ->quality(80)
                ->store('bookings/damage', 'public');
        } catch (ImageException $e) {
            Log::error('Failed to process booking damage photo: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Record the milestone and tell the customer and the shop.
     *
     * Wrapped so a mail or queue outage cannot turn a booked appointment into an
     * error page: the booking is already committed at this point.
     */
    private function notify(Booking $booking): void
    {
        try {
            $this->timeline->record($booking, BookingEvent::TYPE_RECEIVED);

            $staff = User::permission('update bookings')->get();
            if ($staff->isNotEmpty()) {
                Notification::send($staff, new NewBookingStaffNotification($booking));
            }

            event(new BookingCreatedEvent($booking));
        } catch (\Exception $e) {
            Log::error('Booking post-creation notification error: '.$e->getMessage());
        }
    }

    /**
     * Hand a repeat submission the booking it already created.
     *
     * Returning success without the booking used to send the controller looking for
     * a key that was never set, so a double-click or a back-button resubmit lost the
     * customer their confirmation page.
     *
     * @return array<string, mixed>
     */
    private function duplicateResult(string $token): array
    {
        $bookingId = $this->idempotencyService->result($token);
        $booking = is_int($bookingId) ? Booking::find($bookingId) : null;

        if ($booking !== null) {
            return [
                'success' => true,
                'duplicate' => true,
                'booking' => $booking,
                'message' => 'Your booking has already been submitted.',
            ];
        }

        return [
            'success' => false,
            'duplicate' => true,
            'message' => 'Your booking has already been submitted. Please check your email for the confirmation.',
        ];
    }

    /**
     * Read a request value that may legitimately be absent.
     */
    private function nullableString(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
