<!-- Enhanced Responsive Navigation -->
<nav class="main-nav" role="navigation" aria-label="Main navigation">
    <div class="nav-container">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ url('/') }}" class="logo-link" aria-label="Neo Faraid Home">
                <svg class="logo-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
                <span class="logo-text">Neo Faraid</span>
            </a>
        </div>
        
        <!-- Desktop Navigation -->
        <div class="desktop-nav-links">
            <!-- Public Links -->
            <a href="{{ url('/') }}" 
               class="nav-link white-link {{ request()->is('/') ? 'active' : '' }}"
               data-link="home"
               aria-current="{{ request()->is('/') ? 'page' : 'false' }}">
                <span class="link-text">Home</span>
            </a>
            
            <!-- Instant Estate -->
            <a href="{{ route('instant-estate.index') }}" 
               class="nav-link white-link {{ request()->is('instant-estate*') ? 'active' : '' }}"
               data-link="instant-estate"
               aria-current="{{ request()->is('instant-estate*') ? 'page' : 'false' }}">
                <span class="link-text">Instant Estate</span>
            </a>
            
            @auth
            <!-- Calculator only for authenticated users -->
            <a href="{{ route('calculator.index') }}" 
               class="nav-link white-link {{ request()->is('calculator*') && !request()->is('calculator/history') ? 'active' : '' }}"
               data-link="calculator"
               aria-current="{{ request()->is('calculator*') && !request()->is('calculator/history') ? 'page' : 'false' }}">
                <span class="link-text">Calculator</span>
            </a>
            @endauth
            
            <a href="{{ route('about') }}" 
               class="nav-link white-link {{ request()->is('about') ? 'active' : '' }}"
               data-link="about"
               aria-current="{{ request()->is('about') ? 'page' : 'false' }}">
                <span class="link-text">About</span>
            </a>
            
            <a href="{{ route('contact') }}" 
               class="nav-link white-link {{ request()->is('contact') ? 'active' : '' }}"
               data-link="contact"
               aria-current="{{ request()->is('contact') ? 'page' : 'false' }}">
                <span class="link-text">Contact</span>
            </a>
            
            <a href="{{ route('faq.index') }}" 
               class="nav-link white-link {{ request()->is('faq') ? 'active' : '' }}"
               data-link="faq"
               aria-current="{{ request()->is('faq') ? 'page' : 'false' }}">
                <span class="link-text">FAQ</span>
            </a>
            
            <a href="{{ route('feedback.index') }}" 
               class="nav-link white-link {{ request()->is('feedback') ? 'active' : '' }}"
               data-link="feedback"
               aria-current="{{ request()->is('feedback') ? 'page' : 'false' }}">
                <span class="link-text">Feedback</span>
            </a>
            
            <!-- Authentication -->
            @guest
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn-login" aria-label="Login to your account">
                        <svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn-register" aria-label="Create a new account">
                        <svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Register</span>
                    </a>
                </div>
            @else
                <!-- User Dropdown -->
                @php
                    $user = auth()->user();
                @endphp
                
                <div class="user-dropdown-container">
                    <button class="user-dropdown-btn" 
                            aria-label="User menu"
                            aria-expanded="false"
                            aria-haspopup="true">
                        <div class="user-avatar" aria-hidden="true">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <span class="user-name-truncated">{{ Str::limit($user->name, 12) }}</span>
                        @if($user->role === 'admin')
                            <span class="admin-badge" aria-label="Administrator">ADMIN</span>
                        @endif
                        <svg class="dropdown-arrow" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div class="user-dropdown-menu" role="menu" aria-label="User menu">
                        @php
                            $calculationsCount = 0;
                            $unreadNotificationsCount = 0;
                            $lastLogin = 'Just now';
                            
                            try {
                                if (method_exists($user, 'calculations')) {
                                    $calculationsCount = $user->calculations()->count() ?? 0;
                                }
                                
                                if (property_exists($user, 'last_login_at') && $user->last_login_at) {
                                    try {
                                        $lastLogin = \Carbon\Carbon::parse($user->last_login_at)->diffForHumans();
                                    } catch (\Exception $e) {
                                        $lastLogin = 'Recently';
                                    }
                                }
                            } catch (\Exception $e) {}
                        @endphp
                        
                        <div class="user-profile-summary">
                            <div class="user-avatar-large" aria-hidden="true">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="user-info">
                                <h4 class="user-fullname">{{ $user->name }}</h4>
                                <p class="user-email">{{ $user->email }}</p>
                                <p class="last-login">
                                    <small>Last login: {{ $lastLogin }}</small>
                                </p>
                                @if($user->role === 'admin')
                                    <span class="user-role-badge" aria-label="Administrator">
                                        <svg class="role-icon" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Administrator
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Navigation Section -->
                        <div class="dropdown-section">
                            <h5 class="section-title">Navigation</h5>
                            
                            <a href="{{ url('/') }}" 
                               class="dropdown-link {{ request()->is('/') ? 'active' : '' }}"
                               role="menuitem"
                               aria-current="{{ request()->is('/') ? 'page' : 'false' }}">
                                <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <div class="dropdown-link-content">
                                    <span class="link-title">Home</span>
                                    <span class="link-subtitle">Back to homepage</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('dashboard') }}" 
                               class="dropdown-link {{ request()->is('dashboard') ? 'active' : '' }}"
                               role="menuitem"
                               aria-current="{{ request()->is('dashboard') ? 'page' : 'false' }}">
                                <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <div class="dropdown-link-content">
                                    <span class="link-title">Dashboard</span>
                                    <span class="link-subtitle">Your statistics</span>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Calculator Tools Section -->
                        <div class="dropdown-section">
                            <h5 class="section-title">Calculator Tools</h5>
                            
                            <a href="{{ route('instant-estate.index') }}" 
                               class="dropdown-link {{ request()->is('instant-estate*') ? 'active' : '' }}"
                               role="menuitem"
                               aria-current="{{ request()->is('instant-estate*') ? 'page' : 'false' }}">
                                <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <div class="dropdown-link-content">
                                    <span class="link-title">Instant Estate</span>
                                    <span class="link-subtitle">Upload & Process Documents</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('calculator.index') }}" 
                               class="dropdown-link {{ request()->is('calculator*') && !request()->is('calculator/history') ? 'active' : '' }}"
                               role="menuitem"
                               aria-current="{{ request()->is('calculator*') && !request()->is('calculator/history') ? 'page' : 'false' }}">
                                <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div class="dropdown-link-content">
                                    <span class="link-title">Calculator</span>
                                    <span class="link-subtitle">New Faraid Calculation</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('calculator.history') }}" 
                               class="dropdown-link {{ request()->is('calculator/history') ? 'active' : '' }}"
                               role="menuitem"
                               aria-current="{{ request()->is('calculator/history') ? 'page' : 'false' }}">
                                <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="dropdown-link-content">
                                    <span class="link-title">History</span>
                                    <span class="link-subtitle">Past Calculations</span>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Account Section -->
                        <div class="dropdown-section">
                            <h5 class="section-title">Account</h5>
                            <a href="{{ route('profile.edit') }}" 
                               class="dropdown-link {{ request()->is('profile*') ? 'active' : '' }}" 
                               role="menuitem">
                                <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Profile Settings</span>
                            </a>
                        </div>
                        
                        @if($user->role === 'admin')
                            <div class="dropdown-section">
                                <h5 class="section-title">Administration</h5>
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="dropdown-link admin-link {{ request()->is('admin*') ? 'active' : '' }}" 
                                   role="menuitem">
                                    <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <span>Admin Dashboard</span>
                                </a>
                                <a href="{{ route('admin.instant-estate.index') }}" 
                                   class="dropdown-link admin-link {{ request()->is('admin/instant-estate*') ? 'active' : '' }}" 
                                   role="menuitem">
                                    <svg class="link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span>Instant Estate Admin</span>
                                </a>
                            </div>
                        @endif
                        
                        <div class="dropdown-footer">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn" role="menuitem">
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
        
        <!-- Mobile Navigation -->
        <div class="mobile-nav-container">
            <button class="hamburger-menu-btn" 
                    aria-label="Toggle mobile menu" 
                    aria-expanded="false"
                    aria-controls="mobile-menu-sidebar">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
            
            <div class="mobile-menu-overlay" 
                 role="presentation" 
                 aria-hidden="true"></div>
            
            <div class="mobile-menu-sidebar" 
                 id="mobile-menu-sidebar" 
                 role="dialog" 
                 aria-modal="true" 
                 aria-label="Mobile navigation menu">
                <div class="mobile-menu-header">
                    <a href="{{ url('/') }}" class="mobile-logo-link" aria-label="Neo Faraid Home">
                        <svg class="mobile-logo-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span class="mobile-logo-text">Neo Faraid</span>
                    </a>
                    <button class="mobile-close-btn" 
                            aria-label="Close menu"
                            aria-controls="mobile-menu-sidebar">
                        ×
                    </button>
                </div>
                
                <div class="mobile-menu-content">
                    <div class="mobile-nav-links" role="navigation" aria-label="Mobile navigation">
                        <a href="{{ url('/') }}" 
                           class="mobile-nav-link {{ request()->is('/') ? 'active' : '' }}"
                           aria-current="{{ request()->is('/') ? 'page' : 'false' }}">
                            <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Home</span>
                        </a>
                        
                        <!-- Mobile Instant Estate -->
                        <a href="{{ route('instant-estate.index') }}" 
                           class="mobile-nav-link {{ request()->is('instant-estate*') ? 'active' : '' }}"
                           aria-current="{{ request()->is('instant-estate*') ? 'page' : 'false' }}">
                            <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Instant Estate</span>
                        </a>
                        
                        @auth
                        <!-- Calculator only for authenticated users -->
                        <a href="{{ route('calculator.index') }}" 
                           class="mobile-nav-link {{ request()->is('calculator*') && !request()->is('calculator/history') ? 'active' : '' }}"
                           aria-current="{{ request()->is('calculator*') && !request()->is('calculator/history') ? 'page' : 'false' }}">
                            <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>Calculator</span>
                        </a>
                        @endauth
                        
                        <a href="{{ route('about') }}" 
                           class="mobile-nav-link {{ request()->is('about') ? 'active' : '' }}"
                           aria-current="{{ request()->is('about') ? 'page' : 'false' }}">
                            <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>About</span>
                        </a>
                        
                        <a href="{{ route('contact') }}" 
                           class="mobile-nav-link {{ request()->is('contact') ? 'active' : '' }}"
                           aria-current="{{ request()->is('contact') ? 'page' : 'false' }}">
                            <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Contact</span>
                        </a>
                        
                        <a href="{{ route('faq.index') }}" 
                           class="mobile-nav-link {{ request()->is('faq') ? 'active' : '' }}"
                           aria-current="{{ request()->is('faq') ? 'page' : 'false' }}">
                            <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>FAQ</span>
                        </a>
                        
                        <a href="{{ route('feedback.index') }}" 
                           class="mobile-nav-link {{ request()->is('feedback') ? 'active' : '' }}"
                           aria-current="{{ request()->is('feedback') ? 'page' : 'false' }}">
                            <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            <span>Feedback</span>
                        </a>
                    </div>
                    
                    @guest
                        <div class="mobile-auth-section" role="navigation" aria-label="Authentication">
                            <a href="{{ route('login') }}" class="mobile-btn-login" aria-label="Login to your account">
                                <svg class="mobile-btn-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span>Login</span>
                            </a>
                            <a href="{{ route('register') }}" class="mobile-btn-register" aria-label="Create a new account">
                                <svg class="mobile-btn-icon" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                <span>Register</span>
                            </a>
                        </div>
                    @else
                        @php
                            $user = auth()->user();
                        @endphp
                        
                        <div class="mobile-user-section" role="navigation" aria-label="User account">
                            <div class="mobile-user-profile">
                                <div class="mobile-user-avatar" aria-hidden="true">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="mobile-user-info">
                                    <span class="mobile-user-name">{{ $user->name }}</span>
                                    <span class="mobile-user-email">{{ $user->email }}</span>
                                </div>
                            </div>
                            
                            <div class="mobile-dashboard-section" role="navigation" aria-label="My dashboard">
                                <div class="mobile-section-header">
                                    <span class="mobile-section-title">My Tools</span>
                                </div>
                                
                                <a href="{{ route('dashboard') }}" 
                                   class="mobile-dashboard-link {{ request()->is('dashboard') ? 'active' : '' }}"
                                   aria-current="{{ request()->is('dashboard') ? 'page' : 'false' }}">
                                    <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                                
                                <a href="{{ route('instant-estate.index') }}" 
                                   class="mobile-dashboard-link {{ request()->is('instant-estate*') ? 'active' : '' }}"
                                   aria-current="{{ request()->is('instant-estate*') ? 'page' : 'false' }}">
                                    <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span>Instant Estate</span>
                                </a>
                                
                                <a href="{{ route('calculator.index') }}" 
                                   class="mobile-dashboard-link {{ request()->is('calculator*') && !request()->is('calculator/history') ? 'active' : '' }}"
                                   aria-current="{{ request()->is('calculator*') && !request()->is('calculator/history') ? 'page' : 'false' }}">
                                    <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Calculator</span>
                                </a>
                                
                                <a href="{{ route('calculator.history') }}" 
                                   class="mobile-dashboard-link {{ request()->is('calculator/history') ? 'active' : '' }}"
                                   aria-current="{{ request()->is('calculator/history') ? 'page' : 'false' }}">
                                    <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>History</span>
                                </a>
                            </div>
                            
                            <div class="mobile-user-links">
                                <a href="{{ route('profile.edit') }}" class="mobile-user-link {{ request()->is('profile*') ? 'active' : '' }}">
                                    <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Profile Settings</span>
                                </a>
                                
                                @if($user->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="mobile-dashboard-link admin-link {{ request()->is('admin*') ? 'active' : '' }}">
                                        <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <span>Admin Panel</span>
                                        <span class="admin-badge-mobile" aria-label="Administrator">ADMIN</span>
                                    </a>
                                    
                                    <a href="{{ route('admin.instant-estate.index') }}" 
                                       class="mobile-dashboard-link admin-link {{ request()->is('admin/instant-estate*') ? 'active' : '' }}">
                                        <svg class="mobile-link-icon" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span>Instant Estate Admin</span>
                                    </a>
                                @endif
                                
                                <form method="POST" action="{{ route('logout') }}" class="mobile-logout-form">
                                    @csrf
                                    <button type="submit" class="mobile-logout-btn">
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
:root {
    --primary-blue: #1a5fb4;
    --secondary-blue: #2d7ad6;
    --accent-gold: #ffd700;
    --success-green: #10b981;
    --warning-orange: #f59e0b;
    --danger-red: #ef4444;
    --danger-dark: #dc2626;
    --text-white: rgba(255,255,255,0.95);
    --text-light: rgba(255,255,255,0.9);
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --bg-white: #ffffff;
    --bg-light: #f8fafc;
    --bg-lighter: #f1f5f9;
    --bg-dark: #0f172a;
    --bg-overlay: rgba(0, 0, 0, 0.5);
    --border-light: rgba(255,255,255,0.1);
    --border-medium: rgba(255,255,255,0.2);
    --border-dark: rgba(226, 232, 240, 0.5);
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow: 0 8px 24px rgba(0,0,0,0.12);
    --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.18);
    --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.25);
    --transition-fast: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius-sm: 8px;
    --radius: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --spacing-xs: 0.5rem;
    --spacing-sm: 0.75rem;
    --spacing: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --font-family: -apple-system, BlinkMacSystemFont, sans-serif;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-family);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.main-nav {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: var(--shadow);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border-light);
    transition: var(--transition);
}

.nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 var(--spacing);
    height: 64px;
    max-width: 100%;
    margin: 0 auto;
}

.logo-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    text-decoration: none;
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
    letter-spacing: -0.02em;
    transition: var(--transition-fast);
    padding: var(--spacing-xs) 0;
}

.logo-link:hover {
    transform: translateY(-1px);
    opacity: 0.9;
}

.logo-icon {
    width: 28px;
    height: 28px;
    color: var(--accent-gold);
    fill: currentColor;
    filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3));
    animation: logoFloat 4s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-2px); }
}

.logo-text {
    background: linear-gradient(135deg, #ffffff 0%, var(--accent-gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
}

/* Navigation Badges */
.nav-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    margin-left: 0.5rem;
    background: var(--accent-gold);
    color: #333;
    letter-spacing: 0.02em;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    animation: badgePulse 2s infinite;
}

.new-badge {
    background: linear-gradient(135deg, var(--accent-gold) 0%, #ffed4e 100%);
    box-shadow: 0 2px 8px rgba(255,215,0,0.3);
}

.new-badge-small {
    background: linear-gradient(135deg, var(--accent-gold) 0%, #ffed4e 100%);
    font-size: 0.6rem;
    padding: 0.15rem 0.4rem;
    border-radius: 10px;
    color: #333;
    font-weight: 700;
    margin-left: auto;
}

.mobile-new-badge {
    background: linear-gradient(135deg, var(--accent-gold) 0%, #ffed4e 100%);
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    color: #333;
    margin-left: auto;
    letter-spacing: 0.02em;
    animation: badgePulse 2s infinite;
}

@keyframes badgePulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.9; transform: scale(1.05); }
}

.nav-icon {
    width: 18px;
    height: 18px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
}

.mobile-nav-container {
    display: block;
}

.hamburger-menu-btn {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.15);
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-sm);
    padding: 10px;
    cursor: pointer;
    transition: var(--transition-fast);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 1002;
}

