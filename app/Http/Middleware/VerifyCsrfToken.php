<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Webhook endpoints (if any)
        // 'stripe/webhook',
        // 'telegram/webhook',
        // 'whatsapp/webhook',
        
        // API endpoints that don't need CSRF (if using stateless auth)
        // 'api/v1/*',
        
        // Specific endpoints for external services
        // 'payment/callback',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        // Skip CSRF check for specific methods or routes
        if ($this->shouldPassThrough($request)) {
            return $next($request);
        }

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return $this->handleAjaxRequest($request, $next);
        }

        return parent::handle($request, $next);
    }

    /**
     * Handle AJAX requests with custom error response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected function handleAjaxRequest($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            return response()->json([
                'error' => 'Session expired',
                'message' => 'Your session has expired. Please refresh the page.',
                'requires_refresh' => true,
            ], 419); // 419 is the status code Laravel uses for CSRF token mismatch
        }
    }

    /**
     * Check if the request should pass through without CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        // Check if the request URI is in the except array
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        // Allow HEAD and OPTIONS methods
        if (in_array($request->method(), ['HEAD', 'OPTIONS'])) {
            return true;
        }

        // Allow requests from trusted domains (for API)
        if ($this->isTrustedDomain($request)) {
            return true;
        }

        return false;
    }

    /**
     * Check if request comes from a trusted domain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isTrustedDomain($request)
    {
        $trustedDomains = [
            'localhost',
            '127.0.0.1',
            'neo-faraid.test',
            // Add your production domain
            // 'neofaraid.com',
        ];

        $host = $request->getHost();
        $referer = $request->headers->get('referer');
        
        // Check host
        if (in_array($host, $trustedDomains)) {
            return true;
        }
        
        // Check referer if present
        if ($referer) {
            $refererHost = parse_url($referer, PHP_URL_HOST);
            if (in_array($refererHost, $trustedDomains)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add the CSRF token to the response cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response|\Illuminate\Http\JsonResponse  $response
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function addCookieToResponse($request, $response)
    {
        $config = config('session');
        
        $response->headers->setCookie(
            new \Symfony\Component\HttpFoundation\Cookie(
                'XSRF-TOKEN',
                $request->session()->token(),
                time() + 60 * $config['lifetime'],
                $config['path'],
                $config['domain'],
                $config['secure'],
                false, // http_only - false to allow JavaScript access
                false,
                $config['same_site'] ?? 'lax'
            )
        );
        
        return $response;
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);

        // Check for API token in header (for SPA or mobile apps)
        if ($request->header('X-API-Token') && 
            $request->header('X-API-Token') === config('app.api_token')) {
            return true;
        }

        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }

    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (!$token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header, static::serialized());
        }

        return $token;
    }
}