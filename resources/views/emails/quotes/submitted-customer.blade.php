<x-emails.layout
    :title="'Quote Request Received — '.($companyName ?: config('app.name'))"
    :preheader="'We have received your glass quote request and will be in touch within 24 hours.'"
    :header-label="'#'.$quote->id"
    kicker="Quote Request Received"
    :heading="'Thanks, '.$quote->name"
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        Thank you for choosing {{ $companyName ?: config('app.name') }}. We've received your glass request and our specialists are reviewing the specifications. You can expect a detailed response within 24 hours.
    </p>

    <x-emails.panel title="Request Specifications">
        <x-emails.field label="Vehicle" :value="($quote->make_model ?? ucfirst($quote->vehicle_type)).($quote->year ? ' ('.$quote->year.')' : '')" />
        <x-emails.field label="Service" :value="$quote->serviceType?->name ?? 'Glass Service'" />
        <x-emails.field label="Glass Specification" :value="$quote->full_glass_description" />
        <x-emails.field label="Reference" accent :value="'#'.$quote->id" />
        @if ($quote->mobile_service)
            <x-emails.field label="Preference" accent value="Mobile Service Requested" />
        @endif
    </x-emails.panel>

    @if ($quote->message)
        <x-emails.panel title="Your Notes">
            <p style="margin: 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #374151; font-size: 14px; line-height: 1.7; font-style: italic;">&ldquo;{{ $quote->message }}&rdquo;</p>
        </x-emails.panel>
    @endif

    @if ($contact?->telHref($contact?->primaryPhone))
        <p style="margin: 8px 0 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #6b7280; font-size: 13px; line-height: 1.6; text-align: center;">
            Need an answer sooner? Call us on
            <a href="{{ $contact->telHref($contact->primaryPhone) }}" style="color: #111827; font-weight: 700;">{{ $contact->primaryPhone }}</a>.
        </p>
    @endif
</x-emails.layout>
