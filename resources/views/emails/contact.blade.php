<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message - {{ $companyName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #73081d;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #73081d;
        }
        .message-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #73081d;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Message</h1>
        <p>{{ $companyName }}</p>
    </div>
    <div class="content">
        <div class="field">
            <span class="field-label">From:</span> {{ $contactMessage->name }}
        </div>
        <div class="field">
            <span class="field-label">Email:</span> {{ $contactMessage->email }}
        </div>
        @if ($contactMessage->phone)
        <div class="field">
            <span class="field-label">Phone:</span> {{ $contactMessage->phone }}
        </div>
        @endif
        <div class="field">
            <span class="field-label">Subject:</span> {{ $contactMessage->subject }}
        </div>
        <div class="field">
            <span class="field-label">Message:</span>
            <div class="message-box">
                {{ $contactMessage->message }}
            </div>
        </div>
        <div class="field">
            <span class="field-label">Received:</span> {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}
        </div>
    </div>
</body>
</html>