.hamburger-menu-btn:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.05);
}

.hamburger-menu-btn:active {
    transform: scale(0.95);
}

.hamburger-line {
    display: block;
    width: 100%;
    height: 2px;
    background: white;
    border-radius: 1px;
    transition: var(--transition);
}

.hamburger-menu-btn.active .hamburger-line:nth-child(1) {
    transform: translateY(6px) rotate(45deg);
}

.hamburger-menu-btn.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.hamburger-menu-btn.active .hamburger-line:nth-child(3) {
    transform: translateY(-6px) rotate(-45deg);
}

.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--bg-overlay);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 1001;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.mobile-menu-overlay.active {
    opacity: 1;
    visibility: visible;
}

.mobile-menu-sidebar {
    position: fixed;
    top: 0;
    right: -100%;
    width: 85%;
    max-width: 400px;
    height: 100vh;
    background: var(--bg-white);
    z-index: 1002;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    box-shadow: var(--shadow-xl);
}

.mobile-menu-sidebar.active {
    right: 0;
    animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-lg);
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    border-bottom: 1px solid var(--border-light);
}

.mobile-logo-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    text-decoration: none;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.mobile-logo-icon {
    width: 24px;
    height: 24px;
    color: var(--accent-gold);
    fill: currentColor;
}

