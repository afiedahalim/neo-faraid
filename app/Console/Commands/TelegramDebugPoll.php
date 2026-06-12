<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TelegramDebugPoll extends Command
{
    protected $signature = 'telegram:debug-poll';
    protected $description = 'Debug Telegram polling';

    public function handle()
    {
        $botToken = config('services.telegram.bot_token');
        
        $this->info('🔍 DEBUG TELEGRAM POLLING');
        $this->info('Bot Token: ' . substr($botToken, 0, 10) . '...');
        $this->info('Press Ctrl+C to stop');
        $this->newLine();
        
        $offset = 0;
        
        while (true) {
            $this->line('[' . date('H:i:s') . '] Polling for updates...');
            
            $url = "https://api.telegram.org/bot{$botToken}/getUpdates?offset={$offset}&timeout=1";
            
            // Create context with SSL disabled
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                'http' => [
                    'timeout' => 5
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                $this->error('   ❌ Failed to get updates');
                sleep(2);
                continue;
            }
            
            $data = json_decode($response, true);
            
            if (!$data['ok']) {
                $this->error('   ❌ API error: ' . ($data['description'] ?? 'Unknown'));
                sleep(2);
                continue;
            }
            
            if (empty($data['result'])) {
                $this->info('   ℹ️ No new updates');
            } else {
                $this->info('   ✅ Found ' . count($data['result']) . ' update(s)');
                
                foreach ($data['result'] as $update) {
                    $updateId = $update['update_id'];
                    
                    if (isset($update['message'])) {
                        $chatId = $update['message']['chat']['id'];
                        $text = $update['message']['text'] ?? '';
                        $from = $update['message']['from'];
                        
                        $this->info('   📥 Message from: ' . ($from['first_name'] ?? 'Unknown'));
                        $this->info('      Chat ID: ' . $chatId);
                        $this->info('      Text: ' . $text);
                        $this->info('      Update ID: ' . $updateId);
                        
                        // Try to send a simple response
                        $this->sendSimpleResponse($botToken, $chatId, $text, $from);
                    }
                    
                    $offset = $updateId + 1;
                }
            }
            
            sleep(1);
        }
    }
    
    private function sendSimpleResponse($botToken, $chatId, $text, $from)
    {
        $name = $from['first_name'] ?? 'User';
        
        if (strpos($text, '/start') === 0) {
            $responseText = "Hello {$name}! 👋\nDebug mode: I received your /start command!";
        } else {
            $responseText = "Debug: You said '{$text}'";
        }
        
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $postData = http_build_query([
            'chat_id' => $chatId,
            'text' => $responseText
        ]);
        
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ]);
        
        $result = @file_get_contents($url, false, $context);
        
        if ($result === false) {
            $this->error('      ❌ Failed to send response');
        } else {
            $this->info('      ✅ Response sent');
        }
    }
}