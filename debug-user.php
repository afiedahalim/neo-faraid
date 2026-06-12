<?php
// debug-user.php - Debug user password issue

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== USER PASSWORD DEBUG ===\n\n";

// Replace with your email
$email = 'YOUR-EMAIL@EXAMPLE.COM'; // CHANGE THIS

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found: {$email}\n";
    exit;
}

echo "✅ User found:\n";
echo "   ID: {$user->id}\n";
echo "   Name: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   Password Hash: " . $user->password . "\n";
echo "   Hash Length: " . strlen($user->password) . " characters\n";

// Check if it's bcrypt
$isBcrypt = (strlen($user->password) === 60 && 
            (substr($user->password, 0, 4) === '$2y$' || 
             substr($user->password, 0, 4) === '$2a$' ||
             substr($user->password, 0, 4) === '$2b$'));

echo "   Is Bcrypt: " . ($isBcrypt ? "✅ YES" : "❌ NO") . "\n";

if ($isBcrypt) {
    echo "   Bcrypt Details:\n";
    echo "     Algorithm: " . substr($user->password, 1, 3) . "\n";
    echo "     Cost: " . substr($user->password, 4, 2) . "\n";
    echo "     Salt: " . substr($user->password, 7, 22) . "\n";
}

// Test some common passwords
echo "\n=== PASSWORD TESTS ===\n";
$testPasswords = [
    'password123',
    'Password123',
    'password',
    '12345678',
    'admin123',
    'test123'
];

foreach ($testPasswords as $testPass) {
    $matches = Hash::check($testPass, $user->password);
    echo "   '{$testPass}': " . ($matches ? "✅ MATCHES" : "❌ NO MATCH") . "\n";
}

echo "\n=== QUICK FIX ===\n";
echo "Do you want to reset this password? (yes/no): ";
$response = trim(fgets(STDIN));

if (strtolower($response) === 'yes') {
    echo "Enter new password: ";
    $newPassword = trim(fgets(STDIN));
    
    if (empty($newPassword)) {
        $newPassword = 'password123';
        echo "Using default: {$newPassword}\n";
    }
    
    $user->password = Hash::make($newPassword);
    $user->save();
    
    echo "\n✅ Password reset!\n";
    echo "   New Hash: " . $user->password . "\n";
    echo "   Verification: " . (Hash::check($newPassword, $user->password) ? "✅ WORKS" : "❌ FAILED") . "\n";
    echo "   Login with: {$newPassword}\n";
}

echo "\nDone!\n";