.mobile-close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: var(--transition-fast);
}

.mobile-close-btn:hover {
    background: rgba(255,255,255,0.2);
    transform: rotate(90deg);
}

.mobile-menu-content {
    flex: 1;
    padding: var(--spacing);
    overflow-y: auto;
}

.mobile-nav-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: var(--spacing);
}

.mobile-nav-link {
    display: flex;
    align-items: center;
    gap: var(--spacing);
    padding: 1rem;
    text-decoration: none;
    color: var(--text-dark);
    font-weight: 500;
    border-radius: var(--radius);
    transition: var(--transition-fast);
    background: var(--bg-light);
    border: 1px solid transparent;
}

.mobile-nav-link:hover {
    background: var(--bg-lighter);
    transform: translateX(4px);
    border-color: rgba(37, 99, 235, 0.1);
    color: var(--primary-blue);
}

.mobile-nav-link.active {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(26, 95, 180, 0.2);
}

.mobile-nav-link.active .mobile-link-icon {
    color: white;
}

.mobile-link-icon {
    width: 20px;
    height: 20px;
    color: var(--primary-blue);
    stroke: currentColor;
    fill: none;
    flex-shrink: 0;
}

.mobile-dashboard-section {
    background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-lighter) 100%);
    border-radius: var(--radius);
    padding: var(--spacing);
    margin: var(--spacing) 0;
    border: 1px solid var(--border-dark);
    box-shadow: var(--shadow-sm);
}

.mobile-section-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing);
}

.mobile-section-icon {
    width: 20px;
    height: 20px;
    color: var(--primary-blue);
    stroke: currentColor;
    fill: none;
}

.mobile-section-title {
    font-weight: 600;
    color: var(--primary-blue);
    font-size: 1rem;
}

.mobile-dashboard-link {
    display: flex;
    align-items: center;
    gap: var(--spacing);
    padding: 0.875rem;
    background: var(--bg-white);
    border-radius: var(--radius);
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 0.5rem;
    transition: var(--transition-fast);
    border: 1px solid transparent;
    border-left: 3px solid var(--primary-blue);
}

