<?php

namespace App\Services;

use App\Models\Calculation;
use App\Models\EstatePreRegistration;
use Illuminate\Support\Facades\Log;

class FaraidCalculator
{
    // =========================================================================
    // PROPERTIES FOR CALCULATIONS
    // =========================================================================

    protected $estate;
    protected $heirs;
    protected $deceasedGender;
    
    // Flags and counters for heir analysis
    protected $hasSon = false;
    protected $hasDaughter = false;
    protected $hasFather = false;
    protected $hasMother = false;
    protected $hasHusband = false;
    protected $hasWife = false;
    protected $hasBrother = false;
    protected $hasSister = false;
    protected $hasGrandfather = false;
    protected $hasGrandmother = false;
    protected $hasHalfBrotherPaternal = false;
    protected $hasHalfSisterPaternal = false;
    protected $hasHalfBrotherMaternal = false;
    protected $hasHalfSisterMaternal = false;
    protected $hasFullSiblings = false;
    protected $hasPaternalSiblings = false;
    protected $hasMaternalSiblings = false;

    protected $sonCount = 0;
    protected $daughterCount = 0;
    protected $wifeCount = 0;
    protected $husbandCount = 0;
    protected $brotherCount = 0;
    protected $sisterCount = 0;
    protected $fullBrotherCount = 0;
    protected $fullSisterCount = 0;
    protected $paternalHalfBrotherCount = 0;
    protected $paternalHalfSisterCount = 0;
    protected $maternalHalfBrotherCount = 0;
    protected $maternalHalfSisterCount = 0;

    protected $calculationResults = [];
    protected $totalEstate = 0;
    protected $netEstate = 0;

    // =========================================================================
    // CONSTRUCTOR
    // =========================================================================

    public function __construct($estate = null, $heirsData = null, $netEstate = 0, $deceasedGender = null)
    {
        if ($estate instanceof EstatePreRegistration) {
            $this->estate = $estate;
            $this->heirs = $estate->heirs;
            $this->netEstate = $estate->net_estate;
            $this->totalEstate = $estate->total_assets;
            $this->deceasedGender = $estate->deceased_gender ?? 'male';
            $this->analyzeHeirs();
        } elseif (is_array($heirsData)) {
            $this->heirs = $heirsData;
            $this->netEstate = $netEstate;
            $this->totalEstate = $netEstate;
            $this->deceasedGender = $deceasedGender ?? 'male';
            $this->analyzeHeirsFromArray($heirsData);
        }
    }

    // =========================================================================
    // HEIR ANALYSIS METHODS
    // =========================================================================

    protected function analyzeHeirs(): void
    {
        $this->resetHeirFlags();

        if (!$this->heirs) {
            return;
        }

        foreach ($this->heirs as $heir) {
            $this->processHeir($heir);
        }
    }

    protected function analyzeHeirsFromArray(array $heirsData): void
    {
        $this->resetHeirFlags();
        
        // Process spouse
        $this->husbandCount = $heirsData['husband_count'] ?? 0;
        $this->hasHusband = $this->husbandCount > 0;
        $this->wifeCount = min($heirsData['wife_count'] ?? 0, 4);
        $this->hasWife = $this->wifeCount > 0;
        
        // Process parents
        $this->hasFather = ($heirsData['father_status'] ?? 'deceased') === 'alive';
        $this->hasMother = ($heirsData['mother_status'] ?? 'deceased') === 'alive';
        
        // Process children
        $this->sonCount = $heirsData['son_count'] ?? 0;
        $this->hasSon = $this->sonCount > 0;
        $this->daughterCount = $heirsData['daughter_count'] ?? 0;
        $this->hasDaughter = $this->daughterCount > 0;
        
        // Process grandparents
        $this->hasGrandfather = ($heirsData['fathers_father_status'] ?? 'deceased') === 'alive';
        $this->hasGrandmother = ($heirsData['fathers_mother_status'] ?? 'deceased') === 'alive' ||
                                ($heirsData['mothers_mother_status'] ?? 'deceased') === 'alive';
        
        // Process full siblings
        $this->fullBrotherCount = $heirsData['full_brother_count'] ?? 0;
        $this->hasBrother = $this->fullBrotherCount > 0;
        $this->fullSisterCount = $heirsData['full_sister_count'] ?? 0;
        $this->hasSister = $this->fullSisterCount > 0;
        $this->hasFullSiblings = ($this->fullBrotherCount + $this->fullSisterCount) > 0;
        
        // Process paternal half-siblings
        $this->paternalHalfBrotherCount = $heirsData['paternal_half_brother_count'] ?? 0;
        $this->hasHalfBrotherPaternal = $this->paternalHalfBrotherCount > 0;
        $this->paternalHalfSisterCount = $heirsData['paternal_half_sister_count'] ?? 0;
        $this->hasHalfSisterPaternal = $this->paternalHalfSisterCount > 0;
        $this->hasPaternalSiblings = ($this->paternalHalfBrotherCount + $this->paternalHalfSisterCount) > 0;
        
        // Process maternal half-siblings
        $this->maternalHalfBrotherCount = $heirsData['maternal_half_brother_count'] ?? 0;
        $this->hasHalfBrotherMaternal = $this->maternalHalfBrotherCount > 0;
        $this->maternalHalfSisterCount = $heirsData['maternal_half_sister_count'] ?? 0;
        $this->hasHalfSisterMaternal = $this->maternalHalfSisterCount > 0;
        $this->hasMaternalSiblings = ($this->maternalHalfBrotherCount + $this->maternalHalfSisterCount) > 0;
        
        // Total siblings count
        $this->brotherCount = $this->fullBrotherCount + $this->paternalHalfBrotherCount + $this->maternalHalfBrotherCount;
        $this->sisterCount = $this->fullSisterCount + $this->paternalHalfSisterCount + $this->maternalHalfSisterCount;
    }

    protected function processHeir($heir): void
    {
        $relationship = is_array($heir) ? ($heir['relationship'] ?? '') : $heir->relationship;
        
        switch ($relationship) {
            case 'son':
                $this->hasSon = true;
                $this->sonCount++;
                break;
            case 'daughter':
                $this->hasDaughter = true;
                $this->daughterCount++;
                break;
            case 'father':
                $this->hasFather = true;
                break;
            case 'mother':
                $this->hasMother = true;
                break;
            case 'husband':
                $this->hasHusband = true;
                $this->husbandCount++;
                break;
            case 'wife':
                $this->hasWife = true;
                $this->wifeCount++;
                break;
            case 'brother':
            case 'full_brother':
                $this->hasBrother = true;
                $this->brotherCount++;
                $this->fullBrotherCount++;
                break;
            case 'sister':
            case 'full_sister':
                $this->hasSister = true;
                $this->sisterCount++;
                $this->fullSisterCount++;
                break;
            case 'half_brother_paternal':
                $this->hasHalfBrotherPaternal = true;
                $this->brotherCount++;
                $this->paternalHalfBrotherCount++;
                break;
            case 'half_sister_paternal':
                $this->hasHalfSisterPaternal = true;
                $this->sisterCount++;
                $this->paternalHalfSisterCount++;
                break;
            case 'half_brother_maternal':
                $this->hasHalfBrotherMaternal = true;
                $this->brotherCount++;
                $this->maternalHalfBrotherCount++;
                break;
            case 'half_sister_maternal':
                $this->hasHalfSisterMaternal = true;
                $this->sisterCount++;
                $this->maternalHalfSisterCount++;
                break;
            case 'grandfather':
                $this->hasGrandfather = true;
                break;
            case 'grandmother':
            case 'grandmother_paternal':
            case 'grandmother_maternal':
                $this->hasGrandmother = true;
                break;
        }
    }

    protected function resetHeirFlags(): void
    {
        $this->hasSon = false;
        $this->hasDaughter = false;
        $this->hasFather = false;
        $this->hasMother = false;
        $this->hasHusband = false;
        $this->hasWife = false;
        $this->hasBrother = false;
        $this->hasSister = false;
        $this->hasGrandfather = false;
        $this->hasGrandmother = false;
        $this->hasHalfBrotherPaternal = false;
        $this->hasHalfSisterPaternal = false;
        $this->hasHalfBrotherMaternal = false;
        $this->hasHalfSisterMaternal = false;
        $this->hasFullSiblings = false;
        $this->hasPaternalSiblings = false;
        $this->hasMaternalSiblings = false;

        $this->sonCount = 0;
        $this->daughterCount = 0;
        $this->wifeCount = 0;
        $this->husbandCount = 0;
        $this->brotherCount = 0;
        $this->sisterCount = 0;
        $this->fullBrotherCount = 0;
        $this->fullSisterCount = 0;
        $this->paternalHalfBrotherCount = 0;
        $this->paternalHalfSisterCount = 0;
        $this->maternalHalfBrotherCount = 0;
        $this->maternalHalfSisterCount = 0;
    }

    protected function hasChildren(): bool
    {
        return $this->hasSon || $this->hasDaughter;
    }

