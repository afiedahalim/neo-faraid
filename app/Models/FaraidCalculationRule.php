<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaraidCalculationRule extends Model
{
    protected $table = 'faraid_calculation_rules';
    
    protected $fillable = [
        'scenario_number',
        'scenario_name',
        'scenario_description',
        'priority',
        'conditions',
        'distribution_rules',
        'calculation_logic',
        'is_active'
    ];
    
    protected $casts = [
        'conditions' => 'array',
        'distribution_rules' => 'array',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'scenario_number' => 'integer'
    ];
    
    protected $appends = [
        'formatted_conditions',
        'formatted_rules',
        'usage_count'
    ];
    
    /**
     * Get the calculations that used this scenario
     */
    public function calculations()
    {
        return $this->hasMany(Calculation::class, 'faraid_scenario_id');
    }
    
    /**
     * Get the scenario by number
     */
    public static function findByNumber($number)
    {
        return static::where('scenario_number', $number)->first();
    }
    
    /**
     * Get active scenarios ordered by priority
     */
    public static function getActiveScenarios()
    {
        return static::where('is_active', true)
            ->orderBy('priority')
            ->orderBy('scenario_number')
            ->get();
    }
    
    /**
     * Scope a query to only include active scenarios.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to order by priority.
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority')->orderBy('scenario_number');
    }
    
    /**
     * Get formatted conditions for display
     */
    public function getFormattedConditionsAttribute()
    {
        $conditions = $this->conditions;
        if (is_string($conditions)) {
            $conditions = json_decode($conditions, true);
        }
        
        if (!is_array($conditions)) {
            return 'No conditions defined';
        }
        
        $formatted = [];
        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $formattedValue = implode(', ', array_map(function($item) {
                    return is_array($item) ? json_encode($item) : (string)$item;
                }, $value));
            } elseif (is_bool($value)) {
                $formattedValue = $value ? 'Yes' : 'No';
            } else {
                $formattedValue = (string)$value;
            }
            
            $formatted[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $formattedValue;
        }
        
        return implode(', ', $formatted);
    }
    
    /**
     * Get formatted rules for display
     */
    public function getFormattedRulesAttribute()
    {
        try {
            $rules = $this->distribution_rules;
            
            // If it's already an array from the cast, use it directly
            if (is_string($rules)) {
                $rules = json_decode($rules, true);
            }
            
            if (!is_array($rules)) {
                return 'No rules defined';
            }
            
            $formatted = [];
            foreach ($rules as $key => $value) {
                if (is_array($value)) {
                    $subRules = [];
                    foreach ($value as $subKey => $subValue) {
                        // Safely convert subValue to string
                        if (is_array($subValue)) {
                            $subRules[] = ucfirst($subKey) . ': ' . json_encode($subValue);
                        } elseif (is_bool($subValue)) {
                            $subRules[] = ucfirst($subKey) . ': ' . ($subValue ? 'Yes' : 'No');
                        } elseif (is_null($subValue)) {
                            $subRules[] = ucfirst($subKey) . ': null';
                        } else {
                            $subRules[] = ucfirst($subKey) . ': ' . (string)$subValue;
                        }
                    }
                    $formatted[] = ucfirst($key) . ' [' . implode(', ', $subRules) . ']';
                } else {
                    // Safely convert value to string
                    if (is_bool($value)) {
                        $formattedValue = $value ? 'Yes' : 'No';
                    } elseif (is_null($value)) {
                        $formattedValue = 'null';
                    } else {
                        $formattedValue = (string)$value;
                    }
                    $formatted[] = ucfirst($key) . ': ' . $formattedValue;
                }
            }
            
            return implode('; ', $formatted);
            
        } catch (\Exception $e) {
            \Log::error('Error formatting rules in FaraidCalculationRule: ' . $e->getMessage());
            return 'Error formatting rules';
        }
    }
    
    /**
     * Get usage count (how many calculations used this scenario)
     */
    public function getUsageCountAttribute()
    {
        return $this->calculations()->count();
    }
    
    /**
     * Check if this scenario matches given heir conditions
     */
    public function matchesConditions($heirs, $deceasedGender)
    {
        $conditions = $this->conditions;
        if (is_string($conditions)) {
            $conditions = json_decode($conditions, true);
        }
        
        if (!is_array($conditions)) {
            return false;
        }
        
        foreach ($conditions as $key => $value) {
            switch ($key) {
                case 'spouse_present':
                    $hasSpouse = (($heirs['wife_count'] ?? 0) > 0 || ($heirs['husband_count'] ?? 0) > 0);
                    if ($value !== $hasSpouse) return false;
                    break;
                    
                case 'children_present':
                    $hasChildren = (($heirs['son_count'] ?? 0) > 0 || ($heirs['daughter_count'] ?? 0) > 0);
                    if ($value !== $hasChildren) return false;
                    break;
                    
                case 'parents_present':
                    $hasParents = (
                        (isset($heirs['father_status']) && $heirs['father_status'] === 'alive') || 
                        (isset($heirs['mother_status']) && $heirs['mother_status'] === 'alive')
                    );
                    if ($value !== $hasParents) return false;
                    break;
                    
                case 'siblings_present':
                    $hasSiblings = (
                        ($heirs['full_brother_count'] ?? 0) > 0 ||
                        ($heirs['full_sister_count'] ?? 0) > 0 ||
                        ($heirs['paternal_half_brother_count'] ?? 0) > 0 ||
                        ($heirs['paternal_half_sister_count'] ?? 0) > 0 ||
                        ($heirs['maternal_half_brother_count'] ?? 0) > 0 ||
                        ($heirs['maternal_half_sister_count'] ?? 0) > 0
                    );
                    if ($value !== $hasSiblings) return false;
                    break;
                    
                case 'both_parents':
                    $bothParents = (
                        (isset($heirs['father_status']) && $heirs['father_status'] === 'alive') && 
                        (isset($heirs['mother_status']) && $heirs['mother_status'] === 'alive')
                    );
                    if ($value !== $bothParents) return false;
                    break;
                    
                case 'complex_scenario':
                    // Complex scenario: multiple types of heirs present
                    $heirTypes = 0;
                    if (($heirs['wife_count'] ?? 0) > 0 || ($heirs['husband_count'] ?? 0) > 0) $heirTypes++;
                    if (($heirs['father_status'] ?? 'deceased') === 'alive') $heirTypes++;
                    if (($heirs['mother_status'] ?? 'deceased') === 'alive') $heirTypes++;
                    if (($heirs['son_count'] ?? 0) > 0) $heirTypes++;
                    if (($heirs['daughter_count'] ?? 0) > 0) $heirTypes++;
                    
                    $isComplex = ($heirTypes >= 3);
                    if ($value !== $isComplex) return false;
                    break;
                    
                case 'spouse_count':
                    $spouseCount = ($heirs['wife_count'] ?? 0) + ($heirs['husband_count'] ?? 0);
                    if (is_array($value)) {
                        if (!in_array($spouseCount, $value)) return false;
                    } elseif ($value != $spouseCount) {
                        return false;
                    }
                    break;
                    
                case 'children_count':
                    $childrenCount = ($heirs['son_count'] ?? 0) + ($heirs['daughter_count'] ?? 0);
                    if (is_array($value)) {
                        if (!in_array($childrenCount, $value)) return false;
                    } elseif ($value != $childrenCount) {
                        return false;
                    }
                    break;
                    
                case 'deceased_gender':
                    if (is_array($value)) {
                        if (!in_array($deceasedGender, $value)) return false;
                    } elseif ($value != $deceasedGender) {
                        return false;
                    }
                    break;
                    
                case 'default':
                    // Always matches default scenario
                    break;
            }
        }
        
        return true;
    }
    
    /**
     * Apply this scenario's rules to calculate distribution
     */
    public function calculateDistribution($heirs, $totalEstate, $deceasedGender)
    {
        $rules = $this->distribution_rules;
        if (is_string($rules)) {
            $rules = json_decode($rules, true);
        }
        
        if (!is_array($rules)) {
            return $this->applyStandardRules($heirs, $totalEstate, $deceasedGender);
        }
        
        $distribution = [];
        $remaining = $totalEstate;
        
        // Apply standard distribution rules
        if (isset($rules['standard_distribution'])) {
            $stdRules = $rules['standard_distribution'];
            
            // Spouse calculation
            if ($deceasedGender === 'female' && ($heirs['husband_count'] ?? 0) > 0) {
                $husbandShare = $this->parseShareFraction($stdRules['husband'] ?? '1/2_or_1/4');
                $husbandAmount = $totalEstate * $husbandShare;
                
                $distribution[] = [
                    'heir' => 'Husband',
                    'relationship' => 'Husband',
                    'share' => $this->fractionToString($husbandShare),
                    'fraction' => $husbandShare,
                    'amount' => $husbandAmount,
                    'status' => 'Fixed Share',
                    'notes' => 'Spouse share'
                ];
                $remaining -= $husbandAmount;
            }
            
            if ($deceasedGender === 'male' && ($heirs['wife_count'] ?? 0) > 0) {
                $wifeShare = $this->parseShareFraction($stdRules['wife'] ?? '1/4_or_1/8');
                $totalWifeAmount = $totalEstate * $wifeShare;
                $individualWifeAmount = $totalWifeAmount / ($heirs['wife_count'] ?? 1);
                
                for ($i = 1; $i <= ($heirs['wife_count'] ?? 1); $i++) {
                    $distribution[] = [
                        'heir' => "Wife $i",
                        'relationship' => 'Wife',
                        'share' => $this->fractionToString($wifeShare / ($heirs['wife_count'] ?? 1)),
                        'fraction' => $wifeShare / ($heirs['wife_count'] ?? 1),
                        'amount' => $individualWifeAmount,
                        'status' => 'Fixed Share',
                        'notes' => 'Spouse share'
                    ];
                }
                $remaining -= $totalWifeAmount;
            }
            
            // Parent shares
            if (($heirs['father_status'] ?? 'deceased') === 'alive') {
                $fatherShare = $this->parseShareFraction($stdRules['father'] ?? '1/6');
                $fatherAmount = $totalEstate * $fatherShare;
                $distribution[] = [
                    'heir' => 'Father',
                    'relationship' => 'Father',
                    'share' => $this->fractionToString($fatherShare),
                    'fraction' => $fatherShare,
                    'amount' => $fatherAmount,
                    'status' => 'Fixed Share'
                ];
                $remaining -= $fatherAmount;
            }
            
            if (($heirs['mother_status'] ?? 'deceased') === 'alive') {
                $motherShare = $this->parseShareFraction($stdRules['mother'] ?? '1/6');
                $motherAmount = $totalEstate * $motherShare;
                $distribution[] = [
                    'heir' => 'Mother',
                    'relationship' => 'Mother',
                    'share' => $this->fractionToString($motherShare),
                    'fraction' => $motherShare,
                    'amount' => $motherAmount,
                    'status' => 'Fixed Share'
                ];
                $remaining -= $motherAmount;
            }
            
            // Children calculation
            $totalChildren = ($heirs['son_count'] ?? 0) + ($heirs['daughter_count'] ?? 0);
            if ($totalChildren > 0) {
                $childrenShare = $stdRules['children'] ?? 'residual';
                
                if ($childrenShare === 'residual') {
                    // Distribute remaining among children
                    if ($remaining > 0.01) {
                        $sonUnits = ($heirs['son_count'] ?? 0) * 2;
                        $daughterUnits = ($heirs['daughter_count'] ?? 0);
                        $totalUnits = $sonUnits + $daughterUnits;
                        
                        if ($totalUnits > 0) {
                            $unitValue = $remaining / $totalUnits;
                            
                            for ($i = 1; $i <= ($heirs['son_count'] ?? 0); $i++) {
                                $amount = $unitValue * 2;
                                $distribution[] = [
                                    'heir' => "Son $i",
                                    'relationship' => 'Son',
                                    'share' => 'Asabah',
                                    'fraction' => (2 / $totalUnits),
                                    'amount' => $amount,
                                    'status' => 'Residuary'
                                ];
                                $remaining -= $amount;
                            }
                            
                            for ($i = 1; $i <= ($heirs['daughter_count'] ?? 0); $i++) {
                                $amount = $unitValue;
                                $distribution[] = [
                                    'heir' => "Daughter $i",
                                    'relationship' => 'Daughter',
                                    'share' => 'Asabah',
                                    'fraction' => (1 / $totalUnits),
                                    'amount' => $amount,
                                    'status' => 'Residuary'
                                ];
                                $remaining -= $amount;
                            }
                        }
                    }
                } else {
                    // Fixed share for children
                    $childrenShareFraction = $this->parseShareFraction($childrenShare);
                    $totalChildrenAmount = $totalEstate * $childrenShareFraction;
                    
                    if ($totalChildren > 0) {
                        $individualAmount = $totalChildrenAmount / $totalChildren;
                        for ($i = 1; $i <= ($heirs['son_count'] ?? 0); $i++) {
                            $distribution[] = [
                                'heir' => "Son $i",
                                'relationship' => 'Son',
                                'share' => $this->fractionToString($childrenShareFraction / $totalChildren),
                                'fraction' => $childrenShareFraction / $totalChildren,
                                'amount' => $individualAmount,
                                'status' => 'Fixed Share'
                            ];
                        }
                        for ($i = 1; $i <= ($heirs['daughter_count'] ?? 0); $i++) {
                            $distribution[] = [
                                'heir' => "Daughter $i",
                                'relationship' => 'Daughter',
                                'share' => $this->fractionToString($childrenShareFraction / $totalChildren),
                                'fraction' => $childrenShareFraction / $totalChildren,
                                'amount' => $individualAmount,
                                'status' => 'Fixed Share'
                            ];
                        }
                        $remaining -= $totalChildrenAmount;
                    }
                }
            }
        }
        
        // Any remaining goes to Baitulmal
        if ($remaining > 0.01) {
            $distribution[] = [
                'heir' => 'Baitulmal',
                'relationship' => 'State Treasury',
                'share' => 'Surplus',
                'fraction' => $remaining / $totalEstate,
                'amount' => $remaining,
                'status' => 'Surplus',
                'notes' => 'Remaining estate'
            ];
        }
        
        // Calculate percentages
        foreach ($distribution as &$item) {
            if (isset($item['amount']) && $totalEstate > 0) {
                $item['percentage'] = round(($item['amount'] / $totalEstate) * 100, 2);
            }
        }
        
        return $distribution;
    }
    
    /**
     * Parse share fraction from string
     */
    private function parseShareFraction($shareString)
    {
        if (is_numeric($shareString)) {
            return floatval($shareString);
        }
        
        if (strpos($shareString, '/') !== false) {
            $parts = explode('/', $shareString);
            if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                return $parts[0] / $parts[1];
            }
        }
        
        // Handle special cases
        switch ($shareString) {
            case '1/2_or_1/4':
                return 0.5; // Default to 1/2
            case '1/4_or_1/8':
                return 0.25; // Default to 1/4
            case '1/6_or_1/3':
                return 1/6; // Default to 1/6
            case 'residual':
                return 0; // Will be calculated as residual
            default:
                return 0;
        }
    }
    
    /**
     * Convert fraction to string representation
     */
    private function fractionToString($fraction)
    {
        if ($fraction == 0.5) return '1/2';
        if ($fraction == 0.25) return '1/4';
        if ($fraction == 0.125) return '1/8';
        if (abs($fraction - 1/3) < 0.001) return '1/3';
        if (abs($fraction - 1/6) < 0.001) return '1/6';
        if (abs($fraction - 2/3) < 0.001) return '2/3';
        
        // Try to find a simple fraction
        for ($den = 2; $den <= 12; $den++) {
            for ($num = 1; $num < $den; $num++) {
                if (abs($fraction - $num/$den) < 0.001) {
                    return "$num/$den";
                }
            }
        }
        
        return round($fraction * 100, 2) . '%';
    }
    
    /**
     * Apply standard rules for scenarios without specific implementation
     */
    private function applyStandardRules($heirs, $totalEstate, $deceasedGender)
    {
        return $this->calculateDistribution($heirs, $totalEstate, $deceasedGender);
    }
}