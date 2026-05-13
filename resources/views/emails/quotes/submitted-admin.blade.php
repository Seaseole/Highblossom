<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />
    <title>New Quote Request - {{ $companyName }}</title>
    <style type="text/css">
        /* Basic Reset */
        body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important; background-color: #F8F9FA; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
        table { border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        a { text-decoration: none; }

        /* Mobile Adjustments */
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 10px !important; }
            .stack { display: block !important; width: 100% !important; padding-left: 0 !important; padding-right: 0 !important; }
            .mobile-hide { display: none !important; }
            .mobile-padding { padding-top: 20px !important; }
            .h1 { font-size: 24px !important; }
        }
    </style>
</head>
<body style="background-color: #F8F9FA; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #F8F9FA;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" class="container" style="width: 600px; background-color: #ffffff; border: 1px solid #E5E7EB; border-radius: 4px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td bgcolor="#111827" style="padding: 40px 40px; border-bottom: 4px solid #DC2626;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <h1 style="color: #ffffff; font-size: 20px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 2px;">{{ $companyName }}</h1>
                                        <p style="color: #9CA3AF; font-size: 12px; font-weight: 500; margin: 8px 0 0; text-transform: uppercase; letter-spacing: 1px;">Admin Notification: New Quote Request</p>
                                    </td>
                                    <td align="right" style="color: #DC2626; font-size: 14px; font-weight: 700;">
                                        #{{ $quote->id }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Customer Info -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 32px;">
                                <tr>
                                    <td style="border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">
                                        <h2 style="font-size: 14px; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase;">Customer Information</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 16px;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="100" style="font-size: 12px; color: #6B7280; font-weight: 600; padding-bottom: 8px;">NAME</td>
                                                <td style="font-size: 14px; color: #111827; font-weight: 500; padding-bottom: 8px;">{{ $quote->name }}</td>
                                            </tr>
                                            <tr>
                                                <td width="100" style="font-size: 12px; color: #6B7280; font-weight: 600; padding-bottom: 8px;">PHONE</td>
                                                <td style="font-size: 14px; color: #111827; font-weight: 500; padding-bottom: 8px;">{{ $quote->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td width="100" style="font-size: 12px; color: #6B7280; font-weight: 600; padding-bottom: 8px;">EMAIL</td>
                                                <td style="font-size: 14px; color: #111827; font-weight: 500; padding-bottom: 8px;">{{ $quote->email ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Vehicle Details -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 32px; background-color: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <h2 style="font-size: 12px; font-weight: 700; color: #6B7280; margin: 0 0 16px; text-transform: uppercase;">Vehicle Specifications</h2>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="50%" class="stack" valign="top">
                                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 4px; font-weight: 600;">MAKE & MODEL</p>
                                                    <p style="font-size: 15px; color: #111827; margin: 0; font-weight: 700;">{{ $quote->make_model ?? 'Not specified' }}</p>
                                                </td>
                                                <td width="50%" class="stack mobile-padding" valign="top">
                                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0 0 4px; font-weight: 600;">YEAR / REG</p>
                                                    <p style="font-size: 15px; color: #111827; margin: 0; font-weight: 700;">{{ $quote->year ?? '----' }} / {{ $quote->reg_number ?? '----' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Service Details -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 32px;">
                                <tr>
                                    <td style="border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">
                                        <h2 style="font-size: 14px; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase;">Workshop Requirements</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 16px;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding-bottom: 16px;">
                                                    <p style="font-size: 12px; color: #6B7280; margin: 0 0 4px; font-weight: 600;">GLASS TYPE</p>
                                                    <p style="font-size: 14px; color: #111827; margin: 0; font-weight: 600;">{{ $quote->full_glass_description }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 16px;">
                                                    <p style="font-size: 12px; color: #6B7280; margin: 0 0 4px; font-weight: 600;">SERVICE TYPE</p>
                                                    <p style="font-size: 14px; color: #111827; margin: 0; font-weight: 600;">{{ $quote->serviceType?->name ?? 'N/A' }}</p>
                                                </td>
                                            </tr>
                                            @if($quote->mobile_service)
                                            <tr>
                                                <td style="padding-bottom: 16px;">
                                                    <span style="background-color: #FEE2E2; color: #DC2626; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 2px; text-transform: uppercase;">Mobile Service Requested</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @if($quote->message)
                            <!-- User Message -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 32px;">
                                <tr>
                                    <td style="background-color: #F3F4F6; padding: 24px; border-radius: 4px; border-left: 4px solid #9CA3AF;">
                                        <p style="font-size: 12px; color: #6B7280; margin: 0 0 8px; font-weight: 600; text-transform: uppercase;">Message from Customer</p>
                                        <p style="font-size: 14px; color: #374151; margin: 0; font-style: italic; line-height: 1.5;">"{{ $quote->message }}"</p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Reference Image -->
                            @if($quote->image_path)
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 40px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('storage/' . $quote->image_path) }}" target="_blank" style="display: block; padding: 12px 24px; border: 2px solid #111827; color: #111827; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; border-radius: 0;">View Reference Image</a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- CTA -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" bgcolor="#DC2626" style="border-radius: 4px;">
                                        <a href="{{ route('admin.quotes.show', $quote->id) }}" target="_blank" style="display: block; padding: 18px 24px; color: #ffffff; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Access Admin Portal</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#F9FAFB" style="padding: 32px 40px; border-top: 1px solid #E5E7EB; text-align: center;">
                            <p style="color: #9CA3AF; font-size: 11px; margin: 0; text-transform: uppercase; letter-spacing: 1px;">&copy; {{ date('Y') }} {{ $companyName }} PTY LTD. All rights reserved.</p>
                            <p style="color: #9CA3AF; font-size: 10px; margin: 8px 0 0;">This is a system-generated notification. Please do not reply to this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
