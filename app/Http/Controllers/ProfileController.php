<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     * Only contact_phone and address are editable
     * Personal identification fields are read-only
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile information
     * Only contact_phone and address are editable
     * Personal identification fields (name, nric, date_of_birth, gender, email) cannot be changed
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // Validate phone format
        if (!$this->validatePhone($request->contact_phone)) {
            return redirect()->back()
                ->withErrors(['contact_phone' => 'Invalid phone format. Please use format: 012-3456789'])
                ->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Format phone number
            $formattedPhone = $this->formatPhone($request->contact_phone);

            // Update only the editable fields
            $user->update([
                'contact_phone' => $formattedPhone,
                'address' => $request->address,
            ]);

            return redirect()->route('profile.edit')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update profile. Please try again.')
                ->withInput();
        }
    }

    /**
     * Validate phone number format
     * Format: 012-3456789 or 0123456789 (Malaysian mobile numbers)
     */
    protected function validatePhone($phone): bool
    {
        if (empty($phone)) return false;
        $clean = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^01[0-9]{8,9}$/', $clean);
    }

    /**
     * Format phone number with hyphen
     * Example: 0123456789 -> 012-3456789
     */
    protected function formatPhone($phone): string
    {
        if (empty($phone)) return '';
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($clean) >= 10 && strlen($clean) <= 11 && substr($clean, 0, 2) === '01') {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $phone;
    }
}