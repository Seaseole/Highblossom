{{--
    Pill call-to-action, matching the site's .btn-premium.

    Table + bgcolor + inline padding is the bulletproof pattern: it renders as a
    real button in Outlook's Word engine, which ignores border-radius and padding
    on <a> tags. `variant` selects primary (brand red), dark (near-black chrome)
    or outline (quiet, on light bodies); `align` centres by default.
--}}
@props([
    'url',
    'variant' => 'primary',
    'align' => 'center',
])
@php
    $brand = "'Cabinet Grotesk', 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
    $bg = match ($variant) {
        'dark' => '#0f0f14',
        'outline' => 'transparent',
        default => '#dc2626',
    };
    $fg = match ($variant) {
        'outline' => '#111827',
        default => '#ffffff',
    };
    $border = $variant === 'outline' ? 'border: 1px solid #d1d5db;' : 'border: none;';
@endphp
<table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 8px 0 4px;">
    <tr>
        <td align="{{ $align }}">
            <table role="presentation" border="0" cellspacing="0" cellpadding="0" class="hb-btn">
                <tr>
                    <td align="center" bgcolor="{{ $bg }}" style="background-color: {{ $bg }}; border-radius: 9999px; {{ $border }}">
                        <a href="{{ $url }}" target="_blank" rel="noopener" style="display: inline-block; padding: 14px 34px; font-family: {{ $brand }}; color: {{ $fg }}; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; border-radius: 9999px;">{{ $slot }}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
