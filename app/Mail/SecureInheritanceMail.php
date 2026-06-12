<?php

namespace App\Mail;

use App\Models\EstateNotification;
use App\Models\EstatePreRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class SecureInheritanceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The estate notification record
     */
    public EstateNotification $notification;

    /**
     * The secure access URL for the beneficiary
     */
    public string $secureUrl;

    /**
     * Recipient information
     */
    public array $recipient;

    /**
     * The estate pre-registration (if available)
     */
    public ?EstatePreRegistration $estate;

    /**
     * Email expiry timestamp
     */
    public Carbon $expiresAt;

    /**
     * Access token for tracking
     */
    public string $accessToken;

    /**
     * Security verification code
     */
    public string $verificationCode;

    /**
     * Create a new message instance.
     *
     * @param EstateNotification $notification
     * @param string $secureUrl
     * @param array $recipient
     */
    public function __construct(EstateNotification $notification, string $secureUrl, array $recipient)
    {
        $this->notification = $notification;
        $this->secureUrl = $secureUrl;
        $this->recipient = $recipient;
        $this->estate = $notification->estatePreRegistration;
        $this->expiresAt = $notification->expires_at ?? now()->addDays(30);
        $this->accessToken = $notification->notification_token;
        $this->verificationCode = $this->generateVerificationCode();
        
        // Store verification code
        $notification->update([
            'verification_code' => $this->verificationCode,
            'email_sent_at' => now(),
            'email_attempts' => ($notification->email_attempts ?? 0) + 1,
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $deceasedName = $this->estate->deceased_name ?? 
                        $this->notification->beneficiary_name ?? 
                        'the Deceased';
        
        $subject = "⚖️ Inheritance Distribution Notice - Estate of {$deceasedName}";
        
        // Add priority indicator if this is a retry
        if ($this->notification->email_attempts > 1) {
            $subject = "[Retry #{$this->notification->email_attempts}] {$subject}";
        }
        
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'noreply@neofaraid.com'),
                config('mail.from.name', 'Neo Faraid System')
            ),
            replyTo: [
                new Address(
                    config('mail.reply_to.address', 'support@neofaraid.com'),
                    config('mail.reply_to.name', 'Neo Faraid Support')
                ),
            ],
            subject: $subject,
            tags: ['inheritance', 'faraid', 'estate-distribution', 'confidential'],
            metadata: [
                'notification_id' => $this->notification->id,
                'beneficiary_type' => $this->recipient['type'] ?? 'unknown',
                'estate_unique_id' => $this->estate->unique_id ?? null,
                'email_attempt' => $this->notification->email_attempts ?? 1,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $viewData = $this->prepareViewData();
        
        return new Content(
            view: $this->getEmailTemplate(),
            with: $viewData,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // Attach a summary PDF if available and appropriate
        if ($this->shouldAttachPdf()) {
            $pdfPath = $this->generateSummaryPdf();
            
            if ($pdfPath) {
                $attachments[] = Attachment::fromPath($pdfPath)
                    ->as($this->getPdfFilename())
                    ->withMime('application/pdf');
            }
        }
        
        return $attachments;
    }

    /**
     * Prepare all data for the email view
     */
    protected function prepareViewData(): array
    {
        $deceasedName = $this->estate->deceased_name ?? 'the Deceased';
        $deceasedNric = $this->maskSensitive($this->estate->deceased_nric ?? 'N/A');
        
        // Calculate time until expiry
        $daysRemaining = max(0, (int) now()->diffInDays($this->expiresAt));
        $hoursRemaining = max(0, (int) now()->diffInHours($this->expiresAt));
        
        // Determine beneficiary-specific information
        $beneficiaryInfo = $this->getBeneficiarySpecificInfo();
        
        return [
            // Recipient info
            'recipient_name' => $this->recipient['name'] ?? 'Beneficiary',
            'recipient_type' => $this->recipient['type'] ?? 'beneficiary',
            'recipient_relationship' => $this->recipient['relationship'] ?? null,
            
            // Estate info
            'deceased_name' => $deceasedName,
            'deceased_nric' => $deceasedNric,
            'estate_unique_id' => $this->estate->unique_id ?? null,
            
            // Access info
            'secure_url' => $this->secureUrl,
            'access_token' => $this->maskToken($this->accessToken),
            'verification_code' => $this->verificationCode,
            'expires_at' => $this->expiresAt->format('d F Y, h:i A'),
            'expires_in_days' => $daysRemaining,
            'expires_in_hours' => $hoursRemaining,
            'is_expiring_soon' => $daysRemaining <= 7,
            
            // Beneficiary share info
            'beneficiary_info' => $beneficiaryInfo,
            'has_inheritance' => !empty($beneficiaryInfo['share_percentage']),
            'share_percentage' => $beneficiaryInfo['share_percentage'] ?? null,
            'estimated_amount' => $beneficiaryInfo['estimated_amount'] ?? null,
            
            // Notification metadata
            'notification_id' => $this->notification->id,
            'email_sent_at' => now()->format('d F Y, h:i A'),
            'email_attempt' => $this->notification->email_attempts ?? 1,
            
            // Security info
            'security_notice' => $this->getSecurityNotice(),
            'privacy_notice' => $this->getPrivacyNotice(),
            'instructions' => $this->getAccessInstructions(),
            'support_email' => config('mail.support.address', 'support@neofaraid.com'),
            'support_phone' => config('app.support_phone', '+60 1-800-88-1234'),
            
            // Legal
            'disclaimer' => $this->getLegalDisclaimer(),
            'copyright_year' => now()->year,
            'company_name' => config('app.name', 'Neo Faraid'),
            'company_address' => config('app.company_address', 'Kuala Lumpur, Malaysia'),
            
            // Tracking
            'tracking_pixel' => $this->generateTrackingPixelUrl(),
            'unsubscribe_url' => $this->generateUnsubscribeUrl(),
        ];
    }

    /**
     * Get the email template based on beneficiary type
     */
    protected function getEmailTemplate(): string
    {
        return match($this->recipient['type'] ?? 'beneficiary') {
            'heir' => 'emails.inheritance.heir-notification',
            'wasiyyah' => 'emails.inheritance.wasiyyah-notification',
            'trustee' => 'emails.inheritance.trustee-notification',
            default => 'emails.inheritance.beneficiary-notification',
        };
    }

    /**
     * Get beneficiary-specific inheritance information
     */
    protected function getBeneficiarySpecificInfo(): array
    {
        $info = [];
        
        if (!$this->estate) {
            return $info;
        }
        
        $type = $this->recipient['type'] ?? '';
        $email = $this->recipient['email'] ?? '';
        
        if ($type === 'heir') {
            $heir = $this->estate->heirs()
                ->where('email', $email)
                ->first();
            
            if ($heir) {
                $totalAssets = $this->estate->assets()->sum('value');
                $totalDebts = $this->estate->debts()->sum('amount');
                $netEstate = max(0, $totalAssets - $totalDebts);
                $totalWasiyyahPct = $this->estate->wasiyyah()->sum('requested_percentage');
                $wasiyyahAmount = (min($totalWasiyyahPct, 33.33) / 100) * $netEstate;
                $remainingForHeirs = $netEstate - $wasiyyahAmount;
                
                $info = [
                    'name' => $heir->name,
                    'relationship' => $this->formatRelationship($heir->relationship),
                    'share_percentage' => round($heir->share_percentage, 2),
                    'estimated_amount' => round(($heir->share_percentage / 100) * $remainingForHeirs, 2),
                    'formatted_amount' => 'RM ' . number_format(round(($heir->share_percentage / 100) * $remainingForHeirs, 2), 2),
                    'share_type' => $this->determineShareType($heir->relationship),
                ];
            }
        } elseif ($type === 'wasiyyah') {
            $wasiyyah = $this->estate->wasiyyah()
                ->where('beneficiary_email', $email)
                ->first();
            
            if ($wasiyyah) {
                $totalAssets = $this->estate->assets()->sum('value');
                $totalDebts = $this->estate->debts()->sum('amount');
                $netEstate = max(0, $totalAssets - $totalDebts);
                $totalWasiyyahPct = $this->estate->wasiyyah()->sum('requested_percentage');
                $effectivePct = min($totalWasiyyahPct, 33.33);
                $wasiyyahAmount = ($effectivePct / 100) * $netEstate;
                
                $info = [
                    'name' => $wasiyyah->beneficiary_name,
                    'relationship' => $wasiyyah->relationship,
                    'share_percentage' => round($wasiyyah->requested_percentage, 2),
                    'estimated_amount' => round(($wasiyyah->requested_percentage / max($totalWasiyyahPct, 0.01)) * $wasiyyahAmount, 2),
                    'formatted_amount' => 'RM ' . number_format(round(($wasiyyah->requested_percentage / max($totalWasiyyahPct, 0.01)) * $wasiyyahAmount, 2), 2),
                    'is_charity' => $wasiyyah->is_charity ?? false,
                ];
            }
        } elseif ($type === 'trustee') {
            $info = [
                'name' => $this->estate->trustee_name ?? 'Trustee',
                'relationship' => $this->estate->trustee_relationship ?? 'Appointed Trustee',
                'role' => 'Estate Trustee (Wasi)',
                'responsibilities' => [
                    'Manage and safeguard estate assets',
                    'Settle all outstanding debts',
                    'Distribute inheritance according to Faraid law',
                    'Execute wasiyyah (bequests) as specified',
                    'Provide accounting to all beneficiaries',
                ],
            ];
        }
        
        return $info;
    }

    /**
     * Determine if PDF should be attached
     */
    protected function shouldAttachPdf(): bool
    {
        // Attach summary PDF for trustees and heirs
        return in_array($this->recipient['type'] ?? '', ['heir', 'trustee']);
    }

    /**
     * Generate a summary PDF for attachment
     */
    protected function generateSummaryPdf(): ?string
    {
        try {
            if (!$this->estate) {
                return null;
            }
            
            $pdfService = app(\App\Services\PdfGeneratorService::class);
            
            $pdfContent = $pdfService->generateBeneficiaryReport(
                $this->estate,
                $this->recipient['email'] ?? '',
                $this->recipient['type'] ?? 'beneficiary',
                $this->recipient['name'] ?? 'Beneficiary'
            );
            
            $tempPath = storage_path('app/temp/' . uniqid('inheritance_summary_') . '.pdf');
            file_put_contents($tempPath, $pdfContent);
            
            return $tempPath;
            
        } catch (\Exception $e) {
            Log::warning('Could not generate summary PDF for email attachment', [
                'notification_id' => $this->notification->id,
                'error' => $e->getMessage(),
            ]);
            
            return null;
        }
    }

    /**
     * Get PDF filename for attachment
     */
    protected function getPdfFilename(): string
    {
        $deceasedName = Str::slug($this->estate->deceased_name ?? 'estate', '-');
        return "Inheritance_Summary_{$deceasedName}_" . now()->format('Ymd') . '.pdf';
    }

    // ==================== SECURITY & PRIVACY ====================

    /**
     * Generate a verification code for additional security
     */
    protected function generateVerificationCode(): string
    {
        return strtoupper(substr(hash('sha256', 
            $this->notification->notification_token . 
            $this->recipient['email'] . 
            now()->timestamp
        ), 0, 8));
    }

    /**
     * Generate tracking pixel URL for email open tracking
     */
    protected function generateTrackingPixelUrl(): string
    {
        return URL::signedRoute('email.track-open', [
            'notification' => $this->notification->id,
            'token' => substr($this->accessToken, 0, 16),
        ]);
    }

    /**
     * Generate unsubscribe URL
     */
    protected function generateUnsubscribeUrl(): string
    {
        return URL::signedRoute('email.unsubscribe', [
            'notification' => $this->notification->id,
            'email' => $this->recipient['email'],
            'token' => substr($this->accessToken, 0, 16),
        ]);
    }

    /**
     * Get the security notice for the email
     */
    protected function getSecurityNotice(): string
    {
        return "🔒 This email contains confidential inheritance information. " .
               "The access link is unique to you and should not be shared with anyone. " .
               "Each access is logged and monitored for security purposes. " .
               "The link will expire after {$this->expiresAt->diffInDays(now())} days " .
               "or after 5 accesses, whichever comes first.";
    }

    /**
     * Get the privacy notice for the email
     */
    protected function getPrivacyNotice(): string
    {
        return "Your personal information is protected under the Personal Data Protection Act 2010 (PDPA). " .
               "Neo Faraid does not share your information with third parties. " .
               "This email was sent because you are listed as a beneficiary in the estate of " .
               ($this->estate->deceased_name ?? 'the deceased') . ". " .
               "If you believe this email was sent in error, please contact us immediately.";
    }

    /**
     * Generate access instructions for the beneficiary
     */
    protected function getAccessInstructions(): string
    {
        $instructions = [];
        
        $instructions[] = "1. Click the secure access link below to view the inheritance details.";
        $instructions[] = "2. You will be asked to verify your identity using this email address.";
        
        if ($this->verificationCode) {
            $instructions[] = "3. Your verification code is: **{$this->verificationCode}** (keep this confidential).";
        }
        
        $instructions[] = "4. You may view the documents up to 5 times within the access period.";
        $instructions[] = "5. For security, the link will expire on {$this->expiresAt->format('d F Y')}.";
        
        if ($this->recipient['type'] === 'trustee') {
            $instructions[] = "6. As trustee, you have additional responsibilities. Please review the estate management guidelines.";
        }
        
        return implode("\n", $instructions);
    }

    /**
     * Get legal disclaimer
     */
    protected function getLegalDisclaimer(): string
    {
        return "This email and its contents are confidential and intended solely for the named recipient. " .
               "The inheritance distribution is calculated based on Islamic Faraid law and the information " .
               "provided during estate registration. For legal advice, please consult a qualified " .
               "Islamic inheritance lawyer (Peguam Syarie). " .
               "Neo Faraid is a calculation and notification system and does not provide legal advice. " .
               "If you have received this email in error, please delete it immediately and notify the sender.";
    }

    // ==================== FORMATTING HELPERS ====================

    /**
     * Mask sensitive information
     */
    protected function maskSensitive(string $value): string
    {
        if (empty($value) || strlen($value) <= 4) {
            return '****';
        }
        
        return substr($value, 0, 2) . str_repeat('*', strlen($value) - 6) . substr($value, -4);
    }

    /**
     * Mask token for display
     */
    protected function maskToken(string $token): string
    {
        if (strlen($token) <= 16) {
            return str_repeat('*', strlen($token));
        }
        
        return substr($token, 0, 4) . str_repeat('*', 16) . substr($token, -4);
    }

    /**
     * Format relationship for display
     */
    protected function formatRelationship(string $relationship): string
    {
        return [
            'husband' => 'Husband',
            'wife' => 'Wife',
            'father' => 'Father',
            'mother' => 'Mother',
            'son' => 'Son',
            'daughter' => 'Daughter',
            'brother' => 'Brother',
            'sister' => 'Sister',
            'grandfather' => 'Grandfather',
            'grandmother' => 'Grandmother',
            'half_brother_paternal' => 'Paternal Half-Brother',
            'half_brother_maternal' => 'Maternal Half-Brother',
            'half_sister_paternal' => 'Paternal Half-Sister',
            'half_sister_maternal' => 'Maternal Half-Sister',
        ][$relationship] ?? ucwords(str_replace('_', ' ', $relationship));
    }

    /**
     * Determine share type based on relationship
     */
    protected function determineShareType(string $relationship): string
    {
        $fixedShares = ['husband', 'wife', 'father', 'mother', 'daughter', 'sister'];
        $asabahShares = ['son', 'brother'];
        
        if (in_array($relationship, $fixedShares)) {
            return 'Fixed Share (Fard)';
        }
        if (in_array($relationship, $asabahShares)) {
            return 'Residuary Share (Asabah)';
        }
        
        return 'Conditional Share';
    }

    /**
     * Get full display name for the email
     */
    protected function getFullDisplayName(): string
    {
        $name = $this->recipient['name'] ?? 'Beneficiary';
        $type = $this->recipient['type'] ?? '';
        
        if ($type === 'heir' && !empty($this->recipient['relationship'])) {
            return "{$name} (" . $this->formatRelationship($this->recipient['relationship']) . ")";
        }
        
        if ($type === 'trustee') {
            return "{$name} (Appointed Trustee)";
        }
        
        return $name;
    }
}