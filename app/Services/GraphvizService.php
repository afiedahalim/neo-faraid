<?php

namespace App\Services;

use App\Models\Calculation;

class GraphvizService
{
    public function generateFamilyTree(Calculation $calculation)
    {
        try {
            // Generate DOT code for the family tree
            $dotCode = $this->generateDotCode($calculation);
            
            return $dotCode; // Return string DOT code
            
        } catch (\Exception $e) {
            Log::error('GraphvizService error: ' . $e->getMessage());
            
            // Return error as array with success false
            return [
                'success' => false,
                'message' => 'Failed to generate family tree: ' . $e->getMessage(),
                'dot_code' => null
            ];
        }
    }
    
    private function generateDotCode(Calculation $calculation)
    {
        $deceased = $calculation->deceased_name;
        $gender = $calculation->deceased_gender;
        
        $dotCode = "digraph FamilyTree {\n";
        $dotCode .= "    rankdir=TB;\n";
        $dotCode .= "    node [shape=box, style=filled, fillcolor=lightblue];\n";
        $dotCode .= "    edge [dir=none];\n\n";
        
        // Deceased person
        $deceasedColor = ($gender === 'male') ? 'lightblue' : 'lightpink';
        $dotCode .= "    deceased [label=\"Deceased: {$deceased}\\n({$gender})\", fillcolor=\"{$deceasedColor}\"];\n\n";
        
        // Get heirs data
        $heirsData = $calculation->heirs_data ?? [];
        $spouseId = 1;
        $childId = 1;
        $parentId = 1;
        
        // Add spouses
        if (isset($heirsData['marital_status']) && $heirsData['marital_status'] === 'married') {
            if ($gender === 'male' && !empty($heirsData['wife_count']) && $heirsData['wife_count'] > 0) {
                $wifeCount = min($heirsData['wife_count'], 4);
                for ($i = 1; $i <= $wifeCount; $i++) {
                    $dotCode .= "    spouse{$spouseId} [label=\"Wife {$i}\", fillcolor=\"lightpink\"];\n";
                    $dotCode .= "    deceased -> spouse{$spouseId} [label=\"spouse\"];\n";
                    $spouseId++;
                }
            } elseif ($gender === 'female' && !empty($heirsData['husband_count']) && $heirsData['husband_count'] > 0) {
                $dotCode .= "    spouse{$spouseId} [label=\"Husband\", fillcolor=\"lightblue\"];\n";
                $dotCode .= "    deceased -> spouse{$spouseId} [label=\"spouse\"];\n";
                $spouseId++;
            }
        }
        
        // Add parents
        if (!empty($heirsData['father_status']) && $heirsData['father_status'] === 'alive') {
            $dotCode .= "    parent{$parentId} [label=\"Father\", fillcolor=\"lightblue\"];\n";
            $dotCode .= "    parent{$parentId} -> deceased [label=\"parent\"];\n";
            $parentId++;
        }
        
        if (!empty($heirsData['mother_status']) && $heirsData['mother_status'] === 'alive') {
            $dotCode .= "    parent{$parentId} [label=\"Mother\", fillcolor=\"lightpink\"];\n";
            $dotCode .= "    parent{$parentId} -> deceased [label=\"parent\"];\n";
            $parentId++;
        }
        
        // Add children
        if (!empty($heirsData['son_count']) && $heirsData['son_count'] > 0) {
            for ($i = 1; $i <= $heirsData['son_count']; $i++) {
                $dotCode .= "    child{$childId} [label=\"Son {$i}\", fillcolor=\"lightblue\"];\n";
                $dotCode .= "    deceased -> child{$childId} [label=\"child\"];\n";
                $childId++;
            }
        }
        
        if (!empty($heirsData['daughter_count']) && $heirsData['daughter_count'] > 0) {
            for ($i = 1; $i <= $heirsData['daughter_count']; $i++) {
                $dotCode .= "    child{$childId} [label=\"Daughter {$i}\", fillcolor=\"lightpink\"];\n";
                $dotCode .= "    deceased -> child{$childId} [label=\"child\"];\n";
                $childId++;
            }
        }
        
        // Add grandchildren
        if (!empty($heirsData['grandson_count']) && $heirsData['grandson_count'] > 0) {
            for ($i = 1; $i <= $heirsData['grandson_count']; $i++) {
                $dotCode .= "    grandchild{$childId} [label=\"Grandson {$i}\", fillcolor=\"lightblue\"];\n";
                $dotCode .= "    deceased -> grandchild{$childId} [label=\"grandchild\"];\n";
                $childId++;
            }
        }
        
        if (!empty($heirsData['granddaughter_count']) && $heirsData['granddaughter_count'] > 0) {
            for ($i = 1; $i <= $heirsData['granddaughter_count']; $i++) {
                $dotCode .= "    grandchild{$childId} [label=\"Granddaughter {$i}\", fillcolor=\"lightpink\"];\n";
                $dotCode .= "    deceased -> grandchild{$childId} [label=\"grandchild\"];\n";
                $childId++;
            }
        }
        
        $dotCode .= "}\n";
        
        return $dotCode;
    }
}