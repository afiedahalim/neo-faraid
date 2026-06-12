<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DistributionCalculatorService
{
    public function calculateDistributionSummary(array $heirsData, float $netEstate)
    {
        // Ensure netEstate is positive
        if ($netEstate <= 0) {
            return [
                'heirs' => [],
                'total_distributed' => 0,
                'total_eligible' => 0,
                'net_estate' => 0,
                'total_heirs' => 0
            ];
        }
        
        $heirs = [];
        $totalDistributed = 0;

        Log::info('DistributionCalculator: Starting calculation', [
            'netEstate' => $netEstate,
            'heirsData' => $heirsData
        ]);

        // 1. HUSBAND
        if (($heirsData['husband_count'] ?? 0) > 0) {
            $hasChildren = ($heirsData['son_count'] ?? 0) > 0 || ($heirsData['daughter_count'] ?? 0) > 0;
            $share = $hasChildren ? 0.25 : 0.5; // 1/4 with children, 1/2 without
            $amount = $netEstate * $share;
            
            $heirs[] = [
                'name' => 'Husband',
                'heir' => 'Husband',
                'relationship' => 'Husband',
                'share' => $share == 0.25 ? '1/4' : '1/2',
                'amount' => $amount,
                'percentage' => ($amount / $netEstate) * 100,
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'status' => 'Eligible',
                'type' => 'Fixed Share'
            ];
            $totalDistributed += $amount;
        }
        
        // 2. WIFE/WIVES
        if (($heirsData['wife_count'] ?? 0) > 0) {
            $hasChildren = ($heirsData['son_count'] ?? 0) > 0 || ($heirsData['daughter_count'] ?? 0) > 0;
            $share = $hasChildren ? 0.125 : 0.25; // 1/8 with children, 1/4 without
            $totalWifeAmount = $netEstate * $share;
            $wifeCount = min($heirsData['wife_count'], 4);
            $individualWifeAmount = $totalWifeAmount / $wifeCount;
            
            for ($i = 1; $i <= $wifeCount; $i++) {
                $heirs[] = [
                    'name' => "Wife $i",
                    'heir' => "Wife $i",
                    'relationship' => 'Wife',
                    'share' => $share == 0.125 ? '1/8' : '1/4',
                    'amount' => $individualWifeAmount,
                    'percentage' => ($individualWifeAmount / $netEstate) * 100,
                    'formatted_amount' => 'RM ' . number_format($individualWifeAmount, 2),
                    'status' => 'Eligible',
                    'type' => 'Fixed Share'
                ];
                $totalDistributed += $individualWifeAmount;
            }
        }
        
        // 3. FATHER
        if (($heirsData['father_status'] ?? 'deceased') === 'alive') {
            $share = 1/6; // Always 1/6
            $amount = $netEstate * $share;
            
            $heirs[] = [
                'name' => 'Father',
                'heir' => 'Father',
                'relationship' => 'Father',
                'share' => '1/6',
                'amount' => $amount,
                'percentage' => ($amount / $netEstate) * 100,
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'status' => 'Eligible',
                'type' => 'Fixed Share'
            ];
            $totalDistributed += $amount;
        }
        
        // 4. MOTHER
        if (($heirsData['mother_status'] ?? 'deceased') === 'alive') {
            $hasChildren = ($heirsData['son_count'] ?? 0) > 0 || ($heirsData['daughter_count'] ?? 0) > 0;
            $hasSiblings = ($heirsData['full_brother_count'] ?? 0) + ($heirsData['full_sister_count'] ?? 0) >= 2;
            
            $share = ($hasChildren || $hasSiblings) ? 1/6 : 1/3;
            $amount = $netEstate * $share;
            
            $heirs[] = [
                'name' => 'Mother',
                'heir' => 'Mother',
                'relationship' => 'Mother',
                'share' => $share == 1/6 ? '1/6' : '1/3',
                'amount' => $amount,
                'percentage' => ($amount / $netEstate) * 100,
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'status' => 'Eligible',
                'type' => 'Fixed Share'
            ];
            $totalDistributed += $amount;
        }
        
        // 5. CHILDREN (if any)
        $sonCount = $heirsData['son_count'] ?? 0;
        $daughterCount = $heirsData['daughter_count'] ?? 0;
        
        if ($sonCount > 0 || $daughterCount > 0) {
            // Remaining goes to children as Asabah
            $remainingForChildren = $netEstate - $totalDistributed;
            
            if ($remainingForChildren > 0) {
                // Sons get 2 shares, daughters get 1 share
                $sonShareValue = 2;
                $daughterShareValue = 1;
                $totalShares = ($sonCount * $sonShareValue) + ($daughterCount * $daughterShareValue);
                
                if ($totalShares > 0) {
                    // Sons
                    for ($i = 1; $i <= $sonCount; $i++) {
                        $amount = ($remainingForChildren * $sonShareValue) / $totalShares;
                        $heirs[] = [
                            'name' => "Son $i",
                            'heir' => "Son $i",
                            'relationship' => 'Son',
                            'share' => 'Asabah',
                            'amount' => $amount,
                            'percentage' => ($amount / $netEstate) * 100,
                            'formatted_amount' => 'RM ' . number_format($amount, 2),
                            'status' => 'Eligible',
                            'type' => 'Asabah'
                        ];
                        $totalDistributed += $amount;
                    }
                    
                    // Daughters
                    for ($i = 1; $i <= $daughterCount; $i++) {
                        $amount = ($remainingForChildren * $daughterShareValue) / $totalShares;
                        $heirs[] = [
                            'name' => "Daughter $i",
                            'heir' => "Daughter $i",
                            'relationship' => 'Daughter',
                            'share' => 'Asabah',
                            'amount' => $amount,
                            'percentage' => ($amount / $netEstate) * 100,
                            'formatted_amount' => 'RM ' . number_format($amount, 2),
                            'status' => 'Eligible',
                            'type' => 'Asabah'
                        ];
                        $totalDistributed += $amount;
                    }
                }
            }
        }
        
        // 6. BAITULMAL (if any remaining)
        $remainingEstate = $netEstate - $totalDistributed;
        if ($remainingEstate > 0.01) {
            $heirs[] = [
                'name' => 'Baitulmal',
                'heir' => 'Baitulmal',
                'relationship' => 'State Treasury',
                'share' => 'Surplus',
                'amount' => $remainingEstate,
                'percentage' => ($remainingEstate / $netEstate) * 100,
                'formatted_amount' => 'RM ' . number_format($remainingEstate, 2),
                'status' => 'Surplus',
                'type' => 'Surplus'
            ];
            $totalDistributed += $remainingEstate;
        }

        // Filter eligible heirs (exclude Baitulmal/Surplus)
        $eligibleHeirs = array_filter($heirs, function($heir) {
            $amount = $heir['amount'] ?? 0;
            $type = $heir['type'] ?? '';
            return $amount > 0.01 && $type !== 'Surplus';
        });

        $result = [
            'heirs' => $heirs,
            'total_distributed' => $totalDistributed,
            'total_eligible' => count($eligibleHeirs),
            'net_estate' => $netEstate,
            'total_heirs' => count($heirs)
        ];

        Log::info('DistributionCalculator: Result', $result);
        
        return $result;
    }
}