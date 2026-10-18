<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreBookingAction;
use App\Http\Requests\Bookings\StoreBookingRequest;
use App\Models\Booking;
use App\Services\BookingCalendarService;
use App\Services\BookingIcsService;
use App\Services\Contracts\AvailabilityServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle public booking form display, submission, and confirmation.
 */
final class BookingController extends Controller
{
    /** How many months of the calendar to render up front. */
    private const CALENDAR_MONTHS = 3;

    /** How many bookable days to offer as quick picks. */
    private const QUICK_PICK_DAYS = 14;

    public function __construct(
        protected StoreBookingAction $storeBookingAction
    ) {}

    /**
     * Display the public booking form.
     *
     * The calendar and the day strip are built for the service and location the
     * customer already chose when this is a resume after a failed submit. Rendering
     * them for the defaults would offer a slot the server is about to reject.
     */
    public function create(Request $request, BookingCalendarService $calendar, AvailabilityServiceInterface $availability): View
    {
        $service = $this->contextValue($request, 'service_type');
        $location = $this->contextValue($request, 'location');

        return view('site.booking', [
            'calendar' => $calendar->getMonths(self::CALENDAR_MONTHS, $service, $location),
            'days' => $availability->upcomingOpenDays(self::QUICK_PICK_DAYS, $service, $location),
            'serviceDuration' => $availability->durationFor($service),
            'initialStep' => $this->initialStep(),
            'serviceType' => $service,
            'location' => $location,
        ]);
    }

    /**
     * Handle booking form submission.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $result = $this->storeBookingAction->execute($request);

        if ($result['success']) {
            return redirect()
                ->to(URL::signedRoute('bookings.confirmation', $result['booking']))
                ->with('success', $result['message']);
        }

        // A slot lost to another customer is not a mistake the customer made, so it
        // goes back to the picker with everything else they typed still in place.
        return redirect()
            ->back()
            ->with('error', $result['message'])
            ->with('booking_failed_step', empty($result['slot_taken']) ? null : 2)
            ->withInput();
    }

    /**
     * Display the booking confirmation page.
     */
    public function confirmation(Booking $booking): View
    {
        $booking->load('events');

        return view('bookings.confirmation', compact('booking'));
    }

    /**
     * Download the booking as a calendar file.
     */
    public function calendar(Booking $booking, BookingIcsService $ics): RedirectResponse|Response
    {
        $body = $ics->build($booking);

        if ($body === '') {
            return redirect()
                ->route('bookings.create')
                ->with('error', 'This booking has no appointment time to add to a calendar yet.');
        }

        return response($body, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$booking->displayReference().'.ics"',
        ]);
    }

    /**
     * Resume the wizard on the step the customer actually failed.
     *
     * Without this, any validation error dumped a completed form back onto the
     * calendar and the customer had to click forward through their own answers.
     */
    private function initialStep(): int
    {
        $forced = session('booking_failed_step');

        if (is_int($forced) && $forced >= 1) {
            return $forced;
        }

        /** @var ViewErrorBag|null $errors */
        $errors = session('errors');

        if ($errors !== null) {
            foreach (StoreBookingRequest::STEP_FIELDS as $step => $fields) {
                if ($errors->hasAny($fields)) {
                    return $step;
                }
            }
        }

        return 1;
    }

    /**
     * Read a booking context value from the query string or the flashed old input.
     */
    private function contextValue(Request $request, string $key): ?string
    {
        $value = $request->query($key) ?? old($key);

        return is_string($value) && $value !== '' ? $value : null;
    }
}
