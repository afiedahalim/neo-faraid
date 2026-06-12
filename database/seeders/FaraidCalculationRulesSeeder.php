<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FaraidCalculationRule;

class FaraidCalculationRulesSeeder extends Seeder
{
    public function run(): void
    {
        // Check if table exists and has data
        if (FaraidCalculationRule::count() > 0) {
            $this->command->info('Faraid calculation rules already seeded. Skipping...');
            return;
        }
        
        $scenarios = [
            // 1. Spouse Only
            [
                'scenario_number' => 1,
                'scenario_name' => 'Spouse Only',
                'scenario_description' => 'Deceased leaves only spouse with no children, parents, or siblings',
                'priority' => 1,
                'conditions' => json_encode([
                    'spouse_present' => true,
                    'children_present' => false,
                    'parents_present' => false,
                    'siblings_present' => false,
                    'substitute_heirs_present' => false
                ]),
                'distribution_rules' => json_encode([
                    'husband_only' => [
                        'husband' => ['share' => '1/2', 'fraction' => 0.5],
                        'baitulmal' => ['share' => '1/2', 'fraction' => 0.5]
                    ],
                    'wife_only' => [
                        'wife' => ['share' => '1/4', 'fraction' => 0.25],
                        'baitulmal' => ['share' => '3/4', 'fraction' => 0.75]
                    ],
                    'multiple_wives_only' => [
                        'each_wife' => ['share' => '1/8', 'fraction' => 0.125],
                        'baitulmal' => ['share' => 'remaining', 'fraction' => null]
                    ]
                ]),
                'calculation_logic' => 'IF deceased_gender = "male" AND wife_count = 1 AND no_other_heirs THEN wife: 1/4, baitulmal: 3/4
IF deceased_gender = "female" AND husband_count = 1 AND no_other_heirs THEN husband: 1/2, baitulmal: 1/2
IF deceased_gender = "male" AND wife_count > 1 AND no_other_heirs THEN each_wife: 1/8 (total for all wives), baitulmal: remaining',
                'is_active' => true
            ],
            
            // 2. Spouse and Parents (No Children)
            [
                'scenario_number' => 2,
                'scenario_name' => 'Spouse and Parents (No Children)',
                'scenario_description' => 'Deceased leaves spouse and parents with no children',
                'priority' => 2,
                'conditions' => json_encode([
                    'spouse_present' => true,
                    'children_present' => false,
                    'parents_present' => true,
                    'siblings_present' => false
                ]),
                'distribution_rules' => json_encode([
                    'husband_father' => [
                        'husband' => ['share' => '1/2', 'fraction' => 0.5],
                        'father' => ['share' => '1/2', 'fraction' => 0.5]
                    ],
                    'husband_mother' => [
                        'husband' => ['share' => '3/6', 'fraction' => 0.5],
                        'mother' => ['share' => '2/6', 'fraction' => 0.3333],
                        'baitulmal' => ['share' => '1/6', 'fraction' => 0.1667]
                    ],
                    'wife_father' => [
                        'wife' => ['share' => '1/4', 'fraction' => 0.25],
                        'father' => ['share' => '3/4', 'fraction' => 0.75]
                    ],
                    'wife_mother' => [
                        'wife' => ['share' => '3/12', 'fraction' => 0.25],
                        'mother' => ['share' => '4/12', 'fraction' => 0.3333],
                        'baitulmal' => ['share' => '5/12', 'fraction' => 0.4167]
                    ]
                ]),
                'calculation_logic' => 'IF husband_count = 1 AND father_alive = true AND mother_alive = false THEN husband: 1/2, father: 1/2
IF husband_count = 1 AND mother_alive = true AND father_alive = false THEN husband: 3/6, mother: 2/6, baitulmal: 1/6
IF wife_count = 1 AND father_alive = true AND mother_alive = false THEN wife: 1/4, father: 3/4
IF wife_count = 1 AND mother_alive = true AND father_alive = false THEN wife: 3/12, mother: 4/12, baitulmal: 5/12',
                'is_active' => true
            ],
            
            // 3. Spouse and Children
            [
                'scenario_number' => 3,
                'scenario_name' => 'Spouse and Children',
                'scenario_description' => 'Deceased leaves spouse and children with no parents',
                'priority' => 3,
                'conditions' => json_encode([
                    'spouse_present' => true,
                    'children_present' => true,
                    'parents_present' => false
                ]),
                'distribution_rules' => json_encode([
                    'wife_son' => [
                        'wife' => ['share' => '1/8', 'fraction' => 0.125],
                        'son' => ['share' => '7/8', 'fraction' => 0.875]
                    ],
                    'wife_daughter' => [
                        'wife' => ['share' => '1/8', 'fraction' => 0.125],
                        'daughter' => ['share' => '4/8', 'fraction' => 0.5],
                        'baitulmal' => ['share' => '3/8', 'fraction' => 0.375]
                    ],
                    'wife_two_daughters' => [
                        'wife' => ['share' => '3/24', 'fraction' => 0.125],
                        'each_daughter' => ['share' => '8/24', 'fraction' => 0.3333],
                        'baitulmal' => ['share' => '5/24', 'fraction' => 0.2083]
                    ],
                    'husband_daughter' => [
                        'husband' => ['share' => '1/4', 'fraction' => 0.25],
                        'daughter' => ['share' => '2/4', 'fraction' => 0.5],
                        'baitulmal' => ['share' => '1/4', 'fraction' => 0.25]
                    ],
                    'husband_son_daughter' => [
                        'husband' => ['share' => '1/4', 'fraction' => 0.25],
                        'son' => ['share' => '2/4', 'fraction' => 0.5],
                        'daughter' => ['share' => '1/4', 'fraction' => 0.25]
                    ]
                ]),
                'calculation_logic' => 'IF wife_count = 1 AND son_count > 0 THEN wife: 1/8, sons share remaining with double portion for males
IF wife_count = 1 AND daughter_count = 1 AND son_count = 0 THEN wife: 1/8, daughter: 4/8, baitulmal: 3/8
IF wife_count = 1 AND daughter_count >= 2 AND son_count = 0 THEN wife: 1/8, daughters share 2/3 (equally), baitulmal: remaining
IF husband_count = 1 AND daughter_count = 1 AND son_count = 0 THEN husband: 1/4, daughter: 1/2, baitulmal: 1/4
IF husband_count = 1 AND son_count >= 1 THEN husband: 1/4, sons and daughters share remaining (male gets double female)',
                'is_active' => true
            ],
            
            // 4. Children Only
            [
                'scenario_number' => 4,
                'scenario_name' => 'Children Only',
                'scenario_description' => 'Deceased leaves only children with no spouse, parents, or siblings',
                'priority' => 4,
                'conditions' => json_encode([
                    'spouse_present' => false,
                    'children_present' => true,
                    'parents_present' => false,
                    'siblings_present' => false
                ]),
                'distribution_rules' => json_encode([
                    'one_son' => [
                        'son' => ['share' => '1/1', 'fraction' => 1.0]
                    ],
                    'one_daughter' => [
                        'daughter' => ['share' => '1/2', 'fraction' => 0.5],
                        'baitulmal' => ['share' => '1/2', 'fraction' => 0.5]
                    ],
                    'two_daughters' => [
                        'each_daughter' => ['share' => '1/3', 'fraction' => 0.3333],
                        'baitulmal' => ['share' => '1/3', 'fraction' => 0.3333]
                    ],
                    'two_sons_two_daughters' => [
                        'each_son' => ['share' => '2/6', 'fraction' => 0.3333],
                        'each_daughter' => ['share' => '1/6', 'fraction' => 0.1667]
                    ]
                ]),
                'calculation_logic' => 'IF son_count >= 1 THEN sons inherit all (males get double females if daughters present)
IF daughter_count = 1 AND son_count = 0 THEN daughter: 1/2, baitulmal: 1/2
IF daughter_count >= 2 AND son_count = 0 THEN daughters: 2/3 (equally), baitulmal: 1/3
IF mixed children THEN male gets double portion of female',
                'is_active' => true
            ],
            
            // 5. Parents and Children
            [
                'scenario_number' => 5,
                'scenario_name' => 'Parents and Children',
                'scenario_description' => 'Deceased leaves parents and children with no spouse',
                'priority' => 5,
                'conditions' => json_encode([
                    'spouse_present' => false,
                    'children_present' => true,
                    'parents_present' => true
                ]),
                'distribution_rules' => json_encode([
                    'father_son' => [
                        'father' => ['share' => '1/6', 'fraction' => 0.1667],
                        'son' => ['share' => '5/6', 'fraction' => 0.8333]
                    ],
                    'father_daughter' => [
                        'father' => ['share' => '1/2', 'fraction' => 0.5],
                        'daughter' => ['share' => '1/2', 'fraction' => 0.5]
                    ],
                    'mother_son' => [
                        'mother' => ['share' => '1/6', 'fraction' => 0.1667],
                        'son' => ['share' => '5/6', 'fraction' => 0.8333]
                    ],
                    'mother_daughter' => [
                        'mother' => ['share' => '1/6', 'fraction' => 0.1667],
                        'daughter' => ['share' => '3/6', 'fraction' => 0.5],
                        'baitulmal' => ['share' => '2/6', 'fraction' => 0.3333]
                    ]
                ]),
                'calculation_logic' => 'IF father_alive AND son_count >= 1 THEN father: 1/6, sons share remaining (male gets double)
IF father_alive AND daughter_count >= 1 AND son_count = 0 THEN father: 1/2, daughters share 1/2 (if only daughter) or 2/3 (if multiple daughters)
IF mother_alive AND son_count >= 1 THEN mother: 1/6, sons share remaining
IF mother_alive AND daughter_count >= 1 AND son_count = 0 THEN mother: 1/6, daughters share 1/2 (if only) or 2/3 (if multiple), baitulmal: remaining',
                'is_active' => true
            ],
            
            // 6. Parents Only (No Children)
            [
                'scenario_number' => 6,
                'scenario_name' => 'Parents Only (No Children)',
                'scenario_description' => 'Deceased leaves only parents with no spouse, children, or siblings',
                'priority' => 6,
                'conditions' => json_encode([
                    'spouse_present' => false,
                    'children_present' => false,
                    'parents_present' => true,
                    'siblings_present' => false
                ]),
                'distribution_rules' => json_encode([
                    'father_only' => [
                        'father' => ['share' => '1/1', 'fraction' => 1.0]
                    ],
                    'mother_only' => [
                        'mother' => ['share' => '1/3', 'fraction' => 0.3333],
                        'baitulmal' => ['share' => '2/3', 'fraction' => 0.6667]
                    ],
                    'both_parents' => [
                        'mother' => ['share' => '1/3', 'fraction' => 0.3333],
                        'father' => ['share' => '2/3', 'fraction' => 0.6667]
                    ]
                ]),
                'calculation_logic' => 'IF father_alive = true AND mother_alive = false THEN father: 1/1
IF mother_alive = true AND father_alive = false THEN mother: 1/3, baitulmal: 2/3
IF father_alive = true AND mother_alive = true THEN mother: 1/3, father: 2/3',
                'is_active' => true
            ],
            
            // 7. Spouse and Both Parents
            [
                'scenario_number' => 7,
                'scenario_name' => 'Spouse and Both Parents',
                'scenario_description' => 'Deceased leaves spouse and both parents',
                'priority' => 7,
                'conditions' => json_encode([
                    'spouse_present' => true,
                    'children_present' => false,
                    'parents_present' => true,
                    'both_parents' => true
                ]),
                'distribution_rules' => json_encode([
                    'husband_parents' => [
                        'husband' => ['share' => '3/6', 'fraction' => 0.5],
                        'mother' => ['share' => '1/6', 'fraction' => 0.1667],
                        'father' => ['share' => '2/6', 'fraction' => 0.3333]
                    ],
                    'wife_parents' => [
                        'wife' => ['share' => '1/4', 'fraction' => 0.25],
                        'mother' => ['share' => '1/4', 'fraction' => 0.25],
                        'father' => ['share' => '2/4', 'fraction' => 0.5]
                    ]
                ]),
                'calculation_logic' => 'IF husband_count = 1 AND father_alive = true AND mother_alive = true THEN husband: 1/2, mother: 1/6, father: 1/3
IF wife_count = 1 AND father_alive = true AND mother_alive = true THEN wife: 1/4, mother: 1/4, father: 1/2',
                'is_active' => true
            ],
            
            // 8. Siblings Only (Full Siblings)
            [
                'scenario_number' => 8,
                'scenario_name' => 'Siblings Only (Full Siblings)',
                'scenario_description' => 'Deceased leaves siblings with no parents, children, or spouse',
                'priority' => 8,
                'conditions' => json_encode([
                    'spouse_present' => false,
                    'children_present' => false,
                    'parents_present' => false,
                    'siblings_present' => true,
                    'full_siblings_only' => true
                ]),
                'distribution_rules' => json_encode([
                    'one_brother' => [
                        'brother' => ['share' => '1/1', 'fraction' => 1.0]
                    ],
                    'one_sister' => [
                        'sister' => ['share' => '1/2', 'fraction' => 0.5],
                        'baitulmal' => ['share' => '1/2', 'fraction' => 0.5]
                    ],
                    'two_sisters' => [
                        'each_sister' => ['share' => '1/3', 'fraction' => 0.3333],
                        'baitulmal' => ['share' => '1/3', 'fraction' => 0.3333]
                    ],
                    'brother_sister' => [
                        'brother' => ['share' => '2/3', 'fraction' => 0.6667],
                        'sister' => ['share' => '1/3', 'fraction' => 0.3333]
                    ]
                ]),
                'calculation_logic' => 'IF full_brother_count = 1 AND full_sister_count = 0 THEN brother: 1/1
IF full_sister_count = 1 AND full_brother_count = 0 THEN sister: 1/2, baitulmal: 1/2
IF full_sister_count >= 2 AND full_brother_count = 0 THEN sisters: 2/3 (equally), baitulmal: 1/3
IF full_brother_count >= 1 THEN brothers and sisters share as residuaries (male gets double)',
                'is_active' => true
            ],
            
            // 9. Maternal and Half Siblings
            [
                'scenario_number' => 9,
                'scenario_name' => 'Maternal and Half Siblings',
                'scenario_description' => 'Deceased leaves maternal siblings or half-siblings',
                'priority' => 9,
                'conditions' => json_encode([
                    'spouse_present' => false,
                    'children_present' => false,
                    'parents_present' => false,
                    'siblings_present' => true,
                    'maternal_siblings' => true
                ]),
                'distribution_rules' => json_encode([
                    'one_maternal_half_brother' => [
                        'half_brother_maternal' => ['share' => '1/6', 'fraction' => 0.1667],
                        'baitulmal' => ['share' => '5/6', 'fraction' => 0.8333]
                    ],
                    'one_maternal_half_sister' => [
                        'half_sister_maternal' => ['share' => '1/6', 'fraction' => 0.1667],
                        'baitulmal' => ['share' => '5/6', 'fraction' => 0.8333]
                    ],
                    'two_maternal_half_siblings' => [
                        'each_maternal_sibling' => ['share' => '1/3', 'fraction' => 0.3333]
                    ],
                    'paternal_half_brother_maternal_half_sister' => [
                        'half_brother_paternal' => ['share' => '5/6', 'fraction' => 0.8333],
                        'half_sister_maternal' => ['share' => '1/6', 'fraction' => 0.1667]
                    ]
                ]),
                'calculation_logic' => 'IF maternal_half_siblings_count = 1 THEN maternal_sibling: 1/6, baitulmal: 5/6
IF maternal_half_siblings_count >= 2 THEN maternal_siblings: 1/3 (equally)
IF paternal_half_brother AND maternal_half_sister THEN paternal_brother: 5/6 (as residuary), maternal_sister: 1/6',
                'is_active' => true
            ],
            
            // 10. Blocking (Mahjub) Rules
            [
                'scenario_number' => 10,
                'scenario_name' => 'Blocking Rules (Mahjub)',
                'scenario_description' => 'Rules for heirs being blocked by closer relatives',
                'priority' => 10,
                'conditions' => json_encode([
                    'blocking_scenario' => true
                ]),
                'distribution_rules' => json_encode([
                    'son_blocks_siblings' => [
                        'blocked_heirs' => ['siblings', 'uncles', 'cousins'],
                        'blocking_heir' => 'son'
                    ],
                    'father_blocks_siblings' => [
                        'blocked_heirs' => ['siblings'],
                        'blocking_heir' => 'father'
                    ],
                    'grandfather_blocks_uncles' => [
                        'blocked_heirs' => ['uncles'],
                        'blocking_heir' => 'grandfather'
                    ]
                ]),
                'calculation_logic' => 'Son present → Siblings, uncles, cousins are blocked
Father present → Siblings are blocked
Grandfather present → Uncles are blocked
Closer residuary blocks further residuary',
                'is_active' => true
            ],
            
            // 11. Extended Heirs (Asabah)
            [
                'scenario_number' => 11,
                'scenario_name' => 'Extended Heirs (Asabah)',
                'scenario_description' => 'Rules for distant male relatives as residuaries',
                'priority' => 11,
                'conditions' => json_encode([
                    'no_primary_male_heirs' => true,
                    'asabah_present' => true
                ]),
                'distribution_rules' => json_encode([
                    'paternal_uncle_asabah' => [
                        'paternal_uncle' => ['share' => 'remaining', 'fraction' => 1.0]
                    ],
                    'male_cousins_asabah' => [
                        'male_cousins' => ['share' => 'remaining', 'fraction' => 1.0]
                    ],
                    'multiple_asabah' => [
                        'asabah_heirs' => ['share' => 'remaining', 'distribution' => 'closest_first']
                    ]
                ]),
                'calculation_logic' => 'IF no son, father, grandfather THEN paternal uncle inherits as residuary
IF no paternal uncle THEN male cousins inherit as residuary
Closest male agnate relative inherits remaining estate after fixed shares',
                'is_active' => true
            ],
            
            // 12. Awl (Over-Subscription) Cases
            [
                'scenario_number' => 12,
                'scenario_name' => 'Awl Cases (Over-Subscription)',
                'scenario_description' => 'Cases where shares exceed 1 and need adjustment',
                'priority' => 12,
                'conditions' => json_encode([
                    'shares_exceed_one' => true
                ]),
                'distribution_rules' => json_encode([
                    'wife_mother_two_sisters' => [
                        'wife' => ['original' => '1/4', 'adjusted' => '3/13', 'fraction' => 0.2308],
                        'mother' => ['original' => '1/6', 'adjusted' => '2/13', 'fraction' => 0.1538],
                        'each_sister' => ['original' => '2/3', 'adjusted' => '4/13', 'fraction' => 0.3077]
                    ],
                    'husband_two_sisters' => [
                        'husband' => ['original' => '1/2', 'adjusted' => '3/7', 'fraction' => 0.4286],
                        'each_sister' => ['original' => '2/3', 'adjusted' => '2/7', 'fraction' => 0.2857]
                    ]
                ]),
                'calculation_logic' => 'IF total_shares > 1 THEN adjust_all_shares proportionally
Example: Wife(1/4) + Mother(1/6) + 2 Sisters(2/3) = 13/12 > 1
Adjust to: Wife(3/13), Mother(2/13), Each Sister(4/13)',
                'is_active' => true
            ],
            
            // 13. Multiple Wives
            [
                'scenario_number' => 13,
                'scenario_name' => 'Multiple Wives',
                'scenario_description' => 'Deceased leaves multiple wives',
                'priority' => 13,
                'conditions' => json_encode([
                    'wife_count' => '>1'
                ]),
                'distribution_rules' => json_encode([
                    'three_wives_no_children' => [
                        'each_wife' => ['share' => '1/12', 'fraction' => 0.0833],
                        'baitulmal' => ['share' => '9/12', 'fraction' => 0.75]
                    ],
                    'two_wives_two_daughters' => [
                        'each_wife' => ['share' => '3/48', 'fraction' => 0.0625],
                        'each_daughter' => ['share' => '16/48', 'fraction' => 0.3333],
                        'baitulmal' => ['share' => '10/48', 'fraction' => 0.2083]
                    ]
                ]),
                'calculation_logic' => 'IF wife_count > 1 AND no_children THEN wives share 1/8 (total) equally, baitulmal: remaining
IF wife_count > 1 AND children_present THEN wives share 1/8 (total) equally, children get remaining
Multiple wives share the wife portion equally',
                'is_active' => true
            ],
            
            // 14. Standard Faraid Rules (Default)
            [
                'scenario_number' => 14,
                'scenario_name' => 'Standard Faraid Rules',
                'scenario_description' => 'Default Faraid calculation rules with fixed shares',
                'priority' => 999, // Lowest priority - default fallback
                'conditions' => json_encode([
                    'default' => true
                ]),
                'distribution_rules' => json_encode([
                    'fixed_shares' => [
                        'husband_with_children' => ['share' => '1/4', 'fraction' => 0.25],
                        'husband_no_children' => ['share' => '1/2', 'fraction' => 0.5],
                        'wife_with_children' => ['share' => '1/8', 'fraction' => 0.125],
                        'wife_no_children' => ['share' => '1/4', 'fraction' => 0.25],
                        'father' => ['share' => '1/6', 'fraction' => 0.1667],
                        'mother' => ['share' => '1/6', 'fraction' => 0.1667],
                        'daughter_only' => ['share' => '1/2', 'fraction' => 0.5],
                        'daughters_only' => ['share' => '2/3', 'fraction' => 0.6667]
                    ],
                    'residuaries' => [
                        'son_gets_double_daughter' => true,
                        'male_agnates_inherit_remaining' => true
                    ]
                ]),
                'calculation_logic' => '1. Assign fixed shares to eligible heirs
2. If total fixed shares < 1, assign remaining to residuaries (male agnates)
3. Male residuary gets double female residuary
4. If no residuaries, remaining goes to baitulmal
5. Adjust for awl if shares exceed 1',
                'is_active' => true
            ]
        ];
        
        foreach ($scenarios as $scenario) {
            FaraidCalculationRule::create($scenario);
        }
        
        $this->command->info('Faraid calculation rules seeded successfully!');
        $this->command->info('Total scenarios seeded: ' . count($scenarios));
        $this->command->info('Scenarios cover: Spouse, Parents, Children, Siblings, Half-Siblings, Awl, Mahjub, Asabah, and Multiple Wives');
    }
}