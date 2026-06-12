<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trustee Appointment Notification</title>
    <style>
        /* ===== BASE STYLES - ALIGNED WITH ESTATE-SETUP ===== */
        :root {
            --primary-color: #1a5fb4;
            --primary-dark: #0d2d5c;
            --primary-light: #e8f1fd;
            --secondary-color: #2d7ad6;
            --accent-color: #ffd700;
            --success-color: #25D366;
            --success-dark: #128C7E;
            --success-light: #d4edda;
            --danger-color: #dc3545;
            --danger-dark: #c82333;
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
            --gray-900: #0f172a;
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

        .email-container {
            max-width: 700px;
            margin: 0 auto;
            background: var(--white);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        /* ===== HEADER SECTION - MATCHES ESTATE HEADER ===== */
        .email-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: var(--white);
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
            opacity: 0.5;
            pointer-events: none;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 2;
        }

        .email-header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
            position: relative;
            z-index: 2;
        }

        /* ===== CONTENT SECTION ===== */
        .email-content {
            padding: 30px;
        }

        /* ===== GREETING ===== */
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .greeting strong {
            color: var(--primary-color);
        }

        /* ===== INFO BOXES ===== */
        .info-box {
            background: var(--gray-50);
            border-left: 4px solid var(--primary-color);
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .responsibilities {
            background: linear-gradient(135deg, #e8f4f8 0%, #d4eaf1 100%);
            padding: 15px 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid rgba(26, 95, 180, 0.1);
        }

        .responsibilities ul {
            margin: 10px 0 0 20px;
        }

        .responsibilities li {
            margin: 5px 0;
        }

        /* ===== DISTRIBUTION DASHBOARD - KEY ADDITION ===== */
        .distribution-dashboard {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--white) 100%);
            border-radius: var(--border-radius-md);
            margin: 20px 0;
            overflow: hidden;
            border: 1px solid rgba(26, 95, 180, 0.15);
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dashboard-header svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
        }

        .dashboard-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .distribution-card {
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .distribution-card:last-child {
            border-bottom: none;
        }

        .distribution-card:hover {
            background: rgba(26, 95, 180, 0.03);
        }

        .beneficiary-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .beneficiary-name .badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-heir {
            background: var(--primary-light);
            color: var(--primary-color);
        }

        .badge-wasiyyah {
            background: var(--warning-light);
            color: #b76e00;
        }

        .distribution-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .detail-item {
            background: var(--white);
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .detail-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }

        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: var(--gray-500);
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .detail-value.amount {
            color: var(--success-color);
        }

        .relationship-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
        }

        /* ===== VIDEO SECTION ===== */
        .video-info {
            background: var(--warning-light);
            border-left: 4px solid var(--warning-color);
            padding: 15px 20px;
            border-radius: 12px;
            margin: 20px 0;
        }

        /* ===== BUTTONS ===== */
        .button-group {
            text-align: center;
            margin: 25px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            margin: 0 5px;
            transition: var(--transition);
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(26, 95, 180, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 95, 180, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #ff4757 0%, #e84118 100%);
            color: var(--white);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(232, 65, 24, 0.3);
        }

        .btn-pdf {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: var(--white);
        }

        /* ===== ATTACHMENTS SECTION ===== */
        .attachments {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
            padding: 15px 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid var(--gray-200);
        }

        .attachments p {
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== NOTE BOX ===== */
        .note-box {
            background: var(--warning-light);
            border-left: 4px solid var(--warning-color);
            padding: 15px 20px;
            border-radius: 12px;
            margin: 20px 0;
        }

        .note-box p {
            margin: 8px 0;
        }

        /* ===== SUMMARY SECTION (FOR PDF) ===== */
        .summary-section {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-light);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            text-align: center;
        }

        .summary-card {
            background: var(--white);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        .summary-card-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-card-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary-color);
            margin-top: 5px;
        }

        /* ===== DIVIDER ===== */
        hr {
            border: none;
            border-top: 1px solid var(--gray-200);
            margin: 20px 0;
        }

        /* ===== FOOTER ===== */
        .email-footer {
            text-align: center;
            padding: 20px;
            background: var(--gray-50);
            font-size: 11px;
            color: var(--gray-500);
            border-top: 1px solid var(--gray-200);
        }

        /* ===== PDF HIDDEN CONTENT - MATCHES ESTATE-SETUP ===== */
        #pdfContent {
            display: none;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .email-content { padding: 20px; }
            .btn { display: block; margin: 10px 0; }
            .button-group { display: flex; flex-direction: column; gap: 10px; }
            .distribution-details { grid-template-columns: 1fr; }
            .summary-grid { grid-template-columns: 1fr; }
        }

        @media print {
            body { background: white; padding: 0; }
            .email-container { box-shadow: none; margin: 0; max-width: 100%; }
            .btn, .button-group, .video-info, .attachments, .note-box { display: none !important; }
            .distribution-dashboard { break-inside: avoid; }
            .distribution-card { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- HEADER - MATCHES ESTATE HEADER STYLE -->
        <div class="email-header">
            <h1>Trustee Appointment Notification</h1>
            <p>Estate of {{ $deceasedName ?? $estate->deceased_name ?? 'Deceased' }}</p>
        </div>

        <div class="email-content">
            <!-- GREETING -->
            <div class="greeting">
                <p>Dear <strong>{{ $beneficiaryName ?? $trusteeName ?? $estate->trustee_name ?? 'Trustee' }}</strong>,</p>
            </div>

            <p>You have been appointed as the <strong>TRUSTEE</strong> for the estate of <strong>{{ $deceasedName ?? $estate->deceased_name ?? 'the deceased' }}</strong>.</p>

            <!-- TRUSTEE RESPONSIBILITIES -->
            <div class="responsibilities">
                <p><strong>📋 Your Responsibilities:</strong></p>
                <ul>
                    <li>Review the estate distribution plan</li>
                    <li>Ensure all debts are settled from the estate</li>
                    <li>Oversee the distribution to heirs and wasiyyah beneficiaries</li>
                    <li>Ensure compliance with Shariah principles</li>
                </ul>
            </div>

            <!-- ===== DISTRIBUTION DASHBOARD - EACH HEIR & WASIYYAH RECIPIENT SEES THEIR DETAILS ===== -->
            <div class="distribution-dashboard">
                <div class="dashboard-header">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3>Your Distribution Details</h3>
                </div>

                @if(isset($beneficiaryType) && $beneficiaryType === 'heir')
                    {{-- HEIR DISTRIBUTION DETAILS --}}
                    @php
                        $netEstate = $estate->netEstate ?? ($estate->assets->sum('value') - $estate->debts->sum('amount'));
                        $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage') ?? 0;
                        $remainingForHeirs = $netEstate - (($totalWasiyyahPct / 100) * $netEstate);
                        $heirAmount = (($heir->share_percentage ?? 0) / 100) * max(0, $remainingForHeirs);
                    @endphp
                    <div class="distribution-card">
                        <div class="beneficiary-name">
                            {{ $heir->name ?? $beneficiaryName ?? 'N/A' }}
                            <span class="badge badge-heir">Faraid Heir</span>
                        </div>
                        <div class="distribution-details">
                            <div class="detail-item">
                                <div class="detail-label">Relationship to Deceased</div>
                                <div class="relationship-value">{{ ucfirst(str_replace('_', ' ', $heir->relationship ?? $relationship ?? 'N/A')) }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Share Percentage</div>
                                <div class="detail-value">{{ number_format($heir->share_percentage ?? $sharePercentage ?? 0, 2) }}%</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Your Inheritance Amount</div>
                                <div class="detail-value amount">RM {{ number_format($heirAmount, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    @if(isset($heir->nric) || isset($heir->email) || isset($heir->phone))
                        <div style="padding: 0 20px 20px 20px;">
                            <div style="background: var(--gray-50); padding: 12px 15px; border-radius: 10px;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; font-size: 13px;">
                                    @if(isset($heir->nric))
                                    <div><strong>NRIC:</strong> {{ $heir->nric }}</div>
                                    @endif
                                    @if(isset($heir->email))
                                    <div><strong>Email:</strong> {{ $heir->email }}</div>
                                    @endif
                                    @if(isset($heir->phone))
                                    <div><strong>Phone:</strong> {{ $heir->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                @elseif(isset($beneficiaryType) && $beneficiaryType === 'wasiyyah')
                    {{-- WASIYYAH BENEFICIARY DISTRIBUTION DETAILS --}}
                    @php
                        $netEstate = $estate->netEstate ?? ($estate->assets->sum('value') - $estate->debts->sum('amount'));
                        $wasiyyahAmount = (($wasiyyah->requested_percentage ?? $sharePercentage ?? 0) / 100) * $netEstate;
                    @endphp
                    <div class="distribution-card">
                        <div class="beneficiary-name">
                            {{ $wasiyyah->beneficiary_name ?? $beneficiaryName ?? 'N/A' }}
                            <span class="badge badge-wasiyyah">Wasiyyah Beneficiary</span>
                        </div>
                        <div class="distribution-details">
                            <div class="detail-item">
                                <div class="detail-label">Relationship to Deceased</div>
                                <div class="relationship-value">{{ $wasiyyah->relationship ?? $relationship ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Share Percentage</div>
                                <div class="detail-value">{{ number_format($wasiyyah->requested_percentage ?? $sharePercentage ?? 0, 2) }}%</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Your Inheritance Amount</div>
                                <div class="detail-value amount">RM {{ number_format($wasiyyahAmount, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    @if(isset($wasiyyah->beneficiary_nric) || isset($wasiyyah->beneficiary_email) || isset($wasiyyah->beneficiary_phone))
                        <div style="padding: 0 20px 20px 20px;">
                            <div style="background: var(--gray-50); padding: 12px 15px; border-radius: 10px;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; font-size: 13px;">
                                    @if(isset($wasiyyah->beneficiary_nric))
                                    <div><strong>NRIC:</strong> {{ $wasiyyah->beneficiary_nric }}</div>
                                    @endif
                                    @if(isset($wasiyyah->beneficiary_email))
                                    <div><strong>Email:</strong> {{ $wasiyyah->beneficiary_email }}</div>
                                    @endif
                                    @if(isset($wasiyyah->beneficiary_phone))
                                    <div><strong>Phone:</strong> {{ $wasiyyah->beneficiary_phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                @else
                    {{-- FALLBACK / TRUSTEE VIEW - SHOW ALL BENEFICIARIES --}}
                    @php
                        $netEstate = $estate->netEstate ?? ($estate->assets->sum('value') - $estate->debts->sum('amount'));
                        $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage') ?? 0;
                        $remainingForHeirs = $netEstate - (($totalWasiyyahPct / 100) * max(0, $netEstate));
                    @endphp

                    {{-- HEIRS SECTION --}}
                    @if(isset($estate->heirs) && $estate->heirs->count() > 0)
                        @foreach($estate->heirs as $heirItem)
                            @php
                                $heirAmountCalc = (($heirItem->share_percentage ?? 0) / 100) * max(0, $remainingForHeirs);
                            @endphp
                            <div class="distribution-card">
                                <div class="beneficiary-name">
                                    {{ $heirItem->name }}
                                    <span class="badge badge-heir">Faraid Heir</span>
                                </div>
                                <div class="distribution-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Relationship to Deceased</div>
                                        <div class="relationship-value">{{ ucfirst(str_replace('_', ' ', $heirItem->relationship)) }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Share Percentage</div>
                                        <div class="detail-value">{{ number_format($heirItem->share_percentage ?? 0, 2) }}%</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Inheritance Amount</div>
                                        <div class="detail-value amount">RM {{ number_format($heirAmountCalc, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- WASIYYAH SECTION --}}
                    @if(isset($estate->wasiyyah) && $estate->wasiyyah->count() > 0)
                        @foreach($estate->wasiyyah as $wasiyyahItem)
                            @php
                                $wasiyyahAmountCalc = (($wasiyyahItem->requested_percentage ?? 0) / 100) * max(0, $netEstate);
                            @endphp
                            <div class="distribution-card">
                                <div class="beneficiary-name">
                                    {{ $wasiyyahItem->beneficiary_name }}
                                    <span class="badge badge-wasiyyah">Wasiyyah Beneficiary</span>
                                </div>
                                <div class="distribution-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Relationship to Deceased</div>
                                        <div class="relationship-value">{{ $wasiyyahItem->relationship }}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Share Percentage</div>
                                        <div class="detail-value">{{ number_format($wasiyyahItem->requested_percentage ?? 0, 2) }}%</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Inheritance Amount</div>
                                        <div class="detail-value amount">RM {{ number_format($wasiyyahAmountCalc, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif

                {{-- ESTATE SUMMARY SECTION (for reference) --}}
                @php
                    $totalAssets = $estate->assets->sum('value') ?? 0;
                    $totalDebts = $estate->debts->sum('amount') ?? 0;
                    $calcNetEstate = max(0, $totalAssets - $totalDebts);
                    $totalHeirPct = $estate->heirs->sum('share_percentage') ?? 0;
                    $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage') ?? 0;
                    $remainingAfterWasiyyah = $calcNetEstate - (($totalWasiyyahPct / 100) * $calcNetEstate);
                @endphp
                <div class="summary-section">
                    <div class="summary-title">Estate Summary</div>
                    <div class="summary-grid">
                        <div class="summary-card">
                            <div class="summary-card-label">Total Assets</div>
                            <div class="summary-card-value">RM {{ number_format($totalAssets, 2) }}</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-card-label">Total Debts</div>
                            <div class="summary-card-value" style="color: var(--danger-color);">RM {{ number_format($totalDebts, 2) }}</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-card-label">Net Estate</div>
                            <div class="summary-card-value">RM {{ number_format($calcNetEstate, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIDEO SECTION -->
            @if(isset($hasVideo) && $hasVideo && isset($estate) && $estate->will_video_url)
            <div class="video-info">
                <p><strong>📹 Wasiyyah Video Available</strong></p>
                <p>The deceased has recorded a will video. Click the button below to view it.</p>
                <div class="button-group">
                    <a href="{{ $estate->will_video_url }}" class="btn btn-secondary" target="_blank">
                        ▶ Watch Will Video
                    </a>
                </div>
            </div>
            @endif

            <!-- ACTION BUTTONS -->
            <div class="button-group">
                <a href="{{ $accessUrl ?? '#' }}" class="btn btn-primary">📊 Access Trustee Dashboard</a>
                @if(isset($estate) && $estate->will_video_url)
                    <a href="{{ $estate->will_video_url }}" class="btn btn-secondary" target="_blank">▶ View Wasiyyah Video</a>
                @endif
            </div>

            <!-- ATTACHMENTS -->
            <div class="attachments">
                <p><strong>📎 Attached Documents:</strong></p>
                <p>✓ <strong>PDF Estate Distribution Statement</strong> - Complete distribution breakdown (attached)</p>
                @if(isset($hasVideo) && $hasVideo)
                    <p>✓ <strong>Will Video Recording</strong> - Final wishes of the deceased (attached)</p>
                @endif
            </div>

            <!-- IMPORTANT NOTES -->
            <div class="note-box">
                <p><strong>📌 Important Information:</strong></p>
                <p>• Your access link is confidential and should not be shared with others.</p>
                <p>• This link will expire on <strong>{{ $expiryDate ?? $expiry_date ?? '30 days from now' }}</strong>.</p>
                <p>• Please review all estate documents and distribution details carefully.</p>
                <p>• All trustee actions are logged for audit and compliance purposes.</p>
                <p>• Wasiyyah cannot exceed 1/3 of the net estate and cannot override faraid rights of legal heirs.</p>
            </div>

            <hr>
            <p>Thank you for accepting this important responsibility.</p>
            <p>Best regards,<br><strong>Neo Faraid Team</strong></p>
        </div>

        <div class="email-footer">
            <p>This is an automated message from the Neo Faraid System. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Neo Faraid. All rights reserved.</p>
            <p>Secure Estate Distribution Platform | Shariah-Compliant | Faraid Certified</p>
        </div>
    </div>

    <!-- ===== HIDDEN PDF CONTENT - MATCHES ESTATE-SETUP INDEX.BLADE.PHP EXACTLY ===== -->
    <div id="pdfContent" style="display:none;">
        <div style="padding:20px;font-family:'Poppins',sans-serif;max-width:800px;margin:0 auto;">
            <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #1a5fb4;padding-bottom:15px;">
                <h1 style="color:#1a5fb4;font-size:22px;margin:0 0 5px 0;">Estate Planning Document</h1>
                <p style="color:#64748b;font-size:12px;margin:0;">Reference: {{ $estate->unique_id ?? 'N/A' }} | Status: {{ $estate->status_label ?? $estate->status ?? 'N/A' }} | Generated: {{ now()->format('d M Y') }}</p>
            </div>

            <div class="pdf-section-title">Personal Information</div>
            <table class="pdf-info-table">
                <tr><td>Name</td><td><strong>{{ $estate->deceased_name ?? $deceasedName ?? 'N/A' }}</strong></td></tr>
                <tr><td>NRIC</td><td>{{ $estate->deceased_nric ?? 'N/A' }}</td></tr>
                <tr><td>Gender</td><td>{{ ucfirst($estate->gender ?? 'N/A') }}</td></tr>
                <tr><td>Date of Birth</td><td>{{ isset($estate->date_of_birth) ? $estate->date_of_birth->format('d M Y') : 'N/A' }}</td></tr>
                <tr><td>Contact</td><td>{{ $estate->contact_email ?? 'N/A' }} | {{ $estate->contact_phone ?? 'N/A' }}</td></tr>
                <tr><td>Address</td><td>{{ $estate->address ?? 'N/A' }}</td></tr>
                <tr><td>Trustee</td><td>{{ $estate->trustee_name ?? $trusteeName ?? 'Not appointed' }} ({{ $estate->trustee_email ?? 'N/A' }})</td></tr>
            </table>

            <div class="pdf-section-title">Financial Summary</div>
            <div style="display:flex;gap:15px;margin-bottom:15px;">
                <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366;font-size:16px;">RM {{ number_format($estate->assets->sum('value') ?? 0, 2) }}</span></div>
                <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545;font-size:16px;">RM {{ number_format($estate->debts->sum('amount') ?? 0, 2) }}</span></div>
                <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4;font-size:16px;">RM {{ number_format(max(0, ($estate->assets->sum('value') ?? 0) - ($estate->debts->sum('amount') ?? 0)), 2) }}</span></div>
            </div>

            <div class="pdf-section-title">Assets ({{ $estate->assets->count() ?? 0 }})</div>
            @if(isset($estate->assets) && $estate->assets->count() > 0)
                <table class="pdf-info-table">
                    <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Value</strong></th><th><strong>Ownership</strong></th><th><strong>Description</strong></th></tr></thead>
                    <tbody>
                        @foreach($estate->assets as $asset)
                        <tr>
                            <td>{{ $asset->name }}</td>
                            <td>RM {{ number_format($asset->value, 2) }}</td>
                            <td>{{ $asset->ownership_percentage ?? 100 }}%</td>
                            <td>{{ $asset->description ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:#94a3b8;font-size:12px;">No assets registered.</p>
            @endif

            <div class="pdf-section-title">Debts ({{ $estate->debts->count() ?? 0 }})</div>
            @if(isset($estate->debts) && $estate->debts->count() > 0)
                <table class="pdf-info-table">
                    <thead><tr style="background:#f8fafc;"><th><strong>Creditor</strong></th><th><strong>Type</strong></th><th><strong>Amount</strong></th><th><strong>Description</strong></th></tr></thead>
                    <tbody>
                        @foreach($estate->debts as $debt)
                        <tr>
                            <td>{{ $debt->creditor_name }}</td>
                            <td>{{ $debt->debt_type ?? 'Debt' }}</td>
                            <td>RM {{ number_format($debt->amount, 2) }}</td>
                            <td>{{ $debt->description ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:#94a3b8;font-size:12px;">No debts registered.</p>
            @endif

            <div class="pdf-section-title">Faraid Heirs Distribution</div>
            @php
                $netEstatePdf = max(0, ($estate->assets->sum('value') ?? 0) - ($estate->debts->sum('amount') ?? 0));
                $totalWasiyyahPctPdf = $estate->wasiyyah->sum('requested_percentage') ?? 0;
                $remainingForHeirsPdf = $netEstatePdf - (($totalWasiyyahPctPdf / 100) * $netEstatePdf);
            @endphp
            @if(isset($estate->heirs) && $estate->heirs->count() > 0)
                <table class="pdf-info-table">
                    <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>NRIC</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                    <tbody>
                        @foreach($estate->heirs as $heir)
                        <tr>
                            <td>{{ $heir->name }}</td>
                            <td>{{ $heir->nric ?? '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $heir->relationship)) }}</td>
                            <td><strong>{{ number_format($heir->share_percentage ?? 0, 2) }}%</strong></td>
                            <td>RM {{ number_format((($heir->share_percentage ?? 0) / 100) * $remainingForHeirsPdf, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr style="background:#f1f5f9;font-weight:700;"><td colspan="3">Total</td><td>{{ number_format($estate->heirs->sum('share_percentage') ?? 0, 2) }}%</td><td>RM {{ number_format($remainingForHeirsPdf, 2) }}</td></tr>
                    </tbody>
                </table>
            @else
                <p style="color:#94a3b8;font-size:12px;">No heirs registered.</p>
            @endif

            @if(isset($estate->wasiyyah) && $estate->wasiyyah->count() > 0)
                <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
                <table class="pdf-info-table">
                    <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                    <tbody>
                        @foreach($estate->wasiyyah as $item)
                        <tr>
                            <td>{{ $item->beneficiary_name }}</td>
                            <td>{{ $item->relationship }}</td>
                            <td><strong>{{ number_format($item->requested_percentage, 2) }}%</strong></td>
                            <td>RM {{ number_format(($item->requested_percentage / 100) * $netEstatePdf, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total</td><td>{{ number_format($estate->wasiyyah->sum('requested_percentage') ?? 0, 2) }}%</td><td>RM {{ number_format((($estate->wasiyyah->sum('requested_percentage') ?? 0) / 100) * $netEstatePdf, 2) }}</td></tr>
                    </tbody>
                </table>
            @endif

            <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:10px;color:#94a3b8;text-align:center;">
                <p>This is a system-generated document. Generated on {{ now()->format('d F Y, h:i A') }} | Document ID: {{ $estate->unique_id ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <script>
        // PDF styles for the hidden content
        var style = document.createElement('style');
        style.textContent = `
            .pdf-section-title { font-size: 1rem; font-weight: 700; color: #1a5fb4; margin: 1.2rem 0 0.6rem 0; padding-bottom: 0.3rem; border-bottom: 2px solid #e8f1fd; }
            .pdf-info-table { width: 100%; border-collapse: collapse; margin-bottom: 0.8rem; font-size: 0.7rem; }
            .pdf-info-table td { padding: 0.4rem 0.5rem; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
            .pdf-info-table td:first-child { font-weight: 600; color: #475569; width: 30%; white-space: nowrap; }
            .pdf-info-card { padding: 0.5rem 0.8rem; background: #f8fafc; border-radius: 6px; margin-bottom: 0.3rem; border-left: 3px solid #1a5fb4; font-size: 0.7rem; }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>