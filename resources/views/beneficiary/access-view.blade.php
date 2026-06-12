{{-- resources/views/beneficiary/access-view.blade.php --}}
@extends('layouts.app')

@section('title', 'Estate Distribution - ' . ($estate->deceased_name ?? ''))

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
        min-height: 35vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 4rem 2rem;
        border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
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
    
    .card-badge.success {
        background: var(--success-light);
        color: var(--success-dark);
    }
    
    .card-badge.warning {
        background: var(--warning-light);
        color: var(--warning-dark);
    }
    
    .card-badge.info {
        background: var(--info-light);
        color: var(--info-color);
    }
    
    .card-badge.danger {
        background: var(--danger-light);
        color: var(--danger-dark);
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .summary-card {
        background: var(--white);
        padding: 1.75rem;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
        text-align: center;
        transition: var(--transition);
    }
    
    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
    
    .summary-card-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .summary-card-value {
        font-size: 2rem;
        font-weight: 800;
    }
    
    .summary-card-value.primary { color: var(--primary-color); }
    .summary-card-value.success { color: var(--success-color); }
    .summary-card-value.danger { color: var(--danger-color); }
    .summary-card-value.warning { color: var(--warning-color); }
    
    .summary-card-sub {
        font-size: 0.875rem;
        color: var(--gray-500);
        margin-top: 0.25rem;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 1rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--border-radius-md);
        margin-bottom: 1rem;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .detail-value {
        color: var(--gray-700);
    }
    
    .detail-value.highlight {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--success-color);
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: var(--border-radius-md);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-success {
        background: var(--success-light);
        color: var(--success-dark);
    }
    
    .badge-warning {
        background: var(--warning-light);
        color: var(--warning-dark);
    }
    
    .badge-danger {
        background: var(--danger-light);
        color: var(--danger-dark);
    }
    
    .badge-info {
        background: var(--info-light);
        color: var(--info-color);
    }
    
    .badge-primary {
        background: var(--primary-light);
        color: var(--primary-color);
    }
    
    .alert-banner {
        border-radius: var(--border-radius-lg);
        margin-bottom: 2rem;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    
    .alert-banner.success {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: white;
    }
    
    .alert-banner.warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%);
        color: #2c2c2c;
    }
    
    .alert-banner.danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, var(--danger-dark) 100%);
        color: white;
    }
    
    .alert-banner.info {
        background: linear-gradient(135deg, var(--info-color) 0%, #0f6c7c 100%);
        color: white;
    }
    
    .alert-banner-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }
    
    .alert-banner-icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
    }
    
    .button-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
        margin: 1.5rem 0;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1.75rem;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
        font-size: 0.9375rem;
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
        background: linear-gradient(135deg, var(--gray-200) 0%, var(--gray-300) 100%);
        color: var(--gray-700);
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        background: var(--gray-300);
    }
    
    .btn-video {
        background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);
        color: var(--white);
    }
    
    .btn-video:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 71, 87, 0.3);
    }
    
    .btn-pdf {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: var(--white);
    }
    
    .btn-pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(231, 76, 60, 0.3);
    }
    
    .welcome-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: var(--border-radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .welcome-card-bg {
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }
    
    .welcome-card-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    
    .access-badge {
        background: rgba(255,255,255,0.2);
        border-radius: var(--border-radius-md);
        padding: 0.75rem 1.25rem;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    
    .security-notice {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        text-align: center;
        box-shadow: var(--shadow-md);
    }
    
    .security-notice-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--text-light);
        font-size: 0.85rem;
        flex-wrap: wrap;
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
    
    .results-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
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
    
    .results-table tfoot tr {
        background: var(--gray-100);
        font-weight: 700;
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
        padding: 1rem 1.25rem;
    }
    
    .summary-panel-header h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .summary-panel-body {
        padding: 1rem 1.25rem;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
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
    }
    
    .summary-value.positive {
        color: var(--success-color);
    }
    
    .summary-value.negative {
        color: var(--danger-color);
    }
    
    .summary-total {
        background: var(--primary-light);
        margin-top: 0.5rem;
        padding: 0.75rem;
        border-radius: var(--border-radius-sm);
    }
    
    .pdf-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1a5fb4;
        margin: 1rem 0 0.5rem 0;
        padding-bottom: 0.3rem;
        border-bottom: 2px solid #e8f1fd;
    }
    
    .pdf-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0.8rem;
        font-size: 0.75rem;
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
        font-size: 0.75rem;
    }
    
    @media (max-width: 768px) {
        .estate-header { min-height: 35vh !important; padding: 2rem 1rem; }
        .estate-header .hero-title { font-size: 2rem; }
        .estate-header .hero-subtitle { font-size: 1rem; }
        .glass-container { padding: 0 1rem; margin-top: -2rem; }
        .card-body { padding: 1.5rem; }
        .detail-grid { grid-template-columns: 1fr; gap: 0.5rem; }
        .summary-grid { grid-template-columns: repeat(2, 1fr); }
        .shape, .bg-circle-3 { display: none !important; }
        .button-group { flex-direction: column; }
        .btn { justify-content: center; width: 100%; }
        .alert-banner { flex-direction: column; text-align: center; }
        .alert-banner-content { flex-direction: column; }
        .welcome-card-content { flex-direction: column; text-align: center; }
    }
    
    @media (max-width: 480px) {
        .estate-header .hero-title { font-size: 1.5rem; }
        .summary-grid { grid-template-columns: 1fr; }
    }
    
    @media print {
        body { background: white !important; font-size: 10pt; }
        .no-print { display: none !important; }
        .glass-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; break-inside: avoid; margin-bottom: 1rem; }
        .card-body { padding: 1rem; }
        .estate-header { min-height: auto !important; padding: 1rem 1rem; background: #1a5fb4 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .summary-grid { grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
        .btn, .button-group, .alert-banner, .security-notice, .no-print { display: none !important; }
        .glass-container { max-width: 100%; margin: 0; padding: 0 0.5rem; }
        .detail-grid { break-inside: avoid; }
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
</style>

@php
    // Calculate estate values from passed variables or fallback to estate relationships
    $totalAssets = $totalAssets ?? $estate->assets()->sum('value');
    $totalDebts = $totalDebts ?? $estate->debts()->sum('amount');
    $netEstate = max(0, $totalAssets - $totalDebts);
    $totalWasiyyahPct = $totalWasiyyahPct ?? $estate->wasiyyah()->sum('requested_percentage');
    $totalHeirPct = $totalHeirPct ?? $estate->heirs()->sum('share_percentage');
    $maxWasiyyahPct = 33.33;
    $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
    $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
    $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);
    
    // Get current beneficiary distribution based on their role
    $distribution = $distribution ?? null;
    $isHeir = $distribution && $distribution['type'] === 'heir';
    $isWasiyyah = $distribution && $distribution['type'] === 'wasiyyah';
    $isTrustee = $distribution && ($distribution['type'] === 'trustee' || $distribution['type'] === 'alternate_trustee');
    
    // If distribution not provided, try to determine from link
    if (!$distribution && isset($link) && isset($estate)) {
        if ($link->beneficiary_type === 'heir') {
            $heirRecord = $estate->heirs()->find($link->beneficiary_id);
            if ($heirRecord) {
                $isHeir = true;
                $distribution = [
                    'type' => 'heir',
                    'name' => $heirRecord->name,
                    'relationship' => $heirRecord->relationship,
                    'share_percentage' => $heirRecord->share_percentage ?? 0,
                    'amount' => (($heirRecord->share_percentage ?? 0) / 100) * $remainingForHeirs,
                    'formatted_amount' => 'RM ' . number_format((($heirRecord->share_percentage ?? 0) / 100) * $remainingForHeirs, 2)
                ];
            }
        } elseif ($link->beneficiary_type === 'wasiyyah') {
            $wasiyyahRecord = $estate->wasiyyah()->find($link->beneficiary_id);
            if ($wasiyyahRecord) {
                $isWasiyyah = true;
                $distribution = [
                    'type' => 'wasiyyah',
                    'name' => $wasiyyahRecord->beneficiary_name,
                    'relationship' => $wasiyyahRecord->relationship,
                    'requested_percentage' => $wasiyyahRecord->requested_percentage ?? 0,
                    'amount' => (($wasiyyahRecord->requested_percentage ?? 0) / 100) * $netEstate,
                    'formatted_amount' => 'RM ' . number_format((($wasiyyahRecord->requested_percentage ?? 0) / 100) * $netEstate, 2),
                    'description' => $wasiyyahRecord->description ?? ''
                ];
            }
        } elseif ($link->beneficiary_type === 'trustee') {
            $isTrustee = true;
            $distribution = [
                'type' => 'trustee',
                'name' => $link->beneficiary_name,
                'role' => 'Trustee',
                'responsibilities' => $distribution['responsibilities'] ?? [
                    'Oversee and manage estate distribution',
                    'Ensure all debts are properly settled',
                    'Distribute inheritance to heirs according to Faraid principles',
                    'Distribute Wasiyyah to nominated beneficiaries',
                    'Maintain compliance with Shariah law',
                    'Submit final distribution report'
                ]
            ];
        }
    }
    
    // Prepare heirs list
    $heirsList = $heirsList ?? [];
    if (empty($heirsList) && isset($estate) && $estate->heirs) {
        foreach ($estate->heirs as $heir) {
            $heirsList[] = [
                'name' => $heir->name,
                'relationship' => $heir->relationship_label ?? $heir->relationship,
                'percentage' => (float) $heir->share_percentage,
                'amount' => ((float) $heir->share_percentage / 100) * $remainingForHeirs,
            ];
        }
    }
    
    // Prepare wasiyyah list
    $wasiyyahList = $wasiyyahList ?? [];
    if (empty($wasiyyahList) && isset($estate) && $estate->wasiyyah) {
        foreach ($estate->wasiyyah as $was) {
            $wasiyyahList[] = [
                'name' => $was->beneficiary_name,
                'relationship' => $was->relationship,
                'percentage' => (float) $was->requested_percentage,
                'amount' => ((float) $was->requested_percentage / 100) * $netEstate,
            ];
        }
    }
    
    // Debt settlement summary
    $debtSummary = $debtSummary ?? [];
    if (empty($debtSummary) && isset($estate)) {
        $totalDebtCount = $estate->debts->count();
        $settledDebtCount = $estate->debts()->where(function($q) {
            $q->where('status', 'settled')->orWhereRaw('amount <= amount_paid');
        })->count();
        $totalRemainingDebts = $estate->debts()->where(function($q) {
            $q->where('status', '!=', 'settled')->orWhereRaw('amount > amount_paid');
        })->sum('amount');
        $allSettled = $totalDebtCount > 0 ? $settledDebtCount === $totalDebtCount : true;
        
        $debtSummary = [
            'total_count' => $totalDebtCount,
            'settled_count' => $settledDebtCount,
            'formatted_total_remaining' => 'RM ' . number_format($totalRemainingDebts, 2),
            'all_settled' => $allSettled
        ];
    }
    
    // Trustee information
    $trusteeName = $trusteeName ?? ($estate->trustee_name ?? null);
    $trusteeEmail = $trusteeEmail ?? ($estate->trustee_email ?? null);
    $trusteePhone = $trusteePhone ?? ($estate->trustee_phone ?? null);
    $expiryDate = $expiryDate ?? ($link->expires_at->format('d F Y, h:i A') ?? 'N/A');
    
    // Asset badge mapping for PDF
    $assetBadgeMap = [
        'Residential House' => 'real-estate', 'Apartment / Condominium' => 'real-estate',
        'Low-cost Flat (PPR / Kos Rendah)' => 'real-estate', 'Shop Lot' => 'real-estate',
        'Office Unit' => 'real-estate', 'Industrial Property (Factory / Warehouse)' => 'real-estate',
        'Agricultural Land' => 'real-estate', 'Vacant Land / Lot' => 'real-estate',
        'Vehicle (Car / Motorcycle)' => 'financial', 'Bank Savings' => 'financial',
        'Fixed Deposit' => 'financial', 'EPF / KWSP Savings' => 'financial', 
        'Tabung Haji Savings' => 'financial', 'Insurance / Takaful Payout' => 'financial', 
        'Cash in Hand' => 'financial',
        'ASB / Unit Trust Investment' => 'investment', 'Shares / Stocks' => 'investment',
        'Gold / Precious Metals' => 'investment', 'Business Ownership' => 'investment',
        'Digital Assets (Crypto / E-wallet)' => 'digital', 'Other Assets' => 'real-estate'
    ];
    
    $debtBadgeMap = [
        'Housing Loan' => 'secured', 'Car Loan / Hire Purchase' => 'secured', 
        'Business Loan' => 'secured', 'Personal Loan' => 'unsecured',
        'Credit Card Outstanding' => 'unsecured', 'Education Loan (PTPTN)' => 'unsecured',
        'Overdraft / Bank Facility' => 'secured', 'Borrowed from Family / Friends' => 'unsecured',
        'Unpaid Zakat' => 'religious', 'Unpaid Income Tax (LHDN)' => 'secured',
        'Utility Bills' => 'unsecured', 'Medical Bills' => 'unsecured',
        'Funeral Expenses' => 'unsecured', 'Court Fines / Legal Penalties' => 'secured',
        'Other Liabilities' => 'unsecured'
    ];
