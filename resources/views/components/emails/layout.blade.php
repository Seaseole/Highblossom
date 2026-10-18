{{--
    Shared transactional email shell for every message the shop sends.

    Mirrors the public site's identity — a near-black branded header and footer
    wrapping a light, high-contrast body, the #dc2626 accent, Cabinet Grotesk for
    the wordmark and headings, Geist for body copy, and pill CTAs. Hybrid on
    purpose: the dark chrome carries the brand while the white body stays legible
    in every client, including the ones that mangle dark backgrounds.

    Company identity is read from the GlobalSettingsComposer (`$contact`,
    `$companyName`), which `View::composer('*')` already shares with mailable
    views, so no Mailable has to pass footer data through.

    Critical layout styles are inlined because Outlook's Word engine ignores the
    <style> block; that block only carries resets, the webfont, and media queries.
--}}
@props([
    'title' => null,
    'preheader' => null,
    'headerLabel' => null,
    'kicker' => null,
    'heading' => null,
    'footerNote' => null,
])
@php
    $company = $companyName ?: ($contact->companyName ?? null) ?: config('app.name');
    $brand = "'Cabinet Grotesk', 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
    $body = "'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
    $tel = $contact?->telHref($contact?->primaryPhone);
    $mail = $contact?->emailHref();
    $address = $contact?->address;
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <title>{{ $title ?: $company }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap" rel="stylesheet" />
    <style type="text/css">
        body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important; background-color: #f3f4f6; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
        table { border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        td { border-collapse: collapse; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        a { text-decoration: none; }
        @media only screen and (max-width: 600px) {
            .hb-container { width: 100% !important; max-width: 100% !important; }
            .hb-pad { padding-left: 24px !important; padding-right: 24px !important; }
            .hb-stack { display: block !important; width: 100% !important; max-width: 100% !important; }
            .hb-h1 { font-size: 22px !important; }
            .hb-btn a { display: block !important; width: 100% !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: {{ $body }};">
    @if ($preheader)
        <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all; opacity: 0; color: transparent;">{{ $preheader }}&#847;&zwnj;&nbsp;&#8199;&shy;&#847;&zwnj;&nbsp;&#8199;&shy;&#847;&zwnj;&nbsp;</div>
    @endif

    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table role="presentation" width="600" border="0" cellspacing="0" cellpadding="0" class="hb-container" style="width: 600px; max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.10), 0 10px 10px -5px rgba(0,0,0,0.04);">

                    {{-- Branded header --}}
                    <tr>
                        <td bgcolor="#0f0f14" style="background-color: #0f0f14; padding: 28px 40px; border-bottom: 3px solid #dc2626;" class="hb-pad">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-family: {{ $brand }}; color: #ffffff; font-size: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: 3px;">{{ $company }}</td>
                                    @if ($headerLabel)
                                        <td align="right" valign="middle" style="font-family: {{ $body }}; color: #9b9ba6; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 1.5px;">{{ $headerLabel }}</td>
                                    @endif
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Light body --}}
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;" class="hb-pad">
                            @if ($kicker)
                                <p style="margin: 0 0 10px; font-family: {{ $body }}; color: #dc2626; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">{{ $kicker }}</p>
                            @endif
                            @if ($heading)
                                <h1 class="hb-h1" style="margin: 0 0 24px; font-family: {{ $brand }}; color: #111827; font-size: 26px; font-weight: 700; line-height: 1.2;">{{ $heading }}</h1>
                            @endif
                            {{ $slot }}
                        </td>
                    </tr>

                    {{-- Branded footer --}}
                    <tr>
                        <td bgcolor="#0f0f14" style="background-color: #0f0f14; padding: 32px 40px; text-align: center;" class="hb-pad">
                            <p style="margin: 0 0 12px; font-family: {{ $brand }}; color: #ffffff; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">{{ $company }}</p>

                            @if ($address)
                                <p style="margin: 0 0 12px; font-family: {{ $body }}; color: #9b9ba6; font-size: 12px; line-height: 1.6;">{{ $address }}</p>
                            @endif

                            @if ($tel || $mail)
                                <p style="margin: 0 0 16px; font-family: {{ $body }}; font-size: 12px; line-height: 1.6;">
                                    @if ($tel)
                                        <a href="{{ $tel }}" style="color: #ffffff; font-weight: 600;">{{ $contact->primaryPhone }}</a>
                                    @endif
                                    @if ($tel && $mail)
                                        <span style="color: #4b5563;">&nbsp;&nbsp;&bull;&nbsp;&nbsp;</span>
                                    @endif
                                    @if ($mail)
                                        <a href="{{ $mail }}" style="color: #ffffff; font-weight: 600;">{{ $contact->email }}</a>
                                    @endif
                                </p>
                            @endif

                            @if ($footerNote)
                                <p style="margin: 0 0 12px; font-family: {{ $body }}; color: #9b9ba6; font-size: 11px; line-height: 1.6;">{{ $footerNote }}</p>
                            @endif

                            <p style="margin: 0; font-family: {{ $body }}; color: #4b5563; font-size: 11px; letter-spacing: 0.5px;">&copy; {{ date('Y') }} {{ $company }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
