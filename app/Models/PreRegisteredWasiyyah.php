<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PreRegisteredWasiyyah extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_registered_wasiyyah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Foreign Key
        'estate_pre_registration_id',

        // Beneficiary Personal Information
        'beneficiary_name',
        'beneficiary_nric',
        'beneficiary_email',
        'beneficiary_phone',
        'beneficiary_address',
        'beneficiary_gender',
        'beneficiary_date_of_birth',

        // Relationship Information
        'relationship',
        'beneficiary_relationship_type',

        // Organization/Charity Information
        'beneficiary_organization_name',
        'beneficiary_organization_registration_number',
        'is_charity',
        'charity_registration_number',
        'charity_tax_exempt_number',

        // Beneficiary Classification
        'is_non_muslim',

        // Bank Information
        'beneficiary_bank_name',
        'beneficiary_bank_account_number',
        'beneficiary_bank_account_name',

        // Wasiyyah Allocation
        'requested_percentage',
        'approved_percentage',
        'allocation_type',
        'allocated_asset_description',
        'allocated_asset_value',
        'description',

        // Execution Planning
        'execution_priority',
        'priority_level',
        'execution_conditions',
        'execution_deadline',
        'execution_grace_period_days',

        // Revocation Tracking
        'is_revoked',
        'revoked_at',
        'revoked_reason',

        // Execution Tracking
        'is_executed',
        'executed_at',
        'executed_by',
        'execution_notes',
        'execution_amount',
        'execution_receipt_number',
        'execution_proof_document',

        // Witness Information
        'witness_name',
        'witness_nric',
        'witness_phone',

        // Reminder Tracking
        'last_reminder_sent_at',
        'reminder_count',

        // Verification Tracking
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',

        // Tags and Metadata
        'tags',
        'metadata',

        // Dispute Tracking
        'is_disputed',
        'dispute_reason',
        'dispute_resolution',
        'dispute_resolved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Numeric casts
        'requested_percentage' => 'decimal:2',
        'approved_percentage' => 'decimal:2',
        'allocated_asset_value' => 'decimal:2',
        'execution_amount' => 'decimal:2',

        // Integer casts
        'execution_priority' => 'integer',
        'execution_grace_period_days' => 'integer',
        'reminder_count' => 'integer',

        // Boolean casts
        'is_charity' => 'boolean',
        'is_non_muslim' => 'boolean',
        'is_revoked' => 'boolean',
        'is_executed' => 'boolean',
        'is_disputed' => 'boolean',

        // Date casts
        'beneficiary_date_of_birth' => 'date',
        'execution_deadline' => 'date',

        // DateTime casts
        'revoked_at' => 'datetime',
        'executed_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
        'verified_at' => 'datetime',
        'dispute_resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

        // JSON casts
        'tags' => 'array',
        'metadata' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'formatted_percentage',
        'calculated_amount',
        'formatted_amount',
        'formatted_execution_amount',
        'full_contact_info',
        'relationship_label',
        'priority_level_badge',
        'verification_status_badge',
        'allocation_details',
        'days_until_deadline',
        'is_overdue',
        'is_pending_execution',
        'can_be_executed',
        'execution_status_label',
        'beneficiary_type_label',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the estate pre-registration that owns this wasiyyah.
     *
     * @return BelongsTo
     */
    public function estatePreRegistration(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class);
    }

    /**
     * Get the user who executed this wasiyyah.
     *
     * @return BelongsTo
     */
    public function executedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    /**
     * Get the user who verified this wasiyyah.
     *
     * @return BelongsTo
     */
    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =========================================================================
    // FINANCIAL ACCESSORS
    // =========================================================================

    /**
     * Get the formatted percentage string.
     *
     * @return string
     */
    public function getFormattedPercentageAttribute(): string
    {
        return number_format($this->requested_percentage, 2) . '%';
    }

    /**
     * Get the calculated amount based on net estate.
     *
     * @return float
     */
    public function getCalculatedAmountAttribute(): float
    {
        // Use approved percentage if available, otherwise requested
        $percentage = $this->approved_percentage ?? $this->requested_percentage;
        $netEstate = $this->estatePreRegistration->net_estate ?? 0;
        
        return round(($percentage / 100) * $netEstate, 2);
    }

    /**
     * Get the formatted amount string.
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'RM ' . number_format($this->calculated_amount, 2);
    }

    /**
     * Get the execution amount formatted.
     *
     * @return string
     */
    public function getFormattedExecutionAmountAttribute(): string
    {
        if (!$this->execution_amount) {
            return 'Not yet executed';
        }
        return 'RM ' . number_format($this->execution_amount, 2);
    }

    // =========================================================================
    // CONTACT ACCESSORS
    // =========================================================================

    /**
     * Get beneficiary full contact info.
     *
     * @return string
     */
    public function getFullContactInfoAttribute(): string
    {
        $contacts = [];
        
        if ($this->beneficiary_email) {
            $contacts[] = '📧 ' . $this->beneficiary_email;
        }
        if ($this->beneficiary_phone) {
            $contacts[] = '📱 ' . $this->beneficiary_phone;
        }
        
        return !empty($contacts) ? implode(' | ', $contacts) : 'No contact information available';
    }

    /**
     * Get email and phone as an array.
     *
     * @return array
     */
    public function getContactDetailsAttribute(): array
    {
        return [
            'email' => $this->beneficiary_email,
            'phone' => $this->beneficiary_phone,
        ];
    }

    /**
     * Check if beneficiary has complete contact information.
     *
     * @return bool
     */
    public function getHasCompleteContactAttribute(): bool
    {
        return !empty($this->beneficiary_email) && !empty($this->beneficiary_phone);
    }

    // =========================================================================
    // RELATIONSHIP ACCESSORS
    // =========================================================================

    /**
     * Get the relationship label for display.
     *
     * @return string
     */
    public function getRelationshipLabelAttribute(): string
    {
        $labels = [
            'Charity Organization' => 'Charity Organization',
            'Mosque' => 'Mosque / Surau',
            'School' => 'Educational Institution',
            'Friend' => 'Friend',
            'Non-Heir Relative' => 'Non-Heir Relative',
            'Other' => 'Other',
        ];
        
        return $labels[$this->relationship] ?? ucfirst($this->relationship);
    }

    /**
     * Get the beneficiary type label.
     *
     * @return string
     */
    public function getBeneficiaryTypeLabelAttribute(): string
    {
        if ($this->is_charity) {
            return 'Charity / Organization';
        }
        
        if ($this->beneficiary_relationship_type) {
            return ucfirst($this->beneficiary_relationship_type);
        }
        
        return 'Individual';
    }

    // =========================================================================
    // STATUS ACCESSORS
    // =========================================================================

    /**
     * Get the priority level badge with HTML color.
     *
     * @return string
     */
    public function getPriorityLevelBadgeAttribute(): string
    {
        $badges = [
            'low' => '<span class="badge bg-info">Low</span>',
            'medium' => '<span class="badge bg-warning text-dark">Medium</span>',
            'high' => '<span class="badge bg-danger">High</span>',
            'critical' => '<span class="badge bg-dark">Critical</span>',
        ];
        
        return $badges[$this->priority_level] ?? '<span class="badge bg-secondary">' . ucfirst($this->priority_level ?? 'Unknown') . '</span>';
    }

    /**
     * Get the priority level CSS class for styling.
     *
     * @return string
     */
    public function getPriorityLevelClassAttribute(): string
    {
        $classes = [
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'dark',
        ];
        
        return $classes[$this->priority_level] ?? 'secondary';
    }

    /**
     * Get the verification status badge.
     *
     * @return string
     */
    public function getVerificationStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">Pending Verification</span>',
            'verified' => '<span class="badge bg-success">Verified</span>',
            'disputed' => '<span class="badge bg-danger">Disputed</span>',
            'resolved' => '<span class="badge bg-info">Resolved</span>',
        ];
        
        return $badges[$this->verification_status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get the execution status label.
     *
     * @return string
     */
    public function getExecutionStatusLabelAttribute(): string
    {
        if ($this->is_executed) {
            return 'Executed';
        }
        
        if ($this->is_revoked) {
            return 'Revoked';
        }
        
        if ($this->is_disputed) {
            return 'Disputed';
        }
        
        if ($this->verification_status === 'verified') {
            return 'Ready for Execution';
        }
        
        return 'Pending';
    }

    /**
     * Get the execution status CSS class.
     *
     * @return string
     */
    public function getExecutionStatusClassAttribute(): string
    {
        if ($this->is_executed) {
            return 'success';
        }
        
        if ($this->is_revoked) {
            return 'danger';
        }
        
        if ($this->is_disputed) {
            return 'danger';
        }
        
        if ($this->verification_status === 'verified') {
            return 'info';
        }
        
        return 'warning';
    }

    // =========================================================================
    // ALLOCATION ACCESSORS
    // =========================================================================

    /**
     * Get the allocated asset details.
     *
     * @return array
     */
    public function getAllocationDetailsAttribute(): array
    {
        return [
            'type' => $this->allocation_type ?? 'percentage',
            'type_label' => $this->getAllocationTypeLabel(),
            'percentage' => $this->allocation_type === 'percentage' ? $this->requested_percentage : null,
            'fixed_amount' => $this->allocation_type === 'fixed_amount' ? $this->approved_percentage : null,
            'asset_description' => $this->allocation_type === 'specific_asset' ? $this->allocated_asset_description : null,
            'asset_value' => $this->allocation_type === 'specific_asset' ? $this->allocated_asset_value : null,
            'formatted_asset_value' => $this->allocated_asset_value ? 'RM ' . number_format($this->allocated_asset_value, 2) : null,
        ];
    }

    /**
     * Get the allocation type label.
     *
     * @return string
     */
    public function getAllocationTypeLabel(): string
    {
        $labels = [
            'percentage' => 'Percentage of Estate',
            'fixed_amount' => 'Fixed Amount',
            'specific_asset' => 'Specific Asset',
        ];
        
        return $labels[$this->allocation_type] ?? 'Percentage of Estate';
    }

    // =========================================================================
    // DATE ACCESSORS
    // =========================================================================

    /**
     * Check if this wasiyyah is overdue for execution.
     *
     * @return bool
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->execution_deadline || $this->is_executed || $this->is_revoked) {
            return false;
        }
        return now()->startOfDay()->gt($this->execution_deadline);
    }

    /**
     * Get days remaining until execution deadline.
     *
     * @return int|null
     */
    public function getDaysUntilDeadlineAttribute(): ?int
    {
        if (!$this->execution_deadline || $this->is_executed || $this->is_revoked) {
            return null;
        }
        
        $remaining = now()->startOfDay()->diffInDays($this->execution_deadline, false);
        return max(0, $remaining);
    }

    /**
     * Get days overdue.
     *
     * @return int|null
     */
    public function getDaysOverdueAttribute(): ?int
    {
        if (!$this->is_overdue) {
            return null;
        }
        
        return now()->startOfDay()->diffInDays($this->execution_deadline);
    }

    /**
     * Get the formatted execution deadline.
     *
     * @return string
     */
    public function getFormattedExecutionDeadlineAttribute(): string
    {
        if (!$this->execution_deadline) {
            return 'No deadline set';
        }
        
        return $this->execution_deadline->format('d F Y');
    }

    /**
     * Get the formatted revoked date.
     *
     * @return string|null
     */
    public function getFormattedRevokedDateAttribute(): ?string
    {
        return $this->revoked_at ? $this->revoked_at->format('d F Y, h:i A') : null;
    }

    /**
     * Get the formatted executed date.
     *
     * @return string|null
     */
    public function getFormattedExecutedDateAttribute(): ?string
    {
        return $this->executed_at ? $this->executed_at->format('d F Y, h:i A') : null;
    }

    /**
     * Get the formatted verified date.
     *
     * @return string|null
     */
    public function getFormattedVerifiedDateAttribute(): ?string
    {
        return $this->verified_at ? $this->verified_at->format('d F Y, h:i A') : null;
    }

    // =========================================================================
    // COMPUTED BOOLEAN ACCESSORS
    // =========================================================================

    /**
     * Check if this wasiyyah is still pending execution.
     *
     * @return bool
     */
    public function getIsPendingExecutionAttribute(): bool
    {
        return !$this->is_executed && !$this->is_revoked;
    }

    /**
     * Check if this wasiyyah can be executed.
     *
     * @return bool
     */
    public function getCanBeExecutedAttribute(): bool
    {
        return $this->is_pending_execution && 
               $this->verification_status === 'verified' && 
               !$this->is_disputed;
    }

    /**
     * Check if this is a charity wasiyyah.
     *
     * @return bool
     */
    public function getIsCharityWasiyyahAttribute(): bool
    {
        return $this->is_charity || 
               in_array($this->relationship, ['Charity Organization', 'Mosque', 'School']);
    }

    /**
     * Check if this wasiyyah has bank details.
     *
     * @return bool
     */
    public function getHasBankDetailsAttribute(): bool
    {
        return !empty($this->beneficiary_bank_name) && 
               !empty($this->beneficiary_bank_account_number);
    }

    /**
     * Check if this wasiyyah has witness information.
     *
     * @return bool
     */
    public function getHasWitnessAttribute(): bool
    {
        return !empty($this->witness_name) && !empty($this->witness_nric);
    }

    /**
     * Check if grace period has expired.
     *
     * @return bool
     */
    public function getIsGracePeriodExpiredAttribute(): bool
    {
        if (!$this->executed_at || $this->is_executed || $this->is_revoked) {
            return false;
        }
        
        $graceDays = $this->execution_grace_period_days ?? 90;
        return now()->diffInDays($this->created_at) > $graceDays;
    }

    // =========================================================================
    // EXECUTION METHODS
    // =========================================================================

    /**
     * Mark this wasiyyah as executed.
     *
     * @param  float       $amount
     * @param  string|null $receiptNumber
     * @param  string|null $notes
     * @param  int|null    $executedBy
     * @return bool
     */
    public function markAsExecuted(float $amount, ?string $receiptNumber = null, ?string $notes = null, ?int $executedBy = null): bool
    {
        try {
            $result = $this->update([
                'is_executed' => true,
                'executed_at' => now(),
                'execution_amount' => $amount,
                'execution_receipt_number' => $receiptNumber,
                'execution_notes' => $notes,
                'executed_by' => $executedBy ?? auth()->id(),
                'verification_status' => 'verified',
                'is_disputed' => false,
            ]);

            Log::info('Wasiyyah marked as executed', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'amount' => $amount,
                'executed_by' => $executedBy ?? auth()->id(),
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to mark wasiyyah as executed: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Mark this wasiyyah as revoked.
     *
     * @param  string|null $reason
     * @return bool
     */
    public function markAsRevoked(?string $reason = null): bool
    {
        try {
            $result = $this->update([
                'is_revoked' => true,
                'revoked_at' => now(),
                'revoked_reason' => $reason,
            ]);

            Log::info('Wasiyyah marked as revoked', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'reason' => $reason,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to mark wasiyyah as revoked: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Mark this wasiyyah as verified.
     *
     * @param  string|null $notes
     * @param  int|null    $verifiedBy
     * @return bool
     */
    public function markAsVerified(?string $notes = null, ?int $verifiedBy = null): bool
    {
        try {
            $result = $this->update([
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verification_notes' => $notes,
                'verified_by' => $verifiedBy ?? auth()->id(),
            ]);

            Log::info('Wasiyyah marked as verified', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'verified_by' => $verifiedBy ?? auth()->id(),
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to mark wasiyyah as verified: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Mark this wasiyyah as disputed.
     *
     * @param  string $reason
     * @return bool
     */
    public function markAsDisputed(string $reason): bool
    {
        try {
            $result = $this->update([
                'is_disputed' => true,
                'verification_status' => 'disputed',
                'dispute_reason' => $reason,
            ]);

            Log::warning('Wasiyyah marked as disputed', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'reason' => $reason,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to mark wasiyyah as disputed: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Resolve the dispute.
     *
     * @param  string $resolution
     * @return bool
     */
    public function resolveDispute(string $resolution): bool
    {
        try {
            $result = $this->update([
                'is_disputed' => false,
                'verification_status' => 'resolved',
                'dispute_resolution' => $resolution,
                'dispute_resolved_at' => now(),
            ]);

            Log::info('Wasiyyah dispute resolved', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'resolution' => $resolution,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to resolve wasiyyah dispute: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send a reminder for this wasiyyah.
     *
     * @return bool
     */
    public function sendReminder(): bool
    {
        try {
            $result = $this->update([
                'last_reminder_sent_at' => now(),
                'reminder_count' => $this->reminder_count + 1,
            ]);

            Log::info('Wasiyyah reminder sent', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'reminder_count' => $this->reminder_count + 1,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to send wasiyyah reminder: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Approve the requested percentage.
     *
     * @param  float  $approvedPercentage
     * @param  string|null $notes
     * @return bool
     */
    public function approvePercentage(float $approvedPercentage, ?string $notes = null): bool
    {
        try {
            $result = $this->update([
                'approved_percentage' => $approvedPercentage,
                'verification_notes' => $notes,
            ]);

            Log::info('Wasiyyah percentage approved', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'requested' => $this->requested_percentage,
                'approved' => $approvedPercentage,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to approve wasiyyah percentage: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Upload execution proof document.
     *
     * @param  string $documentPath
     * @return bool
     */
    public function uploadExecutionProof(string $documentPath): bool
    {
        try {
            $result = $this->update([
                'execution_proof_document' => $documentPath,
            ]);

            Log::info('Wasiyyah execution proof uploaded', [
                'wasiyyah_id' => $this->id,
                'beneficiary' => $this->beneficiary_name,
                'document' => $documentPath,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to upload execution proof: ' . $e->getMessage(), [
                'wasiyyah_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope for pending wasiyyah (not executed and not revoked).
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('is_executed', false)
                    ->where('is_revoked', false);
    }

    /**
     * Scope for executed wasiyyah.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExecuted($query)
    {
        return $query->where('is_executed', true);
    }

    /**
     * Scope for revoked wasiyyah.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRevoked($query)
    {
        return $query->where('is_revoked', true);
    }

    /**
     * Scope for charity wasiyyah.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCharity($query)
    {
        return $query->where('is_charity', true);
    }

    /**
     * Scope for non-Muslim beneficiaries.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonMuslim($query)
    {
        return $query->where('is_non_muslim', true);
    }

    /**
     * Scope for disputed wasiyyah.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisputed($query)
    {
        return $query->where('is_disputed', true);
    }

    /**
     * Scope for wasiyyah with specific priority level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $level
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriority($query, string $level)
    {
        return $query->where('priority_level', $level);
    }

    /**
     * Scope for high or critical priority wasiyyah.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUrgent($query)
    {
        return $query->whereIn('priority_level', ['high', 'critical']);
    }

    /**
     * Scope for overdue wasiyyah.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('is_executed', false)
                    ->where('is_revoked', false)
                    ->whereNotNull('execution_deadline')
                    ->whereDate('execution_deadline', '<', now()->toDateString());
    }

    /**
     * Scope for wasiyyah due within specific days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDueWithinDays($query, int $days)
    {
        return $query->where('is_executed', false)
                    ->where('is_revoked', false)
                    ->whereNotNull('execution_deadline')
                    ->whereDate('execution_deadline', '<=', now()->addDays($days)->toDateString())
                    ->whereDate('execution_deadline', '>=', now()->toDateString());
    }

    /**
     * Scope for wasiyyah by verification status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerificationStatus($query, string $status)
    {
        return $query->where('verification_status', $status);
    }

    /**
     * Scope for wasiyyah ready for execution.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReadyForExecution($query)
    {
        return $query->where('is_executed', false)
                    ->where('is_revoked', false)
                    ->where('verification_status', 'verified')
                    ->where('is_disputed', false);
    }

    /**
     * Scope for wasiyyah with specific allocation type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllocationType($query, string $type)
    {
        return $query->where('allocation_type', $type);
    }

    /**
     * Scope for wasiyyah ordered by execution priority.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderedByPriority($query)
    {
        return $query->orderByRaw("
            CASE 
                WHEN priority_level = 'critical' THEN 1
                WHEN priority_level = 'high' THEN 2
                WHEN priority_level = 'medium' THEN 3
                WHEN priority_level = 'low' THEN 4
                ELSE 5
            END
        ")->orderBy('execution_priority', 'asc');
    }

    /**
     * Scope for wasiyyah that need reminders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int $maxReminders
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsReminder($query, int $maxReminders = 3)
    {
        return $query->where('is_executed', false)
                    ->where('is_revoked', false)
                    ->where('reminder_count', '<', $maxReminders)
                    ->where(function ($q) {
                        $q->whereNull('last_reminder_sent_at')
                          ->orWhere('last_reminder_sent_at', '<=', now()->subDays(7));
                    });
    }

    /**
     * Scope for wasiyyah created within a date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $from
     * @param  string $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope for wasiyyah with tags.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string|array $tags
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasTags($query, $tags)
    {
        if (is_string($tags)) {
            $tags = [$tags];
        }
        
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->whereJsonContains('tags', $tag);
            }
        });
    }

    // =========================================================================
    // STATIC VALIDATION METHODS
    // =========================================================================

    /**
     * Validate total wasiyyah percentage against the 1/3 rule.
     *
     * @param  \Illuminate\Support\Collection|array $wasiyyahItems
     * @param  float $netEstate
     * @return array
     */
    public static function validateTotalPercentage($wasiyyahItems, float $netEstate): array
    {
        $totalPercentage = collect($wasiyyahItems)->sum('requested_percentage');
        $maxPercentage = 33.33; // 1/3 of estate
        
        if ($totalPercentage > $maxPercentage) {
            return [
                'valid' => false,
                'message' => "Total Wasiyyah percentage ({$totalPercentage}%) exceeds the maximum allowed (33.33% / 1/3 of estate).",
                'total' => $totalPercentage,
                'max' => $maxPercentage,
                'excess' => round($totalPercentage - $maxPercentage, 2),
                'max_amount' => round(($maxPercentage / 100) * $netEstate, 2),
                'current_amount' => round(($totalPercentage / 100) * $netEstate, 2),
            ];
        }
        
        return [
            'valid' => true,
            'message' => null,
            'total' => $totalPercentage,
            'max' => $maxPercentage,
            'remaining' => round($maxPercentage - $totalPercentage, 2),
            'max_amount' => round(($maxPercentage / 100) * $netEstate, 2),
            'current_amount' => round(($totalPercentage / 100) * $netEstate, 2),
        ];
    }

    /**
     * Validate beneficiary email format.
     *
     * @param  string $email
     * @return bool
     */
    public static function validateEmail(string $email): bool
    {
        if (empty($email)) return false;
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate beneficiary NRIC format.
     *
     * @param  string $nric
     * @return bool
     */
    public static function validateNRIC(string $nric): bool
    {
        if (empty($nric)) return false;
        $clean = preg_replace('/[-\s]/', '', $nric);
        return preg_match('/^\d{12}$/', $clean) === 1;
    }

    /**
     * Validate beneficiary phone format.
     *
     * @param  string $phone
     * @return bool
     */
    public static function validatePhone(string $phone): bool
    {
        if (empty($phone)) return true;
        $clean = preg_replace('/[-\s]/', '', $phone);
        return preg_match('/^01\d{8,9}$/', $clean) === 1;
    }

    /**
     * Format NRIC to standard format.
     *
     * @param  string $nric
     * @return string
     */
    public static function formatNRIC(string $nric): string
    {
        if (empty($nric)) return '';
        $clean = preg_replace('/[^0-9]/', '', $nric);
        if (strlen($clean) === 12) {
            return substr($clean, 0, 6) . '-' . substr($clean, 6, 2) . '-' . substr($clean, 8, 4);
        }
        return $nric;
    }

    /**
     * Format phone to standard format.
     *
     * @param  string $phone
     * @return string
     */
    public static function formatPhone(string $phone): string
    {
        if (empty($phone)) return '';
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($clean) >= 10 && strlen($clean) <= 11 && str_starts_with($clean, '01')) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $phone;
    }

    // =========================================================================
    // EVENT HANDLERS
    // =========================================================================

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($wasiyyah) {
            // Set default values if not provided
            if (empty($wasiyyah->allocation_type)) {
                $wasiyyah->allocation_type = 'percentage';
            }
            
            if (empty($wasiyyah->priority_level)) {
                $wasiyyah->priority_level = 'medium';
            }
            
            if (empty($wasiyyah->verification_status)) {
                $wasiyyah->verification_status = 'pending';
            }
            
            if (empty($wasiyyah->execution_grace_period_days)) {
                $wasiyyah->execution_grace_period_days = 90;
            }

            // Log creation
            Log::info('New wasiyyah being created', [
                'beneficiary_name' => $wasiyyah->beneficiary_name,
                'percentage' => $wasiyyah->requested_percentage,
                'estate_id' => $wasiyyah->estate_pre_registration_id,
            ]);
        });

        static::updating(function ($wasiyyah) {
            // Log significant changes
            if ($wasiyyah->isDirty('is_executed') && $wasiyyah->is_executed) {
                Log::info('Wasiyyah execution status changed to executed', [
                    'wasiyyah_id' => $wasiyyah->id,
                    'beneficiary' => $wasiyyah->beneficiary_name,
                ]);
            }
            
            if ($wasiyyah->isDirty('is_revoked') && $wasiyyah->is_revoked) {
                Log::info('Wasiyyah has been revoked', [
                    'wasiyyah_id' => $wasiyyah->id,
                    'beneficiary' => $wasiyyah->beneficiary_name,
                    'reason' => $wasiyyah->revoked_reason,
                ]);
            }
            
            if ($wasiyyah->isDirty('verification_status')) {
                Log::info('Wasiyyah verification status changed', [
                    'wasiyyah_id' => $wasiyyah->id,
                    'old_status' => $wasiyyah->getOriginal('verification_status'),
                    'new_status' => $wasiyyah->verification_status,
                ]);
            }
        });

        static::deleting(function ($wasiyyah) {
            // Log deletion
            Log::info('Wasiyyah being deleted', [
                'wasiyyah_id' => $wasiyyah->id,
                'beneficiary' => $wasiyyah->beneficiary_name,
                'estate_id' => $wasiyyah->estate_pre_registration_id,
            ]);
        });
    }
}