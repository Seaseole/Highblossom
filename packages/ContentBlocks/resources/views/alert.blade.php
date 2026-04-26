@php
    $type = $type ?? 'info';
    $title = $title ?? null;
    $content = $content ?? '';
    $dismissible = $dismissible ?? false;
    $uniqueId = 'cb-alert-' . uniqid();
@endphp

<style>
    .cb-alert {
        --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    .cb-alert {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 1rem;
        padding: 1.25rem;
        position: relative;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        animation: slideIn 200ms var(--ease-out);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cb-alert--info {
        border-left: 4px solid #3b82f6;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    }

    .cb-alert--success {
        border-left: 4px solid #10b981;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    }

    .cb-alert--warning {
        border-left: 4px solid #f59e0b;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    }

    .cb-alert--danger {
        border-left: 4px solid #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    }

    .cb-alert__close {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 0.375rem;
        color: rgba(255, 255, 255, 0.6);
        transition: all 100ms var(--ease-out);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cb-alert__close:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: scale(1.1);
    }

    .cb-alert__close:active {
        transform: scale(0.95);
    }

    .cb-alert__icon {
        flex-shrink: 0;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        padding: 0.25rem;
    }

    .cb-alert--info .cb-alert__icon {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }

    .cb-alert--success .cb-alert__icon {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    .cb-alert--warning .cb-alert__icon {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
        animation: pulse 2s var(--ease-out) infinite;
    }

    .cb-alert--danger .cb-alert__icon {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        animation: pulse 2s var(--ease-out) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .cb-alert__content-wrapper {
        flex: 1;
        min-width: 0;
    }

    .cb-alert__title {
        font-size: 0.875rem;
        font-weight: 600;
        color: white;
        margin: 0 0 0.375rem 0;
        line-height: 1.4;
    }

    .cb-alert__content {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        line-height: 1.5;
    }

    @media (max-width: 640px) {
        .cb-alert {
            padding: 1rem;
            border-radius: 0.75rem;
        }

        .cb-alert__icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        .cb-alert__title {
            font-size: 0.8125rem;
        }

        .cb-alert__content {
            font-size: 0.8125rem;
        }
    }
</style>

<div class="cb-alert cb-alert--{{ $type }}" role="alert" x-data="{ dismissed: false }" x-show="!dismissed" x-transition:leave="transition ease-out duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    @if($dismissible)
        <button type="button" class="cb-alert__close" aria-label="Close alert" @click="dismissed = true">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif

    <div class="cb-alert__icon">
        @if($type === 'info')
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @elseif($type === 'success')
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @elseif($type === 'warning')
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        @elseif($type === 'danger')
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @endif
    </div>

    <div class="cb-alert__content-wrapper">
        @if($title)
            <h4 class="cb-alert__title">{{ $title }}</h4>
        @endif

        <p class="cb-alert__content">{{ $content }}</p>
    </div>
</div>
