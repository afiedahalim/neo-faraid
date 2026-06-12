<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Inheritance Distribution Details</title>
    <style>
        /* ===== INHERIT FROM estate-setup/index.blade.php STYLES ===== */
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
            --warning-light: #fff3cd;
            --info-color: #17a2b8;
            --info-light: #d1ecf1;
            --dark: #1a1a2e;
            --light-bg: #f8f9fa;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --text-primary: #495057;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
            --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.15);
            --border-radius-sm: 12px;
            --border-radius-md: 15px;
            --border-radius-lg: 20px;
            --border-radius-xl: 50px;
            --transition: all 0.3s ease;
        }

        * {
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif !important;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--text-primary);
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header similar to estate-setup */
        .estate-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .estate-header h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .estate-header p {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        /* Glass card styling */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(255, 255, 255, 0.8) 100%);
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header-icon {
            width: 28px;
            height: 28px;
            color: var(--primary-color);
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
            flex: 1;
        }

        .card-badge {
            padding: 0.4rem 0.9rem;
            background: var(--primary-light);
            color: var(--primary-color);
            border-radius: var(--border-radius-md);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.75rem;
        }

        /* Info box for distribution details */
        .info-box {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--white) 100%);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            border-radius: var(--border-radius-md);
            margin: 1.5rem 0;
        }

        .info-box p {
            margin: 0.5rem 0;
            font-size: 1rem;
        }

        .info-box strong {
            color: var(--primary-dark);
        }

        .amount {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--success-color);
            margin: 0.5rem 0;
        }

        /* Summary panel like in estate-setup */
        .summary-panel {
            background: var(--white);
            border-radius: var(--border-radius-md);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            margin: 1rem 0;
        }

        .summary-panel-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0.9rem 1.25rem;
        }

        .summary-panel-header h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        .summary-panel-body {
            padding: 1.25rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
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

        .summary-total {
            background: var(--primary-light);
            margin-top: 0.8rem;
            padding: 0.6rem;
            border-radius: var(--border-radius-sm);
        }

        /* Table styling */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .results-table th,
        .results-table td {
            padding: 0.6rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }

        .results-table th {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
        }

        /* Video and attachments */
        .video-info, .attachments, .note-box {
            background: var(--gray-50);
            border-radius: var(--border-radius-md);
            padding: 1rem 1.25rem;
            margin: 1rem 0;
            border-left: 4px solid;
        }

        .video-info { border-left-color: var(--warning-color); background: var(--warning-light); }
        .attachments { border-left-color: var(--info-color); background: var(--info-light); }
        .note-box { border-left-color: var(--success-color); background: var(--success-light); }

        /* Button styling */
        .btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 95, 180, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 95, 180, 0.4);
        }

        .btn-secondary {
            background: var(--warning-color);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-pdf {
            background: var(--danger-color);
            color: white;
        }

        .btn-pdf:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .button-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 1.5rem 0;
        }

        /* PDF Content Hidden (same as estate-setup) */
        #pdfContent {
            display: none;
        }

        .pdf-section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1a5fb4;
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e8f1fd;
        }

        .pdf-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            font-size: 0.75rem;
        }

        .pdf-info-table td {
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .pdf-info-table td:first-child {
            font-weight: 600;
            color: #475569;
            width: 30%;
            white-space: nowrap;
        }

        .pdf-info-card {
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border-left: 3px solid #1a5fb4;
            font-size: 0.75rem;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: var(--border-radius-md);
            font-size: 0.7rem;
            color: var(--gray-500);
            margin-top: 2rem;
        }

        hr {
            margin: 1rem 0;
            border: none;
            border-top: 1px solid var(--gray-200);
        }

        @media (max-width: 640px) {
            body { padding: 10px; }
            .card-body { padding: 1rem; }
            .button-group { flex-direction: column; }
            .btn { justify-content: center; }
            .summary-item { flex-direction: column; gap: 0.25rem; }
        }

        @media print {
            body { background: white; padding: 0; }
            .glass-card { box-shadow: none; border: 1px solid #ddd; break-inside: avoid; }
            .btn, .button-group, .no-print, .video-info .button-group { display: none !important; }
            .estate-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: #1a5fb4; }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Header matching estate-setup style -->
    <div class="estate-header">
        <h1>Your Inheritance Distribution Details</h1>
        <p>Estate of {{ $deceasedName ?? 'Deceased' }} | Reference: {{ $estate->unique_id ?? 'N/A' }}</p>
    </div>

    <!-- Main Distribution Card -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="card-title">Your Distribution Details</h2>
            <span class="card-badge">{{ ucfirst($beneficiaryType ?? 'Heir') }}</span>
        </div>
        <div class="card-body">
            <div class="greeting">
                <p>Dear <strong>{{ $beneficiaryName ?? 'Beneficiary' }}</strong>,</p>
                <p style="margin-top: 0.5rem;">You have been named as an <strong>{{ strtoupper($beneficiaryType ?? 'HEIR') }}</strong> in the estate of <strong>{{ $deceasedName }}</strong>.</p>
            </div>

            <!-- Critical: Distribution Details Box -->
            <div class="info-box">
                <p><strong>📋 Your Distribution Details:</strong></p>
                <p><strong>Your Name:</strong> {{ $beneficiaryName ?? 'N/A' }}</p>
                <p><strong>Relationship to Deceased:</strong> {{ $relationship ?? ($beneficiaryType === 'Wasiyyah' ? 'Wasiyyah Beneficiary' : 'Faraid Heir') }}</p>
                <p><strong>Share Percentage:</strong> <strong>{{ number_format($sharePercentage ?? 0, 2) }}%</strong></p>
                <p><strong>Your Inheritance Amount:</strong> <span class="amount">{{ $formattedAmount ?? 'RM 0.00' }}</span></p>
            </div>

            <!-- Video Section if available -->
            @if(isset($hasVideo) && $hasVideo && isset($estate) && $estate->will_video_url)
            <div class="video-info">
                <p><strong>📹 Wasiyyah Video Available</strong></p>
                <p>The deceased has recorded a will video. Click the button below to view it.</p>
                <div class="button-group">
                    <a href="{{ $estate->will_video_url }}" class="btn btn-secondary" target="_blank">▶ Watch Will Video</a>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="button-group">
                <a href="{{ $accessUrl ?? '#' }}" class="btn btn-primary">📊 View Full Dashboard</a>
                @if(isset($estate) && $estate->will_video_url)
                    <a href="{{ $estate->will_video_url }}" class="btn btn-secondary" target="_blank">▶ Watch Wasiyyah Video</a>
                @endif
                <button class="btn btn-pdf" onclick="downloadPDF()">📄 Download PDF Statement</button>
            </div>

            <!-- Attachments Section -->
            <div class="attachments">
                <p><strong>📎 Attached Documents:</strong></p>
                <p>✓ <strong>PDF Estate Distribution Statement</strong> - Complete distribution breakdown (attached)</p>
                @if(isset($hasVideo) && $hasVideo)
                    <p>✓ <strong>Will Video Recording</strong> - Final wishes of the deceased (attached)</p>
                @endif
            </div>

            <!-- Important Notes -->
            <div class="note-box">
                <p><strong>📌 Important Notes:</strong></p>
                <p>• This link is for your personal use only. Please do not share it with others.</p>
                <p>• The link will expire on <strong>{{ $expiryDate ?? '30 days' }}</strong>.</p>
                <p>• For security purposes, each access is logged and monitored.</p>
                <p>• Please save the attached PDF document for your personal records.</p>
            </div>

            <!-- Summary Panels (matching estate-setup style) -->
            <div class="summary-panel">
                <div class="summary-panel-header">
                    <h3>Estate Financial Summary</h3>
                </div>
                <div class="summary-panel-body">
                    <div class="summary-item">
                        <span class="summary-label">Total Assets</span>
                        <span class="summary-value positive">RM {{ number_format($totalAssets ?? 0, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Debts</span>
                        <span class="summary-value negative">RM {{ number_format($totalDebts ?? 0, 2) }}</span>
                    </div>
                    <div class="summary-total">
                        <div class="summary-item">
                            <span class="summary-label">Net Estate Value</span>
                            <span class="summary-value">{{ $formattedNetEstate ?? 'RM 0.00' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <p>Best regards,<br><strong>Neo Faraid Team</strong></p>
        </div>
    </div>

    <!-- Full Distribution Breakdown (Heirs + Wasiyyah) similar to Review Section -->
    <div class="glass-card">
        <div class="card-header">
            <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            <h2 class="card-title">Complete Inheritance Distribution</h2>
            <span class="card-badge">Faraid & Wasiyyah</span>
        </div>
        <div class="card-body">
            <!-- Faraid Heirs Table -->
            <h3 style="font-size: 1rem; margin-bottom: 1rem; color: var(--primary-dark);">Faraid Heirs Distribution</h3>
            <div class="results-container" style="overflow-x: auto;">
                <table class="results-table">
                    <thead>
                        <tr><th>Heir Name</th><th>Relationship</th><th>Share (%)</th><th>Amount (RM)</th></tr>
                    </thead>
                    <tbody id="heirsTableBody">
                        @forelse($heirsList ?? [] as $heir)
                        <tr><td>{{ $heir['name'] ?? 'N/A' }}</td><td>{{ $heir['relationship'] ?? '-' }}</td><td><strong>{{ number_format($heir['percentage'] ?? 0, 2) }}%</strong></td><td>RM {{ number_format($heir['amount'] ?? 0, 2) }}</td></tr>
                        @empty
                        <tr><td colspan="4" style="text-align: center;">No heirs data available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Wasiyyah Beneficiaries Table -->
            <h3 style="font-size: 1rem; margin: 1.5rem 0 1rem; color: var(--primary-dark);">Wasiyyah Beneficiaries</h3>
            <div class="results-container" style="overflow-x: auto;">
                <table class="results-table">
                    <thead>
                        <tr><th>Name</th><th>Relationship</th><th>Share (%)</th><th>Amount (RM)</th></tr>
                    </thead>
                    <tbody id="wasiyyahTableBody">
                        @forelse($wasiyyahList ?? [] as $wasiyyah)
                        <tr><td>{{ $wasiyyah['name'] ?? 'N/A' }}</td><td>{{ $wasiyyah['relationship'] ?? '-' }}</td><td><strong>{{ number_format($wasiyyah['percentage'] ?? 0, 2) }}%</strong></td><td>RM {{ number_format($wasiyyah['amount'] ?? 0, 2) }}</td></tr>
                        @empty
                        <tr><td colspan="4" style="text-align: center;">No wasiyyah beneficiaries.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Trustee Info -->
            <div class="summary-panel" style="margin-top: 1.5rem;">
                <div class="summary-panel-header">
                    <h3>Appointed Trustee</h3>
                </div>
                <div class="summary-panel-body">
                    <div class="summary-item"><span class="summary-label">Trustee Name</span><span class="summary-value">{{ $trusteeName ?? 'Not appointed' }}</span></div>
                    <div class="summary-item"><span class="summary-label">Trustee Email</span><span class="summary-value">{{ $trusteeEmail ?? '-' }}</span></div>
                    <div class="summary-item"><span class="summary-label">Trustee Phone</span><span class="summary-value">{{ $trusteePhone ?? '-' }}</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated message from the Neo Faraid System. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} Neo Faraid. All rights reserved.</p>
        <p>Secure Estate Distribution Platform | Shariah-Compliant | Faraid Certified</p>
    </div>
</div>

<!-- ===== HIDDEN PDF CONTENT (IDENTICAL TO estate-setup/index.blade.php) ===== -->
<div id="pdfContent" style="display:none;">
    <div style="padding:20px;font-family:'Poppins',sans-serif;max-width:800px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #1a5fb4;padding-bottom:15px;">
            <h1 style="color:#1a5fb4;font-size:22px;margin:0 0 5px 0;">Estate Planning Document</h1>
            <p style="color:#64748b;font-size:12px;margin:0;">Reference: {{ $estate->unique_id ?? 'N/A' }} | Status: {{ $estate->status_label ?? 'Active' }} | Generated: {{ now()->format('d M Y') }}</p>
        </div>

        <div class="pdf-section-title">Personal Information</div>
        <table class="pdf-info-table">
            <tr><td>Name</td><td><strong>{{ $deceasedName ?? 'N/A' }}</strong></td></tr>
            <tr><td>NRIC</td><td>{{ $deceasedNric ?? 'N/A' }}</td></tr>
            <tr><td>Contact</td><td>{{ $deceasedEmail ?? 'N/A' }} | {{ $deceasedPhone ?? 'N/A' }}</td></tr>
            <tr><td>Trustee</td><td>{{ $trusteeName ?? 'Not appointed' }} ({{ $trusteeEmail ?? 'N/A' }})</td></tr>
        </table>

        <div class="pdf-section-title">Financial Summary</div>
        <div style="display:flex;gap:15px;margin-bottom:15px;">
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366;font-size:16px;">RM {{ number_format($totalAssets ?? 0, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545;font-size:16px;">RM {{ number_format($totalDebts ?? 0, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4;font-size:16px;">{{ $formattedNetEstate ?? 'RM 0.00' }}</span></div>
        </div>

        <div class="pdf-section-title">Faraid Heirs Distribution</div>
        @if(isset($heirsList) && count($heirsList) > 0)
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @foreach($heirsList as $heir)
                    <tr><td>{{ $heir['name'] }}</td><td>{{ $heir['relationship'] }}</td><td><strong>{{ number_format($heir['percentage'], 2) }}%</strong></td><td>RM {{ number_format($heir['amount'], 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color:#94a3b8;font-size:12px;">No heirs registered.</p>
        @endif

        @if(isset($wasiyyahList) && count($wasiyyahList) > 0)
            <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @foreach($wasiyyahList as $wasi)
                    <tr><td>{{ $wasi['name'] }}</td><td>{{ $wasi['relationship'] }}</td><td><strong>{{ number_format($wasi['percentage'], 2) }}%</strong></td><td>RM {{ number_format($wasi['amount'], 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:10px;color:#94a3b8;text-align:center;">
            <p>This is a system-generated document. Generated on {{ now()->format('d F Y, h:i A') }} | Document ID: {{ $estate->unique_id ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<script>
    // ===== PDF DOWNLOAD FUNCTION (same as estate-setup) =====
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
                <title>Estate Plan - {{ $deceasedName ?? 'PDF' }}</title>
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
    }
</script>
</body>
</html>