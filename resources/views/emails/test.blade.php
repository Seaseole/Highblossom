<x-emails.layout
    :title="'SMTP Test — '.config('app.name')"
    preheader="This is a test email confirming your SMTP settings work."
    header-label="Internal"
    kicker="SMTP Configuration Test"
    heading="Your mail is working"
>
    <p style="margin: 0 0 24px; font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #4b5563; font-size: 15px; line-height: 1.7;">
        This is a test email sent from <strong style="color: #111827;">{{ config('app.name') }}</strong>. If you received it, your SMTP settings are configured correctly and transactional mail will deliver.
    </p>

    <x-emails.panel>
        <x-emails.field label="Sent at" muted :value="now()->format('d M Y · H:i:s T')" />
    </x-emails.panel>
</x-emails.layout>
