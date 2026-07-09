<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\BookingCreatedEvent;
use App\Mail\BookingConfirmationMail;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\NewBookingStaffNotification;
use App\Services\Contracts\AvailabilityServiceInterface;
use App\Services\IdempotencyService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
    public function __construct(
        protected Booking $booking,
        protected IdempotencyService $idempotencyService,
        protected AvailabilityServiceInterface $availabilityService
    ) {}

    /**
     * Execute the full booking submission flow.
     *
     * @return array<string, mixed>
     */
    public function execute(Request $request): array
    {
        $idempotencyResult = $this->checkIdempotency($request);
        if ($idempotencyResult) {
            return $idempotencyResult;
        }

        try {
            $booking = DB::transaction(function () use ($request) {
                if (! $this->availabilityService->isSlotAvailable($request->input('scheduled_at'))) {
                    return null;
                }

                $this->markIdempotencyProcessed($request);

                return $this->createBooking($request);
            });

            if ($booking === null) {
                return [
                    'success' => false,
                    'message' => 'That time slot is no longer available. Please select another time.',
                ];
            }

            // Notifications — log failures but never break the response
            try {
                Mail::to($booking->client_email)->queue(new BookingConfirmationMail($booking));

                $staff = User::permission('update bookings')->get();
                if ($staff->isNotEmpty()) {
                    Notification::send($staff, new NewBookingStaffNotification($booking));
                }

                event(new BookingCreatedEvent($booking));
            } catch (\Exception $e) {
                Log::error('Booking post-creation notification error: '.$e->getMessage());
            }

            return [
                'success' => true,
                'message' => 'Your booking has been submitted successfully. We will contact you shortly.',
                'booking' => $booking,
            ];
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'bookings_scheduled_at_unique')) {
                return [
                    'success' => false,
                    'message' => 'That time slot was just booked by someone else. Please choose a different time.',
                ];
            }

            Log::error('Booking database error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'There was an error submitting your booking. Please try again.',
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
     * Check for duplicate submission using idempotency token.
     *
     * @return array<string, mixed>|null
     */
    private function checkIdempotency(Request $request): ?array
    {
        $token = $request->input('_idempotency_token');

        if (! empty($token) && $this->idempotencyService->isProcessed($token)) {
            return [
                'success' => true,
                'duplicate' => true,
                'message' => 'Your booking has already been submitted. Please wait a moment before submitting another.',
            ];
        }

        return null;
    }

    /**
     * Mark idempotency token as processed.
     */
    private function markIdempotencyProcessed(Request $request): void
    {
        $token = $request->input('_idempotency_token');
        if (! empty($token)) {
            $this->idempotencyService->markProcessed($token);
        }
    }

    /**
     * Create booking record in database.
     */
    private function createBooking(Request $request): Booking
    {
        return $this->booking->create([
            'client_name' => $request->input('client_name'),
            'client_email' => $request->input('client_email'),
            'client_phone' => $request->input('client_phone'),
            'vehicle_details' => $request->input('vehicle_details'),
            'scheduled_at' => $request->input('scheduled_at'),
            'location' => $request->input('location'),
            'client_address' => $request->input('client_address'),
            'status' => 'pending',
            'total_price' => 0,
        ]);
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
}
