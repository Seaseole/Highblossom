{{--
    Inline validation hints for one auth input.

    Page-level messages (failed credentials, lockout, invalid token) stay with the
    banner, so a sentence is never shown twice.
--}}
@props(['field'])

@php
    $messages = \App\Support\AuthNotification::fieldMessages($field);
@endphp

@if ($messages !== [])
    <div id="{{ $field }}-error" class="mt-2 space-y-1 px-1">
        @foreach ($messages as $message)
            <p class="flex items-start gap-1.5 text-sm font-medium text-[#DC2626]">
                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $message }}</span>
            </p>
        @endforeach
    </div>
@endif
