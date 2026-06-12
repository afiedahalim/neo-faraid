<?php

namespace App\Http\Middleware;

use App\Models\BeneficiaryAccessLink;
use App\Models\NotificationRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class VerifyBeneficiaryAccess
{
    /**
     * Maximum access attempts allowed per notification
     */
    protected const MAX_ACCESS_COUNT = 5;

    /**
     * Maximum access attempts per IP per hour
     */
    protected const MAX_ATTEMPTS_PER_IP = 10;

    /**
     * Rate limit decay in seconds (1 hour)
     */
    protected const RATE_LIMIT_DECAY = 3600;

    /**
     * Suspicious activity thresholds
     */
    protected const SUSPICIOUS_THRESHOLD = 3;
    protected const BLOCK_THRESHOLD = 5;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Step 1: Extract and validate token
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

        // Step 3: Check for INSTANT-ESTATE notification request (sent emails)
        $notificationRequest = $this->findValidNotificationRequest($token);
        
        if ($notificationRequest) {
            // This is an instant-estate notification token
            $this->grantNotificationRequestAccess($request, $notificationRequest, $token);
            
            $request->merge([
                'verified_notification_request' => $notificationRequest,
                'is_instant_estate' => true,
                'access_granted_at' => now(),
            ]);
            return $next($request);
        }
        
        // Step 4: Check for pending notification request (waiting for admin approval)
        $pendingRequest = $this->findPendingNotificationRequest($token);
        
        if ($pendingRequest) {
            $this->incrementFailedAttempts($request);
            $this->logAccessAttempt($request, $token, 'pending_approval', false);
            return $this->denyAccess(
                'Your report request is pending admin approval. You will receive an email once approved.',
                403,
                'pending'
            );
        }
        
        // Step 5: Check for ESTATE-SETUP beneficiary access link
        $accessLink = $this->findValidAccessLink($token);
        
        if ($accessLink && $this->isAccessLinkValid($accessLink)) {
            $this->grantAccessLinkAccess($request, $accessLink, $token);
            
            $request->merge([
                'verified_access_link' => $accessLink,
                'is_estate_setup' => true,
                'access_granted_at' => now(),
            ]);
            return $next($request);
        }
        
        // Step 6: Check for expired access link (with better error message)
        $expiredLink = $this->findExpiredAccessLink($token);
        if ($expiredLink) {
            $this->incrementFailedAttempts($request);
            $this->logAccessAttempt($request, $token, 'expired', false, null, $expiredLink);
            return $this->denyAccess(
                'This access link has expired on ' . 
                ($expiredLink->expires_at ? $expiredLink->expires_at->format('d F Y, h:i A') : 'unknown date') . 
                '. Please contact the estate administrator for a new link.',
                410,
                'expired'
            );
        }
        
        // Step 7: No valid token found
        $this->incrementFailedAttempts($request);
        $this->logAccessAttempt($request, $token, 'invalid_token', false);
        return $this->denyAccess(
            'Invalid or expired access link. Please contact the estate administrator.',
            403,
            'error'
        );
    }

    // ==================== TOKEN EXTRACTION ====================

    /**
     * Extract token from request (route param, query param, or auth header)
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

        // Check Authorization header
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            if (strlen($token) >= 32) {
                return $token;
            }
        }

        return null;
    }

    // ==================== INSTANT-ESTATE NOTIFICATION VALIDATION ====================

    /**
     * Find valid notification request (sent or approved)
     */
    protected function findValidNotificationRequest(string $token): ?NotificationRequest
    {
        if (!Schema::hasTable('notification_requests')) {
            return null;
        }

        // Find sent notification requests
        $notificationRequest = NotificationRequest::where('access_token', $token)
            ->whereIn('status', [NotificationRequest::STATUS_SENT, NotificationRequest::STATUS_APPROVED])
            ->first();

        if (!$notificationRequest) {
            // Try with hashed token
            $hashedToken = hash('sha256', $token);
            $notificationRequest = NotificationRequest::where('access_token', $hashedToken)
                ->whereIn('status', [NotificationRequest::STATUS_SENT, NotificationRequest::STATUS_APPROVED])
                ->first();
        }

        return $notificationRequest;
    }

    /**
     * Find pending notification request (waiting for admin approval)
     */
    protected function findPendingNotificationRequest(string $token): ?NotificationRequest
    {
        if (!Schema::hasTable('notification_requests')) {
            return null;
        }

        $pendingRequest = NotificationRequest::where('access_token', $token)
            ->where('status', NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL)
            ->first();

        if (!$pendingRequest) {
            $hashedToken = hash('sha256', $token);
            $pendingRequest = NotificationRequest::where('access_token', $hashedToken)
                ->where('status', NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL)
                ->first();
        }

        return $pendingRequest;
    }

    /**
     * Grant access for notification request
     */
    protected function grantNotificationRequestAccess(
        Request $request,
        NotificationRequest $notificationRequest,
        string $token
    ): void {
        // Update access count
        $metadata = $notificationRequest->request_metadata ?? [];
        $accessCount = ($metadata['access_count'] ?? 0) + 1;
        $metadata['access_count'] = $accessCount;
        $metadata['last_accessed_at'] = now()->toIso8601String();
        $metadata['last_accessed_ip'] = $request->ip();
        $metadata['last_accessed_user_agent'] = $request->userAgent();
        
        $notificationRequest->update([
            'request_metadata' => $metadata,
        ]);

        $this->resetFailedAttempts($request);
        $this->logAccessAttempt($request, $token, 'granted', true);
        
        Log::info('Instant estate report accessed via token', [
            'notification_id' => $notificationRequest->id,
            'session_id' => $notificationRequest->session_id,
            'ip' => $request->ip(),
        ]);
    }

    // ==================== ESTATE-SETUP ACCESS LINK VALIDATION ====================

    /**
     * Find valid access link
     */
    protected function findValidAccessLink(string $token): ?BeneficiaryAccessLink
    {
        if (!Schema::hasTable('beneficiary_access_links')) {
            return null;
        }

        $accessLink = BeneficiaryAccessLink::where('access_token', $token)->first();
        
        if (!$accessLink) {
            $hashedToken = hash('sha256', $token);
            $accessLink = BeneficiaryAccessLink::where('access_token', $hashedToken)->first();
        }

        return $accessLink;
    }

    /**
     * Find expired access link
     */
    protected function findExpiredAccessLink(string $token): ?BeneficiaryAccessLink
    {
        if (!Schema::hasTable('beneficiary_access_links')) {
            return null;
        }

        $accessLink = BeneficiaryAccessLink::where('access_token', $token)
            ->where(function ($query) {
                $query->where('expires_at', '<=', now())
                    ->orWhere('status', 'expired');
            })
            ->first();

        if (!$accessLink) {
            $hashedToken = hash('sha256', $token);
            $accessLink = BeneficiaryAccessLink::where('access_token', $hashedToken)
                ->where(function ($query) {
                    $query->where('expires_at', '<=', now())
                        ->orWhere('status', 'expired');
                })
                ->first();
        }

        return $accessLink;
    }

    /**
     * Check if access link is valid
     */
    protected function isAccessLinkValid(BeneficiaryAccessLink $accessLink): bool
    {
        // Check if link is active and not expired
        $isActive = $accessLink->status === 'active' && $accessLink->is_active;
        $isNotExpired = !$accessLink->expires_at || $accessLink->expires_at->isFuture();
        
        return $isActive && $isNotExpired;
    }

    /**
     * Grant access for access link
     */
    protected function grantAccessLinkAccess(
        Request $request,
        BeneficiaryAccessLink $accessLink,
        string $token
    ): void {
        // Record access
        $accessLink->increment('access_count');
        $accessLink->update([
            'last_accessed_at' => now(),
            'last_accessed_ip' => $request->ip(),
            'last_accessed_user_agent' => $request->userAgent(),
        ]);

        $this->resetFailedAttempts($request);
        $this->logAccessAttempt($request, $token, 'granted', true, $accessLink);
        
        Log::info('Beneficiary access link accessed', [
            'link_id' => $accessLink->id,
            'beneficiary_type' => $accessLink->beneficiary_type,
            'beneficiary_email' => $accessLink->beneficiary_email,
            'ip' => $request->ip(),
        ]);
    }

    // ==================== RATE LIMITING ====================

    /**
     * Check if request is rate limited
     */
    protected function isRateLimited(Request $request): bool
    {
        $key = 'beneficiary-access:' . $request->ip();
        return RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS_PER_IP);
    }

    /**
     * Get rate limit retry after seconds
     */
    protected function getRateLimitRetryAfter(Request $request): int
    {
        $key = 'beneficiary-access:' . $request->ip();
        return RateLimiter::availableIn($key);
    }

    /**
     * Increment rate limit counter
     */
    protected function incrementRateLimit(Request $request): void
    {
        $key = 'beneficiary-access:' . $request->ip();
        RateLimiter::hit($key, self::RATE_LIMIT_DECAY);
    }

    /**
     * Increment failed attempts
     */
    protected function incrementFailedAttempts(Request $request): void
    {
        $key = 'beneficiary-failed:' . $request->ip();
        RateLimiter::hit($key, self::RATE_LIMIT_DECAY);

        $attempts = RateLimiter::attempts($key);

        if ($attempts >= self::SUSPICIOUS_THRESHOLD) {
            Log::warning('Multiple failed access attempts detected', [
                'ip' => $request->ip(),
                'attempts' => $attempts,
                'user_agent' => $request->userAgent(),
            ]);
        }

        if ($attempts >= self::BLOCK_THRESHOLD) {
            $this->blockIp($request->ip());
        }
    }

    /**
     * Block IP address
     */
    protected function blockIp(string $ip): void
    {
        $blockedKey = 'blocked-ip:' . $ip;
        cache()->put($blockedKey, true, 86400);

        Log::alert('IP blocked for suspicious activity', [
            'ip' => $ip,
            'blocked_until' => now()->addHours(24)->toISOString(),
        ]);
    }

    /**
     * Reset failed attempts
     */
    protected function resetFailedAttempts(Request $request): void
    {
        RateLimiter::clear('beneficiary-failed:' . $request->ip());
    }

    // ==================== ACCESS DENIAL ====================

    /**
     * Deny access with appropriate response
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
        if (View::exists('errors.access-denied')) {
            return response()->view('errors.access-denied', [
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
                .help-section p {
                    font-size: 0.85rem;
                    color: #475569;
                    margin: 0.5rem 0;
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
     * Get icon SVG for access denied type
     */
    protected function getIconForType(string $type): string
    {
        switch ($type) {
            case 'pending':
                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            case 'expired':
                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            default:
                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>';
        }
    }

    /**
     * Get color for access denied type
     */
    protected function getColorForType(string $type): string
    {
        switch ($type) {
            case 'pending':
                return '#ffc107';
            case 'expired':
                return '#dc3545';
            default:
                return '#dc3545';
        }
    }

    /**
     * Get title for access denied type
     */
    protected function getTitleForType(string $type): string
    {
        switch ($type) {
            case 'pending':
                return 'Pending Approval';
            case 'expired':
                return 'Link Expired';
            default:
                return 'Access Denied';
        }
    }

    // ==================== AUDIT LOGGING ====================

    /**
     * Log access attempt for audit purposes
     */
    protected function logAccessAttempt(
        Request $request,
        ?string $token,
        string $result,
        bool $granted,
        ?BeneficiaryAccessLink $accessLink = null,
        ?BeneficiaryAccessLink $expiredLink = null
    ): void {
        try {
            $linkForLogging = $accessLink ?? $expiredLink;
            
            // Log to laravel log
            Log::info('Beneficiary access attempt', [
                'token_prefix' => $token ? substr($token, 0, 10) . '...' : null,
                'result' => $result,
                'granted' => $granted,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'access_link_id' => $linkForLogging?->id,
                'beneficiary_email' => $linkForLogging?->beneficiary_email,
            ]);
            
            // You can also store in database if AuditLog table exists
            if (class_exists(\App\Models\AuditLog::class) && Schema::hasTable('audit_logs')) {
                \App\Models\AuditLog::create([
                    'action' => $granted ? 'beneficiary_access_granted' : 'beneficiary_access_denied',
                    'performed_by' => null,
                    'performed_by_name' => $linkForLogging?->beneficiary_name ?? 'Unknown',
                    'performed_by_role' => $linkForLogging?->beneficiary_type ?? 'beneficiary',
                    'details' => [
                        'token_prefix' => $token ? substr($token, 0, 8) . '...' : null,
                        'result' => $result,
                        'ip_address' => $request->ip(),
                        'user_agent' => substr($request->userAgent() ?? '', 0, 200),
                        'access_count' => $accessLink?->access_count ?? 0,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to log access attempt', ['error' => $e->getMessage()]);
        }
    }
}