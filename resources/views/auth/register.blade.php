<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register • Neo Faraid</title>
    
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
            overflow-y: auto;
        }

        .page-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 0;
        }

        .background-elements {
            position: fixed;
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

        .register-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 550px;
            margin: 0 auto;
        }

        .register-card {
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

        .required-label::after {
            content: " *";
            color: #dc3545;
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

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            background: #dc3545;
            transition: width 0.3s, background 0.3s;
        }

        .terms-group {
            margin-bottom: 25px;
        }

        .form-check {
            display: flex;
            align-items: flex-start;
        }

        .form-check-input {
            margin-right: 10px;
            margin-top: 3px;
            accent-color: #1a5fb4;
        }

        .form-check-label {
            color: #6c757d;
            font-size: 14px;
            line-height: 1.5;
        }

        .terms-link {
            color: #1a5fb4;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        .register-btn {
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
        }

        .register-btn svg {
            fill: white;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 95, 180, 0.3);
        }

        .register-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
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

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            max-height: 300px;
            overflow-y: auto;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        .telegram-option {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }
        
        .divider {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        
        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 15px;
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }
        
        .divider:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: -1;
        }
        
        .telegram-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            background: #0088cc;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .telegram-btn svg {
            fill: white;
        }
        
        .telegram-btn:hover {
            background: #0077b3;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 136, 204, 0.3);
        }
        
        .telegram-btn svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }
        
        .telegram-hint {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 8px;
            font-style: italic;
        }

        .login-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .login-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .login-btn {
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

        .login-btn svg {
            fill: #1a5fb4;
            transition: fill 0.3s;
        }

        .login-btn:hover {
            background: #1a5fb4;
            color: white;
            transform: translateY(-2px);
        }

        .login-btn:hover svg {
            fill: white;
        }

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

        .form-group.focused .form-label {
            color: #1a5fb4;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 30px 20px;
            }
            .grid-2 {
                grid-template-columns: 1fr;
            }
            .logo-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="background-elements">
        <div class="bg-circle bg-circle-1"></div>
        <div class="bg-circle bg-circle-2"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="register-container">
            <div class="register-card">
                <div class="logo-section">
                    <div class="logo-icon">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h1 class="logo-title">Join Neo Faraid</h1>
                    <p class="logo-subtitle">Create your account to start estate planning</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if (session('telegram_linked'))
                    <div class="alert alert-telegram">
                        ✅ <strong>Telegram Account Linked!</strong><br>
                        Your Telegram account has been successfully linked. You can now use the bot features.
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    @if(request()->has('telegram_auth'))
                        <input type="hidden" name="telegram_auth" value="{{ request()->get('telegram_auth') }}">
                    @endif

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="name" class="form-label required-label">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                               class="form-control @error('name') is-invalid @enderror" placeholder="Enter your full name as per IC/Passport">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid-2">
                        <!-- NRIC / Passport Number -->
                        <div class="form-group">
                            <label for="nric" class="form-label required-label">NRIC/Passport Number</label>
                            <input id="nric" type="text" name="nric" value="{{ old('nric') }}" required 
                                   class="form-control @error('nric') is-invalid @enderror" placeholder="000000-00-0000">
                            @error('nric')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label required-label">Date of Birth</label>
                            <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                                   class="form-control @error('date_of_birth') is-invalid @enderror">
                            @error('date_of_birth')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <!-- Gender -->
                        <div class="form-group">
                            <label for="gender" class="form-label required-label">Gender</label>
                            <select id="gender" name="gender" required class="form-control @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label required-label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                                   class="form-control @error('email') is-invalid @enderror" placeholder="your@email.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label required-label">Password</label>
                            <input id="password" type="password" name="password" required 
                                   class="form-control @error('password') is-invalid @enderror" placeholder="Create a strong password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="password-strength">
                                <div class="strength-bar" id="passwordStrength"></div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label required-label">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm your password">
                            @error('password_confirmation')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Phone -->
                    <div class="form-group">
                        <label for="contact_phone" class="form-label required-label">Contact Phone</label>
                        <input id="contact_phone" type="tel" name="contact_phone" value="{{ old('contact_phone') }}" required 
                               class="form-control @error('contact_phone') is-invalid @enderror" placeholder="012-3456789">
                        @error('contact_phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Residential Address -->
                    <div class="form-group">
                        <label for="address" class="form-label required-label">Residential Address</label>
                        <textarea id="address" name="address" rows="3" required 
                                  class="form-control @error('address') is-invalid @enderror" 
                                  placeholder="Enter your full residential address">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="terms-group">
                        <div class="form-check">
                            <input id="terms" type="checkbox" name="terms" required 
                                   class="form-check-input @error('terms') is-invalid @enderror">
                            <label for="terms" class="form-check-label">
                                I agree to the <a href="#" class="terms-link">Terms & Conditions</a> and <a href="#" class="terms-link">Privacy Policy</a> *
                            </label>
                            @error('terms')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="register-btn" id="submitBtn">
                        <svg viewBox="0 0 20 20" width="18" height="18">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Create Account
                    </button>
                </form>

                <!-- Telegram Option -->
                <div class="telegram-option">
                    <div class="divider">
                        <span>Or continue with</span>
                    </div>
                    
                    <div class="telegram-auth">
                        <a href="https://t.me/FaraidCalculatorBot" target="_blank" class="telegram-btn" id="telegramBtn">
                            <svg viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 0 0-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.37.74-.56 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                            </svg>
                            Telegram Bot
                        </a>
                        <p class="telegram-hint" id="telegramHint">
                            Link your Telegram account for notifications and quick access
                        </p>
                    </div>
                </div>

                <div class="login-section">
                    <p class="login-text">Already have an account?</p>
                    <a href="{{ route('login') }}" class="login-btn">
                        <svg viewBox="0 0 20 20" width="16" height="16">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format NRIC input
            const nricInput = document.getElementById('nric');
            if (nricInput) {
                nricInput.addEventListener('input', function(e) {
                    let val = e.target.value.replace(/[^0-9]/g, '');
                    if (val.length >= 6) {
                        let formatted = val.substring(0,6);
                        if (val.length >= 8) {
                            formatted += '-' + val.substring(6,8);
                            if (val.length >= 12) {
                                formatted += '-' + val.substring(8,12);
                            } else if (val.length > 8) {
                                formatted += '-' + val.substring(8);
                            }
                        } else if (val.length > 6) {
                            formatted += '-' + val.substring(6);
                        }
                        e.target.value = formatted;
                    }
                });
            }

            // Phone input formatting
            const phoneInput = document.getElementById('contact_phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let val = e.target.value.replace(/[^0-9]/g, '');
                    if (val.length > 11) {
                        val = val.substring(0, 11);
                    }
                    if (val.length >= 4) {
                        val = val.substring(0, 3) + '-' + val.substring(3);
                    }
                    e.target.value = val;
                });
            }

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('passwordStrength');
            
            if (passwordInput && strengthBar) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    
                    if (password.length >= 8) strength += 25;
                    if (/[a-z]/.test(password)) strength += 25;
                    if (/[A-Z]/.test(password)) strength += 25;
                    if (/[0-9]/.test(password)) strength += 25;
                    
                    strengthBar.style.width = strength + '%';
                    
                    if (strength < 50) {
                        strengthBar.style.background = '#dc3545';
                    } else if (strength < 75) {
                        strengthBar.style.background = '#ffc107';
                    } else {
                        strengthBar.style.background = '#28a745';
                    }
                });
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

            // Form validation - REMOVED preventDefault to allow actual submission
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('password_confirmation').value;
                    const terms = document.getElementById('terms').checked;
                    const phone = document.getElementById('contact_phone').value;
                    
                    // Validate password match
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('Passwords do not match!');
                        return false;
                    }
                    
                    // Validate password length
                    if (password.length < 8) {
                        e.preventDefault();
                        alert('Password must be at least 8 characters long!');
                        return false;
                    }
                    
                    // Validate terms
                    if (!terms) {
                        e.preventDefault();
                        alert('You must agree to the Terms & Conditions and Privacy Policy');
                        return false;
                    }
                    
                    // Validate phone
                    const phoneNumbersOnly = phone.replace(/[^0-9]/g, '');
                    const phoneRegex = /^01[0-9]{8,9}$/;
                    if (!phoneRegex.test(phoneNumbersOnly)) {
                        e.preventDefault();
                        alert('Invalid phone number. Please use a valid Malaysian mobile number (e.g., 012-3456789)');
                        return false;
                    }
                    
                    // If validation passes, button is disabled and form submits
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg viewBox="0 0 20 20" class="animate-spin" width="18" height="18"><path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z" fill="white"/></svg> Creating Account...';
                    
                    // Form will submit normally after this
                    return true;
                });
            }

            // Set max date for date of birth
            const dobInput = document.getElementById('date_of_birth');
            if (dobInput) {
                const today = new Date();
                dobInput.max = today.toISOString().split('T')[0];
            }

            // Telegram button interaction
            const telegramBtn = document.getElementById('telegramBtn');
            const telegramHint = document.getElementById('telegramHint');
            
            if (telegramBtn && telegramHint) {
                telegramBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.open(this.href, '_blank', 'noopener,noreferrer');
                    
                    const originalText = telegramHint.textContent;
                    telegramHint.innerHTML = '<strong>Opening Telegram...</strong> Please connect with our bot to link your account.';
                    telegramHint.style.color = '#155724';
                    telegramHint.style.fontWeight = '500';
                    
                    setTimeout(() => {
                        telegramHint.textContent = originalText;
                        telegramHint.style.color = '#6c757d';
                        telegramHint.style.fontWeight = '';
                    }, 5000);
                });
            }

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('telegram_auth') && telegramHint) {
                telegramHint.innerHTML = '<strong>Link Token Ready!</strong> Register your account first, then click the Telegram button above to link your account.';
                telegramHint.style.color = '#155724';
                telegramHint.style.fontWeight = '500';
            }
        });
    </script>
</body>
</html>