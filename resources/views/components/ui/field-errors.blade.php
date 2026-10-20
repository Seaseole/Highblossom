{{--
    Inline validation hints for one field.

    Page-level messages (failed credentials, lockout, invalid token) stay with the
    banner, so a sentence is never shown twice.

    The `{{ $field }}-error` id is a contract: x-ui.input points
    aria-describedby at it, and the login view's own aria wiring predates the
    component. Renaming it silently breaks screen-reader association.
--}}
@props(['field'])

@php
    $messages = \App\Support\AuthNotification::fieldMessages($field);
@endphp

@if ($messages !== [])
    <div id="{{ $field }}-error" class="space-y-1">
        @foreach ($messages as $message)
            <p class="flex items-start gap-1.5 text-ui-sm font-medium text-ui-danger">
                <x-ui.icon name="exclamation-circle" size="xs" class="mt-px shrink-0" />
                <span>{{ $message }}</span>
            </p>
        @endforeach
    </div>
@endif
