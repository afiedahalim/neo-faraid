

<?php $__env->startSection('title', 'Calculation History'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    
    * {
        font-family: 'Poppins', sans-serif !important;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
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
        --radius: 12px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
        --primary: #1a5fb4;
        --secondary: #2d7ad6;
    }
    
    body {
        font-family: 'Poppins', sans-serif !important;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
        color: var(--text-primary);
        overflow-x: hidden;
    }
    
    /* ===== SUCCESS MESSAGE ===== */
    .alert-success-message {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: white;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-lg);
        animation: slideDown 0.5s ease;
        position: relative;
        overflow: hidden;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 2rem;
    }
    
    .alert-success-message .alert-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .alert-success-message .alert-icon {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }
    
    .alert-success-message .alert-text {
        flex: 1;
    }
    
    .alert-success-message h4 {
        margin: 0 0 0.5rem 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .alert-success-message p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .alert-success-message .close-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: var(--transition);
    }
    
    .alert-success-message .close-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* ===== CONFIRMATION MODAL ===== */
    .confirmation-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    
    .confirmation-modal.active {
        opacity: 1;
        visibility: visible;
    }
    
    .confirmation-content {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        padding: 2.5rem;
        max-width: 500px;
        width: 90%;
        box-shadow: var(--shadow-lg);
        transform: translateY(-20px);
        transition: var(--transition);
    }
    
    .confirmation-modal.active .confirmation-content {
        transform: translateY(0);
    }
    
    .confirmation-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--warning-color) 0%, #c82333 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .confirmation-icon svg {
        width: 32px;
        height: 32px;
        color: var(--white);
    }
    
    .confirmation-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .confirmation-message {
        color: var(--text-light);
        text-align: center;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .confirmation-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .confirmation-btn {
        padding: 0.875rem 2rem;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        border: none;
        font-size: 1rem;
        min-width: 120px;
        font-family: inherit;
    }
    
    .confirmation-btn.confirm {
        background: linear-gradient(135deg, var(--danger-color) 0%, #c82333 100%);
        color: var(--white);
    }
    
    .confirmation-btn.confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(220, 53, 69, 0.3);
    }
    
    .confirmation-btn.cancel {
        background: var(--light-bg);
        color: var(--text-primary);
    }
    
    .confirmation-btn.cancel:hover {
        background: var(--white);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    
    /* ===== HISTORY HEADER STYLES ===== */
    .history-header {
        min-height: 50vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
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
        position: absolute;
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .history-header .animated-bg .bg-circle-3 {
        position: absolute;
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

    .history-header .shape-1 {
        width: 40px;
        height: 40px;
        top: 20%;
        left: 10%;
        animation-name: float-1;
    }

    .history-header .shape-2 {
        width: 25px;
        height: 25px;
        top: 60%;
        left: 85%;
        animation-name: float-2;
        animation-delay: 1s;
    }

    .history-header .shape-3 {
        width: 35px;
        height: 35px;
        top: 75%;
        left: 15%;
        animation-name: float-3;
        animation-delay: 0.5s;
    }

    .history-header .shape-4 {
        width: 20px;
        height: 20px;
        top: 30%;
        left: 70%;
        animation-name: float-4;
        animation-delay: 1.5s;
    }

    .history-header .animated-speed-fast .shape {
        animation-duration: 4s !important;
    }

    .history-header .animated-speed-fast .bg-circle-3 {
        animation-duration: 3s !important;
    }

    .history-header .hero-container {
        position: relative;
        z-index: 2;
        padding-top: 4rem;
        padding-bottom: 4rem;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding-left: 2rem;
        padding-right: 2rem;
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
        font-size: 3.5rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
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
        font-size: 1.25rem;
        max-width: 600px;
        margin-bottom: 2.5rem;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
    }

    /* ===== GLASS MORPHISM CONTAINER ===== */
    .glass-container {
        max-width: 1200px;
        margin: -4rem auto 0;
        padding: 0 2rem 4rem;
        position: relative;
        z-index: 10;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
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
    
    .card-body {
        padding: 2rem;
    }

    /* ===== STATS SECTION (ALIGNED WITH SEARCH) ===== */
    .stats-section {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-radius: var(--radius-lg);
        border: 1px solid rgba(203, 213, 225, 0.5);
        overflow: hidden;
        margin-bottom: 1.75rem;
        transition: var(--transition);
    }

    .stats-section:hover {
        box-shadow: var(--shadow-lg);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid var(--gray-200);
        background: rgba(248, 250, 252, 0.7);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-icon {
        width: 1.2rem;
        height: 1.2rem;
        color: var(--primary);
    }

    .section-title h2 {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
        letter-spacing: -0.3px;
    }

    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        padding: 1.75rem;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.9);
        border-radius: var(--radius);
        border: 1px solid var(--gray-200);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
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
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        background: var(--primary-light);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 26px;
        height: 26px;
        color: var(--primary);
    }

    .stat-info {
        flex: 1;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    .latest-card {
        margin: 0 1.75rem 1.75rem;
        padding: 1.5rem;
        background: var(--gray-50);
        border-radius: var(--radius);
        border: 1px solid var(--gray-200);
    }

    .latest-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .latest-icon {
        width: 20px;
        height: 20px;
        color: var(--primary);
    }

    .latest-header h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
    }

    .latest-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .latest-info {
        flex: 1;
    }

    .latest-name {
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
    }

    .latest-date {
        font-size: 0.8rem;
        color: var(--gray-500);
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.2rem;
        background: var(--primary);
        color: white;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-view svg {
        width: 16px;
        height: 16px;
        color: white;
    }

    .btn-view:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 95, 180, 0.3);
    }
    
    /* ===== SEARCH SECTION ===== */
    .search-section {
        background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%);
        border-radius: var(--border-radius-lg);
        padding: 2.5rem;
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    
    .search-decoration {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }
    
    .filter-form {
        max-width: 100%;
        margin: 0 auto;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 1rem;
        align-items: start;
        margin-bottom: 1.5rem;
    }
    
    .search-input-container {
        position: relative;
    }
    
    #calculation-search {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        font-size: 1rem;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
        color: var(--gray-900);
    }
    
    #calculation-search:focus {
        outline: none;
        border-color: var(--primary-color);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(26, 95, 180, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: var(--gray-500);
    }
    
    .date-inputs {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .date-input {
        width: 100%;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        font-size: 1rem;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
        color: var(--gray-900);
    }
    
    .date-input:focus {
        outline: none;
        border-color: var(--primary-color);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(26, 95, 180, 0.1);
    }
    
    /* ===== RESULTS TABLE ===== */
    .results-container {
        background: var(--white);
        border-radius: var(--border-radius-md);
        overflow: hidden;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-md);
    }
    
    .results-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Poppins', sans-serif;
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
        padding: 1.25rem 1.5rem;
        color: var(--gray-700);
        font-weight: 500;
        vertical-align: middle;
    }
    
    /* ===== CALCULATION INFO STYLES ===== */
    .calculation-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .calculation-avatar {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .calculation-details h4 {
        color: var(--primary-color);
        margin: 0 0 0.25rem 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .calculation-details p {
        color: var(--text-light);
        margin: 0;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .date-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .date-info .date {
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .date-info .time {
        font-size: 0.9rem;
        color: var(--text-light);
    }
    
    .amount-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .amount-info .amount {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
    }
    
    .amount-info .net-amount {
        font-size: 0.9rem;
        color: var(--text-light);
    }
    
    .heirs-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .heirs-count {
        background: linear-gradient(135deg, var(--accent-color) 0%, #ffb347 100%);
        color: #000;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 800;
        display: inline-block;
    }
    
    /* ===== ACTION BUTTONS ===== */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius-sm);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: var(--transition);
        cursor: pointer;
        color: white;
        text-decoration: none;
    }
    
    .view-btn {
        background: var(--primary-color);
    }
    
    .edit-btn {
        background: var(--accent-color);
        color: #000;
    }
    
    .delete-btn {
        background: var(--danger-color);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    /* ===== MODERN BUTTONS ===== */
    .btn-group-modern {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--gray-200);
    }
    
    .btn {
        padding: 1rem 2rem;
        border: none;
        border-radius: var(--border-radius-md);
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-family: 'Poppins', sans-serif;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        min-width: 140px;
    }
    
    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        opacity: 0;
        transition: var(--transition);
        z-index: -1;
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
    
    .btn-primary:active {
        transform: translateY(-1px);
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
        box-shadow: var(--shadow-md);
    }
    
    .btn-outline-primary {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: var(--white);
        transform: translateY(-2px);
    }
    
    .btn-light {
        background: var(--white);
        color: var(--primary-color);
        border: 2px solid var(--gray-200);
    }
    
    .btn-outline-light {
        background: transparent;
        color: var(--white);
        border: 2px solid rgba(255,255,255,0.3);
    }
    
    .btn-outline-light:hover {
        background: rgba(255,255,255,0.1);
        border-color: var(--white);
    }
    
    .btn-ghost {
        background: transparent;
        color: var(--gray-700);
        border: 2px solid transparent;
    }
    
    .btn-ghost:hover {
        background: var(--gray-100);
        transform: translateY(-2px);
    }
    
    .btn-icon {
        width: 20px;
        height: 20px;
        transition: var(--transition);
    }
    
    .btn:hover .btn-icon {
        transform: translateX(3px);
    }
    
    .btn-secondary:hover .btn-icon,
    .btn-ghost:hover .btn-icon {
        transform: translateX(-3px);
    }
    
    /* ===== PAGINATION ===== */
    .pagination-section {
        border-top: 1px solid var(--gray-200);
        padding-top: 2rem;
        margin-top: 2rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .pagination-info {
        color: var(--text-light);
        font-weight: 500;
    }
    
    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pagination-link,
    .pagination-number,
    .pagination-disabled {
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        border: 2px solid transparent;
    }
    
    .pagination-link {
        color: var(--primary-color);
        border-color: var(--light-border);
    }
    
    .pagination-link:hover {
        background: var(--light-bg);
        border-color: var(--primary-color);
    }
    
    .pagination-number {
        color: var(--text-light);
        border-color: transparent;
    }
    
    .pagination-number.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .pagination-disabled {
        color: var(--light-border);
        font-weight: 500;
        cursor: not-allowed;
    }
    
    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        border: 2px dashed var(--light-border);
        border-radius: var(--border-radius-lg);
        margin-top: 2rem;
        background: var(--white);
    }
    
    .empty-icon {
        font-size: 4rem;
        color: var(--light-border);
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .empty-state h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .empty-state p {
        color: var(--text-light);
        margin-bottom: 2rem;
        font-weight: 500;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* ===== HELP SECTION ===== */
    .help-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    
    .tips-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .tip-item {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        background: var(--white);
        border-radius: var(--border-radius-md);
        border: 1px solid var(--light-border);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .tip-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        opacity: 0;
        transition: var(--transition);
    }
    
    .tip-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
    
    .tip-item:hover::before {
        opacity: 1;
    }
    
    .tip-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .tip-icon svg {
        width: 24px;
        height: 24px;
        color: white;
    }
    
    .tip-item h4 {
        color: var(--primary-color);
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .tip-item p {
        color: var(--text-light);
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .help-cta {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: var(--border-radius-lg);
        padding: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .cta-content h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: white;
    }
    
    .cta-content p {
        opacity: 0.9;
        margin-bottom: 2rem;
        font-size: 1rem;
        color: white;
        font-weight: 500;
        line-height: 1.6;
    }
    
    /* ===== MODERN ALERT STYLES ===== */
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
    
    .modern-alert.success .alert-icon {
        color: var(--success-color);
    }
    
    .modern-alert.warning {
        border-left-color: var(--warning-color);
        background: rgba(255, 193, 7, 0.05);
    }
    
    .modern-alert.warning .alert-icon {
        color: var(--warning-color);
    }
    
    .modern-alert.error {
        border-left-color: var(--danger-color);
        background: rgba(220, 53, 69, 0.05);
    }
    
    .modern-alert.error .alert-icon {
        color: var(--danger-color);
    }
    
    .modern-alert.info {
        border-left-color: var(--primary-color);
        background: rgba(26, 95, 180, 0.05);
    }
    
    .modern-alert.info .alert-icon {
        color: var(--primary-color);
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
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 1024px) {
        .history-header .hero-title { font-size: 2.75rem; }
        .history-header { min-height: 40vh !important; }
        .glass-container { padding: 0 1.5rem 3rem; }
        .modern-alert { min-width: 300px; }
        .help-grid { grid-template-columns: 1fr; }
    }
    
    @media (max-width: 768px) {
        .history-header .hero-title { font-size: 2.25rem; }
        .history-header .hero-subtitle { font-size: 1.1rem; }
        .history-header { min-height: 35vh !important; }
        .filter-grid { grid-template-columns: 1fr; gap: 1rem; }
        .tips-grid { grid-template-columns: 1fr; }
        .card-body { padding: 1.5rem; }
        .results-table { display: block; overflow-x: auto; }
        .action-buttons { flex-wrap: wrap; justify-content: center; }
        .pagination-section { flex-direction: column; align-items: center; }
        .btn-group-modern { flex-direction: column; }
        .btn { width: 100%; }
        .modern-alert-container { left: 20px; right: 20px; max-width: none; }
        .modern-alert { min-width: auto; width: 100%; }
        .history-header .shape { display: none !important; }
        .history-header .bg-circle-3 { display: none !important; }
        .stats-grid { grid-template-columns: 1fr; gap: 1rem; padding: 1.25rem; }
        .stat-card { padding: 1rem; }
        .latest-card { margin: 0 1.25rem 1.25rem; padding: 1rem; }
        .latest-content { flex-direction: column; align-items: flex-start; }
        .btn-view { align-self: stretch; justify-content: center; }
    }
    
    @media (max-width: 480px) {
        .history-header .hero-title { font-size: 2rem; }
        .history-header .hero-subtitle { font-size: 1rem; }
        .history-header { min-height: 30vh !important; }
        .glass-container { padding: 0 1rem 2rem; }
        .card-header { padding: 1.25rem 1.5rem; flex-wrap: wrap; }
        .card-title { font-size: 1.25rem; }
        .results-table th, .results-table td { padding: 0.875rem 1rem; font-size: 0.875rem; }
        .btn { padding: 0.875rem 1.5rem; font-size: 0.9rem; }
        .modern-alert { padding: 1rem; }
        .help-cta { padding: 1.5rem; }
    }
</style>

<!-- Success Message Display -->
<?php if(session('success')): ?>
<div class="alert-success-message">
    <div class="alert-content">
        <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="alert-text">
            <h4>Success!</h4>
            <p><?php echo e(session('success')); ?></p>
        </div>
        <button class="close-btn" onclick="this.parentElement.parentElement.style.display='none'">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Confirmation Modal -->
<div class="confirmation-modal" id="confirmation-modal">
    <div class="confirmation-content">
        <div class="confirmation-icon">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h2 class="confirmation-title" id="modal-title">Confirm Delete</h2>
        <p class="confirmation-message" id="modal-message">
            Are you sure you want to delete this calculation? This action cannot be undone.
        </p>
        <div class="confirmation-actions">
            <button type="button" class="confirmation-btn cancel" id="modal-cancel-btn">Cancel</button>
            <button type="button" class="confirmation-btn confirm" id="modal-confirm-btn">Delete</button>
        </div>
    </div>
</div>

<!-- Modern Alert Container -->
<div class="modern-alert-container" id="alertContainer"></div>

<!-- History Header -->
<header class="hero-section history-header">
    <div class="hero-bg-elements animated-bg animated-speed-fast">
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
            Calculation <span class="hero-highlight">History</span>
        </h1>
        
        <p class="hero-subtitle">
            Review all your previous inheritance calculations. Your data is securely stored and accessible anytime.
        </p>
    </div>
</header>

<!-- Main Container -->
<div class="glass-container">
    <!-- Stats Section (ALIGNED WITH SEARCH) -->
    <div class="stats-section glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h2 class="card-title">Your Statistics</h2>
            <span class="card-badge">Overview</span>
        </div>

        <div class="card-body">
            <div class="stats-grid">
                <!-- Total Calculations -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo e(number_format((int)($stats['total_calculations'] ?? 0))); ?></div>
                        <div class="stat-label">Total Calculations</div>
                    </div>
                </div>

                <!-- Total Assets -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">RM <?php echo e(number_format((float)($stats['total_assets'] ?? 0), 2)); ?></div>
                        <div class="stat-label">Total Assets</div>
                    </div>
                </div>

                <!-- Total Heirs -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo e(number_format((int)($stats['total_heirs'] ?? 0))); ?></div>
                        <div class="stat-label">Total Heirs</div>
                    </div>
                </div>

                <!-- Average Assets -->
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">RM <?php echo e(number_format((float)($stats['average_assets'] ?? 0), 2)); ?></div>
                        <div class="stat-label">Average Assets</div>
                    </div>
                </div>
            </div>

            <!-- Latest Calculation -->
            <?php if(isset($stats['latest_calculation']) && $stats['latest_calculation']): ?>
            <div class="latest-card" style="margin: 0; border-top: 1px solid var(--gray-200); border-radius: 0;">
                <div class="latest-header">
                    <svg class="latest-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4>Latest Calculation</h4>
                </div>
                <div class="latest-content">
                    <div class="latest-info">
                        <div class="latest-name"><?php echo e($stats['latest_calculation']->deceased_name); ?></div>
                        <div class="latest-date"><?php echo e($stats['latest_calculation']->created_at->format('d M Y, h:i A')); ?></div>
                    </div>
                    <a href="<?php echo e(route('calculator.show', $stats['latest_calculation']->id)); ?>" class="btn-view">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Details
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Search and Filter Section -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h2 class="card-title">Search & Filter</h2>
            <span class="card-badge">Find Calculations</span>
        </div>
        
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('calculator.history')); ?>" class="filter-form">
                <div class="filter-grid">
                    <div class="search-input-container">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input 
                            type="text" 
                            id="calculation-search" 
                            name="search" 
                            placeholder="Search by name, ID, or hash..." 
                            value="<?php echo e(request('search')); ?>"
                            autocomplete="off"
                        >
                    </div>
                    
                    <div class="date-inputs">
                        <input 
                            type="date" 
                            class="date-input" 
                            name="from_date" 
                            value="<?php echo e(request('from_date')); ?>"
                            placeholder="From date"
                        >
                    </div>
                    
                    <div class="date-inputs">
                        <input 
                            type="date" 
                            class="date-input" 
                            name="to_date" 
                            value="<?php echo e(request('to_date')); ?>"
                            placeholder="To date"
                        >
                    </div>
                </div>
                
                <div class="btn-group-modern" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--light-border);">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Apply Filters
                    </button>
                    
                    <a href="<?php echo e(route('calculator.history')); ?>" class="btn btn-secondary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear Filters
                    </a>
                    
                    <?php if(request()->hasAny(['search', 'from_date', 'to_date'])): ?>
                    <div style="width: 100%; text-align: center; margin-top: 0.5rem;">
                        <small style="color: var(--primary-color); font-weight: 500;">
                            Showing filtered results
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Calculations Section -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h2 class="card-title">Your Calculations</h2>
            <span class="card-badge"><?php echo e($calculations->count() ?? 0); ?> Records</span>
        </div>
        
        <div class="card-body">
            <?php if(isset($calculations) && $calculations->count() > 0): ?>
            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Deceased Name</th>
                            <th>Date</th>
                            <th>Total Assets</th>
                            <th>Heirs</th>
                            <th>Scenario</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $calculations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calculation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="calculation-info">
                                    <div class="calculation-avatar">
                                        <svg fill="white" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v1.2c0 .66.54 1.2 1.2 1.2h16.8c.66 0 1.2-.54 1.2-1.2v-1.2c0-3.2-6.4-4.8-9.6-4.8z"/>
                                        </svg>
                                    </div>
                                    <div class="calculation-details">
                                        <h4><?php echo e($calculation->deceased_name); ?></h4>
                                        <p>ID: <?php echo e($calculation->id); ?> • <?php echo e(ucfirst($calculation->deceased_gender ?? 'N/A')); ?></p>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="date-info">
                                    <span class="date"><?php echo e($calculation->created_at->format('d M Y')); ?></span>
                                    <span class="time"><?php echo e($calculation->created_at->format('h:i A')); ?></span>
                                </div>
                            </td>
                            
                            <td>
                                <div class="amount-info">
                                    <span class="amount">RM <?php echo e(number_format($calculation->total_assets, 2)); ?></span>
                                    <span class="net-amount">Net: RM <?php echo e(number_format($calculation->net_assets ?? 0, 2)); ?></span>
                                </div>
                            </td>
                            
                            <td>
                                <div class="heirs-info">
                                    <span class="heirs-count"><?php echo e($calculation->total_heirs ?? 0); ?></span>
                                    <span>heir<?php echo e(($calculation->total_heirs ?? 0) > 1 ? 's' : ''); ?></span>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge">Scenario <?php echo e($calculation->scenario_number ?? 1); ?></span>
                            </td>
                            
                            <td class="text-end">
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('calculator.show', $calculation->id)); ?>" 
                                       class="action-btn view-btn"
                                       title="View Details">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="<?php echo e(route('calculator.edit', $calculation->id)); ?>" 
                                       class="action-btn edit-btn"
                                       title="Update">
                                        <svg fill="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                        </svg>
                                    </a>
                                    
                                    <button type="button" 
                                            class="action-btn delete-btn"
                                            title="Delete"
                                            data-calculation-id="<?php echo e($calculation->id); ?>"
                                            data-deceased-name="<?php echo e($calculation->deceased_name); ?>"
                                            onclick="confirmDelete(this)">
                                        <svg fill="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                        </svg>
                                    </button>
                                    
                                    <form action="<?php echo e(route('calculator.destroy', $calculation->id)); ?>" 
                                          method="POST" 
                                          id="delete-form-<?php echo e($calculation->id); ?>"
                                          style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </div>
                             </natal
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($calculations->hasPages()): ?>
            <div class="pagination-section">
                <div class="pagination-info">
                    Showing <?php echo e($calculations->firstItem()); ?> to <?php echo e($calculations->lastItem()); ?> 
                    of <?php echo e($calculations->total()); ?> calculations
                </div>
                
                <nav class="pagination-nav">
                    <?php if($calculations->onFirstPage()): ?>
                    <span class="pagination-disabled">Previous</span>
                    <?php else: ?>
                    <a href="<?php echo e($calculations->previousPageUrl()); ?>" class="pagination-link">Previous</a>
                    <?php endif; ?>
                    
                    <?php $__currentLoopData = $calculations->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($url); ?>" class="pagination-number <?php echo e($page == $calculations->currentPage() ? 'active' : ''); ?>">
                        <?php echo e($page); ?>

                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if($calculations->hasMorePages()): ?>
                    <a href="<?php echo e($calculations->nextPageUrl()); ?>" class="pagination-link">Next</a>
                    <?php else: ?>
                    <span class="pagination-disabled">Next</span>
                    <?php endif; ?>
                </nav>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 64px; height: 64px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3>No Calculations Found</h3>
            <p>
                <?php if(request('search') || request('from_date') || request('to_date')): ?>
                No calculations match your search criteria. Try different filters or clear them to see all calculations.
                <?php else: ?>
                You haven't performed any inheritance calculations yet. Start your first calculation to see it here.
                <?php endif; ?>
            </p>
            <a href="<?php echo e(route('calculator.index')); ?>" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Start Your First Calculation
            </a>
        </div>
        <?php endif; ?>
        </div>
    </div>
    
    <!-- Help Section -->
    <?php if(isset($calculations) && $calculations->count() > 0): ?>
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="card-title">Help & Tips</h2>
            <span class="card-badge">Guidance</span>
        </div>
        
        <div class="card-body">
            <div class="help-grid">
                <div class="help-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Tips for Managing Calculations
                    </h3>
                    <div class="tips-grid">
                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4>Save as PDF</h4>
                                <p>Always save important calculations as PDF for offline access and sharing.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div>
                                <h4>Update Regularly</h4>
                                <p>Update calculations when asset values change or family circumstances evolve.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                            </div>
                            <div>
                                <h4>Share with Family</h4>
                                <p>Share calculation results with family members for transparency.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div>
                                <h4>Clean Up Old Data</h4>
                                <p>Regularly delete outdated calculations to keep your history organized.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="help-cta">
                    <div class="cta-content">
                        <h3><strong>Need Help?</strong></h3>
                        <p>
                            If you have questions about your calculations or need assistance understanding the distribution, 
                            our support team is here to help.
                        </p>
                        <div class="btn-group-modern" style="border-top: none; margin-top: 1rem;">
                            <a href="<?php echo e(route('faq.index')); ?>" class="btn btn-light">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Visit FAQ
                            </a>
                            <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline-light">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Navigation Buttons -->
    <div class="btn-group-modern" style="margin-top: 0;">
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
        <a href="<?php echo e(route('calculator.index')); ?>" class="btn btn-primary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            New Calculation
        </a>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Confirmation Modal Class
class ConfirmationModal {
    constructor(modalId) {
        this.modal = document.getElementById(modalId);
        this.confirmBtn = document.getElementById('modal-confirm-btn');
        this.cancelBtn = document.getElementById('modal-cancel-btn');
        this.title = document.getElementById('modal-title');
        this.message = document.getElementById('modal-message');
        this.pendingDelete = null;
        
        this.init();
    }

    init() {
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });

        this.cancelBtn.addEventListener('click', () => this.hide());

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                this.hide();
            }
        });
    }

    show(calculationId, deceasedName) {
        this.pendingDelete = calculationId;
        this.title.textContent = 'Confirm Delete';
        this.message.innerHTML = `Are you sure you want to delete the calculation for <strong>"${deceasedName}"</strong>? This action cannot be undone.`;
        
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            this.confirmBtn.focus();
        }, 100);
        
        return new Promise((resolve) => {
            const confirmHandler = () => {
                this.hide();
                resolve(true);
            };
            
            const cancelHandler = () => {
                this.hide();
                resolve(false);
            };
            
            this.confirmBtn.addEventListener('click', confirmHandler, { once: true });
            this.cancelBtn.addEventListener('click', cancelHandler, { once: true });
        });
    }

    hide() {
        this.modal.classList.remove('active');
        document.body.style.overflow = '';
        this.pendingDelete = null;
    }
}

