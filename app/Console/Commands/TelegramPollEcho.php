<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramPollEcho extends Command
{
    protected $signature = 'telegram:poll-echo';
    protected $description = 'Simple echo bot using polling';

    public function handle()
    {
        $botToken = config('services.telegram.bot_token');
        
        if (!$botToken) {
            $this->error('❌ Bot token not set!');
            $this->info('Add to .env: TELEGRAM_BOT_TOKEN=8039580931:AAHTEc97UtslsZNSQSKa0HhsJt-chbi66qY');
            return 1;
        }
        
        // Get bot info first
        try {
            $response = Http::withoutVerifying()
                ->get("https://api.telegram.org/bot{$botToken}/getMe");
            
            if (!$response->successful()) {
                $this->error('❌ Cannot connect to bot');
                return 1;
            }
            
            $botInfo = $response->json();
            if (!$botInfo['ok']) {
                $this->error('❌ Bot error: ' . ($botInfo['description'] ?? 'Unknown'));
                return 1;
            }
            
            $botName = $botInfo['result']['first_name'];
            $botUsername = $botInfo['result']['username'];
            
        } catch (\Exception $e) {
            $this->error('❌ Connection failed: ' . $e->getMessage());
            return 1;
        }
        
        $this->info('🤖 SIMPLE ECHO BOT (Polling Mode)');
        $this->info('Bot: ' . $botName . ' (@' . $botUsername . ')');
        $this->info('URL: https://t.me/' . $botUsername);
        $this->info('📱 Send any message to test');
        $this->info('⏹️ Press Ctrl+C to stop');
        $this->newLine();
        
        $offset = 0;
        $messageCount = 0;
        
        while (true) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(30)
                    ->get("https://api.telegram.org/bot{$botToken}/getUpdates", [
                        'offset' => $offset,
                        'timeout' => 5,
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if ($data['ok'] && !empty($data['result'])) {
                        foreach ($data['result'] as $update) {
                            $updateId = $update['update_id'];
                            
                            if (isset($update['message'])) {
                                $chatId = $update['message']['chat']['id'];
                                $text = $update['message']['text'] ?? '';
                                $from = $update['message']['from'];
                                
                                $time = date('H:i:s');
                                $name = $from['first_name'] ?? 'User';
                                $userId = $from['id'];
                                
                                $this->info("[{$time}] {$name} (@{$userId}): {$text}");
                                $messageCount++;
                                
                                // Respond
                                $this->respondToMessage($botToken, $chatId, $text, $from);
                            }
                            
                            $offset = $updateId + 1;
                        }
                    }
                }
                
                sleep(1); // Wait 1 second between polls
                
            } catch (\Exception $e) {
                $this->error('Poll error: ' . $e->getMessage());
                sleep(5); // Wait 5 seconds on error
            }
        }
    }
    
    private function respondToMessage($botToken, $chatId, $text, $from)
    {
        $name = $from['first_name'] ?? 'User';
        
        if (strpos($text, '/start') === 0) {
            $response = "🕌 Assalamualaikum {$name}!\n\nWelcome to Faraid Calculator Bot! 🤖\n\nI can help you calculate Islamic inheritance.\n\nCommands:\n/start - Welcome message\n/help - Show help\n/calculate - Start calculation\n\nVisit our website: " . config('app.url', 'http://localhost:8000');
        } elseif (strpos($text, '/help') === 0) {
            $response = "🤖 Help\n\nAvailable commands:\n• /start - Welcome\n• /help - This message\n• /calculate - Start Faraid calculation\n\nFor full features, visit our website!";
        } elseif (strpos($text, '/calculate') === 0) {
            $response = "🧮 Faraid Calculation\n\nFor complete inheritance calculation with all features, visit our website:\n" . config('app.url', 'http://localhost:8000') . "\n\nYou can:\n• Add all heirs\n• Calculate shares\n• Generate PDF reports\n• Save calculations";
        } elseif (strpos($text, '/') === 0) {
            $response = "❓ Unknown command: {$text}\n\nUse /help to see available commands.";
        } else {
            $response = "👋 Hello {$name}!\n\nYou said: \"{$text}\"\n\nI'm a Faraid calculator bot. Use /help for commands.";
        }
        
        try {
            Http::withoutVerifying()
                ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $response,
                ]);
                
            $this->info("   ✅ Response sent");
            
        } catch (\Exception $e) {
            $this->error('   ❌ Failed to send: ' . $e->getMessage());
        }
    }
}