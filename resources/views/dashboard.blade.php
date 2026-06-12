@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    
    * {
        font-family: 'Poppins', sans-serif !important;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    :root {
        --primary: #1a5fb4;
        --primary-dark: #0d2d5c;
        --primary-light: #e8f1fd;
        --secondary: #2d7ad6;
        --accent: #ffd700;
        --accent-light: #ffed4e;
        --success: #25D366;
        --success-dark: #128C7E;
        --success-light: #d4edda;
        --danger: #dc3545;
        --danger-light: #f8d7da;
        --warning: #ffc107;
        --warning-light: #fff3cd;
        --info: #17a2b8;
        --info-light: #d1ecf1;
        --dark: #1a1a2e;
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
        --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
        --shadow: 0 4px 6px -1px rgba(0,0,0,0.07);
        --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.08);
        --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1);
        --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.15);
        --radius-sm: 10px;
        --radius: 14px;
        --radius-lg: 20px;
        --radius-xl: 28px;
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    body {
        font-family: 'Poppins', sans-serif !important;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        color: var(--gray-700);
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        overflow-x: hidden;
    }
    
    /* ===== ANIMATED BACKGROUND BLOB ===== */
    .bg-blob {
        position: fixed;
        top: -20%;
        right: -10%;
        width: 60vw;
        height: 60vw;
        background: radial-gradient(circle at 30% 40%, rgba(26, 95, 180, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
    }
    
    /* ===== PAGE HEADER ===== */
    .page-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        color: var(--white);
        padding: 2.5rem 0 3.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-repeat: repeat;
        background-size: 60px 60px;
        opacity: 0.4;
    }
    
    .page-header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
    }
    
    .welcome-section h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: -0.5px;
    }
    
    .welcome-section p {
        font-size: 1.0625rem;
        opacity: 0.85;
        max-width: 650px;
        font-weight: 300;
    }
    
    /* SVG Icons */
    .icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1em;
        height: 1em;
        flex-shrink: 0;
    }
    
    .icon svg {
        width: 100%;
        height: 100%;
        fill: currentColor;
    }
    
    /* ===== MAIN CONTENT ===== */
    .main-content {
        flex: 1;
        max-width: 1400px;
        margin: -2rem auto 0;
        padding: 0 2rem 3rem;
        position: relative;
        z-index: 2;
        width: 100%;
    }
    
    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.75rem;
        margin-bottom: 2rem;
    }
    
    .dashboard-grid-full {
        grid-column: 1 / -1;
    }
    
    /* Cards */
    .card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        border: 1px solid rgba(203, 213, 225, 0.5);
        overflow: hidden;
        transition: var(--transition);
    }
    
    .card:hover {
        box-shadow: var(--shadow-lg);
    }
    
    .card-header {
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(248, 250, 252, 0.7);
    }
    
    .card-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
        letter-spacing: -0.3px;
    }
    
    .card-title .icon {
        color: var(--primary);
        font-size: 1.2rem;
    }
    
    .card-body {
        padding: 1.75rem;
    }
    
    .card-body.no-padding {
        padding: 0;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        box-shadow: var(--shadow);
        border: 1px solid rgba(226, 232, 240, 0.8);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::after {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 4px;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
        border-radius: 0 4px 4px 0;
    }
    
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }
    
    .stat-card-icon {
        width: 52px;
        height: 52px;
        background: var(--primary-light);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    
    .stat-card-content {
        flex: 1;
    }
    
    .stat-card-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.2;
        margin-bottom: 0.2rem;
    }
    
    .stat-card-label {
        font-size: 0.875rem;
        color: var(--gray-500);
        font-weight: 500;
    }
    
    .stat-card-trend {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8125rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    .trend-up { color: var(--success); }
    .trend-down { color: var(--danger); }
    
    /* Activity List */
    .activity-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid var(--gray-100);
        transition: var(--transition);
        cursor: default;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        background: rgba(248, 250, 252, 0.8);
    }
    
    .activity-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .activity-icon.calculation { background: var(--primary-light); color: var(--primary); }
    .activity-icon.estate { background: var(--success-light); color: var(--success-dark); }
    .activity-icon.instant { background: var(--info-light); color: var(--info); }
    
    .activity-info {
        flex: 1;
        min-width: 0;
    }
    
    .activity-title {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.2rem;
    }
    
    .activity-meta {
        font-size: 0.8125rem;
        color: var(--gray-500);
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: var(--gray-400);
        white-space: nowrap;
        font-weight: 500;
    }
    
    /* Action Cards */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.25rem;
    }
    
    .action-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border-radius: var(--radius);
        padding: 1.75rem 1.25rem;
        text-align: center;
        text-decoration: none;
        border: 1px solid rgba(203, 213, 225, 0.6);
        transition: var(--transition);
        display: block;
        position: relative;
        overflow: hidden;
    }
    
    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .action-card:hover {
        border-color: var(--primary);
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
        background: white;
    }
    
    .action-card:hover::before {
        transform: scaleX(1);
    }
    
    .action-card-icon {
        width: 56px;
        height: 56px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: var(--primary);
        font-size: 1.4rem;
        transition: var(--transition);
    }
    
    .action-card:hover .action-card-icon {
        background: var(--primary);
        color: var(--white);
        transform: scale(1.05);
    }
    
    .action-card-title {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }
    
    .action-card-desc {
        font-size: 0.8rem;
        color: var(--gray-500);
        line-height: 1.5;
    }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.35rem;
        font-size: 0.9375rem;
        font-weight: 500;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition);
        font-family: inherit;
        white-space: nowrap;
        letter-spacing: -0.2px;
    }
    
    .btn-sm { padding: 0.45rem 1.1rem; font-size: 0.8125rem; }
    .btn-lg { padding: 0.875rem 2rem; font-size: 1.0625rem; }
    
    .btn-primary {
        background: var(--primary);
        color: var(--white);
        box-shadow: 0 4px 10px rgba(26, 95, 180, 0.2);
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(26, 95, 180, 0.35);
    }
    
    .btn-outline {
        background: transparent;
        border: 1.5px solid var(--gray-300);
        color: var(--gray-600);
    }
    
    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }
    
    /* Table */
    .table-container {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid var(--gray-100);
    }
    
    th {
        font-weight: 600;
        color: var(--gray-500);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        background: rgba(248, 250, 252, 0.6);
    }
    
    td {
        font-size: 0.9375rem;
        color: var(--gray-700);
    }
    
    tr:last-child td {
        border-bottom: none;
    }
    
    tr:hover td {
        background: rgba(248, 250, 252, 0.6);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--gray-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--gray-400);
        font-size: 2rem;
    }
    
    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }
    
    .empty-state-text {
        color: var(--gray-500);
        margin-bottom: 1.5rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Toast Notifications */
    .toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
        display: flex;
        flex-direction: column-reverse;
        gap: 0.75rem;
    }
    
    .toast {
        padding: 1rem 1.5rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow-xl);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 320px;
        max-width: 450px;
        animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-left: 4px solid var(--gray-400);
        font-weight: 500;
    }
    
    .toast-success { background: rgba(212, 237, 218, 0.95); color: var(--success-dark); border-left-color: var(--success); }
    .toast-error { background: rgba(248, 215, 218, 0.95); color: var(--danger); border-left-color: var(--danger); }
    .toast-info { background: rgba(209, 236, 241, 0.95); color: var(--info); border-left-color: var(--info); }
    
    @keyframes slideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .sr-only {
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
    
    /* Responsive */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .welcome-section h1 { font-size: 1.75rem; }
        .stats-grid { grid-template-columns: 1fr; }
        .actions-grid { grid-template-columns: 1fr 1fr; }
        .main-content { padding: 0 1.25rem 2rem; }
    }
    
    @media (max-width: 480px) {
        .actions-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="bg-blob" aria-hidden="true"></div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer" aria-live="polite" aria-atomic="true"></div>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-content">
        <div class="welcome-section">
            <h1>
                <span>Welcome back, {{ auth()->user()->name }}!</span>
            </h1>
            <p>Track your inheritance calculations and manage your estate planning in one place.</p>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon" aria-hidden="true">
                <span class="icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 2h10a2 2 0 012 2v16a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2zm0 2v16h10V4H7zm2 2h6v2H9V6zm0 4h6v2H9v-2zm0 4h4v2H9v-2z"/>
                    </svg>
                </span>
            </div>
            <div class="stat-card-content">
                <div class="stat-card-value">{{ number_format((int)($totalCalculations ?? 0)) }}</div>
                <div class="stat-card-label">Faraid Calculations</div>
                <div class="stat-card-trend trend-up">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 7l5 5-5 5M19 12H5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    12% from last month
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" aria-hidden="true">
                <span class="icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                    </svg>
                </span>
            </div>
            <div class="stat-card-content">
                <div class="stat-card-value">RM {{ number_format((float)($totalAssetValue ?? 0), 1) }}K</div>
                <div class="stat-card-label">Total Asset Value</div>
                <div class="stat-card-trend trend-up">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 7l5 5-5 5M19 12H5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    8% from last month
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" aria-hidden="true">
                <span class="icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                </span>
            </div>
            <div class="stat-card-content">
                <div class="stat-card-value">{{ $lastLogin ?? 'Just now' }}</div>
                <div class="stat-card-label">Last Login</div>
                <div class="stat-card-trend">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="color: var(--success); width: 0.6rem; height: 0.6rem;">
                            <circle cx="12" cy="12" r="12" fill="currentColor"/>
                        </svg>
                    </span>
                    Active
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon" aria-hidden="true">
                <span class="icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5z"/>
                    </svg>
                </span>
            </div>
            <div class="stat-card-content">
                <div class="stat-card-value">{{ auth()->user()->created_at->diffInDays(now()) }}</div>
                <div class="stat-card-label">Days as Member</div>
                <div class="stat-card-trend">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/>
                        </svg>
                    </span>
                    Since {{ auth()->user()->created_at->format('M Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Recent Calculations -->
        <section class="card" aria-labelledby="recentCalcHeading">
            <div class="card-header">
                <h2 id="recentCalcHeading" class="card-title">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 3a9 9 0 00-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.954 8.954 0 0013 21a9 9 0 000-18zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
                        </svg>
                    </span>
                    Recent Calculations
                </h2>
                <a href="{{ route('calculator.history') }}" class="btn btn-outline btn-sm">
                    View All
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 7l5 5-5 5M19 12H5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            </div>
            <div class="card-body no-padding">
                @php
                    $recentCalculations = $calculations->take(5) ?? collect();
                @endphp
                
                @if($recentCalculations->isNotEmpty())
                    <div class="table-container" role="region" aria-label="Recent calculations table" tabindex="0">
                        <table>
                            <caption class="sr-only">List of your 5 most recent Faraid calculations</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Deceased Name</th>
                                    <th scope="col">Total Assets</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCalculations as $calculation)
                                <tr>
                                    <td><strong>{{ $calculation->deceased_name ?? 'Unnamed' }}</strong></td>
                                    <td>RM {{ number_format((float)($calculation->total_assets ?? 0), 2) }}</td>
                                    <td>{{ $calculation->created_at ? $calculation->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        @if(isset($calculation->id) && Route::has('calculator.show'))
                                        <a href="{{ route('calculator.show', $calculation->id) }}" class="btn btn-primary btn-sm" aria-label="View calculation for {{ $calculation->deceased_name ?? 'Unnamed' }}">
                                            View
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon" aria-hidden="true">
                            <span class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 2h10a2 2 0 012 2v16a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2zm0 2v16h10V4H7zm2 2h6v2H9V6zm0 4h6v2H9v-2zm0 4h4v2H9v-2z"/>
                                </svg>
                            </span>
                        </div>
                        <h3 class="empty-state-title">No Calculations Yet</h3>
                        <p class="empty-state-text">Start your first Faraid calculation to see your history here.</p>
                        <a href="{{ route('calculator.index') }}" class="btn btn-primary">New Calculation</a>
                    </div>
                @endif
            </div>
        </section>

        <!-- Account Overview -->
        <section class="card" aria-labelledby="accountOverviewHeading">
            <div class="card-header">
                <h2 id="accountOverviewHeading" class="card-title">
                    <span class="icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zm5.6 8H19v6h-2.8v-6z"/>
                        </svg>
                    </span>
                    Account Overview
                </h2>
            </div>
            <div class="card-body">
                <ul class="activity-list" aria-label="Account overview statistics">
                    <li class="activity-item">
                        <div class="activity-icon calculation" aria-hidden="true">
                            <span class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 2h10a2 2 0 012 2v16a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2zm0 2v16h10V4H7zm2 2h6v2H9V6zm0 4h6v2H9v-2zm0 4h4v2H9v-2z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">Average Calculation Value</div>
                            <div class="activity-meta">Based on your calculations</div>
                        </div>
                        <div style="font-weight: 700; color: var(--primary);">
                            RM {{ number_format($calculations->avg('total_assets') ?? 0, 2) }}
                        </div>
                    </li>
                    
                    <li class="activity-item">
                        <div class="activity-icon estate" aria-hidden="true">
                            <span class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">Most Recent Calculation</div>
                            <div class="activity-meta">Last updated</div>
                        </div>
                        <div class="activity-time">
                            {{ $calculations->first() ? $calculations->first()->updated_at->diffForHumans() : 'N/A' }}
                        </div>
                    </li>
                    
                    <li class="activity-item">
                        <div class="activity-icon instant" aria-hidden="true">
                            <span class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">Account Created</div>
                            <div class="activity-meta">Member since</div>
                        </div>
                        <div class="activity-time">
                            {{ auth()->user()->created_at->diffForHumans() }}
                        </div>
                    </li>

                    <li class="activity-item">
                        <div class="activity-icon calculation" aria-hidden="true">
                            <span class="icon">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="activity-info">
                            <div class="activity-title">Total Calculations</div>
                            <div class="activity-meta">All time</div>
                        </div>
                        <div style="font-weight: 700; color: var(--gray-700);">
                            {{ number_format((int)($totalCalculations ?? 0)) }}
                        </div>
                    </li>
                </ul>
            </div>
        </section>
    </div>

    <!-- Quick Actions -->
    <section class="card" style="margin-top: 1.75rem;" aria-labelledby="quickActionsHeading">
        <div class="card-header" style="justify-content: center;">
            <h2 id="quickActionsHeading" class="card-title">
                Quick Actions
            </h2>
        </div>
        <div class="card-body">
            <div class="actions-grid">
                <!-- New Calculation -->
                <a href="{{ route('calculator.index') }}" class="action-card">
                    <div class="action-card-icon" aria-hidden="true">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                                <line x1="8" y1="6" x2="16" y2="6"></line>
                                <line x1="8" y1="10" x2="16" y2="10"></line>
                                <line x1="8" y1="14" x2="12" y2="14"></line>
                                <circle cx="16" cy="16" r="1.5"></circle>
                                <circle cx="16" cy="20" r="1.5"></circle>
                                <circle cx="13" cy="18" r="1.5"></circle>
                                <circle cx="13" cy="14" r="1.5"></circle>
                            </svg>
                        </span>
                    </div>
                    <div class="action-card-title">New Calculation</div>
                    <div class="action-card-desc">Start a Faraid inheritance calculation</div>
                </a>

                <!-- View History -->
                <a href="{{ route('calculator.history') }}" class="action-card">
                    <div class="action-card-icon" aria-hidden="true">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </span>
                    </div>
                    <div class="action-card-title">View History</div>
                    <div class="action-card-desc">Browse past calculations & reports</div>
                </a>

                <!-- Instant Estate -->
                <a href="{{ route('instant-estate.index') }}" class="action-card">
                    <div class="action-card-icon" aria-hidden="true">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M13 2L4.5 13H11L10.5 21.5L19.5 10H13L13 2Z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="action-card-title">Instant Estate</div>
                    <div class="action-card-desc">Upload death certificate for quick processing</div>
                </a>

                <!-- Estate Planning -->
                <a href="{{ route('estate-setup.index') }}" class="action-card">
                    <div class="action-card-icon" aria-hidden="true">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </span>
                    </div>
                    <div class="action-card-title">Estate Planning</div>
                    <div class="action-card-desc">Plan your estate and manage heirs</div>
                </a>

                <!-- FAQ & Help -->
                <a href="{{ route('faq.index') }}" class="action-card">
                    <div class="action-card-icon" aria-hidden="true">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="action-card-title">FAQ & Help</div>
                    <div class="action-card-desc">Get answers to common questions</div>
                </a>

                <!-- Give Feedback -->
                <a href="{{ route('feedback.index') }}" class="action-card">
                    <div class="action-card-icon" aria-hidden="true">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="action-card-title">Give Feedback</div>
                    <div class="action-card-desc">Help us improve the platform</div>
                </a>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    class Toast {
        static show({ type = 'info', title, message, duration = 4000 }) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const icons = {
                success: `<span class="icon" aria-hidden="true"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg></span>`,
                error: `<span class="icon" aria-hidden="true"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg></span>`,
                info: `<span class="icon" aria-hidden="true"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg></span>`
            };

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                ${icons[type] || icons.info}
                <div>
                    <strong>${this.escapeHtml(title)}</strong>
                    <div style="font-size: 0.875rem; margin-top: 0.25rem;">${this.escapeHtml(message)}</div>
                </div>
            `;

            toast.addEventListener('click', () => this.removeToast(toast));
            container.appendChild(toast);

            if (duration > 0) {
                setTimeout(() => this.removeToast(toast), duration);
            }
        }

        static removeToast(toast) {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => { if (toast.parentNode) toast.remove(); }, 300);
        }

        static escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        static success(message, title = 'Success') { this.show({ type: 'success', title, message }); }
        static error(message, title = 'Error') { this.show({ type: 'error', title, message }); }
        static info(message, title = 'Information') { this.show({ type: 'info', title, message }); }
    }

    @if(session('success')) Toast.success("{{ session('success') }}"); @endif
    @if(session('error')) Toast.error("{{ session('error') }}"); @endif
    @if(session('info')) Toast.info("{{ session('info') }}"); @endif
});
</script>
@endpush