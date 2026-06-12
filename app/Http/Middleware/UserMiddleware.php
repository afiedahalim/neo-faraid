<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // LINE 22: Make sure you're not trying to access non-existent relationships here
        // Example of what might cause error on line 22:
        // $estate = Auth::user()->estates->first();
        // $assets = $estate->assets; // ERROR: assets relationship doesn't exist
        
        // Instead, if you need to check something about the user's estates:
        $user = Auth::user();
        
        // This is safe (accessing field, not relationship):
        // $estateCount = $user->estates()->count();
        
        // This would cause error (trying to access non-existent relationship):
        // $assets = $user->estates->first()->assets;
        
        return $next($request);
    }
}