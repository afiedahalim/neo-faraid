<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Calculation;
use App\Services\FamilyTreeService;
use Illuminate\Support\Facades\Log;

class GenerateAllFamilyTrees extends Command
{
    protected $signature = 'familytree:generate-all {--force : Force regenerate all trees}';
    protected $description = 'Generate family trees for all calculations';

    public function handle()
    {
        $force = $this->option('force');
        
        $query = Calculation::query();
        
        if (!$force) {
            $query->whereNull('family_tree_image')
                  ->orWhere('family_tree_image', '=', '');
        }
        
        $total = $query->count();
        $this->info("Found {$total} calculations to process");
        
        if ($total === 0) {
            $this->info("No calculations need family trees");
            return 0;
        }
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $successCount = 0;
        $failCount = 0;
        
        $query->chunk(10, function ($calculations) use ($bar, &$successCount, &$failCount) {
            foreach ($calculations as $calculation) {
                try {
                    $this->generateTreeForCalculation($calculation);
                    $successCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to generate tree for calculation {$calculation->id}: " . $e->getMessage());
                    $failCount++;
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Completed!");
        $this->info("Success: {$successCount}");
        $this->info("Failed: {$failCount}");
        
        return 0;
    }
    
    private function generateTreeForCalculation(Calculation $calculation)
    {
        $calculationData = json_decode($calculation->calculation_data, true) ?? [];
        
        if (empty($calculationData['distribution'])) {
            Log::warning("No distribution data for calculation {$calculation->id}");
            return false;
        }
        
        $heirsData = [
            'wife_count' => $calculation->wife_count ?? 0,
            'husband_count' => $calculation->husband_count ?? 0,
            'father_status' => $calculation->father_status ?? 'none',
            'mother_status' => $calculation->mother_status ?? 'none',
            'son_count' => $calculation->son_count ?? 0,
            'daughter_count' => $calculation->daughter_count ?? 0,
        ];
        
        $familyTreeService = new FamilyTreeService(
            [
                'name' => $calculation->deceased_name,
                'gender' => $calculation->deceased_gender
            ],
            $heirsData,
            $calculationData['distribution']
        );
        
        $filename = 'calc_' . $calculation->id . '_' . time();
        $familyTreeImage = $familyTreeService->generate($filename);
        
        if ($familyTreeImage) {
            $calculation->update([
                'family_tree_image' => $familyTreeImage,
                'tree_generated_at' => now()
            ]);
            return true;
        }
        
        return false;
    }
}