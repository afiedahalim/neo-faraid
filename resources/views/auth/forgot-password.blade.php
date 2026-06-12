<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password • Neo Faraid</title>
    
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        .background-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
        }

        .bg-circle-1 {
            top: 10%;
            right: 5%;
            width: 300px;
            height: 300px;
        }

        .bg-circle-2 {
            bottom: 10%;
            left: 5%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
        }

        .floating-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation-duration: 6s;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
        }

        .shape-1 {
            width: 40px;
            height: 40px;
            top: 20%;
            left: 10%;
            animation: float-1 6s ease-in-out infinite;
        }

        .shape-2 {
            width: 25px;
            height: 25px;
            top: 60%;
            left: 85%;
            animation: float-2 6s ease-in-out infinite 1s;
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Logo/Header */
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

        /* Instruction Text */
        .instruction-text {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #1a5fb4;
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 95, 180, 0.3);
        }

        .submit-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        /* Back to Login Link */
        .back-link-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .back-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(26, 95, 180, 0.1);
            color: #1a5fb4;
            border: 2px solid #1a5fb4;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #1a5fb4;
            color: white;
            transform: translateY(-2px);
        }

        .back-btn svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
            transform: rotate(180deg);
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        /* Success Message */
        .success-message {
            text-align: center;
            padding: 20px;
            animation: fadeIn 0.5s ease-out;
        }

        .success-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .success-icon svg {
            width: 30px;
            height: 30px;
            fill: white;
        }

        .success-title {
            font-size: 20px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 10px;
        }

        .success-text {
            color: #6c757d;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Animations */
        @keyframes float-1 {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(120deg); }
            66% { transform: translateY(8px) rotate(240deg); }
        }

        @keyframes float-2 {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(90deg); }
            66% { transform: translateY(10px) rotate(180deg); }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .logo-title {
                font-size: 24px;
            }
            
            .instruction-text {
                font-size: 13px;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="background-elements">
        <div class="bg-circle bg-circle-1"></div>
        <div class="bg-circle bg-circle-2"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <svg viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="logo-title">Neo Faraid</h1>
                <p class="logo-subtitle">Password Reset</p>
            </div>

            <!-- Instruction Text -->
            <p class="instruction-text">
                Forgot your password? No problem. Just enter your email and we'll email you a password reset link.
            </p>

            <!-- Session Status -->
            @if (session('status'))
            <div class="alert alert-success">
            @php
                $status = session('status');
                if (is_array($status)) {
                echo $status['message'] ?? $status['status'] ?? 'Success';
                if (isset($status['reset_url']) && app()->environment('local')) {
                    echo '<br><small><a href="' . e($status['reset_url']) . '">Click here to reset password</a></small>';
                }
            } else {
                echo e($status);
            }
            @endphp
            </div>
                
                <!-- Success Message -->
                <div class="success-message">
                    <div class="success-icon">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="success-title">Check Your Email!</h3>
                    <p class="success-text">
                        If an account exists with the email you provided, you will receive a password reset link shortly.
                    </p>
                    <a href="{{ route('login') }}" class="back-btn">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            @else
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Enter your email">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        Send Reset Link
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="back-link-section">
                    <p class="back-text">Remembered your password?</p>
                    <a href="{{ route('login') }}" class="back-btn">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Form validation enhancement
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<svg viewBox="0 0 20 20" class="animate-spin"><path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z"/></svg> Sending...';
                    }
                });
            }

            // Add spin animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .animate-spin {
                    animation: spin 1s linear infinite;
                }
                .form-group.focused .form-label {
                    color: #1a5fb4;
                }
            `;
            document.head.appendChild(style);

            // Auto-focus email field if not showing success message
            const emailInput = document.getElementById('email');
            if (emailInput && !document.querySelector('.success-message')) {
                emailInput.focus();
            }
        });
    </script>
</body>
</html>