// Modern Alert System
class ModernAlert {
    static show({ type = 'info', title, message, duration = 4000 }) {
        const container = document.getElementById('alertContainer');
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
                <div class="alert-title">${title}</div>
                <div class="alert-message">${message}</div>
            </div>
            <button class="alert-close" onclick="ModernAlert.close('${alertId}')">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        container.appendChild(alertEl);
        
        setTimeout(() => {
            alertEl.classList.add('show');
        }, 10);
        
        if (duration > 0) {
            setTimeout(() => {
                ModernAlert.close(alertId);
            }, duration);
        }
        
        return alertId;
    }
    
    static close(alertId) {
        const alertEl = document.getElementById(alertId);
        if (alertEl) {
            alertEl.classList.remove('show');
            alertEl.classList.add('hide');
            
            setTimeout(() => {
                if (alertEl.parentNode) {
                    alertEl.parentNode.removeChild(alertEl);
                }
            }, 500);
        }
    }
    
    static success(message, title = 'Success') {
        return this.show({ type: 'success', title, message });
    }
    
    static warning(message, title = 'Warning') {
        return this.show({ type: 'warning', title, message });
    }
    
    static error(message, title = 'Error') {
        return this.show({ type: 'error', title, message });
    }
    
    static info(message, title = 'Info') {
        return this.show({ type: 'info', title, message });
    }
}

