<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit FAQ • Neo Faraid Admin</title>
    <meta name="description" content="Edit FAQ entry in Neo Faraid Admin Panel">
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
        
        /* ===== ALERT CONTAINER ===== */
        .alert-container {
            position: fixed;
            top: 90px;
            right: 2.5rem;
            z-index: 9999;
            width: 400px;
            max-width: 90vw;
        }
        
        .alert-message {
            padding: 1.25rem 1.5rem;
            border-radius: var(--border-radius-md);
            margin-bottom: 1rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            border-left: 4px solid transparent;
        }
        
        .alert-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            z-index: -1;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            color: #155724;
            border-color: #28a745;
            border-left-color: #28a745;
        }
        
        .alert-success::before {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }
        
        .alert-error {
            background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%);
            color: #721c24;
            border-color: var(--danger-color);
            border-left-color: var(--danger-color);
        }
        
        .alert-error::before {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #ffffff 0%, #fffdf5 100%);
            color: #856404;
            border-color: var(--warning-color);
            border-left-color: var(--warning-color);
        }
        
        .alert-warning::before {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #ffffff 0%, #f8fdff 100%);
            color: #0c5460;
            border-color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .alert-info::before {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        }
        
        .alert-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            margin-top: 2px;
        }
        
        .alert-content {
            flex: 1;
        }
        
        .alert-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        
        .alert-message p {
            margin: 0;
            line-height: 1.5;
        }
        
        .alert-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            opacity: 0.7;
            transition: var(--transition);
            padding: 0;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .alert-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .alert-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .alert-progress-bar {
            height: 100%;
            animation: progressBar 5s linear forwards;
        }
        
        .alert-success .alert-progress-bar {
            background: #28a745;
        }
        
        .alert-error .alert-progress-bar {
            background: var(--danger-color);
        }
        
        .alert-warning .alert-progress-bar {
            background: var(--warning-color);
        }
        
        .alert-info .alert-progress-bar {
            background: var(--primary-color);
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
        
        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
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
        
        /* ===== FORM STYLES ===== */
        .form-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            padding: 2.5rem;
            transition: var(--transition);
            max-width: 600px;
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
        
        /* Select Styles */
        .form-select {
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
            cursor: pointer;
        }
        
        .form-select:hover {
            border-color: #b8c2cc;
        }
        
        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        }
        
        /* Radio Group Styles */
        .radio-group {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }
        
        .form-radio {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }
        
        .form-radio-input {
            width: 20px;
            height: 20px;
            border: 2px solid var(--light-border);
            border-radius: 50%;
            background-color: var(--white);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            appearance: none;
            -webkit-appearance: none;
        }
        
        .form-radio-input:checked {
            border-color: var(--primary-color);
        }
        
        .form-radio-input:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            border-radius: 50%;
        }
        
        .form-radio-label {
            font-weight: 500;
            color: var(--text-primary);
            cursor: pointer;
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
        
        /* ===== FORM ACTIONS ===== */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--light-border);
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
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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
            
            .alert-container {
                right: 2rem;
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
            
            .alert-container {
                width: 350px;
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
            
            .alert-container {
                top: 80px;
                right: 1.5rem;
                width: calc(100% - 3rem);
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
            
            .radio-group {
                flex-direction: column;
                gap: 1rem;
            }
            
            .confirmation-content {
                padding: 2rem;
            }
            
            .confirmation-actions {
                flex-direction: column;
            }
            
            .confirmation-btn {
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
            
            .alert-container {
                top: 70px;
                right: 1rem;
                width: calc(100% - 2rem);
            }
            
            .confirmation-content {
                padding: 1.5rem;
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
            
            .confirmation-title {
                font-size: 1.25rem;
            }
            
            .confirmation-icon {
                width: 48px;
                height: 48px;
            }
            
            .confirmation-icon svg {
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
                $authUser = auth()->user();
                $lastLogin = $authUser->last_login_at ? \Carbon\Carbon::parse($authUser->last_login_at)->diffForHumans() : 'Recently';
                $fullInitials = strtoupper(implode('', array_map(function($name) {
                    return substr($name, 0, 1);
                }, explode(' ', $authUser->name, 2))));
            @endphp
            
            <div class="user-profile-container" id="user-profile-container">
                <!-- User Profile Button -->
                <button class="user-profile-btn" 
                        id="user-profile-btn"
                        aria-label="User profile menu"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-user-role="{{ $authUser->role }}"
                        data-user-initials="{{ $fullInitials }}">
                    <div class="user-avatar" aria-hidden="true">
                        <span class="avatar-initials">{{ $fullInitials }}</span>
                        @if($authUser->role === 'admin')
                            <span class="admin-badge" aria-label="Administrator">A</span>
                        @endif
                    </div>
                    <div class="user-profile-info">
                        <span class="user-name">{{ $authUser->name }}</span>
                        <span class="user-role">{{ ucfirst($authUser->role) }}</span>
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
                            @if($authUser->role === 'admin')
                                <div class="admin-badge-large" aria-label="Administrator">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name">{{ $authUser->name }}</h3>
                            <p class="profile-email">{{ $authUser->email }}</p>
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

    <!-- Alert Container -->
    <div class="alert-container" id="alert-container">
        @if(session('success'))
        <div class="alert-message alert-success" data-auto-dismiss="2000" data-redirect="{{ route('admin.faq.index') }}">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <div class="alert-title">FAQ Updated Successfully</div>
                <p>The FAQ has been updated successfully.</p>
                <p style="margin-top: 5px; font-size: 0.9rem; opacity: 0.9;">Redirecting to FAQ list...</p>
            </div>
            <button class="alert-close" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="alert-progress">
                <div class="alert-progress-bar"></div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-message alert-error" data-auto-dismiss="5000">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <div class="alert-title">Update Failed</div>
                <p>{{ session('error') }}</p>
            </div>
            <button class="alert-close" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="alert-progress">
                <div class="alert-progress-bar"></div>
            </div>
        </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmation-modal">
        <div class="confirmation-content">
            <div class="confirmation-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="confirmation-title">Confirm Update</h3>
            <p class="confirmation-message">
                Are you sure you want to update this FAQ?<br>
                This action cannot be undone.
            </p>
            <div class="confirmation-actions">
                <button type="button" class="confirmation-btn cancel" id="modal-cancel-btn">Cancel</button>
                <button type="button" class="confirmation-btn confirm" id="modal-confirm-btn">Yes, Update</button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-title">
                <h1>Edit FAQ</h1>
                <p class="page-subtitle">Update frequently asked question details</p>
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
            <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST" id="edit-faq-form">
                @csrf
                @method('PUT')
                
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert-message alert-error" style="margin-bottom: 1.5rem; position: relative;">
                        <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="alert-content">
                            <div class="alert-title">Update Failed</div>
                            <div>
                                <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button class="alert-close" type="button" onclick="this.parentElement.remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif
                
                <!-- Question Field -->
                <div class="form-group">
                    <label for="question" class="form-label required">Question</label>
                    <input type="text" 
                           id="question" 
                           name="question" 
                           class="form-control @error('question') is-invalid @enderror"
                           value="{{ old('question', $faq->question) }}" 
                           placeholder="Enter the question"
                           required>
                    @error('question')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Answer Field -->
                <div class="form-group">
                    <label for="answer" class="form-label required">Answer</label>
                    <textarea 
                           id="answer" 
                           name="answer" 
                           class="form-control @error('answer') is-invalid @enderror"
                           placeholder="Enter the answer"
                           rows="6"
                           required>{{ old('answer', $faq->answer) }}</textarea>
                    @error('answer')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Category Field -->
                <div class="form-group">
                    <label for="category" class="form-label required">Category</label>
                    <select 
                        id="category" 
                        name="category" 
                        class="form-select @error('category') is-invalid @enderror"
                        required>
                        <option value="" disabled>Select a category</option>
                        <option value="gettingStarted" {{ old('category', $faq->category) == 'gettingStarted' ? 'selected' : '' }}>Getting Started</option>
                        <option value="calculations" {{ old('category', $faq->category) == 'calculations' ? 'selected' : '' }}>Calculations</option>
                        <option value="securityPrivacy" {{ old('category', $faq->category) == 'securityPrivacy' ? 'selected' : '' }}>Security & Privacy</option>
                        <option value="others" {{ old('category', $faq->category) == 'others' ? 'selected' : '' }}>Others</option>
                    </select>
                    @error('category')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Order Field -->
                <div class="form-group">
                    <label for="order" class="form-label required">Display Order</label>
                    <input type="number" 
                           id="order" 
                           name="order" 
                           class="form-control @error('order') is-invalid @enderror"
                           value="{{ old('order', $faq->order) }}" 
                           placeholder="Enter display order (lower numbers appear first)"
                           min="0"
                           required>
                    @error('order')
                        <div class="form-text text-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Publish Status -->
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" 
                               id="is_published" 
                               name="is_published" 
                               value="1"
                               class="form-check-input"
                               {{ old('is_published', $faq->is_published) ? 'checked' : '' }}>
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
                    <button type="submit" 
                            class="btn btn-primary"
                            id="submit-btn">
                        Update FAQ
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
                    activeNavItem: 'faq'
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

        // Alert Manager Class
        class AlertManager {
            constructor(containerId) {
                this.container = document.getElementById(containerId);
                if (!this.container) {
                    console.error('Alert container not found');
                    return;
                }
                this.setupAutoDismiss();
                this.setupCloseButtons();
            }

            setupAutoDismiss() {
                const alerts = this.container.querySelectorAll('.alert-message[data-auto-dismiss]');
                alerts.forEach(alert => {
                    const duration = parseInt(alert.getAttribute('data-auto-dismiss')) || 2000;
                    const redirectUrl = alert.getAttribute('data-redirect');
                    
                    setTimeout(() => {
                        this.dismissAlert(alert);
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        }
                    }, duration);
                });
            }

            setupCloseButtons() {
                this.container.addEventListener('click', (e) => {
                    if (e.target.closest('.alert-close')) {
                        const alert = e.target.closest('.alert-message');
                        this.dismissAlert(alert);
                    }
                });
            }

            dismissAlert(alert) {
                if (!alert) return;
                
                alert.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => {
                    if (alert.parentNode === this.container) {
                        this.container.removeChild(alert);
                    }
                }, 300);
            }

            showAlert(message, type = 'info', title = null, autoDismiss = 2000, redirectUrl = null) {
                const icons = {
                    success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    error: 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z',
                    info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                };

                const defaultTitles = {
                    success: 'Success',
                    error: 'Error',
                    warning: 'Warning',
                    info: 'Info'
                };

                const alert = document.createElement('div');
                alert.className = `alert-message alert-${type}`;
                if (autoDismiss > 0) {
                    alert.setAttribute('data-auto-dismiss', autoDismiss);
                }
                if (redirectUrl) {
                    alert.setAttribute('data-redirect', redirectUrl);
                }

                const redirectText = redirectUrl ? 
                    `<p style="margin-top: 5px; font-size: 0.9rem; opacity: 0.9;">Redirecting to FAQ list...</p>` : '';

                alert.innerHTML = `
                    <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icons[type] || icons.info}"/>
                    </svg>
                    <div class="alert-content">
                        <div class="alert-title">${title || defaultTitles[type] || 'Alert'}</div>
                        <p>${message}</p>
                        ${redirectText}
                    </div>
                    <button class="alert-close" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    ${autoDismiss > 0 ? `
                    <div class="alert-progress">
                        <div class="alert-progress-bar" style="animation-duration: ${autoDismiss}ms"></div>
                    </div>` : ''}
                `;

                this.container.insertBefore(alert, this.container.firstChild);

                if (autoDismiss > 0) {
                    setTimeout(() => {
                        this.dismissAlert(alert);
                        if (redirectUrl) {
                            window.location.href = redirectUrl;
                        }
                    }, autoDismiss);
                }

                return alert;
            }
        }

        // Form Validator Class
        class FormValidator {
            constructor(formId) {
                this.form = document.getElementById(formId);
                this.fields = {
                    question: this.form.querySelector('#question'),
                    answer: this.form.querySelector('#answer'),
                    category: this.form.querySelector('#category'),
                    order: this.form.querySelector('#order')
                };
                this.errors = [];
                this.init();
            }

            init() {
                this.fields.question?.addEventListener('blur', () => this.validateQuestion());
                this.fields.answer?.addEventListener('blur', () => this.validateAnswer());
                this.fields.category?.addEventListener('blur', () => this.validateCategory());
                this.fields.order?.addEventListener('blur', () => this.validateOrder());
            }

            validateQuestion() {
                const value = this.fields.question.value.trim();
                this.clearError(this.fields.question);
                
                if (!value) {
                    this.addError(this.fields.question, 'Question is required');
                    return false;
                }
                
                if (value.length < 5) {
                    this.addError(this.fields.question, 'Question must be at least 5 characters');
                    return false;
                }
                
                return true;
            }

            validateAnswer() {
                const value = this.fields.answer.value.trim();
                this.clearError(this.fields.answer);
                
                if (!value) {
                    this.addError(this.fields.answer, 'Answer is required');
                    return false;
                }
                
                if (value.length < 10) {
                    this.addError(this.fields.answer, 'Answer must be at least 10 characters');
                    return false;
                }
                
                return true;
            }

            validateCategory() {
                const value = this.fields.category.value;
                this.clearError(this.fields.category);
                
                if (!value) {
                    this.addError(this.fields.category, 'Please select a category');
                    return false;
                }
                
                return true;
            }

            validateOrder() {
                const value = this.fields.order.value.trim();
                this.clearError(this.fields.order);
                
                if (!value) {
                    this.addError(this.fields.order, 'Display order is required');
                    return false;
                }
                
                const orderNum = parseInt(value);
                if (isNaN(orderNum) || orderNum < 0) {
                    this.addError(this.fields.order, 'Display order must be a positive number or zero');
                    return false;
                }
                
                return true;
            }

            addError(field, message) {
                field.classList.add('is-invalid');
                let errorElement = field.nextElementSibling;
                
                if (!errorElement || !errorElement.classList.contains('text-error')) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'form-text text-error';
                    field.parentNode.insertBefore(errorElement, field.nextSibling);
                }
                
                errorElement.textContent = message;
            }

            clearError(field) {
                field.classList.remove('is-invalid');
                const errorElement = field.nextElementSibling;
                if (errorElement && errorElement.classList.contains('text-error')) {
                    errorElement.remove();
                }
            }

            addRadioError(fieldName, message) {
                const container = this.form.querySelector(`input[name="${fieldName}"]`).closest('.form-group');
                let errorElement = container.querySelector('.text-error');
                
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'form-text text-error';
                    container.appendChild(errorElement);
                }
                
                errorElement.textContent = message;
            }

            clearRadioError(fieldName) {
                const container = this.form.querySelector(`input[name="${fieldName}"]`).closest('.form-group');
                const errorElement = container.querySelector('.text-error');
                if (errorElement) {
                    errorElement.remove();
                }
            }

            validateAll() {
                this.errors = [];
                
                const questionValid = this.validateQuestion();
                const answerValid = this.validateAnswer();
                const categoryValid = this.validateCategory();
                const orderValid = this.validateOrder();
                
                return questionValid && answerValid && categoryValid && orderValid;
            }

            getErrors() {
                return this.errors;
            }
        }

        // Confirmation Modal Class
        class ConfirmationModal {
            constructor(modalId) {
                this.modal = document.getElementById(modalId);
                this.confirmBtn = document.getElementById('modal-confirm-btn');
                this.cancelBtn = document.getElementById('modal-cancel-btn');
                this.originalFormSubmit = null;
                this.pendingForm = null;
                
                this.init();
            }

            init() {
                // Close modal when clicking outside
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal) {
                        this.hide();
                    }
                });

                // Close modal with cancel button
                this.cancelBtn.addEventListener('click', () => this.hide());

                // Close modal with Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                        this.hide();
                    }
                });
            }

            show(form) {
                this.pendingForm = form;
                this.modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Focus the confirm button for accessibility
                setTimeout(() => {
                    this.confirmBtn.focus();
                }, 100);
            }

            hide() {
                this.modal.classList.remove('active');
                document.body.style.overflow = '';
                this.pendingForm = null;
            }

            setConfirmAction(callback) {
                this.confirmBtn.addEventListener('click', callback, { once: true });
            }
        }

        // Main Application
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize managers
            const navigationManager = new NavigationManager();
            const alertManager = new AlertManager('alert-container');
            const formValidator = new FormValidator('edit-faq-form');
            const confirmationModal = new ConfirmationModal('confirmation-modal');
            
            const form = document.getElementById('edit-faq-form');
            const submitBtn = document.getElementById('submit-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            
            let formModified = false;
            
            // Track form modifications
            form.querySelectorAll('input, textarea, select').forEach(field => {
                const originalValue = field.value;
                
                field.addEventListener('input', () => {
                    formModified = field.value !== originalValue;
                });
                
                field.addEventListener('change', () => {
                    formModified = field.value !== originalValue;
                });
            });
            
            // Form submission with confirmation
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!formValidator.validateAll()) {
                    alertManager.showAlert('Please fix the errors in the form before submitting.', 'error', 'Validation Error', 5000);
                    return;
                }
                
                // Show confirmation modal
                confirmationModal.show(this);
                
                // Set confirm action
                confirmationModal.setConfirmAction(() => {
                    // Show loading state
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    
                    // Hide modal
                    confirmationModal.hide();
                    
                    // Submit the form
                    form.submit();
                });
            });
            
            // Cancel button navigation
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function(e) {
                    if (formModified) {
                        e.preventDefault();
                        if (confirm('You have unsaved changes. Are you sure you want to cancel?')) {
                            window.location.href = this.href;
                        }
                    }
                });
            }
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + S to save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    submitBtn.click();
                }
                
                // Escape to cancel if form not modified
                if (e.key === 'Escape' && !formModified) {
                    window.location.href = cancelBtn.href;
                }
            });
            
            // Add button hover effects
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
            
            // Prevent FOUC (Flash of Unstyled Content)
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.3s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 50);
        });
    </script>
</body>
</html>