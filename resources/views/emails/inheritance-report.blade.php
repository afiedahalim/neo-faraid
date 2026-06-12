{{-- resources/views/emails/inheritance-report.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inheritance Report - {{ $deceased_name ?? 'Deceased' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f5f7fa;
            padding: 20px;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }
        
        .email-header {
            background: linear-gradient(135deg, #0d2d5c 0%, #1a5fb4 100%);
            padding: 35px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,215,0,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .email-header::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,215,0,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .logo-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .logo-icon svg {
            width: 32px;
            height: 32px;
            color: #ffd700;
        }
        
        .email-header h1 {
            color: #ffd700;
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            letter-spacing: -0.5px;
        }
        
        .email-header p {
            color: rgba(255, 255, 255, 0.95);
            margin: 12px 0 0;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        .email-body {
            padding: 35px 30px;
            background: #ffffff;
        }
        
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #1e293b;
            line-height: 1.6;
        }
        
        .greeting strong {
            color: #1a5fb4;
        }
        
        .message-text {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 25px;
            font-size: 15px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a5fb4;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e8f1fd;
        }
        
        .info-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        
        .info-box p {
            margin: 8px 0;
            color: #1e293b;
        }
        
        .info-box p:first-child { margin-top: 0; }
        .info-box p:last-child { margin-bottom: 0; }
        
        .amount {
            font-size: 22px;
            font-weight: 800;
            color: #1a5fb4;
        }
        
        .amount-highlight {
            background: linear-gradient(135deg, #25D36610 0%, #25D36620 100%);
            display: inline-block;
            padding: 4px 12px;
            border-radius: 8px;
            font-weight: 700;
            color: #1a5fb4;
        }
        
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
            color: white !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 95, 180, 0.3);
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 95, 180, 0.4);
        }
        
        .note-box {
            background: #fff8e7;
            border-left: 4px solid #ffc107;
            padding: 18px 22px;
            border-radius: 16px;
            margin: 25px 0;
        }
        
        .note-box p {
            margin: 8px 0;
            color: #856404;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .note-box p:first-child { margin-top: 0; }
        .note-box p:last-child { margin-bottom: 0; }
        .note-box strong { font-size: 14px; }
        
        .attachments-box {
            background: #e8f4f8;
            border-radius: 16px;
            padding: 18px 22px;
            margin: 20px 0;
        }
        
        .attachments-box p {
            margin: 8px 0;
            color: #0c5460;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .attachments-box strong { color: #0c5460; }
        
        .security-badge {
            background: #e8f1fd;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 12px;
            margin-top: 25px;
            text-align: center;
            color: #1a5fb4;
        }
        
        .security-badge svg {
            width: 16px;
            height: 16px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }
        
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer p {
            color: #64748b;
            font-size: 11px;
            margin: 5px 0;
            line-height: 1.5;
        }
        
        .pdf-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1a5fb4;
            margin: 1.2rem 0 0.6rem 0;
            padding-bottom: 0.3rem;
            border-bottom: 2px solid #e8f1fd;
        }
        
        .pdf-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.8rem;
            font-size: 0.7rem;
        }
        
        .pdf-info-table td {
            padding: 0.4rem 0.5rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .pdf-info-table td:first-child {
            font-weight: 600;
            color: #475569;
            width: 30%;
        }
        
        .pdf-info-card {
            padding: 0.5rem 0.8rem;
            background: #f8fafc;
            border-radius: 6px;
            margin-bottom: 0.3rem;
            border-left: 3px solid #1a5fb4;
            font-size: 0.7rem;
        }
        
        @media (max-width: 600px) {
            .email-wrapper { border-radius: 16px; }
            .email-header { padding: 25px 20px; }
            .email-header h1 { font-size: 22px; }
            .email-body { padding: 25px 20px; }
            .button { padding: 12px 24px; font-size: 14px; }
            .section-title { font-size: 16px; }
            .amount { font-size: 18px; }
        }
        
        @media print {
            body { background: white !important; padding: 0; }
            .button-container, .attachments-box, .note-box, .security-badge, .footer { display: none !important; }
            .email-wrapper { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
            .email-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="logo-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1>Inheritance Report</h1>
            <p>Estate of {{ $deceased_name ?? 'the deceased' }}</p>
        </div>

        <div class="email-body">
            <div class="greeting">
                Dear <strong>{{ $recipient_name ?? 'Sir/Madam' }}</strong>,
            </div>
            
            <p class="message-text">
                We have completed the inheritance calculation for the late 
                <strong>{{ $deceased_name ?? 'the deceased' }}</strong>.
            </p>
            
            <div class="info-box">
                <p><strong>📋 Report Details:</strong></p>
                <p>Request ID: {{ $request_id ?? 'N/A' }}</p>
                <p>Generated on: {{ $date ?? now()->format('d M Y, h:i A') }}</p>
            </div>
            
            <div class="section-title">What's Included</div>
            <ul style="margin: 0 0 20px 20px; color: #475569; line-height: 1.8;">
                <li>Deceased information and estate details</li>
                <li>Complete list of eligible heirs</li>
                <li>Inheritance distribution according to Faraid principles</li>
                <li>Breakdown of shares and amounts</li>
                <li>Assets and debts inventory</li>
            </ul>
            
            <div class="section-title">Secure Access</div>
            <div class="info-box">
                <p><strong>🔒 Access Link:</strong> Click the button below to view your report online.</p>
            </div>
            
            <div class="button-container">
                <a href="{{ route('instant-estate.public-view', $access_token ?? '') }}" class="button">View Inheritance Report</a>
            </div>
            
            <div class="attachments-box">
                <p><strong>📎 Attached Document:</strong></p>
                <p>✓ <strong>PDF Estate Distribution Statement</strong> - Complete inheritance breakdown</p>
                <p><em>Please save the attached PDF for your records.</em></p>
            </div>
            
            <div class="note-box">
                <p><strong>📌 Important Notes:</strong></p>
                <p>• This report is confidential and intended only for the legal heirs.</p>
                <p>• Please do not share this report with unauthorized individuals.</p>
                <p>• The online access link will expire after 30 days.</p>
                <p>• This report is for informational purposes only. For legal binding purposes, consult with a qualified Islamic inheritance lawyer (Peguam Syarie) or your local Shariah Court (Mahkamah Syariah).</p>
            </div>
            
            <div class="security-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>This is a secure, encrypted notification. Your privacy is protected.</span>
            </div>
            
            <p class="message-text" style="margin-top: 25px;">
                If you have any questions about this inheritance distribution, 
                please contact our support team at 
                <strong>{{ config('mail.from.address') }}</strong>.
            </p>
            
            <p style="margin-top: 25px;">
                Best regards,<br>
                <strong style="color: #1a5fb4;">Neo Faraid Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>This is an automated message from the Neo Faraid System. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Neo Faraid. All rights reserved.</p>
        </div>
    </div>
</body>
</html>