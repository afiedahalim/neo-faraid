<?php
// app/Services/DebtSettlementService.php

namespace App\Services;

use App\Models\EstatePreRegistration;
use App\Models\PreRegisteredDebt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebtSettlementService
{
    /**
     * Check if all debts are settled for an estate
     */
    public function areAllDebtsSettled(EstatePreRegistration $estate): bool
    {
        $unsatisfiedDebts = $estate->debts()
            ->where(function ($query) {
                $query->where('status', '!=', 'settled')
                    ->orWhereRaw('amount > amount_paid');
            })
            ->count();

        return $unsatisfiedDebts === 0;
    }

    /**
     * Get unsettled debts for an estate
     */
    public function getUnsettledDebts(EstatePreRegistration $estate): array
    {
        return $estate->debts()
            ->where(function ($query) {
                $query->where('status', '!=', 'settled')
                    ->orWhereRaw('amount > amount_paid');
            })
            ->get()
            ->map(function ($debt) {
                return [
                    'id' => $debt->id,
                    'creditor_name' => $debt->creditor_name,
                    'amount' => $debt->amount,
                    'amount_paid' => $debt->amount_paid,
                    'remaining' => $debt->amount - $debt->amount_paid,
                    'status' => $debt->status,
                ];
            })
            ->toArray();
    }

    /**
     * Get debt settlement summary
     */
    public function getSettlementSummary(EstatePreRegistration $estate): array
    {
        $totalDebts = $estate->debts()->sum('amount');
        $totalPaid = $estate->debts()->sum('amount_paid');
        $totalRemaining = $totalDebts - $totalPaid;
        $settledCount = $estate->debts()->where('status', 'settled')->count();
        $totalCount = $estate->debts()->count();

        return [
            'total_debts' => $totalDebts,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'settled_count' => $settledCount,
            'total_count' => $totalCount,
            'all_settled' => $totalRemaining <= 0 && $settledCount === $totalCount,
            'formatted_total_debts' => 'RM ' . number_format($totalDebts, 2),
            'formatted_total_paid' => 'RM ' . number_format($totalPaid, 2),
            'formatted_total_remaining' => 'RM ' . number_format($totalRemaining, 2),
        ];
    }

    /**
     * Record a debt payment
     */
    public function recordPayment(
        PreRegisteredDebt $debt,
        float $amount,
        string $paymentMethod = null,
        string $referenceNumber = null,
        string $notes = null
    ): array {
        DB::beginTransaction();

        try {
            $newAmountPaid = $debt->amount_paid + $amount;
            $remaining = $debt->amount - $newAmountPaid;
            
            // Determine new status
            if ($remaining <= 0) {
                $status = 'settled';
                $settledAt = now();
            } else {
                $status = 'partial';
                $settledAt = null;
            }

            // Record payment in history
            $paymentHistory = $debt->payment_history ?? [];
            $paymentHistory[] = [
                'amount' => $amount,
                'method' => $paymentMethod,
                'reference' => $referenceNumber,
                'notes' => $notes,
                'date' => now()->toDateTimeString(),
                'remaining_after' => $remaining,
            ];

            $debt->update([
                'amount_paid' => $newAmountPaid,
                'status' => $status,
                'settled_at' => $settledAt,
                'payment_history' => $paymentHistory,
            ]);

            DB::commit();

            return [
                'success' => true,
                'debt_id' => $debt->id,
                'amount_paid' => $newAmountPaid,
                'remaining' => $remaining,
                'status' => $status,
                'is_fully_settled' => $status === 'settled',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to record debt payment: ' . $e->getMessage(), [
                'debt_id' => $debt->id,
                'amount' => $amount,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Mark a debt as fully settled
     */
    public function markAsSettled(
        PreRegisteredDebt $debt,
        string $referenceNumber = null,
        string $notes = null
    ): array {
        DB::beginTransaction();

        try {
            $remaining = $debt->amount - $debt->amount_paid;
            
            if ($remaining > 0) {
                // Record the remaining amount as paid
                $paymentHistory = $debt->payment_history ?? [];
                $paymentHistory[] = [
                    'amount' => $remaining,
                    'method' => 'bulk_settlement',
                    'reference' => $referenceNumber,
                    'notes' => $notes ?? 'Marked as settled by admin',
                    'date' => now()->toDateTimeString(),
                    'remaining_after' => 0,
                ];

                $debt->amount_paid = $debt->amount;
            }

            $debt->status = 'settled';
            $debt->settled_at = now();
            $debt->settlement_reference = $referenceNumber;
            $debt->payment_history = $paymentHistory ?? $debt->payment_history;
            $debt->save();

            DB::commit();

            return [
                'success' => true,
                'debt_id' => $debt->id,
                'message' => 'Debt marked as settled',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark debt as settled: ' . $e->getMessage(), [
                'debt_id' => $debt->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}