<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Estate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Owner/User Information
        'user_id',
        'unique_id',

        // Deceased Information
        'deceased_name',
        'deceased_nric',
        'date_of_birth',
        'gender',
        'date_of_death',
        'place_of_death',
        'cause_of_death',

        // Contact Information
        'contact_phone',
        'contact_email',
        'address',
        'city',
        'state',
        'postal_code',
        'country',

        // Estate Status
        'status',

        // Financial Summary
        'total_assets',
        'total_debts',
        'net_estate',
        'distributable_estate',
        'wasiyyah_total_percentage',
        'wasiyyah_total_amount',

        // Trustee Information
        'trustee_name',
        'trustee_nric',
        'trustee_phone',
        'trustee_email',
        'trustee_relationship',
        'trustee_address',

        // Will Information
        'will_video_url',
        'will_video_type',
        'will_video_thumbnail',
        'will_text_content',
        'wasiyyah_instructions',

        // Document References
        'death_certificate_url',
        'death_certificate_path',
        'death_certificate_ocr_data',
        'supporting_documents',

        // Security & Access
        'access_token',
        'token_expires_at',
        'is_public',

        // Admin/Approval
        'admin_approved',
        'admin_approved_at',
        'admin_approved_by',
        'admin_notes',
        'rejection_reason',

        // Notification
        'notification_requested',
        'notification_requested_at',
        'notification_sent',
        'notification_sent_at',
        'notification_recipients',

        // Timestamps
        'completed_at',
        'activated_at',
        'executed_at',
        'cancelled_at',

        // Metadata
        'metadata',
        'tags',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_death' => 'date',
        'total_assets' => 'decimal:2',
        'total_debts' => 'decimal:2',
        'net_estate' => 'decimal:2',
        'distributable_estate' => 'decimal:2',
        'wasiyyah_total_percentage' => 'decimal:2',
        'wasiyyah_total_amount' => 'decimal:2',
        'admin_approved' => 'boolean',
        'admin_approved_at' => 'datetime',
        'notification_requested' => 'boolean',
        'notification_requested_at' => 'datetime',
        'notification_sent' => 'boolean',
        'notification_sent_at' => 'datetime',
        'is_public' => 'boolean',
        'completed_at' => 'datetime',
        'activated_at' => 'datetime',
        'executed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'death_certificate_ocr_data' => 'array',
        'supporting_documents' => 'array',
        'notification_recipients' => 'array',
        'metadata' => 'array',
        'tags' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'access_token',
        'death_certificate_ocr_data',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'status_label',
        'status_color',
        'status_badge_html',
        'is_draft',
        'is_completed',
        'is_activated',
        'is_executed',
        'is_cancelled',
        'has_will_video',
        'has_text_will',
        'will_type',
        'formatted_total_assets',
        'formatted_total_debts',
        'formatted_net_estate',
        'formatted_distributable_estate',
        'completion_percentage',
        'age_at_death',
        'days_since_creation',
        'days_since_activation',
    ];

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

            // Set default status if not set
            if (!$model->status) {
                $model->status = 'draft';
            }

            // Set default country if not set
            if (!$model->country) {
                $model->country = 'Malaysia';
            }
        });

        static::updating(function ($model) {
            // Recalculate financial totals if assets or debts changed
            if ($model->isDirty('total_assets') || $model->isDirty('total_debts')) {
                $model->net_estate = max(0, $model->total_assets - $model->total_debts);
            }
        });

        static::deleting(function ($model) {
            // Clean up related records
            $model->heirs()->delete();
            $model->wasiyyah()->delete();
            $model->assets()->delete();
            $model->debts()->delete();
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
     * Get the admin who approved this estate.
     *
     * @return BelongsTo
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }

    /**
     * Get the heirs for this estate.
     *
     * @return HasMany
     */
    public function heirs(): HasMany
    {
        return $this->hasMany(PreRegisteredHeir::class, 'estate_id');
    }

    /**
     * Get the wasiyyah beneficiaries for this estate.
     *
     * @return HasMany
     */
    public function wasiyyah(): HasMany
    {
        return $this->hasMany(PreRegisteredWasiyyah::class, 'estate_id');
    }

    /**
     * Get the assets for this estate.
     *
     * @return HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(PreRegisteredAsset::class, 'estate_id');
    }

    /**
     * Get the debts for this estate.
     *
     * @return HasMany
     */
    public function debts(): HasMany
    {
        return $this->hasMany(PreRegisteredDebt::class, 'estate_id');
    }

    /**
     * Get the inheritance notifications for this estate.
     *
     * @return HasMany
     */
    public function inheritanceNotifications(): HasMany
    {
        return $this->hasMany(InheritanceNotification::class);
    }

    /**
     * Get the notification requests for this estate.
     *
     * @return HasMany
     */
    public function notificationRequests(): HasMany
    {
        return $this->hasMany(NotificationRequest::class, 'estate_id');
    }

    // =========================================================================
    // ACCESSORS - STATUS
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
            'pending_review' => 'Pending Review',
            'completed' => 'Completed',
            'activated' => 'Activated',
            'executed' => 'Executed',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
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
            'pending_review' => 'warning',
            'completed' => 'primary',
            'activated' => 'success',
            'executed' => 'dark',
            'cancelled' => 'danger',
            'rejected' => 'danger',
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
     * Check if estate is cancelled.
     *
     * @return bool
     */
    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    // =========================================================================
    // ACCESSORS - WILL
    // =========================================================================

    /**
     * Check if estate has a will video.
     *
     * @return bool
     */
    public function getHasWillVideoAttribute(): bool
    {
        return !is_null($this->will_video_url);
    }

    /**
     * Check if estate has a text will.
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
            return $this->will_video_type ?? 'video';
        }

        if ($this->has_text_will) {
            return 'text';
        }

        return 'none';
    }

    // =========================================================================
    // ACCESSORS - FINANCIAL
    // =========================================================================

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

    // =========================================================================
    // ACCESSORS - COMPUTED
    // =========================================================================

    /**
     * Get completion percentage based on filled data.
     *
     * @return int
     */
    public function getCompletionPercentageAttribute(): int
    {
        $fields = [
            'deceased_name',
            'deceased_nric',
            'date_of_birth',
            'gender',
            'contact_phone',
            'contact_email',
            'address',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        // Add points for relationships
        if ($this->heirs()->count() > 0) $completed++;
        if ($this->assets()->count() > 0) $completed++;
        if ($this->debts()->count() > 0) $completed++;

        $totalPossible = count($fields) + 3;

        return (int) round(($completed / $totalPossible) * 100);
    }

    /**
     * Get age at death.
     *
     * @return int|null
     */
    public function getAgeAtDeathAttribute(): ?int
    {
        if (!$this->date_of_birth || !$this->date_of_death) {
            return null;
        }

        return $this->date_of_birth->diffInYears($this->date_of_death);
    }

    /**
     * Get days since creation.
     *
     * @return int
     */
    public function getDaysSinceCreationAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get days since activation.
     *
     * @return int|null
     */
    public function getDaysSinceActivationAttribute(): ?int
    {
        if (!$this->activated_at) {
            return null;
        }

        return $this->activated_at->diffInDays(now());
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope a query to only include draft estates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include pending review estates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    /**
     * Scope a query to only include completed estates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include activated estates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivated($query)
    {
        return $query->where('status', 'activated');
    }

    /**
     * Scope a query to only include executed estates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExecuted($query)
    {
        return $query->where('status', 'executed');
    }

    /**
     * Scope a query to only include cancelled estates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include active estates (not cancelled or executed).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'executed']);
    }

    /**
     * Scope a query to only include admin approved estates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('admin_approved', true);
    }

    /**
     * Scope a query to only include estates pending approval.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingApproval($query)
    {
        return $query->where('admin_approved', false)
            ->where('status', '!=', 'draft');
    }

    /**
     * Scope a query to only include estates with valid access token.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithValidToken($query)
    {
        return $query->whereNotNull('access_token')
            ->where('token_expires_at', '>', now());
    }

    /**
     * Scope a query to only include estates pending notification.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingNotification($query)
    {
        return $query->where('notification_requested', true)
            ->where('notification_sent', false);
    }

    /**
     * Scope a query to only include estates created today.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope a query to only include estates created this week.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to only include estates created this month.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    /**
     * Scope a query to only include estates created this year.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    /**
     * Scope a query to only include estates for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to search estates by name or NRIC.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('deceased_name', 'LIKE', "%{$search}%")
                ->orWhere('deceased_nric', 'LIKE', "%{$search}%")
                ->orWhere('unique_id', 'LIKE', "%{$search}%")
                ->orWhere('contact_email', 'LIKE', "%{$search}%")
                ->orWhere('contact_phone', 'LIKE', "%{$search}%");
        });
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Check if estate is ready for activation.
     *
     * @return bool
     */
    public function isReadyForActivation(): bool
    {
        return $this->heirs()->count() > 0
            && $this->assets()->count() > 0
            && !empty($this->deceased_name)
            && !empty($this->deceased_nric)
            && $this->net_estate > 0;
    }

    /**
     * Check if estate can request notification.
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
     * Get heirs grouped by relationship.
     *
     * @return array
     */
    public function getHeirsByRelationship(): array
    {
        return $this->heirs()
            ->get()
            ->groupBy('relationship')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'heirs' => $group->toArray(),
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
            ->select('id', 'name', 'email', 'relationship', 'share_percentage')
            ->get()
            ->toArray();
    }

    /**
     * Get assets by category.
     *
     * @return array
     */
    public function getAssetsByCategory(): array
    {
        return $this->assets()
            ->get()
            ->groupBy('category')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_value' => $group->sum('value'),
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
        return $this->debts()
            ->get()
            ->groupBy('type')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_amount' => $group->sum('amount'),
                    'items' => $group->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * Get comprehensive estate summary.
     *
     * @return array
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'unique_id' => $this->unique_id,
            'deceased_name' => $this->deceased_name,
            'deceased_nric' => $this->deceased_nric,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'is_activated' => $this->is_activated,
            'is_executed' => $this->is_executed,
            'total_assets' => $this->total_assets,
            'total_debts' => $this->total_debts,
            'net_estate' => $this->net_estate,
            'distributable_estate' => $this->distributable_estate,
            'heirs_count' => $this->heirs()->count(),
            'assets_count' => $this->assets()->count(),
            'debts_count' => $this->debts()->count(),
            'wasiyyah_count' => $this->wasiyyah()->count(),
            'has_will_video' => $this->has_will_video,
            'has_text_will' => $this->has_text_will,
            'admin_approved' => $this->admin_approved,
            'completion_percentage' => $this->completion_percentage,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
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
        $wasiyyahTotal = $this->wasiyyah()->sum('requested_percentage');
        $wasiyyahAmount = ($wasiyyahTotal / 100) * $netEstate;
        $distributableEstate = max(0, $netEstate - $wasiyyahAmount);

        $this->updateQuietly([
            'total_assets' => $totalAssets,
            'total_debts' => $totalDebts,
            'net_estate' => $netEstate,
            'wasiyyah_total_percentage' => $wasiyyahTotal,
            'wasiyyah_total_amount' => $wasiyyahAmount,
            'distributable_estate' => $distributableEstate,
        ]);
    }

    /**
     * Activate the estate.
     *
     * @return bool
     */
    public function activate(): bool
    {
        if (!$this->isReadyForActivation()) {
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
     * Cancel the estate.
     *
     * @param string|null $reason
     * @return bool
     */
    public function cancel(?string $reason = null): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Submit for review.
     *
     * @return bool
     */
    public function submitForReview(): bool
    {
        return $this->update([
            'status' => 'pending_review',
        ]);
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

    /**
     * Upload death certificate.
     *
     * @param string $filePath
     * @param array $ocrData
     * @return bool
     */
    public function uploadDeathCertificate(string $filePath, array $ocrData = []): bool
    {
        return $this->update([
            'death_certificate_url' => $filePath,
            'death_certificate_path' => $filePath,
            'death_certificate_ocr_data' => $ocrData,
        ]);
    }

    /**
     * Add a tag to the estate.
     *
     * @param string $tag
     * @return void
     */
    public function addTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    /**
     * Remove a tag from the estate.
     *
     * @param string $tag
     * @return void
     */
    public function removeTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        $tags = array_values(array_filter($tags, fn($t) => $t !== $tag));
        $this->update(['tags' => $tags]);
    }

    /**
     * Check if estate has a specific tag.
     *
     * @param string $tag
     * @return bool
     */
    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }

    /**
     * Convert estate to array with limited data for public view.
     *
     * @return array
     */
    public function toPublicArray(): array
    {
        return [
            'unique_id' => $this->unique_id,
            'deceased_name' => $this->deceased_name,
            'date_of_death' => $this->date_of_death?->format('d F Y'),
            'status' => $this->status_label,
            'has_will_video' => $this->has_will_video,
            'will_type' => $this->will_type,
            'net_estate' => $this->formatted_net_estate,
            'heirs_count' => $this->heirs()->count(),
            'created_at' => $this->created_at?->format('d F Y'),
        ];
    }
}