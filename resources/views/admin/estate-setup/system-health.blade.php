<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Health • Neo Faraid Admin</title>
    <meta name="description" content="Monitor system health and integrity in Neo Faraid Admin Panel">
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
            --warning-dark: #e0a800;
            --info-color: #17a2b8;
            --info-dark: #138496;
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
            flex-wrap: wrap;
            gap: 1rem;
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
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: var(--white);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: var(--white);
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.25);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%);
            color: #2c2c2c;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: #2c2c2c;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        /* ===== HEALTH CARDS GRID ===== */
        .health-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        
        .health-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow-sm);
            border-left: 5px solid;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .health-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: currentColor;
            opacity: 0.03;
            border-radius: 50%;
            transform: translate(30px, -30px);
            transition: var(--transition);
        }
        
        .health-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        .health-card:hover::before {
            transform: translate(20px, -20px) scale(1.2);
        }
        
        .health-card.success { border-left-color: var(--success-color); }
        .health-card.warning { border-left-color: var(--warning-color); }
        .health-card.danger { border-left-color: var(--danger-color); }
        .health-card.info { border-left-color: var(--info-color); }
        .health-card.primary { border-left-color: var(--primary-color); }
        
        .health-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .health-card.success .health-card-icon {
            background: linear-gradient(135deg, rgba(37, 211, 102, 0.15) 0%, rgba(18, 140, 126, 0.15) 100%);
            color: var(--success-dark);
        }
        
        .health-card.warning .health-card-icon {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(224, 168, 0, 0.15) 100%);
            color: var(--warning-dark);
        }
        
        .health-card.danger .health-card-icon {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.15) 0%, rgba(200, 35, 51, 0.15) 100%);
            color: var(--danger-dark);
        }
        
        .health-card.info .health-card-icon {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.15) 0%, rgba(19, 132, 150, 0.15) 100%);
            color: var(--info-dark);
        }
        
        .health-card.primary .health-card-icon {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.15) 0%, rgba(45, 122, 214, 0.15) 100%);
            color: var(--primary-color);
        }
        
        .health-card-title {
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .health-card-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .health-card.success .health-card-value { color: var(--success-color); }
        .health-card.warning .health-card-value { color: var(--warning-dark); }
        .health-card.danger .health-card-value { color: var(--danger-color); }
        .health-card.info .health-card-value { color: var(--info-color); }
        .health-card.primary .health-card-value { color: var(--primary-color); }
        
        .health-card-desc {
            font-size: 0.85rem;
            color: var(--text-light);
            position: relative;
            z-index: 1;
        }
        
        /* ===== SECTION CARDS ===== */
        .section-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: var(--transition);
        }
        
        .section-card:hover {
            box-shadow: var(--shadow-lg);
        }
        
        .section-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .section-card-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .section-card-header svg {
            width: 24px;
            height: 24px;
        }
        
        .section-card-body {
            padding: 1.5rem;
        }
        
        /* ===== TABLE STYLES ===== */
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(45, 122, 214, 0.05) 100%);
        }
        
        th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--light-border);
        }
        
        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--light-border);
            vertical-align: middle;
            color: var(--text-primary);
            font-weight: 400;
            font-size: 0.95rem;
        }
        
        tbody tr {
            transition: var(--transition);
        }
        
        tbody tr:hover {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.03) 0%, rgba(45, 122, 214, 0.03) 100%);
            transform: translateX(3px);
        }
        
        /* Status Indicators */
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
            position: relative;
        }
        
        .status-dot.green {
            background: var(--success-color);
            box-shadow: 0 0 8px rgba(37, 211, 102, 0.4);
        }
        
        .status-dot.yellow {
            background: var(--warning-color);
            box-shadow: 0 0 8px rgba(255, 193, 7, 0.4);
            animation: pulse 2s infinite;
        }
        
        .status-dot.red {
            background: var(--danger-color);
            box-shadow: 0 0 8px rgba(220, 53, 69, 0.4);
        }
        
        .status-dot.blue {
            background: var(--primary-color);
            box-shadow: 0 0 8px rgba(26, 95, 180, 0.4);
        }
        
        .status-text {
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        
        .status-text.clean { color: var(--success-color); }
        .status-text.issues { color: var(--warning-dark); }
        .status-text.error { color: var(--danger-color); }
        
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
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-left: 4px solid var(--warning-color);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-left: 4px solid var(--info-color);
        }
        
        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1200px) {
            .admin-main {
                padding: 2rem;
            }
        }
        
        @media (max-width: 992px) {
            .admin-top-nav {
                padding: 0 1rem;
            }
            
            .nav-link {
                padding: 0.75rem;
                font-size: 0.85rem;
            }
            
            .nav-link .nav-link-text {
                display: none;
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
            
            .admin-logo-text {
                display: none;
            }
            
            .admin-main {
                padding: 1.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .health-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn-group .btn {
                width: 100%;
                justify-content: center;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            table {
                min-width: 700px;
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
        
        @media (max-width: 480px) {
            .admin-main {
                padding: 1rem;
            }
            
            .health-grid {
                grid-template-columns: 1fr;
            }
            
            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.875rem;
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
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
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
        input:focus {
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
            .user-avatar,
            .status-dot.yellow {
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
                
                <div class="user-profile-dropdown" 
                     id="user-profile-dropdown"
                     role="menu"
                     aria-label="User profile menu"
                     aria-hidden="true">
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
        <header class="page-header">
            <div class="page-title">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="28" height="28" style="vertical-align: middle; margin-right: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    System Health
                </h1>
                <p class="page-subtitle">Monitor the health and integrity of the estate planning system</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.estate-setup.index') }}" class="btn btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    View Estates
                </a>
            </div>
        </header>
        
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

        @if(session('warning'))
            <div class="alert-message alert-warning" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                {{ session('warning') }}
            </div>
        @endif

        <div class="health-grid">
            <div class="health-card primary">
                <div class="health-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="health-card-title">Total Estates</div>
                <div class="health-card-value">{{ number_format((int)($health['total_estates'] ?? 0)) }}</div>
                <div class="health-card-desc">All registered estate plans</div>
            </div>

            <div class="health-card {{ (int)($health['pending_pdf'] ?? 0) > 0 ? 'warning' : 'success' }}">
                <div class="health-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="health-card-title">Pending PDFs</div>
                <div class="health-card-value">{{ number_format((int)($health['pending_pdf'] ?? 0)) }}</div>
                <div class="health-card-desc">Approved without PDF</div>
            </div>
        </div>

        <!-- System Integrity Checks -->
        <div class="section-card">
            <div class="section-card-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3>System Integrity Checks</h3>
            </div>
            <div class="section-card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Check</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Database Connection</strong></td>
                                <td>
                                    <span class="status-text clean">
                                        <span class="status-dot green"></span> Connected
                                    </span>
                                </td>
                                <td>Database is accessible</td>
                            </tr>
                            <tr>
                                <td><strong>PDF Generation Status</strong></td>
                                <td>
                                    <span class="status-text {{ (int)($health['pending_pdf'] ?? 0) > 0 ? 'issues' : 'clean' }}">
                                        <span class="status-dot {{ (int)($health['pending_pdf'] ?? 0) > 0 ? 'yellow' : 'green' }}"></span>
                                        {{ (int)($health['pending_pdf'] ?? 0) > 0 ? 'Pending' : 'Complete' }}
                                    </span>
                                </td>
                                <td>
                                    @if((int)($health['pending_pdf'] ?? 0) > 0)
                                        <span style="color: var(--warning-dark); font-weight: 500;">{{ number_format((int)($health['pending_pdf'] ?? 0)) }} estate(s) pending</span>
                                    @else
                                        <span style="color: var(--success-color); font-weight: 500;">All PDFs generated</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Storage Disk</strong></td>
                                <td>
                                    <span class="status-text {{ is_writable(storage_path('app')) ? 'clean' : 'error' }}">
                                        <span class="status-dot {{ is_writable(storage_path('app')) ? 'green' : 'red' }}"></span>
                                        {{ is_writable(storage_path('app')) ? 'Writable' : 'Not Writable' }}
                                    </span>
                                </td>
                                <td>{{ storage_path('app') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Application Environment</strong></td>
                                <td>
                                    <span class="status-text clean">
                                        <span class="status-dot {{ app()->environment('production') ? 'green' : 'blue' }}"></span>
                                        {{ ucfirst(app()->environment()) }}
                                    </span>
                                </td>
                                <td>PHP {{ phpversion() }} | Laravel {{ app()->version() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
                    activeNavItem: 'estate-setup'
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
                
                document.addEventListener('click', (e) => this.handleOutsideClick(e));
                document.addEventListener('keydown', (e) => this.handleKeydown(e));
                
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
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.setAttribute('role', 'button');
                    this.elements.userProfileBtn.setAttribute('aria-expanded', 'false');
                }
                
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.setAttribute('role', 'menu');
                    this.elements.userProfileDropdown.setAttribute('aria-label', 'User profile menu');
                }
                
                const navLinks = document.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.setAttribute('role', 'menuitem');
                });
                
                const dropdownItems = document.querySelectorAll('.dropdown-nav-item, .account-link');
                dropdownItems.forEach(item => {
                    item.setAttribute('role', 'menuitem');
                });
            }
            
            setupKeyboardNavigation() {
                if (this.elements.navLinks) {
                    const navItems = this.elements.navLinks.querySelectorAll('.nav-link');
                    navItems.forEach((item, index) => {
                        item.addEventListener('keydown', (e) => {
                            if (e.key === 'ArrowRight') {
                                e.preventDefault();
                                const nextItem = navItems[index + 1] || navItems[0];
                                nextItem.focus();
                            } else if (e.key === 'ArrowLeft') {
                                e.preventDefault();
                                const prevItem = navItems[index - 1] || navItems[navItems.length - 1];
                                prevItem.focus();
                            }
                        });
                    });
                }
                
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
                
                let touchStartY = 0;
                
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.addEventListener('touchstart', (e) => {
                        touchStartY = e.touches[0].clientY;
                    });
                    
                    this.elements.userProfileDropdown.addEventListener('touchmove', (e) => {
                        if (!this.state.isDropdownOpen) return;
                        
                        const touchY = e.touches[0].clientY;
                        const diff = touchStartY - touchY;
                        
                        if (diff > 50) {
                            this.closeUserDropdown();
                        }
                    });
                }
                
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
                if (e.key === 'Escape' && this.state.isDropdownOpen) {
                    this.closeUserDropdown();
                    if (this.elements.userProfileBtn) {
                        this.elements.userProfileBtn.focus();
                    }
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
                    
                    setTimeout(() => {
                        this.elements.userProfileDropdown.classList.add('active');
                    }, 10);
                    
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
            }
            
            closeUserDropdown() {
                this.state.isDropdownOpen = false;
                
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.classList.remove('active');
                    this.elements.userProfileDropdown.setAttribute('aria-hidden', 'true');
                    
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
            }
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            try {
                window.navigationManager = new NavigationManager();
                
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
                
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s ease';
                
                setTimeout(() => {
                    document.body.style.opacity = '1';
                }, 50);
                
                // Auto-dismiss alert messages after 5 seconds
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert-message');
                    alerts.forEach(alert => {
                        if (alert.parentNode) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-10px)';
                            alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            setTimeout(() => {
                                if (alert.parentNode) {
                                    alert.remove();
                                }
                            }, 300);
                        }
                    });
                }, 5000);
                
            } catch (error) {
                console.error('Error initializing application:', error);
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