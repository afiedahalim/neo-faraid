<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Link Telegram • Neo Faraid</title>
    
    <!-- Poppins Font -->
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
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .page-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .logo-icon svg {
            width: 32px;
            height: 32px;
            fill: white;
        }

        .logo-title {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #1a5fb4, #2d7ad6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .logo-subtitle {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .telegram-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .telegram-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0088cc 0%, #2da5e0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .telegram-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        .telegram-title {
            font-size: 24px;
            font-weight: 600;
            color: #0088cc;
            margin-bottom: 10px;
        }

        .telegram-description {
            color: #6c757d;
            font-size: 14px;
            line-height: 1.6;
        }

        .status-section {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 12px;
            background: rgba(26, 95, 180, 0.05);
        }

        .status-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a5fb4;
            margin-bottom: 10px;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .status-dot.linked {
            background: #28a745;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
        }

        .status-dot.not-linked {
            background: #dc3545;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
        }

        .status-text {
            font-size: 14px;
            font-weight: 500;
        }

        .qr-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .qr-code {
            display: inline-block;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .qr-code img {
            width: 200px;
            height: 200px;
            display: block;
        }

        .qr-instructions {
            color: #6c757d;
            font-size: 14px;
            line-height: 1.6;
        }

        .instructions {
            background: rgba(0, 136, 204, 0.05);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .instructions-title {
            font-size: 16px;
            font-weight: 600;
            color: #0088cc;
            margin-bottom: 15px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
        }

        .step-number {
            width: 30px;
            height: 30px;
            background: #0088cc;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-weight: 500;
            color: #495057;
            margin-bottom: 5px;
        }

        .step-description {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.5;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0088cc 0%, #2da5e0 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 136, 204, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #495057;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #f8f9fa;
            border-color: #0088cc;
            color: #0088cc;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #3dc95d 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e35d6a 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
        }

        .btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .token-section {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .token-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .token-value {
            font-family: monospace;
            font-size: 14px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            word-break: break-all;
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .page-wrapper {
                padding: 10px 0;
            }
            
            .card {
                padding: 30px 20px;
            }
            
            .logo-title {
                font-size: 24px;
            }
            
            .telegram-title {
                font-size: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .qr-code img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="container">
            <div class="card">
                <!-- Logo Section -->
                <div class="logo-section">
                    <div class="logo-icon">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h1 class="logo-title">Neo Faraid</h1>
                    <p class="logo-subtitle">Islamic Inheritance Calculator</p>
                </div>

                <!-- Telegram Info -->
                <div class="telegram-info">
                    <div class="telegram-icon">
                        <svg viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 0 0-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.37.74-.56 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                        </svg>
                    </div>
                    <h2 class="telegram-title">Telegram Bot Linking</h2>
                    <p class="telegram-description">
                        Link your Telegram account to receive calculation notifications, 
                        PDF reports, and quick access to your inheritance calculations.
                    </p>
                </div>

                <!-- Status Section -->
                <div class="status-section">
                    <h3 class="status-title">Current Status</h3>
                    <div class="status-indicator">
                        <div class="status-dot {{ $isLinked ? 'linked' : 'not-linked' }}"></div>
                        <span class="status-text">
                            @if($isLinked)
                                ✅ Account Linked - You're connected to Telegram!
                            @else
                                🔄 Not Linked - Connect your Telegram account
                            @endif
                        </span>
                    </div>
                    
                    @if($isLinked)
                        <p style="color: #28a745; font-size: 14px;">
                            <strong>Linked as:</strong> 
                            @if($user->telegram_username)
                                @{{ $user->telegram_username }}
                            @else
                                {{ $user->telegram_first_name ?? 'User' }}
                            @endif
                        </p>
                        <p style="color: #6c757d; font-size: 12px; margin-top: 5px;">
                            Linked on: {{ $user->telegram_linked_at ? $user->telegram_linked_at->format('M d, Y H:i') : 'Recently' }}
                        </p>
                    @endif
                </div>

                <!-- QR Code Section -->
                <div class="qr-section">
                    <div class="qr-code">
                        <img id="qrCodeImage" src="{{ $qr_code_url ?? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($botUrl) }}" alt="Telegram Bot QR Code">
                    </div>
                    <p class="qr-instructions">
                        Scan this QR code with Telegram to quickly open the bot and link your account.
                    </p>
                </div>

                <!-- Instructions -->
                <div class="instructions">
                    <h3 class="instructions-title">How to Link</h3>
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-title">Open Telegram</div>
                            <div class="step-description">
                                Open the Telegram app on your phone or computer
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-title">Open Bot</div>
                            <div class="step-description">
                                Click the button below or scan the QR code to open: 
                                <strong>@FaraidCalculatorBot</strong>
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <div class="step-title">Start Bot</div>
                            <div class="step-description">
                                Send <code>/start {{ $telegramToken }}</code> to the bot
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <div class="step-title">Complete</div>
                            <div class="step-description">
                                Follow the instructions in the bot to complete linking
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ $botUrl }}" target="_blank" class="btn btn-primary">
                        <svg viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 0 0-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.37.74-.56 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                        </svg>
                        Open Telegram Bot
                    </a>
                    
                    @if($isLinked)
                        <button onclick="unlinkAccount()" class="btn btn-danger">
                            <svg viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Unlink Account
                        </button>
                    @else
                        <button onclick="copyToken()" class="btn btn-secondary">
                            <svg viewBox="0 0 20 20">
                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                            </svg>
                            Copy Token
                        </button>
                    @endif
                </div>

                <!-- Token Section -->
                <div class="token-section">
                    <div class="token-label">Your Link Token (Valid for 30 minutes):</div>
                    <div class="token-value" id="tokenValue">{{ $telegramToken }}</div>
                </div>

                <!-- Additional Info -->
                <div style="text-align: center; margin-top: 20px;">
                    <p style="color: #6c757d; font-size: 12px;">
                        Bot: @{{ $botUsername }} • 
                        <a href="https://t.me/{{ $botUsername }}" target="_blank" style="color: #0088cc; text-decoration: none;">
                            Open in Telegram
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Copy token to clipboard
        function copyToken() {
            const token = document.getElementById('tokenValue').textContent;
            navigator.clipboard.writeText(token).then(() => {
                alert('Token copied to clipboard! Send /start ' + token + ' to the bot.');
            }).catch(err => {
                console.error('Failed to copy token:', err);
                alert('Failed to copy token. Please select and copy manually.');
            });
        }

        // Unlink account
        function unlinkAccount() {
            if (confirm('Are you sure you want to unlink your Telegram account? You will stop receiving notifications.')) {
                fetch('{{ route("telegram.unlink") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Account unlinked successfully!');
                        location.reload();
                    } else {
                        alert('Failed to unlink account: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to unlink account. Please try again.');
                });
            }
        }

        // Generate new token
        function generateNewToken() {
            fetch('{{ route("telegram.generate-token") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('tokenValue').textContent = data.token;
                    document.getElementById('qrCodeImage').src = data.qr_code_url;
                    alert('New token generated! Use this new token to link.');
                } else {
                    alert('Failed to generate new token');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to generate new token');
            });
        }

        // Check link status periodically
        @if(!$isLinked)
        setInterval(() => {
            fetch('{{ route("telegram.check-status") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.linked) {
                        alert('✅ Account linked successfully!');
                        location.reload();
                    }
                });
        }, 5000); // Check every 5 seconds
        @endif
    </script>
</body>
</html>