<?php

namespace App\Services;

use App\Models\EstatePreRegistration;
use App\Models\InstantEstateSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class PdfGeneratorService
{
    /**
     * Security watermark text applied to all generated PDFs
     */
    protected const WATERMARK_TEXT = 'FOR VERIFIED RECIPIENTS ONLY - CONFIDENTIAL';
    
    /**
     * Document security classification
     */
    protected const SECURITY_CLASSIFICATION = 'RESTRICTED - NEO FARAID INHERITANCE DOCUMENT';

    /**
     * Generate inheritance distribution PDF from InstantEstateSession
     * 
     * @param InstantEstateSession $session The instant estate session
     * @return string Raw PDF content
     * @throws \Exception
     */
    public function generateInheritanceDistributionPdfForSession(InstantEstateSession $session): string
    {
        $reportData = $session->report_data;
        
        if (!$reportData) {
            Log::error('No report data found for session', [
                'session_id' => $session->session_id,
                'status' => $session->status,
            ]);
            throw new \Exception('No report data found for session');
        }
        
        Log::info('Generating inheritance distribution PDF from session', [
            'session_id' => $session->session_id,
            'report_type' => $reportData['report_type'] ?? 'unknown',
        ]);
        
        // Prepare data for the view
        $viewData = $this->prepareViewData($reportData, $session);
        
        // Check if view exists
        if (!view()->exists('instant-estate.report-estate-plan')) {
            Log::error('View instant-estate.report-estate-plan does not exist');
            // Fall back to simple PDF generation
            return $this->generateSimplePdfReport($viewData);
        }
        
        try {
            $pdf = Pdf::loadView('instant-estate.report-estate-plan', $viewData);
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'isPhpEnabled' => false,
                'isJavascriptEnabled' => false,
                'dpi' => 150,
            ]);
            
            $pdfContent = $pdf->output();
            
            Log::info('PDF generated successfully from session', [
                'session_id' => $session->session_id,
                'pdf_size' => strlen($pdfContent),
            ]);
            
            // Save PDF for future use
            $pdfPath = 'estates/' . $session->session_id . '/report_' . now()->format('Ymd_His') . '.pdf';
            Storage::disk('private')->put($pdfPath, $pdfContent);
            $session->update([
                'report_pdf_path' => $pdfPath,
                'has_pdf_report' => true,
            ]);
            
            return $pdfContent;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF from session', [
                'session_id' => $session->session_id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF from estate pre-registration data
     * 
     * @param EstatePreRegistration $estate The estate pre-registration model
     * @return string Raw PDF content
     */
    public function generatePdfFromEstate(EstatePreRegistration $estate): string
    {
        // Load related data
        $estate->load(['heirs', 'assets', 'debts', 'wasiyyah']);
        
        Log::info('Generating PDF from estate pre-registration', [
            'estate_id' => $estate->id,
            'unique_id' => $estate->unique_id,
        ]);
        
        // Prepare data for the view
        $viewData = $this->prepareEstateViewData($estate);
        
        // Check if view exists
        if (!view()->exists('instant-estate.report-estate-plan')) {
            Log::error('View instant-estate.report-estate-plan does not exist');
            return $this->generateSimplePdfReport($viewData);
        }
        
        try {
            $pdf = Pdf::loadView('instant-estate.report-estate-plan', $viewData);
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'isPhpEnabled' => false,
                'isJavascriptEnabled' => false,
                'dpi' => 150,
            ]);
            
            $pdfContent = $pdf->output();
            
            Log::info('PDF generated successfully from estate', [
                'estate_id' => $estate->id,
                'pdf_size' => strlen($pdfContent),
            ]);
            
            return $pdfContent;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF from estate', [
                'estate_id' => $estate->id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to generate PDF from estate: ' . $e->getMessage());
        }
    }

    /**
     * Prepare view data for report-estate-plan.blade.php from session
     * 
     * @param array $reportData
     * @param InstantEstateSession $session
     * @return array
     */
    private function prepareViewData(array $reportData, InstantEstateSession $session): array
    {
        // Calculate financial summaries
        $assets = $this->normalizeAssets($reportData['assets'] ?? []);
        $debts = $this->normalizeDebts($reportData['debts'] ?? []);
        $heirs = $this->normalizeHeirs($reportData['heirs'] ?? []);
        $wasiyyah = $this->normalizeWasiyyah($reportData['wasiyyah'] ?? []);
        
        $totalAssets = $reportData['total_assets'] ?? collect($assets)->sum('value');
        $totalDebts = $reportData['total_debts'] ?? collect($debts)->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        
        $totalWasiyyahPct = $reportData['total_wasiyyah_pct'] ?? collect($wasiyyah)->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $wasiyyahAmount = ($totalWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = $netEstate - $wasiyyahAmount;
        $totalHeirPct = $reportData['total_heir_pct'] ?? collect($heirs)->sum('share_percentage');
        
        // Get deceased information
        $deceasedName = $reportData['deceased_name'] ?? $session->deceased_name ?? 'N/A';
        $deceasedNric = $reportData['deceased_nric'] ?? $session->deceased_nric ?? 'N/A';
        $deceasedGender = $reportData['deceased_gender'] ?? $session->gender ?? 'N/A';
        $deceasedDob = $this->formatDate($reportData['deceased_dob'] ?? $session->date_of_birth ?? null);
        $dateOfDeath = $this->formatDate($reportData['date_of_death'] ?? $session->death_date ?? null);
        $deceasedAddress = $reportData['deceased_address'] ?? $session->residential_address ?? 'N/A';
        
        // Get trustee information
        $trusteeName = $reportData['trustee_name'] ?? $session->trustee_name ?? null;
        $trusteeNric = $reportData['trustee_nric'] ?? $session->trustee_nric ?? null;
        $trusteePhone = $reportData['trustee_phone'] ?? $session->trustee_phone ?? null;
        $trusteeEmail = $reportData['trustee_email'] ?? $session->trustee_email ?? null;
        $trusteeRelationship = $reportData['trustee_relationship'] ?? $session->trustee_relationship ?? 'N/A';
        
        // Document metadata
        $documentId = $reportData['estate_unique_id'] ?? $session->unique_id ?? 'NFR-' . now()->format('Ymd') . '-' . substr($session->session_id, 0, 8);
        $expiryDate = now()->addDays(30)->format('d F Y');
        
        // Create estate object that Blade expects
        $estateObject = (object) [
            'assets' => collect($assets),
            'debts' => collect($debts),
            'heirs' => collect($heirs),
            'wasiyyah' => collect($wasiyyah),
            'deceased_name' => $deceasedName,
            'deceased_nric' => $this->maskNric($deceasedNric),
            'gender' => $deceasedGender,
            'date_of_birth' => $deceasedDob,
            'date_of_death' => $dateOfDeath,
            'address' => $deceasedAddress,
            'trustee_name' => $trusteeName,
            'trustee_nric' => $trusteeNric,
            'trustee_phone' => $trusteePhone,
            'trustee_email' => $trusteeEmail,
            'trustee_relationship' => $trusteeRelationship,
            'unique_id' => $documentId,
            'status' => $reportData['status'] ?? 'completed',
        ];
        
        return [
            'estate' => $estateObject,
            'total_assets' => $totalAssets,
            'total_debts' => $totalDebts,
            'net_estate' => $netEstate,
            'total_wasiyyah_pct' => $totalWasiyyahPct,
            'total_heir_pct' => $totalHeirPct,
            'wasiyyah_amount' => $wasiyyahAmount,
            'remaining_for_heirs' => $remainingForHeirs,
            'deceased_name' => $deceasedName,
            'deceased_nric' => $this->maskNric($deceasedNric),
            'deceased_gender' => $deceasedGender,
            'deceased_dob' => $deceasedDob,
            'deceased_address' => $deceasedAddress,
            'date_of_death' => $dateOfDeath,
            'trustee_name' => $trusteeName,
            'trustee_nric' => $trusteeNric,
            'trustee_phone' => $trusteePhone,
            'trustee_email' => $trusteeEmail,
            'trustee_relationship' => $trusteeRelationship,
            'document_id' => $documentId,
            'expiryDate' => $expiryDate,
            'assets' => collect($assets),
            'debts' => collect($debts),
            'heirs' => collect($heirs),
            'wasiyyah' => collect($wasiyyah),
        ];
    }

    /**
     * Prepare view data for report-estate-plan.blade.php from estate model
     * 
     * @param EstatePreRegistration $estate
     * @return array
     */
    private function prepareEstateViewData(EstatePreRegistration $estate): array
    {
        // Calculate financial summaries
        $totalAssets = $estate->assets->sum('value');
        $totalDebts = $estate->debts->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        
        $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $wasiyyahAmount = ($totalWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = $netEstate - $wasiyyahAmount;
        $totalHeirPct = $estate->heirs->sum('share_percentage');
        
        // Deceased information
        $deceasedDob = $estate->date_of_birth ? $estate->date_of_birth->format('d F Y') : 'N/A';
        $dateOfDeath = $estate->date_of_death ? $estate->date_of_death->format('d F Y') : 'Not specified';
        
        // Document metadata
        $documentId = $estate->unique_id ?? 'EST-' . $estate->id . '-' . now()->format('Ymd');
        $expiryDate = now()->addDays(30)->format('d F Y');
        
        return [
            'estate' => $estate,
            'total_assets' => $totalAssets,
            'total_debts' => $totalDebts,
            'net_estate' => $netEstate,
            'total_wasiyyah_pct' => $totalWasiyyahPct,
            'total_heir_pct' => $totalHeirPct,
            'wasiyyah_amount' => $wasiyyahAmount,
            'remaining_for_heirs' => $remainingForHeirs,
            'deceased_name' => $estate->deceased_name,
            'deceased_nric' => $this->maskNric($estate->deceased_nric),
            'deceased_gender' => $estate->gender,
            'deceased_dob' => $deceasedDob,
            'deceased_address' => $estate->address ?? 'N/A',
            'date_of_death' => $dateOfDeath,
            'trustee_name' => $estate->trustee_name,
            'trustee_nric' => $estate->trustee_nric,
            'trustee_phone' => $estate->trustee_phone,
            'trustee_email' => $estate->trustee_email,
            'trustee_relationship' => $estate->trustee_relationship ?? 'N/A',
            'document_id' => $documentId,
            'expiryDate' => $expiryDate,
            'assets' => $estate->assets,
            'debts' => $estate->debts,
            'heirs' => $estate->heirs,
            'wasiyyah' => $estate->wasiyyah,
        ];
    }

    /**
     * Normalize assets data to consistent object format
     * 
     * @param array $assets
     * @return array
     */
    private function normalizeAssets(array $assets): array
    {
        return array_map(function ($asset) {
            if (is_object($asset)) {
                return [
                    'name' => $asset->name ?? 'N/A',
                    'value' => $asset->value ?? 0,
                    'ownership_percentage' => $asset->ownership_percentage ?? 100,
                    'description' => $asset->description ?? '-',
                ];
            }
            return [
                'name' => $asset['name'] ?? $asset['asset_name'] ?? 'N/A',
                'value' => $asset['value'] ?? $asset['asset_value'] ?? 0,
                'ownership_percentage' => $asset['ownership_percentage'] ?? $asset['ownership'] ?? 100,
                'description' => $asset['description'] ?? '-',
            ];
        }, $assets);
    }

    /**
     * Normalize debts data to consistent object format
     * 
     * @param array $debts
     * @return array
     */
    private function normalizeDebts(array $debts): array
    {
        return array_map(function ($debt) {
            if (is_object($debt)) {
                return [
                    'creditor_name' => $debt->creditor_name ?? 'N/A',
                    'debt_type' => $debt->debt_type ?? 'Other',
                    'amount' => $debt->amount ?? 0,
                    'description' => $debt->description ?? '-',
                ];
            }
            return [
                'creditor_name' => $debt['creditor_name'] ?? $debt['creditor'] ?? 'N/A',
                'debt_type' => $debt['debt_type'] ?? $debt['type'] ?? 'Other',
                'amount' => $debt['amount'] ?? $debt['debt_amount'] ?? 0,
                'description' => $debt['description'] ?? '-',
            ];
        }, $debts);
    }

    /**
     * Normalize heirs data to consistent object format
     * 
     * @param array $heirs
     * @return array
     */
    private function normalizeHeirs(array $heirs): array
    {
        return array_map(function ($heir) {
            if (is_object($heir)) {
                return [
                    'name' => $heir->name ?? 'N/A',
                    'nric' => $heir->nric ?? '-',
                    'relationship' => $heir->relationship ?? 'N/A',
                    'share_percentage' => $heir->share_percentage ?? 0,
                    'share_fraction' => $heir->share_fraction ?? null,
                ];
            }
            return [
                'name' => $heir['name'] ?? $heir['heir_name'] ?? 'N/A',
                'nric' => $heir['nric'] ?? $heir['ic_number'] ?? '-',
                'relationship' => $heir['relationship'] ?? 'N/A',
                'share_percentage' => $heir['share_percentage'] ?? $heir['percentage'] ?? 0,
                'share_fraction' => $heir['share_fraction'] ?? $heir['fraction'] ?? null,
            ];
        }, $heirs);
    }

    /**
     * Normalize wasiyyah data to consistent object format
     * 
     * @param array $wasiyyah
     * @return array
     */
    private function normalizeWasiyyah(array $wasiyyah): array
    {
        return array_map(function ($item) {
            if (is_object($item)) {
                return [
                    'beneficiary_name' => $item->beneficiary_name ?? $item->name ?? 'N/A',
                    'beneficiary_nric' => $item->beneficiary_nric ?? '-',
                    'relationship' => $item->relationship ?? 'N/A',
                    'requested_percentage' => $item->requested_percentage ?? 0,
                    'is_charity' => $item->is_charity ?? false,
                ];
            }
            return [
                'beneficiary_name' => $item['beneficiary_name'] ?? $item['name'] ?? 'N/A',
                'beneficiary_nric' => $item['beneficiary_nric'] ?? $item['nric'] ?? '-',
                'relationship' => $item['relationship'] ?? 'N/A',
                'requested_percentage' => $item['requested_percentage'] ?? $item['percentage'] ?? 0,
                'is_charity' => $item['is_charity'] ?? $item['charity'] ?? false,
            ];
        }, $wasiyyah);
    }

    /**
     * Generate PDF for notification request
     * 
     * @param mixed $notificationRequest The notification request model
     * @return string|null Raw PDF content or null if generation fails
     */
    public function generatePdfForNotification($notificationRequest): ?string
    {
        try {
            // Find the associated session
            $session = null;
            
            if (isset($notificationRequest->instant_estate_session_id) && $notificationRequest->instant_estate_session_id) {
                $session = InstantEstateSession::find($notificationRequest->instant_estate_session_id);
            } elseif (isset($notificationRequest->session_id) && $notificationRequest->session_id) {
                $session = InstantEstateSession::where('session_id', $notificationRequest->session_id)->first();
            }
            
            if (!$session) {
                Log::error('No session found for notification', [
                    'notification_id' => $notificationRequest->id ?? 'unknown',
                ]);
                return null;
            }
            
            if (!$session->report_data) {
                Log::error('No report data in session', [
                    'session_id' => $session->session_id,
                ]);
                return null;
            }
            
            // Check if PDF already exists
            if ($session->has_pdf_report && $session->report_pdf_path && Storage::disk('private')->exists($session->report_pdf_path)) {
                $pdfContent = Storage::disk('private')->get($session->report_pdf_path);
                if ($pdfContent) {
                    Log::info('Using existing PDF for notification', [
                        'notification_id' => $notificationRequest->id ?? 'unknown',
                        'session_id' => $session->session_id,
                    ]);
                    return $pdfContent;
                }
            }
            
            // Generate new PDF
            return $this->generateInheritanceDistributionPdfForSession($session);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF for notification', [
                'notification_id' => $notificationRequest->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate a simple PDF report from raw data (fallback method)
     * 
     * @param array $data The report data
     * @return string Raw PDF content
     */
    public function generateSimplePdfReport(array $data): string
    {
        $deceasedName = $data['deceased_name'] ?? $data['estate']->deceased_name ?? 'the deceased';
        $heirs = $data['heirs'] ?? $data['estate']->heirs ?? [];
        $totalAssets = $data['total_assets'] ?? 0;
        $netEstate = $data['net_estate'] ?? $totalAssets;
        $dateOfDeath = $data['date_of_death'] ?? 'Not specified';
        $documentId = $data['document_id'] ?? 'NFR-' . now()->format('Ymd') . '-' . substr(uniqid(), 0, 8);
        
        $heirsHtml = '';
        if (count($heirs) > 0) {
            $heirsRows = '';
            foreach ($heirs as $heir) {
                $name = is_object($heir) ? ($heir->name ?? 'Unknown') : ($heir['name'] ?? 'Unknown');
                $relationship = is_object($heir) ? ($heir->relationship ?? 'N/A') : ($heir['relationship'] ?? 'N/A');
                $percentage = is_object($heir) ? ($heir->share_percentage ?? 0) : ($heir['share_percentage'] ?? $heir['percentage'] ?? 0);
                $amount = ($percentage / 100) * $netEstate;
                
                $heirsRows .= '<tr>
                    <td>' . htmlspecialchars($name) . '</td>
                    <td>' . htmlspecialchars($relationship) . '</td>
                    <td>' . number_format($percentage, 2) . '%</td>
                    <td>RM ' . number_format($amount, 2) . '</td>
                </tr>';
            }
            
            $heirsHtml = '
            <h2>Heirs Distribution (Faraid)</h2>
            <table>
                <thead>
                    <tr><th>Heir Name</th><th>Relationship</th><th>Share %</th><th>Amount (RM)</th></tr>
                </thead>
                <tbody>' . $heirsRows . '</tbody>
            </table>';
        }
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Inheritance Distribution Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 30px; line-height: 1.6; }
                h1 { color: #1a5fb4; border-bottom: 2px solid #1a5fb4; padding-bottom: 10px; }
                h2 { color: #2d7ad6; margin-top: 25px; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background: #1a5fb4; color: white; }
                .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
                .disclaimer { background: #fff3cd; padding: 15px; margin: 20px 0; border-left: 4px solid #ffc107; font-size: 11px; }
                .watermark { position: fixed; bottom: 20px; left: 0; right: 0; text-align: center; font-size: 8px; color: #ccc; opacity: 0.5; }
            </style>
        </head>
        <body>
            <div style="text-align:center;margin-bottom:20px;">
                <h1>Inheritance Distribution Report</h1>
                <p>Generated: ' . now()->format('d F Y, h:i:s A') . '</p>
            </div>
            
            <h2>Deceased Information</h2>
            <table>
                <tr><th>Name</th><td>' . htmlspecialchars($deceasedName) . '</td></tr>
                <tr><th>Date of Death</th><td>' . htmlspecialchars($dateOfDeath) . '</td></tr>
            </table>
            
            <h2>Financial Summary</h2>
            <table>
                <tr><th>Total Assets</th><td>RM ' . number_format($totalAssets, 2) . '</td></tr>
                <tr><th>Net Estate for Distribution</th><td>RM ' . number_format($netEstate, 2) . '</td></tr>
            </table>
            
            ' . $heirsHtml . '
            
            <div class="disclaimer">
                <strong>Disclaimer:</strong> This report is generated by Neo Faraid - Islamic Inheritance Calculator. 
                It is for informational purposes only. For legal binding purposes, consult with a qualified 
                Islamic inheritance lawyer (Peguam Syarie) or your Shariah Court (Mahkamah Syariah).
            </div>
            
            <div class="footer">
                Neo Faraid - Islamic Inheritance Calculator<br>
                Generated on: ' . now()->format('d F Y, h:i:s A') . ' | Document ID: ' . $documentId . '
            </div>
            <div class="watermark">' . self::WATERMARK_TEXT . '</div>
        </body>
        </html>';
        
        try {
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'sans-serif',
            ]);
            
            return $pdf->output();
            
        } catch (\Exception $e) {
            Log::error('Failed to generate simple PDF report', ['error' => $e->getMessage()]);
            throw new \Exception('Failed to generate simple PDF: ' . $e->getMessage());
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Format date for display
     * 
     * @param mixed $date
     * @return string
     */
    private function formatDate($date): string
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        if ($date instanceof \Carbon\Carbon) {
            return $date->format('d F Y');
        }
        
        if (is_string($date)) {
            try {
                return \Carbon\Carbon::parse($date)->format('d F Y');
            } catch (\Exception $e) {
                return $date;
            }
        }
        
        return 'N/A';
    }

    /**
     * Mask NRIC for privacy
     * 
     * @param string|null $nric
     * @return string
     */
    private function maskNric(?string $nric): string
    {
        if (empty($nric)) {
            return 'N/A';
        }
        
        $clean = preg_replace('/[^0-9]/', '', $nric);
        
        if (strlen($clean) >= 8) {
            return substr($clean, 0, 6) . '-' . substr($clean, 6, 2) . '-' . substr($clean, 8, 2);
        }
        
        if (strlen($clean) === 12) {
            return substr($clean, 0, 6) . '-' . substr($clean, 6, 2) . '-' . substr($clean, 8, 4);
        }
        
        return $nric;
    }

    /**
     * Validate that required PDF views exist
     * 
     * @return array List of missing views
     */
    public function validateViews(): array
    {
        $requiredViews = [
            'instant-estate.report-estate-plan',
        ];
        
        $missingViews = [];
        foreach ($requiredViews as $view) {
            if (!view()->exists($view)) {
                $missingViews[] = $view;
            }
        }
        
        if (!empty($missingViews)) {
            Log::warning('Missing PDF views detected', ['missing_views' => $missingViews]);
        }
        
        return $missingViews;
    }
}