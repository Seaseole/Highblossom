{{--
    A single label/value pair: the small uppercase muted label over a stronger
    value, the rhythm used across the site's spec cards. Pass `value` for plain
    text, or use the slot for links and markup. `accent` paints the value in the
    brand red for the one detail that should pop.
--}}
@props([
    'label',
    'value' => null,
    'accent' => false,
    'muted' => false,
])
@php
    $body = "'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
    $color = $accent ? '#dc2626' : ($muted ? '#4b5563' : '#111827');
@endphp
<table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0 0 16px;">
    <tr>
        <td>
            <p style="margin: 0 0 4px; font-family: {{ $body }}; color: #9ca3af; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px;">{{ $label }}</p>
            <div style="font-family: {{ $body }}; color: {{ $color }}; font-size: 15px; font-weight: 600; line-height: 1.5;">{{ $value ?? $slot }}</div>
        </td>
    </tr>
</table>
