<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InstantEstateSession extends Model
{
    protected $table = 'instant_estate_sessions';

    protected $fillable = [
        // Primary identifiers
        'session_id',
        'user_id',
        
        // Guest information
        'guest_email',
        'guest_name',
        
        // File information
        'file_path',
        'original_filename',
        'file_size',
        'file_mime',
        'file_hash',
        
        // Status tracking
        'status',
        'admin_status',
        'rejection_reason',
        
        // Quality check
        'quality_check_passed',
        'quality_check_details',
        'quality_issues',
        'authenticity_score',
        'authenticity_score_details',
        
        // OCR results
        'extracted_data',
        'ocr_confidence',
        'missing_fields',
        
        // Deceased information (denormalized)
        'deceased_name',
        'deceased_nric',
        'date_of_birth',
        'gender',
        'death_date',
        'death_place',
        'cause_of_death',
        'father_name',
        'mother_name',
        'spouse_name',
        'contact_email',
        'contact_phone',
        'residential_address',
        'marital_status',
        'registration_number',
        
        // Data confirmation
        'data_confirmed_at',
        'data_confirmed_by',
        
        // CAPTCHA verification
        'captcha_verified_at',
        'captcha_verified_by',
        
        // Notification request
        'notification_requested',
        'notification_requested_at',
        'notification_request_id',
        'notification_email',
        
        // Database matching
        'matched_record_id',
        'matched_record_type',
        'matched_record_data',
        
        // Report data
        'report_data',
        'report_generated_at',
        'report_pdf_path',
        
        // Email recipient
        'recipient_email',
        'email_sent_at',
        
        // Associated calculation
        'calculation_id',
        
        // Error tracking
        'error_message',
        'processing_time_ms',
        'processing_attempts',
        'last_processing_attempt_at',
        'processing_completed_at',
        
        // Session expiry
        'expires_at',
        
        // Admin review
        'reviewed_by_admin_id',
        'reviewed_at',
        
        // Metadata
        'metadata',
    ];

    protected $casts = [
        // JSON fields
        'extracted_data' => 'array',
        'quality_check_details' => 'array',
        'quality_issues' => 'array',
        'missing_fields' => 'array',
        'report_data' => 'array',
        'metadata' => 'array',
        'matched_record_data' => 'array',
        'authenticity_score_details' => 'array',
        
        // Date fields
        'date_of_birth' => 'date',
        'death_date' => 'date',
        'expires_at' => 'datetime',
        'data_confirmed_at' => 'datetime',
        'captcha_verified_at' => 'datetime',
        'notification_requested_at' => 'datetime',
        'report_generated_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'last_processing_attempt_at' => 'datetime',
        'processing_completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        
        // Boolean fields
        'quality_check_passed' => 'boolean',
        'notification_requested' => 'boolean',
        
        // Integer fields
        'ocr_confidence' => 'integer',
        'processing_time_ms' => 'integer',
        'processing_attempts' => 'integer',
        'file_size' => 'integer',
        'authenticity_score' => 'integer',
        
        // String fields
        'admin_status' => 'string',
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'formatted_file_size',
        'is_active',
        'is_expired',
        'has_matched_record',
        'has_report',
        'has_pdf_report',
        'report_pdf_url',
        'formatted_deceased_name',
        'formatted_death_date',
        'processing_progress_percentage',
        'masked_nric',
        'formatted_contact_phone',
        'are_debts_settled',
        'total_debts',
        'debts_paid',
        'remaining_debt_amount',
        'admin_status_label',
        'admin_status_color',
        'is_authentic',
        'authenticity_level',
        'authenticity_breakdown',
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    public const STATUS_UPLOADED = 'uploaded';
    public const STATUS_PROCESSING_OCR = 'processing_ocr';
    public const STATUS_OCR_COMPLETED = 'ocr_completed';
    public const STATUS_DATA_CONFIRMED = 'data_confirmed';
    public const STATUS_RECORD_FOUND = 'record_found';
    public const STATUS_NO_RECORD = 'no_record';
    public const STATUS_CAPTCHA_VERIFIED = 'captcha_verified';
    public const STATUS_NOTIFICATION_REQUESTED = 'notification_requested';
    public const STATUS_ADMIN_APPROVED = 'admin_approved';
    public const STATUS_ADMIN_REJECTED = 'admin_rejected';
    public const STATUS_EMAIL_SENT = 'email_sent';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    public const ADMIN_STATUS_PENDING = 'pending_review';
    public const ADMIN_STATUS_APPROVED = 'approved';
    public const ADMIN_STATUS_REJECTED = 'rejected';
    public const ADMIN_STATUS_FLAGGED = 'flagged';

    public const RECORD_TYPE_ESTATE_PLAN = 'estate_plan';
    public const RECORD_TYPE_CALCULATION = 'calculation';

    public const AUTHENTICITY_HIGH = 'high';
    public const AUTHENTICITY_MEDIUM = 'medium';
    public const AUTHENTICITY_LOW = 'low';
    public const AUTHENTICITY_UNVERIFIED = 'unverified';

    public const AUTHENTICITY_CATEGORIES = [
        'document_validity' => 'Document Validity',
        'data_consistency' => 'Data Consistency',
        'ocr_quality' => 'OCR Quality',
        'field_completeness' => 'Field Completeness',
        'format_compliance' => 'Format Compliance',
        'cross_reference' => 'Cross Reference Check',
        'historical_match' => 'Historical Data Match',
        'signature_verification' => 'Signature Verification',
    ];

    public const REQUIRED_FIELDS = [
        'deceased_name' => 'Full Name',
        'deceased_nric' => 'NRIC/Passport Number',
        'date_of_birth' => 'Date of Birth',
        'gender' => 'Gender',
        'death_date' => 'Date of Death',
        'death_place' => 'Place of Death',
        'contact_email' => 'Contact Email',
        'contact_phone' => 'Contact Phone',
        'residential_address' => 'Residential Address',
        'marital_status' => 'Marital Status',
    ];

    public static array $statuses = [
        self::STATUS_UPLOADED => 'Uploaded',
        self::STATUS_PROCESSING_OCR => 'Processing OCR',
        self::STATUS_OCR_COMPLETED => 'OCR Complete',
        self::STATUS_DATA_CONFIRMED => 'Data Confirmed',
        self::STATUS_RECORD_FOUND => 'Record Found',
        self::STATUS_NO_RECORD => 'No Record',
        self::STATUS_CAPTCHA_VERIFIED => 'CAPTCHA Verified',
        self::STATUS_NOTIFICATION_REQUESTED => 'Notification Requested',
        self::STATUS_ADMIN_APPROVED => 'Admin Approved',
        self::STATUS_ADMIN_REJECTED => 'Admin Rejected',
        self::STATUS_EMAIL_SENT => 'Email Sent',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_FAILED => 'Failed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_EXPIRED => 'Expired',
    ];

    public static array $statusColors = [
        self::STATUS_UPLOADED => 'secondary',
        self::STATUS_PROCESSING_OCR => 'info',
        self::STATUS_OCR_COMPLETED => 'primary',
        self::STATUS_DATA_CONFIRMED => 'primary',
        self::STATUS_RECORD_FOUND => 'success',
        self::STATUS_NO_RECORD => 'secondary',
        self::STATUS_CAPTCHA_VERIFIED => 'success',
        self::STATUS_NOTIFICATION_REQUESTED => 'warning',
        self::STATUS_ADMIN_APPROVED => 'success',
        self::STATUS_ADMIN_REJECTED => 'danger',
        self::STATUS_EMAIL_SENT => 'success',
        self::STATUS_COMPLETED => 'success',
        self::STATUS_FAILED => 'danger',
        self::STATUS_CANCELLED => 'dark',
        self::STATUS_EXPIRED => 'secondary',
    ];

    public static array $statusOrder = [
        self::STATUS_UPLOADED => 1,
        self::STATUS_PROCESSING_OCR => 2,
        self::STATUS_OCR_COMPLETED => 3,
        self::STATUS_DATA_CONFIRMED => 4,
        self::STATUS_RECORD_FOUND => 5,
        self::STATUS_NO_RECORD => 5,
        self::STATUS_CAPTCHA_VERIFIED => 6,
        self::STATUS_NOTIFICATION_REQUESTED => 7,
        self::STATUS_ADMIN_APPROVED => 8,
        self::STATUS_ADMIN_REJECTED => 8,
        self::STATUS_EMAIL_SENT => 9,
        self::STATUS_COMPLETED => 10,
        self::STATUS_FAILED => -1,
        self::STATUS_CANCELLED => -1,
        self::STATUS_EXPIRED => -1,
    ];

    public static array $adminStatuses = [
        self::ADMIN_STATUS_PENDING => 'Pending Review',
        self::ADMIN_STATUS_APPROVED => 'Approved',
        self::ADMIN_STATUS_REJECTED => 'Rejected',
        self::ADMIN_STATUS_FLAGGED => 'Flagged for Review',
    ];

    public static array $adminStatusColors = [
        self::ADMIN_STATUS_PENDING => 'warning',
        self::ADMIN_STATUS_APPROVED => 'success',
        self::ADMIN_STATUS_REJECTED => 'danger',
        self::ADMIN_STATUS_FLAGGED => 'orange',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dataConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'data_confirmed_by');
    }

    public function captchaVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captcha_verified_by');
    }

    public function reviewedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin_id');
    }

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(Calculation::class, 'calculation_id');
    }

    public function notificationRequest(): BelongsTo
    {
        return $this->belongsTo(NotificationRequest::class, 'notification_request_id');
    }

    /**
     * Polymorphic relationship to the matched record (estate plan or calculation)
     */
    public function matchedRecord()
    {
        if ($this->matched_record_type === self::RECORD_TYPE_ESTATE_PLAN) {
            return $this->belongsTo(EstatePreRegistration::class, 'matched_record_id');
        }
        if ($this->matched_record_type === self::RECORD_TYPE_CALCULATION) {
            return $this->belongsTo(Calculation::class, 'matched_record_id');
        }
        return null;
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    public function getStatusLabelAttribute(): string
    {
        return self::$statuses[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status ?? 'Unknown'));
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->status] ?? 'secondary';
    }

    public function getAdminStatusLabelAttribute(): string
    {
        return self::$adminStatuses[$this->admin_status] ?? 'Pending Review';
    }

    public function getAdminStatusColorAttribute(): string
    {
        return self::$adminStatusColors[$this->admin_status] ?? 'warning';
    }

    public function getIsAuthenticAttribute(): bool
    {
        return $this->authenticity_score >= 70;
    }

    public function getAuthenticityLevelAttribute(): string
    {
        if ($this->authenticity_score >= 85) {
            return self::AUTHENTICITY_HIGH;
        }
        if ($this->authenticity_score >= 60) {
            return self::AUTHENTICITY_MEDIUM;
        }
        if ($this->authenticity_score >= 30) {
            return self::AUTHENTICITY_LOW;
        }
        return self::AUTHENTICITY_UNVERIFIED;
    }

    /**
     * Get the authenticity breakdown details
     * Returns the authenticity_score_details array or empty array
     */
    public function getAuthenticityBreakdownAttribute(): array
    {
        $details = $this->authenticity_score_details ?? [];
        
        // Ensure all expected categories exist with default values
        $defaultBreakdown = [];
        foreach (self::AUTHENTICITY_CATEGORIES as $key => $label) {
            $defaultBreakdown[$key] = [
                'label' => $label,
                'score' => $details[$key]['score'] ?? 0,
                'max_score' => $details[$key]['max_score'] ?? 100,
                'percentage' => $details[$key]['percentage'] ?? 0,
                'status' => $details[$key]['status'] ?? 'unchecked',
                'details' => $details[$key]['details'] ?? null,
                'issues' => $details[$key]['issues'] ?? [],
            ];
        }

        // Merge with any custom categories that might be in the stored data
        foreach ($details as $key => $value) {
            if (!isset(self::AUTHENTICITY_CATEGORIES[$key])) {
                $defaultBreakdown[$key] = $value;
            }
        }

        return $defaultBreakdown;
    }

    /**
     * Get a simplified authenticity breakdown summary
     */
    public function getAuthenticityBreakdownSummaryAttribute(): array
    {
        $breakdown = $this->authenticity_breakdown;
        
        $summary = [
            'total_categories' => count($breakdown),
            'categories_passed' => 0,
            'categories_failed' => 0,
            'categories_unchecked' => 0,
            'average_percentage' => 0,
            'critical_issues' => [],
            'recommendations' => [],
        ];

        $totalPercentage = 0;
        foreach ($breakdown as $key => $category) {
            $totalPercentage += $category['percentage'];
            
            switch ($category['status']) {
                case 'passed':
                case 'high':
                    $summary['categories_passed']++;
                    break;
                case 'failed':
                case 'low':
                    $summary['categories_failed']++;
                    if (!empty($category['issues'])) {
                        $summary['critical_issues'] = array_merge(
                            $summary['critical_issues'], 
                            $category['issues']
                        );
                    }
                    break;
                default:
                    $summary['categories_unchecked']++;
                    break;
            }
        }

        if ($summary['total_categories'] > 0) {
            $summary['average_percentage'] = round($totalPercentage / $summary['total_categories'], 1);
        }

        // Generate recommendations based on issues
        if ($summary['categories_failed'] > 0) {
            $summary['recommendations'][] = 'Review failed authenticity categories';
        }
        if ($this->authenticity_score < 50) {
            $summary['recommendations'][] = 'Manual verification recommended due to low authenticity score';
        }
        if ($this->ocr_confidence < 70) {
            $summary['recommendations'][] = 'Poor OCR quality detected - consider re-uploading document';
        }

        return $summary;
    }

    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }

    public function getIsActiveAttribute(): bool
    {
        return !$this->is_expired && !in_array($this->status, [
            self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_EXPIRED
        ]);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getHasMatchedRecordAttribute(): bool
    {
        return !empty($this->matched_record_id) && !empty($this->matched_record_type);
    }

    public function getHasReportAttribute(): bool
    {
        return !empty($this->report_data);
    }

    public function getHasPdfReportAttribute(): bool
    {
        return !empty($this->report_pdf_path) && Storage::disk('private')->exists($this->report_pdf_path);
    }

    public function getReportPdfUrlAttribute(): ?string
    {
        if ($this->has_pdf_report) {
            return route('instant-estate.download-report', $this->session_id);
        }
        return null;
    }

    public function getFormattedDeceasedNameAttribute(): string
    {
        return $this->deceased_name ?? 'Unknown Deceased';
    }

    public function getFormattedDeathDateAttribute(): string
    {
        if (!$this->death_date) {
            return 'Not specified';
        }
        return $this->death_date->format('d F Y');
    }

    public function getProcessingProgressPercentageAttribute(): int
    {
        $currentOrder = self::$statusOrder[$this->status] ?? 0;
        $maxOrder = max(array_filter(self::$statusOrder, fn($order) => $order > 0));
        
        if ($currentOrder <= 0) return 0;
        return min(100, (int) round(($currentOrder / $maxOrder) * 100));
    }

    public function getMaskedNricAttribute(): string
    {
        return $this->maskNric();
    }

    public function getFormattedContactPhoneAttribute(): string
    {
        $phone = $this->contact_phone;
        if (empty($phone)) return 'N/A';
        
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($clean) >= 10) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3, 3) . '-' . substr($clean, 6);
        }
        return $phone;
    }

    public function getAreDebtsSettledAttribute(): bool
    {
        if ($this->matched_record_type !== self::RECORD_TYPE_ESTATE_PLAN || !$this->matched_record_id) {
            return false;
        }
        
        $estate = EstatePreRegistration::find($this->matched_record_id);
        if (!$estate) return false;
        
        $totalDebts = $estate->debts()->sum('amount');
        $totalPaid = $estate->debts()->sum('amount_paid');
        return $totalDebts <= $totalPaid;
    }

    public function getTotalDebtsAttribute(): float
    {
        if ($this->matched_record_type !== self::RECORD_TYPE_ESTATE_PLAN || !$this->matched_record_id) {
            return 0;
        }
        
        $estate = EstatePreRegistration::find($this->matched_record_id);
        if (!$estate) return 0;
        
        return (float) $estate->debts()->sum('amount');
    }

    public function getDebtsPaidAttribute(): float
    {
        if ($this->matched_record_type !== self::RECORD_TYPE_ESTATE_PLAN || !$this->matched_record_id) {
            return 0;
        }
        
        $estate = EstatePreRegistration::find($this->matched_record_id);
        if (!$estate) return 0;
        
        return (float) $estate->debts()->sum('amount_paid');
    }

    public function getRemainingDebtAmountAttribute(): float
    {
        return max(0, $this->total_debts - $this->debts_paid);
    }

    // =========================================================================
    // STEP CHECKING METHODS
    // =========================================================================

    public function canConfirmData(): bool
    {
        return in_array($this->status, [self::STATUS_OCR_COMPLETED, self::STATUS_DATA_CONFIRMED]);
    }

    public function canVerifyCaptcha(): bool
    {
        return in_array($this->status, [self::STATUS_DATA_CONFIRMED, self::STATUS_RECORD_FOUND, self::STATUS_NO_RECORD]);
    }

    public function canRequestNotification(): bool
    {
        return in_array($this->status, [self::STATUS_CAPTCHA_VERIFIED, self::STATUS_RECORD_FOUND, self::STATUS_COMPLETED]);
    }

    public function isReadyForDatabaseMatching(): bool
    {
        return $this->status === self::STATUS_DATA_CONFIRMED;
    }

    public function isOcrComplete(): bool
    {
        return in_array($this->status, [
            self::STATUS_OCR_COMPLETED,
            self::STATUS_DATA_CONFIRMED,
            self::STATUS_RECORD_FOUND,
            self::STATUS_NO_RECORD,
            self::STATUS_CAPTCHA_VERIFIED,
            self::STATUS_COMPLETED,
        ]);
    }

    public function canRetry(): bool
    {
        return $this->status === self::STATUS_FAILED && $this->processing_attempts < 3;
    }

    public function canCancel(): bool
    {
        return in_array($this->status, [
            self::STATUS_UPLOADED,
            self::STATUS_PROCESSING_OCR,
            self::STATUS_OCR_COMPLETED,
            self::STATUS_DATA_CONFIRMED,
        ]);
    }

    public function needsAdminReview(): bool
    {
        return $this->admin_status === self::ADMIN_STATUS_PENDING || 
               $this->admin_status === self::ADMIN_STATUS_FLAGGED;
    }

    public function isAdminApproved(): bool
    {
        return $this->admin_status === self::ADMIN_STATUS_APPROVED;
    }

    public function isAdminRejected(): bool
    {
        return $this->admin_status === self::ADMIN_STATUS_REJECTED;
    }

    // =========================================================================
    // STATE MUTATORS (ACTION METHODS)
    // =========================================================================

    public function updateStatus(string $status, ?string $errorMessage = null): self
    {
        $this->update([
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
        return $this;
    }

    public function markOcrCompleted(array $extractedData, int $confidence, array $missingFields): self
    {
        $updateData = [
            'extracted_data' => $extractedData,
            'status' => self::STATUS_OCR_COMPLETED,
            'ocr_confidence' => $confidence,
            'missing_fields' => $missingFields,
            'processing_completed_at' => now(),
        ];

        $denormalizedFields = [
            'deceased_name', 'deceased_nric', 'date_of_birth', 'gender',
            'death_date', 'death_place', 'cause_of_death',
            'contact_email', 'contact_phone', 'residential_address',
            'marital_status', 'father_name', 'mother_name', 'spouse_name',
            'registration_number'
        ];

        foreach ($denormalizedFields as $field) {
            if (isset($extractedData[$field]) && !empty($extractedData[$field])) {
                $value = $extractedData[$field];
                
                if (in_array($field, ['death_date', 'date_of_birth']) && is_string($value)) {
                    try {
                        $timestamp = strtotime($value);
                        if ($timestamp !== false) {
                            $value = date('Y-m-d', $timestamp);
                        }
                    } catch (\Exception $e) {
                        // Keep original value if parsing fails
                    }
                }
                
                $updateData[$field] = $value;
            }
        }

        $this->update($updateData);
        return $this;
    }

    public function confirmData(array $confirmedData): self
    {
        $updateData = [
            'extracted_data' => $confirmedData,
            'status' => self::STATUS_DATA_CONFIRMED,
            'data_confirmed_at' => now(),
            'data_confirmed_by' => auth()->id(), // may be null for guests
        ];

        $denormalizedFields = [
            'deceased_name', 'deceased_nric', 'date_of_birth', 'gender',
            'death_date', 'death_place', 'cause_of_death',
            'contact_email', 'contact_phone', 'residential_address',
            'marital_status', 'father_name', 'mother_name', 'spouse_name',
            'registration_number'
        ];

        foreach ($denormalizedFields as $field) {
            if (isset($confirmedData[$field])) {
                $updateData[$field] = $confirmedData[$field];
            }
        }

        $this->update($updateData);
        return $this;
    }

    public function markMatchedRecord(string $recordId, string $recordType, $record = null): self
    {
        $data = [
            'matched_record_id' => $recordId,
            'matched_record_type' => $recordType,
            'status' => self::STATUS_RECORD_FOUND,
        ];
        if ($record && (is_array($record) || $record instanceof \Illuminate\Contracts\Support\Arrayable)) {
            $data['matched_record_data'] = $record;
        }
        $this->update($data);
        return $this;
    }

    public function markNoRecord(): self
    {
        $this->update([
            'status' => self::STATUS_NO_RECORD,
        ]);
        return $this;
    }

    public function markCaptchaVerified(): self
    {
        $this->update([
            'status' => self::STATUS_CAPTCHA_VERIFIED,
            'captcha_verified_at' => now(),
            'captcha_verified_by' => auth()->id(), // may be null for guests
        ]);
        return $this;
    }

    public function generateReport(array $reportData, ?string $pdfPath = null): self
    {
        $updateData = [
            'report_data' => $reportData,
            'report_generated_at' => now(),
            'status' => self::STATUS_COMPLETED,
        ];
        if ($pdfPath) {
            $updateData['report_pdf_path'] = $pdfPath;
        }
        $this->update($updateData);
        return $this;
    }

    public function markNotificationRequested(string $email, ?int $notificationRequestId = null): self
    {
        $this->update([
            'status' => self::STATUS_NOTIFICATION_REQUESTED,
            'notification_requested' => true,
            'notification_requested_at' => now(),
            'notification_request_id' => $notificationRequestId,
            'recipient_email' => $email,
            'notification_email' => $email,
        ]);
        return $this;
    }

    public function markEmailSent(): self
    {
        $this->update([
            'status' => self::STATUS_EMAIL_SENT,
            'email_sent_at' => now(),
        ]);
        return $this;
    }

    public function markFailed(string $errorMessage): self
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
        return $this;
    }

    public function markCancelled(): self
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
        return $this;
    }

    public function markExpired(): self
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
        return $this;
    }

    public function deletePdfReport(): bool
    {
        if ($this->report_pdf_path && Storage::disk('private')->exists($this->report_pdf_path)) {
            return Storage::disk('private')->delete($this->report_pdf_path);
        }
        return true;
    }

    public function deleteFile(): bool
    {
        if ($this->file_path && Storage::disk('private')->exists($this->file_path)) {
            return Storage::disk('private')->delete($this->file_path);
        }
        return true;
    }

    public function incrementProcessingAttempts(): self
    {
        $this->update([
            'processing_attempts' => $this->processing_attempts + 1,
            'last_processing_attempt_at' => now(),
        ]);
        return $this;
    }

    public function extendExpiry(int $hours = 48): self
    {
        $this->update([
            'expires_at' => now()->addHours($hours),
        ]);
        return $this;
    }

    public function updateMetadata(array $metadata): self
    {
        $currentMetadata = $this->metadata ?? [];
        $this->update([
            'metadata' => array_merge($currentMetadata, $metadata),
        ]);
        return $this;
    }

    // Admin Review Methods

    public function markAsAdminReviewed(string $adminStatus, ?string $rejectionReason = null, ?int $authenticityScore = null): self
    {
        $updateData = [
            'admin_status' => $adminStatus,
            'reviewed_by_admin_id' => auth()->id(),
            'reviewed_at' => now(),
        ];

        if ($rejectionReason) {
            $updateData['rejection_reason'] = $rejectionReason;
        }

        if ($authenticityScore !== null) {
            $updateData['authenticity_score'] = $authenticityScore;
        }

        if ($adminStatus === self::ADMIN_STATUS_APPROVED) {
            $updateData['status'] = self::STATUS_ADMIN_APPROVED;
        } elseif ($adminStatus === self::ADMIN_STATUS_REJECTED) {
            $updateData['status'] = self::STATUS_ADMIN_REJECTED;
        }

        $this->update($updateData);
        return $this;
    }

    public function flagForReview(?string $reason = null): self
    {
        $updateData = [
            'admin_status' => self::ADMIN_STATUS_FLAGGED,
            'rejection_reason' => $reason,
        ];

        $this->update($updateData);
        return $this;
    }

    public function setAuthenticityScore(int $score, ?array $details = null): self
    {
        $updateData = [
            'authenticity_score' => $score,
        ];

        if ($details !== null) {
            $updateData['authenticity_score_details'] = $details;
        }

        $this->update($updateData);
        return $this;
    }

    /**
     * Set authenticity breakdown for a specific category
     */
    public function setAuthenticityCategory(string $category, array $data): self
    {
        $details = $this->authenticity_score_details ?? [];
        $details[$category] = array_merge([
            'label' => self::AUTHENTICITY_CATEGORIES[$category] ?? $category,
            'score' => 0,
            'max_score' => 100,
            'percentage' => 0,
            'status' => 'unchecked',
            'details' => null,
            'issues' => [],
        ], $data);

        // Recalculate percentage
        if ($details[$category]['max_score'] > 0) {
            $details[$category]['percentage'] = round(
                ($details[$category]['score'] / $details[$category]['max_score']) * 100, 
                1
            );
        }

        $this->update([
            'authenticity_score_details' => $details,
        ]);

        // Recalculate overall authenticity score
        $this->recalculateAuthenticityScore();

        return $this;
    }

    /**
     * Recalculate the overall authenticity score based on category breakdowns
     */
    public function recalculateAuthenticityScore(): self
    {
        $breakdown = $this->authenticity_breakdown;
        
        if (empty($breakdown)) {
            return $this;
        }

        $totalScore = 0;
        $totalMaxScore = 0;
        $categoryCount = 0;

        foreach ($breakdown as $category) {
            if ($category['status'] !== 'unchecked') {
                $totalScore += $category['score'];
                $totalMaxScore += $category['max_score'];
                $categoryCount++;
            }
        }

        if ($categoryCount > 0 && $totalMaxScore > 0) {
            $overallScore = (int) round(($totalScore / $totalMaxScore) * 100);
        } else {
            $overallScore = 0;
        }

        $this->update([
            'authenticity_score' => $overallScore,
        ]);

        return $this;
    }

    public function setFileHash(string $hash): self
    {
        $this->update([
            'file_hash' => $hash,
        ]);
        return $this;
    }

    public function setRegistrationNumber(?string $registrationNumber): self
    {
        $this->update([
            'registration_number' => $registrationNumber,
        ]);
        return $this;
    }

    // =========================================================================
    // PRIVATE HELPER METHODS
    // =========================================================================

    private function maskNric(): string
    {
        if (empty($this->deceased_nric)) {
            return 'N/A';
        }
        
        $clean = preg_replace('/[^0-9]/', '', $this->deceased_nric);
        
        if (strlen($clean) >= 8) {
            return '******-' . substr($clean, -4, 2) . '-' . substr($clean, -2);
        }
        
        return '******';
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now())
            ->whereNotIn('status', [self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_EXPIRED]);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByStatuses($query, array $statuses)
    {
        return $query->whereIn('status', $statuses);
    }

    public function scopeNeedsOcr($query)
    {
        return $query->where('status', self::STATUS_UPLOADED);
    }

    public function scopeNeedsDataConfirmation($query)
    {
        return $query->where('status', self::STATUS_OCR_COMPLETED);
    }

    public function scopeNeedsCaptcha($query)
    {
        return $query->where('status', self::STATUS_DATA_CONFIRMED);
    }

    public function scopeWithMatchedRecords($query)
    {
        return $query->whereNotNull('matched_record_id');
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDateRange($query, $from, $to)
    {
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);
        return $query;
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('deceased_name', 'like', '%' . $term . '%')
              ->orWhere('deceased_nric', 'like', '%' . $term . '%')
              ->orWhere('original_filename', 'like', '%' . $term . '%')
              ->orWhere('session_id', 'like', '%' . $term . '%')
              ->orWhere('guest_email', 'like', '%' . $term . '%')
              ->orWhere('guest_name', 'like', '%' . $term . '%')
              ->orWhere('registration_number', 'like', '%' . $term . '%');
        });
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', [
            self::STATUS_COMPLETED,
            self::STATUS_EMAIL_SENT,
            self::STATUS_RECORD_FOUND,
        ]);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopePending($query)
    {
        return $query->whereNotIn('status', [
            self::STATUS_COMPLETED,
            self::STATUS_EMAIL_SENT,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED,
            self::STATUS_EXPIRED,
        ]);
    }

    public function scopeExpiringSoon($query)
    {
        return $query->where('expires_at', '<=', now()->addHours(24))
            ->where('expires_at', '>', now())
            ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_FAILED]);
    }

    public function scopeByMatchedType($query, string $type)
    {
        return $query->where('matched_record_type', $type);
    }

    public function scopeByAdminStatus($query, string $adminStatus)
    {
        return $query->where('admin_status', $adminStatus);
    }

    public function scopeNeedsAdminReview($query)
    {
        return $query->whereIn('admin_status', [
            self::ADMIN_STATUS_PENDING,
            self::ADMIN_STATUS_FLAGGED,
        ]);
    }

    public function scopeByAuthenticityLevel($query, string $level)
    {
        switch ($level) {
            case self::AUTHENTICITY_HIGH:
                return $query->where('authenticity_score', '>=', 85);
            case self::AUTHENTICITY_MEDIUM:
                return $query->whereBetween('authenticity_score', [60, 84]);
            case self::AUTHENTICITY_LOW:
                return $query->whereBetween('authenticity_score', [30, 59]);
            case self::AUTHENTICITY_UNVERIFIED:
                return $query->where('authenticity_score', '<', 30)
                    ->orWhereNull('authenticity_score');
            default:
                return $query;
        }
    }

    public function scopeByReviewedAdmin($query, int $adminId)
    {
        return $query->where('reviewed_by_admin_id', $adminId);
    }

    public function scopeHasAuthenticityDetails($query)
    {
        return $query->whereNotNull('authenticity_score_details');
    }

    // =========================================================================
    // STATISTICS METHODS
    // =========================================================================

    public static function getUserStatistics(int $userId): array
    {
        $query = self::where('user_id', $userId);
        
        return [
            'total' => $query->count(),
            'completed' => $query->clone()->completed()->count(),
            'pending' => $query->clone()->pending()->count(),
            'failed' => $query->clone()->failed()->count(),
            'this_month' => $query->clone()->whereMonth('created_at', now()->month)->count(),
            'average_confidence' => (float) $query->clone()->whereNotNull('ocr_confidence')->avg('ocr_confidence') ?? 0,
            'average_authenticity' => (float) $query->clone()->whereNotNull('authenticity_score')->avg('authenticity_score') ?? 0,
            'needs_review' => $query->clone()->needsAdminReview()->count(),
        ];
    }

    public static function getGlobalStatistics(): array
    {
        return [
            'total' => self::count(),
            'completed' => self::completed()->count(),
            'pending' => self::pending()->count(),
            'failed' => self::failed()->count(),
            'expired' => self::where('status', self::STATUS_EXPIRED)->count(),
            'cancelled' => self::where('status', self::STATUS_CANCELLED)->count(),
            'this_week' => self::where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => self::where('created_at', '>=', now()->subMonth())->count(),
            'with_matched_records' => self::withMatchedRecords()->count(),
            'with_reports' => self::whereNotNull('report_data')->count(),
            'average_confidence' => (float) self::whereNotNull('ocr_confidence')->avg('ocr_confidence') ?? 0,
            'average_authenticity' => (float) self::whereNotNull('authenticity_score')->avg('authenticity_score') ?? 0,
            'needs_admin_review' => self::needsAdminReview()->count(),
            'admin_approved' => self::where('admin_status', self::ADMIN_STATUS_APPROVED)->count(),
            'admin_rejected' => self::where('admin_status', self::ADMIN_STATUS_REJECTED)->count(),
            'flagged_for_review' => self::where('admin_status', self::ADMIN_STATUS_FLAGGED)->count(),
        ];
    }

    public static function getAdminReviewStatistics(): array
    {
        return [
            'pending_review' => self::where('admin_status', self::ADMIN_STATUS_PENDING)->count(),
            'flagged' => self::where('admin_status', self::ADMIN_STATUS_FLAGGED)->count(),
            'approved_today' => self::where('admin_status', self::ADMIN_STATUS_APPROVED)
                ->whereDate('reviewed_at', today())
                ->count(),
            'rejected_today' => self::where('admin_status', self::ADMIN_STATUS_REJECTED)
                ->whereDate('reviewed_at', today())
                ->count(),
            'average_review_time_hours' => self::calculateAverageReviewTime(),
        ];
    }

    /**
     * Get authenticity statistics
     */
    public static function getAuthenticityStatistics(): array
    {
        $total = self::whereNotNull('authenticity_score')->count();
        
        if ($total === 0) {
            return [
                'total_assessed' => 0,
                'high_authenticity' => 0,
                'medium_authenticity' => 0,
                'low_authenticity' => 0,
                'unverified' => 0,
                'average_score' => 0,
                'high_percentage' => 0,
                'medium_percentage' => 0,
                'low_percentage' => 0,
            ];
        }

        $high = self::where('authenticity_score', '>=', 85)->count();
        $medium = self::whereBetween('authenticity_score', [60, 84])->count();
        $low = self::whereBetween('authenticity_score', [30, 59])->count();
        $unverified = self::where('authenticity_score', '<', 30)->count();

        return [
            'total_assessed' => $total,
            'high_authenticity' => $high,
            'medium_authenticity' => $medium,
            'low_authenticity' => $low,
            'unverified' => $unverified,
            'average_score' => round((float) self::whereNotNull('authenticity_score')->avg('authenticity_score'), 1),
            'high_percentage' => $total > 0 ? round(($high / $total) * 100, 1) : 0,
            'medium_percentage' => $total > 0 ? round(($medium / $total) * 100, 1) : 0,
            'low_percentage' => $total > 0 ? round(($low / $total) * 100, 1) : 0,
        ];
    }

    private static function calculateAverageReviewTime(): float
    {
        $reviewed = self::whereNotNull('reviewed_at')
            ->whereNotNull('created_at')
            ->where('admin_status', '!=', self::ADMIN_STATUS_PENDING)
            ->get();

        if ($reviewed->isEmpty()) {
            return 0;
        }

        $totalHours = $reviewed->sum(function ($session) {
            return $session->created_at->diffInHours($session->reviewed_at);
        });

        return round($totalHours / $reviewed->count(), 1);
    }

    // =========================================================================
    // BOOT METHOD
    // =========================================================================

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($session) {
            if (empty($session->session_id)) {
                $session->session_id = (string) Str::uuid();
            }
            if (empty($session->expires_at)) {
                $session->expires_at = now()->addHours(48);
            }
            if (empty($session->processing_attempts)) {
                $session->processing_attempts = 0;
            }
            if (empty($session->status)) {
                $session->status = self::STATUS_UPLOADED;
            }
            if (empty($session->notification_requested)) {
                $session->notification_requested = false;
            }
            if (empty($session->marital_status)) {
                $session->marital_status = 'single';
            }
            if (empty($session->admin_status)) {
                $session->admin_status = self::ADMIN_STATUS_PENDING;
            }
            if (empty($session->authenticity_score)) {
                $session->authenticity_score = 0;
            }
            if (empty($session->authenticity_score_details)) {
                $session->authenticity_score_details = [];
            }
        });
        
        static::deleting(function ($session) {
            $session->deleteFile();
            $session->deletePdfReport();
        });
        
        static::updated(function ($session) {
            if ($session->wasChanged('status')) {
                Log::info('Instant Estate Session status changed', [
                    'session_id' => $session->session_id,
                    'old_status' => $session->getOriginal('status'),
                    'new_status' => $session->status,
                    'user_id' => $session->user_id,
                ]);
            }
            
            if ($session->wasChanged('admin_status')) {
                Log::info('Instant Estate Session admin review status changed', [
                    'session_id' => $session->session_id,
                    'old_admin_status' => $session->getOriginal('admin_status'),
                    'new_admin_status' => $session->admin_status,
                    'reviewed_by' => $session->reviewed_by_admin_id,
                ]);
            }

            if ($session->wasChanged('authenticity_score')) {
                Log::info('Instant Estate Session authenticity score changed', [
                    'session_id' => $session->session_id,
                    'old_score' => $session->getOriginal('authenticity_score'),
                    'new_score' => $session->authenticity_score,
                ]);
            }
        });
    }

    // =========================================================================
    // API RESPONSE METHODS
    // =========================================================================

    public function toApiResponse(): array
    {
        return [
            'session_id' => $this->session_id,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'admin_status' => $this->admin_status,
            'admin_status_label' => $this->admin_status_label,
            'admin_status_color' => $this->admin_status_color,
            'progress_percentage' => $this->processing_progress_percentage,
            'deceased_name' => $this->deceased_name,
            'deceased_nric' => $this->masked_nric,
            'death_date' => $this->formatted_death_date,
            'original_filename' => $this->original_filename,
            'created_at' => $this->created_at?->toIso8601String(),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'is_active' => $this->is_active,
            'has_matched_record' => $this->has_matched_record,
            'has_report' => $this->has_report,
            'has_pdf_report' => $this->has_pdf_report,
            'report_pdf_url' => $this->report_pdf_url,
            'can_confirm_data' => $this->canConfirmData(),
            'can_verify_captcha' => $this->canVerifyCaptcha(),
            'can_request_notification' => $this->canRequestNotification(),
            'can_retry' => $this->canRetry(),
            'can_cancel' => $this->canCancel(),
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->formatted_contact_phone,
            'gender' => $this->gender,
            'are_debts_settled' => $this->are_debts_settled,
            'total_debts' => $this->total_debts,
            'remaining_debt_amount' => $this->remaining_debt_amount,
            'authenticity_score' => $this->authenticity_score,
            'authenticity_level' => $this->authenticity_level,
            'is_authentic' => $this->is_authentic,
            'authenticity_breakdown' => $this->authenticity_breakdown,
            'authenticity_breakdown_summary' => $this->authenticity_breakdown_summary,
            'registration_number' => $this->registration_number,
        ];
    }

    public function toAdminApiResponse(): array
    {
        return array_merge($this->toApiResponse(), [
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name,
            'user_email' => $this->user?->email,
            'guest_email' => $this->guest_email,
            'guest_name' => $this->guest_name,
            'file_size' => $this->formatted_file_size,
            'file_hash' => $this->file_hash,
            'ocr_confidence' => $this->ocr_confidence,
            'missing_fields' => $this->missing_fields,
            'error_message' => $this->error_message,
            'processing_attempts' => $this->processing_attempts,
            'processing_time_ms' => $this->processing_time_ms,
            'data_confirmed_at' => $this->data_confirmed_at?->toIso8601String(),
            'captcha_verified_at' => $this->captcha_verified_at?->toIso8601String(),
            'notification_requested_at' => $this->notification_requested_at?->toIso8601String(),
            'email_sent_at' => $this->email_sent_at?->toIso8601String(),
            'report_generated_at' => $this->report_generated_at?->toIso8601String(),
            'matched_record_type' => $this->matched_record_type,
            'matched_record_id' => $this->matched_record_id,
            'recipient_email' => $this->recipient_email,
            'cause_of_death' => $this->cause_of_death,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'spouse_name' => $this->spouse_name,
            'marital_status' => $this->marital_status,
            'residential_address' => $this->residential_address,
            'date_of_birth' => $this->date_of_birth?->toIso8601String(),
            'notification_request_id' => $this->notification_request_id,
            'calculation_id' => $this->calculation_id,
            'last_processing_attempt_at' => $this->last_processing_attempt_at?->toIso8601String(),
            'processing_completed_at' => $this->processing_completed_at?->toIso8601String(),
            'deceased_nric_original' => $this->deceased_nric,
            'contact_phone_original' => $this->contact_phone,
            'residential_address_full' => $this->residential_address,
            'extracted_data' => $this->extracted_data,
            'report_data' => $this->report_data,
            'rejection_reason' => $this->rejection_reason,
            'reviewed_by_admin_id' => $this->reviewed_by_admin_id,
            'reviewed_by_admin_name' => $this->reviewedByAdmin?->name,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'quality_check_details' => $this->quality_check_details,
            'quality_issues' => $this->quality_issues,
            'authenticity_score_details' => $this->authenticity_score_details,
        ]);
    }
}