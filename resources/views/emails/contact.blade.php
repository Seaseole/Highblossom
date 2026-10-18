<x-emails.layout
    :title="'New Contact Message — '.($companyName ?: config('app.name'))"
    :preheader="'New contact message from '.$contactMessage->name.': '.$contactMessage->subject"
    header-label="Internal"
    kicker="Inbound Support Message"
    :heading="$contactMessage->subject"
    footer-note="Internal use only — this notification was sent to the team inbox."
>
    <x-emails.panel title="From">
        <x-emails.field label="Name" :value="$contactMessage->name" />
        <x-emails.field label="Email" accent :value="$contactMessage->email" />
        @if ($contactMessage->phone)
            <x-emails.field label="Phone" :value="$contactMessage->phone" />
        @endif
        <x-emails.field label="Received" muted :value="$contactMessage->created_at->format('d M Y · H:i T')" />
    </x-emails.panel>

    <x-emails.panel title="Message">
        <p style="margin: 0; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #374151; font-size: 15px; line-height: 1.7;">{{ $contactMessage->message }}</p>
    </x-emails.panel>

    <x-emails.button :url="'mailto:'.$contactMessage->email">Reply to Customer</x-emails.button>
</x-emails.layout>
