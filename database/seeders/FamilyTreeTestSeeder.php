<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Calculation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class FamilyTreeTestSeeder extends Seeder
{
    public function run()
    {
        Log::info('Starting FamilyTreeTestSeeder...');
        
        // Create a test user
        $user = User::firstOrCreate(
            ['email' => 'familytree@test.com'],
            [
                'name' => 'Family Tree Test User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        
        Log::info('Test user created/retrieved', ['user_id' => $user->id]);
        
        // Create test calculations with different scenarios
        
        // Scenario 1: Standard family
        $this->createCalculation($user, [
            'deceased_name' => 'Ahmad bin Abdullah',
            'deceased_gender' => 'male',
            'wife_count' => 1,
            'father_status' => 'alive',
            'mother_status' => 'alive',
            'son_count' => 2,
            'daughter_count' => 1,
            'distribution' => [
                ['heir' => 'Wife', 'relationship' => 'wife', 'share' => '1/8', 'amount' => 12500],
                ['heir' => 'Father', 'relationship' => 'father', 'share' => '1/6', 'amount' => 16666.67],
                ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/6', 'amount' => 16666.67],
                ['heir' => 'Son 1', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 18083.33],
                ['heir' => 'Son 2', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 18083.33],
                ['heir' => 'Daughter', 'relationship' => 'daughter', 'share' => '1/2 of son', 'amount' => 9041.67],
            ]
        ]);
        
        // Scenario 2: Female deceased
        $this->createCalculation($user, [
            'deceased_name' => 'Siti binti Ali',
            'deceased_gender' => 'female',
            'husband_count' => 1,
            'father_status' => 'alive',
            'mother_status' => 'alive',
            'son_count' => 1,
            'daughter_count' => 2,
            'distribution' => [
                ['heir' => 'Husband', 'relationship' => 'husband', 'share' => '1/4', 'amount' => 25000],
                ['heir' => 'Father', 'relationship' => 'father', 'share' => '1/6', 'amount' => 16666.67],
                ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/6', 'amount' => 16666.67],
                ['heir' => 'Son', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 20833.33],
                ['heir' => 'Daughter 1', 'relationship' => 'daughter', 'share' => '1/2 of son', 'amount' => 10416.67],
                ['heir' => 'Daughter 2', 'relationship' => 'daughter', 'share' => '1/2 of son', 'amount' => 10416.67],
            ]
        ]);
        
        // Scenario 3: No children
        $this->createCalculation($user, [
            'deceased_name' => 'Abdul Rahman',
            'deceased_gender' => 'male',
            'wife_count' => 1,
            'father_status' => 'alive',
            'mother_status' => 'alive',
            'son_count' => 0,
            'daughter_count' => 0,
            'distribution' => [
                ['heir' => 'Wife', 'relationship' => 'wife', 'share' => '1/4', 'amount' => 25000],
                ['heir' => 'Father', 'relationship' => 'father', 'share' => 'Residual', 'amount' => 50000],
                ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/3 of residual', 'amount' => 25000],
            ]
        ]);
        
        // Scenario 4: Multiple wives
        $this->createCalculation($user, [
            'deceased_name' => 'Mohammed bin Yusuf',
            'deceased_gender' => 'male',
            'wife_count' => 3,
            'father_status' => 'deceased',
            'mother_status' => 'alive',
            'son_count' => 2,
            'distribution' => [
                ['heir' => 'Wife 1', 'relationship' => 'wife', 'share' => '1/8 shared', 'amount' => 4166.67],
                ['heir' => 'Wife 2', 'relationship' => 'wife', 'share' => '1/8 shared', 'amount' => 4166.67],
                ['heir' => 'Wife 3', 'relationship' => 'wife', 'share' => '1/8 shared', 'amount' => 4166.67],
                ['heir' => 'Mother', 'relationship' => 'mother', 'share' => '1/6', 'amount' => 16666.67],
                ['heir' => 'Son 1', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 29166.67],
                ['heir' => 'Son 2', 'relationship' => 'son', 'share' => 'Residual', 'amount' => 29166.67],
            ]
        ]);
        
        Log::info('FamilyTreeTestSeeder completed successfully');
    }
    
    private function createCalculation($user, $data)
    {
        try {
            $calculation = Calculation::create([
                'user_id' => $user->id,
                'deceased_name' => $data['deceased_name'],
                'deceased_gender' => $data['deceased_gender'],
                'date_of_death' => '2024-01-01',
                'marital_status' => 'married',
                'wife_count' => $data['wife_count'] ?? 0,
                'husband_count' => $data['husband_count'] ?? 0,
                'father_status' => $data['father_status'],
                'mother_status' => $data['mother_status'],
                'son_count' => $data['son_count'] ?? 0,
                'daughter_count' => $data['daughter_count'] ?? 0,
                'total_assets' => 100000,
                'debts' => 0,
                'funeral_costs' => 0,
                'wasiyyah' => 0,
                'net_assets' => 100000,
                'total_heirs' => ($data['wife_count'] ?? 0) + ($data['husband_count'] ?? 0) + 
                                (($data['father_status'] === 'alive') ? 1 : 0) + 
                                (($data['mother_status'] === 'alive') ? 1 : 0) + 
                                ($data['son_count'] ?? 0) + ($data['daughter_count'] ?? 0),
                'heirs_data' => json_encode([
                    'wife_count' => $data['wife_count'] ?? 0,
                    'husband_count' => $data['husband_count'] ?? 0,
                    'father_status' => $data['father_status'],
                    'mother_status' => $data['mother_status'],
                    'son_count' => $data['son_count'] ?? 0,
                    'daughter_count' => $data['daughter_count'] ?? 0,
                ]),
                'calculation_data' => json_encode([
                    'distribution' => $data['distribution']
                ]),
                'calculation_hash' => Calculation::generateHash(),
            ]);
            
            Log::info('Calculation created', [
                'id' => $calculation->id,
                'name' => $calculation->deceased_name,
                'scenario' => $data['deceased_name']
            ]);
            
            return $calculation;
            
        } catch (\Exception $e) {
            Log::error('Error creating calculation: ' . $e->getMessage());
            return null;
        }
    }
}