<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        try {
            $user->sendEmailVerificationNotification();
            Log::info('Verification email resent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification email. Please try again.');
        }

        return back()->with('status', 'verification-link-sent');
    }
}