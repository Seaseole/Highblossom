@component('mail::message')
# Booking Request Received

Hi {{ $booking->client_name }},

Thank you for choosing Highblossom. We have received your booking request for the following vehicle:

**Vehicle Details:**
{{ $booking->vehicle_details }}

**Scheduled Date:**
{{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'To be confirmed' }}

**Location:**
{{ $booking->location === 'mobile' ? 'Mobile Service' : 'Workshop' }}
@if($booking->location === 'mobile' && $booking->client_address)
**Service Address:**
{{ $booking->client_address }}
@endif

We have received your request. Our staff will review your booking and send you a confirmation email once it has been approved and added to our appointment list.

@component('mail::button', ['url' => URL::signedRoute('bookings.confirmation', $booking)])
View booking status
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
