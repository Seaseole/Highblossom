<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Http\Resources\Api\BookingResource;
use App\Models\Booking;
use App\Models\BookingEvent;
use App\Services\BookingTimeline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Read and move bookings for the staff app.
 *
 * Authorization is enforced by the `can:view bookings` / `can:update bookings`
 * route middleware, mirroring the web admin. Status changes go through the same
 * BookingTimeline the admin uses, so a booking is never emailed twice and a
 * cancelled booking stays terminal until it is put back to pending.
 */
final class BookingController extends Controller
{
    /**
     * List bookings, optionally filtered by status and a client/reference search.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $bookings = Booking::query()
            ->with('inspection')
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('search'), fn ($q, $search) => $q->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                    ->orWhere('client_email', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate($this->perPage($request));

        return BookingResource::collection($bookings);
    }

    /**
     * Show one booking with its inspection and customer-visible timeline.
     */
    public function show(Booking $booking): BookingResource
    {
        $booking->load(['inspection.staff', 'inspection.notes.author', 'events.actor']);

        return new BookingResource($booking);
    }

    /**
     * Update editable booking fields (currently the quoted price).
     */
    public function update(UpdateBookingRequest $request, Booking $booking): BookingResource
    {
        $booking->update($request->validated());

        return new BookingResource($booking->fresh());
    }

    /**
     * Move a booking through its lifecycle: pending, confirmed, completed, cancelled.
     */
    public function updateStatus(Request $request, Booking $booking, BookingTimeline $timeline): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $type = match ($validated['status']) {
            'confirmed' => BookingEvent::TYPE_CONFIRMED,
            'completed' => BookingEvent::TYPE_COMPLETED,
            'cancelled' => BookingEvent::TYPE_CANCELLED,
            default => null,
        };

        // Reverting to pending has no milestone of its own, so it stays a plain write.
        if ($type === null) {
            $booking->update(['status' => $validated['status']]);

            return $this->respond($booking, 'Booking status updated.');
        }

        if ($timeline->record($booking, $type, actorId: $request->user()?->id) === null) {
            if ($booking->isCancelled()) {
                return response()->json([
                    'message' => 'This booking is cancelled. Set it back to pending before moving it on.',
                ], 409);
            }

            return $this->respond($booking, 'Booking is already '.$validated['status'].'.');
        }

        return $this->respond($booking, 'Booking status updated.');
    }

    /**
     * Return the refreshed booking with a flash-style message the app can surface.
     */
    private function respond(Booking $booking, string $message): JsonResponse
    {
        $booking->load(['inspection.staff', 'inspection.notes.author', 'events.actor']);

        return response()->json([
            'data' => new BookingResource($booking),
            'message' => $message,
        ]);
    }

    /**
     * Resolve a clamped page size from the request.
     */
    private function perPage(Request $request): int
    {
        return min(100, max(1, $request->integer('per_page', 15)));
    }
}
