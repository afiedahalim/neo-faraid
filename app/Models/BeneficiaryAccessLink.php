<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BeneficiaryAccessLink extends Model
{
    protected $table = 'beneficiary_access_links';

    protected $fillable = [
        'estate_pre_registration_id',
        'estate_id',
        'beneficiary_type',
        'beneficiary_id',
        'beneficiary_name',
        'beneficiary_email',
        'access_token',
        'expires_at',
        'status',
        'access_count',
        'last_accessed_at',
        'last_accessed_ip',
        'last_accessed_user_agent',
        'is_active',
        'revoked_at',
        'revoked_reason',
        'notification_sent_at',
        'notification_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'revoked_at' => 'datetime',
        'notification_sent_at' => 'datetime',
        'access_count' => 'integer',
        'notification_count' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'is_expired',
        'is_valid',
        'access_url',
        'status_label',
        'status_color',
        'formatted_expiry_date',
        'remaining_days',
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    public const TYPE_HEIR = 'heir';
    public const TYPE_WASIYYAH = 'wasiyyah';
    public const TYPE_TRUSTEE = 'trustee';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_REVOKED = 'revoked';
    public const STATUS_EXPIRED = 'expired';

    // =========================================================================
    // TOKEN GENERATION
    // =========================================================================

    /**
     * Generate a unique access token
     */
    public static function generateToken(): string
    {
        return Str::random(64) . '-' . time() . '-' . Str::random(16);
    }

    /**
     * Create an access link for a beneficiary
     */
    public static function createForBeneficiary($estate, string $type, $beneficiary, int $expiryDays = 30): self
    {
        $beneficiaryName = null;
        $beneficiaryEmail = null;
        $beneficiaryId = null;

        if (is_object($beneficiary)) {
            $beneficiaryId = $beneficiary->id ?? null;
            $beneficiaryName = $beneficiary->name ?? 
                              $beneficiary->beneficiary_name ?? 
                              $beneficiary->trustee_name ?? 
                              'Unknown';
            $beneficiaryEmail = $beneficiary->email ?? 
                               $beneficiary->beneficiary_email ?? 
                               $beneficiary->trustee_email ?? 
                               null;
        } elseif (is_array($beneficiary)) {
            $beneficiaryId = $beneficiary['id'] ?? null;
            $beneficiaryName = $beneficiary['name'] ?? 
                              $beneficiary['beneficiary_name'] ?? 
                              $beneficiary['trustee_name'] ?? 
                              'Unknown';
            $beneficiaryEmail = $beneficiary['email'] ?? 
                               $beneficiary['beneficiary_email'] ?? 
                               $beneficiary['trustee_email'] ?? 
                               null;
        }

        return self::create([
            'estate_pre_registration_id' => $estate->id,
            'estate_id' => $estate->id,
            'beneficiary_type' => $type,
            'beneficiary_id' => $beneficiaryId,
            'beneficiary_name' => $beneficiaryName,
            'beneficiary_email' => $beneficiaryEmail,
            'access_token' => self::generateToken(),
            'expires_at' => now()->addDays($expiryDays),
            'status' => self::STATUS_ACTIVE,
            'is_active' => true,
            'access_count' => 0,
            'notification_count' => 0,
        ]);
    }

    /**
     * Create an access link for testing purposes
     */
    public static function createTestLink(int $estateId, string $email, int $expiryDays = 30): self
    {
        return self::create([
            'estate_pre_registration_id' => $estateId,
            'estate_id' => $estateId,
            'beneficiary_type' => self::TYPE_HEIR,
            'beneficiary_name' => 'Test Beneficiary',
            'beneficiary_email' => $email,
            'access_token' => self::generateToken(),
            'expires_at' => now()->addDays($expiryDays),
            'status' => self::STATUS_ACTIVE,
            'is_active' => true,
            'access_count' => 0,
            'notification_count' => 0,
        ]);
    }

    /**
     * Create an access link by email (convenience method)
     */
    public static function createByEmail(int $estateId, string $email, string $name, string $type = 'heir', int $expiryDays = 30): self
    {
        return self::create([
            'estate_pre_registration_id' => $estateId,
            'estate_id' => $estateId,
            'beneficiary_type' => $type,
            'beneficiary_name' => $name,
            'beneficiary_email' => $email,
            'access_token' => self::generateToken(),
            'expires_at' => now()->addDays($expiryDays),
            'status' => self::STATUS_ACTIVE,
            'is_active' => true,
            'access_count' => 0,
            'notification_count' => 0,
        ]);
    }

    // =========================================================================
    // STATUS CHECK METHODS
    // =========================================================================

    /**
     * Check if the access link has expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return $this->expires_at->isPast();
    }

    /**
     * Check if the access link is valid
     */
    public function isValid(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->is_active && !$this->isExpired();
    }

    /**
     * Check if the access link is revoked
     */
    public function isRevoked(): bool
    {
        return $this->status === self::STATUS_REVOKED || !$this->is_active;
    }

    /**
     * Check if the access link is active (valid and not expired)
     */
    public function isActive(): bool
    {
        return $this->isValid();
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

    /**
     * Get the is_expired attribute
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->isExpired();
    }

    /**
     * Get the is_valid attribute
     */
    public function getIsValidAttribute(): bool
    {
        return $this->isValid();
    }

    /**
     * Get the access URL attribute
     */
    public function getAccessUrlAttribute(): string
    {
        return route('beneficiary.access', ['token' => $this->access_token]);
    }

    /**
     * Get the secure beneficiary view URL
     */
    public function getSecureViewUrlAttribute(): string
    {
        return route('estate.secure-view', ['token' => $this->access_token]);
    }

    /**
     * Get status label attribute
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->isRevoked()) {
            return 'Revoked';
        }
        if ($this->isExpired()) {
            return 'Expired';
        }
        if ($this->status === self::STATUS_ACTIVE && $this->is_active) {
            return 'Active';
        }
        return ucfirst($this->status ?? 'Unknown');
    }

    /**
     * Get status color attribute
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->isRevoked()) {
            return 'danger';
        }
        if ($this->isExpired()) {
            return 'warning';
        }
        return 'success';
    }

    /**
     * Get formatted expiry date attribute
     */
    public function getFormattedExpiryDateAttribute(): string
    {
        if (!$this->expires_at) {
            return 'Never expires';
        }
        return $this->expires_at->format('d F Y, h:i A');
    }

    /**
     * Get remaining days attribute
     */
    public function getRemainingDaysAttribute(): ?int
    {
        return $this->getRemainingDays();
    }

    /**
     * Get beneficiary type label
     */
    public function getBeneficiaryTypeLabelAttribute(): string
    {
        $labels = [
            self::TYPE_HEIR => 'Heir',
            self::TYPE_WASIYYAH => 'Wasiyyah Beneficiary',
            self::TYPE_TRUSTEE => 'Trustee',
        ];
        return $labels[$this->beneficiary_type] ?? ucfirst($this->beneficiary_type ?? 'Unknown');
    }

    // =========================================================================
    // ACTION METHODS
    // =========================================================================

    /**
     * Record an access attempt
     */
    public function recordAccess(?string $ip = null, ?string $userAgent = null): void
    {
        $this->increment('access_count');
        $this->last_accessed_at = now();
        $this->last_accessed_ip = $ip;
        $this->last_accessed_user_agent = $userAgent;
        $this->saveQuietly(); // Avoid triggering observers recursively
    }

    /**
     * Revoke the access link
     */
    public function revoke(?string $reason = null): void
    {
        $this->update([
            'status' => self::STATUS_REVOKED,
            'is_active' => false,
            'revoked_at' => now(),
            'revoked_reason' => $reason,
        ]);
    }

    /**
     * Extend the expiry date
     */
    public function extendExpiry(int $additionalDays = 30): void
    {
        $newExpiry = $this->expires_at 
            ? $this->expires_at->addDays($additionalDays)
            : now()->addDays($additionalDays);
        
        $this->update([
            'expires_at' => $newExpiry,
        ]);
    }

    /**
     * Reset expiry date
     */
    public function resetExpiry(int $days = 30): void
    {
        $this->update([
            'expires_at' => now()->addDays($days),
        ]);
    }

    /**
     * Mark notification as sent
     */
    public function markNotificationSent(): void
    {
        $this->update([
            'notification_sent_at' => now(),
            'notification_count' => ($this->notification_count ?? 0) + 1,
        ]);
    }

    /**
     * Reactivate a revoked or expired link
     */
    public function reactivate(?int $expiryDays = null): void
    {
        $updateData = [
            'status' => self::STATUS_ACTIVE,
            'is_active' => true,
            'revoked_at' => null,
            'revoked_reason' => null,
        ];
        
        if ($expiryDays !== null) {
            $updateData['expires_at'] = now()->addDays($expiryDays);
        } elseif ($this->isExpired()) {
            $updateData['expires_at'] = now()->addDays(30);
        }
        
        $this->update($updateData);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Get remaining days until expiry
     */
    public function getRemainingDays(): ?int
    {
        if (!$this->expires_at || $this->isExpired()) {
            return null;
        }
        return (int) now()->diffInDays($this->expires_at, false);
    }

    /**
     * Get formatted expiry date
     */
    public function getFormattedExpiryDate(): string
    {
        if (!$this->expires_at) {
            return 'Never expires';
        }
        return $this->expires_at->format('d F Y, h:i A');
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return $this->status_label;
    }

    /**
     * Get status color class
     */
    public function getStatusColor(): string
    {
        return $this->status_color;
    }

    /**
     * Get access URL (alias for attribute)
     */
    public function getAccessUrl(): string
    {
        return $this->access_url;
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Relationship: Estate Pre Registration
     */
    public function estate(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class, 'estate_pre_registration_id');
    }

    /**
     * Relationship: Estate Pre Registration (alias)
     */
    public function estatePreRegistration(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class, 'estate_pre_registration_id');
    }

    /**
     * Relationship: Access logs
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(BeneficiaryAccessLog::class, 'beneficiary_access_link_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope: Active links only
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope: Expired links
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope: Revoked links
     */
    public function scopeRevoked($query)
    {
        return $query->where('status', self::STATUS_REVOKED);
    }

    /**
     * Scope: By beneficiary email
     */
    public function scopeByEmail($query, string $email)
    {
        return $query->where('beneficiary_email', $email);
    }

    /**
     * Scope: By beneficiary type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('beneficiary_type', $type);
    }

    /**
     * Scope: Recently accessed
     */
    public function scopeRecentlyAccessed($query)
    {
        return $query->whereNotNull('last_accessed_at')
            ->orderBy('last_accessed_at', 'desc');
    }

    /**
     * Scope: Never accessed
     */
    public function scopeNeverAccessed($query)
    {
        return $query->whereNull('last_accessed_at');
    }

    /**
     * Scope: Expiring soon (within days)
     */
    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays($days));
    }

    /**
     * Scope: By estate
     */
    public function scopeByEstate($query, $estateId)
    {
        return $query->where('estate_pre_registration_id', $estateId);
    }

    /**
     * Scope: Notification not sent
     */
    public function scopeNotificationNotSent($query)
    {
        return $query->whereNull('notification_sent_at');
    }

    // =========================================================================
    // STATISTICS METHODS
    // =========================================================================

    /**
     * Get statistics for an estate
     */
    public static function getEstateStatistics(int $estateId): array
    {
        $query = self::where('estate_pre_registration_id', $estateId);
        
        return [
            'total' => $query->count(),
            'active' => $query->clone()->active()->count(),
            'expired' => $query->clone()->expired()->count(),
            'revoked' => $query->clone()->revoked()->count(),
            'accessed' => $query->clone()->whereNotNull('last_accessed_at')->count(),
            'never_accessed' => $query->clone()->neverAccessed()->count(),
            'total_accesses' => $query->clone()->sum('access_count'),
            'notifications_sent' => $query->clone()->whereNotNull('notification_sent_at')->count(),
        ];
    }

    /**
     * Get global statistics
     */
    public static function getGlobalStatistics(): array
    {
        return [
            'total' => self::count(),
            'active' => self::active()->count(),
            'expired' => self::expired()->count(),
            'revoked' => self::revoked()->count(),
            'total_accesses' => self::sum('access_count'),
            'average_accesses' => (float) self::avg('access_count') ?? 0,
            'beneficiary_types' => [
                'heir' => self::byType(self::TYPE_HEIR)->count(),
                'wasiyyah' => self::byType(self::TYPE_WASIYYAH)->count(),
                'trustee' => self::byType(self::TYPE_TRUSTEE)->count(),
            ],
        ];
    }

    // =========================================================================
    // BOOT METHOD
    // =========================================================================

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($link) {
            if (empty($link->access_token)) {
                $link->access_token = self::generateToken();
            }
            if (empty($link->status)) {
                $link->status = self::STATUS_ACTIVE;
            }
            if (is_null($link->is_active)) {
                $link->is_active = true;
            }
            if (empty($link->access_count)) {
                $link->access_count = 0;
            }
            if (empty($link->notification_count)) {
                $link->notification_count = 0;
            }
            
            // Ensure estate_id is set if estate_pre_registration_id is provided
            if (empty($link->estate_id) && !empty($link->estate_pre_registration_id)) {
                $link->estate_id = $link->estate_pre_registration_id;
            }
        });

        static::updating(function ($link) {
            if ($link->isDirty('status') && $link->status === self::STATUS_REVOKED) {
                if (empty($link->revoked_at)) {
                    $link->revoked_at = now();
                }
            }
        });

        static::deleting(function ($link) {
            // Delete associated access logs if cascade is not set
            if (method_exists($link, 'accessLogs')) {
                $link->accessLogs()->delete();
            }
        });
    }

    // =========================================================================
    // API RESPONSE METHODS
    // =========================================================================

    /**
     * Convert to API response array
     */
    public function toApiResponse(): array
    {
        return [
            'id' => $this->id,
            'beneficiary_type' => $this->beneficiary_type,
            'beneficiary_type_label' => $this->beneficiary_type_label,
            'beneficiary_name' => $this->beneficiary_name,
            'beneficiary_email' => $this->beneficiary_email,
            'access_token' => $this->access_token,
            'access_url' => $this->access_url,
            'secure_view_url' => $this->secure_view_url,
            'expires_at' => $this->expires_at?->toIso8601String(),
            'formatted_expiry_date' => $this->formatted_expiry_date,
            'remaining_days' => $this->remaining_days,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'is_expired' => $this->is_expired,
            'is_valid' => $this->is_valid,
            'access_count' => $this->access_count,
            'last_accessed_at' => $this->last_accessed_at?->toIso8601String(),
            'notification_sent_at' => $this->notification_sent_at?->toIso8601String(),
            'notification_count' => $this->notification_count,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * Convert to admin API response array
     */
    public function toAdminApiResponse(): array
    {
        return array_merge($this->toApiResponse(), [
            'estate_pre_registration_id' => $this->estate_pre_registration_id,
            'estate_id' => $this->estate_id,
            'beneficiary_id' => $this->beneficiary_id,
            'is_active' => $this->is_active,
            'revoked_at' => $this->revoked_at?->toIso8601String(),
            'revoked_reason' => $this->revoked_reason,
            'last_accessed_ip' => $this->last_accessed_ip,
            'last_accessed_user_agent' => $this->last_accessed_user_agent,
            'estate_name' => $this->estate?->deceased_name,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}