<?php
// debug-password.php - Debug password reset issues

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== PASSWORD DEBUG UTILITY ===\n\n";

// Get the user you're testing with
$email = 'your-email@example.com'; // CHANGE THIS to your actual email
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found: {$email}\n";
    exit;
}

echo "User found:\n";
echo "  ID: {$user->id}\n";
echo "  Name: {$user->name}\n";
echo "  Email: {$user->email}\n";
echo "  Password hash: {$user->password}\n";
echo "  Hash length: " . strlen($user->password) . " chars\n";

// Check if it's bcrypt
$isBcrypt = (strlen($user->password) === 60 && 
            (substr($user->password, 0, 4) === '$2y$' || 
             substr($user->password, 0, 4) === '$2a$' ||
             substr($user->password, 0, 4) === '$2b$'));

echo "  Is Bcrypt: " . ($isBcrypt ? "✅ YES" : "❌ NO") . "\n";

if ($isBcrypt) {
    echo "  Bcrypt info:\n";
    echo "    Algorithm: " . substr($user->password, 1, 3) . "\n";
    echo "    Cost: " . substr($user->password, 4, 2) . "\n";
    echo "    Salt: " . substr($user->password, 7, 22) . "...\n";
}

// Test password verification
echo "\n=== PASSWORD VERIFICATION TEST ===\n";
echo "Enter the password you're trying to login with: ";
$password = trim(fgets(STDIN));

if (empty($password)) {
    $password = 'password123'; // default test password
    echo "Using default password: {$password}\n";
}

$match = Hash::check($password, $user->password);
echo "\nHash::check result: " . ($match ? "✅ MATCHES" : "❌ DOES NOT MATCH") . "\n";

// Manual verification for debugging
echo "\n=== MANUAL VERIFICATION ===\n";
echo "Password you entered: '{$password}'\n";
echo "Stored hash: {$user->password}\n";

// Try direct PHP password_verify
if (function_exists('password_verify')) {
    $phpVerify = password_verify($password, $user->password);
    echo "PHP password_verify(): " . ($phpVerify ? "✅ TRUE" : "❌ FALSE") . "\n";
}

// Check password info
if (function_exists('password_get_info')) {
    $info = password_get_info($user->password);
    echo "Password info:\n";
    print_r($info);
}

echo "\n=== QUICK FIX ===\n";
echo "Do you want to reset this user's password to 'password123'? (yes/no): ";
$response = trim(fgets(STDIN));

if (strtolower($response) === 'yes') {
    $newPassword = 'password123';
    $user->password = Hash::make($newPassword);
    $user->save();
    
    echo "\n✅ Password reset to: {$newPassword}\n";
    echo "New hash: {$user->password}\n";
    echo "Hash length: " . strlen($user->password) . " chars\n";
    
    // Verify it works
    $verify = Hash::check($newPassword, $user->password);
    echo "Verification test: " . ($verify ? "✅ SUCCESS" : "❌ FAILED") . "\n";
}

echo "\nDone!\n";