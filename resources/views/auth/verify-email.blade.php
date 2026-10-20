{{--
    Email verification notice.

    Both actions are native form posts on purpose: the resend hits Fortify's
    throttle and the logout invalidates the session, so neither belongs behind
    wire:navigate.
--}}
<x-layouts::auth
    :title="__('auth.verify_email.title')"
    :heading="__('auth.verify_email.heading')"
    :subheading="__('auth.ui.verify_email_subheading')"
    :brandLine="__('auth.verify_email.brand_line')"
>
    <div class="mb-6 flex size-11 items-center justify-center rounded-ui-md bg-ui-accent-soft text-ui-link">
        <x-ui.icon name="envelope" size="sm" />
    </div>

    <x-ui.alert-banner
        :notification="session('resent')
            ? ['type' => 'success', 'message' => __('auth.verify_email.resent')]
            : \App\Support\AuthNotification::fromSession()"
    />

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-ui.button
                type="submit"
                variant="primary"
                size="lg"
                block
                icon="arrow-refresh"
                :loading="__('auth.verify_email.resending')"
            >{{ __('auth.verify_email.resend_button') }}</x-ui.button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-ui.button type="submit" variant="secondary" size="md" block>
                {{ __('auth.verify_email.logout_button') }}
            </x-ui.button>
        </form>
    </div>
</x-layouts::auth>
