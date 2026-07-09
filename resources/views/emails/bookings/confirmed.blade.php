@component('mail::message')
# Booking Confirmed

Hi {{ $booking->client_name }},

Great news! Your booking has been confirmed and added to our inspection list.

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

Our team looks forward to serving you. If you need to make any changes, please reply to this email or contact our support team.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
