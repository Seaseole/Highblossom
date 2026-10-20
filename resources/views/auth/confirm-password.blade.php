{{--
    Password confirmation (Fortify's `password.confirm` step, e.g. before enabling
    two-factor authentication).
--}}
<x-layouts::auth
    :title="__('auth.confirm_password.title')"
    :heading="__('auth.confirm_password.heading')"
    :subheading="__('auth.ui.confirm_password_subheading')"
    :brandLine="__('auth.confirm_password.brand_line')"
>
    <x-ui.alert-banner />

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5" novalidate>
        @csrf

        <x-ui.field :label="__('auth.confirm_password.password_label')" field="password">
            <x-ui.input
                name="password"
                type="password"
                reveal
                autocomplete="current-password"
                :placeholder="__('auth.login.password_placeholder')"
                autofocus
                required
            />
        </x-ui.field>

        <x-ui.button
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="__('auth.confirm_password.confirming')"
        >{{ __('auth.confirm_password.confirm_button') }}</x-ui.button>
    </form>
</x-layouts::auth>
