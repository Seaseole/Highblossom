<x-emails.layout
    :title="'New Appointment Booking — '.config('app.name')"
    :preheader="'New booking from '.$booking->client_name.' — action required.'"
    header-label="Action Required"
    kicker="New Appointment Booking"
    :heading="'New booking from '.$booking->client_name"
    footer-note="Internal notification for staff with booking access."
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        A new booking request has come in and is waiting to be reviewed. Open it below to confirm, reschedule or cancel.
    </p>

    <x-emails.panel title="Customer">
        <x-emails.field label="Name" :value="$booking->client_name" />
        <x-emails.field label="Email" accent :value="$booking->client_email" />
        <x-emails.field label="Phone" :value="$booking->client_phone.($booking->prefers_whatsapp ? '  (prefers WhatsApp)' : '')" />
    </x-emails.panel>

    @include('emails.bookings.details')

    <x-emails.button :url="route('admin.bookings.show', $booking)">View Booking</x-emails.button>
</x-emails.layout>
