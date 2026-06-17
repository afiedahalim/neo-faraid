<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Only validate the fields that are actually updated
        $validator = Validator::make($request->all(), [
            'contact_phone' => 'required|regex:/^01[0-9]-[0-9]{7,8}$/',
            'address'       => 'required|string',
        ], [
            'contact_phone.regex' => 'Phone number must be in the format: 01X-XXXXXXX (e.g., 012-3456789)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phone = $this->formatPhone($request->contact_phone);

        $user->update([
            'contact_phone' => $phone,
            'address'       => $request->address,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Format phone number with hyphen (e.g., 0123456789 -> 012-3456789)
     */
    protected function formatPhone($phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($clean) >= 10 && strlen($clean) <= 11 && substr($clean, 0, 2) === '01') {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $phone;
    }
}