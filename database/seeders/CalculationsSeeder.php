<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CalculationsSeeder extends Seeder
{
    public function run(): void
    {
        // Get user IDs
        $adminId = DB::table('users')->where('email', 'admin@neofaraid.com')->value('id');
        $userId = DB::table('users')->where('email', 'user@neofaraid.com')->value('id');

        if (!$adminId || !$userId) {
            $this->command->error('Users not found. Run DatabaseSeeder first.');
            return;
        }

        $calculations = [];
        $now = Carbon::now();

        // Create 5 simple sample calculations
        for ($i = 1; $i <= 5; $i++) {
            $totalAssets = rand(300000, 1500000);
            $gender = $i % 2 == 0 ? 'female' : 'male';
            $maritalStatus = $i % 4 == 0 ? 'single' : 'married';
            
            $hasSpouse = $maritalStatus === 'married';
            $spouseAmount = $hasSpouse ? round($totalAssets * 0.25, 2) : 0;
            $childAmount = $hasSpouse ? round($totalAssets * 0.75, 2) : round($totalAssets, 2);
            
            $calculation = [
                'user_id' => $i <= 2 ? $adminId : $userId,
                'deceased_name' => $this->getSimpleName($i, $gender),
                'deceased_gender' => $gender,
                'date_of_death' => $now->copy()->subDays(rand(30, 365))->format('Y-m-d'),
                'marital_status' => $maritalStatus,
                'wife_count' => ($gender === 'male' && $maritalStatus === 'married') ? 1 : 0,
                'husband_count' => ($gender === 'female' && $maritalStatus === 'married') ? 1 : 0,
                'father_status' => 'deceased',
                'mother_status' => 'deceased',
                'son_count' => 1,
                'daughter_count' => 0,
                'full_brother_count' => 0,
                'full_sister_count' => 0,
                'paternal_brother_count' => 0,
                'paternal_sister_count' => 0,
                'maternal_brother_count' => 0,
                'maternal_sister_count' => 0,
                'maternal_sibling_count' => 0,
                'total_assets' => $totalAssets,
                'net_assets' => $totalAssets, // Net assets equals total assets since no deductions
                'total_heirs' => $hasSpouse ? 2 : 1,
                'scenario_number' => $hasSpouse ? 3 : 4,
                'scenario_description' => $hasSpouse ? 'Spouse and Children Inheritance' : 'Children Only Inheritance',
                'calculation_hash' => 'CALC_' . time() . '_' . $i . '_' . Str::random(10),
                'tree_generation_status' => 'completed',
                'created_at' => $now->copy()->subDays($i * 2),
                'updated_at' => $now->copy()->subDays($i * 2),
            ];
            
            // Generate heirs_data JSON
            $heirsData = [
                'husband_count' => ($gender === 'female' && $maritalStatus === 'married') ? 1 : 0,
                'wife_count' => ($gender === 'male' && $maritalStatus === 'married') ? 1 : 0,
                'father_status' => 'deceased',
                'mother_status' => 'deceased',
                'son_count' => 1,
                'daughter_count' => 0,
                'full_brother_count' => 0,
                'full_sister_count' => 0,
                'paternal_brother_count' => 0,
                'paternal_sister_count' => 0,
                'maternal_brother_count' => 0,
                'maternal_sister_count' => 0,
                'maternal_sibling_count' => 0,
            ];
            $calculation['heirs_data'] = json_encode($heirsData);
            
            // Generate calculation_data JSON
            $calculationData = [];
            if ($hasSpouse) {
                $calculationData[] = [
                    'heir' => $gender === 'male' ? 'Wife 1' : 'Husband',
                    'relationship' => $gender === 'male' ? 'Wife' : 'Husband',
                    'share' => $gender === 'male' ? '1/8' : '1/4',
                    'amount' => $spouseAmount,
                    'fraction' => 0.25,
                    'status' => 'Eligible',
                ];
            }
            $calculationData[] = [
                'heir' => 'Son 1',
                'relationship' => 'Son',
                'share' => 'Asabah',
                'amount' => $childAmount,
                'fraction' => $hasSpouse ? 0.75 : 1.0,
                'status' => 'Eligible',
            ];
            $calculation['calculation_data'] = json_encode($calculationData);
            
            // Generate distribution_summary JSON
            $distributionSummary = [
                'heirs' => $calculationData,
                'total_distributed' => $totalAssets,
                'total_eligible' => $hasSpouse ? 2 : 1,
                'net_estate' => $totalAssets,
                'total_heirs' => $hasSpouse ? 2 : 1,
            ];
            $calculation['distribution_summary'] = json_encode($distributionSummary);
            
            // Generate chart_data JSON
            $chartData = [];
            if ($hasSpouse) {
                $chartData[] = [
                    'heir' => $gender === 'male' ? 'Wife' : 'Husband',
                    'amount' => $spouseAmount,
                    'percentage' => 25.00,
                    'share' => $gender === 'male' ? '1/8' : '1/4',
                    'color' => '#1a5fb4'
                ];
            }
            $chartData[] = [
                'heir' => 'Son',
                'amount' => $childAmount,
                'percentage' => $hasSpouse ? 75.00 : 100.00,
                'share' => 'Asabah',
                'color' => '#28a745'
            ];
            $calculation['chart_data'] = json_encode($chartData);

            $calculations[] = $calculation;
        }

        // Insert the data
        DB::table('calculations')->insert($calculations);

        $this->command->info('✅ ' . count($calculations) . ' calculation records added successfully!');
        $this->command->info('📊 Total calculations in database: ' . DB::table('calculations')->count());
    }

    private function getSimpleName($index, $gender): string
    {
        $firstNames = ['Ahmad', 'Siti', 'Mohammad', 'Nor', 'Abdul'];
        $lastNames = ['Abdullah', 'Ali', 'Ismail', 'Mohd', 'Rahman'];
        
        $firstName = $firstNames[($index - 1) % count($firstNames)];
        $lastName = $lastNames[($index - 1) % count($lastNames)];
        
        return $gender === 'male' ? "{$firstName} bin {$lastName}" : "{$firstName} binti {$lastName}";
    }
}