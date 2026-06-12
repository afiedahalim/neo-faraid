<?php
// telegram-poll.php - Simple polling script
$token = "8039580931:AAHTEc97UtslsZNSQSKa0HhsJt-chbi66qY";
$chatId = "912569433";

echo "🤖 Telegram Bot Polling Started\n";
echo "Bot: @FaraidCalculatorBot\n";
echo "Press Ctrl+C to stop\n\n";

$offset = 0;

while (true) {
    try {
        // Get updates
        $url = "https://api.telegram.org/bot{$token}/getUpdates?offset={$offset}&timeout=30";
        $context = stream_context_create([
            'ssl' => ['verify_peer' => false]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response) {
            $data = json_decode($response, true);
            
            if ($data['ok'] && !empty($data['result'])) {
                foreach ($data['result'] as $update) {
                    $offset = $update['update_id'] + 1;
                    
                    if (isset($update['message'])) {
                        $message = $update['message'];
                        $chatId = $message['chat']['id'];
                        $text = $message['text'] ?? '';
                        $name = $message['chat']['first_name'] ?? 'User';
                        
                        echo "📨 [$name]: $text\n";
                        
                        // Handle commands
                        $responseText = "";
                        switch (strtolower($text)) {
                            case '/start':
                                $responseText = "👋 Assalamualaikum $name!\n\nWelcome to NeoFaraidCalculator 🤖\nIslamic Inheritance Calculation System\n\nCommands:\n/calculate - Start calculation\n/help - Show help\n/about - About this bot";
                                break;
                                
                            case '/help':
                                $responseText = "📚 Faraid Calculator Help\n\nThis bot calculates Islamic inheritance shares.\n\nHow to use:\n1. Send /calculate\n2. Follow the steps\n3. Get accurate Faraid shares";
                                break;
                                
                            case '/about':
                                $responseText = "📖 About NeoFaraidCalculator\n\nVersion: 1.0.0\nDeveloped: 2024\nDeveloper: Afieda Halim\nContact: @afiedahalim";
                                break;
                                
                            case '/calculate':
                                $responseText = "🧮 Faraid Calculation\n\nLet's calculate inheritance shares.\n\nEnter total estate value (RM):";
                                break;
                                
                            default:
                                $responseText = "🤔 I received: $text\n\nUse /start to see commands or /calculate to begin.";
                        }
                        
                        // Send response
                        $data = [
                            'chat_id' => $chatId,
                            'text' => $responseText,
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
                        @file_get_contents("https://api.telegram.org/bot{$token}/sendMessage", false, $context);
                        
                        echo "✅ Replied to $name\n";
                    }
                }
            }
        }
        
        sleep(1); // Wait 1 second between checks
        
    } catch (Exception $e) {
        echo "⚠️ Error: " . $e->getMessage() . "\n";
        sleep(5);
    }
}