<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SecurityController extends Controller
{
    public function settings()
    {
        $user = Auth::user();
        $sessions = $this->getSessions();
        
        return view('security.settings', compact('user', 'sessions'));
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()
            ->with('success', 'Password changed successfully.');
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->save();

        $status = $user->two_factor_enabled ? 'enabled' : 'disabled';
        
        return redirect()->back()
            ->with('success', "Two-factor authentication {$status}.");
    }

    public function sessions()
    {
        $sessions = $this->getSessions();
        
        return view('security.sessions', compact('sessions'));
    }

    public function destroySession(Request $request, $sessionId)
    {
        $sessions = $this->getSessions();
        
        if (isset($sessions[$sessionId])) {
            // Logic to destroy session (you'd need to implement this based on your session driver)
            return redirect()->back()
                ->with('success', 'Session terminated.');
        }
        
        return redirect()->back()
            ->with('error', 'Session not found.');
    }

    private function getSessions()
    {
        // This is a simplified version. You'd need to implement based on your session driver
        return [
            'current' => [
                'id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'last_activity' => now(),
            ]
        ];
    }
}