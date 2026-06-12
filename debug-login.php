<?php
// debug-login.php - Debug login process

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== LOGIN DEBUG ===\n\n";

// Test with a specific user
$email = 'admin@neofaraid.com';
$password = 'password123';

echo "Testing: {$email} / {$password}\n";

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found\n";
    exit;
}

echo "✅ User found:\n";
echo "  ID: {$user->id}\n";
echo "  Name: {$user->name}\n";
echo "  Email: {$user->email}\n";
echo "  Password Hash: " . $user->password . "\n";
echo "  Hash Length: " . strlen($user->password) . "\n";

// Test 1: Direct hash check
$hashCheck = Hash::check($password, $user->password);
echo "\n=== Hash Check ===\n";
echo "Hash::check(): " . ($hashCheck ? "✅ TRUE" : "❌ FALSE") . "\n";

// Test 2: PHP password_verify
if (function_exists('password_verify')) {
    $phpVerify = password_verify($password, $user->password);
    echo "password_verify(): " . ($phpVerify ? "✅ TRUE" : "❌ FALSE") . "\n";
}

// Test 3: Manual bcrypt verification
echo "\n=== Bcrypt Analysis ===\n";
$hash = $user->password;
echo "Prefix: " . substr($hash, 0, 7) . "\n";
echo "Cost: " . substr($hash, 4, 2) . "\n";
echo "Salt: " . substr($hash, 7, 22) . "\n";

// Test 4: Auth attempt
echo "\n=== Auth Attempt ===\n";
$credentials = [
    'email' => $email,
    'password' => $password
];

$authAttempt = \Illuminate\Support\Facades\Auth::attempt($credentials);
echo "Auth::attempt(): " . ($authAttempt ? "✅ SUCCESS" : "❌ FAILED") . "\n";

if (!$authAttempt) {
    echo "\n=== Debugging Auth Failure ===\n";
    
    // Check guard configuration
    $guard = \Illuminate\Support\Facades\Auth::guard();
    $provider = $guard->getProvider();
    
    echo "Guard: " . get_class($guard) . "\n";
    echo "Provider: " . get_class($provider) . "\n";
    
    // Manual validation
    $validated = $guard->validate($credentials);
    echo "Guard validate: " . ($validated ? "✅ TRUE" : "❌ FALSE") . "\n";
    
    // Check if user is active
    echo "User is_active: " . ($user->is_active ? "✅ YES" : "❌ NO") . "\n";
    echo "User status: {$user->status}\n";
    echo "Email verified: " . ($user->email_verified_at ? "✅ YES" : "❌ NO") . "\n";
}