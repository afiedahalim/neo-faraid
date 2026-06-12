<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PreRegisteredDebt extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_registered_debts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Foreign Key
        'estate_pre_registration_id',

        // Creditor Information
        'creditor_name',
        'creditor_contact',

        // Debt Details
        'debt_type',
        'description',
        'amount',
        'type',
        'category',

        // Timeline
        'due_date',

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
        'amount' => 'decimal:2',

        // Date casts
        'due_date' => 'date',

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
        'formatted_amount',
        'amount_float',

        // Classification
        'type_label',
        'category_label',
        'type_badge_html',
        'badge_class',
        'debt_icon',

        // Status
        'is_overdue',
        'is_due_soon',
        'days_until_due',
        'days_overdue',
        'due_status_label',
        'due_status_color',

        // Priority
        'priority_level',
        'is_secured',
        'is_unsecured',
        'is_religious',
        'is_government',
        'is_personal',

        // Documentation
        'has_documents',
        'documents_count',

        // Summary
        'debt_summary',
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
     * Get the estate pre-registration that owns this debt.
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
     * Get the formatted amount of the debt.
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        $amount = $this->amount ?? 0;
        return 'RM ' . number_format((float) $amount, 2);
    }

    /**
     * Get the amount as a float (ensures proper type casting).
     *
     * @return float
     */
    public function getAmountFloatAttribute(): float
    {
        return (float) ($this->amount ?? 0);
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
            $labels = [
                'secured' => 'Secured Debt',
                'unsecured' => 'Unsecured Debt',
                'personal' => 'Personal Debt (Huquq Al-Ibad)',
                'religious' => 'Religious Obligation (Huquq Allah)',
                'government' => 'Government / Statutory Debt',
                'administrative' => 'Administrative / Final Expenses',
                'other' => 'Other Debt',
            ];
            return $labels[$this->type] ?? ucfirst($this->type);
        }

        // Infer type from debt_type if type is not set
        if (!empty($this->debt_type)) {
            $typeMap = [
                'Housing Loan' => 'Secured Debt',
                'Car Loan / Hire Purchase' => 'Secured Debt',
                'Business Loan' => 'Secured Debt',
                'Overdraft / Bank Facility' => 'Secured Debt',
                'Personal Loan' => 'Unsecured Debt',
                'Credit Card Outstanding' => 'Unsecured Debt',
                'Education Loan (PTPTN)' => 'Unsecured Debt',
                'Borrowed from Family / Friends' => 'Personal Debt (Huquq Al-Ibad)',
                'Unpaid Zakat' => 'Religious Obligation (Huquq Allah)',
                'Unpaid Income Tax (LHDN)' => 'Government / Statutory Debt',
                'Court Fines / Legal Penalties' => 'Government / Statutory Debt',
                'Utility Bills' => 'Administrative / Final Expenses',
                'Medical Bills' => 'Administrative / Final Expenses',
                'Funeral Expenses' => 'Administrative / Final Expenses',
                'Other Liabilities' => 'Other Debt',
            ];
            return $typeMap[$this->debt_type] ?? 'Other Debt';
        }

        return 'Other Debt';
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

        // Infer category from type
        $categoryMap = [
            'secured' => 'Secured',
            'unsecured' => 'Unsecured',
            'personal' => 'Personal',
            'religious' => 'Religious',
            'government' => 'Government',
            'administrative' => 'Administrative',
            'other' => 'Other',
        ];

        return $categoryMap[$this->type] ?? 'Other';
    }

    /**
     * Get the debt icon emoji.
     *
     * @return string
     */
    public function getDebtIconAttribute(): string
    {
        $icons = [
            'Secured Debt' => '🏠',
            'Unsecured Debt' => '💳',
            'Personal Debt (Huquq Al-Ibad)' => '👥',
            'Religious Obligation (Huquq Allah)' => '🕌',
            'Government / Statutory Debt' => '🏛️',
            'Administrative / Final Expenses' => '📋',
            'Other Debt' => '📝',
        ];

        $typeLabel = $this->type_label;
        return $icons[$typeLabel] ?? '📝';
    }

    /**
     * Get the badge class for Bootstrap styling.
     *
     * @return string
     */
    public function getBadgeClassAttribute(): string
    {
        $classes = [
            'Secured Debt' => 'badge-secured',
            'Unsecured Debt' => 'badge-unsecured',
            'Personal Debt (Huquq Al-Ibad)' => 'badge-unsecured',
            'Religious Obligation (Huquq Allah)' => 'badge-religious',
            'Government / Statutory Debt' => 'badge-secured',
            'Administrative / Final Expenses' => 'badge-unsecured',
            'Other Debt' => 'badge-unsecured',
        ];

        $typeLabel = $this->type_label;
        return $classes[$typeLabel] ?? 'badge-unsecured';
    }

    /**
     * Get the badge HTML for display.
     *
     * @return string
     */
    public function getTypeBadgeHtmlAttribute(): string
    {
        return sprintf(
            '<span class="badge %s">%s</span>',
            $this->badge_class,
            e($this->type_label)
        );
    }

    /**
     * Get the CSS class for the type badge.
     *
     * @return string
     */
    public function getTypeBadgeClassAttribute(): string
    {
        $classes = [
            'Secured Debt' => 'bg-danger',
            'Unsecured Debt' => 'bg-warning text-dark',
            'Personal Debt (Huquq Al-Ibad)' => 'bg-orange',
            'Religious Obligation (Huquq Allah)' => 'bg-success',
            'Government / Statutory Debt' => 'bg-dark',
            'Administrative / Final Expenses' => 'bg-info',
            'Other Debt' => 'bg-secondary',
        ];

        $typeLabel = $this->type_label;
        return $classes[$typeLabel] ?? 'bg-secondary';
    }

    // =========================================================================
    // STATUS ACCESSORS - DUE DATE
    // =========================================================================

    /**
     * Check if the debt is overdue.
     *
     * @return bool
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date) {
            return false;
        }
        return $this->due_date->isPast();
    }

    /**
     * Check if the debt is due soon (within 30 days).
     *
     * @return bool
     */
    public function getIsDueSoonAttribute(): bool
    {
        if (!$this->due_date || $this->is_overdue) {
            return false;
        }
        return $this->due_date->diffInDays(now()) <= 30;
    }

    /**
     * Get days until the debt is due.
     *
     * @return int|null
     */
    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->due_date || $this->is_overdue) {
            return null;
        }
        return (int) now()->diffInDays($this->due_date);
    }

    /**
     * Get days the debt is overdue.
     *
     * @return int|null
     */
    public function getDaysOverdueAttribute(): ?int
    {
        if (!$this->due_date || !$this->is_overdue) {
            return null;
        }
        return (int) $this->due_date->diffInDays(now());
    }

    /**
     * Get the due status label.
     *
     * @return string
     */
    public function getDueStatusLabelAttribute(): string
    {
        if (!$this->due_date) {
            return 'No Due Date';
        }
        if ($this->is_overdue) {
            return 'Overdue (' . $this->days_overdue . ' days)';
        }
        if ($this->is_due_soon) {
            return 'Due Soon (' . $this->days_until_due . ' days)';
        }
        return 'Due in ' . $this->days_until_due . ' days';
    }

    /**
     * Get the due status color.
     *
     * @return string
     */
    public function getDueStatusColorAttribute(): string
    {
        if (!$this->due_date) {
            return 'secondary';
        }
        if ($this->is_overdue) {
            return 'danger';
        }
        if ($this->is_due_soon) {
            return 'warning';
        }
        return 'success';
    }

    /**
     * Get formatted due date.
     *
     * @return string
     */
    public function getFormattedDueDateAttribute(): string
    {
        return $this->due_date ? $this->due_date->format('d F Y') : 'N/A';
    }

    // =========================================================================
    // PRIORITY ACCESSORS
    // =========================================================================

    /**
     * Get the priority level of the debt.
     *
     * @return string
     */
    public function getPriorityLevelAttribute(): string
    {
        if ($this->is_overdue) {
            return 'Critical';
        }
        if ($this->is_due_soon) {
            return 'High';
        }
        if ($this->is_religious) {
            return 'High';
        }
        if ($this->is_government) {
            return 'High';
        }
        if ($this->is_secured) {
            return 'Medium';
        }
        return 'Low';
    }

    /**
     * Get the priority level color.
     *
     * @return string
     */
    public function getPriorityLevelColorAttribute(): string
    {
        $colors = [
            'Critical' => 'danger',
            'High' => 'warning',
            'Medium' => 'info',
            'Low' => 'success',
        ];
        return $colors[$this->priority_level] ?? 'secondary';
    }

    /**
     * Check if the debt is a secured debt.
     *
     * @return bool
     */
    public function getIsSecuredAttribute(): bool
    {
        return $this->type === 'secured' || str_contains($this->type_label, 'Secured');
    }

    /**
     * Check if the debt is an unsecured debt.
     *
     * @return bool
     */
    public function getIsUnsecuredAttribute(): bool
    {
        return $this->type === 'unsecured';
    }

    /**
     * Check if the debt is a religious obligation.
     *
     * @return bool
     */
    public function getIsReligiousAttribute(): bool
    {
        return $this->type === 'religious' || str_contains($this->type_label, 'Religious');
    }

    /**
     * Check if the debt is a government obligation.
     *
     * @return bool
     */
    public function getIsGovernmentAttribute(): bool
    {
        return $this->type === 'government' || str_contains($this->type_label, 'Government');
    }

    /**
     * Check if the debt is a personal debt.
     *
     * @return bool
     */
    public function getIsPersonalAttribute(): bool
    {
        return $this->type === 'personal' || str_contains($this->type_label, 'Personal');
    }

    /**
     * Check if the debt is an administrative/final expense.
     *
     * @return bool
     */
    public function getIsAdministrativeAttribute(): bool
    {
        return $this->type === 'administrative' || str_contains($this->type_label, 'Administrative');
    }

    // =========================================================================
    // DOCUMENTATION ACCESSORS
    // =========================================================================

    /**
     * Check if the debt has documents.
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
     * Get comprehensive debt summary.
     *
     * @return array
     */
    public function getDebtSummaryAttribute(): array
    {
        return [
            'id' => $this->id,
            'creditor_name' => $this->creditor_name,
            'creditor_contact' => $this->creditor_contact,
            'debt_type' => $this->debt_type,
            'type' => $this->type_label,
            'category' => $this->category_label,
            'icon' => $this->debt_icon,
            'amount' => (float) ($this->amount ?? 0),
            'formatted_amount' => $this->formatted_amount,
            'description' => $this->description,
            'due_date' => $this->formatted_due_date,
            'is_overdue' => $this->is_overdue,
            'is_due_soon' => $this->is_due_soon,
            'due_status' => $this->due_status_label,
            'priority_level' => $this->priority_level,
            'is_secured' => $this->is_secured,
            'has_documents' => $this->has_documents,
            'documents_count' => $this->documents_count,
            'reference_number' => $this->reference_number,
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
            '%s %s | %s | %s | %s',
            $this->debt_icon,
            $this->creditor_name,
            $this->formatted_amount,
            $this->type_label,
            $this->due_status_label
        );
    }

    // =========================================================================
    // METADATA ACCESSORS
    // =========================================================================

    /**
     * Check if the debt has metadata.
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
     * Check if the debt record is complete and valid.
     *
     * @return bool
     */
    public function getIsValidAttribute(): bool
    {
        return !empty($this->creditor_name) && ($this->amount ?? 0) >= 0;
    }

    /**
     * Check if the debt has a description.
     *
     * @return bool
     */
    public function getHasDescriptionAttribute(): bool
    {
        return !empty(trim($this->description ?? ''));
    }

    /**
     * Check if the debt has creditor contact.
     *
     * @return bool
     */
    public function getHasCreditorContactAttribute(): bool
    {
        return !empty(trim($this->creditor_contact ?? ''));
    }

    /**
     * Check if the debt has a reference number.
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
        
        if (empty($this->creditor_name)) {
            $errors[] = 'Creditor name is required';
        }
        
        if (($this->amount ?? 0) < 0) {
            $errors[] = 'Debt amount cannot be negative';
        }
        
        if ($this->due_date && $this->due_date->isBefore(now()->subYears(50))) {
            $errors[] = 'Due date seems too far in the past';
        }
        
        return $errors;
    }

    // =========================================================================
    // BUSINESS METHODS
    // =========================================================================

    /**
     * Check if the debt is overdue.
     *
     * @return bool
     */
    public function isOverdue(): bool
    {
        return $this->is_overdue;
    }

    /**
     * Update the debt amount.
     *
     * @param  float  $newAmount
     * @return bool
     */
    public function updateAmount(float $newAmount): bool
    {
        try {
            $result = $this->update(['amount' => $newAmount]);

            Log::info('Debt amount updated', [
                'debt_id' => $this->id,
                'creditor' => $this->creditor_name,
                'old_amount' => $this->getOriginal('amount'),
                'new_amount' => $newAmount,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update debt amount', [
                'debt_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update the due date.
     *
     * @param  string|\Carbon\Carbon|null  $dueDate
     * @return bool
     */
    public function updateDueDate($dueDate): bool
    {
        try {
            $result = $this->update(['due_date' => $dueDate]);

            Log::info('Debt due date updated', [
                'debt_id' => $this->id,
                'creditor' => $this->creditor_name,
                'old_due_date' => $this->getOriginal('due_date'),
                'new_due_date' => $dueDate,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update debt due date', [
                'debt_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Add a document to the debt.
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
     * Remove a document from the debt.
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
     * Get a summary of the debt for export.
     *
     * @return array
     */
    public function toExportArray(): array
    {
        return [
            'creditor_name' => $this->creditor_name,
            'creditor_contact' => $this->creditor_contact,
            'debt_type' => $this->debt_type,
            'type' => $this->type_label,
            'category' => $this->category_label,
            'amount' => (float) ($this->amount ?? 0),
            'formatted_amount' => $this->formatted_amount,
            'description' => $this->description,
            'due_date' => $this->formatted_due_date,
            'is_overdue' => $this->is_overdue,
            'due_status' => $this->due_status_label,
            'priority_level' => $this->priority_level,
            'reference_number' => $this->reference_number,
            'documents_count' => $this->documents_count,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope for debts by type.
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
     * Scope for debts by category.
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
     * Scope for secured debts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSecured($query)
    {
        return $query->where('type', 'secured');
    }

    /**
     * Scope for unsecured debts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnsecured($query)
    {
        return $query->where('type', 'unsecured');
    }

    /**
     * Scope for religious obligations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReligious($query)
    {
        return $query->where('type', 'religious');
    }

    /**
     * Scope for overdue debts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
                    ->whereDate('due_date', '<', now()->toDateString());
    }

    /**
     * Scope for debts due soon.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDueSoon($query, int $days = 30)
    {
        return $query->whereNotNull('due_date')
                    ->whereDate('due_date', '>=', now()->toDateString())
                    ->whereDate('due_date', '<=', now()->addDays($days)->toDateString());
    }

    /**
     * Scope for debts with documents.
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
     * Scope for high-value debts (above threshold).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  float  $threshold
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighValue($query, float $threshold = 10000)
    {
        return $query->where('amount', '>=', $threshold);
    }

    /**
     * Scope for debts with description.
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
     * Scope for debts by creditor name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $creditorName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCreditor($query, string $creditorName)
    {
        return $query->where('creditor_name', 'LIKE', "%{$creditorName}%");
    }

    /**
     * Scope for debts search.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('creditor_name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('debt_type', 'LIKE', "%{$search}%")
              ->orWhere('reference_number', 'LIKE', "%{$search}%")
              ->orWhere('creditor_contact', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope for debts with no due date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoDueDate($query)
    {
        return $query->whereNull('due_date');
    }

    /**
     * Scope for recently updated debts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentlyUpdated($query, int $days = 30)
    {
        return $query->where('updated_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for priority debts (overdue, religious, or government).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriority($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($sq) {
                $sq->whereNotNull('due_date')
                   ->whereDate('due_date', '<', now()->toDateString());
            })
            ->orWhere('type', 'religious')
            ->orWhere('type', 'government');
        });
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
        static::creating(function ($debt) {
            // Set default values
            if (empty($debt->amount)) {
                $debt->amount = 0;
            }

            // Log creation
            Log::info('Debt being created', [
                'creditor_name' => $debt->creditor_name,
                'amount' => $debt->amount,
                'estate_id' => $debt->estate_pre_registration_id,
            ]);
        });

        static::updating(function ($debt) {
            // Log amount changes
            if ($debt->isDirty('amount')) {
                Log::info('Debt amount changed', [
                    'debt_id' => $debt->id,
                    'creditor' => $debt->creditor_name,
                    'old_amount' => $debt->getOriginal('amount'),
                    'new_amount' => $debt->amount,
                ]);
            }

            // Log due date changes
            if ($debt->isDirty('due_date')) {
                Log::info('Debt due date changed', [
                    'debt_id' => $debt->id,
                    'creditor' => $debt->creditor_name,
                    'old_due_date' => $debt->getOriginal('due_date'),
                    'new_due_date' => $debt->due_date,
                ]);
            }
        });

        static::deleting(function ($debt) {
            // Log deletion
            Log::info('Debt being deleted', [
                'debt_id' => $debt->id,
                'creditor_name' => $debt->creditor_name,
                'amount' => $debt->amount,
                'estate_id' => $debt->estate_pre_registration_id,
            ]);
        });

        static::saved(function ($debt) {
            // Clear cache or perform post-save actions if needed
        });
    }
}