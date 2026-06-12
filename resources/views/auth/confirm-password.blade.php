<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirm Password • Neo Faraid</title>
    
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

        /* Confirm Container */
        .confirm-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
        }

        .confirm-card {
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

        /* Security Message */
        .security-message {
            color: #6c757d;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
            text-align: center;
            padding: 15px;
            background: rgba(13, 45, 92, 0.05);
            border-radius: 12px;
            border-left: 4px solid #1a5fb4;
        }

        /* Security Icon */
        .security-icon {
            text-align: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .security-icon svg {
            width: 80px;
            height: 80px;
            fill: #1a5fb4;
            text-align: center;
            align-items: center;
            opacity: 0.8;
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

        /* Confirm Button */
        .confirm-btn {
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

        .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 95, 180, 0.3);
        }

        .confirm-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
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

        /* Responsive */
        @media (max-width: 480px) {
            .confirm-card {
                padding: 30px 20px;
            }
            
            .logo-title {
                font-size: 24px;
            }
            
            .security-message {
                font-size: 14px;
                padding: 12px;
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

    <!-- Confirm Container -->
    <div class="confirm-container">
        <div class="confirm-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <svg viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="logo-title">Neo Faraid</h1>
                <p class="logo-subtitle">Secure Area Access</p>
            </div>

            <!-- Security Icon -->
            <div class="security-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
            </div>

            <!-- Security Message -->
            <div class="security-message">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </div>

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

            <!-- Confirm Password Form -->
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required 
                           autocomplete="current-password"
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Enter your password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Button -->
                <button type="submit" class="confirm-btn">
                    {{ __('Confirm Password') }}
                </button>
            </form>
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
                        submitBtn.innerHTML = '<svg viewBox="0 0 20 20" class="animate-spin" style="animation: spin 1s linear infinite;"><path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z"/></svg> Verifying...';
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
                
                /* Focus effect for form label */
                .form-group.focused .form-label {
                    color: #1a5fb4;
                }
                
                /* Password field eye toggle */
                .password-toggle {
                    position: absolute;
                    right: 15px;
                    top: 50%;
                    transform: translateY(-50%);
                    background: none;
                    border: none;
                    cursor: pointer;
                    color: #6c757d;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .password-toggle:hover {
                    color: #1a5fb4;
                }
            `;
            document.head.appendChild(style);

            // Add password toggle functionality
            const passwordField = document.getElementById('password');
            if (passwordField) {
                const formGroup = passwordField.parentElement;
                formGroup.style.position = 'relative';
                
                const toggleBtn = document.createElement('button');
                toggleBtn.type = 'button';
                toggleBtn.className = 'password-toggle';
                toggleBtn.innerHTML = `
                    <svg viewBox="0 0 20 20" width="20" height="20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                `;
                
                toggleBtn.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    
                    // Update icon
                    if (type === 'text') {
                        this.innerHTML = `
                            <svg viewBox="0 0 20 20" width="20" height="20">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                            </svg>
                        `;
                    } else {
                        this.innerHTML = `
                            <svg viewBox="0 0 20 20" width="20" height="20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        `;
                    }
                });
                
                formGroup.appendChild(toggleBtn);
            }
        });
    </script>
</body>
</html>