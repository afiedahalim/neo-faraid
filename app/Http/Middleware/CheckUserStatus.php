<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // If user is not active, log them out
            if (!$user->isActive()) {
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();
                
                Session::flash('error', 'Your account has been deactivated.');
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}