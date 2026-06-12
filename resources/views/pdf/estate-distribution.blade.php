{{-- resources/views/pdfs/estate-distribution-statement.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estate Distribution Statement - {{ $estate->deceased_name ?? 'N/A' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', Arial, sans-serif !important;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 40px;
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.5;
            background: #ffffff;
        }

        /* Header Section */
        .pdf-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1a5fb4;
            padding-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #e8f1fd 100%);
            border-radius: 20px;
            padding: 20px;
        }

        .pdf-header h1 {
            color: #1a5fb4;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .pdf-header p {
            color: #64748b;
            font-size: 10pt;
        }

        .estate-badge {
            display: inline-block;
            background: #e8f1fd;
            color: #1a5fb4;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 9pt;
            font-weight: 600;
            margin-top: 12px;
        }

        /* Section Styles */
        .pdf-section-title {
            font-size: 1rem;
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

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0;
            font-size: 9pt;
            border-radius: 12px;
            overflow: hidden;
        }

        .data-table th {
            background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
            color: white;
            font-weight: 600;
            padding: 12px 12px;
            text-align: left;
        }

        .data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background: #f8fafc;
            font-weight: 700;
        }

        .total-row td {
            font-weight: 700;
            color: #1e293b;
            border-top: 1px solid #cbd5e1;
        }

        .info-box {
            background: #d1ecf1;
            color: #0c5460;
            padding: 14px 20px;
            border-radius: 12px;
            margin: 15px 0;
            font-size: 9pt;
        }

        .warning-box {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #b76e00;
            padding: 14px 20px;
            border-radius: 12px;
            margin: 20px 0;
            font-size: 9pt;
        }

        .security-notice {
            background: linear-gradient(135deg, #e8f1fd 0%, #f8fafc 100%);
            padding: 18px 24px;
            border-radius: 15px;
            margin: 30px 0 20px 0;
            font-size: 9pt;
            border: 1px solid #e2e8f0;
        }

        .security-notice strong {
            color: #1a5fb4;
        }

        .pdf-footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
        }

        .beneficiary-box {
            background: linear-gradient(135deg, #e8f1fd 0%, #ffffff 100%);
            padding: 24px 28px;
            border-radius: 20px;
            margin-bottom: 28px;
            border-left: 4px solid #1a5fb4;
        }

        .beneficiary-name {
            font-size: 20px;
            font-weight: 800;
            color: #1a5fb4;
        }

        .beneficiary-type {
            background: #1a5fb4;
            color: white;
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 9pt;
            font-weight: 600;
            margin-left: 12px;
        }

        .beneficiary-share {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(26, 95, 180, 0.2);
        }

        .beneficiary-amount {
            font-size: 24px;
            font-weight: 800;
            color: #1a5fb4;
            margin-top: 6px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 8pt;
            font-weight: 600;
        }

        .badge-real-estate { background: #e8f1fd; color: #1a5fb4; }
        .badge-financial { background: #d4edda; color: #155724; }
        .badge-investment { background: #fff3cd; color: #856404; }
        .badge-digital { background: #e0d4f5; color: #6f42c1; }
        .badge-secured { background: #f8d7da; color: #721c24; }
        .badge-unsecured { background: #d1ecf1; color: #0c5460; }
        .badge-religious { background: #d4edda; color: #155724; }

        @media print {
            body {
                padding: 20px;
                background: white;
            }
            .data-table th {
                background: #1a5fb4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .beneficiary-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Header - Matches Hidden PDF Content Style -->
    <div class="pdf-header">
        <h1>Estate Distribution Statement</h1>
        <p>Reference ID: <strong>{{ $estate->unique_id ?? 'N/A' }}</strong> | Generated: {{ $generated_at ?? now()->format('d M Y') }}</p>
        <span class="estate-badge">Shariah-Compliant | Faraid Certified</span>
    </div>

    <!-- Beneficiary Box (Matches the email/inheritance-result format but aligned to PDF style) -->
    <div class="beneficiary-box">
        <div>
            <span class="beneficiary-name">{{ $beneficiary['name'] ?? 'Valued Recipient' }}</span>
            <span class="beneficiary-type">{{ ucfirst($beneficiary['type'] ?? 'heir') }} Access</span>
        </div>
        @if(isset($beneficiary['share_percentage']))
            <div class="beneficiary-share">
                <div>Your Allocated Share (Faraid): <strong>{{ number_format($beneficiary['share_percentage'], 2) }}%</strong></div>
                <div class="beneficiary-amount">
                    Your Entitled Amount: RM {{ number_format(($beneficiary['share_percentage'] ?? 0) / 100 * ($remainingForHeirs ?? $netEstate), 2) }}
                </div>
            </div>
        @endif
        @if(isset($beneficiary['role']) && in_array($beneficiary['role'], ['trustee', 'alternate_trustee']))
            <div class="beneficiary-share">
                <div><strong>🔑 Trustee Access:</strong> You have been appointed as a trustee to manage and execute this estate according to Islamic inheritance law.</div>
            </div>
        @endif
    </div>

    <!-- Financial Summary Cards (Styled like .pdf-info-card from hidden PDF) -->
    <div class="pdf-section-title">Financial Summary</div>
    <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
        <div class="pdf-info-card" style="flex:1; text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366; font-size:16px;">RM {{ number_format($totalAssets ?? 0, 2) }}</span></div>
        <div class="pdf-info-card" style="flex:1; text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545; font-size:16px;">RM {{ number_format($totalDebts ?? 0, 2) }}</span></div>
        <div class="pdf-info-card" style="flex:1; text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4; font-size:16px;">RM {{ number_format($netEstate ?? 0, 2) }}</span></div>
    </div>

    <!-- Personal Information Table (Matches Hidden PDF Structure) -->
    <div class="pdf-section-title">Personal Information</div>
    <table class="pdf-info-table">
        <tr><td><strong>Name</strong></td><td>{{ $estate->deceased_name ?? 'N/A' }}</td></tr>
        <tr><td><strong>NRIC</strong></td><td>{{ $estate->deceased_nric ?? 'N/A' }}</td></tr>
        <tr><td><strong>Gender</strong></td><td>{{ ucfirst($estate->gender ?? 'N/A') }}</td></tr>
        <tr><td><strong>Date of Birth</strong></td><td>{{ $estate->date_of_birth ? \Carbon\Carbon::parse($estate->date_of_birth)->format('d M Y') : 'N/A' }}</td></tr>
        <tr><td><strong>Contact</strong></td><td>{{ $estate->contact_email ?? 'N/A' }} | {{ $estate->contact_phone ?? 'N/A' }}</td></tr>
        <tr><td><strong>Address</strong></td><td>{{ $estate->address ?? 'N/A' }}</td></tr>
        <tr><td><strong>Trustee</strong></td><td>{{ $estate->trustee_name ?? 'Not appointed' }} ({{ $estate->trustee_email ?? 'N/A' }})</td></tr>
    </table>

    <!-- Assets Section (Matches Hidden PDF exactly) -->
    <div class="pdf-section-title">Assets ({{ $estate->assets->count() ?? 0 }})</div>
    @if(($estate->assets->count() ?? 0) > 0)
        <table class="data-table">
            <thead><tr><th>Name</th><th>Value (RM)</th><th>Ownership</th><th>Your Share (RM)</th></tr></thead>
            <tbody>
                @foreach($estate->assets as $asset)
                <tr>
                    <td><span class="badge badge-real-estate">{{ $asset->name }}</span></td>
                    <td>RM {{ number_format($asset->value, 2) }}</td>
                    <td>{{ $asset->ownership_percentage ?? 100 }}%</td>
                    <td>RM {{ number_format($asset->value * (($asset->ownership_percentage ?? 100) / 100), 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row"><td colspan="2"><strong>TOTAL ASSETS</strong></td><td><strong>RM {{ number_format($totalAssets ?? 0, 2) }}</strong></td><td><strong>RM {{ number_format($totalAssets ?? 0, 2) }}</strong></td></tr>
            </tbody>
        </table>
    @else
        <div class="info-box">No assets registered for this estate.</div>
    @endif

    <!-- Debts Section -->
    <div class="pdf-section-title">Debts &amp; Liabilities ({{ $estate->debts->count() ?? 0 }})</div>
    @if(($estate->debts->count() ?? 0) > 0)
        <table class="data-table">
            <thead><tr><th>Creditor Name</th><th>Debt Type</th><th>Amount (RM)</th></tr></thead>
            <tbody>
                @foreach($estate->debts as $debt)
                <tr>
                    <td><span class="badge badge-secured">{{ $debt->creditor_name }}</span></td>
                    <td>{{ $debt->debt_type ?? 'Debt' }}</td>
                    <td>RM {{ number_format($debt->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row"><td colspan="2"><strong>TOTAL DEBTS</strong></td><td><strong>RM {{ number_format($totalDebts ?? 0, 2) }}</strong></td></tr>
            </tbody>
        </table>
    @else
        <div class="info-box">No debts registered for this estate.</div>
    @endif

    <!-- Faraid Heirs Distribution -->
    <div class="pdf-section-title">Faraid Heirs Distribution</div>
    @if(($estate->heirs->count() ?? 0) > 0)
        <table class="data-table">
            <thead><tr><th>Name</th><th>NRIC</th><th>Relationship</th><th>Share (%)</th><th>Amount (RM)</th></tr></thead>
            <tbody>
                @foreach($estate->heirs as $heir)
                <tr>
                    <td><strong>{{ $heir->name }}</strong></td>
                    <td>{{ $heir->nric ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $heir->relationship)) }}</td>
                    <td><strong>{{ number_format($heir->share_percentage ?? 0, 2) }}%</strong></td>
                    <td>RM {{ number_format((($heir->share_percentage ?? 0) / 100) * ($remainingForHeirs ?? $netEstate), 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row"><td colspan="3"><strong>TOTAL HEIRS DISTRIBUTION</strong></td><td><strong>{{ number_format($totalHeirPct ?? 0, 2) }}%</strong></td><td><strong>RM {{ number_format($remainingForHeirs ?? 0, 2) }}</strong></td></tr>
            </tbody>
        </table>
        <div class="info-box"><strong>📖 Faraid Note:</strong> Islamic inheritance law (Faraid) prescribes fixed shares to eligible heirs. This distribution has been calculated based on the shares specified above.</div>
    @else
        <div class="info-box">No heirs registered for this estate.</div>
    @endif

    <!-- Wasiyyah Beneficiaries -->
    @if(($estate->wasiyyah->count() ?? 0) > 0)
    <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
    <table class="data-table">
        <thead><tr><th>Name</th><th>Relationship</th><th>Share (%)</th><th>Amount (RM)</th></tr></thead>
        <tbody>
            @foreach($estate->wasiyyah as $wasiyyah)
            <tr>
                <td><strong>{{ $wasiyyah->beneficiary_name }}</strong></td>
                <td>{{ $wasiyyah->relationship }}</td>
                <td><strong>{{ number_format($wasiyyah->requested_percentage, 2) }}%</strong></td>
                <td>RM {{ number_format(($wasiyyah->requested_percentage / 100) * $netEstate, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row"><td colspan="2"><strong>TOTAL WASIYYAH</strong></td><td><strong>{{ number_format($totalWasiyyahPct ?? 0, 2) }}%</strong></td><td><strong>RM {{ number_format(($totalWasiyyahPct / 100) * $netEstate, 2) }}</strong></td></tr>
        </tbody>
    </table>
    <div class="warning-box">
        <p><strong>⚠️ Important Shariah Ruling:</strong> Wasiyyah (bequest) is limited to a maximum of 1/3 (33.33%) of the net estate value. This cannot override the Faraid rights of legal heirs.</p>
        @if(($totalWasiyyahPct ?? 0) > 33.33)
        <p><strong>⚠️ Warning:</strong> Current Wasiyyah total exceeds the Islamic limit. This requires consent from all Faraid heirs.</p>
        @elseif(($totalWasiyyahPct ?? 0) > 0)
        <p><strong>✓ Compliance Check:</strong> Wasiyyah total is within the permissible 1/3 limit.</p>
        @endif
    </div>
    @endif

    <!-- Trustee Information -->
    <div class="pdf-section-title">Trustee Information</div>
    <table class="pdf-info-table">
        <tr><td><strong>Name</strong></td><td>{{ $estate->trustee_name ?? 'Not appointed' }}</td></tr>
        <tr><td><strong>NRIC / Passport</strong></td><td>{{ $estate->trustee_nric ?? 'N/A' }}</td></tr>
        <tr><td><strong>Phone Number</strong></td><td>{{ $estate->trustee_phone ?? 'N/A' }}</td></tr>
        <tr><td><strong>Email Address</strong></td><td>{{ $estate->trustee_email ?? 'N/A' }}</td></tr>
    </table>
    <div class="info-box"><strong>📋 Trustee Role:</strong> Responsible for managing, safeguarding, and executing estate distribution according to Islamic inheritance law (Faraid).</div>

    <!-- Legal & Shariah Compliance Notices -->
    <div class="security-notice">
        <strong>🔒 Confidentiality & Legal Notice</strong>
        <p>This document is strictly confidential and intended solely for {{ $beneficiary['name'] ?? 'the recipient' }}. Unauthorized distribution is prohibited.</p>
        <p style="margin-top:10px;"><strong>📜 Shariah Compliance Declaration:</strong> Prepared in accordance with Islamic inheritance law (Faraid) and Malaysian Islamic legal principles.</p>
        <p style="margin-top:10px;"><strong>⚖️ Legal Acknowledgment:</strong> Any disputes shall be resolved under the jurisdiction of the Shariah Court of Malaysia.</p>
    </div>

    <!-- Footer -->
    <div class="pdf-footer">
        <p>This is an official document generated by the Neo Faraid Estate Management System.</p>
        <p>Document ID: {{ $estate->unique_id ?? 'N/A' }} | Generated on: {{ $generated_at ?? now()->format('d F Y, h:i A') }}</p>
        <p>© {{ date('Y') }} Neo Faraid. All rights reserved. | Shariah-Compliant | Faraid Certified</p>
    </div>
</body>
</html>