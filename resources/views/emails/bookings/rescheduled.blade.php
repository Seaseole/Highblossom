@component('mail::message')
# Appointment Rescheduled

Hi {{ $booking->client_name }},

Your appointment has been moved.

**Reference:**
#HB-{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}

@if ($previousSlot)
**Previously:**
{{ \Illuminate\Support\Carbon::parse($previousSlot)->format('M d, Y H:i') }}

@endif
**Now booked for:**
{{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'To be confirmed' }}

**Vehicle Details:**
{{ $booking->vehicle_details }}

**Location:**
{{ $booking->location === 'mobile' ? 'Mobile Service' : 'Workshop' }}
@if ($booking->location === 'mobile' && $booking->client_address)
**Service Address:**
{{ $booking->client_address }}

@endif
If the new time does not suit you, reply to this email and we will find another slot.

@component('mail::button', ['url' => URL::signedRoute('bookings.confirmation', $booking)])
View booking status
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
