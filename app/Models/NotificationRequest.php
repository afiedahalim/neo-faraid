<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationRequest extends Model
{
    protected $table = 'notification_requests';

    protected $fillable = [
        // Session identifiers
        'session_id',
        'instant_estate_session_id',
        'estate_pre_registration_id',
        
        // User references
        'user_id',
        'requested_by',
        
        // Recipient information
        'recipient_email',
        'recipient_name',
        
        // Request status
        'status',
        
        // Security token
        'access_token',
        
        // Deceased information
        'deceased_name',
        'deceased_nric',
        'death_date',
        'death_place',
        
        // Timeline
        'requested_at',
        'approved_at',
        'rejected_at',
        'sent_at',
        
        // Approval/Rejection tracking
        'approved_by',
        'rejected_by',
        'admin_notes',
        
        // Notification sending status
        'notification_sent',
        'notification_sent_at',
        
        // Email tracking
        'emails_sent',
        'email_status',
        'email_results',
        'recipients_count',
        'recipients_list',
        
        // Verification & rejection details
        'verification_result',
        'rejection_reason',
        
        // Metadata
        'request_metadata',
        'metadata',
        
        // Resend tracking
        'resend_count',
        'last_resend_at',
        
        // Error tracking
        'error_message',
        
        // PDF content for email attachment
        'pdf_content',
        
        // PDF path for stored file
        'pdf_path',
        
        // Sent by user
        'sent_by',
    ];

    protected $casts = [
        // Timeline
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'sent_at' => 'datetime',
        'notification_sent_at' => 'datetime',
        'last_resend_at' => 'datetime',
        
        // Boolean flags
        'notification_sent' => 'boolean',
        'emails_sent' => 'boolean',
        
        // JSON fields
        'email_results' => 'array',
        'recipients_list' => 'array',
        'verification_result' => 'array',
        'request_metadata' => 'array',
        'metadata' => 'array',
        
        // String fields
        'pdf_content' => 'string',
        
        // Dates
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'death_date' => 'date',
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'is_pending',
        'is_approved',
        'is_rejected',
        'is_sent',
        'is_email_sent',
        'formatted_deceased_name',
        'formatted_death_date',
        'formatted_requested_at',
        'formatted_approved_at',
        'has_pdf_content',
        'pdf_size',
        'access_url',
        'email_results_summary',
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    public const STATUS_PENDING_ADMIN_APPROVAL = 'pending_admin_approval';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SENT = 'sent';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_FAILED = 'failed';

    public const EMAIL_STATUS_SENT = 'sent';
    public const EMAIL_STATUS_PARTIAL = 'partial';
    public const EMAIL_STATUS_FAILED = 'failed';
    public const EMAIL_STATUS_PENDING = 'pending';

    public static array $statuses = [
        self::STATUS_PENDING_ADMIN_APPROVAL => 'Pending Admin Approval',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_SENT => 'Sent',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_FAILED => 'Failed',
    ];

    public static array $statusColors = [
        self::STATUS_PENDING_ADMIN_APPROVAL => 'warning',
        self::STATUS_PENDING => 'secondary',
        self::STATUS_APPROVED => 'success',
        self::STATUS_REJECTED => 'danger',
        self::STATUS_SENT => 'info',
        self::STATUS_CANCELLED => 'dark',
        self::STATUS_FAILED => 'danger',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function session(): BelongsTo
    {
        return $this->belongsTo(InstantEstateSession::class, 'instant_estate_session_id');
    }

    public function instantEstateSession(): BelongsTo
    {
        return $this->belongsTo(InstantEstateSession::class, 'instant_estate_session_id');
    }

    public function estate(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class, 'estate_pre_registration_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
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

    public function getIsPendingAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING_ADMIN_APPROVAL, self::STATUS_PENDING]);
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getIsSentAttribute(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    public function getIsEmailSentAttribute(): bool
    {
        return $this->emails_sent === true;
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
        return $this->death_date->format('d M Y');
    }

    public function getFormattedRequestedAtAttribute(): string
    {
        if (!$this->requested_at) {
            return 'Not requested';
        }
        return $this->requested_at->format('d M Y, h:i A');
    }

    public function getFormattedApprovedAtAttribute(): string
    {
        if (!$this->approved_at) {
            return 'Not approved';
        }
        return $this->approved_at->format('d M Y, h:i A');
    }

    public function getHasPdfContentAttribute(): bool
    {
        return !empty($this->pdf_content);
    }

    public function getPdfSizeAttribute(): string
    {
        if (empty($this->pdf_content)) {
            return '0 B';
        }
        
        $decodedContent = $this->getDecodedPdfContentAttribute();
        if (!$decodedContent) {
            return '0 B';
        }
        
        $bytes = strlen($decodedContent);
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }

    public function getDecodedPdfContentAttribute(): ?string
    {
        if (empty($this->pdf_content)) {
            return null;
        }
        
        // Check if it's base64 encoded
        if (base64_encode(base64_decode($this->pdf_content, true)) === $this->pdf_content) {
            return base64_decode($this->pdf_content);
        }
        
        return $this->pdf_content;
    }

    public function getAccessUrlAttribute(): string
    {
        if (!$this->access_token) {
            return '#';
        }
        return route('estate.secure-view', $this->access_token);
    }

    public function getEmailResultsSummaryAttribute(): array
    {
        $results = $this->email_results ?? [];
        return [
            'total_attempted' => $results['total_attempted'] ?? 0,
            'successful' => $results['successful'] ?? 0,
            'failed' => $results['failed'] ?? 0,
            'failed_recipients' => $results['failed_recipients'] ?? [],
            'sent_at' => $results['sent_at'] ?? null,
        ];
    }

    // =========================================================================
    // STATUS CHECK METHODS
    // =========================================================================

    public function isPendingAdminApproval(): bool
    {
        return $this->status === self::STATUS_PENDING_ADMIN_APPROVAL;
    }

    public function canBeApproved(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING_ADMIN_APPROVAL, self::STATUS_PENDING]);
    }

    public function canBeRejected(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING_ADMIN_APPROVAL, self::STATUS_PENDING]);
    }

    public function canBeResent(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING_ADMIN_APPROVAL, self::STATUS_APPROVED, self::STATUS_SENT, self::STATUS_FAILED]);
    }

    public function canSendEmail(): bool
    {
        return $this->status === self::STATUS_APPROVED && !empty($this->recipient_email);
    }

    public function canSendEmailWithPdf(): bool
    {
        return $this->canSendEmail() && $this->has_pdf_content;
    }

    // =========================================================================
    // ACTION METHODS
    // =========================================================================

    public function approve(int $adminId, ?string $notes = null): self
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $adminId,
            'admin_notes' => $notes ?? $this->admin_notes,
        ]);
        return $this;
    }

    public function reject(int $adminId, string $reason): self
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejected_by' => $adminId,
            'rejection_reason' => $reason,
        ]);
        return $this;
    }

    public function markEmailSent(array $results = []): self
    {
        $updateData = [
            'status' => self::STATUS_SENT,
            'emails_sent' => true,
            'email_status' => self::EMAIL_STATUS_SENT,
            'notification_sent' => true,
            'sent_at' => now(),
            'notification_sent_at' => now(),
        ];
        
        if (!empty($results)) {
            $updateData['email_results'] = array_merge($this->email_results ?? [], $results);
        }
        
        $this->update($updateData);
        return $this;
    }

    public function markAsSent(int $adminId = null): self
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => now(),
            'sent_by' => $adminId ?? auth()->id(),
            'emails_sent' => true,
            'notification_sent' => true,
            'email_status' => self::EMAIL_STATUS_SENT,
        ]);
        return $this;
    }

    public function markEmailPartial(array $results = []): self
    {
        $this->update([
            'status' => self::STATUS_PENDING,
            'emails_sent' => false,
            'email_status' => self::EMAIL_STATUS_PARTIAL,
            'notification_sent' => false,
            'email_results' => array_merge($this->email_results ?? [], $results),
        ]);
        return $this;
    }

    public function markEmailFailed(string $error): self
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'emails_sent' => false,
            'email_status' => self::EMAIL_STATUS_FAILED,
            'notification_sent' => false,
            'error_message' => $error,
        ]);
        return $this;
    }

    public function cancel(): self
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
        return $this;
    }

    public function regenerateToken(): self
    {
        $this->update(['access_token' => Str::random(64)]);
        return $this;
    }

    public function updateMetadata(array $metadata): self
    {
        $currentMetadata = $this->request_metadata ?? [];
        $this->update(['request_metadata' => array_merge($currentMetadata, $metadata)]);
        return $this;
    }

    public function addEmailResult(string $recipient, bool $success, ?string $error = null): self
    {
        $results = $this->email_results ?? [];
        
        if (!isset($results['attempts'])) {
            $results['attempts'] = [];
            $results['successful'] = 0;
            $results['failed'] = 0;
            $results['total_attempted'] = 0;
            $results['failed_recipients'] = [];
        }
        
        $results['attempts'][] = [
            'recipient' => $recipient,
            'success' => $success,
            'error' => $error,
            'timestamp' => now()->toIso8601String(),
        ];
        
        $results['total_attempted']++;
        
        if ($success) {
            $results['successful']++;
        } else {
            $results['failed']++;
            if (!in_array($recipient, $results['failed_recipients'])) {
                $results['failed_recipients'][] = $recipient;
            }
        }
        
        $results['sent_at'] = now()->toIso8601String();
        
        $this->update(['email_results' => $results]);
        
        $recipientsList = $this->recipients_list ?? [];
        if (!in_array($recipient, $recipientsList)) {
            $recipientsList[] = $recipient;
            $this->update([
                'recipients_list' => $recipientsList,
                'recipients_count' => count($recipientsList),
            ]);
        }
        
        return $this;
    }

    public function setPdfContent(string $pdfContent, bool $isBase64 = false): self
    {
        if ($isBase64) {
            // Verify it's already base64 encoded
            if (base64_encode(base64_decode($pdfContent, true)) !== $pdfContent) {
                $pdfContent = base64_encode($pdfContent);
            }
        } else {
            $pdfContent = base64_encode($pdfContent);
        }
        
        $this->update(['pdf_content' => $pdfContent]);
        return $this;
    }

    public function clearPdfContent(): self
    {
        $this->update(['pdf_content' => null]);
        return $this;
    }

    public function getPdfAttachment(string $filename = 'report.pdf'): ?array
    {
        if (!$this->has_pdf_content) {
            return null;
        }
        
        $decodedContent = $this->getDecodedPdfContentAttribute();
        if (!$decodedContent) {
            return null;
        }
        
        return [
            'data' => $decodedContent,
            'name' => $filename,
            'mime' => 'application/pdf',
        ];
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopePendingApproval($query)
    {
        return $query->where('status', self::STATUS_PENDING_ADMIN_APPROVAL);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeSent($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING_ADMIN_APPROVAL, self::STATUS_PENDING]);
    }

    public function scopeBySession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
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

    public function scopeNotExpired($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    public function scopeEmailNotSent($query)
    {
        return $query->where('emails_sent', false)->where('status', self::STATUS_APPROVED);
    }

    public function scopeByRecipientEmail($query, string $email)
    {
        return $query->where('recipient_email', 'like', '%' . $email . '%');
    }

    public function scopeWithPdfContent($query)
    {
        return $query->whereNotNull('pdf_content');
    }

    // =========================================================================
    // STATISTICS METHODS
    // =========================================================================

    public static function getStatistics(?int $userId = null): array
    {
        $query = self::query();
        if ($userId) $query->where('user_id', $userId);
        
        return [
            'total' => $query->count(),
            'pending_admin_approval' => $query->clone()->pendingApproval()->count(),
            'approved' => $query->clone()->approved()->count(),
            'rejected' => $query->clone()->rejected()->count(),
            'sent' => $query->clone()->sent()->count(),
            'email_sent' => $query->clone()->where('emails_sent', true)->count(),
            'email_pending' => $query->clone()->where('emails_sent', false)->where('status', self::STATUS_APPROVED)->count(),
            'with_pdf_content' => $query->clone()->withPdfContent()->count(),
            'this_month' => $query->clone()->whereMonth('created_at', now()->month)->count(),
            'last_month' => $query->clone()->whereMonth('created_at', now()->subMonth()->month)->count(),
        ];
    }

    // =========================================================================
    // BOOT METHOD
    // =========================================================================

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($request) {
            if (empty($request->access_token)) {
                $request->access_token = Str::random(64);
            }
            if (empty($request->status)) {
                $request->status = self::STATUS_PENDING_ADMIN_APPROVAL;
            }
            if (empty($request->requested_at)) {
                $request->requested_at = now();
            }
            if (empty($request->recipients_count)) {
                $request->recipients_count = 0;
            }
            if (empty($request->emails_sent)) {
                $request->emails_sent = false;
            }
            if (empty($request->notification_sent)) {
                $request->notification_sent = false;
            }
            if (empty($request->email_status)) {
                $request->email_status = self::EMAIL_STATUS_PENDING;
            }
            if (empty($request->resend_count)) {
                $request->resend_count = 0;
            }
            
            // Set nullable foreign keys to null if not provided
            if (empty($request->estate_pre_registration_id)) {
                $request->estate_pre_registration_id = null;
            }
            if (empty($request->instant_estate_session_id)) {
                $request->instant_estate_session_id = null;
            }
            if (empty($request->approved_by)) {
                $request->approved_by = null;
            }
            if (empty($request->rejected_by)) {
                $request->rejected_by = null;
            }
            if (empty($request->requested_by)) {
                $request->requested_by = null;
            }
            if (empty($request->sent_by)) {
                $request->sent_by = null;
            }
        });
        
        static::created(function ($request) {
            $request->updateMetadata([
                'created_via' => 'instant_estate',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
        
        static::updated(function ($request) {
            if ($request->wasChanged('status')) {
                Log::info('Notification request status changed', [
                    'request_id' => $request->id,
                    'old_status' => $request->getOriginal('status'),
                    'new_status' => $request->status,
                ]);
            }
        });
        
        static::deleting(function ($request) {
            if ($request->pdf_content) {
                Log::info('Cleaning up PDF content for notification request', [
                    'request_id' => $request->id,
                ]);
            }
        });
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    public function getDeceasedName(): string
    {
        return $this->deceased_name ?? 'the deceased';
    }

    public function requiresVerification(): bool
    {
        return $this->status === self::STATUS_PENDING_ADMIN_APPROVAL;
    }

    public function getTimeElapsed(): ?string
    {
        if (!$this->requested_at) return null;
        return $this->requested_at->diffForHumans();
    }

    public function getApprovalTime(): ?string
    {
        if (!$this->requested_at || !$this->approved_at) return null;
        $diff = $this->requested_at->diffInMinutes($this->approved_at);
        if ($diff < 60) return $diff . ' minutes';
        if ($diff < 1440) return round($diff / 60, 1) . ' hours';
        return round($diff / 1440, 1) . ' days';
    }

    public function getPdfFilename(): string
    {
        $deceasedName = preg_replace('/[^a-zA-Z0-9]/', '_', $this->getDeceasedName());
        return 'estate_report_' . $deceasedName . '_' . $this->id . '.pdf';
    }

    public function toApiResponse(): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'deceased_name' => $this->deceased_name,
            'deceased_nric' => $this->deceased_nric,
            'death_date' => $this->formatted_death_date,
            'recipient_email' => $this->recipient_email,
            'requested_at' => $this->formatted_requested_at,
            'approved_at' => $this->formatted_approved_at,
            'emails_sent' => $this->emails_sent,
            'has_pdf_content' => $this->has_pdf_content,
            'pdf_size' => $this->pdf_size,
            'access_url' => $this->access_url,
            'can_be_approved' => $this->canBeApproved(),
            'can_be_rejected' => $this->canBeRejected(),
            'can_be_resent' => $this->canBeResent(),
        ];
    }

    public function toAdminApiResponse(): array
    {
        return array_merge($this->toApiResponse(), [
            'admin_notes' => $this->admin_notes,
            'rejection_reason' => $this->rejection_reason,
            'approved_by' => $this->approvedBy?->name,
            'rejected_by' => $this->rejectedBy?->name,
            'requested_by_name' => $this->requestedBy?->name,
            'user_name' => $this->user?->name,
            'email_results_summary' => $this->email_results_summary,
            'recipients_count' => $this->recipients_count,
            'recipients_list' => $this->recipients_list,
            'approval_time' => $this->getApprovalTime(),
            'time_elapsed' => $this->getTimeElapsed(),
            'resend_count' => $this->resend_count,
            'last_resend_at' => $this->last_resend_at?->toIso8601String(),
            'error_message' => $this->error_message,
            'deceased_nric' => $this->deceased_nric,
            'death_place' => $this->death_place,
            'sent_at' => $this->sent_at?->toIso8601String(),
            'notification_sent' => $this->notification_sent,
            'notification_sent_at' => $this->notification_sent_at?->toIso8601String(),
            'email_status' => $this->email_status,
            'verification_result' => $this->verification_result,
            'request_metadata' => $this->request_metadata,
        ]);
    }
}