@props(['session'])

<div class="min-w-0 space-y-0.5">
    <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $session->deviceSummary() }}</p>
    <p class="text-xs text-gray-500 dark:text-gray-400">
        {{ $session->device_type?->label() ?? 'Unknown device' }}
        @if ($session->last_ip_address)
            · <span class="font-mono">{{ $session->last_ip_address }}</span>
        @endif
    </p>
</div>
