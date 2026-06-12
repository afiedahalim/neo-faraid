<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaraidCalculationRule;
use Illuminate\Support\Facades\Log;

class FaraidCalculationController extends Controller
{
    /**
     * Calculate Faraid inheritance (legacy method)
     */
    public function calculate(Request $request)
    {
        try {
            // Legacy calculation logic
            $data = $request->validate([
                'deceased_gender' => 'required|in:male,female',
                'heirs' => 'required|array',
                'total_estate' => 'required|numeric|min:0'
            ]);
            
            // Perform basic calculation
            $distribution = $this->performBasicCalculation(
                $data['heirs'], 
                $data['total_estate'], 
                $data['deceased_gender']
            );
            
            return response()->json([
                'success' => true,
                'distribution' => $distribution,
                'total_distributed' => array_sum(array_column($distribution, 'amount')),
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Faraid calculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Calculation failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Advanced calculation with scenario detection
     */
    public function calculateAdvanced(Request $request)
    {
        try {
            $data = $request->validate([
                'deceased_gender' => 'required|in:male,female',
                'heirs' => 'required|array',
                'total_estate' => 'required|numeric|min:0',
                'scenario_id' => 'nullable|integer',
                'deceased_name' => 'nullable|string',
                'date_of_death' => 'nullable|date',
                'marital_status' => 'nullable|string'
            ]);
            
            // Get all active scenarios
            $scenarios = FaraidCalculationRule::where('is_active', 1)
                ->orderBy('priority')
                ->get();
            
            // Find matching scenario
            $matchedScenario = null;
            
            if ($request->has('scenario_id') && $request->scenario_id) {
                // Use specified scenario
                $matchedScenario = FaraidCalculationRule::find($request->scenario_id);
            }
            
            if (!$matchedScenario) {
                // Auto-detect scenario
                foreach ($scenarios as $scenario) {
                    if ($this->checkScenarioConditions($scenario->conditions, $data['heirs'], $data['deceased_gender'])) {
                        $matchedScenario = $scenario;
                        break;
                    }
                }
            }
            
            // Default to standard rules if no match
            if (!$matchedScenario) {
                $matchedScenario = FaraidCalculationRule::where('scenario_number', 10)->first();
            }
            
            // Calculate distribution based on scenario
            $distribution = $this->applyScenarioRules(
                $matchedScenario, 
                $data['heirs'], 
                $data['total_estate'], 
                $data['deceased_gender']
            );
            
            // Add scenario information
            $scenarioInfo = [
                'id' => $matchedScenario->id,
                'scenario_number' => $matchedScenario->scenario_number,
                'scenario_name' => $matchedScenario->scenario_name,
                'scenario_description' => $matchedScenario->scenario_description,
                'priority' => $matchedScenario->priority,
                'conditions' => $matchedScenario->conditions,
                'calculation_logic' => $matchedScenario->calculation_logic
            ];
            
            return response()->json([
                'success' => true,
                'scenario' => $scenarioInfo,
                'distribution' => $distribution,
                'total_distributed' => array_sum(array_column($distribution, 'amount')),
                'eligible_heirs' => count(array_filter($distribution, function($item) {
                    return ($item['amount'] ?? 0) > 0;
                })),
                'timestamp' => now()->toDateTimeString(),
                'calculation_method' => 'database_scenario'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Advanced Faraid calculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Advanced calculation failed: ' . $e->getMessage(),
                'fallback_available' => true
            ], 500);
        }
    }
    
    /**
     * Check if heir conditions match scenario conditions
     */
    private function checkScenarioConditions($conditions, $heirs, $deceasedGender)
    {
        try {
            $conditions = is_string($conditions) ? json_decode($conditions, true) : $conditions;
            
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
                        
                    case 'default':
                        // Always matches default scenario
                        break;
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Scenario condition check error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Apply scenario rules to calculate distribution
     */
    private function applyScenarioRules($scenario, $heirs, $totalEstate, $deceasedGender)
    {
        $distribution = [];
        $rules = is_string($scenario->distribution_rules) 
            ? json_decode($scenario->distribution_rules, true) 
            : $scenario->distribution_rules;
        
        try {
            // Apply rules based on scenario number
            switch ($scenario->scenario_number) {
                case 1: // Spouse Only
                    if ($deceasedGender === 'female' && ($heirs['husband_count'] ?? 0) > 0) {
                        $husbandShare = $rules['husband_only']['fraction'] ?? 0.5;
                        $husbandAmount = $totalEstate * $husbandShare;
                        
                        $distribution[] = [
                            'heir' => 'Husband',
                            'relationship' => 'Husband',
                            'share' => $rules['husband_only']['share'] ?? '1/2',
                            'fraction' => $husbandShare,
                            'amount' => $husbandAmount,
                            'status' => 'Fixed Share',
                            'notes' => 'Only spouse heir'
                        ];
                        
                        // Add Baitulmal for remaining
                        $baitulmalAmount = $totalEstate - $husbandAmount;
                        if ($baitulmalAmount > 0.01) {
                            $distribution[] = [
                                'heir' => 'Baitulmal',
                                'relationship' => 'State Treasury',
                                'share' => $rules['husband_only']['baitulmal'] ?? '1/2',
                                'fraction' => 1 - $husbandShare,
                                'amount' => $baitulmalAmount,
                                'status' => 'Surplus',
                                'notes' => 'Remaining estate'
                            ];
                        }
                        
                    } elseif ($deceasedGender === 'male' && ($heirs['wife_count'] ?? 0) > 0) {
                        $wifeShare = $rules['wife_only']['fraction'] ?? 0.25;
                        $totalWifeAmount = $totalEstate * $wifeShare;
                        $individualWifeAmount = $totalWifeAmount / ($heirs['wife_count'] ?? 1);
                        
                        for ($i = 1; $i <= ($heirs['wife_count'] ?? 1); $i++) {
                            $distribution[] = [
                                'heir' => "Wife $i",
                                'relationship' => 'Wife',
                                'share' => $rules['wife_only']['share'] ?? '1/4',
                                'fraction' => $wifeShare / ($heirs['wife_count'] ?? 1),
                                'amount' => $individualWifeAmount,
                                'status' => 'Fixed Share',
                                'notes' => 'Only spouse heir'
                            ];
                        }
                        
                        // Add Baitulmal for remaining
                        $baitulmalAmount = $totalEstate - $totalWifeAmount;
                        if ($baitulmalAmount > 0.01) {
                            $distribution[] = [
                                'heir' => 'Baitulmal',
                                'relationship' => 'State Treasury',
                                'share' => $rules['wife_only']['baitulmal'] ?? '3/4',
                                'fraction' => 1 - $wifeShare,
                                'amount' => $baitulmalAmount,
                                'status' => 'Surplus',
                                'notes' => 'Remaining estate'
                            ];
                        }
                    }
                    break;
                    
                case 2: // Spouse and Parents (No Children)
                    $this->applySpouseParentsRules($distribution, $rules, $heirs, $totalEstate, $deceasedGender);
                    break;
                    
                case 3: // Spouse and Children
                    $this->applySpouseChildrenRules($distribution, $rules, $heirs, $totalEstate, $deceasedGender);
                    break;
                    
                case 4: // Children Only
                    $this->applyChildrenOnlyRules($distribution, $rules, $heirs, $totalEstate);
                    break;
                    
                case 5: // Parents and Children
                    $this->applyParentsChildrenRules($distribution, $rules, $heirs, $totalEstate);
                    break;
                    
                case 6: // Parents Only
                    $this->applyParentsOnlyRules($distribution, $rules, $heirs, $totalEstate);
                    break;
                    
                case 7: // Spouse and Both Parents
                    $this->applySpouseBothParentsRules($distribution, $rules, $heirs, $totalEstate, $deceasedGender);
                    break;
                    
                case 8: // Siblings Only
                    $this->applySiblingsOnlyRules($distribution, $rules, $heirs, $totalEstate);
                    break;
                    
                default:
                    // Apply standard Faraid rules
                    $this->applyStandardFaraidRules($distribution, $rules, $heirs, $totalEstate, $deceasedGender);
                    break;
            }
            
            // Calculate percentages
            foreach ($distribution as &$item) {
                if (isset($item['amount']) && $totalEstate > 0) {
                    $item['percentage'] = ($item['amount'] / $totalEstate) * 100;
                }
            }
            
            return $distribution;
            
        } catch (\Exception $e) {
            Log::error('Apply scenario rules error: ' . $e->getMessage());
            // Fallback to standard calculation
            return $this->performBasicCalculation($heirs, $totalEstate, $deceasedGender);
        }
    }
    
    /**
     * Apply spouse and parents rules (Scenario 2)
     */
    private function applySpouseParentsRules(&$distribution, $rules, $heirs, $totalEstate, $deceasedGender)
    {
        if ($deceasedGender === 'female' && ($heirs['husband_count'] ?? 0) > 0) {
            if (($heirs['father_status'] ?? 'deceased') === 'alive' && ($heirs['mother_status'] ?? 'deceased') !== 'alive') {
                // Husband and Father only
                $husbandAmount = $totalEstate * 0.5;
                $fatherAmount = $totalEstate * 0.5;
                
                $distribution[] = [
                    'heir' => 'Husband',
                    'relationship' => 'Husband',
                    'share' => $rules['husband_father']['husband'] ?? '1/2',
                    'fraction' => 0.5,
                    'amount' => $husbandAmount,
                    'status' => 'Fixed Share'
                ];
                
                $distribution[] = [
                    'heir' => 'Father',
                    'relationship' => 'Father',
                    'share' => $rules['husband_father']['father'] ?? '1/2',
                    'fraction' => 0.5,
                    'amount' => $fatherAmount,
                    'status' => 'Fixed Share'
                ];
                
            } elseif (($heirs['mother_status'] ?? 'deceased') === 'alive' && ($heirs['father_status'] ?? 'deceased') !== 'alive') {
                // Husband and Mother only
                $husbandAmount = $totalEstate * (3/6);
                $motherAmount = $totalEstate * (2/6);
                $baitulmalAmount = $totalEstate * (1/6);
                
                $distribution[] = [
                    'heir' => 'Husband',
                    'relationship' => 'Husband',
                    'share' => $rules['husband_mother']['husband'] ?? '3/6',
                    'fraction' => 3/6,
                    'amount' => $husbandAmount,
                    'status' => 'Fixed Share'
                ];
                
                $distribution[] = [
                    'heir' => 'Mother',
                    'relationship' => 'Mother',
                    'share' => $rules['husband_mother']['mother'] ?? '2/6',
                    'fraction' => 2/6,
                    'amount' => $motherAmount,
                    'status' => 'Fixed Share'
                ];
                
                if ($baitulmalAmount > 0.01) {
                    $distribution[] = [
                        'heir' => 'Baitulmal',
                        'relationship' => 'State Treasury',
                        'share' => $rules['husband_mother']['baitulmal'] ?? '1/6',
                        'fraction' => 1/6,
                        'amount' => $baitulmalAmount,
                        'status' => 'Surplus'
                    ];
                }
            }
        } elseif ($deceasedGender === 'male' && ($heirs['wife_count'] ?? 0) > 0) {
            if (($heirs['father_status'] ?? 'deceased') === 'alive' && ($heirs['mother_status'] ?? 'deceased') !== 'alive') {
                // Wife and Father only
                $wifeShare = 0.25;
                $totalWifeAmount = $totalEstate * $wifeShare;
                $individualWifeAmount = $totalWifeAmount / ($heirs['wife_count'] ?? 1);
                $fatherAmount = $totalEstate * 0.75;
                
                for ($i = 1; $i <= ($heirs['wife_count'] ?? 1); $i++) {
                    $distribution[] = [
                        'heir' => "Wife $i",
                        'relationship' => 'Wife',
                        'share' => $rules['wife_father']['wife'] ?? '1/4',
                        'fraction' => $wifeShare / ($heirs['wife_count'] ?? 1),
                        'amount' => $individualWifeAmount,
                        'status' => 'Fixed Share'
                    ];
                }
                
                $distribution[] = [
                    'heir' => 'Father',
                    'relationship' => 'Father',
                    'share' => $rules['wife_father']['father'] ?? '3/4',
                    'fraction' => 0.75,
                    'amount' => $fatherAmount,
                    'status' => 'Fixed Share'
                ];
                
            } elseif (($heirs['mother_status'] ?? 'deceased') === 'alive' && ($heirs['father_status'] ?? 'deceased') !== 'alive') {
                // Wife and Mother only
                $wifeShare = 3/12;
                $motherShare = 4/12;
                $totalWifeAmount = $totalEstate * $wifeShare;
                $individualWifeAmount = $totalWifeAmount / ($heirs['wife_count'] ?? 1);
                $motherAmount = $totalEstate * $motherShare;
                $baitulmalAmount = $totalEstate * (5/12);
                
                for ($i = 1; $i <= ($heirs['wife_count'] ?? 1); $i++) {
                    $distribution[] = [
                        'heir' => "Wife $i",
                        'relationship' => 'Wife',
                        'share' => $rules['wife_mother']['wife'] ?? '3/12',
                        'fraction' => $wifeShare / ($heirs['wife_count'] ?? 1),
                        'amount' => $individualWifeAmount,
                        'status' => 'Fixed Share'
                    ];
                }
                
                $distribution[] = [
                    'heir' => 'Mother',
                    'relationship' => 'Mother',
                    'share' => $rules['wife_mother']['mother'] ?? '4/12',
                    'fraction' => $motherShare,
                    'amount' => $motherAmount,
                    'status' => 'Fixed Share'
                ];
                
                if ($baitulmalAmount > 0.01) {
                    $distribution[] = [
                        'heir' => 'Baitulmal',
                        'relationship' => 'State Treasury',
                        'share' => $rules['wife_mother']['baitulmal'] ?? '5/12',
                        'fraction' => 5/12,
                        'amount' => $baitulmalAmount,
                        'status' => 'Surplus'
                    ];
                }
            }
        }
    }
    
    // Additional helper methods for other scenarios...
    // (You can add similar methods for other scenarios)
    
    /**
     * Perform basic Faraid calculation (fallback)
     */
    private function performBasicCalculation($heirs, $totalEstate, $deceasedGender)
    {
        $distribution = [];
        $remaining = $totalEstate;
        
        // Spouse calculation
        if (($heirs['husband_count'] ?? 0) > 0) {
            $share = $deceasedGender === 'female' ? 0.5 : 0.25;
            $amount = $totalEstate * $share;
            $distribution[] = [
                'heir' => 'Husband',
                'relationship' => 'Husband',
                'share' => $share === 0.5 ? '1/2' : '1/4',
                'fraction' => $share,
                'amount' => $amount,
                'status' => 'Fixed Share'
            ];
            $remaining -= $amount;
        }
        
        if (($heirs['wife_count'] ?? 0) > 0) {
            $share = $deceasedGender === 'male' ? 0.25 : 0.125;
            $totalAmount = $totalEstate * $share;
            $individualAmount = $totalAmount / ($heirs['wife_count'] ?? 1);
            
            for ($i = 1; $i <= ($heirs['wife_count'] ?? 1); $i++) {
                $distribution[] = [
                    'heir' => "Wife $i",
                    'relationship' => 'Wife',
                    'share' => $share === 0.25 ? '1/4' : '1/8',
                    'fraction' => $share / ($heirs['wife_count'] ?? 1),
                    'amount' => $individualAmount,
                    'status' => 'Fixed Share'
                ];
            }
            $remaining -= $totalAmount;
        }
        
        // Parent shares
        if (($heirs['father_status'] ?? 'deceased') === 'alive') {
            $share = 1/6;
            $amount = $totalEstate * $share;
            $distribution[] = [
                'heir' => 'Father',
                'relationship' => 'Father',
                'share' => '1/6',
                'fraction' => $share,
                'amount' => $amount,
                'status' => 'Fixed Share'
            ];
            $remaining -= $amount;
        }
        
        if (($heirs['mother_status'] ?? 'deceased') === 'alive') {
            $share = 1/6;
            $amount = $totalEstate * $share;
            $distribution[] = [
                'heir' => 'Mother',
                'relationship' => 'Mother',
                'share' => '1/6',
                'fraction' => $share,
                'amount' => $amount,
                'status' => 'Fixed Share'
            ];
            $remaining -= $amount;
        }
        
        // Calculate percentages
        foreach ($distribution as &$item) {
            if (isset($item['amount']) && $totalEstate > 0) {
                $item['percentage'] = ($item['amount'] / $totalEstate) * 100;
            }
        }
        
        return $distribution;
    }
    
    /**
     * Apply standard Faraid rules (Scenario 10)
     */
    private function applyStandardFaraidRules(&$distribution, $rules, $heirs, $totalEstate, $deceasedGender)
    {
        // This would implement the standard rules from your database
        // For now, use basic calculation
        $basicDistribution = $this->performBasicCalculation($heirs, $totalEstate, $deceasedGender);
        $distribution = array_merge($distribution, $basicDistribution);
    }
}