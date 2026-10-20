{{--
    Request a password reset link.

    The field takes either identifier. Format errors stay on the field
    (SendPasswordResetLinkRequest): a value that is neither an address nor a well-shaped
    username cannot have an account, so naming the typo leaks nothing. Anything that is
    well-formed but unknown is answered by NeutralPasswordResetLinkRequestResponse with the
    same sentence a known account gets — Fortify's own `passwords.user` disclosed existence,
    which mattered less while the only identifier was an email address people do not hand
    out than it does now that usernames are guessable.
--}}
<x-layouts::auth
    :title="__('auth.forgot_password.title')"
    :heading="__('auth.forgot_password.heading')"
    :subheading="__('auth.forgot_password.subheading')"
    :brandLine="__('auth.forgot_password.brand_line')"
>
    <x-ui.alert-banner />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5" novalidate>
        @csrf

        <x-ui.field :label="__('auth.forgot_password.identifier_label')" field="email">
            <x-ui.input
                name="email"
                type="text"
                autocomplete="username"
                :placeholder="__('auth.login.identifier_placeholder')"
                autofocus
                required
            />
        </x-ui.field>

        <x-ui.button
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="__('auth.forgot_password.sending')"
        >{{ __('auth.forgot_password.send_button') }}</x-ui.button>
    </form>

    <p class="mt-6 text-ui-sm text-ui-muted">
        {{ __('auth.ui.remember_password') }}
        <x-ui.link :href="route('login')" size="sm">{{ __('auth.login.sign_in_button') }}</x-ui.link>
    </p>
</x-layouts::auth>
