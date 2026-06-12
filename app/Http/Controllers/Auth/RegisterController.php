<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nric' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'terms' => 'required|accepted',
        ], [
            'terms.required' => 'You must accept the terms and conditions.',
            'terms.accepted' => 'You must accept the terms and conditions.',
            'nric.required' => 'NRIC/Passport number is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'gender.required' => 'Please select your gender.',
            'contact_phone.required' => 'Contact phone number is required.',
            'address.required' => 'Residential address is required.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$this->validateNRIC($request->nric)) {
            return redirect()->back()
                ->withErrors(['nric' => 'Invalid NRIC format. Please use format: 000000-00-0000 or 12 digits'])
                ->withInput();
        }

        if (!$this->validatePhone($request->contact_phone)) {
            return redirect()->back()
                ->withErrors(['contact_phone' => 'Invalid phone format. Please use format: 012-3456789 or 0123456789 (10-11 digits starting with 01)'])
                ->withInput();
        }

        try {
            $formattedNric = $this->formatNRIC($request->nric);
            $formattedPhone = $this->formatPhone($request->contact_phone);

            $user = User::create([
                'name' => $request->name,
                'nric' => $formattedNric,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'contact_phone' => $formattedPhone,
                'address' => $request->address,
                'role' => 'user',
                'status' => 'active',
                'is_active' => true,
                'email_verified_at' => null,
            ]);

            Log::info('New user registered: ' . $user->email);

            try {
                $user->sendEmailVerificationNotification();
                Log::info('Verification email sent to: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            event(new Registered($user));

            return redirect()->route('login')
                ->with('success', 'Registration successful! Please check your email to verify your account before logging in.');

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again later.')
                ->withInput();
        }
    }

    protected function validateNRIC($nric): bool
    {
        if (empty($nric)) return false;
        $clean = preg_replace('/[-\s]/', '', $nric);
        return preg_match('/^\d{12}$/', $clean);
    }

    protected function formatNRIC($nric): string
    {
        if (empty($nric)) return '';
        $clean = preg_replace('/[^0-9]/', '', $nric);
        if (strlen($clean) === 12) {
            return substr($clean, 0, 6) . '-' . substr($clean, 6, 2) . '-' . substr($clean, 8, 4);
        }
        return $nric;
    }

    protected function validatePhone($phone): bool
    {
        if (empty($phone)) return false;
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (preg_match('/^01[0-9]{8,9}$/', $clean)) {
            $prefix = substr($clean, 0, 3);
            $validPrefixes = ['010', '011', '012', '013', '014', '015', '016', '017', '018', '019'];
            return in_array($prefix, $validPrefixes);
        }
        return false;
    }

    protected function formatPhone($phone): string
    {
        if (empty($phone)) return '';
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (preg_match('/^01[0-9]{8,9}$/', $clean)) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $phone;
    }
}