<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {--remove : Remove webhook} {--test : Test webhook} {--url= : Custom webhook URL}';
    protected $description = 'Set or remove Telegram bot webhook';

    public function handle()
    {
        $botToken = config('services.telegram.bot_token');
        
        if (!$botToken) {
            $this->error('❌ Telegram bot token not configured in .env file.');
            return 1;
        }

        if ($this->option('remove')) {
            return $this->removeWebhook($botToken);
        }

        if ($this->option('test')) {
            return $this->testWebhook($botToken);
        }

        return $this->setWebhook($botToken);
    }

    private function setWebhook($botToken)
    {
        // Use custom URL or default to app URL
        $webhookUrl = $this->option('url') ?: config('app.url') . '/telegram/bot/webhook';
        
        $this->info("🤖 Setting webhook for Faraid Calculator Bot");
        $this->info("📝 Bot Token: " . substr($botToken, 0, 10) . "...");
        $this->info("🔗 Webhook URL: " . $webhookUrl);

        $url = "https://api.telegram.org/bot{$botToken}/setWebhook";

        try {
            $response = Http::withoutVerifying()->post($url, [
                'url' => $webhookUrl,
                'drop_pending_updates' => true,
                'allowed_updates' => ['message', 'callback_query'],
            ]);

            $result = $response->json();

            if ($result['ok']) {
                $this->info('✅ Webhook set successfully!');
                
                // Get webhook info
                $info = $this->getWebhookInfo($botToken);
                if ($info) {
                    $this->info("\n📊 Webhook Information:");
                    $this->info("URL: " . ($info['url'] ?? 'Not set'));
                    $this->info("Pending updates: " . ($info['pending_update_count'] ?? 0));
                }
                
                $this->info("\n🎉 Bot is ready!");
                $this->info("📱 Go to: https://t.me/FaraidCalculatorBot");
                $this->info("💬 Send /start to begin");
                
                return 0;
            } else {
                $this->error('❌ Failed to set webhook: ' . ($result['description'] ?? 'Unknown error'));
                
                // Common error: webhook URL must be HTTPS for production
                if (strpos($result['description'] ?? '', 'bad webhook') !== false) {
                    $this->info("\n💡 Common solutions:");
                    $this->info("1. For local development, use ngrok: ngrok http 8000");
                    $this->info("2. Use the ngrok HTTPS URL as webhook");
                    $this->info("3. Command: php artisan telegram:set-webhook --url=https://your-ngrok-url.ngrok-free.app/telegram/bot/webhook");
                }
                
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            return 1;
        }
    }

    private function removeWebhook($botToken)
    {
        $this->info("🗑️ Removing webhook...");
        
        $url = "https://api.telegram.org/bot{$botToken}/deleteWebhook";
        
        try {
            $response = Http::withoutVerifying()->post($url, [
                'drop_pending_updates' => true
            ]);
            
            $result = $response->json();
            
            if ($result['ok']) {
                $this->info('✅ Webhook removed successfully!');
                return 0;
            } else {
                $this->error('❌ Failed to remove webhook: ' . ($result['description'] ?? 'Unknown error'));
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            return 1;
        }
    }

    private function testWebhook($botToken)
    {
        $this->info("🧪 Testing webhook configuration...");
        
        // Get bot info
        $botInfo = $this->getBotInfo($botToken);
        if ($botInfo['ok']) {
            $bot = $botInfo['result'];
            $this->info("🤖 Bot Information:");
            $this->info("Name: " . $bot['first_name']);
            $this->info("Username: @" . $bot['username']);
        }
        
        // Get webhook info
        $info = $this->getWebhookInfo($botToken);
        
        if (!$info) {
            $this->warn("⚠️ Cannot get webhook info");
        } else {
            $this->info("\n📊 Webhook Status:");
            $this->info("URL: " . ($info['url'] ?? 'Not set'));
            $this->info("Pending updates: " . ($info['pending_update_count'] ?? 0));
            
            if (isset($info['last_error_date'])) {
                $this->warn("⚠️ Last error date: " . date('Y-m-d H:i:s', $info['last_error_date']));
                $this->warn("⚠️ Last error message: " . ($info['last_error_message'] ?? 'Unknown'));
            } else {
                $this->info("✅ No recent errors");
            }
        }
        
        $this->info("\n🔗 Bot URL: https://t.me/FaraidCalculatorBot");
        $this->info("📱 Send /start to test");
        
        return 0;
    }

    private function getWebhookInfo($botToken)
    {
        $url = "https://api.telegram.org/bot{$botToken}/getWebhookInfo";
        
        try {
            $response = Http::withoutVerifying()->get($url);
            return $response->json()['result'] ?? [];
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getBotInfo($botToken)
    {
        $url = "https://api.telegram.org/bot{$botToken}/getMe";
        
        try {
            $response = Http::withoutVerifying()->get($url);
            return $response->json();
        } catch (\Exception $e) {
            return ['ok' => false];
        }
    }
}