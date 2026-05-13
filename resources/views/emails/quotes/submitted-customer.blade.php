<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />
    <title>Quote Request Received - {{ $companyName }}</title>
    <style type="text/css">
        /* Basic Reset */
        body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important; background-color: #F3F4F6; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
        table { border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        a { text-decoration: none; }

        /* Mobile Adjustments */
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 10px !important; }
            .stack { display: block !important; width: 100% !important; padding-left: 0 !important; padding-right: 0 !important; }
            .mobile-center { text-align: center !important; }
            .h1 { font-size: 28px !important; }
        }
    </style>
</head>
<body style="background-color: #F3F4F6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F3F4F6;">
        <tr>
            <td align="center" style="padding: 60px 0;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" class="container" style="width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                    <!-- Header/Hero -->
                    <tr>
                        <td align="center" bgcolor="#111827" style="padding: 60px 40px; border-bottom: 6px solid #DC2626;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 4px;">{{ $companyName }}</h1>
                                        <div style="width: 40px; height: 2px; background-color: #DC2626; margin: 24px auto;"></div>
                                        <p style="color: #9CA3AF; font-size: 14px; font-weight: 500; margin: 0; text-transform: uppercase; letter-spacing: 2px;">Quote Request Confirmed</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 40px 40px 20px;">
                            <p style="font-size: 16px; color: #374151; margin: 0; line-height: 1.6;">Dear <strong>{{ $quote->name }}</strong>,</p>
                            <p style="font-size: 16px; color: #4B5563; margin: 16px 0 0; line-height: 1.6;">Thank you for choosing {{ $companyName }}. We have received your architectural glass request and our specialists are currently reviewing the specifications. We aim to provide a detailed response within 24 hours.</p>
                        </td>
                    </tr>

                    <!-- Quote Summary Card -->
                    <tr>
                        <td style="padding: 20px 40px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 32px;">
                                        <h2 style="font-size: 12px; font-weight: 700; color: #DC2626; margin: 0 0 20px; text-transform: uppercase; letter-spacing: 1px;">Request Specifications</h2>

                                        <!-- Primary Details -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 4px; font-weight: 600;">VEHICLE</p>
                                                    <p style="font-size: 14px; color: #111827; margin: 0; font-weight: 600;">{{ $quote->make_model ?? ucfirst($quote->vehicle_type) }} {{ $quote->year ? '(' . $quote->year . ')' : '' }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 4px; font-weight: 600;">SERVICE</p>
                                                    <p style="font-size: 14px; color: #111827; margin: 0; font-weight: 600;">{{ $quote->serviceType?->name ?? 'Architectural Glass Service' }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 4px; font-weight: 600;">GLASS SPECIFICATION</p>
                                                    <p style="font-size: 14px; color: #111827; margin: 0; font-weight: 600;">{{ $quote->full_glass_description }}</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Secondary Data -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top: 1px solid #E5E7EB; padding-top: 20px;">
                                            <tr>
                                                <td width="50%" class="stack" valign="top">
                                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 2px; font-weight: 600;">REFERENCE</p>
                                                    <p style="font-size: 13px; color: #111827; margin: 0; font-weight: 700;">#{{ $quote->id }}</p>
                                                </td>
                                                @if($quote->mobile_service)
                                                <td width="50%" class="stack mobile-padding" valign="top">
                                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 2px; font-weight: 600;">PREFERENCE</p>
                                                    <p style="font-size: 13px; color: #DC2626; margin: 0; font-weight: 700;">Mobile Service Requested</p>
                                                </td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Message Section (if exists) -->
                    @if($quote->message)
                    <tr>
                        <td style="padding: 20px 40px;">
                            <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 8px; font-weight: 600; text-transform: uppercase;">Your Notes</p>
                            <p style="font-size: 14px; color: #4B5563; margin: 0; font-style: italic; line-height: 1.5; border-left: 2px solid #E5E7EB; padding-left: 16px;">"{{ $quote->message }}"</p>
                        </td>
                    </tr>
                    @endif

                    <!-- CTA
                    <tr>
                        <td style="padding: 40px 40px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" bgcolor="#111827" style="border-radius: 4px;">
                                        <a href="#" style="display: block; padding: 20px 32px; color: #ffffff; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Track Your Request Status</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>--}} -->

                    <!-- Secondary Contact -->
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #E5E7EB; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 24px; text-align: center;">
                                        <p style="font-size: 13px; color: #6B7280; margin: 0 0 4px;">Need immediate technical assistance?</p>
                                        <a href="tel:+267{{ $primaryPhone ?? '+2673117480' }}" style="font-size: 15px; color: #111827; margin: 0; font-weight: 700;">{{ $primaryPhone ?? '+267 311 7480' }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#F9FAFB" style="padding: 40px; border-top: 1px solid #E5E7EB; text-align: center;">
                            <p style="color: #111827; font-size: 12px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 1px;">{{ $companyName }}</p>
                            <p style="color: #9CA3AF; font-size: 11px; margin: 8px 0 0; line-height: 1.5;">Architectural Glass & Aluminum Solutions<br />Gaborone, Botswana</p>
                            <div style="margin: 24px 0;">
                                <table align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="padding: 0 10px; color: #9CA3AF; font-size: 10px;">{{ date('Y') }} &copy; All Rights Reserved</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
