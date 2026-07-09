<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreBookingAction;
use App\Http\Requests\Bookings\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingCalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

/**
 * Handle public booking form display, submission, and confirmation.
 */
final class BookingController extends Controller
{
    public function __construct(
        protected StoreBookingAction $storeBookingAction
    ) {}

    /**
     * Display the public booking form.
     */
    public function create(): View
    {
        $calendar = app(BookingCalendarService::class)->getMonths(12);
        $oldScheduledAt = old('scheduled_at', '');

        return view('site.booking', compact('calendar', 'oldScheduledAt'));
    }

    /**
     * Handle booking form submission.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $result = $this->storeBookingAction->execute($request);

        if ($result['success']) {
            return redirect()->to(URL::signedRoute('bookings.confirmation', $result['booking']))->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message'])->withInput();
    }

    /**
     * Display the booking confirmation page.
     */
    public function confirmation(Booking $booking): View
    {
        return view('bookings.confirmation', compact('booking'));
    }
}
