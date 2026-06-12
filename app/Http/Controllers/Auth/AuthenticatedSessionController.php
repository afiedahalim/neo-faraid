<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        
        // Check if email is verified
        if (!$request->user()->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Please verify your email address before logging in. Check your email for the verification link.');
        }
        
        $request->session()->regenerate();
        
        // Regular users go to home, admins go to dashboard
        $redirectTo = $request->user()->isAdmin() ? '/dashboard' : '/';
        
        return redirect()->intended($redirectTo);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}