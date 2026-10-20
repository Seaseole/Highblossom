{{--
    Reset the password behind a token link.

    `passwordrules` is the browser-native password rules attribute, generated from
    the same Illuminate Password::defaults() the server validates against, so a
    compliant password never fails client-side first. The token is Fortify's, and
    an expired or used one arrives as a page-level message in the banner.
--}}
@php
    $passwordRules = \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString();
@endphp

<x-layouts::auth
    :title="__('auth.reset_password.title')"
    :heading="__('auth.reset_password.heading')"
    :subheading="__('auth.reset_password.subheading')"
    :brandLine="__('auth.reset_password.brand_line')"
>
    <x-ui.alert-banner />

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}" />

        <x-ui.field :label="__('auth.reset_password.email_label')" field="email">
            <x-ui.input
                name="email"
                type="email"
                inputmode="email"
                autocomplete="email"
                placeholder="you@example.com"
                autofocus
                required
            />
        </x-ui.field>

        <x-ui.field :label="__('auth.reset_password.new_password_label')" field="password">
            <x-slot:action>
                <button
                    type="button"
                    data-generate-password="password"
                    data-confirm-target="password_confirmation"
                    class="inline-flex items-center gap-1 rounded-ui-xs text-ui-xs font-semibold uppercase tracking-[0.06em] text-ui-link transition-colors hover:text-ui-link-hover focus:outline-none"
                >
                    <x-ui.icon name="bolt" size="xs" />
                    {{ __('auth.ui.generate') }}
                </button>
            </x-slot:action>

            <x-ui.input
                name="password"
                type="password"
                reveal
                autocomplete="new-password"
                :placeholder="__('auth.login.password_placeholder')"
                passwordrules="{{ $passwordRules }}"
                required
            />

            <x-ui.password-rules input="password" />
        </x-ui.field>

        <x-ui.field
            :label="__('auth.reset_password.password_confirmation_label')"
            field="password_confirmation"
        >
            <x-ui.input
                name="password_confirmation"
                type="password"
                reveal
                autocomplete="new-password"
                :placeholder="__('auth.login.password_placeholder')"
                passwordrules="{{ $passwordRules }}"
                required
            />
        </x-ui.field>

        <x-ui.button
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="__('auth.reset_password.updating')"
        >{{ __('auth.reset_password.reset_button') }}</x-ui.button>
    </form>

    <p class="mt-6 text-ui-sm text-ui-muted">
        <x-ui.link :href="route('login')" size="sm">{{ __('auth.reset_password.back_to_login') }}</x-ui.link>
    </p>
</x-layouts::auth>
