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

        /* Reset Container */
        .reset-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
        }

        .reset-card {
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

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
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

        /* Password Strength */
        .password-strength {
            margin-top: 8px;
        }

        .strength-text {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 4px;
            display: block;
        }

        .strength-bar {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #dc3545;
            transition: width 0.3s, background 0.3s;
        }

        /* Password Match Indicator */
        .password-match {
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .match-success {
            color: #28a745;
        }

        .match-error {
            color: #dc3545;
        }

        /* Reset Button */
        .reset-btn {
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
            margin-top: 10px;
        }

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 95, 180, 0.3);
        }

        .reset-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .reset-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        /* Back to Login Section */
        .back-section {
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
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
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

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .reset-card {
                padding: 30px 20px;
            }
            
            .logo-title {
                font-size: 24px;
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

    <!-- Reset Container -->
    <div class="reset-container">
        <div class="reset-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <svg viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="logo-title">Reset Password</h1>
                <p class="logo-subtitle">Set your new password for Neo Faraid</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

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

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ request()->token ?? old('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" 
                           value="{{ old('email', request()->email ?? '') }}" 
                           required autofocus autocomplete="username"
                           class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Enter your email">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input id="password" type="password" name="password" 
                           required autocomplete="new-password"
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Enter new password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength">
                        <span class="strength-text" id="strength-text">Password strength</span>
                        <div class="strength-bar">
                            <div class="strength-fill" id="strength-fill"></div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input id="password_confirmation" type="password" 
                           name="password_confirmation" 
                           required autocomplete="new-password"
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           placeholder="Confirm new password">
                    @error('password_confirmation')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <span class="password-match" id="password-match"></span>
                </div>

                <!-- Reset Button -->
                <button type="submit" class="reset-btn" id="submitBtn">
                    <svg viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    Reset Password
                </button>
            </form>

            <!-- Back to Login Section -->
            <div class="back-section">
                <p class="back-text">Remember your password?</p>
                <a href="{{ route('login') }}" class="back-btn">
                    <svg viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordMatch = document.getElementById('password-match');
            const submitBtn = document.getElementById('submitBtn');
            const resetForm = document.getElementById('resetForm');
            
            // Check password strength
            function checkPasswordStrength(password) {
                let strength = 0;
                let text = 'Password strength';
                let color = '#dc3545';
                
                // Length check
                if (password.length >= 8) strength += 25;
                
                // Contains lowercase
                if (/[a-z]/.test(password)) strength += 25;
                
                // Contains uppercase
                if (/[A-Z]/.test(password)) strength += 25;
                
                // Contains numbers or special characters
                if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;
                
                // Update strength bar
                if (strengthFill) {
                    strengthFill.style.width = strength + '%';
                    
                    // Update color and text based on strength
                    if (strength < 50) {
                        color = '#dc3545';
                        text = 'Weak';
                    } else if (strength < 75) {
                        color = '#ffc107';
                        text = 'Fair';
                    } else {
                        color = '#28a745';
                        text = 'Strong';
                    }
                    
                    strengthFill.style.background = color;
                }
                
                if (strengthText) {
                    strengthText.textContent = text;
                    strengthText.style.color = color;
                }
            }
            
            // Check password match
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (!password || !confirmPassword) {
                    passwordMatch.textContent = '';
                    passwordMatch.className = 'password-match';
                    return;
                }
                
                if (password === confirmPassword) {
                    passwordMatch.textContent = '✓ Passwords match';
                    passwordMatch.className = 'password-match match-success';
                } else {
                    passwordMatch.textContent = '✗ Passwords do not match';
                    passwordMatch.className = 'password-match match-error';
                }
            }
            
            // Event listeners
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                    checkPasswordMatch();
                });
            }
            
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            }
            
            // Form submission
            if (resetForm) {
                resetForm.addEventListener('submit', function(e) {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<svg viewBox="0 0 20 20" class="animate-spin"><path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z"/></svg> Resetting Password...';
                    }
                });
            }
            
            // Initialize strength on page load
            if (passwordInput && passwordInput.value) {
                checkPasswordStrength(passwordInput.value);
            }
            
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
        });
    </script>
</body>
</html>