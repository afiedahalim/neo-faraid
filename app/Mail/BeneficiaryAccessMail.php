<?php

namespace App\Mail;

use App\Models\EstatePreRegistration;
use App\Models\BeneficiaryAccessLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BeneficiaryAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public EstatePreRegistration $estate;
    public BeneficiaryAccessLink $link;
    public $beneficiary;
    public string $beneficiaryType;
    public string $accessUrl;
    public string $expiryDate;
    public ?float $sharePercentage;
    public ?string $pdfPath;
    public ?string $videoPath;
    public bool $hasVideo;
    public string $formattedAmount;
    public string $viewUrl;

    /**
     * Create a new message instance.
     * 
     * @param EstatePreRegistration $estate
     * @param BeneficiaryAccessLink $link
     * @param mixed $beneficiary
     * @param string $beneficiaryType
     */
    public function __construct(
        EstatePreRegistration $estate,
        BeneficiaryAccessLink $link,
        $beneficiary,
        string $beneficiaryType
    ) {
        $this->estate = $estate;
        $this->link = $link;
        $this->beneficiary = $beneficiary;
        $this->beneficiaryType = $beneficiaryType;
        
        // Use the correct route for beneficiary access
        $this->accessUrl = route('beneficiary.access', ['token' => $link->access_token]);
        $this->viewUrl = route('estate.secure-view', ['token' => $link->access_token]);
        $this->expiryDate = $link->expires_at->format('d F Y');
        $this->sharePercentage = $this->getSharePercentage();
        $this->formattedAmount = $this->calculateFormattedAmount();
        $this->pdfPath = $this->generatePDF();
        $this->videoPath = $this->getVideoPath();
        $this->hasVideo = !is_null($this->videoPath) && file_exists($this->videoPath);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->beneficiaryType) {
            'heir' => 'Inheritance Distribution Notification - ' . $this->estate->deceased_name,
            'trustee' => 'Trustee Appointment - Estate of ' . $this->estate->deceased_name,
            'alternate_trustee' => 'Alternate Trustee Appointment - Estate of ' . $this->estate->deceased_name,
            'wasiyyah' => 'Wasiyyah (Will) Notification - ' . $this->estate->deceased_name,
            default => 'Estate Planning Notification',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = match($this->beneficiaryType) {
            'heir' => 'emails.beneficiary.heir-access',
            'trustee' => 'emails.beneficiary.trustee-access',
            'alternate_trustee' => 'emails.beneficiary.trustee-access',
            'wasiyyah' => 'emails.beneficiary.wasiyyah-access',
            default => 'emails.beneficiary.heir-access',
        };

        // Calculate estate values for email view
        $totalAssets = $this->estate->assets()->sum('value');
        $totalDebts = $this->estate->debts()->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        $totalWasiyyahPct = $this->estate->wasiyyah()->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
        $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);

        return new Content(
            view: $view,
            with: [
                'estate' => $this->estate,
                'deceasedName' => $this->estate->deceased_name,
                'deceasedNric' => $this->maskNric($this->estate->deceased_nric),
                'beneficiaryName' => $this->link->beneficiary_name,
                'beneficiaryEmail' => $this->link->beneficiary_email,
                'beneficiaryType' => $this->beneficiaryType,
                'beneficiaryTypeLabel' => $this->getBeneficiaryTypeLabel(),
                'accessUrl' => $this->accessUrl,
                'viewUrl' => $this->viewUrl,
                'expiryDate' => $this->expiryDate,
                'sharePercentage' => $this->sharePercentage,
                'formattedAmount' => $this->formattedAmount,
                'totalAssets' => $totalAssets,
                'formattedTotalAssets' => 'RM ' . number_format($totalAssets, 2),
                'totalDebts' => $totalDebts,
                'formattedTotalDebts' => 'RM ' . number_format($totalDebts, 2),
                'netEstate' => $netEstate,
                'formattedNetEstate' => 'RM ' . number_format($netEstate, 2),
                'remainingForHeirs' => $remainingForHeirs,
                'formattedRemainingForHeirs' => 'RM ' . number_format($remainingForHeirs, 2),
                'totalWasiyyahPct' => $totalWasiyyahPct,
                'wasiyyahAmount' => $wasiyyahAmount,
                'formattedWasiyyahAmount' => 'RM ' . number_format($wasiyyahAmount, 2),
                'trusteeName' => $this->estate->trustee_name,
                'trusteeEmail' => $this->estate->trustee_email,
                'trusteePhone' => $this->estate->trustee_phone,
                'estateReference' => $this->estate->unique_id,
                'hasVideo' => $this->hasVideo,
                'videoType' => $this->estate->will_video_type ?? 'none',
                'videoUrl' => $this->estate->will_video_url,
                'isShariahCompliant' => $this->estate->is_shariah_compliant ?? true,
                'generatedAt' => now()->format('d F Y, h:i A'),
                'supportEmail' => config('mail.support.address', 'neofaraidadmin@gmail.com'),
                'supportPhone' => config('app.support_phone', '+60 1-234-56-7890'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     * 
     * Attaches PDF for ALL beneficiaries, and video for ALL beneficiaries (if uploaded)
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach PDF to ALL beneficiaries (always)
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $attachments[] = Attachment::fromPath($this->pdfPath)
                ->as('Estate_Distribution_' . $this->estate->unique_id . '.pdf')
                ->withMime('application/pdf');
        }

        // Attach Video to ALL beneficiaries (if video exists and is uploaded file)
        if ($this->hasVideo && $this->videoPath && file_exists($this->videoPath)) {
            $extension = pathinfo($this->videoPath, PATHINFO_EXTENSION);
            $mimeType = match(strtolower($extension)) {
                'mp4' => 'video/mp4',
                'mov' => 'video/quicktime',
                'avi' => 'video/x-msvideo',
                'webm' => 'video/webm',
                'mkv' => 'video/x-matroska',
                'mpg', 'mpeg' => 'video/mpeg',
                default => 'video/mp4',
            };
            
            $attachments[] = Attachment::fromPath($this->videoPath)
                ->as('Will_Video_' . $this->estate->unique_id . '.' . $extension)
                ->withMime($mimeType);
        }

        return $attachments;
    }

    /**
     * Legacy build method for Laravel 7/8 compatibility
     */
    public function build()
    {
        $view = match($this->beneficiaryType) {
            'heir' => 'emails.beneficiary.heir-access',
            'trustee' => 'emails.beneficiary.trustee-access',
            'alternate_trustee' => 'emails.beneficiary.trustee-access',
            'wasiyyah' => 'emails.beneficiary.wasiyyah-access',
            default => 'emails.beneficiary.heir-access',
        };

        $totalAssets = $this->estate->assets()->sum('value');
        $totalDebts = $this->estate->debts()->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        $totalWasiyyahPct = $this->estate->wasiyyah()->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
        $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);

        $mail = $this->subject($this->getSubject())
            ->view($view)
            ->with([
                'estate' => $this->estate,
                'deceasedName' => $this->estate->deceased_name,
                'deceasedNric' => $this->maskNric($this->estate->deceased_nric),
                'beneficiaryName' => $this->link->beneficiary_name,
                'beneficiaryEmail' => $this->link->beneficiary_email,
                'beneficiaryType' => $this->beneficiaryType,
                'beneficiaryTypeLabel' => $this->getBeneficiaryTypeLabel(),
                'accessUrl' => $this->accessUrl,
                'viewUrl' => $this->viewUrl,
                'expiryDate' => $this->expiryDate,
                'sharePercentage' => $this->sharePercentage,
                'formattedAmount' => $this->formattedAmount,
                'totalAssets' => $totalAssets,
                'formattedTotalAssets' => 'RM ' . number_format($totalAssets, 2),
                'totalDebts' => $totalDebts,
                'formattedTotalDebts' => 'RM ' . number_format($totalDebts, 2),
                'netEstate' => $netEstate,
                'formattedNetEstate' => 'RM ' . number_format($netEstate, 2),
                'remainingForHeirs' => $remainingForHeirs,
                'formattedRemainingForHeirs' => 'RM ' . number_format($remainingForHeirs, 2),
                'totalWasiyyahPct' => $totalWasiyyahPct,
                'wasiyyahAmount' => $wasiyyahAmount,
                'formattedWasiyyahAmount' => 'RM ' . number_format($wasiyyahAmount, 2),
                'trusteeName' => $this->estate->trustee_name,
                'trusteeEmail' => $this->estate->trustee_email,
                'trusteePhone' => $this->estate->trustee_phone,
                'estateReference' => $this->estate->unique_id,
                'hasVideo' => $this->hasVideo,
                'videoType' => $this->estate->will_video_type ?? 'none',
                'videoUrl' => $this->estate->will_video_url,
                'isShariahCompliant' => $this->estate->is_shariah_compliant ?? true,
                'generatedAt' => now()->format('d F Y, h:i A'),
                'supportEmail' => config('mail.support.address', 'neofaraidadmin@gmail.com'),
                'supportPhone' => config('app.support_phone', '+60 1-234-56-7890'),
            ]);

        // Attach PDF if available
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $mail->attach($this->pdfPath, [
                'as' => 'Estate_Distribution_' . $this->estate->unique_id . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        // Attach video if available and is uploaded file
        if ($this->hasVideo && $this->videoPath && file_exists($this->videoPath)) {
            $extension = pathinfo($this->videoPath, PATHINFO_EXTENSION);
            $mimeType = match(strtolower($extension)) {
                'mp4' => 'video/mp4',
                'mov' => 'video/quicktime',
                'avi' => 'video/x-msvideo',
                'webm' => 'video/webm',
                default => 'video/mp4',
            };
            
            $mail->attach($this->videoPath, [
                'as' => 'Will_Video_' . $this->estate->unique_id . '.' . $extension,
                'mime' => $mimeType,
            ]);
        }

        return $mail;
    }

    /**
     * Get the email subject
     */
    protected function getSubject(): string
    {
        return match($this->beneficiaryType) {
            'heir' => 'Inheritance Distribution Notification - ' . $this->estate->deceased_name,
            'trustee' => 'Trustee Appointment - Estate of ' . $this->estate->deceased_name,
            'alternate_trustee' => 'Alternate Trustee Appointment - Estate of ' . $this->estate->deceased_name,
            'wasiyyah' => 'Wasiyyah (Will) Notification - ' . $this->estate->deceased_name,
            default => 'Estate Planning Notification - ' . $this->estate->deceased_name,
        };
    }

    /**
     * Get beneficiary type label
     */
    protected function getBeneficiaryTypeLabel(): string
    {
        return match($this->beneficiaryType) {
            'heir' => 'Faraid Heir',
            'trustee' => 'Trustee',
            'alternate_trustee' => 'Alternate Trustee',
            'wasiyyah' => 'Wasiyyah Beneficiary',
            default => 'Beneficiary',
        };
    }

    /**
     * Get share percentage for the beneficiary
     */
    protected function getSharePercentage(): ?float
    {
        if ($this->beneficiaryType === 'heir') {
            $heir = $this->estate->heirs()->find($this->link->beneficiary_id);
            return $heir?->share_percentage ? (float) $heir->share_percentage : null;
        }
        
        if ($this->beneficiaryType === 'wasiyyah') {
            $wasiyyah = $this->estate->wasiyyah()->find($this->link->beneficiary_id);
            return $wasiyyah?->requested_percentage ? (float) $wasiyyah->requested_percentage : null;
        }
        
        return null;
    }

    /**
     * Calculate formatted amount for the beneficiary
     */
    protected function calculateFormattedAmount(): string
    {
        $totalAssets = $this->estate->assets()->sum('value');
        $totalDebts = $this->estate->debts()->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        $totalWasiyyahPct = $this->estate->wasiyyah()->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
        $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);
        
        if ($this->beneficiaryType === 'heir' && $this->sharePercentage) {
            $amount = ($this->sharePercentage / 100) * $remainingForHeirs;
            return 'RM ' . number_format($amount, 2);
        }
        
        if ($this->beneficiaryType === 'wasiyyah' && $this->sharePercentage) {
            $amount = ($this->sharePercentage / 100) * $netEstate;
            return 'RM ' . number_format($amount, 2);
        }
        
        return 'RM 0.00';
    }

    /**
     * Get video file path for attachment
     */
    protected function getVideoPath(): ?string
    {
        // Check if video exists and is uploaded file (not YouTube link)
        if (!$this->estate->will_video_url || $this->estate->will_video_type !== 'upload') {
            return null;
        }

        $url = $this->estate->will_video_url;
        
        // Handle storage URLs (public disk)
        if (str_contains($url, '/storage/')) {
            $relativePath = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            $fullPath = storage_path('app/public/' . $relativePath);
            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }
        
        // Handle direct file paths
        if (file_exists($url)) {
            return $url;
        }
        
        // Try to find in private storage
        if (Storage::disk('private')->exists($url)) {
            $tempPath = storage_path('app/temp/video_' . time() . '_' . basename($url));
            $content = Storage::disk('private')->get($url);
            file_put_contents($tempPath, $content);
            return $tempPath;
        }
        
        return null;
    }

    /**
     * Generate PDF for attachment
     */
    protected function generatePDF(): ?string
    {
        try {
            // Get data for PDF
            $totalAssets = $this->estate->assets()->sum('value');
            $totalDebts = $this->estate->debts()->sum('amount');
            $netEstate = max(0, $totalAssets - $totalDebts);
            $totalHeirPct = $this->estate->heirs()->sum('share_percentage');
            $totalWasiyyahPct = $this->estate->wasiyyah()->sum('requested_percentage');
            $maxWasiyyahPct = 33.33;
            $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
            $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
            $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);
            
            // Calculate beneficiary amount
            $beneficiaryAmount = 0;
            if ($this->beneficiaryType === 'heir' && $this->sharePercentage) {
                $beneficiaryAmount = ($this->sharePercentage / 100) * $remainingForHeirs;
            } elseif ($this->beneficiaryType === 'wasiyyah' && $this->sharePercentage) {
                $beneficiaryAmount = ($this->sharePercentage / 100) * $netEstate;
            }

            // Prepare heirs list
            $heirsList = [];
            foreach ($this->estate->heirs as $heir) {
                $heirsList[] = [
                    'name' => $heir->name,
                    'relationship' => $heir->relationship_label ?? $heir->relationship,
                    'percentage' => (float) $heir->share_percentage,
                    'amount' => ((float) $heir->share_percentage / 100) * $remainingForHeirs,
                ];
            }
            
            // Prepare wasiyyah list
            $wasiyyahList = [];
            foreach ($this->estate->wasiyyah as $was) {
                $wasiyyahList[] = [
                    'name' => $was->beneficiary_name,
                    'relationship' => $was->relationship,
                    'percentage' => (float) $was->requested_percentage,
                    'amount' => ((float) $was->requested_percentage / 100) * $netEstate,
                ];
            }
            
            // Prepare assets list
            $assetsList = [];
            foreach ($this->estate->assets as $asset) {
                $ownedValue = (float) $asset->value * (($asset->ownership_percentage ?? 100) / 100);
                $assetsList[] = [
                    'name' => $asset->name,
                    'type' => $asset->type ?? 'Other',
                    'value' => (float) $asset->value,
                    'formatted_value' => 'RM ' . number_format($asset->value, 2),
                    'ownership_percentage' => (float) ($asset->ownership_percentage ?? 100),
                    'owned_value' => $ownedValue,
                ];
            }
            
            // Prepare debts list
            $debtsList = [];
            foreach ($this->estate->debts as $debt) {
                $remaining = (float) $debt->amount - (float) ($debt->amount_paid ?? 0);
                $debtsList[] = [
                    'creditor_name' => $debt->creditor_name,
                    'type' => $debt->type ?? $debt->debt_type ?? 'Other',
                    'amount' => (float) $debt->amount,
                    'remaining' => max(0, $remaining),
                    'formatted_remaining' => 'RM ' . number_format(max(0, $remaining), 2),
                ];
            }

            // Generate PDF
            $pdf = Pdf::loadView('pdfs.estate-distribution', [
                'estate' => $this->estate,
                'totalAssets' => $totalAssets,
                'formattedTotalAssets' => 'RM ' . number_format($totalAssets, 2),
                'totalDebts' => $totalDebts,
                'formattedTotalDebts' => 'RM ' . number_format($totalDebts, 2),
                'netEstate' => $netEstate,
                'formattedNetEstate' => 'RM ' . number_format($netEstate, 2),
                'totalHeirPct' => $totalHeirPct,
                'totalWasiyyahPct' => $totalWasiyyahPct,
                'effectiveWasiyyahPct' => $effectiveWasiyyahPct,
                'wasiyyahAmount' => $wasiyyahAmount,
                'formattedWasiyyahAmount' => 'RM ' . number_format($wasiyyahAmount, 2),
                'remainingForHeirs' => $remainingForHeirs,
                'formattedRemainingForHeirs' => 'RM ' . number_format($remainingForHeirs, 2),
                'beneficiary' => [
                    'name' => $this->link->beneficiary_name,
                    'type' => $this->beneficiaryType,
                    'type_label' => $this->getBeneficiaryTypeLabel(),
                    'share_percentage' => $this->sharePercentage,
                    'amount' => $beneficiaryAmount,
                    'formatted_amount' => 'RM ' . number_format($beneficiaryAmount, 2),
                ],
                'heirsList' => $heirsList,
                'wasiyyahList' => $wasiyyahList,
                'assetsList' => $assetsList,
                'debtsList' => $debtsList,
                'trusteeName' => $this->estate->trustee_name,
                'trusteeEmail' => $this->estate->trustee_email,
                'trusteePhone' => $this->estate->trustee_phone,
                'deceasedName' => $this->estate->deceased_name,
                'deceasedNric' => $this->maskNric($this->estate->deceased_nric),
                'estateReference' => $this->estate->unique_id,
                'generated_at' => now()->format('d F Y, h:i A'),
                'document_id' => 'EST-' . $this->estate->id . '-' . now()->format('Ymd'),
                'watermark' => 'FOR VERIFIED RECIPIENTS ONLY - CONFIDENTIAL',
                'legal_disclaimer' => $this->getLegalDisclaimer(),
                'shariah_compliance' => $this->getShariahComplianceStatement(),
            ]);

            // Create temp directory if it doesn't exist
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Save to temporary file
            $filename = 'estate_' . $this->estate->unique_id . '_' . time() . '.pdf';
            $path = $tempDir . '/' . $filename;
            
            file_put_contents($path, $pdf->output());
            
            Log::info('PDF generated for beneficiary email', [
                'estate_id' => $this->estate->id,
                'beneficiary_type' => $this->beneficiaryType,
                'beneficiary_email' => $this->link->beneficiary_email,
                'pdf_path' => $path,
                'pdf_size' => filesize($path),
            ]);
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('PDF generation failed for beneficiary email', [
                'estate_id' => $this->estate->id,
                'beneficiary_type' => $this->beneficiaryType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Mask NRIC for privacy
     */
    protected function maskNric(?string $nric): string
    {
        if (empty($nric)) {
            return 'N/A';
        }
        
        $clean = preg_replace('/[^0-9]/', '', $nric);
        
        if (strlen($clean) >= 8) {
            return '******-' . substr($clean, -4, 2) . '-' . substr($clean, -2);
        }
        
        return '******';
    }

    /**
     * Get legal disclaimer text
     */
    protected function getLegalDisclaimer(): string
    {
        return "This document is a computer-generated inheritance distribution report based on Islamic Faraid law. " .
               "It is intended for informational purposes only and should not be considered as legal advice. " .
               "For legal binding purposes, please consult with a qualified Islamic inheritance lawyer (Peguam Syarie) " .
               "or your local Shariah Court (Mahkamah Syariah).";
    }

    /**
     * Get Shariah compliance statement
     */
    protected function getShariahComplianceStatement(): string
    {
        return "This inheritance distribution has been calculated in accordance with Islamic Faraid law " .
               "as prescribed in the Quran (Surah An-Nisa, verses 11-12 and 176) and the Sunnah of Prophet Muhammad (ﷺ).";
    }

    /**
     * Clean up temporary files after email is sent
     */
    public function __destruct()
    {
        // Clean up temporary PDF file
        if ($this->pdfPath && file_exists($this->pdfPath) && str_contains($this->pdfPath, '/temp/')) {
            try {
                unlink($this->pdfPath);
                Log::debug('Temporary PDF file cleaned up', ['path' => $this->pdfPath]);
            } catch (\Exception $e) {
                Log::warning('Failed to clean up temporary PDF file', ['path' => $this->pdfPath, 'error' => $e->getMessage()]);
            }
        }
        
        // Clean up temporary video file
        if ($this->videoPath && file_exists($this->videoPath) && str_contains($this->videoPath, '/temp/')) {
            try {
                unlink($this->videoPath);
                Log::debug('Temporary video file cleaned up', ['path' => $this->videoPath]);
            } catch (\Exception $e) {
                Log::warning('Failed to clean up temporary video file', ['path' => $this->videoPath, 'error' => $e->getMessage()]);
            }
        }
    }
}