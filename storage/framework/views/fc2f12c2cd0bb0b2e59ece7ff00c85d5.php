


<?php $__env->startSection('title', 'Inheritance Report - Estate Plan'); ?>

<?php $__env->startSection('content'); ?>
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
    
    body, html {
        font-family: 'Poppins', sans-serif !important;
    }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
        color: var(--text-primary);
        overflow-x: hidden;
    }
    
    .report-header {
        min-height: 30vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
        padding: 3rem 2rem;
    }

    .report-header .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .report-header .animated-bg .bg-circle {
        position: absolute;
        border-radius: 50%;
    }

    .report-header .animated-bg .bg-circle-1 {
        top: 10%;
        right: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }

    .report-header .animated-bg .bg-circle-2 {
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }

    .report-header .animated-bg .bg-circle-3 {
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .report-header .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }

    .report-header .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .report-header .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    .report-header .shape-1 {
        width: 40px;
        height: 40px;
        top: 20%;
        left: 10%;
        animation-name: float-1;
    }

    .report-header .shape-2 {
        width: 25px;
        height: 25px;
        top: 60%;
        left: 85%;
        animation-name: float-2;
        animation-delay: 1s;
    }

    .report-header .shape-3 {
        width: 35px;
        height: 35px;
        top: 75%;
        left: 15%;
        animation-name: float-3;
        animation-delay: 0.5s;
    }

    .report-header .shape-4 {
        width: 20px;
        height: 20px;
        top: 30%;
        left: 70%;
        animation-name: float-4;
        animation-delay: 1.5s;
    }

    .report-header .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding: 1.5rem;
    }

    .report-header .hero-kicker {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        display: inline-flex;
        padding: 0.75rem 1.75rem;
        border-radius: var(--border-radius-xl);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.2);
        transition: var(--transition);
    }

    .report-header .hero-kicker:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-2px);
    }

    .report-header .kicker-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .report-header .kicker-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 1rem;
    }

    .report-header .kicker-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
        color: var(--accent-color);
    }

    .report-header .hero-title {
        font-size: 2.75rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .report-header .hero-highlight {
        color: var(--accent-color);
        position: relative;
        display: inline-block;
    }

    .report-header .hero-highlight::after {
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

    .report-header .hero-subtitle {
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
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl), 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
        padding: 1.75rem 2rem;
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
    .badge-real-estate { background: #e8f1fd; color: #1a5fb4; }
    .badge-financial { background: #d4edda; color: #155724; }
    .badge-investment { background: #fff3cd; color: #856404; }
    .badge-digital { background: #e0d4f5; color: #6f42c1; }
    .badge-secured { background: #f8d7da; color: #721c24; }
    .badge-unsecured { background: #d1ecf1; color: #0c5460; }
    .badge-religious { background: #d4edda; color: #155724; }
    
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
    
    .results-table tfoot tr {
        background: var(--gray-100);
        font-weight: 700;
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
    
    .disclaimer-card {
        background: var(--gray-50);
        border-radius: var(--border-radius-md);
        padding: 1.25rem;
        margin-top: 1.5rem;
        border: 1px solid var(--gray-200);
    }
    
    /* Button styling - Matching access-view.blade.php */
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
    
    .btn-pdf {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: var(--white);
    }
    
    .btn-pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(231, 76, 60, 0.3);
    }
    
    .btn-dashboard {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
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
    
    .no-print {
        /* Shown by default, hidden on print */
    }
    
    @media print {
        body { background: white !important; font-size: 10pt; }
        .no-print { display: none !important; }
        .glass-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; break-inside: avoid; margin-bottom: 1rem; }
        .card-body { padding: 1rem; }
        .report-header { min-height: auto !important; padding: 1.5rem 2rem; background: #1a5fb4 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .grid-2, .grid-3 { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
        .glass-container { max-width: 100%; margin: 0; padding: 0 1rem; }
        .summary-panel { box-shadow: none; border: 1px solid #e2e8f0; }
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
        .report-header .hero-title { font-size: 2rem; }
        .report-header { min-height: 28vh !important; padding: 2rem 1rem; }
        .glass-container { padding: 0 1rem 1.5rem; margin-top: -2rem; }
        .card-body { padding: 1.5rem; }
        .grid-2, .grid-3 { grid-template-columns: 1fr; }
        .button-group { flex-direction: column; }
        .btn { justify-content: center; width: 100%; }
    }
    
    @media (max-width: 480px) {
        .report-header .hero-title { font-size: 1.5rem; }
        .card-title { font-size: 1.25rem; }
    }
    
    /* PDF specific styles for hidden content */
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
</style>

<?php
    // Asset badge mapping
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
    
    // Debt badge mapping
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
    
    // Calculate financial summary
    $totalAssets = isset($estate) ? $estate->assets()->sum('value') : ($total_assets ?? 0);
    $totalDebts = isset($estate) ? $estate->debts()->sum('amount') : ($total_debts ?? 0);
    $netEstate = max(0, $totalAssets - $totalDebts);
    
    $totalWasiyyahPct = isset($estate) ? $estate->wasiyyah()->sum('requested_percentage') : 0;
    $totalHeirPct = isset($estate) ? $estate->heirs()->sum('share_percentage') : 0;
    
    // Calculate wasiyyah amounts
    $wasiyyahAmount = ($totalWasiyyahPct / 100) * $netEstate;
    $remainingForHeirs = $netEstate - $wasiyyahAmount;
    
    $maxWasiyyahPct = 33.33;
    $effectiveWasiyyahPct = $netEstate > 0 ? $totalWasiyyahPct : 0;
    
    // Determine data source - either from $estate object or from passed variables
    $deceasedName = $estate->deceased_name ?? $deceased_name ?? 'N/A';
    $deceasedNric = $estate->deceased_nric ?? $deceased_nric ?? 'N/A';
    $deceasedGender = $estate->gender ?? $deceased_gender ?? 'N/A';
    $deceasedDob = isset($estate) && $estate->date_of_birth ? $estate->date_of_birth->format('d F Y') : ($deceased_dob ?? 'N/A');
    $dateOfDeath = isset($estate) && $estate->date_of_death ? $estate->date_of_death->format('d F Y') : ($date_of_death ?? 'N/A');
    $deceasedAddress = $estate->address ?? $deceased_address ?? 'N/A';
    
    $trusteeName = $estate->trustee_name ?? $trustee_name ?? null;
    $trusteeNric = $estate->trustee_nric ?? null;
    $trusteePhone = $estate->trustee_phone ?? null;
    $trusteeEmail = $estate->trustee_email ?? $trustee_email ?? null;
    $trusteeRelationship = $estate->trustee_relationship ?? $trustee_relationship ?? 'N/A';
    
    $assets = isset($estate) ? $estate->assets : ($assets ?? []);
    $debts = isset($estate) ? $estate->debts : ($debts ?? []);
    $heirs = isset($estate) ? $estate->heirs : ($heirs ?? []);
    $wasiyyah = isset($estate) ? $estate->wasiyyah : ($wasiyyah ?? []);
    
    $documentId = $estate->unique_id ?? $document_id ?? 'N/A';
    $estateStatus = $estate->status ?? 'draft';
    $expiryDate = $expiryDate ?? '30 days from now';
    
    // Prepare heirs list for PDF and display
    $heirsList = [];
    if (count($heirs) > 0) {
        foreach ($heirs as $heir) {
            $heirsList[] = [
                'name' => $heir->name ?? $heir['name'] ?? 'N/A',
                'nric' => $heir->nric ?? $heir['nric'] ?? '-',
                'relationship' => $heir->relationship ?? $heir['relationship'] ?? 'N/A',
                'percentage' => (float)($heir->share_percentage ?? $heir['share_percentage'] ?? 0),
                'share_fraction' => $heir->share_fraction ?? $heir['share_fraction'] ?? null,
            ];
        }
    }
    
    // Prepare wasiyyah list for PDF
    $wasiyyahList = [];
    if (count($wasiyyah) > 0) {
        foreach ($wasiyyah as $item) {
            $wasiyyahList[] = [
                'name' => $item->beneficiary_name ?? $item['beneficiary_name'] ?? $item['name'] ?? 'N/A',
                'nric' => $item->beneficiary_nric ?? $item['beneficiary_nric'] ?? '-',
                'relationship' => $item->relationship ?? $item['relationship'] ?? 'N/A',
                'percentage' => (float)($item->requested_percentage ?? $item['requested_percentage'] ?? 0),
                'is_charity' => $item->is_charity ?? $item['is_charity'] ?? false,
            ];
        }
    }
    
    // Prepare assets list for PDF
    $assetsList = [];
    if (count($assets) > 0) {
        foreach ($assets as $asset) {
            $assetsList[] = [
                'name' => $asset->name ?? $asset['name'] ?? 'N/A',
                'value' => $asset->value ?? $asset['value'] ?? 0,
                'ownership_percentage' => $asset->ownership_percentage ?? $asset['ownership_percentage'] ?? 100,
                'description' => $asset->description ?? $asset['description'] ?? '-',
            ];
        }
    }
    
    // Prepare debts list for PDF
    $debtsList = [];
    if (count($debts) > 0) {
        foreach ($debts as $debt) {
            $debtsList[] = [
                'creditor_name' => $debt->creditor_name ?? $debt['creditor_name'] ?? 'N/A',
                'debt_type' => $debt->debt_type ?? $debt['type'] ?? 'Other',
                'amount' => $debt->amount ?? $debt['amount'] ?? 0,
                'description' => $debt->description ?? $debt['description'] ?? '-',
            ];
        }
    }
    
    $formattedNetEstate = 'RM ' . number_format($netEstate, 2);
?>

<!-- REPORT HEADER -->
<header class="report-header">
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
            Inheritance <span class="hero-highlight">Distribution</span> Report
        </h1>
        
        <p class="hero-subtitle">
            Estate Plan for <strong><?php echo e($deceasedName); ?></strong>
        </p>
    </div>
</header>

<div class="glass-container">
    
    <!-- CONFIDENTIAL NOTICE -->
    <div class="info-card" style="border-left-color: var(--warning-color); background: linear-gradient(135deg, var(--white) 0%, var(--warning-light) 100%); margin-bottom: 1.5rem;">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--warning-dark);">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <div>
            <strong style="color: var(--warning-dark);">Secure & Confidential</strong>
            <p style="margin: 0; font-size: 0.875rem; color: var(--gray-700);">This is a secure, confidential inheritance report. For authorized recipients only.</p>
        </div>
    </div>
    
    <!-- DECEASED INFORMATION -->
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
                <div>
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Full Name:</strong><br>
                    <span style="font-size: 1.125rem; font-weight: 600; color: var(--gray-900);"><?php echo e($deceasedName); ?></span></p>
                    
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">NRIC/Passport:</strong><br>
                    <span style="font-weight: 500;"><?php echo e($deceasedNric); ?></span></p>
                    
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Gender:</strong><br>
                    <span style="font-weight: 500;"><?php echo e(ucfirst($deceasedGender)); ?></span></p>
                </div>
                <div>
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Date of Birth:</strong><br>
                    <span style="font-weight: 500;"><?php echo e($deceasedDob); ?></span></p>
                    
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Date of Death:</strong><br>
                    <span style="font-weight: 500; color: var(--danger-color);"><?php echo e($dateOfDeath); ?></span></p>
                    
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Address:</strong><br>
                    <span style="font-weight: 500;"><?php echo e($deceasedAddress); ?></span></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FINANCIAL SUMMARY -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h2 class="card-title">Financial Summary</h2>
            <span class="card-badge">Overview</span>
        </div>
        <div class="card-body">
            <div class="grid-3" style="margin-bottom: 1.5rem;">
                <div style="background: var(--white); padding: 1.75rem; border-radius: var(--border-radius-md); border: 1px solid var(--gray-200); text-align: center;">
                    <div style="font-size: 0.875rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Total Assets</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--success-color);">RM <?php echo e(number_format($totalAssets, 2)); ?></div>
                </div>
                <div style="background: var(--white); padding: 1.75rem; border-radius: var(--border-radius-md); border: 1px solid var(--gray-200); text-align: center;">
                    <div style="font-size: 0.875rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Total Debts</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--danger-color);">RM <?php echo e(number_format($totalDebts, 2)); ?></div>
                </div>
                <div style="background: var(--white); padding: 1.75rem; border-radius: var(--border-radius-md); border: 1px solid var(--gray-200); text-align: center;">
                    <div style="font-size: 0.875rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Net Estate</div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--primary-color);">RM <?php echo e(number_format($netEstate, 2)); ?></div>
                </div>
            </div>
            
            <?php if($totalWasiyyahPct > 0): ?>
            <div class="grid-2" style="margin-top: 1rem;">
                <div style="background: linear-gradient(135deg, var(--white) 0%, var(--warning-light) 100%); padding: 1.5rem; border-radius: var(--border-radius-md); border: 1px solid rgba(255, 193, 7, 0.3); text-align: center;">
                    <div style="font-size: 0.875rem; font-weight: 600; color: #b76e00; margin-bottom: 0.5rem;">Wasiyyah (Bequest)</div>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--warning-dark);">RM <?php echo e(number_format($wasiyyahAmount, 2)); ?></div>
                    <div style="font-size: 0.8125rem; color: var(--gray-600); margin-top: 0.25rem;"><?php echo e(number_format($effectiveWasiyyahPct, 2)); ?>% of net estate</div>
                </div>
                <div style="background: linear-gradient(135deg, var(--white) 0%, var(--info-light) 100%); padding: 1.5rem; border-radius: var(--border-radius-md); border: 1px solid rgba(23, 162, 184, 0.3); text-align: center;">
                    <div style="font-size: 0.875rem; font-weight: 600; color: #0c5460; margin-bottom: 0.5rem;">Remaining for Heirs</div>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--info-color);">RM <?php echo e(number_format($remainingForHeirs, 2)); ?></div>
                    <div style="font-size: 0.8125rem; color: var(--gray-600); margin-top: 0.25rem;">After wasiyyah deduction</div>
                </div>
            </div>
            
            <!-- Wasiyyah Limit Bar -->
            <div style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                    <span><strong>Wasiyyah Limit:</strong> 1/3 of Net Estate (RM <?php echo e(number_format(($netEstate * 33.33) / 100, 2)); ?>)</span>
                    <span><strong>Current:</strong> <?php echo e(number_format($totalWasiyyahPct, 2)); ?>%</span>
                </div>
                <div class="wasiyyah-limit-bar">
                    <div class="wasiyyah-limit-fill <?php echo e($totalWasiyyahPct <= $maxWasiyyahPct ? 'safe' : ''); ?>" style="width: <?php echo e(min(100, ($totalWasiyyahPct / $maxWasiyyahPct) * 100)); ?>%;"></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- ASSETS INVENTORY -->
    <?php if(count($assets) > 0): ?>
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h2 class="card-title">Assets Inventory</h2>
            <span class="card-badge"><?php echo e(count($assets)); ?> Asset<?php echo e(count($assets) !== 1 ? 's' : ''); ?></span>
        </div>
        <div class="card-body">
            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Asset Name</th>
                            <th>Category</th>
                            <th>Value (RM)</th>
                            <th>Ownership</th>
                            <th>Owned Value (RM)</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $assetName = $asset->name ?? $asset['name'] ?? 'N/A';
                                $assetValue = $asset->value ?? $asset['value'] ?? 0;
                                $assetOwnership = $asset->ownership_percentage ?? $asset['ownership_percentage'] ?? 100;
                                $assetOwnedValue = $assetValue * ($assetOwnership / 100);
                                $assetDescription = $asset->description ?? $asset['description'] ?? '-';
                                $badge = $assetBadgeMap[$assetName] ?? 'real-estate';
                            ?>
                            <tr>
                                <td>
                                    <span class="badge badge-<?php echo e($badge); ?>"><?php echo e($assetName); ?></span>
                                </td>
                                <td><?php echo e($badge === 'real-estate' ? 'Real Estate' : ($badge === 'financial' ? 'Financial' : ($badge === 'investment' ? 'Investment' : ($badge === 'digital' ? 'Digital' : 'Other')))); ?></td>
                                <td style="text-align: right; font-weight: 600;">RM <?php echo e(number_format($assetValue, 2)); ?></td>
                                <td style="text-align: center;"><?php echo e(number_format($assetOwnership, 0)); ?>%</td>
                                <td style="text-align: right; font-weight: 600; color: var(--success-color);">RM <?php echo e(number_format($assetOwnedValue, 2)); ?></td>
                                <td><?php echo e($assetDescription); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--gray-100);">
                            <td colspan="4"><strong>Total Assets</strong></td>
                            <td style="text-align: right;"><strong style="color: var(--success-color);">RM <?php echo e(number_format($totalAssets, 2)); ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- OUTSTANDING DEBTS -->
    <?php if(count($debts) > 0): ?>
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="card-title">Outstanding Debts</h2>
            <span class="card-badge"><?php echo e(count($debts)); ?> Debt<?php echo e(count($debts) !== 1 ? 's' : ''); ?></span>
        </div>
        <div class="card-body">
            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Creditor</th>
                            <th>Type</th>
                            <th>Classification</th>
                            <th>Description</th>
                            <th>Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $debts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $debtType = $debt->debt_type ?? $debt['type'] ?? 'Other';
                                $creditorName = $debt->creditor_name ?? $debt['creditor_name'] ?? 'N/A';
                                $debtAmount = $debt->amount ?? $debt['amount'] ?? 0;
                                $debtDescription = $debt->description ?? $debt['description'] ?? '-';
                                $dBadge = $debtBadgeMap[$debtType] ?? 'unsecured';
                            ?>
                            <tr>
                                <td><strong><?php echo e($creditorName); ?></strong></td>
                                <td><span class="badge badge-<?php echo e($dBadge); ?>"><?php echo e($debtType); ?></span></td>
                                <td><?php echo e($dBadge === 'secured' ? 'Secured Debt' : ($dBadge === 'religious' ? 'Religious Obligation' : 'Unsecured Debt')); ?></td>
                                <td><?php echo e($debtDescription); ?></td>
                                <td style="text-align: right; font-weight: 600; color: var(--danger-color);">RM <?php echo e(number_format($debtAmount, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--gray-100);">
                            <td colspan="4"><strong>Total Debts</strong></td>
                            <td style="text-align: right;"><strong style="color: var(--danger-color);">RM <?php echo e(number_format($totalDebts, 2)); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- WASIYYAH DISTRIBUTION -->
    <?php if(count($wasiyyah) > 0): ?>
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <h2 class="card-title">Wasiyyah (Bequest) Distribution</h2>
            <span class="card-badge" style="background: var(--warning-light); color: #b76e00;"><?php echo e(count($wasiyyah)); ?> Beneficiar<?php echo e(count($wasiyyah) !== 1 ? 'ies' : 'y'); ?></span>
        </div>
        <div class="card-body">
            <div class="info-card" style="border-left-color: var(--warning-color); background: linear-gradient(135deg, var(--white) 0%, var(--warning-light) 100%);">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>Wasiyyah is limited to <strong>1/3 (33.33%)</strong> of the net estate as per Islamic law. Current usage: <strong style="color: <?php echo e($totalWasiyyahPct > $maxWasiyyahPct ? 'var(--danger-color)' : 'var(--success-color)'); ?>;"><?php echo e(number_format($totalWasiyyahPct, 2)); ?>%</strong></p>
            </div>
            
            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Beneficiary Name</th>
                            <th>NRIC</th>
                            <th>Relationship</th>
                            <th>Requested %</th>
                            <th>Amount (RM)</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $wasiyyah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $beneficiaryName = $item->beneficiary_name ?? $item['beneficiary_name'] ?? $item['name'] ?? 'N/A';
                                $beneficiaryNric = $item->beneficiary_nric ?? $item['beneficiary_nric'] ?? '-';
                                $beneficiaryRelationship = $item->relationship ?? $item['relationship'] ?? 'N/A';
                                $requestedPct = $item->requested_percentage ?? $item['requested_percentage'] ?? 0;
                                $wasAmount = ($requestedPct / 100) * $netEstate;
                                $isCharity = $item->is_charity ?? $item['is_charity'] ?? false;
                            ?>
                            <tr>
                                <td><strong><?php echo e($beneficiaryName); ?></strong></td>
                                <td><?php echo e($beneficiaryNric); ?></td>
                                <td><?php echo e($beneficiaryRelationship); ?></td>
                                <td style="text-align: center;"><?php echo e(number_format($requestedPct, 2)); ?>%</td>
                                <td style="text-align: right; font-weight: 600; color: var(--warning-dark);">RM <?php echo e(number_format($wasAmount, 2)); ?></td>
                                <td>
                                    <?php if($isCharity): ?>
                                        <span class="badge badge-success">Charity</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Individual</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--gray-100);">
                            <td colspan="3"><strong>Total Wasiyyah</strong></td>
                            <td style="text-align: center;"><strong><?php echo e(number_format($totalWasiyyahPct, 2)); ?>%</strong></td>
                            <td style="text-align: right;"><strong style="color: var(--warning-dark);">RM <?php echo e(number_format($wasiyyahAmount, 2)); ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- HEIRS DISTRIBUTION (FARAID) -->
    <?php if(count($heirs) > 0): ?>
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            <h2 class="card-title">Heirs Distribution (Faraid)</h2>
            <span class="card-badge badge-heir"><?php echo e(count($heirs)); ?> Heir<?php echo e(count($heirs) !== 1 ? 's' : ''); ?></span>
        </div>
        <div class="card-body">
            <div class="info-card">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>Distribution based on <strong>Islamic Faraid principles</strong> as prescribed in the Quran (Surah An-Nisa, verses 11-12 and 176) and the Sunnah.</p>
            </div>
            
            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Heir Name</th>
                            <th>NRIC</th>
                            <th>Relationship</th>
                            <th>Share Fraction</th>
                            <th>Percentage</th>
                            <th>Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $heirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $heirName = $heir->name ?? $heir['name'] ?? 'N/A';
                                $heirNric = $heir->nric ?? $heir['nric'] ?? '-';
                                $heirRelationship = $heir->relationship ?? $heir['relationship'] ?? 'N/A';
                                $heirShareFraction = $heir->share_fraction ?? $heir['share_fraction'] ?? ($heir['share'] ?? null);
                                $heirSharePercentage = $heir->share_percentage ?? $heir['share_percentage'] ?? 0;
                                $heirShareAmount = ($heirSharePercentage / 100) * $remainingForHeirs;
                            ?>
                            <tr>
                                <td><span class="badge badge-heir">Heir</span> <strong><?php echo e($heirName); ?></strong></td>
                                <td><?php echo e($heirNric); ?></td>
                                <td><?php echo e(ucfirst(str_replace('_', ' ', $heirRelationship))); ?></td>
                                <td style="text-align: center;"><?php echo e($heirShareFraction ?: number_format($heirSharePercentage, 2) . '%'); ?></td>
                                <td style="text-align: center; font-weight: 600; color: var(--primary-color);"><?php echo e(number_format($heirSharePercentage, 2)); ?>%</td>
                                <td style="text-align: right; font-weight: 700; color: var(--primary-color);">RM <?php echo e(number_format($heirShareAmount, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--primary-light);">
                            <td colspan="4"><strong>Total Distribution</strong></td>
                            <td style="text-align: center;"><strong style="color: <?php echo e(abs($totalHeirPct - 100) < 0.01 ? 'var(--success-color)' : 'var(--danger-color)'); ?>;"><?php echo e(number_format($totalHeirPct, 2)); ?>%</strong></td>
                            <td style="text-align: right;"><strong style="color: var(--primary-color);">RM <?php echo e(number_format($remainingForHeirs, 2)); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- TRUSTEE INFORMATION -->
    <?php if($trusteeName): ?>
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <h2 class="card-title">Trustee Information</h2>
            <span class="card-badge">Appointed Trustee</span>
        </div>
        <div class="card-body">
            <div class="grid-2">
                <div>
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Trustee Name:</strong><br>
                    <span style="font-size: 1.125rem; font-weight: 600;"><?php echo e($trusteeName); ?></span></p>
                    
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Relationship:</strong><br>
                    <span style="font-weight: 500;"><?php echo e($trusteeRelationship); ?></span></p>
                    
                    <?php if($trusteeNric): ?>
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">NRIC/Passport:</strong><br>
                    <span style="font-weight: 500;"><?php echo e($trusteeNric); ?></span></p>
                    <?php endif; ?>
                </div>
                <div>
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Contact Email:</strong><br>
                    <span style="font-weight: 500;"><?php echo e($trusteeEmail ?? 'N/A'); ?></span></p>
                    
                    <?php if($trusteePhone): ?>
                    <p style="margin-bottom: 0.75rem;"><strong style="color: var(--gray-600);">Contact Phone:</strong><br>
                    <span style="font-weight: 500;"><?php echo e($trusteePhone); ?></span></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- SHARIAH COMPLIANCE -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="card-title">Shariah Compliance</h2>
            <span class="card-badge success">Verified</span>
        </div>
        <div class="card-body">
            <div class="info-card" style="border-left-color: var(--success-color); background: linear-gradient(135deg, var(--white) 0%, var(--success-light) 100%);">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--success-dark);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <strong style="color: var(--success-dark);">Faraid Compliant</strong>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem;">This inheritance distribution has been calculated in accordance with Islamic Faraid law as prescribed in the Quran (Surah An-Nisa, verses 11-12 and 176) and the Sunnah.</p>
                </div>
            </div>
            
            <div class="disclaimer-card">
                <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0;">
                    <strong>Disclaimer:</strong> This document is a computer-generated inheritance distribution report based on 
                    Islamic Faraid law. It is intended for informational purposes only and should not be considered as legal advice.
                </p>
            </div>
        </div>
    </div>
    
    <!-- ACTION BUTTONS - Aligned with access-view.blade.php -->
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

    <!-- SECURITY NOTICE - Matching access-view.blade.php -->
    <div class="security-notice no-print">
        <div class="security-notice-content">
            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2.5 5.5A2.5 2.5 0 015 3h10a2.5 2.5 0 012.5 2.5v9a2.5 2.5 0 01-2.5 2.5H5a2.5 2.5 0 01-2.5-2.5v-9zM6 6.5v1a1 1 0 001 1h6a1 1 0 001-1v-1a1 1 0 00-1-1H7a1 1 0 00-1 1z" clip-rule="evenodd"/>
            </svg>
            <span>This is a secure connection. Your access has been logged for security purposes.</span>
            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>
            <span>This link expires on <strong><?php echo e($expiryDate); ?></strong>.</span>
        </div>
    </div>
    
    <!-- FOOTER -->
    <div style="text-align: center; padding: 2rem 0; color: var(--gray-500); font-size: 0.8125rem;">
        <p style="margin-bottom: 0.25rem;">This document is generated by <strong style="color: var(--primary-color);">Neo Faraid</strong> - Islamic Inheritance Calculator.</p>
        <p style="margin-bottom: 0.25rem;">Generated on: <strong><?php echo e(now()->format('d F Y, h:i:s A')); ?></strong></p>
        <p style="margin-bottom: 0;">Document ID: <strong style="color: var(--primary-color);"><?php echo e($documentId); ?></strong></p>
    </div>
</div>

<!-- HIDDEN PDF CONTENT - For PDF generation WITHOUT header title elements -->
<div id="pdfContent" style="display:none;">
    <div style="padding:20px;font-family:'Poppins',sans-serif;max-width:800px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #1a5fb4;padding-bottom:15px;">
            <h1 style="color:#1a5fb4;font-size:22px;margin:0 0 5px 0;">Estate Distribution Statement</h1>
            <p style="color:#64748b;font-size:12px;margin:0;">Reference: <?php echo e($documentId); ?> | Status: <?php echo e(ucfirst($estateStatus)); ?> | Generated: <?php echo e(now()->format('d M Y')); ?></p>
        </div>

        <!-- Deceased Information -->
        <div class="pdf-section-title">Deceased Information</div>
        <table class="pdf-info-table">
            <tr><td><strong>Name</strong></td><td><?php echo e($deceasedName); ?></td></tr>
            <tr><td><strong>NRIC/Passport</strong></td><td><?php echo e($deceasedNric); ?></td></tr>
            <tr><td><strong>Gender</strong></td><td><?php echo e(ucfirst($deceasedGender)); ?></td></tr>
            <tr><td><strong>Date of Birth</strong></td><td><?php echo e($deceasedDob); ?></td></tr>
            <tr><td><strong>Date of Death</strong></td><td><?php echo e($dateOfDeath); ?></td></tr>
            <tr><td><strong>Address</strong></td><td><?php echo e($deceasedAddress); ?></td></tr>
        </table>

        <!-- Financial Summary -->
        <div class="pdf-section-title">Financial Summary</div>
        <div style="display:flex;gap:15px;margin-bottom:15px;">
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366;font-size:16px;">RM <?php echo e(number_format($totalAssets, 2)); ?></span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545;font-size:16px;">RM <?php echo e(number_format($totalDebts, 2)); ?></span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4;font-size:16px;"><?php echo e($formattedNetEstate); ?></span></div>
        </div>

        <?php if(count($assetsList) > 0): ?>
            <div class="pdf-section-title">Assets (<?php echo e(count($assetsList)); ?>)</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Asset Name</strong></th><th><strong>Value</strong></th><th><strong>Ownership</strong></th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $assetsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($asset['name']); ?></td><td>RM <?php echo e(number_format($asset['value'], 2)); ?></td><td><?php echo e($asset['ownership_percentage']); ?>%</td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="1">Total Assets</td><td colspan="2">RM <?php echo e(number_format($totalAssets, 2)); ?></td></tr>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if(count($debtsList) > 0): ?>
            <div class="pdf-section-title">Debts (<?php echo e(count($debtsList)); ?>)</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Creditor</strong></th><th><strong>Type</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $debtsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($debt['creditor_name']); ?></td><td><?php echo e($debt['debt_type']); ?></td><td>RM <?php echo e(number_format($debt['amount'], 2)); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total Debts</td><td>RM <?php echo e(number_format($totalDebts, 2)); ?></td></tr>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if(count($heirsList) > 0): ?>
            <div class="pdf-section-title">Faraid Heirs Distribution</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Heir Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share %</strong></th><th><strong>Amount (RM)</strong></th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $heirsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $heirAmount = ($heir['percentage'] / 100) * $remainingForHeirs;
                    ?>
                    <tr>
                        <td><?php echo e($heir['name']); ?></td>
                        <td><?php echo e(ucfirst(str_replace('_', ' ', $heir['relationship']))); ?></td>
                        <td><?php echo e(number_format($heir['percentage'], 2)); ?>%</td>
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

        <?php if(count($wasiyyahList) > 0): ?>
            <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Beneficiary Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share %</strong></th><th><strong>Amount (RM)</strong></th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $wasiyyahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $wasAmount = ($item['percentage'] / 100) * $netEstate;
                    ?>
                    <tr>
                        <td><?php echo e($item['name']); ?></td>
                        <td><?php echo e(ucfirst($item['relationship'])); ?></td>
                        <td><?php echo e(number_format($item['percentage'], 2)); ?>%</td>
                        <td>RM <?php echo e(number_format($wasAmount, 2)); ?></td>
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

        <?php if($trusteeName): ?>
            <div class="pdf-section-title">Trustee Information</div>
            <table class="pdf-info-table">
                <tr><td><strong>Trustee Name</strong></td><td><?php echo e($trusteeName); ?></td></tr>
                <tr><td><strong>Relationship</strong></td><td><?php echo e($trusteeRelationship); ?></td></tr>
                <?php if($trusteeNric): ?><tr><td><strong>NRIC/Passport</strong></td><td><?php echo e($trusteeNric); ?></td></tr><?php endif; ?>
                <tr><td><strong>Email</strong></td><td><?php echo e($trusteeEmail ?? 'Not provided'); ?></td></tr>
                <?php if($trusteePhone): ?><tr><td><strong>Phone</strong></td><td><?php echo e($trusteePhone); ?></td></tr><?php endif; ?>
            </table>
        <?php endif; ?>

        <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:10px;color:#94a3b8;text-align:center;">
            <p>This is an official estate distribution statement. Generated on <?php echo e(now()->format('d F Y, h:i A')); ?> | Document ID: <?php echo e($documentId); ?></p>
            <p>This document is computer-generated and does not require a signature.</p>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Auto-print on load if requested via URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        setTimeout(function() {
            window.print();
        }, 1000);
    }
})();

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
    
    // Create a clean PDF with only the essential content - NO header title elements
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Estate Distribution Statement - <?php echo e($deceasedName); ?></title>
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
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/instant-estate/report-estate-plan.blade.php ENDPATH**/ ?>