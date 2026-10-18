<x-emails.layout
    :title="'Booking Cancelled — '.config('app.name')"
    :preheader="'Your booking '.$booking->displayReference().' has been cancelled.'"
    :header-label="$booking->displayReference()"
    kicker="Booking Cancelled"
    :heading="'Your appointment was cancelled, '.$booking->client_name"
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        Your booking has been cancelled and the slot released. If this was unexpected, reply to this email and we will get you straight back into the diary.
    </p>

    @include('emails.bookings.details', ['panelTitle' => 'Cancelled Appointment'])

    <x-emails.button :url="route('bookings.create')">Book a new appointment</x-emails.button>
</x-emails.layout>
