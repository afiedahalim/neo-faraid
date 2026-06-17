<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'nric'            => 'required|string|max:20|unique:users',
            'date_of_birth'   => 'required|date',
            'gender'          => 'required|in:male,female',
            'email'           => 'required|email|unique:users',
            'contact_phone'   => 'required|regex:/^01[0-9]-[0-9]{7,8}$/',
            'address'         => 'required|string',
            'role'            => 'required|in:user,admin',
            'status'          => 'required|in:active,inactive',
            'password'        => 'required|string|min:8|confirmed',
        ], [
            'contact_phone.regex' => 'Phone number must be in the format: 01X-XXXXXXX (e.g., 012-3456789)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phone = $this->formatPhone($request->contact_phone);

        $user = User::create([
            'name'           => $request->name,
            'nric'           => $request->nric,
            'date_of_birth'  => $request->date_of_birth,
            'gender'         => $request->gender,
            'email'          => $request->email,
            'contact_phone'  => $phone,
            'address'        => $request->address,
            'role'           => $request->role,
            'status'         => $request->status,
            'is_active'      => $request->status === 'active',
            'password'       => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'nric'            => 'required|string|max:20|unique:users,nric,' . $user->id,
            'date_of_birth'   => 'required|date',
            'gender'          => 'required|in:male,female',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'contact_phone'   => 'required|regex:/^01[0-9]-[0-9]{7,8}$/',
            'address'         => 'required|string',
            'role'            => 'required|in:user,admin',
            'status'          => 'required|in:active,inactive',
        ], [
            'contact_phone.regex' => 'Phone number must be in the format: 01X-XXXXXXX (e.g., 012-3456789)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phone = $this->formatPhone($request->contact_phone);

        $user->update([
            'name'           => $request->name,
            'nric'           => $request->nric,
            'date_of_birth'  => $request->date_of_birth,
            'gender'         => $request->gender,
            'email'          => $request->email,
            'contact_phone'  => $phone,
            'address'        => $request->address,
            'role'           => $request->role,
            'status'         => $request->status,
            'is_active'      => $request->status === 'active',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->is_active = $user->status === 'active';
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User status updated.');
    }

    /**
     * Reset user password (send reset link or set random password).
     */
    public function resetPassword(User $user)
    {
        $newPassword = \Str::random(10);
        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Password reset successfully. New password: {$newPassword}");
    }

    /**
     * Export users list (CSV/Excel).
     */
    public function export()
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Export feature not implemented yet.');
    }

    // ----- Helper methods -----

    /**
     * Format phone number with hyphen (e.g., 0123456789 -> 012-3456789)
     */
    protected function formatPhone($phone): string
    {
        // Remove any non-digit characters
        $clean = preg_replace('/[^0-9]/', '', $phone);
        // If it starts with 01 and has 10 or 11 digits, insert hyphen after the first 3 digits
        if (strlen($clean) >= 10 && strlen($clean) <= 11 && substr($clean, 0, 2) === '01') {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $phone; // fallback
    }
}