<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Manage Estate Planning • Neo Faraid Admin</title>
    <meta name="description" content="Manage all estate pre-registrations and monitor their status in Neo Faraid Admin Panel">
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
            --warning-dark: #e0a800;
            --info-color: #138496;
            --info-dark: #0f6b84;
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

        /* Top Navigation */
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
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-2px);
            }
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
            0%,
            100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
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

        /* Main Content */
        .admin-main {
            padding: 2.5rem;
            background: var(--light-bg);
            max-width: 1400px;
            margin: 0 auto;
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

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary-color);
        }

        .stats-card-title {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stats-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stats-card-change {
            font-size: 0.85rem;
            color: var(--text-light);
        }

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

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: var(--white);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #0f6b84 100%);
            color: var(--white);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--light-border);
            color: var(--text-primary);
        }

        .alert-message {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
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

        .table-container {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            overflow: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
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
            white-space: nowrap;
        }

        td {
            padding: 1.25rem;
            border-bottom: 1px solid var(--light-border);
            vertical-align: middle;
            color: var(--text-primary);
        }

        tbody tr:hover {
            background: rgba(26, 95, 180, 0.03);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-draft {
            background: #e9ecef;
            color: #6c757d;
        }
        .status-completed {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-activated {
            background: #d4edda;
            color: #155724;
        }
        .status-executed {
            background: #e9ecef;
            color: #495057;
        }
        .status-pending_review {
            background: #fff3cd;
            color: #856404;
        }

        .approved-badge {
            display: inline-flex;
            padding: 0.25rem 0.625rem;
            border-radius: 50px;
            font-size: 0.6875rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .approved-yes {
            background: #d4edda;
            color: #155724;
        }
        .approved-no {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-show,
        .btn-approve,
        .btn-reject,
        .btn-toggle,
        .btn-delete,
        .btn-settle-first {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            border: none;
            box-shadow: var(--shadow-sm);
            white-space: nowrap;
        }

        a.btn-show {
            text-decoration: none;
        }

        .btn-show {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            text-decoration: none;
        }

        .btn-approve {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: white;
        }
        .btn-reject {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
            color: #856404;
        }
        .btn-toggle {
            background: transparent;
            border: 2px solid #dee2e6;
            color: #6c757d;
        }
        .btn-delete {
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: white;
        }
        .btn-settle-first {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            text-decoration: none;
        }

        .btn-show:hover,
        .btn-approve:hover,
        .btn-settle-first:hover,
        .btn-reject:hover,
        .btn-toggle:hover,
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--white);
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-md);
            padding: 0.5rem 1rem;
            width: 320px;
        }

        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            padding: 0.5rem;
            font-family: var(--font-family);
            color: var(--text-primary);
            background: transparent;
        }

        .filter-group {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-md);
            font-size: 0.9375rem;
            background: var(--white);
            cursor: pointer;
            font-family: var(--font-family);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: var(--white);
            border-top: 1px solid var(--light-border);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
        }

        .pagination-number {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--light-border);
            border-radius: var(--border-radius-sm);
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            min-width: 40px;
            text-align: center;
        }

        .pagination-number.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
            font-weight: 600;
        }

        /* ========== MODAL OVERLAYS (shared) ========== */
        .modal-overlay {
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
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .modal-overlay.active {
            display: flex;
        }

        /* ========== DELETE MODAL ========== */
        .delete-modal {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header-delete {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
            color: var(--white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-header-delete h3 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body-delete {
            padding: 2rem 1.5rem;
        }

        .modal-body-delete p {
            color: var(--text-primary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        .modal-info-box {
            background: var(--light-bg);
            padding: 1rem 1.25rem;
            border-radius: var(--border-radius-md);
            margin-top: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .modal-info-box strong {
            display: block;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 0.25rem;
        }

        .modal-info-box span {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .modal-footer-delete {
            padding: 1.25rem 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            border-top: 1px solid var(--light-border);
            background: var(--light-bg);
            border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
        }

        .btn-cancel {
            padding: 0.75rem 1.5rem;
            background: var(--white);
            color: var(--text-primary);
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            font-size: 0.9rem;
        }

        .btn-cancel:hover {
            background: #f1f3f5;
            border-color: #ced4da;
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
            font-family: inherit;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-confirm-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        /* ========== APPROVE MODAL ========== */
        .approve-modal {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 520px;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        .modal-header-approve {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: var(--white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-header-approve h3 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body-approve {
            padding: 2rem 1.5rem;
        }

        .modal-body-approve p {
            color: var(--text-primary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        .modal-body-approve .beneficiary-note {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: #f0f9f4;
            border: 1px solid #c8e6c9;
            border-radius: var(--border-radius-md);
            padding: 1rem 1.25rem;
            margin-top: 1rem;
            color: #2e7d32;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .modal-body-approve .beneficiary-note svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .modal-footer-approve {
            padding: 1.25rem 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            border-top: 1px solid var(--light-border);
            background: var(--light-bg);
            border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
        }

        .btn-confirm-approve {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-confirm-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
        }

        /* ========== REJECT MODAL ========== */
        .reject-modal {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 520px;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        .modal-header-reject {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f0932b 0%, #e67e22 100%);
            color: var(--white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-header-reject h3 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body-reject {
            padding: 2rem 1.5rem;
        }

        .modal-body-reject p {
            color: var(--text-primary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        .modal-body-reject .reject-warning-note {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: var(--border-radius-md);
            padding: 1rem 1.25rem;
            margin-top: 1rem;
            color: #e65100;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .modal-body-reject .reject-warning-note svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .modal-footer-reject {
            padding: 1.25rem 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            border-top: 1px solid var(--light-border);
            background: var(--light-bg);
            border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
        }

        .btn-confirm-reject {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #f0932b 0%, #e67e22 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-confirm-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240, 147, 43, 0.35);
        }

        /* ========== TOGGLE MODAL ========== */
        .toggle-modal {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 520px;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        .modal-header-toggle {
            padding: 1.5rem;
            background: linear-gradient(135deg, #5b7fdb 0%, #4a6ac5 100%);
            color: var(--white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-header-toggle h3 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body-toggle {
            padding: 2rem 1.5rem;
        }

        .modal-body-toggle p {
            color: var(--text-primary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        .modal-body-toggle .toggle-info-note {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background: #eef2fb;
            border: 1px solid #cdd9f5;
            border-radius: var(--border-radius-md);
            padding: 1rem 1.25rem;
            margin-top: 1rem;
            color: #2c3e7b;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .modal-body-toggle .toggle-info-note svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .status-change-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            margin: 0 0.25rem;
        }

        .status-change-from {
            background: #e9ecef;
            color: #495057;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .status-change-arrow {
            color: #5b7fdb;
            font-weight: 700;
        }

        .status-change-to {
            background: #d4edda;
            color: #155724;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .status-change-to.is-draft {
            background: #e9ecef;
            color: #6c757d;
        }

        .modal-footer-toggle {
            padding: 1.25rem 1.5rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            border-top: 1px solid var(--light-border);
            background: var(--light-bg);
            border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
        }

        .btn-confirm-toggle {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #5b7fdb 0%, #4a6ac5 100%);
            color: white;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-confirm-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(91, 127, 219, 0.35);
        }

        .debt-status-settled {
            color: var(--success-color);
            font-weight: 600;
        }
        .debt-status-pending {
            color: var(--warning-color);
            font-weight: 600;
        }
        .debt-status-none {
            color: var(--text-light);
        }

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

        @media (max-width: 768px) {
            .admin-top-nav {
                height: 60px;
                padding: 0 1rem;
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
                gap: 1rem;
            }
            .search-container {
                flex-direction: column;
            }
            .search-box {
                width: 100%;
            }
            .table-container {
                overflow-x: auto;
            }
            table {
                min-width: 900px;
            }
            .modal-footer-delete,
            .modal-footer-approve,
            .modal-footer-reject,
            .modal-footer-toggle {
                flex-direction: column;
                gap: 0.75rem;
            }
            .btn-cancel,
            .btn-confirm-delete,
            .btn-confirm-approve,
            .btn-confirm-reject,
            .btn-confirm-toggle {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="admin-top-nav" role="navigation" aria-label="Admin navigation">
        <div class="nav-left">
            <a href="{{ route('admin.dashboard') }}" class="admin-logo" aria-label="Neo Faraid Admin Home">
                <i class="fas fa-layer-group" style="color: var(--accent-color); font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3)); animation: logoFloat 4s ease-in-out infinite;"></i>
                <span class="admin-logo-text">Neo Faraid Admin</span>
            </a>
        </div>
        <div class="nav-right">
            @php
                $user = auth()->user();
                $lastLogin = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently';
                $fullInitials = strtoupper(implode('', array_map(function($name) { return substr($name, 0, 1); }, explode(' ', $user->name, 2))));
            @endphp
            <div class="user-profile-container" id="user-profile-container">
                <button class="user-profile-btn" id="user-profile-btn" aria-label="User profile menu" aria-expanded="false" aria-haspopup="true" data-user-role="{{ $user->role }}" data-user-initials="{{ $fullInitials }}">
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
                            <a href="{{ route('admin.instant-estate.index') }}" class="dropdown-nav-item {{ request()->is('admin/instant-estate*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Manage Instant Estate</span>
                            </a>
                            <a href="{{ route('admin.estate-setup.index') }}" class="dropdown-nav-item active">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>Manage Estate Planning</span>
                            </a>
                        </nav>
                    </div>
                    <div class="dropdown-section">
                        <h4 class="section-title">Account</h4>
                        <div class="account-links">
                            <a href="{{ route('profile.edit') }}" class="account-link {{ request()->is('profile*') ? 'active' : '' }}" role="menuitem">
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

    <main class="admin-main">
        <header class="page-header">
            <div class="page-title">
                <h1>Manage Estate Planning</h1>
                <p class="page-subtitle">Manage all estate pre-registrations and monitor their status. Settle debts first, then approve to send emails.</p>
            </div>
            <div class="header-actions" style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.estate-setup.export') }}" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export CSV
                </a>
                <a href="{{ route('admin.estate-setup.system-health') }}" class="btn btn-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    System Health
                </a>
            </div>
        </header>

        <div class="stats-cards">
            <div class="stats-card"><div class="stats-card-title">Total Estates</div><div class="stats-card-value">{{ $stats['total'] ?? 0 }}</div></div>
            <div class="stats-card"><div class="stats-card-title">Draft</div><div class="stats-card-value">{{ $stats['draft'] ?? 0 }}</div></div>
            <div class="stats-card"><div class="stats-card-title">Activated</div><div class="stats-card-value">{{ $stats['activated'] ?? 0 }}</div></div>
            <div class="stats-card"><div class="stats-card-title">Admin Approved</div><div class="stats-card-value">{{ $stats['approved'] ?? 0 }}</div></div>
        </div>

        @if(session('success'))
            <div class="alert-message alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-message alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="search-container">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search by name, NRIC, email, or unique ID..." id="search-input" value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select class="filter-select" id="status-filter">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="activated" {{ request('status') == 'activated' ? 'selected' : '' }}>Activated</option>
                </select>
                <select class="filter-select" id="approval-filter">
                    <option value="">All Approvals</option>
                    <option value="1" {{ request('approved') == '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ request('approved') == '0' ? 'selected' : '' }}>Not Approved</option>
                </select>
                <button class="btn btn-outline" id="clear-filters-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear Filters
                </button>
            </div>
        </div>

        <div class="table-container">
            @if(isset($estates) && $estates->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Owner</th>
                                <th>Deceased</th>
                                <th>Status</th>
                                <th>Approved</th>
                                <th>Debts</th>
                                <th>Heirs/Assets</th>
                                <th>Net Estate</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="estate-table-body">
                            @foreach($estates as $estate)
                                @php
                                    $ownerInitials = strtoupper(substr($estate->user->name ?? '?', 0, 1));
                                    $deceasedInitials = strtoupper(substr($estate->deceased_name, 0, 1));

                                    $unsettledDebts = $estate->debts()
                                        ->where(function($q) {
                                            $q->where('status', '!=', 'settled')
                                              ->orWhereRaw('amount > amount_paid');
                                        })->count();
                                    $totalDebts = $estate->debts()->count();
                                    $debtsSettled = $totalDebts - $unsettledDebts;
                                    $allDebtsSettled = ($totalDebts === 0) || ($unsettledDebts === 0);

                                    $toggleTargetStatus = $estate->status == 'draft' ? 'activated' : 'draft';
                                    $toggleTargetLabel = $toggleTargetStatus == 'activated' ? 'Activated' : 'Draft';
                                    $currentStatusLabel = $estate->status_label;
                                @endphp
                                <tr class="estate-row"
                                    data-name="{{ strtolower($estate->deceased_name ?? '') }}"
                                    data-user="{{ strtolower($estate->user->name ?? '') }}"
                                    data-unique-id="{{ strtolower($estate->unique_id ?? '') }}"
                                    data-nric="{{ strtolower($estate->deceased_nric ?? '') }}"
                                    data-debts-settled="{{ $allDebtsSettled ? 'true' : 'false' }}">
                                    <td><span class="estate-id">#{{ $estate->id }}</span></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">{{ $ownerInitials }}</div>
                                            <div>
                                                <div style="font-weight: 600; color: var(--primary-color);">{{ $estate->user->name ?? 'N/A' }}</div>
                                                <div style="font-size: 0.85rem; color: var(--text-light);">{{ $estate->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">{{ $deceasedInitials }}</div>
                                            <div>
                                                <div style="font-weight: 600; color: var(--primary-color);">{{ $estate->deceased_name }}</div>
                                                <div style="font-size: 0.85rem; color: var(--text-light);">{{ $estate->deceased_nric ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="status-badge status-{{ $estate->status }}">{{ $currentStatusLabel }}</span></td>
                                    <td>
                                        @if($estate->admin_approved)
                                            <span class="approved-badge approved-yes">Yes</span>
                                        @else
                                            <span class="approved-badge approved-no">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($totalDebts > 0)
                                            <span class="{{ $allDebtsSettled ? 'debt-status-settled' : 'debt-status-pending' }}">
                                                {{ $debtsSettled }}/{{ $totalDebts }} settled
                                            </span>
                                            @if(!$allDebtsSettled)
                                                <br><small style="color: var(--danger-color);">{{ $unsettledDebts }} pending</small>
                                            @endif
                                        @else
                                            <span class="debt-status-none">No debts</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $estate->heirs->count() }} heirs</div>
                                        <div>{{ $estate->assets->count() }} assets</div>
                                    </td>
                                    <td><span>RM {{ number_format($estate->net_estate, 0) }}</span></td>
                                    <td>
                                        <div>{{ $estate->created_at ? $estate->created_at->format('d M Y') : 'N/A' }}</div>
                                        <small style="color: var(--text-light)">{{ $estate->created_at ? $estate->created_at->format('H:i') : '' }}</small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.estate-setup.show', $estate->unique_id) }}" class="btn-show" style="text-decoration: none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Show
                                            </a>

                                            @if(!$estate->admin_approved && $allDebtsSettled)
                                                <form action="{{ route('admin.estate-setup.approve', $estate->unique_id) }}" method="POST" style="display: inline;" class="approve-form">
                                                    @csrf
                                                    <button type="button" class="btn-approve approve-trigger-btn" data-deceased-name="{{ $estate->deceased_name }}" data-unique-id="{{ $estate->unique_id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Approve
                                                    </button>
                                                </form>
                                            @elseif(!$estate->admin_approved && !$allDebtsSettled)
                                                <a href="{{ route('admin.estate-setup.show', $estate->unique_id) }}" class="btn-settle-first" style="text-decoration: none;" title="Settle all debts before approving">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                                    Settle Debts
                                                </a>
                                            @else
                                                <form action="{{ route('admin.estate-setup.reject', $estate->unique_id) }}" method="POST" style="display: inline;" class="reject-form">
                                                    @csrf
                                                    <button type="button" class="btn-reject reject-trigger-btn" data-deceased-name="{{ $estate->deceased_name }}" data-unique-id="{{ $estate->unique_id }}" data-estate-id="{{ $estate->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.estate-setup.toggle-status', $estate->unique_id) }}" method="POST" style="display: inline;" class="toggle-form">
                                                @csrf
                                                <button type="button" class="btn-toggle toggle-trigger-btn"
                                                        data-deceased-name="{{ $estate->deceased_name }}"
                                                        data-unique-id="{{ $estate->unique_id }}"
                                                        data-estate-id="{{ $estate->id }}"
                                                        data-current-status="{{ $estate->status }}"
                                                        data-current-status-label="{{ $currentStatusLabel }}"
                                                        data-target-status="{{ $toggleTargetStatus }}"
                                                        data-target-status-label="{{ $toggleTargetLabel }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                    Toggle
                                                </button>
                                            </form>

                                            <button type="button" class="btn-delete delete-estate-btn"
                                                    data-estate-id="{{ $estate->id }}"
                                                    data-unique-id="{{ $estate->unique_id }}"
                                                    data-deceased-name="{{ $estate->deceased_name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($estates->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">Showing {{ $estates->firstItem() }} to {{ $estates->lastItem() }} of {{ $estates->total() }} estates</div>
                    <div class="pagination">
                        @foreach ($estates->links()->elements[0] as $page => $url)
                            @if($page == $estates->currentPage())
                                <span class="pagination-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

            @else
                <div style="text-align: center; padding: 4rem 2rem; color: var(--text-light);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 64px; height: 64px; margin-bottom: 1rem; color: var(--light-border);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <h3>No Estates Found</h3>
                    <p>No estate pre-registrations match your filters.</p>
                </div>
            @endif
        </div>
    </main>

    <!-- ========== DELETE CONFIRMATION MODAL ========== -->
    <div class="modal-overlay" id="delete-modal" style="display: none;">
        <div class="delete-modal">
            <div class="modal-header-delete">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Delete Estate Registration
                </h3>
            </div>
            <div class="modal-body-delete">
                <p>You are about to <strong>permanently delete</strong> the estate registration for <strong id="estate-deceased-name"></strong>. This action <strong>cannot be undone</strong> — all associated heirs, assets, debts, and history will be completely erased. Please confirm to proceed.</p>
                <div class="modal-info-box">
                    <strong>Estate Details</strong>
                    <span id="estate-name-text"></span>
                </div>
            </div>
            <div class="modal-footer-delete">
                <button type="button" class="btn-cancel" id="cancel-delete">Cancel</button>
                <form id="delete-form" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                <button type="button" class="btn-confirm-delete" id="confirm-delete">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Yes, Delete Estate
                </button>
            </div>
        </div>
    </div>

    <!-- ========== APPROVE CONFIRMATION MODAL ========== -->
    <div class="modal-overlay" id="approve-modal" style="display: none;">
        <div class="approve-modal">
            <div class="modal-header-approve">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Approve Estate Registration
                </h3>
            </div>
            <div class="modal-body-approve">
                <p>You are about to <strong>approve</strong> the estate registration for <strong id="approve-deceased-name"></strong>. Upon confirmation, the system will immediately:</p>
                <div class="beneficiary-note">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Generate unique access links and send notification emails to <strong>all beneficiaries</strong> (heirs, trustees, wasiyyah recipients) via SMTP.</span>
                </div>
                <div class="modal-info-box" style="margin-top: 1rem;">
                    <strong>Estate Details</strong>
                    <span id="approve-estate-name-text"></span>
                </div>
            </div>
            <div class="modal-footer-approve">
                <button type="button" class="btn-cancel" id="cancel-approve">Cancel</button>
                <button type="button" class="btn-confirm-approve" id="confirm-approve">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Yes, Approve Estate
                </button>
            </div>
        </div>
    </div>

    <!-- ========== REJECT CONFIRMATION MODAL ========== -->
    <div class="modal-overlay" id="reject-modal" style="display: none;">
        <div class="reject-modal">
            <div class="modal-header-reject">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Revoke Estate Approval
                </h3>
            </div>
            <div class="modal-body-reject">
                <p>You are about to <strong>revoke admin approval</strong> for the estate registration of <strong id="reject-deceased-name"></strong>. This will:</p>
                <div class="reject-warning-note">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <span>Remove the approved status, which may <strong>restrict beneficiary access</strong> and require re-approval before notification emails can be sent again.</span>
                </div>
                <div class="modal-info-box" style="margin-top: 1rem;">
                    <strong>Estate Details</strong>
                    <span id="reject-estate-name-text"></span>
                </div>
            </div>
            <div class="modal-footer-reject">
                <button type="button" class="btn-cancel" id="cancel-reject">Cancel</button>
                <button type="button" class="btn-confirm-reject" id="confirm-reject">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Yes, Revoke Approval
                </button>
            </div>
        </div>
    </div>

    <!-- ========== TOGGLE STATUS CONFIRMATION MODAL ========== -->
    <div class="modal-overlay" id="toggle-modal" style="display: none;">
        <div class="toggle-modal">
            <div class="modal-header-toggle">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Toggle Estate Status
                </h3>
            </div>
            <div class="modal-body-toggle">
                <p>You are about to change the status for the estate registration of <strong id="toggle-deceased-name"></strong>.</p>
                <div class="toggle-info-note">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>The status will be updated from <strong id="toggle-from-status"></strong> → <strong id="toggle-to-status"></strong>. This may affect how the estate is displayed and processed throughout the system.</span>
                </div>
                <div class="modal-info-box" style="margin-top: 1rem;">
                    <strong>Estate Details</strong>
                    <span id="toggle-estate-name-text"></span>
                </div>
            </div>
            <div class="modal-footer-toggle">
                <button type="button" class="btn-cancel" id="cancel-toggle">Cancel</button>
                <button type="button" class="btn-confirm-toggle" id="confirm-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Yes, Toggle Status
                </button>
            </div>
        </div>
    </div>

    <script>
        class NavigationManager {
            constructor() {
                this.selectors = {
                    userProfileBtn: '#user-profile-btn',
                    userProfileDropdown: '#user-profile-dropdown',
                    logoutForm: '.logout-form'
                };
                this.state = {
                    isDropdownOpen: false,
                    isTouchDevice: 'ontouchstart' in window || navigator.maxTouchPoints > 0
                };
                this.elements = {};
                this.init();
            }

            init() {
                this.cacheElements();
                this.bindEvents();
                this.setupAccessibility();
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
                document.querySelectorAll('.dropdown-nav-item, .quick-action-item, .account-link').forEach(item => {
                    item.setAttribute('role', 'menuitem');
                });
            }

            setupKeyboardNavigation() {
                const dropdownNav = this.elements.userProfileDropdown?.querySelector('.dropdown-nav');
                if (dropdownNav) {
                    const items = dropdownNav.querySelectorAll('.dropdown-nav-item');
                    items.forEach((item, index) => {
                        item.addEventListener('keydown', (e) => {
                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                const next = items[index + 1] || items[0];
                                next.focus();
                            } else if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                const prev = items[index - 1] || items[items.length - 1];
                                prev.focus();
                            }
                        });
                    });
                }
            }

            setupTouchGestures() {
                if (!this.state.isTouchDevice) return;
                let startY = 0;
                if (this.elements.userProfileDropdown) {
                    this.elements.userProfileDropdown.addEventListener('touchstart', (e) => {
                        startY = e.touches[0].clientY;
                    });
                    this.elements.userProfileDropdown.addEventListener('touchmove', (e) => {
                        if (!this.state.isDropdownOpen) return;
                        if (startY - e.touches[0].clientY > 50) {
                            this.closeUserDropdown();
                        }
                    });
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
                    this.elements.userProfileBtn?.focus();
                }
                if (e.key === 'Tab' && this.state.isDropdownOpen && this.elements.userProfileDropdown) {
                    this.trapFocus(this.elements.userProfileDropdown, e);
                }
            }

            trapFocus(element, e) {
                const focusable = element.querySelectorAll(
                    'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                if (focusable.length === 0) return;
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }

            toggleUserDropdown() {
                this.state.isDropdownOpen ? this.closeUserDropdown() : this.openUserDropdown();
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
                        const first = this.elements.userProfileDropdown.querySelector('a, button');
                        if (first) first.focus();
                    }, 100);
                }
                if (this.elements.userProfileBtn) {
                    this.elements.userProfileBtn.setAttribute('aria-expanded', 'true');
                    const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
                    if (chevron) chevron.style.transform = 'rotate(180deg)';
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
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                }
            }
        }

        class EstateManager {
            constructor() {
                // Delete modal elements
                this.deleteButtons = document.querySelectorAll('.delete-estate-btn');
                this.deleteModal = document.getElementById('delete-modal');
                this.cancelDeleteBtn = document.getElementById('cancel-delete');
                this.confirmDeleteBtn = document.getElementById('confirm-delete');
                this.estateNameText = document.getElementById('estate-name-text');
                this.estateDeceasedNameSpan = document.getElementById('estate-deceased-name');
                this.deleteForm = document.getElementById('delete-form');

                // Approve modal elements
                this.approveTriggerButtons = document.querySelectorAll('.approve-trigger-btn');
                this.approveModal = document.getElementById('approve-modal');
                this.cancelApproveBtn = document.getElementById('cancel-approve');
                this.confirmApproveBtn = document.getElementById('confirm-approve');
                this.approveEstateNameText = document.getElementById('approve-estate-name-text');
                this.approveDeceasedNameSpan = document.getElementById('approve-deceased-name');

                // Reject modal elements
                this.rejectTriggerButtons = document.querySelectorAll('.reject-trigger-btn');
                this.rejectModal = document.getElementById('reject-modal');
                this.cancelRejectBtn = document.getElementById('cancel-reject');
                this.confirmRejectBtn = document.getElementById('confirm-reject');
                this.rejectEstateNameText = document.getElementById('reject-estate-name-text');
                this.rejectDeceasedNameSpan = document.getElementById('reject-deceased-name');

                // Toggle modal elements
                this.toggleTriggerButtons = document.querySelectorAll('.toggle-trigger-btn');
                this.toggleModal = document.getElementById('toggle-modal');
                this.cancelToggleBtn = document.getElementById('cancel-toggle');
                this.confirmToggleBtn = document.getElementById('confirm-toggle');
                this.toggleEstateNameText = document.getElementById('toggle-estate-name-text');
                this.toggleDeceasedNameSpan = document.getElementById('toggle-deceased-name');
                this.toggleFromStatus = document.getElementById('toggle-from-status');
                this.toggleToStatus = document.getElementById('toggle-to-status');

                // Search & filters
                this.searchInput = document.getElementById('search-input');
                this.estateRows = document.querySelectorAll('.estate-row');
                this.statusFilter = document.getElementById('status-filter');
                this.approvalFilter = document.getElementById('approval-filter');
                this.clearFiltersBtn = document.getElementById('clear-filters-btn');

                // State
                this.currentDeleteId = null;
                this.currentApproveForm = null;
                this.currentApproveDeceasedName = null;
                this.currentRejectForm = null;
                this.currentToggleForm = null;

                this.init();
            }

            init() {
                this.bindDeleteEvents();
                this.bindApproveEvents();
                this.bindRejectEvents();
                this.bindToggleEvents();
                this.setupSearch();
                this.setupFilters();
                this.bindGlobalKeyboardEvents();
            }

            // ─── DELETE MODAL ───────────────────────────────────
            bindDeleteEvents() {
                this.deleteButtons.forEach(button => {
                    button.addEventListener('click', () => this.handleDeleteClick(button));
                });
                if (this.cancelDeleteBtn) this.cancelDeleteBtn.addEventListener('click', () => this.closeDeleteModal());
                if (this.confirmDeleteBtn) this.confirmDeleteBtn.addEventListener('click', () => this.confirmDelete());
                if (this.deleteModal) {
                    this.deleteModal.addEventListener('click', (e) => {
                        if (e.target === this.deleteModal) this.closeDeleteModal();
                    });
                }
            }

            handleDeleteClick(button) {
                const estateId = button.getAttribute('data-estate-id');
                const deceasedName = button.getAttribute('data-deceased-name');
                const uniqueId = button.getAttribute('data-unique-id');
                this.currentDeleteId = uniqueId;
                if (this.estateNameText) {
                    this.estateNameText.textContent = `Deceased: ${deceasedName} (ID: #${estateId})`;
                }
                if (this.estateDeceasedNameSpan) {
                    this.estateDeceasedNameSpan.textContent = deceasedName;
                }
                this.deleteForm.action = `/admin/estate-setup/${uniqueId}`;
                this.openDeleteModal();
            }

            openDeleteModal() {
                if (this.deleteModal) {
                    this.deleteModal.style.display = 'flex';
                    this.deleteModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        const focusTarget = this.deleteModal.querySelector('button');
                        if (focusTarget) focusTarget.focus();
                    }, 150);
                }
            }

            closeDeleteModal() {
                if (this.deleteModal) {
                    this.deleteModal.classList.remove('active');
                    this.deleteModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                this.currentDeleteId = null;
            }

            confirmDelete() {
                if (this.currentDeleteId && this.deleteForm) {
                    const row = document.querySelector(`.estate-row[data-unique-id="${this.currentDeleteId.toLowerCase()}"]`);
                    if (row) {
                        row.style.opacity = '0.5';
                        row.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => { if (row.parentNode) row.remove(); }, 300);
                    }
                    setTimeout(() => { this.deleteForm.submit(); }, 400);
                }
            }

            // ─── APPROVE MODAL ─────────────────────────────────
            bindApproveEvents() {
                this.approveTriggerButtons.forEach(button => {
                    button.addEventListener('click', () => this.handleApproveClick(button));
                });
                if (this.cancelApproveBtn) this.cancelApproveBtn.addEventListener('click', () => this.closeApproveModal());
                if (this.confirmApproveBtn) this.confirmApproveBtn.addEventListener('click', () => this.confirmApprove());
                if (this.approveModal) {
                    this.approveModal.addEventListener('click', (e) => {
                        if (e.target === this.approveModal) this.closeApproveModal();
                    });
                }
            }

            handleApproveClick(button) {
                const deceasedName = button.getAttribute('data-deceased-name');
                const uniqueId = button.getAttribute('data-unique-id');
                this.currentApproveDeceasedName = deceasedName;
                if (this.approveDeceasedNameSpan) {
                    this.approveDeceasedNameSpan.textContent = deceasedName;
                }
                if (this.approveEstateNameText) {
                    this.approveEstateNameText.textContent = `Deceased: ${deceasedName} (Unique ID: ${uniqueId})`;
                }
                this.currentApproveForm = button.closest('.approve-form');
                this.openApproveModal();
            }

            openApproveModal() {
                if (this.approveModal) {
                    this.approveModal.style.display = 'flex';
                    this.approveModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        const focusTarget = this.approveModal.querySelector('button');
                        if (focusTarget) focusTarget.focus();
                    }, 150);
                }
            }

            closeApproveModal() {
                if (this.approveModal) {
                    this.approveModal.classList.remove('active');
                    this.approveModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                this.currentApproveForm = null;
                this.currentApproveDeceasedName = null;
            }

            confirmApprove() {
                if (this.currentApproveForm) {
                    const row = this.currentApproveForm.closest('.estate-row');
                    if (row) {
                        row.style.opacity = '0.7';
                        row.style.transition = 'opacity 0.3s ease';
                    }
                    setTimeout(() => { this.currentApproveForm.submit(); }, 300);
                }
            }

            // ─── REJECT MODAL ──────────────────────────────────
            bindRejectEvents() {
                this.rejectTriggerButtons.forEach(button => {
                    button.addEventListener('click', () => this.handleRejectClick(button));
                });
                if (this.cancelRejectBtn) this.cancelRejectBtn.addEventListener('click', () => this.closeRejectModal());
                if (this.confirmRejectBtn) this.confirmRejectBtn.addEventListener('click', () => this.confirmReject());
                if (this.rejectModal) {
                    this.rejectModal.addEventListener('click', (e) => {
                        if (e.target === this.rejectModal) this.closeRejectModal();
                    });
                }
            }

            handleRejectClick(button) {
                const deceasedName = button.getAttribute('data-deceased-name');
                const uniqueId = button.getAttribute('data-unique-id');
                const estateId = button.getAttribute('data-estate-id');
                if (this.rejectDeceasedNameSpan) {
                    this.rejectDeceasedNameSpan.textContent = deceasedName;
                }
                if (this.rejectEstateNameText) {
                    this.rejectEstateNameText.textContent = `Deceased: ${deceasedName} (ID: #${estateId}, Unique ID: ${uniqueId})`;
                }
                this.currentRejectForm = button.closest('.reject-form');
                this.openRejectModal();
            }

            openRejectModal() {
                if (this.rejectModal) {
                    this.rejectModal.style.display = 'flex';
                    this.rejectModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        const focusTarget = this.rejectModal.querySelector('button');
                        if (focusTarget) focusTarget.focus();
                    }, 150);
                }
            }

            closeRejectModal() {
                if (this.rejectModal) {
                    this.rejectModal.classList.remove('active');
                    this.rejectModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                this.currentRejectForm = null;
            }

            confirmReject() {
                if (this.currentRejectForm) {
                    const row = this.currentRejectForm.closest('.estate-row');
                    if (row) {
                        row.style.opacity = '0.7';
                        row.style.transition = 'opacity 0.3s ease';
                    }
                    setTimeout(() => { this.currentRejectForm.submit(); }, 300);
                }
            }

            // ─── TOGGLE MODAL ──────────────────────────────────
            bindToggleEvents() {
                this.toggleTriggerButtons.forEach(button => {
                    button.addEventListener('click', () => this.handleToggleClick(button));
                });
                if (this.cancelToggleBtn) this.cancelToggleBtn.addEventListener('click', () => this.closeToggleModal());
                if (this.confirmToggleBtn) this.confirmToggleBtn.addEventListener('click', () => this.confirmToggle());
                if (this.toggleModal) {
                    this.toggleModal.addEventListener('click', (e) => {
                        if (e.target === this.toggleModal) this.closeToggleModal();
                    });
                }
            }

            handleToggleClick(button) {
                const deceasedName = button.getAttribute('data-deceased-name');
                const uniqueId = button.getAttribute('data-unique-id');
                const estateId = button.getAttribute('data-estate-id');
                const currentStatusLabel = button.getAttribute('data-current-status-label');
                const targetStatusLabel = button.getAttribute('data-target-status-label');

                if (this.toggleDeceasedNameSpan) {
                    this.toggleDeceasedNameSpan.textContent = deceasedName;
                }
                if (this.toggleEstateNameText) {
                    this.toggleEstateNameText.textContent = `Deceased: ${deceasedName} (ID: #${estateId}, Unique ID: ${uniqueId})`;
                }
                if (this.toggleFromStatus) {
                    this.toggleFromStatus.textContent = currentStatusLabel;
                }
                if (this.toggleToStatus) {
                    this.toggleToStatus.textContent = targetStatusLabel;
                }
                this.currentToggleForm = button.closest('.toggle-form');
                this.openToggleModal();
            }

            openToggleModal() {
                if (this.toggleModal) {
                    this.toggleModal.style.display = 'flex';
                    this.toggleModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        const focusTarget = this.toggleModal.querySelector('button');
                        if (focusTarget) focusTarget.focus();
                    }, 150);
                }
            }

            closeToggleModal() {
                if (this.toggleModal) {
                    this.toggleModal.classList.remove('active');
                    this.toggleModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                this.currentToggleForm = null;
            }

            confirmToggle() {
                if (this.currentToggleForm) {
                    const row = this.currentToggleForm.closest('.estate-row');
                    if (row) {
                        row.style.opacity = '0.7';
                        row.style.transition = 'opacity 0.3s ease';
                    }
                    setTimeout(() => { this.currentToggleForm.submit(); }, 300);
                }
            }

            // ─── KEYBOARD EVENTS ───────────────────────────────
            bindGlobalKeyboardEvents() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        if (this.deleteModal && this.deleteModal.style.display === 'flex') {
                            this.closeDeleteModal();
                        }
                        if (this.approveModal && this.approveModal.style.display === 'flex') {
                            this.closeApproveModal();
                        }
                        if (this.rejectModal && this.rejectModal.style.display === 'flex') {
                            this.closeRejectModal();
                        }
                        if (this.toggleModal && this.toggleModal.style.display === 'flex') {
                            this.closeToggleModal();
                        }
                    }
                });
            }

            // ─── SEARCH ────────────────────────────────────────
            setupSearch() {
                if (this.searchInput && this.estateRows.length > 0) {
                    this.searchInput.addEventListener('input', (e) => {
                        const searchTerm = e.target.value.toLowerCase().trim();
                        this.estateRows.forEach(row => {
                            const name = row.getAttribute('data-name') || '';
                            const user = row.getAttribute('data-user') || '';
                            const uniqueId = row.getAttribute('data-unique-id') || '';
                            const nric = row.getAttribute('data-nric') || '';
                            row.style.display = (name.includes(searchTerm) || user.includes(searchTerm) || uniqueId.includes(searchTerm) || nric.includes(searchTerm)) ? '' : 'none';
                        });
                    });
                }
            }

            // ─── FILTERS ───────────────────────────────────────
            setupFilters() {
                const applyFilters = () => {
                    const params = new URLSearchParams();
                    if (this.searchInput && this.searchInput.value.trim()) params.set('search', this.searchInput.value.trim());
                    if (this.statusFilter && this.statusFilter.value) params.set('status', this.statusFilter.value);
                    if (this.approvalFilter && this.approvalFilter.value) params.set('approved', this.approvalFilter.value);
                    window.location.href = window.location.pathname + '?' + params.toString();
                };
                if (this.statusFilter) this.statusFilter.addEventListener('change', applyFilters);
                if (this.approvalFilter) this.approvalFilter.addEventListener('change', applyFilters);
                if (this.clearFiltersBtn) {
                    this.clearFiltersBtn.addEventListener('click', () => {
                        window.location.href = window.location.pathname;
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            window.navigationManager = new NavigationManager();
            window.estateManager = new EstateManager();
        });
    </script>
</body>
</html>