<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        $telegramAuth = $request->get('telegram_auth');
        $telegramToken = $telegramAuth ?: Str::random(32);

        if ($telegramAuth) {
            session(['telegram_auth_token' => $telegramAuth]);
        }

        return view('auth.login', [
            'telegram_token' => $telegramToken,
            'telegram_auth' => $telegramAuth
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if ($user->status !== 'active') {
            return back()->withErrors([
                'email' => 'Your account is not active. Please contact support.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // --- MANDATORY EMAIL VERIFICATION CHECK ---
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Please verify your email address before logging in.');
            }
            // --- END CHECK ---

            // Telegram linking (if applicable)
            if ($request->has('telegram_auth') || session('telegram_auth_token')) {
                $token = $request->telegram_auth ?: session('telegram_auth_token');
                $user->update([
                    'telegram_session' => $token,
                    'telegram_session_expires_at' => now()->addMinutes(30)
                ]);
                session()->forget('telegram_auth_token');

                return redirect()->intended($user->isAdmin() ? '/dashboard' : '/')
                    ->with('telegram_linking', true)
                    ->with('telegram_token', $token)
                    ->with('telegram_bot_url', "https://t.me/" . config('services.telegram.bot_username') . "?start=" . $token);
            }

            // Regular user → homepage, admin → dashboard
            $redirectTo = $user->isAdmin() ? '/dashboard' : '/';
            return redirect()->intended($redirectTo);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function telegramWebCallback(Request $request)
    {
        return redirect('/dashboard')->with('success', 'Telegram account linked successfully!');
    }

    public function telegramLinkSuccess(Request $request)
    {
        return view('telegram.link-success', [
            'message' => 'Your Telegram account has been successfully linked!'
        ]);
    }
}