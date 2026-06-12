@extends('layouts.app')

@section('title', 'Faraid Calculator')

@section('content')
<!-- Poppins Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Viz.js for Graphviz -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viz.js/2.1.2/viz.js" integrity="sha512-Mk7+95H5MxVfX8JqJxH8O7oN0QkHrQd6F+JFQ0d5q5Ff5h5H5v5Ff5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viz.js/2.1.2/full.render.js" integrity="sha512-Mk7+95H5MxVfX8JqJxH8O7oN0QkHrQd6F+JFQ0d5q5Ff5h5H5v5Ff5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f5f" crossorigin="anonymous"></script>

<!-- html2canvas & jsPDF -->
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

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
    
    .calculator-header {
        min-height: 50vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 4rem 2rem;
    }

    .calculator-header .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .calculator-header .animated-bg .bg-circle {
        position: absolute;
        border-radius: 50%;
    }

    .calculator-header .animated-bg .bg-circle-1 {
        top: 10%;
        right: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }

    .calculator-header .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .calculator-header .animated-bg .bg-circle-3 {
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .calculator-header .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }

    .calculator-header .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .calculator-header .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    .calculator-header .shape-1 {
        width: 40px;
        height: 40px;
        top: 20%;
        left: 10%;
        animation-name: float-1;
    }

    .calculator-header .shape-2 {
        width: 25px;
        height: 25px;
        top: 60%;
        left: 85%;
        animation-name: float-2;
        animation-delay: 1s;
    }

    .calculator-header .shape-3 {
        width: 35px;
        height: 35px;
        top: 75%;
        left: 15%;
        animation-name: float-3;
        animation-delay: 0.5s;
    }

    .calculator-header .shape-4 {
        width: 20px;
        height: 20px;
        top: 30%;
        left: 70%;
        animation-name: float-4;
        animation-delay: 1.5s;
    }

    .calculator-header .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 2rem;
    }

    .calculator-header .hero-kicker {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        display: inline-flex;
        padding: 0.75rem 1.75rem;
        border-radius: var(--border-radius-xl);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.2);
        transition: var(--transition);
    }

    .calculator-header .hero-kicker:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-2px);
    }

    .calculator-header .kicker-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .calculator-header .kicker-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 1rem;
    }

    .calculator-header .kicker-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        color: var(--accent-color);
    }

    .calculator-header .hero-title {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .calculator-header .hero-highlight {
        color: var(--accent-color);
        position: relative;
        display: inline-block;
    }

    .calculator-header .hero-highlight::after {
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

    .calculator-header .hero-subtitle {
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
    
    .form-group-modern {
        margin-bottom: 1.75rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--gray-800);
        font-weight: 600;
        font-size: 0.9375rem;
        letter-spacing: 0.3px;
    }
    
    .required-label::after {
        content: " *";
        color: var(--danger-color);
    }
    
    .input-group-modern {
        position: relative;
    }
    
    .form-input-modern {
        width: 100%;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        font-size: 1rem;
        transition: var(--transition);
        color: var(--gray-900);
    }
    
    .form-input-modern:focus {
        outline: none;
        border-color: var(--primary-color);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(26, 95, 180, 0.1);
    }
    
    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: var(--gray-500);
    }
    
    select.form-input-modern {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.25rem;
        padding-right: 3rem;
    }
    
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
    
    .heir-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .heir-card {
        background: var(--white);
        padding: 1.5rem;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .heir-card::before {
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
    
    .heir-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .heir-card:hover::before {
        opacity: 1;
    }
    
    .heir-counter {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }
    
    .counter-btn {
        width: 44px;
        height: 44px;
        border: 2px solid var(--gray-200);
        background: var(--white);
        border-radius: var(--border-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 600;
        font-size: 1.25rem;
        color: var(--gray-700);
    }
    
    .counter-btn:hover {
        background: var(--primary-color);
        color: var(--white);
        border-color: var(--primary-color);
        transform: scale(1.05);
    }
    
    .counter-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: var(--gray-100);
    }
    
    .counter-input {
        width: 80px;
        text-align: center;
        border: 2px solid var(--gray-200);
        background: var(--white);
        border-radius: var(--border-radius-md);
        padding: 0.875rem;
        font-weight: 600;
        font-size: 1.125rem;
        color: var(--gray-900);
        transition: var(--transition);
    }
    
    .counter-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
    }
    
    .counter-hint {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-top: 0.5rem;
    }
    
    .status-select {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .status-btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 2px solid var(--gray-200);
        background: var(--white);
        border-radius: var(--border-radius-sm);
        cursor: pointer;
        transition: var(--transition);
        font-weight: 600;
        font-size: 0.875rem;
        text-align: center;
        color: var(--gray-700);
    }
    
    .status-btn:hover {
        background: var(--gray-50);
        border-color: var(--gray-300);
    }
    
    .status-btn.active {
        background: var(--primary-light);
        color: var(--primary-color);
        border-color: var(--primary-color);
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
    
    .btn-danger {
        background: var(--danger-color);
        color: var(--white);
    }
    
    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(220, 53, 69, 0.2);
    }
    
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
    
    .badge-real-estate { background: #e8f1fd; color: #1a5fb4; }
    .badge-financial { background: #d4edda; color: #155724; }
    .badge-investment { background: #fff3cd; color: #856404; }
    .badge-digital { background: #e0d4f5; color: #6f42c1; }
    .badge-secured { background: #f8d7da; color: #721c24; }
    .badge-unsecured { background: #d1ecf1; color: #0c5460; }
    .badge-religious { background: #d4edda; color: #155724; }
    .badge-heir { background: #e8f1fd; color: #1a5fb4; }
    
    .progress-container {
        margin: 3rem 0;
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
        width: 25%;
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
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255,255,255,0.3), 
            transparent);
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
        box-shadow: 0 0 0 4px rgba(6, 214, 160, 0.2);
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
        min-height: 600px;
        max-height: 800px;
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

    .tree-container-wrapper {
        overflow: auto;
    }

    #graphviz-output {
        width: 100%;
        height: 100%;
        min-height: 600px;
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
        min-height: 600px;
    }

    #graphviz-svg-container svg {
        max-width: 100%;
        height: auto;
        min-width: 900px;
        min-height: 600px;
    }
    
    #graphviz-svg-container svg g.node text {
        font-size: 13px !important;
        font-weight: 600 !important;
        font-family: 'Poppins', sans-serif !important;
    }
    
    #graphviz-svg-container svg g.node polygon,
    #graphviz-svg-container svg g.node ellipse {
        stroke-width: 2.5px !important;
    }
    
    #graphviz-svg-container svg g.edge path {
        stroke-width: 2px !important;
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
        min-height: 500px;
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
        color: var(--gray-700);
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
    
    .modern-alert.warning {
        border-left-color: var(--warning-color);
        background: rgba(255, 193, 7, 0.05);
    }
    
    .modern-alert.error {
        border-left-color: var(--danger-color);
        background: rgba(220, 53, 69, 0.05);
    }
    
    .modern-alert.info {
        border-left-color: var(--primary-color);
        background: rgba(26, 95, 180, 0.05);
    }
    
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
        z-index: 100000;
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
    
    .activation-summary-compact {
        background: var(--white);
        border-radius: var(--border-radius-md);
        padding: 1rem;
        border: 1px solid var(--gray-200);
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

    @media print {
        body * { visibility: hidden; }
        .glass-container, .glass-container * { visibility: visible; }
        .glass-container { position: absolute; left: 0; top: 0; width: 100%; background: white !important; padding: 20px; margin: 0; }
        .no-print { display: none !important; }
        .btn-group-modern, .modern-tabs, .modern-alert-container, .progress-container, .tree-controls, .export-controls { display: none !important; }
        .category-section { break-inside: avoid; page-break-inside: avoid; }
        .summary-card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .results-table { box-shadow: none !important; border: 1px solid #ddd !important; }
        .heir-card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .tree-container-wrapper { border: 1px solid #ddd !important; box-shadow: none !important; height: auto; max-height: none; }
        .chart-section { border: 1px solid #ddd !important; box-shadow: none !important; }
    }
    
    .faraid-report-pdf {
        font-family: 'Poppins', 'Helvetica', 'Arial', sans-serif;
        background: white;
        color: #1e293b;
    }
    
    .faraid-report-pdf .report-header {
        background: linear-gradient(135deg, #0d2d5c 0%, #1a5fb4 100%);
        padding: 20px;
        color: white;
        text-align: center;
        border-radius: 8px 8px 0 0;
    }
    
    .faraid-report-pdf .report-header h1 {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 24px;
        margin: 0;
        color: #ffd700;
    }
    
    .faraid-report-pdf .report-header p {
        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        margin: 5px 0 0;
        opacity: 0.9;
    }
    
    .faraid-report-pdf .section-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 14px;
        color: #1a5fb4;
        border-bottom: 2px solid #1a5fb4;
        padding-bottom: 4px;
        margin: 15px 0 10px;
    }
    
    .faraid-report-pdf table {
        font-family: 'Poppins', sans-serif;
        font-size: 9px;
        border-collapse: collapse;
        width: 100%;
    }
    
    .faraid-report-pdf th {
        background: #1a5fb4;
        color: white;
        font-weight: 600;
        padding: 6px;
        text-align: left;
    }
    
    .faraid-report-pdf td {
        padding: 5px 6px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .faraid-report-pdf .summary-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
    }
    
    .faraid-report-pdf .summary-value {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 18px;
        color: #1a5fb4;
    }

    #graphviz-svg-container svg {
        max-width: 100%;
        height: auto;
    }

    .export-ready .tree-container-wrapper {
        overflow: visible !important;
    }
    .export-ready #graphviz-svg-container svg {
        min-width: 1000px;
        width: 1000px;
    }
    
    @media (max-width: 1200px) {
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
        .calculator-header .hero-title { font-size: 2.5rem; }
        .calculator-header { min-height: 40vh !important; padding: 3rem 1.5rem; }
        .glass-container { padding: 0 1.5rem 2rem; }
        .modern-alert { min-width: 300px; }
        .export-buttons { flex-direction: column; }
        .export-btn { min-width: 100%; }
    }
    
    @media (max-width: 768px) {
        .calculator-header .hero-title { font-size: 2rem; }
        .calculator-header .hero-subtitle { font-size: 1.1rem; }
        .calculator-header { min-height: 35vh !important; padding: 2rem 1rem; }
        .modern-tabs { flex-direction: column; }
        .modern-tab { justify-content: flex-start; padding: 1rem 1.25rem; }
        .heir-grid, .summary-grid { grid-template-columns: 1fr; }
        .card-body { padding: 1.5rem; }
        .btn-group-modern { flex-direction: column; }
        .btn { width: 100%; }
        .modern-alert-container { left: 20px; right: 20px; max-width: none; }
        .modern-alert { min-width: auto; width: 100%; }
        .calculator-header .shape,
        .calculator-header .bg-circle-3 { display: none !important; }
        
        .family-tree-section { padding: 1.5rem; }
        .tree-container-wrapper { min-height: 500px; max-height: 600px; }
        .tree-controls { padding: 1rem; }
        .pie-chart-container { width: 250px; height: 250px; }
        .chart-center { width: 110px; height: 110px; }
    }
    
    @media (max-width: 480px) {
        .calculator-header .hero-title { font-size: 1.75rem; }
        .calculator-header { min-height: 30vh !important; padding: 1.5rem 1rem; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -2rem; }
        .family-tree-section { padding: 1rem; }
        .tree-container-wrapper { min-height: 400px; max-height: 500px; }
        .pie-chart-container { width: 220px; height: 220px; }
        .chart-center { width: 100px; height: 100px; }
        .layout-controls, .zoom-controls { position: relative; top: auto; right: auto; margin-top: 1rem; justify-content: center; }
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

<!-- Login Prompt Overlay (shown only when guest tries to save) -->
<div class="login-prompt-overlay" id="loginPromptOverlay">
    <div class="login-prompt-content">
        <div class="login-prompt-icon">
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h2 class="login-prompt-title">Login Required</h2>
        <p class="login-prompt-message">You need to login to save your calculation to the database.<br>As a guest, you can still calculate, preview, and export reports.</p>
        <div class="login-prompt-buttons">
            <button class="btn btn-secondary" id="cancelLoginBtn">Continue as Guest</button>
            <a href="{{ route('login') }}" class="btn btn-primary">Login Now</a>
            <a href="{{ route('register') }}" class="btn btn-ghost">Create Account</a>
        </div>
    </div>
</div>

<!-- Modern Header -->
<header class="hero-section calculator-header">
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
            <span class="hero-highlight">Faraid</span> Calculator
        </h1>
        
        <p class="hero-subtitle">
            Comprehensive Islamic inheritance calculation with family tree visualization, and export capabilities
        </p>
        
        @if(!Auth::check())
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: var(--border-radius-md); padding: 0.75rem 1.5rem; display: inline-flex; align-items: center; gap: 0.75rem; margin-top: 1rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span style="font-size: 0.875rem;">Guest Mode - <a href="{{ route('login') }}" style="color: var(--accent-color); text-decoration: underline;">Login</a> to save calculations</span>
        </div>
        @endif
    </div>
</header>

<!-- Main Container -->
<div class="glass-container">
    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-track">
            <div class="progress-fill" id="progressFill" style="width: 25%;"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step active" id="step1">Deceased</div>
            <div class="progress-step" id="step2">Assets</div>
            <div class="progress-step" id="step3">Heirs</div>
            <div class="progress-step" id="step4">Results</div>
        </div>
    </div>
    
    <!-- Modern Tabs -->
    <div class="modern-tabs">
        <button class="modern-tab active" data-section="deceased">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Deceased
        </button>
        <button class="modern-tab" data-section="assets">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Assets
        </button>
        <button class="modern-tab" data-section="heirs">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            Heirs
        </button>
        <button class="modern-tab" data-section="results">
            <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Results
        </button>
    </div>
    
    <form id="faraidForm" action="{{ route('calculator.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" id="totalAssets" name="total_assets" value="0">
        <input type="hidden" id="deceasedNric" name="deceased_nric" value="{{ old('deceased_nric') }}">
        
        <!-- SECTION 1: DECEASED -->
        <div class="glass-card active-section" id="deceasedSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h2 class="card-title">Deceased Information</h2>
                <span class="card-badge">Step 1 of 4</span>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="deceasedName" class="form-label required-label">Full Name</label>
                            <div class="input-group-modern">
                                <input type="text" id="deceasedName" name="deceased_name" class="form-input-modern" 
                                       placeholder="Enter full name" required value="{{ old('deceased_name') }}">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="deceasedGender" class="form-label required-label">Gender</label>
                            <div class="input-group-modern">
                                <select id="deceasedGender" name="deceased_gender" class="form-input-modern" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="male" {{ old('deceased_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('deceased_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="dateOfDeath" class="form-label required-label">Date of Death</label>
                            <div class="input-group-modern">
                                <input type="date" id="dateOfDeath" name="date_of_death" class="form-input-modern" 
                                       required value="{{ old('date_of_death', date('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="maritalStatus" class="form-label required-label">Marital Status</label>
                            <div class="input-group-modern">
                                <select id="maritalStatus" name="marital_status" class="form-input-modern" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="causeOfDeath" class="form-label required-label">Cause of Death</label>
                            <div class="input-group-modern">
                                <input type="text" id="causeOfDeath" name="cause_of_death" class="form-input-modern" 
                                       placeholder="Enter cause of death (if known)" required value="{{ old('cause_of_death') }}">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="deathPlace" class="form-label required-label">Place of Death</label>
                            <div class="input-group-modern">
                                <input type="text" id="deathPlace" name="death_place" class="form-input-modern" 
                                       placeholder="Enter place of death" required value="{{ old('death_place') }}">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="contactEmail" class="form-label required-label">Contact Email</label>
                            <div class="input-group-modern">
                                <input type="email" id="contactEmail" name="contact_email" class="form-input-modern" 
                                       placeholder="Enter contact email" required value="{{ old('contact_email') }}">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="contactPhone" class="form-label required-label">Contact Phone</label>
                            <div class="input-group-modern">
                                <input type="tel" id="contactPhone" name="contact_phone" class="form-input-modern" 
                                       placeholder="Enter contact phone number" required value="{{ old('contact_phone') }}">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group-modern">
                            <label for="residentialAddress" class="form-label required-label">Residential Address</label>
                            <div class="input-group-modern">
                                <textarea id="residentialAddress" name="residential_address" class="form-input-modern" 
                                          rows="2" placeholder="Enter full residential address" required>{{ old('residential_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="btn-group-modern">
                    <div></div>
                    <button type="button" class="btn btn-primary next-section" data-next="assets" id="nextToAssets">
                        Continue to Assets
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- SECTION 2: ASSETS -->
        <div class="glass-card" id="assetsSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h2 class="card-title">Assets</h2>
                <span class="card-badge" id="totalAssetsBadge">0 Assets</span>
            </div>
            
            <div class="card-body">
                <!-- Summary Cards -->
                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="summary-title">Total Assets (Before Deduction)</div>
                        <div class="summary-value" id="totalAssetsGrossDisplay">RM 0.00</div>
                        <div class="summary-subtitle">Sum of all properties</div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-title">Total Assets (Your Share)</div>
                        <div class="summary-value" id="totalAssetsValue">RM 0.00</div>
                        <div class="summary-subtitle">After ownership percentage</div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-title">Eligible for Distribution</div>
                        <div class="summary-value text-success" id="netAssets">RM 0.00</div>
                        <div class="summary-subtitle">Total amount for inheritance</div>
                    </div>
                </div>
                
                <!-- Assets Input -->
                <div class="category-section">
                    <div class="category-header">
                        <h3 class="category-title">Properties &amp; Assets</h3>
                        <span class="category-badge">Add Multiple</span>
                    </div>
                    
                    <div class="heir-grid">
                        <div class="heir-card">
                            <label class="form-label required-label">Property Type</label>
                            <select id="propertyType" class="form-input-modern">
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
                                <optgroup label="Movable &amp; Financial Assets">
                                    <option value="Vehicle (Car / Motorcycle)">Vehicle (Car / Motorcycle)</option>
                                    <option value="Bank Savings">Bank Savings</option>
                                    <option value="Fixed Deposit">Fixed Deposit</option>
                                    <option value="EPF / KWSP Savings">EPF / KWSP Savings</option>
                                    <option value="Tabung Haji Savings">Tabung Haji Savings</option>
                                </optgroup>
                                <optgroup label="Investment Assets">
                                    <option value="ASB / Unit Trust Investment">ASB / Unit Trust Investment</option>
                                    <option value="Shares / Stocks">Shares / Stocks</option>
                                    <option value="Gold / Precious Metals">Gold / Precious Metals</option>
                                    <option value="Business Ownership">Business Ownership</option>
                                </optgroup>
                                <optgroup label="Other Assets">
                                    <option value="Insurance / Takaful Payout">Insurance / Takaful Payout</option>
                                    <option value="Cash in Hand">Cash in Hand</option>
                                    <option value="Digital Assets (Crypto / E-wallet)">Digital Assets (Crypto / E-wallet)</option>
                                    <option value="Other Assets">Other Assets</option>
                                </optgroup>
                            </select>
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Auto Category</label>
                            <div id="assetCategoryPreview" class="badge badge-real-estate" style="margin-top:0.5rem;">Real Estate</div>
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label required-label">Value</label>
                            <input type="number" id="propertyValue" class="form-input-modern" placeholder="300,000" min="0" step="0.01">
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label required-label">Ownership (%)</label>
                            <input type="number" id="propertyOwnership" class="form-input-modern" value="100" min="0" max="100" step="1">
                            <div class="counter-hint">Your ownership percentage</div>
                        </div>
                        
                        <div class="heir-card" style="grid-column: span 2;">
                            <label class="form-label">Description / Location</label>
                            <input type="text" id="propertyDescription" class="form-input-modern" placeholder="e.g., No. 123, Jalan SS2, Petaling Jaya or details about this asset">
                        </div>
                    </div>
                    
                    <div class="btn-group-modern" style="margin-top: 1rem; padding-top: 0; border-top: 0;">
                        <button type="button" class="btn btn-primary" id="addPropertyBtn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Asset
                        </button>
                        <button type="button" class="btn btn-danger" id="clearPropertiesBtn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Clear All
                        </button>
                    </div>
                    
                    <!-- Properties List -->
                    <div class="mt-4">
                        <div class="results-container">
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Value</th>
                                        <th>Ownership</th>
                                        <th>Your Share</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="propertiesList">
                                    <tr>
                                        <td colspan="7" class="text-center py-8 text-muted">
                                            <div class="mt-2" style="font-weight: 500;">No assets added yet</div>
                                        </strong>
                                    </tr>
                                </tbody>
                                <tfoot id="propertiesTotal" style="display: none;">
                                    <tr style="background: var(--primary-light); font-weight: 600;">
                                        <td colspan="5" style="text-align: right;">Total Assets:</strong>
                                        <td id="propertiesTotalValue" colspan="2">RM 0.00</strong>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="btn-group-modern">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="deceased">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </button>
                    <button type="button" class="btn btn-primary next-section" data-next="heirs" id="nextToHeirs">
                        Continue to Heirs
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- SECTION 3: HEIRS -->
        <div class="glass-card" id="heirsSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                </svg>
                <h2 class="card-title">All Heirs Information</h2>
                <span class="card-badge" id="totalHeirsCountBadge">0 Total Heirs</span>
            </div>
            
            <div class="card-body">
                <!-- Primary Heirs -->
                <div class="category-section">
                    <div class="category-header">
                        <h3 class="category-title">Primary Heirs (Fixed Shares)</h3>
                        <span class="category-badge">Ashabul furud</span>
                    </div>
                    
                    <div class="heir-grid">
                        <!-- Spouse -->
                        <div class="heir-card">
                            <label class="form-label">Husband</label>
                            <div class="counter-hint">Only if deceased is female</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="husbandCount" disabled>-</button>
                                <input type="number" id="husbandCount" name="husband_count" class="counter-input" 
                                       value="{{ old('husband_count', 0) }}" min="0" max="1" readonly>
                                <button type="button" class="counter-btn increment" data-target="husbandCount">+</button>
                            </div>
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Wife</label>
                            <div class="counter-hint">Maximum: 4 wives</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="wifeCount" disabled>-</button>
                                <input type="number" id="wifeCount" name="wife_count" class="counter-input" 
                                       value="{{ old('wife_count', 0) }}" min="0" max="4" readonly>
                                <button type="button" class="counter-btn increment" data-target="wifeCount">+</button>
                            </div>
                        </div>
                        
                        <!-- Parents -->
                        <div class="heir-card">
                            <label class="form-label">Father</label>
                            <div class="counter-hint">Status</div>
                            <div class="status-select">
                                <button type="button" class="status-btn" data-target="fatherStatus" data-value="alive">Alive</button>
                                <button type="button" class="status-btn active" data-target="fatherStatus" data-value="deceased">Deceased</button>
                            </div>
                            <input type="hidden" id="fatherStatus" name="father_status" value="deceased">
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Mother</label>
                            <div class="counter-hint">Status</div>
                            <div class="status-select">
                                <button type="button" class="status-btn" data-target="motherStatus" data-value="alive">Alive</button>
                                <button type="button" class="status-btn active" data-target="motherStatus" data-value="deceased">Deceased</button>
                            </div>
                            <input type="hidden" id="motherStatus" name="mother_status" value="deceased">
                        </div>
                    </div>
                </div>
                
                <!-- Children -->
                <div class="category-section">
                    <div class="category-header">
                        <h3 class="category-title">Children</h3>
                        <span class="category-badge">Biological children</span>
                    </div>
                    
                    <div class="heir-grid">
                        <div class="heir-card">
                            <label class="form-label">Son</label>
                            <div class="counter-hint">Biological sons</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="sonCount" disabled>-</button>
                                <input type="number" id="sonCount" name="son_count" class="counter-input" 
                                       value="{{ old('son_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="sonCount">+</button>
                            </div>
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Daughter</label>
                            <div class="counter-hint">Biological daughters</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="daughterCount" disabled>-</button>
                                <input type="number" id="daughterCount" name="daughter_count" class="counter-input" 
                                       value="{{ old('daughter_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="daughterCount">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grandparents -->
                <div class="category-section">
                    <div class="category-header">
                        <h3 class="category-title">Grandparents (Substitute Heirs)</h3>
                        <span class="category-badge">If parents deceased</span>
                    </div>
                    
                    <div class="heir-grid">
                        <div class="heir-card">
                            <label class="form-label">Father's Father</label>
                            <div class="counter-hint">Paternal grandfather</div>
                            <div class="status-select">
                                <button type="button" class="status-btn" data-target="fathersFatherStatus" data-value="alive">Alive</button>
                                <button type="button" class="status-btn active" data-target="fathersFatherStatus" data-value="deceased">Deceased</button>
                            </div>
                            <input type="hidden" id="fathersFatherStatus" name="fathers_father_status" value="deceased">
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Father's Mother</label>
                            <div class="counter-hint">Paternal grandmother</div>
                            <div class="status-select">
                                <button type="button" class="status-btn" data-target="fathersMotherStatus" data-value="alive">Alive</button>
                                <button type="button" class="status-btn active" data-target="fathersMotherStatus" data-value="deceased">Deceased</button>
                            </div>
                            <input type="hidden" id="fathersMotherStatus" name="fathers_mother_status" value="deceased">
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Mother's Mother</label>
                            <div class="counter-hint">Maternal grandmother</div>
                            <div class="status-select">
                                <button type="button" class="status-btn" data-target="mothersMotherStatus" data-value="alive">Alive</button>
                                <button type="button" class="status-btn active" data-target="mothersMotherStatus" data-value="deceased">Deceased</button>
                            </div>
                            <input type="hidden" id="mothersMotherStatus" name="mothers_mother_status" value="deceased">
                        </div>
                    </div>
                </div>
                
                <!-- Full Siblings -->
                <div class="category-section">
                    <div class="category-header">
                        <h3 class="category-title">Full Siblings</h3>
                        <span class="category-badge">Same father & mother</span>
                    </div>
                    
                    <div class="heir-grid">
                        <div class="heir-card">
                            <label class="form-label">Full Brother</label>
                            <div class="counter-hint">Same father & mother</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="fullBrotherCount" disabled>-</button>
                                <input type="number" id="fullBrotherCount" name="full_brother_count" class="counter-input" 
                                       value="{{ old('full_brother_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="fullBrotherCount">+</button>
                            </div>
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Full Sister</label>
                            <div class="counter-hint">Same father & mother</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="fullSisterCount" disabled>-</button>
                                <input type="number" id="fullSisterCount" name="full_sister_count" class="counter-input" 
                                       value="{{ old('full_sister_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="fullSisterCount">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Paternal Siblings -->
                <div class="category-section">
                    <div class="category-header">
                        <h3 class="category-title">Paternal Half-Siblings</h3>
                        <span class="category-badge">Same father only</span>
                    </div>
                    
                    <div class="heir-grid">
                        <div class="heir-card">
                            <label class="form-label">Paternal Half-Brother</label>
                            <div class="counter-hint">Same father only</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="paternalHalfBrotherCount" disabled>-</button>
                                <input type="number" id="paternalHalfBrotherCount" name="paternal_half_brother_count" class="counter-input" 
                                       value="{{ old('paternal_half_brother_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="paternalHalfBrotherCount">+</button>
                            </div>
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Paternal Half-Sister</label>
                            <div class="counter-hint">Same father only</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="paternalHalfSisterCount" disabled>-</button>
                                <input type="number" id="paternalHalfSisterCount" name="paternal_half_sister_count" class="counter-input" 
                                       value="{{ old('paternal_half_sister_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="paternalHalfSisterCount">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Maternal Siblings -->
                <div class="category-section">
                    <div class="category-header">
                        <h3 class="category-title">Maternal Half-Siblings</h3>
                        <span class="category-badge">Same mother only</span>
                    </div>
                    
                    <div class="heir-grid">
                        <div class="heir-card">
                            <label class="form-label">Maternal Half-Brother</label>
                            <div class="counter-hint">Same mother only</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="maternalHalfBrotherCount" disabled>-</button>
                                <input type="number" id="maternalHalfBrotherCount" name="maternal_half_brother_count" class="counter-input" 
                                       value="{{ old('maternal_half_brother_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="maternalHalfBrotherCount">+</button>
                            </div>
                        </div>
                        
                        <div class="heir-card">
                            <label class="form-label">Maternal Half-Sister</label>
                            <div class="counter-hint">Same mother only</div>
                            <div class="heir-counter">
                                <button type="button" class="counter-btn decrement" data-target="maternalHalfSisterCount" disabled>-</button>
                                <input type="number" id="maternalHalfSisterCount" name="maternal_half_sister_count" class="counter-input" 
                                       value="{{ old('maternal_half_sister_count', 0) }}" min="0" readonly>
                                <button type="button" class="counter-btn increment" data-target="maternalHalfSisterCount">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="btn-group-modern">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="assets">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </button>
                    <button type="button" class="btn btn-primary" id="calculateResults">
                        Calculate Inheritance
                    </button>
                </div>
            </div>
        </div>
        
        <!-- SECTION 4: RESULTS -->
        <div class="glass-card" id="resultsSection">
            <div class="card-header">
                <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h2 class="card-title">Inheritance Distribution Results</h2>
                <span class="card-badge">Calculated</span>
            </div>
            
            <div class="card-body">
                <!-- Loading State -->
                <div id="loadingResults" class="text-center" style="display: none;">
                    <div class="loading-spinner" style="width: 40px; height: 40px; margin: 0 auto 2rem;"></div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Calculating Inheritance...</h3>
                    <p style="color: var(--gray-600);">Processing Faraid rules and distributing assets according to Shariah law</p>
                </div>
                
                <!-- Results Content -->
                <div id="resultsContent" style="display: none;">
                    <!-- Results Summary -->
                    <div class="summary-grid">
                        <div class="summary-card">
                            <div class="summary-title">Total Heirs</div>
                            <div class="summary-value" id="resultTotalHeirs">0</div>
                            <div class="summary-subtitle">Eligible for inheritance</div>
                        </div>
                        
                        <div class="summary-card">
                            <div class="summary-title">Total Estate</div>
                            <div class="summary-value" id="resultNetEstate">RM 0.00</div>
                            <div class="summary-subtitle">Available for distribution</div>
                        </div>
                        
                        <div class="summary-card">
                            <div class="summary-title">Scenario</div>
                            <div class="summary-value" id="resultScenario">0</div>
                            <div class="summary-subtitle">Faraid Rule Applied</div>
                        </div>
                    </div>
                    
                    <!-- Scenario Information -->
                    <div class="category-section">
                        <div class="category-header">
                            <h3 class="category-title">Detected Scenario</h3>
                            <span class="category-badge" id="scenarioBadge">Scenario 0</span>
                        </div>
                        <div id="scenarioDescription" style="margin-top: 1rem; padding: 1.5rem; background: rgba(26, 95, 180, 0.05); border-radius: var(--border-radius-md);">
                            <p id="scenarioText" style="margin: 0; color: var(--gray-700); line-height: 1.6;">
                                Complete all sections and calculate to see scenario details
                            </p>
                        </div>
                    </div>
                    
                    <!-- Detailed Results -->
                    <div class="category-section">
                        <div class="category-header">
                            <h3 class="category-title">Distribution Breakdown</h3>
                            <span class="category-badge">Detailed View</span>
                        </div>
                        
                        <div class="results-container">
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th>Heir</th>
                                        <th>Relationship</th>
                                        <th>Fraction</th>
                                        <th>Amount</th>
                                        <th>Percentage</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="distributionResults">
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-muted">
                                            Complete all sections and calculate results
                                         </strong>
                                    </tr>
                                </tbody>
                                <tfoot id="distributionTotal" style="display: none;">
                                    <tr class="total-row">
                                        <td colspan="3" style="text-align: right; color: white !important;">Total Distributed:</strong>
                                        <td id="totalDistributedValue" style="color: white !important;">RM 0.00</strong>
                                        <td id="totalPercentageValue" style="color: white !important;">100%</strong>
                                        <td style="color: white !important;">&nbsp;</strong>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Family Tree Visualization -->
                    <div class="family-tree-section" id="familyTreeSection">
                        <div class="tree-controls">
                            <div class="tree-title">
                                <h3>Family Tree with Inheritance Distribution</h3>
                                <p>Complete family tree showing ALL heirs and their inheritance amounts</p>
                            </div>
                            <div class="tree-actions">
                            </div>
                        </div>
                        
                        <div class="tree-container-wrapper" id="treeContainerWrapper">
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
                                    <h3>Interactive Family Tree</h3>
                                    <p>Calculate inheritance to see the complete family tree with ALL heirs and their distribution amounts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Visualization -->
                    <div class="category-section">
                        <div class="category-header">
                            <h3 class="category-title">Inheritance Distribution Chart</h3>
                            <span class="category-badge">Interactive Chart</span>
                        </div>
                        
                        <div class="chart-section">
                            <div class="chart-section-title">
                                <h3>Distribution Visualization</h3>
                                <p>Showing only eligible recipients with positive shares</p>
                            </div>
                            
                            <div class="chart-container">
                                <div class="pie-chart-container">
                                    <canvas id="distributionPieChart"></canvas>
                                    <div class="chart-center">
                                        <div class="chart-center-text">Eligible</div>
                                        <div class="chart-center-value" id="eligibleCount">0</div>
                                        <div class="chart-center-text" style="font-size: 0.75rem; margin-top: 0.5rem;">Recipients</div>
                                    </div>
                                </div>
                                
                                <div class="chart-legend" id="chartLegend">
                                    <div class="no-eligible-recipients">
                                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <h3>Chart will appear after calculation</h3>
                                        <p>Calculate inheritance to see the visual distribution</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Export Controls -->
                    <div class="export-controls">
                        <div class="export-title">Export Reports &amp; Images</div>
                        <div class="export-buttons">
                            <button type="button" class="export-btn full-report" id="exportFullReport">
                                <svg class="export-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export Report
                            </button>
                            <button type="button" class="export-btn tree-pdf" id="exportTreePDF">
                                <svg class="export-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Export Family Tree
                            </button>
                        </div>
                    </div>
                    
                    <!-- Navigation & Action Buttons -->
                    <div class="btn-group-modern">
                        <button type="button" class="btn btn-secondary prev-section" data-prev="heirs">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Edit
                        </button>
                        
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <button type="button" class="btn btn-primary" id="saveCalculation">
                                Save Calculation
                            </button>
                            <button type="button" class="btn btn-secondary" id="resetCalculator">
                                New Calculation
                            </button>
                            <a href="{{ route('calculator.index') }}" class="btn btn-ghost">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hidden Fields -->
        <input type="hidden" id="heirsData" name="heirs_data" value="{{ old('heirs_data') }}">
        <input type="hidden" id="assetsData" name="assets_data" value="{{ old('assets_data') }}">
        <input type="hidden" id="calculationData" name="calculation_data" value="{{ old('calculation_data') }}">
        <input type="hidden" id="scenarioData" name="scenario_data" value="{{ old('scenario_data') }}">
        <input type="hidden" id="chartData" name="chart_data" value="{{ old('chart_data') }}">
        <input type="hidden" id="treeData" name="tree_data" value="{{ old('tree_data') }}">
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== GLOBAL VARIABLES =====
    let properties = [];
    let calculationResults = null;
    let detectedScenario = 0;
    let pieChart = null;
    let currentGraphvizLayout = 'dot';
    let graphvizInstance = null;
    let svgViewBox = null;
    let currentScale = 1;
    let familyTreeGenerated = false;
    
    // Auth check - available for saving only
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    
    function formatAmountDecimal(amount) {
        if (amount === undefined || amount === null || isNaN(amount)) return '0.00';
        return parseFloat(amount).toFixed(2);
    }
    
    // ===== LOGIN PROMPT FUNCTIONS =====
    const loginPrompt = document.getElementById('loginPromptOverlay');
    const cancelLoginBtn = document.getElementById('cancelLoginBtn');
    
    function showLoginPrompt() {
        if (!isLoggedIn && loginPrompt) {
            loginPrompt.classList.add('active');
        }
    }
    
    function hideLoginPrompt() {
        if (loginPrompt) {
            loginPrompt.classList.remove('active');
        }
    }
    
    if (cancelLoginBtn) {
        cancelLoginBtn.addEventListener('click', hideLoginPrompt);
    }
    
    // Override the form submission to check login for saving
    const saveButton = document.getElementById('saveCalculation');
    const faraidForm = document.getElementById('faraidForm');
    
    if (saveButton && faraidForm) {
        saveButton.addEventListener('click', function(e) {
            if (!calculationResults || calculationResults.length === 0) {
                ModernAlert.error('Please calculate the distribution before saving.', 'Calculation Required');
                e.preventDefault();
                return;
            }
            
            if (!isLoggedIn) {
                e.preventDefault();
                showLoginPrompt();
            } else {
                // Submit the form
                faraidForm.submit();
            }
        });
    }
    
    // ===== PHONE NUMBER VALIDATION & AUTO-FORMATTING =====
    const phoneInput = document.getElementById('contactPhone');
    
    function formatPhoneNumber(value) {
        let cleaned = value.replace(/\D/g, '');
        if (cleaned.length >= 2 && cleaned.substring(0, 2) === '01') {
            if (cleaned.length >= 3) {
                const prefix = cleaned.substring(0, 3);
                let remaining = cleaned.substring(3);
                if (remaining.length > 8) remaining = remaining.substring(0, 8);
                return `${prefix}-${remaining}`;
            }
        }
        if (cleaned.length >= 4) {
            return `${cleaned.substring(0, 3)}-${cleaned.substring(3)}`;
        }
        return cleaned;
    }
    
    function validatePhoneNumber(phone) {
        const cleanPhone = phone.replace(/-/g, '');
        const phoneRegex = /^(01)[0-9]{8,9}$/;
        if (!phoneRegex.test(cleanPhone)) {
            ModernAlert.error('Please enter a valid mobile number', 'Invalid Phone Number');
            return false;
        }
        return true;
    }
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let cursorPosition = this.selectionStart;
            let originalValue = this.value;
            let formattedValue = formatPhoneNumber(originalValue);
            if (originalValue !== formattedValue) {
                this.value = formattedValue;
                let newCursorPos = cursorPosition;
                if (formattedValue.length > originalValue.length) newCursorPos = cursorPosition + 1;
                else if (formattedValue.length < originalValue.length) newCursorPos = Math.max(0, cursorPosition - 1);
                this.setSelectionRange(newCursorPos, newCursorPos);
            }
        });
        phoneInput.addEventListener('blur', function() {
            const value = this.value;
            if (value && !validatePhoneNumber(value)) {
                this.value = '';
                this.focus();
            }
        });
    }
    
    // ===== ENHANCED SECTION VALIDATION =====
    function validateDeceasedSection() {
        const name = document.getElementById('deceasedName').value.trim();
        const gender = document.getElementById('deceasedGender').value;
        const date = document.getElementById('dateOfDeath').value;
        const maritalStatus = document.getElementById('maritalStatus').value;
        const causeOfDeath = document.getElementById('causeOfDeath').value.trim();
        const deathPlace = document.getElementById('deathPlace').value.trim();
        const contactEmail = document.getElementById('contactEmail').value.trim();
        const contactPhone = document.getElementById('contactPhone').value.trim();
        const residentialAddress = document.getElementById('residentialAddress').value.trim();

        if (!name) { ModernAlert.error('Please enter the deceased name.', 'Required Field'); document.getElementById('deceasedName').focus(); return false; }
        if (!gender) { ModernAlert.error('Please select the deceased gender.', 'Required Field'); document.getElementById('deceasedGender').focus(); return false; }
        if (!date) { ModernAlert.error('Please select the date of death.', 'Required Field'); document.getElementById('dateOfDeath').focus(); return false; }
        if (!maritalStatus) { ModernAlert.error('Please select the marital status.', 'Required Field'); document.getElementById('maritalStatus').focus(); return false; }
        if (!causeOfDeath) { ModernAlert.error('Please enter the cause of death.', 'Required Field'); document.getElementById('causeOfDeath').focus(); return false; }
        if (!deathPlace) { ModernAlert.error('Please enter the place of death.', 'Required Field'); document.getElementById('deathPlace').focus(); return false; }
        if (!contactEmail) { ModernAlert.error('Please enter contact email.', 'Required Field'); document.getElementById('contactEmail').focus(); return false; }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (contactEmail && !emailRegex.test(contactEmail)) { ModernAlert.error('Please enter a valid email address.', 'Invalid Email'); document.getElementById('contactEmail').focus(); return false; }
        
        if (!contactPhone) { ModernAlert.error('Please enter contact phone number.', 'Required Field'); document.getElementById('contactPhone').focus(); return false; }
        
        const cleanPhone = contactPhone.replace(/-/g, '');
        const phoneRegex = /^(01)[0-9]{8,9}$/;
        if (!phoneRegex.test(cleanPhone)) { ModernAlert.error('Please enter a valid mobile number', 'Invalid Phone Number'); document.getElementById('contactPhone').focus(); return false; }
        
        if (!residentialAddress) { ModernAlert.error('Please enter residential address.', 'Required Field'); document.getElementById('residentialAddress').focus(); return false; }
        
        const selectedDate = new Date(date);
        const today = new Date();
        if (selectedDate > today) { ModernAlert.warning('Date of death cannot be in the future.', 'Invalid Date'); document.getElementById('dateOfDeath').focus(); return false; }
        
        return true;
    }
    
    function validateAssetsSection() {
        const totalAssets = properties.reduce((sum, prop) => sum + (prop.value * (prop.ownership / 100)), 0);
        if (totalAssets <= 0) { ModernAlert.warning('Please add at least one asset with positive value before proceeding.', 'No Assets'); return false; }
        return true;
    }
    
    function validateHeirsSection() {
        const wifeCount = parseInt(document.getElementById('wifeCount').value) || 0;
        const husbandCount = parseInt(document.getElementById('husbandCount').value) || 0;
        
        if (wifeCount > 0 && husbandCount > 0) { ModernAlert.error('Cannot have both wife and husband. Please adjust spouse information.', 'Invalid Configuration'); return false; }
        
        const deceasedGender = document.getElementById('deceasedGender').value;
        if (deceasedGender === 'female' && wifeCount > 0) { ModernAlert.error('Female deceased cannot have wife. Please adjust spouse information.', 'Invalid Configuration'); return false; }
        if (deceasedGender === 'male' && husbandCount > 0) { ModernAlert.error('Male deceased cannot have husband. Please adjust spouse information.', 'Invalid Configuration'); return false; }
        
        const hasHeirs = wifeCount > 0 || husbandCount > 0 ||
                        document.getElementById('fatherStatus').value === 'alive' ||
                        document.getElementById('motherStatus').value === 'alive' ||
                        parseInt(document.getElementById('sonCount').value) > 0 ||
                        parseInt(document.getElementById('daughterCount').value) > 0 ||
                        parseInt(document.getElementById('fullBrotherCount').value) > 0 ||
                        parseInt(document.getElementById('fullSisterCount').value) > 0 ||
                        parseInt(document.getElementById('paternalHalfBrotherCount').value) > 0 ||
                        parseInt(document.getElementById('paternalHalfSisterCount').value) > 0 ||
                        parseInt(document.getElementById('maternalHalfBrotherCount').value) > 0 ||
                        parseInt(document.getElementById('maternalHalfSisterCount').value) > 0;
        
        if (!hasHeirs) { ModernAlert.warning('Please add at least one heir to proceed.', 'No Heirs'); return false; }
        
        return true;
    }
    
    // ===== ASSET CLASSIFICATION MAPPING =====
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
    
    // ===== URL PARAMETERS =====
    function getUrlParams() {
        const params = new URLSearchParams(window.location.search);
        return {
            source: params.get('source'),
            session_id: params.get('session_id'),
            deceased_name: params.get('deceased_name'),
            deceased_nric: params.get('deceased_nric'),
            deceased_gender: params.get('deceased_gender'),
            cause_of_death: params.get('cause_of_death'),
            marital_status: params.get('marital_status'),
            date_of_death: params.get('date_of_death'),
            death_place: params.get('death_place'),
            contact_email: params.get('contact_email'),
            contact_phone: params.get('contact_phone'),
            residential_address: params.get('residential_address'),
            wife_count: params.get('wife_count'),
            husband_count: params.get('husband_count'),
            father_status: params.get('father_status'),
            mother_status: params.get('mother_status'),
            son_count: params.get('son_count'),
            daughter_count: params.get('daughter_count')
        };
    }
    
    function prefillFromUrlParams() {
        const params = getUrlParams();
        
        if (params.source === 'instant_estate') {
            console.log('Prefilling from Instant Estate...');
            
            if (params.deceased_name) {
                const nameInput = document.getElementById('deceasedName');
                if (nameInput) {
                    nameInput.value = params.deceased_name;
                    nameInput.dispatchEvent(new Event('input'));
                }
            }
            
            if (params.deceased_nric) {
                const nricInput = document.getElementById('deceasedNric');
                if (nricInput) nricInput.value = params.deceased_nric;
            }
            
            if (params.deceased_gender) {
                const genderSelect = document.getElementById('deceasedGender');
                if (genderSelect) {
                    genderSelect.value = params.deceased_gender;
                    genderSelect.dispatchEvent(new Event('change'));
                }
            }
            
            if (params.marital_status) {
                const maritalSelect = document.getElementById('maritalStatus');
                if (maritalSelect) {
                    maritalSelect.value = params.marital_status;
                    maritalSelect.dispatchEvent(new Event('change'));
                }
            } else {
                const maritalStatusEl = document.getElementById('maritalStatus');
                if (maritalStatusEl) maritalStatusEl.value = 'married';
            }
            
            if (params.date_of_death) {
                const dateInput = document.getElementById('dateOfDeath');
                if (dateInput) {
                    dateInput.value = params.date_of_death;
                    dateInput.dispatchEvent(new Event('change'));
                }
            }
            
            if (params.cause_of_death) {
                const causeInput = document.getElementById('causeOfDeath');
                if (causeInput) causeInput.value = params.cause_of_death;
            }
            
            if (params.death_place) {
                const deathPlaceInput = document.getElementById('deathPlace');
                if (deathPlaceInput) deathPlaceInput.value = params.death_place;
            }
            
            if (params.contact_email) {
                const emailInput = document.getElementById('contactEmail');
                if (emailInput) emailInput.value = params.contact_email;
            }
            
            if (params.contact_phone) {
                const phoneInput = document.getElementById('contactPhone');
                if (phoneInput) phoneInput.value = params.contact_phone;
            }
            
            if (params.residential_address) {
                const addressInput = document.getElementById('residentialAddress');
                if (addressInput) addressInput.value = params.residential_address;
            }
            
            if (params.wife_count) {
                document.getElementById('wifeCount').value = params.wife_count;
                updateCounterButtonState('wifeCount');
            }
            
            if (params.husband_count) {
                document.getElementById('husbandCount').value = params.husband_count;
                updateCounterButtonState('husbandCount');
            }
            
            if (params.father_status === 'alive') {
                const fatherStatusBtn = document.querySelector('.status-btn[data-target="fatherStatus"][data-value="alive"]');
                if (fatherStatusBtn) fatherStatusBtn.click();
            }
            
            if (params.mother_status === 'alive') {
                const motherStatusBtn = document.querySelector('.status-btn[data-target="motherStatus"][data-value="alive"]');
                if (motherStatusBtn) motherStatusBtn.click();
            }
            
            updateTotalHeirsCount();
            
            ModernAlert.success('Data loaded from death certificate! Please review and continue.', 'Data Loaded');
        }
    }
    
    // ===== MODERN ALERT SYSTEM =====
    class ModernAlert {
        static show({ type = 'info', title, message, duration = 4000 }) {
            const container = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            
            const icons = {
                success: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                warning: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`,
                error: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
                info: `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
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
        
        static success(message, title = 'Success') { return this.show({ type: 'success', title, message }); }
        static warning(message, title = 'Warning') { return this.show({ type: 'warning', title, message }); }
        static error(message, title = 'Error') { return this.show({ type: 'error', title, message }); }
        static info(message, title = 'Info') { return this.show({ type: 'info', title, message }); }
    }
    
    window.ModernAlert = ModernAlert;
    
    // ===== SECTION NAVIGATION WITH VALIDATION =====
    const tabs = document.querySelectorAll('.modern-tab');
    const sections = document.querySelectorAll('.glass-card');
    const progressSteps = document.querySelectorAll('.progress-step');
    const progressFill = document.getElementById('progressFill');
    const stepOrder = ['deceased', 'assets', 'heirs', 'results'];
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetSection = this.dataset.section;
            const currentActiveTab = document.querySelector('.modern-tab.active');
            const currentSection = currentActiveTab ? currentActiveTab.dataset.section : 'deceased';
            
            if (targetSection === 'assets' && currentSection === 'deceased') {
                if (!validateDeceasedSection()) return;
            }
            if (targetSection === 'heirs' && currentSection !== 'heirs') {
                if (currentSection === 'deceased') {
                    if (!validateDeceasedSection()) return;
                }
                if (currentSection === 'assets') {
                    if (!validateAssetsSection()) return;
                }
            }
            if (targetSection === 'results' && currentSection !== 'results') {
                if (!validateDeceasedSection()) return;
                if (!validateAssetsSection()) return;
                if (!validateHeirsSection()) return;
                calculateFaraid();
            }
            
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            sections.forEach(section => section.classList.remove('active-section'));
            document.getElementById(targetSection + 'Section').classList.add('active-section');
            updateProgress(targetSection + 'Section');
            if (targetSection === 'heirs') updateTotalHeirsCount();
            if (targetSection === 'assets') updateAssetsSummary();
        });
    });
    
    document.querySelectorAll('.next-section').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const nextSection = this.dataset.next;
            
            if (nextSection === 'assets') {
                if (!validateDeceasedSection()) return;
            }
            if (nextSection === 'heirs') {
                if (!validateDeceasedSection()) return;
                if (!validateAssetsSection()) return;
            }
            
            const nextTab = document.querySelector(`[data-section="${nextSection}"]`);
            if (nextTab) nextTab.click();
        });
    });
    
    document.querySelectorAll('.prev-section').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const prevTab = document.querySelector(`[data-section="${this.dataset.prev}"]`);
            if (prevTab) prevTab.click();
        });
    });
    
    function updateProgress(sectionId) {
        let stepIndex = stepOrder.findIndex(step => step + 'Section' === sectionId);
        if (stepIndex === -1) stepIndex = 0;
        progressFill.style.width = ((stepIndex + 1) / stepOrder.length) * 100 + '%';
        
        progressSteps.forEach((step, idx) => {
            step.classList.remove('active', 'completed');
            if (idx === stepIndex) step.classList.add('active');
            else if (idx < stepIndex) step.classList.add('completed');
        });
    }
    
    // ===== STATUS BUTTONS =====
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const value = this.dataset.value;
            const container = this.parentElement;
            container.querySelectorAll('.status-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const hiddenInput = document.getElementById(targetId);
            if (hiddenInput) hiddenInput.value = value;
            updateTotalHeirsCount();
        });
    });
    
    // ===== COUNTER INPUTS =====
    document.querySelectorAll('.counter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            if (!input) return;
            let value = parseInt(input.value) || 0;
            if (this.classList.contains('increment')) {
                const max = parseInt(input.getAttribute('max')) || Infinity;
                if (value < max) value++;
                else { ModernAlert.warning(`Maximum ${max} reached`, 'Limit'); return; }
            } else if (this.classList.contains('decrement')) {
                const min = parseInt(input.getAttribute('min')) || 0;
                if (value > min) value--;
            }
            input.value = value;
            updateCounterButtonState(targetId);
            updateTotalHeirsCount();
            handleSpouseGenderConflict();
        });
    });
    
    function updateCounterButtonState(targetId) {
        const input = document.getElementById(targetId);
        if (!input) return;
        const value = parseInt(input.value) || 0;
        const min = parseInt(input.getAttribute('min')) || 0;
        const max = parseInt(input.getAttribute('max')) || Infinity;
        const parent = input.parentElement;
        if (!parent) return;
        const decrementBtn = parent.querySelector('.decrement');
        const incrementBtn = parent.querySelector('.increment');
        if (decrementBtn) decrementBtn.disabled = value <= min;
        if (incrementBtn) incrementBtn.disabled = value >= max;
    }
    
    function handleSpouseGenderConflict() {
        const deceasedGender = document.getElementById('deceasedGender').value;
        const wifeCount = parseInt(document.getElementById('wifeCount').value) || 0;
        const husbandCount = parseInt(document.getElementById('husbandCount').value) || 0;
        
        if (deceasedGender === 'female' && wifeCount > 0) {
            ModernAlert.warning('Female deceased cannot have wife. Wife count reset to 0.', 'Gender Conflict');
            document.getElementById('wifeCount').value = 0; updateCounterButtonState('wifeCount'); updateTotalHeirsCount();
        }
        if (deceasedGender === 'male' && husbandCount > 0) {
            ModernAlert.warning('Male deceased cannot have husband. Husband count reset to 0.', 'Gender Conflict');
            document.getElementById('husbandCount').value = 0; updateCounterButtonState('husbandCount'); updateTotalHeirsCount();
        }
        if (wifeCount > 0 && husbandCount > 0) {
            ModernAlert.warning('Cannot have both wife and husband. One spouse type will be reset.', 'Spouse Conflict');
            if (deceasedGender === 'male') { document.getElementById('husbandCount').value = 0; updateCounterButtonState('husbandCount'); }
            else if (deceasedGender === 'female') { document.getElementById('wifeCount').value = 0; updateCounterButtonState('wifeCount'); }
            updateTotalHeirsCount();
        }
    }
    
    function updateTotalHeirsCount() {
        let totalHeirs = 0;
        totalHeirs += parseInt(document.getElementById('wifeCount').value) || 0;
        totalHeirs += parseInt(document.getElementById('husbandCount').value) || 0;
        if (document.getElementById('fatherStatus')?.value === 'alive') totalHeirs++;
        if (document.getElementById('motherStatus')?.value === 'alive') totalHeirs++;
        totalHeirs += parseInt(document.getElementById('sonCount').value) || 0;
        totalHeirs += parseInt(document.getElementById('daughterCount').value) || 0;
        if (document.getElementById('fathersFatherStatus')?.value === 'alive') totalHeirs++;
        if (document.getElementById('fathersMotherStatus')?.value === 'alive') totalHeirs++;
        if (document.getElementById('mothersMotherStatus')?.value === 'alive') totalHeirs++;
        totalHeirs += parseInt(document.getElementById('fullBrotherCount').value) || 0;
        totalHeirs += parseInt(document.getElementById('fullSisterCount').value) || 0;
        totalHeirs += parseInt(document.getElementById('paternalHalfBrotherCount').value) || 0;
        totalHeirs += parseInt(document.getElementById('paternalHalfSisterCount').value) || 0;
        totalHeirs += parseInt(document.getElementById('maternalHalfBrotherCount').value) || 0;
        totalHeirs += parseInt(document.getElementById('maternalHalfSisterCount').value) || 0;
        
        const badge = document.getElementById('totalHeirsCountBadge');
        if (badge) badge.textContent = totalHeirs + (totalHeirs === 1 ? ' Total Heir' : ' Total Heirs');
        return totalHeirs;
    }
    
    // ===== ASSET TYPE DROPDOWN CHANGE =====
    document.getElementById('propertyType')?.addEventListener('change', function() {
        const cls = assetClassification[this.value];
        const preview = document.getElementById('assetCategoryPreview');
        if (preview && cls) { preview.className = `badge ${cls.badge}`; preview.textContent = cls.label; }
    });
    
    // ===== ASSETS MANAGEMENT =====
    document.getElementById('addPropertyBtn')?.addEventListener('click', function() {
        const type = document.getElementById('propertyType').value;
        const description = document.getElementById('propertyDescription').value.trim();
        const value = parseFloat(document.getElementById('propertyValue').value) || 0;
        const ownership = parseFloat(document.getElementById('propertyOwnership').value) || 100;
        
        if (!type) { ModernAlert.error('Please select a property type.', 'Required Field'); document.getElementById('propertyType').focus(); return; }
        if (value <= 0) { ModernAlert.error('Please enter a valid property value greater than 0.', 'Invalid Value'); document.getElementById('propertyValue').focus(); return; }
        if (ownership < 0 || ownership > 100) { ModernAlert.error('Ownership percentage must be between 0 and 100.', 'Invalid Value'); return; }
        
        const classification = assetClassification[type] || { category: 'Other Asset', badge: 'badge-real-estate', label: 'Other Asset' };
        
        const property = {
            id: Date.now(),
            type: type,
            description: description || '',
            value: value,
            ownership: ownership,
            category: classification.category,
            badge: classification.badge,
            label: classification.label
        };
        
        properties.push(property);
        renderPropertiesList();
        updateAssetsSummary();
        clearPropertyForm();
        ModernAlert.success('Asset added successfully!', 'Success');
    });
    
    document.getElementById('clearPropertiesBtn')?.addEventListener('click', function() {
        if (properties.length === 0) { ModernAlert.info('No assets to clear.', 'Info'); return; }
        if (confirm('Are you sure you want to clear all assets?')) {
            properties = [];
            renderPropertiesList();
            updateAssetsSummary();
            ModernAlert.info('All assets cleared.', 'Cleared');
        }
    });
    
    function renderPropertiesList() {
        const tbody = document.getElementById('propertiesList');
        const tfoot = document.getElementById('propertiesTotal');
        
        if (properties.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-muted"><div class="mt-2" style="font-weight: 500;">No assets added yet</div></td></tr>';
            tfoot.style.display = 'none';
            document.getElementById('totalAssetsBadge').textContent = '0 Assets';
            return;
        }
        
        tbody.innerHTML = '';
        let totalShare = 0;
        let totalGross = 0;
        
        properties.forEach((property, index) => {
            totalGross += property.value;
            const shareValue = property.value * (property.ownership / 100);
            totalShare += shareValue;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${escapeHtml(property.type)}</strong></td>
                <td><span class="badge ${property.badge}">${escapeHtml(property.label)}</span></td>
                <td>${escapeHtml(property.description) || '-'}</td>
                <td><strong>RM ${property.value.toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></td>
                <td>${property.ownership}%</td>
                <td><strong>RM ${shareValue.toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></td>
                <td><button type="button" class="btn btn-sm btn-danger delete-property" data-index="${index}">Remove</button></td>
            `;
            tbody.appendChild(row);
        });
        
        document.getElementById('propertiesTotalValue').textContent = `RM ${totalShare.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        tfoot.style.display = 'table-row-group';
        document.getElementById('totalAssetsBadge').textContent = properties.length + (properties.length === 1 ? ' Asset' : ' Assets');
        
        document.querySelectorAll('.delete-property').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                const property = properties[index];
                if (confirm(`Remove ${property.type} - ${property.description || 'No description'}?`)) {
                    properties.splice(index, 1);
                    renderPropertiesList();
                    updateAssetsSummary();
                    ModernAlert.info(`Removed: ${property.type}`, 'Asset Removed');
                }
            });
        });
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
    
    function clearPropertyForm() {
        document.getElementById('propertyType').value = '';
        document.getElementById('propertyValue').value = '';
        document.getElementById('propertyOwnership').value = '100';
        document.getElementById('propertyDescription').value = '';
        document.getElementById('assetCategoryPreview').className = 'badge badge-real-estate';
        document.getElementById('assetCategoryPreview').textContent = 'Real Estate';
        document.getElementById('propertyType').focus();
    }
    
    function updateAssetsSummary() {
        const totalGross = properties.reduce((sum, prop) => sum + (prop.value || 0), 0);
        const totalShare = properties.reduce((sum, prop) => sum + (prop.value * (prop.ownership / 100)), 0);
        
        document.getElementById('totalAssets').value = totalShare;
        
        const totalAssetsGrossDisplay = document.getElementById('totalAssetsGrossDisplay');
        const totalAssetsValue = document.getElementById('totalAssetsValue');
        const netAssets = document.getElementById('netAssets');
        
        if (totalAssetsGrossDisplay) totalAssetsGrossDisplay.textContent = `RM ${totalGross.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        if (totalAssetsValue) totalAssetsValue.textContent = `RM ${totalShare.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        if (netAssets) netAssets.textContent = `RM ${totalShare.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        
        return { totalAssets: totalShare, totalGross: totalGross, netAssets: totalShare };
    }
    
    updateAssetsSummary();
    
    // ===== FARAID CALCULATION FUNCTIONS =====
    document.getElementById('calculateResults').addEventListener('click', function(e) {
        e.preventDefault();
        if (!validateDeceasedSection()) { ModernAlert.error('Please complete the deceased section first.', 'Incomplete Form'); return; }
        if (!validateAssetsSection()) { ModernAlert.error('Please add assets to proceed.', 'Incomplete Form'); return; }
        if (!validateHeirsSection()) { ModernAlert.error('Please add at least one heir.', 'Incomplete Form'); return; }
        
        const resultsTab = document.querySelector('[data-section="results"]');
        if (resultsTab) resultsTab.click();
    });
    
    function addResult(results, heir, relationship, numerator, denominator, amount, status, scenario) {
        const formattedAmount = parseFloat(amount).toFixed(2);
        results.push({
            heir: heir,
            relationship: relationship,
            numerator: numerator,
            denominator: denominator,
            fractionDisplay: `${numerator}/${denominator}`,
            amount: formattedAmount,
            percentage: parseFloat(((parseFloat(formattedAmount) / (results.netEstate || 1)) * 100).toFixed(2)),
            status: status,
            scenario: scenario
        });
    }
    
    function performFaraidCalculation(heirs, netEstate) {
        const results = [];
        results.netEstate = netEstate;
        
        const hasSpouse = (heirs.husband_count > 0) || (heirs.wife_count > 0);
        const hasFather = (heirs.father_status === 'alive');
        const hasMother = (heirs.mother_status === 'alive');
        const hasParents = hasFather || hasMother;
        const hasChildren = (heirs.son_count + heirs.daughter_count) > 0;
        const hasFullSiblings = (heirs.full_brother_count + heirs.full_sister_count) > 0;
        const hasPaternalHalfSiblings = (heirs.paternal_half_brother_count + heirs.paternal_half_sister_count) > 0;
        const hasMaternalHalfSiblings = (heirs.maternal_half_brother_count + heirs.maternal_half_sister_count) > 0;
        const hasSiblings = hasFullSiblings || hasPaternalHalfSiblings || hasMaternalHalfSiblings;
        
        // Spouse only
        if (hasSpouse && !hasParents && !hasChildren && !hasSiblings) {
            if (heirs.husband_count === 1 && heirs.wife_count === 0) {
                addResult(results, "Husband", "Husband", 1, 2, netEstate * 1/2, "Fixed Share", 1);
                if (netEstate * 1/2 > 0.01) addResult(results, "Baitulmal", "State Treasury", 1, 2, netEstate * 1/2, "Surplus", 1);
                return results;
            } else if (heirs.wife_count === 1 && heirs.husband_count === 0) {
                addResult(results, "Wife", "Wife", 1, 4, netEstate * 1/4, "Fixed Share", 1);
                if (netEstate * 3/4 > 0.01) addResult(results, "Baitulmal", "State Treasury", 3, 4, netEstate * 3/4, "Surplus", 1);
                return results;
            }
        }
        
        // Spouse and parents (no children)
        if (hasSpouse && hasParents && !hasChildren && !hasSiblings) {
            if (heirs.husband_count === 1 && hasFather && !hasMother) {
                addResult(results, "Husband", "Husband", 1, 2, netEstate * 1/2, "Fixed Share", 2);
                addResult(results, "Father", "Father", 1, 2, netEstate * 1/2, "Fixed Share", 2);
                return results;
            } else if (heirs.husband_count === 1 && hasMother && !hasFather) {
                addResult(results, "Husband", "Husband", 3, 6, netEstate * 3/6, "Fixed Share", 2);
                addResult(results, "Mother", "Mother", 2, 6, netEstate * 2/6, "Fixed Share", 2);
                if (netEstate * 1/6 > 0.01) addResult(results, "Baitulmal", "State Treasury", 1, 6, netEstate * 1/6, "Surplus", 2);
                return results;
            } else if (heirs.wife_count === 1 && hasFather && !hasMother) {
                addResult(results, "Wife", "Wife", 1, 4, netEstate * 1/4, "Fixed Share", 2);
                addResult(results, "Father", "Father", 3, 4, netEstate * 3/4, "Fixed Share", 2);
                return results;
            } else if (heirs.wife_count === 1 && hasMother && !hasFather) {
                addResult(results, "Wife", "Wife", 3, 12, netEstate * 3/12, "Fixed Share", 2);
                addResult(results, "Mother", "Mother", 4, 12, netEstate * 4/12, "Fixed Share", 2);
                if (netEstate * 5/12 > 0.01) addResult(results, "Baitulmal", "State Treasury", 5, 12, netEstate * 5/12, "Surplus", 2);
                return results;
            } else if (heirs.husband_count === 1 && hasFather && hasMother) {
                addResult(results, "Husband", "Husband", 3, 6, netEstate * 3/6, "Fixed Share", 2);
                addResult(results, "Mother", "Mother", 1, 6, netEstate * 1/6, "Fixed Share", 2);
                addResult(results, "Father", "Father", 2, 6, netEstate * 2/6, "Fixed Share", 2);
                return results;
            } else if (heirs.wife_count === 1 && hasFather && hasMother) {
                addResult(results, "Wife", "Wife", 1, 4, netEstate * 1/4, "Fixed Share", 2);
                addResult(results, "Mother", "Mother", 1, 4, netEstate * 1/4, "Fixed Share", 2);
                addResult(results, "Father", "Father", 2, 4, netEstate * 2/4, "Fixed Share", 2);
                return results;
            }
        }
        
        // Spouse and children
        if (hasSpouse && hasChildren && !hasParents) {
            if (heirs.wife_count === 2 && heirs.husband_count === 0 && heirs.son_count === 0 && heirs.daughter_count === 2 &&
                !hasFather && !hasMother && !hasSiblings) {
                const eachWifeAmount = netEstate * 3/48;
                const eachDaughterAmount = netEstate * 16/48;
                const baitulmalAmount = netEstate * 10/48;
                for (let i = 1; i <= 2; i++) {
                    addResult(results, `Wife ${i}`, "Wife", 3, 48, eachWifeAmount, "Fixed Share", 13);
                }
                for (let i = 1; i <= 2; i++) {
                    addResult(results, `Daughter ${i}`, "Daughter", 16, 48, eachDaughterAmount, "Fixed Share", 13);
                }
                if (baitulmalAmount > 0.01) {
                    addResult(results, "Baitulmal", "State Treasury", 10, 48, baitulmalAmount, "Surplus", 13);
                }
                return results;
            }
            
            if (heirs.wife_count === 1 && heirs.son_count === 1 && heirs.daughter_count === 0) {
                addResult(results, "Wife", "Wife", 1, 8, netEstate * 1/8, "Fixed Share", 3);
                addResult(results, "Son", "Son", 7, 8, netEstate * 7/8, "Asabah", 3);
                return results;
            } else if (heirs.wife_count === 1 && heirs.daughter_count === 1 && heirs.son_count === 0) {
                addResult(results, "Wife", "Wife", 1, 8, netEstate * 1/8, "Fixed Share", 3);
                addResult(results, "Daughter", "Daughter", 4, 8, netEstate * 4/8, "Fixed Share", 3);
                if (netEstate * 3/8 > 0.01) addResult(results, "Baitulmal", "State Treasury", 3, 8, netEstate * 3/8, "Surplus", 3);
                return results;
            } else if (heirs.wife_count === 1 && heirs.daughter_count === 2 && heirs.son_count === 0) {
                addResult(results, "Wife", "Wife", 3, 24, netEstate * 3/24, "Fixed Share", 3);
                for (let i = 1; i <= 2; i++) addResult(results, `Daughter ${i}`, "Daughter", 8, 24, netEstate * 8/24, "Fixed Share", 3);
                if (netEstate * 5/24 > 0.01) addResult(results, "Baitulmal", "State Treasury", 5, 24, netEstate * 5/24, "Surplus", 3);
                return results;
            } else if (heirs.husband_count === 1 && heirs.daughter_count === 1 && heirs.son_count === 0) {
                addResult(results, "Husband", "Husband", 1, 4, netEstate * 1/4, "Fixed Share", 3);
                addResult(results, "Daughter", "Daughter", 2, 4, netEstate * 2/4, "Fixed Share", 3);
                if (netEstate * 1/4 > 0.01) addResult(results, "Baitulmal", "State Treasury", 1, 4, netEstate * 1/4, "Surplus", 3);
                return results;
            } else if (heirs.husband_count === 1 && heirs.son_count === 1 && heirs.daughter_count === 1) {
                addResult(results, "Husband", "Husband", 1, 4, netEstate * 1/4, "Fixed Share", 3);
                addResult(results, "Son", "Son", 2, 4, netEstate * 2/4, "Asabah", 3);
                addResult(results, "Daughter", "Daughter", 1, 4, netEstate * 1/4, "Asabah", 3);
                return results;
            } else {
                if (heirs.wife_count === 1) {
                    addResult(results, "Wife", "Wife", 1, 8, netEstate * 1/8, "Fixed Share", 3);
                } else if (heirs.husband_count === 1) {
                    addResult(results, "Husband", "Husband", 1, 4, netEstate * 1/4, "Fixed Share", 3);
                }
                let remaining = netEstate - (heirs.wife_count === 1 ? netEstate * 1/8 : (heirs.husband_count === 1 ? netEstate * 1/4 : 0));
                const totalShares = (heirs.son_count * 2) + heirs.daughter_count;
                const unitValue = remaining / totalShares;
                for (let i = 1; i <= heirs.son_count; i++) addResult(results, `Son ${i}`, "Son", 2, totalShares, unitValue * 2, "Asabah", 3);
                for (let i = 1; i <= heirs.daughter_count; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, totalShares, unitValue, "Asabah", 3);
                return results;
            }
        }
        
        // Children only
        if (!hasSpouse && !hasParents && hasChildren && !hasSiblings) {
            if (heirs.son_count === 1 && heirs.daughter_count === 0) {
                addResult(results, "Son", "Son", 1, 1, netEstate, "Asabah", 4);
                return results;
            } else if (heirs.daughter_count === 1 && heirs.son_count === 0) {
                addResult(results, "Daughter", "Daughter", 1, 2, netEstate * 1/2, "Fixed Share", 4);
                if (netEstate * 1/2 > 0.01) addResult(results, "Baitulmal", "State Treasury", 1, 2, netEstate * 1/2, "Surplus", 4);
                return results;
            } else if (heirs.daughter_count === 2 && heirs.son_count === 0) {
                for (let i = 1; i <= 2; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, 3, netEstate * 1/3, "Fixed Share", 4);
                if (netEstate * 1/3 > 0.01) addResult(results, "Baitulmal", "State Treasury", 1, 3, netEstate * 1/3, "Surplus", 4);
                return results;
            } else if (heirs.son_count === 2 && heirs.daughter_count === 2) {
                const totalShares = (2 * 2) + 2;
                for (let i = 1; i <= 2; i++) addResult(results, `Son ${i}`, "Son", 2, totalShares, netEstate * 2/totalShares, "Asabah", 4);
                for (let i = 1; i <= 2; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, totalShares, netEstate * 1/totalShares, "Asabah", 4);
                return results;
            } else if (hasChildren) {
                const totalShares = (heirs.son_count * 2) + heirs.daughter_count;
                for (let i = 1; i <= heirs.son_count; i++) addResult(results, `Son ${i}`, "Son", 2, totalShares, netEstate * 2/totalShares, "Asabah", 4);
                for (let i = 1; i <= heirs.daughter_count; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, totalShares, netEstate * 1/totalShares, "Asabah", 4);
                return results;
            }
        }
        
        // Parents and children
        if (!hasSpouse && hasParents && hasChildren) {
            if (hasFather && !hasMother && heirs.son_count === 1 && heirs.daughter_count === 0) {
                addResult(results, "Father", "Father", 1, 6, netEstate * 1/6, "Fixed Share", 5);
                addResult(results, "Son", "Son", 5, 6, netEstate * 5/6, "Asabah", 5);
                return results;
            } else if (hasFather && !hasMother && heirs.daughter_count === 1 && heirs.son_count === 0) {
                addResult(results, "Father", "Father", 1, 2, netEstate * 1/2, "Fixed Share", 5);
                addResult(results, "Daughter", "Daughter", 1, 2, netEstate * 1/2, "Fixed Share", 5);
                return results;
            } else if (!hasFather && hasMother && heirs.son_count === 1 && heirs.daughter_count === 0) {
                addResult(results, "Mother", "Mother", 1, 6, netEstate * 1/6, "Fixed Share", 5);
                addResult(results, "Son", "Son", 5, 6, netEstate * 5/6, "Asabah", 5);
                return results;
            } else if (!hasFather && hasMother && heirs.daughter_count === 1 && heirs.son_count === 0) {
                addResult(results, "Mother", "Mother", 1, 6, netEstate * 1/6, "Fixed Share", 5);
                addResult(results, "Daughter", "Daughter", 3, 6, netEstate * 3/6, "Fixed Share", 5);
                if (netEstate * 2/6 > 0.01) addResult(results, "Baitulmal", "State Treasury", 2, 6, netEstate * 2/6, "Surplus", 5);
                return results;
            } else if (hasFather && hasMother) {
                addResult(results, "Father", "Father", 1, 6, netEstate * 1/6, "Fixed Share", 5);
                addResult(results, "Mother", "Mother", 1, 6, netEstate * 1/6, "Fixed Share", 5);
                const remaining = netEstate - (netEstate * 2/6);
                const totalShares = (heirs.son_count * 2) + heirs.daughter_count;
                const unitValue = remaining / totalShares;
                for (let i = 1; i <= heirs.son_count; i++) addResult(results, `Son ${i}`, "Son", 2, totalShares, unitValue * 2, "Asabah", 5);
                for (let i = 1; i <= heirs.daughter_count; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, totalShares, unitValue, "Asabah", 5);
                return results;
            } else if (hasFather && !hasMother && hasChildren) {
                addResult(results, "Father", "Father", 1, 6, netEstate * 1/6, "Fixed Share", 5);
                const remaining = netEstate - (netEstate * 1/6);
                const totalShares = (heirs.son_count * 2) + heirs.daughter_count;
                const unitValue = remaining / totalShares;
                for (let i = 1; i <= heirs.son_count; i++) addResult(results, `Son ${i}`, "Son", 2, totalShares, unitValue * 2, "Asabah", 5);
                for (let i = 1; i <= heirs.daughter_count; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, totalShares, unitValue, "Asabah", 5);
                return results;
            } else if (!hasFather && hasMother && hasChildren) {
                addResult(results, "Mother", "Mother", 1, 6, netEstate * 1/6, "Fixed Share", 5);
                const remaining = netEstate - (netEstate * 1/6);
                const totalShares = (heirs.son_count * 2) + heirs.daughter_count;
                const unitValue = remaining / totalShares;
                for (let i = 1; i <= heirs.son_count; i++) addResult(results, `Son ${i}`, "Son", 2, totalShares, unitValue * 2, "Asabah", 5);
                for (let i = 1; i <= heirs.daughter_count; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, totalShares, unitValue, "Asabah", 5);
                return results;
            }
        }
        
        // Parents only (no children)
        if (!hasSpouse && hasParents && !hasChildren && !hasSiblings) {
            if (hasFather && !hasMother) {
                addResult(results, "Father", "Father", 1, 1, netEstate, "Asabah", 6);
                return results;
            } else if (!hasFather && hasMother) {
                addResult(results, "Mother", "Mother", 1, 3, netEstate * 1/3, "Fixed Share", 6);
                if (netEstate * 2/3 > 0.01) addResult(results, "Baitulmal", "State Treasury", 2, 3, netEstate * 2/3, "Surplus", 6);
                return results;
            } else if (hasFather && hasMother) {
                addResult(results, "Mother", "Mother", 1, 3, netEstate * 1/3, "Fixed Share", 6);
                addResult(results, "Father", "Father", 2, 3, netEstate * 2/3, "Asabah", 6);
                return results;
            }
        }
        
        // Full siblings only
        if (!hasSpouse && !hasParents && !hasChildren && hasFullSiblings && !hasPaternalHalfSiblings && !hasMaternalHalfSiblings) {
            if (heirs.full_brother_count === 1 && heirs.full_sister_count === 0) {
                addResult(results, "Full Brother", "Full Brother", 1, 1, netEstate, "Asabah", 7);
                return results;
            } else if (heirs.full_sister_count === 1 && heirs.full_brother_count === 0) {
                addResult(results, "Full Sister", "Full Sister", 1, 2, netEstate * 1/2, "Fixed Share", 7);
                if (netEstate * 1/2 > 0.01) addResult(results, "Baitulmal", "State Treasury", 1, 2, netEstate * 1/2, "Surplus", 7);
                return results;
            } else if (heirs.full_sister_count === 2 && heirs.full_brother_count === 0) {
                for (let i = 1; i <= 2; i++) addResult(results, `Full Sister ${i}`, "Full Sister", 1, 3, netEstate * 1/3, "Fixed Share", 7);
                if (netEstate * 1/3 > 0.01) addResult(results, "Baitulmal", "State Treasury", 1, 3, netEstate * 1/3, "Surplus", 7);
                return results;
            } else if (heirs.full_brother_count === 1 && heirs.full_sister_count === 1) {
                addResult(results, "Full Brother", "Full Brother", 2, 3, netEstate * 2/3, "Asabah", 7);
                addResult(results, "Full Sister", "Full Sister", 1, 3, netEstate * 1/3, "Asabah", 7);
                return results;
            } else if (hasFullSiblings) {
                const totalShares = (heirs.full_brother_count * 2) + heirs.full_sister_count;
                for (let i = 1; i <= heirs.full_brother_count; i++) addResult(results, `Full Brother ${i}`, "Full Brother", 2, totalShares, netEstate * 2/totalShares, "Asabah", 7);
                for (let i = 1; i <= heirs.full_sister_count; i++) addResult(results, `Full Sister ${i}`, "Full Sister", 1, totalShares, netEstate * 1/totalShares, "Asabah", 7);
                return results;
            }
        }
        
        // Maternal half-siblings only
        if (!hasSpouse && !hasParents && !hasChildren && hasMaternalHalfSiblings && !hasFullSiblings && !hasPaternalHalfSiblings) {
            if (heirs.maternal_half_brother_count === 1 && heirs.maternal_half_sister_count === 0) {
                addResult(results, "Maternal Half-Brother", "Maternal Half-Brother", 1, 6, netEstate * 1/6, "Fixed Share", 8);
                if (netEstate * 5/6 > 0.01) addResult(results, "Baitulmal", "State Treasury", 5, 6, netEstate * 5/6, "Surplus", 8);
                return results;
            } else if (heirs.maternal_half_sister_count === 1 && heirs.maternal_half_brother_count === 0) {
                addResult(results, "Maternal Half-Sister", "Maternal Half-Sister", 1, 6, netEstate * 1/6, "Fixed Share", 8);
                if (netEstate * 5/6 > 0.01) addResult(results, "Baitulmal", "State Treasury", 5, 6, netEstate * 5/6, "Surplus", 8);
                return results;
            } else if (hasMaternalHalfSiblings) {
                const count = heirs.maternal_half_brother_count + heirs.maternal_half_sister_count;
                for (let i = 1; i <= count; i++) {
                    addResult(results, `Maternal Half-Sibling ${i}`, "Maternal Half-Sibling", 1, 6, netEstate * 1/6, "Fixed Share", 8);
                }
                const totalShares = 6;
                const distributed = (count * netEstate) / 6;
                if (netEstate - distributed > 0.01) addResult(results, "Baitulmal", "State Treasury", (6 - count), 6, netEstate - distributed, "Surplus", 8);
                return results;
            }
        }
        
        // Special: Paternal half-brother + maternal half-sister
        if (!hasSpouse && !hasParents && !hasChildren && !hasFullSiblings &&
            heirs.paternal_half_brother_count === 1 && heirs.maternal_half_sister_count === 1 &&
            heirs.paternal_half_sister_count === 0 && heirs.maternal_half_brother_count === 0) {
            addResult(results, "Paternal Half-Brother", "Paternal Half-Brother", 5, 6, netEstate * 5/6, "Fixed Share", 14);
            addResult(results, "Maternal Half-Sister", "Maternal Half-Sister", 1, 6, netEstate * 1/6, "Fixed Share", 14);
            return results;
        }
        
        // Awl cases
        if (heirs.wife_count === 1 && hasMother && !hasFather && !hasChildren && heirs.full_sister_count === 2 && heirs.full_brother_count === 0 &&
            heirs.paternal_half_brother_count === 0 && heirs.paternal_half_sister_count === 0 && heirs.maternal_half_brother_count === 0 && heirs.maternal_half_sister_count === 0) {
            addResult(results, "Wife", "Wife", 3, 13, netEstate * 3/13, "Fixed Share", 11);
            addResult(results, "Mother", "Mother", 2, 13, netEstate * 2/13, "Fixed Share", 11);
            for (let i = 1; i <= 2; i++) addResult(results, `Full Sister ${i}`, "Full Sister", 4, 13, netEstate * 4/13, "Fixed Share", 11);
            return results;
        }
        
        // Husband + 2 full sisters
        if (heirs.husband_count === 1 && !hasParents && !hasChildren && heirs.full_sister_count === 2 && heirs.full_brother_count === 0 &&
            heirs.paternal_half_brother_count === 0 && heirs.paternal_half_sister_count === 0 && heirs.maternal_half_brother_count === 0 && heirs.maternal_half_sister_count === 0) {
            addResult(results, "Husband", "Husband", 3, 7, netEstate * 3/7, "Fixed Share", 11);
            for (let i = 1; i <= 2; i++) addResult(results, `Full Sister ${i}`, "Full Sister", 2, 7, netEstate * 2/7, "Fixed Share", 11);
            return results;
        }
        
        // Multiple wives special cases
        if (heirs.wife_count === 3 && heirs.husband_count === 0 && !hasParents && !hasChildren && !hasSiblings) {
            const perWife = netEstate * 1/12;
            for (let i = 1; i <= 3; i++) {
                addResult(results, `Wife ${i}`, "Wife", 1, 12, perWife, "Fixed Share", 13);
            }
            if (netEstate * 9/12 > 0.01) {
                addResult(results, "Baitulmal", "State Treasury", 9, 12, netEstate * 9/12, "Surplus", 13);
            }
            return results;
        }
        
        // Surplus cases
        if (heirs.wife_count === 1 && heirs.daughter_count === 1 && !hasParents && heirs.son_count === 0 && !hasSiblings) {
            addResult(results, "Wife", "Wife", 1, 8, netEstate * 1/8, "Fixed Share", 3);
            addResult(results, "Daughter", "Daughter", 4, 8, netEstate * 4/8, "Fixed Share", 3);
            if (netEstate * 3/8 > 0.01) addResult(results, "Baitulmal", "State Treasury", 3, 8, netEstate * 3/8, "Surplus", 3);
            return results;
        }
        
        if (!hasFather && hasMother && heirs.daughter_count === 1 && heirs.son_count === 0 && !hasSpouse) {
            addResult(results, "Mother", "Mother", 1, 6, netEstate * 1/6, "Fixed Share", 5);
            addResult(results, "Daughter", "Daughter", 3, 6, netEstate * 3/6, "Fixed Share", 5);
            if (netEstate * 2/6 > 0.01) addResult(results, "Baitulmal", "State Treasury", 2, 6, netEstate * 2/6, "Surplus", 5);
            return results;
        }
        
        // Fallback: Standard Faraid logic
        let remaining = netEstate;
        
        if (heirs.husband_count === 1) {
            const husbandShare = hasChildren ? 1/4 : 1/2;
            addResult(results, "Husband", "Husband", 1, hasChildren ? 4 : 2, netEstate * husbandShare, "Fixed Share", 15);
            remaining -= netEstate * husbandShare;
        } else if (heirs.wife_count > 0) {
            const totalWifeShare = hasChildren ? 1/8 : 1/4;
            const perWife = (netEstate * totalWifeShare) / heirs.wife_count;
            for (let i = 1; i <= heirs.wife_count; i++) {
                addResult(results, `Wife ${i}`, "Wife", 1, hasChildren ? 8 * heirs.wife_count : 4 * heirs.wife_count, perWife, "Fixed Share", 15);
            }
            remaining -= netEstate * totalWifeShare;
        }
        
        if (hasFather) {
            addResult(results, "Father", "Father", 1, 6, netEstate * 1/6, "Fixed Share", 15);
            remaining -= netEstate * 1/6;
        }
        if (hasMother) {
            let motherDenom = 6;
            if (!hasChildren && !hasSpouse && !hasFather) motherDenom = 3;
            addResult(results, "Mother", "Mother", 1, motherDenom, netEstate * 1/motherDenom, "Fixed Share", 15);
            remaining -= netEstate * 1/motherDenom;
        }
        
        if (remaining > 0 && hasChildren) {
            const totalShares = (heirs.son_count * 2) + heirs.daughter_count;
            const unitValue = remaining / totalShares;
            for (let i = 1; i <= heirs.son_count; i++) addResult(results, `Son ${i}`, "Son", 2, totalShares, unitValue * 2, "Asabah", 15);
            for (let i = 1; i <= heirs.daughter_count; i++) addResult(results, `Daughter ${i}`, "Daughter", 1, totalShares, unitValue, "Asabah", 15);
            remaining = 0;
        }
        
        if (remaining > 0 && !hasChildren && !hasParents && hasFullSiblings) {
            const totalShares = (heirs.full_brother_count * 2) + heirs.full_sister_count;
            const unitValue = remaining / totalShares;
            for (let i = 1; i <= heirs.full_brother_count; i++) addResult(results, `Full Brother ${i}`, "Full Brother", 2, totalShares, unitValue * 2, "Asabah", 15);
            for (let i = 1; i <= heirs.full_sister_count; i++) addResult(results, `Full Sister ${i}`, "Full Sister", 1, totalShares, unitValue, "Asabah", 15);
            remaining = 0;
        }
        
        if (remaining > 0 && !hasChildren && !hasParents && hasPaternalHalfSiblings && !hasFullSiblings) {
            const totalShares = (heirs.paternal_half_brother_count * 2) + heirs.paternal_half_sister_count;
            const unitValue = remaining / totalShares;
            for (let i = 1; i <= heirs.paternal_half_brother_count; i++) addResult(results, `Paternal Half-Brother ${i}`, "Paternal Half-Brother", 2, totalShares, unitValue * 2, "Asabah", 15);
            for (let i = 1; i <= heirs.paternal_half_sister_count; i++) addResult(results, `Paternal Half-Sister ${i}`, "Paternal Half-Sister", 1, totalShares, unitValue, "Asabah", 15);
            remaining = 0;
        }
        
        if (remaining > 0.01) {
            addResult(results, "Baitulmal", "State Treasury", 0, 1, remaining, "Surplus", 15);
        }
        
        return results;
    }
    
    function getScenarioDescription(scenario) {
        const descriptions = {
            1: "Spouse Only - Inheritance distributed solely to the surviving spouse according to fixed shares.",
            2: "Spouse and Parents (No Children) - Estate divided between spouse and parents with specific shares.",
            3: "Spouse and Children - Distribution between spouse and children according to prescribed shares.",
            4: "Children Only - Entire estate distributed among children following Faraid rules.",
            5: "Parents and Children - Both parents and children inherit according to fixed and residual shares.",
            6: "Parents Only (No Children) - Estate divided entirely between the parents.",
            7: "Full Siblings Only - Only full siblings inherit.",
            8: "Maternal Half-Siblings Only - Maternal half-siblings inherit fixed shares.",
            9: "Paternal Half-Siblings Only - Paternal half-siblings inherit.",
            11: "Awl (Over-subscription) - Fixed shares exceed 100%, requiring proportional reduction.",
            13: "Multiple Wives / Daughters - Special distribution with multiple wives or daughters.",
            14: "Paternal Half-Brother + Maternal Half-Sister - Specific distribution.",
            15: "Standard Faraid Rules - General rules applied for complex heir combinations."
        };
        return descriptions[scenario] || "Standard inheritance distribution according to Faraid rules.";
    }
    
    function calculateFaraid() {
        const loadingResults = document.getElementById('loadingResults');
        const resultsContent = document.getElementById('resultsContent');
        if (loadingResults) loadingResults.style.display = 'block';
        if (resultsContent) resultsContent.style.display = 'none';
        
        setTimeout(() => {
            try {
                const assetsSummary = updateAssetsSummary();
                const netEstate = assetsSummary.netAssets;
                
                if (netEstate <= 0) {
                    ModernAlert.error('Total estate must be greater than 0.', 'Invalid Estate');
                    if (loadingResults) loadingResults.style.display = 'none';
                    if (resultsContent) resultsContent.style.display = 'block';
                    return;
                }

                const heirsData = {
                    wife_count: parseInt(document.getElementById('wifeCount').value) || 0,
                    husband_count: parseInt(document.getElementById('husbandCount').value) || 0,
                    father_status: document.getElementById('fatherStatus')?.value || 'deceased',
                    mother_status: document.getElementById('motherStatus')?.value || 'deceased',
                    son_count: parseInt(document.getElementById('sonCount').value) || 0,
                    daughter_count: parseInt(document.getElementById('daughterCount').value) || 0,
                    fathers_father_status: document.getElementById('fathersFatherStatus')?.value || 'deceased',
                    fathers_mother_status: document.getElementById('fathersMotherStatus')?.value || 'deceased',
                    mothers_mother_status: document.getElementById('mothersMotherStatus')?.value || 'deceased',
                    full_brother_count: parseInt(document.getElementById('fullBrotherCount').value) || 0,
                    full_sister_count: parseInt(document.getElementById('fullSisterCount').value) || 0,
                    paternal_half_brother_count: parseInt(document.getElementById('paternalHalfBrotherCount').value) || 0,
                    paternal_half_sister_count: parseInt(document.getElementById('paternalHalfSisterCount').value) || 0,
                    maternal_half_brother_count: parseInt(document.getElementById('maternalHalfBrotherCount').value) || 0,
                    maternal_half_sister_count: parseInt(document.getElementById('maternalHalfSisterCount').value) || 0
                };

                const calculationResult = performFaraidCalculation(heirsData, netEstate);
                
                calculationResults = calculationResult;
                detectedScenario = calculationResult.length > 0 ? calculationResult[0].scenario : 0;
                
                displayResults(calculationResult, detectedScenario, netEstate);
                
                const formData = {
                    deceased_name: document.getElementById('deceasedName').value.trim(),
                    deceased_gender: document.getElementById('deceasedGender').value,
                    date_of_death: document.getElementById('dateOfDeath').value,
                    marital_status: document.getElementById('maritalStatus').value,
                    heirs_data: heirsData,
                    assets_data: { properties: properties, totalAssets: assetsSummary.totalAssets, netAssets: netEstate }
                };
                
                updateHiddenFields(formData, calculationResult, detectedScenario, assetsSummary);
                updatePieChart(calculationResult, netEstate);
                generateGraphvizTree();
                
                ModernAlert.success('Inheritance calculated successfully!', 'Calculation Complete');
                
            } catch (error) {
                console.error('Calculation error:', error);
                ModernAlert.error('An error occurred during calculation.', 'Calculation Error');
            } finally {
                if (loadingResults) loadingResults.style.display = 'none';
                if (resultsContent) resultsContent.style.display = 'block';
            }
        }, 300);
    }
    
    function updateHiddenFields(formData, results, scenario, assetsSummary) {
        const heirsDataField = document.getElementById('heirsData');
        const assetsDataField = document.getElementById('assetsData');
        const calculationDataField = document.getElementById('calculationData');
        const scenarioDataField = document.getElementById('scenarioData');
        const chartDataField = document.getElementById('chartData');
        const treeDataField = document.getElementById('treeData');
        
        const comprehensiveHeirData = { ...formData.heirs_data, deceased_name: formData.deceased_name, deceased_gender: formData.deceased_gender, date_of_death: formData.date_of_death, marital_status: formData.marital_status };
        if (heirsDataField) heirsDataField.value = JSON.stringify(comprehensiveHeirData);
        if (assetsDataField) assetsDataField.value = JSON.stringify(formData.assets_data);
        
        const calculationData = {
            distribution: results.map(r => ({ heir: r.heir, relationship: r.relationship, fraction: r.fractionDisplay, amount: r.amount, percentage: r.percentage, status: r.status })),
            netEstate: assetsSummary.netAssets,
            totalHeirs: results.filter(r => r.amount > 0).length,
            scenario: scenario
        };
        if (calculationDataField) calculationDataField.value = JSON.stringify(calculationData);
        
        const eligibleResults = results.filter(r => r.amount > 0);
        const chartData = {
            segments: eligibleResults.map((r, i) => ({ heir: r.heir, relationship: r.relationship, amount: r.amount, percentage: r.percentage, color: getColorForIndex(i) })),
            totalEligible: eligibleResults.length,
            totalAmount: eligibleResults.reduce((sum, r) => sum + parseFloat(r.amount), 0)
        };
        if (chartDataField) chartDataField.value = JSON.stringify(chartData);
        
        if (scenarioDataField) scenarioDataField.value = JSON.stringify({ scenario_number: scenario, description: getScenarioDescription(scenario), timestamp: new Date().toISOString() });
        
        if (treeDataField) {
            treeDataField.value = JSON.stringify({
                deceased: { name: formData.deceased_name, gender: formData.deceased_gender },
                heirs: formData.heirs_data,
                distributionResults: results,
                timestamp: new Date().toISOString(),
                properties: properties
            });
        }
    }
    
    function getColorForIndex(index) {
        const colors = ['#7e1ab4', '#2d7ad6', '#25D366', '#ffd700', '#dc3545', '#2eaec2', '#1a5fb4', '#ff6b6b', '#51cf66', '#9d4edd', '#f48c06', '#0096c7'];
        return colors[index % colors.length];
    }
    
    function displayResults(results, scenario, netEstate) {
        const totalHeirs = results.filter(r => parseFloat(r.amount) > 0 && r.heir !== 'Baitulmal').length;
        document.getElementById('resultTotalHeirs').textContent = totalHeirs;
        document.getElementById('resultNetEstate').textContent = `RM ${parseFloat(netEstate).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        document.getElementById('resultScenario').textContent = scenario;
        document.getElementById('scenarioBadge').textContent = `Scenario ${scenario}`;
        document.getElementById('scenarioText').textContent = getScenarioDescription(scenario);
        
        const tbody = document.getElementById('distributionResults');
        const tfoot = document.getElementById('distributionTotal');
        if (!tbody) return;
        tbody.innerHTML = '';
        
        if (results.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-muted">No distribution calculated</td></tr>';
            if (tfoot) tfoot.style.display = 'none';
            return;
        }
        
        let totalDistributed = 0;
        let totalPercentage = 0;
        
        results.forEach((result) => {
            const amountNum = parseFloat(result.amount);
            totalDistributed += amountNum;
            totalPercentage += parseFloat(result.percentage);
            
            let badgeClass = 'badge-primary';
            if (result.status.includes('Fixed Share')) badgeClass = 'badge-success';
            else if (result.status.includes('Asabah')) badgeClass = 'badge-warning';
            else if (result.status.includes('Surplus')) badgeClass = 'badge-info';
            else if (result.status.includes('Blocked')) badgeClass = 'badge-danger';
            
            const fractionDisplay = result.fractionDisplay || "0";
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${escapeHtml(result.heir)}</strong></td>
                <td>${ucfirst(escapeHtml(result.relationship))}</td>
                <td>${escapeHtml(fractionDisplay)}</td>
                <td><strong>RM ${amountNum.toLocaleString('en-US', {minimumFractionDigits: 2})}</strong></td>
                <td><strong>${parseFloat(result.percentage).toFixed(2)}%</strong></td>
                <td><span class="badge ${badgeClass}">${escapeHtml(result.status)}</span></td>
            `;
            tbody.appendChild(row);
        });
        
        document.getElementById('totalDistributedValue').textContent = `RM ${totalDistributed.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        document.getElementById('totalPercentageValue').textContent = `${totalPercentage.toFixed(2)}%`;
        if (tfoot) tfoot.style.display = 'table-row-group';
    }
    
    function ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    function updatePieChart(results, netEstate) {
        const eligibleResults = results.filter(result => {
            const amount = parseFloat(result.amount) || 0;
            return amount > 0 && isFinite(amount) && result.heir !== 'Baitulmal';
        });
        eligibleResults.sort((a, b) => (parseFloat(b.amount) || 0) - (parseFloat(a.amount) || 0));
        
        const eligibleCount = eligibleResults.length;
        const totalAmount = eligibleResults.reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
        
        document.getElementById('eligibleCount').textContent = eligibleCount;
        
        const chartLegend = document.getElementById('chartLegend');
        if (!chartLegend) return;
        
        if (eligibleCount === 0 || totalAmount === 0) {
            chartLegend.innerHTML = `<div class="no-eligible-recipients"><svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg><h3>No Eligible Recipients</h3><p>No eligible recipients with positive inheritance amounts</p></div>`;
            return;
        }
        
        const labels = eligibleResults.map(result => result.heir);
        const data = eligibleResults.map(result => parseFloat(result.amount));
        const backgroundColors = eligibleResults.map((result, index) => getColorForIndex(index));
        const percentages = eligibleResults.map(result => parseFloat(result.percentage).toFixed(2));
        
        const ctx = document.getElementById('distributionPieChart').getContext('2d');
        if (pieChart) pieChart.destroy();
        
        pieChart = new Chart(ctx, {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: data, backgroundColor: backgroundColors, borderWidth: 2, borderColor: '#ffffff' }] },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%',
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { const value = context.raw || 0; const percentage = ((value / totalAmount) * 100).toFixed(2); return `${context.label}: RM ${value.toLocaleString('en-US', {minimumFractionDigits: 2})} (${percentage}%)`; } } } },
                animation: { animateScale: true, animateRotate: true }
            }
        });
        
        let legendHTML = '';
        eligibleResults.forEach((result, index) => {
            legendHTML += `<div class="legend-item" data-index="${index}"><div class="legend-color" style="background: ${backgroundColors[index]};"></div><div class="legend-text">${escapeHtml(result.heir)}<div class="legend-relationship">${ucfirst(escapeHtml(result.relationship))}</div><div class="legend-amount">RM ${parseFloat(result.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div></div><div class="legend-percentage">${percentages[index]}%</div></div>`;
        });
        legendHTML += `<div class="legend-summary"><div class="legend-item"><div class="legend-color" style="background: var(--primary-color);"></div><div class="legend-text"><strong>Total Eligible Distribution</strong><div class="legend-amount">${eligibleCount} recipient(s)</div></div><div class="legend-percentage">RM ${totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}</div></div></div>`;
        chartLegend.innerHTML = legendHTML;
        
        addChartInteractivity(eligibleResults, backgroundColors);
    }
    
    function addChartInteractivity(results, colors) {
        const legendItems = document.querySelectorAll('.legend-item:not(:last-child)');
        legendItems.forEach((item, index) => {
            item.addEventListener('mouseenter', function() {
                this.classList.add('highlighted');
                if (pieChart) { pieChart.setActiveElements([{ datasetIndex: 0, index: index }]); pieChart.update(); }
            });
            item.addEventListener('mouseleave', function() {
                this.classList.remove('highlighted');
                if (pieChart) { pieChart.setActiveElements([]); pieChart.update(); }
            });
            item.addEventListener('click', function() {
                if (pieChart) {
                    const activeIndex = pieChart.getActiveElements()[0]?.index;
                    if (activeIndex === index) pieChart.setActiveElements([]);
                    else pieChart.setActiveElements([{ datasetIndex: 0, index: index }]);
                    pieChart.update();
                }
            });
        });
    }
    
    // ===== FAMILY TREE FUNCTIONS =====
    document.getElementById('zoomInBtn')?.addEventListener('click', zoomInTree);
    document.getElementById('zoomOutBtn')?.addEventListener('click', zoomOutTree);
    document.getElementById('resetViewBtn')?.addEventListener('click', resetTreeView);
    
    document.querySelectorAll('.layout-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const layout = this.dataset.layout;
            document.querySelectorAll('.layout-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentGraphvizLayout = layout;
            if (familyTreeGenerated && calculationResults) generateGraphvizTree();
        });
    });
    
    function zoomInTree() {
        if (!graphvizInstance) return;
        const viewBoxValues = graphvizInstance.getAttribute('viewBox')?.split(' ')?.map(Number);
        if (!viewBoxValues || viewBoxValues.length !== 4) return;
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
        const viewBoxValues = graphvizInstance.getAttribute('viewBox')?.split(' ')?.map(Number);
        if (!viewBoxValues || viewBoxValues.length !== 4) return;
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
        if (svgViewBox) graphvizInstance.setAttribute('viewBox', svgViewBox);
        else graphvizInstance.removeAttribute('viewBox');
        currentScale = 1;
    }
    
    function buildAmountLookup(calculationResultsArray) {
        const lookup = {};
        if (!calculationResultsArray) return lookup;
        for (const result of calculationResultsArray) {
            const heirName = result.heir;
            const amountNum = parseFloat(result.amount);
            if (!isNaN(amountNum) && amountNum > 0) {
                lookup[heirName] = amountNum;
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
    
    function generateDOTCodeWithAllHeirs(deceasedName, deceasedGender, heirs, netEstate, calculationResultsArray) {
        const amountLookup = buildAmountLookup(calculationResultsArray);
        
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
        
        if (heirs.father_status === 'alive') {
            dot += createHeirNode('Father', 'Father', 'Father', fatherColor);
            dot += `Deceased -> Father [label="Child", dir=back, fontcolor="#666"];\n`;
        }
        
        if (heirs.mother_status === 'alive') {
            dot += createHeirNode('Mother', 'Mother', 'Mother', motherColor);
            dot += `Deceased -> Mother [label="Child", dir=back, fontcolor="#666"];\n`;
        }
        
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
        
        const baitulmalAmount = amountLookup['Baitulmal'] || 0;
        if (baitulmalAmount > 0 && netEstate > 0) {
            const baitulmalPercentage = ((baitulmalAmount / netEstate) * 100).toFixed(1);
            dot += `Baitulmal [label="Baitulmal\\n${formatAmount(baitulmalAmount)} (${baitulmalPercentage}%)", fillcolor="${baitulmalColor}", fontcolor=white, shape=box3d, width=2.4, height=1, fontsize=11];\n`;
            dot += `Deceased -> Baitulmal [style=dashed, color="#6c757d", penwidth=1.5, fontcolor="#999", label="Surplus"];\n`;
        }
        
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
        
        const amountLookup = buildAmountLookup(calculationResultsArray);
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
    
    function generateGraphvizTree() {
        if (!calculationResults || calculationResults.length === 0) {
            console.log('No calculation results yet, tree will generate after calculation');
            return;
        }
        
        showLoading('Generating Family Tree...', 'Creating complete family tree with ALL heirs and distribution amounts');
        
        try {
            const deceasedName = document.getElementById('deceasedName').value.trim() || 'Deceased';
            const deceasedGender = document.getElementById('deceasedGender').value;
            const heirs = {
                wife_count: parseInt(document.getElementById('wifeCount').value) || 0,
                husband_count: parseInt(document.getElementById('husbandCount').value) || 0,
                father_status: document.getElementById('fatherStatus')?.value || 'deceased',
                mother_status: document.getElementById('motherStatus')?.value || 'deceased',
                son_count: parseInt(document.getElementById('sonCount').value) || 0,
                daughter_count: parseInt(document.getElementById('daughterCount').value) || 0,
                fathers_father_status: document.getElementById('fathersFatherStatus')?.value || 'deceased',
                fathers_mother_status: document.getElementById('fathersMotherStatus')?.value || 'deceased',
                mothers_mother_status: document.getElementById('mothersMotherStatus')?.value || 'deceased',
                full_brother_count: parseInt(document.getElementById('fullBrotherCount').value) || 0,
                full_sister_count: parseInt(document.getElementById('fullSisterCount').value) || 0,
                paternal_half_brother_count: parseInt(document.getElementById('paternalHalfBrotherCount').value) || 0,
                paternal_half_sister_count: parseInt(document.getElementById('paternalHalfSisterCount').value) || 0,
                maternal_half_brother_count: parseInt(document.getElementById('maternalHalfBrotherCount').value) || 0,
                maternal_half_sister_count: parseInt(document.getElementById('maternalHalfSisterCount').value) || 0
            };
            const netEstate = parseFloat(document.getElementById('resultNetEstate')?.textContent.replace('RM ', '').replace(/,/g, '')) || 0;
            
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
    
    document.getElementById('exportTreePDF')?.addEventListener('click', async function() {
        if (!familyTreeGenerated) { 
            ModernAlert.warning('Please calculate inheritance first to generate the family tree.', 'Tree Required'); 
            return; 
        }
        
        showLoading('Exporting Family Tree as PDF...', 'Generating PDF with complete family tree');
        
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            const deceasedName = document.getElementById('deceasedName').value.trim() || 'Unknown';
            
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
    });
    
    document.getElementById('exportFullReport')?.addEventListener('click', async function() {
        if (!calculationResults || calculationResults.length === 0) { 
            ModernAlert.warning('Please calculate results first.', 'No Results'); 
            return; 
        }
        
        if (!familyTreeGenerated) {
            ModernAlert.info('Generating family tree for complete report...', 'Processing');
            generateGraphvizTree();
            await new Promise(resolve => setTimeout(resolve, 3000));
        }
        
        showLoading('Generating Faraid Report...', 'Creating comprehensive document');
        
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            let currentPage = 1;
            
            const deceasedName = document.getElementById('deceasedName').value.trim() || 'Unknown';
            const deceasedGender = document.getElementById('deceasedGender').value;
            const dateOfDeath = document.getElementById('dateOfDeath').value;
            const maritalStatus = document.getElementById('maritalStatus').value;
            const causeOfDeath = document.getElementById('causeOfDeath').value.trim();
            const deathPlace = document.getElementById('deathPlace').value.trim();
            const contactEmail = document.getElementById('contactEmail').value.trim();
            const contactPhone = document.getElementById('contactPhone').value.trim();
            const residentialAddress = document.getElementById('residentialAddress').value.trim();
            const netEstate = document.getElementById('resultNetEstate').textContent || 'RM 0.00';
            
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
            
            const totalGross = properties.reduce((sum, p) => sum + (p.value || 0), 0);
            const totalShare = properties.reduce((sum, p) => sum + (p.value * (p.ownership / 100)), 0);
            addSectionTitle('2. FINANCIAL SUMMARY');
            const financialData = [
                ['Total Assets (Before Deduction)', `RM ${totalGross.toLocaleString('en-US', {minimumFractionDigits: 2})}`],
                ['Net Estate for Distribution', netEstate],
                ['Total Eligible Heirs', document.getElementById('resultTotalHeirs')?.textContent || '0'],
                ['Scenario', `Scenario ${detectedScenario} - ${getScenarioDescription(detectedScenario)}`]
            ];
            addInfoTable(financialData);
            yPos += 4;
            
            if (properties.length > 0) {
                if (yPos > 240) {
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
                addSectionTitle('3. ASSETS LIST');
                
                const assetsData = properties.map(p => [
                    p.type, p.label || 'Asset', p.description || '-',
                    `RM ${p.value.toLocaleString('en-US', {minimumFractionDigits: 2})}`,
                    `${p.ownership}%`,
                    `RM ${(p.value * (p.ownership / 100)).toLocaleString('en-US', {minimumFractionDigits: 2})}`
                ]);
                doc.autoTable({
                    head: [['Type', 'Category', 'Description', 'Value', 'Ownership %', 'Your Share']],
                    body: assetsData,
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
    });
    
    document.getElementById('resetCalculator')?.addEventListener('click', function() {
        if (confirm('Reset all changes?')) window.location.reload();
    });
    
    renderPropertiesList();
    
    const dateOfDeathInput = document.getElementById('dateOfDeath');
    if (dateOfDeathInput) dateOfDeathInput.max = new Date().toISOString().split('T')[0];
    
    prefillFromUrlParams();
    updateTotalHeirsCount();
    
    document.getElementById('deceasedGender')?.addEventListener('change', function() {
        const gender = this.value;
        if (gender === 'female' && parseInt(document.getElementById('wifeCount').value) > 0) {
            document.getElementById('wifeCount').value = 0;
            updateCounterButtonState('wifeCount');
            ModernAlert.warning('Wife count reset for female deceased.', 'Gender Conflict');
        } else if (gender === 'male' && parseInt(document.getElementById('husbandCount').value) > 0) {
            document.getElementById('husbandCount').value = 0;
            updateCounterButtonState('husbandCount');
            ModernAlert.warning('Husband count reset for male deceased.', 'Gender Conflict');
        }
        updateTotalHeirsCount();
    });
    
    document.querySelectorAll('.counter-input').forEach(input => updateCounterButtonState(input.id));
    document.querySelectorAll('.status-btn.active').forEach(btn => {
        const hiddenInput = document.getElementById(btn.dataset.target);
        if (hiddenInput) hiddenInput.value = btn.dataset.value;
    });
    
    document.querySelectorAll('.export-btn').forEach(btn => btn.style.cursor = 'pointer');
});
</script>
@endsection