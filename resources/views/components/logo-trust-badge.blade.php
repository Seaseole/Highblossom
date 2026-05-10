@props([
    'businessName'  => 'BrandName',
    'iconSrc'       => null,          // optional: path to logo image asset
    'variant'       => 'verified',    // 'verified' | 'trusted' | 'premium'
    'fontSize'      => 'text-3xl',    // Tailwind font size class
    'badgeSize'     => 22,            // badge diameter in px (integer)
    'badgeTop'      => null,          // optional: manual top offset (px, positive moves down)
    'badgeRight'    => null,          // optional: manual right offset (px, positive moves left)
    'gap'           => 'gap-2.5',     // optional: gap between icon and text (Tailwind class)
    'showBadge'     => true,          // toggle badge visibility
])

@php
    $variants = [
        'verified' => [
            'bg'    => 'bg-emerald-700',
            'hex'   => '#047857',
            'icon'  => 'check',
            'label' => 'Verified business',
        ],
        'trusted' => [
            'bg'    => 'bg-blue-700',
            'hex'   => '#1d4ed8',
            'icon'  => 'shield',
            'label' => 'Trusted business',
        ],
        'premium' => [
            'bg'    => 'bg-amber-700',
            'hex'   => '#b45309',
            'icon'  => 'star',
            'label' => 'Premium member',
        ],
    ];

    $active     = $variants[$variant] ?? $variants['verified'];
    $iconSize   = round($badgeSize * 0.5);  // icon is ~50% of badge diameter
    
    $defaultOffset = round($badgeSize * 0.45);
    $top           = $badgeTop   ?? -$defaultOffset;
    $right         = $badgeRight ?? -$defaultOffset;
@endphp

{{-- Outer wrapper: relative + inline-flex so badge positions within it --}}
<div {{ $attributes->merge(['class' => "relative inline-flex items-center {$gap} w-fit"]) }}>

    {{-- Icon mark (left) --}}
    @if($iconSrc)
        <img src="{{ $iconSrc }}" alt="{{ $businessName }} icon"
             class="h-10 w-10 rounded-lg object-contain">
    @else
        {{-- Default fallback icon mark --}}
        <div class="h-10 w-10 rounded-lg border border-current/20
                    flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect x="2" y="2" width="8" height="8" rx="1.5"
                      fill="currentColor"/>
                <rect x="12" y="2" width="8" height="8" rx="1.5"
                      fill="currentColor" opacity="0.35"/>
                <rect x="2" y="12" width="18" height="4" rx="1.5"
                      fill="currentColor" opacity="0.25"/>
            </svg>
        </div>
    @endif

    {{-- Logo text --}}
    <span class="{{ $fontSize }} font-semibold tracking-tight leading-none">
        {{ $businessName }}
    </span>

    {{-- Trust badge — pinned top-right of wrapper --}}
    @if($showBadge)
        <span
            role="img"
            aria-label="{{ $active['label'] }}"
            class="{{ $active['bg'] }} absolute rounded-full
                   flex items-center justify-center
                   ring-2 ring-white shadow-sm z-10"
            style="
                width: {{ $badgeSize }}px;
                height: {{ $badgeSize }}px;
                top: {{ $top }}px;
                right: {{ $right }}px;
            "
        >
            {{-- Check icon --}}
            @if($active['icon'] === 'check')
                <svg width="{{ $iconSize }}" height="{{ $iconSize }}"
                     viewBox="0 0 12 12" fill="none"
                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <polyline points="2,6 5,9 10,3"
                              stroke="white" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

            {{-- Shield icon --}}
            @elseif($active['icon'] === 'shield')
                <svg width="{{ $iconSize }}" height="{{ $iconSize }}"
                     viewBox="0 0 12 12" fill="none"
                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M6 1L10.5 3v3.5C10.5 9.5 6 11 6 11S1.5 9.5 1.5 6.5V3L6 1z"
                          stroke="white" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

            {{-- Star icon --}}
            @elseif($active['icon'] === 'star')
                <svg width="{{ $iconSize }}" height="{{ $iconSize }}"
                     viewBox="0 0 12 12" fill="none"
                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <polygon points="6,1 7.5,4.5 11,5 8.5,7.5 9,11 6,9.5 3,11 3.5,7.5 1,5 4.5,4.5"
                             stroke="white" stroke-width="1.2"
                             stroke-linecap="round" stroke-linejoin="round"
                             fill="none"/>
                </svg>
            @endif
        </span>
    @endif

</div>
