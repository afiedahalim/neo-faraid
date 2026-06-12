<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inheritance Report - {{ $deceased_name ?? 'Deceased' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Poppins', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1a5fb4;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1a5fb4;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            color: #666;
            margin-top: 5px;
        }
        
        .deceased-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #1a5fb4;
        }
        
        .deceased-info h3 {
            margin: 0 0 10px;
            color: #1a5fb4;
            font-size: 14px;
        }
        
        .deceased-info p {
            margin: 5px 0;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #1a5fb4;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background: #e8f1fd;
            font-weight: 600;
        }
        
        .total-row {
            background: #1a5fb4;
            color: white;
            font-weight: 600;
        }
        
        .total-row td {
            color: white;
        }
        
        .footer {
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        
        .badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Islamic Inheritance Report (Faraid)</h1>
            <p>Generated on {{ $generated_date ?? now()->format('d M Y H:i:s') }}</p>
            <p><span class="badge">Official Document</span></p>
        </div>
        
        <!-- Deceased Information -->
        <div class="deceased-info">
            <h3>Deceased Information</h3>
            <p><strong>Full Name:</strong> {{ $deceased_name ?? 'Not specified' }}</p>
            <p><strong>NRIC/Passport:</strong> {{ $deceased_nric ?? 'Not specified' }}</p>
            <p><strong>Date of Death:</strong> {{ isset($death_date) ? date('d M Y', strtotime($death_date)) : 'Not specified' }}</p>
            <p><strong>Place of Death:</strong> {{ $death_place ?? 'Not specified' }}</p>
        </div>
        
        <!-- Estate Summary -->
        <div class="section">
            <div class="section-title">Estate Summary</div>
            <table>
                <tr>
                    <th>Description</th>
                    <th>Amount (RM)</th>
                </tr>
                <tr>
                    <td>Total Assets</td>
                    <td>RM {{ number_format($report_data['total_assets'] ?? 0, 2) }}</td>
                </tr>
                @if(isset($report_data['total_debts']) && $report_data['total_debts'] > 0)
                <tr>
                    <td>Total Debts</td>
                    <td>RM {{ number_format($report_data['total_debts'], 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Net Estate for Distribution</strong></td>
                    <td><strong>RM {{ number_format($report_data['net_assets'] ?? $report_data['total_assets'] ?? 0, 2) }}</strong></td>
                </tr>
            </table>
        </div>
        
        <!-- Inheritance Distribution -->
        <div class="section">
            <div class="section-title">Inheritance Distribution</div>
            <table>
                <thead>
                    <tr>
                        <th>Heir</th>
                        <th>Relationship</th>
                        <th>Share</th>
                        <th>Amount (RM)</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $distribution = [];
                        if (isset($report_data['distribution_summary']['heirs'])) {
                            $distribution = $report_data['distribution_summary']['heirs'];
                        } elseif (isset($report_data['inheritance_distribution'])) {
                            $distribution = $report_data['inheritance_distribution'];
                        } elseif (isset($report_data['calculation_data'])) {
                            $distribution = $report_data['calculation_data'];
                        }
                        $totalDistributed = 0;
                    @endphp
                    
                    @forelse($distribution as $heir)
                        @php
                            $amount = $heir['amount'] ?? 0;
                            $totalDistributed += $amount;
                        @endphp
                        <tr>
                            <td><strong>{{ $heir['heir'] ?? $heir['name'] ?? 'Unknown' }}</strong></td>
                            <td>{{ ucfirst($heir['relationship'] ?? 'N/A') }}</td>
                            <td>{{ $heir['share'] ?? '0' }}</td>
                            <td>RM {{ number_format($amount, 2) }}</td>
                            <td>{{ number_format($heir['percentage'] ?? ($amount > 0 ? ($amount / max($report_data['net_assets'] ?? $report_data['total_assets'] ?? 1)) * 100 : 0), 2) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">No distribution data available</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($totalDistributed > 0)
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3"><strong>Total Distributed</strong></td>
                        <td colspan="2"><strong>RM {{ number_format($totalDistributed, 2) }}</strong></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        
        <!-- Scenario Information -->
        @if(!empty($report_data['scenario_description']) || !empty($report_data['scenario_number']))
        <div class="section">
            <div class="section-title">Calculation Scenario</div>
            <table>
                <tr>
                    <th>Scenario Number</th>
                    <td>{{ $report_data['scenario_number'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $report_data['scenario_description'] ?? 'Standard Faraid distribution' }}</td>
                </tr>
                <tr>
                    <th>Calculation Method</th>
                    <td>{{ ucfirst($report_data['calculation_method'] ?? 'Local Calculation') }}</td>
                </tr>
            </table>
        </div>
        @endif
        
        <!-- Will Information -->
        @if(!empty($report_data['will_content']))
        <div class="section">
            <div class="section-title">Will & Testament</div>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <p>{{ $report_data['will_content'] }}</p>
            </div>
        </div>
        @endif
        
        <!-- Video Will -->
        @if(!empty($report_data['video_will_url']))
        <div class="section">
            <div class="section-title">Video Will</div>
            <p>A video will has been recorded and is available at: <a href="{{ $report_data['video_will_url'] }}">{{ $report_data['video_will_url'] }}</a></p>
        </div>
        @endif
        
        <!-- Trustee Information -->
        @if(!empty($report_data['trustee_info']))
        <div class="section">
            <div class="section-title">Trustee Information</div>
            @php
                $trustee = is_array($report_data['trustee_info']) ? $report_data['trustee_info'] : json_decode($report_data['trustee_info'], true);
            @endphp
            <table>
                <tr>
                    <th>Name</th>
                    <td>{{ $trustee['name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Contact</th>
                    <td>{{ $trustee['contact'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $trustee['address'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        @endif
        
        <!-- Disclaimer -->
        <div class="signature">
            <p><strong>Legal Disclaimer:</strong></p>
            <p style="font-size: 10px; color: #666;">This report was generated by the Neo Faraid System based on Islamic inheritance laws (Faraid). The information contained herein is provided for informational purposes only and should not be considered as legal advice. Please consult with a qualified Shariah advisor or legal professional for validation and implementation of this inheritance distribution.</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Document ID: {{ uniqid() }}</p>
            <p>Generated by Neo Faraid System | {{ $generated_date ?? now()->format('d M Y H:i:s') }}</p>
            <p>&copy; {{ date('Y') }} Neo Faraid. All rights reserved.</p>
        </div>
    </div>
</body>
</html>