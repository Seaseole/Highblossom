<x-emails.layout
    :title="'New Quote Request — '.($companyName ?: config('app.name'))"
    :preheader="'New quote request #'.$quote->id.' from '.$quote->name.'.'"
    header-label="Internal"
    kicker="New Quote Request"
    :heading="'Quote #'.$quote->id"
    footer-note="System-generated notification for staff. The customer details are below."
>
    <x-emails.panel title="Customer">
        <x-emails.field label="Name" :value="$quote->name" />
        <x-emails.field label="Phone" :value="$quote->phone" />
        <x-emails.field label="Email" :value="$quote->email ?? 'N/A'" />
    </x-emails.panel>

    <x-emails.panel title="Vehicle Specifications">
        <x-emails.field label="Make &amp; Model" :value="$quote->make_model ?? 'Not specified'" />
        <x-emails.field label="Year / Reg" :value="($quote->year ?? '----').' / '.($quote->reg_number ?? '----')" />
    </x-emails.panel>

    <x-emails.panel title="Workshop Requirements">
        <x-emails.field label="Glass Type" :value="$quote->full_glass_description" />
        <x-emails.field label="Service Type" :value="$quote->serviceType?->name ?? 'N/A'" />
        @if ($quote->mobile_service)
            <x-emails.field label="Preference" accent value="Mobile Service Requested" />
        @endif
    </x-emails.panel>

    @if ($quote->message)
        <x-emails.panel title="Message from Customer">
            <p style="margin: 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #374151; font-size: 14px; line-height: 1.7; font-style: italic;">&ldquo;{{ $quote->message }}&rdquo;</p>
        </x-emails.panel>
    @endif

    <x-emails.button :url="route('admin.quotes.show', $quote->id)">Access Admin Portal</x-emails.button>

    @if ($quote->image_path)
        <x-emails.button variant="outline" :url="url('storage/'.$quote->image_path)">View Reference Image</x-emails.button>
    @endif
</x-emails.layout>
