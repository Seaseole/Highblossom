<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Mail\BookingConfirmedClientMail;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $booking->load(['user', 'inspection.staff']);

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
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        if ($oldStatus !== 'confirmed' && $request->status === 'confirmed') {
            Mail::to($booking->client_email)->queue(new BookingConfirmedClientMail($booking));
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
