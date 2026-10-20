{{--
    Guided recovery state for a rejected passkey sign-in.

    Visibility is driven by resources/js/passkey-signin.js, which toggles the
    `hidden` class on [data-passkey-recovery="<reason>"]. Keep the class-based
    contract: the reason keys and this attribute are how a WebAuthn failure maps
    to copy the user can act on.
--}}
@props(['reason', 'title', 'message', 'cta' => null])

<div
    data-passkey-recovery="{{ $reason }}"
    role="alert"
    {{ $attributes->class('hidden rounded-ui-md border border-ui-line bg-ui-surface-raised px-4 py-3.5') }}
>
    <p class="mb-1 text-ui-sm font-semibold text-ui-text">{{ $title }}</p>
    <p class="text-ui-sm text-ui-muted">{{ $message }}</p>

    @if ($cta)
        <x-ui.button
            type="button"
            variant="secondary"
            size="sm"
            class="mt-3"
            data-passkey-signin
        >{{ $cta }}</x-ui.button>
    @endif
</div>