@endphp

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
                    Precise
                </span>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Reliable
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
            Estate <span class="hero-highlight">Distribution</span>
        </h1>
        
        <p class="hero-subtitle">
            View your inheritance details for {{ $estate->deceased_name ?? 'the deceased' }}
        </p>
    </div>
</header>

<div class="glass-container">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-card-bg"></div>
        <div class="welcome-card-content">
            <div>
                <h2 style="font-size: 1.5rem; margin: 0 0 0.25rem;">Welcome, {{ $link->beneficiary_name ?? ($beneficiaryName ?? 'Beneficiary') }}!</h2>
                <p style="margin: 0; opacity: 0.9;">You are viewing the estate distribution plan for <strong>{{ $estate->deceased_name ?? 'the deceased' }}</strong>.</p>
            </div>
            <div class="access-badge">
                <div style="font-size: 0.8rem; opacity: 0.8;">Access ID</div>
                <div style="font-weight: 600;">{{ substr($link->access_token ?? '', 0, 8) }}...</div>
            </div>
        </div>
    </div>

    <!-- Debt Settlement Alert -->
    @if(isset($debtSummary['all_settled']) && $debtSummary['all_settled'])
        <div class="alert-banner success">
            <div class="alert-banner-content">
                <svg class="alert-banner-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>All debts have been settled. The estate is ready for final distribution.</span>
            </div>
        </div>
    @elseif(isset($debtSummary['total_count']) && $debtSummary['total_count'] > 0)
        <div class="alert-banner warning">
            <div class="alert-banner-content">
                <svg class="alert-banner-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>Debt Settlement Pending: {{ $debtSummary['settled_count'] ?? 0 }}/{{ $debtSummary['total_count'] ?? 0 }} debts settled.</span>
            </div>
            <span class="badge badge-warning">Remaining: {{ $debtSummary['formatted_total_remaining'] ?? 'RM 0.00' }}</span>
        </div>
    @endif

    <!-- Estate Summary Cards -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <h2 class="card-title">Estate Summary</h2>
            <span class="card-badge">Financial Overview</span>
        </div>
        <div class="card-body">
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-card-label">Total Assets</div>
                    <div class="summary-card-value primary">RM {{ number_format($totalAssets, 2) }}</div>
                    <div class="summary-card-sub">Properties & Savings</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-label">Total Debts</div>
                    <div class="summary-card-value danger">RM {{ number_format($totalDebts, 2) }}</div>
                    <div class="summary-card-sub">Liabilities</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-label">Net Estate</div>
                    <div class="summary-card-value success">RM {{ number_format($netEstate, 2) }}</div>
                    <div class="summary-card-sub">After Debt Settlement</div>
                </div>
            </div>
            
            @if($netEstate > 0 && ($totalWasiyyahPct > 0 || $totalHeirPct > 0))
                <div class="summary-panel">
                    <div class="summary-panel-header">
                        <h3>Distribution Breakdown</h3>
                    </div>
                    <div class="summary-panel-body">
                        @if($totalWasiyyahPct > 0)
                            <div class="summary-item">
                                <span class="summary-label">Wasiyyah</span>
                                <span class="summary-value">{{ number_format($totalWasiyyahPct, 2) }}% (RM {{ number_format($wasiyyahAmount, 2) }})</span>
                            </div>
                        @endif
                        @if($totalHeirPct > 0)
                            <div class="summary-item">
                                <span class="summary-label">Faraid Heirs Distribution</span>
                                <span class="summary-value success">{{ number_format($totalHeirPct, 2) }}% of Estate After Wasiyyah</span>
                            </div>
                            <div class="summary-total">
                                <div class="summary-item">
                                    <span class="summary-label">Total Estate for Heirs</span>
                                    <span class="summary-value positive">RM {{ number_format($remainingForHeirs, 2) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- YOUR DISTRIBUTION DETAILS - MAIN CARD -->
    @if($distribution)
    <div class="glass-card" id="distributionCard">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
            </svg>
            <h2 class="card-title">Your Distribution Details</h2>
            <span class="card-badge {{ $isHeir ? 'success' : ($isWasiyyah ? 'info' : 'primary') }}">
                {{ $isHeir ? 'Faraid Heir' : ($isWasiyyah ? 'Wasiyyah Beneficiary' : ($distribution['type'] === 'trustee' ? 'Trustee' : 'Alternate Trustee')) }}
            </span>
        </div>
        <div class="card-body">
            @if($isHeir)
                <div class="detail-grid">
                    <div class="detail-label">Your Name</div>
                    <div class="detail-value">{{ $distribution['name'] }}</div>
                </div>
                <div class="detail-grid">
                    <div class="detail-label">Relationship to Deceased</div>
                    <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $distribution['relationship'])) }}</div>
                </div>
                <div class="detail-grid">
                    <div class="detail-label">Share Percentage</div>
                    <div class="detail-value"><strong>{{ number_format($distribution['share_percentage'], 2) }}%</strong> of Estate After Wasiyyah</div>
                </div>
                <div class="detail-grid" style="background: linear-gradient(135deg, rgba(37,211,102,0.1) 0%, rgba(18,140,126,0.1) 100%);">
                    <div class="detail-label" style="color: var(--success-color);">Your Inheritance Amount</div>
                    <div class="detail-value highlight">{{ $distribution['formatted_amount'] }}</div>
                </div>
                @if($totalHeirPct > 0)
                    <div class="info-card" style="margin-top: 1rem; margin-bottom: 0;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Your share represents <strong>{{ number_format($distribution['share_percentage'], 2) }}%</strong> of the total faraid distribution pool of <strong>RM {{ number_format($remainingForHeirs, 2) }}</strong> (net estate after wasiyyah).</span>
                    </div>
                @endif
            @elseif($isWasiyyah)
                <div class="detail-grid">
                    <div class="detail-label">Beneficiary Name</div>
                    <div class="detail-value">{{ $distribution['name'] }}</div>
                </div>
                <div class="detail-grid">
                    <div class="detail-label">Relationship</div>
                    <div class="detail-value">{{ ucfirst($distribution['relationship']) }}</div>
                </div>
                <div class="detail-grid">
                    <div class="detail-label">Requested Percentage</div>
                    <div class="detail-value"><strong>{{ number_format($distribution['requested_percentage'], 2) }}%</strong> of Net Estate</div>
                </div>
                @if(!empty($distribution['description']))
                <div class="detail-grid">
                    <div class="detail-label">Notes from Deceased</div>
                    <div class="detail-value"><em>{{ $distribution['description'] }}</em></div>
                </div>
                @endif
                <div class="detail-grid" style="background: linear-gradient(135deg, rgba(37,211,102,0.1) 0%, rgba(18,140,126,0.1) 100%);">
                    <div class="detail-label" style="color: var(--success-color);">Wasiyyah Amount</div>
                    <div class="detail-value highlight">{{ $distribution['formatted_amount'] }}</div>
                </div>
                <div class="info-card" style="margin-top: 1rem; margin-bottom: 0;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <span>Wasiyyah cannot exceed 1/3 (33.33%) of net estate. Total wasiyyah: <strong>{{ number_format($totalWasiyyahPct, 2) }}%</strong> of RM {{ number_format($netEstate, 2) }}.</span>
                </div>
            @elseif($isTrustee)
                <div style="text-align: center; padding: 1rem;">
                    <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <svg style="width: 40px; height: 40px; color: white;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 style="margin-bottom: 0.5rem;">You have been appointed as <strong>{{ $distribution['role'] ?? 'Trustee' }}</strong></h3>
                    <p style="color: var(--text-light); margin-bottom: 1rem;">Your responsibilities include:</p>
                    <ul style="text-align: left; display: inline-block; margin-top: 0.5rem;">
                        @foreach($distribution['responsibilities'] ?? [] as $responsibility)
                            <li style="margin-bottom: 0.5rem;">{{ $responsibility }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Complete Heirs Distribution Table -->
    @if(count($heirsList) > 0)
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            <h2 class="card-title">Complete Faraid Heirs Distribution</h2>
            <span class="card-badge">{{ count($heirsList) }} Heir(s)</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Heir Name</th>
                            <th>Relationship</th>
                            <th>Share Percentage</th>
                            <th>Inheritance Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($heirsList as $heir)
                        <tr>
                            <td><strong>{{ $heir['name'] }}</strong></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $heir['relationship'])) }}</span></td>
                            <td><strong>{{ number_format($heir['percentage'], 2) }}%</strong></td>
                            <td style="color: var(--success-color); font-weight: 700;">RM {{ number_format($heir['amount'], 2) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>{{ number_format($totalHeirPct, 2) }}%</strong></td>
                            <td style="color: var(--primary-color);"><strong>RM {{ number_format($remainingForHeirs, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if(abs($totalHeirPct - 100) > 0.01 && $totalHeirPct < 100)
                <div class="alert-banner warning" style="margin-top: 1rem; margin-bottom: 0;">
                    <div class="alert-banner-content">
                        <svg class="alert-banner-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Note: Total distribution does not equal 100%. Remaining {{ number_format(100 - $totalHeirPct, 2) }}% will be distributed to Baitulmal (Public Treasury).</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Complete Wasiyyah Distribution Table -->
    @if(count($wasiyyahList) > 0)
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <h2 class="card-title">Wasiyyah Beneficiaries</h2>
            <span class="card-badge">{{ count($wasiyyahList) }} Beneficiary(s)</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Beneficiary Name</th>
                            <th>Relationship</th>
                            <th>Requested Percentage</th>
                            <th>Wasiyyah Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wasiyyahList as $item)
                        <tr>
                            <td><strong>{{ $item['name'] }}</strong></td>
                            <td>{{ ucfirst($item['relationship']) }}</span></td>
                            <td><strong>{{ number_format($item['percentage'], 2) }}%</strong></td>
                            <td style="color: var(--info-color); font-weight: 700;">RM {{ number_format($item['amount'], 2) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total Wasiyyah</strong></td>
                            <td><strong>{{ number_format($totalWasiyyahPct, 2) }}%</strong></td>
                            <td style="color: var(--primary-color);"><strong>RM {{ number_format($wasiyyahAmount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if($totalWasiyyahPct > 33.33)
                <div class="alert-banner warning" style="margin-top: 1rem; margin-bottom: 0;">
                    <div class="alert-banner-content">
                        <svg class="alert-banner-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>⚠️ Total wasiyyah exceeds 33.33% limit. Only 1/3 of net estate can be distributed via wasiyyah. Excess requires consent from all faraid heirs.</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Assets List (if available) -->
    @if(isset($assetsList) && count($assetsList) > 0)
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h2 class="card-title">Estate Assets</h2>
            <span class="card-badge">{{ count($assetsList) }} Asset(s)</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Asset Name</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Ownership</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assetsList as $asset)
                        <tr>
                            <td><strong>{{ $asset['name'] }}</strong></td>
                            <td>{{ $asset['type'] }}</td>
                            <td>RM {{ number_format($asset['value'], 2) }}</td>
                            <td>{{ $asset['ownership_percentage'] }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total Assets</strong></td>
                            <td colspan="2"><strong>RM {{ number_format($totalAssets, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Debts List (if available) -->
    @if(isset($debtsList) && count($debtsList) > 0)
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h2 class="card-title">Estate Debts</h2>
            <span class="card-badge">{{ count($debtsList) }} Debt(s)</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Creditor</th>
                            <th>Type</th>
                            <th>Total Amount</th>
                            <th>Remaining</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($debtsList as $debt)
                        <tr>
                            <td><strong>{{ $debt['creditor_name'] }}</strong></td>
                            <td>{{ $debt['type'] }}</td>
                            <td>RM {{ number_format($debt['amount'], 2) }}</td>
                            <td class="{{ $debt['remaining'] > 0 ? 'text-danger' : 'text-success' }}">
                                RM {{ number_format($debt['remaining'], 2) }}
                            </td>
                            <td>
                                <span class="badge {{ $debt['remaining'] <= 0 ? 'badge-success' : 'badge-warning' }}">
                                    {{ $debt['remaining'] <= 0 ? 'Settled' : 'Pending' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total Debts</strong></td>
                            <td><strong>RM {{ number_format($totalDebts, 2) }}</strong></td>
                            <td colspan="2"><strong>RM {{ number_format(max(0, $totalDebts - ($debtsPaid ?? 0)), 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Trustee Information -->
    @if($trusteeName)
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h2 class="card-title">Trustee Information</h2>
            <span class="card-badge">Estate Administrator</span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-label">Trustee Name</div>
                <div class="detail-value">{{ $trusteeName }}</div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">Contact Email</div>
                <div class="detail-value">{{ $trusteeEmail ?? 'Not provided' }}</div>
            </div>
            <div class="detail-grid">
                <div class="detail-label">Contact Phone</div>
                <div class="detail-value">{{ $trusteePhone ?? 'Not provided' }}</div>
            </div>
            <div class="info-card" style="margin-bottom: 0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>For any questions regarding the estate distribution, please contact the appointed trustee.</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Video Section (if available) -->
    @if(isset($hasVideo) && $hasVideo)
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <h2 class="card-title">Wasiyyah Video</h2>
            <span class="card-badge">Final Wishes</span>
        </div>
        <div class="card-body">
            <div style="text-align: center;">
                @if(isset($willVideoUrl) && $willVideoUrl)
                    @if(str_contains($willVideoUrl, 'youtube.com') || str_contains($willVideoUrl, 'youtu.be'))
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; margin-bottom: 1rem;">
                            <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: var(--border-radius-md);" 
                                    src="{{ str_replace('watch?v=', 'embed/', $willVideoUrl) }}" 
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="button-group">
                            <a href="{{ $willVideoUrl }}" class="btn btn-video" target="_blank">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Watch on YouTube
                            </a>
                        </div>
                    @else
                        <video controls style="width: 100%; max-width: 600px; border-radius: var(--border-radius-md); margin-bottom: 1rem;">
                            <source src="{{ $willVideoUrl }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="button-group">
                            <a href="{{ $willVideoUrl }}" class="btn btn-video" target="_blank" download>
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download Will Video
                            </a>
                        </div>
                    @endif
                @endif
                <p style="color: var(--text-light); font-size: 0.875rem; margin-top: 1rem;">
                    <strong>Note:</strong> This video contains the final wishes of the deceased.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="glass-card no-print">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h2 class="card-title">Actions</h2>
            <span class="card-badge">Download & View</span>
        </div>
        <div class="card-body">
            <div class="button-group">
                <button onclick="downloadPDF();" class="btn btn-pdf">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download as PDF
                </button>
                <button onclick="window.print();" class="btn btn-secondary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="security-notice no-print">
        <div class="security-notice-content">
            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2.5 5.5A2.5 2.5 0 015 3h10a2.5 2.5 0 012.5 2.5v9a2.5 2.5 0 01-2.5 2.5H5a2.5 2.5 0 01-2.5-2.5v-9zM6 6.5v1a1 1 0 001 1h6a1 1 0 001-1v-1a1 1 0 00-1-1H7a1 1 0 00-1 1z" clip-rule="evenodd"/>
            </svg>
            <span>This is a secure connection. Your access has been logged for security purposes.</span>
            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>
            <span>This link expires on <strong>{{ $expiryDate }}</strong>.</span>
        </div>
    </div>
</div>

<!-- HIDDEN PDF CONTENT -->
<div id="pdfContent" style="display:none;">
    <div style="padding:20px;font-family:'Poppins',sans-serif;max-width:800px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #1a5fb4;padding-bottom:15px;">
            <h1 style="color:#1a5fb4;font-size:22px;margin:0 0 5px 0;">Estate Distribution Statement</h1>
            <p style="color:#64748b;font-size:12px;margin:0;">Reference: {{ $estate->unique_id ?? 'N/A' }} | Status: {{ ucfirst($estate->status ?? 'N/A') }} | Generated: {{ now()->format('d M Y') }}</p>
        </div>

        <!-- Deceased Information -->
        <div class="pdf-section-title">Deceased Information</div>
        <table class="pdf-info-table">
            <tr><td><strong>Name</strong></td><td>{{ $estate->deceased_name ?? 'N/A' }}</td></tr>
            <tr><td><strong>NRIC</strong></td><td>{{ $estate->deceased_nric ?? 'N/A' }}</td></tr>
            <tr><td><strong>Date of Birth</strong></td><td>{{ $estate->date_of_birth ? $estate->date_of_birth->format('d M Y') : 'N/A' }}</td></tr>
            <tr><td><strong>Date of Death</strong></td><td>{{ $estate->date_of_death ? $estate->date_of_death->format('d M Y') : 'N/A' }}</td></tr>
            <tr><td><strong>Gender</strong></td><td>{{ $estate->gender === 'male' ? 'Male' : 'Female' }}</td></tr>
            <tr><td><strong>Contact</strong></td><td>{{ $estate->contact_email ?? 'N/A' }} | {{ $estate->contact_phone ?? 'N/A' }}</td></tr>
        </table>

        <!-- Financial Summary -->
        <div class="pdf-section-title">Financial Summary</div>
        <div style="display:flex;gap:15px;margin-bottom:15px;">
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366;font-size:16px;">RM {{ number_format($totalAssets, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545;font-size:16px;">RM {{ number_format($totalDebts, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4;font-size:16px;">RM {{ number_format($netEstate, 2) }}</span></div>
        </div>

        <!-- Assets -->
        @if(isset($assetsList) && count($assetsList) > 0)
            <div class="pdf-section-title">Assets ({{ count($assetsList) }})</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Asset Name</strong></th><th><strong>Type</strong></th><th><strong>Value</strong></th><th><strong>Ownership</strong></th></tr></thead>
                <tbody>
                    @foreach($assetsList as $asset)
                    <tr><td>{{ $asset['name'] }}</td><td>{{ $asset['type'] }}</td><td>RM {{ number_format($asset['value'], 2) }}</td><td>{{ $asset['ownership_percentage'] }}%</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total Assets</td><td colspan="2">RM {{ number_format($totalAssets, 2) }}</td></tr>
                </tbody>
            </table>
        @endif

        <!-- Debts -->
        @if(isset($debtsList) && count($debtsList) > 0)
            <div class="pdf-section-title">Debts ({{ count($debtsList) }})</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Creditor</strong></th><th><strong>Type</strong></th><th><strong>Amount</strong></th><th><strong>Remaining</strong></th></tr></thead>
                <tbody>
                    @foreach($debtsList as $debt)
                    <tr><td>{{ $debt['creditor_name'] }}</td><td>{{ $debt['type'] }}</td><td>RM {{ number_format($debt['amount'], 2) }}</td><td>RM {{ number_format($debt['remaining'], 2) }}</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total Debts</td><td colspan="2">RM {{ number_format($totalDebts, 2) }}</td></tr>
                </tbody>
            </table>
        @endif

        <!-- Heirs Distribution -->
        @if(count($heirsList) > 0)
            <div class="pdf-section-title">Faraid Heirs Distribution</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share %</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @foreach($heirsList as $heir)
                    <tr><td>{{ $heir['name'] }}</td><td>{{ ucfirst(str_replace('_', ' ', $heir['relationship'])) }}</td><td>{{ number_format($heir['percentage'], 2) }}%</td><td>RM {{ number_format($heir['amount'], 2) }}</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total Heirs Share</td><td>{{ number_format($totalHeirPct, 2) }}%</td><td>RM {{ number_format($remainingForHeirs, 2) }}</td></tr>
                </tbody>
            </table>
        @endif

        <!-- Wasiyyah Distribution -->
        @if(count($wasiyyahList) > 0)
            <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share %</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @foreach($wasiyyahList as $item)
                    <tr><td>{{ $item['name'] }}</td><td>{{ ucfirst($item['relationship']) }}</td><td>{{ number_format($item['percentage'], 2) }}%</td><td>RM {{ number_format($item['amount'], 2) }}</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total Wasiyyah</td><td>{{ number_format($totalWasiyyahPct, 2) }}%</td><td>RM {{ number_format($wasiyyahAmount, 2) }}</td></tr>
                </tbody>
            </table>
        @endif

        <!-- Your Distribution Details - PDF -->
        @if($distribution && ($isHeir || $isWasiyyah))
            <div class="pdf-section-title">Your Distribution Details</div>
            <div class="pdf-info-card" style="background: #e8f1fd;">
                <p><strong>Your Name:</strong> {{ $distribution['name'] }}</p>
                <p><strong>Relationship:</strong> {{ ucfirst($distribution['type'] === 'heir' ? ucfirst(str_replace('_', ' ', $distribution['relationship'])) : $distribution['relationship']) }}</p>
                <p><strong>Share Percentage:</strong> {{ number_format($distribution['type'] === 'heir' ? $distribution['share_percentage'] : $distribution['requested_percentage'], 2) }}%</p>
                <p><strong style="color:#25D366;font-size:16px;">Your Inheritance Amount:</strong> <strong style="color:#25D366;font-size:16px;">{{ $distribution['formatted_amount'] }}</strong></p>
            </div>
        @endif

        <!-- Trustee Information -->
        @if($trusteeName)
            <div class="pdf-section-title">Trustee Information</div>
            <table class="pdf-info-table">
                <tr><td><strong>Trustee Name</strong></td><td>{{ $trusteeName }}</td></tr>
                <tr><td><strong>Email</strong></td><td>{{ $trusteeEmail ?? 'Not provided' }}</td></tr>
                <tr><td><strong>Phone</strong></td><td>{{ $trusteePhone ?? 'Not provided' }}</td></tr>
            </table>
        @endif

        <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:10px;color:#94a3b8;text-align:center;">
            <p>This is an official estate distribution statement. Generated on {{ now()->format('d F Y, h:i A') }} | Document ID: {{ $estate->unique_id ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<script>
function downloadPDF() {
    const pdfContent = document.getElementById('pdfContent');
    if (!pdfContent) {
        alert('PDF content not found.');
        return;
    }
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    if (!printWindow) {
        alert('Please allow pop-ups to download the PDF.');
        return;
    }
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Estate Distribution - {{ $estate->deceased_name ?? 'PDF' }}</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
            <style>
                * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
                body { padding: 20px; font-size: 11pt; color: #333; }
                .pdf-section-title { font-size: 1rem; font-weight: 700; color: #1a5fb4; margin: 1rem 0 0.5rem 0; padding-bottom: 0.3rem; border-bottom: 2px solid #e8f1fd; }
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
}

document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.glass-card:not(.no-print), .summary-card, .welcome-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection