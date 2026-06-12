<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\EstatePreRegistration;
use App\Models\BeneficiaryAccessLink;
use App\Mail\BeneficiaryAccessMail;
use App\Mail\EstateApprovedMail;
use App\Mail\EstateRejectedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send estate approved notification to the estate owner
     */
    public function sendEstateApprovedNotification(EstatePreRegistration $estate): bool
    {
        try {
            Mail::to($estate->user->email)->send(
                new EstateApprovedMail($estate)
            );
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send estate approved notification: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'email' => $estate->user->email,
            ]);
            return false;
        }
    }

    /**
     * Send estate rejected notification to the estate owner
     */
    public function sendEstateRejectedNotification(EstatePreRegistration $estate, string $reason): bool
    {
        try {
            Mail::to($estate->user->email)->send(
                new EstateRejectedMail($estate, $reason)
            );
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send estate rejected notification: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'email' => $estate->user->email,
            ]);
            return false;
        }
    }

    /**
     * Send reminder to beneficiaries about expiring access
     */
    public function sendExpiryReminder(BeneficiaryAccessLink $link): bool
    {
        $daysUntilExpiry = now()->diffInDays($link->expires_at);
        
        // Only send if within 7 days of expiry
        if ($daysUntilExpiry > 7) {
            return false;
        }

        try {
            Mail::to($link->beneficiary_email)->send(
                new AccessExpiryReminderMail($link, $daysUntilExpiry)
            );
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send expiry reminder: ' . $e->getMessage(), [
                'link_id' => $link->id,
                'email' => $link->beneficiary_email,
            ]);
            return false;
        }
    }
}