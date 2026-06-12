<?php
// reset-all-passwords.php - Reset all passwords

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== RESET ALL PASSWORDS ===\n\n";

// Get all users
$users = DB::table('users')->get();
$newPassword = 'password123';

echo "Resetting {$users->count()} users to: {$newPassword}\n";
echo str_repeat("=", 60) . "\n";

foreach ($users as $user) {
    $newHash = Hash::make($newPassword);
    
    DB::table('users')
        ->where('id', $user->id)
        ->update([
            'password' => $newHash,
            'updated_at' => now()
        ]);
    
    // Verify
    $updatedUser = DB::table('users')->find($user->id);
    $check = Hash::check($newPassword, $updatedUser->password);
    
    echo "{$user->email}: " . ($check ? "✅ Reset" : "❌ Failed") . "\n";
}

echo "\n✅ COMPLETE!\n";
echo "All users now have password: {$newPassword}\n";
echo "\nTest URLs:\n";
foreach ($users as $user) {
    echo "  http://neo-faraid.test/test-login/{$user->email}/password123\n";
}
echo "\nLogin Page: http://neo-faraid.test/login\n";