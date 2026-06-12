

<?php $__env->startSection('title', 'Instant Estate'); ?>

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
    
    /* ===== HERO SECTION ===== */
    .instant-hero {
        min-height: 35vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 3rem 2rem;
    }

    .instant-hero .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .instant-hero .animated-bg .bg-circle-1 {
        position: absolute;
        top: 10%;
        right: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }

    .instant-hero .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .instant-hero .animated-bg .bg-circle-3 {
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .instant-hero .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }

    .instant-hero .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .instant-hero .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    .instant-hero .shape-1 { width: 40px; height: 40px; top: 20%; left: 10%; animation-name: float-1; }
    .instant-hero .shape-2 { width: 25px; height: 25px; top: 60%; left: 85%; animation-name: float-2; animation-delay: 1s; }
    .instant-hero .shape-3 { width: 35px; height: 35px; top: 75%; left: 15%; animation-name: float-3; animation-delay: 0.5s; }
    .instant-hero .shape-4 { width: 20px; height: 20px; top: 30%; left: 70%; animation-name: float-4; animation-delay: 1.5s; }

    .instant-hero .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 2rem;
    }

    .instant-hero .hero-kicker {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        display: inline-flex;
        padding: 0.75rem 1.75rem;
        border-radius: var(--border-radius-xl);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.2);
        transition: var(--transition);
    }

    .instant-hero .hero-kicker:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-2px);
    }

    .instant-hero .kicker-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .instant-hero .kicker-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 1rem;
    }

    .instant-hero .kicker-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        color: var(--accent-color);
    }

    .instant-hero .hero-title {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .instant-hero .hero-highlight {
        color: var(--accent-color);
        position: relative;
        display: inline-block;
    }

    .instant-hero .hero-highlight::after {
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

    .instant-hero .hero-subtitle {
        font-size: 1.25rem;
        max-width: 600px;
        margin-bottom: 2.5rem;
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
        height: 100%;
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
        gap: 1rem;
    }
    
    .card-header-icon { width: 32px; height: 32px; color: var(--primary-color); stroke-width: 2; }
    .card-title { font-size: 1.5rem; font-weight: 700; color: var(--dark); margin: 0; flex: 1; }
    .card-badge { padding: 0.5rem 1rem; background: var(--primary-light); color: var(--primary-color); border-radius: var(--border-radius-md); font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(26, 95, 180, 0.2); }
    .card-body { padding: 2rem; }
    
    /* ===== UPLOAD ZONE ===== */
    .upload-zone {
        border: 2px dashed var(--gray-300);
        border-radius: var(--border-radius-lg);
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background: var(--gray-50);
        margin-bottom: 1.5rem;
    }
    
    .upload-zone:hover, .upload-zone.dragover {
        border-color: var(--primary-color);
        background: var(--primary-light);
        transform: scale(1.01);
    }
    
    .upload-zone.error { border-color: var(--danger-color); background: var(--danger-light); }
    
    .upload-icon {
        width: 80px; height: 80px;
        margin: 0 auto 1.5rem;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--primary-color);
    }
    
    .upload-title { font-weight: 600; font-size: 1.1rem; color: var(--gray-800); margin-bottom: 0.5rem; }
    .upload-hint { font-size: 0.875rem; color: var(--gray-500); }
    .file-input { display: none; }
    
    /* ===== FILE PREVIEW ===== */
    .file-preview {
        background: var(--gray-100);
        border-radius: var(--border-radius-md);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: none;
        align-items: center;
        gap: 1rem;
        animation: fadeIn 0.3s ease;
    }
    
    .file-preview.active { display: flex; }
    
    .file-icon {
        width: 48px; height: 48px;
        background: var(--primary-light);
        border-radius: var(--border-radius-sm);
        display: flex; align-items: center; justify-content: center;
        color: var(--primary-color);
    }
    
    .file-info { flex: 1; }
    .file-name { font-weight: 600; color: var(--gray-800); margin-bottom: 0.25rem; word-break: break-word; }
    .file-meta { display: flex; gap: 1rem; font-size: 0.75rem; color: var(--gray-500); }
    
    .file-remove {
        background: none; border: none;
        color: var(--danger-color);
        cursor: pointer; padding: 0.5rem;
        transition: var(--transition);
        border-radius: 50%; width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
    }
    
    .file-remove:hover { background: var(--danger-light); transform: scale(1.1); }
    
    /* ===== PROGRESS BAR ===== */
    .progress-container { margin: 1.5rem 0; display: none; }
    .progress-container.active { display: block; }
    
    .progress-track {
        height: 8px; background: var(--gray-200);
        border-radius: var(--border-radius-xl); overflow: hidden; position: relative;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        width: 0%;
        transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative; overflow: hidden;
    }
    
    .progress-fill::after {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
    
    .progress-steps { display: flex; justify-content: space-between; margin-top: 0.75rem; }
    
    .progress-step {
        text-align: center; font-size: 0.875rem; font-weight: 500;
        color: var(--gray-600); position: relative; padding-top: 1.75rem;
    }
    
    .progress-step::before {
        content: '';
        position: absolute; top: 0; left: 50%; transform: translateX(-50%);
        width: 12px; height: 12px; background: var(--gray-300);
        border-radius: 50%; transition: var(--transition);
    }
    
    .progress-step.active { color: var(--primary-color); font-weight: 600; }
    .progress-step.active::before { background: var(--primary-color); box-shadow: 0 0 0 4px rgba(26, 95, 180, 0.2); }
    .progress-step.completed { color: var(--success-color); }
    .progress-step.completed::before { background: var(--success-color); box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.2); }
    
    .progress-text { text-align: center; font-size: 0.875rem; color: var(--gray-600); margin-top: 0.75rem; font-weight: 500; }
    
    /* ===== STATUS MESSAGE ===== */
    .status-message {
        padding: 1rem 1.25rem; border-radius: var(--border-radius-md);
        margin-bottom: 1.5rem; display: none; align-items: flex-start; gap: 0.75rem;
        animation: fadeIn 0.3s ease;
    }
    
    .status-message.active { display: flex; }
    .status-success { background: var(--success-light); color: var(--success-dark); border-left: 4px solid var(--success-color); }
    .status-error { background: var(--danger-light); color: var(--danger-color); border-left: 4px solid var(--danger-color); }
    .status-warning { background: var(--warning-light); color: #b76e00; border-left: 4px solid var(--warning-color); }
    .status-info { background: var(--info-light); color: var(--info-color); border-left: 4px solid var(--info-color); }
    
    /* ===== EXTRACTED DATA SECTION ===== */
    .extracted-section {
        margin-top: 1.5rem; padding: 1.5rem;
        background: var(--gray-50); border-radius: var(--border-radius-md);
        display: none; animation: fadeIn 0.3s ease;
    }
    
    .extracted-section.active { display: block; }
    
    .extracted-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;
    }
    
    .extracted-title { font-size: 1rem; font-weight: 600; color: var(--gray-800); display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    
    .confidence-badge { padding: 0.25rem 0.75rem; border-radius: var(--border-radius-xl); font-size: 0.75rem; font-weight: 600; }
    .confidence-high { background: var(--success-light); color: var(--success-dark); }
    .confidence-medium { background: var(--warning-light); color: #b76e00; }
    .confidence-low { background: var(--danger-light); color: var(--danger-color); }
    
    .data-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; max-height: 500px; overflow-y: auto; }
    
    .data-item {
        display: flex; flex-direction: column;
        padding: 0.75rem 1rem; background: var(--white);
        border-radius: var(--border-radius-sm); border: 1px solid var(--gray-200);
        transition: var(--transition);
    }
    
    .data-item:hover { border-color: var(--primary-color); box-shadow: var(--shadow-sm); }
    .data-label { font-weight: 500; color: var(--gray-600); margin-bottom: 0.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .data-value { color: var(--gray-800); font-weight: 500; font-size: 0.875rem; word-break: break-word; }
    
    .form-control {
        width: 100%; padding: 0.5rem; border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-300); font-size: 0.875rem;
        transition: var(--transition);
    }
    
    .form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(26, 95, 180, 0.1); }
    .form-select {
        width: 100%; padding: 0.5rem; border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-300); font-size: 0.875rem;
        background-color: var(--white);
    }
    
    /* ===== BUTTONS ===== */
    .btn-group-modern { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1.5rem; }
    
    .btn {
        padding: 0.875rem 1.75rem; border: none;
        border-radius: var(--border-radius-md); font-weight: 600; font-size: 0.95rem;
        cursor: pointer; transition: var(--transition);
        display: inline-flex; align-items: center; justify-content: center; gap: 0.75rem;
        text-decoration: none; line-height: 1;
    }
    
    .btn-primary { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: var(--white); box-shadow: var(--shadow-primary); }
    .btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: var(--shadow-lg), var(--shadow-primary); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-success { background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%); color: var(--white); }
    .btn-success:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
    .btn-secondary { background: var(--white); color: var(--gray-700); border: 2px solid var(--gray-300); }
    .btn-secondary:hover { background: var(--gray-100); border-color: var(--gray-400); transform: translateY(-2px); }
    .btn-outline { background: transparent; border: 2px solid var(--gray-300); color: var(--gray-700); }
    .btn-outline:hover { border-color: var(--primary-color); color: var(--primary-color); transform: translateY(-2px); }
    .btn-sm { padding: 0.5rem 1rem; font-size: 0.875rem; }
    
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
    
    /* ===== QUALITY ALERT MODAL ===== */
    .modal-overlay.quality-alert .modal-content {
        max-width: 550px;
    }
    
    .requirements-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }
    
    .requirements-list li {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .requirements-list .icon-success { color: var(--success-color); }
    .requirements-list .icon-danger { color: var(--danger-color); }
    .requirements-list .icon-warning { color: var(--warning-color); }
    
    /* ===== CAPTCHA STYLES ===== */
    .captcha-container {
        background: var(--gray-100);
        padding: 1.5rem;
        border-radius: var(--border-radius-md);
        text-align: center;
        margin-bottom: 1.5rem;
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
    
    /* ===== FEATURES LIST ===== */
    .features-list { list-style: none; padding: 0; margin: 0; }
    .feature-item { display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid var(--gray-200); }
    .feature-item:last-child { border-bottom: none; }
    .feature-icon { width: 32px; height: 32px; background: var(--success-light); border-radius: var(--border-radius-sm); display: flex; align-items: center; justify-content: center; color: var(--success-color); flex-shrink: 0; }
    .feature-content { flex: 1; }
    .feature-title { font-weight: 600; color: var(--gray-800); margin-bottom: 0.25rem; }
    .feature-desc { font-size: 0.875rem; color: var(--gray-600); }
    
    /* ===== INFO BOXES ===== */
    .info-box { background: var(--gray-50); border-radius: var(--border-radius-md); padding: 1.25rem; margin-top: 1.25rem; }
    .info-box-blue { background: var(--info-light); border-left: 3px solid var(--info-color); }
    .info-box-green { background: var(--success-light); border-left: 3px solid var(--success-color); }
    .info-box-title { font-size: 0.9rem; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .info-box-text { font-size: 0.8rem; color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5; }
    
    /* ===== LOADING OVERLAY ===== */
    .loading-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex; align-items: center; justify-content: center;
        z-index: 99999; opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
    }
    
    .loading-overlay.active { opacity: 1; pointer-events: all; }
    .loading-content { background: white; padding: 3rem; border-radius: var(--border-radius-lg); text-align: center; max-width: 400px; width: 90%; box-shadow: var(--shadow-xl); }
    .loading-spinner { width: 60px; height: 60px; border: 4px solid var(--gray-200); border-top-color: var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1.5rem; }
    .loading-text { font-size: 1.125rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.5rem; }
    .loading-subtext { color: var(--gray-600); font-size: 0.875rem; }
    
    /* ===== MODERN ALERT ===== */
    .modern-alert-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; max-width: 400px; }
    
    .modern-alert {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem 1.25rem; background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px); border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg); border-left: 4px solid var(--primary-color);
        transform: translateX(120%); opacity: 0;
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 300px;
    }
    
    .modern-alert.show { transform: translateX(0); opacity: 1; }
    .modern-alert.hide { transform: translateX(120%); opacity: 0; }
    
    .alert-icon { width: 20px; height: 20px; flex-shrink: 0; }
    .alert-content { flex: 1; }
    .alert-title { font-weight: 600; font-size: 0.9rem; color: var(--gray-900); margin-bottom: 0.25rem; }
    .alert-message { font-size: 0.8rem; color: var(--gray-600); line-height: 1.4; }
    
    .alert-close { background: none; border: none; color: var(--gray-500); cursor: pointer; padding: 0.25rem; border-radius: 6px; transition: var(--transition); width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .alert-close:hover { background: var(--gray-100); color: var(--gray-700); }
    
    .modern-alert.success { border-left-color: var(--success-color); background: rgba(40, 167, 69, 0.05); }
    .modern-alert.warning { border-left-color: var(--warning-color); background: rgba(255, 193, 7, 0.05); }
    .modern-alert.error { border-left-color: var(--danger-color); background: rgba(220, 53, 69, 0.05); }
    .modern-alert.info { border-left-color: var(--primary-color); background: rgba(26, 95, 180, 0.05); }
    
    /* ===== FIELD MISSING STYLES ===== */
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
    
    /* ===== ANIMATIONS ===== */
    @keyframes float-1 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-15px) rotate(120deg)}66%{transform:translateY(8px) rotate(240deg)} }
    @keyframes float-2 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-20px) rotate(90deg)}66%{transform:translateY(10px) rotate(180deg)} }
    @keyframes float-3 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-12px) rotate(60deg)}66%{transform:translateY(6px) rotate(120deg)} }
    @keyframes float-4 { 0%,100%{transform:translateY(0) rotate(0deg)}33%{transform:translateY(-18px) rotate(150deg)}66%{transform:translateY(9px) rotate(300deg)} }
    @keyframes pulse { 0%,100%{opacity:0.7;transform:scale(1)}50%{opacity:1;transform:scale(1.1)} }
    @keyframes shimmer { 0%{transform:translateX(-100%)}100%{transform:translateX(100%)} }
    @keyframes spin { to{transform:rotate(360deg)} }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)} }
    
    /* ===== LOGIN PROMPT MODAL ===== */
    .login-prompt-overlay {
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
        z-index: 100001;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .login-prompt-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .login-prompt-content {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        max-width: 500px;
        width: 90%;
        padding: 2rem;
        box-shadow: var(--shadow-xl);
        transform: scale(0.9);
        transition: transform 0.3s ease;
        text-align: center;
    }
    
    .login-prompt-overlay.active .login-prompt-content {
        transform: scale(1);
    }
    
    .login-prompt-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
    }
    
    .login-prompt-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }
    
    .login-prompt-message {
        color: var(--gray-600);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .login-prompt-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) { .instant-hero .hero-title{font-size:2.5rem}.instant-hero{min-height:40vh!important;padding:3rem 1.5rem}.glass-container{padding:0 1.5rem 2rem;margin-top:-2rem} }
    @media (max-width: 768px) { .instant-hero .hero-title{font-size:2rem}.instant-hero .hero-subtitle{font-size:1.1rem}.instant-hero{min-height:35vh!important;padding:2rem 1rem}.glass-container{padding:0 1rem 1.5rem;margin-top:-1.5rem}.card-header{padding:1.25rem 1.5rem}.card-title{font-size:1.25rem}.card-body{padding:1.5rem}.data-grid{grid-template-columns:1fr}.btn-group-modern{flex-direction:column}.btn{width:100%}.modern-alert-container{left:20px;right:20px;max-width:none}.modern-alert{min-width:auto;width:100%}.progress-steps{display:none}.instant-hero .shape,.instant-hero .bg-circle-3{display:none!important} }
    @media (max-width: 480px) { .instant-hero .hero-title{font-size:1.75rem}.instant-hero .hero-subtitle{font-size:1rem}.instant-hero{min-height:30vh!important;padding:1.5rem 1rem}.upload-zone{padding:1.5rem 1rem}.data-value{font-size:.75rem}.data-label{font-size:.75rem} }
