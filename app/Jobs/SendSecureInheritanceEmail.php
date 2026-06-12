<?php

namespace App\Jobs;

use App\Mail\SecureInheritanceMail;
use App\Models\EstateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Throwable;

class SendSecureInheritanceEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     * Retry after: 1 minute, 5 minutes, 15 minutes
     *
     * @var array
     */
    public $backoff = [60, 300, 900];

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * The estate notification model instance.
     *
     * @var EstateNotification
     */
    protected EstateNotification $notification;

    /**
     * The secure access URL for the beneficiary.
     *
     * @var string
     */
    protected string $secureUrl;

    /**
     * Recipient information array.
     *
     * @var array
     */
    protected array $recipient;

    /**
     * Whether this is a retry attempt.
     *
     * @var bool
     */
    protected bool $isRetry;

    /**
     * Job processing start time for performance tracking.
     *
     * @var float
     */
    protected float $startTime;

    /**
     * Create a new job instance.
     *
     * @param EstateNotification $notification
     * @param string $secureUrl
     * @param array $recipient
     * @param bool $isRetry
     */
    public function __construct(
        EstateNotification $notification,
        string $secureUrl,
        array $recipient,
        bool $isRetry = false
    ) {
        $this->notification = $notification;
        $this->secureUrl = $secureUrl;
        $this->recipient = $recipient;
        $this->isRetry = $isRetry;

        // Set queue based on priority
        $this->onQueue($this->determineQueue());
        
        // Set delay for retries
        if ($isRetry) {
            $this->delay = now()->addMinutes(2);
        }
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [
            // Prevent overlapping emails to same recipient within 30 seconds
            new WithoutOverlapping(
                'inheritance-email:' . $this->recipient['email'],
                30
            ),
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->startTime = microtime(true);

        Log::info('📧 SendSecureInheritanceEmail: Processing started', [
            'notification_id' => $this->notification->id,
            'recipient_email' => $this->maskEmail($this->recipient['email']),
            'recipient_type' => $this->recipient['type'] ?? 'unknown',
            'attempt' => $this->attempts(),
            'is_retry' => $this->isRetry,
            'queue' => $this->queue,
        ]);

        try {
            // Pre-send validation
            $this->validateBeforeSend();

            // Check rate limiting
            $this->checkRateLimit();

            // Check if notification is still valid
            if (!$this->isNotificationValid()) {
                $this->handleExpiredNotification();
                return;
            }

            // Check duplicate sends
            if ($this->isDuplicateSend()) {
                Log::warning('Duplicate email send prevented', [
                    'notification_id' => $this->notification->id,
                    'email' => $this->maskEmail($this->recipient['email']),
                ]);
                return;
            }

            // Update notification status to sending
            $this->notification->update([
                'status' => 'sending',
                'sending_started_at' => now(),
            ]);

            // Send the email
            $this->sendEmail();

            // Update notification on success
            $this->markAsSent();

            // Log success
            $processingTime = round((microtime(true) - $this->startTime) * 1000, 2);
            
            Log::info('✅ SendSecureInheritanceEmail: Email sent successfully', [
                'notification_id' => $this->notification->id,
                'recipient_email' => $this->maskEmail($this->recipient['email']),
                'recipient_type' => $this->recipient['type'] ?? 'unknown',
                'attempt' => $this->attempts(),
                'processing_time_ms' => $processingTime,
            ]);

        } catch (\Swift_TransportException $e) {
            // SMTP/transport errors - retryable
            $this->handleTransportError($e);
            
        } catch (\Swift_RfcComplianceException $e) {
            // Invalid email address - do NOT retry
            $this->handleInvalidEmailError($e);
            
        } catch (\Illuminate\Mail\Events\MessageSending $e) {
            // Message sending errors
            $this->handleSendingError($e);
            
        } catch (\Exception $e) {
            // General errors
            $this->handleGeneralError($e);
        }
    }

    /**
     * Send the actual email
     */
    protected function sendEmail(): void
    {
        $mail = new SecureInheritanceMail(
            $this->notification,
            $this->secureUrl,
            $this->recipient
        );

        // Send with specific configuration
        Mail::to($this->recipient['email'])
            ->send($mail);

        // Small delay to allow mailer to process
        usleep(100000); // 100ms
    }

    // ==================== PRE-SEND VALIDATION ====================

    /**
     * Validate before sending
     */
    protected function validateBeforeSend(): void
    {
        // Validate email format
        if (!filter_var($this->recipient['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                "Invalid email address: {$this->recipient['email']}"
            );
        }

        // Validate email domain has valid MX records
        if (!$this->hasValidMxRecord($this->recipient['email'])) {
            Log::warning('Email domain has no MX record', [
                'email' => $this->maskEmail($this->recipient['email']),
                'notification_id' => $this->notification->id,
            ]);
            // Don't throw - still attempt delivery (some domains use different configs)
        }

        // Validate secure URL is accessible
        if (!filter_var($this->secureUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(
                "Invalid secure URL: {$this->secureUrl}"
            );
        }

        // Validate notification exists and is in valid state
        if (!$this->notification->exists) {
            throw new \RuntimeException(
                "Notification record not found: {$this->notification->id}"
            );
        }
    }

    /**
     * Check if email domain has valid MX records
     */
    protected function hasValidMxRecord(string $email): bool
    {
        $domain = substr(strrchr($email, "@"), 1);
        
        if (empty($domain)) {
            return false;
        }

        // Cache MX lookup results for 1 hour
        $cacheKey = "mx_record:{$domain}";
        
        return Cache::remember($cacheKey, 3600, function () use ($domain) {
            return checkdnsrr($domain, 'MX');
        });
    }

    /**
     * Check rate limiting for email sending
     */
    protected function checkRateLimit(): void
    {
        $key = "email_rate_limit:" . $this->recipient['email'];
        $maxEmailsPerHour = 5;
        $windowSeconds = 3600;

        $sentCount = Cache::get($key, 0);

        if ($sentCount >= $maxEmailsPerHour) {
            Log::warning('Email rate limit exceeded', [
                'email' => $this->maskEmail($this->recipient['email']),
                'sent_count' => $sentCount,
                'notification_id' => $this->notification->id,
            ]);

            // Release job back to queue with delay
            $this->release(900); // 15 minutes delay
            
            throw new \RuntimeException(
                "Rate limit exceeded for {$this->recipient['email']}. " .
                "Released for retry in 15 minutes."
            );
        }

        // Increment counter
        Cache::put($key, $sentCount + 1, $windowSeconds);
    }

    /**
     * Check if notification is still valid to send
     */
    protected function isNotificationValid(): bool
    {
        // Check if already sent successfully
        if ($this->notification->status === 'sent' && 
            !empty($this->notification->sent_at)) {
            return false;
        }

        // Check if permanently failed
        if ($this->notification->status === 'permanently_failed') {
            return false;
        }

        // Check if cancelled
        if ($this->notification->status === 'cancelled') {
            return false;
        }

        // Check if expired
        if ($this->notification->expires_at && 
            $this->notification->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check for duplicate sends using idempotency
     */
    protected function isDuplicateSend(): bool
    {
        $idempotencyKey = "email_sent:{$this->notification->id}:" . 
                         md5($this->recipient['email']);

        if (Cache::has($idempotencyKey)) {
            return true;
        }

        // Set idempotency lock for 5 minutes
        Cache::put($idempotencyKey, true, 300);

        return false;
    }

    // ==================== SUCCESS HANDLERS ====================

    /**
     * Mark notification as successfully sent
     */
    protected function markAsSent(): void
    {
        $this->notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'delivery_status' => 'delivered',
            'sending_completed_at' => now(),
            'email_attempts' => $this->attempts(),
            'last_error' => null,
            'processing_time_ms' => round((microtime(true) - $this->startTime) * 1000, 2),
        ]);
    }

    // ==================== ERROR HANDLERS ====================

    /**
     * Handle transport errors (retryable)
     */
    protected function handleTransportError(\Swift_TransportException $e): void
    {
        Log::error('📧 Email transport error (will retry)', [
            'notification_id' => $this->notification->id,
            'email' => $this->maskEmail($this->recipient['email']),
            'error' => $e->getMessage(),
            'attempt' => $this->attempts(),
        ]);

        $this->notification->update([
            'status' => 'failed',
            'delivery_status' => 'transport_error',
            'last_error' => $e->getMessage(),
            'last_error_at' => now(),
            'email_attempts' => $this->attempts(),
        ]);

        // If this was the last attempt, mark as permanently failed
        if ($this->attempts() >= $this->tries) {
            $this->handlePermanentFailure($e->getMessage(), 'transport_error_max_retries');
            return;
        }

        // Re-throw to trigger retry
        throw $e;
    }

    /**
     * Handle invalid email errors (non-retryable)
     */
    protected function handleInvalidEmailError(\Exception $e): void
    {
        Log::error('📧 Invalid email address (not retrying)', [
            'notification_id' => $this->notification->id,
            'email' => $this->maskEmail($this->recipient['email']),
            'error' => $e->getMessage(),
        ]);

        $this->handlePermanentFailure(
            "Invalid email address: {$e->getMessage()}",
            'invalid_email_address'
        );
    }

    /**
     * Handle sending errors
     */
    protected function handleSendingError(\Exception $e): void
    {
        Log::error('📧 Email sending error', [
            'notification_id' => $this->notification->id,
            'email' => $this->maskEmail($this->recipient['email']),
            'error' => $e->getMessage(),
            'attempt' => $this->attempts(),
        ]);

        $this->notification->update([
            'delivery_status' => 'sending_error',
            'last_error' => $e->getMessage(),
            'last_error_at' => now(),
        ]);

        if ($this->attempts() >= $this->tries) {
            $this->handlePermanentFailure($e->getMessage(), 'sending_error_max_retries');
            return;
        }

        throw $e;
    }

    /**
     * Handle general errors
     */
    protected function handleGeneralError(\Exception $e): void
    {
        Log::error('📧 Unexpected email error', [
            'notification_id' => $this->notification->id,
            'email' => $this->maskEmail($this->recipient['email']),
            'error' => $e->getMessage(),
            'attempt' => $this->attempts(),
            'trace' => $e->getTraceAsString(),
        ]);

        $this->notification->update([
            'delivery_status' => 'error',
            'last_error' => $e->getMessage(),
            'last_error_at' => now(),
            'email_attempts' => $this->attempts(),
        ]);

        if ($this->attempts() >= $this->tries) {
            $this->handlePermanentFailure($e->getMessage(), 'unexpected_error_max_retries');
            return;
        }

        throw $e;
    }

    /**
     * Handle permanent failure (all retries exhausted)
     */
    protected function handlePermanentFailure(string $errorMessage, string $failureReason): void
    {
        Log::warning('📧 Email permanently failed after all retries', [
            'notification_id' => $this->notification->id,
            'email' => $this->maskEmail($this->recipient['email']),
            'reason' => $failureReason,
            'error' => $errorMessage,
            'total_attempts' => $this->attempts(),
        ]);

        $this->notification->update([
            'status' => 'permanently_failed',
            'delivery_status' => 'failed',
            'failure_reason' => $failureReason,
            'failure_details' => $errorMessage,
            'failed_at' => now(),
            'email_attempts' => $this->attempts(),
            'last_error' => $errorMessage,
            'last_error_at' => now(),
            'requires_manual_intervention' => true,
        ]);

        // Log for admin attention
        Log::critical('🚨 Email delivery permanently failed - Admin attention required', [
            'notification_id' => $this->notification->id,
            'recipient_email' => $this->maskEmail($this->recipient['email']),
            'recipient_name' => $this->recipient['name'] ?? 'Unknown',
            'recipient_type' => $this->recipient['type'] ?? 'unknown',
            'estate_id' => $this->notification->estate_pre_registration_id,
            'failure_reason' => $failureReason,
            'error_message' => $errorMessage,
            'action_required' => 'Manual intervention needed to resend this notification',
        ]);
    }

    /**
     * Handle expired notification
     */
    protected function handleExpiredNotification(): void
    {
        Log::info('Notification expired, marking as such', [
            'notification_id' => $this->notification->id,
        ]);

        $this->notification->update([
            'status' => 'expired',
            'delivery_status' => 'not_sent_expired',
            'expired_at' => now(),
        ]);

        // Don't throw - just silently complete
    }

    // ==================== FAILED JOB HANDLER ====================

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('💥 SendSecureInheritanceEmail: Job failed completely', [
            'notification_id' => $this->notification->id,
            'recipient_email' => $this->maskEmail($this->recipient['email']),
            'recipient_type' => $this->recipient['type'] ?? 'unknown',
            'error' => $exception->getMessage(),
            'total_attempts' => $this->attempts(),
            'job_id' => $this->job->getJobId() ?? null,
        ]);

        // Mark as permanently failed if not already done
        if ($this->notification->status !== 'permanently_failed') {
            $this->notification->update([
                'status' => 'permanently_failed',
                'delivery_status' => 'failed',
                'failure_reason' => 'job_failed_exception',
                'failure_details' => $exception->getMessage(),
                'failed_at' => now(),
                'last_error' => $exception->getMessage(),
                'last_error_at' => now(),
                'requires_manual_intervention' => true,
                'job_id' => $this->job->getJobId() ?? null,
            ]);
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Determine which queue to use based on priority
     */
    protected function determineQueue(): string
    {
        $type = $this->recipient['type'] ?? 'default';
        
        return match($type) {
            'trustee' => 'notifications-high',
            'heir' => 'notifications',
            'wasiyyah' => 'notifications',
            default => 'notifications-low',
        };
    }

    /**
     * Mask email for logging
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

    /**
     * Get job metadata for monitoring
     */
    public function metadata(): array
    {
        return [
            'notification_id' => $this->notification->id,
            'recipient_type' => $this->recipient['type'] ?? 'unknown',
            'recipient_email_domain' => substr(strrchr($this->recipient['email'] ?? '', "@"), 1),
            'is_retry' => $this->isRetry,
            'estate_id' => $this->notification->estate_pre_registration_id,
            'queue' => $this->queue,
            'tries' => $this->tries,
            'timeout' => $this->timeout,
        ];
    }

    /**
     * Get tags for monitoring
     */
    public function tags(): array
    {
        return [
            'inheritance-email',
            'type:' . ($this->recipient['type'] ?? 'unknown'),
            'estate:' . ($this->notification->estate_pre_registration_id ?? 'none'),
        ];
    }

    /**
     * Display name for the job in queue monitor
     */
    public function displayName(): string
    {
        return "Send Inheritance Email to " . $this->maskEmail($this->recipient['email']);
    }
}