<x-emails.layout
    :title="'Booking Request Received — '.config('app.name')"
    :preheader="'We have received your booking request for '.$booking->vehicleSummary().'.'"
    :header-label="$booking->displayReference()"
    kicker="Request Received"
    :heading="'Thanks, '.$booking->client_name"
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        Thank you for choosing {{ $companyName ?: config('app.name') }}. We've received your booking request and our team is reviewing it. You'll get a confirmation email as soon as it's approved and added to our appointment list.
    </p>

    @include('emails.bookings.details')

    <x-emails.button :url="URL::signedRoute('bookings.confirmation', $booking)">View booking status</x-emails.button>

    <p style="margin: 20px 0 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #9ca3af; font-size: 13px; line-height: 1.6; text-align: center;">
        Need to change something? Just reply to this email.
    </p>
</x-emails.layout>
