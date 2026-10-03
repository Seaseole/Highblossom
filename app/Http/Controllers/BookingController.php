<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreBookingAction;
use App\Http\Requests\Bookings\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingCalendarService;
use App\Services\Contracts\AvailabilityServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ViewErrorBag;
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
    public function create(BookingCalendarService $calendar, AvailabilityServiceInterface $availability): View
    {
        return view('site.booking', [
            'calendar' => $calendar->getMonths(3),
            'days' => $availability->upcomingOpenDays(14),
            'initialStep' => $this->initialStep(),
        ]);
    }

    /**
     * Resume the wizard on the step the customer actually failed, so a validation
     * error no longer dumps a completed form back onto the calendar.
     */
    private function initialStep(): int
    {
        /** @var ViewErrorBag $errors */
        $errors = session('errors');

        if ($errors !== null) {
            if ($errors->hasAny(['scheduled_at'])) {
                return 1;
            }

            if ($errors->hasAny(['client_name', 'client_email', 'client_phone'])) {
                return 2;
            }

            if ($errors->hasAny(['vehicle_details', 'location', 'client_address'])) {
                return 3;
            }
        }

        return session('error') ? 3 : 1;
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
        $booking->load('events');

        return view('bookings.confirmation', compact('booking'));
    }
}
