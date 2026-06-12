<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        // Get token and email from request
        $token = $request->route('token');
        $email = $request->input('email', $request->query('email'));
        
        if (!$token || !$email) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid password reset link.');
        }
        
        // Check if token exists
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();
        
        if (!$tokenData) {
            return redirect()->route('password.request')
                ->with('error', 'This password reset token has expired or is invalid.');
        }
        
        // Verify the token
        if (!hash_equals($tokenData->token, hash('sha256', $token))) {
            return redirect()->route('password.request')
                ->with('error', 'This password reset token is invalid.');
        }
        
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Check if token exists
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token has expired.']);
        }

        // Verify the token
        if (!hash_equals($tokenData->token, hash('sha256', $request->token))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset token is invalid.']);
        }

        // Find the user
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(60);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        event(new PasswordReset($user));

        // Log the user in
        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Your password has been reset successfully!');
    }
}