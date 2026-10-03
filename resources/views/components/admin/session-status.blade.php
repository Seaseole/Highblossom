@props(['session', 'isCurrent' => false])

@php
    $active = $session->isActive();

    $label = match (true) {
        $isCurrent => 'This device',
        $active => 'Active',
        default => $session->end_reason?->label() ?? 'Ended',
    };

    $tone = match (true) {
        $isCurrent => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
        $active => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        default => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$tone}"]) }}>
    {{ $label }}
</span>