    protected function hasSpouse(): bool
    {
        return $this->hasHusband || $this->hasWife;
    }

    protected function hasParents(): bool
    {
        return $this->hasFather || $this->hasMother;
    }

    protected function hasBothParents(): bool
    {
        return $this->hasFather && $this->hasMother;
    }

    protected function hasGrandparents(): bool
    {
        return $this->hasGrandfather || $this->hasGrandmother;
    }

    protected function hasFullSiblingsOnly(): bool
    {
        return $this->hasFullSiblings && !$this->hasPaternalSiblings && !$this->hasMaternalSiblings;
    }

    protected function hasPaternalSiblingsOnly(): bool
    {
        return $this->hasPaternalSiblings && !$this->hasFullSiblings && !$this->hasMaternalSiblings;
    }

    protected function hasMaternalSiblingsOnly(): bool
    {
        return $this->hasMaternalSiblings && !$this->hasFullSiblings && !$this->hasPaternalSiblings;
    }

    protected function hasAnySiblings(): bool
    {
        return $this->hasFullSiblings || $this->hasPaternalSiblings || $this->hasMaternalSiblings;
    }

    // =========================================================================
    // MAIN CALCULATION METHOD
    // =========================================================================

    public function calculate(): array
    {
        Log::info('FaraidCalculator starting calculation', [
            'net_estate' => $this->netEstate,
            'deceased_gender' => $this->deceasedGender,
            'has_husband' => $this->hasHusband,
            'has_wife' => $this->hasWife,
            'has_father' => $this->hasFather,
            'has_mother' => $this->hasMother,
            'has_son' => $this->hasSon,
            'has_daughter' => $this->hasDaughter,
            'son_count' => $this->sonCount,
            'daughter_count' => $this->daughterCount,
            'wife_count' => $this->wifeCount,
            'full_brother_count' => $this->fullBrotherCount,
            'full_sister_count' => $this->fullSisterCount,
            'paternal_half_brother_count' => $this->paternalHalfBrotherCount,
            'paternal_half_sister_count' => $this->paternalHalfSisterCount,
            'maternal_half_brother_count' => $this->maternalHalfBrotherCount,
            'maternal_half_sister_count' => $this->maternalHalfSisterCount,
        ]);

        $this->calculationResults = [];
        
        // Check for zero net estate
        if ($this->netEstate <= 0) {
            return $this->getEmptyResult();
        }

        // =====================================================================
        // SCENARIO 1: Spouse Only (No Children, No Parents)
        // =====================================================================
        if ($this->hasSpouse() && !$this->hasParents() && !$this->hasChildren() && !$this->hasAnySiblings()) {
            return $this->calculateSpouseOnly();
        }

        // =====================================================================
        // SCENARIO 2: Spouse and Parents (No Children)
        // =====================================================================
        if ($this->hasSpouse() && $this->hasParents() && !$this->hasChildren()) {
            return $this->calculateSpouseAndParents();
        }

        // =====================================================================
        // SCENARIO 3: Spouse and Children
        // =====================================================================
        if ($this->hasSpouse() && $this->hasChildren()) {
            return $this->calculateSpouseAndChildren();
        }

        // =====================================================================
        // SCENARIO 4: Children Only (No Spouse, No Parents)
        // =====================================================================
        if ($this->hasChildren() && !$this->hasParents() && !$this->hasSpouse()) {
            return $this->calculateChildrenOnly();
        }

        // =====================================================================
        // SCENARIO 5: Parents and Children
        // =====================================================================
        if ($this->hasParents() && $this->hasChildren()) {
            return $this->calculateParentsAndChildren();
        }

        // =====================================================================
        // SCENARIO 6: Parents Only (No Children, No Spouse)
        // =====================================================================
        if ($this->hasParents() && !$this->hasChildren() && !$this->hasSpouse()) {
            return $this->calculateParentsOnly();
        }

        // =====================================================================
        // SCENARIO 7: Siblings Only (Full Siblings)
        // =====================================================================
        if ($this->hasFullSiblingsOnly() && !$this->hasParents() && !$this->hasChildren() && !$this->hasSpouse() && $this->hasFullSiblings) {
            return $this->calculateFullSiblingsOnly();
        }

        // =====================================================================
        // SCENARIO 8: Maternal Half-Siblings Only
        // =====================================================================
        if ($this->hasMaternalSiblingsOnly() && !$this->hasParents() && !$this->hasChildren() && !$this->hasSpouse() && !$this->hasFather) {
            return $this->calculateMaternalHalfSiblingsOnly();
        }

        // =====================================================================
        // SCENARIO 9: Paternal Half-Siblings Only
        // =====================================================================
        if ($this->hasPaternalSiblingsOnly() && !$this->hasParents() && !$this->hasChildren() && !$this->hasSpouse() && !$this->hasFather) {
            return $this->calculatePaternalHalfSiblingsOnly();
        }

        // =====================================================================
        // SCENARIO 10: Mixed Siblings (Full + Half) - Awl (Over-subscription)
        // =====================================================================
        if ($this->hasAnySiblings() && !$this->hasParents() && !$this->hasChildren() && !$this->hasSpouse()) {
            return $this->calculateMixedSiblingsAwl();
        }

        // =====================================================================
        // SCENARIO 11: Spouse and Siblings (No Parents, No Children)
        // =====================================================================
        if ($this->hasSpouse() && $this->hasAnySiblings() && !$this->hasParents() && !$this->hasChildren()) {
            return $this->calculateSpouseAndSiblings();
        }

        // =====================================================================
        // SCENARIO 12: Awl (Over-subscription) Cases
        // =====================================================================
        $awlResult = $this->checkAndHandleAwlCases();
        if ($awlResult) {
            return $awlResult;
        }

        // =====================================================================
        // SCENARIO 13: Grandparents Only
        // =====================================================================
        if ($this->hasGrandparents() && !$this->hasParents() && !$this->hasChildren() && !$this->hasSpouse() && !$this->hasAnySiblings()) {
            return $this->calculateGrandparentsOnly();
        }

        // =====================================================================
        // SCENARIO 14: Multiple Wives Scenario
        // =====================================================================
        if ($this->hasWife && $this->wifeCount > 1) {
            return $this->calculateMultipleWivesScenario();
        }

        // =====================================================================
        // SCENARIO 15: Surplus Estate (Baitulmal)
        // =====================================================================
        if (!$this->hasSpouse() && !$this->hasParents() && !$this->hasChildren() && !$this->hasAnySiblings()) {
            return $this->calculateSurplusOnly();
        }

        // =====================================================================
        // DEFAULT: Combined distribution for complex scenarios
        // =====================================================================
        return $this->calculateCombinedDistribution();
    }

    protected function getEmptyResult(): array
    {
        return [
            'distribution' => [],
            'total_distributed' => 0,
            'total_eligible' => 0,
            'scenario' => 'No Estate',
            'scenario_number' => 0,
            'net_estate' => $this->netEstate,
            'awl_applied' => false,
            'awl_factor' => 1
        ];
    }

    // =========================================================================
    // SCENARIO 1: SPOUSE ONLY
    // =========================================================================
    // Husbands get 1/2, Wives get 1/4, remainder to Baitulmal

    protected function calculateSpouseOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 1 - Spouse Only');
        
        $results = [];
        $remaining = $this->netEstate;

        if ($this->hasHusband) {
            // Husband gets 1/2 when no children
            $husbandAmount = $this->netEstate * 0.5;
            $results[] = $this->createResult('Husband', 'Husband', '1/2', 0.5, $husbandAmount, 'Fixed Share');
            $remaining -= $husbandAmount;
        } elseif ($this->hasWife) {
            // Wife gets 1/4 when no children
            $totalWifeAmount = $this->netEstate * 0.25;
            $individualAmount = $totalWifeAmount / $this->wifeCount;
            
            for ($i = 1; $i <= $this->wifeCount; $i++) {
                $results[] = $this->createResult("Wife $i", 'Wife', '1/4', 0.25 / $this->wifeCount, $individualAmount, 'Fixed Share');
            }
            $remaining -= $totalWifeAmount;
        }

