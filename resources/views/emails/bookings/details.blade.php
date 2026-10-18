{{--
    Appointment summary shared by every booking email so the detail block reads
    identically whether the message is a request, a confirmation, a completion or
    a cancellation. `$booking` is inherited from the including view; pass
    `$panelTitle` to rename the card. When a reschedule supplies `$previousSlot`,
    the appointment row splits into "Previously" and "Now booked for".
--}}
@php
    $appointment = $booking->scheduled_at
        ? $booking->scheduled_at->format('l, j M Y').' · '.$booking->scheduled_at->format('H:i').' – '.$booking->ends_at->format('H:i')
        : 'To be confirmed';
    $previous = (isset($previousSlot) && $previousSlot !== null)
        ? \Illuminate\Support\Carbon::parse($previousSlot)->format('l, j M Y · H:i')
        : null;
@endphp
<x-emails.panel :title="$panelTitle ?? 'Appointment Details'">
    <x-emails.field label="Reference" accent :value="$booking->displayReference()" />
    <x-emails.field label="Vehicle" :value="$booking->vehicleSummary()" />
    @if ($booking->service_type)
        <x-emails.field label="Service" :value="$booking->service_type->label()" />
    @endif
    @if ($previous)
        <x-emails.field label="Previously" muted :value="$previous" />
        <x-emails.field label="Now booked for" :value="$appointment" />
    @else
        <x-emails.field label="Appointment" :value="$appointment" />
    @endif
    <x-emails.field label="Location" :value="$booking->location === 'mobile' ? 'Mobile Service' : 'Workshop'" />
    @if ($booking->location === 'mobile' && $booking->client_address)
        <x-emails.field label="Service Address" :value="$booking->client_address" />
    @endif
</x-emails.panel>
