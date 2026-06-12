<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Neo Faraid')</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1a5fb4, #2d7ad6);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e2e8f0;
        }
        .button {
            display: inline-block;
            background: #1a5fb4;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-box {
            background: #f0f7ff;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #1a5fb4;
        }
        .warning-box {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #25D366;
        }
        @media (max-width: 600px) {
            .content { padding: 20px; }
            .header { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>@yield('header', 'Neo Faraid')</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p>This is an automated message from Neo Faraid. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Neo Faraid. All rights reserved.</p>
        </div>
    </div>
</body>
</html>