@extends('layouts.app')

@section('title', 'Calculation Details')

@section('content')
<!-- Poppins Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Viz.js for Graphviz -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viz.js/2.1.2/viz.js" integrity="sha512-Mk7+95H5MxVfX8JqJxH8O7oN0QkHrQd6F+JFQ0d5q5Ff5h5H5v5F5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viz.js/2.1.2/full.render.js" integrity="sha512-Mk7+95H5MxVfX8JqJxH8O7oN0QkHrQd6F+JFQ0d5q5Ff5h5H5v5F5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5" crossorigin="anonymous"></script>

<!-- html2canvas & jsPDF -->
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<style>
    /* ===== ALL EXISTING STYLES REMAIN EXACTLY THE SAME AS INDEX.BLADE.PHP ===== */
    /* (Copy all CSS from index.blade.php here to ensure identical styling) */
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
        font-family: 'Poppins', sans-serif !important;
    }
    
    input, select, textarea, button, .btn, .form-control, .modal, .alert {
        font-family: 'Poppins', sans-serif !important;
    }
    
    .calculation-header {
        min-height: 50vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 4rem 2rem;
    }

    .calculation-header .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .calculation-header .animated-bg .bg-circle {
        position: absolute;
        border-radius: 50%;
    }

    .calculation-header .animated-bg .bg-circle-1 {
        top: 10%;
        right: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }

    .calculation-header .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .calculation-header .animated-bg .bg-circle-3 {
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .calculation-header .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }

    .calculation-header .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .calculation-header .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    .calculation-header .shape-1 {
        width: 40px;
        height: 40px;
        top: 20%;
        left: 10%;
        animation-name: float-1;
    }

    .calculation-header .shape-2 {
        width: 25px;
        height: 25px;
        top: 60%;
        left: 85%;
        animation-name: float-2;
        animation-delay: 1s;
    }

    .calculation-header .shape-3 {
        width: 35px;
        height: 35px;
        top: 75%;
        left: 15%;
        animation-name: float-3;
        animation-delay: 0.5s;
    }

    .calculation-header .shape-4 {
        width: 20px;
        height: 20px;
        top: 30%;
        left: 70%;
        animation-name: float-4;
        animation-delay: 1.5s;
    }

    .calculation-header .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 2rem;
    }

    .calculation-header .hero-kicker {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        display: inline-flex;
        padding: 0.75rem 1.75rem;
        border-radius: var(--border-radius-xl);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.2);
        transition: var(--transition);
    }

    .calculation-header .hero-kicker:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-2px);
    }

    .calculation-header .kicker-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .calculation-header .kicker-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 1rem;
    }

    .calculation-header .kicker-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        color: var(--accent-color);
    }

    .calculation-header .hero-title {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .calculation-header .hero-highlight {
        color: var(--accent-color);
        position: relative;
        display: inline-block;
    }

    .calculation-header .hero-highlight::after {
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

    .calculation-header .hero-subtitle {
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
        padding: 1.25rem 1.5rem;
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
    
    .modern-tab::before {
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
    
    .modern-tab.active::before {
        opacity: 1;
    }
    
    .tab-icon {
        width: 20px;
        height: 20px;
        transition: var(--transition);
    }
    
    .modern-tab.active .tab-icon {
        color: var(--white);
        transform: scale(1.1);
    }
    
    .update-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: white;
        border-radius: var(--border-radius-md);
        margin-bottom: 1.5rem;
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .update-indicator svg {
        width: 20px;
        height: 20px;
    }

    .update-indicator span {
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .summary-card {
        background: var(--white);
        padding: 1.75rem;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.02) 0%, transparent 100%);
        z-index: -1;
    }
    
    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    
    .summary-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .summary-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .summary-subtitle {
        font-size: 0.875rem;
        color: var(--gray-500);
    }
    
    .text-success { color: var(--success-color) !important; }
    .text-danger { color: var(--danger-color) !important; }
    
    .category-section {
        background: rgba(248, 249, 250, 0.5);
        border-radius: var(--border-radius-md);
        padding: 1.75rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-color);
        transition: var(--transition);
    }
    
    .category-section:hover {
        background: rgba(248, 249, 250, 0.8);
        transform: translateX(4px);
    }
    
    .category-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .category-icon {
        width: 28px;
        height: 28px;
        color: var(--primary-color);
        stroke-width: 2;
    }
    
    .category-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
        flex: 1;
    }
    
    .category-badge {
        padding: 0.375rem 0.875rem;
        background: var(--primary-light);
        color: var(--primary-color);
        border-radius: var(--border-radius-md);
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(26, 95, 180, 0.2);
    }
    
    .distribution-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .distribution-card {
        background: var(--white);
        padding: 1.75rem;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
        transition: var(--transition);
    }
    
    .distribution-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    
    .distribution-card h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .distribution-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-100);
    }
    
    .distribution-item:last-child {
        border-bottom: none;
    }
    
    .distribution-item .name {
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .distribution-item .amount {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .distribution-item .share {
        font-size: 0.875rem;
        color: var(--gray-500);
        background: var(--gray-100);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-sm);
    }
    
    .distribution-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: var(--border-radius-md);
        color: var(--white);
        margin-top: 1.5rem;
    }
    
    .distribution-total .label {
        font-weight: 600;
    }
    
    .distribution-total .value {
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .results-container {
        background: var(--white);
        border-radius: var(--border-radius-md);
        overflow: hidden;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }
    
    .results-table {
        width: 100%;
        border-collapse: collapse;
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
    }
    
    .results-table td strong {
        color: var(--gray-900);
        font-weight: 600;
    }
    
    .total-row {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
    }
    
    .total-row td,
    .total-row td strong {
        color: white !important;
        font-weight: 700 !important;
        font-size: 1.1rem;
    }
    
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
        color: var(--success-color);
        border: 1px solid rgba(6, 214, 160, 0.2);
    }
    
    .badge-warning {
        background: var(--warning-light);
        color: #b76e00;
        border: 1px solid rgba(255, 158, 0, 0.2);
    }
    
    .badge-danger {
        background: var(--danger-light);
        color: var(--danger-color);
        border: 1px solid rgba(239, 71, 111, 0.2);
    }
    
    .badge-info {
        background: var(--info-light);
        color: var(--info-color);
        border: 1px solid rgba(23, 162, 184, 0.2);
    }
    
    .badge-updated {
        background: var(--success-light);
        color: var(--success-dark);
        border: 1px solid rgba(37, 211, 102, 0.2);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .chart-section {
        background: linear-gradient(135deg, rgba(248, 249, 250, 0.8) 0%, rgba(233, 236, 239, 0.8) 100%);
        border-radius: var(--border-radius-md);
        padding: 2rem;
        border: 2px solid var(--gray-300);
        margin-bottom: 2rem;
    }

    .chart-section-title {
        text-align: center;
        margin-bottom: 2rem;
    }

    .chart-section-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }

    .chart-section-title p {
        color: var(--gray-600);
    }

    .chart-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3rem;
        flex-wrap: wrap;
    }

    .pie-chart-container {
        position: relative;
        width: 300px;
        height: 300px;
    }

    #distributionPieChart {
        width: 100%;
        height: 100%;
    }

    .chart-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 140px;
        height: 140px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        box-shadow: var(--shadow-md);
        z-index: 2;
    }

    .chart-center-text {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        text-align: center;
    }

    .chart-center-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
        margin-top: 0.25rem;
    }

    .chart-legend {
        min-width: 280px;
        max-width: 350px;
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .chart-legend::-webkit-scrollbar {
        width: 6px;
    }

    .chart-legend::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .chart-legend::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .chart-legend::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        padding: 0.75rem;
        border-radius: var(--border-radius-sm);
        background: rgba(255, 255, 255, 0.8);
        transition: var(--transition);
        border: 1px solid transparent;
        cursor: pointer;
    }

    .legend-item:hover {
        background: var(--white);
        transform: translateX(4px);
        border-color: var(--gray-200);
        box-shadow: var(--shadow-sm);
    }

    .legend-item.highlighted {
        background: var(--primary-light) !important;
        border: 1px solid var(--primary-color) !important;
        transform: scale(1.05) !important;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .legend-text {
        flex: 1;
        font-size: 0.875rem;
        color: var(--gray-800);
        font-weight: 500;
    }

    .legend-percentage {
        font-weight: 700;
        color: var(--gray-900);
        font-size: 0.875rem;
        min-width: 60px;
        text-align: right;
    }

    .legend-amount {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-top: 0.125rem;
    }

    .legend-relationship {
        font-size: 0.7rem;
        color: var(--gray-400);
        font-weight: 400;
    }

    .legend-summary {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-200);
    }

    .legend-summary .legend-item {
        background: var(--primary-light);
        border: 1px solid rgba(26, 95, 180, 0.2);
        margin-bottom: 0;
    }

    .legend-summary .legend-color {
        background: var(--primary-color) !important;
    }

    .legend-summary .legend-percentage {
        color: var(--primary-color);
        font-weight: 800;
    }

    .no-eligible-recipients {
        text-align: center;
        padding: 3rem 2rem;
    }

    .no-eligible-recipients svg {
        margin-bottom: 1rem;
        color: var(--gray-400);
    }

    .no-eligible-recipients h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .no-eligible-recipients p {
        color: var(--gray-500);
        max-width: 500px;
        margin: 0 auto;
    }
    
    .family-tree-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: var(--border-radius-md);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 2px solid var(--gray-300);
    }

    .tree-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: var(--white);
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
    }

    .tree-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }

    .tree-title p {
        color: var(--gray-600);
        margin: 0.25rem 0 0 0;
    }

    .tree-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .tree-container-wrapper {
        background: white;
        border-radius: var(--border-radius-md);
        padding: 1rem;
        border: 1px solid var(--gray-200);
        position: relative;
        min-height: 500px;
        max-height: 600px;
        overflow: auto;
    }

    .tree-container-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .tree-container-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .tree-container-wrapper::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .tree-container-wrapper::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    #graphviz-output {
        width: 100%;
        height: 100%;
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    #graphviz-svg-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .tree-placeholder {
        text-align: center;
        color: var(--gray-500);
        padding: 2rem;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 400px;
    }

    .tree-placeholder svg {
        margin-bottom: 1rem;
        color: var(--gray-400);
        width: 64px;
        height: 64px;
    }

    .tree-placeholder h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .tree-placeholder p {
        color: var(--gray-500);
        max-width: 400px;
        margin: 0 auto;
    }

    .layout-controls {
        display: flex;
        gap: 0.5rem;
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        z-index: 100;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .layout-btn {
        padding: 0.5rem 1rem;
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-sm);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .layout-btn:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
    }

    .layout-btn.active {
        background: var(--primary-light);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .zoom-controls {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 100;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .zoom-btn {
        width: 32px;
        height: 32px;
        background: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-sm);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .zoom-btn:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
    }

    .zoom-btn svg {
        width: 16px;
        height: 16px;
    }
    
    .export-controls {
        background: var(--white);
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
    }

    .export-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 1rem;
    }

    .export-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .export-btn {
        padding: 0.875rem 1.5rem;
        border: 2px solid var(--gray-300);
        background: var(--white);
        border-radius: var(--border-radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        min-width: 200px;
        justify-content: center;
    }

    .export-btn:hover {
        background: var(--gray-100);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .export-btn.pdf {
        border-color: var(--danger-color);
        color: var(--danger-color);
    }

    .export-btn.pdf:hover {
        background: var(--danger-light);
    }

    .export-btn.full-report {
        border-color: var(--success-color);
        color: var(--success-color);
    }

    .export-btn.full-report:hover {
        background: var(--success-light);
    }

    .export-btn.tree-pdf {
        border-color: #9d4edd;
        color: #9d4edd;
    }

    .export-btn.tree-pdf:hover {
        background: rgba(157, 78, 221, 0.1);
    }

    .export-icon {
        width: 20px;
        height: 20px;
    }
    
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
        text-decoration: none;
        min-width: 140px;
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
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
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
    
    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
        color: var(--white);
    }
    
    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg), 0 10px 30px rgba(37, 211, 102, 0.2);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #c82333 100%);
        color: var(--white);
    }
    
    .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg), 0 10px 30px rgba(220, 53, 69, 0.2);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, var(--warning-dark) 100%);
        color: var(--white);
    }
    
    .btn-warning:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg), 0 10px 30px rgba(255, 193, 7, 0.2);
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

    @media (max-width: 1200px) {
        .glass-container {
            max-width: 100%;
        }
        
        .tree-controls {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .tree-actions {
            width: 100%;
            justify-content: flex-start;
        }
        
        .chart-container {
            flex-direction: column;
            gap: 2rem;
        }
    }

    @media (max-width: 1024px) {
        .calculation-header .hero-title { font-size: 2.5rem; }
        .calculation-header { min-height: 40vh !important; padding: 3rem 1.5rem; }
        .glass-container { padding: 0 1.5rem 2rem; }
        .modern-alert { min-width: 300px; }
        .export-buttons {
            flex-direction: column;
        }
        .export-btn {
            min-width: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .calculation-header .hero-title { font-size: 2rem; }
        .calculation-header .hero-subtitle { font-size: 1.1rem; }
        .calculation-header { min-height: 35vh !important; padding: 2rem 1rem; }
        .modern-tabs { flex-direction: column; }
        .modern-tab { justify-content: flex-start; padding: 1rem 1.25rem; }
        .summary-grid, .distribution-grid { grid-template-columns: 1fr; }
        .card-body { padding: 1.5rem; }
        .btn-group-modern { flex-direction: column; }
        .btn { width: 100%; }
        .modern-alert-container { left: 20px; right: 20px; max-width: none; }
        .modern-alert { min-width: auto; width: 100%; }
        .calculation-header .shape,
        .calculation-header .bg-circle-3 { display: none !important; }
        
        .chart-section {
            padding: 1.5rem;
        }
        
        .pie-chart-container {
            width: 250px;
            height: 250px;
        }
        
        .chart-center {
            width: 110px;
            height: 110px;
        }
        
        .family-tree-section {
            padding: 1.5rem;
        }
        
        .tree-container-wrapper {
            min-height: 400px;
            max-height: 500px;
        }
        
        .tree-controls {
            padding: 1rem;
        }
        
        .tree-title h3 {
            font-size: 1.25rem;
        }
    }
    
    @media (max-width: 480px) {
        .calculation-header .hero-title { font-size: 1.75rem; }
        .calculation-header .hero-subtitle { font-size: 1rem; }
        .calculation-header { min-height: 30vh !important; padding: 1.5rem 1rem; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -2rem; }
        .card-header { padding: 1.25rem 1.5rem; flex-wrap: wrap; }
        .card-title { font-size: 1.25rem; }
        .results-table th,
        .results-table td { padding: 0.875rem 1rem; }
        .btn { padding: 0.875rem 1.5rem; font-size: 0.9rem; }
        .modern-alert { padding: 1rem; }
        
        .export-btn {
            min-width: 100%;
        }
        
        .pie-chart-container {
            width: 220px;
            height: 220px;
        }
        
        .chart-center {
            width: 100px;
            height: 100px;
        }
        
        .chart-center-value {
            font-size: 1.5rem;
        }
        
        .family-tree-section {
            padding: 1rem;
        }
        
        .tree-container-wrapper {
            min-height: 350px;
            max-height: 450px;
        }
        
        .tree-title p {
            font-size: 0.875rem;
        }
        
        .tree-actions {
            flex-direction: column;
        }
        
        .layout-controls, .zoom-controls {
            position: relative;
            top: auto;
            right: auto;
            margin-top: 1rem;
            justify-content: center;
        }
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        
        .glass-container, .glass-container * {
            visibility: visible;
        }
        
        .glass-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            padding: 20px;
            margin: 0;
        }
        
        .no-print {
            display: none !important;
        }
        
        .btn-group-modern,
        .modern-tabs,
        .modern-alert-container,
        .export-controls,
        .loading-overlay {
            display: none !important;
        }
        
        .glass-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .results-table {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .chart-section {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        
        .family-tree-section {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        
        .tree-container-wrapper {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            height: 400px;
        }
    }
</style>

<!-- Modern Alert Container -->
<div class="modern-alert-container" id="alertContainer"></div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Processing...</div>
        <div class="loading-subtext" id="loadingSubtext">Please wait while we generate your report</div>
    </div>
</div>

<!-- Modern Header -->
<header class="hero-section calculation-header">
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
            Calculation <span class="hero-highlight">Details</span>
        </h1>
        
        <p class="hero-subtitle">
            View and manage your saved Faraid calculation for {{ $calculation->deceased_name }}
            @if($calculation->updated_at->gt($calculation->created_at))
                <br><small style="opacity: 0.9;">Last updated: {{ $calculation->updated_at->format('d M Y, h:i A') }}</small>
            @endif
        </p>
    </div>
</header>

<!-- Main Container -->
<div class="glass-container">
    <!-- Modern Tabs -->
    <div class="modern-tabs">
        <button class="modern-tab active" data-section="overview">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Overview
        </button>
        <button class="modern-tab" data-section="distribution">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            Distribution
        </button>
        <button class="modern-tab" data-section="family-tree">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Family Tree
        </button>
        <button class="modern-tab" data-section="actions">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h1m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 01118 0z"/>
            </svg>
            Actions
        </button>
    </div>
    
    <!-- Update Status Indicator -->
    @if(session('updated') || ($calculation->updated_at->gt($calculation->created_at->addMinutes(5))))
    <div class="update-indicator" id="updateIndicator">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>
            @if(session('updated'))
                Calculation updated successfully! Family tree regenerated with latest data.
            @else
                This calculation was last updated on {{ $calculation->updated_at->format('d M Y') }}
            @endif
        </span>
    </div>
    @endif
    
    <!-- Overview Section -->
    <div class="glass-card active-section" id="overviewSection">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h2 class="card-title">Calculation Overview</h2>
            <span class="card-badge">
                {{ $calculation->created_at->format('d M Y') }}
                @if($calculation->updated_at->gt($calculation->created_at))
                    <span class="badge-updated ml-2">Updated</span>
                @endif
            </span>
        </div>
        
        <div class="card-body">
            <!-- Summary Grid -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-title">Deceased Name</div>
                    <div class="summary-value">{{ $calculation->deceased_name }}</div>
                    <div class="summary-subtitle">{{ ucfirst($calculation->deceased_gender) }}</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-title">Net Estate</div>
                    <div class="summary-value">{{ $calculation->formatted_net_assets }}</div>
                    <div class="summary-subtitle">Available for distribution</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-title">Eligible Heirs</div>
                    <div class="summary-value">{{ $calculation->eligible_heirs_count ?? 0 }}</div>
                    <div class="summary-subtitle">Receive inheritance</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-title">Date Created</div>
                    <div class="summary-value">{{ $calculation->created_at->format('d M') }}</div>
                    <div class="summary-subtitle">{{ $calculation->created_at->format('Y') }}</div>
                </div>
            </div>
            
            <!-- Financial Summary -->
            <div class="distribution-grid">
                <div class="distribution-card">
                    <h4>Financial Summary</h4>
                    <div class="distribution-item">
                        <span class="name">Total Assets</span>
                        <span class="amount">{{ $calculation->formatted_total_assets }}</span>
                    </div>
                    <div class="distribution-item">
                        <span class="name">Net Assets</span>
                        <span class="amount text-success">{{ $calculation->formatted_net_assets }}</span>
                    </div>
                </div>
                
                <div class="distribution-card">
                    <h4>Calculation Details</h4>
                    <div class="distribution-item">
                        <span class="name">Marital Status</span>
                        <span class="amount">{{ ucfirst($calculation->marital_status) }}</span>
                    </div>
                    <div class="distribution-item">
                        <span class="name">Date of Death</span>
                        <span class="amount">{{ $calculation->formatted_date_of_death ?? 'N/A' }}</span>
                    </div>
                    <div class="distribution-item">
                        <span class="name">Scenario</span>
                        <span class="amount">Scenario {{ $calculation->scenario_number ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Chart Visualization -->
            @php
                // Get distribution summary safely
                $distributionSummary = is_array($calculation->distribution_summary) 
                    ? $calculation->distribution_summary 
                    : json_decode($calculation->distribution_summary ?? '[]', true);
                
                $heirs = $distributionSummary['heirs'] ?? [];
                $totalDistributed = $distributionSummary['total_distributed'] ?? 0;
                $netEstate = $distributionSummary['net_estate'] ?? $calculation->net_assets;
                
                $eligibleHeirs = array_filter($heirs, function($h) {
                    $amount = is_array($h) ? ($h['amount'] ?? 0) : ($h->amount ?? 0);
                    return $amount > 0.01;
                });
                $totalEligible = count($eligibleHeirs);
                
                $chartData = [];
                if (count($eligibleHeirs) > 0) {
                    $colors = ['#7e1ab4', '#2d7ad6', '#25D366', '#ffd700', '#dc3545', '#2eaec2', '#1a5fb4', '#ff6b6b', '#51cf66', '#9d4edd', '#f48c06', '#0096c7'];
                    $colorIndex = 0;
                    
                    foreach ($eligibleHeirs as $heir) {
                        $heirArray = is_array($heir) ? $heir : $heir->toArray();
                        $amount = $heirArray['amount'] ?? 0;
                        $percentage = $netEstate > 0 ? ($amount / $netEstate) * 100 : 0;
                        $chartData[] = [
                            'heir' => $heirArray['name'] ?? $heirArray['heir'] ?? 'Unknown',
                            'relationship' => $heirArray['relationship'] ?? 'Unknown',
                            'amount' => $amount,
                            'percentage' => round($percentage, 2),
                            'color' => $colors[$colorIndex % count($colors)]
                        ];
                        $colorIndex++;
                    }
                }
            @endphp
            
            @if($totalEligible > 0)
                <div class="chart-section">
                    <div class="chart-section-title">
                        <h3>Distribution Visualization</h3>
                        <p>Showing only eligible recipients with positive shares</p>
                    </div>
                    
                    <div class="chart-container">
                        <div class="pie-chart-container">
                            <canvas id="overviewPieChart"></canvas>
                            <div class="chart-center">
                                <div class="chart-center-text">Eligible</div>
                                <div class="chart-center-value" id="eligibleCount">{{ $totalEligible }}</div>
                                <div class="chart-center-text" style="font-size: 0.75rem; margin-top: 0.5rem;">Recipients</div>
                            </div>
                        </div>
                        
                        <div class="chart-legend" id="overviewChartLegend">
                            @foreach($chartData as $item)
                            <div class="legend-item" data-index="{{ $loop->index }}">
                                <div class="legend-color" style="background: {{ $item['color'] ?? '#7e1ab4' }};"></div>
                                <div class="legend-text">
                                    {{ $item['heir'] ?? 'Unknown' }}
                                    <div class="legend-relationship">{{ $item['relationship'] ?? 'Unknown' }}</div>
                                    <div class="legend-amount">RM {{ number_format($item['amount'] ?? 0, 2) }}</div>
                                </div>
                                <div class="legend-percentage">{{ $item['percentage'] ?? 0 }}%</div>
                            </div>
                            @endforeach
                            
                            <div class="legend-summary">
                                <div class="legend-item">
                                    <div class="legend-color" style="background: var(--primary-color);"></div>
                                    <div class="legend-text">
                                        <strong>Total Eligible Distribution</strong>
                                        <div class="legend-amount">{{ count($chartData) }} recipient(s)</div>
                                    </div>
                                    <div class="legend-percentage">RM {{ number_format($totalDistributed, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Quick Distribution Preview -->
            @if(count($eligibleHeirs) > 0)
            <div class="results-container">
                <div class="card-header" style="border-bottom: 1px solid var(--gray-200); margin-bottom: 0;">
                    <h3 class="card-title" style="font-size: 1.25rem;">Distribution Summary</h3>
                </div>
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Heir</th>
                            <th>Share</th>
                            <th>Amount</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eligibleHeirs as $heir)
                        @php
                            $heirArray = is_array($heir) ? $heir : $heir->toArray();
                            $amount = $heirArray['amount'] ?? 0;
                            $percentage = $netEstate > 0 ? ($amount / $netEstate) * 100 : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $heirArray['name'] ?? $heirArray['heir'] ?? 'Unknown' }}</strong></td>
                            <td><span class="badge badge-primary">{{ $heirArray['share'] ?? 'N/A' }}</span></td>
                            <td><strong>RM {{ number_format($amount, 2) }}</strong></td>
                            <td>{{ number_format($percentage, 2) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="2" style="text-align: right;">Total Distributed:</td>
                            <td>RM {{ number_format($totalDistributed, 2) }}</td>
                            <td>100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
            
            <div class="btn-group-modern">
                <a href="{{ route('calculator.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Calculations
                </a>
                <button type="button" class="btn btn-primary" onclick="switchToSection('distribution')">
                    View Full Distribution
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Distribution Section -->
    <div class="glass-card" id="distributionSection">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            <h2 class="card-title">Detailed Distribution</h2>
            <span class="card-badge">{{ $calculation->eligible_heirs_count ?? 0 }} Eligible Heirs</span>
        </div>
        
        <div class="card-body">
            @php
                // Ensure distribution summary is properly structured
                $distributionSummary = is_array($calculation->distribution_summary) 
                    ? $calculation->distribution_summary 
                    : json_decode($calculation->distribution_summary ?? '[]', true);
                
                if (empty($distributionSummary)) {
                    // Fallback to basic calculation if no distribution summary exists
                    $heirsData = is_array($calculation->heirs_data) 
                        ? $calculation->heirs_data 
                        : json_decode($calculation->heirs_data ?? '[]', true);
                    
                    if (empty($heirsData)) {
                        $heirsData = [
                            'husband_count' => $calculation->husband_count ?? 0,
                            'wife_count' => $calculation->wife_count ?? 0,
                            'father_status' => $calculation->father_status ?? 'deceased',
                            'mother_status' => $calculation->mother_status ?? 'deceased',
                            'son_count' => $calculation->son_count ?? 0,
                            'daughter_count' => $calculation->daughter_count ?? 0,
                            'full_brother_count' => $calculation->full_brother_count ?? 0,
                            'full_sister_count' => $calculation->full_sister_count ?? 0,
                            'paternal_brother_count' => $calculation->paternal_brother_count ?? 0,
                            'paternal_sister_count' => $calculation->paternal_sister_count ?? 0,
                            'maternal_brother_count' => $calculation->maternal_brother_count ?? 0,
                            'maternal_sister_count' => $calculation->maternal_sister_count ?? 0,
                        ];
                    }
                    
                    $netEstate = $calculation->net_assets;
                    $heirs = [];
                    $totalDistributed = 0;
                    
                    // Basic Faraid calculation logic (simplified for fallback)
                    if ($calculation->deceased_gender === 'male') {
                        if ($calculation->marital_status === 'married') {
                            if (($heirsData['wife_count'] ?? 0) > 0) {
                                $wifeShare = 1/8;
                                $amount = $netEstate * $wifeShare;
                                $heirs[] = [
                                    'name' => 'Wife',
                                    'relationship' => 'spouse',
                                    'share' => '1/8',
                                    'amount' => $amount,
                                    'type' => 'Fixed Share',
                                    'status' => 'Eligible'
                                ];
                                $totalDistributed += $amount;
                            }
                        }
                    } else {
                        if ($calculation->marital_status === 'married') {
                            if (($heirsData['husband_count'] ?? 0) > 0) {
                                $husbandShare = 1/4;
                                $amount = $netEstate * $husbandShare;
                                $heirs[] = [
                                    'name' => 'Husband',
                                    'relationship' => 'spouse',
                                    'share' => '1/4',
                                    'amount' => $amount,
                                    'type' => 'Fixed Share',
                                    'status' => 'Eligible'
                                ];
                                $totalDistributed += $amount;
                            }
                        }
                    }
                    
                    if (($heirsData['father_status'] ?? 'deceased') === 'alive') {
                        $fatherShare = 1/6;
                        $amount = $netEstate * $fatherShare;
                        $heirs[] = [
                            'name' => 'Father',
                            'relationship' => 'father',
                            'share' => '1/6',
                            'amount' => $amount,
                            'type' => 'Fixed Share',
                            'status' => 'Eligible'
                        ];
                        $totalDistributed += $amount;
                    }
                    
                    if (($heirsData['mother_status'] ?? 'deceased') === 'alive') {
                        $motherShare = 1/6;
                        $amount = $netEstate * $motherShare;
                        $heirs[] = [
                            'name' => 'Mother',
                            'relationship' => 'mother',
                            'share' => '1/6',
                            'amount' => $amount,
                            'type' => 'Fixed Share',
                            'status' => 'Eligible'
                        ];
                        $totalDistributed += $amount;
                    }
                    
                    $remainingEstate = $netEstate - $totalDistributed;
                    $totalChildren = ($heirsData['son_count'] ?? 0) + ($heirsData['daughter_count'] ?? 0);
                    
                    if ($totalChildren > 0) {
                        $sonShare = 2;
                        $daughterShare = 1;
                        $totalShares = ($heirsData['son_count'] ?? 0) * $sonShare + ($heirsData['daughter_count'] ?? 0) * $daughterShare;
                        
                        if ($totalShares > 0) {
                            $shareValue = $remainingEstate / $totalShares;
                            
                            for ($i = 1; $i <= ($heirsData['son_count'] ?? 0); $i++) {
                                $amount = $shareValue * $sonShare;
                                $heirs[] = [
                                    'name' => 'Son ' . $i,
                                    'relationship' => 'son',
                                    'share' => 'Asabah',
                                    'amount' => $amount,
                                    'type' => 'Asabah',
                                    'status' => 'Eligible'
                                ];
                                $totalDistributed += $amount;
                            }
                            
                            for ($i = 1; $i <= ($heirsData['daughter_count'] ?? 0); $i++) {
                                $amount = $shareValue * $daughterShare;
                                $heirs[] = [
                                    'name' => 'Daughter ' . $i,
                                    'relationship' => 'daughter',
                                    'share' => 'Asabah',
                                    'amount' => $amount,
                                    'type' => 'Asabah',
                                    'status' => 'Eligible'
                                ];
                                $totalDistributed += $amount;
                            }
                        }
                    }
                    
                    if ($totalDistributed < $netEstate) {
                        $surplus = $netEstate - $totalDistributed;
                        $heirs[] = [
                            'name' => 'Baitulmal',
                            'relationship' => 'state treasury',
                            'share' => 'Surplus',
                            'amount' => $surplus,
                            'type' => 'Surplus',
                            'status' => 'Eligible'
                        ];
                        $totalDistributed += $surplus;
                    }
                    
                    $distributionSummary = [
                        'heirs' => $heirs,
                        'total_distributed' => $totalDistributed,
                        'total_eligible' => count(array_filter($heirs, function($h) {
                            return ($h['amount'] ?? 0) > 0 && ($h['type'] ?? '') !== 'Surplus';
                        })),
                        'net_estate' => $netEstate
                    ];
                    
                    // Update calculation if possible
                    try {
                        $calculation->update([
                            'distribution_summary' => $distributionSummary,
                            'eligible_heirs_count' => $distributionSummary['total_eligible']
                        ]);
                        $calculation->refresh();
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Log::error('Error updating calculation: ' . $e->getMessage());
                    }
                }
                
                $heirs = $distributionSummary['heirs'] ?? [];
                $totalDistributed = $distributionSummary['total_distributed'] ?? 0;
                $totalEligible = $distributionSummary['total_eligible'] ?? 0;
                $netEstate = $distributionSummary['net_estate'] ?? $calculation->net_assets;
                $hasHeirs = !empty($heirs);
                
                $eligibleHeirs = array_filter($heirs, function($h) {
                    $amount = is_array($h) ? ($h['amount'] ?? 0) : ($h->amount ?? 0);
                    return $amount > 0.01;
                });
            @endphp
            
            @if($hasHeirs)
                @if(session('updated'))
                <div class="update-indicator" style="background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%); margin-bottom: 1.5rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Showing updated distribution from your edit</span>
                </div>
                @endif
                
                <!-- Detailed Distribution Table -->
                <div class="results-container">
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>Heir</th>
                                <th>Relationship</th>
                                <th>Share</th>
                                <th>Amount (RM)</th>
                                <th>Percentage</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($heirs as $heir)
                                @php
                                    $heirArray = is_array($heir) ? $heir : $heir->toArray();
                                    $amount = $heirArray['amount'] ?? 0;
                                    $status = $heirArray['status'] ?? 'Eligible';
                                    $type = $heirArray['type'] ?? '';
                                    $percentage = $netEstate > 0 ? ($amount / $netEstate) * 100 : 0;
                                    
                                    $badgeClass = 'badge-primary';
                                    if ($type === 'Fixed Share') $badgeClass = 'badge-success';
                                    elseif ($type === 'Asabah') $badgeClass = 'badge-warning';
                                    elseif ($type === 'Surplus') $badgeClass = 'badge-info';
                                    elseif ($status === 'Blocked' || $status === 'Excluded') $badgeClass = 'badge-danger';
                                @endphp
                                <tr>
                                    <td><strong>{{ $heirArray['name'] ?? $heirArray['heir'] ?? 'Unknown' }}</strong></td>
                                    <td>{{ ucfirst($heirArray['relationship'] ?? 'Unknown') }}</td>
                                    <td><span class="badge {{ $badgeClass }}">{{ $heirArray['share'] ?? 'N/A' }}</span></td>
                                    <td><strong>RM {{ number_format($amount, 2) }}</strong></td>
                                    <td>{{ number_format($percentage, 2) }}%</td>
                                    <td><span class="badge {{ $badgeClass }}">{{ $status }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" style="text-align: right;">Total Distributed:</td>
                                <td>RM {{ number_format($totalDistributed, 2) }}</td>
                                <td>100%</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                @if(count($eligibleHeirs) > 0)
                    <!-- Distribution Summary Cards -->
                    <div class="distribution-grid">
                        <div class="distribution-card">
                            <h4>Top Recipients</h4>
                            @php
                                $topHeirs = array_slice($eligibleHeirs, 0, 5);
                            @endphp
                            
                            @foreach($topHeirs as $heir)
                            @php
                                $heirArray = is_array($heir) ? $heir : $heir->toArray();
                            @endphp
                            <div class="distribution-item">
                                <span class="name">{{ $heirArray['name'] ?? $heirArray['heir'] ?? 'Unknown' }}</span>
                                <span class="amount">RM {{ number_format($heirArray['amount'] ?? 0, 2) }}</span>
                                <span class="share">{{ $heirArray['share'] ?? 'N/A' }}</span>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="distribution-card">
                            <h4>Distribution Summary</h4>
                            <div class="distribution-item">
                                <span class="name">Total Eligible Heirs</span>
                                <span class="amount">{{ $totalEligible }}</span>
                            </div>
                            <div class="distribution-item">
                                <span class="name">Total Distribution</span>
                                <span class="amount">RM {{ number_format($totalDistributed, 2) }}</span>
                            </div>
                            <div class="distribution-item">
                                <span class="name">Net Estate</span>
                                <span class="amount">RM {{ number_format($netEstate, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="distribution-total">
                        <div class="label">Total Distributed to Heirs</div>
                        <div class="value">RM {{ number_format($totalDistributed, 2) }}</div>
                    </div>
                    
                    <!-- Chart Visualization -->
                    @php
                        $chartData = [];
                        if (count($eligibleHeirs) > 0) {
                            $colors = ['#7e1ab4', '#2d7ad6', '#25D366', '#ffd700', '#dc3545', '#2eaec2', '#1a5fb4', '#ff6b6b', '#51cf66', '#9d4edd', '#f48c06', '#0096c7'];
                            $colorIndex = 0;
                            
                            foreach ($eligibleHeirs as $heir) {
                                $heirArray = is_array($heir) ? $heir : $heir->toArray();
                                $amount = $heirArray['amount'] ?? 0;
                                $percentage = $netEstate > 0 ? ($amount / $netEstate) * 100 : 0;
                                $chartData[] = [
                                    'heir' => $heirArray['name'] ?? $heirArray['heir'] ?? 'Unknown',
                                    'relationship' => $heirArray['relationship'] ?? 'Unknown',
                                    'amount' => $amount,
                                    'percentage' => round($percentage, 2),
                                    'color' => $colors[$colorIndex % count($colors)]
                                ];
                                $colorIndex++;
                            }
                        }
                    @endphp
                    
                    @if(count($chartData) > 0)
                        <div class="chart-section">
                            <div class="chart-section-title">
                                <h3>Distribution Visualization</h3>
                                <p>Interactive chart showing updated inheritance distribution</p>
                            </div>
                            
                            <div class="chart-container">
                                <div class="pie-chart-container">
                                    <canvas id="distributionPieChart"></canvas>
                                    <div class="chart-center">
                                        <div class="chart-center-text">Eligible</div>
                                        <div class="chart-center-value" id="distributionEligibleCount">{{ $totalEligible }}</div>
                                        <div class="chart-center-text" style="font-size: 0.75rem; margin-top: 0.5rem;">Recipients</div>
                                    </div>
                                </div>
                                
                                <div class="chart-legend" id="distributionChartLegend">
                                    @foreach($chartData as $item)
                                    <div class="legend-item" data-index="{{ $loop->index }}">
                                        <div class="legend-color" style="background: {{ $item['color'] ?? '#7e1ab4' }};"></div>
                                        <div class="legend-text">
                                            {{ $item['heir'] ?? 'Unknown' }}
                                            <div class="legend-relationship">{{ $item['relationship'] ?? 'Unknown' }}</div>
                                            <div class="legend-amount">RM {{ number_format($item['amount'] ?? 0, 2) }}</div>
                                        </div>
                                        <div class="legend-percentage">{{ $item['percentage'] ?? 0 }}%</div>
                                    </div>
                                    @endforeach
                                    
                                    <div class="legend-summary">
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: var(--primary-color);"></div>
                                            <div class="legend-text">
                                                <strong>Total Eligible Distribution</strong>
                                                <div class="legend-amount">{{ count($chartData) }} recipient(s)</div>
                                            </div>
                                            <div class="legend-percentage">RM {{ number_format($totalDistributed, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- No Eligible Recipients -->
                    <div class="no-eligible-recipients">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3>No Eligible Recipients</h3>
                        <p>There are no eligible heirs with positive inheritance amounts in this calculation.</p>
                    </div>
                @endif
                
            @else
                <!-- No Heirs Data -->
                <div class="no-eligible-recipients">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3>No Distribution Data Available</h3>
                    <p>The distribution calculation could not be loaded. Please recalculate.</p>
                </div>
            @endif
            
            <div class="btn-group-modern">
                <button type="button" class="btn btn-secondary" onclick="switchToSection('overview')">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Overview
                </button>
                <a href="{{ route('calculator.edit', $calculation->id) }}" class="btn btn-warning">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Edit & Recalculate
                </a>
                <button type="button" class="btn btn-primary" onclick="switchToSection('family-tree')">
                    View Family Tree
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Family Tree Section -->
    <div class="glass-card" id="family-treeSection">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h2 class="card-title">Family Tree Visualization</h2>
            <span class="card-badge">Auto-Updated</span>
        </div>
        
        <div class="card-body">
            <!-- Family Tree Visualization -->
            <div class="family-tree-section">
                <div class="tree-controls">
                    <div class="tree-title">
                        <h3>Family Tree with Inheritance Distribution</h3>
                        <p>Visual representation of family relationships with inheritance amounts</p>
                    </div>
                </div>
                
                <div class="tree-container-wrapper">
                    <!-- Zoom Controls -->
                    <div class="zoom-controls" id="zoomControls" style="display: none;">
                        <button class="zoom-btn" id="zoomInBtn" title="Zoom In">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </button>
                        <button class="zoom-btn" id="resetViewBtn" title="Reset View">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                        <button class="zoom-btn" id="zoomOutBtn" title="Zoom Out">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Layout Controls -->
                    <div class="layout-controls" id="layoutControls" style="display: none;">
                        <button class="layout-btn active" data-layout="dot">Tree Layout</button>
                        <button class="layout-btn" data-layout="circo">Circular</button>
                        <button class="layout-btn" data-layout="twopi">Radial</button>
                    </div>
                    
                    <div id="graphviz-output">
                        <div id="graphvizPlaceholder" class="tree-placeholder">
                            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <h3>Family Tree Visualization</h3>
                            <p>Click "Generate Family Tree" to create a visual family tree diagram showing inheritance amounts</p>
                            <button type="button" class="btn btn-primary mt-3" id="generateTreeFromPlaceholder">
                                Generate Family Tree
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Family Structure Summary -->
            <div class="distribution-grid">
                <div class="distribution-card">
                    <h4>Family Structure</h4>
                    <div class="distribution-item">
                        <span class="name">Deceased Name</span>
                        <span class="amount">{{ $calculation->deceased_name }}</span>
                    </div>
                    <div class="distribution-item">
                        <span class="name">Marital Status</span>
                        <span class="amount">{{ ucfirst($calculation->marital_status) }}</span>
                    </div>
                    <div class="distribution-item">
                        <span class="name">Total Family Members</span>
                        <span class="amount">{{ $calculation->total_heirs ?? count($heirs) }}</span>
                    </div>
                </div>
                
                <div class="distribution-card">
                    <h4>Heir Relationships</h4>
                    @php
                        $relationships = [];
                        if (count($heirs) > 0) {
                            foreach($heirs as $heir) {
                                $heirArray = is_array($heir) ? $heir : $heir->toArray();
                                $relationship = $heirArray['relationship'] ?? 'Unknown';
                                if (!isset($relationships[$relationship])) {
                                    $relationships[$relationship] = 0;
                                }
                                $relationships[$relationship]++;
                            }
                        }
                    @endphp
                    @if(count($relationships) > 0)
                        @foreach($relationships as $relationship => $count)
                        <div class="distribution-item">
                            <span class="name">{{ ucfirst($relationship) }}(s)</span>
                            <span class="amount">{{ $count }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="distribution-item">
                            <span class="name">No relationships data</span>
                            <span class="amount">-</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="btn-group-modern">
                <button type="button" class="btn btn-secondary" onclick="switchToSection('distribution')">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Distribution
                </button>
                <button type="button" class="btn btn-primary" id="refreshFamilyTree">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh Tree
                </button>
            </div>
        </div>
    </div>
    
    <!-- Actions Section -->
    <div class="glass-card" id="actionsSection">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h1m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 01118 0z"/>
            </svg>
            <h2 class="card-title">Calculation Actions</h2>
            <span class="card-badge">Manage</span>
        </div>
        
        <div class="card-body">
            <!-- Action Cards -->
            <div class="distribution-grid">
                <a href="{{ route('calculator.edit', $calculation->id) }}" class="distribution-card" style="text-decoration: none; color: inherit; display: block;">
                    <h4>Edit Calculation</h4>
                    <div class="distribution-item">
                        <span class="name">Modify Details</span>
                        <span class="amount">Update heirs or assets</span>
                    </div>
                    <div style="margin-top: 1rem;">
                        <button class="btn btn-secondary" style="width: 100%;">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Calculation
                        </button>
                    </div>
                </a>
                
                <form id="deleteForm" action="{{ route('calculator.destroy', $calculation->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="distribution-card" style="cursor: pointer; background: var(--danger-light); border-color: var(--danger-color);" 
                     onclick="confirmDelete()">
                    <h4 style="color: var(--danger-color);">Delete Calculation</h4>
                    <div class="distribution-item">
                        <span class="name" style="color: var(--danger-color);">Remove Permanently</span>
                        <span class="amount" style="color: var(--danger-color);">Cannot be undone</span>
                    </div>
                    <div style="margin-top: 1rem;">
                        <button class="btn btn-danger" style="width: 100%;">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Calculation
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Export Controls -->
            <div class="export-controls">
                <div class="export-title">Export Reports & Images</div>
                <div class="export-buttons">
                    <button type="button" class="export-btn full-report" id="exportFullReport">
                        <svg class="export-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Report
                    </button>
                    <button type="button" class="export-btn tree-pdf" id="exportTreePDFAction">
                        <svg class="export-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Export Family Tree
                    </button>
                </div>
            </div>
            
            <!-- Navigation & Action Buttons -->
            <div class="btn-group-modern">
                <a href="{{ route('calculator.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Calculations
                </a>
                
                <a href="{{ route('calculator.index') }}" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    New Calculation
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== GLOBAL VARIABLES =====
    let overviewPieChart = null;
    let distributionPieChart = null;
    let currentGraphvizLayout = 'dot';
    let graphvizInstance = null;
    let svgViewBox = null;
    let currentScale = 1;
    let familyTreeGenerated = false;
    let calculationResults = null; // Will store distribution results as array for tree generation
    
    // ===== CALCULATION DATA =====
    const calculationData = @json($calculation->toArray());
    const distributionSummary = @json($calculation->distribution_summary ?? []);
    const treeData = @json($calculation->tree_data ?? null);
    
    // Prepare distribution results array from distributionSummary
    if (distributionSummary && distributionSummary.heirs && distributionSummary.heirs.length > 0) {
        calculationResults = distributionSummary.heirs.map(heir => ({
            heir: heir.name || heir.heir,
            relationship: heir.relationship,
            amount: heir.amount,
            percentage: heir.percentage,
            fractionDisplay: heir.share,
            status: heir.status
        }));
        // Also store netEstate on the array for convenience
        if (calculationResults) calculationResults.netEstate = distributionSummary.net_estate || calculationData.net_assets;
    }
    
    // ===== MODERN ALERT SYSTEM =====
    class ModernAlert {
        static show({ type = 'info', title, message, duration = 4000 }) {
            const container = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            
            const icons = {
                success: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 01118 0z"/>
                </svg>`,
                warning: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>`,
                error: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 01118 0z"/>
                </svg>`,
                info: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h1m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 01118 0z"/>
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
            setTimeout(() => alertEl.classList.add('show'), 10);
            if (duration > 0) setTimeout(() => ModernAlert.close(alertId), duration);
            return alertId;
        }
        
        static close(alertId) {
            const alertEl = document.getElementById(alertId);
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('hide');
                setTimeout(() => alertEl.parentNode?.removeChild(alertEl), 500);
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
    
    // ===== SECTION NAVIGATION =====
    const tabs = document.querySelectorAll('.modern-tab');
    const sections = document.querySelectorAll('.glass-card');
    
    function switchToSection(sectionId) {
        tabs.forEach(t => t.classList.remove('active'));
        sections.forEach(s => s.classList.remove('active-section'));
        
        const targetTab = document.querySelector(`[data-section="${sectionId}"]`);
        const targetSection = document.getElementById(`${sectionId}Section`);
        
        if (targetTab) targetTab.classList.add('active');
        if (targetSection) targetSection.classList.add('active-section');
        
        // Auto-generate family tree when switching to that tab
        if (sectionId === 'family-tree') {
            setTimeout(() => {
                const placeholder = document.getElementById('graphvizPlaceholder');
                if (placeholder && placeholder.style.display !== 'none') {
                    generateFamilyTree();
                }
            }, 300);
        }
    }
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const sectionId = this.dataset.section;
            switchToSection(sectionId);
        });
    });
    
    window.switchToSection = switchToSection;
    
    // ===== CHART GENERATION =====
    function generatePieCharts() {
        const heirs = distributionSummary.heirs || [];
        const totalDistributed = distributionSummary.total_distributed || 0;
        const netEstate = distributionSummary.net_estate || calculationData.net_assets;
        
        if (heirs.length === 0 || totalDistributed === 0) {
            return;
        }
        
        const eligibleHeirs = heirs.filter(heir => (heir.amount || 0) > 0.01);
        
        if (eligibleHeirs.length === 0) {
            return;
        }
        
        const sortedHeirs = [...eligibleHeirs].sort((a, b) => (b.amount || 0) - (a.amount || 0));
        
        const labels = sortedHeirs.map(heir => heir.name || heir.heir);
        const data = sortedHeirs.map(heir => heir.amount || 0);
        const backgroundColors = sortedHeirs.map((heir, index) => getColorForIndex(index));
        
        // Generate overview pie chart
        const overviewCtx = document.getElementById('overviewPieChart');
        if (overviewCtx) {
            if (overviewPieChart) {
                overviewPieChart.destroy();
            }
            
            overviewPieChart = new Chart(overviewCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const percentage = ((value / totalDistributed) * 100).toFixed(2);
                                    return `${context.label}: RM ${value.toLocaleString('en-US', {minimumFractionDigits: 2})} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
            
            addChartInteractivity(overviewPieChart, 'overviewChartLegend');
        }
        
        // Generate distribution pie chart
        const distributionCtx = document.getElementById('distributionPieChart');
        if (distributionCtx) {
            if (distributionPieChart) {
                distributionPieChart.destroy();
            }
            
            distributionPieChart = new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const percentage = ((value / totalDistributed) * 100).toFixed(2);
                                    return `${context.label}: RM ${value.toLocaleString('en-US', {minimumFractionDigits: 2})} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
            
            addChartInteractivity(distributionPieChart, 'distributionChartLegend');
        }
    }
    
    function addChartInteractivity(chart, legendId) {
        const legendItems = document.querySelectorAll(`#${legendId} .legend-item:not(:last-child)`);
        
        legendItems.forEach((item, index) => {
            item.addEventListener('mouseenter', function() {
                this.classList.add('highlighted');
                if (chart) {
                    chart.setActiveElements([{ datasetIndex: 0, index: index }]);
                    chart.update();
                }
            });
            
            item.addEventListener('mouseleave', function() {
                this.classList.remove('highlighted');
                if (chart) {
                    chart.setActiveElements([]);
                    chart.update();
                }
            });
            
            item.addEventListener('click', function() {
                if (chart) {
                    const activeIndex = chart.getActiveElements()[0]?.index;
                    if (activeIndex === index) {
                        chart.setActiveElements([]);
                    } else {
                        chart.setActiveElements([{ datasetIndex: 0, index: index }]);
                    }
                    chart.update();
                }
            });
        });
    }
    
    function getColorForIndex(index) {
        const colors = [
            '#7e1ab4', '#2d7ad6', '#25D366', '#ffd700', '#dc3545', '#2eaec2',
            '#1a5fb4', '#ff6b6b', '#51cf66', '#9d4edd', '#f48c06', '#0096c7'
        ];
        return colors[index % colors.length];
    }
    
    // ===== HELPER: Build amount lookup from distribution results =====
    function buildAmountLookupFromResults(results) {
        const lookup = {};
        if (!results) return lookup;
        for (const result of results) {
            const heirName = result.heir;
            const amountNum = parseFloat(result.amount);
            if (!isNaN(amountNum) && amountNum > 0) {
                lookup[heirName] = amountNum;
                // Store generic keys for easier matching
                if (heirName === 'Husband') lookup['Husband'] = amountNum;
                if (heirName === 'Wife' || heirName.startsWith('Wife ')) lookup['Wife'] = amountNum;
                if (heirName === 'Father') lookup['Father'] = amountNum;
                if (heirName === 'Mother') lookup['Mother'] = amountNum;
                if (heirName === 'Son' || heirName.startsWith('Son ')) lookup['Son'] = amountNum;
                if (heirName === 'Daughter' || heirName.startsWith('Daughter ')) lookup['Daughter'] = amountNum;
                if (heirName.startsWith('Full Brother')) lookup['FullBrother'] = amountNum;
                if (heirName.startsWith('Full Sister')) lookup['FullSister'] = amountNum;
                if (heirName.startsWith('Paternal Half-Brother')) lookup['PaternalHalfBrother'] = amountNum;
                if (heirName.startsWith('Paternal Half-Sister')) lookup['PaternalHalfSister'] = amountNum;
                if (heirName.startsWith('Maternal Half-Brother')) lookup['MaternalHalfBrother'] = amountNum;
                if (heirName.startsWith('Maternal Half-Sister')) lookup['MaternalHalfSister'] = amountNum;
                if (heirName === 'Baitulmal') lookup['Baitulmal'] = amountNum;
            }
        }
        return lookup;
    }
    
    // ===== FAMILY TREE GENERATION (aligned with index.blade.php) =====
    function generateDOTCodeWithAllHeirs(deceasedName, deceasedGender, heirs, netEstate, calculationResultsArray) {
        const amountLookup = buildAmountLookupFromResults(calculationResultsArray);
        
        const deceasedColor = '#dc3545';
        const spouseColor = '#ffc107';
        const fatherColor = '#007bff';
        const motherColor = '#17a2b8';
        const sonColor = '#28a745';
        const daughterColor = '#e83e8c';
        const siblingColor = '#6f42c1';
        const baitulmalColor = '#6c757d';
        const grandparentColor = '#fd7e14';
        const summaryColor = '#1a5fb4';
        
        const formatAmount = (amount) => {
            if (!amount || amount <= 0) return 'RM 0.00';
            return `RM ${amount.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        };
        
        const getNodeLabel = (name, amountKey, defaultName = name) => {
            let amount = amountLookup[amountKey] || 0;
            // Fallback to generic keys if exact match not found
            if (amount === 0 && amountKey === 'Wife 1' && heirs.wife_count === 1) amount = amountLookup['Wife'] || 0;
            if (amount === 0 && amountKey.startsWith('Wife')) amount = amountLookup['Wife'] || 0;
            if (amount === 0 && (amountKey === 'Son' || amountKey.startsWith('Son'))) amount = amountLookup['Son'] || 0;
            if (amount === 0 && (amountKey === 'Daughter' || amountKey.startsWith('Daughter'))) amount = amountLookup['Daughter'] || 0;
            if (amount === 0 && (amountKey === 'Full Brother' || amountKey.startsWith('FullBrother'))) amount = amountLookup['FullBrother'] || 0;
            if (amount === 0 && (amountKey === 'Full Sister' || amountKey.startsWith('FullSister'))) amount = amountLookup['FullSister'] || 0;
            if (amount === 0 && (amountKey === 'Paternal Half-Brother' || amountKey.startsWith('PaternalHalfBrother'))) amount = amountLookup['PaternalHalfBrother'] || 0;
            if (amount === 0 && (amountKey === 'Paternal Half-Sister' || amountKey.startsWith('PaternalHalfSister'))) amount = amountLookup['PaternalHalfSister'] || 0;
            if (amount === 0 && (amountKey === 'Maternal Half-Brother' || amountKey.startsWith('MaternalHalfBrother'))) amount = amountLookup['MaternalHalfBrother'] || 0;
            if (amount === 0 && (amountKey === 'Maternal Half-Sister' || amountKey.startsWith('MaternalHalfSister'))) amount = amountLookup['MaternalHalfSister'] || 0;
            
            if (amount > 0 && netEstate > 0) {
                const percentage = ((amount / netEstate) * 100).toFixed(1);
                return `${escapeHtml(defaultName)}\\n${formatAmount(amount)} (${percentage}%)`;
            }
            return `${escapeHtml(defaultName)}\\nRM 0.00`;
        };
        
        let dot = `digraph FamilyTree {
    rankdir=TB;
    compound=true;
    newrank=true;
    splines=ortho;
    nodesep=0.4;
    ranksep=0.5;
    size="10,8";
    ratio=compress;
    node [shape=box, style="rounded,filled", fillcolor="#f8f9fa", fontname="Poppins", fontsize=11];
    edge [color="#6c757d", penwidth=1.5, fontname="Poppins", fontsize=9];
    graph [bgcolor="white", fontname="Poppins"];
    
    Deceased [label="${escapeHtml(deceasedName)}\\n(Deceased)", fillcolor="${deceasedColor}", fontcolor=white, shape=ellipse, style="filled", width=2.2, height=1, fontsize=13];
    `;
        
        const createHeirNode = (id, label, amountKey, color) => {
            const nodeLabel = getNodeLabel(label, amountKey, label);
            return `${id} [label="${nodeLabel}", fillcolor="${color}", fontcolor=${amountLookup[amountKey] > 0 ? 'white' : '#666'}, width=2.0, height=0.8, fontsize=10];\n`;
        };
        
        // Spouse
        if (heirs.husband_count > 0) {
            dot += createHeirNode('Husband', 'Husband', 'Husband', spouseColor);
            dot += `Deceased -> Husband [label="Spouse", fontcolor="#666"];\n`;
        } else if (heirs.wife_count > 0) {
            for (let i = 1; i <= heirs.wife_count; i++) {
                const key = `Wife ${i}`;
                dot += createHeirNode(`Wife_${i}`, `Wife ${i}`, key, spouseColor);
                dot += `Deceased -> Wife_${i} [label="Spouse", fontcolor="#666"];\n`;
            }
        }
        
        // Parents
        if (heirs.father_status === 'alive') {
            dot += createHeirNode('Father', 'Father', 'Father', fatherColor);
            dot += `Deceased -> Father [label="Child", dir=back, fontcolor="#666"];\n`;
        }
        
        if (heirs.mother_status === 'alive') {
            dot += createHeirNode('Mother', 'Mother', 'Mother', motherColor);
            dot += `Deceased -> Mother [label="Child", dir=back, fontcolor="#666"];\n`;
        }
        
        // Grandparents
        if (heirs.fathers_father_status === 'alive') {
            dot += createHeirNode('FathersFather', "Father's Father", 'Paternal Grandfather', grandparentColor);
            if (heirs.father_status === 'alive') {
                dot += `Father -> FathersFather [label="Parent", dir=back, fontcolor="#999"];\n`;
            } else {
                dot += `Deceased -> FathersFather [label="Grandfather", style=dashed, fontcolor="#999"];\n`;
            }
        }
        if (heirs.fathers_mother_status === 'alive') {
            dot += createHeirNode('FathersMother', "Father's Mother", 'Paternal Grandmother', grandparentColor);
            if (heirs.father_status === 'alive') {
                dot += `Father -> FathersMother [label="Parent", dir=back, fontcolor="#999"];\n`;
            } else {
                dot += `Deceased -> FathersMother [label="Grandmother", style=dashed, fontcolor="#999"];\n`;
            }
        }
        if (heirs.mothers_mother_status === 'alive') {
            dot += createHeirNode('MothersMother', "Mother's Mother", 'Maternal Grandmother', grandparentColor);
            if (heirs.mother_status === 'alive') {
                dot += `Mother -> MothersMother [label="Parent", dir=back, fontcolor="#999"];\n`;
            } else {
                dot += `Deceased -> MothersMother [label="Grandmother", style=dashed, fontcolor="#999"];\n`;
            }
        }
        
        // Children
        let childNodes = [];
        for (let i = 1; i <= heirs.son_count; i++) {
            const key = `Son ${i}`;
            const nodeId = `Son_${i}`;
            dot += createHeirNode(nodeId, `Son ${i}`, key, sonColor);
            dot += `Deceased -> ${nodeId} [label="Parent", dir=back, fontcolor="#666"];\n`;
            childNodes.push(nodeId);
        }
        for (let i = 1; i <= heirs.daughter_count; i++) {
            const key = `Daughter ${i}`;
            const nodeId = `Daughter_${i}`;
            dot += createHeirNode(nodeId, `Daughter ${i}`, key, daughterColor);
            dot += `Deceased -> ${nodeId} [label="Parent", dir=back, fontcolor="#666"];\n`;
            childNodes.push(nodeId);
        }
        if (childNodes.length > 0) {
            dot += `subgraph cluster_children { label="Children"; style=dashed; color="#adb5bd"; bgcolor="#f8f9fa"; fontsize=10; `;
            childNodes.forEach(nodeId => dot += `${nodeId}; `);
            dot += `}\n`;
        }
        
        // Siblings
        let siblingNodes = [];
        for (let i = 1; i <= heirs.full_brother_count; i++) {
            const key = `Full Brother ${i}`;
            const nodeId = `FullBrother_${i}`;
            dot += createHeirNode(nodeId, `Full Bro ${i}`, key, siblingColor);
            if (heirs.father_status === 'alive') dot += `Father -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            if (heirs.mother_status === 'alive') dot += `Mother -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            if (heirs.father_status !== 'alive' && heirs.mother_status !== 'alive') dot += `Deceased -> ${nodeId} [label="Sibling", style=dashed, fontcolor="#999"];\n`;
            siblingNodes.push(nodeId);
        }
        for (let i = 1; i <= heirs.full_sister_count; i++) {
            const key = `Full Sister ${i}`;
            const nodeId = `FullSister_${i}`;
            dot += createHeirNode(nodeId, `Full Sis ${i}`, key, siblingColor);
            if (heirs.father_status === 'alive') dot += `Father -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            if (heirs.mother_status === 'alive') dot += `Mother -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            if (heirs.father_status !== 'alive' && heirs.mother_status !== 'alive') dot += `Deceased -> ${nodeId} [label="Sibling", style=dashed, fontcolor="#999"];\n`;
            siblingNodes.push(nodeId);
        }
        for (let i = 1; i <= heirs.paternal_half_brother_count; i++) {
            const key = `Paternal Half-Brother ${i}`;
            const nodeId = `PaternalHalfBrother_${i}`;
            dot += createHeirNode(nodeId, `Pat Half-Bro ${i}`, key, siblingColor);
            if (heirs.father_status === 'alive') dot += `Father -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            else dot += `Deceased -> ${nodeId} [label="Half-Sibling", style=dashed, fontcolor="#999"];\n`;
            siblingNodes.push(nodeId);
        }
        for (let i = 1; i <= heirs.paternal_half_sister_count; i++) {
            const key = `Paternal Half-Sister ${i}`;
            const nodeId = `PaternalHalfSister_${i}`;
            dot += createHeirNode(nodeId, `Pat Half-Sis ${i}`, key, siblingColor);
            if (heirs.father_status === 'alive') dot += `Father -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            else dot += `Deceased -> ${nodeId} [label="Half-Sibling", style=dashed, fontcolor="#999"];\n`;
            siblingNodes.push(nodeId);
        }
        for (let i = 1; i <= heirs.maternal_half_brother_count; i++) {
            const key = `Maternal Half-Brother ${i}`;
            const nodeId = `MaternalHalfBrother_${i}`;
            dot += createHeirNode(nodeId, `Mat Half-Bro ${i}`, key, siblingColor);
            if (heirs.mother_status === 'alive') dot += `Mother -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            else dot += `Deceased -> ${nodeId} [label="Half-Sibling", style=dashed, fontcolor="#999"];\n`;
            siblingNodes.push(nodeId);
        }
        for (let i = 1; i <= heirs.maternal_half_sister_count; i++) {
            const key = `Maternal Half-Sister ${i}`;
            const nodeId = `MaternalHalfSister_${i}`;
            dot += createHeirNode(nodeId, `Mat Half-Sis ${i}`, key, siblingColor);
            if (heirs.mother_status === 'alive') dot += `Mother -> ${nodeId} [label="Child", dir=back, fontcolor="#999"];\n`;
            else dot += `Deceased -> ${nodeId} [label="Half-Sibling", style=dashed, fontcolor="#999"];\n`;
            siblingNodes.push(nodeId);
        }
        if (siblingNodes.length > 0) {
            dot += `subgraph cluster_siblings { label="Siblings"; style=dashed; color="#adb5bd"; bgcolor="#f8f9fa"; fontsize=10; `;
            siblingNodes.forEach(nodeId => dot += `${nodeId}; `);
            dot += `}\n`;
        }
        
        // Baitulmal
        const baitulmalAmount = amountLookup['Baitulmal'] || 0;
        if (baitulmalAmount > 0 && netEstate > 0) {
            const baitulmalPercentage = ((baitulmalAmount / netEstate) * 100).toFixed(1);
            dot += `Baitulmal [label="Baitulmal\\n${formatAmount(baitulmalAmount)} (${baitulmalPercentage}%)", fillcolor="${baitulmalColor}", fontcolor=white, shape=box3d, width=2.4, height=1, fontsize=11];\n`;
            dot += `Deceased -> Baitulmal [style=dashed, color="#6c757d", penwidth=1.5, fontcolor="#999", label="Surplus"];\n`;
        }
        
        // Summary
        const totalDistributed = Object.values(amountLookup).reduce((sum, amt) => sum + (amt || 0), 0);
        const eligibleHeirs = Object.keys(amountLookup).filter(key => amountLookup[key] > 0 && key !== 'Baitulmal').length;
        
        dot += `
    Summary [label="TOTAL\\n${formatAmount(totalDistributed)}\\n${eligibleHeirs} Heirs", shape=box3d, fillcolor="${summaryColor}", fontcolor=white, fontsize=11, width=3, height=1.5];
    Deceased -> Summary [style=dashed, color="${summaryColor}", penwidth=2, arrowhead=none];
    `;
        
        dot += `}`;
        return dot;
    }
    
    function renderSimpleTreeWithAllHeirs(deceasedName, deceasedGender, heirs, calculationResultsArray, netEstate) {
        const outputDiv = document.getElementById('graphviz-output');
        const svgContainer = document.getElementById('graphviz-svg-container') || outputDiv;
        if (!svgContainer) return;
        
        const amountLookup = buildAmountLookupFromResults(calculationResultsArray);
        const colors = { deceased: '#dc3545', spouse: '#ffc107', father: '#007bff', mother: '#17a2b8', son: '#28a745', daughter: '#e83e8c', baitulmal: '#6c757d', sibling: '#6f42c1', grandparent: '#fd7e14' };
        const formatAmount = (amount) => { if (!amount || amount <= 0) return 'RM 0.00'; return `RM ${amount.toLocaleString('en-US', {minimumFractionDigits: 2})}`; };
        const calcPercent = (amount) => { if (!amount || !netEstate || amount <= 0) return ''; return ` (${((amount/netEstate)*100).toFixed(1)}%)`; };
        
        let html = `<div style="width:100%;padding:1.5rem;font-family:'Poppins',sans-serif;min-width:800px;background:white;border-radius:12px;">
            <h3 style="text-align:center;color:#495057;margin-bottom:1.5rem;">Family Tree with Complete Inheritance Distribution</h3>
            <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:2rem;">
                <div style="background:${colors.deceased};color:white;padding:1rem 2rem;border-radius:16px;font-weight:700;font-size:1rem;text-align:center;min-width:250px;box-shadow:0 4px 12px rgba(0,0,0,0.2);">${escapeHtml(deceasedName)}<br><span style="font-size:0.8rem;">Deceased</span></div>
                <div style="height:2rem;width:2px;background:#6c757d;"></div>
            </div>
            <div style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;margin-bottom:1.5rem;">`;
        
        if (heirs.husband_count > 0) {
            const amount = amountLookup['Husband'] || 0;
            html += `<div style="display:flex;flex-direction:column;align-items:center;"><div style="background:${colors.spouse};color:${amount > 0 ? 'black' : '#666'};padding:1rem 1.5rem;border-radius:12px;font-weight:600;text-align:center;min-width:180px;">Husband<br><span style="font-size:0.8rem;">Spouse</span><div style="font-size:0.8rem;margin-top:0.3rem;color:#2e7d32;">${formatAmount(amount)}${calcPercent(amount)}</div></div></div>`;
        } else if (heirs.wife_count > 0) {
            for (let i = 1; i <= heirs.wife_count; i++) {
                let amount = amountLookup[`Wife ${i}`] || 0;
                if (heirs.wife_count === 1 && amount === 0 && amountLookup['Wife']) amount = amountLookup['Wife'];
                html += `<div style="display:flex;flex-direction:column;align-items:center;"><div style="background:${colors.spouse};color:${amount > 0 ? 'black' : '#666'};padding:1rem 1.5rem;border-radius:12px;font-weight:600;text-align:center;min-width:180px;">Wife ${i}<br><span style="font-size:0.8rem;">Spouse</span><div style="font-size:0.8rem;margin-top:0.3rem;color:#2e7d32;">${formatAmount(amount)}${calcPercent(amount)}</div></div></div>`;
            }
        }
        html += `</div>`;
        
        if (heirs.father_status === 'alive' || heirs.mother_status === 'alive') {
            html += `<div style="display:flex;gap:2rem;flex-wrap:wrap;justify-content:center;margin-bottom:1.5rem;">`;
            if (heirs.father_status === 'alive') {
                const amount = amountLookup['Father'] || 0;
                html += `<div style="display:flex;flex-direction:column;align-items:center;"><div style="background:${colors.father};color:${amount > 0 ? 'white' : '#666'};padding:1rem 1.5rem;border-radius:12px;font-weight:600;text-align:center;min-width:180px;">Father<br><span style="font-size:0.8rem;">Parent</span><div style="font-size:0.8rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div></div>`;
            }
            if (heirs.mother_status === 'alive') {
                const amount = amountLookup['Mother'] || 0;
                html += `<div style="display:flex;flex-direction:column;align-items:center;"><div style="background:${colors.mother};color:${amount > 0 ? 'white' : '#666'};padding:1rem 1.5rem;border-radius:12px;font-weight:600;text-align:center;min-width:180px;">Mother<br><span style="font-size:0.8rem;">Parent</span><div style="font-size:0.8rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div></div>`;
            }
            html += `</div>`;
        }
        
        if (heirs.son_count > 0 || heirs.daughter_count > 0) {
            html += `<div style="margin-top:1rem;"><h4 style="color:#6c757d;margin-bottom:0.8rem;text-align:center;font-size:1rem;">Children</h4><div style="display:flex;gap:0.8rem;flex-wrap:wrap;justify-content:center;">`;
            for (let i = 1; i <= heirs.son_count; i++) {
                const amount = amountLookup[`Son ${i}`] || amountLookup['Son'] || 0;
                html += `<div style="background:${colors.son};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Son ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            for (let i = 1; i <= heirs.daughter_count; i++) {
                const amount = amountLookup[`Daughter ${i}`] || amountLookup['Daughter'] || 0;
                html += `<div style="background:${colors.daughter};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Daughter ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            html += `</div></div>`;
        }
        
        const hasSiblings = heirs.full_brother_count > 0 || heirs.full_sister_count > 0 || 
                           heirs.paternal_half_brother_count > 0 || heirs.paternal_half_sister_count > 0 ||
                           heirs.maternal_half_brother_count > 0 || heirs.maternal_half_sister_count > 0;
        
        if (hasSiblings) {
            html += `<div style="margin-top:1rem;"><h4 style="color:#6c757d;margin-bottom:0.8rem;text-align:center;font-size:1rem;">Siblings</h4><div style="display:flex;gap:0.8rem;flex-wrap:wrap;justify-content:center;">`;
            
            for (let i = 1; i <= heirs.full_brother_count; i++) {
                const amount = amountLookup[`Full Brother ${i}`] || amountLookup['FullBrother'] || 0;
                html += `<div style="background:${colors.sibling};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Full Brother ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            for (let i = 1; i <= heirs.full_sister_count; i++) {
                const amount = amountLookup[`Full Sister ${i}`] || amountLookup['FullSister'] || 0;
                html += `<div style="background:${colors.sibling};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Full Sister ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            for (let i = 1; i <= heirs.paternal_half_brother_count; i++) {
                const amount = amountLookup[`Paternal Half-Brother ${i}`] || amountLookup['PaternalHalfBrother'] || 0;
                html += `<div style="background:${colors.sibling};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Paternal Half-Brother ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            for (let i = 1; i <= heirs.paternal_half_sister_count; i++) {
                const amount = amountLookup[`Paternal Half-Sister ${i}`] || amountLookup['PaternalHalfSister'] || 0;
                html += `<div style="background:${colors.sibling};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Paternal Half-Sister ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            for (let i = 1; i <= heirs.maternal_half_brother_count; i++) {
                const amount = amountLookup[`Maternal Half-Brother ${i}`] || amountLookup['MaternalHalfBrother'] || 0;
                html += `<div style="background:${colors.sibling};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Maternal Half-Brother ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            for (let i = 1; i <= heirs.maternal_half_sister_count; i++) {
                const amount = amountLookup[`Maternal Half-Sister ${i}`] || amountLookup['MaternalHalfSister'] || 0;
                html += `<div style="background:${colors.sibling};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Maternal Half-Sister ${i}<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            
            html += `</div></div>`;
        }
        
        if (heirs.fathers_father_status === 'alive' || heirs.fathers_mother_status === 'alive' || heirs.mothers_mother_status === 'alive') {
            html += `<div style="margin-top:1rem;"><h4 style="color:#6c757d;margin-bottom:0.8rem;text-align:center;font-size:1rem;">Grandparents</h4><div style="display:flex;gap:0.8rem;flex-wrap:wrap;justify-content:center;">`;
            
            if (heirs.fathers_father_status === 'alive') {
                const amount = amountLookup['Paternal Grandfather'] || 0;
                html += `<div style="background:${colors.grandparent};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Father's Father<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            if (heirs.fathers_mother_status === 'alive') {
                const amount = amountLookup['Paternal Grandmother'] || 0;
                html += `<div style="background:${colors.grandparent};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Father's Mother<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            if (heirs.mothers_mother_status === 'alive') {
                const amount = amountLookup['Maternal Grandmother'] || 0;
                html += `<div style="background:${colors.grandparent};color:${amount > 0 ? 'white' : '#666'};padding:0.8rem 1.2rem;border-radius:10px;font-weight:600;min-width:150px;text-align:center;">Mother's Mother<div style="font-size:0.7rem;margin-top:0.3rem;color:#d4edda;">${formatAmount(amount)}${calcPercent(amount)}</div></div>`;
            }
            
            html += `</div></div>`;
        }
        
        const baitulmalAmount = amountLookup['Baitulmal'] || 0;
        if (baitulmalAmount > 0) {
            html += `<div style="margin-top:1.5rem;text-align:center;"><h4 style="color:#6c757d;margin-bottom:0.8rem;font-size:1rem;">Baitulmal (State Treasury)</h4><div style="background:${colors.baitulmal};color:white;padding:0.8rem 1.5rem;border-radius:12px;font-weight:600;display:inline-block;">${formatAmount(baitulmalAmount)}${calcPercent(baitulmalAmount)}</div></div>`;
        }
        
        const totalDistributed = Object.values(amountLookup).reduce((sum, amt) => sum + (amt || 0), 0);
        const eligibleHeirs = Object.keys(amountLookup).filter(key => amountLookup[key] > 0 && key !== 'Baitulmal').length;
        
        html += `<div style="margin-top:2rem;text-align:center;padding:1rem;background:linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);border-radius:16px;color:white;">
            <div style="font-size:0.8rem;margin-bottom:0.3rem;">TOTAL DISTRIBUTED</div>
            <div style="font-size:1.6rem;font-weight:800;">RM ${netEstate.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
            <div style="font-size:0.7rem;margin-top:0.3rem;">${eligibleHeirs} Eligible Heirs | Net Estate: RM ${netEstate.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
        </div>`;
        
        html += `</div>`;
        svgContainer.innerHTML = html;
        graphvizInstance = null;
        familyTreeGenerated = true;
    }
    
    function generateFamilyTree() {
        if (!calculationResults || calculationResults.length === 0) {
            ModernAlert.warning('No calculation results available for tree generation.', 'No Data');
            return;
        }
        
        showLoading('Generating Family Tree...', 'Creating complete family tree with ALL heirs and distribution amounts');
        
        try {
            const deceasedName = calculationData.deceased_name || 'Deceased';
            const deceasedGender = calculationData.deceased_gender;
            
            // Build heirs object from calculation data (same as in index)
            const heirs = {
                wife_count: calculationData.wife_count || 0,
                husband_count: calculationData.husband_count || 0,
                father_status: calculationData.father_status || 'deceased',
                mother_status: calculationData.mother_status || 'deceased',
                son_count: calculationData.son_count || 0,
                daughter_count: calculationData.daughter_count || 0,
                fathers_father_status: calculationData.fathers_father_status || 'deceased',
                fathers_mother_status: calculationData.fathers_mother_status || 'deceased',
                mothers_mother_status: calculationData.mothers_mother_status || 'deceased',
                full_brother_count: calculationData.full_brother_count || 0,
                full_sister_count: calculationData.full_sister_count || 0,
                paternal_half_brother_count: calculationData.paternal_half_brother_count || 0,
                paternal_half_sister_count: calculationData.paternal_half_sister_count || 0,
                maternal_half_brother_count: calculationData.maternal_half_brother_count || 0,
                maternal_half_sister_count: calculationData.maternal_half_sister_count || 0
            };
            
            const netEstate = calculationResults.netEstate || calculationData.net_assets || 0;
            
            const dotCode = generateDOTCodeWithAllHeirs(deceasedName, deceasedGender, heirs, netEstate, calculationResults);
            
            const outputDiv = document.getElementById('graphviz-output');
            const placeholder = document.getElementById('graphvizPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
            outputDiv.innerHTML = '';
            
            const svgContainer = document.createElement('div');
            svgContainer.id = 'graphviz-svg-container';
            svgContainer.style.width = '100%';
            svgContainer.style.height = '100%';
            svgContainer.style.display = 'flex';
            svgContainer.style.alignItems = 'center';
            svgContainer.style.justifyContent = 'center';
            svgContainer.style.minHeight = '600px';
            outputDiv.appendChild(svgContainer);
            
            try {
                const viz = new Viz();
                viz.renderSVGElement(dotCode, { engine: currentGraphvizLayout })
                    .then(function(svgElement) {
                        svgElement.setAttribute('width', '100%');
                        svgElement.setAttribute('height', '100%');
                        svgElement.style.minWidth = '900px';
                        svgElement.style.minHeight = '600px';
                        svgElement.style.maxWidth = 'none';
                        svgElement.style.maxHeight = 'none';
                        
                        svgViewBox = svgElement.getAttribute('viewBox');
                        if (!svgViewBox) {
                            try {
                                const bbox = svgElement.getBBox();
                                svgViewBox = `${bbox.x} ${bbox.y} ${bbox.width} ${bbox.height}`;
                                svgElement.setAttribute('viewBox', svgViewBox);
                            } catch(e) {
                                svgViewBox = '0 0 1200 800';
                                svgElement.setAttribute('viewBox', svgViewBox);
                            }
                        }
                        
                        svgContainer.innerHTML = '';
                        svgContainer.appendChild(svgElement);
                        
                        graphvizInstance = svgElement;
                        familyTreeGenerated = true;
                        addZoomPanToSVG(svgElement);
                        document.getElementById('zoomControls').style.display = 'flex';
                        document.getElementById('layoutControls').style.display = 'flex';
                        
                        hideLoading();
                        ModernAlert.success('Family tree generated!', 'Tree Generated');
                    })
                    .catch(error => {
                        console.error('Graphviz rendering error:', error);
                        hideLoading();
                        renderSimpleTreeWithAllHeirs(deceasedName, deceasedGender, heirs, calculationResults, netEstate);
                        ModernAlert.success('Family tree generated using simple visualization.', 'Tree Generated');
                    });
            } catch (vizError) {
                console.error('Viz.js error:', vizError);
                hideLoading();
                renderSimpleTreeWithAllHeirs(deceasedName, deceasedGender, heirs, calculationResults, netEstate);
                ModernAlert.success('Family tree generated successfully.', 'Tree Generated');
            }
        } catch (error) {
            console.error('Tree generation error:', error);
            hideLoading();
            ModernAlert.error('Failed to generate family tree.', 'Generation Error');
        }
    }
    
    function addZoomPanToSVG(svgElement) {
        let isPanning = false;
        let startPoint = { x: 0, y: 0 };
        let viewBoxValues = svgViewBox?.split(' ')?.map(Number);
        if (!viewBoxValues || viewBoxValues.length !== 4) viewBoxValues = [0, 0, 1200, 800];
        let viewBox = { x: viewBoxValues[0], y: viewBoxValues[1], width: viewBoxValues[2], height: viewBoxValues[3] };
        
        svgElement.style.cursor = 'grab';
        
        svgElement.addEventListener('wheel', function(e) {
            e.preventDefault();
            const zoomIntensity = 0.1;
            const delta = e.deltaY < 0 ? 1 - zoomIntensity : 1 + zoomIntensity;
            const newWidth = viewBox.width * delta;
            const newHeight = viewBox.height * delta;
            const rect = svgElement.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;
            const dx = (mouseX / rect.width) * (newWidth - viewBox.width);
            const dy = (mouseY / rect.height) * (newHeight - viewBox.height);
            viewBox.x -= dx; viewBox.y -= dy;
            viewBox.width = newWidth; viewBox.height = newHeight;
            svgElement.setAttribute('viewBox', `${viewBox.x} ${viewBox.y} ${viewBox.width} ${viewBox.height}`);
            currentScale *= delta;
        });
        
        svgElement.addEventListener('mousedown', function(e) {
            if (e.button === 0) { isPanning = true; startPoint = { x: e.clientX, y: e.clientY }; svgElement.style.cursor = 'grabbing'; }
        });
        
        svgElement.addEventListener('mousemove', function(e) {
            if (isPanning) {
                const dx = (e.clientX - startPoint.x) * (viewBox.width / svgElement.clientWidth);
                const dy = (e.clientY - startPoint.y) * (viewBox.height / svgElement.clientHeight);
                viewBox.x -= dx; viewBox.y -= dy;
                svgElement.setAttribute('viewBox', `${viewBox.x} ${viewBox.y} ${viewBox.width} ${viewBox.height}`);
                startPoint = { x: e.clientX, y: e.clientY };
            }
        });
        
        svgElement.addEventListener('mouseup', function() { isPanning = false; svgElement.style.cursor = 'grab'; });
        svgElement.addEventListener('mouseleave', function() { isPanning = false; svgElement.style.cursor = 'default'; });
    }
    
    // ===== EXPORT FUNCTIONS =====
    function showLoading(text = 'Processing...', subtext = 'Please wait...') {
        const overlay = document.getElementById('loadingOverlay');
        document.getElementById('loadingText').textContent = text;
        document.getElementById('loadingSubtext').textContent = subtext;
        if (overlay) overlay.classList.add('active');
    }
    
    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.remove('active');
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    function ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    // Export Family Tree PDF (landscape)
    async function exportTreePDF() {
        if (!familyTreeGenerated && !graphvizInstance && !document.querySelector('#graphviz-svg-container svg, #graphviz-svg-container > div')) {
            ModernAlert.warning('Please generate the family tree first.', 'Tree Required');
            return;
        }
        
        showLoading('Exporting Family Tree as PDF...', 'Generating PDF with complete family tree');
        
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            const deceasedName = calculationData.deceased_name || 'Unknown';
            
            doc.setFillColor(26, 95, 180);
            doc.rect(0, 0, 297, 25, 'F');
            doc.setFontSize(18);
            doc.setTextColor(255, 215, 0);
            doc.text('FAMILY TREE', 148.5, 12, { align: 'center', fontStyle: 'bold' });
            doc.setFontSize(9);
            doc.setTextColor(255, 255, 255);
            const dateStr = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            doc.text(`Deceased: ${deceasedName} | Generated: ${dateStr}`, 148.5, 22, { align: 'center' });
            
            await new Promise(resolve => setTimeout(resolve, 500));
            
            const treeContainer = document.querySelector('#graphviz-svg-container svg');
            const simpleTreeContainer = document.querySelector('#graphviz-svg-container > div');
            
            if (treeContainer || simpleTreeContainer) {
                const tempDiv = document.createElement('div');
                tempDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;width:1100px;padding:20px;background:white;border-radius:8px;font-family:Poppins,sans-serif;';
                const clone = (treeContainer || simpleTreeContainer).cloneNode(true);
                
                if (clone.tagName === 'svg') {
                    let viewBox = clone.getAttribute('viewBox');
                    if (viewBox) {
                        let parts = viewBox.split(' ').map(Number);
                        if (parts.length === 4) {
                            parts[2] = Math.max(parts[2], 1400);
                            parts[3] = Math.max(parts[3], 900);
                            clone.setAttribute('viewBox', parts.join(' '));
                        }
                    } else {
                        clone.setAttribute('viewBox', '0 0 1400 900');
                    }
                    clone.setAttribute('width', '1100px');
                    clone.setAttribute('height', 'auto');
                } else {
                    clone.style.minWidth = '1000px';
                    clone.style.fontFamily = "'Poppins', sans-serif";
                }
                
                tempDiv.appendChild(clone);
                document.body.appendChild(tempDiv);
                await new Promise(resolve => setTimeout(resolve, 500));
                
                const canvas = await html2canvas(tempDiv, { 
                    scale: 3,
                    backgroundColor: '#ffffff', 
                    logging: false,
                    allowTaint: true,
                    useCORS: true,
                    windowWidth: 1200,
                    windowHeight: 1000
                });
                document.body.removeChild(tempDiv);
                
                const maxWidth = 267;
                const maxHeight = 175;
                let imgWidth = maxWidth;
                let imgHeight = (canvas.height / canvas.width) * imgWidth;
                if (imgHeight > maxHeight) { imgHeight = maxHeight; imgWidth = (canvas.width / canvas.height) * imgHeight; }
                const xPos = (297 - imgWidth) / 2;
                const yPos = 28;
                doc.addImage(canvas.toDataURL('image/png'), 'PNG', xPos, yPos, imgWidth, imgHeight);
                
                doc.setFontSize(7); doc.setTextColor(100, 100, 100);
                doc.text('© Generated by Neo Faraid', 148.5, yPos + imgHeight + 8, { align: 'center' });
            } else {
                doc.setFontSize(12); doc.setTextColor(200, 0, 0);
                doc.text('Family tree could not be captured. Please ensure the tree is visible.', 148.5, 100, { align: 'center' });
            }
            
            const fileName = `Family Tree - ${deceasedName.replace(/\s+/g, ' ')}.pdf`;
            doc.save(fileName);
            
            hideLoading();
            ModernAlert.success('Family tree exported as PDF!', 'Export Complete');
        } catch (error) {
            console.error('Export error:', error);
            hideLoading();
            ModernAlert.error('Failed to export tree PDF. Please try again.', 'Export Error');
        }
    }
    
    // Export Full Report (similar to index)
    async function exportFullReport() {
        if (!calculationResults || calculationResults.length === 0) {
            ModernAlert.warning('No distribution data available for report.', 'No Data');
            return;
        }
        
        if (!familyTreeGenerated && !graphvizInstance && !document.querySelector('#graphviz-svg-container svg, #graphviz-svg-container > div')) {
            ModernAlert.info('Generating family tree for complete report...', 'Processing');
            generateFamilyTree();
            await new Promise(resolve => setTimeout(resolve, 3000));
        }
        
        showLoading('Generating Faraid Report...', 'Creating comprehensive document');
        
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            let currentPage = 1;
            
            const deceasedName = calculationData.deceased_name || 'Unknown';
            const deceasedGender = calculationData.deceased_gender;
            const dateOfDeath = calculationData.date_of_death;
            const maritalStatus = calculationData.marital_status;
            const causeOfDeath = calculationData.cause_of_death || '';
            const deathPlace = calculationData.death_place || '';
            const contactEmail = calculationData.contact_email || '';
            const contactPhone = calculationData.contact_phone || '';
            const residentialAddress = calculationData.residential_address || '';
            const netEstate = calculationResults.netEstate || calculationData.net_assets || 0;
            
            let yPos = 18;
            
            doc.setFillColor(26, 95, 180);
            doc.rect(0, 0, 210, 28, 'F');
            doc.setFontSize(16);
            doc.setTextColor(255, 215, 0);
            doc.text('FARAID REPORT', 105, 13, { align: 'center', fontStyle: 'bold' });
            doc.setFontSize(8);
            doc.setTextColor(255, 255, 255);
            const genDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            doc.text(`Generated: ${genDate}`, 105, 22, { align: 'center' });
            yPos = 34;
            
            function addSectionTitle(title) {
                doc.setFontSize(11);
                doc.setTextColor(26, 95, 180);
                doc.text(title, 15, yPos);
                yPos += 8;
            }
            
            function addInfoTable(data, columnWidths = [45, 130]) {
                doc.autoTable({
                    body: data,
                    startY: yPos,
                    theme: 'plain',
                    columnStyles: { 0: { fontStyle: 'bold', cellWidth: columnWidths[0] }, 1: { cellWidth: columnWidths[1] } },
                    margin: { left: 15 },
                    styles: { fontSize: 9, cellPadding: 3, lineColor: [226, 232, 240], lineWidth: 0.1, font: 'helvetica' }
                });
                yPos = doc.lastAutoTable.finalY + 8;
                if (yPos > 270) {
                    doc.addPage();
                    currentPage++;
                    yPos = 18;
                    doc.setFillColor(26, 95, 180);
                    doc.rect(0, 0, 210, 28, 'F');
                    doc.setFontSize(16); doc.setTextColor(255, 215, 0);
                    doc.text('FARAID REPORT', 105, 13, { align: 'center', fontStyle: 'bold' });
                    doc.setFontSize(8); doc.setTextColor(255, 255, 255);
                    doc.text(`Deceased: ${deceasedName}`, 105, 22, { align: 'center' });
                    yPos = 34;
                }
                return yPos;
            }
            
            addSectionTitle('1. DECEASED INFORMATION');
            const deceasedData = [
                ['Full Name', deceasedName],
                ['Gender', ucfirst(deceasedGender)],
                ['Date of Death', dateOfDeath ? new Date(dateOfDeath).toLocaleDateString() : 'N/A'],
                ['Marital Status', ucfirst(maritalStatus)],
                ['Cause of Death', causeOfDeath || 'N/A'],
                ['Place of Death', deathPlace || 'N/A'],
                ['Contact Email', contactEmail || 'N/A'],
                ['Contact Phone', contactPhone || 'N/A'],
                ['Residential Address', residentialAddress || 'N/A']
            ];
            addInfoTable(deceasedData);
            yPos += 4;
            
            // Assets summary from stored data
            const assetsData = calculationData.assets_data ? (typeof calculationData.assets_data === 'string' ? JSON.parse(calculationData.assets_data) : calculationData.assets_data) : { properties: [] };
            const properties = assetsData.properties || [];
            const totalGross = properties.reduce((sum, p) => sum + (p.value || 0), 0);
            const totalShare = properties.reduce((sum, p) => sum + ((p.value || 0) * ((p.ownership || 100) / 100)), 0);
            
            addSectionTitle('2. FINANCIAL SUMMARY');
            const financialData = [
                ['Total Assets (Before Deduction)', `RM ${totalGross.toLocaleString('en-US', {minimumFractionDigits: 2})}`],
                ['Net Estate for Distribution', `RM ${netEstate.toLocaleString('en-US', {minimumFractionDigits: 2})}`],
                ['Total Eligible Heirs', (distributionSummary.total_eligible || 0).toString()],
                ['Scenario', `Scenario ${calculationData.scenario_number || 'N/A'} - ${calculationData.scenario_description || ''}`]
            ];
            addInfoTable(financialData);
            yPos += 4;
            
            if (properties.length > 0 && yPos > 240) {
                doc.addPage();
                currentPage++;
                yPos = 18;
                doc.setFillColor(26, 95, 180);
                doc.rect(0, 0, 210, 28, 'F');
                doc.setFontSize(16); doc.setTextColor(255, 215, 0);
                doc.text('FARAID REPORT', 105, 13, { align: 'center', fontStyle: 'bold' });
                doc.setFontSize(8); doc.setTextColor(255, 255, 255);
                doc.text(`Deceased: ${deceasedName}`, 105, 22, { align: 'center' });
                yPos = 34;
            }
            
            if (properties.length > 0) {
                addSectionTitle('3. ASSETS LIST');
                const assetsTableData = properties.map(p => [
                    p.type, p.label || 'Asset', p.description || '-',
                    `RM ${p.value.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                    `${p.ownership || 100}%`,
                    `RM ${((p.value || 0) * ((p.ownership || 100) / 100)).toLocaleString('en-US', {minimumFractionDigits: 2})}`
                ]);
                doc.autoTable({
                    head: [['Type', 'Category', 'Description', 'Value', 'Ownership %', 'Your Share']],
                    body: assetsTableData,
                    startY: yPos,
                    theme: 'grid',
                    headStyles: { fillColor: [26, 95, 180], fontSize: 9, textColor: 255, fontStyle: 'bold' },
                    alternateRowStyles: { fillColor: [245, 245, 245] },
                    styles: { fontSize: 8, cellPadding: 3, lineColor: [200, 200, 200], lineWidth: 0.1, font: 'helvetica' },
                    columnStyles: { 0: { cellWidth: 35 }, 1: { cellWidth: 22 }, 2: { cellWidth: 45 }, 3: { cellWidth: 28 }, 4: { cellWidth: 20 }, 5: { cellWidth: 30 } }
                });
                yPos = doc.lastAutoTable.finalY + 10;
                if (yPos > 270) {
                    doc.addPage();
                    currentPage++;
                    yPos = 18;
                    doc.setFillColor(26, 95, 180);
                    doc.rect(0, 0, 210, 28, 'F');
                    doc.setFontSize(16); doc.setTextColor(255, 215, 0);
                    doc.text('FARAID REPORT', 105, 13, { align: 'center', fontStyle: 'bold' });
                    doc.setFontSize(8); doc.setTextColor(255, 255, 255);
                    doc.text(`Deceased: ${deceasedName}`, 105, 22, { align: 'center' });
                    yPos = 34;
                }
            }
            
            addSectionTitle('4. INHERITANCE DISTRIBUTION');
            const distData = calculationResults
                .filter(r => r.heir !== 'Baitulmal')
                .map(r => {
                    const fractionDisplay = r.fractionDisplay || "0";
                    const amountNum = parseFloat(r.amount);
                    return [r.heir, ucfirst(r.relationship), fractionDisplay,
                        `RM ${amountNum.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                        parseFloat(r.percentage).toFixed(2) + '%', r.status];
                });
            doc.autoTable({
                head: [['Heir', 'Relationship', 'Fraction', 'Amount', 'Percentage', 'Status']],
                body: distData,
                startY: yPos,
                theme: 'grid',
                headStyles: { fillColor: [26, 95, 180], fontSize: 9, textColor: 255, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [245, 245, 245] },
                styles: { fontSize: 8, cellPadding: 3, lineColor: [200, 200, 200], lineWidth: 0.1, font: 'helvetica' },
                columnStyles: { 0: { cellWidth: 30 }, 1: { cellWidth: 25 }, 2: { cellWidth: 20 }, 3: { cellWidth: 35 }, 4: { cellWidth: 25 }, 5: { cellWidth: 35 } }
            });
            yPos = doc.lastAutoTable.finalY + 10;
            
            if (yPos > 100) {
                doc.addPage();
                currentPage++;
                yPos = 18;
                doc.setFillColor(26, 95, 180);
                doc.rect(0, 0, 210, 28, 'F');
                doc.setFontSize(16); doc.setTextColor(255, 215, 0);
                doc.text('FARAID REPORT - FAMILY TREE', 105, 13, { align: 'center', fontStyle: 'bold' });
                doc.setFontSize(8); doc.setTextColor(255, 255, 255);
                doc.text(`Deceased: ${deceasedName}`, 105, 22, { align: 'center' });
                yPos = 34;
            }
            
            addSectionTitle('5. FAMILY TREE WITH INHERITANCE DISTRIBUTION');
            
            await new Promise(resolve => setTimeout(resolve, 500));
            
            const treeContainer = document.querySelector('#graphviz-svg-container svg');
            const simpleTree = document.querySelector('#graphviz-svg-container > div');
            
            if (treeContainer || simpleTree) {
                try {
                    const tempDiv = document.createElement('div');
                    tempDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;width:1000px;padding:20px;background:white;border-radius:8px;font-family:Poppins,sans-serif;';
                    const clone = (treeContainer || simpleTree).cloneNode(true);
                    
                    if (clone.tagName === 'svg') {
                        let viewBox = clone.getAttribute('viewBox');
                        if (!viewBox) viewBox = '0 0 1200 800';
                        clone.setAttribute('viewBox', viewBox);
                        clone.setAttribute('width', '1000px');
                        clone.setAttribute('height', 'auto');
                    } else {
                        clone.style.minWidth = '1000px';
                        clone.style.fontFamily = "'Poppins', sans-serif";
                    }
                    
                    tempDiv.appendChild(clone);
                    document.body.appendChild(tempDiv);
                    await new Promise(resolve => setTimeout(resolve, 500));
                    
                    const canvas = await html2canvas(tempDiv, { 
                        scale: 2, 
                        backgroundColor: '#ffffff', 
                        logging: false,
                        allowTaint: true,
                        useCORS: true,
                        windowWidth: 1200,
                        windowHeight: 1000
                    });
                    document.body.removeChild(tempDiv);
                    
                    const maxWidth = 170;
                    const maxHeight = 240;
                    let imgWidth = maxWidth;
                    let imgHeight = (canvas.height / canvas.width) * imgWidth;
                    if (imgHeight > maxHeight) { imgHeight = maxHeight; imgWidth = (canvas.width / canvas.height) * imgHeight; }
                    const xPos = (210 - imgWidth) / 2;
                    doc.addImage(canvas.toDataURL('image/png'), 'PNG', xPos, yPos, imgWidth, imgHeight);
                    yPos += imgHeight + 10;
                } catch (err) {
                    console.warn('Tree capture failed', err);
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    doc.text('Family tree could not be captured.', 15, yPos);
                    yPos += 8;
                }
            } else {
                doc.setFontSize(8);
                doc.text('Family tree not available.', 15, yPos);
                yPos += 8;
            }
            
            if (yPos > 250) {
                doc.addPage();
                currentPage++;
                yPos = 18;
            }
            doc.setFontSize(7);
            doc.setTextColor(100, 100, 100);
            doc.text('© Generated by Neo Faraid | All calculations follow Shariah principles and Faraid rules.', 105, yPos + 22, { align: 'center' });
            
            const fileName = `Faraid Report - ${deceasedName.replace(/\s+/g, ' ')}.pdf`;
            doc.save(fileName);
            
            hideLoading();
            ModernAlert.success('Complete report generated successfully!', 'Export Complete');
        } catch (error) {
            console.error('Full report error:', error);
            hideLoading();
            ModernAlert.error('Failed to generate report. Please try again.', 'Export Error');
        }
    }
    
    // ===== ZOOM AND LAYOUT CONTROLS =====
    function zoomInTree() {
        if (!graphvizInstance) return;
        const viewBoxValues = graphvizInstance.getAttribute('viewBox').split(' ').map(Number);
        const viewBox = { x: viewBoxValues[0], y: viewBoxValues[1], width: viewBoxValues[2], height: viewBoxValues[3] };
        const zoomFactor = 0.8;
        const newWidth = viewBox.width * zoomFactor;
        const newHeight = viewBox.height * zoomFactor;
        const dx = (viewBox.width - newWidth) / 2;
        const dy = (viewBox.height - newHeight) / 2;
        viewBox.x += dx; viewBox.y += dy; viewBox.width = newWidth; viewBox.height = newHeight;
        graphvizInstance.setAttribute('viewBox', `${viewBox.x} ${viewBox.y} ${viewBox.width} ${viewBox.height}`);
        currentScale *= zoomFactor;
    }
    
    function zoomOutTree() {
        if (!graphvizInstance) return;
        const viewBoxValues = graphvizInstance.getAttribute('viewBox').split(' ').map(Number);
        const viewBox = { x: viewBoxValues[0], y: viewBoxValues[1], width: viewBoxValues[2], height: viewBoxValues[3] };
        const zoomFactor = 1.2;
        const newWidth = viewBox.width * zoomFactor;
        const newHeight = viewBox.height * zoomFactor;
        const dx = (viewBox.width - newWidth) / 2;
        const dy = (viewBox.height - newHeight) / 2;
        viewBox.x += dx; viewBox.y += dy; viewBox.width = newWidth; viewBox.height = newHeight;
        graphvizInstance.setAttribute('viewBox', `${viewBox.x} ${viewBox.y} ${viewBox.width} ${viewBox.height}`);
        currentScale *= zoomFactor;
    }
    
    function resetTreeView() {
        if (!graphvizInstance) return;
        graphvizInstance.setAttribute('viewBox', svgViewBox);
        currentScale = 1;
    }
    
    // ===== DELETE CONFIRMATION =====
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this calculation? This action cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    }
    
    // ===== EVENT LISTENERS =====
    document.getElementById('generateFamilyTree')?.addEventListener('click', generateFamilyTree);
    document.getElementById('generateTreeFromPlaceholder')?.addEventListener('click', generateFamilyTree);
    document.getElementById('refreshFamilyTree')?.addEventListener('click', generateFamilyTree);
    document.getElementById('zoomInBtn')?.addEventListener('click', zoomInTree);
    document.getElementById('zoomOutBtn')?.addEventListener('click', zoomOutTree);
    document.getElementById('resetViewBtn')?.addEventListener('click', resetTreeView);
    document.getElementById('exportFullReport')?.addEventListener('click', exportFullReport);
    document.getElementById('exportTreePDF')?.addEventListener('click', exportTreePDF);
    document.getElementById('exportTreePDFAction')?.addEventListener('click', exportTreePDF);
    
    // Layout controls
    document.querySelectorAll('.layout-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const layout = this.dataset.layout;
            document.querySelectorAll('.layout-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentGraphvizLayout = layout;
            if (graphvizInstance || familyTreeGenerated) {
                generateFamilyTree();
            }
        });
    });
    
    // ===== INITIALIZATION =====
    window.ModernAlert = ModernAlert;
    window.confirmDelete = confirmDelete;
    
    // Generate pie charts on page load
    setTimeout(() => {
        generatePieCharts();
    }, 500);
    
    // Auto-generate family tree if we're on the family tree tab
    const activeTab = document.querySelector('.modern-tab.active');
    if (activeTab && activeTab.dataset.section === 'family-tree') {
        setTimeout(() => {
            const placeholder = document.getElementById('graphvizPlaceholder');
            if (placeholder && placeholder.style.display !== 'none') {
                generateFamilyTree();
            }
        }, 1000);
    }
});
</script>
@endsection