{{--
    Authentication notification banner.

    Rendered server-side because the auth pages do not boot Alpine, so the
    session toaster cannot run here.

    Page-level messages only — failed credentials, lockout, invalid reset token — or a
    neutral summary when every message in the bag belongs to a field. The field sentences
    belong to x-ui.field-errors, and App\Support\AuthNotification is what keeps the two
    from overlapping, so never move a sentence from one to the other.
--}}
@props(['notification' => null])

@php
    $notification ??= \App\Support\AuthNotification::fromSession();

    $styles = match ($notification['type'] ?? 'error') {
        'success' => 'border-ui-success/25 bg-ui-success-soft text-ui-success',
        'info' => 'border-ui-info/25 bg-ui-info-soft text-ui-info',
        'warning' => 'border-ui-warning/25 bg-ui-warning-soft text-ui-warning',
        default => 'border-ui-danger/25 bg-ui-danger-soft text-ui-danger',
    };

    $icon = ($notification['type'] ?? 'error') === 'success' ? 'check' : 'exclamation-circle';
@endphp

@if ($notification && ! empty($notification['message']))
    <div
        data-auth-alert
        role="alert"
        aria-live="polite"
        {{ $attributes->class('mb-5 flex items-start gap-2.5 rounded-ui-md border px-3.5 py-3 text-ui-sm font-medium '.$styles) }}
    >
        <x-ui.icon :name="$icon" size="xs" class="mt-px shrink-0" />

        <p class="min-w-0 flex-1">{{ $notification['message'] }}</p>
    </div>
@endif
