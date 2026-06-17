<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User • Neo Faraid Admin</title>
    <meta name="description" content="Edit user details in Neo Faraid Admin Panel">
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
            filter: drop-shadow(0 2px 4px rgba(255, 215, 0, 0.3));
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
            color: rgba(255, 255, 255, 0.95);
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
            background: rgba(255, 255, 255, 0.15);
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: var(--accent-color);
            border-color: rgba(255, 215, 0, 0.3);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 100px;
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            text-decoration: none;
        }

        .user-profile-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.15);
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
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
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
            background: rgba(255, 255, 255, 0.2);
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

        .page-title h1 .highlight {
            color: var(--accent-color);
        }

        .page-subtitle {
            color: var(--text-light);
            font-weight: 400;
            font-size: 0.95rem;
        }

        /* ===== BADGES ===== */
        .badge-group {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: rgba(26, 95, 180, 0.08);
            border: 1px solid rgba(26, 95, 180, 0.15);
            border-radius: var(--border-radius-xl);
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-primary);
        }

        .badge-pill .pill-icon {
            width: 16px;
            height: 16px;
            color: var(--primary-color);
        }

        .badge-pill.user-edit {
            background: rgba(255, 215, 0, 0.12);
            border-color: rgba(255, 215, 0, 0.25);
            color: var(--primary-dark);
        }

        .badge-pill.user-edit .pill-icon {
            color: var(--accent-color);
        }

        /* ===== FORM CONTAINER ===== */
        .form-container {
            max-width: 820px;
            margin: 0 auto;
        }

        .form-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: var(--transition);
        }

        .form-card:hover {
            box-shadow: var(--shadow-lg);
        }

        .form-card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-card-header svg {
            width: 24px;
            height: 24px;
            stroke-width: 2;
        }

        .form-card-body {
            padding: 2rem;
        }

        /* ===== FORM STYLES ===== */
        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: var(--danger-color);
            font-weight: 600;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-sm);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--white);
            color: var(--text-primary);
            font-family: inherit;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
            outline: none;
        }

        .form-input.is-invalid {
            border-color: var(--danger-color);
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .form-input[readonly] {
            background: var(--light-bg);
            cursor: not-allowed;
            color: var(--text-light);
        }

        textarea.form-input {
            resize: vertical;
            min-height: 100px;
        }

        .form-hint {
            color: var(--text-light);
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.85rem;
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .error-message svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        /* Radio Group */
        .radio-group {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding-top: 0.25rem;
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
            flex-shrink: 0;
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

        .form-radio-input:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }

        .form-radio-label {
            font-weight: 500;
            color: var(--text-primary);
            cursor: pointer;
        }

        /* Divider */
        .form-divider {
            border: none;
            border-top: 2px solid var(--light-border);
            margin: 2rem 0;
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-section-title svg {
            width: 22px;
            height: 22px;
            color: var(--primary-color);
        }

        /* Info Note */
        .info-note {
            background: #e8f1fd;
            border-left: 4px solid var(--primary-color);
            padding: 0.9rem 1.25rem;
            border-radius: var(--border-radius-sm);
            margin-bottom: 1.75rem;
            font-size: 0.9rem;
            color: var(--text-primary);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            line-height: 1.5;
        }

        .info-note svg {
            flex-shrink: 0;
            margin-top: 0.1rem;
            width: 20px;
            height: 20px;
            color: var(--primary-color);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--light-border);
        }

        .btn {
            padding: 0.85rem 2rem;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: var(--transition);
            border: none;
            font-size: 0.95rem;
            font-family: inherit;
            flex: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
            color: var(--primary-dark);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .btn-primary span {
            position: relative;
            z-index: 2;
        }

        .btn-hover-effect {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transition: left 0.6s ease;
            z-index: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-accent);
        }

        .btn-primary:hover .btn-hover-effect {
            left: 100%;
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary:active {
            transform: translateY(0);
        }

        /* ===== ALERT MESSAGES ===== */
        .alert-message {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        .alert-message ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .alert-message ul li {
            margin-bottom: 0.2rem;
        }

        .alert-message ul li:last-child {
            margin-bottom: 0;
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

        .alert-message svg {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            margin-top: 0.1rem;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        /* ===== DROPDOWN BACKDROP ===== */
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .nav-links { overflow-x: auto; padding-bottom: 5px; }
            .nav-link { white-space: nowrap; font-size: 0.9rem; padding: 0.75rem 1rem; }
            .admin-main { padding: 2rem; }
        }

        @media (max-width: 992px) {
            .admin-top-nav { padding: 0 1rem; }
            .nav-links { gap: 0.15rem; }
            .nav-link { padding: 0.75rem; font-size: 0.85rem; }
            .nav-link .nav-link-text { display: none; }
            .nav-link svg { margin-right: 0; }
            .admin-logo-text { font-size: 1.2rem; }
            .user-profile-btn { min-width: auto; padding: 0.5rem; }
            .user-profile-info { display: none; }
        }

        @media (max-width: 768px) {
            .admin-top-nav { height: 60px; }
            .nav-left { gap: 1rem; }
            .admin-logo-text { display: none; }
            .nav-links { margin-left: auto; }
            .admin-main { padding: 1.5rem; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .badge-group { width: 100%; }
            .badge-pill { flex: 1; justify-content: center; }
            .form-card-body { padding: 1.5rem; }
            .form-card-header { padding: 1.25rem 1.5rem; }
            .grid-2 { grid-template-columns: 1fr; gap: 0; }
            .form-actions { flex-direction: column; }
            .btn { flex: none; width: 100%; }
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
            .form-section-title { font-size: 1.05rem; }
            .info-note { font-size: 0.85rem; padding: 0.75rem 1rem; }
        }

        @media (max-width: 480px) {
            .admin-main { padding: 1rem; }
            .form-card-body { padding: 1.25rem; }
            .form-card-header { padding: 1rem 1.25rem; }
            .form-card-header h2 { font-size: 1.05rem; }
            .page-title h1 { font-size: 1.5rem; }
            .badge-pill { font-size: 0.8rem; padding: 0.4rem 1rem; }
            .nav-link { padding: 0.5rem; }
            .nav-link svg { width: 18px; height: 18px; }
            .admin-logo-icon { width: 28px; height: 28px; }
            .user-avatar { width: 32px; height: 32px; }
            .avatar-initials { font-size: 0.9rem; }
            .form-radio-group { gap: 1rem; }
            .form-input { padding: 0.85rem 1rem; font-size: 0.95rem; }
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

        button:focus, a:focus, input:focus, select:focus, textarea:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }

        /* ===== REDUCED MOTION ===== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .admin-logo-icon, .user-avatar { animation: none; }
            .user-status.active::before { animation: none; }
        }
    </style>
</head>
<body>
    <!-- Enhanced Top Navigation -->
    <nav class="admin-top-nav" role="navigation" aria-label="Admin navigation">
        <div class="nav-left">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-logo" aria-label="Neo Faraid Admin Home">
                <i class="fas fa-layer-group" style="color: var(--accent-color); font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3)); animation: logoFloat 4s ease-in-out infinite;"></i>
                <span class="admin-logo-text">Neo Faraid Admin</span>
            </a>
        </div>

        <!-- User Profile Section -->
        <div class="nav-right">
            <?php
            $authUser = auth()->user();
            $lastLogin = $authUser->last_login_at ? \Carbon\Carbon::parse($authUser->last_login_at)->diffForHumans() : 'Recently';
            $fullInitials = strtoupper(implode('', array_map(function($name) {
                return substr($name, 0, 1);
            }, explode(' ', $authUser->name, 2))));
            ?>

            <div class="user-profile-container" id="user-profile-container">
                <!-- User Profile Button -->
                <button class="user-profile-btn"
                    id="user-profile-btn"
                    aria-label="User profile menu"
                    aria-expanded="false"
                    aria-haspopup="true"
                    data-user-role="<?php echo e($authUser->role); ?>"
                    data-user-initials="<?php echo e($fullInitials); ?>">
                    <div class="user-avatar" aria-hidden="true">
                        <span class="avatar-initials"><?php echo e($fullInitials); ?></span>
                        <?php if($authUser->role === 'admin'): ?>
                        <span class="admin-badge" aria-label="Administrator">A</span>
                        <?php endif; ?>
                    </div>
                    <div class="user-profile-info">
                        <span class="user-name"><?php echo e($authUser->name); ?></span>
                        <span class="user-role"><?php echo e(ucfirst($authUser->role)); ?></span>
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
                            <span class="avatar-initials-large"><?php echo e($fullInitials); ?></span>
                            <?php if($authUser->role === 'admin'): ?>
                            <div class="admin-badge-large" aria-label="Administrator">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name"><?php echo e($authUser->name); ?></h3>
                            <p class="profile-email"><?php echo e($authUser->email); ?></p>
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

                    <!-- Navigation Links -->
                    <div class="dropdown-section">
                        <h4 class="section-title">Navigation</h4>
                        <nav class="dropdown-nav" role="navigation" aria-label="Admin navigation">
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/users*') ? 'active' : ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                                <span>Manage Users</span>
                            </a>
                            <a href="<?php echo e(route('admin.feedback.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/feedback*') ? 'active' : ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                <span>Manage Feedback</span>
                            </a>
                            <a href="<?php echo e(route('admin.faq.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/faq*') ? 'active' : ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Manage FAQ</span>
                            </a>
                            <a href="<?php echo e(route('admin.calculations.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/calculations*') ? 'active' : ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                <span>Manage Calculations</span>
                            </a>
                            <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="dropdown-nav-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Manage Instant Estate</span>
                            </a>
                            <a href="<?php echo e(route('admin.estate-setup.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/estate-setup*') ? 'active' : ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>Manage Estate Planning</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Account Settings -->
                    <div class="dropdown-section">
                        <h4 class="section-title">Account</h4>
                        <div class="account-links">
                            <a href="<?php echo e(route('profile.edit')); ?>"
                                class="account-link <?php echo e(request()->is('profile*') ? 'active' : ''); ?>"
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
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="logout-btn" role="menuitem" aria-label="Log out from admin panel">
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
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-title">
                <h1>Edit User</h1>
                <p class="page-subtitle">Update user details and account settings</p>
            </div>
            <div class="badge-group">
                <span class="badge-pill user-edit">
                    <svg class="pill-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editing: <?php echo e($user->name); ?>

                </span>
                <span class="badge-pill">
                    <svg class="pill-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    User #<?php echo e($user->id); ?>

                </span>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if(session('success')): ?>
        <div class="alert-message alert-success" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert-message alert-error" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-card">
                <div class="form-card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Personal Information
                    </h2>
                </div>

                <div class="form-card-body">
                    <div class="info-note">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>All fields are editable. Please ensure the information is accurate before saving.</span>
                    </div>

                    <form method="POST" action="<?php echo e(route('admin.users.update', $user->id)); ?>" id="edit-user-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('name', $user->name)); ?>"
                                    placeholder="Enter full name" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="nric" class="form-label">NRIC/Passport Number <span class="required">*</span></label>
                                <input type="text" id="nric" name="nric"
                                    class="form-input <?php $__errorArgs = ['nric'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('nric', $user->nric ?? '')); ?>"
                                    placeholder="Enter NRIC or Passport" required>
                                <?php $__errorArgs = ['nric'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="dob" class="form-label">Date of Birth <span class="required">*</span></label>
                                <input type="date" id="dob" name="date_of_birth"
                                    class="form-input <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '')); ?>" required>
                                <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Gender <span class="required">*</span></label>
                                <div class="radio-group">
                                    <div class="form-radio">
                                        <input type="radio" id="gender_male" name="gender" value="male"
                                            class="form-radio-input"
                                            <?php echo e(old('gender', $user->gender) == 'male' ? 'checked' : ''); ?>>
                                        <label for="gender_male" class="form-radio-label">Male</label>
                                    </div>
                                    <div class="form-radio">
                                        <input type="radio" id="gender_female" name="gender" value="female"
                                            class="form-radio-input"
                                            <?php echo e(old('gender', $user->gender) == 'female' ? 'checked' : ''); ?>>
                                        <label for="gender_female" class="form-radio-label">Female</label>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email"
                                class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('email', $user->email)); ?>"
                                placeholder="Enter email address" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Updated Phone Field with new format hint -->
                        <div class="form-group">
                            <label for="contact_phone" class="form-label">Contact Phone <span class="required">*</span></label>
                            <input type="tel" name="contact_phone" id="contact_phone"
                                class="form-input <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('contact_phone', $user->contact_phone)); ?>"
                                required placeholder="01X-XXXXXXX">
                            <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Residential Address <span class="required">*</span></label>
                            <textarea name="address" id="address" rows="3"
                                class="form-input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required placeholder="Enter full residential address"><?php echo e(old('address', $user->address)); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <hr class="form-divider">

                        <div class="form-section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Account Settings
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Role <span class="required">*</span></label>
                                <div class="radio-group">
                                    <div class="form-radio">
                                        <input type="radio" id="role_user" name="role" value="user"
                                            class="form-radio-input"
                                            <?php echo e(old('role', $user->role) == 'user' ? 'checked' : ''); ?>>
                                        <label for="role_user" class="form-radio-label">User</label>
                                    </div>
                                    <div class="form-radio">
                                        <input type="radio" id="role_admin" name="role" value="admin"
                                            class="form-radio-input"
                                            <?php echo e(old('role', $user->role) == 'admin' ? 'checked' : ''); ?>>
                                        <label for="role_admin" class="form-radio-label">Admin</label>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Status <span class="required">*</span></label>
                                <div class="radio-group">
                                    <div class="form-radio">
                                        <input type="radio" id="status_active" name="status" value="active"
                                            class="form-radio-input"
                                            <?php echo e(old('status', $user->status) == 'active' ? 'checked' : ''); ?>>
                                        <label for="status_active" class="form-radio-label">Active</label>
                                    </div>
                                    <div class="form-radio">
                                        <input type="radio" id="status_inactive" name="status" value="inactive"
                                            class="form-radio-input"
                                            <?php echo e(old('status', $user->status) == 'inactive' ? 'checked' : ''); ?>>
                                        <label for="status_inactive" class="form-radio-label">Inactive</label>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="error-message">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?php echo e($message); ?>

                                </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span>Update User</span>
                                <div class="btn-hover-effect"></div>
                            </button>
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // ===== NAVIGATION MANAGER =====
        class NavigationManager {
            constructor() {
                this.selectors = {
                    userProfileBtn: '#user-profile-btn',
                    userProfileDropdown: '#user-profile-dropdown',
                    logoutForm: '.logout-form'
                };
                this.state = { isDropdownOpen: false };
                this.elements = {};
                this.init();
            }

            init() {
                this.cacheElements();
                this.bindEvents();
            }

            cacheElements() {
                Object.keys(this.selectors).forEach(key => {
                    this.elements[key] = document.querySelector(this.selectors[key]);
                });
            }

            bindEvents() {
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.toggleDropdown();
                    });
                }
                document.addEventListener('click', (e) => this.handleOutsideClick(e));
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.state.isDropdownOpen) {
                        this.closeDropdown();
                    }
                });
            }

            toggleDropdown() {
                this.state.isDropdownOpen = !this.state.isDropdownOpen;
                if (this.state.isDropdownOpen) {
                    this.openDropdown();
                } else {
                    this.closeDropdown();
                }
            }

            openDropdown() {
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.style.display = 'block';
                    setTimeout(() => {
                        this.elements.userProfileDropdown.classList.add('active');
                    }, 10);
                }
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.setAttribute('aria-expanded', 'true');
                    const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
                    if (chevron) chevron.style.transform = 'rotate(180deg)';
                }
                if (window.innerWidth < 768) this.addBackdrop();
            }

            closeDropdown() {
                this.state.isDropdownOpen = false;
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.classList.remove('active');
                    setTimeout(() => {
                        if (!this.state.isDropdownOpen) {
                            this.elements.userProfileDropdown.style.display = 'none';
                        }
                    }, 300);
                }
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.setAttribute('aria-expanded', 'false');
                    const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                }
                this.removeBackdrop();
            }

            handleOutsideClick(e) {
                if (this.state.isDropdownOpen &&
                    this.elements.userProfileDropdown &&
                    !this.elements.userProfileDropdown.contains(e.target) &&
                    !this.elements.userProfileBtn.contains(e.target)) {
                    this.closeDropdown();
                }
            }

            addBackdrop() {
                let backdrop = document.querySelector('.dropdown-backdrop');
                if (!backdrop) {
                    backdrop = document.createElement('div');
                    backdrop.className = 'dropdown-backdrop';
                    document.body.appendChild(backdrop);
                    setTimeout(() => backdrop.style.opacity = '1', 10);
                    backdrop.addEventListener('click', () => this.closeDropdown());
                }
            }

            removeBackdrop() {
                const backdrop = document.querySelector('.dropdown-backdrop');
                if (backdrop) {
                    backdrop.style.opacity = '0';
                    setTimeout(() => {
                        if (backdrop.parentNode) backdrop.parentNode.removeChild(backdrop);
                    }, 300);
                }
            }
        }

        // ===== FORM HANDLER =====
        class EditUserForm {
            constructor() {
                this.phoneInput = document.getElementById('contact_phone');
                this.form = document.getElementById('edit-user-form');
                this.submitBtn = document.getElementById('submitBtn');
                this.init();
            }

            init() {
                this.setupPhoneFormatting();
                this.setupFormSubmission();
                this.setupAutoDismissAlerts();
            }

            setupPhoneFormatting() {
                if (!this.phoneInput) return;

                // Format initial value
                let initialValue = this.phoneInput.value.replace(/[^0-9]/g, '');
                if (initialValue.length >= 3 && initialValue.length <= 11) {
                    if (initialValue.startsWith('01')) {
                        initialValue = initialValue.substring(0, 3) + '-' + initialValue.substring(3);
                    }
                }
                this.phoneInput.value = initialValue;

                // Format on input
                this.phoneInput.addEventListener('input', function(e) {
                    let val = this.value.replace(/[^0-9]/g, '');
                    if (val.length >= 3 && val.length <= 11) {
                        if (val.startsWith('01')) {
                            val = val.substring(0, 3) + '-' + val.substring(3);
                        }
                    }
                    if (val.length > 11) val = val.substring(0, 11);
                    this.value = val;
                });

                // Prevent invalid characters
                this.phoneInput.addEventListener('keydown', function(e) {
                    if (e.key === '-' || e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '.') {
                        e.preventDefault();
                    }
                });
            }

            setupFormSubmission() {
                if (!this.form || !this.submitBtn) return;

                this.form.addEventListener('submit', () => {
                    this.submitBtn.disabled = true;
                    this.submitBtn.querySelector('span').innerHTML =
                        '<svg class="animate-spin" viewBox="0 0 20 20" width="18" height="18" style="margin-right: 8px; vertical-align: middle;">' +
                        '<path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z" fill="currentColor"/>' +
                        '</svg> Updating...';
                });
            }

            setupAutoDismissAlerts() {
                const alerts = document.querySelectorAll('.alert-message');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        setTimeout(() => {
                            if (alert.parentNode) alert.remove();
                        }, 300);
                    }, 5000);
                });
            }
        }

        // ===== INIT =====
        document.addEventListener('DOMContentLoaded', function() {
            try {
                new NavigationManager();
                new EditUserForm();

                // Prevent FOUC
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    document.body.style.opacity = '1';
                }, 50);

                // Add spin animation style if missing
                if (!document.querySelector('#spin-style')) {
                    const style = document.createElement('style');
                    style.id = 'spin-style';
                    style.textContent = `
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        .animate-spin {
                            animation: spin 1s linear infinite;
                        }
                    `;
                    document.head.appendChild(style);
                }
            } catch (error) {
                console.error('Error initializing:', error);
            }
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>