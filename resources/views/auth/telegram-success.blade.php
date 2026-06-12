{{-- resources/views/auth/telegram-success.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Linked Successfully • Neo Faraid</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .success-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        
        .success-icon svg {
            width: 50px;
            height: 50px;
            fill: white;
        }
        
        h1 {
            color: #25D366;
            margin-bottom: 15px;
            font-size: 32px;
        }
        
        .message {
            color: #555;
            margin-bottom: 30px;
            font-size: 18px;
            line-height: 1.5;
        }
        
        .features {
            background: #f0fff4;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .features h3 {
            color: #128C7E;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-list li {
            padding: 10px 0 10px 35px;
            position: relative;
            color: #555;
            margin-bottom: 8px;
        }
        
        .feature-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #25D366;
            font-weight: bold;
            font-size: 18px;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            flex: 1;
            padding: 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s;
            min-width: 120px;
        }
        
        .btn-telegram {
            background: #0088cc;
            color: white;
        }
        
        .btn-telegram:hover {
            background: #0077b3;
            transform: translateY(-2px);
        }
        
        .btn-web {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #dee2e6;
        }
        
        .btn-web:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .btn-dashboard {
            background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
            color: white;
            margin-top: 10px;
        }
        
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 95, 180, 0.3);
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <svg viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
        </div>
        
        <h1>Successfully Linked!</h1>
        <p class="message">Your Telegram account has been successfully linked with Neo Faraid.</p>
        
        <div class="features">
            <h3>🎉 What You Can Do Now:</h3>
            <ul class="feature-list">
                <li>Access your calculations from Telegram</li>
                <li>Receive notifications for new features</li>
                <li>Start new calculations with /calculate</li>
                <li>View your history with /history</li>
                <li>Sync data between web and Telegram</li>
            </ul>
        </div>
        
        <div class="buttons">
            <a href="https://t.me/{{ config('services.telegram.bot_username') }}" class="btn btn-telegram" target="_blank">
                Open Telegram
            </a>
            <a href="{{ route('calculator.create') }}" class="btn btn-web">
                Start Calculation
            </a>
        </div>
        
        <a href="{{ route('home') }}" class="btn btn-dashboard">
            Go to Dashboard
        </a>
    </div>
</body>
</html>