</style>

<!-- Modern Alert Container -->
<div class="modern-alert-container" id="alertContainer"></div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Processing Document...</div>
        <div class="loading-subtext" id="loadingSubtext">Please wait while we extract information</div>
    </div>
</div>

<!-- Login Prompt Overlay (shown when guest tries to request email) -->
<div class="login-prompt-overlay" id="loginPromptOverlay">
    <div class="login-prompt-content">
        <div class="login-prompt-icon">
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h2 class="login-prompt-title">Login Required</h2>
        <p class="login-prompt-message">You need to login to request the report via email.<br>As a guest, you can still view the report but will not receive an email copy.</p>
        <div class="login-prompt-buttons">
            <button class="btn btn-secondary" id="cancelLoginBtn">Continue as Guest</button>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login Now</a>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-ghost">Create Account</a>
        </div>
    </div>
</div>

<!-- Quality Alert Modal -->
<div class="modal-overlay" id="qualityAlertModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">⚠️ Important: Document Requirements</div>
            <button class="modal-close" id="closeQualityModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="missing-warning" style="background: var(--danger-light); border-left-color: var(--danger-color);">
                <strong>Please upload photos of the ORIGINAL death certificate only.</strong>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <h6 style="font-weight: 600; color: var(--danger-color); margin-bottom: 0.5rem;">The following are NOT acceptable:</h6>
                <ul class="requirements-list">
                    <li><span class="icon-warning">⚠️</span> Blurry images</li>
                    <li><span class="icon-warning">⚠️</span> Glare or reflection</li>
                    <li><span class="icon-warning">⚠️</span> Images with invisible or cropped edges</li>
                    <li><span class="icon-warning">⚠️</span> Low resolution images</li>
                </ul>
            </div>
            
            <div>
                <h6 style="font-weight: 600; color: var(--success-color); margin-bottom: 0.5rem;">Requirements:</h6>
                <ul class="requirements-list">
                    <li><span class="icon-success">✓</span> Clear photo showing the ENTIRE document</li>
                    <li><span class="icon-success">✓</span> Good lighting with no shadows</li>
                    <li><span class="icon-success">✓</span> All text must be readable</li>
                    <li><span class="icon-success">✓</span> Original document only (not photocopy)</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button class="btn btn-success" id="proceedToUploadBtn">I Understand, Proceed to Upload</button>
        </div>
    </div>
