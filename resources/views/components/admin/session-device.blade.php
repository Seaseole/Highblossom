@props(['session'])

<div class="hb-device">
    <p class="hb-device__name">{{ $session->deviceSummary() }}</p>

    <p class="hb-device__meta">
        {{ $session->device_type?->label() ?? 'Unknown device' }}
        @if ($session->last_ip_address)
            <span class="hb-sep" aria-hidden="true">·</span>
            <span class="hb-mono">{{ $session->last_ip_address }}</span>
        @endif
    </p>
</div>
