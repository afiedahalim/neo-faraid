<?php

namespace App\Console\Commands;

use App\Models\Calculation;
use App\Http\Controllers\CalculationController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FixCalculations extends Command
{
    protected $signature = 'calculations:fix';
    protected $description = 'Fix calculations with missing distribution data and ensure consistency';

    protected $calculationController;

    public function __construct(CalculationController $calculationController = null)
    {
        parent::__construct();
        $this->calculationController = $calculationController ?? new CalculationController();
    }

    public function handle()
    {
        $this->info('Starting to fix calculations...');
        
        $calculations = Calculation::all();
        $fixed = 0;
        $errors = 0;
        $skipped = 0;
        
        $totalCalculations = $calculations->count();
        $this->info("Found {$totalCalculations} calculations to process.");
        
        $progressBar = $this->output->createProgressBar($totalCalculations);
        $progressBar->start();
        
        foreach ($calculations as $calculation) {
            try {
                // Force fix for all calculations to ensure consistency
                $this->newLine();
                $this->info("Processing calculation ID: {$calculation->id} - {$calculation->deceased_name}");
                
                // Log current state
                $currentEligible = $calculation->eligible_heirs_count ?? 0;
                $hasDistribution = !empty($calculation->distribution_summary);
                $hasCalculationData = !empty($calculation->calculation_data);
                
                $this->info("Current state - Eligible Heirs: {$currentEligible}, " . 
                           "Has Distribution: " . ($hasDistribution ? 'Yes' : 'No') . ", " .
                           "Has Calculation Data: " . ($hasCalculationData ? 'Yes' : 'No'));
                
                // Check if calculation already has complete data
                if ($hasDistribution && $hasCalculationData && $currentEligible > 0) {
                    $this->info("Calculation ID: {$calculation->id} appears to have complete data. Running verification...");
                }
                
                // Always run fix to ensure data consistency
                $success = $this->calculationController->fixCalculationData($calculation);
                
                if ($success === null) {
                    $skipped++;
                    $this->warn("⚠ Calculation ID: {$calculation->id} was skipped (fixCalculationData returned null)");
                    $progressBar->advance();
                    continue;
                }
                
                // Refresh to get updated data
                $calculation->refresh();
                $newEligible = $calculation->eligible_heirs_count ?? 0;
                $newHasDistribution = !empty($calculation->distribution_summary);
                $newHasCalculationData = !empty($calculation->calculation_data);
                
                if ($success) {
                    $fixed++;
                    $this->info("✓ Successfully processed calculation ID: {$calculation->id}");
                    $this->info("  Eligible Heirs: {$currentEligible} → {$newEligible}");
                    $this->info("  Distribution: " . ($hasDistribution ? 'Yes' : 'No') . " → " . ($newHasDistribution ? 'Yes' : 'No'));
                    $this->info("  Calculation Data: " . ($hasCalculationData ? 'Yes' : 'No') . " → " . ($newHasCalculationData ? 'Yes' : 'No'));
                } else {
                    $errors++;
                    $this->error("✗ Failed to fix calculation ID: {$calculation->id}");
                    $this->error("  Method returned false - check controller logic.");
                }
                
                // Add a small delay to avoid overwhelming the system
                usleep(100000); // 100ms
                
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error fixing calculation {$calculation->id}: {$e->getMessage()}");
                $this->error("Stack trace: " . $e->getTraceAsString());
                Log::error("Error fixing calculation {$calculation->id}: " . $e->getMessage(), [
                    'exception' => $e,
                    'calculation_id' => $calculation->id
                ]);
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info("========== PROCESSING COMPLETE ==========");
        $this->info("Total calculations processed: {$totalCalculations}");
        $this->info("Successfully fixed/verified: {$fixed}");
        $this->info("Skipped: {$skipped}");
        $this->info("Errors: {$errors}");
        
        if ($errors > 0) {
            $this->error("⚠ There were {$errors} error(s) during processing. Check the logs for details.");
            return Command::FAILURE;
        }
        
        $this->info("✅ All calculations have been processed successfully!");
        return Command::SUCCESS;
    }
}