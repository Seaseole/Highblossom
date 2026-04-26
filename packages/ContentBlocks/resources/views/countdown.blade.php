@php
    $targetDate = $target_date ?? null;
    $label = $label ?? null;
    $timezone = $timezone ?? 'UTC';
    $uniqueId = 'cb-countdown-' . uniqid();
@endphp

<style>
    .cb-countdown {
        --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
        --ease-in-out: cubic-bezier(0.77, 0, 0.175, 1);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .cb-countdown {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 1.5rem;
        box-shadow: var(--glass-shadow);
        padding: 2rem;
        max-width: 600px;
        margin: 0 auto;
        animation: fadeIn 200ms var(--ease-out);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cb-countdown__label {
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.7);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .cb-countdown__timer {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .cb-countdown__unit {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }

    .cb-countdown__value {
        font-size: 2.5rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        color: white;
        line-height: 1;
        transition: transform 100ms var(--ease-out);
    }

    .cb-countdown__unit:hover .cb-countdown__value {
        transform: scale(1.05);
    }

    .cb-countdown__unit:nth-child(4) .cb-countdown__value {
        animation: pulse 1s var(--ease-out) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .cb-countdown__unit-label {
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.5);
    }

    .cb-countdown__expired {
        text-align: center;
        font-size: 1.25rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.7);
        padding: 1rem;
        animation: fadeIn 200ms var(--ease-out);
    }

    @media (max-width: 640px) {
        .cb-countdown {
            padding: 1.5rem;
            border-radius: 1rem;
        }

        .cb-countdown__timer {
            gap: 0.75rem;
        }

        .cb-countdown__value {
            font-size: 2rem;
        }

        .cb-countdown__unit-label {
            font-size: 0.625rem;
        }
    }
</style>

<div class="cb-countdown" x-data="{
    targetDate: new Date('{{ $targetDate }}'),
    remaining: { days: 0, hours: 0, minutes: 0, seconds: 0 },
    expired: false,
    init() {
        this.calculateRemaining();
        setInterval(() => this.calculateRemaining(), 1000);
    },
    calculateRemaining() {
        const now = new Date();
        const diff = this.targetDate - now;
        
        if (diff <= 0) {
            this.expired = true;
            this.remaining = { days: 0, hours: 0, minutes: 0, seconds: 0 };
            return;
        }
        
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        this.remaining = { days, hours, minutes, seconds };
    }
}" x-init="init()">
    @if($label)
        <div class="cb-countdown__label">{{ $label }}</div>
    @endif

    <div class="cb-countdown__timer" x-show="!expired" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
        <div class="cb-countdown__unit">
            <span class="cb-countdown__value" x-text="String(remaining.days).padStart(2, '0')">00</span>
            <span class="cb-countdown__unit-label">Days</span>
        </div>
        <div class="cb-countdown__unit">
            <span class="cb-countdown__value" x-text="String(remaining.hours).padStart(2, '0')">00</span>
            <span class="cb-countdown__unit-label">Hours</span>
        </div>
        <div class="cb-countdown__unit">
            <span class="cb-countdown__value" x-text="String(remaining.minutes).padStart(2, '0')">00</span>
            <span class="cb-countdown__unit-label">Minutes</span>
        </div>
        <div class="cb-countdown__unit">
            <span class="cb-countdown__value" x-text="String(remaining.seconds).padStart(2, '0')">00</span>
            <span class="cb-countdown__unit-label">Seconds</span>
        </div>
    </div>

    <div class="cb-countdown__expired" x-show="expired" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        Countdown expired
    </div>
</div>
