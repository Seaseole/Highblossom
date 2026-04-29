<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title>New Quote Request - {{ $companyName }}</title>
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
        
        .section {
            margin-bottom: 24px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #DC2626;
        }
        
        .section-title {
            font-weight: 600;
            color: #DC2626;
            margin-bottom: 12px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .field {
            margin-bottom: 12px;
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
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.025em;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            transition: transform 300ms cubic-bezier(0.23, 1, 0.32, 1);
        }
        
        .badge-mobile {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
        }
        
        .status-pending {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 600;
            letter-spacing: 0.025em;
            box-shadow: 0 2px 4px rgba(217, 119, 6, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .status-pending::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            transition: transform 300ms cubic-bezier(0.23, 1, 0.32, 1);
        }
        
        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
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
        <p style="margin: 8px 0 0; opacity: 0.9; font-size: 14px;">New Quote Request Received</p>
    </div>
    <div class="content">
        <div class="section">
            <div class="section-title">Customer Information</div>
            <div class="field">
                <span class="field-label">Name:</span>
                <span class="field-value">{{ $quote->name }}</span>
            </div>
            <div class="field">
                <span class="field-label">Phone:</span>
                <span class="field-value">{{ $quote->phone }}</span>
            </div>
            @if ($quote->email)
            <div class="field">
                <span class="field-label">Email:</span>
                <span class="field-value">{{ $quote->email }}</span>
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Vehicle Details</div>
            <div class="field">
                <span class="field-label">Type:</span>
                <span class="field-value">{{ ucfirst($quote->vehicle_type) }}</span>
            </div>
            @if ($quote->make_model)
            <div class="field">
                <span class="field-label">Make/Model:</span>
                <span class="field-value">{{ $quote->make_model }}</span>
            </div>
            @endif
            @if ($quote->reg_number)
            <div class="field">
                <span class="field-label">Registration:</span>
                <span class="field-value">{{ $quote->reg_number }}</span>
            </div>
            @endif
            @if ($quote->year)
            <div class="field">
                <span class="field-label">Year:</span>
                <span class="field-value">{{ $quote->year }}</span>
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Service Requested</div>
            <div class="field">
                <span class="field-label">Glass Type:</span>
                <span class="field-value">{{ $quote->glassType?->name ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Service Type:</span>
                <span class="field-value">{{ $quote->serviceType?->name ?? 'N/A' }}</span>
            </div>
            @if ($quote->mobile_service)
            <div class="field">
                <span class="field-label">Service Type:</span>
                <span class="badge badge-mobile">Mobile Service Requested</span>
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Quote Details</div>
            <div class="field">
                <span class="field-label">Quote ID:</span>
                <span class="field-value">#{{ $quote->id }}</span>
            </div>
            <div class="field">
                <span class="field-label">Status:</span>
                <span class="badge status-pending">{{ ucfirst($quote->status) }}</span>
            </div>
            <div class="field">
                <span class="field-label">Submitted:</span>
                <span class="field-value">{{ $quote->created_at->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>
    </div>
    <div class="footer">
        <p style="margin: 0 0 16px;">This quote request requires immediate attention. Please review the details above and contact the customer within 24 hours.</p>
        <a href="#" class="action-button">View Quote in Admin Panel</a>
    </div>
</body>
</html>
