<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email • Neo Faraid</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0d2d5c 0%, #1a5fb4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verify-container {
            max-width: 500px;
            width: 100%;
        }

        .verify-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .email-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .email-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a5fb4;
            margin-bottom: 15px;
        }

        .message {
            color: #6c757d;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .login-link {
            display: inline-block;
            margin-top: 20px;
            color: #1a5fb4;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .steps {
            text-align: left;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .steps h4 {
            color: #495057;
            margin-bottom: 10px;
        }

        .steps ol {
            padding-left: 20px;
            color: #6c757d;
            font-size: 14px;
        }

        .steps li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="email-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
            </div>
            
            <h1>Verify Your Email</h1>
            
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <p class="message">
                Thanks for registering! We've sent a verification link to your email address.
            </p>
            
            <div class="steps">
                <h4>📧 Next Steps:</h4>
                <ol>
                    <li>Check your email inbox</li>
                    <li>Look for email from <strong>Neo Faraid &lt;neofaraidadmin@gmail.com&gt;</strong></li>
                    <li>Click the verification link in the email</li>
                    <li>You will be redirected to the login page</li>
                    <li>Login with your credentials</li>
                </ol>
                <p style="font-size: 12px; color: #6c757d; margin-top: 10px;">
                    💡 Didn't receive the email? Check your spam/junk folder.
                </p>
            </div>
            
            <a href="{{ route('login') }}" class="login-link">
                ← Back to Login
            </a>
        </div>
    </div>
</body>
</html>