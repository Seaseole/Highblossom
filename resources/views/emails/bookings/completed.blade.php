<x-emails.layout
    :title="'Appointment Completed — '.config('app.name')"
    :preheader="'Your appointment '.$booking->displayReference().' is complete. Thank you for choosing us.'"
    :header-label="$booking->displayReference()"
    kicker="Appointment Completed"
    :heading="'All done, '.$booking->client_name"
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        Your appointment is complete. Thank you for choosing {{ $companyName ?: config('app.name') }} — we appreciate your business.
    </p>

    @if ($summary)
        <x-emails.panel title="Notes from our team">
            <p style="margin: 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #374151; font-size: 14px; line-height: 1.7;">{{ $summary }}</p>
        </x-emails.panel>
    @endif

    @include('emails.bookings.details', ['panelTitle' => 'Completed Appointment'])

    <x-emails.button :url="URL::signedRoute('bookings.confirmation', $booking)">View booking status</x-emails.button>

    <p style="margin: 20px 0 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #9ca3af; font-size: 13px; line-height: 1.6; text-align: center;">
        If anything is not as you expected, reply to this email and we will sort it out.
    </p>
</x-emails.layout>
