<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Manage Instant Estate • Neo Faraid Admin</title>
    <meta name="description" content="Manage all instant estate uploads, OCR results, and notifications in one place.">
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
            transition: var(--transition);
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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

        .btn-outline:hover {
            background: #f1f3f5;
            border-color: #ced4da;
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
            min-width: 900px; /* reduced min-width after column removal */
        }

        thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        th {
            padding: 1.25rem;
            text-align: left;
            font-weight: 600;
            color: var(--white);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--light-border);
            vertical-align: middle;
            color: var(--text-primary);
            font-size: 0.875rem;
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

        .status-uploaded { background: #e9ecef; color: #6c757d; }
        .status-processing_ocr { background: #fff3cd; color: #856404; }
        .status-ocr_completed { background: #d1ecf1; color: #0c5460; }
        .status-data_confirmed { background: #cce5ff; color: #004085; }
        .status-record_found { background: #d4edda; color: #155724; }
        .status-no_record { background: #e2e3e5; color: #383d41; }
        .status-captcha_verified { background: #d4edda; color: #155724; }
        .status-notification_requested { background: #fff3cd; color: #856404; }
        .status-email_sent { background: #d4edda; color: #155724; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .status-cancelled, .status-expired { background: #e2e3e5; color: #383d41; }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-show, .btn-approve, .btn-reject, .btn-email, .btn-delete {
            padding: 0.5rem 0.9rem;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--transition);
            border: none;
            box-shadow: var(--shadow-sm);
            white-space: nowrap;
            text-decoration: none;
        }
        .btn-show { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; }
        .btn-approve { background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%); color: white; }
        .btn-reject { background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%); color: #856404; }
        .btn-email { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; }
        .btn-delete { background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%); color: white; }
        .btn-show:hover, .btn-approve:hover, .btn-reject:hover, .btn-email:hover, .btn-delete:hover {
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
            transition: var(--transition);
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
            color: var(--text-primary);
            background: transparent;
            font-size: 0.9rem;
        }

        .search-box svg {
            width: 20px;
            height: 20px;
            color: var(--text-light);
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

        .pagination-info {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
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

        /* ========== MODAL STYLES ========== */
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
        .modal-overlay.active { display: flex; }

        .delete-modal, .approve-modal, .reject-modal, .confirm-modal {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 520px;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header-delete, .modal-header-approve, .modal-header-reject, .modal-header-confirm {
            padding: 1.5rem;
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
        }
        .modal-header-delete { background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%); }
        .modal-header-approve { background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%); }
        .modal-header-reject { background: linear-gradient(135deg, #f0932b 0%, #e67e22 100%); }
        .modal-header-confirm { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }

        .modal-header-delete h3, .modal-header-approve h3, .modal-header-reject h3, .modal-header-confirm h3 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body-delete, .modal-body-approve, .modal-body-reject, .modal-body-confirm {
            padding: 2rem 1.5rem;
        }

        .modal-body-delete p, .modal-body-approve p, .modal-body-reject p, .modal-body-confirm p {
            color: var(--text-primary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0 0 1rem 0;
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

        .modal-footer-delete, .modal-footer-approve, .modal-footer-reject, .modal-footer-confirm {
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

        .btn-confirm-delete, .btn-confirm-approve, .btn-confirm-reject, .btn-confirm-action {
            padding: 0.75rem 1.5rem;
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
        .btn-confirm-delete { background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%); }
        .btn-confirm-approve { background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%); }
        .btn-confirm-reject { background: linear-gradient(135deg, #f0932b 0%, #e67e22 100%); }
        .btn-confirm-action { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }

        textarea.form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--light-border);
            border-radius: var(--border-radius-md);
            font-family: inherit;
            font-size: 0.9rem;
            resize: vertical;
        }

        /* Toast & Loading */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            border-radius: var(--border-radius-md);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--success-color);
            z-index: 2200;
            display: flex;
            align-items: center;
            gap: 1rem;
            transform: translateX(120%);
            transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-width: 450px;
        }
        .toast-notification.show { transform: translateX(0); }
        .toast-notification.error { border-left-color: var(--danger-color); }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .loading-overlay.active { opacity: 1; pointer-events: all; }
        .loading-card {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius-lg);
            text-align: center;
        }
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e2e8f0;
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            color: #ced4da;
        }

        @media (max-width: 768px) {
            .admin-top-nav { height: 60px; padding: 0 1rem; }
            .admin-logo-text { display: none; }
            .admin-main { padding: 1.5rem; }
            .search-container { flex-direction: column; align-items: stretch; }
            .search-box { width: 100%; }
            .action-buttons { gap: 0.35rem; }
            .btn-show, .btn-approve, .btn-reject, .btn-email, .btn-delete { padding: 0.4rem 0.6rem; font-size: 0.7rem; }
            .modal-footer-delete, .modal-footer-approve, .modal-footer-reject, .modal-footer-confirm {
                flex-direction: column;
                gap: 0.75rem;
            }
            .btn-cancel, .btn-confirm-delete, .btn-confirm-approve, .btn-confirm-reject, .btn-confirm-action {
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
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-logo" aria-label="Neo Faraid Admin Home">
                <i class="fas fa-layer-group" style="color: var(--accent-color); font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3)); animation: logoFloat 4s ease-in-out infinite;"></i>
                <span class="admin-logo-text">Neo Faraid Admin</span>
            </a>
        </div>
        <div class="nav-right">
            <?php
                $user = auth()->user();
                $lastLogin = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently';
                $fullInitials = strtoupper(implode('', array_map(function($name) { return substr($name, 0, 1); }, explode(' ', $user->name, 2))));
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
                        <nav class="dropdown-nav" role="navigation">
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
                            <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="dropdown-nav-item active">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Manage Instant Estate</span>
                            </a>
                            <a href="<?php echo e(route('admin.estate-setup.index')); ?>" class="dropdown-nav-item <?php echo e(request()->is('admin/estate-setup*') ? 'active' : ''); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span>Manage Estate Planning</span>
                            </a>
                        </nav>
                    </div>
                    <div class="dropdown-section">
                        <h4 class="section-title">Account</h4>
                        <div class="account-links">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="account-link <?php echo e(request()->is('profile*') ? 'active' : ''); ?>" role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Profile Settings</span>
                            </a>
                        </div>
                    </div>
                    <div class="dropdown-footer">
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form">
                            <?php echo csrf_field(); ?>
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
                <h1>Manage Instant Estate</h1>
                <p class="page-subtitle">Uploaded death certificates, OCR results, approvals & notifications</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo e(route('admin.instant-estate.approval.pending')); ?>" class="btn btn-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Pending Reviews
                </a>
                <a href="<?php echo e(route('admin.instant-estate.export')); ?>" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export CSV
                </a>
            </div>
        </header>

        <div class="stats-cards">
            <div class="stats-card"><div class="stats-card-title">Total Sessions</div><div class="stats-card-value"><?php echo e($stats['total'] ?? 0); ?></div></div>
            <div class="stats-card"><div class="stats-card-title">Pending Reviews</div><div class="stats-card-value"><?php echo e($stats['pending_approval'] ?? 0); ?></div></div>
            <div class="stats-card"><div class="stats-card-title">Approved</div><div class="stats-card-value"><?php echo e($stats['approved'] ?? 0); ?></div></div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert-message alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert-message alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="search-container">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Session ID, deceased name, email..." id="search-input" value="<?php echo e(request('search')); ?>">
            </div>
            <div class="filter-group">
                <select id="status-filter" class="filter-select">
                    <option value="">All Statuses</option>
                    <?php $__currentLoopData = ['uploaded','processing_ocr','ocr_completed','data_confirmed','record_found','no_record','captcha_verified','notification_requested','email_sent','completed','failed','cancelled','expired']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php echo e(request('status')==$status?'selected':''); ?>><?php echo e(ucfirst(str_replace('_',' ',$status))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button id="clear-filters-btn" class="btn btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear Filters
                </button>
            </div>
        </div>

        <div class="table-container">
            <?php if(isset($sessions) && $sessions->count()): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Session ID</th>
                                <th>Deceased Name</th>
                                <th>User / Guest</th>
                                <th>Upload Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="instant-estate-table-body">
                            <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isValidUuid = $session->session_id && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $session->session_id);
                                
                                $userInfo = $session->user ? $session->user->name : ($session->guest_name?:'Guest');
                                if($session->guest_email) $userInfo .= ' ('.$session->guest_email.')';
                                $statusColorClass = 'status-'.$session->status;

                                $notificationRequest = $session->notificationRequest ?? null;
                                $notificationRequestId = null;
                                $notifStatus = 'none';
                                $canApprove = false;
                                $canReject = false;
                                $hasPdfReport = $session->has_pdf_report ?? false;
                                $canSend = false;
                                $canResend = false;
                                $showEmailActions = false;

                                if($session->notification_requested && $notificationRequest){
                                    $notificationRequestId = $notificationRequest->id;
                                    $notifStatus = $notificationRequest->status;
                                    if($notifStatus == 'pending_admin_approval') $notifStatus = 'pending';
                                    $canApprove = in_array($notifStatus,['pending','approved']) && $notifStatus !== 'rejected' && $notifStatus !== 'sent';
                                    $canReject = in_array($notifStatus,['pending','approved']) && $notifStatus !== 'rejected';
                                    $canSend = ($notifStatus === 'approved') || ($notifStatus === 'pending' && $hasPdfReport);
                                    $canResend = ($notifStatus === 'sent');
                                    $showEmailActions = $notificationRequestId && ($canApprove || $canReject || $canSend || $canResend);
                                } elseif($session->notification_requested){
                                    // notification requested but no model? consider as pending approval
                                    $canApprove = true;
                                    $canReject = true;
                                    $showEmailActions = true; // but no id, maybe handle?
                                    // In the original code it still shows actions; let's replicate
                                }
                            ?>
                            <tr class="session-row" data-session-id="<?php echo e($session->session_id); ?>" data-name="<?php echo e(strtolower($session->deceased_name ?? '')); ?>" data-user="<?php echo e(strtolower($userInfo)); ?>">
                                <td><code><?php echo e($session->session_id ?: 'N/A'); ?></code></td>
                                <td><strong><?php echo e($session->deceased_name ?: 'N/A'); ?></strong></td>
                                <td><?php echo e($userInfo); ?></td>
                                <td><?php echo e($session->created_at ? $session->created_at->format('d M Y, H:i') : 'N/A'); ?></td>
                                <td><span class="status-badge <?php echo e($statusColorClass); ?>"><?php echo e($session->status_label ?? ucfirst(str_replace('_',' ',$session->status))); ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if($isValidUuid): ?>
                                            <a href="<?php echo e(route('admin.instant-estate.show', $session->session_id)); ?>" class="btn-show">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>Show
                                            </a>
                                        <?php endif; ?>

                                        <?php if($showEmailActions && $isValidUuid): ?>
                                            <?php if($canApprove): ?>
                                                <button class="btn-approve approve-notif-btn" data-request-id="<?php echo e($notificationRequestId); ?>" data-session-name="<?php echo e(e($session->deceased_name ?: 'Session')); ?>">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Approve
                                                </button>
                                            <?php endif; ?>
                                            <?php if($canReject): ?>
                                                <button class="btn-reject reject-notif-btn" data-request-id="<?php echo e($notificationRequestId); ?>" data-session-name="<?php echo e(e($session->deceased_name ?: 'Session')); ?>">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Reject
                                                </button>
                                            <?php endif; ?>
                                            <?php if($canSend): ?>
                                                <button class="btn-email send-email-btn" data-request-id="<?php echo e($notificationRequestId); ?>" data-session-name="<?php echo e(e($session->deceased_name ?: 'Session')); ?>">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>Send Email
                                                </button>
                                            <?php endif; ?>
                                            <?php if($canResend): ?>
                                                <button class="btn-email resend-email-btn" data-request-id="<?php echo e($notificationRequestId); ?>" data-session-name="<?php echo e(e($session->deceased_name ?: 'Session')); ?>">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Resend
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($isValidUuid): ?>
                                            <button class="btn-delete delete-session-btn" data-id="<?php echo e($session->session_id); ?>" data-name="<?php echo e(e($session->deceased_name ?: 'Session #'.$session->session_id)); ?>" data-route="<?php echo e(route('admin.instant-estate.delete-session', $session->session_id)); ?>">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Delete
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php if($sessions->hasPages()): ?>
                <div class="pagination-wrapper">
                    <div class="pagination-info">Showing <?php echo e($sessions->firstItem()); ?>–<?php echo e($sessions->lastItem()); ?> of <?php echo e($sessions->total()); ?> sessions</div>
                    <div class="pagination">
                        <?php echo e($sessions->links()); ?>

                    </div>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h3>No Instant Estate Sessions Found</h3>
                    <p>Try adjusting your filters.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Delete form (submitted directly) -->
    <form id="delete-form" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal-overlay" style="display: none;">
        <div class="delete-modal">
            <div class="modal-header-delete">
                <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>Delete Session</h3>
            </div>
            <div class="modal-body-delete">
                <p>Permanently delete this session? <strong>This action cannot be undone.</strong></p>
                <div class="modal-info-box"><strong>Session Details</strong><span id="delete-session-name">—</span></div>
            </div>
            <div class="modal-footer-delete">
                <button class="btn-cancel" id="cancel-delete">Cancel</button>
                <button class="btn-confirm-delete" id="confirm-delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Yes, Delete</button>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approve-modal" class="modal-overlay" style="display: none;">
        <div class="approve-modal">
            <div class="modal-header-approve">
                <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>Approve Notification</h3>
            </div>
            <div class="modal-body-approve">
                <p>Approve this request? The user will receive the report email.</p>
                <div class="modal-info-box"><strong>Session</strong><span id="approve-session-name">—</span></div>
            </div>
            <div class="modal-footer-approve">
                <button class="btn-cancel" id="cancel-approve">Cancel</button>
                <button class="btn-confirm-approve" id="confirm-approve"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Yes, Approve</button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="modal-overlay" style="display: none;">
        <div class="reject-modal">
            <div class="modal-header-reject">
                <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Reject Request</h3>
            </div>
            <div class="modal-body-reject">
                <p>Reject this notification request. The user will not receive the report unless approved later.</p>
                <div class="modal-info-box"><strong>Session</strong><span id="reject-session-name">—</span></div>
                <div style="margin-top:1rem;">
                    <label for="rejectionReason" style="font-weight:600;display:block;margin-bottom:0.5rem;">Reason (optional):</label>
                    <textarea id="rejectionReason" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer-reject">
                <button class="btn-cancel" id="cancel-reject">Cancel</button>
                <button class="btn-confirm-reject" id="confirm-reject"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Yes, Reject</button>
            </div>
        </div>
    </div>

    <!-- Generic confirmation modal for email actions -->
    <div id="confirm-modal" class="modal-overlay" style="display: none;">
        <div class="confirm-modal">
            <div class="modal-header-confirm">
                <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Confirm Action</h3>
            </div>
            <div class="modal-body-confirm">
                <p id="confirm-message">Are you sure you want to proceed?</p>
                <div class="modal-info-box"><strong>Session</strong><span id="confirm-session-name">—</span></div>
            </div>
            <div class="modal-footer-confirm">
                <button class="btn-cancel" id="cancel-confirm">Cancel</button>
                <button class="btn-confirm-action" id="confirm-action-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span id="confirm-btn-text">Confirm</span></button>
            </div>
        </div>
    </div>

    <div id="toastMessage" class="toast-notification"><span id="toastIcon">✅</span><span id="toastText"></span></div>
    <div id="loadingOverlay" class="loading-overlay"><div class="loading-card"><div class="loading-spinner"></div><p>Processing...</p></div></div>

    <script>
        class NavigationManager {
            constructor() {
                this.selectors = { userProfileBtn: '#user-profile-btn', userProfileDropdown: '#user-profile-dropdown' };
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
                        this.toggleUserDropdown();
                    });
                }
                document.addEventListener('click', (e) => this.handleOutsideClick(e));
                document.addEventListener('keydown', (e) => this.handleKeydown(e));
            }
            handleOutsideClick(e) {
                if (this.state.isDropdownOpen && this.elements.userProfileDropdown && !this.elements.userProfileDropdown.contains(e.target) && !this.elements.userProfileBtn.contains(e.target)) {
                    this.closeUserDropdown();
                }
            }
            handleKeydown(e) {
                if (e.key === 'Escape' && this.state.isDropdownOpen) this.closeUserDropdown();
            }
            toggleUserDropdown() {
                this.state.isDropdownOpen ? this.closeUserDropdown() : this.openUserDropdown();
            }
            openUserDropdown() {
                this.state.isDropdownOpen = true;
                this.elements.userProfileDropdown.style.display = 'block';
                setTimeout(() => this.elements.userProfileDropdown.classList.add('active'), 10);
                this.elements.userProfileBtn.setAttribute('aria-expanded', 'true');
                const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            }
            closeUserDropdown() {
                this.state.isDropdownOpen = false;
                this.elements.userProfileDropdown.classList.remove('active');
                setTimeout(() => {
                    if (!this.state.isDropdownOpen) this.elements.userProfileDropdown.style.display = 'none';
                }, 300);
                this.elements.userProfileBtn.setAttribute('aria-expanded', 'false');
                const chevron = this.elements.userProfileBtn.querySelector('.chevron-icon');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        class InstantEstateManager {
            constructor() {
                this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                this.deleteModal = document.getElementById('delete-modal');
                this.approveModal = document.getElementById('approve-modal');
                this.rejectModal = document.getElementById('reject-modal');
                this.confirmModal = document.getElementById('confirm-modal');
                this.deleteForm = document.getElementById('delete-form');
                this.currentApproveId = null;
                this.currentRejectId = null;
                this.currentEmailRequestId = null;
                this.currentEmailAction = null;
                this.init();
            }
            init() {
                this.bindButtons();
                this.setupSearchFilters();
            }
            bindButtons() {
                document.querySelectorAll('.delete-session-btn').forEach(btn => btn.addEventListener('click', () => this.handleDelete(btn)));
                document.querySelectorAll('.approve-notif-btn').forEach(btn => btn.addEventListener('click', () => this.handleApprove(btn)));
                document.querySelectorAll('.reject-notif-btn').forEach(btn => btn.addEventListener('click', () => this.handleReject(btn)));
                document.querySelectorAll('.send-email-btn, .resend-email-btn').forEach(btn => btn.addEventListener('click', () => this.handleEmail(btn)));

                document.getElementById('cancel-delete')?.addEventListener('click', () => this.closeModal(this.deleteModal));
                document.getElementById('cancel-approve')?.addEventListener('click', () => this.closeModal(this.approveModal));
                document.getElementById('cancel-reject')?.addEventListener('click', () => this.closeModal(this.rejectModal));
                document.getElementById('cancel-confirm')?.addEventListener('click', () => this.closeModal(this.confirmModal));
                document.getElementById('confirm-delete')?.addEventListener('click', () => this.submitDelete());
                document.getElementById('confirm-approve')?.addEventListener('click', () => this.confirmApprove());
                document.getElementById('confirm-reject')?.addEventListener('click', () => this.confirmReject());
                document.getElementById('confirm-action-btn')?.addEventListener('click', () => this.confirmEmailAction());

                [this.deleteModal, this.approveModal, this.rejectModal, this.confirmModal].forEach(m => {
                    if (m) m.addEventListener('click', (e) => { if (e.target === m) this.closeModal(m); });
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        if (this.deleteModal?.classList.contains('active')) this.closeModal(this.deleteModal);
                        if (this.approveModal?.classList.contains('active')) this.closeModal(this.approveModal);
                        if (this.rejectModal?.classList.contains('active')) this.closeModal(this.rejectModal);
                        if (this.confirmModal?.classList.contains('active')) this.closeModal(this.confirmModal);
                    }
                });
            }
            handleDelete(btn) {
                this.deleteForm.action = btn.dataset.route;
                document.getElementById('delete-session-name').textContent = btn.dataset.name || 'Session';
                this.openModal(this.deleteModal);
            }
            submitDelete() {
                this.deleteForm.submit();
            }
            handleApprove(btn) {
                this.currentApproveId = btn.dataset.requestId;
                document.getElementById('approve-session-name').textContent = btn.dataset.sessionName;
                this.openModal(this.approveModal);
            }
            async confirmApprove() {
                if (!this.currentApproveId) return;
                const btn = document.getElementById('confirm-approve');
                btn.disabled = true;
                this.showLoading(true);
                this.closeModal(this.approveModal);
                try {
                    const resp = await fetch(`/admin/instant-estate/notifications/${this.currentApproveId}/approve`, { method: 'POST', headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' } });
                    const data = await resp.json();
                    if (data.success) {
                        this.showToast(data.message || 'Approved');
                        setTimeout(() => window.location.reload(), 1200);
                    } else this.showToast(data.message || 'Error', true);
                } catch (e) {
                    this.showToast('Network error', true);
                } finally {
                    this.showLoading(false);
                    btn.disabled = false;
                    this.currentApproveId = null;
                }
            }
            handleReject(btn) {
                this.currentRejectId = btn.dataset.requestId;
                document.getElementById('reject-session-name').textContent = btn.dataset.sessionName;
                document.getElementById('rejectionReason').value = '';
                this.openModal(this.rejectModal);
            }
            async confirmReject() {
                if (!this.currentRejectId) return;
                const reason = document.getElementById('rejectionReason').value.trim();
                const btn = document.getElementById('confirm-reject');
                btn.disabled = true;
                this.showLoading(true);
                this.closeModal(this.rejectModal);
                try {
                    const resp = await fetch(`/admin/instant-estate/notifications/${this.currentRejectId}/reject`, { method: 'POST', headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ reason }) });
                    const data = await resp.json();
                    if (data.success) {
                        this.showToast(data.message || 'Rejected');
                        setTimeout(() => window.location.reload(), 1200);
                    } else this.showToast(data.message || 'Error', true);
                } catch (e) {
                    this.showToast('Network error', true);
                } finally {
                    this.showLoading(false);
                    btn.disabled = false;
                    this.currentRejectId = null;
                }
            }
            handleEmail(btn) {
                const isResend = btn.classList.contains('resend-email-btn');
                this.currentEmailRequestId = btn.dataset.requestId;
                this.currentEmailAction = isResend ? 'resend' : 'send';
                document.getElementById('confirm-session-name').textContent = btn.dataset.sessionName;
                document.getElementById('confirm-message').textContent = `Are you sure you want to ${this.currentEmailAction} the email for this session?`;
                document.getElementById('confirm-btn-text').textContent = `Yes, ${this.currentEmailAction.charAt(0).toUpperCase() + this.currentEmailAction.slice(1)}`;
                this.openModal(this.confirmModal);
            }
            async confirmEmailAction() {
                if (!this.currentEmailRequestId) return;
                const btn = document.getElementById('confirm-action-btn');
                btn.disabled = true;
                this.showLoading(true);
                this.closeModal(this.confirmModal);
                try {
                    const resp = await fetch(`/admin/instant-estate/notifications/${this.currentEmailRequestId}/${this.currentEmailAction}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' } });
                    const data = await resp.json();
                    if (data.success) {
                        this.showToast(data.message || 'Email sent');
                        setTimeout(() => window.location.reload(), 1200);
                    } else this.showToast(data.message || 'Error', true);
                } catch (e) {
                    this.showToast('Network error', true);
                } finally {
                    this.showLoading(false);
                    btn.disabled = false;
                    this.currentEmailRequestId = null;
                    this.currentEmailAction = null;
                }
            }
            setupSearchFilters() {
                const searchInput = document.getElementById('search-input');
                const rows = document.querySelectorAll('.session-row');
                if (searchInput) {
                    searchInput.addEventListener('input', () => {
                        const term = searchInput.value.toLowerCase().trim();
                        rows.forEach(row => {
                            const match = row.dataset.name?.includes(term) || row.dataset.user?.includes(term) || row.dataset.sessionId?.includes(term);
                            row.style.display = match ? '' : 'none';
                        });
                    });
                }

                const statusFilter = document.getElementById('status-filter');
                const clearBtn = document.getElementById('clear-filters-btn');
                const applyServer = () => {
                    const params = new URLSearchParams();
                    if (statusFilter?.value) params.set('status', statusFilter.value);
                    if (searchInput?.value.trim()) params.set('search', searchInput.value.trim());
                    window.location.href = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                };
                statusFilter?.addEventListener('change', applyServer);
                clearBtn?.addEventListener('click', () => window.location.href = window.location.pathname);
            }
            openModal(modal) {
                if (modal) {
                    modal.classList.add('active');
                    modal.style.display = 'flex';
                    document.body.classList.add('modal-open');
                }
            }
            closeModal(modal) {
                if (modal) {
                    modal.classList.remove('active');
                    modal.style.display = 'none';
                    if (!document.querySelector('.modal-overlay.active')) document.body.classList.remove('modal-open');
                }
            }
            showToast(msg, isError = false) {
                const toast = document.getElementById('toastMessage');
                const icon = document.getElementById('toastIcon');
                const text = document.getElementById('toastText');
                if (!toast) return;
                icon.textContent = isError ? '❌' : '✅';
                text.textContent = msg;
                toast.classList.add('show');
                if (isError) toast.classList.add('error');
                else toast.classList.remove('error');
                setTimeout(() => toast.classList.remove('show'), 4000);
            }
            showLoading(show) {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    if (show) {
                        overlay.classList.add('active');
                        document.body.classList.add('modal-open');
                    } else {
                        overlay.classList.remove('active');
                        if (!document.querySelector('.modal-overlay.active')) document.body.classList.remove('modal-open');
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            window.navigationManager = new NavigationManager();
            window.instantEstateManager = new InstantEstateManager();
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/admin/instant-estate/index.blade.php ENDPATH**/ ?>