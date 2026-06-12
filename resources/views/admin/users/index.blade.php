<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users • Neo Faraid Admin</title>
    <meta name="description" content="Manage system users in Neo Faraid Admin Panel">
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
            --info-color: #138496; /* Updated show button color */
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
        
        /* ===== BUTTONS ===== */
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
        
        /* ===== USER DETAILS MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
            padding: 1rem;
        }
        
        .modal-content {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.4s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: var(--white);
            width: 40px;
            height: 40px;
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
            padding: 2rem;
        }
        
        .user-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .detail-group {
            background: var(--light-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius-md);
            border-left: 4px solid var(--primary-color);
        }
        
        .detail-label {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .detail-value {
            font-size: 1.125rem;
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .user-info-container {
            grid-column: 1 / -1;
            margin-top: 1rem;
        }
        
        .user-info-avatar {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .avatar-large {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 2rem;
            box-shadow: var(--shadow-md);
        }
        
        .user-info-text h3 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .user-info-text p {
            color: var(--text-light);
        }
        
        .modal-footer {
            padding: 1.5rem 2rem;
            background: var(--light-bg);
            border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid var(--light-border);
        }
        
        /* ===== MESSAGES ===== */
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
        
        /* ===== TABLE STYLES ===== */
        .table-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: var(--transition);
        }
        
        .table-container:hover {
            box-shadow: var(--shadow-lg);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        th {
            padding: 1.25rem;
            text-align: left;
            font-weight: 600;
            color: var(--white);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        th:first-child {
            border-top-left-radius: var(--border-radius-lg);
        }
        
        th:last-child {
            border-top-right-radius: var(--border-radius-lg);
        }
        
        td {
            padding: 1.25rem;
            border-bottom: 1px solid var(--light-border);
            vertical-align: middle;
            color: var(--text-primary);
            font-weight: 400;
        }
        
        tbody tr {
            transition: var(--transition);
        }
        
        tbody tr:hover {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(45, 122, 214, 0.05) 100%);
            transform: translateX(5px);
        }
        
        .user-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar-table {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name-table {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .user-id {
            font-size: 0.85rem;
            color: var(--text-light);
        }
        
        /* Status Badges */
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: var(--border-radius-xl);
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-active {
            background: linear-gradient(135deg, rgba(37, 211, 102, 0.1) 0%, rgba(33, 136, 56, 0.1) 100%);
            color: var(--success-color);
            border: 1px solid rgba(37, 211, 102, 0.2);
        }
        
        .status-inactive {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(200, 35, 51, 0.1) 100%);
            color: var(--danger-color);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        
        /* Role Badges */
        .role-badge {
            padding: 0.4rem 1rem;
            border-radius: var(--border-radius-xl);
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .role-user {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 86, 179, 0.1) 100%);
            color: #0069d9;
            border: 1px solid rgba(0, 123, 255, 0.2);
        }
        
        .role-admin {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(224, 168, 0, 0.1) 100%);
            color: var(--warning-color);
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        
        .role-super-admin {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.1) 0%, rgba(13, 45, 92, 0.1) 100%);
            color: var(--primary-dark);
            border: 1px solid rgba(26, 95, 180, 0.2);
        }
        
        /* ===== ACTION BUTTONS ===== */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        /* SHOW BUTTON */
        .btn-show {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-show:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-edit {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-delete.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .btn-show svg,
        .btn-edit svg,
        .btn-delete svg {
            width: 16px;
            height: 16px;
            stroke-width: 2.5;
        }
        
        .btn-modal {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-modal-close {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        
        .btn-modal-close:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        /* ===== DELETE CONFIRMATION MODAL ===== */
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2100;
            animation: fadeIn 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .delete-modal {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
            transform: translateY(-20px);
            animation: modalSlideIn 0.3s ease forwards;
        }
        
        @keyframes modalSlideIn {
            to {
                transform: translateY(0);
            }
        }
        
        .modal-header-delete {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: var(--white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }
        
        .modal-header-delete h3 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.3rem;
        }
        
        .modal-body-delete {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--light-border);
        }
        
        .modal-body-delete p {
            margin-bottom: 1rem;
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .user-details-delete {
            background: var(--light-bg);
            padding: 1rem;
            border-radius: var(--border-radius-md);
            margin-top: 1rem;
        }
        
        .user-details-delete strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .modal-footer-delete {
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        .btn-cancel {
            padding: 0.75rem 1.5rem;
            background: var(--light-bg);
            color: var(--text-primary);
            border: 1px solid var(--light-border);
            border-radius: var(--border-radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-cancel:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }
        
        .btn-confirm-delete {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-confirm-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.25);
        }
        
        /* ===== SEARCH BOX ===== */
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background: var(--white);
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-md);
            padding: 0.5rem 1rem;
            width: 320px;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        
        .search-box:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        }
        
        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            padding: 0.5rem;
            font-family: var(--font-family);
            font-weight: 400;
            color: var(--text-primary);
            background: transparent;
        }
        
        .search-box input::placeholder {
            color: var(--text-light);
        }
        
        .search-box svg {
            width: 20px;
            height: 20px;
            color: var(--text-light);
        }
        
        /* ===== NO DATA ===== */
        .no-data {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
        }
        
        .no-data svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            color: var(--light-border);
        }
        
        /* ===== MODERN PAGINATION ===== */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: var(--white);
            border-top: 1px solid var(--light-border);
        }
        
        .pagination-info {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .pagination-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .pagination-arrow {
            padding: 0.5rem 1rem;
            border: 1px solid var(--light-border);
            border-radius: var(--border-radius-sm);
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--white);
        }
        
        .pagination-arrow:hover:not(.disabled) {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.1) 0%, rgba(45, 122, 214, 0.1) 100%);
            border-color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }
        
        .pagination-arrow.disabled {
            color: var(--text-light);
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        .pagination-arrow svg {
            width: 16px;
            height: 16px;
        }
        
        .pagination-numbers {
            display: flex;
            gap: 0.25rem;
        }
        
        .pagination-number {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--light-border);
            border-radius: var(--border-radius-sm);
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            transition: var(--transition);
            min-width: 40px;
            text-align: center;
        }
        
        .pagination-number:hover:not(.active) {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.1) 0%, rgba(45, 122, 214, 0.1) 100%);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }
        
        .pagination-number.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }
        
        .pagination-ellipsis {
            padding: 0.5rem 0.5rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* ===== LOADING STATES ===== */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius-lg);
            z-index: 1;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--light-border);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
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
            
            .search-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                width: 100%;
            }
            
            .table-container {
                border-radius: var(--border-radius-md);
                overflow-x: auto;
            }
            
            table {
                min-width: 900px;
            }
            
            .action-buttons {
                flex-direction: column;
                min-width: 140px;
            }
            
            .pagination-wrapper {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .pagination-info {
                text-align: center;
            }
            
            .pagination-container {
                justify-content: center;
            }
            
            .modal-footer,
            .modal-footer-delete {
                flex-direction: column;
            }
            
            .modal-footer button,
            .modal-footer-delete button {
                width: 100%;
            }
            
            .user-detail-grid {
                grid-template-columns: 1fr;
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
            
            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.875rem;
            }
            
            .btn-show,
            .btn-edit,
            .btn-delete {
                padding: 0.4rem 0.75rem;
                font-size: 0.75rem;
            }
            
            th, td {
                padding: 0.75rem;
            }
            
            .status-badge,
            .role-badge {
                font-size: 0.75rem;
                padding: 0.3rem 0.75rem;
            }
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .pagination-arrow {
                padding: 0.4rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .pagination-number {
                padding: 0.4rem 0.6rem;
                min-width: 35px;
                font-size: 0.875rem;
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
            
            .pagination-arrow span {
                display: none;
            }
            
            .pagination-arrow {
                padding: 0.5rem;
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
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Page Header -->
        <header class="page-header">
            <div class="page-title">
                <h1>Manage Users</h1>
                <p class="page-subtitle">View and manage all system users</p>
            </div>
        </header>
        
        <!-- Alert Messages -->
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
        
        <!-- Search Section -->
        <div class="search-container">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       placeholder="Search users by name, email, or role..." 
                       id="search-input"
                       value="{{ request('search') }}">
            </div>
        </div>
        
        <!-- Table Container -->
        <div class="table-container">
            @if($users->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        @foreach($users as $user)
                            @php
                                $status = $user->display_status ?? 
                                        ($user->last_login_at && $user->last_login_at >= now()->subDays(30) ? 'active' : 'inactive');
                                $userInitials = strtoupper(substr($user->name, 0, 1));
                            @endphp
                            <tr class="user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ strtolower($user->role) }}" data-id="{{ $user->id }}">
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-table">
                                            {{ $userInitials }}
                                        </div>
                                        <div class="user-info">
                                            <span class="user-name-table">{{ $user->name }}</span>
                                            <span class="user-id">ID: {{ $user->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="role-badge role-{{ $user->role }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $user->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 0.85rem; color: var(--text-light);">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-show show-user-btn" 
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                data-user-email="{{ $user->email }}"
                                                data-user-role="{{ $user->role }}"
                                                data-user-status="{{ $status }}"
                                                data-user-initials="{{ $userInitials }}"
                                                data-user-created="{{ $user->created_at->format('M d, Y h:i A') }}"
                                                data-user-updated="{{ $user->updated_at->format('M d, Y h:i A') }}"
                                                data-user-last-login="{{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Show
                                        </button>
                                        
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-form" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-delete delete-btn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($users->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>
                    <div class="pagination-container">
                        <nav class="pagination" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            @if($users->onFirstPage())
                                <span class="pagination-arrow disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span>Previous</span>
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="pagination-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span>Previous</span>
                                </a>
                            @endif
                            
                            {{-- Pagination Elements --}}
                            <div class="pagination-numbers">
                                @foreach ($users->links()->elements[0] as $page => $url)
                                    @if($page == $users->currentPage())
                                        <span class="pagination-number active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>
                            
                            {{-- Next Page Link --}}
                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="pagination-arrow">
                                    <span>Next</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="pagination-arrow disabled">
                                    <span>Next</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
                @endif
                
            @else
                <div class="no-data">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                    </svg>
                    <h3>No users found</h3>
                    <p>There are no users to display.</p>
                </div>
            @endif
        </div>
    </main>

    <!-- User Details Modal -->
    <div id="userDetailsModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                    </svg>
                    User Details
                </h2>
                <button class="modal-close" id="closeUserModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div id="userModalLoading" style="text-align: center; padding: 3rem;">
                    <div style="margin-bottom: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" style="color: var(--primary-color); animation: spin 1s linear infinite;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <p style="color: var(--text-light);">Loading user details...</p>
                </div>
                <div id="userModalContent" style="display: none;">
                    <!-- User details will be loaded here dynamically -->
                </div>
                <div id="userModalError" style="display: none; text-align: center; padding: 3rem; color: var(--danger-color);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" style="margin-bottom: 1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p id="userErrorMessage">Error loading user details. Please try again.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="delete-modal-overlay" id="delete-modal">
        <div class="delete-modal">
            <div class="modal-header-delete">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    Confirm Deletion
                </h3>
            </div>
            <div class="modal-body-delete">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="user-details-delete">
                    <strong>User Details:</strong>
                    <span id="user-name-text"></span>
                </div>
                <p><strong>Note:</strong> This will permanently remove the user and all associated data from the system.</p>
            </div>
            <div class="modal-footer-delete">
                <button type="button" class="btn-cancel" id="cancel-delete">Cancel</button>
                <form id="delete-form" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" class="btn-confirm-delete" id="confirm-delete">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Yes, Delete User
                </button>
            </div>
        </div>
    </div>

    <script>
        // Enhanced Navigation Manager Class
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
                    activeNavItem: 'users'
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

        // User Management Class
        class UserManager {
            constructor() {
                // Modal elements
                this.userModal = document.getElementById('userDetailsModal');
                this.closeUserModalBtn = document.getElementById('closeUserModal');
                this.userModalContent = document.getElementById('userModalContent');
                this.userModalLoading = document.getElementById('userModalLoading');
                this.userModalError = document.getElementById('userModalError');
                this.userErrorMessage = document.getElementById('userErrorMessage');
                
                // Delete modal elements
                this.deleteButtons = document.querySelectorAll('.delete-btn');
                this.deleteModal = document.getElementById('delete-modal');
                this.cancelDeleteBtn = document.getElementById('cancel-delete');
                this.confirmDeleteBtn = document.getElementById('confirm-delete');
                this.userNameText = document.getElementById('user-name-text');
                this.deleteForms = document.querySelectorAll('.delete-form');
                
                // User data
                this.showButtons = document.querySelectorAll('.show-user-btn');
                
                // Search elements
                this.searchInput = document.getElementById('search-input');
                this.userRows = document.querySelectorAll('.user-row');
                
                // State
                this.currentDeleteId = null;
                this.currentDeleteForm = null;
                
                this.init();
            }
            
            init() {
                this.bindEvents();
                this.setupSearch();
                this.setupAutoDismissAlerts();
            }
            
            bindEvents() {
                // Show user details
                this.showButtons.forEach(button => {
                    button.addEventListener('click', () => this.showUserDetails(button));
                });
                
                // Close user modal
                if (this.closeUserModalBtn) this.closeUserModalBtn.addEventListener('click', () => this.closeUserModal());
                
                // Close modal on overlay click
                this.userModal.addEventListener('click', (e) => {
                    if (e.target === this.userModal) {
                        this.closeUserModal();
                    }
                });
                
                // Close modal with Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.userModal.style.display === 'flex') {
                        this.closeUserModal();
                    }
                });
                
                // Delete functionality
                this.deleteButtons.forEach((button, index) => {
                    button.addEventListener('click', () => this.handleDeleteClick(button, index));
                });
                
                // Cancel delete
                this.cancelDeleteBtn.addEventListener('click', () => this.closeDeleteModal());
                
                // Confirm delete
                this.confirmDeleteBtn.addEventListener('click', () => this.confirmDelete());
                
                // Close delete modal when clicking outside
                this.deleteModal.addEventListener('click', (e) => {
                    if (e.target === this.deleteModal) {
                        this.closeDeleteModal();
                    }
                });
                
                // Close delete modal with Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.deleteModal.style.display === 'flex') {
                        this.closeDeleteModal();
                    }
                });
            }
            
            showUserDetails(button) {
                const userId = button.getAttribute('data-user-id');
                const name = button.getAttribute('data-user-name');
                const email = button.getAttribute('data-user-email');
                const role = button.getAttribute('data-user-role');
                const status = button.getAttribute('data-user-status');
                const initials = button.getAttribute('data-user-initials');
                const created = button.getAttribute('data-user-created');
                const updated = button.getAttribute('data-user-updated');
                const lastLogin = button.getAttribute('data-user-last-login');
                
                this.showUserModal({
                    id: userId,
                    name: name,
                    email: email,
                    role: role,
                    status: status,
                    initials: initials,
                    created: created,
                    updated: updated,
                    lastLogin: lastLogin
                });
            }
            
            showUserModal(user) {
                // Show modal
                this.userModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                
                // Show loading initially
                this.userModalLoading.style.display = 'block';
                this.userModalContent.style.display = 'none';
                this.userModalError.style.display = 'none';
                
                // Simulate loading delay
                setTimeout(() => {
                    try {
                        // Get role badge class
                        const roleClass = `role-${user.role}`;
                        const statusClass = `status-${user.status}`;
                        
                        // Create user details HTML
                        const userHTML = `
                            <div class="user-detail-grid">
                                <div class="user-info-container">
                                    <div class="user-info-avatar">
                                        <div class="avatar-large">${user.initials}</div>
                                        <div class="user-info-text">
                                            <h3>${user.name}</h3>
                                            <p>${user.email}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">User ID</div>
                                    <div class="detail-value">#${user.id}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Role</div>
                                    <div class="detail-value">
                                        <span class="role-badge ${roleClass}">
                                            ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Status</div>
                                    <div class="detail-value">
                                        <span class="status-badge ${statusClass}">
                                            ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Created Date</div>
                                    <div class="detail-value">${user.created}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Last Updated</div>
                                    <div class="detail-value">${user.updated}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Last Login</div>
                                    <div class="detail-value">${user.lastLogin}</div>
                                </div>
                            </div>
                        `;
                        
                        // Update modal content
                        this.userModalContent.innerHTML = userHTML;
                        
                        // Hide loading and show content
                        this.userModalLoading.style.display = 'none';
                        this.userModalContent.style.display = 'block';
                    } catch (error) {
                        console.error('Error loading user details:', error);
                        this.userErrorMessage.textContent = 'Error loading user details. Please try again.';
                        this.userModalLoading.style.display = 'none';
                        this.userModalError.style.display = 'block';
                    }
                }, 300);
            }
            
            closeUserModal() {
                this.userModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            handleDeleteClick(button, index) {
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                this.currentDeleteId = userId;
                this.userNameText.textContent = userName;
                this.currentDeleteForm = this.deleteForms[index];
                
                // Show the modal
                this.deleteModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            closeDeleteModal() {
                this.deleteModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                this.currentDeleteId = null;
                this.currentDeleteForm = null;
                
                // Reset confirm button
                this.confirmDeleteBtn.classList.remove('loading');
                this.confirmDeleteBtn.disabled = false;
                this.confirmDeleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Yes, Delete User
                `;
            }
            
            confirmDelete() {
                if (this.currentDeleteId && this.currentDeleteForm) {
                    // Disable button and show loading state
                    this.confirmDeleteBtn.classList.add('loading');
                    this.confirmDeleteBtn.disabled = true;
                    this.confirmDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18" class="animate-spin">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Deleting...
                    `;
                    
                    // Submit the delete form
                    setTimeout(() => {
                        this.currentDeleteForm.submit();
                    }, 500);
                }
            }
            
            setupSearch() {
                if (this.searchInput && this.userRows.length > 0) {
                    this.searchInput.addEventListener('input', (e) => {
                        const searchTerm = e.target.value.toLowerCase().trim();
                        
                        this.userRows.forEach(row => {
                            const name = row.getAttribute('data-name');
                            const email = row.getAttribute('data-email');
                            const role = row.getAttribute('data-role');
                            
                            if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                    
                    // Add keyboard shortcut for search
                    document.addEventListener('keydown', (e) => {
                        // Focus search input on Ctrl+K
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.searchInput.focus();
                        }
                        
                        // Escape to clear search
                        if (e.key === 'Escape' && document.activeElement === this.searchInput) {
                            this.searchInput.value = '';
                            this.searchInput.dispatchEvent(new Event('input'));
                        }
                    });
                }
            }
            
            setupAutoDismissAlerts() {
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert-message');
                    alerts.forEach(alert => {
                        if (alert.parentNode) {
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
                
                // Initialize User Manager
                window.userManager = new UserManager();
                
                // Add spin animation for loading
                const style = document.createElement('style');
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
                
                // Prevent FOUC (Flash of Unstyled Content)
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s ease';
                
                setTimeout(() => {
                    document.body.style.opacity = '1';
                }, 50);
                
            } catch (error) {
                console.error('Error initializing application:', error);
                // Show error message to user
                const mainContent = document.querySelector('.admin-main');
                if (mainContent) {
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert-message alert-error';
                    errorAlert.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>An error occurred while loading the page. Please refresh and try again.</span>
                    `;
                    mainContent.prepend(errorAlert);
                }
            }
        });
    </script>
</body>
</html>