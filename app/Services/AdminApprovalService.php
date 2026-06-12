<?php
// app/Services/AdminApprovalService.php

namespace App\Services;

use App\Models\EstatePreRegistration;
use App\Models\BeneficiaryAccessLink;
use App\Models\PreRegisteredHeir;
use App\Models\PreRegisteredWasiyyah;
use App\Mail\BeneficiaryAccessMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminApprovalService
{
    protected DebtSettlementService $debtService;
    protected NotificationService $notificationService;

    public function __construct(
        DebtSettlementService $debtService,
        NotificationService $notificationService
    ) {
        $this->debtService = $debtService;
        $this->notificationService = $notificationService;
    }

    /**
     * Check if estate is ready for admin approval
     */
    public function isReadyForApproval(EstatePreRegistration $estate): array
    {
        $issues = [];

        // Check basic requirements
        if (empty($estate->deceased_name)) {
            $issues[] = 'Deceased name is missing';
        }
        if (empty($estate->deceased_nric)) {
            $issues[] = 'Deceased NRIC is missing';
        }
        if ($estate->heirs()->count() === 0) {
            $issues[] = 'No heirs have been added';
        }
        if ($estate->assets()->count() === 0) {
            $issues[] = 'No assets have been added';
        }
        if ($estate->net_estate <= 0) {
            $issues[] = 'Net estate value must be greater than zero';
        }
        if (empty($estate->trustee_name) || empty($estate->trustee_email)) {
            $issues[] = 'Trustee information is incomplete';
        }

        // Check heir distribution
        $totalHeirPercentage = $estate->heirs()->sum('share_percentage');
        if (abs($totalHeirPercentage - 100) > 0.01) {
            $issues[] = "Heir distribution must equal 100% (currently {$totalHeirPercentage}%)";
        }

        // Check wasiyyah limit
        $totalWasiyyah = $estate->wasiyyah()->sum('requested_percentage');
        if ($totalWasiyyah > 33.33) {
            $issues[] = "Total wasiyyah ({$totalWasiyyah}%) exceeds the 1/3 limit (33.33%)";
        }

        // Check for missing emails
        $heirsWithoutEmail = $estate->heirs()->whereNull('email')->orWhere('email', '')->count();
        if ($heirsWithoutEmail > 0) {
            $issues[] = "{$heirsWithoutEmail} heir(s) are missing email addresses";
        }

        $wasiyyahWithoutEmail = $estate->wasiyyah()->whereNull('beneficiary_email')->orWhere('beneficiary_email', '')->count();
        if ($wasiyyahWithoutEmail > 0) {
            $issues[] = "{$wasiyyahWithoutEmail} wasiyyah beneficiary(s) are missing email addresses";
        }

        return [
            'ready' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Approve an estate after admin review
     */
    public function approve(EstatePreRegistration $estate, int $adminId, string $notes = null): array
    {
        DB::beginTransaction();

        try {
            // Check if debts are settled
            $debtsSettled = $this->debtService->areAllDebtsSettled($estate);
            
            if (!$debtsSettled) {
                $unsettledDebts = $this->debtService->getUnsettledDebts($estate);
                return [
                    'success' => false,
                    'error' => 'Debt settlement required',
                    'unsettled_debts' => $unsettledDebts,
                    'message' => 'Cannot approve estate until all debts are settled.',
                ];
            }

            // Update estate status
            $estate->update([
                'admin_approved' => true,
                'admin_approved_at' => now(),
                'admin_approved_by' => $adminId,
                'admin_notes' => $notes,
                'status' => 'activated',
                'activated_at' => now(),
            ]);

            // Generate access links for all beneficiaries
            $accessLinks = $this->generateAccessLinks($estate);
            
            // Send notification emails
            $emailResults = $this->sendBeneficiaryNotifications($estate, $accessLinks);

            DB::commit();

            // Log the approval
            Log::info('Estate approved by admin', [
                'estate_id' => $estate->id,
                'admin_id' => $adminId,
                'beneficiaries_notified' => count($emailResults['sent']),
                'total_beneficiaries' => count($emailResults['total']),
            ]);

            return [
                'success' => true,
                'message' => 'Estate approved successfully. Access links have been sent to all beneficiaries.',
                'access_links_generated' => count($accessLinks),
                'emails_sent' => $emailResults['sent'],
                'emails_failed' => $emailResults['failed'],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve estate: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'admin_id' => $adminId,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Reject an estate
     */
    public function reject(EstatePreRegistration $estate, int $adminId, string $reason): array
    {
        DB::beginTransaction();

        try {
            $estate->update([
                'admin_approved' => false,
                'rejected_at' => now(),
                'rejected_by' => $adminId,
                'rejection_reason' => $reason,
                'status' => 'draft', // Return to draft so user can fix issues
            ]);

            DB::commit();

            Log::info('Estate rejected by admin', [
                'estate_id' => $estate->id,
                'admin_id' => $adminId,
                'reason' => $reason,
            ]);

            return [
                'success' => true,
                'message' => 'Estate rejected successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject estate: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'admin_id' => $adminId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate secure access links for all beneficiaries
     */
    protected function generateAccessLinks(EstatePreRegistration $estate): array
    {
        $accessLinks = [];
        $expiryDays = config('estate.access_link_expiry_days', 30);

        // Generate links for heirs
        foreach ($estate->heirs as $heir) {
            if (!empty($heir->email)) {
                $link = BeneficiaryAccessLink::createForBeneficiary(
                    $estate,
                    'heir',
                    $heir,
                    $expiryDays
                );
                $accessLinks['heirs'][] = $link;
            }
        }

        // Generate link for trustee
        if (!empty($estate->trustee_email)) {
            $link = BeneficiaryAccessLink::createForBeneficiary(
                $estate,
                'trustee',
                (object) [
                    'name' => $estate->trustee_name,
                    'email' => $estate->trustee_email,
                ],
                $expiryDays
            );
            $accessLinks['trustee'] = $link;
        }

        // Generate link for alternate trustee
        if (!empty($estate->alternate_trustee_email)) {
            $link = BeneficiaryAccessLink::createForBeneficiary(
                $estate,
                'alternate_trustee',
                (object) [
                    'name' => $estate->alternate_trustee_name,
                    'email' => $estate->alternate_trustee_email,
                ],
                $expiryDays
            );
            $accessLinks['alternate_trustee'] = $link;
        }

        // Generate links for wasiyyah beneficiaries
        foreach ($estate->wasiyyah as $wasiyyah) {
            if (!empty($wasiyyah->beneficiary_email)) {
                $link = BeneficiaryAccessLink::createForBeneficiary(
                    $estate,
                    'wasiyyah',
                    $wasiyyah,
                    $expiryDays
                );
                $accessLinks['wasiyyah'][] = $link;
            }
        }

        return $accessLinks;
    }

    /**
     * Send notification emails to all beneficiaries with their access links
     */
    protected function sendBeneficiaryNotifications(EstatePreRegistration $estate, array $accessLinks): array
    {
        $results = [
            'total' => [],
            'sent' => [],
            'failed' => [],
        ];

        // Send to heirs
        if (isset($accessLinks['heirs'])) {
            foreach ($accessLinks['heirs'] as $link) {
                $results['total'][] = $link->beneficiary_email;
                try {
                    Mail::to($link->beneficiary_email)->send(
                        new BeneficiaryAccessMail($estate, $link, 'heir')
                    );
                    $link->update(['notification_sent_at' => now()]);
                    $results['sent'][] = $link->beneficiary_email;
                } catch (\Exception $e) {
                    Log::error('Failed to send heir notification: ' . $e->getMessage(), [
                        'email' => $link->beneficiary_email,
                        'estate_id' => $estate->id,
                    ]);
                    $results['failed'][] = $link->beneficiary_email;
                }
            }
        }

        // Send to trustee
        if (isset($accessLinks['trustee'])) {
            $link = $accessLinks['trustee'];
            $results['total'][] = $link->beneficiary_email;
            try {
                Mail::to($link->beneficiary_email)->send(
                    new BeneficiaryAccessMail($estate, $link, 'trustee')
                );
                $link->update(['notification_sent_at' => now()]);
                $results['sent'][] = $link->beneficiary_email;
            } catch (\Exception $e) {
                Log::error('Failed to send trustee notification: ' . $e->getMessage(), [
                    'email' => $link->beneficiary_email,
                    'estate_id' => $estate->id,
                ]);
                $results['failed'][] = $link->beneficiary_email;
            }
        }

        // Send to alternate trustee
        if (isset($accessLinks['alternate_trustee'])) {
            $link = $accessLinks['alternate_trustee'];
            $results['total'][] = $link->beneficiary_email;
            try {
                Mail::to($link->beneficiary_email)->send(
                    new BeneficiaryAccessMail($estate, $link, 'alternate_trustee')
                );
                $link->update(['notification_sent_at' => now()]);
                $results['sent'][] = $link->beneficiary_email;
            } catch (\Exception $e) {
                Log::error('Failed to send alternate trustee notification: ' . $e->getMessage(), [
                    'email' => $link->beneficiary_email,
                    'estate_id' => $estate->id,
                ]);
                $results['failed'][] = $link->beneficiary_email;
            }
        }

        // Send to wasiyyah beneficiaries
        if (isset($accessLinks['wasiyyah'])) {
            foreach ($accessLinks['wasiyyah'] as $link) {
                $results['total'][] = $link->beneficiary_email;
                try {
                    Mail::to($link->beneficiary_email)->send(
                        new BeneficiaryAccessMail($estate, $link, 'wasiyyah')
                    );
                    $link->update(['notification_sent_at' => now()]);
                    $results['sent'][] = $link->beneficiary_email;
                } catch (\Exception $e) {
                    Log::error('Failed to send wasiyyah notification: ' . $e->getMessage(), [
                        'email' => $link->beneficiary_email,
                        'estate_id' => $estate->id,
                    ]);
                    $results['failed'][] = $link->beneficiary_email;
                }
            }
        }

        // Update estate notification tracking
        $estate->update([
            'notification_sent' => true,
            'notification_sent_at' => now(),
        ]);

        return $results;
    }

    /**
     * Check if an estate has active access links
     */
    public function hasActiveAccessLinks(EstatePreRegistration $estate): bool
    {
        return BeneficiaryAccessLink::where('estate_pre_registration_id', $estate->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Get all active access links for an estate
     */
    public function getActiveAccessLinks(EstatePreRegistration $estate)
    {
        return BeneficiaryAccessLink::where('estate_pre_registration_id', $estate->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->get();
    }
}