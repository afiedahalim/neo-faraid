<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Session::flash('error', 'Please login to access this page.');
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user is active
        if (!$user->isActive()) {
            Auth::logout();
            Session::flash('error', 'Your account has been deactivated. Please contact administrator.');
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (!$user->isAdmin()) {
            Session::flash('error', 'You do not have permission to access the admin panel.');
            return redirect()->route('home');
        }

        return $next($request);
    }
}