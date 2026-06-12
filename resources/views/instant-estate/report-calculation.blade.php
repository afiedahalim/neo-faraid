{{-- resources/views/instant-estate/report-calculation.blade.php --}}
@extends('layouts.app')

@section('title', 'Inheritance Report - Faraid Calculation')

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
        min-height: 30vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 3rem 2rem;
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
        display: block;
        animation: fadeIn 0.5s ease-out;
    }

    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl), 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .card-header {
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
        padding: 1.5rem 2rem;
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
    
    .card-badge.warning {
        background: var(--warning-light);
        color: var(--warning-dark);
    }
    
    .card-body {
        padding: 2rem;
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
    
    .grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
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
    
    .info-card svg {
        flex-shrink: 0;
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
        margin-top: 1rem;
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
    
    .results-table tfoot td {
        background: var(--gray-100);
        font-weight: 700;
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
    .badge-husband { background: #e8f1fd; color: #1a5fb4; }
    .badge-wife { background: #fce4ec; color: #c2185b; }
    .badge-son { background: #e8f1fd; color: #1a5fb4; }
    .badge-daughter { background: #fce4ec; color: #c2185b; }
    .badge-father { background: #e8f1fd; color: #1a5fb4; }
    .badge-mother { background: #fce4ec; color: #c2185b; }
    
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
    
    .shariah-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #1a5fb4 0%, #0d2d5c 100%);
        color: var(--accent-color);
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-xl);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .calculation-details {
        background: var(--gray-50);
        border-radius: var(--border-radius-md);
        padding: 1.25rem;
        font-size: 0.875rem;
        line-height: 1.6;
        border-left: 4px solid var(--primary-color);
    }
    
    .calculation-details pre {
        background: transparent;
        font-family: monospace;
        font-size: 0.8125rem;
        white-space: pre-wrap;
        margin: 0;
    }
    
    .family-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--gray-100);
        border-radius: var(--border-radius-xl);
        font-size: 0.8125rem;
        font-weight: 500;
    }
    
    .family-chip.alive {
        background: var(--success-light);
        color: var(--success-dark);
    }
    
    .family-chip.deceased {
        background: var(--danger-light);
        color: var(--danger-dark);
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
    
    @media (max-width: 1024px) {
        .grid-3, .grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .estate-header .hero-title { font-size: 2rem; }
        .estate-header { min-height: 35vh !important; padding: 2rem 1rem; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -2rem; }
        .card-body { padding: 1.5rem; }
        .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        .btn-group { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
    
    @media (max-width: 480px) {
        .estate-header .hero-title { font-size: 1.5rem; }
        .card-title { font-size: 1.25rem; }
    }
    
    @media print {
        body { background: white !important; font-size: 10pt; }
        .no-print { display: none !important; }
        .glass-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; break-inside: avoid; margin-bottom: 1rem; }
        .card-body { padding: 1rem; }
        .estate-header { min-height: auto !important; padding: 1.5rem 2rem; background: #1a5fb4 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .btn, .btn-group, .modern-alert-container { display: none !important; }
        .glass-container { max-width: 100%; margin: 0; padding: 0 1rem; }
    }
</style>

<div class="modern-alert-container" id="alertContainer"></div>

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
                    Faraid Compliant
                </span>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Precise Calculation
                </span>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Shariah-Compliant
                </span>
            </div>
        </div>
        
        <h1 class="hero-title">
            <span class="hero-highlight">Inheritance</span> Report
        </h1>
        
        <p class="hero-subtitle">
            Detailed Faraid calculation report based on Islamic inheritance law. 
            This report outlines the rightful shares of each heir according to Shariah.
        </p>
    </div>
</header>

<div class="glass-container">
    <!-- Session Messages -->
    @if(session('success'))
        <div class="info-card" style="background: var(--success-light); border-left-color: var(--success-color); margin-bottom: 1.5rem;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--success-dark);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="info-card" style="background: var(--danger-light); border-left-color: var(--danger-color); margin-bottom: 1.5rem;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--danger-dark);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong>Error</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Deceased Information Card -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h2 class="card-title">Deceased Information</h2>
            <span class="card-badge">Personal Details</span>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="summary-item" style="border-bottom: none; padding: 0.5rem 0;">
                    <span class="summary-label">Full Name</span>
                    <span class="summary-value" style="font-size: 1rem;">{{ $deceased_name ?? 'N/A' }}</span>
                </div>
                <div class="summary-item" style="border-bottom: none; padding: 0.5rem 0;">
                    <span class="summary-label">Gender</span>
                    <span class="summary-value" style="font-size: 1rem;">
                        @if(($deceased_gender ?? '') === 'male')
                            👨 Male
                        @elseif(($deceased_gender ?? '') === 'female')
                            👩 Female
                        @else
                            {{ ucfirst($deceased_gender ?? 'N/A') }}
                        @endif
                    </span>
                </div>
                <div class="summary-item" style="border-bottom: none; padding: 0.5rem 0;">
                    <span class="summary-label">Date of Death</span>
                    <span class="summary-value" style="font-size: 1rem;">{{ $date_of_death ?? 'N/A' }}</span>
                </div>
                <div class="summary-item" style="border-bottom: none; padding: 0.5rem 0;">
                    <span class="summary-label">Marital Status</span>
                    <span class="summary-value" style="font-size: 1rem;">{{ ucfirst($marital_status ?? 'N/A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary Card -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h2 class="card-title">Financial Summary</h2>
            <span class="card-badge">Estate Value</span>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div class="summary-panel" style="margin-top: 0;">
                    <div class="summary-panel-header">
                        <h3>Total Assets</h3>
                    </div>
                    <div class="summary-panel-body">
                        <div class="summary-item">
                            <span class="summary-label">Gross Estate Value</span>
                            <span class="summary-value positive">{{ $formatted_total_assets ?? 'RM 0.00' }}</span>
                        </div>
                        @if(isset($total_debts) && $total_debts > 0)
                        <div class="summary-item">
                            <span class="summary-label">Less: Debts & Liabilities</span>
                            <span class="summary-value negative">- {{ number_format($total_debts, 2) }}</span>
                        </div>
                        @endif
                        @if(isset($funeral_expenses) && $funeral_expenses > 0)
                        <div class="summary-item">
                            <span class="summary-label">Less: Funeral Expenses</span>
                            <span class="summary-value negative">- {{ number_format($funeral_expenses, 2) }}</span>
                        </div>
                        @endif
                        @if(isset($zakat_deductions) && $zakat_deductions > 0)
                        <div class="summary-item">
                            <span class="summary-label">Less: Unpaid Zakat</span>
                            <span class="summary-value negative">- {{ number_format($zakat_deductions, 2) }}</span>
                        </div>
                        @endif
                        <div class="summary-total">
                            <div class="summary-item">
                                <span class="summary-label">Net Estate for Distribution</span>
                                <span class="summary-value">{{ $formatted_net_assets ?? 'RM 0.00' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="summary-panel" style="margin-top: 0;">
                    <div class="summary-panel-header">
                        <h3>Distribution Overview</h3>
                    </div>
                    <div class="summary-panel-body">
                        <div class="summary-item">
                            <span class="summary-label">Total Heirs</span>
                            <span class="summary-value">{{ count($heirs ?? []) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Distribution</span>
                            <span class="summary-value positive">{{ $formatted_net_assets ?? 'RM 0.00' }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Distribution Method</span>
                            <span class="summary-value">Faraid (Islamic Law)</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Shariah Status</span>
                            <span class="summary-value" style="color: var(--success-color);">✓ Compliant</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Family Composition Card -->
    @if(!empty($family_composition) && is_array($family_composition))
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h2 class="card-title">Family Composition</h2>
            <span class="card-badge">Faraid Eligibility</span>
        </div>
        <div class="card-body">
            <div class="grid-3">
                @php
                    $familyLabels = [
                        'wife_count' => ['icon' => '👩', 'label' => 'Wives'],
                        'husband_count' => ['icon' => '👨', 'label' => 'Husbands'],
                        'father_status' => ['icon' => '👨‍🦳', 'label' => 'Father'],
                        'mother_status' => ['icon' => '👩‍🦳', 'label' => 'Mother'],
                        'son_count' => ['icon' => '👦', 'label' => 'Sons'],
                        'daughter_count' => ['icon' => '👧', 'label' => 'Daughters'],
                        'full_brother_count' => ['icon' => '👨', 'label' => 'Full Brothers'],
                        'full_sister_count' => ['icon' => '👩', 'label' => 'Full Sisters'],
                        'paternal_brother_count' => ['icon' => '👨', 'label' => 'Paternal Half-Brothers'],
                        'paternal_sister_count' => ['icon' => '👩', 'label' => 'Paternal Half-Sisters'],
                        'maternal_brother_count' => ['icon' => '👨', 'label' => 'Maternal Brothers'],
                        'maternal_sister_count' => ['icon' => '👩', 'label' => 'Maternal Sisters'],
                        'grandfather_status' => ['icon' => '👴', 'label' => 'Grandfather'],
                        'grandmother_status' => ['icon' => '👵', 'label' => 'Grandmother'],
                    ];
                @endphp
                @foreach($family_composition as $key => $value)
                    @php
                        $labelInfo = $familyLabels[$key] ?? ['icon' => '📋', 'label' => ucfirst(str_replace('_', ' ', $key))];
                        $isStatusField = in_array($key, ['father_status', 'mother_status', 'grandfather_status', 'grandmother_status']);
                    @endphp
                    @if($value !== null && $value !== '' && $value !== 0)
                        <div class="family-chip {{ $isStatusField && $value === 'alive' ? 'alive' : ($isStatusField && $value === 'deceased' ? 'deceased' : '') }}">
                            <span>{{ $labelInfo['icon'] }}</span>
                            <span><strong>{{ $labelInfo['label'] }}:</strong></span>
                            <span>
                                @if($isStatusField)
                                    @if($value === 'alive')
                                        <span class="badge badge-success" style="font-size: 0.7rem;">✓ Alive</span>
                                    @elseif($value === 'deceased')
                                        <span class="badge badge-danger" style="font-size: 0.7rem;">✗ Deceased</span>
                                    @else
                                        {{ $value }}
                                    @endif
                                @else
                                    {{ $value }}
                                @endif
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Heirs Distribution Card -->
    @if(!empty($heirs) && count($heirs) > 0)
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h2 class="card-title">Heirs Distribution</h2>
            <span class="card-badge success">{{ count($heirs) }} Heir(s)</span>
        </div>
        <div class="card-body">
            <div class="info-card">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>Distribution based on Islamic Faraid principles as prescribed in the Quran (Surah An-Nisa, verses 11-12 and 176). Each heir's share has been calculated according to their relationship to the deceased and the presence of other heirs.</p>
            </div>

            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Heir Name</th>
                            <th>Relationship</th>
                            <th>Faraid Share</th>
                            <th>Amount (RM)</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($heirs as $heir)
                        <tr>
                            <td>
                                <strong>{{ $heir['name'] ?? 'Unknown' }}</strong>
                                @if(!empty($heir['nric']))
                                    <br><small class="text-muted" style="font-size: 0.7rem;">{{ $heir['nric'] }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower(str_replace(' ', '-', $heir['relationship'] ?? 'heir')) }}" style="background: var(--primary-light);">
                                    {{ ucfirst(str_replace('_', ' ', $heir['relationship'] ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>
                                {{ $heir['share'] ?? (isset($heir['fraction']) ? $heir['fraction'] : number_format($heir['percentage'] ?? 0, 2) . '%') }}
                            </td>
                            <td style="font-weight: 600; color: var(--success-dark);">
                                RM {{ number_format($heir['amount'] ?? 0, 2) }}
                            </td>
                            <td>{{ number_format($heir['percentage'] ?? 0, 2) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background: var(--gray-100);">
                        <tr>
                            <td colspan="3"><strong>Total Distribution</strong></td>
                            <td style="font-weight: 700; font-size: 1rem;"><strong>RM {{ number_format($net_assets ?? 0, 2) }}</strong></td>
                            <td><strong>100%</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="btn-group no-print">
                <button class="btn btn-primary" onclick="printReport()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Print / Save as PDF
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="glass-card">
        <div class="card-body" style="text-align: center; padding: 3rem 2rem;">
            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity: 0.5; margin: 0 auto 1rem; display: block;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 style="color: var(--gray-700); margin-bottom: 0.5rem;">No Heirs Data Available</h3>
            <p style="color: var(--gray-500); margin-bottom: 1.5rem;">No heir distribution data found for this calculation. Please ensure the family composition has been properly defined.</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
        </div>
    </div>
    @endif

    <!-- Calculation Details Card -->
    @if(!empty($calculation_data))
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <h2 class="card-title">Calculation Details</h2>
            <span class="card-badge">Faraid Methodology</span>
        </div>
        <div class="card-body">
            <div class="calculation-details">
                @if(is_array($calculation_data))
                    @foreach($calculation_data as $key => $value)
                        @if(!empty($value))
                            <div style="margin-bottom: 0.5rem;">
                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                @if(is_array($value))
                                    <pre style="margin-top: 0.25rem; background: transparent; padding: 0;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    {{ $value }}
                                @endif
                            </div>
                        @endif
                    @endforeach
                @else
                    {{ $calculation_data }}
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Shariah Compliance Card -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h2 class="card-title">Shariah Compliance</h2>
            <span class="card-badge success">Compliant</span>
        </div>
        <div class="card-body">
            <div class="info-card" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-left-color: var(--success-color);">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--success-dark);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <strong style="font-size: 1rem;">✓ Halal & Shariah-Compliant Inheritance Distribution</strong>
                    <p style="margin-top: 0.25rem; margin-bottom: 0;">This inheritance calculation has been performed in accordance with Islamic Faraid law as prescribed in the Quran (Surah An-Nisa, verses 11-12 and 176) and the Sunnah of Prophet Muhammad (ﷺ).</p>
                </div>
            </div>

            <div style="margin-top: 1rem; padding: 1rem; background: var(--gray-50); border-radius: var(--border-radius-md);">
                <p style="color: var(--gray-600); font-size: 0.8125rem; margin-bottom: 0.5rem;">
                    <strong>📖 Reference:</strong> Faraid (Islamic inheritance law) is derived from the Quran and Sunnah. The shares are fixed and cannot be altered by the deceased's will except within the 1/3 limit for non-heirs.
                </p>
                <p style="color: var(--gray-600); font-size: 0.8125rem; margin-bottom: 0;">
                    <strong>⚖️ Legal Note:</strong> This report is for informational purposes only. For legal distribution of estate, consult a qualified Islamic inheritance lawyer (Peguam Syarie) or the Shariah Court (Mahkamah Syariah).
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align: center; padding: 2rem 0 1rem; color: var(--gray-500); font-size: 0.75rem; border-top: 1px solid var(--gray-200); margin-top: 0.5rem;">
        <p>This document is generated by Neo Faraid - Islamic Inheritance Calculator</p>
        <p>Generated on: {{ now()->format('d F Y, h:i:s A') }}</p>
        @if(isset($calculation_id))
            <p>Calculation ID: {{ $calculation_id }}</p>
        @endif
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
                success: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                error: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                warning: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`,
                info: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
            };
            
            const alertEl = document.createElement('div');
            alertEl.className = `modern-alert ${type}`;
            alertEl.id = alertId;
            alertEl.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;background:white;border-radius:12px;box-shadow:0 20px 40px rgba(0,0,0,0.12);padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;min-width:300px;border-left:4px solid;transform:translateX(120%);opacity:0;transition:all 0.4s cubic-bezier(0.68,-0.55,0.265,1.55);';
            alertEl.style.borderLeftColor = type === 'success' ? '#25D366' : type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : '#17a2b8';
            alertEl.innerHTML = `
                ${icons[type] || icons.info}
                <div style="flex:1;">
                    <div style="font-weight:700;margin-bottom:0.25rem;">${escapeHtml(title)}</div>
                    <div style="font-size:0.8125rem;color:#64748b;">${message}</div>
                </div>
                <button onclick="document.getElementById('${alertId}').remove()" style="background:none;border:none;cursor:pointer;color:#94a3b8;">✕</button>
            `;
            
            container.appendChild(alertEl);
            setTimeout(() => {
                alertEl.style.transform = 'translateX(0)';
                alertEl.style.opacity = '1';
            }, 10);
            
            if (duration > 0) {
                setTimeout(() => {
                    alertEl.style.transform = 'translateX(120%)';
                    alertEl.style.opacity = '0';
                    setTimeout(() => alertEl.remove(), 500);
                }, duration);
            }
        }
        
        static success(message, title = 'Success') { return this.show({ type: 'success', title, message }); }
        static error(message, title = 'Error') { return this.show({ type: 'error', title, message, duration: 5000 }); }
        static warning(message, title = 'Warning') { return this.show({ type: 'warning', title, message }); }
        static info(message, title = 'Information') { return this.show({ type: 'info', title, message }); }
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
    
    window.printReport = function() {
        window.print();
        ModernAlert.success('Print dialog opened. Select "Save as PDF" to download.', 'Print Ready');
    };
    
    // Expose ModernAlert globally
    window.ModernAlert = ModernAlert;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Any initialization if needed
        console.log('Faraid Calculation Report Loaded');
    });
})();
</script>

<!-- Additional styles for modern-alert that might not be in main CSS -->
<style>
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
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
    }
    
    @media print {
        .modern-alert-container, .no-print {
            display: none !important;
        }
        .glass-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }
        .estate-header {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

@endsection