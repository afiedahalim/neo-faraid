<?php
// list-users.php - List all users in database

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=== ALL USERS IN DATABASE ===\n\n";

$users = User::all();

if ($users->count() === 0) {
    echo "❌ No users found in database!\n";
    echo "\nTry registering a new user first.\n";
    exit;
}

echo "Found {$users->count()} user(s):\n";
echo str_repeat("=", 80) . "\n";

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Password Hash: " . substr($user->password, 0, 30) . "...\n";
    echo "Hash Length: " . strlen($user->password) . " chars\n";
    
    // Check if it's bcrypt
    $isBcrypt = (strlen($user->password) === 60 && 
                (substr($user->password, 0, 4) === '$2y$' || 
                 substr($user->password, 0, 4) === '$2a$' ||
                 substr($user->password, 0, 4) === '$2b$'));
    
    echo "Is Bcrypt: " . ($isBcrypt ? "✅ YES" : "❌ NO") . "\n";
    echo "Created: " . $user->created_at . "\n";
    echo str_repeat("-", 80) . "\n";
}

echo "\n=== TEST COMMANDS ===\n";
echo "Copy one of the emails above and test with:\n";
foreach ($users as $user) {
    echo "http://neo-faraid.test/test-login/{$user->email}/password123\n";
}

echo "\n=== QUICK FIX ===\n";
echo "Do you want to reset ALL passwords to 'password123'? (yes/no): ";
$response = trim(fgets(STDIN));

if (strtolower($response) === 'yes') {
    echo "\nResetting all passwords...\n";
    
    foreach ($users as $user) {
        $user->password = \Illuminate\Support\Facades\Hash::make('password123');
        $user->save();
        
        $check = \Illuminate\Support\Facades\Hash::check('password123', $user->password);
        echo "  {$user->email}: " . ($check ? "✅ Fixed" : "❌ Failed") . "\n";
    }
    
    echo "\n✅ All passwords reset to: password123\n";
    echo "Test login with any user using 'password123'\n";
}