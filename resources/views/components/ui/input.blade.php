{{--
    Text-family control.

    Its state is derived from the validation key: `name` doubles as the error key,
    so a view never repeats `$errors->has(...)` and cannot get it wrong. Look and
    feel live in `.ui-field` (resources/css/auth.css), not in a utility string, so
    the Alpine-driven two-factor screen can style the same control on a plain
    element — a `:disabled="expr"` binding on a Blade tag compiles as PHP.

    Unhandled attributes (autocomplete, inputmode, maxlength, passwordrules,
    x-model, data-*) pass straight through to the input.

    The reveal toggle keeps every hook resources/js/app.js binds to:
    data-toggle-password, aria-controls/expanded, data-show/hide-label and the two
    icon markers. The auth pages boot no Alpine, so that script is the only thing
    that can flip this control.
--}}
@props([
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'reveal' => false,
    'invalid' => null,
])

@php
    $id = $id ?: ($name ?: 'field-'.\Illuminate\Support\Str::random(8));
    $invalid = $invalid ?? ($name ? $errors->has($name) : false);
    $describedBy = $name && \App\Support\AuthNotification::fieldMessages($name) !== [] ? $name.'-error' : null;
@endphp

<div class="relative">
    <input
        id="{{ $id }}"
        @if ($name) name="{{ $name }}" @endif
        type="{{ $reveal ? 'password' : $type }}"
        value="{{ $value ?? ($name ? old($name) : null) }}"
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($invalid) aria-invalid="true" @endif
        {{ $attributes->class('ui-field '.($reveal ? 'pr-11' : '')) }}
    />

    @if ($reveal)
        <button
            type="button"
            data-toggle-password="{{ $id }}"
            aria-controls="{{ $id }}"
            aria-expanded="false"
            aria-label="{{ __('auth.login.show_password') }}"
            data-show-label="{{ __('auth.login.show_password') }}"
            data-hide-label="{{ __('auth.login.hide_password') }}"
            class="absolute inset-y-0 right-0 flex items-center rounded-ui-md px-3 text-ui-faint transition-colors hover:text-ui-muted focus:outline-none"
        >
            <x-ui.icon name="eye" size="sm" data-icon-eye />
            <x-ui.icon name="eye-slash" size="sm" data-icon-eye-off class="hidden" />
        </button>
    @endif
</div>
