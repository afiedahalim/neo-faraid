

<?php $__env->startSection('title', 'Estate Planning'); ?>

<?php $__env->startSection('content'); ?>
<!-- Poppins Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    /* ===== All existing styles remain unchanged ===== */
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
    
    body, html { font-family: 'Poppins', sans-serif !important; }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
        color: var(--text-primary);
        overflow-x: hidden;
    }
    
    input, select, textarea, button, .btn, .form-control, .modal, .alert {
        font-family: 'Poppins', sans-serif !important;
    }
    
    /* ===== Hero Header ===== */
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
    
    /* ===== Glass Container ===== */
    .glass-container {
        max-width: 1400px;
        margin: -3rem auto 3rem;
        padding: 0 2rem;
        position: relative;
        z-index: 10;
    }
    
    /* ===== Dashboard Cards ===== */
    .estate-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        transition: var(--transition);
        margin-bottom: 2rem;
    }
    
    .estate-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl), 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    .estate-card-header {
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
        padding: 1.75rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .estate-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .estate-title h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    .estate-status {
        padding: 0.5rem 1rem;
        background: var(--primary-light);
        color: var(--primary-color);
        border-radius: var(--border-radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid rgba(26, 95, 180, 0.2);
    }
    
    .status-draft { background: #e2e8f0; color: #475569; border-color: #cbd5e1; }
    .status-activated { background: #d4edda; color: #155724; border-color: #c3e6cb; }
    .status-completed { background: #cce5ff; color: #004085; border-color: #b8daff; }
    .status-executed { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
    
    .estate-meta {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin-top: 0.5rem;
    }
    
    .estate-card-body {
        padding: 2rem;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: var(--gray-50);
        border-radius: var(--border-radius-md);
        padding: 1rem;
        text-align: center;
        border: 1px solid var(--gray-200);
        transition: var(--transition);
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    
    .stat-number {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin-top: 0.25rem;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .info-label {
        font-weight: 600;
        color: var(--gray-700);
    }
    
    .btn-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
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
        gap: 0.5rem;
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
    
    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: var(--white);
    }
    
    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.2);
    }
    
    .btn-warning {
        background: var(--warning-color);
        color: #856404;
    }
    
    .btn-warning:hover {
        background: #e0a800;
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background: var(--danger-color);
        color: var(--white);
    }
    
    .btn-danger:hover {
        background: #c82333;
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
    
    .btn-pdf {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }
    
    .btn-pdf:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
    }
    
    .btn-icon {
        width: 18px;
        height: 18px;
        transition: var(--transition);
    }
    
    .btn:hover .btn-icon {
        transform: translateX(2px);
    }
    
    .btn-secondary:hover .btn-icon {
        transform: translateX(-2px);
    }
    
    /* ===== Alerts ===== */
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
    }
    
    .alert-close:hover {
        background: var(--gray-100);
        color: var(--gray-700);
    }
    
    .modern-alert.success { border-left-color: var(--success-color); }
    .modern-alert.warning { border-left-color: var(--warning-color); }
    .modern-alert.error { border-left-color: var(--danger-color); }
    .modern-alert.info { border-left-color: var(--primary-color); }
    
    /* ===== Loading Overlay ===== */
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
    
    /* ===== Confirmation Modal ===== */
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
    
    /* ===== Empty State ===== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .empty-state svg {
        color: var(--gray-400);
        margin-bottom: 1rem;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: var(--gray-500);
        margin-bottom: 1.5rem;
    }
    
    /* ===== PDF Hidden Content ===== */
    .pdf-hidden-content {
        display: none;
    }
    
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
    
    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .estate-header .hero-title { font-size: 2rem; }
        .estate-header { min-height: 35vh !important; padding: 2rem 1rem; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -2rem; }
        .estate-card-header { flex-direction: column; align-items: flex-start; }
        .summary-grid { grid-template-columns: 1fr 1fr; }
        .btn-group { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
        .modern-alert-container { left: 20px; right: 20px; max-width: none; }
        .modern-alert { min-width: auto; width: 100%; }
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
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- Alert Container -->
<div class="modern-alert-container" id="alertContainer"></div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Processing...</div>
        <div class="loading-subtext" id="loadingSubtext">Please wait</div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="confirmation-modal-overlay" id="confirmationModal">
    <div class="confirmation-modal">
        <div class="confirmation-modal-header">
            <div class="confirmation-modal-icon" id="modalIcon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="confirmation-modal-title" id="modalTitle">Confirm Action</h3>
        </div>
        <div class="confirmation-modal-body">
            <p class="confirmation-modal-message" id="modalMessage">Are you sure you want to proceed?</p>
            <div id="modalItemName" style="margin-top: 0.5rem;"></div>
        </div>
        <div class="confirmation-modal-footer">
            <button class="btn btn-outline" id="modalCancelBtn">Cancel</button>
            <button class="btn btn-danger" id="modalConfirmBtn" style="background: var(--danger-color); color: white;">Confirm</button>
        </div>
    </div>
</div>

<!-- Hero Header -->
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
            <span class="hero-highlight">Estate</span> Planning
        </h1>
        <p class="hero-subtitle">
            Manage your digital estate planning documents. View, edit, activate, or delete your estate plans.
        </p>
    </div>
</header>

<div class="glass-container">
    <?php if(session('success')): ?>
        <div class="modern-alert success show" style="margin-bottom: 1rem; transform: none; opacity: 1;">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <div class="alert-title">Success</div>
                <div class="alert-message"><?php echo e(session('success')); ?></div>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="modern-alert error show" style="margin-bottom: 1rem; transform: none; opacity: 1;">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <div class="alert-title">Error</div>
                <div class="alert-message"><?php echo e(session('error')); ?></div>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
    <?php endif; ?>
    <?php if(session('info')): ?>
        <div class="modern-alert info show" style="margin-bottom: 1rem; transform: none; opacity: 1;">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="alert-content">
                <div class="alert-title">Information</div>
                <div class="alert-message"><?php echo e(session('info')); ?></div>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
        </div>
    <?php endif; ?>

    <?php
        $userEstates = \App\Models\EstatePreRegistration::where('user_id', Auth::id())
            ->with(['heirs', 'assets', 'debts', 'wasiyyah'])
            ->orderBy('created_at', 'desc')
            ->get();
    ?>

    <?php if($userEstates->isEmpty()): ?>
        <div class="empty-state">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3>No Estate Plans Yet</h3>
            <p>Start your digital estate planning journey today.</p>
            <a href="<?php echo e(route('estate-setup.create')); ?>" class="btn btn-primary">Create Your First Estate Plan</a>
        </div>
    <?php else: ?>
        <?php $__currentLoopData = $userEstates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $totalAssets = $estate->assets->sum('value') ?? 0;
                $totalDebts = $estate->debts->sum('amount') ?? 0;
                $netEstate = max(0, $totalAssets - $totalDebts);
                $heirsCount = $estate->heirs->count();
                $wasiyyahCount = $estate->wasiyyah->count();
                $assetsCount = $estate->assets->count();
                $debtsCount = $estate->debts->count();
                $statusClass = match($estate->status) {
                    'activated' => 'status-activated',
                    'completed' => 'status-completed',
                    'executed' => 'status-executed',
                    default => 'status-draft'
                };
                $statusLabel = ucfirst($estate->status);
                $canActivate = $estate->status === 'draft' && $heirsCount > 0 && $assetsCount > 0 && $netEstate > 0;
            ?>
            <div class="estate-card" data-id="<?php echo e($estate->unique_id); ?>">
                <div class="estate-card-header">
                    <div class="estate-title">
                        <h2><?php echo e($estate->deceased_name ?? 'Unnamed Estate'); ?></h2>
                        <span class="estate-status <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span>
                    </div>
                    <div class="estate-meta">
                        Created: <?php echo e($estate->created_at->format('d M Y')); ?>

                        <?php if($estate->activated_at): ?> | Activated: <?php echo e($estate->activated_at->format('d M Y')); ?> <?php endif; ?>
                    </div>
                </div>
                <div class="estate-card-body">
                    <div class="summary-grid">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo e($heirsCount); ?></div>
                            <div class="stat-label">Heirs</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?php echo e($assetsCount); ?></div>
                            <div class="stat-label">Assets</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?php echo e($debtsCount); ?></div>
                            <div class="stat-label">Debts</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?php echo e($wasiyyahCount); ?></div>
                            <div class="stat-label">Wasiyyah</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">RM <?php echo e(number_format($netEstate, 2)); ?></div>
                            <div class="stat-label">Net Estate</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <span class="info-label">NRIC:</span>
                        <span><?php echo e($estate->deceased_nric ?? 'Not provided'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trustee:</span>
                        <span><?php echo e($estate->trustee_name ?? 'Not appointed'); ?></span>
                    </div>
                    <?php if($estate->status === 'activated'): ?>
                        <div class="info-row">
                            <span class="info-label">Access Token:</span>
                            <span><code><?php echo e($estate->access_token); ?></code></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Token Expires:</span>
                            <span><?php echo e($estate->token_expires_at ? $estate->token_expires_at->format('d M Y') : 'Never'); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="btn-group">
                        <!-- Download PDF - always available -->
                        <button class="btn btn-pdf" onclick="downloadEstatePDF('<?php echo e($estate->unique_id); ?>')">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download as PDF
                        </button>

                        <!-- Edit Plan - uses the correct route -->
                        <a href="<?php echo e(route('estate-setup.edit', $estate->unique_id)); ?>" class="btn btn-primary">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Edit Plan
                        </a>

                        <?php if($estate->status === 'draft'): ?>
                            <?php if($canActivate): ?>
                                <button class="btn btn-success activate-btn" data-id="<?php echo e($estate->unique_id); ?>">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Activate Plan
                                </button>
                            <?php else: ?>
                                <button class="btn btn-outline" disabled style="opacity:0.5;">
                                    ⚠️ Cannot Activate (Missing Heirs/Assets or Negative Net)
                                </button>
                            <?php endif; ?>
                        <?php elseif($estate->status === 'activated'): ?>
                            <button class="btn btn-warning deactivate-btn" data-id="<?php echo e($estate->unique_id); ?>">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Deactivate
                            </button>
                        <?php endif; ?>

                        <button class="btn btn-danger delete-btn" data-id="<?php echo e($estate->unique_id); ?>" data-name="<?php echo e($estate->deceased_name); ?>">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            
            <div id="pdfContent-<?php echo e($estate->unique_id); ?>" class="pdf-hidden-content">
                <div style="padding:20px;font-family:'Poppins',sans-serif;max-width:800px;margin:0 auto;">
                    <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #1a5fb4;padding-bottom:15px;">
                        <h1 style="color:#1a5fb4;font-size:22px;margin:0 0 5px 0;">Estate Distribution Statement</h1>
                        <p style="color:#64748b;font-size:12px;margin:0;">
                            Reference: <?php echo e($estate->unique_id); ?> | Status: <?php echo e(ucfirst($estate->status)); ?> | Generated: <?php echo e(now()->format('d M Y')); ?>

                        </p>
                    </div>

                    <!-- Deceased Information -->
                    <div class="pdf-section-title">Deceased Information</div>
                    <table class="pdf-info-table">
                        <tr><td><strong>Name</strong></td><td><?php echo e($estate->deceased_name ?? 'N/A'); ?></td></tr>
                        <tr><td><strong>NRIC/Passport</strong></td><td><?php echo e($estate->deceased_nric ?? 'N/A'); ?></td></tr>
                        <tr><td><strong>Gender</strong></td><td><?php echo e(ucfirst($estate->gender ?? 'N/A')); ?></td></tr>
                        <tr><td><strong>Date of Birth</strong></td><td><?php echo e($estate->date_of_birth ? $estate->date_of_birth->format('d F Y') : 'N/A'); ?></td></tr>
                        <tr><td><strong>Address</strong></td><td><?php echo e($estate->address ?? 'N/A'); ?></td></tr>
                    </table>

                    <!-- Financial Summary -->
                    <div class="pdf-section-title">Financial Summary</div>
                    <div style="display:flex;gap:15px;margin-bottom:15px;">
                        <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366;font-size:16px;">RM <?php echo e(number_format($totalAssets, 2)); ?></span></div>
                        <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545;font-size:16px;">RM <?php echo e(number_format($totalDebts, 2)); ?></span></div>
                        <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4;font-size:16px;">RM <?php echo e(number_format($netEstate, 2)); ?></span></div>
                    </div>

                    <!-- Assets -->
                    <?php if($assetsCount > 0): ?>
                    <div class="pdf-section-title">Assets (<?php echo e($assetsCount); ?>)</div>
                    <table class="pdf-info-table">
                        <thead><tr style="background:#f8fafc;"><th><strong>Asset Name</strong></th><th><strong>Value (RM)</strong></th><th><strong>Ownership</strong></th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $estate->assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($asset->name ?? 'N/A'); ?></td>
                                <td>RM <?php echo e(number_format($asset->value ?? 0, 2)); ?></td>
                                <td><?php echo e($asset->ownership_percentage ?? 100); ?>%</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr style="background:#f1f5f9;font-weight:700;"><td colspan="1">Total Assets</td><td colspan="2">RM <?php echo e(number_format($totalAssets, 2)); ?></td></tr>
                        </tbody>
                    </table>
                    <?php endif; ?>

                    <!-- Debts -->
                    <?php if($debtsCount > 0): ?>
                    <div class="pdf-section-title">Debts (<?php echo e($debtsCount); ?>)</div>
                    <table class="pdf-info-table">
                        <thead><tr style="background:#f8fafc;"><th><strong>Creditor</strong></th><th><strong>Type</strong></th><th><strong>Amount (RM)</strong></th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $estate->debts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($debt->creditor_name ?? 'N/A'); ?></td>
                                <td><?php echo e($debt->debt_type ?? $debt->type ?? 'Other'); ?></td>
                                <td>RM <?php echo e(number_format($debt->amount ?? 0, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total Debts</td><td>RM <?php echo e(number_format($totalDebts, 2)); ?></td></tr>
                        </tbody>
                    </table>
                    <?php endif; ?>

                    <!-- Faraid Heirs Distribution -->
                    <?php if($heirsCount > 0): ?>
                    <?php
                        $totalHeirPct = $estate->heirs->sum('share_percentage');
                        $remainingForHeirs = $netEstate - (($estate->wasiyyah->sum('requested_percentage') / 100) * $netEstate);
                    ?>
                    <div class="pdf-section-title">Faraid Heirs Distribution</div>
                    <table class="pdf-info-table">
                        <thead><tr style="background:#f8fafc;"><th><strong>Heir Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share %</strong></th><th><strong>Amount (RM)</strong></th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $estate->heirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $heirAmount = ($heir->share_percentage / 100) * $remainingForHeirs;
                            ?>
                            <tr>
                                <td><?php echo e($heir->name ?? 'N/A'); ?></td>
                                <td><?php echo e(ucfirst(str_replace('_', ' ', $heir->relationship ?? 'N/A'))); ?></td>
                                <td><?php echo e(number_format($heir->share_percentage, 2)); ?>%</td>
                                <td>RM <?php echo e(number_format($heirAmount, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr style="background:#f1f5f9;font-weight:700;">
                                <td colspan="2">Total</td>
                                <td><?php echo e(number_format($totalHeirPct, 2)); ?>%</td>
                                <td>RM <?php echo e(number_format($remainingForHeirs, 2)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php endif; ?>

                    <!-- Wasiyyah Beneficiaries -->
                    <?php if($wasiyyahCount > 0): ?>
                    <?php
                        $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage');
                        $wasiyyahAmount = ($totalWasiyyahPct / 100) * $netEstate;
                    ?>
                    <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
                    <table class="pdf-info-table">
                        <thead><tr style="background:#f8fafc;"><th><strong>Beneficiary Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share %</strong></th><th><strong>Amount (RM)</strong></th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $estate->wasiyyah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $itemAmount = ($item->requested_percentage / 100) * $netEstate;
                            ?>
                            <tr>
                                <td><?php echo e($item->beneficiary_name ?? 'N/A'); ?></td>
                                <td><?php echo e(ucfirst($item->relationship ?? 'N/A')); ?></td>
                                <td><?php echo e(number_format($item->requested_percentage, 2)); ?>%</td>
                                <td>RM <?php echo e(number_format($itemAmount, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr style="background:#f1f5f9;font-weight:700;">
                                <td colspan="2">Total Wasiyyah</td>
                                <td><?php echo e(number_format($totalWasiyyahPct, 2)); ?>%</td>
                                <td>RM <?php echo e(number_format($wasiyyahAmount, 2)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php endif; ?>

                    <!-- Trustee Information -->
                    <?php if($estate->trustee_name): ?>
                    <div class="pdf-section-title">Trustee Information</div>
                    <table class="pdf-info-table">
                        <tr><td><strong>Trustee Name</strong></td><td><?php echo e($estate->trustee_name); ?></td></tr>
                        <tr><td><strong>Relationship</strong></td><td><?php echo e($estate->trustee_relationship ?? 'N/A'); ?></td></tr>
                        <?php if($estate->trustee_nric): ?><tr><td><strong>NRIC/Passport</strong></td><td><?php echo e($estate->trustee_nric); ?></td></tr><?php endif; ?>
                        <tr><td><strong>Email</strong></td><td><?php echo e($estate->trustee_email ?? 'Not provided'); ?></td></tr>
                        <?php if($estate->trustee_phone): ?><tr><td><strong>Phone</strong></td><td><?php echo e($estate->trustee_phone); ?></td></tr><?php endif; ?>
                    </table>
                    <?php endif; ?>

                    <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:10px;color:#94a3b8;text-align:center;">
                        <p>This is an official estate distribution statement. Generated on <?php echo e(now()->format('d F Y, h:i A')); ?> | Document ID: <?php echo e($estate->unique_id); ?></p>
                        <p>This document is computer-generated and does not require a signature.</p>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>

<script>
    // Modern Alert System
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
                <button class="alert-close" onclick="ModernAlert.close('${alertId}')">✕</button>
            `;
            container.appendChild(alertEl);
            setTimeout(() => alertEl.classList.add('show'), 10);
            if (duration > 0) setTimeout(() => ModernAlert.close(alertId), duration);
            return alertId;
        }
        static close(alertId) {
            const alertEl = document.getElementById(alertId);
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('hide');
                setTimeout(() => alertEl.remove(), 500);
            }
        }
        static success(message, title = 'Success') { return this.show({ type: 'success', title, message }); }
        static error(message, title = 'Error') { return this.show({ type: 'error', title, message, duration: 5000 }); }
        static warning(message, title = 'Warning') { return this.show({ type: 'warning', title, message }); }
        static info(message, title = 'Information') { return this.show({ type: 'info', title, message }); }
    }
    window.ModernAlert = ModernAlert;

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // Loading overlay
    function showLoading(text = 'Processing...', subtext = 'Please wait') {
        const overlay = document.getElementById('loadingOverlay');
        document.getElementById('loadingText').textContent = text;
        document.getElementById('loadingSubtext').textContent = subtext;
        if (overlay) overlay.classList.add('active');
    }
    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.remove('active');
    }

    // Confirmation modal
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
            confirmBtn.style.color = 'white';
        } else {
            modalIcon.innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`;
            confirmBtn.style.background = 'var(--warning-color)';
            confirmBtn.style.color = '#856404';
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

    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Activate buttons
    document.querySelectorAll('.activate-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            const estateId = this.dataset.id;
            showConfirmationModal({
                title: 'Activate Estate Plan',
                message: 'Are you sure you want to activate this estate plan? It will be submitted for admin approval and your heirs will be notified.',
                iconType: 'warning',
                onConfirm: async () => {
                    showLoading('Activating Estate Plan...', 'Please wait');
                    try {
                        const response = await fetch(`/estate-setup/${estateId}/activate`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            ModernAlert.success('Estate plan activated successfully! Pending admin approval.', 'Activated');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            ModernAlert.error(data.message || 'Activation failed. Please check requirements.', 'Activation Failed');
                        }
                    } catch (err) {
                        ModernAlert.error('Network error. Please try again.', 'Error');
                    } finally {
                        hideLoading();
                    }
                }
            });
        });
    });

    // Deactivate buttons
    document.querySelectorAll('.deactivate-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            const estateId = this.dataset.id;
            showConfirmationModal({
                title: 'Deactivate Estate Plan',
                message: 'Deactivating will revoke access tokens and make the plan inactive. Are you sure?',
                iconType: 'danger',
                onConfirm: async () => {
                    showLoading('Deactivating...', 'Please wait');
                    try {
                        const response = await fetch(`/estate-setup/${estateId}/deactivate`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            ModernAlert.success('Estate plan deactivated successfully.', 'Deactivated');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            ModernAlert.error(data.message || 'Deactivation failed.', 'Error');
                        }
                    } catch (err) {
                        ModernAlert.error('Network error.', 'Error');
                    } finally {
                        hideLoading();
                    }
                }
            });
        });
    });

    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            const estateId = this.dataset.id;
            const estateName = this.dataset.name || 'this estate';
            showConfirmationModal({
                title: 'Delete Estate Plan',
                message: `Permanently delete "${estateName}" and all related data? This action cannot be undone.`,
                iconType: 'danger',
                onConfirm: async () => {
                    showLoading('Deleting...', 'Please wait');
                    try {
                        const response = await fetch(`/estate-setup/${estateId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            ModernAlert.success('Estate plan deleted successfully.', 'Deleted');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            ModernAlert.error(data.message || 'Deletion failed.', 'Error');
                        }
                    } catch (err) {
                        ModernAlert.error('Network error.', 'Error');
                    } finally {
                        hideLoading();
                    }
                }
            });
        });
    });

    // PDF Download Function
    window.downloadEstatePDF = function(estateId) {
        const pdfContent = document.getElementById(`pdfContent-${estateId}`);
        if (!pdfContent) {
            ModernAlert.error('PDF content not found.', 'Error');
            return;
        }

        const printWindow = window.open('', '_blank', 'width=800,height=600');
        if (!printWindow) {
            ModernAlert.error('Please allow pop-ups to download the PDF.', 'Pop-up Blocked');
            return;
        }

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Estate Plan - ${escapeHtml(document.querySelector(`.estate-card[data-id="${estateId}"] .estate-title h2`)?.innerText || 'Estate')}</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
                <style>
                    * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
                    body { padding: 20px; font-size: 11pt; color: #333; background: white; }
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
    };
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/estate-setup/index.blade.php ENDPATH**/ ?>