<?php
// app/Services/TelegramService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Calculation;
use Illuminate\Support\Str;

class TelegramService
{
    private $botToken;
    private $apiUrl;
    
    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }
    
    /**
     * Send message to Telegram user
     */
    public function sendMessage($chatId, $text, $parseMode = 'HTML', $keyboard = null)
    {
        try {
            $data = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => $parseMode,
            ];
            
            if ($keyboard) {
                $data['reply_markup'] = json_encode($keyboard);
            }
            
            $response = Http::post("{$this->apiUrl}/sendMessage", $data);
            
            if ($response->failed()) {
                Log::error('Telegram sendMessage failed:', [
                    'chat_id' => $chatId,
                    'error' => $response->body()
                ]);
                return false;
            }
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage exception:', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Send calculation results to Telegram
     */
    public function sendCalculationResult($chatId, Calculation $calculation)
    {
        $message = $this->formatCalculationMessage($calculation);
        
        // Create inline keyboard with options
        $keyboard = [
            'inline_keyboard' => [
                [
                    [
                        'text' => '📊 View Details',
                        'url' => route('calculator.show', $calculation->id)
                    ]
                ],
                [
                    [
                        'text' => '🔄 New Calculation',
                        'callback_data' => 'new_calculation'
                    ],
                    [
                        'text' => '📋 View History',
                        'callback_data' => 'view_history'
                    ]
                ]
            ]
        ];
        
        return $this->sendMessage($chatId, $message, 'HTML', $keyboard);
    }
    
    /**
     * Format calculation message
     */
    private function formatCalculationMessage(Calculation $calculation)
    {
        $message = "<b>📊 Faraid Calculation Result</b>\n\n";
        $message .= "<b>Deceased:</b> {$calculation->deceased_name}\n";
        $message .= "<b>Net Estate:</b> {$calculation->formatted_net_assets}\n";
        $message .= "<b>Total Eligible Heirs:</b> {$calculation->eligible_heirs_count}\n\n";
        
        $message .= "<b>Distribution Summary:</b>\n";
        
        if ($calculation->distribution_summary && isset($calculation->distribution_summary['heirs'])) {
            $counter = 1;
            foreach ($calculation->distribution_summary['heirs'] as $heir) {
                if (($heir['amount'] ?? 0) > 0) {
                    $message .= "{$counter}. {$heir['name']}: {$heir['share']} = {$heir['formatted_amount']}\n";
                    $counter++;
                }
            }
        }
        
        $message .= "\n<i>Calculation ID: {$calculation->calculation_hash}</i>";
        
        return $message;
    }
    
    /**
     * Handle webhook update
     */
    public function handleWebhookUpdate($update)
    {
        Log::info('Telegram webhook update:', $update);
        
        if (isset($update['message'])) {
            return $this->handleMessage($update['message']);
        }
        
        if (isset($update['callback_query'])) {
            return $this->handleCallbackQuery($update['callback_query']);
        }
        
        return ['status' => 'ignored'];
    }
    
    /**
     * Handle incoming message
     */
    private function handleMessage($message)
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';
        $from = $message['from'];
        
        Log::info('Processing Telegram message:', [
            'chat_id' => $chatId,
            'text' => $text,
            'from' => $from
        ]);
        
        // Handle /start command
        if (strpos($text, '/start') === 0) {
            return $this->handleStartCommand($chatId, $from, $text);
        }
        
        // Handle other commands
        return $this->handleCommand($chatId, $text, $from);
    }
    
    /**
     * Handle /start command
     */
    private function handleStartCommand($chatId, $from, $text)
    {
        $parts = explode(' ', $text);
        $token = count($parts) > 1 ? $parts[1] : null;
        
        if ($token) {
            // Handle authentication token
            return $this->handleAuthentication($chatId, $from, $token);
        }
        
        // Send welcome message
        $welcomeMessage = $this->getWelcomeMessage($from);
        return $this->sendMessage($chatId, $welcomeMessage);
    }
    
    /**
     * Handle authentication token
     */
    private function handleAuthentication($chatId, $from, $token)
    {
        // Find user by session token
        $user = User::where('telegram_session', $token)->first();
        
        if (!$user) {
            $this->sendMessage($chatId, "❌ Invalid or expired authentication token. Please try again.");
            return ['status' => 'invalid_token'];
        }
        
        // Link Telegram account
        $user->update([
            'telegram_id' => $from['id'],
            'telegram_username' => $from['username'] ?? null,
            'telegram_first_name' => $from['first_name'],
            'telegram_last_name' => $from['last_name'] ?? null,
            'telegram_linked_at' => now(),
            'telegram_session' => null, // Clear token
        ]);
        
        $message = "✅ <b>Account Linked Successfully!</b>\n\n";
        $message .= "Welcome to <b>Neo Faraid Calculator</b>, {$user->name}!\n\n";
        $message .= "You can now use the following commands:\n";
        $message .= "• /calculate - Start new calculation\n";
        $message .= "• /history - View your calculations\n";
        $message .= "• /help - Show help guide\n";
        $message .= "• /web - Open web dashboard\n";
        
        $this->sendMessage($chatId, $message);
        
        return ['status' => 'authenticated', 'user_id' => $user->id];
    }
    
    /**
     * Get welcome message
     */
    private function getWelcomeMessage($from)
    {
        $message = "👋 <b>Welcome to Neo Faraid Calculator!</b>\n\n";
        $message .= "I'm your Islamic inheritance calculation assistant.\n\n";
        $message .= "<b>To get started:</b>\n";
        $message .= "1. Visit our website to create an account\n";
        $message .= "2. Link your account with the /link command\n";
        $message .= "3. Start calculating with /calculate\n\n";
        $message .= "<b>Available Commands:</b>\n";
        $message .= "/start - Start the bot\n";
        $message .= "/link - Link your web account\n";
        $message .= "/help - Show help guide\n";
        
        return $message;
    }
    
    /**
     * Handle other commands
     */
    private function handleCommand($chatId, $text, $from)
    {
        $user = User::where('telegram_id', $from['id'])->first();
        
        switch ($text) {
            case '/link':
                return $this->sendLinkInstructions($chatId, $user);
                
            case '/calculate':
                return $this->startCalculation($chatId, $user);
                
            case '/history':
                return $this->showHistory($chatId, $user);
                
            case '/help':
                return $this->showHelp($chatId);
                
            case '/web':
                $webUrl = route('home');
                $message = "🌐 <b>Web Dashboard</b>\n\n";
                $message .= "Open your web dashboard:\n";
                $message .= "<a href='{$webUrl}'>{$webUrl}</a>";
                return $this->sendMessage($chatId, $message);
                
            default:
                $message = "🤖 I don't understand that command.\n\n";
                $message .= "Try one of these:\n";
                $message .= "/start - Start the bot\n";
                $message .= "/link - Link your account\n";
                $message .= "/calculate - Start calculation\n";
                $message .= "/help - Show help guide";
                return $this->sendMessage($chatId, $message);
        }
    }
    
    /**
     * Send link instructions
     */
    private function sendLinkInstructions($chatId, $user)
    {
        if ($user) {
            $message = "✅ <b>Account Already Linked</b>\n\n";
            $message .= "You're logged in as: <b>{$user->name}</b>\n";
            $message .= "Email: {$user->email}\n\n";
            $message .= "Use /calculate to start a new calculation.";
            return $this->sendMessage($chatId, $message);
        }
        
        // Generate link token
        $token = Str::random(32);
        $webUrl = route('login') . "?telegram_auth={$token}";
        
        $message = "🔗 <b>Link Your Account</b>\n\n";
        $message .= "To link your Telegram account:\n\n";
        $message .= "1. <b>Click this link:</b>\n";
        $message .= "<a href='{$webUrl}'>{$webUrl}</a>\n\n";
        $message .= "2. Log in with your email and password\n";
        $message .= "3. Click 'Link Telegram Account'\n";
        $message .= "4. You'll be redirected back here automatically\n\n";
        $message .= "<i>This link expires in 15 minutes.</i>";
        
        return $this->sendMessage($chatId, $message);
    }
    
    /**
     * Start calculation process
     */
    private function startCalculation($chatId, $user)
    {
        if (!$user) {
            return $this->sendMessage($chatId, "❌ Please link your account first using /link");
        }
        
        $message = "📝 <b>New Faraid Calculation</b>\n\n";
        $message .= "I'll guide you through the calculation process.\n\n";
        $message .= "For a complete experience with forms and visualizations, please use our web interface:\n";
        $message .= "<a href='" . route('calculator.create') . "'>Open Calculator</a>\n\n";
        $message .= "Your calculations will sync automatically between Telegram and web.";
        
        return $this->sendMessage($chatId, $message);
    }
    
    /**
     * Show calculation history
     */
    private function showHistory($chatId, $user)
    {
        if (!$user) {
            return $this->sendMessage($chatId, "❌ Please link your account first using /link");
        }
        
        $calculations = Calculation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        if ($calculations->isEmpty()) {
            return $this->sendMessage($chatId, "📭 You haven't made any calculations yet.\n\nUse /calculate to start.");
        }
        
        $message = "📋 <b>Your Recent Calculations</b>\n\n";
        
        foreach ($calculations as $calc) {
            $message .= "• <b>{$calc->deceased_name}</b>\n";
            $message .= "  📅 {$calc->formatted_date_of_death}\n";
            $message .= "  💰 {$calc->formatted_net_assets}\n";
            $message .= "  👥 {$calc->eligible_heirs_count} heirs\n";
            $message .= "  🔗 <a href='" . route('calculator.show', $calc->id) . "'>View Details</a>\n";
            $message .= "\n";
        }
        
        $message .= "<i>View all calculations on the web dashboard.</i>";
        
        return $this->sendMessage($chatId, $message);
    }
    
    /**
     * Show help guide
     */
    private function showHelp($chatId)
    {
        $message = "❓ <b>Neo Faraid Bot Help</b>\n\n";
        $message .= "<b>Available Commands:</b>\n";
        $message .= "/start - Start the bot\n";
        $message .= "/link - Link your web account\n";
        $message .= "/calculate - Start new calculation\n";
        $message .= "/history - View your calculations\n";
        $message .= "/web - Open web dashboard\n";
        $message .= "/help - Show this help\n\n";
        $message .= "<b>How It Works:</b>\n";
        $message .= "1. Create account on our website\n";
        $message .= "2. Link it with Telegram using /link\n";
        $message .= "3. Perform calculations on web or Telegram\n";
        $message .= "4. View results on both platforms\n\n";
        $message .= "<b>Note:</b> For complex calculations with forms and visualizations, use the web interface for the best experience.";
        
        return $this->sendMessage($chatId, $message);
    }
    
    /**
     * Handle callback queries
     */
    private function handleCallbackQuery($callbackQuery)
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $data = $callbackQuery['data'];
        $messageId = $callbackQuery['message']['message_id'];
        
        // Answer callback query
        $this->answerCallbackQuery($callbackQuery['id']);
        
        // Handle different callback data
        switch ($data) {
            case 'new_calculation':
                $user = User::where('telegram_id', $callbackQuery['from']['id'])->first();
                return $this->startCalculation($chatId, $user);
                
            case 'view_history':
                $user = User::where('telegram_id', $callbackQuery['from']['id'])->first();
                return $this->showHistory($chatId, $user);
                
            default:
                return ['status' => 'unknown_callback'];
        }
    }
    
    /**
     * Answer callback query
     */
    private function answerCallbackQuery($callbackQueryId)
    {
        try {
            Http::post("{$this->apiUrl}/answerCallbackQuery", [
                'callback_query_id' => $callbackQueryId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to answer callback query:', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Set webhook
     */
    public function setWebhook($url)
    {
        try {
            $response = Http::post("{$this->apiUrl}/setWebhook", [
                'url' => $url,
            ]);
            
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to set webhook:', ['error' => $e->getMessage()]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get bot info
     */
    public function getBotInfo()
    {
        try {
            $response = Http::get("{$this->apiUrl}/getMe");
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to get bot info:', ['error' => $e->getMessage()]);
            return null;
        }
    }
}