        // Remaining goes to Baitulmal
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }

        return $this->finalizeResults($results, 1);
    }

    // =========================================================================
    // SCENARIO 2: SPOUSE AND PARENTS (NO CHILDREN)
    // =========================================================================
    // Cases: Husband+Father, Husband+Mother, Wife+Father, Wife+Mother

    protected function calculateSpouseAndParents(): array
    {
        Log::info('FaraidCalculator: Scenario 2 - Spouse and Parents (No Children)');
        
        $results = [];
        $remaining = $this->netEstate;

        // Step 1: Calculate spouse share
        if ($this->hasHusband) {
            // Husband gets 1/2 when no children
            $husbandAmount = $this->netEstate * 0.5;
            $results[] = $this->createResult('Husband', 'Husband', '1/2', 0.5, $husbandAmount, 'Fixed Share');
            $remaining -= $husbandAmount;
        } elseif ($this->hasWife) {
            // Wife gets 1/4 when no children
            $totalWifeAmount = $this->netEstate * 0.25;
            $individualAmount = $totalWifeAmount / $this->wifeCount;
            
            for ($i = 1; $i <= $this->wifeCount; $i++) {
                $results[] = $this->createResult("Wife $i", 'Wife', '1/4', 0.25 / $this->wifeCount, $individualAmount, 'Fixed Share');
            }
            $remaining -= $totalWifeAmount;
        }

        // Step 2: Distribute remaining to parents
        if ($this->hasFather && $this->hasMother) {
            // Both parents alive
            if ($this->hasHusband) {
                // Husband + Father + Mother
                // Husband gets 1/2, Mother gets 1/6, Father gets 1/3
                $motherAmount = $this->netEstate * (1/6);
                $fatherAmount = $this->netEstate * (1/3);
                
                $results[] = $this->createResult('Mother', 'Mother', '1/6', 1/6, $motherAmount, 'Fixed Share');
                $results[] = $this->createResult('Father', 'Father', '1/3', 1/3, $fatherAmount, 'Asabah');
                $remaining = 0;
            } else {
                // Wife + Father + Mother
                // Wife gets 1/4, Mother gets 1/4, Father gets 2/4
                $motherAmount = $remaining * (1/3);
                $fatherAmount = $remaining * (2/3);
                
                $results[] = $this->createResult('Mother', 'Mother', '1/4', 0.25, $motherAmount, 'Fixed Share');
                $results[] = $this->createResult('Father', 'Father', '2/4', 0.5, $fatherAmount, 'Asabah');
                $remaining = 0;
            }
        } elseif ($this->hasFather) {
            // Only father alive
            if ($this->hasHusband) {
                // Husband + Father - Husband gets 1/2, Father gets 1/2
                $fatherAmount = $remaining;
                $results[] = $this->createResult('Father', 'Father', '1/2', 0.5, $fatherAmount, 'Asabah');
                $remaining = 0;
            } else {
                // Wife + Father - Wife gets 1/4, Father gets 3/4
                $fatherAmount = $remaining;
                $results[] = $this->createResult('Father', 'Father', '3/4', 0.75, $fatherAmount, 'Asabah');
                $remaining = 0;
            }
        } elseif ($this->hasMother) {
            // Only mother alive
            if ($this->hasHusband) {
                // Husband + Mother - Husband gets 1/2, Mother gets 1/3 of remaining (1/6), Baitulmal gets 1/3
                $motherAmount = $remaining * (1/3);
                $results[] = $this->createResult('Mother', 'Mother', '1/6', 1/6, $motherAmount, 'Fixed Share');
                $remaining -= $motherAmount;
            } else {
                // Wife + Mother - Wife gets 3/12, Mother gets 4/12, Baitulmal gets 5/12
                $totalWifeAmount = $this->netEstate * 0.25;
                $motherAmount = $this->netEstate * (4/12);
                
                // Update results instead of re-adding
                $remaining = $this->netEstate - $totalWifeAmount - $motherAmount;
            }
        }

        // Baitulmal for remaining
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }

        return $this->finalizeResults($results, 2);
    }

    // =========================================================================
    // SCENARIO 3: SPOUSE AND CHILDREN
    // =========================================================================
    // Cases: Wife+One Son, Wife+One Daughter, Wife+Two Daughters, 
    //        Husband+One Daughter, Husband+One Son+One Daughter

    protected function calculateSpouseAndChildren(): array
    {
        Log::info('FaraidCalculator: Scenario 3 - Spouse and Children');
        
        $results = [];
        $remaining = $this->netEstate;

        // Step 1: Calculate spouse share
        if ($this->hasHusband) {
            // Husband gets 1/4 when children exist
            $husbandAmount = $this->netEstate * 0.25;
            $results[] = $this->createResult('Husband', 'Husband', '1/4', 0.25, $husbandAmount, 'Fixed Share');
            $remaining -= $husbandAmount;
        } elseif ($this->hasWife) {
            // Wife gets 1/8 when children exist
            $totalWifeAmount = $this->netEstate * 0.125;
            $individualAmount = $totalWifeAmount / $this->wifeCount;
            
            for ($i = 1; $i <= $this->wifeCount; $i++) {
                $results[] = $this->createResult("Wife $i", 'Wife', '1/8', 0.125 / $this->wifeCount, $individualAmount, 'Fixed Share');
            }
            $remaining -= $totalWifeAmount;
        }

        // Step 2: Distribute remaining to children
        if ($remaining > 0.01 && ($this->hasSon || $this->hasDaughter)) {
            
            // Case: One wife + One son
            if ($this->hasWife && $this->wifeCount === 1 && $this->sonCount === 1 && $this->daughterCount === 0) {
                $sonAmount = $remaining;
                $results[] = $this->createResult('Son 1', 'Son', '7/8', 0.875, $sonAmount, 'Asabah');
                $remaining = 0;
            }
            // Case: One wife + One daughter
            elseif ($this->hasWife && $this->wifeCount === 1 && $this->daughterCount === 1 && $this->sonCount === 0) {
                $daughterAmount = $remaining * (4/7);
                $results[] = $this->createResult('Daughter 1', 'Daughter', '4/7', 4/7, $daughterAmount, 'Fixed Share');
                $remaining -= $daughterAmount;
            }
            // Case: One wife + Two daughters
            elseif ($this->hasWife && $this->wifeCount === 1 && $this->daughterCount === 2 && $this->sonCount === 0) {
                $eachDaughterAmount = $remaining * (4/9);
                for ($i = 1; $i <= 2; $i++) {
                    $results[] = $this->createResult("Daughter $i", 'Daughter', '4/9 shared', 4/9, $eachDaughterAmount, 'Fixed Share');
                }
                $remaining -= ($eachDaughterAmount * 2);
            }
            // Case: Husband + One daughter
            elseif ($this->hasHusband && $this->daughterCount === 1 && $this->sonCount === 0) {
                $daughterAmount = $remaining * (2/3);
                $results[] = $this->createResult('Daughter 1', 'Daughter', '2/3', 2/3, $daughterAmount, 'Fixed Share');
                $remaining -= $daughterAmount;
            }
            // Case: Husband + One son + One daughter
            elseif ($this->hasHusband && $this->sonCount === 1 && $this->daughterCount === 1) {
                $sonAmount = $remaining * (2/3);
                $daughterAmount = $remaining * (1/3);
                $results[] = $this->createResult('Son 1', 'Son', '2/3', 2/3, $sonAmount, 'Asabah');
                $results[] = $this->createResult('Daughter 1', 'Daughter', '1/3', 1/3, $daughterAmount, 'Asabah');
                $remaining = 0;
            }
            // General case for spouse and children
            else {
                $totalShares = ($this->sonCount * 2) + $this->daughterCount;
                
                if ($totalShares > 0) {
                    $unitValue = $remaining / $totalShares;
                    
                    // Distribute to sons (each gets 2 units)
                    for ($i = 1; $i <= $this->sonCount; $i++) {
                        $amount = $unitValue * 2;
                        $results[] = $this->createResult("Son $i", 'Son', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
                    }
                    
                    // Distribute to daughters (each gets 1 unit)
                    for ($i = 1; $i <= $this->daughterCount; $i++) {
                        $amount = $unitValue;
                        $results[] = $this->createResult("Daughter $i", 'Daughter', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
                    }
                    $remaining = 0;
                }
            }
        }

        // Any remaining goes to Baitulmal
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }

        return $this->finalizeResults($results, 3);
    }

    // =========================================================================
    // SCENARIO 4: CHILDREN ONLY
    // =========================================================================
    // Cases: One son, One daughter, Two daughters, Two sons + two daughters

    protected function calculateChildrenOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 4 - Children Only');
        
        $results = [];
        $remaining = $this->netEstate;

        // Case: Only one son - inherits entire estate (1/1)
        if ($this->sonCount === 1 && !$this->hasDaughter) {
            $perSon = $remaining;
            $results[] = $this->createResult('Son 1', 'Son', '1/1', 1, $perSon, 'Asabah');
            $remaining = 0;
        }
        // Case: Only multiple sons - each gets equal share
        elseif ($this->sonCount > 0 && !$this->hasDaughter) {
            $perSon = $remaining / $this->sonCount;
            for ($i = 1; $i <= $this->sonCount; $i++) {
                $results[] = $this->createResult("Son $i", 'Son', "1/{$this->sonCount}", 1 / $this->sonCount, $perSon, 'Asabah');
            }
            $remaining = 0;
        }
        // Case: Only one daughter
        elseif ($this->daughterCount === 1 && !$this->hasSon) {
            $daughterAmount = $remaining * 0.5;
            $results[] = $this->createResult('Daughter 1', 'Daughter', '1/2', 0.5, $daughterAmount, 'Fixed Share');
            $remaining -= $daughterAmount;
        }
        // Case: Two or more daughters (no sons)
        elseif ($this->daughterCount >= 2 && !$this->hasSon) {
            $totalDaughtersShare = $remaining * (2/3);
            $perDaughter = $totalDaughtersShare / $this->daughterCount;
            
            for ($i = 1; $i <= $this->daughterCount; $i++) {
                $results[] = $this->createResult("Daughter $i", 'Daughter', '2/3 shared', (2/3) / $this->daughterCount, $perDaughter, 'Fixed Share');
            }
            $remaining -= $totalDaughtersShare;
        }
        // Case: Two sons and two daughters
        elseif ($this->sonCount === 2 && $this->daughterCount === 2) {
            $totalShares = (2 * 2) + 2; // (2 sons * 2) + 2 daughters = 4 + 2 = 6
            $unitValue = $remaining / $totalShares;
            
            // Each son gets 2/6
            for ($i = 1; $i <= 2; $i++) {
                $amount = $unitValue * 2;
                $results[] = $this->createResult("Son $i", 'Son', '2/6', 2/6, $amount, 'Asabah');
            }
            // Each daughter gets 1/6
            for ($i = 1; $i <= 2; $i++) {
                $amount = $unitValue;
                $results[] = $this->createResult("Daughter $i", 'Daughter', '1/6', 1/6, $amount, 'Asabah');
            }
            $remaining = 0;
        }
        // Case: Both sons and daughters
        elseif ($this->hasSon && $this->hasDaughter) {
            $totalShares = ($this->sonCount * 2) + $this->daughterCount;
            $unitValue = $remaining / $totalShares;
            
            for ($i = 1; $i <= $this->sonCount; $i++) {
                $amount = $unitValue * 2;
                $results[] = $this->createResult("Son $i", 'Son', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
            }
            
            for ($i = 1; $i <= $this->daughterCount; $i++) {
                $amount = $unitValue;
                $results[] = $this->createResult("Daughter $i", 'Daughter', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
            }
            $remaining = 0;
        }

        // Baitulmal for remaining
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }

        return $this->finalizeResults($results, 4);
    }

    // =========================================================================
    // SCENARIO 5: PARENTS AND CHILDREN
    // =========================================================================
    // Cases: Father + One Son, Father + One Daughter, Mother + One Son, Mother + One Daughter

    protected function calculateParentsAndChildren(): array
    {
        Log::info('FaraidCalculator: Scenario 5 - Parents and Children');
        
        $results = [];
        $remaining = $this->netEstate;

        // Case: Father + One Son
        if ($this->hasFather && !$this->hasMother && $this->sonCount === 1 && $this->daughterCount === 0) {
            $fatherAmount = $this->netEstate * (1/6);
            $sonAmount = $this->netEstate * (5/6);
            
            $results[] = $this->createResult('Father', 'Father', '1/6', 1/6, $fatherAmount, 'Fixed Share');
            $results[] = $this->createResult('Son 1', 'Son', '5/6', 5/6, $sonAmount, 'Asabah');
            $remaining = 0;
        }
        // Case: Father + One Daughter
        elseif ($this->hasFather && !$this->hasMother && $this->daughterCount === 1 && $this->sonCount === 0) {
            $fatherAmount = $this->netEstate * 0.5;
            $daughterAmount = $this->netEstate * 0.5;
            
            $results[] = $this->createResult('Father', 'Father', '1/2', 0.5, $fatherAmount, 'Fixed Share');
            $results[] = $this->createResult('Daughter 1', 'Daughter', '1/2', 0.5, $daughterAmount, 'Fixed Share');
            $remaining = 0;
        }
        // Case: Mother + One Son
        elseif (!$this->hasFather && $this->hasMother && $this->sonCount === 1 && $this->daughterCount === 0) {
            $motherAmount = $this->netEstate * (1/6);
            $sonAmount = $this->netEstate * (5/6);
            
            $results[] = $this->createResult('Mother', 'Mother', '1/6', 1/6, $motherAmount, 'Fixed Share');
            $results[] = $this->createResult('Son 1', 'Son', '5/6', 5/6, $sonAmount, 'Asabah');
            $remaining = 0;
        }
        // Case: Mother + One Daughter
        elseif (!$this->hasFather && $this->hasMother && $this->daughterCount === 1 && $this->sonCount === 0) {
            $motherAmount = $this->netEstate * (1/6);
            $daughterAmount = $this->netEstate * (3/6);
            
            $results[] = $this->createResult('Mother', 'Mother', '1/6', 1/6, $motherAmount, 'Fixed Share');
            $results[] = $this->createResult('Daughter 1', 'Daughter', '3/6', 0.5, $daughterAmount, 'Fixed Share');
            $remaining -= ($motherAmount + $daughterAmount);
        }
        // Case: Both parents + children
        else {
            // Parents get 1/6 each when children exist
            if ($this->hasFather) {
                $fatherAmount = $this->netEstate * (1/6);
                $results[] = $this->createResult('Father', 'Father', '1/6', 1/6, $fatherAmount, 'Fixed Share');
                $remaining -= $fatherAmount;
            }
            
            if ($this->hasMother) {
                $motherAmount = $this->netEstate * (1/6);
                $results[] = $this->createResult('Mother', 'Mother', '1/6', 1/6, $motherAmount, 'Fixed Share');
                $remaining -= $motherAmount;
            }

            // Remaining goes to children
            if ($remaining > 0.01 && ($this->hasSon || $this->hasDaughter)) {
                $totalShares = ($this->sonCount * 2) + $this->daughterCount;
                
                if ($totalShares > 0) {
                    $unitValue = $remaining / $totalShares;
                    
                    for ($i = 1; $i <= $this->sonCount; $i++) {
                        $amount = $unitValue * 2;
                        $results[] = $this->createResult("Son $i", 'Son', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
                    }
                    
                    for ($i = 1; $i <= $this->daughterCount; $i++) {
                        $amount = $unitValue;
                        $results[] = $this->createResult("Daughter $i", 'Daughter', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
                    }
                    $remaining = 0;
                }
            }
        }

        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }

        return $this->finalizeResults($results, 5);
    }

    // =========================================================================
    // SCENARIO 6: PARENTS ONLY
    // =========================================================================
    // Cases: Father only, Mother only, Both parents

    protected function calculateParentsOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 6 - Parents Only');
        
        $results = [];
        $remaining = $this->netEstate;

        // Case: Father only - inherits entire estate (1/1)
        if ($this->hasFather && !$this->hasMother) {
            $results[] = $this->createResult('Father', 'Father', '1/1', 1, $remaining, 'Asabah');
            $remaining = 0;
        }
        // Case: Mother only - receives 1/3, Baitulmal gets 2/3
        elseif (!$this->hasFather && $this->hasMother) {
            $motherAmount = $remaining * (1/3);
            $results[] = $this->createResult('Mother', 'Mother', '1/3', 1/3, $motherAmount, 'Fixed Share');
            $remaining -= $motherAmount;
        }
        // Case: Both mother and father - Mother gets 1/3, Father gets 2/3
        elseif ($this->hasFather && $this->hasMother) {
            $motherAmount = $remaining * (1/3);
            $fatherAmount = $remaining * (2/3);
            
            $results[] = $this->createResult('Mother', 'Mother', '1/3', 1/3, $motherAmount, 'Fixed Share');
            $results[] = $this->createResult('Father', 'Father', '2/3', 2/3, $fatherAmount, 'Asabah');
            $remaining = 0;
        }

        // Baitulmal for remaining
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }

        return $this->finalizeResults($results, 6);
    }

    // =========================================================================
    // SCENARIO 7: FULL SIBLINGS ONLY
    // =========================================================================
    // Cases: One brother, One sister only, Two sisters, One brother + one sister

    protected function calculateFullSiblingsOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 7 - Full Siblings Only');
        
        $results = [];
        $remaining = $this->netEstate;

        // Case: Only one brother - inherits entire estate (1/1)
        if ($this->fullBrotherCount === 1 && $this->fullSisterCount === 0) {
            $perBrother = $remaining;
            $results[] = $this->createResult('Full Brother 1', 'Full Brother', '1/1', 1, $perBrother, 'Asabah');
            $remaining = 0;
        }
        // Case: Only brothers - equal shares
        elseif ($this->fullBrotherCount > 0 && $this->fullSisterCount === 0) {
            $perBrother = $remaining / $this->fullBrotherCount;
            for ($i = 1; $i <= $this->fullBrotherCount; $i++) {
                $results[] = $this->createResult("Full Brother $i", 'Full Brother', "1/{$this->fullBrotherCount}", 1 / $this->fullBrotherCount, $perBrother, 'Asabah');
            }
            $remaining = 0;
        }
        // Case: Only one sister
        elseif ($this->fullBrotherCount === 0 && $this->fullSisterCount === 1) {
            $sisterAmount = $remaining * 0.5;
            $results[] = $this->createResult('Full Sister 1', 'Full Sister', '1/2', 0.5, $sisterAmount, 'Fixed Share');
            $remaining -= $sisterAmount;
        }
        // Case: Two or more sisters (no brothers)
        elseif ($this->fullBrotherCount === 0 && $this->fullSisterCount >= 2) {
            $totalSistersShare = $remaining * (2/3);
            $perSister = $totalSistersShare / $this->fullSisterCount;
            
            for ($i = 1; $i <= $this->fullSisterCount; $i++) {
                $results[] = $this->createResult("Full Sister $i", 'Full Sister', '2/3 shared', (2/3) / $this->fullSisterCount, $perSister, 'Fixed Share');
            }
            $remaining -= $totalSistersShare;
        }
        // Case: One brother and one sister
        elseif ($this->fullBrotherCount === 1 && $this->fullSisterCount === 1) {
            $brotherAmount = $remaining * (2/3);
            $sisterAmount = $remaining * (1/3);
            
            $results[] = $this->createResult('Full Brother 1', 'Full Brother', '2/3', 2/3, $brotherAmount, 'Asabah');
            $results[] = $this->createResult('Full Sister 1', 'Full Sister', '1/3', 1/3, $sisterAmount, 'Asabah');
            $remaining = 0;
        }
        // Case: Both brothers and sisters
        elseif ($this->fullBrotherCount > 0 && $this->fullSisterCount > 0) {
            $totalShares = ($this->fullBrotherCount * 2) + $this->fullSisterCount;
            $unitValue = $remaining / $totalShares;
            
            for ($i = 1; $i <= $this->fullBrotherCount; $i++) {
                $amount = $unitValue * 2;
                $results[] = $this->createResult("Full Brother $i", 'Full Brother', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
            }
            
            for ($i = 1; $i <= $this->fullSisterCount; $i++) {
                $amount = $unitValue;
                $results[] = $this->createResult("Full Sister $i", 'Full Sister', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
            }
            $remaining = 0;
        }

        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }

        return $this->finalizeResults($results, 7);
    }

    // =========================================================================
    // SCENARIO 8: MATERNAL HALF-SIBLINGS ONLY
    // =========================================================================

    protected function calculateMaternalHalfSiblingsOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 8 - Maternal Half-Siblings Only');
        
        $results = [];
        $remaining = $this->netEstate;
        
        $totalMaternal = $this->maternalHalfBrotherCount + $this->maternalHalfSisterCount;
        
        if ($totalMaternal === 0) {
            return $this->calculateSurplusOnly();
        }
        
        if ($totalMaternal === 1) {
            // Single maternal half-sibling gets 1/6
            $amount = $remaining * (1/6);
            if ($this->maternalHalfBrotherCount > 0) {
                $results[] = $this->createResult('Maternal Half-Brother 1', 'Maternal Half-Brother', '1/6', 1/6, $amount, 'Fixed Share');
            } else {
                $results[] = $this->createResult('Maternal Half-Sister 1', 'Maternal Half-Sister', '1/6', 1/6, $amount, 'Fixed Share');
            }
            $remaining -= $amount;
        } else {
            // Two or more maternal half-siblings get 1/3 total, equally divided
            $totalShare = $remaining * (1/3);
            $perSibling = $totalShare / $totalMaternal;
            
            for ($i = 1; $i <= $this->maternalHalfBrotherCount; $i++) {
                $results[] = $this->createResult("Maternal Half-Brother $i", 'Maternal Half-Brother', '1/3 shared', (1/3) / $totalMaternal, $perSibling, 'Fixed Share');
            }
            
            for ($i = 1; $i <= $this->maternalHalfSisterCount; $i++) {
                $results[] = $this->createResult("Maternal Half-Sister $i", 'Maternal Half-Sister', '1/3 shared', (1/3) / $totalMaternal, $perSibling, 'Fixed Share');
            }
            $remaining -= $totalShare;
        }
        
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }
        
        return $this->finalizeResults($results, 8);
    }

    // =========================================================================
    // SCENARIO 9: PATERNAL HALF-SIBLINGS ONLY
    // =========================================================================

    protected function calculatePaternalHalfSiblingsOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 9 - Paternal Half-Siblings Only');
        
        $results = [];
        $remaining = $this->netEstate;
        
        // Case: Only paternal half-brothers
        if ($this->paternalHalfBrotherCount > 0 && $this->paternalHalfSisterCount === 0) {
            if ($this->paternalHalfBrotherCount === 1) {
                $perBrother = $remaining;
                $results[] = $this->createResult('Paternal Half-Brother 1', 'Paternal Half-Brother', '1/1', 1, $perBrother, 'Asabah');
            } else {
                $perBrother = $remaining / $this->paternalHalfBrotherCount;
                for ($i = 1; $i <= $this->paternalHalfBrotherCount; $i++) {
                    $results[] = $this->createResult("Paternal Half-Brother $i", 'Paternal Half-Brother', "1/{$this->paternalHalfBrotherCount}", 1 / $this->paternalHalfBrotherCount, $perBrother, 'Asabah');
                }
            }
            $remaining = 0;
        }
        // Case: Only paternal half-sisters
        elseif ($this->paternalHalfBrotherCount === 0 && $this->paternalHalfSisterCount > 0) {
            if ($this->paternalHalfSisterCount === 1) {
                $sisterAmount = $remaining * 0.5;
                $results[] = $this->createResult('Paternal Half-Sister 1', 'Paternal Half-Sister', '1/2', 0.5, $sisterAmount, 'Fixed Share');
                $remaining -= $sisterAmount;
            } else {
                $totalSistersShare = $remaining * (2/3);
                $perSister = $totalSistersShare / $this->paternalHalfSisterCount;
                
                for ($i = 1; $i <= $this->paternalHalfSisterCount; $i++) {
                    $results[] = $this->createResult("Paternal Half-Sister $i", 'Paternal Half-Sister', '2/3 shared', (2/3) / $this->paternalHalfSisterCount, $perSister, 'Fixed Share');
                }
                $remaining -= $totalSistersShare;
            }
        }
        // Case: Both paternal half-brothers and half-sisters
        else {
            $totalShares = ($this->paternalHalfBrotherCount * 2) + $this->paternalHalfSisterCount;
            $unitValue = $remaining / $totalShares;
            
            for ($i = 1; $i <= $this->paternalHalfBrotherCount; $i++) {
                $amount = $unitValue * 2;
                $results[] = $this->createResult("Paternal Half-Brother $i", 'Paternal Half-Brother', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
            }
            
            for ($i = 1; $i <= $this->paternalHalfSisterCount; $i++) {
                $amount = $unitValue;
                $results[] = $this->createResult("Paternal Half-Sister $i", 'Paternal Half-Sister', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
            }
            $remaining = 0;
        }
        
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }
        
        return $this->finalizeResults($results, 9);
    }

    // =========================================================================
    // SCENARIO 10: MIXED SIBLINGS WITH AWL (OVER-SUBSCRIPTION)
    // =========================================================================

    protected function calculateMixedSiblingsAwl(): array
    {
        Log::info('FaraidCalculator: Scenario 10 - Mixed Siblings with Awl');
        
        $results = [];
        $remaining = $this->netEstate;
        
        // Calculate shares for different sibling types
        $fullTotal = ($this->fullBrotherCount * 2) + $this->fullSisterCount;
        
        // Maternal half-siblings get 1/3 of total estate (fixed share)
        $maternalCount = $this->maternalHalfBrotherCount + $this->maternalHalfSisterCount;
        
        // Paternal half-siblings are Asabah
        $paternalTotal = ($this->paternalHalfBrotherCount * 2) + $this->paternalHalfSisterCount;
        
        // Calculate total required shares
        $totalShares = $fullTotal + $paternalTotal + ($maternalCount > 0 ? 1 : 0);
        
        // Check for Awl (over-subscription)
        if ($totalShares > 0) {
            // Apply Awl reduction
            $awlFactor = 1 / $totalShares;
            
            // Distribute maternal half-siblings share (1/3 of estate, then reduced by Awl)
            if ($maternalCount > 0) {
                $totalMaternalAmount = $remaining * (1/3) * $awlFactor;
                $perMaternal = $totalMaternalAmount / $maternalCount;
                
                for ($i = 1; $i <= $this->maternalHalfBrotherCount; $i++) {
                    $results[] = $this->createResult("Maternal Half-Brother $i", 'Maternal Half-Brother', "Awl ({$awlFactor})", null, $perMaternal, 'Fixed Share (Awl)');
                }
                
                for ($i = 1; $i <= $this->maternalHalfSisterCount; $i++) {
                    $results[] = $this->createResult("Maternal Half-Sister $i", 'Maternal Half-Sister', "Awl ({$awlFactor})", null, $perMaternal, 'Fixed Share (Awl)');
                }
            }
            
            // Distribute full siblings
            if ($fullTotal > 0) {
                $fullTotalAmount = $remaining * $awlFactor * ($fullTotal / $totalShares);
                $unitValue = $fullTotalAmount / $fullTotal;
                
                for ($i = 1; $i <= $this->fullBrotherCount; $i++) {
                    $amount = $unitValue * 2;
                    $results[] = $this->createResult("Full Brother $i", 'Full Brother', "Asabah (Awl {$awlFactor})", null, $amount, 'Asabah (Awl)');
                }
                
                for ($i = 1; $i <= $this->fullSisterCount; $i++) {
                    $amount = $unitValue;
                    $results[] = $this->createResult("Full Sister $i", 'Full Sister', "Asabah (Awl {$awlFactor})", null, $amount, 'Asabah (Awl)');
                }
            }
            
            // Distribute paternal half-siblings
            if ($paternalTotal > 0) {
                $paternalTotalAmount = $remaining * $awlFactor * ($paternalTotal / $totalShares);
                $unitValue = $paternalTotalAmount / $paternalTotal;
                
                for ($i = 1; $i <= $this->paternalHalfBrotherCount; $i++) {
                    $amount = $unitValue * 2;
                    $results[] = $this->createResult("Paternal Half-Brother $i", 'Paternal Half-Brother', "Asabah (Awl {$awlFactor})", null, $amount, 'Asabah (Awl)');
                }
                
                for ($i = 1; $i <= $this->paternalHalfSisterCount; $i++) {
                    $amount = $unitValue;
                    $results[] = $this->createResult("Paternal Half-Sister $i", 'Paternal Half-Sister', "Asabah (Awl {$awlFactor})", null, $amount, 'Asabah (Awl)');
                }
            }
            
            return $this->finalizeResults($results, 10, true, $awlFactor);
        }
        
        // Normal distribution (no Awl)
        return $this->calculateSiblingsOnlyNormal();
    }

    protected function calculateSiblingsOnlyNormal(): array
    {
        $results = [];
        $remaining = $this->netEstate;
        
        // Distribute maternal half-siblings (1/3 of estate)
        $maternalCount = $this->maternalHalfBrotherCount + $this->maternalHalfSisterCount;
        if ($maternalCount > 0) {
            $totalMaternalAmount = $remaining * (1/3);
            $perMaternal = $totalMaternalAmount / $maternalCount;
            
            for ($i = 1; $i <= $this->maternalHalfBrotherCount; $i++) {
                $results[] = $this->createResult("Maternal Half-Brother $i", 'Maternal Half-Brother', '1/3 shared', (1/3) / $maternalCount, $perMaternal, 'Fixed Share');
            }
            
            for ($i = 1; $i <= $this->maternalHalfSisterCount; $i++) {
                $results[] = $this->createResult("Maternal Half-Sister $i", 'Maternal Half-Sister', '1/3 shared', (1/3) / $maternalCount, $perMaternal, 'Fixed Share');
            }
            $remaining -= $totalMaternalAmount;
        }
        
        // Distribute full siblings and paternal half-siblings as Asabah
        if ($remaining > 0.01) {
            $fullTotal = ($this->fullBrotherCount * 2) + $this->fullSisterCount;
            $paternalTotal = ($this->paternalHalfBrotherCount * 2) + $this->paternalHalfSisterCount;
            $totalUnits = $fullTotal + $paternalTotal;
            
            if ($totalUnits > 0) {
                $unitValue = $remaining / $totalUnits;
                
                // Full siblings
                for ($i = 1; $i <= $this->fullBrotherCount; $i++) {
                    $amount = $unitValue * 2;
                    $results[] = $this->createResult("Full Brother $i", 'Full Brother', 'Asabah', (2 / $totalUnits), $amount, 'Asabah');
                }
                
                for ($i = 1; $i <= $this->fullSisterCount; $i++) {
                    $amount = $unitValue;
                    $results[] = $this->createResult("Full Sister $i", 'Full Sister', 'Asabah', (1 / $totalUnits), $amount, 'Asabah');
                }
                
                // Paternal half-siblings
                for ($i = 1; $i <= $this->paternalHalfBrotherCount; $i++) {
                    $amount = $unitValue * 2;
                    $results[] = $this->createResult("Paternal Half-Brother $i", 'Paternal Half-Brother', 'Asabah', (2 / $totalUnits), $amount, 'Asabah');
                }
                
                for ($i = 1; $i <= $this->paternalHalfSisterCount; $i++) {
                    $amount = $unitValue;
                    $results[] = $this->createResult("Paternal Half-Sister $i", 'Paternal Half-Sister', 'Asabah', (1 / $totalUnits), $amount, 'Asabah');
                }
                $remaining = 0;
            }
        }
        
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }
        
        return $this->finalizeResults($results, 10);
    }

    // =========================================================================
    // SCENARIO 11: SPOUSE AND SIBLINGS
    // =========================================================================

    protected function calculateSpouseAndSiblings(): array
    {
        Log::info('FaraidCalculator: Scenario 11 - Spouse and Siblings');
        
        $results = [];
        $remaining = $this->netEstate;
        
        // Step 1: Spouse gets fixed share
        if ($this->hasHusband) {
            $husbandAmount = $this->netEstate * 0.5;
            $results[] = $this->createResult('Husband', 'Husband', '1/2', 0.5, $husbandAmount, 'Fixed Share');
            $remaining -= $husbandAmount;
        } elseif ($this->hasWife) {
            $totalWifeAmount = $this->netEstate * 0.25;
            $individualAmount = $totalWifeAmount / $this->wifeCount;
            
            for ($i = 1; $i <= $this->wifeCount; $i++) {
                $results[] = $this->createResult("Wife $i", 'Wife', '1/4', 0.25 / $this->wifeCount, $individualAmount, 'Fixed Share');
            }
            $remaining -= $totalWifeAmount;
        }
        
        // Step 2: Siblings share the remaining
        if ($remaining > 0.01) {
            $siblingResults = $this->calculateSiblingShares($remaining);
            $results = array_merge($results, $siblingResults);
            $remaining = 0;
        }
        
        return $this->finalizeResults($results, 11);
    }

    protected function calculateSiblingShares($availableAmount): array
    {
        $results = [];
        
        // Full siblings are preferred over paternal half-siblings
        if ($this->hasFullSiblings) {
            $totalShares = ($this->fullBrotherCount * 2) + $this->fullSisterCount;
            $unitValue = $availableAmount / $totalShares;
            
            for ($i = 1; $i <= $this->fullBrotherCount; $i++) {
                $amount = $unitValue * 2;
                $results[] = $this->createResult("Full Brother $i", 'Full Brother', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
            }
            
            for ($i = 1; $i <= $this->fullSisterCount; $i++) {
                $amount = $unitValue;
                $results[] = $this->createResult("Full Sister $i", 'Full Sister', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
            }
        } elseif ($this->hasPaternalSiblings) {
            $totalShares = ($this->paternalHalfBrotherCount * 2) + $this->paternalHalfSisterCount;
            $unitValue = $availableAmount / $totalShares;
            
            for ($i = 1; $i <= $this->paternalHalfBrotherCount; $i++) {
                $amount = $unitValue * 2;
                $results[] = $this->createResult("Paternal Half-Brother $i", 'Paternal Half-Brother', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
            }
            
            for ($i = 1; $i <= $this->paternalHalfSisterCount; $i++) {
                $amount = $unitValue;
                $results[] = $this->createResult("Paternal Half-Sister $i", 'Paternal Half-Sister', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
            }
        } elseif ($this->hasMaternalSiblings) {
            $totalMaternal = $this->maternalHalfBrotherCount + $this->maternalHalfSisterCount;
            $perSibling = $availableAmount / $totalMaternal;
            
            for ($i = 1; $i <= $this->maternalHalfBrotherCount; $i++) {
                $results[] = $this->createResult("Maternal Half-Brother $i", 'Maternal Half-Brother', 'Equal', 1 / $totalMaternal, $perSibling, 'Fixed Share');
            }
            
            for ($i = 1; $i <= $this->maternalHalfSisterCount; $i++) {
                $results[] = $this->createResult("Maternal Half-Sister $i", 'Maternal Half-Sister', 'Equal', 1 / $totalMaternal, $perSibling, 'Fixed Share');
            }
        }
        
        return $results;
    }

    // =========================================================================
    // SCENARIO 12: AWL (OVER-SUBSCRIPTION) CASES
    // =========================================================================

    protected function checkAndHandleAwlCases(): ?array
    {
        // Case: One Wife, Mother, and Two Sisters (Full)
        if ($this->hasWife && $this->wifeCount === 1 && $this->hasMother && $this->fullSisterCount === 2 && !$this->hasSon && !$this->hasFather && !$this->hasBrother) {
            return $this->calculateAwlWifeMotherTwoSisters();
        }
        
        // Case: Husband and Two Sisters (Full)
        if ($this->hasHusband && $this->fullSisterCount === 2 && !$this->hasWife && !$this->hasParents() && !$this->hasChildren()) {
            return $this->calculateAwlHusbandTwoSisters();
        }
        
        // Case: Husband, Mother, Father (Awl)
        if ($this->hasHusband && $this->hasFather && $this->hasMother && !$this->hasChildren()) {
            return $this->calculateAwlHusbandMotherFather();
        }
        
        // Case: Wife, Mother, Father (Awl)
        if ($this->hasWife && $this->wifeCount === 1 && $this->hasFather && $this->hasMother && !$this->hasChildren()) {
            return $this->calculateAwlWifeMotherFather();
        }
        
        return null;
    }

    protected function calculateAwlWifeMotherTwoSisters(): array
    {
        Log::info('FaraidCalculator: Awl Case - Wife, Mother, and Two Sisters');
        
        $results = [];
        
        // Shares without Awl:
        // Wife: 1/8 = 3/24
        // Mother: 1/6 = 4/24  
        // Two Sisters: 2/3 = 16/24
        // Total: 23/24 -> Need Awl to 24/24
        
        // Apply Awl factor (24/23)
        $factor = 24 / 23;
        
        $wifeAmount = $this->netEstate * (1/8) * $factor;
        $motherAmount = $this->netEstate * (1/6) * $factor;
        $perSisterAmount = $this->netEstate * (2/3) * $factor / 2;
        
        $results[] = $this->createResult('Wife 1', 'Wife', '3/13', null, $wifeAmount, 'Fixed Share (Awl)');
        $results[] = $this->createResult('Mother', 'Mother', '4/13', null, $motherAmount, 'Fixed Share (Awl)');
        
        for ($i = 1; $i <= 2; $i++) {
            $results[] = $this->createResult("Full Sister $i", 'Full Sister', '4/13', null, $perSisterAmount, 'Fixed Share (Awl)');
        }
        
        return $this->finalizeResults($results, 12, true, $factor);
    }

    protected function calculateAwlHusbandTwoSisters(): array
    {
        Log::info('FaraidCalculator: Awl Case - Husband and Two Sisters');
        
        $results = [];
        
        // Shares without Awl:
        // Husband: 1/2 = 3/6
        // Two Sisters: 2/3 = 4/6
        // Total: 7/6 -> Need Awl to 7/7
        
        $husbandAmount = $this->netEstate * (3/7);
        $perSisterAmount = $this->netEstate * (2/7);
        
        $results[] = $this->createResult('Husband', 'Husband', '3/7', null, $husbandAmount, 'Fixed Share (Awl)');
        
        for ($i = 1; $i <= 2; $i++) {
            $results[] = $this->createResult("Full Sister $i", 'Full Sister', '2/7', null, $perSisterAmount, 'Fixed Share (Awl)');
        }
        
        return $this->finalizeResults($results, 12, true, 1);
    }

    protected function calculateAwlHusbandMotherFather(): array
    {
        Log::info('FaraidCalculator: Awl Case - Husband, Mother, Father');
        
        $results = [];
        
        // Husband gets 1/2 = 3/6
        // Mother gets 1/3 = 2/6
        // Father gets 1/6 = 1/6
        
        $husbandAmount = $this->netEstate * 0.5;
        $motherAmount = $this->netEstate * (1/3);
        $fatherAmount = $this->netEstate * (1/6);
        
        $results[] = $this->createResult('Husband', 'Husband', '1/2', 0.5, $husbandAmount, 'Fixed Share');
        $results[] = $this->createResult('Mother', 'Mother', '1/3', 1/3, $motherAmount, 'Fixed Share');
        $results[] = $this->createResult('Father', 'Father', '1/6', 1/6, $fatherAmount, 'Asabah');
        
        return $this->finalizeResults($results, 12, false, 1);
    }

    protected function calculateAwlWifeMotherFather(): array
    {
        Log::info('FaraidCalculator: Awl Case - Wife, Mother, Father');
        
        $results = [];
        
        // Wife gets 1/4 = 3/12
        // Mother gets 1/3 = 4/12
        // Father gets 1/6 = 2/12
        // Total: 9/12 -> Awl to 9/9
        
        $wifeAmount = $this->netEstate * (3/9);
        $motherAmount = $this->netEstate * (4/9);
        $fatherAmount = $this->netEstate * (2/9);
        
        $results[] = $this->createResult('Wife 1', 'Wife', '1/3', null, $wifeAmount, 'Fixed Share (Awl)');
        $results[] = $this->createResult('Mother', 'Mother', '4/9', null, $motherAmount, 'Fixed Share (Awl)');
        $results[] = $this->createResult('Father', 'Father', '2/9', null, $fatherAmount, 'Asabah (Awl)');
        
        return $this->finalizeResults($results, 12, true, 1);
    }

    // =========================================================================
    // SCENARIO 13: GRANDPARENTS ONLY
    // =========================================================================

    protected function calculateGrandparentsOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 13 - Grandparents Only');
        
        $results = [];
        $remaining = $this->netEstate;
        
        $grandparentCount = ($this->hasGrandfather ? 1 : 0) + ($this->hasGrandmother ? 1 : 0);
        
        if ($grandparentCount === 1) {
            // Single grandparent gets 1/6
            $amount = $remaining * (1/6);
            $heirName = $this->hasGrandfather ? 'Paternal Grandfather' : 'Grandmother';
            $relationship = $this->hasGrandfather ? 'Paternal Grandfather' : 'Grandmother';
            $results[] = $this->createResult($heirName, $relationship, '1/6', 1/6, $amount, 'Fixed Share');
            $remaining -= $amount;
        } elseif ($grandparentCount === 2) {
            // Two grandparents get 1/6 total, equally divided
            $totalShare = $remaining * (1/6);
            $perGrandparent = $totalShare / 2;
            
            if ($this->hasGrandfather) {
                $results[] = $this->createResult('Paternal Grandfather', 'Paternal Grandfather', '1/12', 1/12, $perGrandparent, 'Fixed Share');
            }
            if ($this->hasGrandmother) {
                $results[] = $this->createResult('Grandmother', 'Grandmother', '1/12', 1/12, $perGrandparent, 'Fixed Share');
            }
            $remaining -= $totalShare;
        }
        
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }
        
        return $this->finalizeResults($results, 13);
    }

    // =========================================================================
    // SCENARIO 14: MULTIPLE WIVES SCENARIO
    // =========================================================================

    protected function calculateMultipleWivesScenario(): array
    {
        Log::info('FaraidCalculator: Scenario 14 - Multiple Wives');
        
        $results = [];
        $remaining = $this->netEstate;
        
        // Multiple wives scenario - wives share 1/8 or 1/4 equally among themselves
        if ($this->hasWife) {
            $wifeShareFraction = $this->hasChildren() ? 0.125 : 0.25;
            $totalWifeAmount = $this->netEstate * $wifeShareFraction;
            $individualAmount = $totalWifeAmount / $this->wifeCount;
            
            // Case: Three wives and no children - each wife gets 1/12
            if ($this->wifeCount === 3 && !$this->hasChildren()) {
                $individualAmount = $this->netEstate * (1/12);
                for ($i = 1; $i <= $this->wifeCount; $i++) {
                    $results[] = $this->createResult("Wife $i", 'Wife', '1/12', 1/12, $individualAmount, 'Fixed Share');
                }
                $totalWifeAmount = $individualAmount * 3;
                $remaining -= $totalWifeAmount;
            } else {
                for ($i = 1; $i <= $this->wifeCount; $i++) {
                    $results[] = $this->createResult("Wife $i", 'Wife', $wifeShareFraction == 0.125 ? '1/8' : '1/4', $wifeShareFraction / $this->wifeCount, $individualAmount, 'Fixed Share');
                }
                $remaining -= $totalWifeAmount;
            }
        }
        
        // Distribute remaining to other heirs
        if ($remaining > 0.01) {
            if ($this->hasChildren()) {
                $totalShares = ($this->sonCount * 2) + $this->daughterCount;
                if ($totalShares > 0) {
                    $unitValue = $remaining / $totalShares;
                    
                    for ($i = 1; $i <= $this->sonCount; $i++) {
                        $amount = $unitValue * 2;
                        $results[] = $this->createResult("Son $i", 'Son', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
                    }
                    
                    for ($i = 1; $i <= $this->daughterCount; $i++) {
                        $amount = $unitValue;
                        $results[] = $this->createResult("Daughter $i", 'Daughter', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
                    }
                    $remaining = 0;
                }
            } elseif ($this->hasParents()) {
                if ($this->hasFather && $this->hasMother) {
                    $motherAmount = $remaining * (1/3);
                    $fatherAmount = $remaining * (2/3);
                    $results[] = $this->createResult('Mother', 'Mother', '1/3 of remaining', 1/3, $motherAmount, 'Fixed Share');
                    $results[] = $this->createResult('Father', 'Father', '2/3 of remaining', 2/3, $fatherAmount, 'Asabah');
                    $remaining = 0;
                } elseif ($this->hasFather) {
                    $results[] = $this->createResult('Father', 'Father', 'All remaining', 1, $remaining, 'Asabah');
                    $remaining = 0;
                } elseif ($this->hasMother) {
                    $motherAmount = $remaining * (1/3);
                    $results[] = $this->createResult('Mother', 'Mother', '1/3 of remaining', 1/3, $motherAmount, 'Fixed Share');
                    $remaining -= $motherAmount;
                }
            }
        }
        
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }
        
        return $this->finalizeResults($results, 14);
    }

    // =========================================================================
    // SCENARIO 15: SURPLUS ONLY (BAITULMAL)
    // =========================================================================

    protected function calculateSurplusOnly(): array
    {
        Log::info('FaraidCalculator: Scenario 15 - Surplus Only (Baitulmal)');
        
        $results = [];
        
        $results[] = $this->createResult('Baitulmal', 'State Treasury', '1/1', 1, $this->netEstate, 'Surplus');
        
        return $this->finalizeResults($results, 15);
    }

    // =========================================================================
    // COMBINED DISTRIBUTION FOR COMPLEX SCENARIOS
    // =========================================================================

    protected function calculateCombinedDistribution(): array
    {
        Log::info('FaraidCalculator: Combined Distribution for Complex Scenario');
        
        $results = [];
        $remaining = $this->netEstate;
        
        // Fixed shares for eligible heirs
        if ($this->hasHusband) {
            $share = $this->hasChildren() ? 0.25 : 0.5;
            $amount = $this->netEstate * $share;
            $results[] = $this->createResult('Husband', 'Husband', $share == 0.25 ? '1/4' : '1/2', $share, $amount, 'Fixed Share');
            $remaining -= $amount;
        }
        
        if ($this->hasWife) {
            $share = $this->hasChildren() ? 0.125 : 0.25;
            $totalAmount = $this->netEstate * $share;
            $individualAmount = $totalAmount / $this->wifeCount;
            
            for ($i = 1; $i <= $this->wifeCount; $i++) {
                $results[] = $this->createResult("Wife $i", 'Wife', $share == 0.125 ? '1/8' : '1/4', $share / $this->wifeCount, $individualAmount, 'Fixed Share');
            }
            $remaining -= $totalAmount;
        }
        
        if ($this->hasFather) {
            $share = $this->hasChildren() ? 1/6 : (1/6);
            $amount = $this->netEstate * $share;
            $results[] = $this->createResult('Father', 'Father', '1/6', 1/6, $amount, 'Fixed Share');
            $remaining -= $amount;
        }
        
        if ($this->hasMother) {
            if ($this->hasChildren() || ($this->hasFather && $this->hasMother && !$this->hasChildren())) {
                $share = 1/6;
            } else {
                $share = 1/3;
            }
            $amount = $this->netEstate * $share;
            $results[] = $this->createResult('Mother', 'Mother', $share == 1/6 ? '1/6' : '1/3', $share, $amount, 'Fixed Share');
            $remaining -= $amount;
        }
        
        // Children as Asabah
        if ($remaining > 0.01 && ($this->hasSon || $this->hasDaughter)) {
            $totalShares = ($this->sonCount * 2) + $this->daughterCount;
            if ($totalShares > 0) {
                $unitValue = $remaining / $totalShares;
                
                for ($i = 1; $i <= $this->sonCount; $i++) {
                    $amount = $unitValue * 2;
                    $results[] = $this->createResult("Son $i", 'Son', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
                }
                
                for ($i = 1; $i <= $this->daughterCount; $i++) {
                    $amount = $unitValue;
                    $results[] = $this->createResult("Daughter $i", 'Daughter', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
                }
                $remaining = 0;
            }
        }
        
        // Siblings as Asabah if no children and remaining estate
        if ($remaining > 0.01 && !$this->hasChildren() && $this->hasAnySiblings()) {
            if ($this->hasFullSiblings) {
                $totalShares = ($this->fullBrotherCount * 2) + $this->fullSisterCount;
                if ($totalShares > 0) {
                    $unitValue = $remaining / $totalShares;
                    
                    for ($i = 1; $i <= $this->fullBrotherCount; $i++) {
                        $amount = $unitValue * 2;
                        $results[] = $this->createResult("Full Brother $i", 'Full Brother', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
                    }
                    
                    for ($i = 1; $i <= $this->fullSisterCount; $i++) {
                        $amount = $unitValue;
                        $results[] = $this->createResult("Full Sister $i", 'Full Sister', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
                    }
                    $remaining = 0;
                }
            } elseif ($this->hasPaternalSiblings) {
                $totalShares = ($this->paternalHalfBrotherCount * 2) + $this->paternalHalfSisterCount;
                if ($totalShares > 0) {
                    $unitValue = $remaining / $totalShares;
                    
                    for ($i = 1; $i <= $this->paternalHalfBrotherCount; $i++) {
                        $amount = $unitValue * 2;
                        $results[] = $this->createResult("Paternal Half-Brother $i", 'Paternal Half-Brother', 'Asabah', (2 / $totalShares), $amount, 'Asabah');
                    }
                    
                    for ($i = 1; $i <= $this->paternalHalfSisterCount; $i++) {
                        $amount = $unitValue;
                        $results[] = $this->createResult("Paternal Half-Sister $i", 'Paternal Half-Sister', 'Asabah', (1 / $totalShares), $amount, 'Asabah');
                    }
                    $remaining = 0;
                }
            } elseif ($this->hasMaternalSiblings) {
                $totalMaternal = $this->maternalHalfBrotherCount + $this->maternalHalfSisterCount;
                $perSibling = $remaining / $totalMaternal;
                
                for ($i = 1; $i <= $this->maternalHalfBrotherCount; $i++) {
                    $results[] = $this->createResult("Maternal Half-Brother $i", 'Maternal Half-Brother', 'Equal Share', 1 / $totalMaternal, $perSibling, 'Fixed Share');
                }
                
                for ($i = 1; $i <= $this->maternalHalfSisterCount; $i++) {
                    $results[] = $this->createResult("Maternal Half-Sister $i", 'Maternal Half-Sister', 'Equal Share', 1 / $totalMaternal, $perSibling, 'Fixed Share');
                }
                $remaining = 0;
            }
        }
        
        if ($remaining > 0.01) {
            $results[] = $this->createResult('Baitulmal', 'State Treasury', 'Surplus', $remaining / $this->netEstate, $remaining, 'Surplus');
        }
        
        return $this->finalizeResults($results, 15);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    protected function createResult($heir, $relationship, $share, $fraction, $amount, $status): array
    {
        $percentage = $this->netEstate > 0 ? round(($amount / $this->netEstate) * 100, 2) : 0;
        
        return [
            'heir' => $heir,
            'relationship' => $relationship,
            'share' => $share,
            'fraction' => $fraction,
            'amount' => round($amount, 2),
            'formatted_amount' => 'RM ' . number_format(round($amount, 2), 2),
            'percentage' => $percentage,
            'status' => $status,
            'type' => $status === 'Surplus' ? 'Surplus' : ($status === 'Asabah' || strpos($status, 'Asabah') !== false ? 'Asabah' : 'Fixed Share')
        ];
    }

    protected function finalizeResults(array $results, int $scenarioNumber, bool $awlApplied = false, float $awlFactor = 1): array
    {
        // Filter out zero amounts
        $filteredResults = array_filter($results, function($result) {
            return ($result['amount'] ?? 0) > 0.01;
        });
        
        $totalDistributed = array_sum(array_column($filteredResults, 'amount'));
        $totalEligible = count(array_filter($filteredResults, function($r) {
            return ($r['status'] ?? '') !== 'Surplus';
        }));
        
        return [
            'distribution' => array_values($filteredResults),
            'total_distributed' => round($totalDistributed, 2),
            'total_eligible' => $totalEligible,
            'scenario' => $this->getScenarioDescription($scenarioNumber),
            'scenario_number' => $scenarioNumber,
            'net_estate' => $this->netEstate,
            'awl_applied' => $awlApplied,
            'awl_factor' => $awlFactor
        ];
    }

    protected function getScenarioDescription($scenario): string
    {
        $descriptions = [
            1 => 'Spouse Only Inheritance',
            2 => 'Spouse and Parents (No Children)',
            3 => 'Spouse and Children',
            4 => 'Children Only',
            5 => 'Parents and Children',
            6 => 'Parents Only',
            7 => 'Full Siblings Only',
            8 => 'Maternal Half-Siblings Only',
            9 => 'Paternal Half-Siblings Only',
            10 => 'Mixed Siblings Distribution',
            11 => 'Spouse and Siblings',
            12 => 'Awl (Over-subscription) Case',
            13 => 'Grandparents Only',
            14 => 'Multiple Wives Scenario',
            15 => 'Combined Distribution'
        ];
        
        return $descriptions[$scenario] ?? 'Standard Faraid Distribution';
    }

    public function getCalculationResults(): array
    {
        return $this->calculationResults;
    }

    public function setNetEstate(float $netEstate): self
    {
        $this->netEstate = $netEstate;
        return $this;
    }

    public function setHeirsData(array $heirsData): self
    {
        $this->analyzeHeirsFromArray($heirsData);
        return $this;
    }
    
    public function setDeceasedGender(string $gender): self
    {
        $this->deceasedGender = $gender;
        return $this;
    }
}