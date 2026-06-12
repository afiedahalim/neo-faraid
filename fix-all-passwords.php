<?php
// fix-all-passwords.php - Emergency password fix for ALL users

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== EMERGENCY PASSWORD FIX FOR ALL USERS ===\n\n";

// Get all users
$users = User::all();
$totalUsers = $users->count();
$fixedCount = 0;

echo "Found {$totalUsers} users\n";
echo str_repeat("=", 50) . "\n\n";

foreach ($users as $user) {
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Current Hash: " . substr($user->password, 0, 30) . "...\n";
    echo "Hash Length: " . strlen($user->password) . "\n";
    
    // Generate new password
    $newPassword = 'password123'; // Default password for all users
    
    // Create new hash
    $newHash = Hash::make($newPassword);
    
    // Update using direct DB query (bypasses all model events)
    DB::table('users')
        ->where('id', $user->id)
        ->update([
            'password' => $newHash,
            'updated_at' => now()
        ]);
    
    // Verify
    $user->refresh();
    $verification = Hash::check($newPassword, $user->password);
    
    echo "New Hash: " . substr($user->password, 0, 30) . "...\n";
    echo "Verification: " . ($verification ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    echo "New Password: {$newPassword}\n";
    echo str_repeat("-", 50) . "\n";
    
    if ($verification) {
        $fixedCount++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total Users: {$totalUsers}\n";
echo "Successfully Fixed: {$fixedCount}\n";
echo "Default Password: password123\n";
echo "\nAll users can now login with: password123\n";
echo "Please ask users to change their password after login.\n";