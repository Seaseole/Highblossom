<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SMTP Test</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #DC2626;">SMTP Configuration Test</h2>
        <p>This is a test email sent from <strong>{{ config('app.name') }}</strong>.</p>
        <p>If you received this email, your SMTP settings are configured correctly.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #999;">Sent at {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
