<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create FAQ • Neo Faraid Admin</title>
    <meta name="description" content="Create new FAQ entry in Neo Faraid Admin Panel">
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
            --light-bg: #f8f9fa;
            --light-border: #e9ecef;
            --text-primary: #495057;
            --text-light: #6c757d;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
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
        
        /* ===== ENHANCED TOP NAVIGATION ===== */
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
            border-bottom: 1px solid rgba(255,255,255,0.1);
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
        
        .nav-links {
            display: flex;
            gap: 0.25rem;
            height: 100%;
            align-items: center;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            color: rgba(255,255,255,0.95);
            text-decoration: none;
            font-weight: 500;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            border: 1px solid transparent;
            height: 44px;
            position: relative;
            font-size: 0.95rem;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: var(--white);
            border-color: rgba(255,255,255,0.2);
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: var(--accent-color);
            border-color: rgba(255,215,0,0.3);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255,215,0,0.1);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }
        
        .nav-link svg {
            width: 20px;
            height: 20px;
            stroke-width: 2;
        }
        
        /* ===== ENHANCED USER PROFILE SECTION ===== */
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
        
        /* User Profile Dropdown */
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
        
        /* Profile Header */
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
        
        /* Dropdown Sections */
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
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            padding: 0 1.5rem;
        }
        
        .quick-action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 0.5rem;
            background: var(--light-bg);
            border-radius: var(--border-radius-md);
            color: var(--text-primary);
            text-decoration: none;
            transition: var(--transition);
            border: 2px solid transparent;
        }
        
        .quick-action-item:hover {
            background: var(--white);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        
        .quick-action-item.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
        }
        
        .quick-action-item svg {
            width: 20px;
            height: 20px;
        }
        
        .quick-action-item span {
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }
        
        /* Dropdown Navigation */
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
        
        /* Account Links */
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
        
        /* Dropdown Footer */
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
        
        /* ===== MAIN CONTENT ===== */
        .admin-main {
            padding: 2.5rem;
            background: var(--light-bg);
            overflow-y: auto;
            max-width: 1400px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
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
        
        .btn-back {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
            color: var(--text-primary);
            border-radius: var(--border-radius-md);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--white);
        }
        
        .btn-back svg {
            width: 18px;
            height: 18px;
            stroke-width: 2.5;
        }
        
        /* ===== ALERT MESSAGES ===== */
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
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-left: 4px solid var(--warning-color);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-left: 4px solid var(--primary-color);
        }
        
        /* ===== CONFIRMATION MODAL ===== */
        .confirmation-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        
        .confirmation-modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .confirmation-content {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            box-shadow: var(--shadow-lg);
            transform: translateY(-20px);
            transition: var(--transition);
        }
        
        .confirmation-modal.active .confirmation-content {
            transform: translateY(0);
        }
        
        .confirmation-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--warning-color) 0%, #ffdf7e 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .confirmation-icon svg {
            width: 32px;
            height: 32px;
            color: var(--white);
        }
        
        .confirmation-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .confirmation-message {
            color: var(--text-light);
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .confirmation-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .confirmation-btn {
            padding: 0.875rem 2rem;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 1rem;
            min-width: 120px;
            font-family: inherit;
        }
        
        .confirmation-btn.confirm {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
        }
        
        .confirmation-btn.confirm:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .confirmation-btn.cancel {
            background: var(--light-bg);
            color: var(--text-primary);
        }
        
        .confirmation-btn.cancel:hover {
            background: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        
        /* ===== BUTTON STYLES ===== */
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
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 100%);
            color: var(--text-primary);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--white);
        }
        
        /* ===== FORM STYLES ===== */
        .form-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            padding: 2.5rem;
            transition: var(--transition);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-container:hover {
            box-shadow: var(--shadow-lg);
        }
        
        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.95rem;
        }
        
        .form-label.required::after {
            content: ' *';
            color: var(--danger-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            background: var(--white);
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-md);
            font-family: inherit;
            font-weight: 400;
            color: var(--text-primary);
            transition: var(--transition);
            font-size: 0.95rem;
        }
        
        .form-control:hover {
            border-color: #b8c2cc;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: var(--danger-color);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23dc3545'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 20px;
            padding-right: 3rem;
        }
        
        textarea.form-control {
            min-height: 140px;
            resize: vertical;
            line-height: 1.6;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236c757d'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 20px;
            padding-right: 3rem;
            cursor: pointer;
        }
        
        select.form-control:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%231a5fb4'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        }
        
        .form-text {
            display: block;
            margin-top: 0.5rem;
            color: var(--text-light);
            font-size: 0.85rem;
        }
        
        .form-text.text-error {
            color: var(--danger-color);
            font-weight: 500;
        }
        
        /* Checkbox Styles */
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            user-select: none;
        }
        
        .form-check-input {
            width: 22px;
            height: 22px;
            border: 2px solid var(--light-border);
            border-radius: 6px;
            background: var(--white);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            appearance: none;
            -webkit-appearance: none;
        }
        
        .form-check-input:checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-input:checked::after {
            content: '';
            position: absolute;
            top: 4px;
            left: 8px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .form-check-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        }
        
        .form-check-label {
            font-weight: 500;
            color: var(--text-primary);
            cursor: pointer;
        }
        
        /* ===== FORM ACTIONS ===== */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--light-border);
        }
        
        /* ===== CATEGORY BADGES ===== */
        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            border-radius: var(--border-radius-xl);
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        .category-getting-started {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 86, 179, 0.1) 100%);
            color: #0069d9;
            border: 1px solid rgba(0, 123, 255, 0.2);
        }
        
        .category-calculations {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(33, 136, 56, 0.1) 100%);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        
        .category-security-privacy {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(224, 168, 0, 0.1) 100%);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        
        .category-other {
            background: linear-gradient(135deg, rgba(108, 117, 125, 0.1) 0%, rgba(90, 98, 104, 0.1) 100%);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }
        
        /* ===== LOADING STATES ===== */
        .btn.loading {
            position: relative;
            color: transparent !important;
        }
        
        .btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 0.8s linear infinite;
        }
        
        .btn-secondary.loading::after {
            border: 2px solid rgba(108, 117, 125, 0.3);
            border-top-color: var(--text-light);
        }
        
        @keyframes spin {
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
        
        /* ===== SUCCESS MODAL ===== */
        .success-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10001;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        
        .success-modal.active {
            opacity: 1;
            visibility: visible;
        }
        
        .success-content {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 3rem;
            max-width: 450px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
            transform: translateY(-20px);
            transition: var(--transition);
        }
        
        .success-modal.active .success-content {
            transform: translateY(0);
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        @keyframes bounceIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .success-icon svg {
            width: 40px;
            height: 40px;
            color: var(--white);
        }
        
        .success-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--success-dark);
            margin-bottom: 1rem;
        }
        
        .success-message {
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 1.1rem;
        }
        
        .success-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .success-btn {
            padding: 0.875rem 2rem;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 1rem;
            min-width: 140px;
            font-family: inherit;
        }
        
        .success-btn.primary {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: var(--white);
        }
        
        .success-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
        }
        
        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1200px) {
            .nav-links {
                overflow-x: auto;
                padding-bottom: 5px;
            }
            
            .nav-link {
                white-space: nowrap;
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }
            
            .admin-main {
                padding: 2rem;
            }
        }
        
        @media (max-width: 992px) {
            .admin-top-nav {
                padding: 0 1rem;
            }
            
            .nav-links {
                gap: 0.15rem;
            }
            
            .nav-link {
                padding: 0.75rem;
                font-size: 0.85rem;
            }
            
            .nav-link .nav-link-text {
                display: none;
            }
            
            .nav-link svg {
                margin-right: 0;
            }
            
            .admin-logo-text {
                font-size: 1.2rem;
            }
            
            .user-profile-btn {
                min-width: auto;
                padding: 0.5rem;
            }
            
            .user-profile-info {
                display: none;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .admin-top-nav {
                height: 60px;
            }
            
            .nav-left {
                gap: 1rem;
            }
            
            .admin-logo-text {
                display: none;
            }
            
            .nav-links {
                margin-left: auto;
            }
            
            .admin-main {
                padding: 1.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .form-container {
                padding: 1.5rem;
            }
            
            .confirmation-content,
            .success-content {
                padding: 2rem;
            }
            
            .confirmation-actions,
            .success-actions {
                flex-direction: column;
            }
            
            .confirmation-btn,
            .success-btn {
                width: 100%;
            }
            
            .user-profile-dropdown {
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                width: 100%;
                max-width: 100%;
                border-radius: 0;
                border-left: none;
                border-right: none;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .admin-main {
                padding: 1rem;
            }
            
            .form-container {
                padding: 1.25rem;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-control {
                padding: 0.75rem;
            }
            
            .confirmation-content,
            .success-content {
                padding: 1.5rem;
            }
            
            .quick-action-item {
                flex-direction: row;
                justify-content: flex-start;
                padding: 0.75rem 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .nav-link {
                padding: 0.5rem;
            }
            
            .nav-link svg {
                width: 18px;
                height: 18px;
            }
            
            .admin-logo-icon {
                width: 28px;
                height: 28px;
            }
            
            .alert-message {
                padding: 1rem;
            }
            
            .confirmation-title,
            .success-title {
                font-size: 1.25rem;
            }
            
            .confirmation-icon,
            .success-icon {
                width: 48px;
                height: 48px;
            }
            
            .confirmation-icon svg,
            .success-icon svg {
                width: 24px;
                height: 24px;
            }
            
            .user-avatar {
                width: 32px;
                height: 32px;
            }
            
            .avatar-initials {
                font-size: 0.9rem;
            }
        }
        
        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--light-bg);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--text-light);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        
        /* ===== ACCESSIBILITY ===== */
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        button:focus,
        a:focus,
        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }
        
        /* ===== REDUCED MOTION ===== */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .admin-logo-icon,
            .user-avatar {
                animation: none;
            }
            
            .user-status.active::before {
                animation: none;
            }
        }
        
        /* ===== DROPDOWN BACKDROP ===== */
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Enhanced Top Navigation -->
    <nav class="admin-top-nav" role="navigation" aria-label="Admin navigation">
        <div class="nav-left">
            <a href="{{ route('admin.dashboard') }}" class="admin-logo" aria-label="Neo Faraid Admin Home">
                <i class="fas fa-layer-group" style="color: var(--accent-color); font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3)); animation: logoFloat 4s ease-in-out infinite;"></i>
                <span class="admin-logo-text">Neo Faraid Admin</span>
            </a>
        </div>
        
        <!-- User Profile Section -->
        <div class="nav-right">
            @php
                $user = auth()->user();
                $lastLogin = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently';
                $fullInitials = strtoupper(implode('', array_map(function($name) {
                    return substr($name, 0, 1);
                }, explode(' ', $user->name, 2))));
            @endphp
            
            <div class="user-profile-container" id="user-profile-container">
                <!-- User Profile Button -->
                <button class="user-profile-btn" 
                        id="user-profile-btn"
                        aria-label="User profile menu"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-user-role="{{ $user->role }}"
                        data-user-initials="{{ $fullInitials }}">
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
                
                <!-- User Profile Dropdown -->
                <div class="user-profile-dropdown" 
                     id="user-profile-dropdown"
                     role="menu"
                     aria-label="User profile menu"
                     aria-hidden="true">
                    <!-- Profile Header -->
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
                    
                    <!-- Navigation Links -->
                    <div class="dropdown-section">
                        <h4 class="section-title">Navigation</h4>
                        <nav class="dropdown-nav" role="navigation" aria-label="Admin navigation">
                            <a href="{{ route('admin.users.index') }}" class="dropdown-nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                                <span>Manage Users</span>
                            </a>
                            <a href="{{ route('admin.feedback.index') }}" class="dropdown-nav-item {{ request()->is('admin/feedback*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                <span>Manage Feedback</span>
                            </a>
                            <a href="{{ route('admin.faq.index') }}" class="dropdown-nav-item {{ request()->is('admin/faq*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Manage FAQ</span>
                            </a>
                            <a href="{{ route('admin.calculations.index') }}" class="dropdown-nav-item {{ request()->is('admin/calculations*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                <span>Manage Calculations</span>
                            </a>
                            <a href="{{ route('admin.instant-estate.index') }}" class="dropdown-nav-item active">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Manage Instant Estate</span>
                            </a>
                            <a href="{{ route('admin.estate-setup.index') }}" class="dropdown-nav-item {{ request()->is('admin/estate-setup*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>Manage Estate Planning</span>
                            </a>
                        </nav>
                    </div>
                    
                    <!-- Account Settings -->
                    <div class="dropdown-section">
                        <h4 class="section-title">Account</h4>
                        <div class="account-links">
                            <a href="{{ route('profile.edit') }}" 
                               class="account-link {{ request()->is('profile*') ? 'active' : '' }}"
                               role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Profile Settings</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Footer with Logout -->
                    <div class="dropdown-footer">
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn" role="menuitem" aria-label="Log out from admin panel">
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmation-modal">
        <div class="confirmation-content">
            <div class="confirmation-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="confirmation-title">Confirm Creation</h3>
            <p class="confirmation-message">
                Are you sure you want to create this FAQ?<br>
                This will add a new entry to the FAQ section.
            </p>
            <div class="confirmation-actions">
                <button type="button" class="confirmation-btn cancel" id="modal-cancel-btn">Cancel</button>
                <button type="button" class="confirmation-btn confirm" id="modal-confirm-btn">Yes, Create</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal" id="success-modal">
        <div class="success-content">
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="success-title">Success!</h3>
            <p class="success-message" id="success-message">
                FAQ successfully created!
            </p>
            <div class="success-actions">
                <button type="button" class="success-btn primary" id="success-ok-btn">
                    View All FAQs
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-title">
                <h1>Create New FAQ</h1>
                <p class="page-subtitle">Add a new frequently asked question to the system</p>
            </div>
            
            <a href="{{ route('admin.faq.index') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to FAQ
            </a>
        </header>
        
        <!-- Form Container -->
        <div class="form-container">
            <form action="{{ route('admin.faq.store') }}" method="POST" id="create-faq-form">
                @csrf
                
                <!-- Dynamic Alert Container -->
                <div id="dynamic-alert-container">
                    @if(session('success'))
                    <div class="alert-message alert-success" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert-message alert-error" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                    @endif
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert-message alert-error" role="alert" id="form-errors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <strong>Please fix the following errors:</strong>
                            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Question Field -->
                <div class="form-group">
                    <label for="question" class="form-label required">Question</label>
                    <input type="text" 
                           id="question" 
                           name="question" 
                           class="form-control @error('question') is-invalid @enderror"
                           value="{{ old('question') }}"
                           placeholder="Enter the frequently asked question"
                           required>
                    @error('question')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Enter a clear and concise question that users might ask
                    </div>
                </div>

                <!-- Answer Field -->
                <div class="form-group">
                    <label for="answer" class="form-label required">Answer</label>
                    <textarea id="answer" 
                              name="answer" 
                              class="form-control @error('answer') is-invalid @enderror"
                              rows="6"
                              placeholder="Provide a detailed answer to the question"
                              required>{{ old('answer') }}</textarea>
                    @error('answer')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Provide a comprehensive answer. You can use simple HTML tags like &lt;strong&gt;, &lt;em&gt;, &lt;a&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;
                    </div>
                </div>

                <!-- Category Field -->
                <div class="form-group">
                    <label for="category" class="form-label required">Category</label>
                    <select id="category" 
                            name="category" 
                            class="form-control @error('category') is-invalid @enderror"
                            required>
                        <option value="">Select a category</option>
                        <option value="gettingStarted" {{ old('category', 'gettingStarted') == 'gettingStarted' ? 'selected' : '' }}>
                            Getting Started
                        </option>
                        <option value="calculations" {{ old('category') == 'calculations' ? 'selected' : '' }}>
                            Calculations
                        </option>
                        <option value="securityPrivacy" {{ old('category') == 'securityPrivacy' ? 'selected' : '' }}>
                            Security & Privacy
                        </option>
                        <option value="others" {{ old('category') == 'others' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>
                    @error('category')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Choose the most relevant category for this FAQ
                    </div>
                    
                    <!-- Category Badge Preview -->
                    <div id="category-preview">
                        <span class="category-badge" id="category-badge">
                            @php
                                $category = old('category', 'gettingStarted');
                                $categoryClass = match($category) {
                                    'gettingStarted' => 'category-getting-started',
                                    'calculations' => 'category-calculations',
                                    'securityPrivacy' => 'category-security-privacy',
                                    'others' => 'category-other',
                                    default => 'category-getting-started'
                                };
                                
                                $categoryLabel = match($category) {
                                    'gettingStarted' => 'Getting Started',
                                    'calculations' => 'Calculations',
                                    'securityPrivacy' => 'Security & Privacy',
                                    'others' => 'Other',
                                    default => 'Getting Started'
                                };
                            @endphp
                            <span class="{{ $categoryClass }}">{{ $categoryLabel }}</span>
                        </span>
                        <span class="form-text">Category preview</span>
                    </div>
                </div>

                <!-- Display Order Field -->
                <div class="form-group">
                    <label for="order" class="form-label required">Display Order</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           class="form-control @error('order') is-invalid @enderror"
                           value="{{ old('order', 0) }}"
                           placeholder="Enter display order"
                           min="0"
                           max="999"
                           required>
                    @error('order')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Lower numbers appear first. Use 0 for highest priority
                    </div>
                </div>

                <!-- Publish Status -->
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" 
                               id="is_published" 
                               name="is_published" 
                               value="1"
                               class="form-check-input"
                               {{ old('is_published', true) ? 'checked' : '' }}>
                        <label for="is_published" class="form-check-label">
                            Publish this FAQ
                        </label>
                    </div>
                    <div class="form-text">
                        Unpublished FAQs won't be visible to users
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" 
                            class="btn btn-primary"
                            id="submit-btn">
                        Create FAQ
                    </button>
                    
                    <button type="button" 
                            class="btn btn-secondary"
                            id="reset-btn">
                        Reset
                    </button>
                    
                    <a href="{{ route('admin.faq.index') }}" 
                       class="btn btn-secondary"
                       id="cancel-btn">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Navigation Manager Class
        class NavigationManager {
            constructor() {
                this.selectors = {
                    userProfileBtn: '#user-profile-btn',
                    userProfileDropdown: '#user-profile-dropdown',
                    navLinks: '.nav-links',
                    dropdownNav: '.dropdown-nav',
                    logoutForm: '.logout-form'
                };
                
                this.state = {
                    isDropdownOpen: false,
                    isTouchDevice: 'ontouchstart' in window || navigator.maxTouchPoints > 0,
                    activeNavItem: 'faq' // Default active based on current page
                };
                
                this.elements = {};
                this.init();
            }
            
            init() {
                this.cacheElements();
                this.bindEvents();
                this.setupAccessibility();
                this.updateActiveStates();
                this.setupKeyboardNavigation();
                this.setupTouchGestures();
            }
            
            cacheElements() {
                Object.keys(this.selectors).forEach(key => {
                    this.elements[key] = document.querySelector(this.selectors[key]);
                });
            }
            
            bindEvents() {
                // User profile dropdown
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.toggleUserDropdown();
                    });
                    
                    this.elements.userProfileBtn.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            this.toggleUserDropdown();
                        }
                        if (e.key === 'ArrowDown' && !this.state.isDropdownOpen) {
                            e.preventDefault();
                            this.openUserDropdown();
                        }
                    });
                }
                
                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => this.handleOutsideClick(e));
                
                // Keyboard navigation
                document.addEventListener('keydown', (e) => this.handleKeydown(e));
                
                // Nav link clicks
                if (this.elements.navLinks) {
                    this.elements.navLinks.addEventListener('click', (e) => {
                        const navLink = e.target.closest('.nav-link');
                        if (navLink) {
                            this.setActiveNavItem(navLink.dataset.navItem);
                        }
                    });
                }
            }
            
            setupAccessibility() {
                // Set ARIA attributes
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.setAttribute('role', 'button');
                    this.elements.userProfileBtn.setAttribute('aria-expanded', 'false');
                }
                
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.setAttribute('role', 'menu');
                    this.elements.userProfileDropdown.setAttribute('aria-label', 'User profile menu');
                }
                
                // Set roles for nav links
                const navLinks = document.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.setAttribute('role', 'menuitem');
                });
                
                // Set roles for dropdown items
                const dropdownItems = document.querySelectorAll('.dropdown-nav-item, .quick-action-item, .account-link');
                dropdownItems.forEach(item => {
                    item.setAttribute('role', 'menuitem');
                });
            }
            
            setupKeyboardNavigation() {
                // Add keyboard navigation to main nav
                if (this.elements.navLinks) {
                    const navItems = this.elements.navLinks.querySelectorAll('.nav-link');
                    navItems.forEach((item, index) => {
                        item.addEventListener('keydown', (e) => {
                            switch(e.key) {
                                case 'ArrowRight':
                                    e.preventDefault();
                                    const nextItem = navItems[index + 1] || navItems[0];
                                    nextItem.focus();
                                    break;
                                case 'ArrowLeft':
                                    e.preventDefault();
                                    const prevItem = navItems[index - 1] || navItems[navItems.length - 1];
                                    prevItem.focus();
                                    break;
                                case 'Home':
                                    e.preventDefault();
                                    navItems[0].focus();
                                    break;
                                case 'End':
                                    e.preventDefault();
                                    navItems[navItems.length - 1].focus();
                                    break;
                            }
                        });
                    });
                }
                
                // Add keyboard navigation to dropdown
                if (this.elements.dropdownNav) {
                    const dropdownItems = this.elements.dropdownNav.querySelectorAll('.dropdown-nav-item');
                    dropdownItems.forEach((item, index) => {
                        item.addEventListener('keydown', (e) => {
                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                const nextItem = dropdownItems[index + 1] || dropdownItems[0];
                                nextItem.focus();
                            } else if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                const prevItem = dropdownItems[index - 1] || dropdownItems[dropdownItems.length - 1];
                                prevItem.focus();
                            }
                        });
                    });
                }
            }
            
            setupTouchGestures() {
                if (!this.state.isTouchDevice) return;
                
                // Add swipe gesture support for dropdown
                let touchStartY = 0;
                
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.addEventListener('touchstart', (e) => {
                        touchStartY = e.touches[0].clientY;
                    });
                    
                    this.elements.userProfileDropdown.addEventListener('touchmove', (e) => {
                        if (!this.state.isDropdownOpen) return;
                        
                        const touchY = e.touches[0].clientY;
                        const diff = touchStartY - touchY;
                        
                        if (diff > 50) { // Swipe up
                            this.closeUserDropdown();
                        }
                    });
                }
                
                // Prevent zoom on double tap for nav items
                const navItems = document.querySelectorAll('.nav-link');
                navItems.forEach(item => {
                    item.addEventListener('touchstart', (e) => {
                        if (e.touches.length > 1) {
                            e.preventDefault();
                        }
                    }, { passive: false });
                });
            }
            
            updateActiveStates() {
                // Update main nav active states
                const navItems = document.querySelectorAll('.nav-link');
                navItems.forEach(item => {
                    const navItem = item.dataset.navItem;
                    if (navItem === this.state.activeNavItem) {
                        item.classList.add('active');
                        item.setAttribute('aria-current', 'page');
                    } else {
                        item.classList.remove('active');
                        item.setAttribute('aria-current', 'false');
                    }
                });
                
                // Update dropdown nav active states
                const dropdownItems = document.querySelectorAll('.dropdown-nav-item');
                dropdownItems.forEach(item => {
                    const href = item.getAttribute('href');
                    if (href && window.location.pathname.includes(href.replace(window.location.origin, ''))) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
            
            setActiveNavItem(navItem) {
                if (navItem) {
                    this.state.activeNavItem = navItem;
                    this.updateActiveStates();
                    
                    // Store in session for persistence
                    sessionStorage.setItem('activeNavItem', navItem);
                }
            }
            
            handleOutsideClick(e) {
                if (this.state.isDropdownOpen && 
                    this.elements.userProfileDropdown && 
                    !this.elements.userProfileDropdown.contains(e.target) && 
                    !this.elements.userProfileBtn.contains(e.target)) {
                    this.closeUserDropdown();
                }
            }
            
            handleKeydown(e) {
                // Close dropdown on Escape
                if (e.key === 'Escape' && this.state.isDropdownOpen) {
                    this.closeUserDropdown();
                    if (this.elements.userProfileBtn) {
                        this.elements.userProfileBtn.focus();
                    }
                }
                
                // Trap focus in dropdown when open
                if (e.key === 'Tab' && this.state.isDropdownOpen && this.elements.userProfileDropdown) {
                    this.trapFocus(this.elements.userProfileDropdown, e);
                }
            }
            
            trapFocus(element, e) {
                const focusableElements = element.querySelectorAll(
                    'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                
                if (focusableElements.length === 0) return;
                
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
            
            toggleUserDropdown() {
                if (this.state.isDropdownOpen) {
                    this.closeUserDropdown();
                } else {
                    this.openUserDropdown();
                }
            }
            
            openUserDropdown() {
                this.state.isDropdownOpen = true;
                
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.style.display = 'block';
                    this.elements.userProfileDropdown.setAttribute('aria-hidden', 'false');
                    
                    // Add animation class
                    setTimeout(() => {
                        this.elements.userProfileDropdown.classList.add('active');
                    }, 10);
                    
                    // Focus first item
                    setTimeout(() => {
                        const firstItem = this.elements.userProfileDropdown.querySelector('a, button');
                        if (firstItem) firstItem.focus();
                    }, 100);
                }
                
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.setAttribute('aria-expanded', 'true');
                    const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
                    if (chevron) {
                        chevron.style.transform = 'rotate(180deg)';
                    }
                }
                
                // Add backdrop for mobile
                if (window.innerWidth < 768) {
                    this.addBackdrop();
                }
            }
            
            closeUserDropdown() {
                this.state.isDropdownOpen = false;
                
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.classList.remove('active');
                    this.elements.userProfileDropdown.setAttribute('aria-hidden', 'true');
                    
                    // Remove after animation
                    setTimeout(() => {
                        if (!this.state.isDropdownOpen) {
                            this.elements.userProfileDropdown.style.display = 'none';
                        }
                    }, 300);
                }
                
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.setAttribute('aria-expanded', 'false');
                    const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
                    if (chevron) {
                        chevron.style.transform = 'rotate(0deg)';
                    }
                }
                
                // Remove backdrop
                this.removeBackdrop();
            }
            
            addBackdrop() {
                let backdrop = document.querySelector('.dropdown-backdrop');
                if (!backdrop) {
                    backdrop = document.createElement('div');
                    backdrop.className = 'dropdown-backdrop';
                    document.body.appendChild(backdrop);
                    
                    setTimeout(() => {
                        backdrop.style.opacity = '1';
                    }, 10);
                    
                    backdrop.addEventListener('click', () => this.closeUserDropdown());
                }
            }
            
            removeBackdrop() {
                const backdrop = document.querySelector('.dropdown-backdrop');
                if (backdrop) {
                    backdrop.style.opacity = '0';
                    setTimeout(() => {
                        if (backdrop.parentNode) {
                            backdrop.parentNode.removeChild(backdrop);
                        }
                    }, 300);
                }
            }
        }

        // Form Functionality
        class FormManager {
            constructor() {
                this.form = document.getElementById('create-faq-form');
                this.submitBtn = document.getElementById('submit-btn');
                this.resetBtn = document.getElementById('reset-btn');
                this.cancelBtn = document.getElementById('cancel-btn');
                this.categorySelect = document.getElementById('category');
                this.categoryBadge = document.getElementById('category-badge');
                this.dynamicAlertContainer = document.getElementById('dynamic-alert-container');
                
                // Modal Elements
                this.confirmationModal = document.getElementById('confirmation-modal');
                this.successModal = document.getElementById('success-modal');
                this.modalCancelBtn = document.getElementById('modal-cancel-btn');
                this.modalConfirmBtn = document.getElementById('modal-confirm-btn');
                this.successOkBtn = document.getElementById('success-ok-btn');
                
                // Default values for reset
                this.defaultValues = {
                    question: '',
                    answer: '',
                    category: 'gettingStarted',
                    order: '0',
                    is_published: true
                };
                
                // Form modified flag
                this.formModified = false;
                
                this.init();
            }
            
            init() {
                this.bindEvents();
                this.initConfirmationModal();
                this.initSuccessModal();
                this.updateCategoryBadge();
                this.setupAutoDismissAlerts();
            }
            
            bindEvents() {
                // Track form modifications
                if (this.form) {
                    this.form.querySelectorAll('input, textarea, select').forEach(field => {
                        field.addEventListener('input', () => {
                            this.formModified = true;
                        });
                        
                        field.addEventListener('change', () => {
                            this.formModified = true;
                        });
                    });
                }
                
                // Submit button
                if (this.submitBtn) {
                    this.submitBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (this.validateForm()) {
                            this.confirmationModal.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        }
                    });
                }
                
                // Reset button
                if (this.resetBtn) {
                    this.resetBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.handleReset();
                    });
                }
                
                // Cancel button
                if (this.cancelBtn) {
                    this.cancelBtn.addEventListener('click', (e) => {
                        if (this.formModified) {
                            e.preventDefault();
                            if (confirm('You have unsaved changes. Are you sure you want to cancel?')) {
                                window.location.href = this.cancelBtn.href;
                            }
                        }
                    });
                }
                
                // Category select change
                if (this.categorySelect) {
                    this.categorySelect.addEventListener('change', () => {
                        this.updateCategoryBadge();
                    });
                }
                
                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    // Ctrl + Enter to submit form
                    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && this.submitBtn) {
                        e.preventDefault();
                        this.submitBtn.click();
                    }
                    
                    // Escape to cancel/focus back button
                    if (e.key === 'Escape') {
                        if (this.confirmationModal.classList.contains('active')) {
                            this.hideConfirmationModal();
                        } else if (this.successModal.classList.contains('active')) {
                            this.redirectToFAQIndex();
                        }
                    }
                });
                
                // Button hover effects
                const buttons = document.querySelectorAll('.btn, .nav-link, .user-profile-btn, .logout-btn, .btn-back, .dropdown-nav-item, .quick-action-item, .account-link');
                buttons.forEach(btn => {
                    btn.addEventListener('mouseenter', function() {
                        if (!this.disabled) {
                            this.style.transform = 'translateY(-2px)';
                        }
                    });
                    
                    btn.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            }
            
            initConfirmationModal() {
                if (!this.modalCancelBtn || !this.modalConfirmBtn) return;
                
                this.modalCancelBtn.addEventListener('click', () => this.hideConfirmationModal());
                
                this.modalConfirmBtn.addEventListener('click', () => {
                    this.hideConfirmationModal();
                    this.submitForm();
                });
                
                // Hide modal when clicking outside
                if (this.confirmationModal) {
                    this.confirmationModal.addEventListener('click', (e) => {
                        if (e.target === this.confirmationModal) {
                            this.hideConfirmationModal();
                        }
                    });
                }
                
                // Hide modal with Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.confirmationModal.classList.contains('active')) {
                        this.hideConfirmationModal();
                    }
                });
            }
            
            initSuccessModal() {
                if (!this.successOkBtn) return;
                
                this.successOkBtn.addEventListener('click', () => this.redirectToFAQIndex());
                
                // Hide modal when clicking outside
                if (this.successModal) {
                    this.successModal.addEventListener('click', (e) => {
                        if (e.target === this.successModal) {
                            this.redirectToFAQIndex();
                        }
                    });
                }
                
                // Hide modal with Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.successModal.classList.contains('active')) {
                        this.redirectToFAQIndex();
                    }
                });
            }
            
            hideConfirmationModal() {
                this.confirmationModal.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            showSuccessModal(message = 'FAQ successfully created!') {
                const successMessage = document.getElementById('success-message');
                if (successMessage) {
                    successMessage.textContent = message;
                }
                
                this.successModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Auto redirect after 5 seconds
                setTimeout(() => {
                    if (this.successModal.classList.contains('active')) {
                        this.redirectToFAQIndex();
                    }
                }, 5000);
            }
            
            redirectToFAQIndex() {
                window.location.href = '{{ route("admin.faq.index") }}?success=FAQ+successfully+created';
            }
            
            validateForm() {
                if (!this.form) return false;
                
                this.clearFormErrors();
                
                let isValid = true;
                const question = document.getElementById('question')?.value.trim();
                const answer = document.getElementById('answer')?.value.trim();
                const category = document.getElementById('category')?.value;
                const order = document.getElementById('order')?.value.trim();
                
                // Validate question
                if (!question) {
                    this.showFieldError('question', 'Question is required');
                    isValid = false;
                } else if (question.length < 5) {
                    this.showFieldError('question', 'Question must be at least 5 characters');
                    isValid = false;
                } else if (question.length > 255) {
                    this.showFieldError('question', 'Question must not exceed 255 characters');
                    isValid = false;
                }
                
                // Validate answer
                if (!answer) {
                    this.showFieldError('answer', 'Answer is required');
                    isValid = false;
                } else if (answer.length < 10) {
                    this.showFieldError('answer', 'Answer must be at least 10 characters');
                    isValid = false;
                } else if (answer.length > 2000) {
                    this.showFieldError('answer', 'Answer must not exceed 2000 characters');
                    isValid = false;
                }
                
                // Validate category
                if (!category) {
                    this.showFieldError('category', 'Category is required');
                    isValid = false;
                }
                
                // Validate order
                if (!order) {
                    this.showFieldError('order', 'Display order is required');
                    isValid = false;
                } else {
                    const orderNum = parseInt(order);
                    if (isNaN(orderNum) || orderNum < 0 || orderNum > 999) {
                        this.showFieldError('order', 'Display order must be a number between 0 and 999');
                        isValid = false;
                    }
                }
                
                if (!isValid) {
                    this.showAlert('Please fix the errors in the form before submitting.', 'error', 5000);
                    // Scroll to first error
                    const firstError = this.form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
                
                return isValid;
            }
            
            showFieldError(fieldName, message) {
                const field = document.getElementById(fieldName);
                if (!field) return;
                
                field.classList.add('is-invalid');
                
                // Remove existing error
                let errorElement = field.nextElementSibling;
                while (errorElement && errorElement.classList.contains('text-error')) {
                    errorElement.remove();
                    errorElement = field.nextElementSibling;
                }
                
                // Create new error element
                errorElement = document.createElement('div');
                errorElement.className = 'form-text text-error';
                errorElement.textContent = message;
                field.parentNode.insertBefore(errorElement, field.nextSibling);
            }
            
            clearFormErrors() {
                if (!this.form) return;
                
                // Remove error messages
                const errorMessages = this.form.querySelectorAll('.form-text.text-error');
                errorMessages.forEach(error => error.remove());
                
                // Remove invalid classes
                const invalidInputs = this.form.querySelectorAll('.is-invalid');
                invalidInputs.forEach(input => input.classList.remove('is-invalid'));
                
                // Remove dynamic alerts
                if (this.dynamicAlertContainer) {
                    const dynamicAlerts = this.dynamicAlertContainer.querySelectorAll('.alert-message');
                    dynamicAlerts.forEach(alert => {
                        if (alert.id !== 'form-errors') {
                            alert.remove();
                        }
                    });
                }
            }
            
            submitForm() {
                if (!this.form || !this.submitBtn) return;
                
                // Show loading state
                this.submitBtn.classList.add('loading');
                this.submitBtn.disabled = true;
                
                // Submit the form directly
                this.form.submit();
            }
            
            handleReset() {
                // Check if form has any values
                const hasValues = this.checkFormHasValues();
                
                if (hasValues) {
                    if (confirm('Are you sure you want to reset all form fields? This will clear all entered data.')) {
                        this.resetForm();
                        this.showAlert('Form has been reset to default values.', 'info', 3000);
                    }
                } else {
                    this.resetForm();
                    this.showAlert('Form has been reset to default values.', 'info', 3000);
                }
            }
            
            checkFormHasValues() {
                const questionField = document.getElementById('question');
                const answerField = document.getElementById('answer');
                const categoryField = document.getElementById('category');
                const orderField = document.getElementById('order');
                
                return (questionField && questionField.value.trim() !== '') ||
                       (answerField && answerField.value.trim() !== '') ||
                       (categoryField && categoryField.value !== 'gettingStarted') ||
                       (orderField && orderField.value !== '0');
            }
            
            resetForm() {
                // Reset form fields to default values
                const questionField = document.getElementById('question');
                const answerField = document.getElementById('answer');
                const categoryField = document.getElementById('category');
                const orderField = document.getElementById('order');
                const publishField = document.getElementById('is_published');
                
                if (questionField) questionField.value = this.defaultValues.question;
                if (answerField) answerField.value = this.defaultValues.answer;
                if (categoryField) categoryField.value = this.defaultValues.category;
                if (orderField) orderField.value = this.defaultValues.order;
                if (publishField) publishField.checked = this.defaultValues.is_published;
                
                // Clear validation errors
                this.clearFormErrors();
                
                // Update category badge
                this.updateCategoryBadge();
                
                // Reset form modified flag
                this.formModified = false;
            }
            
            updateCategoryBadge() {
                if (!this.categorySelect || !this.categoryBadge) return;
                
                const category = this.categorySelect.value || 'gettingStarted';
                let badgeClass = 'category-getting-started';
                let badgeText = 'Getting Started';
                
                switch(category) {
                    case 'gettingStarted':
                        badgeClass = 'category-getting-started';
                        badgeText = 'Getting Started';
                        break;
                    case 'calculations':
                        badgeClass = 'category-calculations';
                        badgeText = 'Calculations';
                        break;
                    case 'securityPrivacy':
                        badgeClass = 'category-security-privacy';
                        badgeText = 'Security & Privacy';
                        break;
                    case 'others':
                        badgeClass = 'category-other';
                        badgeText = 'Other';
                        break;
                    default:
                        badgeClass = 'category-getting-started';
                        badgeText = 'Getting Started';
                        break;
                }
                
                // Update badge
                this.categoryBadge.innerHTML = `<span class="${badgeClass}">${badgeText}</span>`;
            }
            
            showAlert(message, type = 'info', duration = 3000) {
                if (!this.dynamicAlertContainer) return;
                
                // Remove existing alerts (except form errors)
                const existingAlerts = this.dynamicAlertContainer.querySelectorAll('.alert-message');
                existingAlerts.forEach(alert => {
                    if (alert.id !== 'form-errors') {
                        alert.remove();
                    }
                });
                
                // Create alert element
                const alert = document.createElement('div');
                alert.className = `alert-message alert-${type}`;
                alert.setAttribute('role', 'alert');
                
                // Alert icon
                let iconPath = '';
                switch(type) {
                    case 'success':
                        iconPath = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                        break;
                    case 'error':
                        iconPath = 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                        break;
                    default:
                        iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                }
                
                alert.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
                    </svg>
                    <span>${message}</span>
                `;
                
                // Insert alert at the top of dynamic container
                this.dynamicAlertContainer.insertBefore(alert, this.dynamicAlertContainer.firstChild);
                
                // Auto-remove success/info alerts after duration
                if (type === 'success' || type === 'info') {
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                if (alert.parentNode) {
                                    alert.remove();
                                }
                            }, 300);
                        }
                    }, duration);
                }
                
                // Scroll to alert
                alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            setupAutoDismissAlerts() {
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert-message');
                    alerts.forEach(alert => {
                        if (alert.parentNode && alert.id !== 'form-errors') {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                if (alert.parentNode) {
                                    alert.remove();
                                }
                            }, 300);
                        }
                    });
                }, 5000);
            }
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Initialize Navigation Manager
                window.navigationManager = new NavigationManager();
                
                // Initialize Form Manager
                window.formManager = new FormManager();
                
                // Prevent FOUC (Flash of Unstyled Content)
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s ease';
                
                setTimeout(() => {
                    document.body.style.opacity = '1';
                }, 50);
                
            } catch (error) {
                console.error('Error initializing application:', error);
                // Show error message to user
                const alertContainer = document.getElementById('dynamic-alert-container');
                if (alertContainer) {
                    alertContainer.innerHTML = `
                        <div class="alert-message alert-error" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>An error occurred while loading the page. Please refresh and try again.</span>
                        </div>
                    `;
                }
            }
        });
    </script>
</body>
</html>