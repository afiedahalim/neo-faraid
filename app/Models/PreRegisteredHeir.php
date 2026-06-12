<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PreRegisteredHeir extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_registered_heirs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Foreign Key
        'estate_pre_registration_id',

        // Personal Information
        'name',
        'nric',
        'email',
        'gender',
        'date_of_birth',
        'phone',
        'address',

        // Relationship Information
        'relationship',
        'relationship_type',

        // Inheritance Details
        'share_percentage',
        'calculated_percentage',

        // Priority and Metadata
        'priority',
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

        // Numeric casts
        'share_percentage' => 'decimal:2',
        'calculated_percentage' => 'decimal:2',
        'priority' => 'integer',

        // DateTime casts
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

        // JSON casts
        'metadata' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_name',
        'relationship_label',
        'relationship_type_label',
        'gender_label',
        'contact_info',
        'display_percentage',
        'formatted_share_percentage',
        'calculated_amount',
        'formatted_calculated_amount',
        'age',
        'has_complete_contact_info',
        'has_complete_identification',
        'heir_category',
        'heir_category_color',
        'is_primary_heir',
        'is_asabah',
        'is_blocked',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the estate pre-registration that owns this heir.
     *
     * @return BelongsTo
     */
    public function estatePreRegistration(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class);
    }

    // =========================================================================
    // NAME ACCESSORS
    // =========================================================================

    /**
     * Get the full name (alias for name).
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->name ?? 'Unknown Heir';
    }

    /**
     * Get the first name.
     *
     * @return string
     */
    public function getFirstNameAttribute(): string
    {
        $parts = explode(' ', trim($this->name ?? ''));
        return $parts[0] ?? '';
    }

    /**
     * Get the last name (or remaining names).
     *
     * @return string
     */
    public function getLastNameAttribute(): string
    {
        $parts = explode(' ', trim($this->name ?? ''));
        if (count($parts) <= 1) {
            return '';
        }
        array_shift($parts);
        return implode(' ', $parts);
    }

    /**
     * Get the initials from the name.
     *
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name ?? ''));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return $initials ?: '?';
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
            'husband' => 'Husband',
            'wife' => 'Wife',
            'son' => 'Son',
            'daughter' => 'Daughter',
            'father' => 'Father',
            'mother' => 'Mother',
            'brother' => 'Full Brother',
            'sister' => 'Full Sister',
            'grandfather' => 'Grandfather',
            'grandmother' => 'Grandmother',
            'half_brother_paternal' => 'Half-Brother (Paternal)',
            'half_brother_maternal' => 'Half-Brother (Maternal)',
            'half_sister_paternal' => 'Half-Sister (Paternal)',
            'half_sister_maternal' => 'Half-Sister (Maternal)',
            'half_brother_full' => 'Half-Brother (Paternal & Maternal)',
            'half_sister_full' => 'Half-Sister (Paternal & Maternal)',
            'grandmother_paternal' => "Father's Mother",
            'grandmother_maternal' => "Mother's Mother",
            'paternal_uncle' => 'Paternal Uncle',
            'male_cousin' => 'Male Cousin (Paternal)',
        ];
        
        return $labels[$this->relationship] ?? ucfirst(str_replace('_', ' ', $this->relationship ?? 'other'));
    }

    /**
     * Get the relationship type label for display.
     *
     * @return string
     */
    public function getRelationshipTypeLabelAttribute(): string
    {
        $types = [
            'primary' => 'Primary Heir',
            'substitute' => 'Substitute Heir',
            'secondary' => 'Secondary Heir',
            'asabah' => 'Asabah (Residuary)',
            'other' => 'Other',
        ];
        
        return $types[$this->relationship_type] ?? 'Other';
    }

    /**
     * Get the relationship type color for badges.
     *
     * @return string
     */
    public function getRelationshipTypeColorAttribute(): string
    {
        $colors = [
            'primary' => 'primary',
            'substitute' => 'warning',
            'secondary' => 'info',
            'asabah' => 'success',
            'other' => 'secondary',
        ];
        
        return $colors[$this->relationship_type] ?? 'secondary';
    }

    /**
     * Get the relationship icon.
     *
     * @return string
     */
    public function getRelationshipIconAttribute(): string
    {
        $icons = [
            'husband' => '👨',
            'wife' => '👩',
            'son' => '👦',
            'daughter' => '👧',
            'father' => '👴',
            'mother' => '👵',
            'brother' => '👨',
            'sister' => '👩',
            'grandfather' => '👴',
            'grandmother' => '👵',
        ];
        
        return $icons[$this->relationship] ?? '👤';
    }

    /**
     * Get short relationship code.
     *
     * @return string
     */
    public function getRelationshipCodeAttribute(): string
    {
        $codes = [
            'husband' => 'H',
            'wife' => 'W',
            'son' => 'S',
            'daughter' => 'D',
            'father' => 'F',
            'mother' => 'M',
            'brother' => 'B',
            'sister' => 'SIS',
            'grandfather' => 'GF',
            'grandmother' => 'GM',
            'half_brother_paternal' => 'HBP',
            'half_brother_maternal' => 'HBM',
            'half_sister_paternal' => 'HSP',
            'half_sister_maternal' => 'HSM',
        ];
        
        return $codes[$this->relationship] ?? strtoupper(substr($this->relationship, 0, 3));
    }

    // =========================================================================
    // GENDER ACCESSORS
    // =========================================================================

    /**
     * Get the gender label.
     *
     * @return string
     */
    public function getGenderLabelAttribute(): string
    {
        if (!$this->gender) {
            // Infer gender from relationship if possible
            $maleRelationships = ['husband', 'son', 'father', 'brother', 'grandfather', 'half_brother_paternal', 'half_brother_maternal', 'paternal_uncle', 'male_cousin'];
            $femaleRelationships = ['wife', 'daughter', 'mother', 'sister', 'grandmother', 'half_sister_paternal', 'half_sister_maternal'];
            
            if (in_array($this->relationship, $maleRelationships)) {
                return 'Male';
            }
            if (in_array($this->relationship, $femaleRelationships)) {
                return 'Female';
            }
            return 'Not specified';
        }
        
        return $this->gender === 'male' ? 'Male' : 'Female';
    }

    /**
     * Get the gender icon.
     *
     * @return string
     */
    public function getGenderIconAttribute(): string
    {
        $genderLabel = $this->gender_label;
        
        if ($genderLabel === 'Male') return '♂️';
        if ($genderLabel === 'Female') return '♀️';
        return '⚧';
    }

    /**
     * Check if heir is male.
     *
     * @return bool
     */
    public function getIsMaleAttribute(): bool
    {
        if ($this->gender) {
            return $this->gender === 'male';
        }
        
        $maleRelationships = ['husband', 'son', 'father', 'brother', 'grandfather', 'half_brother_paternal', 'half_brother_maternal', 'paternal_uncle', 'male_cousin'];
        return in_array($this->relationship, $maleRelationships);
    }

    /**
     * Check if heir is female.
     *
     * @return bool
     */
    public function getIsFemaleAttribute(): bool
    {
        return !$this->is_male;
    }

    // =========================================================================
    // CONTACT ACCESSORS
    // =========================================================================

    /**
     * Get combined contact information.
     *
     * @return string
     */
    public function getContactInfoAttribute(): string
    {
        $contacts = [];
        
        if ($this->email) {
            $contacts[] = '📧 ' . $this->email;
        }
        if ($this->phone) {
            $contacts[] = '📱 ' . $this->phone;
        }
        
        return implode(' | ', $contacts) ?: 'No contact information available';
    }

    /**
     * Get contact details as array.
     *
     * @return array
     */
    public function getContactDetailsAttribute(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }

    /**
     * Check if this heir has complete contact information.
     *
     * @return bool
     */
    public function getHasCompleteContactInfoAttribute(): bool
    {
        return !empty($this->email) && !empty($this->phone);
    }

    /**
     * Check if this heir has complete identification.
     *
     * @return bool
     */
    public function getHasCompleteIdentificationAttribute(): bool
    {
        return !empty($this->name) && !empty($this->nric);
    }

    /**
     * Check if this heir has all required information.
     *
     * @return bool
     */
    public function getIsCompleteAttribute(): bool
    {
        return $this->has_complete_contact_info && 
               $this->has_complete_identification &&
               !empty($this->relationship);
    }

    /**
     * Get missing information list.
     *
     * @return array
     */
    public function getMissingInformationAttribute(): array
    {
        $missing = [];
        
        if (empty($this->name)) $missing[] = 'Full Name';
        if (empty($this->nric)) $missing[] = 'NRIC/Passport';
        if (empty($this->email)) $missing[] = 'Email';
        if (empty($this->phone)) $missing[] = 'Phone';
        if (empty($this->relationship)) $missing[] = 'Relationship';
        
        return $missing;
    }

    // =========================================================================
    // SHARE PERCENTAGE ACCESSORS
    // =========================================================================

    /**
     * Get the display percentage (uses calculated_percentage if available).
     *
     * @return float
     */
    public function getDisplayPercentageAttribute(): float
    {
        return (float) ($this->calculated_percentage ?? $this->share_percentage ?? 0);
    }

    /**
     * Get the formatted share percentage.
     *
     * @return string
     */
    public function getFormattedSharePercentageAttribute(): string
    {
        return number_format($this->display_percentage, 2) . '%';
    }

    /**
     * Check if share is calculated or manually set.
     *
     * @return string
     */
    public function getShareSourceAttribute(): string
    {
        if ($this->calculated_percentage !== null) {
            return 'Faraid Calculated';
        }
        if ($this->share_percentage !== null) {
            return 'Manually Set';
        }
        return 'Not Set';
    }

    /**
     * Get share source color.
     *
     * @return string
     */
    public function getShareSourceColorAttribute(): string
    {
        return $this->share_source === 'Faraid Calculated' ? 'success' : 
               ($this->share_source === 'Manually Set' ? 'warning' : 'danger');
    }

    // =========================================================================
    // AMOUNT ACCESSORS
    // =========================================================================

    /**
     * Get the calculated amount based on net estate.
     *
     * @return float
     */
    public function getCalculatedAmountAttribute(): float
    {
        $percentage = $this->display_percentage;
        $netEstate = $this->estatePreRegistration->net_estate ?? 0;
        
        return round(($percentage / 100) * $netEstate, 2);
    }

    /**
     * Get the formatted calculated amount.
     *
     * @return string
     */
    public function getFormattedCalculatedAmountAttribute(): string
    {
        return 'RM ' . number_format($this->calculated_amount, 2);
    }

    /**
     * Get the wasiyyah-adjusted amount.
     *
     * @return float
     */
    public function getAdjustedAmountAttribute(): float
    {
        $percentage = $this->display_percentage;
        $remainingEstate = $this->estatePreRegistration->remaining_estate_for_heirs ?? 0;
        
        return round(($percentage / 100) * $remainingEstate, 2);
    }

    /**
     * Get the formatted adjusted amount.
     *
     * @return string
     */
    public function getFormattedAdjustedAmountAttribute(): string
    {
        return 'RM ' . number_format($this->adjusted_amount, 2);
    }

    // =========================================================================
    // DATE ACCESSORS
    // =========================================================================

    /**
     * Get the age from date of birth.
     *
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->age;
    }

    /**
     * Get the formatted date of birth.
     *
     * @return string|null
     */
    public function getFormattedDateOfBirthAttribute(): ?string
    {
        return $this->date_of_birth ? $this->date_of_birth->format('d F Y') : null;
    }

    /**
     * Check if heir is a minor (under 18).
     *
     * @return bool
     */
    public function getIsMinorAttribute(): bool
    {
        return $this->age !== null && $this->age < 18;
    }

    /**
     * Check if heir is an adult (18 and above).
     *
     * @return bool
     */
    public function getIsAdultAttribute(): bool
    {
        return $this->age !== null && $this->age >= 18;
    }

    /**
     * Get the age group.
     *
     * @return string
     */
    public function getAgeGroupAttribute(): string
    {
        if ($this->age === null) return 'Unknown';
        if ($this->age < 18) return 'Minor';
        if ($this->age < 30) return 'Young Adult';
        if ($this->age < 50) return 'Adult';
        if ($this->age < 65) return 'Middle Aged';
        return 'Senior';
    }

    // =========================================================================
    // HEIR CATEGORY ACCESSORS
    // =========================================================================

    /**
     * Get the heir category based on relationship.
     *
     * @return string
     */
    public function getHeirCategoryAttribute(): string
    {
        $primary = ['husband', 'wife', 'father', 'mother'];
        $asabah = ['son', 'daughter', 'brother', 'paternal_uncle', 'male_cousin'];
        $substitute = ['grandfather', 'grandmother', 'grandmother_paternal', 'grandmother_maternal'];
        $secondary = [
            'half_brother_full', 'half_brother_paternal', 'half_brother_maternal',
            'half_sister_full', 'half_sister_paternal', 'half_sister_maternal',
        ];
        
        if (in_array($this->relationship, $primary)) return 'Primary Heir';
        if (in_array($this->relationship, $asabah)) return 'Asabah (Residuary)';
        if (in_array($this->relationship, $substitute)) return 'Substitute Heir';
        if (in_array($this->relationship, $secondary)) return 'Secondary Heir';
        
        return 'Other';
    }

    /**
     * Get the heir category color for badges.
     *
     * @return string
     */
    public function getHeirCategoryColorAttribute(): string
    {
        $colors = [
            'Primary Heir' => 'primary',
            'Asabah (Residuary)' => 'success',
            'Substitute Heir' => 'warning',
            'Secondary Heir' => 'info',
            'Other' => 'secondary',
        ];
        
        return $colors[$this->heir_category] ?? 'secondary';
    }

    /**
     * Check if this is a primary heir.
     *
     * @return bool
     */
    public function getIsPrimaryHeirAttribute(): bool
    {
        $primary = ['husband', 'wife', 'father', 'mother'];
        return in_array($this->relationship, $primary);
    }

    /**
     * Check if this is an asabah heir.
     *
     * @return bool
     */
    public function getIsAsabahAttribute(): bool
    {
        $asabah = ['son', 'daughter', 'brother', 'paternal_uncle', 'male_cousin'];
        return in_array($this->relationship, $asabah);
    }

    /**
     * Check if this heir can be blocked by other heirs.
     *
     * @return bool
     */
    public function getIsBlockableAttribute(): bool
    {
        $unblockable = ['husband', 'wife', 'father', 'mother', 'son', 'daughter'];
        return !in_array($this->relationship, $unblockable);
    }

    /**
     * Check if this heir is currently blocked.
     * Blocked heirs are those who would be excluded by higher-priority heirs.
     *
     * @return bool
     */
    public function getIsBlockedAttribute(): bool
    {
        // This is a simplified check - actual Faraid rules are more complex
        if ($this->is_primary_heir || $this->is_asabah) {
            return false;
        }
        
        // Count related primary/asabah heirs that might block this one
        $estate = $this->estatePreRegistration;
        if (!$estate) return false;
        
        $allHeirs = $estate->heirs;
        
        // Brothers/sisters are blocked by father or son
        if (in_array($this->relationship, ['brother', 'sister', 'half_brother_paternal', 'half_sister_paternal'])) {
            $hasFather = $allHeirs->where('relationship', 'father')->count() > 0;
            $hasSon = $allHeirs->where('relationship', 'son')->count() > 0;
            return $hasFather || $hasSon;
        }
        
        // Grandparents are blocked by parents
        if (in_array($this->relationship, ['grandfather', 'grandmother', 'grandmother_paternal', 'grandmother_maternal'])) {
            $hasFather = $allHeirs->where('relationship', 'father')->count() > 0;
            $hasMother = $allHeirs->where('relationship', 'mother')->count() > 0;
            return $hasFather || $hasMother;
        }
        
        return false;
    }

    /**
     * Get the blocked status label.
     *
     * @return string
     */
    public function getBlockedStatusLabelAttribute(): string
    {
        if (!$this->is_blockable) {
            return 'Cannot be blocked';
        }
        return $this->is_blocked ? 'Blocked (Mahjub)' : 'Eligible';
    }

    /**
     * Get the blocked status color.
     *
     * @return string
     */
    public function getBlockedStatusColorAttribute(): string
    {
        if (!$this->is_blockable) {
            return 'success';
        }
        return $this->is_blocked ? 'danger' : 'success';
    }

    // =========================================================================
    // FARAID-SPECIFIC ACCESSORS
    // =========================================================================

    /**
     * Get the Faraid share type.
     *
     * @return string|null
     */
    public function getFaraidShareTypeAttribute(): ?string
    {
        $fixedShareRelationships = ['husband', 'wife', 'father', 'mother', 'daughter', 'sister', 'half_sister_maternal', 'half_sister_paternal'];
        $asabahRelationships = ['son', 'brother', 'paternal_uncle', 'male_cousin'];
        
        if (in_array($this->relationship, $fixedShareRelationships)) {
            return 'Fixed Share (Fard)';
        }
        if (in_array($this->relationship, $asabahRelationships)) {
            return 'Residuary (Asabah)';
        }
        
        return 'Conditional';
    }

    /**
     * Get the Faraid share type color.
     *
     * @return string
     */
    public function getFaraidShareTypeColorAttribute(): string
    {
        switch ($this->faraid_share_type) {
            case 'Fixed Share (Fard)':
                return 'primary';
            case 'Residuary (Asabah)':
                return 'success';
            case 'Conditional':
                return 'warning';
            default:
                return 'secondary';
        }
    }

    /**
     * Get the heir's share ratio relative to siblings.
     *
     * @return float
     */
    public function getShareRatioAttribute(): float
    {
        // Males generally get twice the share of females in similar category
        if ($this->is_male) {
            $maleDoubleRelationships = ['son', 'brother', 'half_brother_paternal', 'half_brother_full'];
            if (in_array($this->relationship, $maleDoubleRelationships)) {
                return 2.0;
            }
        }
        
        return 1.0;
    }

    /**
     * Get the share ratio label.
     *
     * @return string
     */
    public function getShareRatioLabelAttribute(): string
    {
        if ($this->share_ratio == 2.0) {
            return '2:1 (Double share)';
        }
        return '1:1 (Equal share)';
    }

    // =========================================================================
    // VALIDATION METHODS
    // =========================================================================

    /**
     * Check if the heir data is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $requiredFields = ['name', 'nric', 'email', 'phone', 'relationship'];
        
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        
        if (!self::validateNRIC($this->nric)) return false;
        if (!self::validateEmail($this->email)) return false;
        if (!self::validatePhone($this->phone)) return false;
        
        return true;
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public function getValidationErrors(): array
    {
        $errors = [];
        
        if (empty($this->name)) $errors[] = 'Name is required';
        if (empty($this->nric)) $errors[] = 'NRIC is required';
        elseif (!self::validateNRIC($this->nric)) $errors[] = 'Invalid NRIC format';
        
        if (empty($this->email)) $errors[] = 'Email is required';
        elseif (!self::validateEmail($this->email)) $errors[] = 'Invalid email format';
        
        if (empty($this->phone)) $errors[] = 'Phone is required';
        elseif (!self::validatePhone($this->phone)) $errors[] = 'Invalid phone format';
        
        if (empty($this->relationship)) $errors[] = 'Relationship is required';
        
        return $errors;
    }

    // =========================================================================
    // STATIC VALIDATION METHODS
    // =========================================================================

    /**
     * Validate NRIC format.
     *
     * @param  string|null $nric
     * @return bool
     */
    public static function validateNRIC(?string $nric): bool
    {
        if (empty($nric)) return false;
        $clean = preg_replace('/[-\s]/', '', $nric);
        return preg_match('/^\d{12}$/', $clean) === 1;
    }

    /**
     * Validate email format.
     *
     * @param  string|null $email
     * @return bool
     */
    public static function validateEmail(?string $email): bool
    {
        if (empty($email)) return false;
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone format (Malaysian).
     *
     * @param  string|null $phone
     * @return bool
     */
    public static function validatePhone(?string $phone): bool
    {
        if (empty($phone)) return false;
        // Accept formats: 01X-XXXXXXX, 01X-XXXXXXXX, 01XXXXXXXX, 01XXXXXXXXX
        $clean = preg_replace('/[-\s]/', '', $phone);
        return preg_match('/^01\d{8,9}$/', $clean) === 1;
    }

    /**
     * Format NRIC to standard format.
     *
     * @param  string|null $nric
     * @return string
     */
    public static function formatNRIC(?string $nric): string
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
     * @param  string|null $phone
     * @return string
     */
    public static function formatPhone(?string $phone): string
    {
        if (empty($phone)) return '';
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($clean) >= 10 && strlen($clean) <= 11 && str_starts_with($clean, '01')) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $phone;
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope for primary heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrimary($query)
    {
        return $query->whereIn('relationship', ['husband', 'wife', 'father', 'mother']);
    }

    /**
     * Scope for asabah heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAsabah($query)
    {
        return $query->whereIn('relationship', ['son', 'daughter', 'brother', 'paternal_uncle', 'male_cousin']);
    }

    /**
     * Scope for substitute heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubstitute($query)
    {
        return $query->whereIn('relationship', ['grandfather', 'grandmother', 'grandmother_paternal', 'grandmother_maternal']);
    }

    /**
     * Scope for secondary heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSecondary($query)
    {
        return $query->whereIn('relationship', [
            'half_brother_full', 'half_brother_paternal', 'half_brother_maternal',
            'half_sister_full', 'half_sister_paternal', 'half_sister_maternal',
        ]);
    }

    /**
     * Scope for male heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMale($query)
    {
        $maleRelationships = ['husband', 'son', 'father', 'brother', 'grandfather', 'half_brother_paternal', 'half_brother_maternal', 'half_brother_full', 'paternal_uncle', 'male_cousin'];
        
        return $query->where(function ($q) use ($maleRelationships) {
            $q->whereIn('relationship', $maleRelationships)
              ->orWhere('gender', 'male');
        });
    }

    /**
     * Scope for female heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFemale($query)
    {
        $femaleRelationships = ['wife', 'daughter', 'mother', 'sister', 'grandmother', 'half_sister_paternal', 'half_sister_maternal', 'half_sister_full', 'grandmother_paternal', 'grandmother_maternal'];
        
        return $query->where(function ($q) use ($femaleRelationships) {
            $q->whereIn('relationship', $femaleRelationships)
              ->orWhere('gender', 'female');
        });
    }

    /**
     * Scope for heirs with email.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasEmail($query)
    {
        return $query->whereNotNull('email')->where('email', '!=', '');
    }

    /**
     * Scope for heirs with phone.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasPhone($query)
    {
        return $query->whereNotNull('phone')->where('phone', '!=', '');
    }

    /**
     * Scope for heirs with complete contact info.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleteContact($query)
    {
        return $query->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->whereNotNull('phone')
                    ->where('phone', '!=', '');
    }

    /**
     * Scope for heirs with calculated percentage.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasCalculatedShare($query)
    {
        return $query->whereNotNull('calculated_percentage');
    }

    /**
     * Scope for heirs with manually set share.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasManualShare($query)
    {
        return $query->whereNotNull('share_percentage');
    }

    /**
     * Scope for eligible heirs (not blocked).
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEligible($query)
    {
        // Heirs that cannot be blocked
        return $query->whereIn('relationship', [
            'husband', 'wife', 'father', 'mother', 'son', 'daughter',
        ]);
    }

    /**
     * Scope for minor heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinor($query)
    {
        return $query->whereNotNull('date_of_birth')
                    ->whereDate('date_of_birth', '>', now()->subYears(18)->toDateString());
    }

    /**
     * Scope for adult heirs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdult($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('date_of_birth')
              ->orWhereDate('date_of_birth', '<=', now()->subYears(18)->toDateString());
        });
    }

    /**
     * Scope for heirs ordered by Faraid priority.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderedByFaraidPriority($query)
    {
        return $query->orderByRaw("
            CASE 
                WHEN relationship = 'husband' THEN 1
                WHEN relationship = 'wife' THEN 2
                WHEN relationship = 'father' THEN 3
                WHEN relationship = 'mother' THEN 4
                WHEN relationship = 'son' THEN 5
                WHEN relationship = 'daughter' THEN 6
                WHEN relationship = 'brother' THEN 7
                WHEN relationship = 'sister' THEN 8
                WHEN relationship = 'grandfather' THEN 9
                WHEN relationship = 'grandmother' THEN 10
                ELSE 99
            END
        ");
    }

    /**
     * Scope for search by name or NRIC.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('nric', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }

    // =========================================================================
    // BUSINESS METHODS
    // =========================================================================

    /**
     * Set the calculated Faraid percentage.
     *
     * @param  float $percentage
     * @return bool
     */
    public function setCalculatedShare(float $percentage): bool
    {
        try {
            $result = $this->update([
                'calculated_percentage' => round($percentage, 2),
            ]);

            Log::info('Heir calculated share updated', [
                'heir_id' => $this->id,
                'name' => $this->name,
                'old_percentage' => $this->getOriginal('calculated_percentage'),
                'new_percentage' => $percentage,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update heir calculated share: ' . $e->getMessage(), [
                'heir_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Set the manual share percentage.
     *
     * @param  float $percentage
     * @return bool
     */
    public function setManualShare(float $percentage): bool
    {
        try {
            $result = $this->update([
                'share_percentage' => round($percentage, 2),
            ]);

            Log::info('Heir manual share updated', [
                'heir_id' => $this->id,
                'name' => $this->name,
                'old_percentage' => $this->getOriginal('share_percentage'),
                'new_percentage' => $percentage,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update heir manual share: ' . $e->getMessage(), [
                'heir_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update contact information.
     *
     * @param  string $email
     * @param  string $phone
     * @param  string|null $address
     * @return bool
     */
    public function updateContactInfo(string $email, string $phone, ?string $address = null): bool
    {
        try {
            $data = [
                'email' => $email,
                'phone' => self::formatPhone($phone),
            ];
            
            if ($address !== null) {
                $data['address'] = $address;
            }

            $result = $this->update($data);

            Log::info('Heir contact info updated', [
                'heir_id' => $this->id,
                'name' => $this->name,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update heir contact info: ' . $e->getMessage(), [
                'heir_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update personal information.
     *
     * @param  string $name
     * @param  string $nric
     * @param  string|null $gender
     * @param  string|null $dateOfBirth
     * @return bool
     */
    public function updatePersonalInfo(string $name, string $nric, ?string $gender = null, ?string $dateOfBirth = null): bool
    {
        try {
            $data = [
                'name' => $name,
                'nric' => self::formatNRIC($nric),
            ];
            
            if ($gender !== null) {
                $data['gender'] = $gender;
            }
            
            if ($dateOfBirth !== null) {
                $data['date_of_birth'] = $dateOfBirth;
            }

            $result = $this->update($data);

            Log::info('Heir personal info updated', [
                'heir_id' => $this->id,
                'name' => $name,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update heir personal info: ' . $e->getMessage(), [
                'heir_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
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
        static::creating(function ($heir) {
            // Set default values
            if (empty($heir->priority)) {
                $heir->priority = 0;
            }

            // Auto-set relationship type based on relationship
            if (empty($heir->relationship_type)) {
                $heir->relationship_type = self::determineRelationshipType($heir->relationship);
            }

            // Format NRIC and phone
            if ($heir->nric) {
                $heir->nric = self::formatNRIC($heir->nric);
            }
            if ($heir->phone) {
                $heir->phone = self::formatPhone($heir->phone);
            }

            // Log creation
            Log::info('New heir being created', [
                'name' => $heir->name,
                'relationship' => $heir->relationship,
                'estate_id' => $heir->estate_pre_registration_id,
            ]);
        });

        static::updating(function ($heir) {
            // Format NRIC and phone before saving
            if ($heir->isDirty('nric') && $heir->nric) {
                $heir->nric = self::formatNRIC($heir->nric);
            }
            if ($heir->isDirty('phone') && $heir->phone) {
                $heir->phone = self::formatPhone($heir->phone);
            }

            // Auto-update relationship type if relationship changed
            if ($heir->isDirty('relationship') && !$heir->isDirty('relationship_type')) {
                $heir->relationship_type = self::determineRelationshipType($heir->relationship);
            }

            // Log significant changes
            if ($heir->isDirty('calculated_percentage')) {
                Log::info('Heir calculated share updated', [
                    'heir_id' => $heir->id,
                    'old' => $heir->getOriginal('calculated_percentage'),
                    'new' => $heir->calculated_percentage,
                ]);
            }
        });

        static::deleting(function ($heir) {
            // Log deletion
            Log::info('Heir being deleted', [
                'heir_id' => $heir->id,
                'name' => $heir->name,
                'estate_id' => $heir->estate_pre_registration_id,
            ]);
        });
    }

    /**
     * Determine relationship type from relationship value.
     *
     * @param  string $relationship
     * @return string
     */
    protected static function determineRelationshipType(string $relationship): string
    {
        $primary = ['husband', 'wife', 'father', 'mother'];
        $substitute = ['grandfather', 'grandmother_paternal', 'grandmother_maternal'];
        $secondary = [
            'half_brother_full', 'half_brother_paternal', 'half_brother_maternal',
            'half_sister_full', 'half_sister_paternal', 'half_sister_maternal',
        ];
        $asabah = ['son', 'daughter'];
        
        if (in_array($relationship, $primary)) return 'primary';
        if (in_array($relationship, $substitute)) return 'substitute';
        if (in_array($relationship, $secondary)) return 'secondary';
        if (in_array($relationship, $asabah)) return 'asabah';
        
        return 'other';
    }
}