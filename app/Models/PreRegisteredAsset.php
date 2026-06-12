<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PreRegisteredAsset extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_registered_assets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Foreign Key
        'estate_pre_registration_id',

        // Asset Information
        'name',
        'type',
        'category',
        'description',
        'value',
        'location',
        'ownership_percentage',

        // Reference and Documentation
        'reference_number',
        'documents',

        // Metadata
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Numeric casts
        'value' => 'decimal:2',
        'ownership_percentage' => 'decimal:2',

        // JSON casts
        'documents' => 'array',
        'metadata' => 'array',

        // DateTime casts
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        // Financial
        'formatted_value',
        'owned_value',
        'formatted_owned_value',

        // Classification
        'type_label',
        'category_label',
        'asset_icon',
        'asset_badge_html',
        'badge_class',

        // Status
        'is_fully_owned',
        'is_partially_owned',
        'ownership_percentage_display',

        // Documentation
        'has_documents',
        'documents_count',

        // Summary
        'asset_summary',
        'quick_summary',

        // Metadata
        'has_metadata',
        'metadata_keys',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the estate pre-registration that owns this asset.
     *
     * @return BelongsTo
     */
    public function estatePreRegistration(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class);
    }

    // =========================================================================
    // FINANCIAL ACCESSORS
    // =========================================================================

    /**
     * Get the formatted value of the asset.
     *
     * @return string
     */
    public function getFormattedValueAttribute(): string
    {
        $value = $this->value ?? 0;
        return 'RM ' . number_format((float) $value, 2);
    }

    /**
     * Get the owned value (value × ownership percentage).
     *
     * @return float
     */
    public function getOwnedValueAttribute(): float
    {
        $value = (float) ($this->value ?? 0);
        $percentage = (float) ($this->ownership_percentage ?? 100);
        return round(($value * $percentage) / 100, 2);
    }

    /**
     * Get the formatted owned value.
     *
     * @return string
     */
    public function getFormattedOwnedValueAttribute(): string
    {
        return 'RM ' . number_format($this->owned_value, 2);
    }

    /**
     * Get the value as a float (ensures proper type casting).
     *
     * @return float
     */
    public function getValueFloatAttribute(): float
    {
        return (float) ($this->value ?? 0);
    }

    /**
     * Get the ownership percentage as a float.
     *
     * @return float
     */
    public function getOwnershipPercentageFloatAttribute(): float
    {
        return (float) ($this->ownership_percentage ?? 100);
    }

    // =========================================================================
    // CLASSIFICATION ACCESSORS
    // =========================================================================

    /**
     * Get the type label for display.
     *
     * @return string
     */
    public function getTypeLabelAttribute(): string
    {
        if (!empty($this->type)) {
            return $this->type;
        }

        // Infer type from asset name if type is not set
        $typeMap = [
            'Residential House' => 'Property',
            'Apartment / Condominium' => 'Property',
            'Low-cost Flat (PPR / Kos Rendah)' => 'Property',
            'Shop Lot' => 'Property',
            'Office Unit' => 'Property',
            'Industrial Property (Factory / Warehouse)' => 'Property',
            'Agricultural Land' => 'Property',
            'Vacant Land / Lot' => 'Property',
            'Vehicle (Car / Motorcycle)' => 'Vehicle',
            'Bank Savings' => 'Cash/Savings',
            'Fixed Deposit' => 'Cash/Savings',
            'EPF / KWSP Savings' => 'Retirement',
            'Tabung Haji Savings' => 'Savings',
            'ASB / Unit Trust Investment' => 'Investment',
            'Shares / Stocks' => 'Investment',
            'Gold / Precious Metals' => 'Investment',
            'Business Ownership' => 'Business',
            'Insurance / Takaful Payout' => 'Insurance',
            'Cash in Hand' => 'Cash',
            'Digital Assets (Crypto / E-wallet)' => 'Digital',
            'Other Assets' => 'Other',
        ];

        return $typeMap[$this->name] ?? 'Other';
    }

    /**
     * Get the category label for display.
     *
     * @return string
     */
    public function getCategoryLabelAttribute(): string
    {
        if (!empty($this->category)) {
            return $this->category;
        }

        // Infer category from asset name if category is not set
        $categoryMap = [
            'Residential House' => 'Real Estate',
            'Apartment / Condominium' => 'Real Estate',
            'Low-cost Flat (PPR / Kos Rendah)' => 'Real Estate',
            'Shop Lot' => 'Real Estate',
            'Office Unit' => 'Real Estate',
            'Industrial Property (Factory / Warehouse)' => 'Real Estate',
            'Agricultural Land' => 'Real Estate',
            'Vacant Land / Lot' => 'Real Estate',
            'Vehicle (Car / Motorcycle)' => 'Movable Asset',
            'Bank Savings' => 'Financial Asset',
            'Fixed Deposit' => 'Financial Asset',
            'EPF / KWSP Savings' => 'Financial Asset',
            'Tabung Haji Savings' => 'Financial Asset',
            'ASB / Unit Trust Investment' => 'Investment Asset',
            'Shares / Stocks' => 'Investment Asset',
            'Gold / Precious Metals' => 'Investment Asset',
            'Business Ownership' => 'Business Asset',
            'Insurance / Takaful Payout' => 'Financial Asset',
            'Cash in Hand' => 'Liquid Asset',
            'Digital Assets (Crypto / E-wallet)' => 'Digital Asset',
            'Other Assets' => 'Other',
        ];

        return $categoryMap[$this->name] ?? 'Other';
    }

    /**
     * Get the asset icon emoji.
     *
     * @return string
     */
    public function getAssetIconAttribute(): string
    {
        $icons = [
            'Real Estate' => '🏠',
            'Movable Asset' => '🚗',
            'Financial Asset' => '💰',
            'Investment Asset' => '📈',
            'Business Asset' => '🏢',
            'Digital Asset' => '💻',
            'Liquid Asset' => '💵',
            'Retirement' => '🏦',
            'Savings' => '🐖',
            'Insurance' => '🛡️',
            'Other' => '📋',
        ];

        $category = $this->category_label;
        return $icons[$category] ?? '📋';
    }

    /**
     * Get the badge class for Bootstrap styling.
     *
     * @return string
     */
    public function getBadgeClassAttribute(): string
    {
        $classes = [
            'Real Estate' => 'badge-real-estate',
            'Movable Asset' => 'badge-financial',
            'Financial Asset' => 'badge-financial',
            'Investment Asset' => 'badge-investment',
            'Business Asset' => 'badge-investment',
            'Digital Asset' => 'badge-digital',
            'Liquid Asset' => 'badge-financial',
            'Retirement' => 'badge-financial',
            'Savings' => 'badge-financial',
            'Insurance' => 'badge-financial',
            'Other' => 'badge-real-estate',
        ];

        $category = $this->category_label;
        return $classes[$category] ?? 'badge-real-estate';
    }

    /**
     * Get the badge HTML for display.
     *
     * @return string
     */
    public function getAssetBadgeHtmlAttribute(): string
    {
        return sprintf(
            '<span class="badge %s">%s</span>',
            $this->badge_class,
            e($this->category_label)
        );
    }

    /**
     * Get the CSS class for the category badge.
     *
     * @return string
     */
    public function getCategoryBadgeClassAttribute(): string
    {
        $classes = [
            'Real Estate' => 'bg-primary',
            'Movable Asset' => 'bg-info',
            'Financial Asset' => 'bg-success',
            'Investment Asset' => 'bg-warning text-dark',
            'Business Asset' => 'bg-secondary',
            'Digital Asset' => 'bg-purple',
            'Liquid Asset' => 'bg-teal',
            'Retirement' => 'bg-indigo',
            'Savings' => 'bg-cyan',
            'Insurance' => 'bg-orange',
            'Other' => 'bg-dark',
        ];

        $category = $this->category_label;
        return $classes[$category] ?? 'bg-dark';
    }

    // =========================================================================
    // OWNERSHIP STATUS ACCESSORS
    // =========================================================================

    /**
     * Check if the asset is fully owned (100%).
     *
     * @return bool
     */
    public function getIsFullyOwnedAttribute(): bool
    {
        return (float) ($this->ownership_percentage ?? 100) >= 100;
    }

    /**
     * Check if the asset is partially owned.
     *
     * @return bool
     */
    public function getIsPartiallyOwnedAttribute(): bool
    {
        $percentage = (float) ($this->ownership_percentage ?? 100);
        return $percentage > 0 && $percentage < 100;
    }

    /**
     * Get the ownership percentage display string.
     *
     * @return string
     */
    public function getOwnershipPercentageDisplayAttribute(): string
    {
        $percentage = (float) ($this->ownership_percentage ?? 100);
        return number_format($percentage, 0) . '%';
    }

    /**
     * Get the ownership status label.
     *
     * @return string
     */
    public function getOwnershipStatusLabelAttribute(): string
    {
        if ($this->is_fully_owned) {
            return 'Fully Owned';
        }
        if ($this->is_partially_owned) {
            return 'Partially Owned (' . $this->ownership_percentage_display . ')';
        }
        return 'No Ownership';
    }

    /**
     * Get the ownership status color.
     *
     * @return string
     */
    public function getOwnershipStatusColorAttribute(): string
    {
        if ($this->is_fully_owned) {
            return 'success';
        }
        if ($this->is_partially_owned) {
            return 'warning';
        }
        return 'danger';
    }

    // =========================================================================
    // DOCUMENTATION ACCESSORS
    // =========================================================================

    /**
     * Check if the asset has documents.
     *
     * @return bool
     */
    public function getHasDocumentsAttribute(): bool
    {
        return !empty($this->documents) && is_array($this->documents) && count($this->documents) > 0;
    }

    /**
     * Get the count of documents.
     *
     * @return int
     */
    public function getDocumentsCountAttribute(): int
    {
        if (!$this->has_documents) {
            return 0;
        }
        return count($this->documents);
    }

    /**
     * Get documents as an array (ensures proper type).
     *
     * @return array
     */
    public function getDocumentsArrayAttribute(): array
    {
        if (is_array($this->documents)) {
            return $this->documents;
        }
        return [];
    }

    // =========================================================================
    // SUMMARY ACCESSORS
    // =========================================================================

    /**
     * Get comprehensive asset summary.
     *
     * @return array
     */
    public function getAssetSummaryAttribute(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type_label,
            'category' => $this->category_label,
            'icon' => $this->asset_icon,
            'value' => (float) ($this->value ?? 0),
            'formatted_value' => $this->formatted_value,
            'ownership_percentage' => (float) ($this->ownership_percentage ?? 100),
            'owned_value' => $this->owned_value,
            'formatted_owned_value' => $this->formatted_owned_value,
            'description' => $this->description,
            'location' => $this->location,
            'is_fully_owned' => $this->is_fully_owned,
            'has_documents' => $this->has_documents,
            'documents_count' => $this->documents_count,
            'created_at' => $this->created_at?->format('d M Y'),
            'updated_at' => $this->updated_at?->format('d M Y'),
        ];
    }

    /**
     * Get a quick summary string.
     *
     * @return string
     */
    public function getQuickSummaryAttribute(): string
    {
        return sprintf(
            '%s %s | %s | %s | %s ownership',
            $this->asset_icon,
            $this->name,
            $this->formatted_value,
            $this->formatted_owned_value,
            $this->ownership_percentage_display
        );
    }

    // =========================================================================
    // METADATA ACCESSORS
    // =========================================================================

    /**
     * Check if the asset has metadata.
     *
     * @return bool
     */
    public function getHasMetadataAttribute(): bool
    {
        return !empty($this->metadata) && is_array($this->metadata) && count($this->metadata) > 0;
    }

    /**
     * Get metadata keys.
     *
     * @return array
     */
    public function getMetadataKeysAttribute(): array
    {
        if (!$this->has_metadata) {
            return [];
        }
        return array_keys($this->metadata);
    }

    /**
     * Get a metadata value by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function getMetadata(string $key, $default = null)
    {
        if (!$this->has_metadata) {
            return $default;
        }
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Set a metadata value.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return bool
     */
    public function setMetadata(string $key, $value): bool
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        return $this->update(['metadata' => $metadata]);
    }

    /**
     * Remove a metadata key.
     *
     * @param  string  $key
     * @return bool
     */
    public function removeMetadata(string $key): bool
    {
        if (!$this->has_metadata) {
            return true;
        }
        $metadata = $this->metadata;
        unset($metadata[$key]);
        return $this->update(['metadata' => $metadata]);
    }

    // =========================================================================
    // VALIDATION ACCESSORS
    // =========================================================================

    /**
     * Check if the asset record is complete and valid.
     *
     * @return bool
     */
    public function getIsValidAttribute(): bool
    {
        return !empty($this->name) && ($this->value ?? 0) >= 0;
    }

    /**
     * Check if the asset has a description.
     *
     * @return bool
     */
    public function getHasDescriptionAttribute(): bool
    {
        return !empty(trim($this->description ?? ''));
    }

    /**
     * Check if the asset has a location.
     *
     * @return bool
     */
    public function getHasLocationAttribute(): bool
    {
        return !empty(trim($this->location ?? ''));
    }

    /**
     * Check if the asset has a reference number.
     *
     * @return bool
     */
    public function getHasReferenceNumberAttribute(): bool
    {
        return !empty(trim($this->reference_number ?? ''));
    }

    /**
     * Get validation errors if any.
     *
     * @return array
     */
    public function getValidationErrorsAttribute(): array
    {
        $errors = [];
        
        if (empty($this->name)) {
            $errors[] = 'Asset name is required';
        }
        
        if (($this->value ?? 0) < 0) {
            $errors[] = 'Asset value cannot be negative';
        }
        
        $percentage = (float) ($this->ownership_percentage ?? 100);
        if ($percentage < 0 || $percentage > 100) {
            $errors[] = 'Ownership percentage must be between 0 and 100';
        }
        
        return $errors;
    }

    // =========================================================================
    // REAL ESTATE SPECIFIC ACCESSORS
    // =========================================================================

    /**
     * Check if this is a real estate asset.
     *
     * @return bool
     */
    public function getIsRealEstateAttribute(): bool
    {
        return $this->category_label === 'Real Estate';
    }

    /**
     * Check if this is a financial asset.
     *
     * @return bool
     */
    public function getIsFinancialAttribute(): bool
    {
        return in_array($this->category_label, ['Financial Asset', 'Liquid Asset', 'Retirement', 'Savings']);
    }

    /**
     * Check if this is an investment asset.
     *
     * @return bool
     */
    public function getIsInvestmentAttribute(): bool
    {
        return $this->category_label === 'Investment Asset';
    }

    /**
     * Check if this is a digital asset.
     *
     * @return bool
     */
    public function getIsDigitalAttribute(): bool
    {
        return $this->category_label === 'Digital Asset';
    }

    /**
     * Check if this is a liquid asset (cash or easily convertible).
     *
     * @return bool
     */
    public function getIsLiquidAttribute(): bool
    {
        return in_array($this->category_label, ['Liquid Asset', 'Financial Asset', 'Savings']);
    }

    // =========================================================================
    // BUSINESS METHODS
    // =========================================================================

    /**
     * Calculate the owned value dynamically.
     *
     * @param  float|null  $totalValue
     * @return float
     */
    public function calculateOwnedValue(?float $totalValue = null): float
    {
        $value = $totalValue ?? (float) ($this->value ?? 0);
        $percentage = (float) ($this->ownership_percentage ?? 100);
        return round(($value * $percentage) / 100, 2);
    }

    /**
     * Update the asset value.
     *
     * @param  float  $newValue
     * @return bool
     */
    public function updateValue(float $newValue): bool
    {
        try {
            $result = $this->update(['value' => $newValue]);

            Log::info('Asset value updated', [
                'asset_id' => $this->id,
                'name' => $this->name,
                'old_value' => $this->getOriginal('value'),
                'new_value' => $newValue,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update asset value', [
                'asset_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update the ownership percentage.
     *
     * @param  float  $percentage
     * @return bool
     */
    public function updateOwnership(float $percentage): bool
    {
        if ($percentage < 0 || $percentage > 100) {
            Log::warning('Invalid ownership percentage', [
                'asset_id' => $this->id,
                'percentage' => $percentage,
            ]);
            return false;
        }

        try {
            $result = $this->update(['ownership_percentage' => $percentage]);

            Log::info('Asset ownership updated', [
                'asset_id' => $this->id,
                'name' => $this->name,
                'old_percentage' => $this->getOriginal('ownership_percentage'),
                'new_percentage' => $percentage,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update asset ownership', [
                'asset_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Add a document to the asset.
     *
     * @param  string  $documentPath
     * @return bool
     */
    public function addDocument(string $documentPath): bool
    {
        $documents = $this->documents ?? [];
        $documents[] = $documentPath;
        return $this->update(['documents' => $documents]);
    }

    /**
     * Remove a document from the asset.
     *
     * @param  int  $index
     * @return bool
     */
    public function removeDocument(int $index): bool
    {
        if (!$this->has_documents) {
            return true;
        }
        $documents = $this->documents;
        if (isset($documents[$index])) {
            unset($documents[$index]);
            $documents = array_values($documents); // Re-index
            return $this->update(['documents' => $documents]);
        }
        return false;
    }

    /**
     * Get a summary of the asset for export.
     *
     * @return array
     */
    public function toExportArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type_label,
            'category' => $this->category_label,
            'value' => (float) ($this->value ?? 0),
            'formatted_value' => $this->formatted_value,
            'ownership_percentage' => (float) ($this->ownership_percentage ?? 100),
            'owned_value' => $this->owned_value,
            'formatted_owned_value' => $this->formatted_owned_value,
            'description' => $this->description,
            'location' => $this->location,
            'reference_number' => $this->reference_number,
            'documents_count' => $this->documents_count,
            'is_fully_owned' => $this->is_fully_owned,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope for assets by type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for assets by category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for real estate assets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRealEstate($query)
    {
        return $query->where('category', 'Real Estate');
    }

    /**
     * Scope for financial assets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinancial($query)
    {
        return $query->whereIn('category', ['Financial Asset', 'Liquid Asset', 'Retirement', 'Savings']);
    }

    /**
     * Scope for fully owned assets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullyOwned($query)
    {
        return $query->where('ownership_percentage', '>=', 100);
    }

    /**
     * Scope for partially owned assets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePartiallyOwned($query)
    {
        return $query->where('ownership_percentage', '>', 0)
                    ->where('ownership_percentage', '<', 100);
    }

    /**
     * Scope for assets with documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasDocuments($query)
    {
        return $query->whereNotNull('documents')
                    ->where('documents', '!=', '[]')
                    ->where('documents', '!=', 'null');
    }

    /**
     * Scope for high-value assets (above threshold).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  float  $threshold
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighValue($query, float $threshold = 100000)
    {
        return $query->where('value', '>=', $threshold);
    }

    /**
     * Scope for assets with description.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasDescription($query)
    {
        return $query->whereNotNull('description')
                    ->where('description', '!=', '');
    }

    /**
     * Scope for assets search.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('location', 'LIKE', "%{$search}%")
              ->orWhere('reference_number', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope for recently updated assets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentlyUpdated($query, int $days = 30)
    {
        return $query->where('updated_at', '>=', now()->subDays($days));
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
        static::creating(function ($asset) {
            // Set default values
            if (empty($asset->ownership_percentage)) {
                $asset->ownership_percentage = 100;
            }

            if (empty($asset->value)) {
                $asset->value = 0;
            }

            // Log creation
            Log::info('Asset being created', [
                'name' => $asset->name,
                'value' => $asset->value,
                'estate_id' => $asset->estate_pre_registration_id,
            ]);
        });

        static::updating(function ($asset) {
            // Log value changes
            if ($asset->isDirty('value')) {
                Log::info('Asset value changed', [
                    'asset_id' => $asset->id,
                    'name' => $asset->name,
                    'old_value' => $asset->getOriginal('value'),
                    'new_value' => $asset->value,
                ]);
            }

            // Log ownership changes
            if ($asset->isDirty('ownership_percentage')) {
                Log::info('Asset ownership changed', [
                    'asset_id' => $asset->id,
                    'name' => $asset->name,
                    'old_percentage' => $asset->getOriginal('ownership_percentage'),
                    'new_percentage' => $asset->ownership_percentage,
                ]);
            }
        });

        static::deleting(function ($asset) {
            // Log deletion
            Log::info('Asset being deleted', [
                'asset_id' => $asset->id,
                'name' => $asset->name,
                'value' => $asset->value,
                'estate_id' => $asset->estate_pre_registration_id,
            ]);
        });

        static::saved(function ($asset) {
            // Clear cache or perform post-save actions if needed
        });
    }
}