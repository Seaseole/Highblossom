<x-emails.layout
    :title="'Booking Confirmed — '.config('app.name')"
    :preheader="'Your booking '.$booking->displayReference().' is confirmed.'"
    :header-label="$booking->displayReference()"
    kicker="Booking Confirmed"
    :heading="'You are all set, '.$booking->client_name"
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        Great news — your booking is confirmed and on our appointment list. Please arrive a few minutes early so we can get you straight in.
    </p>

    @include('emails.bookings.details')

    <x-emails.button :url="URL::signedRoute('bookings.confirmation', $booking)">View booking status</x-emails.button>

    <p style="margin: 20px 0 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #9ca3af; font-size: 13px; line-height: 1.6; text-align: center;">
        Need to make a change? Reply to this email and we will help.
    </p>
</x-emails.layout>
