<?php
// bot-debug.php - Debug version with detailed logging
$token = "8039580931:AAHTEc97UtslsZNSQSKa0HhsJt-chbi66qY";

echo "========================================\n";
echo "🤖 NEOFARAID BOT - DEBUG MODE\n";
echo "Bot: @FaraidCalculatorBot\n";
echo "Token: " . substr($token, 0, 10) . "...\n";
echo "Started: " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n\n";

// Test connection first
echo "[DEBUG] Testing API connection...\n";
$testUrl = "https://api.telegram.org/bot{$token}/getMe";
$context = stream_context_create([
    'ssl' => ['verify_peer' => false],
    'http' => ['timeout' => 10]
]);

$testResponse = @file_get_contents($testUrl, false, $context);
if ($testResponse) {
    $testData = json_decode($testResponse, true);
    if ($testData['ok']) {
        echo "[DEBUG] ✅ Connected to: {$testData['result']['first_name']}\n";
    } else {
        echo "[DEBUG] ❌ Connection failed: " . json_encode($testData) . "\n";
        exit(1);
    }
} else {
    echo "[DEBUG] ❌ No response from API\n";
    exit(1);
}

// Get current updates to see what's there
echo "\n[DEBUG] Checking for existing updates...\n";
$updatesUrl = "https://api.telegram.org/bot{$token}/getUpdates?offset=-1";
$updatesResponse = @file_get_contents($updatesUrl, false, $context);
if ($updatesResponse) {
    $updatesData = json_decode($updatesResponse, true);
    if ($updatesData['ok']) {
        $count = count($updatesData['result']);
        echo "[DEBUG] Found {$count} updates in queue\n";
        
        if ($count > 0) {
            echo "[DEBUG] Last update ID: " . $updatesData['result'][$count-1]['update_id'] . "\n";
            echo "[DEBUG] Last message: " . ($updatesData['result'][$count-1]['message']['text'] ?? 'N/A') . "\n";
        }
    }
}

// Now start polling
echo "\n[DEBUG] Starting polling...\n";
echo "Press Ctrl+C to stop\n\n";

$offset = 0;
$counter = 0;

while (true) {
    $counter++;
    echo "[" . date('H:i:s') . "] Poll #{$counter}...\n";
    
    $url = "https://api.telegram.org/bot{$token}/getUpdates?offset={$offset}&limit=100&timeout=25";
    
    // Add detailed error reporting
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ],
        'http' => [
            'timeout' => 30,
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        $error = error_get_last();
        echo "[" . date('H:i:s') . "] ❌ file_get_contents failed: " . ($error['message'] ?? 'Unknown error') . "\n";
        sleep(5);
        continue;
    }
    
    if (empty($response)) {
        echo "[" . date('H:i:s') . "] ⚠️ Empty response from API\n";
        sleep(2);
        continue;
    }
    
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "[" . date('H:i:s') . "] ❌ JSON decode error: " . json_last_error_msg() . "\n";
        echo "Raw response: " . substr($response, 0, 200) . "...\n";
        sleep(5);
        continue;
    }
    
    if (!$data) {
        echo "[" . date('H:i:s') . "] ❌ No data from API\n";
        sleep(5);
        continue;
    }
    
    if (!isset($data['ok'])) {
        echo "[" . date('H:i:s') . "] ❌ Invalid response format\n";
        echo "Response: " . json_encode($data) . "\n";
        sleep(5);
        continue;
    }
    
    if (!$data['ok']) {
        echo "[" . date('H:i:s') . "] ❌ API error: " . ($data['description'] ?? 'Unknown') . "\n";
        sleep(5);
        continue;
    }
    
    if (empty($data['result'])) {
        echo "[" . date('H:i:s') . "] 🔍 No new messages\n";
        sleep(1);
        continue;
    }
    
    echo "[" . date('H:i:s') . "] ✅ Found " . count($data['result']) . " new message(s)\n";
    
    foreach ($data['result'] as $update) {
        $updateId = $update['update_id'];
        echo "[" . date('H:i:s') . "] Processing update ID: {$updateId}\n";
        
        // Update offset
        if ($updateId >= $offset) {
            $offset = $updateId + 1;
            echo "[" . date('H:i:s') . "] Offset updated to: {$offset}\n";
        }
        
        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';
            $name = $message['chat']['first_name'] ?? 'User';
            
            echo "[" . date('H:i:s') . "] 📨 [{$name}]: {$text}\n";
            
            // Simple response
            $reply = "✅ Received: {$text}";
            
            // Send response
            $sendData = [
                'chat_id' => $chatId,
                'text' => $reply,
                'parse_mode' => 'HTML'
            ];
            
            $sendOptions = [
                'http' => [
                    'header' => "Content-Type: application/json\r\n",
                    'method' => 'POST',
                    'content' => json_encode($sendData),
                    'ignore_errors' => true,
                ],
                'ssl' => ['verify_peer' => false]
            ];
            
            $sendContext = stream_context_create($sendOptions);
            $sendResult = @file_get_contents("https://api.telegram.org/bot{$token}/sendMessage", false, $sendContext);
            
            if ($sendResult) {
                echo "[" . date('H:i:s') . "] ✅ Replied to {$name}\n";
            } else {
                echo "[" . date('H:i:s') . "] ❌ Failed to send reply\n";
            }
        }
    }
    
    sleep(1);
}