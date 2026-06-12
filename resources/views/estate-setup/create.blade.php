@extends('layouts.app')

@section('title', 'Create Estate Planning')

@if(!Auth::check())
    <script>window.location.href = "{{ route('login') }}";</script>
@endif

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #1a5fb4;
        --primary-dark: #0d2d5c;
        --primary-light: #e8f1fd;
        --secondary-color: #2d7ad6;
        --accent-color: #ffd700;
        --accent-light: #ffed4e;
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
        --info-light: #d1ecf1;
        --dark: #1a1a2e;
        --light-bg: #f8f9fa;
        --light-border: #e9ecef;
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
        --text-primary: #495057;
        --text-light: #6c757d;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
        --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
        --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.15);
        --shadow-accent: 0 10px 25px rgba(255, 215, 0, 0.3);
        --shadow-primary: 0 10px 30px rgba(26, 95, 180, 0.2);
        --border-radius-sm: 12px;
        --border-radius-md: 15px;
        --border-radius-lg: 20px;
        --border-radius-xl: 50px;
        --transition: all 0.3s ease;
    }

    * {
        font-family: 'Poppins', sans-serif !important;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body, html {
        font-family: 'Poppins', sans-serif !important;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
        color: var(--text-primary);
        overflow-x: hidden;
    }

    .estate-header {
        min-height: 40vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 4rem 2rem;
    }

    .estate-header .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .estate-header .animated-bg .bg-circle {
        position: absolute;
        border-radius: 50%;
    }

    .estate-header .animated-bg .bg-circle-1 {
        top: 10%;
        right: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }

    .estate-header .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .estate-header .animated-bg .bg-circle-3 {
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .estate-header .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }

    .estate-header .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .estate-header .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    .estate-header .shape-1 {
        width: 40px;
        height: 40px;
        top: 20%;
        left: 10%;
        animation-name: float-1;
    }

    .estate-header .shape-2 {
        width: 25px;
        height: 25px;
        top: 60%;
        left: 85%;
        animation-name: float-2;
        animation-delay: 1s;
    }

    .estate-header .shape-3 {
        width: 35px;
        height: 35px;
        top: 75%;
        left: 15%;
        animation-name: float-3;
        animation-delay: 0.5s;
    }

    .estate-header .shape-4 {
        width: 20px;
        height: 20px;
        top: 30%;
        left: 70%;
        animation-name: float-4;
        animation-delay: 1.5s;
    }

    .estate-header .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 2rem;
    }

    .estate-header .hero-kicker {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        display: inline-flex;
        padding: 0.75rem 1.75rem;
        border-radius: var(--border-radius-xl);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.2);
        transition: var(--transition);
    }

    .estate-header .hero-kicker:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-2px);
    }

    .estate-header .kicker-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .estate-header .kicker-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 1rem;
    }

    .estate-header .kicker-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        color: var(--accent-color);
    }

    .estate-header .hero-title {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .estate-header .hero-highlight {
        color: var(--accent-color);
        position: relative;
        display: inline-block;
    }

    .estate-header .hero-highlight::after {
        content: '';
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 8px;
        background: rgba(255, 215, 0, 0.3);
        z-index: -1;
        border-radius: 4px;
    }

    .estate-header .hero-subtitle {
        font-size: 1.25rem;
        max-width: 600px;
        margin-bottom: 2.5rem;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
    }

    .glass-container {
        max-width: 1400px;
        margin: -3rem auto 3rem;
        padding: 0 2rem;
        position: relative;
        z-index: 10;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        transition: var(--transition);
        margin-bottom: 2rem;
        display: none;
    }

    .glass-card.active-section {
        display: block;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-header {
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
        padding: 1.75rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-header-icon {
        width: 32px;
        height: 32px;
        color: var(--primary-color);
        stroke-width: 2;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        flex: 1;
    }

    .card-badge {
        padding: 0.5rem 1rem;
        background: var(--primary-light);
        color: var(--primary-color);
        border-radius: var(--border-radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid rgba(26, 95, 180, 0.2);
    }

    .card-badge.success {
        background: var(--success-light);
        color: var(--success-dark);
    }

    .card-body {
        padding: 2rem;
    }

    .smart-validation-banner {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        margin-bottom: 1.5rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        box-shadow: var(--shadow-md);
        border-left: 4px solid var(--primary-color);
        transition: var(--transition);
    }

    .smart-validation-banner:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .validation-status {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .validation-icon {
        width: 28px;
        height: 28px;
    }

    .validation-message {
        font-weight: 600;
        font-size: 0.9375rem;
    }

    .validation-detail {
        font-size: 0.8125rem;
        color: var(--gray-500);
        margin-top: 0.25rem;
    }

    .validation-badge {
        padding: 0.375rem 0.875rem;
        border-radius: var(--border-radius-md);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .banner-success {
        border-left-color: var(--success-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--success-light) 100%);
    }

    .banner-warning {
        border-left-color: var(--warning-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--warning-light) 100%);
    }

    .banner-error {
        border-left-color: var(--danger-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--danger-light) 100%);
    }

    .banner-info {
        border-left-color: var(--info-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--info-light) 100%);
    }

    .progress-container {
        margin: 2rem 0 2rem;
        position: relative;
    }

    .progress-track {
        height: 8px;
        background: var(--gray-200);
        border-radius: var(--border-radius-xl);
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        width: 0%;
        transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }

    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-top: 0.75rem;
    }

    .progress-step {
        text-align: center;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-600);
        position: relative;
        padding-top: 1.75rem;
        flex: 1;
    }

    .progress-step::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 12px;
        height: 12px;
        background: var(--gray-300);
        border-radius: 50%;
        transition: var(--transition);
    }

    .progress-step.active {
        color: var(--primary-color);
        font-weight: 600;
    }

    .progress-step.active::before {
        background: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(26, 95, 180, 0.2);
    }

    .progress-step.completed {
        color: var(--success-color);
    }

    .progress-step.completed::before {
        background: var(--success-color);
        box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.2);
    }

    .modern-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        padding: 0.5rem;
        border-radius: var(--border-radius-lg);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: var(--shadow-lg);
        position: sticky;
        top: 1rem;
        z-index: 1000;
    }

    .modern-tab {
        flex: 1;
        padding: 1rem 1.5rem;
        border: none;
        background: transparent;
        color: var(--gray-600);
        font-weight: 600;
        border-radius: var(--border-radius-md);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }

    .modern-tab:hover {
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .modern-tab.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
        box-shadow: var(--shadow-primary);
        transform: translateY(-2px);
    }

    .modern-tab.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .tab-icon {
        width: 20px;
        height: 20px;
        transition: var(--transition);
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .required-label::after {
        content: " *";
        color: var(--danger-color);
    }

    .form-control, .form-input-modern {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-sm);
        font-size: 1rem;
        transition: var(--transition);
        background: var(--white);
    }

    .form-control:focus, .form-input-modern:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
    }

    .form-control.error {
        border-color: var(--danger-color);
        background-color: var(--danger-light);
    }

    .error-message {
        color: var(--danger-color);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    select.form-control, select.form-input-modern {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.25rem;
    }

    .heir-card, .wasiyyah-card, .asset-card, .debt-card, .credential-card {
        background: var(--white);
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
        margin-bottom: 1rem;
        overflow: hidden;
        transition: var(--transition);
    }

    .heir-card:hover, .wasiyyah-card:hover, .asset-card:hover, .debt-card:hover, .credential-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }

    .heir-card-header, .wasiyyah-card-header, .asset-card-header, .debt-card-header, .credential-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
        border-bottom: 1px solid var(--gray-200);
    }

    .heir-card-title, .wasiyyah-card-title, .asset-card-title, .debt-card-title, .credential-card-title {
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .heir-card-body, .wasiyyah-card-body, .asset-card-body, .debt-card-body, .credential-card-body {
        padding: 1.25rem;
    }

    .btn-remove {
        background: none;
        border: none;
        color: var(--danger-color);
        cursor: pointer;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        transition: var(--transition);
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-weight: 500;
    }

    .btn-remove:hover {
        background: var(--danger-light);
        color: var(--danger-dark);
    }

    .btn {
        padding: 0.875rem 1.75rem;
        border: none;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
        box-shadow: var(--shadow-primary);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg), var(--shadow-primary);
    }

    .btn-secondary {
        background: var(--gray-200);
        color: var(--gray-700);
    }

    .btn-secondary:hover {
        background: var(--gray-300);
        transform: translateY(-2px);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: var(--white);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg), 0 10px 30px rgba(37, 211, 102, 0.2);
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--gray-300);
        color: var(--gray-700);
    }

    .btn-outline:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
        transform: translateY(-2px);
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    .btn-group:first-of-type {
        margin-top: 0;
        padding-top: 0;
        border-top: none;
    }

    .summary-panel {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-top: 1.5rem;
    }

    .summary-panel-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
        padding: 1.25rem;
    }

    .summary-panel-header h3 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-panel-body {
        padding: 1.25rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        font-weight: 600;
        color: var(--gray-600);
    }

    .summary-value {
        font-weight: 700;
        font-size: 1.125rem;
    }

    .summary-value.positive {
        color: var(--success-color);
    }

    .summary-value.negative {
        color: var(--danger-color);
    }

    .summary-total {
        background: var(--primary-light);
        margin-top: 1rem;
        padding: 0.75rem;
        border-radius: var(--border-radius-sm);
    }

    .summary-total .summary-label {
        color: var(--primary-dark);
    }

    .summary-total .summary-value {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .results-container {
        margin-top: 1.5rem;
        overflow-x: auto;
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
    }

    .results-table th,
    .results-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid var(--gray-200);
    }

    .results-table th {
        background: var(--gray-50);
        font-weight: 600;
        color: var(--gray-700);
    }

    .info-card {
        background: var(--primary-light);
        border-radius: var(--border-radius-md);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid var(--primary-color);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: var(--border-radius-md);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-primary {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    .badge-success {
        background: var(--success-light);
        color: var(--success-dark);
    }

    .badge-warning {
        background: var(--warning-light);
        color: #b76e00;
    }

    .badge-info {
        background: var(--info-light);
        color: var(--info-color);
    }

    .badge-danger {
        background: var(--danger-light);
        color: var(--danger-dark);
    }

    .badge-heir { background: #e8f1fd; color: #1a5fb4; }
    .badge-real-estate { background: #e8f1fd; color: #1a5fb4; }
    .badge-financial { background: #d4edda; color: #155724; }
    .badge-investment { background: #fff3cd; color: #856404; }
    .badge-digital { background: #e0d4f5; color: #6f42c1; }
    .badge-secured { background: #f8d7da; color: #721c24; }
    .badge-unsecured { background: #d1ecf1; color: #0c5460; }
    .badge-religious { background: #d4edda; color: #155724; }

    .modern-alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 420px;
    }

    .modern-alert {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 320px;
        border-left: 4px solid;
    }

    .modern-alert.show {
        transform: translateX(0);
        opacity: 1;
    }

    .modern-alert.success {
        border-left-color: var(--success-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--success-light) 100%);
    }

    .modern-alert.error {
        border-left-color: var(--danger-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--danger-light) 100%);
    }

    .modern-alert.warning {
        border-left-color: var(--warning-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--warning-light) 100%);
    }

    .modern-alert.info {
        border-left-color: var(--info-color);
        background: linear-gradient(135deg, var(--white) 0%, var(--info-light) 100%);
    }

    .alert-icon {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 700;
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .alert-message {
        font-size: 0.8125rem;
        color: var(--gray-600);
        line-height: 1.4;
    }

    .alert-close {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        color: var(--gray-500);
        transition: var(--transition);
        border-radius: 6px;
        flex-shrink: 0;
    }

    .alert-close:hover {
        background: var(--gray-100);
        color: var(--gray-700);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .loading-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .loading-content {
        background: white;
        padding: 3rem;
        border-radius: var(--border-radius-lg);
        text-align: center;
        max-width: 400px;
        width: 90%;
        box-shadow: var(--shadow-xl);
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid var(--gray-200);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1.5rem;
    }

    .loading-text {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }

    .loading-subtext {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .confirmation-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .confirmation-modal-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .confirmation-modal {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        width: 90%;
        max-width: 480px;
        box-shadow: var(--shadow-xl);
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }

    .confirmation-modal-overlay.active .confirmation-modal {
        transform: scale(1);
    }

    .confirmation-modal-header {
        padding: 1.5rem 1.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .confirmation-modal-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .confirmation-modal-icon.warning {
        background: var(--warning-light);
        color: var(--warning-dark);
    }

    .confirmation-modal-icon.danger {
        background: var(--danger-light);
        color: var(--danger-dark);
    }

    .confirmation-modal-icon.info {
        background: var(--info-light);
        color: var(--info-color);
    }

    .confirmation-modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gray-900);
        flex: 1;
    }

    .confirmation-modal-body {
        padding: 1.75rem;
    }

    .confirmation-modal-message {
        color: var(--gray-700);
        line-height: 1.6;
        font-size: 1rem;
    }

    .confirmation-modal-item-name {
        font-weight: 600;
        color: var(--gray-900);
        background: var(--gray-100);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        display: inline-block;
        margin: 0.25rem 0;
    }

    .confirmation-modal-footer {
        padding: 1.25rem 1.75rem;
        background: var(--gray-50);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .faraid-calculator-panel {
        background: linear-gradient(135deg, #f0f7ff 0%, #e8f1fd 100%);
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(26, 95, 180, 0.2);
    }

    .faraid-calculator-panel h4 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .wasiyyah-limit-bar {
        height: 8px;
        background: var(--gray-200);
        border-radius: var(--border-radius-xl);
        margin: 0.5rem 0;
        overflow: hidden;
    }

    .wasiyyah-limit-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--warning-color), var(--danger-color));
        width: 0%;
        transition: width 0.3s ease;
        border-radius: var(--border-radius-xl);
    }

    .wasiyyah-limit-fill.safe {
        background: linear-gradient(90deg, var(--success-color), var(--success-dark));
    }

    .activation-summary-compact {
        background: var(--white);
        border-radius: var(--border-radius-md);
        padding: 1rem;
        border: 1px solid var(--gray-200);
    }

    .activation-summary-compact .summary-item {
        padding: 0.5rem 0;
    }

    .activation-summary-compact .summary-label {
        font-size: 0.875rem;
    }

    .activation-summary-compact .summary-value {
        font-size: 0.9375rem;
    }

    .pdf-section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1a5fb4;
        margin: 1.5rem 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e8f1fd;
    }

    .pdf-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        font-size: 0.75rem;
    }

    .pdf-info-table td {
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }

    .pdf-info-table td:first-child {
        font-weight: 600;
        color: #475569;
        width: 30%;
        white-space: nowrap;
    }

    .pdf-info-card {
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        border-left: 3px solid #1a5fb4;
        font-size: 0.75rem;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 40px;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--gray-500);
        background: white;
        padding: 0 5px;
    }

    .collapsible-section {
        margin-top: 1.5rem;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        overflow: hidden;
    }

    .collapsible-header {
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
        padding: 1rem 1.25rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        color: var(--gray-800);
        transition: var(--transition);
    }

    .collapsible-header:hover {
        background: var(--gray-100);
    }

    .collapsible-content {
        padding: 1.25rem;
        display: none;
        border-top: 1px solid var(--gray-200);
    }

    .collapsible-content.show {
        display: block;
    }

    .heir-summary-panel {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-top: 1.5rem;
    }

    .heir-summary-panel .summary-panel-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
        padding: 1.25rem;
    }

    .heir-summary-panel .summary-panel-body {
        padding: 1.25rem;
    }

    .video-upload-area {
        border: 2px dashed var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background: var(--gray-50);
    }

    .video-upload-area:hover {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }

    .video-preview {
        margin-top: 1rem;
        text-align: center;
    }

    .video-preview video {
        max-width: 100%;
        border-radius: var(--border-radius-md);
        max-height: 300px;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-container {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        width: 90%;
        max-width: 700px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-overlay.active .modal-container {
        transform: scale(1);
    }

    .modal-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
    }

    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .modal-close:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    @media (max-width: 1024px) {
        .grid-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .estate-header .hero-title { font-size: 2rem; }
        .estate-header { min-height: 35vh !important; padding: 2rem 1rem; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -2rem; }
        .modern-tabs { flex-direction: column; }
        .modern-tab { justify-content: flex-start; }
        .card-body { padding: 1.5rem; }
        .grid-2, .grid-3 { grid-template-columns: 1fr; }
        .btn-group { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
        .smart-validation-banner { flex-direction: column; align-items: flex-start; }
        .modern-alert-container { left: 20px; right: 20px; max-width: none; }
        .modern-alert { min-width: auto; width: 100%; }
        .video-option-tabs { flex-direction: column; border-bottom: none; }
        .video-option-tab { padding: 0.5rem 1rem; }
        .video-option-tab.active::after { display: none; }
        .youtube-preview iframe { height: 200px; }
    }

    @media (max-width: 480px) {
        .estate-header .hero-title { font-size: 1.5rem; }
        .progress-steps { font-size: 0.7rem; }
        .progress-step { padding-top: 1.5rem; }
        .card-title { font-size: 1.25rem; }
    }

    @keyframes float-1 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-15px) rotate(120deg); }
        66% { transform: translateY(8px) rotate(240deg); }
    }

    @keyframes float-2 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(90deg); }
        66% { transform: translateY(10px) rotate(180deg); }
    }

    @keyframes float-3 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-12px) rotate(60deg); }
        66% { transform: translateY(6px) rotate(120deg); }
    }

    @keyframes float-4 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-18px) rotate(150deg); }
        66% { transform: translateY(9px) rotate(300deg); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.7; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<div class="modern-alert-container" id="alertContainer"></div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Processing...</div>
        <div class="loading-subtext" id="loadingSubtext">Please wait while we process your request</div>
    </div>
</div>

<div class="confirmation-modal-overlay" id="confirmationModal">
    <div class="confirmation-modal">
        <div class="confirmation-modal-header">
            <div class="confirmation-modal-icon" id="modalIcon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="confirmation-modal-title" id="modalTitle">Confirm Action</h3>
        </div>
        <div class="confirmation-modal-body">
            <p class="confirmation-modal-message" id="modalMessage">Are you sure you want to proceed?</p>
            <div id="modalItemName" style="margin-top: 0.5rem;"></div>
        </div>
        <div class="confirmation-modal-footer">
            <button class="btn btn-secondary" id="modalCancelBtn">Cancel</button>
            <button class="btn btn-danger" id="modalConfirmBtn" style="background: var(--danger-color); color: white;">Confirm</button>
        </div>
    </div>
</div>

<header class="estate-header">
    <div class="hero-bg-elements animated-bg">
        <div class="bg-circle bg-circle-1"></div>
        <div class="bg-circle bg-circle-2"></div>
        <div class="bg-circle bg-circle-3"></div>
        <div class="bg-pattern"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
    </div>

    <div class="hero-container">
        <div class="hero-kicker">
            <div class="kicker-content">
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Precise
                </span>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Reliable
                </span>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Shariah-Compliant
                </span>
            </div>
        </div>
        <h1 class="hero-title">
            <span class="hero-highlight">Create</span> Estate Planning
        </h1>
        <p class="hero-subtitle">
            Begin your digital estate planning journey. Your information is secure and will only be accessible to your designated heirs.
        </p>
    </div>
</header>

<div class="glass-container">
    <div class="smart-validation-banner" id="smartValidationBanner">
        <div class="validation-status">
            <div class="validation-icon" id="validationIcon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="validation-message" id="validationMessage">System ready for activation</div>
                <div class="validation-detail" id="validationDetail">Complete all sections to activate your estate plan</div>
            </div>
        </div>
        <div class="validation-badge" id="validationBadge">Ready</div>
    </div>

    <div class="progress-container">
        <div class="progress-track">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step active" id="step1">Profile & Credentials</div>
            <div class="progress-step" id="step2">Assets</div>
            <div class="progress-step" id="step3">Debts</div>
            <div class="progress-step" id="step4">Heirs</div>
            <div class="progress-step" id="step5">Wasiyyah</div>
            <div class="progress-step" id="step6">Review</div>
        </div>
    </div>

    <div class="modern-tabs">
        <button class="modern-tab active" data-section="profile" data-section-index="0">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </button>
        <button class="modern-tab" data-section="assets" data-section-index="1">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Assets
        </button>
        <button class="modern-tab" data-section="debts" data-section-index="2">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Debts
        </button>
        <button class="modern-tab" data-section="heirs" data-section-index="3">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            Heirs
        </button>
        <button class="modern-tab" data-section="wasiyyah" data-section-index="4">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Wasiyyah
        </button>
        <button class="modern-tab" data-section="review" data-section-index="5">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Review
        </button>
    </div>

    <form method="POST" action="{{ route('estate-setup.store') }}" id="estateForm" enctype="multipart/form-data">
        @csrf

        <!-- SECTION 1: PROFILE & CREDENTIALS -->
        <div class="glass-card active-section" id="profileSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h2 class="card-title">Deceased Profile</h2>
                <span class="card-badge">Step 1 of 6</span>
            </div>
            <div class="card-body">
                <div class="info-card">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>This information is about YOU (the person creating the estate plan). Your heirs will use this information to verify your identity.</p>
                </div>

                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-color);">Personal Information</h3>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="deceased_name" class="form-label required-label">Full Name</label>
                        <input type="text" class="form-control" id="deceased_name" name="deceased_name" 
                               value="{{ old('deceased_name', $user->name) }}" 
                               placeholder="Enter your full name as per IC/Passport" required readonly 
                               style="background-color: #f0f0f0; cursor: not-allowed;">
                        <div class="error-message" id="nameError"></div>
                        <small style="color: var(--gray-500);">This information is from your registration and cannot be changed.</small>
                    </div>

                    <div class="form-group">
                        <label for="deceased_nric" class="form-label required-label">NRIC/Passport Number</label>
                        <input type="text" class="form-control" id="deceased_nric" name="deceased_nric" 
                               value="{{ old('deceased_nric', $user->formatted_nric ?? $user->nric) }}" 
                               placeholder="000000-00-0000" required readonly 
                               style="background-color: #f0f0f0; cursor: not-allowed;"
                               pattern="^\d{6}-\d{2}-\d{4}$|\d{12}$">
                        <div class="error-message" id="nricError"></div>
                        <small style="color: var(--gray-500);">This information is from your registration and cannot be changed.</small>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth" class="form-label required-label">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" 
                               value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" 
                               readonly style="background-color: #f0f0f0; cursor: not-allowed;">
                        <div class="error-message" id="dobError"></div>
                        <small style="color: var(--gray-500);">This information is from your registration and cannot be changed.</small>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="form-label required-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" disabled style="background-color: #f0f0f0; cursor: not-allowed;">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <div class="error-message" id="genderError"></div>
                        <input type="hidden" name="gender" value="{{ $user->gender }}">
                        <small style="color: var(--gray-500);">This information is from your registration and cannot be changed.</small>
                    </div>

                    <div class="form-group">
                        <label for="contact_phone" class="form-label required-label">Contact Phone</label>
                        <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                               value="{{ old('contact_phone', $user->formatted_contact_phone ?? $user->contact_phone) }}" 
                               placeholder="012-3456789" required
                               pattern="^01\d-\d{7,8}$|^01\d{8,9}$">
                        <div class="error-message" id="phoneError"></div>
                        <small style="color: var(--gray-500);">You can update your contact phone if needed.</small>
                    </div>

                    <div class="form-group">
                        <label for="contact_email" class="form-label required-label">Contact Email</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" 
                               value="{{ old('contact_email', $user->email) }}" 
                               placeholder="your@email.com" required readonly 
                               style="background-color: #f0f0f0; cursor: not-allowed;">
                        <div class="error-message" id="emailError"></div>
                        <small style="color: var(--gray-500);">This information is from your registration and cannot be changed.</small>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="address" class="form-label required-label">Residential Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" 
                                  placeholder="Enter your full residential address" required>{{ old('address', $user->address) }}</textarea>
                        <div class="error-message" id="addressError"></div>
                        <small style="color: var(--gray-500);">You can update your residential address if needed.</small>
                    </div>
                </div>

                <div class="collapsible-section">
                    <div class="collapsible-header" onclick="window.toggleCredentials()">
                        <span>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; margin-right: 0.5rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Digital Credentials (Social Media, Bank Accounts, Passwords)
                        </span>
                        <span id="credentialsToggleIcon">▼</span>
                    </div>
                    <div class="collapsible-content" id="credentialsContent">
                        <div class="info-card" style="background: var(--warning-light); margin-bottom: 1rem;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <p><strong>Security Note:</strong> All credentials are encrypted before storage. Only your appointed trustee will be able to access this information after your passing and proper verification.</p>
                        </div>

                        <div class="credential-card">
                            <div class="credential-card-header">
                                <div class="credential-card-title">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Digital Credential
                                </div>
                            </div>
                            <div class="credential-card-body">
                                <div class="grid-3">
                                    <div class="form-group">
                                        <label class="form-label required-label">Platform / Service</label>
                                        <select id="credentialPlatform" class="form-control">
                                            <option value="">Select Platform</option>
                                            <optgroup label="Social Media">
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Twitter/X">Twitter/X</option>
                                                <option value="LinkedIn">LinkedIn</option>
                                                <option value="TikTok">TikTok</option>
                                                <option value="WhatsApp">WhatsApp</option>
                                                <option value="Telegram">Telegram</option>
                                            </optgroup>
                                            <optgroup label="Banking & Finance">
                                                <option value="Maybank2u">Maybank2u</option>
                                                <option value="CIMB Clicks">CIMB Clicks</option>
                                                <option value="Public Bank">Public Bank</option>
                                                <option value="RHB Bank">RHB Bank</option>
                                                <option value="Hong Leong Connect">Hong Leong Connect</option>
                                                <option value="AmBank">AmBank</option>
                                                <option value="Bank Islam">Bank Islam</option>
                                                <option value="Bank Rakyat">Bank Rakyat</option>
                                                <option value="BSN">BSN</option>
                                                <option value="Touch 'n Go eWallet">Touch 'n Go eWallet</option>
                                                <option value="Boost">Boost</option>
                                                <option value="GrabPay">GrabPay</option>
                                                <option value="BigPay">BigPay</option>
                                            </optgroup>
                                            <optgroup label="Email & Cloud">
                                                <option value="Gmail">Gmail</option>
                                                <option value="Outlook/Hotmail">Outlook/Hotmail</option>
                                                <option value="Yahoo Mail">Yahoo Mail</option>
                                                <option value="Google Drive">Google Drive</option>
                                                <option value="Dropbox">Dropbox</option>
                                                <option value="iCloud">iCloud</option>
                                                <option value="OneDrive">OneDrive</option>
                                            </optgroup>
                                            <optgroup label="Government & EPF">
                                                <option value="EPF i-Akaun">EPF i-Akaun</option>
                                                <option value="LHDN e-Filing">LHDN e-Filing</option>
                                                <option value="MyEG">MyEG</option>
                                                <option value="JPJ e-Services">JPJ e-Services</option>
                                                <option value="MyGov Portal">MyGov Portal</option>
                                            </optgroup>
                                            <optgroup label="Investment & Trading">
                                                <option value="Rakuten Trade">Rakuten Trade</option>
                                                <option value="Mplus">Mplus</option>
                                                <option value="FSMOne">FSMOne</option>
                                                <option value="Binance">Binance</option>
                                                <option value="Luno">Luno</option>
                                                <option value="Hata">Hata</option>
                                            </optgroup>
                                            <optgroup label="Other">
                                                <option value="Other Bank">Other Bank</option>
                                                <option value="Other Social Media">Other Social Media</option>
                                                <option value="Other Service">Other Service</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required-label">Platform Name (Custom)</label>
                                        <input type="text" id="credentialPlatformCustom" class="form-control" placeholder="e.g., Affin Bank, WeChat, etc.">
                                        <small>Leave blank if selected from dropdown</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required-label">Username / ID / Email</label>
                                        <input type="text" id="credentialUsername" class="form-control" placeholder="Username, email or ID">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label required-label">Password / Access Key</label>
                                        <div class="password-wrapper">
                                            <input type="password" id="credentialPassword" class="form-control" placeholder="Password or access key">
                                            <span class="password-toggle" onclick="window.togglePassword('credentialPassword')">👁️</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Security Questions / 2FA Backup Codes</label>
                                        <textarea id="credentialSecurity" class="form-control" rows="2" placeholder="Security questions answers, 2FA backup codes, recovery phrases..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Additional Notes</label>
                                        <input type="text" id="credentialNotes" class="form-control" placeholder="Any additional information">
                                    </div>
                                </div>
                                <div class="btn-group" style="margin-top:1rem;padding-top:0;border-top:0;">
                                    <button type="button" class="btn btn-primary" id="addCredentialBtn">+ Add Credential</button>
                                    <button type="button" class="btn btn-secondary" id="clearCredentialsBtn">Clear All</button>
                                </div>
                            </div>
                        </div>
                        <div id="credentialsList" style="margin-top:1.5rem;"></div>

                        <div class="summary-panel">
                            <div class="summary-panel-header">
                                <h3>Credentials Summary</h3>
                            </div>
                            <div class="summary-panel-body">
                                <div class="summary-item">
                                    <span class="summary-label">Total Credentials</span>
                                    <span class="summary-value" id="credentialsCountDisplay">0</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Categories</span>
                                    <span class="summary-value" id="credentialsCategoriesDisplay">None</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <a href="{{ route('estate-setup.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="button" class="btn btn-primary next-section" data-next="assets">Save & Continue →</button>
                </div>
            </div>
        </div>

        <!-- SECTION 2: ASSETS -->
        <div class="glass-card" id="assetsSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h2 class="card-title">Your Assets</h2>
                <span class="card-badge" id="totalAssetsBadge">0 Assets</span>
            </div>
            <div class="card-body">
                <div class="info-card">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>List all estate assets including property, cash, savings, investments, business ownership, EPF/KWSP, insurance, gold, and digital assets.</p>
                </div>

                <div class="asset-card">
                    <div class="asset-card-header">
                        <div class="asset-card-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Asset
                        </div>
                    </div>
                    <div class="asset-card-body">
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label required-label">Asset Name</label>
                                <select id="assetName" class="form-control">
                                    <option value="" disabled selected>Select Asset Type</option>
                                    <optgroup label="Real Estate (Immovable Property)">
                                        <option value="Residential House">Residential House</option>
                                        <option value="Apartment / Condominium">Apartment / Condominium</option>
                                        <option value="Low-cost Flat (PPR / Kos Rendah)">Low-cost Flat (PPR / Kos Rendah)</option>
                                        <option value="Shop Lot">Shop Lot</option>
                                        <option value="Office Unit">Office Unit</option>
                                        <option value="Industrial Property (Factory / Warehouse)">Industrial Property (Factory / Warehouse)</option>
                                        <option value="Agricultural Land">Agricultural Land</option>
                                        <option value="Vacant Land / Lot">Vacant Land / Lot</option>
                                    </optgroup>
                                    <optgroup label="Movable & Financial Assets">
                                        <option value="Vehicle (Car / Motorcycle)">Vehicle (Car / Motorcycle)</option>
                                        <option value="Bank Savings">Bank Savings</option>
                                        <option value="Fixed Deposit">Fixed Deposit</option>
                                        <option value="EPF / KWSP Savings">EPF / KWSP Savings</option>
                                        <option value="Tabung Haji Savings">Tabung Haji Savings</option>
                                        <option value="Insurance / Takaful Payout">Insurance / Takaful Payout</option>
                                        <option value="Cash in Hand">Cash in Hand</option>
                                    </optgroup>
                                    <optgroup label="Investment Assets">
                                        <option value="ASB / Unit Trust Investment">ASB / Unit Trust Investment</option>
                                        <option value="Shares / Stocks">Shares / Stocks</option>
                                        <option value="Gold / Precious Metals">Gold / Precious Metals</option>
                                        <option value="Business Ownership">Business Ownership</option>
                                        <option value="Digital Assets (Crypto / E-wallet)">Digital Assets (Crypto / E-wallet)</option>
                                    </optgroup>
                                    <optgroup label="Other Assets">
                                        <option value="Other Assets">Other Assets</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Auto Category</label>
                                <div><span id="assetCategoryPreview" class="badge badge-real-estate">Real Estate</span></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Value (RM)</label>
                                <input type="number" id="assetValue" class="form-control" placeholder="0.00" min="0" step="0.01">
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Ownership (%)</label>
                                <input type="number" id="assetOwnership" class="form-control" value="100" min="0" max="100" step="1">
                                <small style="color: var(--gray-500);">Your ownership percentage of this asset</small>
                            </div>
                            <div class="form-group" style="grid-column:span 2;">
                                <label class="form-label">Description / Location</label>
                                <input type="text" id="assetDescription" class="form-control" placeholder="e.g., No. 123, Jalan SS2, Petaling Jaya">
                            </div>
                        </div>
                        <div class="btn-group" style="margin-top:1rem;padding-top:0;border-top:0;">
                            <button type="button" class="btn btn-primary" id="addAssetBtn">+ Add Asset</button>
                            <button type="button" class="btn btn-secondary" id="clearAssetsBtn">Clear All</button>
                        </div>
                    </div>
                </div>
                <div id="assetsList" style="margin-top:1.5rem;"></div>

                <div class="summary-panel">
                    <div class="summary-panel-header">
                        <h3>Estate Summary</h3>
                    </div>
                    <div class="summary-panel-body">
                        <div class="summary-item">
                            <span class="summary-label">Total Assets (Before Deductions)</span>
                            <span class="summary-value" id="totalAssetsGrossDisplay">RM 0.00</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Assets (Your Share)</span>
                            <span class="summary-value" id="totalAssetsDisplay">RM 0.00</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Assets Count</span>
                            <span class="summary-value" id="assetsCountDisplay">0</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="assetsData" name="assets_data">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="profile">← Back</button>
                    <button type="button" class="btn btn-primary next-section" data-next="debts">Continue to Debts →</button>
                </div>
            </div>
        </div>

        <!-- SECTION 3: DEBTS -->
        <div class="glass-card" id="debtsSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="card-title">Outstanding Debts</h2>
                <span class="card-badge" id="totalDebtsBadge">0 Debts</span>
            </div>
            <div class="card-body">
                <div class="info-card">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Record all liabilities including loans, credit cards, zakat, tax, medical bills, funeral expenses, and other obligations.</p>
                </div>

                <div class="debt-card">
                    <div class="debt-card-header">
                        <div class="debt-card-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Debt
                        </div>
                    </div>
                    <div class="debt-card-body">
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label required-label">Debt Type</label>
                                <select id="debtName" class="form-control">
                                    <option value="" disabled selected>Select Debt Type</option>
                                    <optgroup label="Bank & Financing Debts">
                                        <option value="Housing Loan">Housing Loan</option>
                                        <option value="Personal Loan">Personal Loan</option>
                                        <option value="Car Loan / Hire Purchase">Car Loan / Hire Purchase</option>
                                        <option value="Credit Card Outstanding">Credit Card Outstanding</option>
                                        <option value="Business Loan">Business Loan</option>
                                        <option value="Education Loan (PTPTN)">Education Loan (PTPTN)</option>
                                        <option value="Overdraft / Bank Facility">Overdraft / Bank Facility</option>
                                    </optgroup>
                                    <optgroup label="Personal & Social Liabilities">
                                        <option value="Borrowed from Family / Friends">Borrowed from Family / Friends</option>
                                        <option value="Medical Bills">Medical Bills</option>
                                        <option value="Funeral Expenses">Funeral Expenses</option>
                                    </optgroup>
                                    <optgroup label="Religious & Government Obligations">
                                        <option value="Unpaid Zakat">Unpaid Zakat</option>
                                        <option value="Unpaid Income Tax (LHDN)">Unpaid Income Tax (LHDN)</option>
                                        <option value="Court Fines / Legal Penalties">Court Fines / Legal Penalties</option>
                                    </optgroup>
                                    <optgroup label="Utilities & Other Liabilities">
                                        <option value="Utility Bills">Utility Bills</option>
                                        <option value="Other Liabilities">Other Liabilities</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Auto Classification</label>
                                <div><span id="debtClassificationPreview" class="badge badge-secured">Secured Debt</span></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Amount (RM)</label>
                                <input type="number" id="debtAmount" class="form-control" placeholder="0.00" min="0" step="0.01">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Creditor Name</label>
                                <input type="text" id="debtCreditor" class="form-control" placeholder="e.g., Maybank, CIMB, Friend Name">
                            </div>
                            <div class="form-group" style="grid-column:span 2;">
                                <label class="form-label">Description</label>
                                <input type="text" id="debtDescription" class="form-control" placeholder="Additional details about this debt">
                            </div>
                        </div>
                        <div class="btn-group" style="margin-top:1rem;padding-top:0;border-top:0;">
                            <button type="button" class="btn btn-primary" id="addDebtBtn">+ Add Debt</button>
                            <button type="button" class="btn btn-secondary" id="clearDebtsBtn">Clear All</button>
                        </div>
                    </div>
                </div>
                <div id="debtsList" style="margin-top:1.5rem;"></div>

                <div class="summary-panel">
                    <div class="summary-panel-header">
                        <h3>Estate Summary</h3>
                    </div>
                    <div class="summary-panel-body">
                        <div class="summary-item">
                            <span class="summary-label">Total Assets (Your Share)</span>
                            <span class="summary-value" id="totalAssetsDisplay2">RM 0.00</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Debts</span>
                            <span class="summary-value negative" id="totalDebtsDisplay">RM 0.00</span>
                        </div>
                        <div class="summary-total">
                            <div class="summary-item">
                                <span class="summary-label">Net Estate Value</span>
                                <span class="summary-value" id="netEstateDisplay">RM 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="debtsData" name="debts_data">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="assets">← Back</button>
                    <button type="button" class="btn btn-primary next-section" data-next="heirs">Continue to Heirs →</button>
                </div>
            </div>
        </div>

        <!-- SECTION 4: HEIRS (FARAID) with EXACT FRACTION CALCULATOR -->
        <div class="glass-card" id="heirsSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                </svg>
                <h2 class="card-title">Faraid Heirs Distribution</h2>
                <span class="card-badge" id="totalHeirsBadge">0 Heirs</span>
            </div>
            <div class="card-body">
                <div class="info-card">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Define legal faraid beneficiaries. Set inheritance distribution percentages based on Islamic law. Total distribution must equal 100% of the net estate after wasiyyah.</p>
                </div>

                <div class="faraid-calculator-panel">
                    <h4>
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Auto-Calculate Faraid Shares (Shariah-Compliant)
                    </h4>
                    <div class="grid-3">
                        <div class="form-group">
                            <label class="form-label">Spouse</label>
                            <select id="spouseType" class="form-control">
                                <option value="none">None</option>
                                <option value="husband">Husband</option>
                                <option value="wife">Wife (one or more)</option>
                            </select>
                        </div>
                        <div class="form-group" id="wivesCountGroup" style="display:none;">
                            <label class="form-label">Number of Wives (1-4)</label>
                            <input type="number" id="wivesCount" class="form-control" min="1" max="4" value="1">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sons</label>
                            <input type="number" id="sonsCount" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Daughters</label>
                            <input type="number" id="daughtersCount" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Father alive?</label>
                            <select id="fatherAlive" class="form-control">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mother alive?</label>
                            <select id="motherAlive" class="form-control">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Brothers (same both parents)</label>
                            <input type="number" id="fullBrothers" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Sisters (same both parents)</label>
                            <input type="number" id="fullSisters" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Paternal Half-Brothers (same father)</label>
                            <input type="number" id="paternalHalfBrothers" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Paternal Half-Sisters (same father)</label>
                            <input type="number" id="paternalHalfSisters" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maternal Half-Brothers (same mother)</label>
                            <input type="number" id="maternalHalfBrothers" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maternal Half-Sisters (same mother)</label>
                            <input type="number" id="maternalHalfSisters" class="form-control" min="0" value="0">
                        </div>
                    </div>
                    <div class="btn-group" style="margin-top:0;padding-top:0;border-top:0;">
                        <button type="button" class="btn btn-primary" id="calculateFaraidBtn">Calculate Faraid Shares</button>
                        <button type="button" class="btn btn-secondary" id="resetHeirsBtn">Reset Form</button>
                    </div>
                    <div id="faraidResultArea" style="margin-top: 1rem; display: none;"></div>
                </div>

                <h3 style="margin: 1.5rem 0 1rem; font-size: 1.125rem;">Add New Heir</h3>

                <div class="heir-card">
                    <div class="heir-card-header">
                        <div class="heir-card-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            New Heir
                        </div>
                    </div>
                    <div class="heir-card-body">
                        <div class="grid-3">
                            <div class="form-group">
                                <label class="form-label required-label">Heir Full Name</label>
                                <input type="text" id="heirName" class="form-control" placeholder="Full Name" required>
                                <div class="error-message" id="heirNameError"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">NRIC/Passport</label>
                                <input type="text" id="heirNric" class="form-control" placeholder="000000-00-0000" required oninput="this.value = formatNric(this.value)">
                                <div class="error-message" id="heirNricError"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Contact Email</label>
                                <input type="email" id="heirEmail" class="form-control" placeholder="heir@example.com" required>
                                <div class="error-message" id="heirEmailError"></div>
                                <small style="color: var(--gray-500);">Required for notification</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Relationship</label>
                                <select id="heirRelationship" class="form-control" required>
                                    <option value="" disabled selected>Select Relationship</option>
                                    <optgroup label="Primary Heirs">
                                        <option value="husband">Husband</option>
                                        <option value="wife">Wife</option>
                                        <option value="father">Father</option>
                                        <option value="mother">Mother</option>
                                    </optgroup>
                                    <optgroup label="Substitute Heirs">
                                        <option value="grandfather">Father's Father (Grandfather)</option>
                                        <option value="grandmother_paternal">Father's Mother (Grandmother)</option>
                                        <option value="grandmother_maternal">Mother's Mother (Grandmother)</option>
                                    </optgroup>
                                    <optgroup label="Secondary Heirs">
                                        <option value="half_brother_full">Full Brother</option>
                                        <option value="half_brother_paternal">Paternal Half-Brother</option>
                                        <option value="half_brother_maternal">Maternal Half-Brother</option>
                                        <option value="half_sister_full">Full Sister</option>
                                        <option value="half_sister_paternal">Paternal Half-Sister</option>
                                        <option value="half_sister_maternal">Maternal Half-Sister</option>
                                    </optgroup>
                                    <optgroup label="Children">
                                        <option value="son">Son</option>
                                        <option value="daughter">Daughter</option>
                                    </optgroup>
                                    <optgroup label="Institutional Beneficiary">
                                        <option value="baitulmal">Baitulmal (Public Treasury)</option>
                                    </optgroup>
                                </select>
                                <div class="error-message" id="heirRelationshipError"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Share Percentage (%)</label>
                                <input type="number" id="heirPercentage" class="form-control" placeholder="0.00" step="0.01" min="0" max="100" required>
                                <div class="error-message" id="heirPercentageError"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Contact Phone</label>
                                <input type="tel" id="heirPhone" class="form-control" placeholder="012-3456789" required oninput="this.value = formatPhone(this.value)">
                                <div class="error-message" id="heirPhoneError"></div>
                            </div>
                        </div>
                        <div class="btn-group" style="margin-top:0;padding-top:0;border-top:0;">
                            <button type="button" class="btn btn-primary" id="addHeirBtn">+ Add Heir</button>
                            <button type="button" class="btn btn-secondary" id="clearHeirsBtn">Clear All Heirs</button>
                        </div>
                    </div>
                </div>

                <div id="heirsList" style="margin-top:1.5rem;"></div>

                <div class="heir-summary-panel">
                    <div class="summary-panel-header">
                        <h3>Heirs Distribution Summary</h3>
                    </div>
                    <div class="summary-panel-body">
                        <div class="summary-item">
                            <span class="summary-label">Total Heirs</span>
                            <span class="summary-value" id="heirsCountDisplay">0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Distribution</span>
                            <span class="summary-value" id="totalPercentageDisplay">0%</span>
                        </div>
                        <div class="summary-total">
                            <div class="summary-item">
                                <span class="summary-label">Remaining to Allocate</span>
                                <span class="summary-value" id="remainingPercentageDisplay">100%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="heirsData" name="heirs_data">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="debts">← Back</button>
                    <button type="button" class="btn btn-primary next-section" data-next="wasiyyah">Continue to Wasiyyah →</button>
                </div>
            </div>
        </div>

        <!-- SECTION 5: WASIYYAH & TRUSTEE -->
        <div class="glass-card" id="wasiyyahSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <h2 class="card-title">Wasiyyah & Trustee</h2>
                <span class="card-badge" id="wasiyyahBadge">0 Beneficiaries</span>
            </div>
            <div class="card-body">
                <div class="info-card">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p><strong>Wasiyyah:</strong> A legal document stating how assets should be distributed (limited to 1/3 of estate, cannot override faraid rights of legal heirs).<br>
                    <strong>Trustee:</strong> Appointed to manage, safeguard, and execute estate distribution according to Islamic inheritance law (faraid).</p>
                </div>

                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-color);">Trustee Information</h3>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="trustee_name" class="form-label required-label">Trustee Full Name</label>
                        <input type="text" class="form-control" id="trustee_name" name="trustee_name" placeholder="Enter trustee's full name" required>
                        <div class="error-message" id="trusteeNameError"></div>
                    </div>
                    <div class="form-group">
                        <label for="trustee_nric" class="form-label required-label">Trustee NRIC/Passport</label>
                        <input type="text" class="form-control" id="trustee_nric" name="trustee_nric" placeholder="000000-00-0000" required pattern="^\d{6}-\d{2}-\d{4}$|\d{12}$" oninput="this.value = formatNric(this.value)">
                        <div class="error-message" id="trusteeNricError"></div>
                    </div>
                    <div class="form-group">
                        <label for="trustee_phone" class="form-label required-label">Trustee Contact Phone</label>
                        <input type="tel" class="form-control" id="trustee_phone" name="trustee_phone" placeholder="012-3456789" required pattern="^01\d-\d{7,8}$|^01\d{8,9}$" oninput="this.value = formatPhone(this.value)">
                        <div class="error-message" id="trusteePhoneError"></div>
                    </div>
                    <div class="form-group">
                        <label for="trustee_email" class="form-label required-label">Trustee Email</label>
                        <input type="email" class="form-control" id="trustee_email" name="trustee_email" placeholder="trustee@email.com" required>
                        <div class="error-message" id="trusteeEmailError"></div>
                        <small style="color: var(--gray-500);">Required for notification</small>
                    </div>
                </div>

                <h3 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 1rem; color: var(--primary-color);">Wasiyyah Beneficiaries</h3>
                <div class="info-card" style="margin-bottom: 1rem;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Add beneficiaries for wasiyyah (will). Total wasiyyah cannot exceed 1/3 (33.33%) of the net estate.</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span><strong>Wasiyyah Limit:</strong> 1/3 of Net Estate (RM <span id="maxWasiyyahAmount">0.00</span>)</span>
                        <span><strong>Current Wasiyyah:</strong> <span id="currentWasiyyahPercent">0.00</span>% (RM <span id="currentWasiyyahAmount">0.00</span>)</span>
                    </div>
                    <div class="wasiyyah-limit-bar">
                        <div class="wasiyyah-limit-fill" id="wasiyyahLimitFill" style="width: 0%;"></div>
                    </div>
                </div>

                <div class="wasiyyah-card">
                    <div class="wasiyyah-card-header">
                        <div class="wasiyyah-card-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Wasiyyah Beneficiary
                        </div>
                    </div>
                    <div class="wasiyyah-card-body">
                        <div class="grid-3">
                            <div class="form-group">
                                <label class="form-label required-label">Beneficiary Name</label>
                                <input type="text" id="wasiyyahName" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">NRIC/Passport</label>
                                <input type="text" id="wasiyyahNric" class="form-control" placeholder="000000-00-0000" required pattern="^\d{6}-\d{2}-\d{4}$|\d{12}$" oninput="this.value = formatNric(this.value)">
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Contact Email</label>
                                <input type="email" id="wasiyyahEmail" class="form-control" placeholder="beneficiary@email.com" required>
                                <small style="color: var(--gray-500);">Required for notification</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Relationship</label>
                                <select id="wasiyyahRelationship" class="form-control" required>
                                    <option value="" disabled selected>Select Relationship</option>
                                    <option value="Charity Organization">Charity Organization</option>
                                    <option value="Mosque">Mosque</option>
                                    <option value="School">School</option>
                                    <option value="Friend">Friend</option>
                                    <option value="Non-Heir Relative">Non-Heir Relative</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Percentage (%)</label>
                                <input type="number" id="wasiyyahPercentage" class="form-control" placeholder="0.00" step="0.01" min="0" max="33.33" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-label">Contact Phone</label>
                                <input type="tel" id="wasiyyahPhone" class="form-control" placeholder="012-3456789" required pattern="^01\d-\d{7,8}$|^01\d{8,9}$" oninput="this.value = formatPhone(this.value)">
                            </div>
                        </div>
                        <div class="btn-group" style="margin-top:0;padding-top:0;border-top:0;">
                            <button type="button" class="btn btn-primary" id="addWasiyyahBtn">+ Add Beneficiary</button>
                            <button type="button" class="btn btn-secondary" id="clearWasiyyahBtn">Clear All</button>
                        </div>
                    </div>
                </div>
                <div id="wasiyyahList" style="margin-top:1.5rem;"></div>

                <input type="hidden" id="wasiyyahData" name="wasiyyah_data">

                <h3 style="font-size: 1rem; font-weight: 600; margin: 1.5rem 0 1rem; color: var(--primary-color);">Final Wishes Video Message</h3>
                <div class="info-card" style="margin-bottom: 1rem;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <p>Share your final wishes via video or YouTube link. This message will be accessible to your heirs and trustee after your passing.</p>
                </div>

                <!-- Video Options Tabs -->
                <div class="video-option-tabs">
                    <button type="button" class="video-option-tab active" data-video-option="upload">📹 Upload Video</button>
                    <button type="button" class="video-option-tab" data-video-option="youtube">▶️ YouTube Link</button>
                </div>

                <!-- Upload Video Option -->
                <div class="video-option-content active" id="uploadVideoOption">
                    <div class="video-upload-area" id="videoUploadArea">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; color: var(--primary-color);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <p style="margin-bottom: 0.5rem;">Click to upload a video file</p>
                        <small style="color: var(--gray-500);">MP4, MOV, or AVI format. Max 25MB (recommended for email delivery).</small>
                        <input type="file" id="will_video" name="will_video" accept="video/*" style="display: none;">
                    </div>
                    <div class="video-preview" id="videoPreview" style="display: none;">
                        <video id="videoPlayer" controls></video>
                        <button type="button" class="btn btn-secondary btn-sm" id="removeVideoBtn" style="margin-top: 0.5rem;">Remove Video</button>
                    </div>
                </div>

                <!-- YouTube Link Option -->
                <div class="video-option-content" id="youtubeVideoOption">
                    <div class="youtube-url-input">
                        <label class="form-label">YouTube Video URL</label>
                        <input type="url" id="youtube_url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
                        <small style="color: var(--gray-500);">Enter a valid YouTube video link (unlisted or public). The video will be embedded for heirs to view.</small>
                    </div>
                    <div class="youtube-preview" id="youtubePreview">
                        <div style="margin-bottom: 0.5rem;"><strong>Preview:</strong></div>
                        <iframe id="youtubeIframe" src="" frameborder="0" allowfullscreen></iframe>
                        <button type="button" class="btn btn-secondary btn-sm" id="removeYoutubeBtn" style="margin-top: 0.5rem; display: none;">Remove YouTube Link</button>
                    </div>
                </div>

                <input type="hidden" id="video_option" name="video_option" value="none">
                <input type="hidden" id="will_video_url" name="will_video_url">

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="heirs">← Back</button>
                    <button type="button" class="btn btn-primary next-section" data-next="review">Continue to Review →</button>
                </div>
            </div>
        </div>

        <!-- SECTION 6: REVIEW & ACTIVATE -->
        <div class="glass-card" id="reviewSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="card-title">Review & Activate</h2>
                <span class="card-badge">Final Step</span>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="background:var(--gray-50);padding:1rem;border-radius:12px;text-align:center;">
                                <div style="font-size:0.875rem;color:var(--gray-600);">Estate Owner</div>
                                <div style="font-weight:700;font-size:1.125rem;" id="reviewName">-</div>
                            </div>
                            <div style="background:var(--gray-50);padding:1rem;border-radius:12px;text-align:center;">
                                <div style="font-size:0.875rem;color:var(--gray-600);">Total Heirs</div>
                                <div style="font-weight:700;font-size:1.125rem;" id="reviewHeirsCount">0</div>
                            </div>
                            <div style="background:var(--gray-50);padding:1rem;border-radius:12px;text-align:center;">
                                <div style="font-size:0.875rem;color:var(--gray-600);">Net Estate</div>
                                <div style="font-weight:700;font-size:1.125rem;color:var(--success-color);" id="reviewNetEstate">RM 0.00</div>
                            </div>
                        </div>

                        <div class="category-section">
                            <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;">Heirs Distribution (Faraid)</h3>
                            <div class="results-container">
                                <table class="results-table">
                                    <thead>
                                        <tr><th>Heir Name</th><th>Email</th><th>Relationship</th><th>Share (%)</th><th>Amount (RM)</th></tr>
                                    </thead>
                                    <tbody id="reviewHeirsTable">
                                        <tr><td colspan="5" style="text-align:center;padding:2rem;">Complete previous sections to see distribution preview</span></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="category-section" style="margin-top:1.5rem;">
                            <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;">Wasiyyah Beneficiaries</h3>
                            <div class="results-container">
                                <table class="results-table">
                                    <thead>
                                        <tr><th>Name</th><th>Email</th><th>Relationship</th><th>Share (%)</th><th>Amount (RM)</th></tr>
                                    </thead>
                                    <tbody id="reviewWasiyyahTable">
                                        <tr><td colspan="5" style="text-align:center;padding:1rem;">No wasiyyah beneficiaries</span></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="category-section" style="margin-top:1.5rem;">
                            <h3 style="font-size:1rem;font-weight:600;margin-bottom:1rem;">Trustee & Final Wishes</h3>
                            <div class="info-card" style="margin-bottom: 0;">
                                <div>
                                    <strong>Trustee:</strong> <span id="reviewTrusteeName">Not appointed</span><br>
                                    <strong>Final Wishes:</strong> <span id="reviewVideoStatus">Not provided</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="activation-summary-compact">
                            <h3 style="font-size:0.9375rem;font-weight:700;margin-bottom:0.75rem;color:var(--primary-color);">Activation Summary</h3>
                            <div class="summary-item">
                                <span class="summary-label">Status</span>
                                <span class="summary-value" id="activationStatus" style="font-size:0.875rem;">Ready for Activation</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Net Estate</span>
                                <span class="summary-value" id="activationNetEstate" style="font-size:0.875rem;">RM 0.00</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Wasiyyah Total</span>
                                <span class="summary-value" id="activationWasiyyahPercent" style="font-size:0.875rem;">0%</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Faraid Heirs</span>
                                <span class="summary-value" id="activationHeirsCount" style="font-size:0.875rem;">0</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Total Faraid %</span>
                                <span class="summary-value" id="activationFaraidPercent" style="font-size:0.875rem;">0%</span>
                            </div>
                            <div style="background:var(--primary-light);padding:0.5rem;border-radius:8px;margin-top:0.75rem;">
                                <div class="summary-item" style="padding:0.25rem 0;border-bottom:none;">
                                    <span class="summary-label" style="font-size:0.8125rem;">Remaining for Heirs</span>
                                    <span class="summary-value" id="activationRemainingForHeirs" style="font-size:0.8125rem;">RM 0.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="category-section" style="margin-top:1rem;">
                            <h3 style="font-size:0.9375rem;font-weight:600;margin-bottom:0.75rem;">Activation Confirmation</h3>
                            <div class="heir-card" style="background:var(--primary-light);padding:0.75rem 1rem;border-radius:var(--border-radius-md);">
                                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;font-size:0.875rem;">
                                    <input type="checkbox" id="confirmationCheckbox" style="width:18px;height:18px;">
                                    I confirm that all information provided is true and accurate to the best of my knowledge.
                                </label>
                                <small style="color:var(--gray-600);display:block;margin-top:0.5rem;font-size:0.75rem;">By activating this estate plan, you acknowledge that this is a legally binding document under Islamic law. The wasiyyah cannot exceed 1/3 of the net estate and cannot override faraid rights of legal heirs.</small>
                            </div>
                        </div>

                        <div class="btn-group" style="margin-top:1rem;">
                            <button type="button" class="btn btn-secondary prev-section" data-prev="wasiyyah">← Back</button>
                            <button type="button" class="btn btn-success" id="activatePlanBtn">Activate Estate Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function() {
    'use strict';

    // ===== STRICT PHONE FORMAT ENFORCEMENT =====
    const PHONE_PATTERN_REGEX = /^01\d-\d{7,8}$|^01\d{8,9}$/;
    const PHONE_FORMAT_EXAMPLE = "012-3456789";
    const MAX_VIDEO_SIZE_MB = 25;
    const MAX_VIDEO_SIZE_BYTES = MAX_VIDEO_SIZE_MB * 1024 * 1024;

    // ===== UTILITIES =====
    function escapeHtml(str) { 
        if (!str) return ''; 
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); 
    }

    function validateNRIC(nric) {
        if (!nric) return false;
        const clean = nric.replace(/[-\s]/g, '');
        return /^\d{6}-\d{2}-\d{4}$/.test(nric) || /^\d{12}$/.test(clean);
    }

    function formatNRIC(value) {
        if (!value) return value;
        let val = value.replace(/[^0-9]/g, '');
        if (val.length === 12) {
            val = val.substring(0,6) + '-' + val.substring(6,8) + '-' + val.substring(8,12);
        }
        return val;
    }

    function validateEmail(email) {
        if (!email) return false;
        const emailRegex = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
        return emailRegex.test(email);
    }

    function autoFormatPhone(value) {
        if (!value) return value;
        let digitsOnly = value.replace(/\D/g, '');
        if (!digitsOnly.startsWith('01') && digitsOnly.length >= 2) {
            if (digitsOnly.startsWith('1')) digitsOnly = '0' + digitsOnly;
        }
        if (digitsOnly.length > 11) digitsOnly = digitsOnly.substring(0, 11);
        if (digitsOnly.length >= 3 && digitsOnly.length > 3) {
            const prefix = digitsOnly.substring(0, 3);
            const suffix = digitsOnly.substring(3);
            return prefix + '-' + suffix;
        }
        return digitsOnly;
    }

    function validatePhoneStrict(phone) {
        if (!phone) return false;
        const cleanPhone = phone.replace(/\s/g, '');
        return PHONE_PATTERN_REGEX.test(cleanPhone);
    }

    // ===== ALERT SYSTEM =====
    class ModernAlert {
        static show({ type = 'info', title, message, duration = 4000 }) {
            const container = document.getElementById('alertContainer');
            if (!container) return;
            const alertId = 'alert-' + Date.now();
            const icons = {
                success: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                error: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                warning: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`,
                info: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
            };
            const alertEl = document.createElement('div');
            alertEl.className = `modern-alert ${type}`;
            alertEl.id = alertId;
            alertEl.innerHTML = `
                ${icons[type] || icons.info}
                <div class="alert-content">
                    <div class="alert-title">${escapeHtml(title)}</div>
                    <div class="alert-message">${escapeHtml(message)}</div>
                </div>
                <button class="alert-close" onclick="document.getElementById('${alertId}').classList.remove('show'); setTimeout(() => document.getElementById('${alertId}')?.remove(), 500);">✕</button>
            `;
            container.appendChild(alertEl);
            setTimeout(() => alertEl.classList.add('show'), 10);
            if (duration > 0) {
                setTimeout(() => {
                    alertEl.classList.remove('show');
                    setTimeout(() => alertEl.remove(), 500);
                }, duration);
            }
            return alertId;
        }
        static success(message, title = 'Success') { return this.show({ type: 'success', title, message }); }
        static error(message, title = 'Action Blocked') { return this.show({ type: 'error', title, message, duration: 5000 }); }
        static warning(message, title = 'Warning') { return this.show({ type: 'warning', title, message }); }
        static info(message, title = 'Information') { return this.show({ type: 'info', title, message }); }
    }

    // ===== CONFIRMATION MODAL =====
    let pendingConfirmCallback = null;
    function showConfirmationModal(options) {
        const { title, message, itemName, iconType = 'warning', onConfirm } = options;
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const modalItemName = document.getElementById('modalItemName');
        const modalIcon = document.getElementById('modalIcon');
        const confirmBtn = document.getElementById('modalConfirmBtn');
        const cancelBtn = document.getElementById('modalCancelBtn');
        modalTitle.textContent = title || 'Confirm Action';
        modalMessage.textContent = message || 'Are you sure you want to proceed?';
        if (itemName) {
            modalItemName.innerHTML = `<span class="confirmation-modal-item-name">${escapeHtml(itemName)}</span>`;
        } else {
            modalItemName.innerHTML = '';
        }
        modalIcon.className = `confirmation-modal-icon ${iconType}`;
        if (iconType === 'danger') {
            modalIcon.innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>`;
            confirmBtn.style.background = 'var(--danger-color)';
        } else if (iconType === 'warning') {
            modalIcon.innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`;
            confirmBtn.style.background = 'var(--warning-color)';
        } else {
            modalIcon.innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
            confirmBtn.style.background = 'var(--info-color)';
        }
        pendingConfirmCallback = onConfirm;
        const closeModal = () => {
            modal.classList.remove('active');
            pendingConfirmCallback = null;
        };
        confirmBtn.onclick = () => {
            if (pendingConfirmCallback) pendingConfirmCallback();
            closeModal();
        };
        cancelBtn.onclick = closeModal;
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        modal.classList.add('active');
    }

    // ===== GLOBAL STATE =====
    let heirs = [];
    let assets = [];
    let debts = [];
    let credentials = [];
    let wasiyyah = [];
    let nextHeirId = 1;
    let nextAssetId = 1;
    let nextDebtId = 1;
    let nextCredentialId = 1;
    let nextWasiyyahId = 1;
    let videoFile = null;
    let youtubeUrl = '';
    let currentVideoOption = 'none';

    // ===== NET ESTATE CALCULATIONS =====
    function getTotalAssetsShare() {
        return assets.reduce((sum, a) => sum + (a.value * (a.ownership / 100)), 0);
    }
    function getTotalDebts() {
        return debts.reduce((sum, d) => sum + d.amount, 0);
    }
    function getNetEstate() {
        return Math.max(0, getTotalAssetsShare() - getTotalDebts());
    }

    // ===== ASSETS MANAGEMENT =====
    const assetClassification = {
        "Residential House": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Apartment / Condominium": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Low-cost Flat (PPR / Kos Rendah)": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Shop Lot": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Office Unit": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Industrial Property (Factory / Warehouse)": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Agricultural Land": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Vacant Land / Lot": { category: "Real Estate (Immovable Property)", badge: "badge-real-estate", label: "Real Estate" },
        "Vehicle (Car / Motorcycle)": { category: "Movable Asset", badge: "badge-financial", label: "Movable Asset" },
        "Bank Savings": { category: "Financial Asset", badge: "badge-financial", label: "Financial Asset" },
        "Fixed Deposit": { category: "Financial Asset", badge: "badge-financial", label: "Financial Asset" },
        "EPF / KWSP Savings": { category: "Financial Asset", badge: "badge-financial", label: "Financial Asset" },
        "Tabung Haji Savings": { category: "Financial Asset", badge: "badge-financial", label: "Financial Asset" },
        "ASB / Unit Trust Investment": { category: "Investment Asset", badge: "badge-investment", label: "Investment Asset" },
        "Shares / Stocks": { category: "Investment Asset", badge: "badge-investment", label: "Investment Asset" },
        "Gold / Precious Metals": { category: "Investment Asset", badge: "badge-investment", label: "Investment Asset" },
        "Business Ownership": { category: "Business Asset", badge: "badge-investment", label: "Business Asset" },
        "Insurance / Takaful Payout": { category: "Financial Asset", badge: "badge-financial", label: "Financial Asset" },
        "Cash in Hand": { category: "Liquid Asset", badge: "badge-financial", label: "Liquid Asset" },
        "Digital Assets (Crypto / E-wallet)": { category: "Digital Asset", badge: "badge-digital", label: "Digital Asset" },
        "Other Assets": { category: "Other Asset", badge: "badge-real-estate", label: "Other Asset" }
    };

    function addAsset() {
        const name = document.getElementById('assetName')?.value;
        const value = parseFloat(document.getElementById('assetValue')?.value) || 0;
        const ownership = parseFloat(document.getElementById('assetOwnership')?.value) || 100;
        const description = document.getElementById('assetDescription')?.value.trim() || '';
        if (!name) { ModernAlert.error('Please select an asset type.', 'Required Field'); return; }
        if (value <= 0) { ModernAlert.error('Please enter a valid asset value greater than 0.', 'Invalid Value'); return; }
        if (ownership < 0 || ownership > 100) { ModernAlert.error('Ownership percentage must be between 0 and 100.', 'Invalid Value'); return; }
        const classification = assetClassification[name] || { category: 'Other Asset', badge: 'badge-real-estate', label: 'Other Asset' };
        assets.push({ id: nextAssetId++, name, value, ownership, description, category: classification.category, badge: classification.badge, label: classification.label });
        renderAssetsList();
        clearAssetForm();
        updateTotals();
        ModernAlert.success(`Asset "${name}" added with value RM ${value.toLocaleString()} (${ownership}% ownership).`, 'Success');
    }

    function clearAssetForm() {
        document.getElementById('assetName').value = '';
        document.getElementById('assetValue').value = '';
        document.getElementById('assetOwnership').value = '100';
        document.getElementById('assetDescription').value = '';
        const preview = document.getElementById('assetCategoryPreview');
        if (preview) { preview.className = 'badge badge-real-estate'; preview.textContent = 'Real Estate'; }
    }

    function removeAsset(index) {
        const assetName = assets[index]?.name || 'this asset';
        showConfirmationModal({
            title: 'Remove Asset', message: 'Are you sure you want to remove this asset?', itemName: assetName, iconType: 'danger',
            onConfirm: () => { assets.splice(index, 1); renderAssetsList(); updateTotals(); ModernAlert.info(`${assetName} has been removed.`, 'Asset Removed'); }
        });
    }

    function clearAllAssets() {
        if (assets.length === 0) { ModernAlert.info('No assets to clear.', 'Information'); return; }
        showConfirmationModal({
            title: 'Clear All Assets', message: `Are you sure you want to remove all ${assets.length} asset(s)?`, iconType: 'danger',
            onConfirm: () => { assets = []; renderAssetsList(); updateTotals(); ModernAlert.warning('All assets have been cleared.', 'Assets Cleared'); }
        });
    }

    function renderAssetsList() {
        const container = document.getElementById('assetsList');
        if (!container) return;
        if (assets.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--gray-500);border:1px dashed var(--gray-300);border-radius:12px;">No assets added yet. Add your first asset above.</div>';
            return;
        }
        container.innerHTML = '';
        assets.forEach((asset, index) => {
            const shareValue = asset.value * (asset.ownership / 100);
            const card = document.createElement('div');
            card.className = 'asset-card';
            card.innerHTML = `
                <div class="asset-card-header">
                    <div class="asset-card-title">
                        <span class="badge ${asset.badge}">${asset.label}</span>
                        <strong>${escapeHtml(asset.name)}</strong>
                    </div>
                    <button type="button" class="btn-remove" data-action="remove-asset" data-index="${index}">✕ Remove</button>
                </div>
                <div class="asset-card-body">
                    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                        <div><strong>Value (RM):</strong> ${asset.value.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                        <div><strong>Ownership:</strong> ${asset.ownership}%</div>
                        <div><strong>Your Share:</strong> RM ${shareValue.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                        <div><strong>Description:</strong> ${escapeHtml(asset.description) || '-'}</div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
        container.querySelectorAll('[data-action="remove-asset"]').forEach(btn => {
            btn.addEventListener('click', function() { removeAsset(parseInt(this.dataset.index)); });
        });
    }

    // ===== DEBTS MANAGEMENT =====
    const debtClassification = {
        "Housing Loan": { type: "Secured Debt", badge: "badge-secured", label: "Secured Debt" },
        "Personal Loan": { type: "Unsecured Debt", badge: "badge-unsecured", label: "Unsecured Debt" },
        "Car Loan / Hire Purchase": { type: "Secured Debt", badge: "badge-secured", label: "Secured Debt" },
        "Credit Card Outstanding": { type: "Unsecured Debt", badge: "badge-unsecured", label: "Unsecured Debt" },
        "Business Loan": { type: "Secured Debt", badge: "badge-secured", label: "Secured Debt" },
        "Education Loan (PTPTN)": { type: "Unsecured Debt", badge: "badge-unsecured", label: "Unsecured Debt" },
        "Overdraft / Bank Facility": { type: "Secured Debt", badge: "badge-secured", label: "Secured Debt" },
        "Borrowed from Family / Friends": { type: "Personal Debt (Huquq Al-Ibad)", badge: "badge-unsecured", label: "Personal Debt" },
        "Unpaid Zakat": { type: "Religious Obligations (Huquq Allah)", badge: "badge-religious", label: "Religious Obligation" },
        "Unpaid Income Tax (LHDN)": { type: "Government / Statutory Debt", badge: "badge-secured", label: "Government Debt" },
        "Utility Bills": { type: "Administrative / Final Expenses", badge: "badge-unsecured", label: "Administrative" },
        "Medical Bills": { type: "Administrative / Final Expenses", badge: "badge-unsecured", label: "Administrative" },
        "Funeral Expenses": { type: "Administrative / Final Expenses", badge: "badge-unsecured", label: "Administrative" },
        "Court Fines / Legal Penalties": { type: "Government / Statutory Debt", badge: "badge-secured", label: "Government Debt" },
        "Other Liabilities": { type: "Other Debt", badge: "badge-unsecured", label: "Other Debt" }
    };

    function addDebt() {
        const name = document.getElementById('debtName')?.value;
        const amount = parseFloat(document.getElementById('debtAmount')?.value) || 0;
        const creditor = document.getElementById('debtCreditor')?.value.trim() || '';
        const description = document.getElementById('debtDescription')?.value.trim() || '';
        if (!name) { ModernAlert.error('Please select a debt type.', 'Required Field'); return; }
        if (amount <= 0) { ModernAlert.error('Please enter a valid debt amount greater than 0.', 'Invalid Value'); return; }
        const classification = debtClassification[name] || { type: 'Other Debt', badge: 'badge-unsecured', label: 'Other Debt' };
        debts.push({ id: nextDebtId++, name, amount, creditor, description, type: classification.type, badge: classification.badge, label: classification.label });
        renderDebtsList();
        clearDebtForm();
        updateTotals();
        ModernAlert.info(`Debt "${name}" recorded.`, 'Debt Recorded');
    }

    function clearDebtForm() {
        document.getElementById('debtName').value = '';
        document.getElementById('debtAmount').value = '';
        document.getElementById('debtCreditor').value = '';
        document.getElementById('debtDescription').value = '';
        const preview = document.getElementById('debtClassificationPreview');
        if (preview) { preview.className = 'badge badge-secured'; preview.textContent = 'Secured Debt'; }
    }

    function removeDebt(index) {
        const debtName = debts[index]?.name || 'this debt';
        showConfirmationModal({
            title: 'Remove Debt', message: 'Are you sure you want to remove this debt?', itemName: debtName, iconType: 'danger',
            onConfirm: () => { debts.splice(index, 1); renderDebtsList(); updateTotals(); ModernAlert.info(`${debtName} has been removed.`, 'Debt Removed'); }
        });
    }

    function clearAllDebts() {
        if (debts.length === 0) { ModernAlert.info('No debts to clear.', 'Information'); return; }
        showConfirmationModal({
            title: 'Clear All Debts', message: `Are you sure you want to remove all ${debts.length} debt(s)?`, iconType: 'danger',
            onConfirm: () => { debts = []; renderDebtsList(); updateTotals(); ModernAlert.warning('All debts have been cleared.', 'Debts Cleared'); }
        });
    }

    function renderDebtsList() {
        const container = document.getElementById('debtsList');
        if (!container) return;
        if (debts.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--gray-500);border:1px dashed var(--gray-300);border-radius:12px;">No debts added yet. Add your first debt above.</div>';
            return;
        }
        container.innerHTML = '';
        debts.forEach((debt, index) => {
            const card = document.createElement('div');
            card.className = 'debt-card';
            card.innerHTML = `
                <div class="debt-card-header">
                    <div class="debt-card-title">
                        <span class="badge ${debt.badge}">${debt.label}</span>
                        <strong>${escapeHtml(debt.name)}</strong>
                    </div>
                    <button type="button" class="btn-remove" data-action="remove-debt" data-index="${index}">✕ Remove</button>
                </div>
                <div class="debt-card-body">
                    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                        <div><strong>Amount:</strong> RM ${debt.amount.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                        <div><strong>Creditor:</strong> ${escapeHtml(debt.creditor) || '-'}</div>
                        <div><strong>Description:</strong> ${escapeHtml(debt.description) || '-'}</div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
        container.querySelectorAll('[data-action="remove-debt"]').forEach(btn => {
            btn.addEventListener('click', function() { removeDebt(parseInt(this.dataset.index)); });
        });
    }

    // ===== WASIYYAH MANAGEMENT =====
    function addWasiyyah() {
        const name = document.getElementById('wasiyyahName')?.value.trim();
        let nric = document.getElementById('wasiyyahNric')?.value.trim();
        const email = document.getElementById('wasiyyahEmail')?.value.trim();
        const relationship = document.getElementById('wasiyyahRelationship')?.value;
        const percentage = parseFloat(document.getElementById('wasiyyahPercentage')?.value) || 0;
        let phone = document.getElementById('wasiyyahPhone')?.value.trim();
        if (!name) { ModernAlert.error('Beneficiary name is required.', 'Required Field'); return; }
        if (!nric) { ModernAlert.error('NRIC/Passport is required.', 'Required Field'); return; }
        if (!validateNRIC(nric)) { ModernAlert.error(`Invalid NRIC format. Use ${NRIC_FORMAT}.`, 'Invalid NRIC'); return; }
        if (!email) { ModernAlert.error('Email is required for notification.', 'Required Field'); return; }
        if (!validateEmail(email)) { ModernAlert.error('Please enter a valid email address.', 'Invalid Email'); return; }
        if (!relationship) { ModernAlert.error('Please select a relationship.', 'Required Field'); return; }
        if (percentage <= 0) { ModernAlert.error('Percentage must be greater than 0.', 'Invalid Value'); return; }
        if (!phone) { ModernAlert.error('Contact phone is required.', 'Required Field'); return; }
        if (!validatePhoneStrict(phone)) { ModernAlert.error(`Invalid phone number. Use format: ${PHONE_FORMAT_EXAMPLE}`, 'Invalid Phone'); return; }
        const currentTotal = wasiyyah.reduce((sum, w) => sum + w.percentage, 0);
        if (currentTotal + percentage > 33.33) {
            ModernAlert.error(`Total wasiyyah cannot exceed 1/3 (33.33%) of net estate. Current total: ${currentTotal.toFixed(2)}%.`, 'Wasiyyah Limit Exceeded');
            return;
        }
        if (nric) nric = formatNRIC(nric);
        if (phone) phone = autoFormatPhone(phone);
        wasiyyah.push({ id: nextWasiyyahId++, name, nric: nric, email, relationship, percentage, phone: phone });
        renderWasiyyahList();
        clearWasiyyahForm();
        updateTotals();
        ModernAlert.success(`Wasiyyah beneficiary "${name}" added (${percentage}%).`, 'Success');
    }

    function clearWasiyyahForm() {
        ['wasiyyahName', 'wasiyyahNric', 'wasiyyahEmail', 'wasiyyahRelationship', 'wasiyyahPercentage', 'wasiyyahPhone']
            .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    }

    function removeWasiyyah(index) {
        const beneficiaryName = wasiyyah[index]?.name || 'this beneficiary';
        showConfirmationModal({
            title: 'Remove Wasiyyah Beneficiary', message: 'Are you sure you want to remove this wasiyyah beneficiary?', itemName: beneficiaryName, iconType: 'danger',
            onConfirm: () => { wasiyyah.splice(index, 1); renderWasiyyahList(); updateTotals(); ModernAlert.info(`${beneficiaryName} has been removed.`, 'Beneficiary Removed'); }
        });
    }

    function clearAllWasiyyah() {
        if (wasiyyah.length === 0) { ModernAlert.info('No wasiyyah beneficiaries to clear.', 'Information'); return; }
        showConfirmationModal({
            title: 'Clear All Wasiyyah', message: `Are you sure you want to remove all ${wasiyyah.length} wasiyyah beneficiaries?`, iconType: 'danger',
            onConfirm: () => { wasiyyah = []; renderWasiyyahList(); updateTotals(); ModernAlert.warning('All wasiyyah beneficiaries have been cleared.', 'Wasiyyah Cleared'); }
        });
    }

    function renderWasiyyahList() {
        const container = document.getElementById('wasiyyahList');
        if (!container) return;
        const netEstate = getNetEstate();
        const totalPercent = wasiyyah.reduce((sum, w) => sum + w.percentage, 0);
        if (wasiyyah.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--gray-500);border:1px dashed var(--gray-300);border-radius:12px;">No wasiyyah beneficiaries added yet. Add beneficiaries above (max 33.33% total).</div>';
            document.getElementById('wasiyyahBadge').textContent = '0 Beneficiaries';
            return;
        }
        container.innerHTML = '';
        document.getElementById('wasiyyahBadge').textContent = wasiyyah.length + (wasiyyah.length === 1 ? ' Beneficiary' : ' Beneficiaries');
        wasiyyah.forEach((w, index) => {
            const amount = (netEstate * w.percentage) / 100;
            const card = document.createElement('div');
            card.className = 'wasiyyah-card';
            card.innerHTML = `
                <div class="wasiyyah-card-header">
                    <div class="wasiyyah-card-title">
                        <span class="badge badge-info">Wasiyyah</span>
                        <strong>${escapeHtml(w.name)}</strong>
                    </div>
                    <button type="button" class="btn-remove" data-action="remove-wasiyyah" data-index="${index}">✕ Remove</button>
                </div>
                <div class="wasiyyah-card-body">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;">
                        <div><strong>NRIC:</strong> ${escapeHtml(w.nric) || '-'}</div>
                        <div><strong>Email:</strong> ${escapeHtml(w.email)}</div>
                        <div><strong>Relationship:</strong> ${escapeHtml(w.relationship)}</div>
                        <div><strong>Percentage:</strong> ${w.percentage}%</div>
                        <div><strong>Amount:</strong> RM ${amount.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                        <div><strong>Phone:</strong> ${escapeHtml(w.phone) || '-'}</div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
        container.querySelectorAll('[data-action="remove-wasiyyah"]').forEach(btn => {
            btn.addEventListener('click', function() { removeWasiyyah(parseInt(this.dataset.index)); });
        });
        const fillBar = document.getElementById('wasiyyahLimitFill');
        const percentUsed = (totalPercent / 33.33) * 100;
        fillBar.style.width = Math.min(percentUsed, 100) + '%';
        fillBar.className = totalPercent <= 33.33 ? 'wasiyyah-limit-fill safe' : 'wasiyyah-limit-fill';
    }

    // ===== HEIRS MANAGEMENT (with Faraid Exact Fractions) =====
    const NRIC_FORMAT = "000000-00-0000";
    const NRIC_REGEX = /^\d{6}-\d{2}-\d{4}$/;
    const NRIC_RAW_REGEX = /^\d{12}$/;

    function validateHeirFields() {
        let isValid = true;
        const name = document.getElementById('heirName')?.value.trim();
        const nric = document.getElementById('heirNric')?.value.trim();
        const email = document.getElementById('heirEmail')?.value.trim();
        const relationship = document.getElementById('heirRelationship')?.value;
        const percentage = parseFloat(document.getElementById('heirPercentage')?.value) || 0;
        const phone = document.getElementById('heirPhone')?.value.trim();
        document.getElementById('heirNameError').textContent = name ? '' : 'Full name is required';
        if (!name) isValid = false;
        document.getElementById('heirNricError').textContent = !nric ? 'NRIC/Passport is required' : (!validateNRIC(nric) ? `Invalid NRIC format. Use ${NRIC_FORMAT}` : '');
        if (!nric || !validateNRIC(nric)) isValid = false;
        document.getElementById('heirEmailError').textContent = !email ? 'Email is required for notification' : (!validateEmail(email) ? 'Invalid email format' : '');
        if (!email || !validateEmail(email)) isValid = false;
        document.getElementById('heirRelationshipError').textContent = relationship ? '' : 'Relationship is required';
        if (!relationship) isValid = false;
        document.getElementById('heirPercentageError').textContent = percentage > 0 ? '' : 'Share percentage is required';
        if (percentage <= 0) isValid = false;
        document.getElementById('heirPhoneError').textContent = !phone ? 'Contact phone is required' : (!validatePhoneStrict(phone) ? `Invalid phone format. Use ${PHONE_FORMAT_EXAMPLE}` : '');
        if (!phone || !validatePhoneStrict(phone)) isValid = false;
        return isValid;
    }

    function addHeir() {
        if (!validateHeirFields()) { 
            ModernAlert.error('Please fix all validation errors before adding the heir. All fields marked with * are required.', 'Validation Error'); 
            return; 
        }
        const name = document.getElementById('heirName')?.value.trim();
        let nric = document.getElementById('heirNric')?.value.trim();
        const email = document.getElementById('heirEmail')?.value.trim();
        const relationship = document.getElementById('heirRelationship')?.value;
        const percentage = parseFloat(document.getElementById('heirPercentage')?.value) || 0;
        let phone = document.getElementById('heirPhone')?.value.trim();
        if (nric) nric = formatNRIC(nric);
        if (phone) phone = autoFormatPhone(phone);
        const relationshipMap = {
            'husband': 'Husband', 'wife': 'Wife', 'father': 'Father', 'mother': 'Mother',
            'grandfather': "Father's Father", 'grandmother_paternal': "Father's Mother",
            'grandmother_maternal': "Mother's Mother", 'son': 'Son', 'daughter': 'Daughter',
            'half_brother_full': "Paternal & Maternal Half-Brother",
            'half_brother_paternal': "Paternal Half-Brother",
            'half_brother_maternal': "Maternal Half-Brother",
            'half_sister_full': "Paternal & Maternal Half-Sister",
            'half_sister_paternal': "Paternal Half-Sister",
            'half_sister_maternal': "Maternal Half-Sister",
            'baitulmal': 'Baitulmal (Public Treasury)'
        };
        const relationshipDisplay = relationshipMap[relationship] || relationship;
        heirs.push({ id: nextHeirId++, name, nric: nric, email, relationship, relationshipDisplay, percentage, phone: phone });
        renderHeirsList();
        clearHeirForm();
        updateTotals();
        ModernAlert.success(`Heir "${name}" added.`, 'Success');
    }

    function clearHeirForm() {
        ['heirName', 'heirNric', 'heirEmail', 'heirRelationship', 'heirPercentage', 'heirPhone']
            .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
        ['heirNameError', 'heirNricError', 'heirEmailError', 'heirRelationshipError', 'heirPercentageError', 'heirPhoneError']
            .forEach(id => { const el = document.getElementById(id); if (el) el.textContent = ''; });
    }

    function removeHeir(index) {
        const heirName = heirs[index]?.name || 'this heir';
        showConfirmationModal({
            title: 'Remove Heir', message: 'Are you sure you want to remove this heir?', itemName: heirName, iconType: 'danger',
            onConfirm: () => { heirs.splice(index, 1); renderHeirsList(); updateTotals(); ModernAlert.info(`${heirName} has been removed.`, 'Heir Removed'); }
        });
    }

    function clearAllHeirs() {
        if (heirs.length === 0) { ModernAlert.info('No heirs to clear.', 'Information'); return; }
        showConfirmationModal({
            title: 'Clear All Heirs', message: `Are you sure you want to remove all ${heirs.length} heir(s)?`, iconType: 'danger',
            onConfirm: () => { heirs = []; nextHeirId = 1; renderHeirsList(); updateTotals(); ModernAlert.warning('All heirs have been cleared.', 'Heirs Cleared'); }
        });
    }

    function updateHeirField(index, field, value) {
        if (heirs[index]) {
            if (field === 'nric') value = formatNRIC(value);
            else if (field === 'phone') value = autoFormatPhone(value);
            heirs[index][field] = value;
            renderHeirsList();
            updateTotals();
        }
    }

    function updateHeirPercentage(index, value) {
        if (heirs[index]) {
            heirs[index].percentage = parseFloat(value) || 0;
            renderHeirsList();
            updateTotals();
        }
    }

    function renderHeirsList() {
        const container = document.getElementById('heirsList');
        if (!container) return;
        const totalPercentage = heirs.reduce((sum, h) => sum + (h.percentage || 0), 0);
        document.getElementById('totalHeirsBadge').textContent = heirs.length + (heirs.length === 1 ? ' Heir' : ' Heirs');
        document.getElementById('heirsCountDisplay').textContent = heirs.length;
        document.getElementById('totalPercentageDisplay').textContent = totalPercentage.toFixed(2) + '%';
        document.getElementById('totalPercentageDisplay').style.color = Math.abs(totalPercentage - 100) > 0.01 ? '#dc3545' : '#28a745';
        document.getElementById('remainingPercentageDisplay').textContent = (100 - totalPercentage).toFixed(2) + '%';
        if (heirs.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--gray-500);border:1px dashed var(--gray-300);border-radius:12px;">No heirs added yet. Click "Add Heir" or use "Calculate Faraid Shares" to designate beneficiaries. Total distribution must equal 100%.</div>';
            return;
        }
        container.innerHTML = '';
        heirs.forEach((heir, index) => {
            const isIncomplete = !heir.name || heir.name.startsWith('Son ') || heir.name.startsWith('Daughter ') || 
                                heir.name === 'Husband' || heir.name === 'Wife' || heir.name === 'Father' || heir.name === 'Mother' ||
                                !heir.nric || !validateNRIC(heir.nric) || 
                                !heir.email || !validateEmail(heir.email) ||
                                !heir.phone || !validatePhoneStrict(heir.phone);
            const card = document.createElement('div');
            card.className = 'heir-card';
            if (isIncomplete) card.style.border = '2px solid var(--danger-color)';
            card.innerHTML = `
                <div class="heir-card-header">
                    <div class="heir-card-title">
                        <span class="badge badge-heir">Heir</span>
                        <strong>${escapeHtml(heir.name)}</strong>
                        <span style="margin-left:0.5rem;font-size:0.875rem;color:var(--gray-600);">(${escapeHtml(heir.relationshipDisplay)})</span>
                    </div>
                    <button type="button" class="btn-remove" data-action="remove-heir" data-index="${index}">✕ Remove</button>
                </div>
                <div class="heir-card-body">
                    ${isIncomplete ? '<div style="background:var(--danger-light);padding:0.5rem 0.75rem;border-radius:8px;margin-bottom:1rem;font-size:0.8125rem;color:var(--danger-dark);">⚠️ Please update all fields (Name, NRIC, Email, Phone).</div>' : ''}
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;">
                        <div><strong>NRIC:</strong> ${escapeHtml(heir.nric) || '-'}</div>
                        <div><strong>Email:</strong> ${escapeHtml(heir.email) || '-'}</div>
                        <div><strong>Phone:</strong> ${escapeHtml(heir.phone) || '-'}</div>
                        <div><strong>Share Percentage:</strong> <span style="color:var(--primary-color);font-weight:700;">${(heir.percentage || 0).toFixed(2)}%</span></div>
                    </div>
                    <div style="margin-top:1rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0.75rem;">
                        <div><input type="text" class="form-control heir-edit-name" placeholder="Full Name *" value="${escapeHtml(heir.name)}" data-index="${index}" data-field="name" required></div>
                        <div><input type="text" class="form-control heir-edit-nric" placeholder="${NRIC_FORMAT} *" value="${escapeHtml(heir.nric)}" data-index="${index}" data-field="nric" required pattern="^\d{6}-\d{2}-\d{4}$|\d{12}$"></div>
                        <div><input type="email" class="form-control heir-edit-email" placeholder="Email *" value="${escapeHtml(heir.email)}" data-index="${index}" data-field="email" required></div>
                        <div><input type="tel" class="form-control heir-edit-phone" placeholder="${PHONE_FORMAT_EXAMPLE} *" value="${escapeHtml(heir.phone)}" data-index="${index}" data-field="phone" required pattern="^01\d-\d{7,8}$|^01\d{8,9}$"></div>
                        <div><input type="number" class="form-control heir-edit-percentage" placeholder="% *" value="${heir.percentage}" step="0.01" min="0" max="100" data-index="${index}" data-field="percentage" required></div>
                        <button type="button" class="btn btn-secondary btn-sm heir-update-btn" data-index="${index}" style="padding:0.5rem 1rem;white-space:nowrap;">Update</button>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
        container.querySelectorAll('.heir-edit-phone').forEach(input => {
            input.addEventListener('input', function(e) { e.target.value = autoFormatPhone(e.target.value); });
            input.addEventListener('blur', function(e) {
                const formatted = autoFormatPhone(e.target.value);
                if (formatted !== e.target.value) e.target.value = formatted;
            });
        });
        container.querySelectorAll('[data-action="remove-heir"]').forEach(btn => {
            btn.addEventListener('click', function() { removeHeir(parseInt(this.dataset.index)); });
        });
        container.querySelectorAll('.heir-update-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                const card = this.closest('.heir-card');
                const nameInput = card.querySelector('.heir-edit-name');
                const nricInput = card.querySelector('.heir-edit-nric');
                const emailInput = card.querySelector('.heir-edit-email');
                const phoneInput = card.querySelector('.heir-edit-phone');
                const percentageInput = card.querySelector('.heir-edit-percentage');
                let hasError = false;
                if (nameInput) {
                    const nameVal = nameInput.value.trim();
                    if (!nameVal) { ModernAlert.error(`Heir #${index + 1}: Full name is required.`, 'Required Field'); hasError = true; } 
                    else { updateHeirField(index, 'name', nameVal); }
                }
                if (nricInput) {
                    const nricVal = nricInput.value.trim();
                    if (!nricVal) { ModernAlert.error(`Heir #${index + 1}: NRIC/Passport is required.`, 'Required Field'); hasError = true; } 
                    else if (!validateNRIC(nricVal)) { ModernAlert.error(`Heir #${index + 1}: Invalid NRIC format. Use ${NRIC_FORMAT}.`, 'Invalid Format'); hasError = true; } 
                    else { updateHeirField(index, 'nric', nricVal); }
                }
                if (emailInput) {
                    const emailVal = emailInput.value.trim();
                    if (!emailVal) { ModernAlert.error(`Heir #${index + 1}: Email is required for notification.`, 'Required Field'); hasError = true; } 
                    else if (!validateEmail(emailVal)) { ModernAlert.error(`Heir #${index + 1}: Invalid email format.`, 'Invalid Email'); hasError = true; } 
                    else { updateHeirField(index, 'email', emailVal); }
                }
                if (phoneInput) {
                    const phoneVal = phoneInput.value.trim();
                    if (!phoneVal) { ModernAlert.error(`Heir #${index + 1}: Contact phone is required.`, 'Required Field'); hasError = true; } 
                    else if (!validatePhoneStrict(phoneVal)) { ModernAlert.error(`Heir #${index + 1}: Invalid phone format. Use ${PHONE_FORMAT_EXAMPLE}.`, 'Invalid Phone'); hasError = true; } 
                    else { updateHeirField(index, 'phone', phoneVal); }
                }
                if (percentageInput) {
                    const percentVal = parseFloat(percentageInput.value) || 0;
                    if (percentVal <= 0) { ModernAlert.error(`Heir #${index + 1}: Share percentage is required.`, 'Required Field'); hasError = true; } 
                    else { updateHeirPercentage(index, percentVal); }
                }
                if (!hasError) { ModernAlert.success(`Heir #${index + 1} updated successfully.`, 'Update Complete'); }
            });
        });
    }

    // ========== EXACT FARAID CALCULATOR (FRACTIONS AS SPECIFIED) ==========
    function addHeirToResult(heirsArr, name, relationship, num, den) {
        heirsArr.push({ name, relationship, fraction: { num: num, den: den } });
    }

    function calculateFaraid() {
        const spouse = document.getElementById('spouseType').value;
        let wivesCount = parseInt(document.getElementById('wivesCount').value) || 0;
        const sons = parseInt(document.getElementById('sonsCount').value) || 0;
        const daughters = parseInt(document.getElementById('daughtersCount').value) || 0;
        const father = document.getElementById('fatherAlive').value === 'yes';
        const mother = document.getElementById('motherAlive').value === 'yes';
        const fullBro = parseInt(document.getElementById('fullBrothers').value) || 0;
        const fullSis = parseInt(document.getElementById('fullSisters').value) || 0;
        const patBro = parseInt(document.getElementById('paternalHalfBrothers').value) || 0;
        const patSis = parseInt(document.getElementById('paternalHalfSisters').value) || 0;
        const matBro = parseInt(document.getElementById('maternalHalfBrothers').value) || 0;
        const matSis = parseInt(document.getElementById('maternalHalfSisters').value) || 0;

        let heirsArr = [];

        // ----- CATEGORY: SPOUSE ONLY -----
        if (spouse !== 'none' && sons === 0 && daughters === 0 && !father && !mother && (fullBro+fullSis+patBro+patSis+matBro+matSis) === 0) {
            if (spouse === 'husband') {
                addHeirToResult(heirsArr, 'Husband', 'husband', 1, 2);
                addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 2);
            } else { // wife
                for (let i = 1; i <= wivesCount; i++) {
                    addHeirToResult(heirsArr, `Wife ${wivesCount > 1 ? i : ''}`, 'wife', 1, 4);
                }
                addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 3, 4);
            }
        }
        // ----- CATEGORY: SPOUSE AND PARENTS (NO CHILDREN) -----
        else if (spouse !== 'none' && sons === 0 && daughters === 0 && (father || mother) && (fullBro+fullSis+patBro+patSis+matBro+matSis) === 0) {
            if (spouse === 'husband') {
                if (father && !mother) {
                    addHeirToResult(heirsArr, 'Husband', 'husband', 1, 2);
                    addHeirToResult(heirsArr, 'Father', 'father', 1, 2);
                } else if (mother && !father) {
                    addHeirToResult(heirsArr, 'Husband', 'husband', 3, 6);
                    addHeirToResult(heirsArr, 'Mother', 'mother', 2, 6);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 6);
                } else if (father && mother) {
                    addHeirToResult(heirsArr, 'Husband', 'husband', 3, 6);
                    addHeirToResult(heirsArr, 'Mother', 'mother', 1, 6);
                    addHeirToResult(heirsArr, 'Father', 'father', 2, 6);
                }
            } else { // wife
                if (father && !mother) {
                    addHeirToResult(heirsArr, 'Wife', 'wife', 1, 4);
                    addHeirToResult(heirsArr, 'Father', 'father', 3, 4);
                } else if (mother && !father) {
                    addHeirToResult(heirsArr, 'Wife', 'wife', 3, 12);
                    addHeirToResult(heirsArr, 'Mother', 'mother', 4, 12);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 5, 12);
                } else if (father && mother) {
                    addHeirToResult(heirsArr, 'Wife', 'wife', 1, 4);
                    addHeirToResult(heirsArr, 'Mother', 'mother', 1, 4);
                    addHeirToResult(heirsArr, 'Father', 'father', 2, 4);
                }
            }
        }
        // ----- CATEGORY: SPOUSE AND CHILDREN (with exact fractions) -----
        else if (spouse !== 'none' && (sons + daughters) > 0) {
            if (spouse === 'husband') {
                if (sons === 1 && daughters === 0) {
                    addHeirToResult(heirsArr, 'Husband', 'husband', 1, 4);
                    addHeirToResult(heirsArr, 'Son', 'son', 3, 4);
                } else if (sons === 0 && daughters === 1) {
                    addHeirToResult(heirsArr, 'Husband', 'husband', 1, 4);
                    addHeirToResult(heirsArr, 'Daughter', 'daughter', 2, 4);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 4);
                } else if (sons === 0 && daughters === 2) {
                    addHeirToResult(heirsArr, 'Husband', 'husband', 3, 12);
                    addHeirToResult(heirsArr, 'Daughter 1', 'daughter', 4, 12);
                    addHeirToResult(heirsArr, 'Daughter 2', 'daughter', 4, 12);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 12);
                } else if (sons === 1 && daughters === 1) {
                    addHeirToResult(heirsArr, 'Husband', 'husband', 1, 4);
                    addHeirToResult(heirsArr, 'Son', 'son', 2, 4);
                    addHeirToResult(heirsArr, 'Daughter', 'daughter', 1, 4);
                } else {
                    // general case: husband 1/4, children as asabah (son:2 parts, daughter:1 part)
                    const totalParts = sons*2 + daughters;
                    addHeirToResult(heirsArr, 'Husband', 'husband', 1, 4);
                    const remainingNum = 3, remainingDen = 4;
                    for (let i=1; i<=sons; i++) addHeirToResult(heirsArr, `Son ${i}`, 'son', remainingNum*2, remainingDen*totalParts);
                    for (let i=1; i<=daughters; i++) addHeirToResult(heirsArr, `Daughter ${i}`, 'daughter', remainingNum, remainingDen*totalParts);
                }
            } else { // wife
                if (sons === 1 && daughters === 0) {
                    addHeirToResult(heirsArr, 'Wife', 'wife', 1, 8);
                    addHeirToResult(heirsArr, 'Son', 'son', 7, 8);
                } else if (sons === 0 && daughters === 1) {
                    addHeirToResult(heirsArr, 'Wife', 'wife', 1, 8);
                    addHeirToResult(heirsArr, 'Daughter', 'daughter', 4, 8);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 3, 8);
                } else if (sons === 0 && daughters === 2) {
                    addHeirToResult(heirsArr, 'Wife', 'wife', 3, 24);
                    addHeirToResult(heirsArr, 'Daughter 1', 'daughter', 8, 24);
                    addHeirToResult(heirsArr, 'Daughter 2', 'daughter', 8, 24);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 5, 24);
                } else {
                    const totalParts = sons*2 + daughters;
                    addHeirToResult(heirsArr, 'Wife', 'wife', 1, 8);
                    const remainingNum = 7, remainingDen = 8;
                    for (let i=1; i<=sons; i++) addHeirToResult(heirsArr, `Son ${i}`, 'son', remainingNum*2, remainingDen*totalParts);
                    for (let i=1; i<=daughters; i++) addHeirToResult(heirsArr, `Daughter ${i}`, 'daughter', remainingNum, remainingDen*totalParts);
                }
            }
        }
        // ----- CATEGORY: CHILDREN ONLY (no spouse, no parents) -----
        else if (spouse === 'none' && (sons+daughters) > 0 && !father && !mother) {
            const totalParts = sons*2 + daughters;
            if (sons === 1 && daughters === 0) {
                addHeirToResult(heirsArr, 'Son', 'son', 1, 1);
            } else if (sons === 0 && daughters === 1) {
                addHeirToResult(heirsArr, 'Daughter', 'daughter', 1, 2);
                addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 2);
            } else if (sons === 0 && daughters === 2) {
                addHeirToResult(heirsArr, 'Daughter 1', 'daughter', 1, 3);
                addHeirToResult(heirsArr, 'Daughter 2', 'daughter', 1, 3);
                addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 3);
            } else if (sons === 2 && daughters === 2) {
                addHeirToResult(heirsArr, 'Son 1', 'son', 2, 6);
                addHeirToResult(heirsArr, 'Son 2', 'son', 2, 6);
                addHeirToResult(heirsArr, 'Daughter 1', 'daughter', 1, 6);
                addHeirToResult(heirsArr, 'Daughter 2', 'daughter', 1, 6);
            } else {
                for (let i=1; i<=sons; i++) addHeirToResult(heirsArr, `Son ${i}`, 'son', 2, totalParts);
                for (let i=1; i<=daughters; i++) addHeirToResult(heirsArr, `Daughter ${i}`, 'daughter', 1, totalParts);
            }
        }
        // ----- CATEGORY: PARENTS AND CHILDREN -----
        else if (spouse === 'none' && (sons+daughters) > 0 && (father || mother)) {
            if (father && !mother) {
                if (sons === 1 && daughters === 0) {
                    addHeirToResult(heirsArr, 'Father', 'father', 1, 6);
                    addHeirToResult(heirsArr, 'Son', 'son', 5, 6);
                } else if (sons === 0 && daughters === 1) {
                    addHeirToResult(heirsArr, 'Father', 'father', 1, 2);
                    addHeirToResult(heirsArr, 'Daughter', 'daughter', 1, 2);
                } else {
                    addHeirToResult(heirsArr, 'Father', 'father', 1, 6);
                    const totalParts = sons*2 + daughters;
                    const remainingNum = 5, remainingDen = 6;
                    for (let i=1; i<=sons; i++) addHeirToResult(heirsArr, `Son ${i}`, 'son', remainingNum*2, remainingDen*totalParts);
                    for (let i=1; i<=daughters; i++) addHeirToResult(heirsArr, `Daughter ${i}`, 'daughter', remainingNum, remainingDen*totalParts);
                }
            } else if (mother && !father) {
                if (sons === 1 && daughters === 0) {
                    addHeirToResult(heirsArr, 'Mother', 'mother', 1, 6);
                    addHeirToResult(heirsArr, 'Son', 'son', 5, 6);
                } else if (sons === 0 && daughters === 1) {
                    addHeirToResult(heirsArr, 'Mother', 'mother', 1, 6);
                    addHeirToResult(heirsArr, 'Daughter', 'daughter', 3, 6);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 2, 6);
                } else {
                    addHeirToResult(heirsArr, 'Mother', 'mother', 1, 6);
                    const totalParts = sons*2 + daughters;
                    const remainingNum = 5, remainingDen = 6;
                    for (let i=1; i<=sons; i++) addHeirToResult(heirsArr, `Son ${i}`, 'son', remainingNum*2, remainingDen*totalParts);
                    for (let i=1; i<=daughters; i++) addHeirToResult(heirsArr, `Daughter ${i}`, 'daughter', remainingNum, remainingDen*totalParts);
                }
            } else if (father && mother) {
                addHeirToResult(heirsArr, 'Mother', 'mother', 1, 6);
                addHeirToResult(heirsArr, 'Father', 'father', 1, 6);
                const totalParts = sons*2 + daughters;
                const remainingNum = 4, remainingDen = 6;
                for (let i=1; i<=sons; i++) addHeirToResult(heirsArr, `Son ${i}`, 'son', remainingNum*2, remainingDen*totalParts);
                for (let i=1; i<=daughters; i++) addHeirToResult(heirsArr, `Daughter ${i}`, 'daughter', remainingNum, remainingDen*totalParts);
            }
        }
        // ----- CATEGORY: PARENTS ONLY (no children) -----
        else if (spouse === 'none' && sons === 0 && daughters === 0 && (father || mother) && (fullBro+fullSis+patBro+patSis+matBro+matSis) === 0) {
            if (father && !mother) {
                addHeirToResult(heirsArr, 'Father', 'father', 1, 1);
            } else if (mother && !father) {
                addHeirToResult(heirsArr, 'Mother', 'mother', 1, 3);
                addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 2, 3);
            } else if (father && mother) {
                addHeirToResult(heirsArr, 'Mother', 'mother', 1, 3);
                addHeirToResult(heirsArr, 'Father', 'father', 2, 3);
            }
        }
        // ----- CATEGORY: SIBLINGS (no parents, no children) -----
        else if (spouse === 'none' && sons === 0 && daughters === 0 && !father && !mother && (fullBro+fullSis+patBro+patSis+matBro+matSis) > 0) {
            
            // ***** NEW SPECIAL CASE: 1 Paternal Half-Brother + 1 Maternal Half-Sister *****
            if (fullBro === 0 && fullSis === 0 && patBro === 1 && patSis === 0 && matBro === 0 && matSis === 1) {
                addHeirToResult(heirsArr, 'Paternal Half-Brother', 'half_brother_paternal', 5, 6);
                addHeirToResult(heirsArr, 'Maternal Half-Sister', 'half_sister_maternal', 1, 6);
            }
            // End of special case

            else if (fullBro === 1 && fullSis === 0 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
                addHeirToResult(heirsArr, 'Full Brother', 'half_brother_full', 1, 1);
            } else if (fullBro === 0 && fullSis === 1 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
                addHeirToResult(heirsArr, 'Full Sister', 'half_sister_full', 1, 2);
                addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 2);
            } else if (fullBro === 0 && fullSis === 2 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
                addHeirToResult(heirsArr, 'Full Sister 1', 'half_sister_full', 1, 3);
                addHeirToResult(heirsArr, 'Full Sister 2', 'half_sister_full', 1, 3);
                addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 3);
            } else if (fullBro === 1 && fullSis === 1 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
                addHeirToResult(heirsArr, 'Full Brother', 'half_brother_full', 2, 3);
                addHeirToResult(heirsArr, 'Full Sister', 'half_sister_full', 1, 3);
            } else if (fullBro > 0 || fullSis > 0) {
                const totalParts = fullBro*2 + fullSis;
                for (let i=1; i<=fullBro; i++) addHeirToResult(heirsArr, `Full Brother ${i}`, 'half_brother_full', 2, totalParts);
                for (let i=1; i<=fullSis; i++) addHeirToResult(heirsArr, `Full Sister ${i}`, 'half_sister_full', 1, totalParts);
            } else if (patBro > 0 || patSis > 0) {
                const totalParts = patBro*2 + patSis;
                for (let i=1; i<=patBro; i++) addHeirToResult(heirsArr, `Paternal Half-Brother ${i}`, 'half_brother_paternal', 2, totalParts);
                for (let i=1; i<=patSis; i++) addHeirToResult(heirsArr, `Paternal Half-Sister ${i}`, 'half_sister_paternal', 1, totalParts);
            } else if (matBro > 0 || matSis > 0) {
                if (matBro === 1 && matSis === 0 && fullBro===0 && fullSis===0 && patBro===0 && patSis===0) {
                    addHeirToResult(heirsArr, 'Maternal Half-Brother', 'half_brother_maternal', 1, 6);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 5, 6);
                } else if (matBro === 0 && matSis === 1 && fullBro===0 && fullSis===0 && patBro===0 && patSis===0) {
                    addHeirToResult(heirsArr, 'Maternal Half-Sister', 'half_sister_maternal', 1, 6);
                    addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 5, 6);
                } else {
                    const eachNum = 1, eachDen = 6;
                    for (let i=1; i<=matBro; i++) addHeirToResult(heirsArr, `Maternal Half-Brother ${i}`, 'half_brother_maternal', eachNum, eachDen);
                    for (let i=1; i<=matSis; i++) addHeirToResult(heirsArr, `Maternal Half-Sister ${i}`, 'half_sister_maternal', eachNum, eachDen);
                    const totalMat = matBro + matSis;
                    const remainingNum = 6 - totalMat;
                    if (remainingNum > 0) addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', remainingNum, 6);
                }
            }
        }
        // ----- AWL & SPECIAL CASES -----
        // 1 Wife + Mother + 2 Full Sisters
        if (spouse === 'wife' && wivesCount === 1 && mother && !father && sons===0 && daughters===0 && fullSis===2 && fullBro===0 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
            heirsArr = [];
            addHeirToResult(heirsArr, 'Wife', 'wife', 3, 13);
            addHeirToResult(heirsArr, 'Mother', 'mother', 2, 13);
            addHeirToResult(heirsArr, 'Full Sister 1', 'half_sister_full', 4, 13);
            addHeirToResult(heirsArr, 'Full Sister 2', 'half_sister_full', 4, 13);
        }
        // Husband + 2 Full Sisters
        if (spouse === 'husband' && !father && !mother && sons===0 && daughters===0 && fullSis===2 && fullBro===0 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
            heirsArr = [];
            addHeirToResult(heirsArr, 'Husband', 'husband', 3, 7);
            addHeirToResult(heirsArr, 'Full Sister 1', 'half_sister_full', 2, 7);
            addHeirToResult(heirsArr, 'Full Sister 2', 'half_sister_full', 2, 7);
        }
        // 3 Wives + No Children, No Parents, No Siblings
        if (spouse === 'wife' && wivesCount === 3 && sons===0 && daughters===0 && !father && !mother && fullBro===0 && fullSis===0 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
            heirsArr = [];
            for (let i=1; i<=3; i++) addHeirToResult(heirsArr, `Wife ${i}`, 'wife', 1, 12);
            addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 9, 12);
        }
        // 2 Wives + 2 Daughters
        if (spouse === 'wife' && wivesCount === 2 && sons===0 && daughters===2 && !father && !mother && fullBro===0 && fullSis===0 && patBro===0 && patSis===0 && matBro===0 && matSis===0) {
            heirsArr = [];
            addHeirToResult(heirsArr, 'Wife 1', 'wife', 3, 48);
            addHeirToResult(heirsArr, 'Wife 2', 'wife', 3, 48);
            addHeirToResult(heirsArr, 'Daughter 1', 'daughter', 16, 48);
            addHeirToResult(heirsArr, 'Daughter 2', 'daughter', 16, 48);
            addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 10, 48);
        }

        if (heirsArr.length === 0) {
            addHeirToResult(heirsArr, 'Baitulmal (Public Treasury)', 'baitulmal', 1, 1);
        }

        // Store and display
        localStorage.setItem('faraid_last_calculation', JSON.stringify(heirsArr.map(h => ({ name: h.name, relationship: h.relationship, fraction: h.fraction }))));
        displayCalculationResults(heirsArr);
    }

    function displayCalculationResults(heirsArr) {
        let resultHtml = '<div style="max-height:400px;overflow-y:auto;margin-bottom:1rem;">';
        resultHtml += '<table class="results-table"><thead><tr><th>Heir</th><th>Relationship</th><th>Fraction</th><th>Percentage (%)</th><th>Action</th></tr></thead><tbody>';
        for (const h of heirsArr) {
            const percent = (h.fraction.num * 100) / h.fraction.den;
            resultHtml += `
                <tr>
                    <td><strong>${escapeHtml(h.name)}</strong></td>
                    <td>${escapeHtml(h.relationship)}</td>
                    <td>${h.fraction.num}/${h.fraction.den}</td>
                    <td>${percent.toFixed(2)}%</span></td>
                    <td><button type="button" class="copy-heir-btn" data-name="${escapeHtml(h.name)}" data-rel="${h.relationship}" data-percent="${percent.toFixed(2)}" style="padding:0.25rem 0.5rem; background:#1a5fb4; color:white; border:none; border-radius:6px; cursor:pointer;">Copy to Form</button></td>
                </tr>
            `;
        }
        resultHtml += '</tbody></table></div>';
        resultHtml += '<div class="btn-group" style="margin-top:1rem;"><button type="button" class="btn btn-secondary" id="clearStoredCalculationBtn" style="background:#dc3545; color:white;">Clear Stored Result</button></div>';
        const resultArea = document.getElementById('faraidResultArea');
        if (resultArea) {
            resultArea.innerHTML = resultHtml;
            resultArea.style.display = 'block';
            document.querySelectorAll('.copy-heir-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('heirName').value = this.dataset.name;
                    document.getElementById('heirPercentage').value = this.dataset.percent;
                    const rel = this.dataset.rel;
                    const relSelect = document.getElementById('heirRelationship');
                    let found = false;
                    for (let i=0; i<relSelect.options.length; i++) {
                        if (relSelect.options[i].value === rel) { relSelect.selectedIndex = i; found = true; break; }
                    }
                    if (!found) {
                        const mapping = {
                            'husband':'husband','wife':'wife','father':'father','mother':'mother','son':'son','daughter':'daughter',
                            'half_brother_full':'half_brother_full','half_sister_full':'half_sister_full',
                            'half_brother_paternal':'half_brother_paternal','half_sister_paternal':'half_sister_paternal',
                            'half_brother_maternal':'half_brother_maternal','half_sister_maternal':'half_sister_maternal','baitulmal':'baitulmal'
                        };
                        if (mapping[rel]) {
                            for (let i=0; i<relSelect.options.length; i++) {
                                if (relSelect.options[i].value === mapping[rel]) { relSelect.selectedIndex = i; break; }
                            }
                        }
                    }
                    document.getElementById('addHeirForm').scrollIntoView({ behavior: 'smooth' });
                    ModernAlert.success('Form filled. Complete NRIC, email, phone and submit.', 'Ready');
                });
            });
            const clearBtn = document.getElementById('clearStoredCalculationBtn');
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    localStorage.removeItem('faraid_last_calculation');
                    resultArea.style.display = 'none';
                    ModernAlert.info('Stored calculation cleared.', 'Cleared');
                });
            }
        }
    }

    // ===== CREDENTIALS MANAGEMENT =====
    function addCredential() {
        let platform = document.getElementById('credentialPlatform')?.value;
        const platformCustom = document.getElementById('credentialPlatformCustom')?.value.trim();
        const username = document.getElementById('credentialUsername')?.value.trim();
        const password = document.getElementById('credentialPassword')?.value;
        const security = document.getElementById('credentialSecurity')?.value.trim();
        const notes = document.getElementById('credentialNotes')?.value.trim();
        if (platformCustom) platform = platformCustom;
        if (!platform) { ModernAlert.error('Please select or enter a platform name.', 'Required Field'); return; }
        if (!username) { ModernAlert.error('Username/ID is required.', 'Required Field'); return; }
        if (!password) { ModernAlert.error('Password/Access Key is required.', 'Required Field'); return; }
        credentials.push({ id: nextCredentialId++, platform, username, password: btoa(password), security, notes });
        renderCredentialsList();
        clearCredentialForm();
        updateTotals();
        ModernAlert.success(`Credential for "${platform}" added securely.`, 'Success');
    }

    function clearCredentialForm() {
        ['credentialPlatform', 'credentialPlatformCustom', 'credentialUsername', 'credentialPassword', 'credentialSecurity', 'credentialNotes']
            .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    }

    function removeCredential(index) {
        const platform = credentials[index]?.platform || 'this credential';
        showConfirmationModal({
            title: 'Remove Credential', message: 'Are you sure you want to remove this credential?',
            itemName: platform, iconType: 'danger',
            onConfirm: () => { credentials.splice(index, 1); renderCredentialsList(); updateTotals(); ModernAlert.info(`${platform} has been removed.`, 'Credential Removed'); }
        });
    }

    function clearAllCredentials() {
        if (credentials.length === 0) { ModernAlert.info('No credentials to clear.', 'Information'); return; }
        showConfirmationModal({
            title: 'Clear All Credentials', message: `Are you sure you want to remove all ${credentials.length} credential(s)?`, iconType: 'danger',
            onConfirm: () => { credentials = []; renderCredentialsList(); updateTotals(); ModernAlert.warning('All credentials have been cleared.', 'Credentials Cleared'); }
        });
    }

    function renderCredentialsList() {
        const container = document.getElementById('credentialsList');
        if (!container) return;
        if (credentials.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:1rem;color:var(--gray-500);border:1px dashed var(--gray-300);border-radius:12px;">No digital credentials added yet.</div>';
            document.getElementById('credentialsCountDisplay').textContent = '0';
            document.getElementById('credentialsCategoriesDisplay').textContent = 'None';
            return;
        }
        container.innerHTML = '';
        credentials.forEach((cred, index) => {
            const card = document.createElement('div');
            card.className = 'credential-card';
            card.innerHTML = `
                <div class="credential-card-header">
                    <div class="credential-card-title">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <strong>${escapeHtml(cred.platform)}</strong>
                    </div>
                    <button type="button" class="btn-remove" data-action="remove-credential" data-index="${index}">✕ Remove</button>
                </div>
                <div class="credential-card-body">
                    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                        <div><strong>Username:</strong> ${escapeHtml(cred.username)}</div>
                        <div><strong>Password:</strong> ••••••••</div>
                        ${cred.security ? `<div><strong>Security:</strong> ${escapeHtml(cred.security.substring(0, 50))}${cred.security.length > 50 ? '...' : ''}</div>` : ''}
                        ${cred.notes ? `<div><strong>Notes:</strong> ${escapeHtml(cred.notes)}</div>` : ''}
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
        container.querySelectorAll('[data-action="remove-credential"]').forEach(btn => {
            btn.addEventListener('click', function() { removeCredential(parseInt(this.dataset.index)); });
        });
        const categories = [...new Set(credentials.map(c => {
            if (c.platform.includes('Bank') || c.platform.includes('Maybank') || c.platform.includes('CIMB')) return 'Banking';
            if (c.platform.includes('Facebook') || c.platform.includes('Instagram')) return 'Social Media';
            if (c.platform.includes('Gmail') || c.platform.includes('Outlook')) return 'Email';
            return 'Other';
        }))];
        document.getElementById('credentialsCategoriesDisplay').textContent = categories.join(', ') || 'None';
        document.getElementById('credentialsCountDisplay').textContent = credentials.length;
    }

    // ===== UPDATE ALL TOTALS & REVIEW =====
    function updateTotals() {
        const totalAssetsShare = getTotalAssetsShare();
        const totalDebts = getTotalDebts();
        const netEstate = Math.max(0, totalAssetsShare - totalDebts);
        const totalWasiyyahPercent = wasiyyah.reduce((sum, w) => sum + w.percentage, 0);
        const totalWasiyyahAmount = (netEstate * totalWasiyyahPercent) / 100;
        const remainingForHeirs = netEstate - totalWasiyyahAmount;
        const totalHeirPercentage = heirs.reduce((sum, h) => sum + (h.percentage || 0), 0);

        document.getElementById('totalAssetsGrossDisplay').textContent = `RM ${assets.reduce((s,a)=>s+a.value,0).toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('totalAssetsDisplay').textContent = `RM ${totalAssetsShare.toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('totalAssetsDisplay2').textContent = `RM ${totalAssetsShare.toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('totalDebtsDisplay').textContent = `RM ${totalDebts.toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('netEstateDisplay').textContent = `RM ${netEstate.toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('reviewNetEstate').textContent = `RM ${netEstate.toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('activationNetEstate').textContent = `RM ${netEstate.toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('maxWasiyyahAmount').textContent = ((netEstate * 33.33) / 100).toLocaleString('en-US',{minimumFractionDigits:2});
        document.getElementById('currentWasiyyahPercent').textContent = totalWasiyyahPercent.toFixed(2);
        document.getElementById('currentWasiyyahAmount').textContent = totalWasiyyahAmount.toLocaleString('en-US',{minimumFractionDigits:2});
        document.getElementById('totalAssetsBadge').textContent = assets.length + (assets.length === 1 ? ' Asset' : ' Assets');
        document.getElementById('totalDebtsBadge').textContent = debts.length + (debts.length === 1 ? ' Debt' : ' Debts');
        document.getElementById('assetsCountDisplay').textContent = assets.length;
        document.getElementById('activationWasiyyahPercent').textContent = totalWasiyyahPercent.toFixed(2) + '%';
        document.getElementById('activationFaraidPercent').textContent = totalHeirPercentage.toFixed(2) + '%';
        document.getElementById('reviewHeirsCount').textContent = heirs.length;
        document.getElementById('activationHeirsCount').textContent = heirs.length;
        document.getElementById('activationRemainingForHeirs').textContent = `RM ${remainingForHeirs.toLocaleString('en-US',{minimumFractionDigits:2})}`;
        document.getElementById('reviewTrusteeName').textContent = document.getElementById('trustee_name')?.value.trim() || 'Not appointed';
        document.getElementById('reviewName').textContent = document.getElementById('deceased_name')?.value.trim() || '-';

        let videoStatusText = 'Not provided';
        if (currentVideoOption === 'upload' && videoFile) videoStatusText = `Video uploaded (${(videoFile.size/(1024*1024)).toFixed(1)}MB)`;
        else if (currentVideoOption === 'youtube' && youtubeUrl) videoStatusText = 'YouTube link provided';
        document.getElementById('reviewVideoStatus').textContent = videoStatusText;

        // Update heirs review table
        const tbodyHeirs = document.getElementById('reviewHeirsTable');
        if (tbodyHeirs) {
            if (heirs.length === 0) tbodyHeirs.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:2rem;">No heirs designated yet</span></tr>';
            else {
                tbodyHeirs.innerHTML = '';
                heirs.forEach(heir => {
                    const amount = (remainingForHeirs * (heir.percentage || 0) / 100);
                    tbodyHeirs.innerHTML += `<tr><td style="padding:0.75rem;">${escapeHtml(heir.name)}</span><td style="padding:0.75rem;">${escapeHtml(heir.email||'-')}</span><td style="padding:0.75rem;">${escapeHtml(heir.relationshipDisplay)}</span><td style="padding:0.75rem;"><strong>${(heir.percentage||0).toFixed(2)}%</strong></span><td style="padding:0.75rem;">RM ${amount.toLocaleString('en-US',{minimumFractionDigits:2})}</span></tr>`;
                });
            }
        }

        // Update wasiyyah review table
        const tbodyWasi = document.getElementById('reviewWasiyyahTable');
        if (tbodyWasi) {
            if (wasiyyah.length === 0) tbodyWasi.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:1rem;">No wasiyyah beneficiaries</span></tr>';
            else {
                tbodyWasi.innerHTML = '';
                wasiyyah.forEach(w => {
                    const amount = (netEstate * w.percentage) / 100;
                    tbodyWasi.innerHTML += `<tr><td style="padding:0.75rem;">${escapeHtml(w.name)}</span><td style="padding:0.75rem;">${escapeHtml(w.email)}</span><td style="padding:0.75rem;">${escapeHtml(w.relationship)}</span><td style="padding:0.75rem;"><strong>${w.percentage.toFixed(2)}%</strong></span><td style="padding:0.75rem;">RM ${amount.toLocaleString('en-US',{minimumFractionDigits:2})}</span></tr>`;
                });
            }
        }

        // Smart validation banner
        const isReady = validateProfile(false) && assets.length > 0 && heirs.length > 0 && Math.abs(totalHeirPercentage-100)<=0.01 && totalWasiyyahPercent<=33.33 && validateWasiyyah(false);
        const banner = document.getElementById('smartValidationBanner');
        if (isReady) {
            banner.className = 'smart-validation-banner banner-success';
            document.getElementById('validationIcon').innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
            document.getElementById('validationMessage').textContent = 'System ready for activation';
            document.getElementById('validationDetail').textContent = 'All sections completed. Your estate plan is ready to activate.';
            document.getElementById('validationBadge').style.background = 'var(--success-color)';
            document.getElementById('validationBadge').style.color = 'white';
            document.getElementById('validationBadge').textContent = 'Ready';
        } else {
            banner.className = 'smart-validation-banner banner-warning';
            document.getElementById('validationIcon').innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`;
            document.getElementById('validationMessage').textContent = 'Estate planning in progress';
            let missing = [];
            if (!validateProfile(false)) missing.push('Complete Profile');
            if (assets.length === 0) missing.push('Assets');
            if (heirs.length === 0) missing.push('Heirs');
            if (!validateWasiyyah(false)) missing.push('Trustee Info');
            if (netEstate <= 0) missing.push('Positive Net Estate');
            document.getElementById('validationDetail').textContent = `Missing: ${missing.join(', ')}. Complete all sections to activate.`;
            document.getElementById('validationBadge').style.background = 'var(--warning-color)';
            document.getElementById('validationBadge').style.color = '#856404';
            document.getElementById('validationBadge').textContent = `${missing.length} Remaining`;
        }

        // Enable/disable activate button
        const activateBtn = document.getElementById('activatePlanBtn');
        const confirmBox = document.getElementById('confirmationCheckbox');
        if (activateBtn && confirmBox) activateBtn.disabled = !(isReady && confirmBox.checked);
    }

    function validateProfile(showAlert = true) {
        const name = document.getElementById('deceased_name')?.value.trim();
        const nric = document.getElementById('deceased_nric')?.value.trim();
        const phone = document.getElementById('contact_phone')?.value.trim();
        const email = document.getElementById('contact_email')?.value.trim();
        const address = document.getElementById('address')?.value.trim();
        let valid = !!(name && validateNRIC(nric) && validatePhoneStrict(phone) && validateEmail(email) && address);
        if (!valid && showAlert) ModernAlert.error('Please complete all profile fields correctly.', 'Profile Incomplete');
        return valid;
    }

    function validateWasiyyah(showAlert = true) {
        const trusteeName = document.getElementById('trustee_name')?.value.trim();
        const trusteeNric = document.getElementById('trustee_nric')?.value.trim();
        const trusteePhone = document.getElementById('trustee_phone')?.value.trim();
        const trusteeEmail = document.getElementById('trustee_email')?.value.trim();
        if (!trusteeName || !validateNRIC(trusteeNric) || !validatePhoneStrict(trusteePhone) || !validateEmail(trusteeEmail)) {
            if (showAlert) ModernAlert.error('Please complete all trustee fields correctly.', 'Trustee Incomplete');
            return false;
        }
        return true;
    }

    // ===== VIDEO HANDLERS =====
    const videoUploadArea = document.getElementById('videoUploadArea');
    const videoInput = document.getElementById('will_video');
    const videoPreviewDiv = document.getElementById('videoPreview');
    const videoPlayer = document.getElementById('videoPlayer');
    const removeVideoBtn = document.getElementById('removeVideoBtn');
    const youtubeUrlInput = document.getElementById('youtube_url');
    const youtubePreview = document.getElementById('youtubePreview');
    const youtubeIframe = document.getElementById('youtubeIframe');
    const removeYoutubeBtn = document.getElementById('removeYoutubeBtn');
    const videoOptionTabs = document.querySelectorAll('.video-option-tab');

    function setVideoOption(option) {
        currentVideoOption = option;
        document.getElementById('video_option').value = option;
        const uploadOption = document.getElementById('uploadVideoOption');
        const youtubeOption = document.getElementById('youtubeVideoOption');
        const uploadTab = document.querySelector('[data-video-option="upload"]');
        const youtubeTab = document.querySelector('[data-video-option="youtube"]');
        if (option === 'upload') {
            uploadOption.classList.add('active');
            youtubeOption.classList.remove('active');
            uploadTab.classList.add('active');
            youtubeTab.classList.remove('active');
        } else if (option === 'youtube') {
            uploadOption.classList.remove('active');
            youtubeOption.classList.add('active');
            uploadTab.classList.remove('active');
            youtubeTab.classList.add('active');
        } else {
            uploadOption.classList.remove('active');
            youtubeOption.classList.remove('active');
        }
    }

    function extractYouTubeId(url) {
        if (!url) return null;
        const patterns = [/(?:youtube\.com\/watch\?v=)([^&]+)/, /(?:youtu\.be\/)([^?]+)/, /(?:youtube\.com\/embed\/)([^?]+)/];
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) return match[1];
        }
        return null;
    }

    function getYouTubeEmbedUrl(url) {
        const id = extractYouTubeId(url);
        return id ? `https://www.youtube.com/embed/${id}` : null;
    }

    function isValidYouTubeUrl(url) { return extractYouTubeId(url) !== null; }

    function handleYoutubeUrl(url) {
        if (!url.trim()) { ModernAlert.warning('Please enter a YouTube URL.', 'Missing URL'); return false; }
        if (!isValidYouTubeUrl(url)) { ModernAlert.error('Invalid YouTube URL.', 'Invalid URL'); return false; }
        youtubeUrl = url.trim();
        videoFile = null;
        const embedUrl = getYouTubeEmbedUrl(youtubeUrl);
        if (embedUrl) {
            youtubeIframe.src = embedUrl;
            youtubePreview.style.display = 'block';
            removeYoutubeBtn.style.display = 'inline-flex';
            setVideoOption('youtube');
            ModernAlert.success('YouTube link added successfully.', 'Success');
            updateTotals();
            return true;
        }
        return false;
    }

    function handleVideoFile(file) {
        if (!file) return false;
        if (file.size > MAX_VIDEO_SIZE_BYTES) {
            ModernAlert.error(`Video exceeds ${MAX_VIDEO_SIZE_MB}MB limit. Please use a smaller video or YouTube link.`, 'File Too Large');
            return false;
        }
        videoFile = file;
        youtubeUrl = '';
        videoPlayer.src = URL.createObjectURL(file);
        videoPreviewDiv.style.display = 'block';
        videoUploadArea.style.display = 'none';
        setVideoOption('upload');
        ModernAlert.success(`Video uploaded (${(file.size/(1024*1024)).toFixed(1)}MB).`, 'Success');
        updateTotals();
        return true;
    }

    if (videoUploadArea) {
        videoUploadArea.addEventListener('click', () => videoInput?.click());
        videoUploadArea.addEventListener('dragover', (e) => { e.preventDefault(); videoUploadArea.style.borderColor = 'var(--primary-color)'; });
        videoUploadArea.addEventListener('dragleave', () => { videoUploadArea.style.borderColor = 'var(--gray-300)'; });
        videoUploadArea.addEventListener('drop', (e) => { e.preventDefault(); videoUploadArea.style.borderColor = 'var(--gray-300)'; const file = e.dataTransfer.files[0]; if (file && file.type.startsWith('video/')) handleVideoFile(file); else ModernAlert.warning('Please drop a valid video file.'); });
    }
    if (videoInput) videoInput.addEventListener('change', (e) => { if (e.target.files.length) handleVideoFile(e.target.files[0]); });
    if (removeVideoBtn) removeVideoBtn.addEventListener('click', () => {
        showConfirmationModal({
            title: 'Remove Video', message: 'Remove the uploaded video message?', iconType: 'warning',
            onConfirm: () => { videoFile = null; videoPlayer.pause(); videoPlayer.src = ''; videoPreviewDiv.style.display = 'none'; videoUploadArea.style.display = 'block'; videoInput.value = ''; setVideoOption('upload'); ModernAlert.info('Video removed.'); updateTotals(); }
        });
    });
    if (youtubeUrlInput) {
        youtubeUrlInput.addEventListener('blur', function() { if (this.value.trim()) handleYoutubeUrl(this.value); });
        youtubeUrlInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') { e.preventDefault(); if (this.value.trim()) handleYoutubeUrl(this.value); } });
    }
    if (removeYoutubeBtn) removeYoutubeBtn.addEventListener('click', () => {
        showConfirmationModal({
            title: 'Remove YouTube Link', message: 'Remove the YouTube video link?', iconType: 'warning',
            onConfirm: () => { youtubeUrl = ''; if (youtubeUrlInput) youtubeUrlInput.value = ''; youtubeIframe.src = ''; youtubePreview.style.display = 'none'; removeYoutubeBtn.style.display = 'none'; setVideoOption('upload'); ModernAlert.info('YouTube link removed.'); updateTotals(); }
        });
    });
    videoOptionTabs.forEach(tab => {
        tab.addEventListener('click', function() { setVideoOption(this.dataset.videoOption); updateTotals(); });
    });

    // ===== POPULATE HIDDEN FIELDS =====
    function populateHiddenFields() {
        document.getElementById('heirsData').value = JSON.stringify(heirs.map(h => ({ name:h.name, nric:h.nric, email:h.email, relationship:h.relationship, relationship_display:h.relationshipDisplay, percentage:h.percentage, phone:h.phone })));
        document.getElementById('assetsData').value = JSON.stringify(assets.map(a => ({ name:a.name, value:a.value, ownership:a.ownership, description:a.description, category:a.category, label:a.label })));
        document.getElementById('debtsData').value = JSON.stringify(debts.map(d => ({ name:d.name, amount:d.amount, creditor:d.creditor, description:d.description, type:d.type, label:d.label })));
        document.getElementById('wasiyyahData').value = JSON.stringify(wasiyyah.map(w => ({ name:w.name, nric:w.nric, email:w.email, relationship:w.relationship, percentage:w.percentage, phone:w.phone })));
        if (currentVideoOption === 'upload' && videoFile) { document.getElementById('video_option').value = 'upload'; document.getElementById('will_video_url').value = ''; }
        else if (currentVideoOption === 'youtube' && youtubeUrl) { document.getElementById('video_option').value = 'youtube'; document.getElementById('will_video_url').value = youtubeUrl; }
        else { document.getElementById('video_option').value = 'none'; document.getElementById('will_video_url').value = ''; }
    }

    // ===== FORM SUBMISSION =====
    const form = document.getElementById('estateForm');
    const activateBtn = document.getElementById('activatePlanBtn');
    const confirmationCheckbox = document.getElementById('confirmationCheckbox');
    if (confirmationCheckbox) confirmationCheckbox.addEventListener('change', () => updateTotals());
    if (activateBtn) {
        activateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!validateProfile(true)) { document.querySelector('[data-section="profile"]')?.click(); return; }
            if (assets.length === 0) { ModernAlert.error('You must add at least one asset.', 'Assets Required'); document.querySelector('[data-section="assets"]')?.click(); return; }
            if (!validateWasiyyah(true)) { document.querySelector('[data-section="wasiyyah"]')?.click(); return; }
            const totalHeirPct = heirs.reduce((s,h)=>s+(h.percentage||0),0);
            if (Math.abs(totalHeirPct-100)>0.01) { ModernAlert.error(`Heirs total distribution must be 100%. Current total: ${totalHeirPct.toFixed(2)}%`, 'Distribution Error'); document.querySelector('[data-section="heirs"]')?.click(); return; }
            const totalWasiPct = wasiyyah.reduce((s,w)=>s+w.percentage,0);
            if (totalWasiPct > 33.33) { ModernAlert.error(`Wasiyyah total (${totalWasiPct.toFixed(2)}%) exceeds 33.33% limit.`, 'Limit Exceeded'); document.querySelector('[data-section="wasiyyah"]')?.click(); return; }
            if (!confirmationCheckbox?.checked) { ModernAlert.error('Please confirm that all information is true and accurate.', 'Action Blocked'); document.querySelector('[data-section="review"]')?.click(); return; }

            populateHiddenFields();
            showLoading('Activating your estate plan...', 'Please wait while we process your data');

            const formData = new FormData(form);
            formData.append('activate_after_save', '1');
            if (currentVideoOption === 'upload' && videoFile) { if (videoFile.size > MAX_VIDEO_SIZE_BYTES) { hideLoading(); ModernAlert.error(`Video exceeds ${MAX_VIDEO_SIZE_MB}MB limit.`, 'Video Too Large'); return; } formData.append('will_video', videoFile); }
            else if (currentVideoOption === 'youtube' && youtubeUrl) formData.append('youtube_url', youtubeUrl);
            formData.append('credentials_data', JSON.stringify(credentials.map(c => ({ platform:c.platform, username:c.username, password:c.password, security:c.security||'', notes:c.notes||'' }))));

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    ModernAlert.success(data.message || 'Estate plan activated successfully!', 'Success');
                    setTimeout(() => window.location.href = data.redirect || '{{ route("estate-setup.index") }}', 1500);
                } else {
                    ModernAlert.error(data.message || 'Failed to activate estate plan. Please try again.', 'Activation Failed');
                }
            })
            .catch(error => { hideLoading(); console.error(error); ModernAlert.error('Network error. Please check your connection and try again.', 'Connection Error'); });
        });
    }

    function showLoading(text, subtext) {
        const overlay = document.getElementById('loadingOverlay');
        document.getElementById('loadingText').textContent = text;
        document.getElementById('loadingSubtext').textContent = subtext;
        if (overlay) overlay.classList.add('active');
    }
    function hideLoading() { const overlay = document.getElementById('loadingOverlay'); if (overlay) overlay.classList.remove('active'); }

    // ===== NAVIGATION =====
    const stepOrder = ['profile', 'assets', 'debts', 'heirs', 'wasiyyah', 'review'];
    let unlockedSectionIndex = 0;
    function isProfileComplete() { return validateProfile(false); }
    function isAssetsComplete() { return assets.length > 0; }
    function isDebtsComplete() { return true; }
    function isHeirsComplete() { return heirs.length > 0 && Math.abs(heirs.reduce((s,h)=>s+(h.percentage||0),0)-100)<=0.01; }
    function isWasiyyahComplete() { return validateWasiyyah(false); }
    function isReviewReady() { return isProfileComplete() && isAssetsComplete() && isHeirsComplete() && isWasiyyahComplete() && confirmationCheckbox?.checked === true; }
    function getMaxCompletedIndex() {
        let idx = 0;
        if (isProfileComplete()) idx=0;
        if (isAssetsComplete()) idx=1;
        if (isAssetsComplete()) idx=2; // debts optional
        if (isHeirsComplete()) idx=3;
        if (isWasiyyahComplete()) idx=4;
        if (isReviewReady()) idx=5;
        return idx;
    }
    function updateUnlockedSections() {
        const maxComp = getMaxCompletedIndex();
        unlockedSectionIndex = Math.min(maxComp+1, 5);
        document.querySelectorAll('.modern-tab').forEach((tab, idx) => {
            if (idx <= unlockedSectionIndex) tab.classList.remove('disabled');
            else tab.classList.add('disabled');
        });
    }
    function switchToSection(sectionId, sectionIndex) {
        if (sectionIndex > unlockedSectionIndex) { ModernAlert.error(`Please complete the previous section first.`, 'Section Locked'); return false; }
        document.querySelectorAll('.modern-tab').forEach(t=>t.classList.remove('active'));
        document.querySelector(`[data-section="${sectionId}"]`).classList.add('active');
        document.querySelectorAll('.glass-card').forEach(s=>s.classList.remove('active-section'));
        document.getElementById(sectionId+'Section').classList.add('active-section');
        updateProgress(sectionId+'Section');
        if (sectionId === 'review') updateTotals();
        return true;
    }
    function updateProgress(sectionId) {
        let step = stepOrder.findIndex(s => s+'Section' === sectionId);
        if (step === -1) step = 0;
        document.getElementById('progressFill').style.width = ((step+1)/stepOrder.length)*100 + '%';
        document.querySelectorAll('.progress-step').forEach((p,i) => { p.classList.remove('active','completed'); if(i===step) p.classList.add('active'); else if(i<step) p.classList.add('completed'); });
    }
    document.querySelectorAll('.next-section').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentId = this.closest('.glass-card')?.id.replace('Section','');
            let idx = stepOrder.indexOf(currentId);
            if (idx === -1) idx = 0;
            let valid = true;
            if (idx === 0) valid = validateProfile(true);
            else if (idx === 1) valid = assets.length > 0 ? true : (ModernAlert.error('Please add at least one asset.', 'Assets Required'), false);
            else if (idx === 3) valid = heirs.length > 0 && Math.abs(heirs.reduce((s,h)=>s+(h.percentage||0),0)-100)<=0.01 ? true : (ModernAlert.error('Heirs total must be 100%.', 'Distribution Error'), false);
            else if (idx === 4) valid = validateWasiyyah(true);
            if (valid && idx < 5) { unlockedSectionIndex = Math.max(unlockedSectionIndex, idx+1); updateUnlockedSections(); switchToSection(stepOrder[idx+1], idx+1); }
        });
    });
    document.querySelectorAll('.prev-section').forEach(btn => {
        btn.addEventListener('click', function() {
            const prev = this.dataset.prev;
            if (prev) switchToSection(prev, stepOrder.indexOf(prev));
        });
    });
    document.querySelectorAll('.modern-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const sec = this.dataset.section;
            const idx = parseInt(this.dataset.sectionIndex);
            switchToSection(sec, idx);
        });
    });

    // ===== INIT EVENT LISTENERS =====
    document.getElementById('addCredentialBtn')?.addEventListener('click', addCredential);
    document.getElementById('clearCredentialsBtn')?.addEventListener('click', clearAllCredentials);
    document.getElementById('addAssetBtn')?.addEventListener('click', addAsset);
    document.getElementById('clearAssetsBtn')?.addEventListener('click', clearAllAssets);
    document.getElementById('addDebtBtn')?.addEventListener('click', addDebt);
    document.getElementById('clearDebtsBtn')?.addEventListener('click', clearAllDebts);
    document.getElementById('addHeirBtn')?.addEventListener('click', addHeir);
    document.getElementById('clearHeirsBtn')?.addEventListener('click', clearAllHeirs);
    document.getElementById('addWasiyyahBtn')?.addEventListener('click', addWasiyyah);
    document.getElementById('clearWasiyyahBtn')?.addEventListener('click', clearAllWasiyyah);
    document.getElementById('calculateFaraidBtn')?.addEventListener('click', calculateFaraid);
    document.getElementById('resetHeirsBtn')?.addEventListener('click', function() {
        document.getElementById('spouseType').value = 'none';
        document.getElementById('wivesCount').value = '1';
        document.getElementById('sonsCount').value = '0';
        document.getElementById('daughtersCount').value = '0';
        document.getElementById('fatherAlive').value = 'no';
        document.getElementById('motherAlive').value = 'no';
        document.getElementById('fullBrothers').value = '0';
        document.getElementById('fullSisters').value = '0';
        document.getElementById('paternalHalfBrothers').value = '0';
        document.getElementById('paternalHalfSisters').value = '0';
        document.getElementById('maternalHalfBrothers').value = '0';
        document.getElementById('maternalHalfSisters').value = '0';
        document.getElementById('faraidResultArea').style.display = 'none';
        localStorage.removeItem('faraid_last_calculation');
        ModernAlert.info('Faraid calculator reset.', 'Reset Complete');
    });
    document.getElementById('spouseType')?.addEventListener('change', function() {
        document.getElementById('wivesCountGroup').style.display = this.value === 'wife' ? 'block' : 'none';
    });
    document.getElementById('assetName')?.addEventListener('change', function() {
        const cls = assetClassification[this.value];
        const preview = document.getElementById('assetCategoryPreview');
        if (preview && cls) { preview.className = `badge ${cls.badge}`; preview.textContent = cls.label; }
    });
    document.getElementById('debtName')?.addEventListener('change', function() {
        const cls = debtClassification[this.value];
        const preview = document.getElementById('debtClassificationPreview');
        if (preview && cls) { preview.className = `badge ${cls.badge}`; preview.textContent = cls.label; }
    });
    document.getElementById('credentialPlatform')?.addEventListener('change', function() {
        if (this.value && !this.value.startsWith('Other')) document.getElementById('credentialPlatformCustom').value = this.value;
    });
    ['deceased_nric', 'trustee_nric', 'heirNric', 'wasiyyahNric'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', function(e) { e.target.value = formatNRIC(e.target.value); });
    });
    const dobInput = document.getElementById('date_of_birth');
    if (dobInput) dobInput.max = new Date().toISOString().split('T')[0];
    window.togglePassword = function(id) { const f = document.getElementById(id); if (f) f.type = f.type === 'password' ? 'text' : 'password'; };
    window.toggleCredentials = function() {
        const c = document.getElementById('credentialsContent');
        const ic = document.getElementById('credentialsToggleIcon');
        if (c.classList.contains('show')) { c.classList.remove('show'); ic.textContent = '▼'; }
        else { c.classList.add('show'); ic.textContent = '▲'; }
    };
    window.formatPhone = autoFormatPhone;
    window.formatNric = formatNRIC;

    // Load stored faraid calculation
    const stored = localStorage.getItem('faraid_last_calculation');
    if (stored && stored !== 'undefined') {
        try {
            const heirsArr = JSON.parse(stored);
            if (heirsArr && heirsArr.length) displayCalculationResults(heirsArr);
        } catch(e) {}
    }

    renderCredentialsList();
    renderAssetsList();
    renderDebtsList();
    renderHeirsList();
    renderWasiyyahList();
    setVideoOption('upload');
    updateTotals();
    updateUnlockedSections();

})();
</script>
@endsection