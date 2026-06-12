<?php

namespace App\Http\Controllers;

use App\Models\FaraidCalculationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FaraidController extends Controller
{
    /**
     * Get all active scenario rules
     */
    public function getScenarioRules()
    {
        try {
            $rules = FaraidCalculationRule::where('is_active', 1)
                ->orderBy('priority')
                ->get()
                ->mapWithKeys(function ($rule) {
                    return [
                        $rule->scenario_number => [
                            'scenario_number' => $rule->scenario_number,
                            'scenario_name' => $rule->scenario_name,
                            'scenario_description' => $rule->scenario_description,
                            'conditions' => json_decode($rule->conditions, true),
                            'distribution_rules' => json_decode($rule->distribution_rules, true),
                            'calculation_logic' => $rule->calculation_logic,
                            'priority' => $rule->priority
                        ]
                    ];
                });
            
            return response()->json(['rules' => $rules]);
        } catch (\Exception $e) {
            \Log::error('Error fetching scenario rules: ' . $e->getMessage());
            return response()->json(['rules' => []], 500);
        }
    }

    /**
     * Match scenario based on heirs and deceased gender
     */
    public function matchScenario(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'heirs' => 'required|array',
                'deceased_gender' => 'required|in:male,female'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Invalid input data',
                    'details' => $validator->errors()
                ], 400);
            }
            
            $heirs = $request->input('heirs');
            $deceasedGender = $request->input('deceased_gender');
            
            $scenarios = FaraidCalculationRule::where('is_active', 1)
                ->orderBy('priority')
                ->get();
            
            $matchedScenario = null;
            
            foreach ($scenarios as $scenario) {
                $conditions = json_decode($scenario->conditions, true);
                
                if ($this->checkConditions($conditions, $heirs, $deceasedGender)) {
                    $matchedScenario = $scenario;
                    break;
                }
            }
            
            if (!$matchedScenario) {
                // Default scenario
                $matchedScenario = (object) [
                    'scenario_number' => 15,
                    'scenario_name' => 'Complex Inheritance',
                    'scenario_description' => 'Complex inheritance distribution with multiple heirs',
                    'distribution_rules' => json_encode([
                        "standard_distribution" => [
                            "husband" => "1/4_or_1/2",
                            "wife" => "1/8_or_1/4",
                            "father" => "1/6",
                            "mother" => "1/6",
                            "children" => "residual"
                        ]
                    ]),
                    'conditions' => json_encode([]),
                    'calculation_logic' => 'complex_calculation'
                ];
            }
            
            return response()->json([
                'scenario' => [
                    'scenario_number' => $matchedScenario->scenario_number,
                    'scenario_name' => $matchedScenario->scenario_name,
                    'scenario_description' => $matchedScenario->scenario_description,
                    'distribution_rules' => json_decode($matchedScenario->distribution_rules, true),
                    'conditions' => json_decode($matchedScenario->conditions, true),
                    'calculation_logic' => $matchedScenario->calculation_logic ?? null
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error matching scenario: ' . $e->getMessage());
            return response()->json([
                'scenario' => [
                    'scenario_number' => 15,
                    'scenario_name' => 'Default Scenario',
                    'scenario_description' => 'Standard inheritance distribution',
                    'distribution_rules' => []
                ]
            ], 500);
        }
    }

    /**
     * Check if conditions match the given heirs and deceased gender
     */
    private function checkConditions($conditions, $heirs, $deceasedGender)
    {
        if (empty($conditions)) return false;
        
        // Check spouse count
        if (isset($conditions['spouse_count'])) {
            $spouseCount = ($heirs['wife_count'] ?? 0) + ($heirs['husband_count'] ?? 0);
            if (is_array($conditions['spouse_count'])) {
                if (!in_array($spouseCount, $conditions['spouse_count'])) return false;
            } elseif ($conditions['spouse_count'] != $spouseCount) {
                return false;
            }
        }
        
        // Check children count
        if (isset($conditions['children_count'])) {
            $childrenCount = ($heirs['son_count'] ?? 0) + ($heirs['daughter_count'] ?? 0);
            if (is_array($conditions['children_count'])) {
                if (!in_array($childrenCount, $conditions['children_count'])) return false;
            } elseif ($conditions['children_count'] != $childrenCount) {
                return false;
            }
        }
        
        // Check parents alive
        if (isset($conditions['parents_alive'])) {
            $parentsAlive = (($heirs['father_status'] ?? 'deceased') === 'alive' ? 1 : 0) + 
                           (($heirs['mother_status'] ?? 'deceased') === 'alive' ? 1 : 0);
            if (is_array($conditions['parents_alive'])) {
                if (!in_array($parentsAlive, $conditions['parents_alive'])) return false;
            } elseif ($conditions['parents_alive'] != $parentsAlive) {
                return false;
            }
        }
        
        // Check siblings count
        if (isset($conditions['siblings_count'])) {
            $siblingsCount = ($heirs['full_brother_count'] ?? 0) + ($heirs['full_sister_count'] ?? 0) +
                            ($heirs['paternal_half_brother_count'] ?? 0) + ($heirs['paternal_half_sister_count'] ?? 0) +
                            ($heirs['maternal_half_brother_count'] ?? 0) + ($heirs['maternal_half_sister_count'] ?? 0);
            if (is_array($conditions['siblings_count'])) {
                if (!in_array($siblingsCount, $conditions['siblings_count'])) return false;
            } elseif ($conditions['siblings_count'] != $siblingsCount) {
                return false;
            }
        }
        
        // Check deceased gender
        if (isset($conditions['deceased_gender'])) {
            if (is_array($conditions['deceased_gender'])) {
                if (!in_array($deceasedGender, $conditions['deceased_gender'])) return false;
            } elseif ($conditions['deceased_gender'] != $deceasedGender) {
                return false;
            }
        }
        
        // Check spouse presence
        if (isset($conditions['spouse_present'])) {
            $hasSpouse = (($heirs['wife_count'] ?? 0) > 0 || ($heirs['husband_count'] ?? 0) > 0);
            if ($conditions['spouse_present'] !== $hasSpouse) return false;
        }
        
        // Check children presence
        if (isset($conditions['children_present'])) {
            $hasChildren = (($heirs['son_count'] ?? 0) > 0 || ($heirs['daughter_count'] ?? 0) > 0);
            if ($conditions['children_present'] !== $hasChildren) return false;
        }
        
        // Check parents presence
        if (isset($conditions['parents_present'])) {
            $hasParents = (
                (isset($heirs['father_status']) && $heirs['father_status'] === 'alive') || 
                (isset($heirs['mother_status']) && $heirs['mother_status'] === 'alive')
            );
            if ($conditions['parents_present'] !== $hasParents) return false;
        }
        
        // Check both parents
        if (isset($conditions['both_parents'])) {
            $bothParents = (
                (isset($heirs['father_status']) && $heirs['father_status'] === 'alive') && 
                (isset($heirs['mother_status']) && $heirs['mother_status'] === 'alive')
            );
            if ($conditions['both_parents'] !== $bothParents) return false;
        }
        
        // Check complex scenario
        if (isset($conditions['complex_scenario'])) {
            $heirTypes = 0;
            if (($heirs['wife_count'] ?? 0) > 0 || ($heirs['husband_count'] ?? 0) > 0) $heirTypes++;
            if (($heirs['father_status'] ?? 'deceased') === 'alive') $heirTypes++;
            if (($heirs['mother_status'] ?? 'deceased') === 'alive') $heirTypes++;
            if (($heirs['son_count'] ?? 0) > 0) $heirTypes++;
            if (($heirs['daughter_count'] ?? 0) > 0) $heirTypes++;
            
            $isComplex = ($heirTypes >= 3);
            if ($conditions['complex_scenario'] !== $isComplex) return false;
        }
        
        // Check specific heir counts
        if (isset($conditions['son_count'])) {
            if (($heirs['son_count'] ?? 0) != $conditions['son_count']) return false;
        }
        
        if (isset($conditions['daughter_count'])) {
            if (($heirs['daughter_count'] ?? 0) != $conditions['daughter_count']) return false;
        }
        
        if (isset($conditions['wife_count'])) {
            if (($heirs['wife_count'] ?? 0) != $conditions['wife_count']) return false;
        }
        
        if (isset($conditions['husband_count'])) {
            if (($heirs['husband_count'] ?? 0) != $conditions['husband_count']) return false;
        }
        
        return true;
    }

    /**
     * Admin: List all faraid rules
     */
    public function adminIndex()
    {
        $rules = FaraidCalculationRule::orderBy('priority')->get();
        return view('admin.faraid-rules.index', compact('rules'));
    }

    /**
     * Admin: Create new rule form
     */
    public function adminCreate()
    {
        return view('admin.faraid-rules.create');
    }

    /**
     * Admin: Store new rule
     */
    public function adminStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scenario_number' => 'required|integer|unique:faraid_calculation_rules',
            'scenario_name' => 'required|string|max:255',
            'scenario_description' => 'nullable|string',
            'priority' => 'required|integer',
            'conditions' => 'required|json',
            'distribution_rules' => 'required|json',
            'calculation_logic' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        FaraidCalculationRule::create([
            'scenario_number' => $request->scenario_number,
            'scenario_name' => $request->scenario_name,
            'scenario_description' => $request->scenario_description,
            'priority' => $request->priority,
            'conditions' => json_decode($request->conditions, true),
            'distribution_rules' => json_decode($request->distribution_rules, true),
            'calculation_logic' => $request->calculation_logic,
            'is_active' => $request->boolean('is_active')
        ]);
        
        return redirect()->route('admin.faraid-rules.index')
            ->with('success', 'Faraid rule created successfully.');
    }

    /**
     * Admin: Edit rule form
     */
    public function adminEdit($id)
    {
        $rule = FaraidCalculationRule::findOrFail($id);
        return view('admin.faraid-rules.edit', compact('rule'));
    }

    /**
     * Admin: Update rule
     */
    public function adminUpdate(Request $request, $id)
    {
        $rule = FaraidCalculationRule::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'scenario_number' => 'required|integer|unique:faraid_calculation_rules,scenario_number,' . $id,
            'scenario_name' => 'required|string|max:255',
            'scenario_description' => 'nullable|string',
            'priority' => 'required|integer',
            'conditions' => 'required|json',
            'distribution_rules' => 'required|json',
            'calculation_logic' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $rule->update([
            'scenario_number' => $request->scenario_number,
            'scenario_name' => $request->scenario_name,
            'scenario_description' => $request->scenario_description,
            'priority' => $request->priority,
            'conditions' => json_decode($request->conditions, true),
            'distribution_rules' => json_decode($request->distribution_rules, true),
            'calculation_logic' => $request->calculation_logic,
            'is_active' => $request->boolean('is_active')
        ]);
        
        return redirect()->route('admin.faraid-rules.index')
            ->with('success', 'Faraid rule updated successfully.');
    }

    /**
     * Admin: Delete rule
     */
    public function adminDestroy($id)
    {
        $rule = FaraidCalculationRule::findOrFail($id);
        $rule->delete();
        
        return redirect()->route('admin.faraid-rules.index')
            ->with('success', 'Faraid rule deleted successfully.');
    }

    /**
     * Admin: Toggle rule active status
     */
    public function adminToggleActive($id)
    {
        $rule = FaraidCalculationRule::findOrFail($id);
        $rule->is_active = !$rule->is_active;
        $rule->save();
        
        return redirect()->back()
            ->with('success', 'Rule status updated successfully.');
    }
}