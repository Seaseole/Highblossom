<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />
    <title>New Contact Message - {{ $companyName }}</title>
    <style type="text/css">
        /* Basic Reset */
        body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important; background-color: #111827; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
        table { border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        a { text-decoration: none; }

        /* Mobile Adjustments */
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 10px !important; }
            .stack { display: block !important; width: 100% !important; }
            .h1 { font-size: 20px !important; }
        }
    </style>
</head>
<body style="background-color: #111827; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #111827;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" class="container" style="width: 600px; background-color: #16161D; border: 1px solid #374151; border-radius: 4px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td bgcolor="#DC2626" style="padding: 24px 40px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <h1 style="color: #ffffff; font-size: 16px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 2px;">{{ $companyName }}</h1>
                                        <p style="color: #ffffff; font-size: 11px; font-weight: 500; margin: 4px 0 0; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px;">Inbound Support Message</p>
                                    </td>
                                    <td align="right">
                                        <div style="width: 12px; height: 12px; background-color: #ffffff; border-radius: 50%;"></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Sender Grid -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 40px; border-bottom: 1px solid #374151; padding-bottom: 32px;">
                                <tr>
                                    <td width="50%" class="stack" valign="top" style="padding-bottom: 20px;">
                                        <p style="font-size: 10px; color: #9CA3AF; margin: 0 0 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">FROM</p>
                                        <p style="font-size: 15px; color: #FAFAFA; margin: 0; font-weight: 600;">{{ $contactMessage->name }}</p>
                                    </td>
                                    <td width="50%" class="stack" valign="top">
                                        <p style="font-size: 10px; color: #9CA3AF; margin: 0 0 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">SUBJECT</p>
                                        <p style="font-size: 15px; color: #FAFAFA; margin: 0; font-weight: 600;">{{ $contactMessage->subject }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" class="stack" valign="top" style="padding-top: 20px;">
                                        <p style="font-size: 10px; color: #9CA3AF; margin: 0 0 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">EMAIL</p>
                                        <p style="font-size: 13px; color: #DC2626; margin: 0; font-weight: 600;">{{ $contactMessage->email }}</p>
                                    </td>
                                    @if($contactMessage->phone)
                                    <td width="50%" class="stack" valign="top" style="padding-top: 20px;">
                                        <p style="font-size: 10px; color: #9CA3AF; margin: 0 0 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">PHONE</p>
                                        <p style="font-size: 13px; color: #FAFAFA; margin: 0; font-weight: 600;">{{ $contactMessage->phone }}</p>
                                    </td>
                                    @endif
                                </tr>
                            </table>

                            <!-- Message Body -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 40px;">
                                <tr>
                                    <td bgcolor="#1F2937" style="padding: 32px; border-radius: 4px; border: 1px solid #374151;">
                                        <p style="font-size: 10px; color: #9CA3AF; margin: 0 0 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Message Transcript</p>
                                        <p style="font-size: 15px; color: #E5E7EB; margin: 0; line-height: 1.7; font-family: 'Geist Mono', 'Courier New', monospace;">
                                            {{ $contactMessage->message }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Timestamp -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 40px;">
                                <tr>
                                    <td>
                                        <p style="font-size: 11px; color: #6B7280; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Received: {{ $contactMessage->created_at->format('d M Y | H:i T') }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Action -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="mailto:{{ $contactMessage->email }}" style="display: block; padding: 16px 24px; background-color: #ffffff; color: #111827; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; border-radius: 4px;">Direct Reply to Customer</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#111827" style="padding: 32px 40px; border-top: 1px solid #374151; text-align: center;">
                            <p style="color: #4B5563; font-size: 10px; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Highblossom Technical Portal | Internal Use Only</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