// Global function to confirm deletion using modal
async function confirmDelete(button) {
    const calculationId = button.getAttribute('data-calculation-id');
    const deceasedName = button.getAttribute('data-deceased-name');
    
    const confirmed = await confirmationModal.show(calculationId, deceasedName);
    
    if (confirmed) {
        ModernAlert.info('Deleting calculation...', 'Processing');
        
        const form = document.getElementById(`delete-form-${calculationId}`);
        if (form) {
            form.submit();
        } else {
            console.error('Delete form not found');
            ModernAlert.error('Delete form not found', 'Error');
        }
    }
}

// Main Application
document.addEventListener('DOMContentLoaded', function() {
    window.confirmationModal = new ConfirmationModal('confirmation-modal');
    
    <?php if(session('success')): ?>
    ModernAlert.success("<?php echo e(session('success')); ?>");
    <?php endif; ?>
    
    <?php if(session('error')): ?>
    ModernAlert.error("<?php echo e(session('error')); ?>");
    <?php endif; ?>
    
    <?php if(session('warning')): ?>
    ModernAlert.warning("<?php echo e(session('warning')); ?>");
    <?php endif; ?>
    
    <?php if(session('info')): ?>
    ModernAlert.info("<?php echo e(session('info')); ?>");
    <?php endif; ?>
    
    // Table row animations
    const tableRows = document.querySelectorAll('.results-table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Card hover effects
    const cards = document.querySelectorAll('.glass-card, .stat-card, .tip-item');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (this.classList.contains('stat-card') || this.classList.contains('tip-item')) {
                this.style.transform = 'translateY(-5px)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (this.classList.contains('stat-card') || this.classList.contains('tip-item')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
    
    // Button hover effects
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            if (!this.disabled) {
                this.style.transform = 'translateY(-2px)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Action buttons hover effect
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Date input handling
    const fromDateInput = document.querySelector('input[name="from_date"]');
    const toDateInput = document.querySelector('input[name="to_date"]');
    
    if (fromDateInput && toDateInput) {
        fromDateInput.max = new Date().toISOString().split('T')[0];
        
        fromDateInput.addEventListener('change', function() {
            toDateInput.min = this.value;
            toDateInput.max = new Date().toISOString().split('T')[0];
        });
        
        toDateInput.addEventListener('change', function() {
            if (fromDateInput.value && this.value < fromDateInput.value) {
                this.value = fromDateInput.value;
                ModernAlert.warning('To date cannot be earlier than from date');
            }
        });
    }
    
    // Search input debounce
    const searchInput = document.getElementById('calculation-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length === 0 || this.value.length >= 2) {
                    const url = new URL(window.location.href);
                    if (this.value) {
                        url.searchParams.set('search', this.value);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.location.href = url.toString();
                }
            }, 500);
        });
    }
    
    // Responsive table handling
    function setupMobileTable() {
        if (window.innerWidth <= 768) {
            const table = document.querySelector('.results-table');
            if (!table) return;
            
            const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    cell.setAttribute('data-label', headers[index]);
                });
            });
        }
    }
    
    setupMobileTable();
    window.addEventListener('resize', setupMobileTable);
    
    window.ModernAlert = ModernAlert;
    window.confirmDelete = confirmDelete;
    
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.3s ease';
    
    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 50);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/calculator/history.blade.php ENDPATH**/ ?>