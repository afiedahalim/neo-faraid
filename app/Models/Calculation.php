<?php
// app/Models/Calculation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class Calculation extends Model
{
    use HasFactory;

    protected $table = 'calculations';

    protected $fillable = [
        'user_id',
        'deceased_name',
        'deceased_nric',
        'deceased_gender',
        'date_of_death',
        'marital_status',
        'cause_of_death',
        'death_place',
        'contact_email',
        'contact_phone',
        'residential_address',
        'wife_count',
        'husband_count',
        'father_status',
        'mother_status',
        'fathers_father_status',
        'fathers_mother_status',
        'mothers_mother_status',
        'son_count',
        'daughter_count',
        'full_brother_count',
        'full_sister_count',
        'paternal_brother_count',
        'paternal_sister_count',
        'maternal_brother_count',
        'maternal_sister_count',
        'total_assets',
        'net_assets',
        'total_heirs',
        'eligible_heirs_count',
        'scenario_number',
        'faraid_scenario_id',
        'scenario_description',
        'calculation_method',
        'heirs_data',
        'assets_data',
        'calculation_data',
        'distribution_summary',
        'chart_data',
        'tree_data',
        'scenario_data',
        'scenario_rules_applied',
        'tree_generation_status',
        'tree_generation_attempted_at',
        'family_tree_image',
        'tree_generated_at',
        'tree_generation_error',
        'share_token',
        'share_expires_at',
        'graphviz_data',
        'calculation_hash',
    ];

    protected $casts = [
        'date_of_death' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'share_expires_at' => 'datetime',
        'tree_generation_attempted_at' => 'datetime',
        'tree_generated_at' => 'datetime',
        'total_assets' => 'decimal:2',
        'net_assets' => 'decimal:2',
        'heirs_data' => 'array',
        'assets_data' => 'array',
        'calculation_data' => 'array',
        'distribution_summary' => 'array',
        'chart_data' => 'array',
        'tree_data' => 'array',
        'scenario_data' => 'array',
        'scenario_rules_applied' => 'array',
        'graphviz_data' => 'array',
    ];

    protected $appends = [
        'masked_nric',
        'full_nric',
        'display_nric',
        'share_url',
        'total_distributed',
        'heirs_list',
        'formatted_total_assets',
        'formatted_net_assets',
        'formatted_date',
        'formatted_date_of_death',
        'has_family_tree',
        'tree_generated_date',
        'detailed_scenario_description',
        'distribution_summary_decoded',
        'tree_status_badge',
        'can_regenerate_tree',
        'has_graphviz_tree',
        'graphviz_tree_url',
        'graphviz_tree_preview',
        'family_tree_exists',
        'heirs_count',
        'faraid_scenario_info',
        'calculation_method_badge',
        'scenario_rules_preview',
        'database_scenario_name',
        'calculation_method_display',
        'scenario_with_rules',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user who created this calculation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the faraid scenario rule used
     */
    public function faraidScenario()
    {
        return $this->belongsTo(FaraidCalculationRule::class, 'faraid_scenario_id');
    }

    /**
     * Get shares for this calculation
     */
    public function shares()
    {
        return $this->hasMany(CalculationShare::class);
    }

    // ==================== MUTATORS ====================

    /**
     * Set date_of_death attribute
     */
    public function setDateOfDeathAttribute($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            $this->attributes['date_of_death'] = $value->format('Y-m-d');
        } elseif (is_string($value)) {
            try {
                $date = Carbon::parse($value);
                $this->attributes['date_of_death'] = $date->format('Y-m-d');
            } catch (\Exception $e) {
                $this->attributes['date_of_death'] = $value;
            }
        } else {
            $this->attributes['date_of_death'] = $value;
        }
    }

    /**
     * Set scenario_number attribute and auto-link to FaraidCalculationRule
     */
    public function setScenarioNumberAttribute($value)
    {
        $this->attributes['scenario_number'] = $value;
        
        if ($value && class_exists(FaraidCalculationRule::class)) {
            $scenario = FaraidCalculationRule::where('scenario_number', $value)->first();
            if ($scenario) {
                $this->attributes['faraid_scenario_id'] = $scenario->id;
                $this->attributes['scenario_description'] = $scenario->scenario_name;
            }
        }
    }

    // ==================== ACCESSORS ====================

    /**
     * Format deceased NRIC for display (masked)
     */
    public function getMaskedNricAttribute(): string
    {
        if (empty($this->deceased_nric)) {
            return 'Not Provided';
        }
        
        $nric = trim($this->deceased_nric);
        
        // If NRIC has dashes or spaces, clean it first
        $cleanNric = preg_replace('/[^0-9]/', '', $nric);
        
        if (strlen($cleanNric) >= 8) {
            $first = substr($cleanNric, 0, 6);
            $last = substr($cleanNric, -2);
            return $first . '****' . $last;
        }
        
        if (strlen($nric) >= 8) {
            $first = substr($nric, 0, 6);
            $last = substr($nric, -2);
            return $first . '****' . $last;
        }
        
        // If NRIC is short, show first 4 chars only
        return substr($nric, 0, 4) . '****';
    }

    /**
     * Get full NRIC (unmasked)
     */
    public function getFullNricAttribute(): string
    {
        return $this->deceased_nric ?? 'Not Provided';
    }

    /**
     * Get formatted NRIC for display with toggle icon
     */
    public function getDisplayNricAttribute(): string
    {
        if (empty($this->deceased_nric)) {
            return '<span class="nric-badge no-nric">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="12" height="12">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        No NRIC
                    </span>';
        }
        
        return '<div class="nric-display">
                    <span class="nric-masked" data-full-nric="' . e($this->deceased_nric) . '" onclick="toggleNricDisplay(this)">
                        ' . e($this->masked_nric) . '
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="12" height="12" style="display: inline; margin-left: 4px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </span>
                </div>';
    }

    /**
     * Get formatted date of death
     */
    public function getFormattedDateOfDeathAttribute()
    {
        if (!$this->date_of_death) return null;
        
        try {
            return Carbon::parse($this->date_of_death)->format('d M Y');
        } catch (\Exception $e) {
            return $this->date_of_death;
        }
    }

    /**
     * Get formatted total assets
     */
    public function getFormattedTotalAssetsAttribute()
    {
        return 'RM ' . number_format($this->total_assets, 2);
    }

    /**
     * Get formatted net assets
     */
    public function getFormattedNetAssetsAttribute()
    {
        return 'RM ' . number_format($this->net_assets, 2);
    }

    /**
     * Get formatted creation date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y, h:i A') : 'N/A';
    }

    /**
     * Get total distributed amount from distribution summary
     */
    public function getTotalDistributedAttribute(): float
    {
        return $this->distribution_summary['total_distributed'] ?? 0;
    }

    /**
     * Get heirs list from distribution summary
     */
    public function getHeirsListAttribute(): array
    {
        return $this->distribution_summary['heirs'] ?? [];
    }

    /**
     * Get shareable URL
     */
    public function getShareUrlAttribute(): ?string
    {
        if ($this->share_token && $this->share_expires_at && $this->share_expires_at->isFuture()) {
            return route('calculator.shared', $this->share_token);
        }
        return null;
    }

    /**
     * Check if has family tree image
     */
    public function getHasFamilyTreeAttribute()
    {
        return !empty($this->family_tree_image) && $this->family_tree_image !== '';
    }

    /**
     * Get tree generated date formatted
     */
    public function getTreeGeneratedDateAttribute()
    {
        return $this->tree_generated_at 
            ? $this->tree_generated_at->format('d M Y, h:i A')
            : null;
    }

    /**
     * Get heirs count from heirs_data
     */
    public function getHeirsCountAttribute()
    {
        if (is_array($this->heirs_data)) {
            return count($this->heirs_data);
        }
        return 0;
    }

    /**
     * Get eligible heirs count from distribution summary
     */
    public function getEligibleHeirsCountAttribute()
    {
        // Check if we have a pre-calculated value
        if (isset($this->attributes['eligible_heirs_count']) && $this->attributes['eligible_heirs_count'] > 0) {
            return (int) $this->attributes['eligible_heirs_count'];
        }
        
        $distribution = $this->distribution_summary_decoded['heirs'] ?? [];
        
        if (empty($distribution)) {
            return 0;
        }
        
        $eligibleHeirs = array_filter($distribution, function($heir) {
            $amount = $heir['amount'] ?? 0;
            $type = $heir['type'] ?? '';
            return $amount > 0.01 && $type !== 'Surplus';
        });
        
        return count($eligibleHeirs);
    }

    /**
     * Get detailed scenario description
     */
    public function getDetailedScenarioDescriptionAttribute()
    {
        if ($this->faraid_scenario_id && $this->faraidScenario) {
            return $this->faraidScenario->scenario_description;
        }
        
        if (is_array($this->scenario_data) && isset($this->scenario_data['description'])) {
            return $this->scenario_data['description'];
        }
        
        $scenario = $this->scenario_number ?? 0;
        $descriptions = [
            1 => "Scenario 1: Spouse Only - Deceased leaves only spouse with no children, parents, or siblings",
            2 => "Scenario 2: Spouse and Parents (No Children) - Deceased leaves spouse and parents with no children or siblings",
            3 => "Scenario 3: Spouse and Children - Deceased leaves spouse and children with no parents or siblings",
            4 => "Scenario 4: Children Only - Deceased leaves only children with no spouse, parents, or siblings",
            5 => "Scenario 5: Parents and Children - Deceased leaves parents and children with no spouse or siblings",
            6 => "Scenario 6: Parents Only (No Children) - Deceased leaves only parents with no spouse, children, or siblings",
            7 => "Scenario 7: Spouse and Parents - Deceased leaves spouse and parents with no children",
            8 => "Scenario 8: Siblings Only - Deceased leaves siblings with no parents, children, or spouse",
            9 => "Scenario 9: Maternal and Half Siblings - Deceased leaves maternal half-siblings",
            10 => "Scenario 10: Blocking (Mahjub) - Some heirs are blocked from inheritance",
            11 => "Scenario 11: Extended Heirs (Asabah) - Inheritance passes to extended male relatives",
            12 => "Scenario 12: Awl (Over-Subscription) - Total shares exceed 1, requiring proportional reduction",
            13 => "Scenario 13: Surplus Estate - Remaining estate goes to Baitulmal",
            14 => "Scenario 14: Multiple Wives - Distribution with multiple wives",
            15 => "Standard inheritance distribution according to Faraid rules"
        ];
        
        return $descriptions[$scenario] ?? "Standard inheritance distribution according to Faraid rules";
    }

    /**
     * Get distribution summary decoded (always returns array)
     */
    public function getDistributionSummaryDecodedAttribute()
    {
        if (is_array($this->distribution_summary)) {
            return $this->distribution_summary;
        }
        
        if (is_string($this->distribution_summary)) {
            $decoded = json_decode($this->distribution_summary, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        
        // If no distribution_summary exists, calculate it
        if ($this->net_assets > 0) {
            try {
                $heirsData = $this->extractHeirsDataFromModel();
                $netEstate = $this->net_assets;
                
                // Use controller's calculation method
                if (class_exists(\App\Http\Controllers\CalculationController::class)) {
                    $controller = new \App\Http\Controllers\CalculationController();
                    $distributionSummary = $controller->calculateDistributionSummaryPublic($heirsData, $netEstate);
                    
                    // Save it back to the model
                    $this->update(['distribution_summary' => $distributionSummary]);
                    $this->refresh();
                    
                    return $distributionSummary;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to decode distribution summary: ' . $e->getMessage());
            }
        }
        
        // Return empty structure as fallback
        return [
            'total_eligible' => 0,
            'total_distributed' => 0,
            'total_heirs' => 0,
            'net_estate' => $this->net_assets,
            'calculation_method' => $this->calculation_method ?? 'unknown',
            'scenario_source' => 'unknown',
            'heirs' => []
        ];
    }

    /**
     * Get faraid scenario info
     */
    public function getFaraidScenarioInfoAttribute()
    {
        if ($this->faraid_scenario_id && $this->faraidScenario) {
            return [
                'id' => $this->faraidScenario->id,
                'scenario_number' => $this->faraidScenario->scenario_number,
                'name' => $this->faraidScenario->scenario_name,
                'description' => $this->faraidScenario->scenario_description,
                'priority' => $this->faraidScenario->priority,
                'conditions' => $this->faraidScenario->conditions,
                'rules' => $this->faraidScenario->distribution_rules,
                'is_active' => $this->faraidScenario->is_active,
                'calculation_logic' => $this->faraidScenario->calculation_logic
            ];
        }
        
        return null;
    }

    /**
     * Get calculation method badge HTML
     */
    public function getCalculationMethodBadgeAttribute()
    {
        $method = $this->calculation_method ?? 'local';
        $badges = [
            'database' => '<span class="badge bg-success">Database Rules</span>',
            'local' => '<span class="badge bg-info">Local Calculation</span>',
            'api' => '<span class="badge bg-warning">API Calculation</span>'
        ];
        
        return $badges[$method] ?? '<span class="badge bg-secondary">' . ucfirst($method) . '</span>';
    }

    /**
     * Get calculation method display name
     */
    public function getCalculationMethodDisplayAttribute()
    {
        $methods = [
            'local' => 'Local Calculation',
            'database' => 'Database Scenario',
            'api' => 'API Calculation'
        ];
        
        return $methods[$this->calculation_method] ?? ucfirst($this->calculation_method);
    }

    /**
     * Get scenario rules preview
     */
    public function getScenarioRulesPreviewAttribute()
    {
        if ($this->scenario_rules_applied && is_array($this->scenario_rules_applied)) {
            $rules = $this->scenario_rules_applied;
            $preview = '';
            
            if (isset($rules['spouse'])) {
                $preview .= "Spouse: {$rules['spouse']}\n";
            }
            if (isset($rules['father'])) {
                $preview .= "Father: {$rules['father']}\n";
            }
            if (isset($rules['mother'])) {
                $preview .= "Mother: {$rules['mother']}\n";
            }
            if (isset($rules['children'])) {
                $preview .= "Children: {$rules['children']}\n";
            }
            
            return $preview;
        }
        
        return 'No specific rules applied';
    }

    /**
     * Get scenario with rules
     */
    public function getScenarioWithRulesAttribute()
    {
        if ($this->faraidScenario) {
            return [
                'name' => $this->faraidScenario->scenario_name,
                'description' => $this->faraidScenario->scenario_description,
                'rules' => json_decode($this->faraidScenario->distribution_rules, true),
                'logic' => $this->faraidScenario->calculation_logic,
                'source' => 'database'
            ];
        }
        
        return [
            'name' => $this->scenario_description,
            'description' => $this->getDetailedScenarioDescriptionAttribute(),
            'rules' => $this->scenario_rules_applied ?? [],
            'logic' => 'Standard Faraid rules applied',
            'source' => 'local'
        ];
    }

    /**
     * Get database scenario name
     */
    public function getDatabaseScenarioNameAttribute()
    {
        if ($this->faraidScenario) {
            return $this->faraidScenario->scenario_name;
        }
        
        return $this->scenario_description ?? 'Custom Scenario';
    }

    /**
     * Get tree status badge HTML
     */
    public function getTreeStatusBadgeAttribute()
    {
        $status = $this->tree_generation_status ?? 'pending';
        $badges = [
            'pending' => '<span class="badge bg-secondary">Pending</span>',
            'processing' => '<span class="badge bg-warning text-dark">Processing</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>'
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Check if tree can be regenerated
     */
    public function getCanRegenerateTreeAttribute()
    {
        if ($this->tree_generation_status === 'failed') {
            return true;
        }
        
        if ($this->tree_generation_status === 'pending' || $this->tree_generation_status === 'processing') {
            if ($this->tree_generation_attempted_at) {
                return $this->tree_generation_attempted_at->diffInMinutes(now()) > 5;
            }
            return true;
        }
        
        if ($this->tree_generation_status === 'completed' && $this->tree_generated_at) {
            return $this->tree_generated_at->diffInHours(now()) > 24;
        }
        
        return false;
    }

    /**
     * Check if has Graphviz tree data
     */
    public function getHasGraphvizTreeAttribute()
    {
        return !empty($this->tree_data);
    }

    /**
     * Get Graphviz tree URL
     */
    public function getGraphvizTreeUrlAttribute()
    {
        if (!$this->family_tree_image) {
            return null;
        }
        
        if (strpos($this->family_tree_image, 'graphviz') !== false) {
            return $this->getFamilyTreeUrl();
        }
        
        return null;
    }

    /**
     * Get Graphviz tree preview
     */
    public function getGraphvizTreePreviewAttribute()
    {
        $treeData = $this->tree_data;
        
        if (is_array($treeData)) {
            $treeData = json_encode($treeData, JSON_PRETTY_PRINT);
        }
        
        if (!$treeData) {
            return null;
        }
        
        return substr($treeData, 0, 500) . 
               (strlen($treeData) > 500 ? '...' : '');
    }

    /**
     * Check if family tree file exists
     */
    public function getFamilyTreeExistsAttribute()
    {
        $path = $this->getFamilyTreePath();
        return $path && file_exists($path);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Extract heirs data from model attributes
     */
    private function extractHeirsDataFromModel()
    {
        return [
            'husband_count' => $this->husband_count ?? 0,
            'wife_count' => $this->wife_count ?? 0,
            'father_status' => $this->father_status ?? 'none',
            'mother_status' => $this->mother_status ?? 'none',
            'son_count' => $this->son_count ?? 0,
            'daughter_count' => $this->daughter_count ?? 0,
            'full_brother_count' => $this->full_brother_count ?? 0,
            'full_sister_count' => $this->full_sister_count ?? 0,
            'paternal_brother_count' => $this->paternal_brother_count ?? 0,
            'paternal_sister_count' => $this->paternal_sister_count ?? 0,
            'maternal_brother_count' => $this->maternal_brother_count ?? 0,
            'maternal_sister_count' => $this->maternal_sister_count ?? 0,
            'fathers_father_status' => $this->fathers_father_status ?? 'none',
            'fathers_mother_status' => $this->fathers_mother_status ?? 'none',
            'mothers_mother_status' => $this->mothers_mother_status ?? 'none',
        ];
    }

    /**
     * Get heir share from distribution
     */
    private function getHeirShare($heirType, $distribution, $index = null)
    {
        if (empty($distribution)) {
            return '';
        }
        
        $searchType = strtolower($heirType);
        
        foreach ($distribution as $heir) {
            $heirName = strtolower($heir['name'] ?? '');
            $relationship = strtolower($heir['relationship'] ?? '');
            
            $isMatch = false;
            
            if ($index !== null && $index > 1) {
                $pattern = $searchType . '[\s_]*' . $index;
                $isMatch = preg_match("/$pattern/", $heirName) || 
                          preg_match("/$pattern/", $relationship);
            } else {
                $isMatch = str_contains($heirName, $searchType) || 
                          str_contains($relationship, $searchType);
            }
            
            if ($isMatch) {
                $share = $heir['share'] ?? 'Unknown';
                $amount = $heir['formatted_amount'] ?? 'RM 0.00';
                $status = isset($heir['status']) ? "\\n({$heir['status']})" : '';
                return "\\n$share\\n$amount$status";
            }
        }
        
        return '';
    }

    /**
     * Get family tree file path
     */
    public function getFamilyTreePath()
    {
        if (!$this->family_tree_image) {
            return null;
        }
        
        if (strpos($this->family_tree_image, 'http') === 0) {
            $path = parse_url($this->family_tree_image, PHP_URL_PATH);
            $path = str_replace('/storage/', '', $path);
            return storage_path('app/public/' . $path);
        }
        
        return storage_path('app/public/' . ltrim($this->family_tree_image, '/'));
    }

    /**
     * Get family tree URL
     */
    public function getFamilyTreeUrl()
    {
        if (!$this->family_tree_image) {
            return null;
        }
        
        if (strpos($this->family_tree_image, 'http') === 0) {
            return $this->family_tree_image;
        }
        
        return asset('storage/' . ltrim($this->family_tree_image, '/'));
    }

    // ==================== PUBLIC METHODS ====================

    /**
     * Generate a unique hash for this calculation
     */
    public static function generateHash(): string
    {
        return 'CALC_' . time() . '_' . bin2hex(random_bytes(4));
    }

    /**
     * Check if calculation has valid NRIC (lenient - just checks if not empty)
     */
    public function hasValidNric(): bool
    {
        return !empty($this->deceased_nric) && trim($this->deceased_nric) !== '';
    }

    /**
     * Check if calculation has valid NRIC (strict - checks minimum 12 digits)
     */
    public function hasValidStrictNric(): bool
    {
        return !empty($this->deceased_nric) && strlen(preg_replace('/[^0-9]/', '', $this->deceased_nric)) >= 12;
    }

    /**
     * Check if share link is active
     */
    public function isShareActive(): bool
    {
        return !empty($this->share_token) && $this->share_expires_at && $this->share_expires_at->isFuture();
    }

    /**
     * Check if calculation has distribution
     */
    public function hasDistribution(): bool
    {
        return !empty($this->distribution_summary) && 
               isset($this->distribution_summary['heirs']) && 
               count($this->distribution_summary['heirs']) > 0;
    }

    /**
     * Cleanup tree file
     */
    public function cleanupTreeFile()
    {
        $path = $this->getFamilyTreePath();
        
        if ($path && file_exists($path)) {
            try {
                unlink($path);
                $this->update([
                    'family_tree_image' => null,
                    'tree_generated_at' => null,
                    'tree_generation_status' => 'pending'
                ]);
                return true;
            } catch (\Exception $e) {
                \Log::error('Failed to delete tree file: ' . $e->getMessage());
                return false;
            }
        }
        return true;
    }

    /**
     * Get eligible heirs from distribution
     */
    public function getEligibleHeirs()
    {
        $distribution = $this->distribution_summary_decoded['heirs'] ?? [];
        
        if (empty($distribution)) {
            return [];
        }
        
        return array_filter($distribution, function($heir) {
            $amount = $heir['amount'] ?? 0;
            $type = $heir['type'] ?? '';
            return $amount > 0.01 && $type !== 'Surplus';
        });
    }

    /**
     * Mark tree as processing
     */
    public function markTreeProcessing()
    {
        $this->update([
            'tree_generation_status' => 'processing',
            'tree_generation_attempted_at' => now(),
            'tree_generation_error' => null
        ]);
    }

    /**
     * Mark tree as completed
     */
    public function markTreeCompleted($imagePath)
    {
        $this->update([
            'tree_generation_status' => 'completed',
            'family_tree_image' => $imagePath,
            'tree_generated_at' => now(),
            'tree_generation_error' => null
        ]);
    }

    /**
     * Mark tree as failed
     */
    public function markTreeFailed($errorMessage)
    {
        $this->update([
            'tree_generation_status' => 'failed',
            'tree_generation_error' => $errorMessage
        ]);
    }

    /**
     * Get Graphviz download URL
     */
    public function getGraphvizDownloadUrl($format = 'svg')
    {
        return route('calculator.download-graphviz', ['calculation' => $this->id, 'format' => $format]);
    }

    /**
     * Get Graphviz generation URL
     */
    public function getGraphvizGenerationUrl()
    {
        return route('calculator.generate-graphviz', $this->id);
    }

    /**
     * Get scenario admin URL
     */
    public function getScenarioAdminUrl()
    {
        if ($this->faraid_scenario_id) {
            return route('admin.faraid-scenarios.edit', $this->faraid_scenario_id);
        }
        return null;
    }

    /**
     * Get calculation stats
     */
    public static function getCalculationStats($userId = null)
    {
        $query = self::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $totalCalculations = $query->count();
        $databaseCalculations = $query->clone()->where('calculation_method', 'database')->count();
        $localCalculations = $query->clone()->where('calculation_method', 'local')->count();
        $apiCalculations = $query->clone()->where('calculation_method', 'api')->count();
        
        return [
            'total_calculations' => $totalCalculations,
            'database_scenarios' => $query->clone()->whereNotNull('faraid_scenario_id')->count(),
            'database_calculations' => $databaseCalculations,
            'local_calculations' => $localCalculations,
            'api_calculations' => $apiCalculations,
            'with_trees' => $query->clone()->whereNotNull('family_tree_image')->count(),
            'recent_calculations' => $query->clone()->where('created_at', '>=', now()->subDays(7))->count(),
            'scenario_breakdown' => $query->clone()->selectRaw('scenario_number, count(*) as count')
                ->groupBy('scenario_number')
                ->get()
                ->pluck('count', 'scenario_number')
                ->toArray(),
            'method_breakdown' => [
                'database' => $databaseCalculations,
                'local' => $localCalculations,
                'api' => $apiCalculations
            ]
        ];
    }

    /**
     * Get distribution breakdown for display
     */
    public function getDistributionBreakdown()
    {
        $distribution = $this->distribution_summary_decoded['heirs'] ?? [];
        
        if (empty($distribution)) {
            return [
                'total' => 0,
                'breakdown' => [],
                'summary' => []
            ];
        }

        $breakdown = [];
        $summary = [
            'fixed_shares' => 0,
            'asabah' => 0,
            'surplus' => 0,
            'blocked' => 0
        ];

        foreach ($distribution as $heir) {
            $amount = $heir['amount'] ?? 0;
            $type = $heir['type'] ?? 'Unknown';
            $status = $heir['status'] ?? 'Unknown';
            
            $breakdown[] = [
                'heir' => $heir['heir'] ?? $heir['name'] ?? 'Unknown',
                'relationship' => $heir['relationship'] ?? 'Unknown',
                'share' => $heir['share'] ?? '0',
                'amount' => $amount,
                'formatted_amount' => $heir['formatted_amount'] ?? 'RM ' . number_format($amount, 2),
                'percentage' => $heir['percentage'] ?? 0,
                'type' => $type,
                'status' => $status
            ];

            // Update summary
            if ($type === 'Fixed Share') {
                $summary['fixed_shares'] += $amount;
            } elseif ($type === 'Asabah') {
                $summary['asabah'] += $amount;
            } elseif ($type === 'Surplus') {
                $summary['surplus'] += $amount;
            }
        }

        return [
            'total' => $this->distribution_summary_decoded['total_distributed'] ?? 0,
            'breakdown' => $breakdown,
            'summary' => $summary
        ];
    }

    /**
     * Check if calculation is using database scenario
     */
    public function isUsingDatabaseScenario()
    {
        return $this->calculation_method === 'database' && $this->faraid_scenario_id !== null;
    }

    /**
     * Get calculation details for display
     */
    public function getCalculationDetails()
    {
        return [
            'id' => $this->id,
            'deceased_name' => $this->deceased_name,
            'date_of_death' => $this->formatted_date_of_death,
            'gender' => ucfirst($this->deceased_gender),
            'marital_status' => ucfirst($this->marital_status),
            'total_assets' => $this->formatted_total_assets,
            'net_assets' => $this->formatted_net_assets,
            'total_heirs' => $this->total_heirs,
            'eligible_heirs' => $this->eligible_heirs_count,
            'scenario' => [
                'number' => $this->scenario_number,
                'name' => $this->scenario_description,
                'description' => $this->detailed_scenario_description,
                'method' => $this->calculation_method_display
            ],
            'calculation_method' => $this->calculation_method,
            'calculation_hash' => $this->calculation_hash,
            'created_at' => $this->formatted_date,
            'has_family_tree' => $this->has_family_tree,
            'tree_status' => $this->tree_generation_status,
            'tree_generated_at' => $this->tree_generated_date
        ];
    }

    /**
     * Validate calculation data integrity
     */
    public function validateDataIntegrity()
    {
        $errors = [];

        // Check required fields
        $requiredFields = [
            'deceased_name',
            'deceased_gender',
            'date_of_death',
            'marital_status',
            'total_assets',
            'net_assets'
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                $errors[] = "Missing required field: {$field}";
            }
        }

        // Check JSON fields
        $jsonFields = [
            'heirs_data',
            'distribution_summary',
            'calculation_data'
        ];

        foreach ($jsonFields as $field) {
            if (!empty($this->$field) && !is_array($this->$field)) {
                if (is_string($this->$field)) {
                    $decoded = json_decode($this->$field, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $errors[] = "Invalid JSON in field: {$field}";
                    }
                } else {
                    $errors[] = "Invalid data type in field: {$field}";
                }
            }
        }

        // Check financial consistency
        if ($this->net_assets > $this->total_assets) {
            $errors[] = "Net assets cannot be greater than total assets";
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'warnings' => []
        ];
    }

    // ==================== SCOPES ====================

    /**
     * Scope for searching
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('deceased_name', 'like', "%{$search}%")
              ->orWhere('deceased_nric', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%")
              ->orWhere('calculation_hash', 'like', "%{$search}%")
              ->orWhere('scenario_description', 'like', "%{$search}%")
              ->orWhereHas('user', function($sub) use ($search) {
                  $sub->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Scope for filtering by NRIC
     */
    public function scopeWhereNric($query, $nric)
    {
        if (empty($nric)) {
            return $query;
        }
        
        return $query->where('deceased_nric', 'like', "%{$nric}%");
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        
        return $query;
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for calculations with family tree
     */
    public function scopeHasFamilyTree($query)
    {
        return $query->whereNotNull('family_tree_image')
                    ->where('family_tree_image', '!=', '');
    }

    /**
     * Scope for calculations with Graphviz tree
     */
    public function scopeHasGraphvizTree($query)
    {
        return $query->whereNotNull('tree_data')
                    ->where('tree_data', '!=', '');
    }

    /**
     * Scope for recent calculations
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for tree status
     */
    public function scopeWithTreeStatus($query, $status)
    {
        return $query->where('tree_generation_status', $status);
    }

    /**
     * Scope for database scenarios
     */
    public function scopeUsingDatabaseScenarios($query)
    {
        return $query->whereNotNull('faraid_scenario_id')
                    ->where('calculation_method', 'database');
    }

    /**
     * Scope for specific scenario number
     */
    public function scopeWithScenarioNumber($query, $scenarioNumber)
    {
        return $query->where('scenario_number', $scenarioNumber);
    }

    /**
     * Scope for database calculations
     */
    public function scopeDatabaseCalculations($query)
    {
        return $query->where('calculation_method', 'database');
    }

    /**
     * Scope for local calculations
     */
    public function scopeLocalCalculations($query)
    {
        return $query->where('calculation_method', 'local');
    }

    // ==================== EVENT HANDLERS ====================

    /**
     * Boot the model
     */
    protected static function booted()
    {
        static::creating(function ($calculation) {
            if (empty($calculation->calculation_hash)) {
                $calculation->calculation_hash = self::generateHash();
            }
            
            if (empty($calculation->tree_generation_status)) {
                $calculation->tree_generation_status = 'pending';
            }
            
            if (empty($calculation->calculation_method)) {
                $calculation->calculation_method = 'local';
            }
            
            if ($calculation->date_of_death && !is_string($calculation->date_of_death)) {
                try {
                    $calculation->date_of_death = Carbon::parse($calculation->date_of_death)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Keep original value if parsing fails
                }
            }
            
            // Ensure net_assets equals total_assets since no deductions
            if (empty($calculation->net_assets) && !empty($calculation->total_assets)) {
                $calculation->net_assets = $calculation->total_assets;
            }

            // Ensure eligible_heirs_count is set
            if (empty($calculation->eligible_heirs_count)) {
                $calculation->eligible_heirs_count = 0;
            }
        });

        static::updating(function ($calculation) {
            // Update eligible_heirs_count if distribution_summary changes
            if ($calculation->isDirty('distribution_summary')) {
                $distribution = $calculation->distribution_summary;
                if (is_string($distribution)) {
                    $distribution = json_decode($distribution, true);
                }
                
                if (is_array($distribution) && isset($distribution['heirs'])) {
                    $eligibleHeirs = array_filter($distribution['heirs'], function($heir) {
                        $amount = $heir['amount'] ?? 0;
                        $type = $heir['type'] ?? '';
                        return $amount > 0.01 && $type !== 'Surplus';
                    });
                    
                    $calculation->eligible_heirs_count = count($eligibleHeirs);
                }
            }
        });

        static::deleting(function ($calculation) {
            $calculation->cleanupTreeFile();
        });
    }
}