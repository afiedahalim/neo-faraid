<?php

namespace App\Console\Commands;

use App\Models\Calculation;
use App\Services\FaraidCalculator;
use Illuminate\Console\Command;

class DebugCalculations extends Command
{
    protected $signature = 'calculations:debug {id}';
    protected $description = 'Debug calculation data and logic';

    protected $calculator;

    public function __construct(FaraidCalculator $calculator)
    {
        parent::__construct();
        $this->calculator = $calculator;
    }

    public function handle()
    {
        $id = $this->argument('id');
        
        $this->info("🔍 DEBUGGING CALCULATION ID: {$id}");
        $this->line("==========================================");
        
        $calculation = Calculation::find($id);
        
        if (!$calculation) {
            $this->error("Calculation not found!");
            return 1;
        }
        
        // Basic info
        $this->info("📋 BASIC INFO:");
        $this->line("  Deceased: {$calculation->deceased_name}");
        $this->line("  Net Assets: RM " . number_format($calculation->net_assets, 2));
        $this->line("  Created: {$calculation->created_at}");
        
        // Heir counts from database columns
        $this->info("\n👥 HEIR COUNTS (from database columns):");
        $this->line("  Husband: {$calculation->husband_count}");
        $this->line("  Wife: {$calculation->wife_count}");
        $this->line("  Father Status: {$calculation->father_status}");
        $this->line("  Mother Status: {$calculation->mother_status}");
        $this->line("  Sons: {$calculation->son_count}");
        $this->line("  Daughters: {$calculation->daughter_count}");
        $this->line("  Full Brothers: {$calculation->full_brother_count}");
        $this->line("  Full Sisters: {$calculation->full_sister_count}");
        $this->line("  Paternal Brothers: {$calculation->paternal_brother_count}");
        $this->line("  Paternal Sisters: {$calculation->paternal_sister_count}");
        $this->line("  Maternal Brothers/Sisters: {$calculation->maternal_sibling_count}");
        
        // Calculate total heirs from columns
        $totalFromColumns = 0;
        $totalFromColumns += $calculation->husband_count;
        $totalFromColumns += $calculation->wife_count;
        $totalFromColumns += ($calculation->father_status === 'alive') ? 1 : 0;
        $totalFromColumns += ($calculation->mother_status === 'alive') ? 1 : 0;
        $totalFromColumns += $calculation->son_count;
        $totalFromColumns += $calculation->daughter_count;
        $totalFromColumns += $calculation->full_brother_count;
        $totalFromColumns += $calculation->full_sister_count;
        $totalFromColumns += $calculation->paternal_brother_count;
        $totalFromColumns += $calculation->paternal_sister_count;
        $totalFromColumns += $calculation->maternal_sibling_count;
        
        $this->line("  ✅ Total Heirs (calculated): {$totalFromColumns}");
        
        // Check heirs_data field
        $this->info("\n📊 HEIRS DATA (from JSON field):");
        if (!empty($calculation->heirs_data) && is_array($calculation->heirs_data)) {
            $this->line("  ✅ heirs_data exists with " . count($calculation->heirs_data) . " keys");
            foreach ($calculation->heirs_data as $key => $value) {
                $this->line("    {$key}: {$value}");
            }
        } else {
            $this->error("  ❌ heirs_data is empty or not an array");
        }
        
        // Check distribution summary
        $this->info("\n📈 DISTRIBUTION SUMMARY:");
        $summary = $calculation->distribution_summary;
        if (isset($summary['total_eligible'])) {
            $this->line("  ✅ distribution_summary exists");
            $this->line("    Total Eligible: {$summary['total_eligible']}");
            $this->line("    Total Distributed: RM " . number_format($summary['total_distributed'], 2));
            $this->line("    Net Estate: {$calculation->formatted_net_assets}");
            $this->line("    Number of heirs in summary: " . count($summary['heirs']));
            
            if (count($summary['heirs']) > 0) {
                $this->line("\n  🧾 DETAILED DISTRIBUTION:");
                foreach ($summary['heirs'] as $heir) {
                    $this->line("    - {$heir['relationship']}: {$heir['share']} = {$heir['formatted_amount']} ({$heir['percentage']}%)");
                }
            }
        } else {
            $this->error("  ❌ distribution_summary is empty");
        }
        
        // Check calculation_data
        $this->info("\n🧮 CALCULATION DATA:");
        if (!empty($calculation->calculation_data) && is_array($calculation->calculation_data)) {
            $this->line("  ✅ calculation_data exists with " . count($calculation->calculation_data) . " items");
            foreach ($calculation->calculation_data as $key => $value) {
                if ($key === 'distribution' && is_array($value)) {
                    $this->line("    Distribution items: " . count($value));
                } else {
                    $this->line("    {$key}: " . (is_array($value) ? json_encode($value) : $value));
                }
            }
        } else {
            $this->error("  ❌ calculation_data is empty or not an array");
        }
        
        // Test recalculation
        $this->info("\n🧪 TESTING CALCULATION LOGIC:");
        
        try {
            // Get heirs data
            $heirsData = $calculation->heirs_data;
            if (empty($heirsData)) {
                // Try to extract from columns
                $heirsData = [
                    'husband_count' => $calculation->husband_count ?? 0,
                    'wife_count' => $calculation->wife_count ?? 0,
                    'father_status' => $calculation->father_status ?? 'deceased',
                    'mother_status' => $calculation->mother_status ?? 'deceased',
                    'son_count' => $calculation->son_count ?? 0,
                    'daughter_count' => $calculation->daughter_count ?? 0,
                    'full_brother_count' => $calculation->full_brother_count ?? 0,
                    'full_sister_count' => $calculation->full_sister_count ?? 0,
                    'paternal_brother_count' => $calculation->paternal_brother_count ?? 0,
                    'paternal_sister_count' => $calculation->paternal_sister_count ?? 0,
                    'maternal_sibling_count' => $calculation->maternal_sibling_count ?? 0,
                ];
            }
            
            $this->line("  📊 Using heirs data:");
            foreach ($heirsData as $key => $value) {
                $this->line("    {$key}: {$value}");
            }
            
            // Perform calculation
            $result = $this->calculator->calculateDistribution(
                $calculation->deceased_gender,
                $heirsData,
                $calculation->net_assets
            );
            
            $this->line("\n  ✅ Calculation successful!");
            $this->line("  📦 Result structure:");
            foreach ($result as $key => $value) {
                if ($key === 'distribution' && is_array($value)) {
                    $eligible = array_filter($value, function($heir) {
                        return ($heir['amount'] ?? 0) > 0;
                    });
                    $this->line("    {$key}: " . count($eligible) . " eligible heirs");
                    
                    if (count($eligible) > 0) {
                        $this->line("\n    📝 Eligible heirs:");
                        foreach ($eligible as $heir) {
                            $amount = isset($heir['amount']) ? number_format($heir['amount'], 2) : '0.00';
                            $this->line("      - {$heir['heir']} ({$heir['relationship']}): {$heir['share']} = RM {$amount}");
                        }
                    }
                } else {
                    $this->line("    {$key}: " . (is_array($value) ? json_encode($value) : $value));
                }
            }
            
            // Update calculation
            $calculation->calculation_data = $result;
            $calculation->save();
            
            $this->info("\n✅ Calculation data updated successfully!");
            
        } catch (\Exception $e) {
            $this->error("  ❌ Calculation failed: " . $e->getMessage());
            $this->error("  Stack trace: " . $e->getTraceAsString());
        }
        
        $this->line("\n==========================================");
        $this->info("Debug completed!");
        
        return 0;
    }
}