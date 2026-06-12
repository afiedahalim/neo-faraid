<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogsInstantEstateActivity
{
    /**
     * Log instant estate activity
     */
    protected function logInstantEstate(string $action, array $data = [], ?string $sessionId = null): void
    {
        $logData = array_merge($data, [
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        Log::channel('instant_estate')->info($action, $logData);
    }

    /**
     * Log OCR processing steps
     */
    protected function logOcrProcess(string $step, array $data = [], ?string $sessionId = null): void
    {
        Log::channel('ocr')->info("OCR: {$step}", array_merge($data, [
            'session_id' => $sessionId,
        ]));
    }

    /**
     * Log performance metrics
     */
    protected function logPerformance(string $operation, float $duration, array $metadata = []): void
    {
        Log::channel('performance')->info($operation, array_merge($metadata, [
            'duration_ms' => round($duration, 2),
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
        ]));
    }

    /**
     * Log errors
     */
    protected function logError(string $message, array $context = [], ?\Exception $exception = null): void
    {
        if ($exception) {
            $context['exception'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        Log::channel('errors')->error($message, $context);
        Log::channel('instant_estate')->error($message, $context);
    }

    /**
     * Log successful completion
     */
    protected function logSuccess(string $sessionId, array $results = []): void
    {
        $this->logInstantEstate('Session completed successfully', $results, $sessionId);
    }

    /**
     * Log status change
     */
    protected function logStatusChange(string $sessionId, string $oldStatus, string $newStatus, array $metadata = []): void
    {
        $this->logInstantEstate('Status changed', array_merge($metadata, [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]), $sessionId);
    }
}