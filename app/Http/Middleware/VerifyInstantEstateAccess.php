<?php
// app/Http/Middleware/VerifyInstantEstateAccess.php

namespace App\Http\Middleware;

use App\Models\NotificationRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class VerifyInstantEstateAccess
{
    /**
     * Maximum access attempts per IP per hour
     */
    protected const MAX_ATTEMPTS_PER_IP = 10;

    /**
     * Rate limit decay in seconds (1 hour)
     */
    protected const RATE_LIMIT_DECAY = 3600;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Step 1: Extract token from route or query parameter
        $token = $this->extractToken($request);

        if (!$token) {
            $this->logAccessAttempt($request, null, 'missing_token', false);
            return $this->denyAccess('No access token provided.', 400);
        }

        // Step 2: Rate limit check
        if ($this->isRateLimited($request)) {
            $this->logAccessAttempt($request, $token, 'rate_limited', false);
            return $this->denyAccess(
                'Too many access attempts. Please try again in ' . 
                $this->getRateLimitRetryAfter($request) . ' seconds.',
                429
            );
        }

        // Step 3: Find valid notification request (instant-estate)
        $notificationRequest = $this->findValidNotificationRequest($token);

        if (!$notificationRequest) {
            // Check for expired/old notification
            $expiredRequest = $this->findExpiredNotificationRequest($token);
            if ($expiredRequest) {
                $this->incrementFailedAttempts($request);
                $this->logAccessAttempt($request, $token, 'expired', false);
                return $this->denyAccess(
                    'This access link has expired. Please request a new report.',
                    410,
                    'expired'
                );
            }

            $this->incrementFailedAttempts($request);
            $this->logAccessAttempt($request, $token, 'invalid_token', false);
            return $this->denyAccess(
                'Invalid or expired access link. Please contact the estate administrator.',
                403,
                'error'
            );
        }

        // Step 4: Check if notification is approved/sent
        if (!$this->isNotificationAccessible($notificationRequest)) {
            $this->incrementFailedAttempts($request);
            $this->logAccessAttempt($request, $token, 'not_accessible', false);
            
            if ($notificationRequest->status === NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL) {
                return $this->denyAccess(
                    'Your report request is pending admin approval. You will receive an email once approved.',
                    403,
                    'pending'
                );
            }
            
            if ($notificationRequest->status === NotificationRequest::STATUS_REJECTED) {
                return $this->denyAccess(
                    'Your report request has been rejected. Please contact the estate administrator for more information.',
                    403,
                    'rejected'
                );
            }
            
            return $this->denyAccess(
                'This report is not yet available for access. Status: ' . ucfirst(str_replace('_', ' ', $notificationRequest->status)),
                403,
                'unavailable'
            );
        }

        // Step 5: Check expiry date if set
        if ($this->isNotificationExpired($notificationRequest)) {
            $this->incrementFailedAttempts($request);
            $this->logAccessAttempt($request, $token, 'expired', false);
            return $this->denyAccess(
                'This access link has expired on ' . 
                ($notificationRequest->expires_at ? $notificationRequest->expires_at->format('d F Y, h:i A') : 'unknown date') . 
                '. Please request a new report.',
                410,
                'expired'
            );
        }

        // Step 6: Grant access
        $this->grantAccess($request, $notificationRequest, $token);

        // Merge data into request for controller
        $request->merge([
            'verified_notification' => $notificationRequest,
            'is_instant_estate' => true,
            'access_granted_at' => now(),
        ]);

        return $next($request);
    }

    // ==================== TOKEN EXTRACTION ====================

    /**
     * Extract token from request (route, query, or header)
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function extractToken(Request $request): ?string
    {
        // Check route parameter
        $token = $request->route('token');
        if ($token && is_string($token) && strlen($token) >= 32) {
            return $token;
        }

        // Check query parameter
        $token = $request->query('token');
        if ($token && is_string($token) && strlen($token) >= 32) {
            return $token;
        }

        // Check Authorization header (Bearer token)
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            if (strlen($token) >= 32) {
                return $token;
            }
        }

        return null;
    }

    // ==================== NOTIFICATION VALIDATION ====================

    /**
     * Find valid notification request
     *
     * @param  string  $token
     * @return NotificationRequest|null
     */
    protected function findValidNotificationRequest(string $token): ?NotificationRequest
    {
        if (!Schema::hasTable('notification_requests')) {
            Log::warning('Notification requests table does not exist');
            return null;
        }

        // Find by access_token (exact match)
        $notificationRequest = NotificationRequest::where('access_token', $token)
            ->first();

        // If not found, try with hashed token
        if (!$notificationRequest) {
            $hashedToken = hash('sha256', $token);
            $notificationRequest = NotificationRequest::where('access_token', $hashedToken)->first();
        }

        return $notificationRequest;
    }

    /**
     * Find expired notification request
     *
     * @param  string  $token
     * @return NotificationRequest|null
     */
    protected function findExpiredNotificationRequest(string $token): ?NotificationRequest
    {
        if (!Schema::hasTable('notification_requests')) {
            return null;
        }

        $expiredRequest = NotificationRequest::where('access_token', $token)
            ->where(function ($query) {
                $query->where('expires_at', '<=', now())
                    ->orWhere('created_at', '<=', now()->subDays(30));
            })
            ->first();

        if (!$expiredRequest) {
            $hashedToken = hash('sha256', $token);
            $expiredRequest = NotificationRequest::where('access_token', $hashedToken)
                ->where(function ($query) {
                    $query->where('expires_at', '<=', now())
                        ->orWhere('created_at', '<=', now()->subDays(30));
                })
                ->first();
        }

        return $expiredRequest;
    }

    /**
     * Check if notification is accessible (approved or sent)
     *
     * @param  NotificationRequest  $notificationRequest
     * @return bool
     */
    protected function isNotificationAccessible(NotificationRequest $notificationRequest): bool
    {
        return in_array($notificationRequest->status, [
            NotificationRequest::STATUS_SENT,
            NotificationRequest::STATUS_APPROVED,
        ]);
    }

    /**
     * Check if notification has expired
     *
     * @param  NotificationRequest  $notificationRequest
     * @return bool
     */
    protected function isNotificationExpired(NotificationRequest $notificationRequest): bool
    {
        // Check if expires_at is set and is in the past
        if ($notificationRequest->expires_at && $notificationRequest->expires_at->isPast()) {
            return true;
        }

        // Default expiry: 30 days after creation
        if ($notificationRequest->created_at && $notificationRequest->created_at->addDays(30)->isPast()) {
            return true;
        }

        return false;
    }

    // ==================== RATE LIMITING ====================

    /**
     * Check if request is rate limited
     *
     * @param  Request  $request
     * @return bool
     */
    protected function isRateLimited(Request $request): bool
    {
        $key = 'instant-estate-access:' . $request->ip();
        return RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS_PER_IP);
    }

    /**
     * Get rate limit retry after seconds
     *
     * @param  Request  $request
     * @return int
     */
    protected function getRateLimitRetryAfter(Request $request): int
    {
        $key = 'instant-estate-access:' . $request->ip();
        return RateLimiter::availableIn($key);
    }

    /**
     * Increment rate limit counter
     *
     * @param  Request  $request
     * @return void
     */
    protected function incrementRateLimit(Request $request): void
    {
        $key = 'instant-estate-access:' . $request->ip();
        RateLimiter::hit($key, self::RATE_LIMIT_DECAY);
    }

    /**
     * Increment failed attempts counter
     *
     * @param  Request  $request
     * @return void
     */
    protected function incrementFailedAttempts(Request $request): void
    {
        $key = 'instant-estate-failed:' . $request->ip();
        RateLimiter::hit($key, self::RATE_LIMIT_DECAY);

        $attempts = RateLimiter::attempts($key);

        if ($attempts >= 5) {
            Log::warning('Multiple failed instant estate access attempts', [
                'ip' => $request->ip(),
                'attempts' => $attempts,
                'user_agent' => $request->userAgent(),
            ]);
        }
    }

    /**
     * Reset failed attempts counter
     *
     * @param  Request  $request
     * @return void
     */
    protected function resetFailedAttempts(Request $request): void
    {
        RateLimiter::clear('instant-estate-failed:' . $request->ip());
    }

    // ==================== ACCESS GRANT ====================

    /**
     * Grant access and update notification metadata
     *
     * @param  Request  $request
     * @param  NotificationRequest  $notificationRequest
     * @param  string  $token
     * @return void
     */
    protected function grantAccess(
        Request $request,
        NotificationRequest $notificationRequest,
        string $token
    ): void {
        // Update metadata with access information
        $metadata = $notificationRequest->request_metadata ?? [];
        $accessCount = ($metadata['access_count'] ?? 0) + 1;
        
        $metadata['access_count'] = $accessCount;
        $metadata['last_accessed_at'] = now()->toIso8601String();
        $metadata['last_accessed_ip'] = $request->ip();
        $metadata['last_accessed_user_agent'] = $request->userAgent();
        
        // Store access history
        $accessHistory = $metadata['access_history'] ?? [];
        $accessHistory[] = [
                'accessed_at' => now()->toIso8601String(),
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 200),
            ];
        
        // Keep only last 10 accesses
        $accessHistory = array_slice($accessHistory, -10);
        $metadata['access_history'] = $accessHistory;
        
        $notificationRequest->update([
            'request_metadata' => $metadata,
        ]);

        $this->resetFailedAttempts($request);
        $this->logAccessAttempt($request, $token, 'granted', true);
        
        Log::info('Instant estate report accessed', [
            'notification_id' => $notificationRequest->id,
            'session_id' => $notificationRequest->session_id,
            'access_count' => $accessCount,
            'ip' => $request->ip(),
        ]);
    }

    // ==================== ACCESS DENIAL ====================

    /**
     * Deny access with appropriate response
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  string  $type
     * @return Response
     */
    protected function denyAccess(
        string $message,
        int $statusCode = 403,
        string $type = 'error'
    ): Response {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'type' => $type,
                'status_code' => $statusCode,
            ], $statusCode);
        }

        // Check if custom view exists
        if (View::exists('errors.instant-estate-access-denied')) {
            return response()->view('errors.instant-estate-access-denied', [
                'message' => $message,
                'status_code' => $statusCode,
                'type' => $type,
                'support_email' => config('mail.support.address', 'neofaraidadmin@gmail.com'),
                'support_phone' => config('app.support_phone', '+60 1-234-56-7890'),
            ], $statusCode);
        }

        // Fallback HTML response
        $fallbackHtml = $this->getFallbackDeniedHtml($message, $statusCode, $type);
        
        return response($fallbackHtml, $statusCode)->header('Content-Type', 'text/html');
    }

    /**
     * Generate fallback HTML for access denied
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  string  $type
     * @return string
     */
    protected function getFallbackDeniedHtml(string $message, int $statusCode, string $type = 'error'): string
    {
        $icon = $this->getIconForType($type);
        $color = $this->getColorForType($type);
        $title = $this->getTitleForType($type);
        
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $title . ' - Neo Faraid</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
            <style>
                * { font-family: "Poppins", sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 2rem;
                }
                .container {
                    max-width: 550px;
                    background: rgba(255,255,255,0.95);
                    backdrop-filter: blur(20px);
                    border-radius: 20px;
                    padding: 3rem 2rem;
                    text-align: center;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
                    border: 1px solid rgba(255,255,255,0.2);
                    animation: fadeIn 0.5s ease-out;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .icon {
                    width: 80px;
                    height: 80px;
                    margin: 0 auto 1.5rem;
                    background: ' . ($type === 'pending' ? '#fff3cd' : '#ffe0e0') . ';
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: ' . $color . ';
                }
                h1 { color: ' . $color . '; font-size: 1.8rem; margin-bottom: 0.5rem; }
                .status { color: #64748b; margin-bottom: 1.5rem; font-size: 0.9rem; }
                .message { 
                    background: #f8fafc; 
                    padding: 1.25rem; 
                    border-radius: 16px; 
                    margin: 1.5rem 0; 
                    border-left: 4px solid ' . $color . ';
                    text-align: left;
                }
                .message p { margin: 0; line-height: 1.6; color: #334155; }
                .help-section {
                    background: #f1f5f9;
                    border-radius: 16px;
                    padding: 1.25rem;
                    margin: 1.5rem 0;
                    text-align: left;
                }
                .help-section h4 {
                    font-size: 0.9rem;
                    font-weight: 700;
                    color: #1e293b;
                    margin-bottom: 0.75rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                .help-section .contact-item {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    margin: 0.5rem 0;
                }
                .btn-group {
                    display: flex;
                    gap: 1rem;
                    justify-content: center;
                    flex-wrap: wrap;
                    margin-top: 1rem;
                }
                .btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.75rem 1.5rem;
                    background: linear-gradient(135deg, #1a5fb4, #2d7ad6);
                    color: white;
                    text-decoration: none;
                    border-radius: 50px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }
                .btn-secondary {
                    background: #e2e8f0;
                    color: #1e293b;
                }
                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                }
                .footer {
                    margin-top: 1.5rem;
                    padding-top: 1rem;
                    border-top: 1px solid #e2e8f0;
                    font-size: 0.7rem;
                    color: #94a3b8;
                }
                @media (max-width: 640px) { 
                    .container { padding: 2rem 1.5rem; } 
                    h1 { font-size: 1.5rem; }
                    .btn-group { flex-direction: column; }
                    .btn { justify-content: center; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="icon">
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">' . $icon . '</svg>
                </div>
                <h1>' . $title . '</h1>
                <div class="status">Status Code: ' . $statusCode . '</div>
                <div class="message">
                    <p>' . htmlspecialchars($message) . '</p>
                </div>
                
                <div class="help-section">
                    <h4>
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636L9.172 14.828a4 4 0 005.656 5.656l9.192-9.192a4 4 0 00-5.656-5.656z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 15.5l-4 4"/>
                        </svg>
                        Need Assistance?
                    </h4>
                    <div class="contact-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>📧 Email: <strong>neofaraidadmin@gmail.com</strong></span>
                    </div>
                    <div class="contact-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>📞 Phone: <strong>+60 1-234-56-7890</strong></span>
                    </div>
                    <div class="contact-item">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>⏰ Business Hours: Monday - Friday, 9:00 AM - 5:00 PM</span>
                    </div>
                </div>
                
                <div class="btn-group">
                    <a href="' . url('/') . '" class="btn">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Go to Homepage
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back
                    </a>
                </div>
                
                <div class="footer">
                    <p>© ' . date('Y') . ' Neo Faraid - Islamic Inheritance Calculator. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Get icon SVG path based on error type
     *
     * @param  string  $type
     * @return string
     */
    protected function getIconForType(string $type): string
    {
        switch ($type) {
            case 'pending':
                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            case 'expired':
                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            case 'rejected':
                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            default:
                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>';
        }
    }

    /**
     * Get color based on error type
     *
     * @param  string  $type
     * @return string
     */
    protected function getColorForType(string $type): string
    {
        switch ($type) {
            case 'pending':
                return '#ffc107';
            case 'expired':
                return '#dc3545';
            case 'rejected':
                return '#dc3545';
            default:
                return '#dc3545';
        }
    }

    /**
     * Get title based on error type
     *
     * @param  string  $type
     * @return string
     */
    protected function getTitleForType(string $type): string
    {
        switch ($type) {
            case 'pending':
                return 'Pending Approval';
            case 'expired':
                return 'Link Expired';
            case 'rejected':
                return 'Request Rejected';
            default:
                return 'Access Denied';
        }
    }

    // ==================== AUDIT LOGGING ====================

    /**
     * Log access attempt for audit purposes
     *
     * @param  Request  $request
     * @param  string|null  $token
     * @param  string  $result
     * @param  bool  $granted
     * @return void
     */
    protected function logAccessAttempt(
        Request $request,
        ?string $token,
        string $result,
        bool $granted
    ): void {
        try {
            Log::info('Instant estate access attempt', [
                'token_prefix' => $token ? substr($token, 0, 10) . '...' : null,
                'result' => $result,
                'granted' => $granted,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log access attempt', ['error' => $e->getMessage()]);
        }
    }
}