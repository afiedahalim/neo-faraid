<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class EstatePreRegistration extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estate_pre_registrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // User Relationship
        'user_id',
        'unique_id',

        // Deceased (Owner) Personal Information
        'deceased_name',
        'deceased_nric',
        'date_of_birth',
        'gender',
        'contact_phone',
        'contact_email',
        'address',

        // Trustee Information
        'trustee_name',
        'trustee_nric',
        'trustee_phone',
        'trustee_email',
        'trustee_relationship',
        'trustee_address',

        // Alternate Trustee Information
        'alternate_trustee_name',
        'alternate_trustee_nric',
        'alternate_trustee_phone',
        'alternate_trustee_email',
        'alternate_trustee_relationship',

        // Estate Status
        'status',
        'completed_at',
        'activated_at',
        'executed_at',

        // Will and Video
        'will_video_url',
        'will_video_type',
        'will_video_thumbnail',
        'will_text_content',

        // Instructions
        'wasiyyah_instructions',
        'special_instructions',
        'funeral_instructions',

        // Emergency Contact
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',

        // Estate Valuation
        'estate_valuation_date',
        'estate_valuation_notes',

        // Lawyer Information
        'lawyer_name',
        'lawyer_contact',
        'lawyer_firm',

        // Witness Information
        'witness_name',
        'witness_nric',
        'witness_phone',

        // Shariah Compliance
        'is_shariah_compliant',
        'shariah_advisor_name',
        'shariah_advisor_contact',

        // Notes and Review
        'estate_notes',
        'reviewed_at',
        'reviewed_by',

        // Security & Access
        'access_token',
        'token_expires_at',

        // Admin Approval
        'admin_approved',
        'admin_approved_at',
        'admin_notes',

        // Notification
        'notification_requested',
        'notification_requested_at',
        'notification_sent',
        'notification_sent_at',

        // Death Certificate
        'death_certificate_url',
        'ocr_extracted_data',
        'death_certificate_uploaded_at',

        // Metadata
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Date casts
        'date_of_birth' => 'date',
        'estate_valuation_date' => 'date',

        // DateTime casts
        'completed_at' => 'datetime',
        'activated_at' => 'datetime',
        'executed_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'admin_approved_at' => 'datetime',
        'notification_requested_at' => 'datetime',
        'notification_sent_at' => 'datetime',
        'death_certificate_uploaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

        // Boolean casts
        'is_shariah_compliant' => 'boolean',
        'admin_approved' => 'boolean',
        'notification_requested' => 'boolean',
        'notification_sent' => 'boolean',

        // Array/JSON casts
        'ocr_extracted_data' => 'array',
        'metadata' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        // Financial
        'total_assets',
        'total_debts',
        'net_estate',
        'distributable_estate',
        'total_wasiyyah_percentage',
        'total_wasiyyah_amount',
        'remaining_estate_for_heirs',
        'formatted_total_assets',
        'formatted_total_debts',
        'formatted_net_estate',
        'formatted_distributable_estate',
        'formatted_wasiyyah_amount',

        // Status
        'status_label',
        'status_color',
        'status_badge_html',
        'is_draft',
        'is_completed',
        'is_activated',
        'is_executed',
        'status_icon',

        // Progress
        'completion_percentage',
        'completion_status_label',
        'completed_steps',
        'total_steps',

        // Will
        'has_will_video',
        'has_text_will',
        'will_type',
        'will_type_label',

        // Trustee
        'has_trustee',
        'has_alternate_trustee',
        'trustee_full_info',

        // Dates
        'age',
        'formatted_date_of_birth',
        'days_since_creation',
        'days_since_activation',
        'days_since_execution',
        'days_since_review',

        // Counts
        'heirs_count',
        'assets_count',
        'debts_count',
        'wasiyyah_count',
        'notification_requests_count',

        // Summary
        'summary',
        'quick_summary',

        // Validation
        'is_ready_for_activation',
        'activation_blockers',
        'validation_issues',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'access_token',
        'ocr_extracted_data',
    ];

    // =========================================================================
    // BOOTED METHOD
    // =========================================================================

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            // Generate unique ID if not set
            if (!$model->unique_id) {
                $model->unique_id = (string) Str::uuid();
            }

            // Set default status
            if (!$model->status) {
                $model->status = 'draft';
            }

            // Set default Shariah compliance
            if (!isset($model->is_shariah_compliant)) {
                $model->is_shariah_compliant = true;
            }

            // Log creation
            Log::info('Estate pre-registration being created', [
                'deceased_name' => $model->deceased_name,
                'user_id' => $model->user_id,
            ]);
        });

        static::updating(function ($model) {
            // Log status changes
            if ($model->isDirty('status')) {
                Log::info('Estate status changed', [
                    'estate_id' => $model->id,
                    'old_status' => $model->getOriginal('status'),
                    'new_status' => $model->status,
                ]);
            }

            // Log activation
            if ($model->isDirty('status') && $model->status === 'activated') {
                Log::info('Estate activated', [
                    'estate_id' => $model->id,
                    'deceased_name' => $model->deceased_name,
                ]);
            }

            // Log execution
            if ($model->isDirty('status') && $model->status === 'executed') {
                Log::info('Estate executed', [
                    'estate_id' => $model->id,
                    'deceased_name' => $model->deceased_name,
                ]);
            }
        });

        static::deleting(function ($model) {
            // Log deletion
            Log::info('Estate pre-registration being deleted', [
                'estate_id' => $model->id,
                'deceased_name' => $model->deceased_name,
            ]);
        });
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the user that owns the estate.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who last reviewed this estate.
     *
     * @return BelongsTo
     */
    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the heirs for this estate.
     *
     * @return HasMany
     */
    public function heirs(): HasMany
    {
        return $this->hasMany(PreRegisteredHeir::class, 'estate_pre_registration_id');
    }

    /**
     * Get the wasiyyah beneficiaries for this estate.
     *
     * @return HasMany
     */
    public function wasiyyah(): HasMany
    {
        return $this->hasMany(PreRegisteredWasiyyah::class, 'estate_pre_registration_id');
    }

    /**
     * Get the assets for this estate.
     *
     * @return HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(PreRegisteredAsset::class, 'estate_pre_registration_id');
    }

    /**
     * Get the debts for this estate.
     *
     * @return HasMany
     */
    public function debts(): HasMany
    {
        return $this->hasMany(PreRegisteredDebt::class, 'estate_pre_registration_id');
    }

    /**
     * Get the notification requests for this estate.
     *
     * @return HasMany
     */
    public function notificationRequests(): HasMany
    {
        return $this->hasMany(NotificationRequest::class, 'estate_pre_registration_id');
    }

    /**
     * Get the digital credentials for this estate.
     *
     * @return HasMany
     */
    public function digitalCredentials(): HasMany
    {
        return $this->hasMany(DigitalCredential::class, 'estate_pre_registration_id');
    }

    /**
     * Get the inheritance notifications for this estate.
     *
     * @return HasMany
     */
    public function inheritanceNotifications(): HasMany
    {
        return $this->hasMany(InheritanceNotification::class, 'estate_id');
    }

    /**
     * Get the beneficiary access links for this estate.
     *
     * @return HasMany
     */
    public function beneficiaryAccessLinks(): HasMany
    {
        return $this->hasMany(BeneficiaryAccessLink::class, 'estate_pre_registration_id');
    }

    // =========================================================================
    // FINANCIAL ACCESSORS
    // =========================================================================

    /**
     * Get total assets value.
     *
     * @return float
     */
    public function getTotalAssetsAttribute(): float
    {
        return (float) $this->assets()->sum('value');
    }

    /**
     * Get total debts amount.
     *
     * @return float
     */
    public function getTotalDebtsAttribute(): float
    {
        return (float) $this->debts()->sum('amount');
    }

    /**
     * Get net estate value.
     *
     * @return float
     */
    public function getNetEstateAttribute(): float
    {
        return max(0, $this->total_assets - $this->total_debts);
    }

    /**
     * Get distributable estate after wasiyyah.
     *
     * @return float
     */
    public function getDistributableEstateAttribute(): float
    {
        $wasiyyahAmount = $this->total_wasiyyah_amount;
        $maxWasiyyah = $this->net_estate / 3;
        
        $actualWasiyyah = min($wasiyyahAmount, $maxWasiyyah);
        
        return max(0, $this->net_estate - $actualWasiyyah);
    }

    /**
     * Get total wasiyyah percentage.
     *
     * @return float
     */
    public function getTotalWasiyyahPercentageAttribute(): float
    {
        return (float) $this->wasiyyah()->sum('requested_percentage');
    }

    /**
     * Get total wasiyyah amount.
     *
     * @return float
     */
    public function getTotalWasiyyahAmountAttribute(): float
    {
        return ($this->total_wasiyyah_percentage / 100) * $this->net_estate;
    }

    /**
     * Get remaining estate for heirs after wasiyyah deductions.
     *
     * @return float
     */
    public function getRemainingEstateForHeirsAttribute(): float
    {
        $wasiyyahAmount = $this->total_wasiyyah_amount;
        $maxWasiyyah = $this->net_estate / 3;
        
        if ($wasiyyahAmount > $maxWasiyyah) {
            return $this->net_estate - $maxWasiyyah;
        }
        
        return $this->net_estate - $wasiyyahAmount;
    }

    /**
     * Get formatted total assets.
     *
     * @return string
     */
    public function getFormattedTotalAssetsAttribute(): string
    {
        return 'RM ' . number_format($this->total_assets, 2);
    }

    /**
     * Get formatted total debts.
     *
     * @return string
     */
    public function getFormattedTotalDebtsAttribute(): string
    {
        return 'RM ' . number_format($this->total_debts, 2);
    }

    /**
     * Get formatted net estate.
     *
     * @return string
     */
    public function getFormattedNetEstateAttribute(): string
    {
        return 'RM ' . number_format($this->net_estate, 2);
    }

    /**
     * Get formatted distributable estate.
     *
     * @return string
     */
    public function getFormattedDistributableEstateAttribute(): string
    {
        return 'RM ' . number_format($this->distributable_estate, 2);
    }

    /**
     * Get formatted wasiyyah amount.
     *
     * @return string
     */
    public function getFormattedWasiyyahAmountAttribute(): string
    {
        return 'RM ' . number_format($this->total_wasiyyah_amount, 2);
    }

    // =========================================================================
    // STATUS ACCESSORS
    // =========================================================================

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'draft' => 'Draft',
            'completed' => 'Completed',
            'activated' => 'Activated',
            'executed' => 'Executed',
        ];
        return $statuses[$this->status] ?? ucfirst($this->status ?? 'Unknown');
    }

    /**
     * Get the status color for Bootstrap badges.
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'draft' => 'secondary',
            'completed' => 'primary',
            'activated' => 'success',
            'executed' => 'dark',
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeHtmlAttribute(): string
    {
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $this->status_color,
            $this->status_label
        );
    }

    /**
     * Get status icon.
     *
     * @return string
     */
    public function getStatusIconAttribute(): string
    {
        $icons = [
            'draft' => '📝',
            'completed' => '✅',
            'activated' => '🔒',
            'executed' => '🏁',
        ];
        return $icons[$this->status] ?? '❓';
    }

    /**
     * Check if estate is draft.
     *
     * @return bool
     */
    public function getIsDraftAttribute(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if estate is completed.
     *
     * @return bool
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if estate is activated.
     *
     * @return bool
     */
    public function getIsActivatedAttribute(): bool
    {
        return $this->status === 'activated';
    }

    /**
     * Check if estate is executed.
     *
     * @return bool
     */
    public function getIsExecutedAttribute(): bool
    {
        return $this->status === 'executed';
    }

    /**
     * Check if estate is active (not draft and not executed).
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['completed', 'activated']);
    }

    /**
     * Check if estate is final (executed).
     *
     * @return bool
     */
    public function getIsFinalAttribute(): bool
    {
        return $this->status === 'executed';
    }

    // =========================================================================
    // PROGRESS ACCESSORS
    // =========================================================================

    /**
     * Get completion percentage based on filled data.
     *
     * @return int
     */
    public function getCompletionPercentageAttribute(): int
    {
        $steps = $this->total_steps;
        $completed = $this->completed_steps;
        
        if ($steps === 0) return 0;
        
        return (int) round(($completed / $steps) * 100);
    }

    /**
     * Get completion status label.
     *
     * @return string
     */
    public function getCompletionStatusLabelAttribute(): string
    {
        $percentage = $this->completion_percentage;
        
        if ($percentage >= 100) return 'Complete';
        if ($percentage >= 75) return 'Almost Complete';
        if ($percentage >= 50) return 'Half Complete';
        if ($percentage >= 25) return 'In Progress';
        return 'Just Started';
    }

    /**
     * Get completed steps count.
     *
     * @return int
     */
    public function getCompletedStepsAttribute(): int
    {
        $completed = 0;
        
        if (!empty($this->deceased_name)) $completed++;
        if (!empty($this->deceased_nric)) $completed++;
        if (!empty($this->gender)) $completed++;
        if (!empty($this->contact_email)) $completed++;
        if ($this->heirs()->count() > 0) $completed++;
        if ($this->assets()->count() > 0) $completed++;
        if ($this->debts()->count() > 0) $completed++;
        if (!empty($this->trustee_name)) $completed++;
        if ($this->wasiyyah()->count() > 0) $completed++;
        if ($this->has_will_video || $this->has_text_will) $completed++;
        
        return $completed;
    }

    /**
     * Get total steps count.
     *
     * @return int
     */
    public function getTotalStepsAttribute(): int
    {
        return 10;
    }

    // =========================================================================
    // WILL ACCESSORS
    // =========================================================================

    /**
     * Check if estate has a will video.
     *
     * @return bool
     */
    public function getHasWillVideoAttribute(): bool
    {
        return !is_null($this->will_video_url) && !empty($this->will_video_url);
    }

    /**
     * Check if estate has text will.
     *
     * @return bool
     */
    public function getHasTextWillAttribute(): bool
    {
        return !is_null($this->will_text_content) && !empty(trim($this->will_text_content));
    }

    /**
     * Get the will type.
     *
     * @return string
     */
    public function getWillTypeAttribute(): string
    {
        if ($this->has_will_video) {
            return 'video';
        }
        if ($this->has_text_will) {
            return 'text';
        }
        return 'none';
    }

    /**
     * Get will type label.
     *
     * @return string
     */
    public function getWillTypeLabelAttribute(): string
    {
        $labels = [
            'video' => 'Video Will',
            'text' => 'Written Will',
            'none' => 'No Will',
        ];
        return $labels[$this->will_type] ?? 'Unknown';
    }

    /**
     * Check if will video is from YouTube.
     *
     * @return bool
     */
    public function getIsYoutubeVideoAttribute(): bool
    {
        return $this->will_video_type === 'youtube' || 
               ($this->will_video_url && Str::contains($this->will_video_url, 'youtube'));
    }

    /**
     * Check if will video is uploaded file.
     *
     * @return bool
     */
    public function getIsUploadedVideoAttribute(): bool
    {
        return $this->will_video_type === 'upload' && 
               $this->will_video_url && 
               !Str::contains($this->will_video_url, 'youtube');
    }

    // =========================================================================
    // TRUSTEE ACCESSORS
    // =========================================================================

    /**
     * Check if trustee is appointed.
     *
     * @return bool
     */
    public function getHasTrusteeAttribute(): bool
    {
        return !empty($this->trustee_name) && !empty($this->trustee_email);
    }

    /**
     * Check if alternate trustee is appointed.
     *
     * @return bool
     */
    public function getHasAlternateTrusteeAttribute(): bool
    {
        return !empty($this->alternate_trustee_name) && !empty($this->alternate_trustee_email);
    }

    /**
     * Get trustee full information.
     *
     * @return array
     */
    public function getTrusteeFullInfoAttribute(): array
    {
        return [
            'primary' => [
                'name' => $this->trustee_name,
                'nric' => $this->trustee_nric,
                'phone' => $this->trustee_phone,
                'email' => $this->trustee_email,
                'relationship' => $this->trustee_relationship,
                'address' => $this->trustee_address,
            ],
            'alternate' => [
                'name' => $this->alternate_trustee_name,
                'nric' => $this->alternate_trustee_nric,
                'phone' => $this->alternate_trustee_phone,
                'email' => $this->alternate_trustee_email,
                'relationship' => $this->alternate_trustee_relationship,
            ],
        ];
    }

    // =========================================================================
    // DATE ACCESSORS
    // =========================================================================

    /**
     * Get age from date of birth.
     *
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) return null;
        return $this->date_of_birth->age;
    }

    /**
     * Get formatted date of birth.
     *
     * @return string|null
     */
    public function getFormattedDateOfBirthAttribute(): ?string
    {
        return $this->date_of_birth ? $this->date_of_birth->format('d F Y') : null;
    }

    /**
     * Get days since creation.
     *
     * @return int
     */
    public function getDaysSinceCreationAttribute(): int
    {
        return $this->created_at ? $this->created_at->diffInDays(now()) : 0;
    }

    /**
     * Get days since activation.
     *
     * @return int|null
     */
    public function getDaysSinceActivationAttribute(): ?int
    {
        return $this->activated_at ? $this->activated_at->diffInDays(now()) : null;
    }

    /**
     * Get days since execution.
     *
     * @return int|null
     */
    public function getDaysSinceExecutionAttribute(): ?int
    {
        return $this->executed_at ? $this->executed_at->diffInDays(now()) : null;
    }

    /**
     * Get days since last review.
     *
     * @return int|null
     */
    public function getDaysSinceReviewAttribute(): ?int
    {
        return $this->reviewed_at ? $this->reviewed_at->diffInDays(now()) : null;
    }

    /**
     * Get formatted creation date.
     *
     * @return string
     */
    public function getFormattedCreatedDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d F Y, h:i A') : 'N/A';
    }

    /**
     * Get formatted activation date.
     *
     * @return string|null
     */
    public function getFormattedActivatedDateAttribute(): ?string
    {
        return $this->activated_at ? $this->activated_at->format('d F Y, h:i A') : null;
    }

    /**
     * Get formatted execution date.
     *
     * @return string|null
     */
    public function getFormattedExecutedDateAttribute(): ?string
    {
        return $this->executed_at ? $this->executed_at->format('d F Y, h:i A') : null;
    }

    // =========================================================================
    // COUNT ACCESSORS
    // =========================================================================

    /**
     * Get heirs count.
     *
     * @return int
     */
    public function getHeirsCountAttribute(): int
    {
        return $this->heirs()->count();
    }

    /**
     * Get assets count.
     *
     * @return int
     */
    public function getAssetsCountAttribute(): int
    {
        return $this->assets()->count();
    }

    /**
     * Get debts count.
     *
     * @return int
     */
    public function getDebtsCountAttribute(): int
    {
        return $this->debts()->count();
    }

    /**
     * Get wasiyyah count.
     *
     * @return int
     */
    public function getWasiyyahCountAttribute(): int
    {
        return $this->wasiyyah()->count();
    }

    /**
     * Get notification requests count.
     *
     * @return int
     */
    public function getNotificationRequestsCountAttribute(): int
    {
        return $this->notificationRequests()->count();
    }

    // =========================================================================
    // SUMMARY ACCESSORS
    // =========================================================================

    /**
     * Get comprehensive summary.
     *
     * @return array
     */
    public function getSummaryAttribute(): array
    {
        return [
            'id' => $this->id,
            'unique_id' => $this->unique_id,
            'deceased_name' => $this->deceased_name,
            'deceased_nric' => $this->deceased_nric,
            'gender' => $this->gender,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'is_activated' => $this->is_activated,
            'is_executed' => $this->is_executed,
            'financial' => [
                'total_assets' => $this->total_assets,
                'total_debts' => $this->total_debts,
                'net_estate' => $this->net_estate,
                'distributable_estate' => $this->distributable_estate,
                'wasiyyah_percentage' => $this->total_wasiyyah_percentage,
                'wasiyyah_amount' => $this->total_wasiyyah_amount,
            ],
            'counts' => [
                'heirs' => $this->heirs_count,
                'assets' => $this->assets_count,
                'debts' => $this->debts_count,
                'wasiyyah' => $this->wasiyyah_count,
            ],
            'trustee' => [
                'has_trustee' => $this->has_trustee,
                'has_alternate' => $this->has_alternate_trustee,
            ],
            'will' => [
                'has_video' => $this->has_will_video,
                'has_text' => $this->has_text_will,
                'type' => $this->will_type,
            ],
            'shariah_compliant' => $this->is_shariah_compliant,
            'admin_approved' => $this->admin_approved,
            'completion_percentage' => $this->completion_percentage,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }

    /**
     * Get quick summary string.
     *
     * @return string
     */
    public function getQuickSummaryAttribute(): string
    {
        return sprintf(
            '%s | %s | Net: %s | Heirs: %d | %s',
            $this->deceased_name ?? 'Unknown',
            $this->status_label,
            $this->formatted_net_estate,
            $this->heirs_count,
            $this->completion_percentage . '% complete'
        );
    }

    // =========================================================================
    // VALIDATION ACCESSORS
    // =========================================================================

    /**
     * Check if estate is ready for activation.
     *
     * @return bool
     */
    public function getIsReadyForActivationAttribute(): bool
    {
        return empty($this->activation_blockers);
    }

    /**
     * Get activation blockers.
     *
     * @return array
     */
    public function getActivationBlockersAttribute(): array
    {
        $blockers = [];
        
        if (empty($this->deceased_name)) {
            $blockers[] = 'Deceased name is required';
        }
        if (empty($this->deceased_nric)) {
            $blockers[] = 'Deceased NRIC is required';
        }
        if ($this->heirs_count === 0) {
            $blockers[] = 'At least one heir is required';
        }
        if ($this->assets_count === 0) {
            $blockers[] = 'At least one asset is required';
        }
        if ($this->net_estate <= 0) {
            $blockers[] = 'Net estate must be greater than zero';
        }
        if (!$this->has_trustee) {
            $blockers[] = 'Trustee must be appointed';
        }
        
        // Check heir distribution
        $totalHeirPercentage = $this->heirs()->sum('share_percentage');
        if ($this->heirs_count > 0 && abs($totalHeirPercentage - 100) > 0.01) {
            $blockers[] = "Heir distribution ({$totalHeirPercentage}%) must equal 100%";
        }
        
        // Check wasiyyah limit
        if ($this->total_wasiyyah_percentage > 33.33) {
            $blockers[] = "Wasiyyah ({$this->total_wasiyyah_percentage}%) exceeds 1/3 limit";
        }
        
        return $blockers;
    }

    /**
     * Get all validation issues.
     *
     * @return array
     */
    public function getValidationIssuesAttribute(): array
    {
        $issues = [];
        
        // Profile issues
        if (empty($this->deceased_name)) $issues[] = 'Missing deceased name';
        if (empty($this->deceased_nric)) $issues[] = 'Missing NRIC';
        if (empty($this->gender)) $issues[] = 'Missing gender';
        if (empty($this->contact_email)) $issues[] = 'Missing contact email';
        
        // Heir issues
        if ($this->heirs_count === 0) $issues[] = 'No heirs registered';
        
        // Asset issues
        if ($this->assets_count === 0) $issues[] = 'No assets registered';
        
        // Financial issues
        if ($this->net_estate <= 0) $issues[] = 'Net estate is zero or negative';
        
        // Trustee issues
        if (!$this->has_trustee) $issues[] = 'Trustee not appointed';
        
        // Wasiyyah issues
        if ($this->total_wasiyyah_percentage > 33.33) {
            $issues[] = "Wasiyyah ({$this->total_wasiyyah_percentage}%) exceeds limit";
        }
        
        // Heir distribution issues
        $totalHeirPercentage = $this->heirs()->sum('share_percentage');
        if ($this->heirs_count > 0 && abs($totalHeirPercentage - 100) > 0.01) {
            $issues[] = "Heir distribution ({$totalHeirPercentage}%) ≠ 100%";
        }
        
        return $issues;
    }

    // =========================================================================
    // BUSINESS METHODS - STATUS MANAGEMENT
    // =========================================================================

    /**
     * Check if estate is ready for activation.
     *
     * @return bool
     */
    public function isReadyForActivation(): bool
    {
        return $this->heirs_count > 0 
            && $this->assets_count > 0
            && !empty($this->deceased_name) 
            && !empty($this->deceased_nric)
            && $this->net_estate > 0
            && $this->has_trustee;
    }

    /**
     * Activate the estate.
     *
     * @return bool
     */
    public function activate(): bool
    {
        if (!$this->isReadyForActivation()) {
            Log::warning('Attempted to activate estate that is not ready', [
                'estate_id' => $this->id,
                'blockers' => $this->activation_blockers,
            ]);
            return false;
        }

        return $this->update([
            'status' => 'activated',
            'activated_at' => now(),
            'access_token' => Str::random(64),
            'token_expires_at' => now()->addYears(100),
        ]);
    }

    /**
     * Deactivate the estate.
     *
     * @return bool
     */
    public function deactivate(): bool
    {
        return $this->update([
            'status' => 'draft',
            'activated_at' => null,
            'access_token' => null,
            'token_expires_at' => null,
        ]);
    }

    /**
     * Mark estate as executed.
     *
     * @return bool
     */
    public function markAsExecuted(): bool
    {
        return $this->update([
            'status' => 'executed',
            'executed_at' => now(),
        ]);
    }

    /**
     * Mark estate as completed.
     *
     * @return bool
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark estate as reviewed.
     *
     * @param  int|null $userId
     * @return bool
     */
    public function markAsReviewed(?int $userId = null): bool
    {
        return $this->update([
            'reviewed_at' => now(),
            'reviewed_by' => $userId ?? auth()->id(),
        ]);
    }

    // =========================================================================
    // BUSINESS METHODS - SECURITY
    // =========================================================================

    /**
     * Get the secure will URL.
     *
     * @return string|null
     */
    public function getSecureWillUrl(): ?string
    {
        if (!$this->access_token) {
            return null;
        }
        return route('estate.view-will', ['token' => $this->access_token]);
    }

    /**
     * Get secure view URL for beneficiaries.
     *
     * @return string|null
     */
    public function getSecureViewUrl(): ?string
    {
        if (!$this->access_token) {
            return null;
        }
        return route('estate.secure-view', ['token' => $this->access_token]);
    }

    /**
     * Check if the access token is valid.
     *
     * @return bool
     */
    public function hasValidToken(): bool
    {
        return $this->access_token 
            && $this->token_expires_at 
            && $this->token_expires_at->isFuture();
    }

    /**
     * Check if the access token is expired.
     *
     * @return bool
     */
    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }

    /**
     * Regenerate access token.
     *
     * @return string
     */
    public function regenerateToken(): string
    {
        $token = Str::random(64);
        
        $this->update([
            'access_token' => $token,
            'token_expires_at' => now()->addYears(100),
        ]);
        
        return $token;
    }

    /**
     * Revoke access token.
     *
     * @return bool
     */
    public function revokeToken(): bool
    {
        return $this->update([
            'access_token' => null,
            'token_expires_at' => null,
        ]);
    }

    // =========================================================================
    // BUSINESS METHODS - NOTIFICATION
    // =========================================================================

    /**
     * Check if notification can be requested.
     *
     * @return bool
     */
    public function canRequestNotification(): bool
    {
        return $this->status === 'activated' 
            && !$this->notification_requested 
            && !is_null($this->death_certificate_url);
    }

    /**
     * Request notification.
     *
     * @return bool
     */
    public function requestNotification(): bool
    {
        if (!$this->canRequestNotification()) {
            return false;
        }

        return $this->update([
            'notification_requested' => true,
            'notification_requested_at' => now(),
        ]);
    }

    /**
     * Mark notification as sent.
     *
     * @return bool
     */
    public function markNotificationSent(): bool
    {
        return $this->update([
            'notification_sent' => true,
            'notification_sent_at' => now(),
        ]);
    }

    // =========================================================================
    // BUSINESS METHODS - DEATH CERTIFICATE
    // =========================================================================

    /**
     * Upload death certificate.
     *
     * @param  string $filePath
     * @param  array  $ocrData
     * @return bool
     */
    public function uploadDeathCertificate(string $filePath, array $ocrData = []): bool
    {
        return $this->update([
            'death_certificate_url' => $filePath,
            'ocr_extracted_data' => $ocrData,
            'death_certificate_uploaded_at' => now(),
        ]);
    }

    /**
     * Check if death certificate is uploaded.
     *
     * @return bool
     */
    public function hasDeathCertificate(): bool
    {
        return !is_null($this->death_certificate_url);
    }

    // =========================================================================
    // BUSINESS METHODS - WILL
    // =========================================================================

    /**
     * Check if estate has a video will.
     *
     * @return bool
     */
    public function hasVideoWill(): bool
    {
        return !is_null($this->will_video_url) && !empty($this->will_video_url);
    }

    /**
     * Check if estate has a text will.
     *
     * @return bool
     */
    public function hasTextWill(): bool
    {
        return !is_null($this->will_text_content) && !empty(trim($this->will_text_content));
    }

    /**
     * Check if estate has any will.
     *
     * @return bool
     */
    public function hasWill(): bool
    {
        return $this->hasVideoWill() || $this->hasTextWill();
    }

    // =========================================================================
    // BUSINESS METHODS - HEIRS & DISTRIBUTION
    // =========================================================================

    /**
     * Get heirs by relationship.
     *
     * @return array
     */
    public function getHeirsByRelationship(): array
    {
        return $this->heirs
            ->groupBy('relationship')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'heirs' => $group->map(function ($heir) {
                        return [
                            'id' => $heir->id,
                            'name' => $heir->name,
                            'share_percentage' => $heir->display_percentage,
                            'email' => $heir->email,
                            'phone' => $heir->phone,
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * Get beneficiaries with email addresses.
     *
     * @return array
     */
    public function getBeneficiariesWithEmails(): array
    {
        return $this->heirs()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->select('id', 'name', 'email', 'relationship', 'phone', 'share_percentage')
            ->get()
            ->toArray();
    }

    /**
     * Get all beneficiaries (heirs + wasiyyah) with emails.
     *
     * @return array
     */
    public function getAllBeneficiariesWithEmails(): array
    {
        $heirs = $this->getBeneficiariesWithEmails();
        
        $wasiyyahBeneficiaries = $this->wasiyyah()
            ->whereNotNull('beneficiary_email')
            ->where('beneficiary_email', '!=', '')
            ->select('id', 'beneficiary_name as name', 'beneficiary_email as email', 'relationship', 'beneficiary_phone as phone', 'requested_percentage as share_percentage')
            ->get()
            ->map(function ($item) {
                $item->type = 'wasiyyah';
                return $item;
            })
            ->toArray();
        
        return array_merge(
            array_map(function ($heir) { $heir['type'] = 'heir'; return $heir; }, $heirs),
            $wasiyyahBeneficiaries
        );
    }

    /**
     * Calculate Faraid distribution.
     *
     * @return array
     */
    public function calculateDistribution(): array
    {
        $netEstate = $this->remaining_estate_for_heirs;
        $heirsWithCalculatedPercent = $this->heirs()->whereNotNull('calculated_percentage')->get();
        
        if ($heirsWithCalculatedPercent->count() === 0 || $netEstate <= 0) {
            return [];
        }
        
        return $heirsWithCalculatedPercent->map(function ($heir) use ($netEstate) {
            $percentage = $heir->calculated_percentage ?? $heir->share_percentage ?? 0;
            return [
                'heir_id' => $heir->id,
                'name' => $heir->name,
                'email' => $heir->email,
                'nric' => $heir->nric,
                'phone' => $heir->phone,
                'relationship' => $heir->relationship,
                'relationship_label' => $heir->relationship_label,
                'share_percentage' => round($percentage, 2),
                'amount' => round(($percentage / 100) * $netEstate, 2),
                'formatted_amount' => 'RM ' . number_format(round(($percentage / 100) * $netEstate, 2), 2),
            ];
        })->toArray();
    }

    /**
     * Get assets by category.
     *
     * @return array
     */
    public function getAssetsByCategory(): array
    {
        return $this->assets
            ->groupBy('category')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_value' => $group->sum('value'),
                    'formatted_total' => 'RM ' . number_format($group->sum('value'), 2),
                    'items' => $group->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * Get debts by type.
     *
     * @return array
     */
    public function getDebtsByType(): array
    {
        return $this->debts
            ->groupBy('type')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_amount' => $group->sum('amount'),
                    'formatted_total' => 'RM ' . number_format($group->sum('amount'), 2),
                    'items' => $group->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * Recalculate financial totals from related models.
     *
     * @return void
     */
    public function recalculateFinancials(): void
    {
        $totalAssets = $this->assets()->sum('value');
        $totalDebts = $this->debts()->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);

        $this->updateQuietly([
            'total_assets' => $totalAssets,
            'total_debts' => $totalDebts,
            'net_estate' => $netEstate,
        ]);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope for draft estates.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for completed estates.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for activated estates.
     */
    public function scopeActivated($query)
    {
        return $query->where('status', 'activated');
    }

    /**
     * Scope for executed estates.
     */
    public function scopeExecuted($query)
    {
        return $query->where('status', 'executed');
    }

    /**
     * Scope for active estates (not draft, not executed).
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['draft', 'executed']);
    }

    /**
     * Scope for estates ready for activation.
     */
    public function scopeReadyForActivation($query)
    {
        return $query->where('status', 'draft')
            ->whereNotNull('deceased_name')
            ->whereNotNull('deceased_nric')
            ->whereHas('heirs')
            ->whereHas('assets');
    }

    /**
     * Scope for estates with valid access token.
     */
    public function scopeWithValidToken($query)
    {
        return $query->whereNotNull('access_token')
            ->where('token_expires_at', '>', now());
    }

    /**
     * Scope for estates pending notification.
     */
    public function scopePendingNotification($query)
    {
        return $query->where('notification_requested', true)
            ->where('notification_sent', false);
    }

    /**
     * Scope for admin approved estates.
     */
    public function scopeApproved($query)
    {
        return $query->where('admin_approved', true);
    }

    /**
     * Scope for Shariah-compliant estates.
     */
    public function scopeShariahCompliant($query)
    {
        return $query->where('is_shariah_compliant', true);
    }

    /**
     * Scope for estates with will video.
     */
    public function scopeHasWillVideo($query)
    {
        return $query->whereNotNull('will_video_url')->where('will_video_url', '!=', '');
    }

    /**
     * Scope for estates with text will.
     */
    public function scopeHasTextWill($query)
    {
        return $query->whereNotNull('will_text_content')->where('will_text_content', '!=', '');
    }

    /**
     * Scope for estates with trustee.
     */
    public function scopeHasTrustee($query)
    {
        return $query->whereNotNull('trustee_name')
            ->where('trustee_name', '!=', '')
            ->whereNotNull('trustee_email')
            ->where('trustee_email', '!=', '');
    }

    /**
     * Scope for estates created today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for estates created this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for estates created this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    /**
     * Scope for estates created this year.
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    /**
     * Scope for estates by gender.
     */
    public function scopeByGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Scope for estates search.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('deceased_name', 'LIKE', "%{$search}%")
              ->orWhere('deceased_nric', 'LIKE', "%{$search}%")
              ->orWhere('unique_id', 'LIKE', "%{$search}%")
              ->orWhere('contact_email', 'LIKE', "%{$search}%")
              ->orWhere('contact_phone', 'LIKE', "%{$search}%")
              ->orWhere('trustee_name', 'LIKE', "%{$search}%")
              ->orWhere('trustee_email', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope for estates needing review (not reviewed in X days).
     */
    public function scopeNeedsReview($query, int $days = 90)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('reviewed_at')
              ->orWhere('reviewed_at', '<=', now()->subDays($days));
        });
    }

    /**
     * Scope for estates with incomplete heir distribution.
     */
    public function scopeIncompleteDistribution($query)
    {
        return $query->whereHas('heirs', function ($q) {
            // This is a simplified check
            $q->whereNull('share_percentage')
              ->whereNull('calculated_percentage');
        });
    }
}