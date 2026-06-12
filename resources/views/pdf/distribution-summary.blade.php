<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estate Distribution Summary - {{ $estate->deceased_name }}</title>
    <style>
        body {
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #1a5fb4;
        }
        .header h1 {
            color: #1a5fb4;
            font-size: 22px;
            margin: 0 0 5px 0;
        }
        .header p {
            color: #64748b;
            font-size: 11px;
            margin: 0;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a5fb4;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e8f1fd;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .info-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #475569;
            width: 35%;
        }
        .info-card {
            padding: 10px 15px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 3px solid #1a5fb4;
        }
        .amount-positive {
            color: #25D366;
            font-weight: 700;
        }
        .amount-negative {
            color: #dc3545;
            font-weight: 700;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10pt;
        }
        .data-table th {
            background: #f1f5f9;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
        }
        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .total-row {
            background: #f1f5f9;
            font-weight: 700;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 9pt;
            color: #94a3b8;
            text-align: center;
        }
        .warning-note {
            background: #fff3cd;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 3px solid #ffc107;
            font-size: 9pt;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Estate Distribution Summary</h1>
        <p>Reference: {{ $estate->unique_id }} | Generated: {{ now()->format('d F Y, h:i A') }}</p>
    </div>

    <!-- Deceased Information -->
    <div class="section-title">Deceased Information</div>
    <table class="info-table">
        <tr><td>Full Name</td><td><strong>{{ $estate->deceased_name }}</strong></td></tr>
        <tr><td>NRIC/Passport</td><td>{{ $estate->deceased_nric ?? 'N/A' }}</td></tr>
        <tr><td>Date of Birth</td><td>{{ $estate->date_of_birth ? $estate->date_of_birth->format('d F Y') : 'N/A' }}</td></tr>
        <tr><td>Gender</td><td>{{ ucfirst($estate->gender ?? 'N/A') }}</td></tr>
        <tr><td>Contact Email</td><td>{{ $estate->contact_email ?? 'N/A' }}</td></tr>
        <tr><td>Address</td><td>{{ $estate->address ?? 'N/A' }}</td></tr>
    </table>

    <!-- Financial Summary -->
    <div class="section-title">Financial Summary</div>
    <div style="display: flex; gap: 15px; margin-bottom: 20px;">
        <div class="info-card" style="flex: 1; text-align: center;">
            <strong>Total Assets</strong><br>
            <span style="color: #25D366; font-size: 16px;">{{ $distributionData['formatted_total_assets'] }}</span>
        </div>
        <div class="info-card" style="flex: 1; text-align: center;">
            <strong>Total Debts</strong><br>
            <span style="color: #dc3545; font-size: 16px;">{{ $distributionData['formatted_total_debts'] }}</span>
        </div>
        <div class="info-card" style="flex: 1; text-align: center;">
            <strong>Net Estate</strong><br>
            <span style="color: #1a5fb4; font-size: 16px;">{{ $distributionData['formatted_net_estate'] }}</span>
        </div>
    </div>

    <!-- Your Distribution Details -->
    @if($distributionData['your_share'])
    <div class="section-title">Your Distribution Details</div>
    <div class="info-card" style="background: #e8f1fd;">
        <p><strong>Your Name:</strong> {{ $link->beneficiary_name }}</p>
        <p><strong>Your Role:</strong> {{ ucfirst(str_replace('_', ' ', $beneficiaryType)) }}</p>
        @if($beneficiaryType === 'heir')
        <p><strong>Your Share Percentage:</strong> {{ number_format($distributionData['your_share']['share_percentage'], 2) }}%</p>
        <p><strong style="color: #25D366;">Your Inheritance Amount:</strong> <strong style="color: #25D366;">{{ $distributionData['your_share']['formatted_amount'] }}</strong></p>
        @elseif($beneficiaryType === 'wasiyyah')
        <p><strong>Requested Percentage:</strong> {{ number_format($distributionData['your_share']['share_percentage'], 2) }}%</p>
        <p><strong style="color: #25D366;">Wasiyyah Amount:</strong> <strong style="color: #25D366;">{{ $distributionData['your_share']['formatted_amount'] }}</strong></p>
        @endif
    </div>
    @endif

    <!-- Faraid Heirs Distribution -->
    @if(count($distributionData['heirs']) > 0)
    <div class="section-title">Faraid Heirs Distribution</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Heir Name</th>
                <th>Relationship</th>
                <th>Share Percentage</th>
                <th>Inheritance Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distributionData['heirs'] as $heir)
            <tr>
                <td><strong>{{ $heir['name'] }}</strong></td>
                <td>{{ $heir['relationship'] }}</td>
                <td>{{ number_format($heir['share_percentage'], 2) }}%</td>
                <td>{{ $heir['formatted_amount'] }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ number_format(array_sum(array_column($distributionData['heirs'], 'share_percentage')), 2) }}%</strong></td>
                <td><strong>{{ $distributionData['formatted_remaining_for_heirs'] }}</strong></td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Wasiyyah Beneficiaries -->
    @if(count($distributionData['wasiyyah_beneficiaries']) > 0)
    <div class="section-title">Wasiyyah Beneficiaries</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Beneficiary Name</th>
                <th>Relationship</th>
                <th>Requested Percentage</th>
                <th>Wasiyyah Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distributionData['wasiyyah_beneficiaries'] as $wasiyyah)
            <tr>
                <td><strong>{{ $wasiyyah['name'] }}</strong></td>
                <td>{{ $wasiyyah['relationship'] }}</td>
                <td>{{ number_format($wasiyyah['percentage'], 2) }}%</td>
                <td>{{ $wasiyyah['formatted_amount'] }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2"><strong>Total Wasiyyah</strong></td>
                <td><strong>{{ number_format(array_sum(array_column($distributionData['wasiyyah_beneficiaries'], 'percentage')), 2) }}%</strong></td>
                <td><strong>{{ $distributionData['formatted_net_estate'] }}</strong></td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Wasiyyah Limit Warning -->
    @if(array_sum(array_column($distributionData['wasiyyah_beneficiaries'], 'percentage')) > 33.33)
    <div class="warning-note">
        <strong>⚠️ Important:</strong> Total Wasiyyah exceeds the 1/3 limit (33.33%). Only 1/3 of the net estate can be distributed via Wasiyyah. The excess requires consent from all Faraid heirs.
    </div>
    @endif

    <!-- Trustee Information -->
    <div class="section-title">Trustee Information</div>
    <table class="info-table">
        <tr><td>Trustee Name</td><td><strong>{{ $distributionData['trustee']['name'] }}</strong></td></tr>
        <tr><td>Trustee Email</td><td>{{ $distributionData['trustee']['email'] }}</td></tr>
        <tr><td>Trustee Phone</td><td>{{ $distributionData['trustee']['phone'] ?? 'Not provided' }}</td></tr>
    </table>

    <div class="footer">
        <p>This is an official estate distribution summary. Generated on {{ now()->format('d F Y, h:i A') }}</p>
        <p>Document ID: {{ $estate->unique_id }}</p>
        <p>This document is for informational purposes. For official distribution, please contact the appointed trustee.</p>
    </div>
</body>
</html>