<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Pending Reviews • Neo Faraid Admin</title>
    <meta name="description" content="Review pending instant estate documents and approve or reject them">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #1a5fb4;
            --primary-dark: #0d2d5c;
            --secondary-color: #2d7ad6;
            --accent-color: #ffd700;
            --accent-light: #ffed4e;
            --danger-color: #dc3545;
            --danger-dark: #c82333;
            --success-color: #25D366;
            --success-dark: #128C7E;
            --warning-color: #ffc107;
            --info-color: #138496;
            --light-bg: #f8f9fa;
            --light-border: #e9ecef;
            --text-primary: #495057;
            --text-light: #6c757d;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.12);
            --shadow-accent: 0 10px 25px rgba(255, 215, 0, 0.3);
            --border-radius-sm: 12px;
            --border-radius-md: 15px;
            --border-radius-lg: 20px;
            --border-radius-xl: 50px;
            --transition: all 0.3s ease;
            --font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        * {
            font-family: var(--font-family);
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--light-bg);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ========== TOP NAVIGATION ========== */
        .admin-top-nav {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: var(--white);
            padding: 0 2rem;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: var(--transition);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--white);
            font-weight: 700;
            font-size: 1.4rem;
            transition: var(--transition);
        }
        .admin-logo:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .admin-logo i {
            color: var(--accent-color);
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3));
            animation: logoFloat 4s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-2px); }
        }

        .admin-logo-text {
            background: linear-gradient(135deg, #ffffff 0%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .nav-right {
            display: flex;
            align-items: center;
        }

        .user-profile-container {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 100px;
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            text-decoration: none;
        }
        .user-profile-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.15);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
            box-shadow: var(--shadow-accent);
        }

        .avatar-initials {
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary-dark);
        }

        .admin-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 16px;
            height: 16px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--primary-color);
        }

        .user-profile-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .user-name {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-role {
            display: block;
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .chevron-icon {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        /* Dropdown Menu */
        .user-profile-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            min-width: 320px;
            max-width: 400px;
            z-index: 1001;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            border: 1px solid var(--light-border);
        }
        .user-profile-dropdown.active {
            opacity: 1;
            transform: translateY(0);
        }

        .profile-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .profile-avatar-large {
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .avatar-initials-large {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .admin-badge-large {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 28px;
            height: 28px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
        }

        .admin-badge-large svg {
            width: 14px;
            height: 14px;
        }

        .profile-info {
            text-align: left;
        }

        .profile-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.25rem 0;
            letter-spacing: -0.01em;
        }

        .profile-email {
            font-size: 0.85rem;
            opacity: 0.9;
            margin: 0 0 0.75rem 0;
            font-weight: 400;
        }

        .profile-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }

        .last-login {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.9;
        }

        .last-login svg {
            width: 14px;
            height: 14px;
        }

        .user-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .user-status.active {
            color: var(--accent-color);
        }

        .user-status.active::before {
            content: '●';
            margin-right: 0.25rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .dropdown-section {
            padding: 1rem 0;
            border-bottom: 1px solid var(--light-border);
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

        .dropdown-nav {
            padding: 0 1.5rem;
        }

        .dropdown-nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 0;
            color: var(--text-primary);
            text-decoration: none;
            transition: var(--transition);
            border-bottom: 1px solid var(--light-border);
            position: relative;
        }

        .dropdown-nav-item:last-child {
            border-bottom: none;
        }

        .dropdown-nav-item:hover {
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .dropdown-nav-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .dropdown-nav-item.active::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
        }

        .dropdown-nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--light-bg);
            color: var(--text-light);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            min-width: 24px;
            text-align: center;
        }

        .account-links {
            padding: 0 1.5rem;
        }

        .account-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 0;
            color: var(--text-primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .account-link:hover {
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .account-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .account-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .dropdown-footer {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
            border-top: 1px solid var(--light-border);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            border: none;
            color: var(--white);
            font-weight: 600;
            border-radius: var(--border-radius-md);
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.25);
        }

        /* ========== MAIN CONTENT ========== */
        .admin-main {
            padding: 2.5rem;
            background: var(--light-bg);
            overflow-y: auto;
            max-width: 1400px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--primary-color);
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--light-border);
        }

        .page-title h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-light);
            font-weight: 400;
            font-size: 0.95rem;
        }

        /* Buttons */
        .btn {
            padding: 0.6rem 1.25rem;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            border: none;
            font-size: 0.85rem;
            box-shadow: var(--shadow-sm);
            font-family: inherit;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--light-border);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            background: var(--light-bg);
            border-color: var(--text-light);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: var(--white);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: var(--white);
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-warning { background: #fff3cd; color: #856404; }

        /* Pending review cards */
        .review-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: var(--transition);
            display: flex;
            flex-direction: row;
        }

        .review-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .review-card-img {
            width: 250px;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .review-card-img img {
            max-width: 100%;
            max-height: 220px;
            border-radius: var(--border-radius-sm);
            box-shadow: var(--shadow-sm);
        }

        .review-card-body {
            flex: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .review-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .review-session-id {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .review-score {
            font-weight: 700;
            font-size: 1rem;
        }

        .review-breakdown {
            background: var(--light-bg);
            border-radius: var(--border-radius-sm);
            padding: 0.75rem;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            max-height: 120px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .review-extracted {
            margin-bottom: 1rem;
        }

        .review-extracted h6 {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .review-extracted ul {
            list-style: none;
            padding: 0;
        }

        .review-extracted li {
            font-size: 0.9rem;
            padding: 0.25rem 0;
            border-bottom: 1px solid var(--light-border);
        }

        .review-extracted li:last-child {
            border-bottom: none;
        }

        .review-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .review-actions form {
            display: inline-flex;
            gap: 0.5rem;
        }

        .review-actions input[type="text"] {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--light-border);
            border-radius: var(--border-radius-sm);
            font-family: inherit;
            font-size: 0.85rem;
            width: 180px;
        }

        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .alert-message {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid var(--info-color);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-top-nav { padding: 0 1rem; }
            .admin-logo-text { font-size: 1.2rem; }
            .user-profile-btn { min-width: auto; padding: 0.5rem; }
            .user-profile-info { display: none; }
        }

        @media (max-width: 768px) {
            .review-card { flex-direction: column; }
            .review-card-img { width: 100%; }
            .admin-top-nav { height: 60px; }
            .admin-logo-text { display: none; }
            .admin-main { padding: 1.5rem; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        }

        @media (max-width: 576px) {
            .admin-main { padding: 1rem; }
            .btn { padding: 0.5rem 1rem; font-size: 0.8rem; }
            .review-actions input[type="text"] { width: 130px; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--light-bg); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--text-light); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-color); }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="admin-top-nav" role="navigation" aria-label="Admin navigation">
        <div class="nav-left">
            <a href="{{ route('admin.dashboard') }}" class="admin-logo" aria-label="Neo Faraid Admin Home">
                <i class="fas fa-layer-group"></i>
                <span class="admin-logo-text">Neo Faraid Admin</span>
            </a>
        </div>
        <div class="nav-right">
            @php
                $user = auth()->user();
                $lastLogin = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently';
                $fullInitials = strtoupper(implode('', array_map(fn($n) => $n[0], explode(' ', $user->name, 2))));
            @endphp
            <div class="user-profile-container" id="user-profile-container">
                <button class="user-profile-btn" id="user-profile-btn" aria-label="User profile menu" aria-expanded="false" aria-haspopup="true">
                    <div class="user-avatar" aria-hidden="true">
                        <span class="avatar-initials">{{ $fullInitials }}</span>
                        @if($user->role === 'admin')
                            <span class="admin-badge" aria-label="Administrator">A</span>
                        @endif
                    </div>
                    <div class="user-profile-info">
                        <span class="user-name">{{ $user->name }}</span>
                        <span class="user-role">{{ ucfirst($user->role) }}</span>
                    </div>
                    <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="user-profile-dropdown" id="user-profile-dropdown" role="menu" aria-label="User profile menu" aria-hidden="true">
                    <div class="profile-header">
                        <div class="profile-avatar-large" aria-hidden="true">
                            <span class="avatar-initials-large">{{ $fullInitials }}</span>
                            @if($user->role === 'admin')
                                <div class="admin-badge-large" aria-label="Administrator">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name">{{ $user->name }}</h3>
                            <p class="profile-email">{{ $user->email }}</p>
                            <div class="profile-meta">
                                <span class="last-login">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Last login: {{ $lastLogin }}
                                </span>
                                <span class="user-status active">● Online</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-section">
                        <h4 class="section-title">Navigation</h4>
                        <nav class="dropdown-nav" role="navigation" aria-label="Admin navigation">
                            <a href="{{ route('admin.users.index') }}" class="dropdown-nav-item">Manage Users</a>
                            <a href="{{ route('admin.feedback.index') }}" class="dropdown-nav-item">Manage Feedback</a>
                            <a href="{{ route('admin.faq.index') }}" class="dropdown-nav-item">Manage FAQ</a>
                            <a href="{{ route('admin.calculations.index') }}" class="dropdown-nav-item">Manage Calculations</a>
                            <a href="{{ route('admin.instant-estate.index') }}" class="dropdown-nav-item active">Manage Instant Estate</a>
                            <a href="{{ route('admin.estate-setup.index') }}" class="dropdown-nav-item">Manage Estate Planning</a>
                        </nav>
                    </div>
                    <div class="dropdown-section">
                        <h4 class="section-title">Account</h4>
                        <div class="account-links">
                            <a href="{{ route('profile.edit') }}" class="account-link">Profile Settings</a>
                        </div>
                    </div>
                    <div class="dropdown-footer">
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <a href="{{ route('admin.instant-estate.index') }}" class="btn btn-outline" style="margin-bottom:1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
            Back to Instant Estate
        </a>

        <header class="page-header">
            <div class="page-title">
                <h1>Pending Document Reviews</h1>
                <p class="page-subtitle">Review and approve or reject documents flagged by the system</p>
            </div>
        </header>

        @if(session('success'))
            <div class="alert-message alert-info" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(isset($sessions) && $sessions->count())
            @foreach($sessions as $session)
                @php
                    $isValidUuid = $session->session_id && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $session->session_id);
                @endphp
                @if($isValidUuid)
                    <div class="review-card">
                        <div class="review-card-body">
                            <div class="review-card-header">
                                <span class="review-session-id">{{ $session->session_id }}</span>
                                <span class="status-badge {{ $session->admin_status === 'pending_approval' ? 'badge-info' : 'badge-warning' }}">
                                    {{ $session->admin_status === 'pending_approval' ? 'Auto‑approved (score ≥80)' : 'Suspicious (60‑79)' }}
                                </span>
                            </div>

                            <div class="review-score">
                                Authenticity Score: <strong>{{ $session->authenticity_score }}/100</strong>
                            </div>

                            <div class="review-breakdown">
                                {{ json_encode($session->authenticity_breakdown ?? [], JSON_PRETTY_PRINT) }}
                            </div>

                            <div class="review-extracted">
                                <h6>Extracted Data</h6>
                                <ul>
                                    <li>Deceased: {{ $session->deceased_name }}</li>
                                    <li>NRIC: {{ $session->deceased_nric }}</li>
                                    <li>Death Date: {{ $session->death_date }}</li>
                                    <li>Registration No: {{ $session->registration_number }}</li>
                                </ul>
                            </div>

                            <div class="review-actions">
                                <form action="{{ route('admin.instant-estate.approval.approve', $session->session_id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success">Approve</button>
                                </form>
                                <form action="{{ route('admin.instant-estate.approval.reject', $session->session_id) }}" method="POST">
                                    @csrf
                                    <input type="text" name="reason" placeholder="Rejection reason" required>
                                    <button class="btn btn-danger">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="pagination-container">
                {{ $sessions->links() }}
            </div>
        @else
            <div class="alert-message alert-info" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                No pending document reviews found.
            </div>
        @endif
    </main>

    <script>
        // Navigation dropdown manager
        class NavigationManager {
            constructor() {
                this.btn = document.getElementById('user-profile-btn');
                this.dropdown = document.getElementById('user-profile-dropdown');
                this.isOpen = false;
                this.init();
            }
            init() {
                if (!this.btn || !this.dropdown) return;
                this.btn.addEventListener('click', (e) => { e.stopPropagation(); this.toggle(); });
                this.btn.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.toggle(); }
                    if (e.key === 'ArrowDown' && !this.isOpen) { e.preventDefault(); this.open(); }
                });
                document.addEventListener('click', (e) => {
                    if (this.isOpen && !this.dropdown.contains(e.target) && !this.btn.contains(e.target)) this.close();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isOpen) { this.close(); this.btn.focus(); }
                });
            }
            toggle() { this.isOpen ? this.close() : this.open(); }
            open() {
                this.isOpen = true;
                this.dropdown.style.display = 'block';
                this.dropdown.setAttribute('aria-hidden', 'false');
                setTimeout(() => this.dropdown.classList.add('active'), 10);
                this.btn.setAttribute('aria-expanded', 'true');
                const chevron = this.btn.querySelector('.chevron-icon');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            }
            close() {
                this.isOpen = false;
                this.dropdown.classList.remove('active');
                this.dropdown.setAttribute('aria-hidden', 'true');
                setTimeout(() => { if (!this.isOpen) this.dropdown.style.display = 'none'; }, 300);
                this.btn.setAttribute('aria-expanded', 'false');
                const chevron = this.btn.querySelector('.chevron-icon');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new NavigationManager();
        });
    </script>
</body>
</html>