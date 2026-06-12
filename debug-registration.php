<?php
// debug-registration.php - Debug user registration

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== REGISTRATION DEBUG ===\n\n";

// Test user data
$testUser = [
    'name' => 'Test User',
    'email' => 'test' . time() . '@example.com',
    'password' => 'password123'
];

echo "Test Registration Data:\n";
echo "  Name: {$testUser['name']}\n";
echo "  Email: {$testUser['email']}\n";
echo "  Password: {$testUser['password']}\n";
echo "  Hash of password: " . Hash::make($testUser['password']) . "\n";

// Check if User model has password mutator
$user = new User();
$reflection = new ReflectionClass($user);
$methods = $reflection->getMethods();

echo "\n=== User Model Analysis ===\n";
echo "Methods in User model:\n";
foreach ($methods as $method) {
    if (strpos($method->name, 'password') !== false) {
        echo "  - {$method->name}\n";
    }
}

// Check fillable attributes
if (property_exists($user, 'fillable')) {
    echo "\nFillable attributes:\n";
    print_r($user->getFillable());
}

// Check hidden attributes
if (property_exists($user, 'hidden')) {
    echo "\nHidden attributes:\n";
    print_r($user->getHidden());
}

// Test creating a user
echo "\n=== Test User Creation ===\n";
try {
    $newUser = User::create([
        'name' => $testUser['name'],
        'email' => $testUser['email'],
        'password' => Hash::make($testUser['password']),
        'role' => 'user',
        'status' => 'active',
        'is_active' => 1
    ]);
    
    echo "✅ User created successfully!\n";
    echo "  User ID: {$newUser->id}\n";
    echo "  Stored Hash: {$newUser->password}\n";
    echo "  Hash Length: " . strlen($newUser->password) . "\n";
    
    // Verify password
    $verify = Hash::check($testUser['password'], $newUser->password);
    echo "  Password Verification: " . ($verify ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    // Test login attempt
    $authAttempt = \Illuminate\Support\Facades\Auth::attempt([
        'email' => $testUser['email'],
        'password' => $testUser['password']
    ]);
    echo "  Auth Attempt: " . ($authAttempt ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    // Clean up
    $newUser->delete();
    echo "  Test user cleaned up.\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating user: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\nDone!\n";