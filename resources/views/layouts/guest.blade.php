<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        
        .auth-card {
            max-width: 400px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        
        .auth-body {
            padding: 30px;
            background-color: white;
            border-radius: 0 0 10px 10px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Logo/Header -->
                <div class="text-center mb-5">
                    <h1 class="display-4 text-primary">Faraid Calculator</h1>
                    <p class="lead text-muted">Islamic Inheritance Calculator</p>
                </div>
                
                <!-- Navigation Links -->
                <div class="text-center mb-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                            @endif
                        @endauth
                    @endif
                    
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">Home</a>
                </div>
                
                <!-- Content Section -->
                <div class="auth-card">
                    <div class="auth-header text-center">
                        <h4 class="mb-0">
                            @yield('title', 'Welcome')
                        </h4>
                    </div>
                    
                    <div class="auth-body">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <!-- Main Content -->
                        @yield('content')
                    </div>
                </div>
                
                <!-- Footer Links -->
                <div class="text-center mt-4">
                    <p class="text-muted">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none me-3">Forgot password?</a>
                        @endif
                        <a href="{{ route('faq.index') }}" class="text-decoration-none me-3">FAQ</a>
                        <a href="{{ route('contact') }}" class="text-decoration-none">Contact</a>
                    </p>
                    <p class="text-muted small">
                        &copy; {{ date('Y') }} Faraid Calculator. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>