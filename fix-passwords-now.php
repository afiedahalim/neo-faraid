<?php
// IMMEDIATE PASSWORD FIX - RUN THIS FIRST
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "🔄 RESETTING ALL PASSWORDS...\n\n";

$users = DB::table('users')->get();
foreach ($users as $user) {
    $newPassword = 'password123';
    $newHash = Hash::make($newPassword);
    
    DB::table('users')->where('id', $user->id)->update([
        'password' => $newHash,
        'updated_at' => now()
    ]);
    
    echo "✅ {$user->email} → password123\n";
}

echo "\n🎉 DONE! All passwords reset to: password123\n";
echo "\n📋 TEST LOGINS:\n";
foreach ($users as $user) {
    echo "   • {$user->email} / password123\n";
}
echo "\n🌐 Login page: http://neo-faraid.test/login\n";