{{--
    Register.

    Only reachable when the `enable_registration` company setting is on — the same
    gate the login link checks, enforced server-side by EnsureRegistrationIsEnabled.
    Restyled anyway so the flow stays one system if that toggle is switched on.
--}}
@php
    $passwordRules = \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString();
@endphp

<x-layouts::auth
    :title="__('auth.register.title')"
    :heading="__('auth.register.heading')"
    :subheading="__('auth.register.subheading')"
    :brandLine="__('auth.register.brand_line')"
>
    <x-ui.alert-banner />

    <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
        @csrf

        <x-ui.field :label="__('auth.register.name_label')" field="name">
            <x-ui.input
                name="name"
                type="text"
                autocomplete="name"
                placeholder="Full name"
                autofocus
                required
            />
        </x-ui.field>

        <x-ui.field :label="__('auth.register.username_label')" field="username">
            <x-ui.input
                name="username"
                type="text"
                autocomplete="username"
                :placeholder="__('auth.register.username_placeholder')"
                required
            />

            <p class="mt-1.5 text-ui-xs text-ui-muted">{{ \App\Support\Username::formatHint() }}</p>
        </x-ui.field>

        <x-ui.field :label="__('auth.register.email_label')" field="email">
            <x-ui.input
                name="email"
                type="email"
                inputmode="email"
                autocomplete="email"
                placeholder="you@example.com"
                required
            />
        </x-ui.field>

        <x-ui.field :label="__('auth.register.password_label')" field="password">
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

        <x-ui.field :label="__('auth.register.password_confirmation_label')" field="password_confirmation">
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

        <div class="space-y-2.5 pt-1">
            <x-ui.checkbox name="terms" required>
                I agree to the <x-ui.link :href="route('terms')" size="sm" underline>{{ __('auth.ui.terms_link') }}</x-ui.link>
            </x-ui.checkbox>
            <x-ui.checkbox name="privacy" required>
                I agree to the <x-ui.link :href="route('privacy')" size="sm" underline>{{ __('auth.ui.privacy_link') }}</x-ui.link>
            </x-ui.checkbox>

            <x-ui.field-errors field="terms" />
            <x-ui.field-errors field="privacy" />
        </div>

        <x-ui.button
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="__('auth.register.creating_account')"
        >{{ __('auth.register.register_button') }}</x-ui.button>
    </form>

    <p class="mt-6 text-ui-sm text-ui-muted">
        {{ __('auth.register.already_have_account') }}
        <x-ui.link :href="route('login')" size="sm">{{ __('auth.register.sign_in_link') }}</x-ui.link>
    </p>
</x-layouts::auth>
