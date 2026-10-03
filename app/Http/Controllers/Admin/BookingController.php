<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\BookingEvent;
use App\Services\BookingTimeline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Manage CRUD operations for bookings.
 */
final class BookingController
{
    /**
     * Display a paginated list of bookings.
     */
    public function index(): View
    {
        $bookings = Booking::with(['inspection', 'user'])
            ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
            ->when(request('search'), fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('client_name', 'like', "%{$s}%")
                    ->orWhere('client_email', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'inspection.staff', 'inspection.notes.author', 'events.actor']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update the specified booking.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return back()->with('success', 'Booking updated.');
    }

    /**
     * Update the status of the specified booking.
     */
    public function updateStatus(Request $request, Booking $booking, BookingTimeline $timeline): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $type = match ($request->status) {
            'confirmed' => BookingEvent::TYPE_CONFIRMED,
            'completed' => BookingEvent::TYPE_COMPLETED,
            'cancelled' => BookingEvent::TYPE_CANCELLED,
            default => null,
        };

        // Reverting to pending has no milestone of its own, so it stays a plain
        // status write rather than re-notifying the customer that they booked.
        if ($type === null) {
            $booking->update(['status' => $request->status]);

            return back()->with('success', 'Booking status updated successfully.');
        }

        // A cancelled booking is terminal: the timeline refuses to move it on, and
        // pending is the documented way to put it back in play.
        if ($timeline->record($booking, $type, actorId: $request->user()?->id) === null) {
            if ($booking->isCancelled()) {
                return back()->with('error', 'This booking is cancelled. Set it back to pending before moving it on.');
            }

            return back()->with('success', 'Booking is already '.$request->status.'.');
        }

        return back()->with('success', 'Booking status updated successfully.');
    }

    /**
     * Delete the specified booking.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