</div>

<!-- CAPTCHA Modal -->
<div class="modal-overlay" id="captchaModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">🔒 Security Verification</div>
            <button class="modal-close" id="closeCaptchaModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="captcha-container">
                <div class="captcha-code" id="captchaCode">LOADING</div>
                <input type="text" id="captchaInput" class="captcha-input" placeholder="Enter the code above" maxlength="6" autocomplete="off">
                <button class="captcha-refresh" id="refreshCaptcha">⟳ Refresh Code</button>
            </div>
            <p style="font-size: 0.875rem; color: var(--gray-600); text-align: center;">
                Please complete this verification to access the inheritance report.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelCaptchaBtn">Cancel</button>
            <button class="btn btn-primary" id="verifyCaptchaBtn">Verify & Continue</button>
        </div>
    </div>
</div>

<!-- Email Request Modal (after report opens) - FIXED: added email input -->
<div class="modal-overlay" id="emailReportModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">📧 Send Report Link via Email</div>
            <button class="modal-close" id="closeEmailReportModal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 1rem; color: var(--gray-600);">
                Enter your email address to receive the report link.
            </p>
            <div class="form-group" style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-800);">
                    Email Address <span style="color: var(--danger-color);">*</span>
                </label>
                <input type="email" 
                       id="emailReportInput" 
                       class="form-control" 
                       placeholder="your-email@example.com"
                       autocomplete="email">
                <small id="emailReportError" style="color: var(--danger-color); display: none; margin-top: 0.25rem;">
                    Please enter a valid email address.
                </small>
            </div>
            <div class="info-box info-box-blue" style="margin-top: 0.5rem; padding: 0.75rem;">
                <p style="margin: 0; font-size: 0.8rem;">The report link will be sent to the email address you provide.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="skipEmailReportBtn">No, Thank You</button>
            <button class="btn btn-success" id="confirmEmailReportBtn">Yes, Send to Email</button>
        </div>
    </div>
