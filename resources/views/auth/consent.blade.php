{{--
    Post-login consent capture.

    Shown when EnsureConsentRecorded finds no recorded consent for this user. The
    two boxes are what the middleware looks for, and logging out is the only
    refusal path — there is no "skip", by design.
--}}
<x-layouts::auth
    :title="__('auth.ui.consent_heading')"
    :heading="__('auth.ui.consent_heading')"
    :subheading="__('auth.ui.consent_subheading')"
    :brandLine="__('auth.ui.consent_brand_line')"
>
    <x-ui.alert-banner />

    <form method="POST" action="{{ route('consent.store') }}" class="space-y-5" novalidate>
        @csrf

        <div class="space-y-2.5">
            <x-ui.checkbox name="terms" required>
                I agree to the <x-ui.link :href="route('terms')" size="sm" underline external>{{ __('auth.ui.terms_link') }}</x-ui.link>
            </x-ui.checkbox>
            <x-ui.checkbox name="privacy" required>
                I agree to the <x-ui.link :href="route('privacy')" size="sm" underline external>{{ __('auth.ui.privacy_link') }}</x-ui.link>
            </x-ui.checkbox>

            <x-ui.field-errors field="terms" />
            <x-ui.field-errors field="privacy" />
        </div>

        <x-ui.button
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="__('auth.consent.saving')"
        >{{ __('auth.ui.consent_accept') }}</x-ui.button>
    </form>

    <p class="mt-6 text-ui-sm text-ui-muted">
        {{ __('auth.ui.consent_decline') }}

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf

            <x-ui.button type="submit" variant="ghost" size="sm">{{ __('auth.ui.log_out') }}</x-ui.button>
        </form>
    </p>
</x-layouts::auth>
