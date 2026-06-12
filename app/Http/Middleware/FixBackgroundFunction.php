<?php

namespace App\Http\Middleware;

use Closure;

class FixBackgroundFunction
{
    public function handle($request, Closure $next)
    {
        // Define the function if it doesn't exist
        if (!function_exists('generateAnimatedBackground')) {
            function generateAnimatedBackground() {
                return ''; // Return empty
            }
        }
        
        return $next($request);
    }
}