.mobile-dashboard-link:hover {
    background: var(--bg-lighter);
    transform: translateX(4px);
    border-color: rgba(37, 99, 235, 0.1);
}

.mobile-dashboard-link.active {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    border-left-color: var(--accent-gold);
    box-shadow: 0 4px 12px rgba(26, 95, 180, 0.2);
}

.mobile-dashboard-link.active .mobile-link-icon {
    color: white;
}

.admin-badge-mobile {
    background: var(--accent-gold);
    color: #333;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: 0 2px 8px rgba(255,215,0,0.2);
    margin-left: auto;
}

.mobile-auth-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-top: var(--spacing);
    padding-top: var(--spacing);
    border-top: 1px solid var(--border-dark);
}

.mobile-btn-login,
.mobile-btn-register {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem;
    text-decoration: none;
    border-radius: var(--radius);
    font-weight: 600;
    transition: var(--transition-fast);
    border: 2px solid transparent;
    font-size: 0.9rem;
}

.mobile-btn-login {
    color: var(--primary-blue);
    border-color: var(--primary-blue);
    background: var(--bg-white);
}

.mobile-btn-login:hover {
    background: var(--primary-blue);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(26, 95, 180, 0.2);
}

.mobile-btn-register {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(26, 95, 180, 0.15);
}

.mobile-btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(26, 95, 180, 0.25);
    filter: brightness(1.1);
}

.mobile-btn-icon {
    width: 18px;
    height: 18px;
    stroke: currentColor;
    fill: none;
}

.mobile-user-section {
    background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-lighter) 100%);
    border-radius: var(--radius);
    padding: var(--spacing);
    margin-top: var(--spacing);
    border: 1px solid var(--border-dark);
}

.mobile-user-profile {
    display: flex;
    align-items: center;
    gap: var(--spacing);
    margin-bottom: var(--spacing);
    padding-bottom: var(--spacing);
    border-bottom: 1px solid var(--border-dark);
}

.mobile-user-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(26, 95, 180, 0.2);
}

.mobile-user-info {
    flex: 1;
}

.mobile-user-name {
    display: block;
    font-weight: 600;
    color: var(--primary-blue);
    font-size: 0.95rem;
    margin-bottom: 0.125rem;
}

.mobile-user-email {
    display: block;
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 400;
}

.mobile-user-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.mobile-user-link {
    display: flex;
    align-items: center;
    gap: var(--spacing);
    padding: 0.875rem;
    background: var(--bg-white);
    border-radius: var(--radius);
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-fast);
    border: 1px solid transparent;
}

.mobile-user-link:hover {
    background: var(--bg-lighter);
    transform: translateX(4px);
    border-color: rgba(37, 99, 235, 0.1);
}

.mobile-logout-form {
    margin-top: 0.5rem;
}

.mobile-logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 0.875rem;
    background: linear-gradient(135deg, var(--danger-red) 0%, var(--danger-dark) 100%);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-fast);
    font-size: 0.9rem;
}

.mobile-logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.25);
    filter: brightness(1.1);
}

.desktop-nav-links {
    display: none;
}

