<?php
// send.php - Simple script to send Telegram messages
if ($argc < 2) {
    echo "Usage: php send.php \"Your message\"\n";
    exit(1);
}

$token = "8039580931:AAHTEc97UtslsZNSQSKa0HhsJt-chbi66qY";
$chatId = "912569433";
$message = $argv[1];

echo "Sending: $message\n";

$url = "https://api.telegram.org/bot{$token}/sendMessage";
$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'HTML'
];

$options = [
    'http' => [
        'header' => "Content-Type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data),
    ],
    'ssl' => ['verify_peer' => false]
];

$context = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result) {
    $response = json_decode($result, true);
    if ($response['ok']) {
        echo "✅ Message sent! ID: {$response['result']['message_id']}\n";
        exit(0);
    } else {
        echo "❌ Failed: " . json_encode($response) . "\n";
        exit(1);
    }
} else {
    echo "❌ No response from API\n";
    exit(1);
}