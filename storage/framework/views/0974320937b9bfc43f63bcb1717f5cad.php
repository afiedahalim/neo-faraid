

<?php $__env->startSection('title', 'Instant Estate History'); ?>

<?php if(!Auth::check()): ?>
    <script>
        window.location.href = "<?php echo e(route('login')); ?>";
    </script>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<!-- Add Poppins font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<style>
    /* ===== CSS VARIABLES & RESET ===== */
    :root {
        --primary-color: #1a5fb4;
        --primary-dark: #0d2d5c;
        --primary-light: #e8f1fd;
        --secondary-color: #2d7ad6;
        --accent-color: #ffd700;
        --accent-light: #ffed4e;
        --success-color: #28a745;
        --success-dark: #218838;
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
    
    /* ===== HISTORY HEADER ===== */
    .history-header {
        min-height: 35vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 3rem 2rem;
    }

    .history-header .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .history-header .animated-bg .bg-circle-1 {
        position: absolute;
        top: 10%;
        right: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }

    .history-header .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .history-header .animated-bg .bg-circle-3 {
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .history-header .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }

    .history-header .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .history-header .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    .history-header .shape-1 { width: 40px; height: 40px; top: 20%; left: 10%; animation-name: float-1; }
    .history-header .shape-2 { width: 25px; height: 25px; top: 60%; left: 85%; animation-name: float-2; animation-delay: 1s; }
    .history-header .shape-3 { width: 35px; height: 35px; top: 75%; left: 15%; animation-name: float-3; animation-delay: 0.5s; }
    .history-header .shape-4 { width: 20px; height: 20px; top: 30%; left: 70%; animation-name: float-4; animation-delay: 1.5s; }

    .history-header .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 2rem;
    }

    .history-header .hero-kicker {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        display: inline-flex;
        padding: 0.75rem 1.75rem;
        border-radius: var(--border-radius-xl);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.2);
        transition: var(--transition);
    }

    .history-header .hero-kicker:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-2px);
    }

    .history-header .kicker-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .history-header .kicker-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 1rem;
    }

    .history-header .kicker-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        color: var(--accent-color);
    }

    .history-header .hero-title {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .history-header .hero-highlight {
        color: var(--accent-color);
        position: relative;
        display: inline-block;
    }

    .history-header .hero-highlight::after {
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

    .history-header .hero-subtitle {
        font-size: 1.125rem;
        max-width: 600px;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
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
    }
    
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl), 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
        padding: 1.75rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .card-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .card-header-icon { width: 32px; height: 32px; color: var(--primary-color); stroke-width: 2; }
    .card-title { font-size: 1.5rem; font-weight: 700; color: var(--dark); margin: 0; }
    .card-badge { padding: 0.5rem 1rem; background: var(--primary-light); color: var(--primary-color); border-radius: var(--border-radius-md); font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(26, 95, 180, 0.2); }
    .card-body { padding: 2rem; }
    
    /* ===== BUTTONS ===== */
    .btn {
        padding: 0.875rem 1.75rem;
        border: none;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        line-height: 1;
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
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg), var(--shadow-primary);
    }
    
    .btn-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: var(--white);
        transform: translateY(-2px);
    }
    
    .btn-outline-danger {
        background: transparent;
        border: 2px solid var(--danger-color);
        color: var(--danger-color);
    }
    
    .btn-outline-danger:hover {
        background: var(--danger-color);
        color: var(--white);
        transform: translateY(-2px);
    }
    
    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: var(--white);
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .btn-secondary {
        background: var(--white);
        color: var(--gray-700);
        border: 2px solid var(--gray-300);
    }
    
    .btn-secondary:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
        transform: translateY(-2px);
    }
    
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .btn-icon {
        width: 16px;
        height: 16px;
        transition: var(--transition);
    }
    
    .btn:hover .btn-icon {
        transform: translateX(2px);
    }
    
    /* ===== FORM INPUTS ===== */
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--gray-300);
        border-radius: var(--border-radius-sm);
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
        transition: var(--transition);
        background: var(--white);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
    }
    
    .form-input.error {
        border-color: var(--danger-color);
        background: var(--danger-light);
    }
    
    /* ===== TABLE STYLES ===== */
    .results-container {
        background: var(--white);
        border-radius: var(--border-radius-md);
        overflow-x: auto;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-md);
    }
    
    .results-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    
    .results-table thead {
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
        border-bottom: 1px solid var(--gray-200);
    }
    
    .results-table th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: var(--gray-800);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .results-table tbody tr {
        border-bottom: 1px solid var(--gray-100);
        transition: var(--transition);
    }
    
    .results-table tbody tr:hover {
        background: var(--gray-50);
    }
    
    .results-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .results-table td {
        padding: 1rem 1.5rem;
        color: var(--gray-700);
        font-weight: 500;
        vertical-align: middle;
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
    }
    
    .badge-primary {
        background: var(--primary-light);
        color: var(--primary-color);
        border: 1px solid rgba(26, 95, 180, 0.2);
    }
    
    .badge-success {
        background: var(--success-light);
        color: var(--success-dark);
        border: 1px solid rgba(40, 167, 69, 0.2);
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
    
    .badge-secondary {
        background: var(--gray-100);
        color: var(--gray-600);
        border: 1px solid var(--gray-200);
    }
    
    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: var(--gray-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-400);
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
    }
    
    .empty-subtitle {
        color: var(--gray-500);
        margin-bottom: 1.5rem;
    }
    
    /* ===== MODAL STYLES ===== */
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
        max-height: 90vh;
        overflow-y: auto;
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
    
    /* ===== INFO BOXES ===== */
    .info-box {
        background: var(--gray-50);
        border-radius: var(--border-radius-md);
        padding: 1.25rem;
        margin-top: 1.25rem;
    }
    
    .info-box-blue {
        background: var(--info-light);
        border-left: 3px solid var(--info-color);
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
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        border-left: 4px solid var(--primary-color);
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 300px;
    }
    
    .modern-alert.show {
        transform: translateX(0);
        opacity: 1;
    }
    
    .modern-alert.hide {
        transform: translateX(120%);
        opacity: 0;
    }
    
    .alert-icon { width: 20px; height: 20px; flex-shrink: 0; }
    .alert-content { flex: 1; }
    .alert-title { font-weight: 600; font-size: 0.9rem; color: var(--gray-900); margin-bottom: 0.25rem; }
    .alert-message { font-size: 0.8rem; color: var(--gray-600); line-height: 1.4; }
    
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
    
    .alert-close:hover { background: var(--gray-100); color: var(--gray-700); }
    
    .modern-alert.success { border-left-color: var(--success-color); background: rgba(40, 167, 69, 0.05); }
    .modern-alert.warning { border-left-color: var(--warning-color); background: rgba(255, 193, 7, 0.05); }
    .modern-alert.error { border-left-color: var(--danger-color); background: rgba(220, 53, 69, 0.05); }
    .modern-alert.info { border-left-color: var(--primary-color); background: rgba(26, 95, 180, 0.05); }
    
    /* Page alert (non-floating) */
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
    
    /* ===== DELETE WARNING BOX ===== */
    .delete-warning {
        background: var(--danger-light);
        border-left: 4px solid var(--danger-color);
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius-md);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--danger-color);
    }
    
    /* ===== PAGINATION ===== */
    .pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        flex-wrap: wrap;
    }
    
    .pagination .page-item .page-link {
        padding: 0.75rem 1.25rem;
        border: 1px solid var(--gray-200);
        background: var(--white);
        color: var(--gray-700);
        text-decoration: none;
        border-radius: var(--border-radius-sm);
        transition: var(--transition);
        font-weight: 500;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
        border-color: var(--primary-color);
    }
    
    .pagination .page-item .page-link:hover {
        background: var(--gray-100);
        transform: translateY(-2px);
    }
    
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* ===== ANIMATIONS ===== */
    @keyframes float-1 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-15px) rotate(120deg)}66%{transform:translateY(8px) rotate(240deg)} }
    @keyframes float-2 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-20px) rotate(90deg)}66%{transform:translateY(10px) rotate(180deg)} }
    @keyframes float-3 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-12px) rotate(60deg)}66%{transform:translateY(6px) rotate(120deg)} }
    @keyframes float-4 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-18px) rotate(150deg)}66%{transform:translateY(9px) rotate(300deg)} }
    @keyframes pulse { 0%,100%{opacity:0.7;transform:scale(1)}50%{opacity:1;transform:scale(1.1)} }
    @keyframes spin { to{transform:rotate(360deg)} }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)} }
    @keyframes shake { 0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)} }
    
    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 1024px) {
        .history-header .hero-title { font-size: 2.5rem; }
        .history-header { min-height: 30vh !important; padding: 3rem 1.5rem; }
        .glass-container { padding: 0 1.5rem 2rem; margin-top: -2rem; }
        .modern-alert { min-width: 300px; }
    }
    
    @media (max-width: 768px) {
        .history-header .hero-title { font-size: 2rem; }
        .history-header .hero-subtitle { font-size: 1rem; }
        .history-header { min-height: 30vh !important; padding: 2rem 1rem; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -1.5rem; }
        .card-header { flex-direction: column; align-items: flex-start; }
        .card-header-left { width: 100%; }
        .results-table th,
        .results-table td { padding: 0.75rem 1rem; }
        .btn { padding: 0.5rem 1rem; font-size: 0.75rem; }
        .modern-alert-container { left: 20px; right: 20px; max-width: none; }
        .modern-alert { min-width: auto; width: 100%; }
        .history-header .shape,
        .history-header .bg-circle-3 { display: none !important; }
    }
    
    @media (max-width: 480px) {
        .history-header .hero-title { font-size: 1.75rem; }
        .history-header .hero-subtitle { font-size: 0.9rem; }
        .history-header { min-height: 25vh !important; padding: 1.5rem 1rem; }
        .results-table th,
        .results-table td { padding: 0.5rem 0.75rem; }
        .btn { padding: 0.375rem 0.75rem; font-size: 0.7rem; }
        .badge { padding: 0.25rem 0.5rem; font-size: 0.65rem; }
        .pagination .page-item .page-link { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
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

<!-- Email Send Confirmation Modal -->
<div class="modal-overlay" id="sendEmailModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">📧 Send Report Link via Email</div>
            <button class="modal-close" id="closeSendEmailModal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="modalSessionId" value="">
            <p style="margin-bottom: 1rem; color: var(--gray-600);">
                Enter the email address to receive the report link.
            </p>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-800);">
                    Email Address <span style="color: var(--danger-color);">*</span>
                </label>
                <input type="email" 
                       id="sendEmailInput" 
                       class="form-input" 
                       placeholder="Enter recipient email address"
                       autocomplete="email">
                <small id="sendEmailError" style="color: var(--danger-color); display: none; margin-top: 0.25rem;">
                    Please enter a valid email address.
                </small>
            </div>
            <div class="info-box info-box-blue" style="margin-top: 0.5rem; padding: 0.75rem;">
                <p style="margin: 0; font-size: 0.8rem;">
                    <strong>Deceased:</strong> <span id="sendEmailDeceasedName"></span><br>
                    <strong>Session ID:</strong> <code id="sendEmailSessionIdDisplay" style="font-size: 0.75rem;"></code>
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelSendEmailBtn">Cancel</button>
            <button class="btn btn-success" id="confirmSendEmailBtn">Send Report Link</button>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL – ONLY ONE, CLEAN STRUCTURE -->
<div class="modal-overlay" id="deleteConfirmModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">🗑️ Delete Session</div>
            <button class="modal-close" id="closeDeleteModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="delete-warning">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <strong>Warning: This action cannot be undone!</strong>
            </div>
            <p style="margin-bottom: 1rem; color: var(--gray-600);">
                You are about to delete the following session:
            </p>
            <div class="info-box" style="margin-bottom: 1rem;">
                <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem;">
                    <strong>Filename:</strong> <span id="deleteFilename"></span>
                </p>
                <p style="margin: 0; font-size: 0.875rem;">
                    <strong>Session ID:</strong> <code style="font-size: 0.75rem;" id="deleteSessionId"></code>
                </p>
            </div>
            <p style="color: var(--danger-color); font-size: 0.875rem; font-weight: 500;">
                Are you sure you want to proceed with deletion?
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-outline-danger" id="confirmDeleteBtn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section class="history-header">
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
            <span class="hero-highlight">Instant Estate</span> History
        </h1>
        
        <p class="hero-subtitle">
            View and manage all your previously processed death certificates and inheritance calculations.
        </p>
    </div>
</section>

<!-- Main Container -->
<div class="glass-container">
    <div class="glass-card">
        <div class="card-header">
            <div class="card-header-left">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="card-title">Processing History</h2>
                <span class="card-badge"><?php echo e($sessions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $sessions->total() : $sessions->count()); ?> Total Records</span>
            </div>
            <a href="<?php echo e(route('instant-estate.index')); ?>" class="btn btn-primary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Upload
            </a>
        </div>
        
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="modern-alert success show page-alert">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="alert-content">
                        <div class="alert-title">Success</div>
                        <div class="alert-message"><?php echo e(session('success')); ?></div>
                    </div>
                    <button class="alert-close" onclick="this.closest('.modern-alert').remove()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="modern-alert error show page-alert">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="alert-content">
                        <div class="alert-title">Error</div>
                        <div class="alert-message"><?php echo e(session('error')); ?></div>
                    </div>
                    <button class="alert-close" onclick="this.closest('.modern-alert').remove()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if(session('warning')): ?>
                <div class="modern-alert warning show page-alert">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div class="alert-content">
                        <div class="alert-title">Warning</div>
                        <div class="alert-message"><?php echo e(session('warning')); ?></div>
                    </div>
                    <button class="alert-close" onclick="this.closest('.modern-alert').remove()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Sessions Table -->
            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Filename</th>
                            <th>Status</th>
                            <th>Admin Status</th>
                            <th>Deceased</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sessionsTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // ULTRA-STRICT UUID VALIDATION
                            $isValidUuid = false;
                            $sessionIdRaw = $session->session_id ?? null;
                            if (is_string($sessionIdRaw) && strlen($sessionIdRaw) === 36) {
                                $isValidUuid = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $sessionIdRaw);
                            }
                            // Reject literal "null" strings
                            if ($sessionIdRaw === 'null' || $sessionIdRaw === 'NULL') {
                                $isValidUuid = false;
                            }
                            
                            $safeSessionId = $isValidUuid ? $sessionIdRaw : null;
                        ?>
                        <tr>
                            <td>
                                <code style="background: var(--gray-100); padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.75rem;">
                                    <?php echo e($safeSessionId ? substr($safeSessionId, 0, 8) . '...' : 'N/A'); ?>

                                </code>
                            </td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo e($session->original_filename); ?>">
                                <?php echo e(Str::limit($session->original_filename ?? 'Unknown', 30)); ?>

                            </td>
                            <td>
                                <?php
                                    $statusColors = [
                                        'uploaded' => 'secondary',
                                        'processing_ocr' => 'info',
                                        'ocr_completed' => 'primary',
                                        'auto_filled' => 'success',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'warning',
                                        'redirected_to_manual' => 'warning',
                                    ];
                                    $color = $statusColors[$session->status] ?? 'secondary';
                                    
                                    $statusLabels = [
                                        'uploaded' => 'Uploaded',
                                        'processing_ocr' => 'Processing OCR',
                                        'ocr_completed' => 'OCR Complete',
                                        'auto_filled' => 'Auto-Filled',
                                        'completed' => 'Completed',
                                        'failed' => 'Failed',
                                        'cancelled' => 'Cancelled',
                                        'redirected_to_manual' => 'Manual Entry',
                                    ];
                                    $label = $statusLabels[$session->status] ?? ucfirst(str_replace('_', ' ', $session->status));
                                ?>
                                <span class="badge badge-<?php echo e($color); ?>">
                                    <?php echo e($label); ?>

                                </span>
                            </td>
                            <!-- Admin Status Column -->
                            <td>
                                <?php if($session->admin_status_label): ?>
                                    <span class="badge badge-<?php echo e($session->admin_status_color ?? 'secondary'); ?>">
                                        <?php echo e($session->admin_status_label); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($session->extracted_data['deceased_name'] ?? $session->deceased_name ?? 'N/A'); ?>

                                <?php if(!empty($session->extracted_data['deceased_nric']) || !empty($session->deceased_nric)): ?>
                                    <br>
                                    <small style="color: var(--gray-500); font-size: 0.7rem;">
                                        <?php echo e($session->extracted_data['deceased_nric'] ?? $session->deceased_nric); ?>

                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span title="<?php echo e($session->created_at ? $session->created_at->format('Y-m-d H:i:s') : ''); ?>">
                                    <?php echo e($session->created_at ? $session->created_at->diffForHumans() : 'N/A'); ?>

                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                                    <?php if($session->calculation_id): ?>
                                        <a href="<?php echo e(route('calculator.show', $session->calculation_id)); ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 0116 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>
                                    <?php elseif(in_array($session->status, ['ocr_completed', 'auto_filled']) && $safeSessionId): ?>
                                        <a href="<?php echo e(route('instant-estate.view-session', $safeSessionId)); ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Continue
                                        </a>
                                    <?php endif; ?>
                                    
                                    
                                    <?php if($session->admin_status === 'approved' && $isValidUuid && $safeSessionId): ?>
                                        <button type="button" 
                                                class="btn btn-success btn-sm send-report-btn"
                                                data-session-id="<?php echo e($safeSessionId); ?>"
                                                data-deceased-name="<?php echo e($session->deceased_name ?? $session->extracted_data['deceased_name'] ?? 'the deceased'); ?>"
                                                data-default-email="<?php echo e($session->contact_email ?? $session->guest_email ?? Auth::user()->email ?? ''); ?>">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Send Report Link
                                        </button>
                                    <?php endif; ?>
                                    
                                    
                                    <?php if(!$session->calculation_id && $isValidUuid && $safeSessionId): ?>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm delete-btn"
                                                data-session-id="<?php echo e($safeSessionId); ?>"
                                                data-filename="<?php echo e($session->original_filename ?? 'Unknown'); ?>"
                                                data-delete-url="<?php echo e(route('instant-estate.delete-session', $safeSessionId)); ?>">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <div class="empty-title">No Records Found</div>
                                    <div class="empty-subtitle">You haven't processed any death certificates yet.</div>
                                    <a href="<?php echo e(route('instant-estate.index')); ?>" class="btn btn-primary">
                                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Upload Your First Document
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($sessions instanceof \Illuminate\Pagination\LengthAwarePaginator && $sessions->hasPages()): ?>
                <div class="pagination-container">
                    <?php echo e($sessions->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== MODERN ALERT SYSTEM =====
    class ModernAlert {
        static show({ type = 'info', title, message, duration = 4000 }) {
            const container = document.getElementById('alertContainer');
            if (!container) return;
            
            const alertId = 'alert-' + Date.now();
            
            const icons = {
                success: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>`,
                warning: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>`,
                error: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>`,
                info: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>`
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
            if (duration > 0) setTimeout(() => ModernAlert.close(alertId), duration);
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
        static warning(message, title = 'Warning') { return this.show({ type: 'warning', title, message }); }
        static error(message, title = 'Error') { return this.show({ type: 'error', title, message, duration: 6000 }); }
        static info(message, title = 'Info') { return this.show({ type: 'info', title, message }); }
    }
    
    window.ModernAlert = ModernAlert;
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function isValidUuid(uuid) {
        if (!uuid || typeof uuid !== 'string') return false;
        const regex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
        return regex.test(uuid);
    }
    
    // ===== SINGLE DELETE MODAL LOGIC =====
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteForm = document.getElementById('deleteForm');
    const deleteFilenameSpan = document.getElementById('deleteFilename');
    const deleteSessionIdSpan = document.getElementById('deleteSessionId');
    
    function showDeleteModal(sessionId, filename, deleteUrl) {
        if (!sessionId || !deleteUrl || !isValidUuid(sessionId)) {
            ModernAlert.error('Invalid session data. Cannot delete.', 'Error');
            return;
        }
        if (deleteFilenameSpan) deleteFilenameSpan.textContent = filename || 'Unknown';
        if (deleteSessionIdSpan) deleteSessionIdSpan.textContent = sessionId.substring(0, 8) + '...';
        if (deleteForm) deleteForm.action = deleteUrl;
        if (deleteConfirmModal) deleteConfirmModal.classList.add('active');
    }
    
    function hideDeleteModal() {
        if (deleteConfirmModal) deleteConfirmModal.classList.remove('active');
        if (deleteForm) deleteForm.action = '';
    }
    
    if (closeDeleteModal) closeDeleteModal.addEventListener('click', hideDeleteModal);
    if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', hideDeleteModal);
    if (deleteConfirmModal) {
        deleteConfirmModal.addEventListener('click', (e) => { if (e.target === deleteConfirmModal) hideDeleteModal(); });
    }
    
    // Attach one handler to all delete buttons – they show the SINGLE modal and then the form submits normally
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const sessionId = this.getAttribute('data-session-id');
            const filename = this.getAttribute('data-filename');
            const deleteUrl = this.getAttribute('data-delete-url');
            if (sessionId && deleteUrl && isValidUuid(sessionId)) {
                showDeleteModal(sessionId, filename, deleteUrl);
            } else {
                ModernAlert.error('Unable to delete. Session ID is missing or invalid.', 'Error');
            }
        });
    });
    
    // ===== SEND REPORT LINK FUNCTIONALITY (AJAX) =====
    const sendEmailModal = document.getElementById('sendEmailModal');
    const closeSendEmailModal = document.getElementById('closeSendEmailModal');
    const cancelSendEmailBtn = document.getElementById('cancelSendEmailBtn');
    const confirmSendEmailBtn = document.getElementById('confirmSendEmailBtn');
    const sendEmailInput = document.getElementById('sendEmailInput');
    const sendEmailError = document.getElementById('sendEmailError');
    const sendEmailDeceasedName = document.getElementById('sendEmailDeceasedName');
    const sendEmailSessionIdDisplay = document.getElementById('sendEmailSessionIdDisplay');
    const modalSessionIdField = document.getElementById('modalSessionId');
    
    function showSendEmailModal(sessionId, defaultEmail, deceasedName) {
        if (!isValidUuid(sessionId)) {
            console.error('Invalid session ID (not a valid UUID):', sessionId);
            ModernAlert.error('Invalid session identifier. Please refresh the page and try again.', 'Error');
            return;
        }
        if (modalSessionIdField) modalSessionIdField.value = sessionId;
        if (sendEmailInput) sendEmailInput.value = defaultEmail || '';
        if (sendEmailDeceasedName) sendEmailDeceasedName.textContent = deceasedName || 'N/A';
        if (sendEmailSessionIdDisplay) sendEmailSessionIdDisplay.textContent = sessionId.substring(0, 8) + '...';
        if (sendEmailError) sendEmailError.style.display = 'none';
        if (sendEmailInput) sendEmailInput.classList.remove('error');
        if (sendEmailModal) sendEmailModal.classList.add('active');
        setTimeout(() => { if (sendEmailInput) sendEmailInput.focus(); }, 300);
    }
    
    function hideSendEmailModal() {
        if (sendEmailModal) sendEmailModal.classList.remove('active');
        if (modalSessionIdField) modalSessionIdField.value = '';
    }
    
    async function sendReportLink() {
        const sessionId = modalSessionIdField ? modalSessionIdField.value.trim() : '';
        if (!sessionId || !isValidUuid(sessionId)) {
            ModernAlert.error('Session ID is missing or invalid. Please reopen the modal and try again.', 'Error');
            return;
        }
        
        const email = sendEmailInput ? sendEmailInput.value.trim() : '';
        if (!email) {
            if (sendEmailError) {
                sendEmailError.textContent = 'Please enter an email address.';
                sendEmailError.style.display = 'block';
            }
            if (sendEmailInput) {
                sendEmailInput.classList.add('error');
                sendEmailInput.focus();
            }
            return;
        }
        if (!isValidEmail(email)) {
            if (sendEmailError) {
                sendEmailError.textContent = 'Please enter a valid email address.';
                sendEmailError.style.display = 'block';
            }
            if (sendEmailInput) {
                sendEmailInput.classList.add('error');
                sendEmailInput.focus();
            }
            return;
        }
        if (sendEmailError) sendEmailError.style.display = 'none';
        if (sendEmailInput) sendEmailInput.classList.remove('error');
        
        hideSendEmailModal();
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) loadingOverlay.classList.add('active');
        const loadingText = document.getElementById('loadingText');
        const loadingSubtext = document.getElementById('loadingSubtext');
        if (loadingText) loadingText.textContent = 'Sending Report Link...';
        if (loadingSubtext) loadingSubtext.textContent = 'Please wait';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        try {
            const response = await fetch(`/instant-estate/send-report-link/${encodeURIComponent(sessionId)}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    session_id: sessionId
                })
            });
            const data = await response.json();
            if (data.success) {
                ModernAlert.success(data.message || `Report link has been sent to ${email}.`, 'Email Sent');
            } else {
                ModernAlert.error(data.message || 'Failed to send report link. Please try again.', 'Error');
            }
        } catch (error) {
            console.error('Send report link error:', error);
            ModernAlert.error('Network error. Please try again.', 'Error');
        } finally {
            if (loadingOverlay) loadingOverlay.classList.remove('active');
        }
    }
    
    if (closeSendEmailModal) closeSendEmailModal.addEventListener('click', hideSendEmailModal);
    if (cancelSendEmailBtn) cancelSendEmailBtn.addEventListener('click', hideSendEmailModal);
    if (confirmSendEmailBtn) confirmSendEmailBtn.addEventListener('click', sendReportLink);
    if (sendEmailModal) {
        sendEmailModal.addEventListener('click', (e) => { if (e.target === sendEmailModal) hideSendEmailModal(); });
    }
    if (sendEmailInput) {
        sendEmailInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') { e.preventDefault(); sendReportLink(); } });
        sendEmailInput.addEventListener('input', () => { if (sendEmailError) sendEmailError.style.display = 'none'; sendEmailInput.classList.remove('error'); });
    }
    
    document.querySelectorAll('.send-report-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const sessionId = this.getAttribute('data-session-id');
            const defaultEmail = this.getAttribute('data-default-email') || '';
            const deceasedName = this.getAttribute('data-deceased-name') || 'the deceased';
            if (sessionId && isValidUuid(sessionId)) {
                showSendEmailModal(sessionId, defaultEmail, deceasedName);
            } else {
                ModernAlert.error('Session ID is missing or invalid. Cannot send report.', 'Error');
            }
        });
    });
    
    // Auto-hide page alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.page-alert.show').forEach(alert => {
            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('hide');
                setTimeout(() => { if (alert.parentNode) alert.remove(); }, 500);
            }, 5000);
        });
    }, 1000);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (sendEmailModal && sendEmailModal.classList.contains('active')) hideSendEmailModal();
            if (deleteConfirmModal && deleteConfirmModal.classList.contains('active')) hideDeleteModal();
        }
    });
    
    console.log('✅ History page ready – single delete confirmation active');
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/instant-estate/history.blade.php ENDPATH**/ ?>