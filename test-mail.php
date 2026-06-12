<?php
// test-mail.php - Quick mail configuration test

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;

// Manually configure mail
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Mail Configuration...\n";
echo "=============================\n\n";

// Check .env file
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    echo ".env File Found\n";
    
    if (strpos($envContent, 'MAIL_MAILER=log') !== false) {
        echo "✓ MAIL_MAILER is set to 'log'\n";
    } elseif (strpos($envContent, 'MAIL_MAILER=smtp') !== false) {
        echo "✗ MAIL_MAILER is set to 'smtp' - THIS IS THE PROBLEM!\n";
        echo "  Change it to 'log' in .env file\n";
    } else {
        echo "? MAIL_MAILER not found in .env\n";
    }
} else {
    echo "✗ .env file not found!\n";
}

echo "\n\nTesting Mail Sending...\n";
echo "=======================\n";

try {
    // Test with log driver (should not try to connect to SMTP)
    Mail::raw('Test email content', function($message) {
        $message->to('test@example.com')
                ->subject('Test Email from Neo Faraid');
    });
    
    echo "✓ Email queued successfully!\n";
    echo "  (With 'log' driver, email is saved to storage/logs/laravel.log)\n\n";
    
    // Check log file
    $logFile = __DIR__ . '/storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $logSize = filesize($logFile);
        echo "Log file size: " . $logSize . " bytes\n";
        
        if ($logSize > 0) {
            echo "✓ Log file has content\n";
        }
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n\nCurrent Mail Configuration:\n";
echo "===========================\n";
print_r(config('mail'));