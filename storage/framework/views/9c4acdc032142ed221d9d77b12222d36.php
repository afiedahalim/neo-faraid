<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Dashboard • Neo Faraid</title>
    
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #1a5fb4;
            --primary-dark: #0d2d5c;
            --primary-light: #e8f1fd;
            --secondary-color: #2d7ad6;
            --accent-color: #ffd700;
            --success-color: #25D366;
            --success-dark: #128C7E;
            --success-light: #d4edda;
            --danger-color: #dc3545;
            --danger-dark: #c82333;
            --danger-light: #f8d7da;
            --warning-color: #ffc107;
            --warning-dark: #e0a800;
            --warning-light: #fff3cd;
            --info-color: #17a2b8;
            --info-dark: #138496;
            --info-light: #d1ecf1;
            --purple-color: #6f42c1;
            --purple-light: #e8e1f5;
            --orange-color: #fd7e14;
            --orange-light: #ffe5d0;
            --teal-color: #20c997;
            --teal-light: #d2f4ea;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
            --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.15);
            --border-radius-sm: 12px;
            --border-radius-md: 15px;
            --border-radius-lg: 20px;
            --border-radius-xl: 50px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 50%, #f0f4f8 100%);
            color: var(--gray-700);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            width: 100%;
        }

        /* ===== TOP NAVIGATION BAR ===== */
        .admin-topbar {
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
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .topbar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--white);
            font-weight: 700;
            font-size: 1.3rem;
            transition: var(--transition);
        }

        .topbar-logo:hover {
            opacity: 0.9;
        }

        .topbar-logo i {
            color: var(--accent-color);
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 4px rgba(255,215,0,0.3));
            animation: logoFloat 4s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }

        .topbar-logo-text {
            background: linear-gradient(135deg, #ffffff 0%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            background: linear-gradient(135deg, var(--accent-color) 0%, #ffed4e 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--primary-dark);
            flex-shrink: 0;
            position: relative;
        }

        .user-avatar .admin-dot {
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

        .user-info-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .user-name-text {
            font-weight: 600;
            font-size: 0.9rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-role-text {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .btn-logout-top {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: var(--border-radius-md);
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            text-decoration: none;
            font-size: 0.85rem;
            border: none;
        }

        .btn-logout-top:hover {
            background: rgba(220, 53, 69, 0.3);
            border-color: rgba(220, 53, 69, 0.5);
        }

        /* ===== DASHBOARD HEADER ===== */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: white;
            padding: 50px 20px 40px;
            border-radius: 0 0 40px 40px;
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
            width: 100%;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,215,0,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(45,122,214,0.2) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.8;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .welcome-text h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            color: white;
            letter-spacing: -0.5px;
        }

        .welcome-text .greeting-emoji {
            display: inline-block;
            animation: wave 2s ease-in-out infinite;
        }

        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .welcome-text p {
            font-size: 1.1rem;
            opacity: 0.85;
            max-width: 700px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .quick-stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .quick-stat {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius-md);
            padding: 20px;
            text-align: center;
            transition: var(--transition);
        }

        .quick-stat:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: var(--shadow-lg);
        }

        .quick-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--accent-color);
            margin-bottom: 5px;
            display: block;
            line-height: 1;
        }

        .quick-stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        /* ===== MAIN CONTAINER ===== */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem 60px;
            width: 100%;
        }

        /* ===== MANAGEMENT NAVIGATION ===== */
        .management-nav {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid var(--gray-200);
            width: 100%;
        }

        .management-nav-header {
            padding: 1rem 1.5rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            font-weight: 700;
            color: var(--primary-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .management-nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0;
        }

        .management-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 20px;
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            border-right: 1px solid var(--gray-100);
            border-bottom: 1px solid var(--gray-100);
            position: relative;
        }

        .management-nav-item:hover {
            background: var(--gray-50);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
            z-index: 1;
        }

        .management-nav-item.active {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.08) 0%, rgba(45, 122, 214, 0.08) 100%);
            color: var(--primary-color);
            font-weight: 600;
        }

        .management-nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10%;
            bottom: 10%;
            width: 4px;
            background: var(--primary-color);
            border-radius: 0 4px 4px 0;
        }

        .nav-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Management Navigation Icon Colors */
        .nav-icon-users { background: var(--primary-light); color: var(--primary-color); }
        .nav-icon-feedback { background: var(--warning-light); color: var(--warning-dark); }
        .nav-icon-faq { background: var(--success-light); color: var(--success-dark); }
        .nav-icon-calculations { background: var(--purple-light); color: var(--purple-color); }
        .nav-icon-instant { background: var(--danger-light); color: var(--danger-dark); }
        .nav-icon-estate { background: var(--orange-light); color: var(--orange-color); }
        .nav-icon-faraid { background: var(--teal-light); color: var(--teal-color); }

        .nav-item-label {
            font-weight: 500;
        }

        .nav-item-badge {
            margin-left: auto;
            background: var(--gray-100);
            color: var(--gray-600);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            min-width: 24px;
            text-align: center;
        }

        .nav-item-badge.red { background: var(--danger-light); color: var(--danger-dark); }
        .nav-item-badge.green { background: var(--success-light); color: var(--success-dark); }
        .nav-item-badge.orange { background: var(--orange-light); color: var(--orange-color); }
        .nav-item-badge.purple { background: var(--purple-light); color: var(--purple-color); }
        .nav-item-badge.yellow { background: var(--warning-light); color: var(--warning-dark); }
        .nav-item-badge.blue { background: var(--primary-light); color: var(--primary-color); }

        /* ===== SECTION HEADER ===== */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
        }

        /* ===== STATS CARDS GRID - 3 COLUMNS ===== */
        .stats-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--gray-200);
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: transparent;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            transition: var(--transition);
        }

        /* Top border colors - Aligned with Management Sections */
        .stat-card.card-users::after { background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); }
        .stat-card.card-feedback::after { background: linear-gradient(90deg, var(--warning-color), #ffd54f); }
        .stat-card.card-faq::after { background: linear-gradient(90deg, var(--success-color), #34d399); }
        .stat-card.card-calculations::after { background: linear-gradient(90deg, var(--purple-color), #a78bfa); }
        .stat-card.card-instant::after { background: linear-gradient(90deg, var(--danger-color), #f87171); }
        .stat-card.card-estate::after { background: linear-gradient(90deg, var(--orange-color), #ffb347); }

        /* Icon circle colors - Aligned with Management Sections */
        .stat-icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .card-users .stat-icon-circle { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; }
        .card-feedback .stat-icon-circle { background: linear-gradient(135deg, var(--warning-color), #ffd54f); color: white; }
        .card-faq .stat-icon-circle { background: linear-gradient(135deg, var(--success-color), #34d399); color: white; }
        .card-calculations .stat-icon-circle { background: linear-gradient(135deg, var(--purple-color), #a78bfa); color: white; }
        .card-instant .stat-icon-circle { background: linear-gradient(135deg, var(--danger-color), #f87171); color: white; }
        .card-estate .stat-icon-circle { background: linear-gradient(135deg, var(--orange-color), #ffb347); color: white; }

        .stat-info {
            flex: 1;
            min-width: 0;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.25rem;
            letter-spacing: -1px;
        }

        .card-users .stat-number { color: var(--primary-color); }
        .card-feedback .stat-number { color: var(--warning-dark); }
        .card-faq .stat-number { color: var(--success-dark); }
        .card-calculations .stat-number { color: var(--purple-color); }
        .card-instant .stat-number { color: var(--danger-dark); }
        .card-estate .stat-number { color: var(--orange-color); }

        .stat-label-text {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
            margin-bottom: 0.75rem;
        }

        .stat-sub-items {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .stat-sub-item {
            font-size: 0.75rem;
            color: var(--gray-500);
            background: var(--gray-100);
            padding: 0.25rem 0.6rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .stat-link-arrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .stat-link-arrow:hover {
            gap: 10px;
            color: var(--primary-dark);
        }

        .stat-link-arrow i {
            font-size: 0.75rem;
            transition: var(--transition);
        }

        .stat-link-arrow:hover i {
            transform: translateX(3px);
        }

        /* ===== ACTIVITY SECTION ===== */
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .activity-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid var(--gray-200);
        }

        .activity-card:hover {
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }

        .activity-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--gray-50);
        }

        .activity-card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .activity-card-header h3 i {
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .activity-card-body {
            padding: 0;
            max-height: 450px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition);
            gap: 1rem;
        }

        .activity-item:hover {
            background: linear-gradient(90deg, rgba(26, 95, 180, 0.03) 0%, transparent 100%);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            min-width: 0;
        }

        .activity-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
            color: white;
        }

        .avatar-blue { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); }
        .avatar-green { background: linear-gradient(135deg, var(--success-color), #34d399); }
        .avatar-purple { background: linear-gradient(135deg, var(--purple-color), #a78bfa); }
        .avatar-orange { background: linear-gradient(135deg, var(--warning-color), var(--orange-color)); }
        .avatar-teal { background: linear-gradient(135deg, var(--teal-color), #34d399); }

        .activity-details {
            flex: 1;
            min-width: 0;
        }

        .activity-details h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .activity-details p {
            font-size: 0.8rem;
            color: var(--gray-500);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .activity-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
            flex-shrink: 0;
        }

        .activity-time {
            font-size: 0.75rem;
            color: var(--gray-400);
            white-space: nowrap;
        }

        .activity-badge {
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .badge-success { background: var(--success-light); color: var(--success-dark); }
        .badge-warning { background: var(--warning-light); color: var(--warning-dark); }
        .badge-info { background: var(--info-light); color: var(--info-dark); }
        .badge-danger { background: var(--danger-light); color: var(--danger-dark); }
        .badge-primary { background: var(--primary-light); color: var(--primary-color); }
        .badge-purple { background: var(--purple-light); color: var(--purple-color); }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-400);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state h4 {
            color: var(--gray-500);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .empty-state p {
            font-size: 0.85rem;
        }

        /* ===== BUTTONS ===== */
        .btn {
            padding: 10px 20px;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            border: none;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--gray-300);
            color: var(--gray-600);
        }

        .btn-outline:hover {
            background: var(--gray-100);
            border-color: var(--gray-400);
        }

        /* ===== FOOTER ===== */
        .dashboard-footer {
            text-align: center;
            padding: 2rem;
            border-top: 1px solid var(--gray-200);
            margin-top: 2rem;
        }

        .footer-content {
            display: inline-flex;
            align-items: center;
            gap: 2rem;
            background: var(--white);
            padding: 1.25rem 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .footer-user-info {
            text-align: left;
        }

        .footer-user-name {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .footer-user-role {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        .btn-logout-footer {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--danger-color), var(--danger-dark));
            color: white;
            border: none;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
            font-size: 0.85rem;
        }

        .btn-logout-footer:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .management-nav-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .activity-grid {
                grid-template-columns: 1fr;
            }
            .stats-cards-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 992px) {
            .stats-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .admin-topbar {
                padding: 0 1rem;
                height: 60px;
            }
            .topbar-logo-text {
                display: none;
            }
            .user-info-text {
                display: none;
            }
            .dashboard-header {
                padding: 30px 15px 25px;
                border-radius: 0 0 25px 25px;
            }
            .welcome-text h1 {
                font-size: 1.5rem;
            }
            .dashboard-container {
                padding: 0 1rem 40px;
            }
            .management-nav-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-cards-grid {
                grid-template-columns: 1fr;
            }
            .quick-stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .section-title {
                font-size: 1.25rem;
            }
            .footer-content {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .management-nav-grid {
                grid-template-columns: 1fr;
            }
            .quick-stats-row {
                grid-template-columns: 1fr;
            }
            .stat-card {
                padding: 1.25rem;
            }
            .stat-icon-circle {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }
            .stat-number {
                font-size: 1.5rem;
            }
        }

        /* ===== SCROLLBAR ===== */
        .activity-card-body::-webkit-scrollbar {
            width: 5px;
        }
        .activity-card-body::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 10px;
        }
        .activity-card-body::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 10px;
        }
        .activity-card-body::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* ===== RIPPLE EFFECT ===== */
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* ===== PRINT ===== */
        @media print {
            .admin-topbar, .btn-logout-footer, .btn, .management-nav {
                display: none;
            }
            body {
                background: white;
            }
        }
    </style>
</head>
<body>
    <!-- ===== TOP NAVIGATION BAR ===== -->
    <nav class="admin-topbar" role="navigation" aria-label="Admin navigation">
        <div class="topbar-left">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="topbar-logo">
                <i class="fas fa-layer-group"></i>
                <span class="topbar-logo-text">Neo Faraid Admin</span>
            </a>
        </div>
        <div class="topbar-right">
            <a href="<?php echo e(route('profile.edit')); ?>" class="user-profile-btn" title="Profile Settings">
                <div class="user-avatar">
                    <?php echo e(strtoupper(substr(auth()->user()->name ?? 'A', 0, 1))); ?>

                    <span class="admin-dot">A</span>
                </div>
                <div class="user-info-text">
                    <span class="user-name-text"><?php echo e(auth()->user()->name ?? 'Admin'); ?></span>
                    <span class="user-role-text">Administrator</span>
                </div>
            </a>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-logout-top" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
        <!-- DASHBOARD HEADER -->
        <div class="dashboard-header">
            <div class="header-content">
                <div class="welcome-text">
                    <h1>
                        <span class="greeting-emoji">👋</span> 
                        Welcome back, <?php echo e(auth()->user()->name ?? 'Admin'); ?>!
                    </h1>
                    <p>Comprehensive admin dashboard for managing all aspects of the Neo Faraid platform including users, feedback, FAQs, calculations, instant estate sessions, and estate pre-registrations.</p>
                </div>
                
                <div class="quick-stats-row">
                    <div class="quick-stat">
                        <span class="quick-stat-value"><?php echo e(number_format((int)($totalUsers ?? 0))); ?></span>
                        <span class="quick-stat-label">Total Users</span>
                    </div>
                    <div class="quick-stat">
                        <span class="quick-stat-value"><?php echo e(number_format((int)($totalFeedback ?? 0))); ?></span>
                        <span class="quick-stat-label">Feedback</span>
                    </div>
                    <div class="quick-stat">
                        <span class="quick-stat-value"><?php echo e(number_format((int)($totalFaqs ?? 0))); ?></span>
                        <span class="quick-stat-label">FAQs</span>
                    </div>
                    <div class="quick-stat">
                        <span class="quick-stat-value"><?php echo e(number_format((int)($totalCalculations ?? 0))); ?></span>
                        <span class="quick-stat-label">Calculations</span>
                    </div>
                    <div class="quick-stat">
                        <span class="quick-stat-value"><?php echo e(number_format((int)($totalInstantSessions ?? 0))); ?></span>
                        <span class="quick-stat-label">Instant Estate</span>
                    </div>
                    <div class="quick-stat">
                        <span class="quick-stat-value"><?php echo e(number_format((int)($totalEstateSetup ?? 0))); ?></span>
                        <span class="quick-stat-label">Estate Setup</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MAIN DASHBOARD CONTAINER -->
        <div class="dashboard-container">
            
            <!-- ===== MANAGEMENT NAVIGATION ===== -->
            <div class="management-nav">
                <div class="management-nav-header">
                    <i class="fas fa-compass"></i> Management Sections
                </div>
                <div class="management-nav-grid">
                    
                    <?php if(Route::has('admin.users.index')): ?>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="management-nav-item <?php echo e(request()->is('admin/users*') ? 'active' : ''); ?>">
                        <div class="nav-icon-wrapper nav-icon-users">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-item-label">Users</span>
                        <span class="nav-item-badge blue"><?php echo e(number_format((int)($totalUsers ?? 0))); ?></span>
                    </a>
                    <?php endif; ?>

                    
                    <?php if(Route::has('admin.feedback.index')): ?>
                    <a href="<?php echo e(route('admin.feedback.index')); ?>" class="management-nav-item <?php echo e(request()->is('admin/feedback*') ? 'active' : ''); ?>">
                        <div class="nav-icon-wrapper nav-icon-feedback">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <span class="nav-item-label">Feedback</span>
                        <span class="nav-item-badge yellow"><?php echo e(number_format((int)($totalFeedback ?? 0))); ?></span>
                    </a>
                    <?php endif; ?>

                    
                    <?php if(Route::has('admin.faq.index')): ?>
                    <a href="<?php echo e(route('admin.faq.index')); ?>" class="management-nav-item <?php echo e(request()->is('admin/faq*') ? 'active' : ''); ?>">
                        <div class="nav-icon-wrapper nav-icon-faq">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span class="nav-item-label">FAQ</span>
                        <span class="nav-item-badge green"><?php echo e(number_format((int)($totalFaqs ?? 0))); ?></span>
                    </a>
                    <?php endif; ?>

                    
                    <?php if(Route::has('admin.calculations.index')): ?>
                    <a href="<?php echo e(route('admin.calculations.index')); ?>" class="management-nav-item <?php echo e(request()->is('admin/calculations*') ? 'active' : ''); ?>">
                        <div class="nav-icon-wrapper nav-icon-calculations">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <span class="nav-item-label">Calculations</span>
                        <span class="nav-item-badge purple"><?php echo e(number_format((int)($totalCalculations ?? 0))); ?></span>
                    </a>
                    <?php endif; ?>

                    
                    <?php if(Route::has('admin.instant-estate.index')): ?>
                    <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="management-nav-item <?php echo e(request()->is('admin/instant-estate*') ? 'active' : ''); ?>">
                        <div class="nav-icon-wrapper nav-icon-instant">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <span class="nav-item-label">Instant Estate</span>
                        <span class="nav-item-badge red"><?php echo e(number_format((int)($totalInstantSessions ?? 0))); ?></span>
                    </a>
                    <?php endif; ?>

                    
                    <?php if(Route::has('admin.estate-setup.index')): ?>
                    <a href="<?php echo e(route('admin.estate-setup.index')); ?>" class="management-nav-item <?php echo e(request()->is('admin/estate-setup*') ? 'active' : ''); ?>">
                        <div class="nav-icon-wrapper nav-icon-estate">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="nav-item-label">Estate Setup</span>
                        <span class="nav-item-badge orange"><?php echo e(number_format((int)($totalEstateSetup ?? 0))); ?></span>
                    </a>
                    <?php endif; ?>

                    
                    <?php if(Route::has('database.seeders.FaraidCalculationRulesSeeder')): ?>
                    <a href="<?php echo e(route('database.seeders.FaraidCalculationRulesSeeder')); ?>" class="management-nav-item <?php echo e(request()->is('admin/faraid-rules*') ? 'active' : ''); ?>">
                        <div class="nav-icon-wrapper nav-icon-faraid">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <span class="nav-item-label">Faraid Rules</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== STATISTICS OVERVIEW - 3 COLUMNS ===== -->
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-chart-pie"></i> Statistics Overview
                </h2>
            </div>

            <div class="stats-cards-grid">
                
                <div class="stat-card card-users" onclick="window.location.href='<?php echo e(Route::has('admin.users.index') ? route('admin.users.index') : '#'); ?>'">
                    <div class="stat-icon-circle">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <?php $totalUsers = $totalUsers ?? 0; ?>
                        <div class="stat-number"><?php echo e(number_format((int)$totalUsers)); ?></div>
                        <div class="stat-label-text">Registered Users</div>
                        <div class="stat-sub-items">
                            <span class="stat-sub-item">Admins: <?php echo e(number_format((int)($adminCount ?? 0))); ?></span>
                            <span class="stat-sub-item">Active: <?php echo e(number_format((int)($activeUserCount ?? 0))); ?></span>
                        </div>
                        <?php if(Route::has('admin.users.index')): ?>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="stat-link-arrow">
                            Manage Users <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="stat-card card-feedback" onclick="window.location.href='<?php echo e(Route::has('admin.feedback.index') ? route('admin.feedback.index') : '#'); ?>'">
                    <div class="stat-icon-circle">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <?php $totalFeedback = $totalFeedback ?? 0; ?>
                        <div class="stat-number"><?php echo e(number_format((int)$totalFeedback)); ?></div>
                        <div class="stat-label-text">Total Feedback</div>
                        <div class="stat-sub-items">
                            <span class="stat-sub-item">Rejected: <?php echo e(number_format((int)($rejectedFeedback ?? 0))); ?></span>
                            <span class="stat-sub-item">Approved: <?php echo e(number_format((int)($approvedFeedback ?? 0))); ?></span>
                        </div>
                        <?php if(Route::has('admin.feedback.index')): ?>
                        <a href="<?php echo e(route('admin.feedback.index')); ?>" class="stat-link-arrow">
                            Manage Feedback <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="stat-card card-faq" onclick="window.location.href='<?php echo e(Route::has('admin.faq.index') ? route('admin.faq.index') : '#'); ?>'">
                    <div class="stat-icon-circle">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stat-info">
                        <?php $totalFaqs = $totalFaqs ?? 0; ?>
                        <div class="stat-number"><?php echo e(number_format((int)$totalFaqs)); ?></div>
                        <div class="stat-label-text">FAQ Entries</div>
                        <div class="stat-sub-items">
                            <span class="stat-sub-item">Published: <?php echo e(number_format((int)($publishedFaqs ?? 0))); ?></span>
                            <span class="stat-sub-item">Categories: <?php echo e(number_format((int)($faqCategories ?? 0))); ?></span>
                        </div>
                        <?php if(Route::has('admin.faq.index')): ?>
                        <a href="<?php echo e(route('admin.faq.index')); ?>" class="stat-link-arrow">
                            Manage FAQ <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="stat-card card-calculations" onclick="window.location.href='<?php echo e(Route::has('admin.calculations.index') ? route('admin.calculations.index') : '#'); ?>'">
                    <div class="stat-icon-circle">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="stat-info">
                        <?php $totalCalculations = $totalCalculations ?? 0; ?>
                        <div class="stat-number"><?php echo e(number_format((int)$totalCalculations)); ?></div>
                        <div class="stat-label-text">Faraid Calculations</div>
                        <div class="stat-sub-items">
                            <span class="stat-sub-item">Total Calculation: <?php echo e(number_format((int)($totalCalculations ?? 0))); ?></span>
                            <span class="stat-sub-item">Total Asset: <?php echo e(number_format((int)($totalAssets ?? 0))); ?></span>
                        </div>
                        <?php if(Route::has('admin.calculations.index')): ?>
                        <a href="<?php echo e(route('admin.calculations.index')); ?>" class="stat-link-arrow">
                            View Calculations <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="stat-card card-instant" onclick="window.location.href='<?php echo e(Route::has('admin.instant-estate.index') ? route('admin.instant-estate.index') : '#'); ?>'">
                    <div class="stat-icon-circle">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="stat-info">
                        <?php $totalInstantSessions = $totalInstantSessions ?? 0; ?>
                        <div class="stat-number"><?php echo e(number_format((int)$totalInstantSessions)); ?></div>
                        <div class="stat-label-text">Instant Sessions</div>
                        <div class="stat-sub-items">
                            <span class="stat-sub-item">Completed: <?php echo e(number_format((int)($completedSessions ?? 0))); ?></span>
                            <span class="stat-sub-item">Processing: <?php echo e(number_format((int)($processingSessions ?? 0))); ?></span>
                        </div>
                        <?php if(Route::has('admin.instant-estate.index')): ?>
                        <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="stat-link-arrow">
                            View Sessions <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="stat-card card-estate" onclick="window.location.href='<?php echo e(Route::has('admin.estate-setup.index') ? route('admin.estate-setup.index') : '#'); ?>'">
                    <div class="stat-icon-circle">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-info">
                        <?php $totalEstateSetup = $totalEstateSetup ?? 0; ?>
                        <div class="stat-number"><?php echo e(number_format((int)$totalEstateSetup)); ?></div>
                        <div class="stat-label-text">Pre-Registrations</div>
                        <div class="stat-sub-items">
                            <span class="stat-sub-item">Activated: <?php echo e(number_format((int)($activatedEstates ?? 0))); ?></span>
                            <span class="stat-sub-item">Draft: <?php echo e(number_format((int)($draftEstates ?? 0))); ?></span>
                        </div>
                        <?php if(Route::has('admin.estate-setup.index')): ?>
                        <a href="<?php echo e(route('admin.estate-setup.index')); ?>" class="stat-link-arrow">
                            Manage Estates <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ===== RECENT ACTIVITY ===== -->
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-clock"></i> Recent Activity
                </h2>
            </div>

            <div class="activity-grid">
                
                <div class="activity-card">
                    <div class="activity-card-header">
                        <h3><i class="fas fa-user-plus"></i> Recent Users</h3>
                        <?php if(Route::has('admin.users.index')): ?>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-sm btn-primary">View All</a>
                        <?php endif; ?>
                    </div>
                    <div class="activity-card-body">
                        <?php $users = $users ?? collect(); ?>
                        <?php if($users->count() > 0): ?>
                            <?php $__currentLoopData = $users->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="activity-item">
                                    <div class="activity-left">
                                        <div class="activity-avatar avatar-blue">
                                            <?php echo e(strtoupper(substr($user->name ?? 'U', 0, 1))); ?>

                                        </div>
                                        <div class="activity-details">
                                            <h4><?php echo e($user->name ?? 'Unknown User'); ?></h4>
                                            <p><?php echo e($user->email ?? 'No email'); ?></p>
                                        </div>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="activity-time">
                                            <?php if(isset($user->created_at) && is_object($user->created_at)): ?>
                                                <?php echo e($user->created_at->diffForHumans()); ?>

                                            <?php else: ?>
                                                Recently
                                            <?php endif; ?>
                                        </span>
                                        <span class="activity-badge <?php echo e(($user->role ?? 'user') === 'admin' ? 'badge-primary' : 'badge-info'); ?>">
                                            <?php echo e(ucfirst($user->role ?? 'user')); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <h4>No users registered yet</h4>
                                <p>New users will appear here</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="activity-card">
                    <div class="activity-card-header">
                        <h3><i class="fas fa-comments"></i> Recent Feedback</h3>
                        <?php if(Route::has('admin.feedback.index')): ?>
                        <a href="<?php echo e(route('admin.feedback.index')); ?>" class="btn btn-sm btn-primary">View All</a>
                        <?php endif; ?>
                    </div>
                    <div class="activity-card-body">
                        <?php $recentFeedback = $recentFeedback ?? collect(); ?>
                        <?php if($recentFeedback->count() > 0): ?>
                            <?php $__currentLoopData = $recentFeedback->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="activity-item">
                                    <div class="activity-left">
                                        <div class="activity-avatar avatar-purple">
                                            <?php echo e(strtoupper(substr(optional($feedback->user)->name ?? 'A', 0, 1))); ?>

                                        </div>
                                        <div class="activity-details">
                                            <h4><?php echo e(optional($feedback->user)->name ?? 'Anonymous User'); ?></h4>
                                            <p><?php echo e(\Illuminate\Support\Str::limit($feedback->message ?? 'No message', 60)); ?></p>
                                        </div>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="activity-time">
                                            <?php if(isset($feedback->created_at) && is_object($feedback->created_at)): ?>
                                                <?php echo e($feedback->created_at->diffForHumans()); ?>

                                            <?php else: ?>
                                                Recently
                                            <?php endif; ?>
                                        </span>
                                        <?php
                                            $fbStatus = $feedback->status ?? 'rejected';
                                            $fbBadgeClass = match($fbStatus) {
                                                'approved' => 'badge-success',
                                                'rejected' => 'badge-danger',
                                                'resolved' => 'badge-success',
                                                default => 'badge-warning'
                                            };
                                        ?>
                                        <span class="activity-badge <?php echo e($fbBadgeClass); ?>">
                                            <?php echo e(ucfirst($fbStatus)); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-comment-slash"></i>
                                <h4>No feedback submitted yet</h4>
                                <p>User feedback will appear here</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="activity-card">
                    <div class="activity-card-header">
                        <h3><i class="fas fa-calculator"></i> Recent Calculations</h3>
                        <?php if(Route::has('admin.calculations.index')): ?>
                        <a href="<?php echo e(route('admin.calculations.index')); ?>" class="btn btn-sm btn-primary">View All</a>
                        <?php endif; ?>
                    </div>
                    <div class="activity-card-body">
                        <?php $recentCalculations = $recentCalculations ?? collect(); ?>
                        <?php if($recentCalculations->count() > 0): ?>
                            <?php $__currentLoopData = $recentCalculations->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="activity-item">
                                    <div class="activity-left">
                                        <div class="activity-avatar avatar-teal">
                                            <?php echo e(strtoupper(substr($calc->deceased_name ?? 'D', 0, 1))); ?>

                                        </div>
                                        <div class="activity-details">
                                            <h4><?php echo e($calc->deceased_name ?? 'Unknown'); ?></h4>
                                            <p>
                                                <?php echo e(optional($calc->user)->name ?? 'Unknown User'); ?> 
                                                <?php if(isset($calc->total_estate)): ?>
                                                    • RM <?php echo e(number_format($calc->total_estate, 0)); ?>

                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="activity-time">
                                            <?php if(isset($calc->created_at) && is_object($calc->created_at)): ?>
                                                <?php echo e($calc->created_at->diffForHumans()); ?>

                                            <?php else: ?>
                                                Recently
                                            <?php endif; ?>
                                        </span>
                                        <span class="activity-badge badge-primary">Calculation</span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-calculator"></i>
                                <h4>No calculations performed yet</h4>
                                <p>Faraid calculations will appear here</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="activity-card">
                    <div class="activity-card-header">
                        <h3><i class="fas fa-bolt"></i> Recent Instant Sessions</h3>
                        <?php if(Route::has('admin.instant-estate.index')): ?>
                        <a href="<?php echo e(route('admin.instant-estate.index')); ?>" class="btn btn-sm btn-primary">View All</a>
                        <?php endif; ?>
                    </div>
                    <div class="activity-card-body">
                        <?php $recentSessions = $recentSessions ?? collect(); ?>
                        <?php if($recentSessions->count() > 0): ?>
                            <?php $__currentLoopData = $recentSessions->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $deceasedName = isset($session->extracted_data['deceased_name']) 
                                        ? $session->extracted_data['deceased_name'] 
                                        : ($session->deceased_name ?? 'Not extracted');
                                    $sesStatus = $session->status ?? 'unknown';
                                    $sesBadgeClass = match($sesStatus) {
                                        'auto_filled' => 'badge-success',
                                        'ocr_completed' => 'badge-info',
                                        'processing_ocr' => 'badge-warning',
                                        'failed' => 'badge-danger',
                                        default => 'badge-primary'
                                    };
                                    $sesStatusLabel = match($sesStatus) {
                                        'auto_filled' => 'Completed',
                                        'ocr_completed' => 'OCR Done',
                                        'processing_ocr' => 'Processing',
                                        'failed' => 'Failed',
                                        default => ucfirst(str_replace('_', ' ', $sesStatus))
                                    };
                                ?>
                                <div class="activity-item">
                                    <div class="activity-left">
                                        <div class="activity-avatar avatar-orange">
                                            <?php echo e(strtoupper(substr($deceasedName, 0, 1))); ?>

                                        </div>
                                        <div class="activity-details">
                                            <h4><?php echo e($deceasedName); ?></h4>
                                            <p><?php echo e(\Illuminate\Support\Str::limit($session->original_filename ?? 'Unknown file', 50)); ?></p>
                                        </div>
                                    </div>
                                    <div class="activity-meta">
                                        <span class="activity-time">
                                            <?php if(isset($session->created_at) && is_object($session->created_at)): ?>
                                                <?php echo e($session->created_at->diffForHumans()); ?>

                                            <?php else: ?>
                                                Recently
                                            <?php endif; ?>
                                        </span>
                                        <span class="activity-badge <?php echo e($sesBadgeClass); ?>"><?php echo e($sesStatusLabel); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <h4>No instant estate sessions yet</h4>
                                <p>Instant estate processing sessions will appear here</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ===== FOOTER ===== -->
            <div class="dashboard-footer">
                <div class="footer-content">
                    <div class="footer-user-info">
                        <div class="footer-user-name"><?php echo e(auth()->user()->name ?? 'Admin'); ?></div>
                        <div class="footer-user-role">Administrator • <?php echo e(now()->format('l, F j, Y')); ?></div>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn-logout-footer">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout from Admin Panel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            // ===== INTERSECTION OBSERVER FOR ANIMATIONS =====
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.stat-card, .activity-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // ===== ACTIVE NAV ITEM DETECTION =====
            const currentPath = window.location.pathname;
            document.querySelectorAll('.management-nav-item').forEach(item => {
                const href = item.getAttribute('href');
                if (href && currentPath.includes(new URL(href, window.location.origin).pathname)) {
                    item.classList.add('active');
                }
            });

            // ===== RIPPLE EFFECT ON STAT CARDS =====
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.closest('a')) return;
                    
                    const ripple = document.createElement('div');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = e.clientX - rect.left - size/2 + 'px';
                    ripple.style.top = e.clientY - rect.top - size/2 + 'px';
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(26, 95, 180, 0.1)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.zIndex = '1';
                    ripple.style.pointerEvents = 'none';
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // ===== CLOCK UPDATE =====
            function updateFooterTime() {
                const footerRole = document.querySelector('.footer-user-role');
                if (footerRole) {
                    const now = new Date();
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    const dateStr = now.toLocaleDateString('en-US', options);
                    const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                    const currentText = footerRole.textContent || '';
                    if (currentText.includes('•')) {
                        const parts = currentText.split('•');
                        if (parts.length >= 2) {
                            footerRole.textContent = parts[0].trim() + ' • ' + dateStr + ' ' + timeStr;
                        }
                    }
                }
            }
            updateFooterTime();
            setInterval(updateFooterTime, 30000);

            // ===== KEYBOARD SHORTCUTS =====
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const firstLink = document.querySelector('.management-nav-item');
                    if (firstLink) firstLink.focus();
                }
                if (e.key === 'Escape') {
                    document.activeElement.blur();
                }
            });

            console.log('✅ Neo Faraid Admin Dashboard loaded!');
        })();
    </script>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>