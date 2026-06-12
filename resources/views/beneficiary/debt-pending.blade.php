@extends('layouts.app')

@section('title', 'Debt Settlement Pending')

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
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
        color: var(--text-primary);
        overflow-x: hidden;
    }
    
    .estate-header {
        min-height: 45vh !important;
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
        animation: pulse 8s ease-in-out infinite;
    }

    .estate-header .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
        animation: pulse 6s ease-in-out infinite reverse;
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
        font-size: 2.75rem;
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
        font-size: 1.125rem;
        max-width: 600px;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
    }
    
    .glass-container {
        max-width: 1200px;
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
        box-shadow: var(--shadow-xl);
    }
    
    .card-header {
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
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
    
    .card-body {
        padding: 2rem;
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
    
    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
    
    .status-card {
        background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%);
        border-radius: var(--border-radius-lg);
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        color: #2c2c2c;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .status-card-bg {
        position: absolute;
        top: 0;
        right: 0;
        width: 250px;
        height: 250px;
        background: rgba(0,0,0,0.05);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }
    
    .status-card-bg-2 {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200px;
        height: 200px;
        background: rgba(0,0,0,0.03);
        border-radius: 50%;
        transform: translate(-30%, 30%);
    }
    
    .status-card-icon {
        background: rgba(0,0,0,0.1);
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
    }
    
    .status-card-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .status-card-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .welcome-message {
        background: linear-gradient(135deg, var(--info-color) 0%, #138496 100%);
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }
    
    .progress-wrapper {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        height: 100%;
    }
    
    .progress-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .progress-icon {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        width: 50px;
        height: 50px;
        border-radius: var(--border-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .progress-icon svg {
        width: 24px;
        height: 24px;
        color: white;
    }
    
    .progress-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }
    
    .progress-bar-container {
        margin-bottom: 1rem;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-light);
    }
    
    .progress-track {
        height: 25px;
        background: var(--gray-200);
        border-radius: 25px;
        overflow: hidden;
    }
    
    .progress-fill {
        background: linear-gradient(90deg, var(--success-color), #34ce57);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
        font-weight: 500;
        transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        width: 0%;
    }
    
    .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0.5rem;
    }
    
    .stats-table tr {
        border-bottom: 1px solid var(--gray-200);
    }
    
    .stats-table td {
        padding: 0.75rem 0;
    }
    
    .stats-table td:first-child {
        color: var(--gray-600);
    }
    
    .stats-table td:last-child {
        text-align: right;
        font-weight: 600;
    }
    
    .stats-table .success-value {
        color: var(--success-color);
    }
    
    .stats-table .warning-value {
        color: var(--warning-color);
        font-weight: 700;
    }
    
    .pending-debts-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .debt-item {
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--border-radius-sm);
        margin-bottom: 0.75rem;
        transition: var(--transition);
    }
    
    .debt-item:hover {
        transform: translateX(4px);
        background: var(--gray-100);
    }
    
    .debt-creditor {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .debt-amount {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
    }
    
    .debt-remaining {
        color: var(--warning-color);
        font-weight: 600;
    }
    
    .debt-total {
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-top: 0.25rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--gray-500);
    }
    
    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .notification-card {
        text-align: center;
        padding: 2rem;
    }
    
    .notification-icon {
        background: var(--gray-50);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .notification-icon svg {
        width: 32px;
        height: 32px;
        color: var(--primary-color);
    }
    
    .notification-text {
        color: var(--gray-600);
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: var(--border-radius-md);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-warning {
        background: var(--warning-light);
        color: var(--warning-dark);
    }

    /* PDF STYLES - Matching estate-setup/index.blade.php */
    .pdf-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a5fb4;
        margin: 1.2rem 0 0.6rem 0;
        padding-bottom: 0.3rem;
        border-bottom: 2px solid #e8f1fd;
    }
    
    .pdf-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0.8rem;
        font-size: 0.7rem;
    }
    
    .pdf-info-table td {
        padding: 0.4rem 0.5rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }
    
    .pdf-info-table td:first-child {
        font-weight: 600;
        color: #475569;
        width: 30%;
    }
    
    .pdf-info-card {
        padding: 0.5rem 0.8rem;
        background: #f8fafc;
        border-radius: 6px;
        margin-bottom: 0.3rem;
        border-left: 3px solid #1a5fb4;
        font-size: 0.7rem;
    }

    /* Dashboard Card Styles for Distribution Details */
    .distribution-card {
        background: var(--white);
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
        padding: 1.5rem;
        margin-top: 1.5rem;
        transition: var(--transition);
    }
    
    .distribution-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }
    
    .distribution-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .distribution-header-icon {
        width: 40px;
        height: 40px;
        color: var(--success-color);
    }
    
    .distribution-header-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        flex: 1;
    }
    
    .distribution-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .distribution-detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: var(--gray-50);
        border-radius: var(--border-radius-sm);
    }
    
    .distribution-detail-label {
        font-weight: 600;
        color: var(--gray-600);
    }
    
    .distribution-detail-value {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
    }
    
    .distribution-detail-value.amount {
        color: var(--success-color);
        font-size: 1.25rem;
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
    
    @media (max-width: 768px) {
        .estate-header { min-height: 40vh; padding: 2rem 1rem; }
        .estate-header .hero-title { font-size: 2rem; }
        .estate-header .hero-subtitle { font-size: 1rem; }
        .glass-container { padding: 0 1rem; margin-top: -2rem; }
        .grid-2 { grid-template-columns: 1fr; gap: 1.5rem; }
        .card-body { padding: 1.5rem; }
        .status-card-title { font-size: 1.5rem; }
        .shape, .bg-circle-3 { display: none !important; }
        .distribution-details { grid-template-columns: 1fr; }
    }
    
    @media (max-width: 480px) {
        .estate-header .hero-title { font-size: 1.5rem; }
        .status-card-icon { width: 70px; height: 70px; }
        .status-card-icon svg { width: 35px; height: 35px; }
        .status-card-title { font-size: 1.25rem; }
    }
    
    @media print {
        body { background: white !important; font-size: 10pt; }
        .no-print, .estate-header, .status-card, .welcome-message, .notification-card, .progress-wrapper, .grid-2, .glass-card, .modern-alert-container, .loading-overlay, .confirmation-modal-overlay { display: none !important; }
        .glass-container { margin: 0; padding: 0; }
        .distribution-card { page-break-inside: avoid; border: 1px solid #e2e8f0; }
    }
</style>

<div class="modern-alert-container" id="alertContainer"></div>

<div class="loading-overlay" id="loadingOverlay" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:99999;opacity:0;pointer-events:none;transition:opacity 0.3s ease;">
    <div class="loading-content" style="background:white;padding:3rem;border-radius:20px;text-align:center;max-width:400px;width:90%;box-shadow:0 25px 50px -12px rgba(0,0,0,0.15);">
        <div class="loading-spinner" style="width:60px;height:60px;border:4px solid #e2e8f0;border-top-color:var(--primary-color);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 1.5rem;"></div>
        <div class="loading-text" style="font-size:1.125rem;font-weight:600;color:#0f172a;margin-bottom:0.5rem;">Processing...</div>
        <div class="loading-subtext" style="color:#64748b;font-size:0.875rem;">Please wait while we process your request</div>
    </div>
</div>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .loading-overlay.active {
        opacity: 1;
        pointer-events: all;
    }
</style>

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
                    <svg class="kicker-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Debt Settlement
                </span>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    In Progress
                </span>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Faraid Compliant
                </span>
            </div>
        </div>
        
        <h1 class="hero-title">
            Debt Settlement <span class="hero-highlight">In Progress</span>
        </h1>
        
        <p class="hero-subtitle">
            Access to estate distribution will be granted once all debts are fully settled
        </p>
    </div>
</header>

<div class="glass-container">
    <!-- Status Card -->
    <div class="status-card">
        <div class="status-card-bg"></div>
        <div class="status-card-bg-2"></div>
        <div class="status-card-icon">
            <svg width="50px" height="50px" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h2 class="status-card-title">Access Restricted</h2>
        <p class="status-card-subtitle">The estate is currently undergoing debt settlement</p>
    </div>

    <!-- Welcome Message -->
    <div class="welcome-message">
        <strong style="font-size: 1.1rem;">Dear {{ $link->beneficiary_name ?? ($heir->name ?? 'Beneficiary') }},</strong>
        <p style="margin: 0.75rem 0 0; opacity: 0.95;">
            Access to the estate distribution details will be granted once all debts 
            have been fully settled. This ensures fair and proper distribution according 
            to Islamic inheritance laws (Faraid).
        </p>
    </div>

    <!-- Main Grid -->
    <div class="grid-2">
        <!-- Progress Card -->
        <div class="progress-wrapper">
            <div class="progress-header">
                <div class="progress-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                    </svg>
                </div>
                <h3 class="progress-title">Debt Settlement Status</h3>
            </div>
            
            <div class="progress-bar-container">
                <div class="progress-label">
                    <span>Progress</span>
                    <span>{{ $debtSummary['settled_count'] ?? 0 }}/{{ $debtSummary['total_count'] ?? 0 }} debts settled</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill" style="width: {{ ($debtSummary['settled_count'] ?? 0) / max(1, ($debtSummary['total_count'] ?? 1)) * 100 }}%;">
                        {{ number_format(($debtSummary['settled_count'] ?? 0) / max(1, ($debtSummary['total_count'] ?? 1)) * 100, 0) }}%
                    </div>
                </div>
            </div>
            
            <table class="stats-table">
                <tr>
                    <td>Total Debts</td>
                    <td>{{ $debtSummary['formatted_total_debts'] ?? 'RM 0' }}</td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td class="success-value">{{ $debtSummary['formatted_total_paid'] ?? 'RM 0' }}</td>
                </tr>
                <tr>
                    <td>Remaining</td>
                    <td class="warning-value">{{ $debtSummary['formatted_total_remaining'] ?? 'RM 0' }}</td>
                </tr>
            </table>
        </div>

        <!-- Pending Debts Card -->
        <div class="progress-wrapper">
            <div class="progress-header">
                <div class="progress-icon" style="background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%);">
                    <svg width="24px" height="24px" fill="#2c2c2c" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm.01-3a1 1 0 010-2h.01a1 1 0 110 2h-.01z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="progress-title">Pending Debts</h3>
            </div>
            
            @if(isset($unsettledDebts) && count($unsettledDebts) > 0)
                <div class="pending-debts-list">
                    @foreach($unsettledDebts as $debt)
                    <div class="debt-item">
                        <div class="debt-creditor">{{ $debt['creditor_name'] ?? $debt->creditor_name ?? 'Unknown Creditor' }}</div>
                        <div class="debt-amount">
                            <span>Outstanding:</span>
                            <span class="debt-remaining">RM {{ number_format($debt['remaining'] ?? $debt->remaining_amount ?? 0, 2) }}</span>
                        </div>
                        <div class="debt-total">Total: RM {{ number_format($debt['amount'] ?? $debt->total_amount ?? 0, 2) }}</div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <p>No pending debts found</p>
                </div>
            @endif
        </div>
    </div>

    <!-- YOUR DISTRIBUTION DETAILS - Dashboard Card -->
    @if(isset($distributionDetails))
    <div class="distribution-card">
        <div class="distribution-header">
            <svg class="distribution-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <h3 class="distribution-header-title">Your Distribution Details</h3>
            <span class="card-badge success" style="background: var(--success-light); color: var(--success-dark);">Pending</span>
        </div>
        <div class="distribution-details">
            <div class="distribution-detail-item">
                <span class="distribution-detail-label">Your Name</span>
                <span class="distribution-detail-value">{{ $distributionDetails['name'] ?? 'N/A' }}</span>
            </div>
            <div class="distribution-detail-item">
                <span class="distribution-detail-label">Relationship to Deceased</span>
                <span class="distribution-detail-value">{{ $distributionDetails['relationship'] ?? 'N/A' }}</span>
            </div>
            <div class="distribution-detail-item">
                <span class="distribution-detail-label">Share Percentage</span>
                <span class="distribution-detail-value">{{ number_format($distributionDetails['percentage'] ?? 0, 2) }}%</span>
            </div>
            <div class="distribution-detail-item">
                <span class="distribution-detail-label">Your Inheritance Amount</span>
                <span class="distribution-detail-value amount">RM {{ number_format($distributionDetails['amount'] ?? 0, 2) }}</span>
            </div>
        </div>
        @if(($distributionDetails['type'] ?? '') === 'heir')
        <div class="info-card" style="margin-bottom: 0; margin-top: 0.5rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-size: 0.875rem;">This is your Faraid (Islamic inheritance) share. Distribution will be processed after all debts are settled.</p>
        </div>
        @elseif(($distributionDetails['type'] ?? '') === 'wasiyyah')
        <div class="info-card" style="margin-bottom: 0; margin-top: 0.5rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-size: 0.875rem;">This is your Wasiyyah share. Distribution will be processed after all debts are settled and within the 1/3 limit of net estate.</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Notification Card -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h2 class="card-title">Stay Updated</h2>
            <span class="card-badge">Notification</span>
        </div>
        <div class="card-body notification-card">
            <div class="notification-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="notification-text">
                You will receive an email notification once debts are settled and access is granted.
            </p>
        </div>
    </div>
</div>

<!-- HIDDEN PDF CONTENT - Matching estate-setup/index.blade.php structure -->
<div id="pdfContent" style="display:none;">
    <div style="padding:20px;font-family:'Poppins',sans-serif;max-width:800px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #1a5fb4;padding-bottom:15px;">
            <h1 style="color:#1a5fb4;font-size:22px;margin:0 0 5px 0;">Estate Planning Document</h1>
            <p style="color:#64748b;font-size:12px;margin:0;">Reference: {{ $estate->unique_id ?? 'N/A' }} | Status: {{ $estate->status_label ?? 'Pending' }} | Generated: {{ now()->format('d M Y') }}</p>
        </div>

        <div class="pdf-section-title">Personal Information</div>
        <table class="pdf-info-table">
            <tr><td>Name</td><td><strong>{{ $estate->deceased_name ?? 'N/A' }}</strong></td></tr>
            <tr><td>NRIC</td><td>{{ $estate->deceased_nric ?? 'N/A' }}</td></tr>
            <tr><td>Gender</td><td>{{ ucfirst($estate->gender ?? 'N/A') }}</td></tr>
            <tr><td>Date of Birth</td><td>{{ $estate->date_of_birth ? $estate->date_of_birth->format('d M Y') : 'N/A' }}</td></tr>
            <tr><td>Contact</td><td>{{ $estate->contact_email ?? 'N/A' }} | {{ $estate->contact_phone ?? 'N/A' }}</td></tr>
            <tr><td>Address</td><td>{{ $estate->address ?? 'N/A' }}</td></tr>
            <tr><td>Trustee</td><td>{{ $estate->trustee_name ?? 'Not appointed' }} ({{ $estate->trustee_email ?? 'N/A' }})</td></tr>
        </table>

        <div class="pdf-section-title">Financial Summary</div>
        <div style="display:flex;gap:15px;margin-bottom:15px;">
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366;font-size:16px;">RM {{ number_format($totalAssets ?? 0, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545;font-size:16px;">RM {{ number_format($totalDebts ?? 0, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4;font-size:16px;">RM {{ number_format(($totalAssets ?? 0) - ($totalDebts ?? 0), 2) }}</span></div>
        </div>

        <div class="pdf-section-title">Assets ({{ count($assets ?? []) }})</div>
        @if(isset($assets) && count($assets) > 0)
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Value</strong></th><th><strong>Ownership</strong></th><th><strong>Description</strong></th></tr></thead>
                <tbody>@foreach($assets as $asset)<tr><td>{{ $asset['name'] ?? $asset->name ?? 'N/A' }}</td><td>RM {{ number_format($asset['value'] ?? $asset->value ?? 0, 2) }}</td><td>{{ $asset['ownership_percentage'] ?? $asset->ownership_percentage ?? 100 }}%</td><td>{{ $asset['description'] ?? $asset->description ?? '-' }}</td></tr>@endforeach</tbody>
            </table>
        @else
            <p style="color:#94a3b8;font-size:12px;">No assets registered.</p>
        @endif

        <div class="pdf-section-title">Debts ({{ count($debts ?? []) }})</div>
        @if(isset($debts) && count($debts) > 0)
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Creditor</strong></th><th><strong>Type</strong></th><th><strong>Amount</strong></th><th><strong>Description</strong></th></tr></thead>
                <tbody>@foreach($debts as $debt)<tr><td>{{ $debt['creditor_name'] ?? $debt->creditor_name ?? 'N/A' }}</td><td>{{ $debt['debt_type'] ?? $debt->debt_type ?? 'Debt' }}</td><td>RM {{ number_format($debt['amount'] ?? $debt->amount ?? 0, 2) }}</td><td>{{ $debt['description'] ?? $debt->description ?? '-' }}</td></tr>@endforeach</tbody>
            </table>
        @else
            <p style="color:#94a3b8;font-size:12px;">No debts registered.</p>
        @endif

        <div class="pdf-section-title">Faraid Heirs Distribution</div>
        @if(isset($heirs) && count($heirs) > 0)
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>NRIC</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @php $netEstate = max(0, ($totalAssets ?? 0) - ($totalDebts ?? 0)); $totalWasiyyahPct = $totalWasiyyahPct ?? 0; $remainingForHeirs = $netEstate * (1 - ($totalWasiyyahPct / 100)); @endphp
                    @foreach($heirs as $heir)
                        <tr><td>{{ $heir['name'] ?? $heir->name ?? 'N/A' }}</td><td>{{ $heir['nric'] ?? $heir->nric ?? '-' }}</td><td>{{ ucfirst(str_replace('_', ' ', $heir['relationship'] ?? $heir->relationship ?? 'N/A')) }}</td><td><strong>{{ number_format($heir['share_percentage'] ?? $heir->share_percentage ?? 0, 2) }}%</strong></td><td>RM {{ number_format((($heir['share_percentage'] ?? $heir->share_percentage ?? 0) / 100) * $remainingForHeirs, 2) }}</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="3">Total</td><td>{{ number_format($totalHeirPct ?? 0, 2) }}%</td><td>RM {{ number_format($remainingForHeirs, 2) }}</td></tr>
                </tbody>
            </table>
        @else
            <p style="color:#94a3b8;font-size:12px;">No heirs registered.</p>
        @endif

        @if(isset($wasiyyahBeneficiaries) && count($wasiyyahBeneficiaries) > 0)
            <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @foreach($wasiyyahBeneficiaries as $item)
                        <tr><td>{{ $item['beneficiary_name'] ?? $item->beneficiary_name ?? 'N/A' }}</td><td>{{ $item['relationship'] ?? $item->relationship ?? 'N/A' }}</td><td><strong>{{ number_format($item['requested_percentage'] ?? $item->requested_percentage ?? 0, 2) }}%</strong></td><td>RM {{ number_format((($item['requested_percentage'] ?? $item->requested_percentage ?? 0) / 100) * $netEstate, 2) }}</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total</td><td>{{ number_format($totalWasiyyahPct, 2) }}%</td><td>RM {{ number_format(($totalWasiyyahPct / 100) * $netEstate, 2) }}</td></tr>
                </tbody>
            </table>
        @endif

        <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:10px;color:#94a3b8;text-align:center;">
            <p>This is a system-generated document. Generated on {{ now()->format('d F Y, h:i A') }} | Document ID: {{ $estate->unique_id ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Alert System
    class ModernAlert {
        static show({ type = 'info', title, message, duration = 4000 }) {
            const container = document.getElementById('alertContainer');
            if (!container) return;
            
            const alertId = 'alert-' + Date.now();
            const icons = {
                success: `<svg class="alert-icon" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                error: `<svg class="alert-icon" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                warning: `<svg class="alert-icon" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`,
                info: `<svg class="alert-icon" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
            };
            
            const alertEl = document.createElement('div');
            alertEl.className = `modern-alert ${type}`;
            alertEl.id = alertId;
            alertEl.style.cssText = 'display:flex;align-items:flex-start;gap:1rem;padding:1rem 1.25rem;background:rgba(255,255,255,0.98);backdrop-filter:blur(20px);border-radius:15px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.15);transform:translateX(120%);opacity:0;transition:all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);min-width:320px;border-left:4px solid;';
            if (type === 'success') alertEl.style.borderLeftColor = '#25D366';
            else if (type === 'error') alertEl.style.borderLeftColor = '#dc3545';
            else if (type === 'warning') alertEl.style.borderLeftColor = '#ffc107';
            else alertEl.style.borderLeftColor = '#17a2b8';
            
            alertEl.innerHTML = `
                ${icons[type] || icons.info}
                <div class="alert-content" style="flex:1;">
                    <div class="alert-title" style="font-weight:700;font-size:0.9375rem;margin-bottom:0.25rem;">${escapeHtml(title)}</div>
                    <div class="alert-message" style="font-size:0.8125rem;color:#64748b;line-height:1.4;">${message}</div>
                </div>
                <button class="alert-close" onclick="document.getElementById('${alertId}').classList.remove('show'); setTimeout(() => document.getElementById('${alertId}')?.remove(), 500);" style="background:none;border:none;cursor:pointer;padding:0.25rem;color:#64748b;">✕</button>
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
    
    function escapeHtml(str) { 
        if (!str) return ''; 
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); 
    }
    
    // PDF Download Function - Same as estate-setup/index.blade.php
    window.downloadPDF = function() {
        const pdfContent = document.getElementById('pdfContent');
        if (!pdfContent) {
            ModernAlert.error('PDF content not found.', 'Download Failed');
            return;
        }
        
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        if (!printWindow) {
            ModernAlert.error('Please allow pop-ups to download the PDF.', 'Popup Blocked');
            return;
        }
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Estate Plan - {{ $estate->deceased_name ?? 'PDF' }}</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
                <style>
                    * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
                    body { padding: 20px; font-size: 11pt; color: #333; }
                    .pdf-section-title { font-size: 1rem; font-weight: 700; color: #1a5fb4; margin: 1.2rem 0 0.6rem 0; padding-bottom: 0.3rem; border-bottom: 2px solid #e8f1fd; }
                    .pdf-info-table { width: 100%; border-collapse: collapse; margin-bottom: 0.8rem; font-size: 0.7rem; }
                    .pdf-info-table td { padding: 0.4rem 0.5rem; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
                    .pdf-info-table td:first-child { font-weight: 600; color: #475569; width: 30%; }
                    .pdf-info-card { padding: 0.5rem 0.8rem; background: #f8fafc; border-radius: 6px; margin-bottom: 0.3rem; border-left: 3px solid #1a5fb4; font-size: 0.7rem; }
                    @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
                </style>
            </head>
            <body>
                ${pdfContent.innerHTML}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 500);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
        
        ModernAlert.success('PDF generation started. Your browser print dialog will open. Select "Save as PDF" to download.', 'PDF Ready');
    };
    
    // Progress bar animation
    document.addEventListener('DOMContentLoaded', function() {
        const progressFill = document.getElementById('progressFill');
        if (progressFill) {
            const targetWidth = progressFill.style.width;
            progressFill.style.width = '0%';
            setTimeout(() => {
                progressFill.style.width = targetWidth;
            }, 100);
        }
        
        const cards = document.querySelectorAll('.progress-wrapper, .glass-card, .status-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
                this.style.transition = 'all 0.3s ease';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
    
})();
</script>
@endsection