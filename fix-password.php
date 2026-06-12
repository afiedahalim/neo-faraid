<?php
// fix-password.php - Emergency password fix script

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== Password Fix Utility ===\n\n";

// List all users
$users = User::all();

echo "Found " . $users->count() . " users:\n";
echo str_repeat("-", 50) . "\n";

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Password Hash: " . substr($user->password, 0, 30) . "...\n";
    
    // Check if password is bcrypt
    $isBcrypt = (strlen($user->password) === 60 && 
                (substr($user->password, 0, 4) === '$2y$' || 
                 substr($user->password, 0, 4) === '$2a$' ||
                 substr($user->password, 0, 4) === '$2b$'));
    
    echo "Is Bcrypt: " . ($isBcrypt ? "YES" : "NO") . "\n";
    
    if (!$isBcrypt) {
        echo "⚠️  NOT using Bcrypt!\n";
        echo "Fix with: php artisan users:fix-passwords --id={$user->id}\n";
    }
    
    echo str_repeat("-", 50) . "\n";
}

// Ask if user wants to fix passwords
echo "\nDo you want to fix passwords? (yes/no): ";
$handle = fopen("php://stdin", "r");
$response = trim(fgets($handle));

if (strtolower($response) === 'yes') {
    echo "Enter new password for all users: ";
    $password = trim(fgets($handle));
    
    if (empty($password)) {
        $password = 'password123';
        echo "Using default password: {$password}\n";
    }
    
    echo "\nFixing passwords...\n";
    
    foreach ($users as $user) {
        $user->password = Hash::make($password);
        $user->save();
        echo "Fixed user: {$user->email}\n";
    }
    
    echo "\n✅ All passwords have been reset to: {$password}\n";
    echo "Please inform users to reset their passwords after login.\n";
}

fclose($handle);
echo "\nDone!\n";