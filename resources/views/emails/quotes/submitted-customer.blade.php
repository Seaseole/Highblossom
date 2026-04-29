<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title>Quote Request Received - {{ $companyName }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            max-width: 600px;
            margin: 0 auto;
            padding: 16px;
            background: #f8f9fa;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 12px;
            }
        }
        
        .header {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            color: white;
            padding: 32px 24px;
            text-align: center;
            border-radius: 12px 12px 0 0;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g opacity="0.1"><rect width="60" height="60" fill="white"/><path d="M30 15c-8.284 0-15 6.716-15 15 6.716 15 15 15-6.716 15-15zm0 25c-8.284 0-15 6.716-15 15 6.716 15 15 15-6.716 15-15z" fill="white"/></g></svg>') center/60px;
            opacity: 0.05;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.025em;
        }
        
        .content {
            background: white;
            padding: 32px 24px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .summary-box {
            background: #f8f9fa;
            padding: 24px;
            border-left: 4px solid #DC2626;
            margin: 24px 0;
            border-radius: 8px;
        }
        
        .field {
            margin-bottom: 16px;
            display: flex;
            align-items: baseline;
            gap: 8px;
        }
        
        .field-label {
            font-weight: 500;
            color: #6b7280;
            min-width: 120px;
            flex-shrink: 0;
        }
        
        .field-value {
            color: #1a1a1a;
            word-break: break-word;
        }
        
        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        
        .contact-info {
            margin-top: 20px;
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 150ms cubic-bezier(0.32, 0.72, 0, 1), background-color 200ms cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.15), 0 1px 2px rgba(220, 38, 38, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 70%, transparent 100%);
            transition: transform 300ms cubic-bezier(0.23, 1, 0.32, 1);
        }
        
        .action-button:hover {
            background: linear-gradient(135deg, #B91C1C 0%, #991B1B 100%);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 8px rgba(185, 28, 28, 0.25), 0 2px 4px rgba(185, 28, 28, 0.15);
        }
        
        .action-button:active {
            transform: translateY(0) scale(0.98);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="logo-text">{{ $companyName }}</h1>
        <p style="margin: 8px 0 0; opacity: 0.9; font-size: 14px;">Quote Request Confirmation</p>
    </div>
    <div class="content">
        <p style="font-size: 16px; margin-bottom: 24px; color: #374151;">Dear {{ $quote->name }},</p>

        <p style="font-size: 16px; margin-bottom: 24px;">Thank you for choosing {{ $companyName }} for your auto glass needs. We've received your quote request and our team is already reviewing the details.</p>

        <div class="summary-box">
            <div style="font-weight: 600; color: #DC2626; margin-bottom: 16px; font-size: 14px;">QUOTE SUMMARY</div>
            <div class="field">
                <span class="field-label">Vehicle:</span>
                <span class="field-value">{{ $quote->make_model ?? ucfirst($quote->vehicle_type) }}</span>
            </div>
            <div class="field">
                <span class="field-label">Service:</span>
                <span class="field-value">{{ $quote->serviceType?->name ?? 'Auto Glass Service' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Quote Reference:</span>
                <span class="field-value" style="font-weight: 600;">#{{ $quote->id }}</span>
            </div>
        </div>

        <p style="font-size: 16px; margin: 24px 0;">We aim to provide you with a detailed quote within <strong style="color: #DC2626;">24 hours</strong> during business days. Our team may contact you for additional information or to schedule an inspection.</p>

        <div class="contact-info">
            <p style="font-weight: 600; margin-bottom: 8px; color: #374151;">Need urgent assistance?</p>
            <p style="margin-bottom: 16px;">Call us at <strong>{{ $primaryPhone ?? '+267 311 7480' }}</strong> or visit our workshop at <strong>Plot 123, Main Road, Broadhurst</strong></p>
            <a href="#" class="action-button" style="margin-top: 16px;">Track Your Quote</a>
        </div>

        <div class="footer">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 20px; background: #f1f5f9; border-radius: 8px; border: 1px solid #e5e7eb;">
                <div>
                    <p style="margin: 0; font-size: 14px; color: #6b7280;"><strong>Need urgent assistance?</strong></p>
                    <p style="margin: 4px 0 8px; font-size: 12px; color: #6b7280;">Call: <strong>+267 311 7480</strong></p>
                    <p style="margin: 0; font-size: 12px; color: #6b7280;">Visit: <strong>Plot 123, Main Road, Broadhurst</strong></p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 11px; color: #6b7280;">Mon-Fri: 8AM-5PM</p>
                    <p style="margin: 0; font-size: 11px; color: #6b7280;">Sat: 9AM-1PM</p>
                </div>
            </div>
            <p style="margin: 16px 0 8px; font-size: 14px; color: #374151;"><strong>Best regards,</strong></p>
            <p style="margin-bottom: 8px;"><strong> {{ $companyName }}</strong></p>
            <p style="font-size: 12px; color: #6b7280; margin-top: 20px;">
                This is an automated confirmation. For inquiries, please contact our support team.
            </p>
        </div>
    </div>
</body>
</html>
