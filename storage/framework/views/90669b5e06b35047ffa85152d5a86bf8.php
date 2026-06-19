<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login • Neo Faraid</title>
    
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

        /* Main container for centering */
        .page-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 0;
        }

        /* Animated Background */
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

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: visible;
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

        /* Remember Me */
        .remember-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 8px;
            accent-color: #1a5fb4;
        }

        .form-check-label {
            color: #6c757d;
            font-size: 14px;
        }

        .forgot-link {
            color: #1a5fb4;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #1568ce;
            text-decoration: underline;
        }

        /* Login Button */
        .login-btn {
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

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 95, 180, 0.3);
        }

        .login-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        /* Telegram Option */
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

        /* Register Link */
        .register-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .register-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .register-btn {
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

        .register-btn:hover {
            background: #1a5fb4;
            color: white;
            transform: translateY(-2px);
        }

        .register-btn svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        /* Alerts */
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
        }

        /* Telegram Success Alert */
        .alert-telegram {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            border-left: 4px solid #0088cc;
        }

        /* 419 Error Specific Styling */
        .alert-expired {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            border-left: 4px solid #ffc107;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
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
            body {
                padding: 10px;
            }
            
            .page-wrapper {
                padding: 10px 0;
            }
            
            .login-card {
                padding: 30px 20px;
            }
            
            .logo-title {
                font-size: 24px;
            }
            
            .remember-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .divider span {
                font-size: 12px;
                padding: 0 10px;
            }
            
            .telegram-btn {
                padding: 12px;
                font-size: 14px;
            }
        }

        @media (max-height: 800px) {
            .page-wrapper {
                padding: 40px 0;
            }
            
            .login-card {
                margin: 20px 0;
            }
        }

        @media (max-height: 600px) {
            body {
                overflow-y: auto;
            }
            
            .page-wrapper {
                min-height: auto;
                padding: 20px 0;
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

    <!-- Main Wrapper for Centering -->
    <div class="page-wrapper">
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
                    <p class="logo-subtitle">Islamic Inheritance Calculator</p>
                </div>

                <!-- Session Expired Warning -->
                <?php if(session()->has('expired')): ?>
                    <div class="alert alert-expired">
                        ⚠️ <strong>Session Expired</strong><br>
                        Your session has expired. Please try logging in again.
                    </div>
                <?php endif; ?>

                <!-- Session Status -->
                <?php if(session('status')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <!-- Telegram Linking Status -->
                <?php if(session('telegram_linked')): ?>
                    <div class="alert alert-telegram">
                        ✅ <strong>Telegram Account Linked!</strong><br>
                        Your Telegram account has been successfully linked. You can now use the bot features.
                    </div>
                <?php endif; ?>

                <!-- CSRF Token Debug (remove in production) -->
                <?php if(config('app.debug')): ?>
                    <div style="display: none;" id="csrf-debug">
                        CSRF Token: <?php echo e(csrf_token()); ?><br>
                        Session ID: <?php echo e(session()->getId()); ?>

                    </div>
                <?php endif; ?>

                <!-- Validation Errors -->
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm">
                    <?php echo csrf_field(); ?>
                    <!-- CSRF Token Field (explicit hidden input) -->
                    <input type="hidden" name="_token" id="csrf_token" value="<?php echo e(csrf_token()); ?>">
                    
                    <!-- Additional anti-forgery field -->
                    <input type="hidden" name="_method" value="POST">

                    <!-- Telegram Token (if present in URL) -->
                    <?php if(request()->has('telegram_auth')): ?>
                        <input type="hidden" name="telegram_auth" value="<?php echo e(request()->get('telegram_auth')); ?>">
                    <?php endif; ?>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus 
                               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="Enter your email" autocomplete="email">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" required 
                               class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="Enter your password" autocomplete="current-password">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="remember-group">
                        <div class="form-check">
                            <input id="remember" type="checkbox" name="remember" class="form-check-input" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            <label for="remember" class="form-check-label">Remember me</label>
                        </div>
                        <?php if(Route::has('password.request')): ?>
                            <a href="<?php echo e(route('password.request')); ?>" class="forgot-link">
                                Forgot password?
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-btn" id="submitBtn">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Sign In
                    </button>
                </form>

                <!-- Telegram Option -->
                <div class="telegram-option">
                    <div class="divider">
                        <span>Or continue with</span>
                    </div>
                    
                    <div class="telegram-auth">
                        <a href="https://t.me/FaraidCalculatorBot" target="_blank" class="telegram-btn">
                            <svg viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 0 0-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.20-1.12-.31-1.08-.66.02-.18.27-.37.74-.56 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                            </svg>
                            Telegram Bot
                        </a>
                        <p class="telegram-hint">
                            Link your Telegram account for notifications and quick access
                        </p>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="register-section">
                    <p class="register-text">Don't have an account?</p>
                    <a href="<?php echo e(route('register')); ?>" class="register-btn">
                        <svg viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Login page loaded - CSRF Token Present');
            
            // Get CSRF token from meta tag
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            
            if (!csrfToken) {
                console.error('CSRF token not found in meta tag!');
                // Try to get from hidden input
                const csrfInput = document.getElementById('csrf_token');
                if (csrfInput && csrfInput.value) {
                    console.log('CSRF token found in hidden input');
                } else {
                    console.error('No CSRF token found anywhere!');
                    // Show warning to user
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-expired';
                    alertDiv.innerHTML = '⚠️ <strong>Security Token Missing</strong><br>Please refresh the page and try again.';
                    document.querySelector('.login-card').insertBefore(alertDiv, document.querySelector('.logo-section').nextSibling);
                }
            }

            // Store original form HTML for reset
            const originalFormHTML = document.getElementById('loginForm').innerHTML;
            let isSubmitting = false;

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

            // Form submission handler
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function(e) {
                // Prevent double submission
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }
                
                // Validate CSRF token
                const formCsrfToken = document.getElementById('csrf_token').value;
                if (!formCsrfToken) {
                    e.preventDefault();
                    console.error('CSRF token missing in form');
                    alert('Security token missing. Please refresh the page and try again.');
                    return;
                }
                
                // Show loading state
                isSubmitting = true;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg viewBox="0 0 20 20" class="animate-spin"><path fill-rule="evenodd" d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z"/></svg> Signing In...';
                }
                
                // You can optionally add form validation here
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                if (!email || !password) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                    resetFormState();
                    return;
                }
                
                // Form will submit normally
                console.log('Form submitting with CSRF token:', formCsrfToken.substring(0, 20) + '...');
            });

            // Reset form state function
            function resetFormState() {
                isSubmitting = false;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Sign In';
                }
            }

            // Handle form reset on page unload
            window.addEventListener('beforeunload', function() {
                if (isSubmitting) {
                    // If still submitting when leaving page, reset form
                    resetFormState();
                }
            });

            // Telegram button click tracking
            const telegramBtn = document.querySelector('.telegram-btn');
            if (telegramBtn) {
                telegramBtn.addEventListener('click', function(e) {
                    // Open in new tab
                    e.preventDefault();
                    window.open(this.href, '_blank', 'noopener,noreferrer');
                    
                    // Optional: Show message
                    const telegramHint = document.querySelector('.telegram-hint');
                    if (telegramHint) {
                        const originalText = telegramHint.textContent;
                        telegramHint.innerHTML = '<strong>Opening Telegram...</strong> Please connect with our bot to link your account.';
                        telegramHint.style.color = '#155724';
                        telegramHint.style.fontWeight = '500';
                        
                        // Reset after 5 seconds
                        setTimeout(() => {
                            telegramHint.textContent = originalText;
                            telegramHint.style.color = '';
                            telegramHint.style.fontWeight = '';
                        }, 5000);
                    }
                });
            }

            // Check for Telegram auth parameter in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('telegram_auth')) {
                // Show Telegram linking instruction
                const telegramHint = document.querySelector('.telegram-hint');
                if (telegramHint) {
                    telegramHint.innerHTML = '<strong>Link Token Ready!</strong> Log in first, then click the Telegram button above to link your account.';
                    telegramHint.style.color = '#155724';
                    telegramHint.style.fontWeight = '500';
                }
            }

            // Check for 419 error in URL
            if (urlParams.has('expired') || window.location.href.includes('419')) {
                // Show expired session warning
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-expired';
                alertDiv.innerHTML = '⚠️ <strong>Session Expired</strong><br>Your previous session has expired. Please log in again.';
                document.querySelector('.login-card').insertBefore(alertDiv, document.querySelector('.logo-section').nextSibling);
                
                // Refresh CSRF token
                refreshCsrfToken();
            }

            // Function to refresh CSRF token
            function refreshCsrfToken() {
                // This would typically be an AJAX call to get a new token
                console.log('Refreshing CSRF token...');
                
                // Update meta tag and hidden input with new token (simulated)
                setTimeout(() => {
                    console.log('CSRF token refreshed (simulated)');
                }, 1000);
            }

            // Add focus styles
            const style = document.createElement('style');
            style.textContent = `
                .form-group.focused .form-label {
                    color: #1a5fb4;
                }
                .telegram-btn:active,
                .login-btn:active {
                    transform: translateY(0);
                }
                .login-btn:disabled {
                    opacity: 0.7;
                    cursor: not-allowed;
                    transform: none !important;
                    box-shadow: none !important;
                }
            `;
            document.head.appendChild(style);

            // Enable scrolling on very small screens
            function checkViewportHeight() {
                const loginCard = document.querySelector('.login-card');
                if (!loginCard) return;
                
                const viewportHeight = window.innerHeight;
                const cardHeight = loginCard.offsetHeight;
                
                if (cardHeight > viewportHeight - 40) {
                    document.body.style.overflowY = 'auto';
                } else {
                    document.body.style.overflowY = 'hidden';
                }
            }
            
            // Check on load and resize
            checkViewportHeight();
            window.addEventListener('resize', checkViewportHeight);
            
            // Handle page cache issues
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    console.log('Page loaded from cache, refreshing CSRF...');
                    // Refresh the page to get new CSRF token
                    window.location.reload();
                }
            });
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/auth/login.blade.php ENDPATH**/ ?>