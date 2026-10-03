@component('mail::message')
# Appointment Completed

Hi {{ $booking->client_name }},

Your appointment has been completed. Thank you for choosing Highblossom.

**Vehicle Details:**
{{ $booking->vehicle_details }}

**Appointment:**
{{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'To be confirmed' }}

**Location:**
{{ $booking->location === 'mobile' ? 'Mobile Service' : 'Workshop' }}
@if ($booking->location === 'mobile' && $booking->client_address)
**Service Address:**
{{ $booking->client_address }}

@endif
@if ($summary)
**Notes from our team:**
{{ $summary }}

@endif
If anything is not as you expected, simply reply to this email and we will sort it out.

@component('mail::button', ['url' => URL::signedRoute('bookings.confirmation', $booking)])
View booking status
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
