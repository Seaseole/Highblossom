<x-emails.layout
    :title="'Appointment Rescheduled — '.config('app.name')"
    :preheader="'Your appointment '.$booking->displayReference().' has been moved to a new time.'"
    :header-label="$booking->displayReference()"
    kicker="Appointment Rescheduled"
    :heading="'Your appointment has moved, '.$booking->client_name"
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        We've moved your appointment to a new time. The updated details are below — if the new slot no longer suits you, reply to this email and we'll find another.
    </p>

    @include('emails.bookings.details')

    <x-emails.button :url="URL::signedRoute('bookings.confirmation', $booking)">View booking status</x-emails.button>
</x-emails.layout>