</div>

<?php
    $maxFileSize = 10;
    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
    $allowedMimeTypes = 'application/pdf,image/jpeg,image/png,image/webp';
    $deceasedEmail = Auth::check() ? Auth::user()->email : '';
?>

<!-- Hero Section -->
<section class="instant-hero">
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
                <span class="kicker-item"><svg class="kicker-icon" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Precise</span>
                <span class="kicker-item"><svg class="kicker-icon" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Reliable</span>
                <span class="kicker-item"><svg class="kicker-icon" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Shariah-Compliant</span>
            </div>
        </div>
        <h1 class="hero-title"><span class="hero-highlight">Instant Estate</span><br>Death Certificate Processing</h1>
        <p class="hero-subtitle">Upload a death certificate and let our AI automatically extract information for inheritance processing.</p>
        
        <?php if(!Auth::check()): ?>
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: var(--border-radius-md); padding: 0.75rem 1.5rem; display: inline-flex; align-items: center; gap: 0.75rem; margin-top: 1rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span style="font-size: 0.875rem;">Guest Mode - <a href="<?php echo e(route('login')); ?>" style="color: var(--accent-color); text-decoration: underline;">Login</a> to request reports via email</span>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Main Container -->
<div class="glass-container">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="glass-card">
                <div class="card-header">
                    <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <h2 class="card-title">Upload Death Certificate</h2>
                    <span class="card-badge">Step 1 of 3</span>
                </div>
                <div class="card-body">
                    <div class="upload-zone" id="uploadZone">
                        <div class="upload-icon"><svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg></div>
                        <div class="upload-title">Click or drag & drop to upload</div>
                        <div class="upload-hint">Supported formats: PDF, JPG, JPEG, PNG, WEBP<br>Maximum file size: <?php echo e($maxFileSize); ?>MB</div>
                        <input type="file" id="fileInput" class="file-input" accept="<?php echo e($allowedMimeTypes); ?>">
                    </div>
                    <div class="file-preview" id="filePreview">
                        <div class="file-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                        <div class="file-info"><div class="file-name" id="fileName">document.pdf</div><div class="file-meta"><span id="fileSize">0 KB</span><span id="fileType">PDF</span></div></div>
                        <button class="file-remove" id="removeFileBtn" title="Remove file"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <div class="progress-container" id="progressContainer">
                        <div class="progress-track"><div class="progress-fill" id="progressFill"></div></div>
                        <div class="progress-steps">
                            <div class="progress-step" id="stepUpload">Upload</div>
                            <div class="progress-step" id="stepOCR">OCR</div>
                            <div class="progress-step" id="stepComplete">Complete</div>
                        </div>
                        <div class="progress-text" id="progressText">Ready to process</div>
                    </div>
                    <div class="status-message" id="statusMessage"></div>
                    <div class="btn-group-modern">
                        <button class="btn btn-primary" id="processBtn" disabled>Process Death Certificate</button>
                        <button class="btn btn-outline" id="cancelBtn" style="display: none;">Cancel</button>
                    </div>
                    
                    <!-- Extracted Data Section (shown after OCR) -->
                    <div class="extracted-section" id="extractedSection">
                        <div class="extracted-header">
                            <div class="extracted-title">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Extracted Information 
                                <span class="confidence-badge" id="confidenceBadge"></span>
                            </div>
                        </div>
                        <div id="missingFieldsWarning" class="missing-warning" style="display: none;">
                            <span>⚠️</span>
                            <strong>Missing Required Fields:</strong> Please fill in the highlighted fields below.
                        </div>
                        <div class="data-grid" id="extractedFields"></div>
                        <div class="btn-group-modern">
                            <button class="btn btn-success" id="editAndContinueBtn">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Continue (Database Matching)
                            </button>
                            <button class="btn btn-secondary" id="newUploadBtn">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload New
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br></br>
        <div class="col-lg-5">
            <div class="glass-card">
                <div class="card-header"><svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><h2 class="card-title">How It Works</h2><span class="card-badge">AI Process</span></div>
                <div class="card-body">
                    <ul class="features-list">
                        <li class="feature-item"><div class="feature-icon"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg></div><div class="feature-content"><div class="feature-title">1. Upload Certificate</div><div class="feature-desc">Upload the death certificate in PDF or image format</div></div></li>
                        <li class="feature-item"><div class="feature-icon"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><div class="feature-content"><div class="feature-title">2. AI Extraction</div><div class="feature-desc">Our OCR technology extracts key information automatically</div></div></li>
                        <li class="feature-item"><div class="feature-icon"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div><div class="feature-content"><div class="feature-title">3. Edit & Confirm</div><div class="feature-desc">Review extracted data and fill missing fields</div></div></li>
                        <li class="feature-item"><div class="feature-icon"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div><div class="feature-content"><div class="feature-title">4. Database Matching</div><div class="feature-desc">System searches for existing estate records</div></div></li>
                        <li class="feature-item"><div class="feature-icon"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div><div class="feature-content"><div class="feature-title">5. CAPTCHA Verification</div><div class="feature-desc">Complete security verification to access results</div></div></li>
                        <li class="feature-item"><div class="feature-icon"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div><div class="feature-content"><div class="feature-title">6. Report or Calculator</div><div class="feature-desc">View inheritance report or continue to calculator</div></div></li>
                    </ul>
                    <div class="info-box info-box-blue"><div class="info-box-title"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Privacy & Security</div><p class="info-box-text">Documents are processed securely and never stored permanently.</p></div>
                    <div class="info-box info-box-green"><div class="info-box-title"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Processing History</div><div class="info-box-text"><a href="<?php echo e(route('instant-estate.history')); ?>" style="color: var(--primary-color); font-weight: 500; text-decoration: none;">View your history →</a> to see all previous uploads.</div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== AUTHENTICATION STATE ==========
    const isLoggedIn = <?php echo e(Auth::check() ? 'true' : 'false'); ?>;

    // ========== LOGIN PROMPT ==========
    const loginPromptOverlay = document.getElementById('loginPromptOverlay');
    const cancelLoginBtn = document.getElementById('cancelLoginBtn');

    function showLoginPrompt() {
        if (!isLoggedIn && loginPromptOverlay) {
            loginPromptOverlay.classList.add('active');
        }
    }

    function hideLoginPrompt() {
        if (loginPromptOverlay) {
            loginPromptOverlay.classList.remove('active');
        }
    }

    if (cancelLoginBtn) {
        cancelLoginBtn.addEventListener('click', hideLoginPrompt);
    }

    if (loginPromptOverlay) {
        loginPromptOverlay.addEventListener('click', (e) => {
            if (e.target === loginPromptOverlay) hideLoginPrompt();
        });
    }

    // ========== MODERN ALERT SYSTEM ==========
    class ModernAlert {
        static show({type='info',title,message,duration=4000}) {
            const c = document.getElementById('alertContainer'), id = 'a'+Date.now();
            const icons = { 
                success:'<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                warning:'<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>',
                error:'<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                info:'<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            };
            const el = document.createElement('div'); el.className=`modern-alert ${type}`; el.id=id;
            el.innerHTML=`${icons[type]||icons.info}<div class="alert-content"><div class="alert-title">${escapeHtml(title)}</div><div class="alert-message">${escapeHtml(message)}</div></div><button class="alert-close" onclick="ModernAlert.close('${id}')"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
            c.appendChild(el); requestAnimationFrame(()=>el.classList.add('show'));
            if(duration>0) setTimeout(()=>ModernAlert.close(id),duration);
            return id;
        }
        static close(id) { const el=document.getElementById(id); if(el){el.classList.remove('show');el.classList.add('hide');setTimeout(()=>el.parentNode?.removeChild(el),500);} }
        static success(m,t='Success'){return this.show({type:'success',title:t,message:m});}
        static warning(m,t='Warning'){return this.show({type:'warning',title:t,message:m});}
        static error(m,t='Error'){return this.show({type:'error',title:t,message:m});}
        static info(m,t='Info'){return this.show({type:'info',title:t,message:m});}
    }
    window.ModernAlert = ModernAlert;

    function escapeHtml(t) { if(!t) return ''; const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

    const MAX_FILE_SIZE = 10 * 1024 * 1024;
    const ALLOWED_TYPES = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
    
    const routes = {
        upload: '<?php echo e(route("instant-estate.upload")); ?>',
        processOcr: '/instant-estate/process-ocr',
        editAndContinue: '/instant-estate/edit-and-continue',
        verifyCaptcha: '/instant-estate/verify-captcha',
        requestNotification: '/instant-estate/request-notification',
        viewReport: '/instant-estate/view-report',
        status: '/instant-estate/status',
        getToken: '/instant-estate/get-token',
        sendEmailLink: '/instant-estate/send-report-link'
    };
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let selectedFile = null, currentSessionId = null, extractedData = null, isProcessing = false;
    let currentCaptchaCode = null;
    let approvalPollingInterval = null;
    let approvalHandled = false;
    let currentAccessToken = null;
    let currentReportUrl = null;

    const elements = {
        uploadZone: document.getElementById('uploadZone'),
        fileInput: document.getElementById('fileInput'),
        filePreview: document.getElementById('filePreview'),
        fileName: document.getElementById('fileName'),
        fileSize: document.getElementById('fileSize'),
        fileType: document.getElementById('fileType'),
        removeFileBtn: document.getElementById('removeFileBtn'),
        processBtn: document.getElementById('processBtn'),
        cancelBtn: document.getElementById('cancelBtn'),
        statusMessage: document.getElementById('statusMessage'),
        progressContainer: document.getElementById('progressContainer'),
        progressFill: document.getElementById('progressFill'),
        progressText: document.getElementById('progressText'),
        stepUpload: document.getElementById('stepUpload'),
        stepOCR: document.getElementById('stepOCR'),
        stepComplete: document.getElementById('stepComplete'),
        extractedSection: document.getElementById('extractedSection'),
        extractedFields: document.getElementById('extractedFields'),
        confidenceBadge: document.getElementById('confidenceBadge'),
        missingFieldsWarning: document.getElementById('missingFieldsWarning'),
        editAndContinueBtn: document.getElementById('editAndContinueBtn'),
        newUploadBtn: document.getElementById('newUploadBtn'),
        loadingOverlay: document.getElementById('loadingOverlay'),
        loadingText: document.getElementById('loadingText'),
        loadingSubtext: document.getElementById('loadingSubtext'),
        qualityModal: document.getElementById('qualityAlertModal'),
        captchaModal: document.getElementById('captchaModal'),
        emailReportModal: document.getElementById('emailReportModal'),
        emailReportInput: document.getElementById('emailReportInput'),
        emailReportError: document.getElementById('emailReportError')
    };

    // ==================== UTILITY FUNCTIONS ====================
    
    const formatFileSize = b => b < 1024 ? b+' B' : b < 1048576 ? (b/1024).toFixed(1)+' KB' : (b/1048576).toFixed(2)+' MB';
    const getFileType = m => m === 'application/pdf' ? 'PDF' : m.startsWith('image/') ? 'Image' : 'File';
    const getConfidenceLevel = c => c >= 80 ? 'high' : c >= 50 ? 'medium' : 'low';
    
    const showStatus = (t,ti,m) => { const e=elements.statusMessage; e.className=`status-message status-${t} active`; e.innerHTML=`<div class="status-content"><strong>${escapeHtml(ti)}</strong><br>${escapeHtml(m)}</div>`; };
    const hideStatus = () => elements.statusMessage.classList.remove('active');
    
    const updateProgress = (p,t,s) => {
        elements.progressFill.style.width=p+'%'; elements.progressText.textContent=t;
        if(p>=20) elements.stepUpload.classList.add('completed');
        if(p>=50) elements.stepOCR.classList.add('completed');
        if(p>=80) elements.stepComplete.classList.add('completed');
        if(s){
            document.querySelectorAll('.progress-step').forEach(e=>e.classList.remove('active'));
            if(s==='upload') elements.stepUpload.classList.add('active');
            if(s==='ocr') elements.stepOCR.classList.add('active');
            if(s==='complete') elements.stepComplete.classList.add('active');
        }
    };
    
    const showProgress = s => { elements.progressContainer.classList.toggle('active',s); if(!s){elements.progressFill.style.width='0%';['stepUpload','stepOCR','stepComplete'].forEach(stepId=>{const el=elements[stepId];if(el){el.classList.remove('completed','active');}});} };
    
    const showLoading = (s,t='Processing...',st='Please wait') => { elements.loadingOverlay.classList.toggle('active',s); if(s){elements.loadingText.textContent=t;elements.loadingSubtext.textContent=st;} };
    
    // ==================== MODAL FUNCTIONS ====================
    
    const showQualityAlert = () => { elements.qualityModal.classList.add('active'); };
    const hideQualityAlert = () => { elements.qualityModal.classList.remove('active'); };
    
    const generateCaptcha = () => {
        currentCaptchaCode = Math.floor(100000 + Math.random() * 900000).toString();
        const captchaCodeEl = document.getElementById('captchaCode');
        if (captchaCodeEl) captchaCodeEl.textContent = currentCaptchaCode;
        const input = document.getElementById('captchaInput');
        if (input) input.value = '';
        return currentCaptchaCode;
    };
    
    const showCaptchaModal = () => {
        generateCaptcha();
        elements.captchaModal.classList.add('active');
    };
    
    const hideCaptchaModal = () => { elements.captchaModal.classList.remove('active'); };
    
    const showEmailReportModal = () => {
        if (elements.emailReportInput) elements.emailReportInput.value = '';
        if (elements.emailReportError) elements.emailReportError.style.display = 'none';
        if (elements.emailReportModal) elements.emailReportModal.classList.add('active');
    };
    
    const hideEmailReportModal = () => {
        if (elements.emailReportModal) elements.emailReportModal.classList.remove('active');
    };
    
    // ==================== REQUEST REPORT (OPEN NEW TAB) ====================
    async function requestReportAndOpen() {
        if (!currentSessionId) {
            ModernAlert.error('Session not found. Please refresh and try again.', 'Error');
            return;
        }
        
        showLoading(true, 'Preparing Report...', 'Generating secure link');
        
        try {
            const tokenResp = await fetch(`${routes.getToken}/${currentSessionId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const tokenData = await tokenResp.json();
            
            if (!tokenData.success || !tokenData.access_token) {
                throw new Error(tokenData.message || 'Unable to generate report link');
            }
            
            currentAccessToken = tokenData.access_token;
            currentReportUrl = `/instant-estate/report/${currentAccessToken}`;
            
            window.open(currentReportUrl, '_blank');
            
            showLoading(false);
            
            setTimeout(() => {
                showEmailReportModal();
            }, 500);
            
        } catch (error) {
            console.error('Request report error:', error);
            showLoading(false);
            ModernAlert.error(error.message || 'Unable to generate report. Please try again later.', 'Report Error');
        }
    }
    
    // ==================== SEND EMAIL LINK (FIXED: includes email) ====================
    async function sendReportLinkToEmail() {
        if (!currentSessionId || !currentAccessToken) {
            ModernAlert.error('Report link not available. Please request the report first.', 'Error');
            return;
        }
        
        if (!isLoggedIn) {
            hideEmailReportModal();
            showLoginPrompt();
            return;
        }
        
        const email = elements.emailReportInput ? elements.emailReportInput.value.trim() : '';
        if (!email) {
            if (elements.emailReportError) {
                elements.emailReportError.textContent = 'Please enter an email address.';
                elements.emailReportError.style.display = 'block';
            }
            if (elements.emailReportInput) {
                elements.emailReportInput.classList.add('error');
                elements.emailReportInput.focus();
            }
            return;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            if (elements.emailReportError) {
                elements.emailReportError.textContent = 'Please enter a valid email address.';
                elements.emailReportError.style.display = 'block';
            }
            if (elements.emailReportInput) {
                elements.emailReportInput.classList.add('error');
                elements.emailReportInput.focus();
            }
            return;
        }
        if (elements.emailReportError) elements.emailReportError.style.display = 'none';
        if (elements.emailReportInput) elements.emailReportInput.classList.remove('error');
        
        hideEmailReportModal();
        showLoading(true, 'Sending Email...', 'Please wait');
        
        try {
            const response = await fetch(`${routes.sendEmailLink}/${currentSessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email, session_id: currentSessionId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                ModernAlert.success(data.message || `Report link has been sent to ${email}.`, 'Email Sent');
            } else {
                throw new Error(data.message || 'Failed to send email');
            }
        } catch (error) {
            console.error('Send email error:', error);
            ModernAlert.error(error.message || 'Unable to send email. Please try again.', 'Email Error');
        } finally {
            showLoading(false);
        }
    }
    
    // ==================== ADMIN APPROVAL POLLING ====================
    const startApprovalPolling = (sessionId) => {
        if (approvalPollingInterval) clearInterval(approvalPollingInterval);
        approvalHandled = false;
        
        showLoading(true, 'Awaiting Admin Approval', 'Your report is pending review. We are checking status automatically.');
        
        approvalPollingInterval = setInterval(async () => {
            try {
                const resp = await fetch(`${routes.status}/${sessionId}`, {
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const data = await resp.json();
                
                if (data.admin_status === 'approved' && !approvalHandled) {
                    approvalHandled = true;
                    clearInterval(approvalPollingInterval);
                    approvalPollingInterval = null;
                    
                    showLoading(false);
                    ModernAlert.success('Your document has been approved by admin!', 'Approved');
                    
                    const requestBtn = document.createElement('button');
                    requestBtn.className = 'btn btn-success';
                    requestBtn.innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Request Report';
                    requestBtn.onclick = () => requestReportAndOpen();
                    
                    const container = document.querySelector('.btn-group-modern');
                    if (container) {
                        const existing = document.getElementById('dynamicRequestBtn');
                        if (existing) existing.remove();
                        requestBtn.id = 'dynamicRequestBtn';
                        container.parentNode.insertBefore(requestBtn, container.nextSibling);
                        ModernAlert.info('Your report is ready. Click "Request Report" to view and receive via email.', 'Ready');
                    }
                    
                } else if (data.admin_status === 'rejected') {
                    clearInterval(approvalPollingInterval);
                    approvalPollingInterval = null;
                    showLoading(false);
                    const reason = data.rejection_reason || 'No reason provided';
                    ModernAlert.error(`Your document was rejected: ${reason}`, 'Rejected');
                }
            } catch (error) {
                console.error('Status polling error:', error);
            }
        }, 5000);
    };
    
    // ==================== DISPLAY EXTRACTED DATA ====================
    
    function displayExtractedData(data, confidence, missingFields) {
        const fieldLabels = {
            deceased_name: 'Deceased Full Name',
            deceased_nric: 'NRIC/Passport Number',
            date_of_birth: 'Date of Birth',
            gender: 'Gender',
            death_date: 'Date of Death',
            death_place: 'Place of Death',
            cause_of_death: 'Cause of Death',
            marital_status: 'Marital Status',
            contact_email: 'Contact Email',
            contact_phone: 'Contact Phone',
            residential_address: 'Residential Address',
            father_name: "Father's Name",
            mother_name: "Mother's Name",
            spouse_name: "Spouse's Name"
        };
        
        const confidenceLevel = getConfidenceLevel(confidence);
        
        elements.confidenceBadge.className = `confidence-badge confidence-${confidenceLevel}`;
        elements.confidenceBadge.textContent = `${confidence}% Confidence`;
        
        let fieldsHtml = '';
        let missingCount = 0;
        
        Object.entries(fieldLabels).forEach(([key, label]) => {
            const value = data[key] || '';
            const isRequired = ['deceased_name', 'deceased_nric', 'death_date', 'death_place', 'contact_email', 'contact_phone', 'residential_address'].includes(key);
            const isMissing = isRequired && !value;
            if (isMissing) missingCount++;
            
            let inputHtml = '';
            
            if (key === 'gender') {
                inputHtml = `<select class="form-select ${isMissing ? 'field-missing' : ''}" data-field="${key}">
                    <option value="">Select Gender</option>
                    <option value="male" ${value === 'male' ? 'selected' : ''}>Male</option>
                    <option value="female" ${value === 'female' ? 'selected' : ''}>Female</option>
                </select>`;
            } else if (key === 'marital_status') {
                inputHtml = `<select class="form-select ${isMissing ? 'field-missing' : ''}" data-field="${key}">
                    <option value="">Select Status</option>
                    <option value="single" ${value === 'single' ? 'selected' : ''}>Single</option>
                    <option value="married" ${value === 'married' ? 'selected' : ''}>Married</option>
                    <option value="divorced" ${value === 'divorced' ? 'selected' : ''}>Divorced</option>
                    <option value="widowed" ${value === 'widowed' ? 'selected' : ''}>Widowed</option>
                </select>`;
            } else if (key === 'date_of_birth' || key === 'death_date') {
                inputHtml = `<input type="date" class="form-control ${isMissing ? 'field-missing' : ''}" data-field="${key}" value="${escapeHtml(value)}">`;
            } else {
                inputHtml = `<input type="${key === 'contact_email' ? 'email' : 'text'}" class="form-control ${isMissing ? 'field-missing' : ''}" data-field="${key}" placeholder="Enter ${label.toLowerCase()}" value="${escapeHtml(value)}">`;
            }
            
            fieldsHtml += `
                <div class="data-item">
                    <div class="data-label">
                        ${label} ${isRequired ? '<span style="color: var(--danger-color);">*</span>' : ''}
                    </div>
                    ${inputHtml}
                </div>
            `;
        });
        
        elements.extractedFields.innerHTML = fieldsHtml;
        
        if (missingCount > 0) {
            elements.missingFieldsWarning.style.display = 'flex';
            elements.missingFieldsWarning.innerHTML = `<span>⚠️</span> <strong>Missing Required Fields (${missingCount}):</strong> Please fill in the highlighted fields below before continuing.`;
        } else {
            elements.missingFieldsWarning.style.display = 'none';
        }
        
        elements.extractedSection.classList.add('active');
    }

    // ==================== RESET UI ====================
    
    function resetUI(){
        hideStatus();
        showProgress(false);
        elements.extractedSection.classList.remove('active');
        elements.processBtn.disabled=!selectedFile;
        elements.cancelBtn.style.display='none';
        elements.processBtn.innerHTML='<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Process Death Certificate';
        if (approvalPollingInterval) {
            clearInterval(approvalPollingInterval);
            approvalPollingInterval = null;
        }
        approvalHandled = false;
        const dynBtn = document.getElementById('dynamicRequestBtn');
        if (dynBtn) dynBtn.remove();
    }
    
    function setProcessingState(p){
        isProcessing=p;
        elements.processBtn.disabled=p||!selectedFile;
        elements.cancelBtn.style.display=p?'inline-flex':'none';
        elements.uploadZone.style.pointerEvents=p?'none':'';
        elements.removeFileBtn.disabled=p;
    }

    function clearFile(){
        selectedFile=null;
        currentSessionId=null;
        extractedData=null;
        currentAccessToken=null;
        currentReportUrl=null;
        elements.fileInput.value='';
        elements.filePreview.classList.remove('active');
        if (approvalPollingInterval) {
            clearInterval(approvalPollingInterval);
            approvalPollingInterval = null;
        }
        approvalHandled = false;
        resetUI();
    }

    // ==================== STEP 1: HANDLE FILE SELECT ====================
    
    function handleFileSelect(f){
        if(!f)return;
        if(!ALLOWED_TYPES.includes(f.type)){
            ModernAlert.error('Please upload PDF or image (JPG,PNG,WEBP).');
            return;
        }
        if(f.size>MAX_FILE_SIZE){
            ModernAlert.error(`Max 10MB. Your file: ${formatFileSize(f.size)}.`);
            return;
        }
        selectedFile=f;
        currentSessionId=null;
        extractedData=null;
        elements.fileName.textContent=f.name;
        elements.fileSize.textContent=formatFileSize(f.size);
        elements.fileType.textContent=getFileType(f.type);
        elements.filePreview.classList.add('active');
        elements.processBtn.disabled=false;
        resetUI();
    }

    // ==================== STEP 2: UPLOAD FILE ====================
    
    async function uploadFile(){
        if(!selectedFile||isProcessing)return;
        const fd=new FormData();
        fd.append('death_certificate',selectedFile);
        setProcessingState(true);
        showProgress(true);
        updateProgress(20,'Uploading...','upload');
        showLoading(true,'Uploading','Securely uploading...');
        try{
            const r=await fetch(routes.upload,{method:'POST',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:fd});
            const d=await r.json();
            if(!r.ok)throw new Error(d.message||'Upload failed');
            currentSessionId=d.session_id;
            updateProgress(40,'File uploaded, starting OCR...','ocr');
            await processOCR(currentSessionId);
        }catch(e){
            console.error(e);
            showStatus('error','Upload Failed',e.message);
            showLoading(false);
            setProcessingState(false);
            showProgress(false);
            ModernAlert.error(e.message);
        }
    }

    // ==================== STEP 3: PROCESS OCR ====================
    
    async function processOCR(sessionId){
        if(!sessionId)return;
        updateProgress(50,'Running OCR...','ocr');
        showLoading(true,'Processing OCR','AI extracting data...');
        try{
            const r=await fetch(`${routes.processOcr}/${sessionId}`,{method:'POST',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json','Content-Type':'application/json'}});
            const d=await r.json();
            if(!r.ok)throw new Error(d.message||'OCR failed');
            extractedData = d.extracted_data;
            const confidence = d.confidence || 0;
            const missingFields = d.missing_fields || [];
            
            updateProgress(100,'OCR Complete!','complete');
            showLoading(false);
            setProcessingState(false);
            showProgress(false);
            displayExtractedData(extractedData, confidence, missingFields);
            ModernAlert.success('OCR completed! Review the extracted data and click "Continue".', 'OCR Complete');
        }catch(e){
            console.error(e);
            showStatus('warning','Partial',e.message);
            showLoading(false);
            if(extractedData)displayExtractedData(extractedData, 0, []);
            setProcessingState(false);
            showProgress(false);
            ModernAlert.warning(e.message);
        }
    }

    // ==================== STEP 4 & 5: EDIT & CONTINUE ====================
    
    async function editAndContinue() {
        if (!currentSessionId) {
            ModernAlert.error('No session found. Please upload a document first.', 'Session Error');
            return;
        }
        
        const editedData = {};
        document.querySelectorAll('[data-field]').forEach(input => {
            const field = input.getAttribute('data-field');
            editedData[field] = input.value;
        });
        
        const finalData = { ...extractedData, ...editedData };
        
        const requiredFields = ['deceased_name', 'deceased_nric', 'death_date', 'death_place', 'contact_email', 'contact_phone', 'residential_address'];
        let missingRequired = [];
        
        for (const field of requiredFields) {
            if (!finalData[field] || finalData[field].trim() === '') {
                missingRequired.push(field);
                const input = document.querySelector(`[data-field="${field}"]`);
                if (input) input.classList.add('field-missing');
            }
        }
        
        if (missingRequired.length > 0) {
            ModernAlert.warning('Please fill in all required fields highlighted below.', 'Missing Required Fields');
            return;
        }
        
        showLoading(true, 'Saving data...', 'Searching database for matching records');
        
        try {
            const payload = { extracted_data: finalData };
            const response = await fetch(`${routes.editAndContinue}/${currentSessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to save data');
            }
            showLoading(false);
            if (data.needs_captcha) {
                ModernAlert.info('Database search completed. Please complete CAPTCHA verification to continue.', 'Security Check');
                showCaptchaModal();
            } else {
                throw new Error('Invalid response from server');
            }
        } catch (error) {
            console.error('Edit and continue error:', error);
            showLoading(false);
            ModernAlert.error(error.message, 'Error');
        }
    }

    // ==================== STEP 6 & 7: CAPTCHA VERIFICATION & START APPROVAL POLLING ====================
    
    async function verifyCaptchaAndContinue() {
        const userInput = document.getElementById('captchaInput').value.trim();
        if (userInput !== currentCaptchaCode) {
            ModernAlert.error('Invalid verification code. Please try again.', 'Verification Failed');
            generateCaptcha();
            return false;
        }
        hideCaptchaModal();
        showLoading(true, 'Verifying CAPTCHA...', 'Please wait');
        try {
            const response = await fetch(`${routes.verifyCaptcha}/${currentSessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ captcha_token: 'verified_' + Date.now() })
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'CAPTCHA verification failed');
            }
            showLoading(false);
            ModernAlert.info('Verification passed. Your report is now pending admin approval.', 'Pending Approval');
            startApprovalPolling(currentSessionId);
        } catch (error) {
            console.error('Verification error:', error);
            showLoading(false);
            ModernAlert.error(error.message, 'Verification Error');
            showCaptchaModal();
        }
    }

    // ==================== CANCEL PROCESSING ====================
    
    async function cancelProcessing(){
        if(!currentSessionId)return;
        try{
            await fetch(`/instant-estate/session/${currentSessionId}/cancel`,{method:'POST',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}});
        }catch(e){}
        showLoading(false);
        setProcessingState(false);
        showProgress(false);
        if (approvalPollingInterval) {
            clearInterval(approvalPollingInterval);
            approvalPollingInterval = null;
        }
        approvalHandled = false;
        ModernAlert.info('Processing cancelled.');
    }

    // ==================== EVENT LISTENERS ====================
    
    const setupModals = () => {
        const closeQualityBtn = document.getElementById('closeQualityModal');
        const proceedToUpload = document.getElementById('proceedToUploadBtn');
        if (closeQualityBtn) closeQualityBtn.addEventListener('click', hideQualityAlert);
        if (proceedToUpload) proceedToUpload.addEventListener('click', hideQualityAlert);
        setTimeout(() => { showQualityAlert(); }, 500);
        
        const closeCaptchaBtn = document.getElementById('closeCaptchaModal');
        const cancelCaptchaBtn = document.getElementById('cancelCaptchaBtn');
        const verifyCaptchaBtn = document.getElementById('verifyCaptchaBtn');
        const refreshCaptchaBtn = document.getElementById('refreshCaptcha');
        if (closeCaptchaBtn) closeCaptchaBtn.addEventListener('click', hideCaptchaModal);
        if (cancelCaptchaBtn) cancelCaptchaBtn.addEventListener('click', hideCaptchaModal);
        if (verifyCaptchaBtn) verifyCaptchaBtn.addEventListener('click', verifyCaptchaAndContinue);
        if (refreshCaptchaBtn) refreshCaptchaBtn.addEventListener('click', generateCaptcha);
        
        const closeEmailReportBtn = document.getElementById('closeEmailReportModal');
        const skipEmailReportBtn = document.getElementById('skipEmailReportBtn');
        const confirmEmailReportBtn = document.getElementById('confirmEmailReportBtn');
        if (closeEmailReportBtn) closeEmailReportBtn.addEventListener('click', hideEmailReportModal);
        if (skipEmailReportBtn) skipEmailReportBtn.addEventListener('click', hideEmailReportModal);
        if (confirmEmailReportBtn) confirmEmailReportBtn.addEventListener('click', sendReportLinkToEmail);
        
        elements.qualityModal.addEventListener('click', (e) => { if (e.target === elements.qualityModal) hideQualityAlert(); });
        elements.captchaModal.addEventListener('click', (e) => { if (e.target === elements.captchaModal) hideCaptchaModal(); });
        elements.emailReportModal.addEventListener('click', (e) => { if (e.target === elements.emailReportModal) hideEmailReportModal(); });
    };
    
    elements.uploadZone.addEventListener('click',()=>elements.fileInput.click());
    elements.fileInput.addEventListener('change',e=>{if(e.target.files&&e.target.files[0])handleFileSelect(e.target.files[0]);});
    elements.uploadZone.addEventListener('dragover',e=>{e.preventDefault();elements.uploadZone.classList.add('dragover');});
    elements.uploadZone.addEventListener('dragleave',()=>elements.uploadZone.classList.remove('dragover'));
    elements.uploadZone.addEventListener('drop',e=>{e.preventDefault();elements.uploadZone.classList.remove('dragover');if(e.dataTransfer.files[0])handleFileSelect(e.dataTransfer.files[0]);});
    elements.removeFileBtn.addEventListener('click',clearFile);
    elements.processBtn.addEventListener('click',uploadFile);
    elements.cancelBtn.addEventListener('click',cancelProcessing);
    elements.editAndContinueBtn.addEventListener('click', editAndContinue);
    elements.newUploadBtn.addEventListener('click',()=>{clearFile();window.scrollTo({top:0,behavior:'smooth'});});
    
    setupModals();

    console.log('✅ Instant Estate ready - Guest mode enabled. Login required only for email requests.');
    console.log('🔧 Flow: Quality Alert → Upload → OCR → Edit → Database Match → CAPTCHA Verification → Admin Approval Polling → Request Report Button → Open Report in New Tab → Ask to Send Link via Email');
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/instant-estate/index.blade.php ENDPATH**/ ?>