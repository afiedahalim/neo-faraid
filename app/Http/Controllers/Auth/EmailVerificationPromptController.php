<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): View
    {
        // If email is already verified, redirect to dashboard
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/dashboard');
        }

        // Show the verification notice page
        return view('auth.verify-email');
    }
}