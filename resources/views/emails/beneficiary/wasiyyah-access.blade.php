{{-- resources/views/emails/beneficiary/wasiyyah-access.blade.php --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Poppins', sans-serif !important;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: #f5f7fa;
        padding: 20px;
    }
    
    .email-wrapper {
        max-width: 600px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }
    
    .email-header {
        background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
        padding: 35px 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .email-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .email-header::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,215,0,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .email-header h1 {
        color: #ffd700;
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        position: relative;
        z-index: 1;
        letter-spacing: -0.5px;
    }
    
    .email-header p {
        color: rgba(255, 255, 255, 0.95);
        margin: 12px 0 0;
        font-size: 15px;
        position: relative;
        z-index: 1;
    }
    
    .email-body {
        padding: 35px 30px;
        background: #ffffff;
    }
    
    .greeting {
        font-size: 16px;
        margin-bottom: 20px;
        color: #1e293b;
        line-height: 1.6;
    }
    
    .greeting strong {
        color: #1a5fb4;
    }
    
    .message-text {
        color: #475569;
        line-height: 1.7;
        margin-bottom: 20px;
        font-size: 15px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a5fb4;
        margin: 25px 0 15px 0;
        padding-bottom: 8px;
        border-bottom: 2px solid #e8f1fd;
    }
    
    .info-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        margin: 20px 0;
        border: 1px solid #e2e8f0;
    }
    
    .info-box p {
        margin: 8px 0;
        color: #1e293b;
    }
    
    .info-box p:first-child {
        margin-top: 0;
    }
    
    .info-box p:last-child {
        margin-bottom: 0;
    }
    
    .video-box {
        background: linear-gradient(135deg, #e8f4f8 0%, #d1ecf1 100%);
        border-radius: 16px;
        padding: 20px;
        margin: 20px 0;
        border-left: 4px solid #1a5fb4;
    }
    
    .video-box p {
        margin: 8px 0;
        color: #0c5460;
    }
    
    .video-box p:first-child {
        margin-top: 0;
    }
    
    .video-box p:last-child {
        margin-bottom: 0;
    }
    
    .video-badge {
        display: inline-flex;
        align-items: center;
        background: #ffd700;
        color: #1a5fb4;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin-right: 10px;
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: #f8fafc;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .info-table tr {
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-table tr:last-child {
        border-bottom: none;
    }
    
    .info-table th {
        background: #1a5fb4;
        color: white;
        font-weight: 600;
        padding: 14px 18px;
        text-align: left;
        width: 40%;
        font-size: 14px;
    }
    
    .info-table td {
        padding: 14px 18px;
        color: #1e293b;
        font-weight: 500;
        font-size: 14px;
    }
    
    .amount {
        font-size: 22px;
        font-weight: 800;
        color: #1a5fb4;
    }
    
    .amount-highlight {
        background: linear-gradient(135deg, #25D36610 0%, #25D36620 100%);
        display: inline-block;
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: 700;
        color: #1a5fb4;
    }
    
    .button-container {
        text-align: center;
        margin: 35px 0;
    }
    
    .button {
        display: inline-block;
        background: linear-gradient(135deg, #1a5fb4 0%, #2d7ad6 100%);
        color: white !important;
        text-decoration: none;
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(26, 95, 180, 0.3);
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(26, 95, 180, 0.4);
    }
    
    .note-box {
        background: #fff8e7;
        border-left: 4px solid #ffc107;
        padding: 18px 22px;
        border-radius: 16px;
        margin: 25px 0;
    }
    
    .note-box p {
        margin: 8px 0;
        color: #856404;
        font-size: 13px;
        line-height: 1.5;
    }
    
    .note-box p:first-child {
        margin-top: 0;
    }
    
    .note-box p:last-child {
        margin-bottom: 0;
    }
    
    .note-box strong {
        font-size: 14px;
    }
    
    .attachments-box {
        background: #e8f4f8;
        border-radius: 16px;
        padding: 18px 22px;
        margin: 20px 0;
    }
    
    .attachments-box p {
        margin: 8px 0;
        color: #0c5460;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .attachments-box p:first-child {
        margin-top: 0;
    }
    
    .attachments-box p:last-child {
        margin-bottom: 0;
    }
    
    .attachments-box strong {
        color: #0c5460;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        background: #e8f1fd;
        color: #1a5fb4;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-right: 8px;
    }
    
    .security-badge {
        background: #e8f1fd;
        color: #1a5fb4;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 12px;
        margin-top: 20px;
        text-align: center;
    }
    
    .security-badge svg {
        width: 16px;
        height: 16px;
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
    }
    
    .footer {
        background: #f8fafc;
        padding: 20px 30px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
    }
    
    .footer p {
        color: #64748b;
        font-size: 11px;
        margin: 5px 0;
        line-height: 1.5;
    }
    
    .footer p:first-child {
        margin-top: 0;
    }
    
    .footer p:last-child {
        margin-bottom: 0;
    }
    
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
    
    @media (max-width: 600px) {
        .email-wrapper {
            border-radius: 16px;
        }
        
        .email-header {
            padding: 25px 20px;
        }
        
        .email-header h1 {
            font-size: 22px;
        }
        
        .email-body {
            padding: 25px 20px;
        }
        
        .info-table th,
        .info-table td {
            display: block;
            width: 100%;
            padding: 10px 15px;
        }
        
        .info-table th {
            border-bottom: none;
            padding-bottom: 5px;
        }
        
        .info-table td {
            padding-top: 0;
            margin-bottom: 10px;
        }
        
        .button {
            padding: 12px 24px;
            font-size: 14px;
        }
        
        .section-title {
            font-size: 16px;
        }
        
        .amount {
            font-size: 18px;
        }
        
        .video-badge {
            display: inline-block;
            margin-bottom: 8px;
        }
    }
    
    @media print {
        body { background: white !important; font-size: 10pt; }
        .button-container, .attachments-box, .video-box, .note-box, .security-badge, .footer { display: none !important; }
        .email-wrapper { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        .email-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .info-table th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .pdf-section-title { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .pdf-info-card { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<div class="email-wrapper">
    <div class="email-header">
        <h1>Wasiyyah (Will) Notification</h1>
        <p>Estate of {{ $deceasedName }}</p>
    </div>

    <div class="email-body">
        <div class="greeting">
            Dear <strong>{{ $beneficiaryName }}</strong>,
        </div>
        
        <p class="message-text">
            You have been named as a <strong>Wasiyyah beneficiary</strong> in the estate of 
            <strong>{{ $deceasedName }}</strong>.
        </p>
        
        @php
            // Get the specific wasiyyah record for this beneficiary
            $wasiyyah = isset($estate) && isset($beneficiaryEmail) 
                ? $estate->wasiyyah->where('beneficiary_email', $beneficiaryEmail)->first() 
                : null;
            
            // Calculate share percentage and amounts
            $sharePercentage = $wasiyyah ? $wasiyyah->requested_percentage : ($sharePercentage ?? 0);
            $totalAssets = isset($estate) ? $estate->assets()->sum('value') : 0;
            $totalDebts = isset($estate) ? $estate->debts()->sum('amount') : 0;
            $netEstate = isset($estate) ? max(0, $totalAssets - $totalDebts) : 0;
            $estimatedAmount = ($sharePercentage / 100) * $netEstate;
            $hasVideo = isset($hasVideo) ? $hasVideo : (isset($estate) && isset($estate->will_video_url) && $estate->will_video_url);
            $relationship = $wasiyyah ? $wasiyyah->relationship : ($relationship ?? 'Beneficiary');
            $totalWasiyyahPct = isset($estate) ? $estate->wasiyyah()->sum('requested_percentage') : 0;
            $maxWasiyyahPct = 33.33;
            $remainingForHeirs = $netEstate - (($totalWasiyyahPct / 100) * $netEstate);
            $totalHeirPct = isset($estate) ? $estate->heirs()->sum('share_percentage') : 0;
        @endphp
        
        <div class="section-title">Your Wasiyyah Details</div>
        
        <div class="info-box">
            <p><strong>Your Name:</strong> {{ $beneficiaryName }}</p>
            <p><strong>Relationship to Deceased:</strong> {{ $relationship }}</p>
            <p><strong>Share Percentage:</strong> <span class="amount-highlight">{{ number_format($sharePercentage, 2) }}%</span></p>
            <p><strong>Your Inheritance Amount:</strong> <span class="amount">{{ $formattedAmount ?? 'RM ' . number_format($estimatedAmount, 2) }}</span></p>
            @if(isset($estate->unique_id))
                <p><strong>Estate ID:</strong> {{ $estate->unique_id }}</p>
            @endif
        </div>

        <!-- Estate Summary Section (Matches index.blade.php style) -->
        <div class="section-title">Estate Summary</div>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin: 20px 0;">
            <div style="flex: 1; min-width: 120px; background: #f8fafc; padding: 12px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.75rem; color: #64748b;">Net Estate Value</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: #1a5fb4;">RM {{ number_format($netEstate, 2) }}</div>
            </div>
            <div style="flex: 1; min-width: 120px; background: #f8fafc; padding: 12px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.75rem; color: #64748b;">Wasiyyah Limit (1/3)</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: #ffc107;">{{ number_format($maxWasiyyahPct, 2) }}%</div>
            </div>
            <div style="flex: 1; min-width: 120px; background: #f8fafc; padding: 12px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.75rem; color: #64748b;">Total Wasiyyah</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: {{ $totalWasiyyahPct <= $maxWasiyyahPct ? '#25D366' : '#dc3545' }};">{{ number_format($totalWasiyyahPct, 2) }}%</div>
            </div>
        </div>

        @if($hasVideo)
        <div class="video-box">
            <p>
                <span class="video-badge">📹 VIDEO ATTACHED</span>
                <strong>Will Video Recording</strong>
            </p>
            <p>The deceased has recorded a will video. The video file is attached to this email.</p>
            <p><small>Please save this video for your records. It contains the final wishes of the deceased.</small></p>
        </div>
        @endif
        
        <div class="button-container">
            <a href="{{ $accessUrl }}" class="button">View Online Dashboard</a>
        </div>
        
        <div class="attachments-box">
            <p><strong>📎 Attached Documents:</strong></p>
            <p>✓ <strong>PDF Estate Distribution Statement</strong> - Please review and save for your records</p>
            @if($hasVideo)
                <p>✓ <strong>Will Video Recording</strong> - Final wishes of the deceased</p>
            @endif
            <p style="margin-top: 10px;"><em>Note: Attachments are available when you access the online dashboard or as separate email attachments.</em></p>
        </div>
        
        <div class="note-box">
            <p><strong>📌 Important Notes:</strong></p>
            <p>• This link is for your personal use only. Please do not share it with others.</p>
            <p>• The link will expire on <strong>{{ $expiryDate }}</strong>.</p>
            <p>• For security purposes, each access is logged and monitored.</p>
            <p>• Please save the attached PDF document for your personal records.</p>
            @if($hasVideo)
                <p>• Please download and save the will video attachment for your records.</p>
            @endif
            <p>• <strong>Wasiyyah is limited to 1/3 (33.33%) of the net estate value</strong> as per Islamic inheritance law (Faraid).</p>
            <p>• If you did not expect this notification, please contact us immediately.</p>
        </div>
        
        <div class="security-badge">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span>This is a secure, encrypted notification. Your privacy is protected.</span>
        </div>
        
        <p class="message-text" style="margin-top: 25px;">
            If you have any questions about your Wasiyyah allocation or the distribution process, 
            please contact the estate administrator at 
            <strong>{{ config('mail.from.address') }}</strong>.
        </p>
        
        <p style="margin-top: 25px;">
            Best regards,<br>
            <strong style="color: #1a5fb4;">Neo Faraid Team</strong>
        </p>
    </div>

    <div class="footer">
        <p>This is an automated message from the Neo Faraid System. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Neo Faraid. All rights reserved.</p>
        <p>Secure Estate Distribution Platform | Shariah-Compliant | Faraid Certified</p>
    </div>
</div>

<!-- HIDDEN PDF CONTENT (Matches estate-setup/index.blade.php) -->
<div id="pdfContent" style="display:none;">
    <div style="padding:20px;font-family:'Poppins',sans-serif;max-width:800px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:20px;border-bottom:2px solid #1a5fb4;padding-bottom:15px;">
            <h1 style="color:#1a5fb4;font-size:22px;margin:0 0 5px 0;">Estate Planning Document</h1>
            <p style="color:#64748b;font-size:12px;margin:0;">Reference: {{ $estate->unique_id ?? 'N/A' }} | Status: {{ $estate->status_label ?? 'N/A' }} | Generated: {{ now()->format('d M Y') }}</p>
        </div>

        <div class="pdf-section-title">Wasiyyah Beneficiary Information</div>
        <table class="pdf-info-table">
            <tr><td>Beneficiary Name</td><td><strong>{{ $beneficiaryName }}</strong></td></tr>
            <tr><td>Relationship to Deceased</td><td>{{ $relationship }}</td></tr>
            <tr><td>Share Percentage</td><td><strong>{{ number_format($sharePercentage, 2) }}%</strong></td></tr>
            <tr><td>Inheritance Amount</td><td><strong>RM {{ number_format($estimatedAmount, 2) }}</strong></td></tr>
            <tr><td>Estate ID</td><td>{{ $estate->unique_id ?? 'N/A' }}</td></tr>
        </table>

        <div class="pdf-section-title">Financial Summary</div>
        <div style="display:flex;gap:15px;margin-bottom:15px;">
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Assets</strong><br><span style="color:#25D366;font-size:16px;">RM {{ number_format($totalAssets, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Total Debts</strong><br><span style="color:#dc3545;font-size:16px;">RM {{ number_format($totalDebts, 2) }}</span></div>
            <div class="pdf-info-card" style="flex:1;text-align:center;"><strong>Net Estate</strong><br><span style="color:#1a5fb4;font-size:16px;">RM {{ number_format($netEstate, 2) }}</span></div>
        </div>

        <div class="pdf-section-title">Assets ({{ isset($estate) ? $estate->assets->count() : 0 }})</div>
        @if(isset($estate) && $estate->assets->count() > 0)
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Value</strong></th><th><strong>Ownership</strong></th><th><strong>Description</strong></th></tr></thead>
                <tbody>@foreach($estate->assets as $asset)<tr><td>{{ $asset->name }}</td><td>RM {{ number_format($asset->value, 2) }}</td><td>{{ $asset->ownership_percentage ?? 100 }}%</td><td>{{ $asset->description ?? '-' }}</td></tr>@endforeach</tbody>
            </table>
        @else
            <p style="color:#94a3b8;font-size:12px;">No assets registered.</p>
        @endif

        <div class="pdf-section-title">Debts ({{ isset($estate) ? $estate->debts->count() : 0 }})</div>
        @if(isset($estate) && $estate->debts->count() > 0)
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Creditor</strong></th><th><strong>Type</strong></th><th><strong>Amount</strong></th><th><strong>Description</strong></th></tr></thead>
                <tbody>@foreach($estate->debts as $debt)<tr><td>{{ $debt->creditor_name }}</td><td>{{ $debt->debt_type ?? 'Debt' }}</td><td>RM {{ number_format($debt->amount, 2) }}</td><td>{{ $debt->description ?? '-' }}</td></tr>@endforeach</tbody>
            </table>
        @else
            <p style="color:#94a3b8;font-size:12px;">No debts registered.</p>
        @endif

        <div class="pdf-section-title">Faraid Heirs Distribution</div>
        @if(isset($estate) && $estate->heirs->count() > 0)
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>NRIC</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @foreach($estate->heirs as $heir)
                        <tr><td>{{ $heir->name }}</td><td>{{ $heir->nric ?? '-' }}</td><td>{{ ucfirst(str_replace('_', ' ', $heir->relationship)) }}</td><td><strong>{{ number_format($heir->share_percentage ?? 0, 2) }}%</strong></td><td>RM {{ number_format((($heir->share_percentage ?? 0) / 100) * $remainingForHeirs, 2) }}</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="3">Total</td><td>{{ number_format($totalHeirPct, 2) }}%</td><td>RM {{ number_format($remainingForHeirs, 2) }}</td></tr>
                </tbody>
            </table>
        @else
            <p style="color:#94a3b8;font-size:12px;">No heirs registered.</p>
        @endif

        @if(isset($estate) && $estate->wasiyyah->count() > 0)
            <div class="pdf-section-title">Wasiyyah Beneficiaries</div>
            <table class="pdf-info-table">
                <thead><tr style="background:#f8fafc;"><th><strong>Name</strong></th><th><strong>Relationship</strong></th><th><strong>Share</strong></th><th><strong>Amount</strong></th></tr></thead>
                <tbody>
                    @foreach($estate->wasiyyah as $item)
                        <tr><td>{{ $item->beneficiary_name }}</td><td>{{ $item->relationship }}</td><td><strong>{{ number_format($item->requested_percentage, 2) }}%</strong></td><td>RM {{ number_format(($item->requested_percentage / 100) * $netEstate, 2) }}</td></tr>
                    @endforeach
                    <tr style="background:#f1f5f9;font-weight:700;"><td colspan="2">Total</td><td>{{ number_format($totalWasiyyahPct, 2) }}%</td><td>RM {{ number_format(($totalWasiyyahPct / 100) * $netEstate, 2) }}</td></tr>
                </tbody>
            </table>
        @endif

        <div style="margin-top:20px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:10px;color:#94a3b8;text-align:center;">
            <p>This is a system-generated document. Generated on {{ now()->format('d F Y, h:i A') }} | Document ID: {{ $estate->unique_id ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<script>
    (function() {
        // Auto-trigger PDF download as attachment if needed
        // This function can be called by the backend to attach the PDF
        window.generatePDFAttachment = function() {
            const pdfContent = document.getElementById('pdfContent');
            if (!pdfContent) return '';
            return pdfContent.innerHTML;
        };
    })();
</script>