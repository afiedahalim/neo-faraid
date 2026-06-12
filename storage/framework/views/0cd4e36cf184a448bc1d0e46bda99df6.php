<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Session Details • Neo Faraid Admin</title>
    <meta name="description" content="View and manage instant estate session details, OCR data, and document preview">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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

        .admin-logo-icon {
            width: 32px;
            height: 32px;
            color: var(--accent-color);
            fill: currentColor;
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
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            border: none;
            font-size: 0.95rem;
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

        .btn-delete {
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: var(--white);
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-download {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: var(--white);
        }

        /* Alert Messages */
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

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid var(--danger-color);
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(255px, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: var(--white);
            padding: 1.25rem;
            border-radius: var(--border-radius-md);
            border-left: 4px solid var(--primary-color);
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .info-item:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 0.96rem;
            color: var(--text-primary);
            font-weight: 550;
        }

        /* Status badges (inline for instant estate) */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-uploaded { background: #e9ecef; color: #6c757d; }
        .status-processing_ocr { background: #fff3cd; color: #856404; }
        .status-ocr_completed { background: #d1ecf1; color: #0c5460; }
        .status-data_confirmed { background: #cce5ff; color: #004085; }
        .status-record_found { background: #d4edda; color: #155724; }
        .status-no_record { background: #e2e3e5; color: #383d41; }
        .status-captcha_verified { background: #d4edda; color: #155724; }
        .status-email_sent { background: #d4edda; color: #155724; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }

        .badge-pass {
            background: #d4edda;
            color: #155724;
            padding: 0.25rem 0.625rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-fail {
            background: #f8d7da;
            color: #721c24;
            padding: 0.25rem 0.625rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Content blocks */
        .content-block {
            margin-bottom: 2rem;
        }

        .block-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .block-title svg {
            width: 22px;
            height: 22px;
            color: var(--secondary-color);
        }

        .document-preview {
            background: var(--light-bg);
            border-radius: var(--border-radius-md);
            padding: 1.5rem;
            border: 2px dashed var(--light-border);
            text-align: center;
            margin-top: 0.5rem;
        }

        .document-preview img {
            max-width: 100%;
            max-height: 400px;
            border-radius: var(--border-radius-sm);
            box-shadow: var(--shadow-sm);
        }

        .ocr-data {
            background: var(--light-bg);
            border-radius: var(--border-radius-md);
            padding: 1.5rem;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            word-break: break-word;
            color: var(--text-primary);
            max-height: 400px;
            overflow-y: auto;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 2rem;
            justify-content: flex-end;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            backdrop-filter: blur(5px);
            padding: 1rem;
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 520px;
            animation: modalSlideUp 0.3s ease;
        }

        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: var(--white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: var(--white);
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem 1.5rem;
        }

        .modal-info-box {
            background: var(--light-bg);
            padding: 1rem 1.25rem;
            border-radius: var(--border-radius-md);
            margin-top: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-cancel {
            padding: 0.75rem 1.5rem;
            background: var(--white);
            color: var(--text-primary);
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-md);
            font-weight: 500;
            cursor: pointer;
        }

        .btn-confirm-delete {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--light-bg); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--text-light); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-color); }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-top-nav { padding: 0 1rem; }
            .admin-logo-text { font-size: 1.2rem; }
            .user-profile-btn { min-width: auto; padding: 0.5rem; }
            .user-profile-info { display: none; }
        }

        @media (max-width: 768px) {
            .admin-top-nav { height: 60px; }
            .admin-logo-text { display: none; }
            .admin-main { padding: 1.5rem; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .info-grid { grid-template-columns: 1fr; }
            .modal-footer { flex-direction: column; }
            .modal-footer button { width: 100%; }
        }

        @media (max-width: 576px) {
            .admin-main { padding: 1rem; }
            .btn { padding: 0.6rem 1rem; font-size: 0.875rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>
    <?php
        $isValidUuid = $session->session_id && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $session->session_id);
    ?>

    <!-- Navigation Bar -->
    <nav class="admin-top-nav" role="navigation" aria-label="Admin navigation">
        <div class="nav-left">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-logo" aria-label="Neo Faraid Admin Home">
                <i class="fas fa-layer-group" style="color: var(--accent-color); font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3)); animation: logoFloat 4s ease-in-out infinite;"></i>
                <span class="admin-logo-text">Neo Faraid Admin</span>
            </a>
        </div>
        <div class="nav-right">
            <?php
                $user = auth()->user();
                $lastLogin = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently';
                $fullInitials = strtoupper(implode('', array_map(fn($n) => $n[0], explode(' ', $user->name, 2))));
            ?>
            <div class="user-profile-container" id="user-profile-container">
                <button class="user-profile-btn" id="user-profile-btn" aria-label="User profile menu" aria-expanded="false" aria-haspopup="true">
                    <div class="user-avatar" aria-hidden="true">
                        <span class="avatar-initials"><?php echo e($fullInitials); ?></span>
                        <?php if($user->role === 'admin'): ?>
                            <span class="admin-badge" aria-label="Administrator">A</span>
                        <?php endif; ?>
                    </div>
                    <div class="user-profile-info">
                        <span class="user-name"><?php echo e($user->name); ?></span>
                        <span class="user-role"><?php echo e(ucfirst($user->role)); ?></span>
                    </div>
                    <svg class="chevron-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="user-profile-dropdown" id="user-profile-dropdown" role="menu" aria-label="User profile menu" aria-hidden="true">
                    <div class="profile-header">
                        <div class="profile-avatar-large" aria-hidden="true">
                            <span class="avatar-initials-large"><?php echo e($fullInitials); ?></span>
                            <?php if($user->role === 'admin'): ?>
                                <div class="admin-badge-large" aria-label="Administrator">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name"><?php echo e($user->name); ?></h3>
                            <p class="profile-email"><?php echo e($user->email); ?></p>
                            <div class="profile-meta">
                                <span class="last-login">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Last login: <?php echo e($lastLogin); ?>

                                </span>
                                <span class="user-status active">● Online</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-section">
                        <h4 class="section-title">Navigation</h4>
                        <nav class="dropdown-nav" role="navigation" aria-label="Admin navigation">
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="dropdown-nav-item">Manage Users</a>
                            <a href="<?php echo e(route('admin.feedback.index')); ?>" class="dropdown-nav-item">Manage Feedback</a>
                            <a href="<?php echo e(route('admin.faq.index')); ?>" class="dropdown-nav-item">Manage FAQ</a>
                            <a href="<?php echo e(route('admin.calculations.index')); ?>" class="dropdown-nav-item">Manage Calculations</a>
                            <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="dropdown-nav-item active">Manage Instant Estate</a>
                            <a href="<?php echo e(route('admin.estate-setup.index')); ?>" class="dropdown-nav-item">Manage Estate Planning</a>
                        </nav>
                    </div>
                    <div class="dropdown-section">
                        <h4 class="section-title">Account</h4>
                        <div class="account-links">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="account-link">Profile Settings</a>
                        </div>
                    </div>
                    <div class="dropdown-footer">
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form">
                            <?php echo csrf_field(); ?>
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
        <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="btn btn-outline" style="margin-bottom:1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
            Back to Instant Estate
        </a>

        <header class="page-header">
            <div class="page-title">
                <h1>Session Details</h1>
                <p class="page-subtitle">Deceased: <?php echo e($session->deceased_name ?? 'N/A'); ?> | ID: <?php echo e($session->session_id); ?></p>
            </div>
        </header>

        <?php if(session('success')): ?>
            <div class="alert-message alert-success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert-message alert-error" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Session Information Grid -->
        <div class="content-block">
            <div class="block-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Session Overview
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo e($session->status); ?>">
                            <?php echo e($session->status_label ?? ucfirst(str_replace('_', ' ', $session->status))); ?>

                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Deceased Name</div>
                    <div class="info-value"><?php echo e($session->deceased_name ?: 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Created By</div>
                    <div class="info-value">
                        <?php if($session->user): ?>
                            <?php echo e($session->user->name); ?> (<?php echo e($session->user->email); ?>)
                        <?php else: ?>
                            Guest: <?php echo e($session->guest_name ?: 'Unknown'); ?><?php echo e($session->guest_email ? ' ('.$session->guest_email.')' : ''); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Uploaded At</div>
                    <div class="info-value"><?php echo e($session->created_at?->format('d M Y, H:i') ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Original Filename</div>
                    <div class="info-value" style="word-break:break-all;"><?php echo e($session->original_filename ?: 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">File Size</div>
                    <div class="info-value"><?php echo e($session->formatted_file_size ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Quality Check</div>
                    <div class="info-value">
                        <?php if($session->quality_check_passed): ?>
                            <span class="badge-pass">Passed</span>
                        <?php else: ?>
                            <span class="badge-fail">Failed</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">OCR Confidence</div>
                    <div class="info-value"><?php echo e($session->ocr_confidence ?? 'N/A'); ?>%</div>
                </div>
                <?php if($session->matched_record_type): ?>
                <div class="info-item">
                    <div class="info-label">Matched Record</div>
                    <div class="info-value">
                        <?php echo e(ucfirst(str_replace('_', ' ', $session->matched_record_type))); ?>

                        (ID: <?php echo e($session->matched_record_id); ?>)
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- OCR Extracted Data -->
        <?php if($session->extracted_data): ?>
            <div class="content-block">
                <div class="block-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    OCR Extracted Data
                </div>
                <pre class="ocr-data"><?php echo e(json_encode($session->extracted_data, JSON_PRETTY_PRINT)); ?></pre>
            </div>
        <?php endif; ?>

        <!-- Quality Check Details -->
        <?php if($session->quality_check_details): ?>
            <div class="content-block">
                <div class="block-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Quality Check Details
                </div>
                <pre class="ocr-data"><?php echo e(json_encode($session->quality_check_details, JSON_PRETTY_PRINT)); ?></pre>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <?php if($isValidUuid): ?>
                <button type="button" class="btn btn-delete delete-session-btn"
                        data-id="<?php echo e($session->session_id); ?>"
                        data-name="<?php echo e($session->deceased_name ?: 'Session #'.$session->session_id); ?>"
                        data-route="<?php echo e(route('admin.instant-estate.delete-session', $session->session_id)); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Session
                </button>
            <?php endif; ?>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-modal" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="delete-modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Confirm Session Deletion
                </h3>
                <button class="modal-close" id="cancel-delete-modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p>You are about to <strong>permanently delete</strong> this instant estate session. This action <strong>cannot be undone</strong> and all associated data including the uploaded document, OCR results, and processing history will be irrevocably removed.</p>
                <div class="modal-info-box">
                    <strong>Session Reference:</strong>
                    <span id="delete-session-name"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="cancel-delete-btn">Cancel</button>
                    <form id="delete-form" method="POST" style="display: none;"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form>
                    <button type="button" class="btn-confirm-delete" id="confirm-delete-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Yes, Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==================== NAVIGATION MANAGER ====================
        class NavigationManager {
            constructor() {
                this.btn = document.getElementById('user-profile-btn');
                this.dropdown = document.getElementById('user-profile-dropdown');
                this.isOpen = false;
                this.isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
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
                if (this.isTouch) this.setupTouch();
            }
            setupTouch() {
                let startY = 0;
                this.dropdown.addEventListener('touchstart', (e) => { startY = e.touches[0].clientY; });
                this.dropdown.addEventListener('touchmove', (e) => {
                    if (!this.isOpen) return;
                    if (startY - e.touches[0].clientY > 50) this.close();
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

        // ==================== DELETE MODAL MANAGER ====================
        class DeleteModalManager {
            constructor() {
                this.modal = document.getElementById('delete-modal');
                this.closeModalBtn = document.getElementById('cancel-delete-modal');
                this.cancelBtn = document.getElementById('cancel-delete-btn');
                this.confirmBtn = document.getElementById('confirm-delete-btn');
                this.deleteForm = document.getElementById('delete-form');
                this.sessionNameSpan = document.getElementById('delete-session-name');
                this.deleteButtons = document.querySelectorAll('.delete-session-btn');
                this.init();
            }
            init() {
                if (!this.modal) return;
                this.deleteButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const route = btn.dataset.route;
                        const name = btn.dataset.name;
                        if (this.deleteForm) this.deleteForm.action = route;
                        if (this.sessionNameSpan) this.sessionNameSpan.textContent = name;
                        this.open();
                    });
                });
                [this.closeModalBtn, this.cancelBtn].forEach(el => {
                    if (el) el.addEventListener('click', () => this.close());
                });
                if (this.confirmBtn) {
                    this.confirmBtn.addEventListener('click', () => this.deleteForm?.submit());
                }
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal) this.close();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.modal.classList.contains('active')) this.close();
                });
            }
            open() {
                this.modal.style.display = 'flex';
                this.modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                setTimeout(() => this.confirmBtn?.focus(), 100);
            }
            close() {
                this.modal.classList.remove('active');
                this.modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Boot
        document.addEventListener('DOMContentLoaded', () => {
            new NavigationManager();
            new DeleteModalManager();
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/admin/instant-estate/show.blade.php ENDPATH**/ ?>