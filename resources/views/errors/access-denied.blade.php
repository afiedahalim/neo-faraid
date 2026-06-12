<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Neo Faraid</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            max-width: 550px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: #ffe0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc3545;
        }
        h1 { color: #dc3545; font-size: 1.8rem; margin-bottom: 0.5rem; }
        .status { color: #64748b; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .message { 
            background: #f8fafc; 
            padding: 1.25rem; 
            border-radius: 16px; 
            margin: 1.5rem 0; 
            border-left: 4px solid #dc3545;
            text-align: left;
        }
        .message p { margin: 0; line-height: 1.6; color: #334155; }
        .help-section {
            background: #f1f5f9;
            border-radius: 16px;
            padding: 1.25rem;
            margin: 1.5rem 0;
            text-align: left;
        }
        .help-section h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .help-section p {
            font-size: 0.85rem;
            color: #475569;
            margin: 0.5rem 0;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #1a5fb4, #2d7ad6);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #1e293b;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .footer {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.7rem;
            color: #94a3b8;
        }
        @media (max-width: 640px) { 
            .container { padding: 2rem 1.5rem; } 
            h1 { font-size: 1.5rem; }
            .btn-group { flex-direction: column; }
            .btn { justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1>Access Denied</h1>
        <div class="status">Invalid or Expired Link</div>
        <div class="message">
            <p>{{ $message ?? 'The access link you used is invalid or has expired. Please contact the estate administrator for a new link.' }}</p>
        </div>
        
        <div class="help-section">
            <h4>
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636L9.172 14.828a4 4 0 005.656 5.656l9.192-9.192a4 4 0 00-5.656-5.656z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 15.5l-4 4"/>
                </svg>
                Need Assistance?
            </h4>
            <div class="contact-item">
                <span>📧</span>
                <span>Email: <strong>{{ $support_email ?? 'neofaraidadmin@gmail.com' }}</strong></span>
            </div>
            <div class="contact-item">
                <span>📞</span>
                <span>Phone: <strong>{{ $support_phone ?? '+60 1-234-56-7890' }}</strong></span>
            </div>
        </div>
        
        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Go to Homepage
            </a>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Neo Faraid - Islamic Inheritance Calculator. All rights reserved.</p>
        </div>
    </div>
</body>
</html>