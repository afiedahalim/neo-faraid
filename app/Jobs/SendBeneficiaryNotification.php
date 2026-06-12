<?php

namespace App\Jobs;

use App\Models\EstatePreRegistration;
use App\Models\BeneficiaryAccessLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class SendBeneficiaryNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     */
    public $backoff = [60, 300, 600];

    protected $recipientData;
    protected $estate;
    protected $accessLink;

    public function __construct(array $recipientData, EstatePreRegistration $estate, BeneficiaryAccessLink $accessLink = null)
    {
        $this->recipientData = $recipientData;
        $this->estate = $estate;
        $this->accessLink = $accessLink;
    }

    public function handle(): void
    {
        try {
            $email = $this->recipientData['email'] ?? null;
            $name = $this->recipientData['name'] ?? 'Beneficiary';
            $type = $this->recipientData['type'] ?? 'beneficiary';
            
            if (!$email) {
                Log::warning('SendBeneficiaryNotification: No email provided for beneficiary', [
                    'estate_id' => $this->estate->id,
                    'beneficiary_name' => $name,
                ]);
                return;
            }

            Log::info('SendBeneficiaryNotification: Preparing to send email', [
                'estate_id' => $this->estate->id,
                'deceased_name' => $this->estate->deceased_name,
                'recipient_email' => $this->maskEmail($email),
                'recipient_type' => $type,
                'attempt' => $this->attempts(),
            ]);

            // Generate PDF for this beneficiary
            $pdf = $this->generateBeneficiaryPDF();
            
            // Get video URL if available
            $videoUrl = $this->estate->will_video_url ?? null;
            $hasVideo = !empty($videoUrl);
            
            $subject = $this->getSubject($type);
            
            // Build email content
            $content = $this->getEmailContent($type, $name, $hasVideo, $videoUrl);
            
            // Send email with PDF attachment via SMTP
            Mail::send([], [], function ($message) use ($email, $subject, $content, $pdf, $hasVideo, $videoUrl) {
                $message->to($email)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->html($content);
                
                // Attach PDF with inheritance details
                $message->attachData($pdf->output(), 'inheritance-details-' . $this->estate->unique_id . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
            });

            // Update access link notification status
            if ($this->accessLink) {
                $this->accessLink->update([
                    'notification_sent_at' => now(),
                ]);
            }

            Log::info('SendBeneficiaryNotification: Email sent successfully via SMTP', [
                'estate_id' => $this->estate->id,
                'recipient_email' => $this->maskEmail($email),
                'recipient_type' => $type,
                'has_video' => $hasVideo,
                'has_pdf' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('SendBeneficiaryNotification: Failed to send email', [
                'estate_id' => $this->estate->id,
                'recipient_email' => $this->recipientData['email'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // If this was the last attempt, log final failure
            if ($this->attempts() >= $this->tries) {
                Log::critical('SendBeneficiaryNotification: All retry attempts exhausted', [
                    'estate_id' => $this->estate->id,
                    'recipient_email' => $this->recipientData['email'] ?? 'unknown',
                    'total_attempts' => $this->attempts(),
                ]);
            }
            
            throw $e;
        }
    }

    protected function generateBeneficiaryPDF()
    {
        $data = [
            'estate' => $this->estate,
            'recipient' => $this->recipientData,
            'accessLink' => $this->accessLink,
            'generated_at' => now()->format('d F Y H:i:s'),
        ];
        
        return Pdf::loadView('pdf.inheritance-details', $data);
    }

    protected function getSubject($type): string
    {
        $subjects = [
            'heir' => 'Inheritance Distribution Notification - ' . $this->estate->deceased_name,
            'trustee' => 'Trustee Appointment - Estate of ' . $this->estate->deceased_name,
            'alternate_trustee' => 'Alternate Trustee Appointment - Estate of ' . $this->estate->deceased_name,
            'wasiyyah' => 'Wasiyyah (Will) Notification - ' . $this->estate->deceased_name,
        ];
        
        return $subjects[$type] ?? 'Estate Planning Notification - ' . $this->estate->deceased_name;
    }

    protected function getEmailContent($type, $name, $hasVideo, $videoUrl): string
    {
        $sharePercentage = $this->recipientData['share_percentage'] ?? $this->recipientData['percentage'] ?? null;
        $secureLink = $this->recipientData['secure_link'] ?? '#';
        $expiryDate = now()->addDays(30)->format('d F Y');
        
        $content = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Inheritance Distribution Notification</title>
            <style>
                body { font-family: Poppins, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #1a5fb4, #2d7ad6); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .header h2 { margin: 0; font-size: 22px; }
                .header p { margin: 5px 0 0; opacity: 0.9; }
                .content { background: #fff; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #25D366; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; font-weight: bold; }
                .button:hover { background: #128C7E; }
                .info-box { background: #f5f5f5; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #1a5fb4; }
                .video-box { background: #e8f4f8; padding: 15px; border-radius: 8px; margin: 15px 0; text-align: center; border-left: 4px solid #ff0000; }
                .video-link { display: inline-block; background: #ff0000; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #888; }
                .amount { font-size: 24px; font-weight: bold; color: #25D366; }
                .warning-box { background: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Inheritance Distribution Notification</h2>
                    <p>Estate of ' . htmlspecialchars($this->estate->deceased_name) . '</p>
                </div>
                <div class="content">
                    <p>Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
                    
                    <p>You have been named as a <strong>' . ucfirst($type) . '</strong> in the estate of <strong>' . htmlspecialchars($this->estate->deceased_name) . '</strong>.</p>';
        
        if ($sharePercentage) {
            $amount = ($sharePercentage / 100) * ($this->estate->net_estate ?? 0);
            $content .= '
                    <div class="info-box">
                        <p><strong>Your Inheritance Details:</strong></p>
                        <p>Share Percentage: <strong>' . number_format($sharePercentage, 2) . '%</strong></p>
                        <p class="amount">Estimated Amount: RM ' . number_format($amount, 2) . '</p>
                    </div>';
        }
        
        if ($hasVideo && $videoUrl) {
            $content .= '
                    <div class="video-box">
                        <p><strong>📹  Wasiyyah Video Available</strong></p>
                        <p>The deceased has recorded a wasiyyah video. Click below to watch:</p>
                        <a href="' . htmlspecialchars($videoUrl) . '" class="video-link" style="color: white;">▶ Watch Wasiyyah Video</a>
                    </div>';
        }
        
        $content .= '
                    <p>Click the button below to view your complete inheritance details:</p>
                    
                    <div style="text-align: center;">
                        <a href="' . htmlspecialchars($secureLink) . '" class="button">View Inheritance Details</a>
                    </div>
                    
                    <div class="info-box">
                        <p><strong>📌   Attached to this email:</strong></p>
                        <p>✓ PDF Document with your complete inheritance details</p>
                        <p>✓ Share percentage and estimated amount breakdown</p>
                        <p>✓ Estate summary and distribution plan</p>
                    </div>
                    
                    <div class="warning-box">
                        <p><strong>⚠️   Important Notes:</strong></p>
                        <p>• This link is for your personal use only. Do not share it with others.</p>
                        <p>• The link wasiyyah expire on <strong>' . $expiryDate . '</strong>.</p>
                        <p>• Each access is logged for security purposes.</p>
                        <p>• The attached PDF is for your records.</p>
                    </div>
                    
                    <p>If you have any questions, please contact the estate administrator or the appointed trustee.</p>
                    
                    <p>Best regards,<br><strong>Neo Faraid Team</strong></p>
                </div>
                <div class="footer">
                    <p>This is an automated message from Neo Faraid. Please do not reply to this email.</p>
                    <p>If you believe you received this email in error, please contact support.</p>
                    <p>&copy; ' . date('Y') . ' Neo Faraid. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $content;
    }

    /**
     * Mask email for logging purposes.
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        
        if (count($parts) !== 2) {
            return '***@***.***';
        }
        
        $name = $parts[0];
        $domain = $parts[1];
        
        $visibleChars = min(2, max(1, (int)(strlen($name) / 3)));
        $maskedName = substr($name, 0, $visibleChars) . 
                      str_repeat('*', max(0, strlen($name) - $visibleChars));
        
        return $maskedName . '@' . $domain;
    }
}