<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, initial-scale=1.0">
    <title>Estate Notification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Reset styles for email clients */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f5f7fa;
            margin: 0;
            padding: 30px 20px;
            -webkit-font-smoothing: antialiased;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }
        
        /* Header Styles */
        .email-header {
            background: linear-gradient(135deg, #0d2d5c 0%, #1a5fb4 100%);
            padding: 40px 30px;
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
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .email-header p {
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-size: 14px;
            font-weight: 400;
        }
        
        /* Content Styles */
        .email-content {
            padding: 40px 30px;
            background: #ffffff;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e293b;
        }
        
        .greeting strong {
            color: #1a5fb4;
        }
        
        .message {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 25px;
            font-size: 15px;
        }
        
        .info-box {
            background: linear-gradient(135deg, #f0f7ff 0%, #e8f1fd 100%);
            border-left: 4px solid #1a5fb4;
            padding: 20px 24px;
            margin: 25px 0;
            border-radius: 16px;
        }
        
        .info-box strong {
            color: #1a5fb4;
            display: block;
            margin-bottom: 12px;
            font-size: 16px;
            font-weight: 700;
        }
        
        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .info-box li {
            margin: 8px 0;
            color: #334155;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .warning-box {
            background: #fff8f0;
            border-left-color: #ed8936;
        }
        
        .warning-box strong {
            color: #ed8936;
        }
        
        .details-list {
            margin: 20px 0;
            padding-left: 24px;
            list-style-type: none;
        }
        
        .details-list li {
            margin: 12px 0;
            color: #334155;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .details-list li svg {
            width: 18px;
            height: 18px;
            color: #1a5fb4;
            flex-shrink: 0;
        }
        
        .button-container {
            text-align: center;
            margin: 35px 0 30px;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
            color: #ffffff !important;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 95, 180, 0.3);
        }
        
        .expiry-info {
            background-color: #f8fafc;
            padding: 16px 20px;
            border-radius: 14px;
            margin: 25px 0;
            font-size: 13px;
            color: #475569;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        
        .expiry-info strong {
            color: #1a5fb4;
            font-weight: 600;
        }
        
        .estate-ref {
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .estate-ref strong {
            color: #1a5fb4;
            font-weight: 600;
        }
        
        /* Footer Styles */
        .email-footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .email-footer p {
            margin: 8px 0;
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
        }
        
        .email-footer strong {
            color: #1a5fb4;
            font-weight: 600;
        }
        
        .footer-links {
            margin-top: 15px;
        }
        
        .footer-links a {
            color: #1a5fb4;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 15px 10px;
            }
            
            .email-content {
                padding: 30px 20px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .email-header h1 {
                font-size: 24px;
            }
            
            .logo-icon {
                width: 55px;
                height: 55px;
            }
            
            .logo-icon svg {
                width: 26px;
                height: 26px;
            }
            
            .btn {
                padding: 12px 25px;
                font-size: 14px;
            }
            
            .info-box {
                padding: 16px 18px;
            }
            
            .details-list li {
                font-size: 13px;
            }
        }
        
        @media (max-width: 480px) {
            .email-content {
                padding: 25px 15px;
            }
            
            .greeting {
                font-size: 16px;
            }
            
            .message {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="email-header">
            <div class="logo-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1>Estate Notification</h1>
            <p>Neo Faraid - Islamic Inheritance Management</p>
        </div>
        
        <!-- Content Section -->
        <div class="email-content">
            <div class="greeting">
                Dear <strong>{{ $beneficiary_name ?? 'Beneficiary' }}</strong>,
            </div>
            
            <p class="message">
                You have been identified as a beneficiary of the estate of 
                <strong>{{ $deceased_name ?? 'the deceased' }}</strong>.
            </p>
            
            <div class="info-box">
                <strong>What You Need to Know</strong>
                <ul>
                    <li>You are listed as a legal heir in the deceased's estate plan</li>
                    <li>The estate includes assets, debts, and a will (if available)</li>
                    <li>Debts must be settled before inheritance distribution</li>
                    <li>Distribution follows Islamic inheritance laws (Faraid)</li>
                </ul>
            </div>
            
            <p class="message" style="margin-bottom: 10px;">Please review the complete estate information, including:</p>
            <ul class="details-list">
                <li>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    📜 Will video and written instructions (if available)
                </li>
                <li>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    🏠 List of assets and properties
                </li>
                <li>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    💰 Outstanding debts and liabilities
                </li>
                <li>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ⚖️ Inheritance distribution details (after debt settlement)
                </li>
                <li>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    📋 Next steps and required documentation
                </li>
            </ul>
            
            <div class="button-container">
                <a href="{{ $view_url ?? '#' }}" class="btn">View Estate Information</a>
            </div>
            
            <div class="info-box warning-box">
                <strong>⚠️ Important Security Notice</strong>
                <p style="margin: 5px 0 0; font-size: 14px; color: #9b6b00;">This link is confidential and should only be used by the intended beneficiary. Do not forward this email to anyone else. Each access is logged for security purposes.</p>
            </div>
            
            <div class="expiry-info">
                <strong>🔒 Link Expiry:</strong> {{ $expiry_date ?? '7 days from now' }}<br>
                <span style="font-size: 12px;">After this date, please contact the estate administrator for access.</span>
            </div>
            
            @if(isset($additional_instructions) && $additional_instructions)
                <div class="info-box" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-left-color: #4caf50;">
                    <strong style="color: #2e7d32;">📋 Additional Instructions</strong>
                    <p style="margin: 8px 0 0; color: #1b5e20;">{{ $additional_instructions }}</p>
                </div>
            @endif
            
            @if(isset($estate_reference) && $estate_reference)
                <div class="estate-ref">
                    <strong>Reference Number:</strong> {{ $estate_reference }}
                </div>
            @endif
        </div>
        
        <!-- Footer Section -->
        <div class="email-footer">
            <p><strong>Neo Faraid Inheritance Management System</strong></p>
            <p>Your Trusted Partner in Shariah-Compliant Estate Planning</p>
            <div class="footer-links">
                <p style="margin-top: 15px;">
                    Need assistance? 
                    <a href="{{ $contact_url ?? '#' }}" style="color: #1a5fb4; text-decoration: none;">Contact Support</a>
                </p>
            </div>
            <p style="margin-top: 15px;">
                This is an automated message from Neo Faraid. Please do not reply to this email.
            </p>
            <p>
                &copy; {{ date('Y') }} Neo Faraid. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>