@media (min-width: 768px) {
    .nav-container {
        padding: 0 2rem;
        max-width: 1400px;
    }
    
    .logo-link {
        font-size: 1.5rem;
    }
    
    .logo-icon {
        width: 32px;
        height: 32px;
    }
    
    .desktop-nav-links {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        height: 100%;
    }
    
    .mobile-nav-container {
        display: none;
    }
    
    .nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        border-radius: var(--radius);
        transition: var(--transition-fast);
        height: 44px;
        background: transparent;
        border: 1px solid transparent;
    }
    
    .nav-link.white-link {
        color: var(--text-white) !important;
    }
    
    .nav-link.white-link:hover {
        color: white !important;
        background: rgba(255,255,255,0.15);
        border-color: var(--border-medium);
        transform: translateY(-1px);
    }
    
    .nav-link.white-link.active {
        color: var(--accent-gold) !important;
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,215,0,0.3);
        box-shadow: 0 4px 12px rgba(255,215,0,0.1);
    }
    
    .user-dropdown-container {
        position: relative;
        margin-left: 0.5rem;
    }
    
    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: var(--radius);
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
        height: 44px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .admin-badge {
        background: var(--accent-gold);
        color: #333;
        font-size: 0.6rem;
        font-weight: 700;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        text-transform: uppercase;
    }
    
    .user-dropdown-btn:hover {
        background: rgba(255,255,255,0.25);
        border-color: rgba(255,255,255,0.4);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255,255,255,0.1);
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--accent-gold) 0%, #ffed4e 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(255,215,0,0.2);
    }
    
    .user-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        min-width: 320px;
        max-width: 400px;
        display: none;
        overflow: hidden;
        z-index: 1001;
        animation: dropdownSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-15px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .user-profile-summary {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar-large {
        width: 56px;
        height: 56px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--primary-blue);
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        animation: avatarPulse 3s ease-in-out infinite;
    }

    @keyframes avatarPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .user-info {
        flex: 1;
    }

    .user-fullname {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        letter-spacing: -0.01em;
    }

    .user-email {
        font-size: 0.85rem;
        opacity: 0.9;
        margin: 0 0 0.75rem 0;
        font-weight: 400;
    }

    .last-login {
        font-size: 0.8rem;
        opacity: 0.8;
        margin: 0.5rem 0 0.75rem 0;
    }

    .user-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.2);
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .role-icon {
        width: 12px;
        height: 12px;
        stroke: currentColor;
        fill: none;
    }

    .dropdown-section {
        padding: 1rem 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .dropdown-section:last-of-type {
        border-bottom: none;
    }

    .section-title {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0 1.5rem 0.5rem 1.5rem;
        margin: 0;
    }

    .dropdown-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.875rem 1.5rem;
        color: #334155;
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition-fast);
        position: relative;
        overflow: hidden;
    }

    .dropdown-link-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .dropdown-link:hover {
        background: #f8fafc;
        color: var(--primary-blue);
        transform: translateX(4px);
    }

    .dropdown-link:hover::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--primary-blue);
    }

    .link-icon {
        width: 18px;
        height: 18px;
        color: var(--primary-blue);
        stroke: currentColor;
        fill: none;
        flex-shrink: 0;
    }

    .link-title {
        font-weight: 600;
        font-size: 0.9rem;
        display: block;
        letter-spacing: -0.01em;
    }

    .link-subtitle {
        font-size: 0.75rem;
        color: #64748b;
        display: block;
        margin-top: 0.125rem;
        font-weight: 400;
    }

    .dropdown-link.active {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        color: white;
    }

    .dropdown-link.active .link-icon {
        color: white;
    }

    .dropdown-link.active .link-subtitle {
        color: rgba(255,255,255,0.9);
    }

    .dropdown-link.admin-link {
        border-left: 3px solid var(--accent-gold);
    }

    .dropdown-footer {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-lighter) 100%);
        border-top: 1px solid rgba(226, 232, 240, 0.5);
    }

    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--danger-red) 0%, var(--danger-dark) 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: var(--radius);
        cursor: pointer;
        transition: var(--transition-fast);
        font-size: 0.95rem;
    }

    .logout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.25);
        filter: brightness(1.1);
    }
    
    .auth-buttons {
        display: flex;
        gap: 0.75rem;
        margin-left: 0.5rem;
    }
    
    .btn-login,
    .btn-register {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius);
        font-weight: 600;
        transition: var(--transition-fast);
        height: 44px;
        font-size: 0.95rem;
    }
    
    .btn-login {
        color: white;
        border: 1px solid var(--border-medium);
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .btn-login:hover {
        border-color: white;
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,255,255,0.1);
    }
    
    .btn-register {
        background: linear-gradient(135deg, var(--accent-gold) 0%, #ffed4e 100%);
        color: #333;
        box-shadow: 0 4px 12px rgba(255,215,0,0.2);
    }
    
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255,215,0,0.3);
        filter: brightness(1.1);
    }
    
    .btn-icon {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }
    
    .dropdown-arrow {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        transition: var(--transition);
    }
}

@media (min-width: 1024px) {
    .nav-container {
        padding: 0 2.5rem;
    }
    
    .desktop-nav-links {
        gap: 0.75rem;
    }
    
    .nav-link {
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
    }
    
    .user-dropdown-menu {
        min-width: 340px;
    }
}

@media (min-width: 1280px) {
    .nav-container {
        padding: 0 3rem;
    }
    
    .desktop-nav-links {
        gap: 1rem;
    }
    
    .nav-link {
        font-size: 1rem;
        padding: 0.875rem 1.5rem;
    }
    
    .user-dropdown-menu {
        min-width: 360px;
    }
}

@media (min-width: 1536px) {
    .nav-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 4rem;
    }
}

.nav-link:focus,
.user-dropdown-btn:focus,
.mobile-nav-link:focus,
.mobile-dashboard-link:focus,
.mobile-user-link:focus,
.btn-login:focus,
.btn-register:focus,
.logout-btn:focus,
.mobile-logout-btn:focus,
.mobile-btn-login:focus,
.mobile-btn-register:focus {
    outline: 2px solid var(--accent-gold);
    outline-offset: 2px;
}

@media (prefers-contrast: high) {
    .main-nav {
        background: var(--primary-blue);
    }
    
    .nav-link,
    .mobile-nav-link {
        border: 2px solid currentColor;
    }
    
    .mobile-menu-sidebar {
        border: 2px solid #000;
    }
}

@media (prefers-reduced-motion: reduce) {
    .logo-icon,
    .user-avatar-large,
    .nav-badge,
    .mobile-new-badge {
        animation: none;
    }
    
    .nav-link,
    .mobile-nav-link,
    .mobile-dashboard-link,
    .mobile-user-link,
    .dropdown-link {
        transition: none;
    }
    
    .user-dropdown-menu,
    .mobile-menu-sidebar,
    .mobile-menu-overlay {
        transition: none;
    }
}
</style>

<script>
class ResponsiveNavigationManager {
    constructor() {
        this.selectors = {
            hamburgerBtn: '.hamburger-menu-btn',
            mobileMenuSidebar: '.mobile-menu-sidebar',
            mobileMenuOverlay: '.mobile-menu-overlay',
            mobileCloseBtn: '.mobile-close-btn',
            userDropdownBtn: '.user-dropdown-btn',
            userDropdown: '.user-dropdown-menu',
            navLinks: '[data-link]',
            mobileNavLinks: '.mobile-nav-link, .mobile-dashboard-link, .mobile-user-link'
        };
        
        this.state = {
            isMobileMenuOpen: false,
            isUserDropdownOpen: false,
            lastScrollY: 0,
            isTouchDevice: 'ontouchstart' in window || navigator.maxTouchPoints > 0
        };
        
        this.elements = {};
        this.init();
    }
    
