@component('mail::message')
# Booking Cancelled

Hi {{ $booking->client_name }},

Your booking has been cancelled.

**Reference:**
#HB-{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}

**Vehicle Details:**
{{ $booking->vehicle_details }}

**Appointment:**
{{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y H:i') : 'To be confirmed' }}

You can book a new appointment any time, or reply to this email if the cancellation was unexpected and we will help you get back in the diary.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
