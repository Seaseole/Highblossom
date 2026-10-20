{{--
    Sign in.

    Fortify owns the POST, the throttling and the redirect; this view only has to
    keep its DOM contract with resources/js/passkey-signin.js,
    resources/js/submit-pending.js and the password toggle in app.js. The element
    ids, the data-* hooks and the `hidden` class swapping are all load-bearing —
    see docs/design-system.md before restyling anything here.
--}}
<x-layouts::auth
    :title="__('auth.login.title')"
    :heading="__('auth.login.heading')"
    :subheading="__('auth.login.subheading')"
    :brandLine="__('auth.login.welcome_back')"
>
    {{-- Page-level messages live here: failed credentials, lockout with its
         seconds remaining. Field-level messages stay under their field. --}}
    <x-ui.alert-banner />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        <x-ui.field :label="__('auth.login.identifier_label')" field="email">
            <x-ui.input
                name="email"
                type="text"
                autocomplete="username"
                :placeholder="__('auth.login.identifier_placeholder')"
                autofocus
                required
            />
        </x-ui.field>

        <x-ui.field :label="__('auth.login.password_label')" field="password">
            <x-slot:action>
                <x-ui.link :href="route('password.request')" size="xs" tone="accent">
                    {{ __('auth.login.forgot_password') }}
                </x-ui.link>
            </x-slot:action>

            <x-ui.input
                name="password"
                type="password"
                reveal
                autocomplete="current-password"
                :placeholder="__('auth.login.password_placeholder')"
                required
            />
        </x-ui.field>

        <x-ui.checkbox name="remember" :label="__('auth.login.remember_me')" />

        <div class="space-y-4 pt-1">
            <x-ui.button
                type="submit"
                variant="primary"
                size="lg"
                block
                :loading="__('auth.login.signing_in')"
            >{{ __('auth.login.sign_in_button') }}</x-ui.button>

            <x-ui.divider :label="__('auth.ui.or')" />

            {{-- The passkey button is type=button: it authenticates over JSON and
                 then follows the redirect the server hands back. --}}
            <x-ui.button
                id="passkey-signin-btn"
                type="button"
                variant="secondary"
                size="lg"
                block
                icon="key"
                data-passkey-signin
            >
                <span
                    id="passkey-signin-label"
                    data-sign-in="{{ __('auth.login.passkey.button') }}"
                    data-waiting="{{ __('auth.login.passkey.waiting') }}"
                    data-unsupported="{{ __('auth.login.passkey.unsupported') }}"
                >{{ __('auth.login.passkey.button') }}</span>
            </x-ui.button>

            <div
                id="passkey-error"
                role="alert"
                data-default="{{ __('auth.login.passkey.generic_error') }}"
                class="hidden rounded-ui-md border border-ui-danger/25 bg-ui-danger-soft px-3.5 py-3 text-ui-sm font-medium text-ui-danger"
            ></div>

            <x-passkey-recovery-panel
                reason="unrecognized_passkey"
                :title="__('auth.login.passkey.recovery.unrecognized_passkey.title')"
                :message="__('auth.login.passkey.recovery.unrecognized_passkey.message')"
                :cta="__('auth.login.passkey.recovery.unrecognized_passkey.try_another')"
            />
            <x-passkey-recovery-panel
                reason="expired_passkey_session"
                :title="__('auth.login.passkey.recovery.expired_passkey_session.title')"
                :message="__('auth.login.passkey.recovery.expired_passkey_session.message')"
                :cta="__('auth.login.passkey.button')"
            />
            <x-passkey-recovery-panel
                reason="passkey_verification_failed"
                :title="__('auth.login.passkey.recovery.passkey_verification_failed.title')"
                :message="__('auth.login.passkey.recovery.passkey_verification_failed.message')"
            />
            <x-passkey-recovery-panel
                reason="too_many_attempts"
                :title="__('auth.login.passkey.recovery.too_many_attempts.title')"
                :message="__('auth.login.passkey.recovery.too_many_attempts.message')"
            />
        </div>
    </form>

    @if (filter_var($settings['enable_registration'] ?? '0', FILTER_VALIDATE_BOOLEAN))
        <p class="mt-6 text-ui-sm text-ui-muted">
            {{ __('auth.ui.no_account') }}
            <x-ui.link :href="route('register')" size="sm">{{ __('auth.ui.register_link') }}</x-ui.link>
        </p>
    @endif
</x-layouts::auth>
