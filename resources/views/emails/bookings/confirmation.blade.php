@component('mail::message')
# Booking Confirmation

Hi {{ $booking->client_name }},

Thank you for choosing Highblossom. We have received your booking request for the following vehicle:

**Vehicle Details:**
{{ $booking->vehicle_details }}

**Scheduled Date:**
{{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'To be confirmed' }}

We will review your request and get back to you shortly.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
