<?php

namespace App\Jobs;

use App\Models\InstantEstateSession;
use App\Models\Calculation;
use App\Models\EstateDebt;
use App\Services\OCRService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessInstantEstateSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 300;
    public $tries = 3;
    public $backoff = [60, 120, 300];

    protected InstantEstateSession $session;
    protected float $startTime;

    public function __construct(InstantEstateSession $session)
    {
        $this->session = $session;
    }

    public function handle(OCRService $ocrService): void
    {
        $this->startTime = microtime(true);

        try {
            DB::beginTransaction();

            // Step 1: Start OCR
            $this->updateStatus('processing_ocr', 'Starting OCR processing');

            // Step 2: Extract text from death certificate
            $extractedData = $ocrService->extractFromDeathCertificate($this->session->file_path);

            // Step 3: Validate extracted data
            $validatedData = $ocrService->validateExtractedData($extractedData);

            // Step 4: Update session with extracted data
            $this->session->update([
                'extracted_data' => $validatedData,
                'deceased_name' => $validatedData['deceased_name'] ?? null,
                'deceased_nric' => $validatedData['deceased_nric'] ?? null,
                'estate_value' => $validatedData['estate_value'] ?? 0,
                'death_date' => $validatedData['death_date'] ?? null,
                'death_place' => $validatedData['death_place'] ?? null,
                'status' => 'ocr_completed',
                'ocr_confidence' => $validatedData['_confidence'] ?? 0,
            ]);

            Log::info('Instant Estate OCR completed', [
                'session_id' => $this->session->session_id,
                'fields_extracted' => count(array_filter($validatedData)),
                'confidence' => $validatedData['_confidence'] ?? 0,
            ]);

            // Step 5: Validate data completeness
            $this->updateStatus('data_validated', 'Data validated');

            // Step 6: Search database for existing records
            $matches = $this->searchDatabase($validatedData);

            $this->session->update([
                'database_search_results' => $matches,
                'search_performed_at' => now(),
                'status' => 'search_completed',
            ]);

            // Step 7: Auto-fill if matches found
            if (!empty($matches)) {
                $calculation = $this->autoFillCalculator($validatedData, $matches[0]);
                $this->session->update([
                    'calculation_id' => $calculation->id,
                    'status' => 'auto_filled',
                    'completed_at' => now(),
                ]);

                Log::info('Instant Estate auto-filled', [
                    'session_id' => $this->session->session_id,
                    'calculation_id' => $calculation->id,
                ]);
            } else {
                $this->session->update([
                    'status' => 'redirected_to_manual',
                    'completed_at' => now(),
                ]);

                Log::info('Instant Estate - no matches found', [
                    'session_id' => $this->session->session_id,
                ]);
            }

            // Step 8: Extract debts from document
            $this->extractDebts($validatedData);

            // Step 9: Extract will content
            $this->extractWillContent($validatedData);

            // Calculate processing time
            $processingTime = (microtime(true) - $this->startTime) * 1000;
            $this->session->update(['processing_time_ms' => (int) $processingTime]);

            DB::commit();

            Log::info('Instant Estate processing completed', [
                'session_id' => $this->session->session_id,
                'processing_time_ms' => round($processingTime),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = 'Processing failed: ' . $e->getMessage();
            Log::error('Instant Estate Job Failed: ' . $errorMessage, [
                'session_id' => $this->session->session_id,
                'trace' => $e->getTraceAsString(),
            ]);

            $this->session->update([
                'status' => 'failed',
                'error_message' => $errorMessage,
                'completed_at' => now(),
            ]);

            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff[$this->attempts() - 1]);
            }

            throw $e;
        }
    }

    protected function updateStatus(string $status, string $message): void
    {
        $this->session->update(['status' => $status]);
        Log::info("Instant Estate: {$message}", [
            'session_id' => $this->session->session_id,
            'status' => $status,
        ]);
    }

    protected function searchDatabase(array $extractedData): array
    {
        $matches = [];

        if (!empty($extractedData['deceased_nric'])) {
            $matches = Calculation::where('deceased_nric', $extractedData['deceased_nric'])
                ->where('user_id', $this->session->user_id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->toArray();
        }

        if (empty($matches) && !empty($extractedData['deceased_name'])) {
            $matches = Calculation::where('deceased_name', 'LIKE', '%' . $extractedData['deceased_name'] . '%')
                ->where('user_id', $this->session->user_id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->toArray();
        }

        return $matches;
    }

    protected function autoFillCalculator(array $extractedData, ?array $existingCalculation = null): Calculation
    {
        $heirsData = $this->prepareHeirsData($extractedData);

        $calculationData = [
            'user_id' => $this->session->user_id,
            'session_id' => $this->session->session_id,
            'deceased_name' => $existingCalculation['deceased_name'] ?? $extractedData['deceased_name'] ?? null,
            'deceased_nric' => $existingCalculation['deceased_nric'] ?? $extractedData['deceased_nric'] ?? null,
            'deceased_gender' => isset($extractedData['is_male']) ? ($extractedData['is_male'] ? 'male' : 'female') : null,
            'date_of_death' => $existingCalculation['date_of_death'] ?? $extractedData['death_date'] ?? null,
            'marital_status' => $extractedData['marital_status'] ?? 'married',
            'total_assets' => $existingCalculation['total_assets'] ?? $extractedData['estate_value'] ?? 0,
            'net_assets' => $existingCalculation['net_assets'] ?? $extractedData['estate_value'] ?? 0,
            'spouse_name' => $extractedData['spouse_name'] ?? null,
            'father_name' => $extractedData['father_name'] ?? null,
            'mother_name' => $extractedData['mother_name'] ?? null,
            'wife_count' => $heirsData['wife_count'] ?? 0,
            'husband_count' => $heirsData['husband_count'] ?? 0,
            'father_status' => $heirsData['father_status'] ?? 'deceased',
            'mother_status' => $heirsData['mother_status'] ?? 'deceased',
            'son_count' => $heirsData['son_count'] ?? 0,
            'daughter_count' => $heirsData['daughter_count'] ?? 0,
            'status' => 'draft',
            'source' => 'instant_estate',
            'calculation_method' => 'instant_estate',
            'calculation_hash' => Calculation::generateHash(),
        ];

        $calculation = Calculation::create($calculationData);

        $this->session->update([
            'auto_fill_data' => [
                'calculation_id' => $calculation->id,
                'filled_at' => now()->toIso8601String(),
                'extracted_data_used' => $extractedData,
            ],
        ]);

        return $calculation;
    }

    protected function prepareHeirsData(array $extractedData): array
    {
        $heirsData = [
            'wife_count' => 0,
            'husband_count' => 0,
            'father_status' => 'deceased',
            'mother_status' => 'deceased',
            'son_count' => 0,
            'daughter_count' => 0,
        ];

        // Set spouse based on gender
        if (isset($extractedData['is_male'])) {
            if ($extractedData['is_male'] && !empty($extractedData['spouse_name'])) {
                $heirsData['wife_count'] = 1;
            } elseif (!$extractedData['is_male'] && !empty($extractedData['spouse_name'])) {
                $heirsData['husband_count'] = 1;
            }
        }

        // Set parents status
        if (!empty($extractedData['father_name'])) {
            $heirsData['father_status'] = 'alive';
        }

        if (!empty($extractedData['mother_name'])) {
            $heirsData['mother_status'] = 'alive';
        }

        // Set children distribution
        if (!empty($extractedData['children_count'])) {
            $childrenCount = (int) $extractedData['children_count'];
            $heirsData['son_count'] = (int) ceil($childrenCount / 2);
            $heirsData['daughter_count'] = (int) floor($childrenCount / 2);
        }

        return $heirsData;
    }

    protected function extractDebts(array $extractedData): void
    {
        $debts = [];

        // Check for debts in extracted data
        if (!empty($extractedData['debts']) && is_array($extractedData['debts'])) {
            $debts = $extractedData['debts'];
        }

        // Save debts to database
        foreach ($debts as $debtData) {
            EstateDebt::create([
                'instant_estate_session_id' => $this->session->id,
                'creditor_name' => $debtData['creditor_name'] ?? 'Unknown Creditor',
                'amount' => $debtData['amount'] ?? 0,
                'description' => $debtData['description'] ?? null,
                'status' => 'pending',
            ]);
        }

        // Update debt summary
        $this->updateDebtSummary();
    }

    protected function updateDebtSummary(): void
    {
        $totalDebts = EstateDebt::where('instant_estate_session_id', $this->session->id)->sum('amount');
        $debtsPaid = EstateDebt::where('instant_estate_session_id', $this->session->id)->sum('amount_paid');

        $this->session->update([
            'total_debts' => $totalDebts,
            'debts_paid' => $debtsPaid,
            'debts_settled' => $debtsPaid >= $totalDebts,
        ]);
    }

    protected function extractWillContent(array $extractedData): void
    {
        $willContent = null;

        if (!empty($extractedData['will_content'])) {
            $willContent = $extractedData['will_content'];
        }

        if ($willContent) {
            $this->session->update([
                'will_content' => $willContent,
                'will_access_token' => bin2hex(random_bytes(32)),
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Instant Estate Job Failed Permanently', [
            'session_id' => $this->session->session_id,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);

        $this->session->update([
            'status' => 'failed',
            'error_message' => 'Processing failed after ' . $this->attempts() . ' attempts: ' . $exception->getMessage(),
            'completed_at' => now(),
        ]);
    }
}