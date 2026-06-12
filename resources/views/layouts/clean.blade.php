<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Neo Faraid')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Figtree', sans-serif; 
            min-height: 100vh;
            position: relative;
        }
        .content { position: relative; z-index: 10; }
    </style>
</head>
<body>
    <!-- Use component instead of function -->
    <x-app-background type="animated" />
    
    <div class="content">
        @include('partials.navigation')
        
        <main class="min-h-screen">
            @yield('content')
        </main>
        
        @include('partials.footer')
    </div>
    
    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
    
    @stack('scripts')
</body>
</html>