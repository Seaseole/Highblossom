{{--
    Inset card for grouping related details, echoing the site's surface-container
    panels. Light on the light body so it reads as a distinct block without the
    weight of a bordered box. `title` renders as the small uppercase red label.
--}}
@props([
    'title' => null,
])
@php
    $body = "'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
@endphp
<table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0 0 24px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;">
    <tr>
        <td style="padding: 24px;">
            @if ($title)
                <p style="margin: 0 0 18px; font-family: {{ $body }}; color: #dc2626; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">{{ $title }}</p>
            @endif
            {{ $slot }}
        </td>
    </tr>
</table>
