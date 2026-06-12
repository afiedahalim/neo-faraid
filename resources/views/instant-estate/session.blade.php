@extends('layouts.app')

@section('title', 'Instant Estate - Session Details')

@section('content')
<!-- Add Poppins font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* ===== CSS VARIABLES & RESET ===== */
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
    
    /* ===== SESSION HEADER ===== */
    .session-header {
        min-height: 25vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 2.5rem 2rem;
    }

    .session-header .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .session-header .animated-bg .bg-circle {
        position: absolute;
        border-radius: 50%;
    }

    .session-header .animated-bg .bg-circle-1 {
        top: 10%;
        right: 5%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }

    .session-header .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .session-header .animated-bg .bg-circle-3 {
        bottom: 20%;
        right: 15%;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .session-header .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }

    .session-header .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .session-header .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    .session-header .shape-1 { width: 35px; height: 35px; top: 20%; left: 10%; animation-name: float-1; }
    .session-header .shape-2 { width: 25px; height: 25px; top: 60%; left: 85%; animation-name: float-2; animation-delay: 1s; }
    .session-header .shape-3 { width: 30px; height: 30px; top: 75%; left: 15%; animation-name: float-3; animation-delay: 0.5s; }
    .session-header .shape-4 { width: 20px; height: 20px; top: 30%; left: 70%; animation-name: float-4; animation-delay: 1.5s; }

    .session-header .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 1.5rem;
    }

    .session-header .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .session-header .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .session-header .back-btn {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255,255,255,0.2);
        color: var(--white);
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
    }

    .session-header .back-btn:hover {
        background: rgba(255,255,255,0.2);
        transform: translateX(-3px);
    }

    .session-header .hero-title {
        font-size: 2rem;
        line-height: 1.2;
        margin-bottom: 0.25rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 700;
    }

    .session-header .hero-subtitle {
        font-size: 0.9rem;
        opacity: 0.85;
        font-weight: 400;
    }

    .session-header .status-badge-large {
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius-xl);
        font-weight: 600;
        font-size: 0.875rem;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* ===== MAIN CONTAINER ===== */
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
    }
    
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl), 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    .card-header-custom {
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
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    .card-body-custom {
        padding: 2rem;
    }
    
    /* ===== INFO GRID ===== */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .info-section {
        background: var(--white);
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        border: 1px solid var(--gray-200);
        transition: var(--transition);
    }
    
    .info-section:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }
    
    .info-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary-light);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-section-title .section-icon {
        width: 20px;
        height: 20px;
        color: var(--primary-color);
    }
    
    .info-table {
        width: 100%;
    }
    
    .info-table tr {
        border-bottom: 1px solid var(--gray-100);
        transition: var(--transition);
    }
    
    .info-table tr:last-child {
        border-bottom: none;
    }
    
    .info-table tr:hover {
        background: var(--gray-50);
    }
    
    .info-table th {
        padding: 0.75rem 1rem 0.75rem 0;
        text-align: left;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.875rem;
        white-space: nowrap;
        width: 40%;
        vertical-align: top;
    }
    
    .info-table td {
        padding: 0.75rem 0;
        color: var(--gray-600);
        font-size: 0.875rem;
        word-break: break-word;
    }
    
    .info-table code {
        background: var(--gray-100);
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        color: var(--primary-color);
        word-break: break-all;
    }
    
    /* ===== EXTRACTED DATA TABLE ===== */
    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    
    .data-card {
        background: var(--gray-50);
        border-radius: var(--border-radius-sm);
        padding: 1rem;
        border: 1px solid var(--gray-200);
        transition: var(--transition);
    }
    
    .data-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }
    
    .data-card-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-500);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .data-card-value {
        font-size: 0.9rem;
        color: var(--gray-800);
        font-weight: 500;
        word-break: break-word;
    }
    
    .data-card-value.empty {
        color: var(--gray-400);
        font-style: italic;
        font-weight: 400;
    }
    
    .data-card.missing {
        border-color: var(--warning-light);
        background: var(--warning-light);
    }
    
    .data-card.missing .data-card-label {
        color: #b76e00;
    }
    
    /* ===== CONFIDENCE BAR ===== */
    .confidence-bar-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .confidence-bar {
        flex: 1;
        max-width: 150px;
        height: 10px;
        background: var(--gray-200);
        border-radius: 5px;
        overflow: hidden;
        position: relative;
    }
    
    .confidence-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 5px;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .confidence-value {
        font-weight: 700;
        font-size: 1rem;
        min-width: 45px;
        text-align: right;
    }
    
    .confidence-high { color: var(--success-color); }
    .confidence-medium { color: var(--warning-dark); }
    .confidence-low { color: var(--danger-color); }
    
    /* ===== EDITABLE FORM SECTION ===== */
    .editable-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-200);
    }
    
    .field-missing {
        border-color: var(--danger-color) !important;
        background: var(--danger-light) !important;
    }
    
    .missing-warning {
        background: var(--warning-light);
        border-left: 4px solid var(--warning-color);
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius-md);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }
    
    /* ===== ACTIONS SECTION ===== */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
        box-shadow: var(--shadow-primary);
    }
    
    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg), var(--shadow-primary);
    }
    
    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: var(--white);
    }
    
    .btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .btn-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
    }
    
    .btn-outline-primary:hover:not(:disabled) {
        background: var(--primary-color);
        color: var(--white);
        transform: translateY(-2px);
    }
    
    .btn-outline-danger {
        background: transparent;
        border: 2px solid var(--danger-color);
        color: var(--danger-color);
    }
    
    .btn-outline-danger:hover:not(:disabled) {
        background: var(--danger-color);
        color: var(--white);
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }
    
    .btn-secondary:hover {
        background: var(--gray-200);
        transform: translateY(-2px);
    }
    
    .btn-icon {
        width: 16px;
        height: 16px;
        transition: var(--transition);
    }
    
    .btn:hover .btn-icon {
        transform: translateX(2px);
    }
    
    /* ===== BADGES ===== */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: var(--border-radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    
    .badge-success {
        background: var(--success-light);
        color: var(--success-dark);
        border: 1px solid rgba(37, 211, 102, 0.2);
    }
    
    .badge-warning {
        background: var(--warning-light);
        color: #b76e00;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
    
    .badge-danger {
        background: var(--danger-light);
        color: var(--danger-color);
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .badge-info {
        background: var(--info-light);
        color: var(--info-color);
        border: 1px solid rgba(23, 162, 184, 0.2);
    }
    
    .badge-primary {
        background: var(--primary-light);
        color: var(--primary-color);
        border: 1px solid rgba(26, 95, 180, 0.2);
    }
    
    .badge-secondary {
        background: var(--gray-100);
        color: var(--gray-600);
        border: 1px solid var(--gray-200);
    }
    
    /* ===== ERROR ALERT BOX ===== */
    .error-alert-box {
        margin-top: 1.5rem;
        padding: 1.25rem;
        background: var(--danger-light);
        border-radius: var(--border-radius-md);
        border: 1px solid var(--danger-color);
        border-left: 4px solid var(--danger-color);
    }
    
    .error-alert-box .error-header {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .error-alert-box .error-icon {
        width: 24px;
        height: 24px;
        color: var(--danger-color);
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .error-alert-box .error-title {
        font-weight: 700;
        color: var(--danger-color);
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .error-alert-box .error-message {
        color: var(--gray-700);
        font-size: 0.875rem;
        margin: 0;
    }
    
    .error-alert-box .error-actions {
        margin-top: 0.75rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    /* ===== MODERN ALERT ===== */
    .modern-alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 400px;
    }
    
    .modern-alert {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        border-left: 4px solid var(--primary-color);
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 350px;
    }
    
    .modern-alert.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .modern-alert.hide {
        transform: translateX(120%);
        opacity: 0;
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
        font-weight: 600;
        font-size: 1rem;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
    }
    
    .alert-message {
        font-size: 0.875rem;
        color: var(--gray-600);
        line-height: 1.4;
    }
    
    .alert-close {
        background: none;
        border: none;
        color: var(--gray-500);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 6px;
        transition: var(--transition);
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .alert-close:hover {
        background: var(--gray-100);
        color: var(--gray-700);
    }
    
    .modern-alert.success {
        border-left-color: var(--success-color);
        background: rgba(37, 211, 102, 0.05);
    }
    
    .modern-alert.error {
        border-left-color: var(--danger-color);
        background: rgba(220, 53, 69, 0.05);
    }
    
    .modern-alert.warning {
        border-left-color: var(--warning-color);
        background: rgba(255, 193, 7, 0.05);
    }
    
    .modern-alert.info {
        border-left-color: var(--primary-color);
        background: rgba(26, 95, 180, 0.05);
    }
    
    .page-alert {
        margin-bottom: 1.5rem;
        position: static;
        transform: none;
        opacity: 1;
        min-width: auto;
    }
    
    /* ===== LOADING OVERLAY ===== */
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
    
    /* ===== CAPTCHA MODAL STYLES ===== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
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
    
    .modal-content {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        max-width: 500px;
        width: 90%;
        padding: 2rem;
        box-shadow: var(--shadow-xl);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    .modal-overlay.active .modal-content {
        transform: scale(1);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gray-800);
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray-400);
        transition: var(--transition);
    }
    
    .modal-close:hover {
        color: var(--danger-color);
    }
    
    .modal-body {
        margin-bottom: 1.5rem;
    }
    
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    
    .captcha-container {
        background: var(--gray-100);
        padding: 1.5rem;
        border-radius: var(--border-radius-md);
        text-align: center;
    }
    
    .captcha-code {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 0.5rem;
        background: var(--white);
        padding: 1rem;
        border-radius: var(--border-radius-md);
        font-family: monospace;
        border: 2px solid var(--gray-300);
        margin-bottom: 1rem;
    }
    
    .captcha-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        font-size: 1rem;
        text-align: center;
        letter-spacing: 0.25rem;
    }
    
    .captcha-input:focus {
        outline: none;
        border-color: var(--primary-color);
    }
    
    .captcha-refresh {
        margin-top: 0.5rem;
        background: none;
        border: none;
        color: var(--primary-color);
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    /* ===== EMAIL MODAL ===== */
    .email-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    
    .email-input:focus {
        outline: none;
        border-color: var(--primary-color);
    }
    
    /* ===== ANIMATIONS ===== */
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
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-in {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 1024px) {
        .session-header .hero-title { font-size: 1.75rem; }
        .session-header { min-height: 20vh !important; padding: 2rem 1.5rem; }
        .glass-container { padding: 0 1.5rem 2rem; margin-top: -2rem; }
        .modern-alert { min-width: 300px; }
    }
    
    @media (max-width: 768px) {
        .session-header .hero-title { font-size: 1.5rem; }
        .session-header { min-height: 20vh !important; padding: 1.5rem 1rem; }
        .session-header .header-content { flex-direction: column; align-items: flex-start; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -1.5rem; }
        .info-grid { grid-template-columns: 1fr; }
        .data-grid { grid-template-columns: 1fr; }
        .actions-grid { grid-template-columns: 1fr; }
        .info-table th { width: 45%; }
        .modern-alert-container { left: 20px; right: 20px; max-width: none; }
        .modern-alert { min-width: auto; width: 100%; }
        .session-header .shape,
        .session-header .bg-circle-3 { display: none !important; }
    }
    
    @media (max-width: 480px) {
        .session-header .hero-title { font-size: 1.25rem; }
        .session-header { min-height: 18vh !important; padding: 1rem; }
        .session-header .back-btn { width: 40px; height: 40px; }
        .card-header-custom { padding: 1.25rem 1rem; }
        .card-body-custom { padding: 1.25rem 1rem; }
        .info-table th { width: 50%; font-size: 0.75rem; }
        .info-table td { font-size: 0.75rem; }
        .btn { padding: 0.5rem 1rem; font-size: 0.75rem; }
        .badge { padding: 0.25rem 0.5rem; font-size: 0.65rem; }
        .data-card { padding: 0.75rem; }
    }
</style>

<!-- Modern Alert Container -->
<div class="modern-alert-container" id="alertContainer"></div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Processing...</div>
        <div class="loading-subtext" id="loadingSubtext">Please wait</div>
    </div>
</div>

<!-- CAPTCHA Modal -->
<div class="modal-overlay" id="captchaModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Security Verification</div>
            <button class="modal-close" id="closeCaptchaModal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 1rem; color: var(--gray-600);">
                Please complete the CAPTCHA verification to access the inheritance results. 
                This ensures the request is made by a real human and prevents automated access.
            </p>
            <div class="captcha-container">
                <div class="captcha-code" id="captchaCode">LOADING</div>
                <input type="text" id="captchaInput" class="captcha-input" placeholder="Enter the code above" maxlength="6" autocomplete="off">
                <button class="captcha-refresh" id="refreshCaptchaBtn">⟳ Refresh Code</button>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelCaptchaBtn">Cancel</button>
            <button class="btn btn-primary" id="verifyCaptchaBtn">Verify & Continue</button>
        </div>
    </div>
</div>

<!-- Email Request Modal (Step 5) -->
<div class="modal-overlay" id="emailModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Request Report via Email</div>
            <button class="modal-close" id="closeEmailModal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 1rem; color: var(--gray-600);">
                Your inheritance report is ready. Enter your email address to receive the report.
                You will be notified once the admin approves and sends the report.
            </p>
            <input type="email" id="emailInput" class="email-input" placeholder="your@email.com" autocomplete="off">
            <p style="margin-top: 0.75rem; font-size: 0.75rem; color: var(--gray-500);">
                <strong>Note:</strong> Reports require admin approval before delivery. You will receive an email once approved.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="skipEmailBtn">Skip for Now</button>
            <button class="btn btn-success" id="submitEmailBtn">Send Report</button>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section class="session-header">
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

    <div class="container hero-container">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('instant-estate.history') }}" class="back-btn" title="Back to History">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="hero-title">Instant Estate Session</h1>
                    <p class="hero-subtitle">{{ $session->original_filename ?? 'Death Certificate Processing' }}</p>
                </div>
            </div>
            <div>
                @php
                    $statusColors = [
                        'uploaded' => ['bg' => 'rgba(255,255,255,0.1)', 'text' => '#fff'],
                        'processing_ocr' => ['bg' => 'rgba(23,162,184,0.3)', 'text' => '#fff'],
                        'ocr_completed' => ['bg' => 'rgba(26,95,180,0.3)', 'text' => '#fff'],
                        'data_confirmed' => ['bg' => 'rgba(37,211,102,0.3)', 'text' => '#fff'],
                        'captcha_verified' => ['bg' => 'rgba(37,211,102,0.3)', 'text' => '#fff'],
                        'record_found' => ['bg' => 'rgba(37,211,102,0.3)', 'text' => '#fff'],
                        'notification_requested' => ['bg' => 'rgba(255,193,7,0.3)', 'text' => '#111'],
                        'email_sent' => ['bg' => 'rgba(37,211,102,0.3)', 'text' => '#fff'],
                        'completed' => ['bg' => 'rgba(37,211,102,0.3)', 'text' => '#fff'],
                        'failed' => ['bg' => 'rgba(220,53,69,0.3)', 'text' => '#fff'],
                    ];
                    $statusStyle = $statusColors[$session->status] ?? ['bg' => 'rgba(255,255,255,0.1)', 'text' => '#fff'];
                    
                    $statusLabels = [
                        'uploaded' => 'Uploaded',
                        'processing_ocr' => 'Processing OCR',
                        'ocr_completed' => 'OCR Complete',
                        'data_confirmed' => 'Data Confirmed',
                        'captcha_verified' => 'CAPTCHA Verified',
                        'record_found' => 'Record Found',
                        'no_record' => 'No Record - Redirect to Calculator',
                        'notification_requested' => 'Notification Requested',
                        'email_sent' => 'Email Sent',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ];
                    $statusLabel = $statusLabels[$session->status] ?? ucfirst(str_replace('_', ' ', $session->status));
                @endphp
                <span class="status-badge-large" style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }};">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Main Container -->
<div class="glass-container">
    
    <!-- Step 1 Quality Alert Display (if quality issues) -->
    @if($session->quality_check_passed === false && $session->quality_issues)
    <div class="modern-alert warning show page-alert animate-in">
        <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <div class="alert-content">
            <div class="alert-title">Document Quality Warning</div>
            <div class="alert-message">
                Please upload photos of the ORIGINAL death certificate only. 
                The following were detected: {{ implode(', ', $session->quality_issues) }}. 
                Please ensure clear photo showing the ENTIRE document.
            </div>
        </div>
    </div>
    @endif
    
    <!-- Admin Status Message / Reject Reason -->
    @if($session->admin_status || $session->reject_reason)
    <div class="modern-alert {{ $session->admin_status === 'rejected' ? 'error' : ($session->admin_status === 'approved' ? 'success' : 'info') }} show page-alert animate-in">
        <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @if($session->admin_status === 'rejected')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @elseif($session->admin_status === 'approved')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @endif
        </svg>
        <div class="alert-content">
            <div class="alert-title">
                @if($session->admin_status === 'rejected')
                    Request Rejected
                @elseif($session->admin_status === 'approved')
                    Request Approved
                @else
                    Status Update
                @endif
            </div>
            <div class="alert-message">
                {{ $session->reject_reason ?? 'Your request has been ' . $session->admin_status . '.' }}
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="modern-alert success show page-alert animate-in">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <div class="alert-title">Success</div>
                <div class="alert-message">{{ session('success') }}</div>
            </div>
            <button class="alert-close" onclick="this.closest('.modern-alert').remove()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="modern-alert error show page-alert animate-in">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <div class="alert-title">Error</div>
                <div class="alert-message">{{ session('error') }}</div>
            </div>
            <button class="alert-close" onclick="this.closest('.modern-alert').remove()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Session Information Card (Step 1-2) -->
    <div class="glass-card animate-in" style="animation-delay: 0.1s;">
        <div class="card-header-custom">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="card-title">Document & OCR Information</h2>
        </div>
        <div class="card-body-custom">
            <div class="info-grid">
                <div class="info-section">
                    <div class="info-section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Document Details
                    </div>
                    <table class="info-table">
                        <tr><th>Session ID</th><td><code>{{ $session->session_id }}</code></td></tr>
                        <tr><th>Filename</th><td title="{{ $session->original_filename }}">{{ Str::limit($session->original_filename, 40) }}</td></tr>
                        @if($session->file_size)<tr><th>File Size</th><td>{{ number_format($session->file_size / 1024, 2) }} KB</td></tr>@endif
                        @if($session->file_mime)<tr><th>File Type</th><td><span class="badge badge-secondary">{{ strtoupper(explode('/', $session->file_mime)[1] ?? $session->file_mime) }}</span></td></tr>@endif
                        <tr><th>Created</th><td title="{{ $session->created_at->format('Y-m-d H:i:s') }}">{{ $session->created_at->diffForHumans() }}</td></tr>
                        @if($session->processing_completed_at)<tr><th>OCR Completed</th><td>{{ $session->processing_completed_at->diffForHumans() }}</td></tr>@endif
                    </table>
                </div>

                <div class="info-section">
                    <div class="info-section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        OCR Processing Status
                    </div>
                    <table class="info-table">
                        <tr><th>Status</th><td><span class="badge badge-{{ $session->status_color ?? 'secondary' }}">{{ $statusLabel }}</span></td></tr>
                        @if(isset($session->ocr_confidence))
                        <tr>
                            <th>OCR Confidence</th>
                            <td>
                                @php
                                    $confidenceClass = $session->ocr_confidence >= 80 ? 'confidence-high' : ($session->ocr_confidence >= 50 ? 'confidence-medium' : 'confidence-low');
                                @endphp
                                <div class="confidence-bar-container">
                                    <div class="confidence-bar">
                                        <div class="confidence-bar-fill" data-width="{{ $session->ocr_confidence }}" style="width: 0%;"></div>
                                    </div>
                                    <span class="confidence-value {{ $confidenceClass }}">{{ $session->ocr_confidence }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endif
                        <tr><th>Quality Check</th><td>{!! $session->quality_check_passed ? '<span class="badge badge-success">Passed</span>' : '<span class="badge badge-danger">Failed - Please re-upload original document</span>' !!}</td></tr>
                        @if($session->missing_fields && count($session->missing_fields) > 0)
                        <tr><th>Missing Required Fields</th><td><span class="badge badge-warning">{{ count($session->missing_fields) }} field(s) need review</span></td></tr>
                        @endif
                        @if($session->error_message)<tr><th>Error</th><td style="color: var(--danger-color);">{{ $session->error_message }}</td></tr>@endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Extracted Data with Edit Capability -->
    <div class="glass-card animate-in" style="animation-delay: 0.2s;">
        <div class="card-header-custom">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <h2 class="card-title">Extracted Data (Review & Edit Required Fields)</h2>
        </div>
        <div class="card-body-custom">
            @php
                $requiredFields = [
                    'deceased_name' => 'Deceased Full Name',
                    'deceased_nric' => 'NRIC/Passport Number',
                    'date_of_birth' => 'Date of Birth',
                    'gender' => 'Gender',
                    'death_date' => 'Date of Death',
                    'death_place' => 'Place of Death',
                    'contact_email' => 'Contact Email',
                    'contact_phone' => 'Contact Phone',
                    'residential_address' => 'Residential Address',
                    'registration_number' => 'Registration Number (No. Daftar)',  // NEW FIELD
                ];
                $extractedData = $session->extracted_data ?? [];
                $missingFields = $session->missing_fields ?? [];
            @endphp
            
            @if(count($missingFields) > 0)
            <div class="missing-warning">
                <strong>Missing Required Fields:</strong> 
                Please fill in the highlighted fields below before confirming.
            </div>
            @endif
            
            <div class="data-grid" id="extractedFieldsContainer">
                @foreach($requiredFields as $field => $label)
                    @php
                        $value = $extractedData[$field] ?? '';
                        $isMissing = in_array($field, $missingFields);
                        $isRequired = true;
                    @endphp
                    <div class="data-card {{ $isMissing ? 'missing' : '' }}" data-field="{{ $field }}">
                        <div class="data-card-label">
                            @if($isRequired)<span style="color: var(--danger-color);" title="Required field">* </span>@endif
                            {{ $label }}
                            @if($isMissing)<span style="color: #b76e00;"> (missing - required)</span>@endif
                        </div>
                        @if($field === 'gender')
                            <select class="editable-field" data-field="{{ $field }}" style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius-sm); border: 1px solid var(--gray-300);">
                                <option value="">Select Gender</option>
                                <option value="male" {{ $value === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $value === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        @elseif($field === 'date_of_birth' || $field === 'death_date')
                            <input type="date" class="editable-field {{ $isMissing ? 'field-missing' : '' }}" 
                                   data-field="{{ $field }}" value="{{ $value }}" 
                                   style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius-sm); border: 1px solid var(--gray-300);">
                        @elseif($field === 'contact_email')
                            <input type="email" class="editable-field {{ $isMissing ? 'field-missing' : '' }}" 
                                   data-field="{{ $field }}" value="{{ htmlspecialchars($value) }}" 
                                   placeholder="Enter {{ $label }}" 
                                   style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius-sm); border: 1px solid var(--gray-300);">
                        @elseif($field === 'contact_phone')
                            <input type="tel" class="editable-field {{ $isMissing ? 'field-missing' : '' }}" 
                                   data-field="{{ $field }}" value="{{ htmlspecialchars($value) }}" 
                                   placeholder="Enter {{ $label }}" 
                                   style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius-sm); border: 1px solid var(--gray-300);">
                        @else
                            <input type="text" class="editable-field {{ $isMissing ? 'field-missing' : '' }}" 
                                   data-field="{{ $field }}" value="{{ htmlspecialchars($value) }}" 
                                   placeholder="Enter {{ $label }}" 
                                   style="width: 100%; padding: 0.5rem; border-radius: var(--border-radius-sm); border: 1px solid var(--gray-300);">
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="editable-section">
                <div class="btn-group-modern" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <!-- REMOVED server-side disabled condition; JavaScript will manage the state -->
                    <button class="btn btn-primary" id="confirmDataBtn">
                        Confirm Verified Data
                    </button>
                    @if($session->status === 'data_confirmed' || $session->status === 'record_found' || $session->status === 'captcha_verified')
                    <span class="badge badge-success">✓ Data Confirmed</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Step 4-5: Record Match Result -->
    @if($session->status === 'record_found' || $session->status === 'captcha_verified' || $session->status === 'completed')
    <div class="glass-card animate-in" style="animation-delay: 0.3s;">
        <div class="card-header-custom">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <h2 class="card-title">Database Match Found</h2>
        </div>
        <div class="card-body-custom">
            <div class="info-section" style="background: var(--success-light); border-color: var(--success-color);">
                <div class="info-section-title">
                    <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Matching Record Retrieved from Database
                </div>
                <p style="margin-bottom: 1rem;">
                    ✅ A matching record was found in our database. 
                    The inheritance report has been generated based on the existing estate plan or calculation.
                </p>
                @if($session->matched_record_type)
                <p><strong>Record Type:</strong> {{ ucfirst(str_replace('_', ' ', $session->matched_record_type)) }}</p>
                @endif
                
                <div class="actions-grid">
                    @if($session->status === 'record_found')
                        <button class="btn btn-primary" id="verifyAccessBtn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Verify with CAPTCHA to Access Report
                        </button>
                    @elseif($session->status === 'captcha_verified' || $session->status === 'completed')
                        @if($session->report_pdf_path)
                            <a href="{{ route('instant-estate.view-report', $session->session_id) }}" class="btn btn-success" target="_blank">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Full Report (PDF)
                            </a>
                            <button class="btn btn-outline-primary" id="requestEmailBtn">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Request Email Notification (Admin Approval Required)
                            </button>
                        @else
                            <p class="text-muted">Report generation in progress...</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Step 6: No Record Found - Redirect to Calculator -->
    @if($session->status === 'no_record')
    <div class="glass-card animate-in" style="animation-delay: 0.3s;">
        <div class="card-header-custom">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <h2 class="card-title">No Matching Record Found</h2>
        </div>
        <div class="card-body-custom">
            <div class="info-section" style="background: var(--info-light); border-color: var(--info-color);">
                <div class="info-section-title">
                    <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    No Record Found - Redirect to Calculator
                </div>
                <p style="margin-bottom: 1rem;">
                    ℹ️ No matching estate planning or inheritance calculation record was found in our database.
                    You will be redirected to the Faraid calculator where you can manually enter the information 
                    to calculate the inheritance distribution according to Islamic Faraid principles.
                </p>
                
                <div class="actions-grid">
                    <a href="{{ route('calculator.create') }}?source=instant_estate&session_id={{ $session->session_id }}" 
                       class="btn btn-primary" id="redirectToCalculatorBtn">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Faraid Calculator
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions Card -->
    <div class="glass-card animate-in" style="animation-delay: 0.4s;">
        <div class="card-header-custom">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            <h2 class="card-title">Session Actions</h2>
        </div>
        <div class="card-body-custom">
            <div class="actions-grid">
                <a href="{{ route('instant-estate.history') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to History
                </a>

                @if($session->file_path)
                    <a href="{{ route('instant-estate.download-certificate', $session->session_id) }}" class="btn btn-outline-primary">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Certificate
                    </a>
                @endif

                @if(!$session->calculation_id && $session->status !== 'completed')
                    <button type="button" 
                            class="btn btn-outline-danger delete-btn"
                            data-session-id="{{ $session->session_id }}"
                            data-filename="{{ $session->original_filename }}"
                            data-delete-url="{{ route('instant-estate.delete-session', $session->session_id) }}">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Session
                    </button>
                @endif
            </div>

            <!-- Error Alert for Failed Sessions -->
            @if($session->status === 'failed')
                <div class="error-alert-box">
                    <div class="error-header">
                        <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <strong class="error-title">Processing Failed</strong>
                            <p class="error-message">
                                @if($session->error_message)
                                    {{ $session->error_message }}
                                @else
                                    The processing of this document failed. This could be due to poor image quality.
                                    Please ensure you upload a clear photo of the ORIGINAL death certificate.
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="error-actions">
                        <a href="{{ route('instant-estate.index') }}" class="btn btn-primary btn-sm">
                            Upload New Document (Step 1)
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Step 8: Success Info for Completed Sessions -->
            @if(in_array($session->status, ['completed', 'email_sent']))
                <div style="margin-top: 1.5rem; padding: 1rem; background: var(--success-light); border-radius: var(--border-radius-md);">
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px; flex-shrink: 0; color: var(--success-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <strong style="color: var(--success-dark); display: block; margin-bottom: 0.25rem;">Process Complete</strong>
                            <p style="margin: 0; color: var(--gray-700); font-size: 0.875rem;">
                                The inheritance process has been completed successfully.
                                @if($session->report_pdf_path)
                                    You can view the report or request it via email.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== MODERN ALERT SYSTEM =====
    class ModernAlert {
        static show({ type = 'info', title, message, duration = 4000 }) {
            const container = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            
            const icons = {
                success: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                warning: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`,
                error: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
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
                <button class="alert-close" onclick="ModernAlert.close('${alertId}')">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            
            container.appendChild(alertEl);
            requestAnimationFrame(() => alertEl.classList.add('show'));
            
            if (duration > 0) {
                setTimeout(() => ModernAlert.close(alertId), duration);
            }
            
            return alertId;
        }
        
        static close(alertId) {
            const alertEl = document.getElementById(alertId);
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('hide');
                setTimeout(() => { if (alertEl.parentNode) alertEl.parentNode.removeChild(alertEl); }, 500);
            }
        }
        
        static success(message, title = 'Success') { return this.show({ type: 'success', title, message }); }
        static error(message, title = 'Error') { return this.show({ type: 'error', title, message, duration: 6000 }); }
        static warning(message, title = 'Warning') { return this.show({ type: 'warning', title, message }); }
        static info(message, title = 'Info') { return this.show({ type: 'info', title, message }); }
    }
    
    window.ModernAlert = ModernAlert;
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // ===== ANIMATE CONFIDENCE BARS =====
    document.querySelectorAll('.confidence-bar-fill').forEach(bar => {
        const targetWidth = bar.getAttribute('data-width');
        if (targetWidth) {
            setTimeout(() => { bar.style.width = targetWidth + '%'; }, 300);
        }
    });
    
    // ===== DYNAMICALLY ENABLE / DISABLE CONFIRM BUTTON BASED ON REQUIRED FIELDS =====
    const requiredFieldKeys = @json(array_keys($requiredFields));
    const confirmDataBtn = document.getElementById('confirmDataBtn');
    
    function checkAllRequiredFields() {
        const fields = document.querySelectorAll('.editable-field');
        let allFilled = true;
        
        fields.forEach(field => {
            const fieldName = field.getAttribute('data-field');
            // Only check fields that are required
            if (requiredFieldKeys.includes(fieldName)) {
                if (field.value.trim() === '') {
                    allFilled = false;
                }
            }
        });
        
        // Enable/disable button if status allows (not already confirmed)
        if (confirmDataBtn && !confirmDataBtn.hasAttribute('data-confirmed')) {
            confirmDataBtn.disabled = !allFilled;
        }
    }
    
    // Listen for input changes on all editable fields
    document.getElementById('extractedFieldsContainer').addEventListener('input', checkAllRequiredFields);
    
    // Initial check (disable button if required fields are empty)
    checkAllRequiredFields();
    
    // ===== Step 3: Confirm Data Button =====
    if (confirmDataBtn) {
        confirmDataBtn.addEventListener('click', async function() {
            // This handler is only reached if button is enabled (all required fields filled)
            const editedData = {};
            const missingFields = [];
            
            document.querySelectorAll('.editable-field').forEach(field => {
                const fieldName = field.getAttribute('data-field');
                const value = field.value.trim();
                editedData[fieldName] = value;
                
                if (requiredFieldKeys.includes(fieldName) && !value) {
                    missingFields.push(fieldName);
                    field.classList.add('field-missing');
                } else {
                    field.classList.remove('field-missing');
                }
            });
            
            // Show loading
            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.classList.add('active');
            document.getElementById('loadingText').textContent = 'Confirming Data...';
            document.getElementById('loadingSubtext').textContent = 'Saving verified data';
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const sessionId = '{{ $session->session_id }}';
                
                const response = await fetch(`/instant-estate/edit-and-continue/${sessionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ extracted_data: editedData })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Disable button after successful confirmation
                    confirmDataBtn.disabled = true;
                    confirmDataBtn.setAttribute('data-confirmed', 'true');
                    
                    if (data.record_found) {
                        ModernAlert.success(data.message, 'Database Match Found');
                        showCaptchaModal(sessionId);
                    } else if (data.redirect_url) {
                        ModernAlert.success(data.message, 'Redirecting to Calculator');
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1500);
                    } else {
                        ModernAlert.success('Data confirmed successfully!', 'Complete');
                        setTimeout(() => { window.location.reload(); }, 1000);
                    }
                } else {
                    ModernAlert.error(data.message || 'Failed to confirm data', 'Error');
                }
            } catch (error) {
                console.error('Confirm data error:', error);
                ModernAlert.error('Network error. Please try again.', 'Error');
            } finally {
                loadingOverlay.classList.remove('active');
            }
        });
    }
    
    // ===== Step 7: CAPTCHA Functions =====
    let currentCaptchaCode = null;
    let currentSessionId = '{{ $session->session_id }}';
    
    const generateCaptcha = () => {
        currentCaptchaCode = Math.floor(100000 + Math.random() * 900000).toString();
        const captchaCodeEl = document.getElementById('captchaCode');
        if (captchaCodeEl) captchaCodeEl.textContent = currentCaptchaCode;
        const input = document.getElementById('captchaInput');
        if (input) input.value = '';
        return currentCaptchaCode;
    };
    
    const showCaptchaModal = (sessionId) => {
        currentSessionId = sessionId || currentSessionId;
        generateCaptcha();
        document.getElementById('captchaModal').classList.add('active');
    };
    
    const hideCaptchaModal = () => {
        document.getElementById('captchaModal').classList.remove('active');
    };
    
    const verifyCaptcha = async () => {
        const userInput = document.getElementById('captchaInput').value.trim();
        
        if (userInput !== currentCaptchaCode) {
            ModernAlert.error('Invalid verification code. Please try again.', 'CAPTCHA Failed');
            generateCaptcha();
            return false;
        }
        
        hideCaptchaModal();
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.classList.add('active');
        document.getElementById('loadingText').textContent = 'Verifying CAPTCHA...';
        document.getElementById('loadingSubtext').textContent = 'Security verification in progress';
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch(`/instant-estate/verify-captcha/${currentSessionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ captcha_token: 'verified_' + Date.now() })
            });
            
            const data = await response.json();
            
            if (data.success) {
                ModernAlert.success('CAPTCHA verified successfully!', 'Complete');
                
                if (data.next_action === 'view_report' || data.report_data) {
                    showEmailModal();
                } else if (data.redirect_url) {
                    setTimeout(() => { window.location.href = data.redirect_url; }, 1500);
                } else {
                    setTimeout(() => { window.location.reload(); }, 1000);
                }
            } else {
                ModernAlert.error(data.message || 'CAPTCHA verification failed', 'Verification Failed');
                showCaptchaModal(currentSessionId);
            }
        } catch (error) {
            console.error('CAPTCHA verification error:', error);
            ModernAlert.error('Network error during verification', 'Error');
            showCaptchaModal(currentSessionId);
        } finally {
            loadingOverlay.classList.remove('active');
        }
    };
    
    // ===== Step 5: Email Request Functions =====
    const showEmailModal = () => {
        document.getElementById('emailInput').value = '';
        document.getElementById('emailModal').classList.add('active');
    };
    
    const hideEmailModal = () => {
        document.getElementById('emailModal').classList.remove('active');
    };
    
    const requestEmailNotification = async () => {
        const emailInput = document.getElementById('emailInput');
        const email = emailInput ? emailInput.value.trim() : '';
        
        if (!email || !email.includes('@') || !email.includes('.')) {
            ModernAlert.error('Please enter a valid email address.', 'Invalid Email');
            return;
        }
        
        hideEmailModal();
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.classList.add('active');
        document.getElementById('loadingText').textContent = 'Submitting Request...';
        document.getElementById('loadingSubtext').textContent = 'Requesting admin approval';
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch(`/instant-estate/request-notification/${currentSessionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            });
            
            const data = await response.json();
            
            if (data.success) {
                ModernAlert.success(
                    '✅ Request submitted! You will receive an email once admin approves and sends the report.',
                    'Notification Requested'
                );
                setTimeout(() => { window.location.reload(); }, 2000);
            } else {
                ModernAlert.error(data.message || 'Request failed', 'Error');
            }
        } catch (error) {
            console.error('Email request error:', error);
            ModernAlert.error('Network error. Please try again.', 'Error');
        } finally {
            loadingOverlay.classList.remove('active');
        }
    };
    
    // ===== Modal Event Listeners =====
    const verifyAccessBtn = document.getElementById('verifyAccessBtn');
    if (verifyAccessBtn) {
        verifyAccessBtn.addEventListener('click', () => showCaptchaModal(currentSessionId));
    }
    
    const requestEmailBtn = document.getElementById('requestEmailBtn');
    if (requestEmailBtn) {
        requestEmailBtn.addEventListener('click', () => showEmailModal());
    }
    
    document.getElementById('closeCaptchaModal')?.addEventListener('click', hideCaptchaModal);
    document.getElementById('cancelCaptchaBtn')?.addEventListener('click', hideCaptchaModal);
    document.getElementById('verifyCaptchaBtn')?.addEventListener('click', verifyCaptcha);
    document.getElementById('refreshCaptchaBtn')?.addEventListener('click', generateCaptcha);
    
    document.getElementById('closeEmailModal')?.addEventListener('click', hideEmailModal);
    document.getElementById('skipEmailBtn')?.addEventListener('click', hideEmailModal);
    document.getElementById('submitEmailBtn')?.addEventListener('click', requestEmailNotification);
    
    // Close modals when clicking outside
    document.getElementById('captchaModal')?.addEventListener('click', (e) => {
        if (e.target === document.getElementById('captchaModal')) hideCaptchaModal();
    });
    document.getElementById('emailModal')?.addEventListener('click', (e) => {
        if (e.target === document.getElementById('emailModal')) hideEmailModal();
    });
    
    // ===== DELETE SESSION FUNCTIONALITY =====
    async function deleteSession(sessionId, filename, deleteUrl) {
        const confirmed = confirm(`Are you sure you want to delete "${filename}"?\n\nThis action cannot be undone.`);
        if (!confirmed) return;
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            ModernAlert.error('CSRF token not found. Please refresh the page.', 'Security Error');
            return;
        }
        
        loadingOverlay.classList.add('active');
        document.getElementById('loadingText').textContent = 'Deleting...';
        document.getElementById('loadingSubtext').textContent = 'Removing session: ' + filename;
        
        try {
            const response = await fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                ModernAlert.success(data.message || `"${filename}" has been deleted successfully.`, 'Deleted');
                setTimeout(() => {
                    window.location.href = data.redirect_url || '{{ route("instant-estate.history") }}';
                }, 1500);
            } else {
                ModernAlert.error(data.message || 'Failed to delete the session.', 'Delete Failed');
            }
        } catch (error) {
            console.error('Delete error:', error);
            ModernAlert.error('An unexpected error occurred.', 'Error');
        } finally {
            loadingOverlay.classList.remove('active');
        }
    }
    
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const sessionId = this.getAttribute('data-session-id');
            const filename = this.getAttribute('data-filename');
            const deleteUrl = this.getAttribute('data-delete-url');
            if (sessionId && deleteUrl) deleteSession(sessionId, filename, deleteUrl);
        });
    });
    
    // ===== AUTO-HIDE PAGE ALERTS =====
    setTimeout(() => {
        document.querySelectorAll('.page-alert.show').forEach(alert => {
            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    }, 1000);
    
    console.log('✅ Instant Estate Session View Loaded');
});
</script>
@endsection