    init() {
        this.cacheElements();
        
        if (!this.elements.hamburgerBtn && !this.elements.userDropdownBtn) {
            console.warn('Navigation elements not found.');
            return;
        }
        
        this.bindEvents();
        this.handleResize();
        this.setActiveLinks();
        this.setupScrollBehavior();
        this.setupAccessibility();
        this.setupTouchGestures();
        this.updateAriaStates();
        this.preventFOUC();
    }
    
    cacheElements() {
        Object.keys(this.selectors).forEach(key => {
            this.elements[key] = document.querySelector(this.selectors[key]);
        });
        
        this.elements.navLinks = document.querySelectorAll(this.selectors.navLinks);
        this.elements.mobileNavLinks = document.querySelectorAll(this.selectors.mobileNavLinks);
        this.elements.mainNav = document.querySelector('.main-nav');
    }
    
    preventFOUC() {
        if (this.elements.mobileMenuSidebar) {
            this.elements.mobileMenuSidebar.style.visibility = 'hidden';
            setTimeout(() => {
                if (this.elements.mobileMenuSidebar) {
                    this.elements.mobileMenuSidebar.style.visibility = 'visible';
                }
            }, 100);
        }
    }
    
    bindEvents() {
        this.bindEvent(this.elements.hamburgerBtn, 'click', (e) => {
            e.stopPropagation();
            this.toggleMobileMenu();
        });
        
        this.bindEvent(this.elements.hamburgerBtn, 'keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.toggleMobileMenu();
            }
        });
        
        this.bindEvent(this.elements.mobileCloseBtn, 'click', () => this.closeMobileMenu());
        this.bindEvent(this.elements.mobileMenuOverlay, 'click', () => this.closeMobileMenu());
        
        this.bindEvent(this.elements.userDropdownBtn, 'click', (e) => {
            e.stopPropagation();
            this.toggleUserDropdown();
        });
        
        this.bindEvent(this.elements.userDropdownBtn, 'keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.toggleUserDropdown();
            }
            if (e.key === 'ArrowDown' && !this.state.isUserDropdownOpen) {
                e.preventDefault();
                this.openUserDropdown();
            }
        });
        
        this.bindEvent(document, 'click', (e) => this.handleOutsideClick(e));
        this.bindEvent(document, 'keydown', (e) => this.handleKeydown(e));
        
        this.bindEvent(window, 'resize', () => this.debounce(this.handleResize, 150));
        this.bindEvent(window, 'orientationchange', () => this.handleResize());
        
        this.elements.navLinks.forEach(link => {
            this.bindEvent(link, 'click', () => this.closeAllMenus());
        });
        
        this.elements.mobileNavLinks.forEach(link => {
            this.bindEvent(link, 'click', () => this.closeAllMenus());
        });
    }
    
    bindEvent(element, event, handler) {
        if (element) {
            element.addEventListener(event, handler);
        }
    }
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    setupAccessibility() {
        if (this.elements.mainNav) {
            this.elements.mainNav.setAttribute('role', 'navigation');
            this.elements.mainNav.setAttribute('aria-label', 'Main navigation');
        }
        
        const interactiveElements = document.querySelectorAll('button, [role="button"], a[href]');
        interactiveElements.forEach(el => {
            if (el.getAttribute('role') !== 'button' && el.tagName !== 'BUTTON') {
                el.setAttribute('role', el.tagName === 'A' ? 'link' : 'button');
            }
        });
    }
    
    updateAriaStates() {
        if (this.elements.hamburgerBtn) {
            this.elements.hamburgerBtn.setAttribute('aria-expanded', this.state.isMobileMenuOpen);
        }
        if (this.elements.userDropdownBtn) {
            this.elements.userDropdownBtn.setAttribute('aria-expanded', this.state.isUserDropdownOpen);
        }
        
        const userArrow = this.elements.userDropdownBtn?.querySelector('.dropdown-arrow');
        
        if (userArrow) {
            userArrow.style.transform = this.state.isUserDropdownOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }
    
    setupTouchGestures() {
        if (!this.state.isTouchDevice || !this.elements.mobileMenuSidebar) return;
        
        let touchStartX = 0;
        let touchEndX = 0;
        
        this.bindEvent(this.elements.mobileMenuSidebar, 'touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        this.bindEvent(this.elements.mobileMenuSidebar, 'touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            
            if (this.state.isMobileMenuOpen && diff > 50) {
                this.closeMobileMenu();
            }
        });
    }
    
    setupScrollBehavior() {
        if (!this.elements.mainNav) return;
        
        let ticking = false;
        
        this.bindEvent(window, 'scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const currentScrollY = window.scrollY;
                    
                    if (currentScrollY > this.state.lastScrollY && currentScrollY > 100) {
                        this.elements.mainNav.style.transform = 'translateY(-100%)';
                    } else {
                        this.elements.mainNav.style.transform = 'translateY(0)';
                    }
                    
                    this.state.lastScrollY = currentScrollY;
                    ticking = false;
                });
                ticking = true;
            }
        });
    }
    
    handleOutsideClick(e) {
        if (this.state.isUserDropdownOpen && 
            this.elements.userDropdown && 
            !this.elements.userDropdown.contains(e.target) && 
            !this.elements.userDropdownBtn.contains(e.target)) {
            this.closeUserDropdown();
        }
        
        if (this.state.isMobileMenuOpen && 
            !this.elements.mobileMenuSidebar.contains(e.target) && 
            !this.elements.hamburgerBtn.contains(e.target)) {
            this.closeMobileMenu();
        }
    }
    
    handleKeydown(e) {
        switch (e.key) {
            case 'Escape':
                this.handleEscapeKey();
                break;
            case 'Tab':
                this.handleTabNavigation(e);
                break;
        }
    }
    
    handleEscapeKey() {
        if (this.state.isMobileMenuOpen) {
            this.closeMobileMenu();
        } else if (this.state.isUserDropdownOpen) {
            this.closeUserDropdown();
        }
    }
    
    handleTabNavigation(e) {
        if (this.state.isMobileMenuOpen && this.elements.mobileMenuSidebar) {
            this.trapFocus(this.elements.mobileMenuSidebar, e);
        }
        
        if (this.state.isUserDropdownOpen && this.elements.userDropdown) {
            this.trapFocus(this.elements.userDropdown, e, this.elements.userDropdownBtn);
        }
    }
    
    trapFocus(element, e, triggerElement = null) {
        const focusableElements = element.querySelectorAll(
            'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey && document.activeElement === firstElement) {
            e.preventDefault();
            if (triggerElement) {
                triggerElement.focus();
            } else {
                lastElement.focus();
            }
        } else if (!e.shiftKey && document.activeElement === lastElement) {
            e.preventDefault();
            if (triggerElement) {
                triggerElement.focus();
            } else {
                firstElement.focus();
            }
        } else if (!e.shiftKey && triggerElement && document.activeElement === triggerElement) {
            e.preventDefault();
            firstElement.focus();
        }
    }
    
    toggleMobileMenu() {
        if (this.state.isMobileMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }
    
    openMobileMenu() {
        this.state.isMobileMenuOpen = true;
        
        this.elements.mobileMenuSidebar?.classList.add('active');
        this.elements.mobileMenuOverlay?.classList.add('active');
        this.elements.hamburgerBtn?.classList.add('active');
        
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
        
        setTimeout(() => {
            const firstFocusable = this.elements.mobileMenuSidebar?.querySelector(
                'a, button, [tabindex]:not([tabindex="-1"])'
            );
            if (firstFocusable) firstFocusable.focus();
        }, 100);
        
        this.closeUserDropdown();
        
        this.updateAriaStates();
    }
    
    closeMobileMenu() {
        this.state.isMobileMenuOpen = false;
        
        this.elements.mobileMenuSidebar?.classList.remove('active');
        this.elements.mobileMenuOverlay?.classList.remove('active');
        this.elements.hamburgerBtn?.classList.remove('active');
        
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        
        this.elements.hamburgerBtn?.focus();
        
        this.updateAriaStates();
    }
    
    toggleUserDropdown() {
        if (this.state.isUserDropdownOpen) {
            this.closeUserDropdown();
        } else {
            this.openUserDropdown();
        }
    }
    
    openUserDropdown() {
        this.state.isUserDropdownOpen = true;
        this.elements.userDropdown.style.display = 'block';
        
        setTimeout(() => {
            const firstLink = this.elements.userDropdown.querySelector('a');
            if (firstLink) firstLink.focus();
        }, 100);
        
        this.closeMobileMenu();
        
        this.updateAriaStates();
    }
    
    closeUserDropdown() {
        this.state.isUserDropdownOpen = false;
        this.elements.userDropdown.style.display = 'none';
        
        this.elements.userDropdownBtn?.focus();
        
        this.updateAriaStates();
    }
    
    closeAllMenus() {
        if (this.state.isMobileMenuOpen) this.closeMobileMenu();
        if (this.state.isUserDropdownOpen) this.closeUserDropdown();
    }
    
    handleResize() {
        const isMobile = window.innerWidth < 768;
        
        if (!isMobile) {
            if (this.state.isMobileMenuOpen) {
                this.closeMobileMenu();
            }
        } else {
            if (this.state.isUserDropdownOpen) {
                this.closeUserDropdown();
            }
        }
    }
    
    setActiveLinks() {
        const currentPath = window.location.pathname;
        
        this.elements.navLinks.forEach(link => {
            const linkTarget = link.getAttribute('data-link');
            if (linkTarget && (currentPath.includes(linkTarget) || 
                (linkTarget === 'home' && (currentPath === '/' || currentPath === '')) ||
                (linkTarget === 'instant-estate' && currentPath.includes('instant-estate')))) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
        
        this.elements.mobileNavLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (currentPath === href || 
                (href === '/' && currentPath === '/') ||
                (href !== '/' && currentPath.includes(href)))) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    try {
        window.navManager = new ResponsiveNavigationManager();
        
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.3s ease';
        
        setTimeout(() => {
            document.body.style.opacity = '1';
        }, 50);
        
    } catch (error) {
        console.error('Error initializing navigation manager:', error);
    }
});

if (typeof window !== 'undefined') {
    window.NavigationManager = ResponsiveNavigationManager;
}
</script>