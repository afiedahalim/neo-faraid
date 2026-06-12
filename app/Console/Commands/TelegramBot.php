<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TelegramBot extends Command
{
    protected $signature = 'telegram:bot';
    protected $description = 'Run Telegram bot in polling mode with Neo Faraid Calculator features';

    // Bot token from .env
    private $botToken;
    private $botApiUrl;
    private $sessionFile;
    private $logDir;

    public function handle()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        
        if (!$this->botToken) {
            $this->error('TELEGRAM_BOT_TOKEN not set in .env');
            $this->info('Please add TELEGRAM_BOT_TOKEN=your_token_here to your .env file');
            return 1;
        }
        
        $this->botApiUrl = "https://api.telegram.org/bot{$this->botToken}/";
        
        // Setup directories
        $this->logDir = storage_path('logs/telegram/');
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
        
        $this->sessionFile = $this->logDir . 'polling_sessions.json';
        
        $this->info('🤖 NEO FARAID CALCULATOR BOT (Polling Mode)');
        $this->line('===========================================');
        $this->info('Mode: Long Polling');
        $this->line('Press Ctrl+C to stop');
        $this->newLine();
        
        $offset = 0;
        
        while (true) {
            try {
                // Get updates with timeout
                $url = $this->botApiUrl . "getUpdates?offset={$offset}&timeout=30";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 35);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);
                
                if ($error) {
                    $this->warn("CURL Error: {$error}");
                    sleep(5);
                    continue;
                }
                
                if ($response) {
                    $data = json_decode($response, true);
                    
                    if (isset($data['ok']) && $data['ok'] && !empty($data['result'])) {
                        foreach ($data['result'] as $update) {
                            $offset = $update['update_id'] + 1;
                            
                            if (isset($update['message'])) {
                                $this->processMessage($update['message']);
                            }
                        }
                    }
                }
                
                usleep(500000); // 0.5 second delay
                
            } catch (\Exception $e) {
                $this->warn('Error: ' . $e->getMessage());
                $this->logMessage("Polling error: " . $e->getMessage());
                sleep(5);
            }
        }
        
        return 0;
    }
    
    /**
     * Process incoming message
     */
    private function processMessage($message)
    {
        if (!isset($message['chat']['id']) || !isset($message['from']['id'])) {
            return;
        }
        
        $chatId = $message['chat']['id'];
        $userId = $message['from']['id'];
        $name = $message['from']['first_name'] ?? 'User';
        $text = isset($message['text']) ? trim($message['text']) : '';
        
        $this->info("[{$name}]: {$text}");
        $this->logMessage("From {$name} ({$chatId}): {$text}");
        
        // Get or initialize session
        $session = $this->getSession($chatId);
        
        // Handle commands (start with /)
        if (strpos($text, '/') === 0) {
            $this->handleCommand($chatId, $text, $name, $session);
            return;
        }
        
        // Handle regular message flow
        $this->processFlow($chatId, $text, $session);
    }
    
    /**
     * Handle commands
     */
    private function handleCommand($chatId, $text, $name, &$session)
    {
        switch ($text) {
            case '/start':
                $session = $this->initSession();
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "👋 <b>Assalamualaikum {$name}!</b>\n\n" .
                    "Welcome to <b>Neo Faraid Calculator 🤖</b>\n" .
                    "Islamic Inheritance Calculation System\n\n" .
                    "📋 <b>Available Commands:</b>\n" .
                    "• /calculate – Start new calculation\n" .
                    "• /help – View instructions\n" .
                    "• /about – About this bot\n" .
                    "• /cancel – Cancel current process\n\n" .
                    "📊 Click /calculate to begin."
                );
                break;
                
            case '/help':
                $this->sendMessage($chatId,
                    "📘 <b>HOW TO USE NEO FARAID CALCULATOR</b>\n\n" .
                    "1️⃣ <b>Start Calculation</b>\n" .
                    "   Send /calculate to begin\n\n" .
                    "2️⃣ <b>Enter Deceased Details</b>\n" .
                    "   • Name\n" .
                    "   • Date of Death (DD-MM-YYYY)\n" .
                    "   • Gender\n" .
                    "   • Marital Status\n\n" .
                    "3️⃣ <b>Select Heirs</b>\n" .
                    "   • Choose from categories:\n" .
                    "     - Spouse\n" .
                    "     - Children\n" .
                    "     - Parents\n" .
                    "     - Grandparents\n" .
                    "     - Siblings\n\n" .
                    "4️⃣ <b>Add Assets</b>\n" .
                    "   • Asset type\n" .
                    "   • Description\n" .
                    "   • Value (RM)\n\n" .
                    "5️⃣ <b>View Results</b>\n" .
                    "   • Family tree\n" .
                    "   • Inheritance distribution\n" .
                    "   • Quranic references\n\n" .
                    "💡 <b>Tip:</b> Use /cancel anytime to restart."
                );
                break;
                
            case '/about':
                $this->sendMessage($chatId,
                    "🤖 <b>NEO FARAID CALCULATOR</b>\n\n" .
                    "Developer: Afieda Halim\n" .
                    "Platform: Telegram Bot\n" .
                    "Purpose: Islamic Inheritance Calculator\n\n" .
                    "✨ <b>Features:</b>\n" .
                    "• Complete Faraid calculation\n" .
                    "• 14 inheritance scenarios\n" .
                    "• Mahjub (Blocking) rules\n" .
                    "• Awl (Over-subscription)\n" .
                    "• Multiple wives support\n" .
                    "• Family tree display\n" .
                    "• Asset management\n" .
                    "📚 Based on Quranic verses 4:11-12"
                );
                break;
                
            case '/cancel':
                $this->clearSession($chatId);
                $this->sendMessage($chatId, 
                    "✅ <b>Process cancelled.</b>\n\n" .
                    "You can start a new calculation with /calculate",
                    $this->removeKeyboard()
                );
                break;
                
            case '/calculate':
                $session = $this->initSession();
                $session['step'] = 'name';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, "👤 <b>Step 1 of 5:</b> Enter deceased name:");
                break;
                
            default:
                $this->sendMessage($chatId, 
                    "❓ <b>Unknown command.</b>\n" .
                    "Type /help for available commands."
                );
        }
    }
    
    /**
     * Main flow processor
     */
    private function processFlow($chatId, $text, &$session)
    {
        $step = $session['step'] ?? 'idle';
        
        switch ($step) {
            case 'idle':
                // Do nothing, wait for command
                break;
                
            case 'name':
                $session['data']['name'] = htmlspecialchars(trim($text));
                $session['step'] = 'date';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, 
                    "📅 <b>Step 2 of 5:</b> Enter date of death\n" .
                    "Format: <code>DD-MM-YYYY</code>\n" .
                    "Example: 15-01-2024"
                );
                break;
                
            case 'date':
                if (!$this->validateDateDDMMYYYY($text)) {
                    $this->sendMessage($chatId,
                        "❌ <b>Invalid date format!</b>\n\n" .
                        "Please use: <code>DD-MM-YYYY</code>\n" .
                        "Example: 15-01-2024\n\n" .
                        "Make sure:\n" .
                        "• Day: 01-31\n" .
                        "• Month: 01-12\n" .
                        "• Year: 1900-" . date('Y')
                    );
                    return;
                }
                $session['data']['date'] = $text;
                $session['step'] = 'gender';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "⚧ <b>Step 3 of 5:</b> Select gender",
                    $this->keyboard([['Male', 'Female']])
                );
                break;
                
            case 'gender':
                $gender = strtolower(trim($text));
                if (!in_array($gender, ['male', 'female'])) {
                    $this->sendMessage($chatId, 
                        "❌ Please select either <b>Male</b> or <b>Female</b>",
                        $this->keyboard([['Male', 'Female']])
                    );
                    return;
                }
                $session['data']['gender'] = $gender;
                $session['step'] = 'marital';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "💍 <b>Step 4 of 5:</b> Select marital status",
                    $this->keyboard([
                        ['Single', 'Married'],
                        ['Divorced', 'Widowed']
                    ])
                );
                break;
                
            case 'marital':
                $marital = strtolower(trim($text));
                $validStatus = ['single', 'married', 'divorced', 'widowed'];
                
                if (!in_array($marital, $validStatus)) {
                    $this->sendMessage($chatId,
                        "❌ Please select a valid marital status",
                        $this->keyboard([
                            ['Single', 'Married'],
                            ['Divorced', 'Widowed']
                        ])
                    );
                    return;
                }
                
                $session['data']['marital'] = $marital;
                $session['step'] = 'heir_category';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "👨‍👩‍👧 <b>Step 5 of 5:</b> Select heir category",
                    $this->heirCategoryMenu($session['data']['gender'])
                );
                break;
                
            case 'heir_category':
                if ($text === '✅ Done Selecting Heirs') {
                    $hasHeirs = false;
                    foreach ($session['data']['heirs'] as $count) {
                        if ($count > 0) {
                            $hasHeirs = true;
                            break;
                        }
                    }
                    
                    if (!$hasHeirs) {
                        $this->sendMessage($chatId,
                            "⚠️ <b>No heirs selected!</b>\n\n" .
                            "Please select at least one heir before continuing.\n" .
                            "If no heirs exist, the estate goes to Baitulmal.",
                            $this->heirCategoryMenu($session['data']['gender'])
                        );
                        return;
                    }
                    
                    $session['step'] = 'asset_type';
                    $this->saveSession($chatId, $session);
                    $this->sendMessage($chatId,
                        "📦 <b>ASSET ENTRY</b>\n\n" .
                        "Enter asset type:\n" .
                        "(e.g., House, Cash, Land, Gold, Investments)"
                    );
                    return;
                }
                
                $session['data']['current_category'] = $text;
                $session['step'] = 'heir_selection';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "👥 Select heirs from <b>{$text}</b>:",
                    $this->heirSelectionMenu($text, $session['data']['gender'])
                );
                break;
                
            case 'heir_selection':
                if ($text === '⬅ Back to Categories') {
                    $session['step'] = 'heir_category';
                    $this->saveSession($chatId, $session);
                    $this->sendMessage($chatId,
                        "👨‍👩‍👧 Select heir category:",
                        $this->heirCategoryMenu($session['data']['gender'])
                    );
                    return;
                }
                
                $session['data']['current_heir'] = $text;
                $session['step'] = 'heir_count';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "🔢 Enter number of <b>{$text}</b>:",
                    $this->heirCountKeyboard($text)
                );
                break;
                
            case 'heir_count':
                $heir = $session['data']['current_heir'];
                $count = intval(trim($text));
                
                if ($count < 0) {
                    $this->sendMessage($chatId,
                        "❌ Number cannot be negative.\n" .
                        "Please enter 0 or more.",
                        $this->heirCountKeyboard($heir)
                    );
                    return;
                }
                
                // Special limits
                if ($heir === 'Husband' && $count > 1) {
                    $this->sendMessage($chatId,
                        "❌ Maximum 1 husband allowed.\n" .
                        "Please enter 0 or 1.",
                        $this->keyboard([['0', '1']])
                    );
                    return;
                }
                
                if ($heir === 'Wife' && $count > 4) {
                    $this->sendMessage($chatId,
                        "❌ Maximum 4 wives allowed in Islam.\n" .
                        "Please enter 0-4.",
                        $this->keyboard([['0', '1', '2', '3', '4']])
                    );
                    return;
                }
                
                $key = $this->heirKey($heir);
                $session['data']['heirs'][$key] = $count;
                
                $session['step'] = 'heir_category';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "✅ <b>{$heir}</b> saved: <b>{$count}</b>\n\n" .
                    "Select more heirs or click 'Done':",
                    $this->heirCategoryMenu($session['data']['gender'])
                );
                break;
                
            case 'asset_type':
                $session['data']['current_asset']['type'] = htmlspecialchars(trim($text));
                $session['step'] = 'asset_desc';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, "📝 Enter asset description:");
                break;
                
            case 'asset_desc':
                $session['data']['current_asset']['desc'] = htmlspecialchars(trim($text));
                $session['step'] = 'asset_value';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, "💰 Enter asset value in RM:");
                break;
                
            case 'asset_value':
                $value = str_replace([',', ' '], '', trim($text));
                
                if (!is_numeric($value) || $value <= 0) {
                    $this->sendMessage($chatId,
                        "❌ Invalid amount!\n" .
                        "Please enter a valid number greater than 0.\n" .
                        "Example: 500000 or 500,000"
                    );
                    return;
                }
                
                $session['data']['current_asset']['value'] = floatval($value);
                $session['data']['assets'][] = $session['data']['current_asset'];
                $session['data']['current_asset'] = [];
                
                $session['step'] = 'asset_menu';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "✅ Asset added successfully!\n\n" .
                    "What would you like to do next?",
                    $this->keyboard([
                        ['➕ Add Another Asset'],
                        ['📊 View Asset Summary'],
                        ['📜 Calculate Inheritance']
                    ])
                );
                break;
                
            case 'asset_menu':
                switch ($text) {
                    case '➕ Add Another Asset':
                        $session['step'] = 'asset_type';
                        $this->saveSession($chatId, $session);
                        $this->sendMessage($chatId,
                            "📦 Enter next asset type:\n" .
                            "(e.g., House, Cash, Land, Gold)"
                        );
                        break;
                        
                    case '📊 View Asset Summary':
                        $this->showAssetSummary($chatId, $session);
                        break;
                        
                    case '📜 Calculate Inheritance':
                        $session['telegram_user'] = [
                            'id' => $chatId,
                            'first_name' => $session['data']['name'] ?? 'User',
                            'last_name' => '',
                            'username' => '',
                            'chat_id' => $chatId
                        ];
                        
                        // Calculate and show results
                        $this->showResult($chatId, $session['data'], $session['telegram_user']);
                        
                        // Clear session after calculation
                        $this->clearSession($chatId);
                        break;
                        
                    default:
                        $this->sendMessage($chatId,
                            "Please select an option:",
                            $this->keyboard([
                                ['➕ Add Another Asset'],
                                ['📊 View Asset Summary'],
                                ['📜 Calculate Inheritance']
                            ])
                        );
                }
                break;
                
            default:
                $session['step'] = 'idle';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, "Something went wrong. Please start over with /calculate");
        }
    }
    
    /**
     * Send message to Telegram
     */
    private function sendMessage($chatId, $text, $keyboard = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true
        ];
        
        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }
        
        $url = $this->botApiUrl . 'sendMessage?' . http_build_query($data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            $this->logMessage("CURL Error sending to {$chatId}: {$error}");
        }
        
        $this->line("  ↳ Replied to chat {$chatId}");
        
        return $result;
    }
    
    /**
     * Create keyboard
     */
    private function keyboard($buttons, $one_time = true)
    {
        return [
            'keyboard' => $buttons,
            'resize_keyboard' => true,
            'one_time_keyboard' => $one_time
        ];
    }
    
    /**
     * Remove keyboard
     */
    private function removeKeyboard()
    {
        return [
            'remove_keyboard' => true
        ];
    }
    
    /**
     * Initialize session
     */
    private function initSession()
    {
        return [
            'step' => 'idle',
            'data' => [
                'name' => '',
                'date' => '',
                'gender' => '',
                'marital' => '',
                'assets' => [],
                'current_asset' => [],
                'heirs' => [
                    'husband' => 0,
                    'wife' => 0,
                    'son' => 0,
                    'daughter' => 0,
                    'father' => 0,
                    'mother' => 0,
                    'grandfather' => 0,
                    'grandmother_father' => 0,
                    'grandmother_mother' => 0,
                    'brother' => 0,
                    'sister' => 0,
                    'half_brother_paternal' => 0,
                    'half_brother_maternal' => 0,
                    'half_sister_paternal' => 0,
                    'half_sister_maternal' => 0,
                    'paternal_uncle' => 0,
                    'male_cousin' => 0
                ],
                'current_heir' => '',
                'current_category' => ''
            ],
            'telegram_user' => []
        ];
    }
    
    /**
     * Get session
     */
    private function getSession($chatId)
    {
        if (!file_exists($this->sessionFile)) {
            file_put_contents($this->sessionFile, json_encode([]));
            return $this->initSession();
        }
        
        $sessions = json_decode(file_get_contents($this->sessionFile), true) ?: [];
        
        if (!isset($sessions[$chatId])) {
            return $this->initSession();
        }
        
        // Check if session is expired (1 hour)
        if (isset($sessions[$chatId]['timestamp']) && 
            (time() - $sessions[$chatId]['timestamp']) > 3600) {
            unset($sessions[$chatId]);
            file_put_contents($this->sessionFile, json_encode($sessions));
            return $this->initSession();
        }
        
        return $sessions[$chatId];
    }
    
    /**
     * Save session
     */
    private function saveSession($chatId, $session)
    {
        $sessions = [];
        if (file_exists($this->sessionFile)) {
            $sessions = json_decode(file_get_contents($this->sessionFile), true) ?: [];
        }
        
        $session['timestamp'] = time();
        $sessions[$chatId] = $session;
        
        file_put_contents($this->sessionFile, json_encode($sessions));
    }
    
    /**
     * Clear session
     */
    private function clearSession($chatId)
    {
        if (file_exists($this->sessionFile)) {
            $sessions = json_decode(file_get_contents($this->sessionFile), true) ?: [];
            unset($sessions[$chatId]);
            file_put_contents($this->sessionFile, json_encode($sessions));
        }
    }
    
    /**
     * Validate date DD-MM-YYYY
     */
    private function validateDateDDMMYYYY($date)
    {
        $pattern = '/^(\d{2})-(\d{2})-(\d{4})$/';
        if (!preg_match($pattern, $date, $matches)) {
            return false;
        }
        
        $day = intval($matches[1]);
        $month = intval($matches[2]);
        $year = intval($matches[3]);
        
        if ($day < 1 || $day > 31) return false;
        if ($month < 1 || $month > 12) return false;
        if ($year < 1900 || $year > date('Y')) return false;
        
        return checkdate($month, $day, $year);
    }
    
    /**
     * Format date for display
     */
    private function formatDateDisplay($date)
    {
        if ($this->validateDateDDMMYYYY($date)) {
            $dateObj = \DateTime::createFromFormat('d-m-Y', $date);
            return $dateObj->format('j F Y');
        }
        return $date;
    }
    
    /**
     * Heir category menu
     */
    private function heirCategoryMenu($gender)
    {
        $buttons = [];
        
        if ($gender === 'female') {
            $buttons[] = ['🤵 Husband'];
        } elseif ($gender === 'male') {
            $buttons[] = ['👰 Wife'];
        }
        
        $buttons[] = ['👨‍👩‍👧 Parents'];
        $buttons[] = ['👦👧 Children'];
        $buttons[] = ['👴👵 Grandparents'];
        $buttons[] = ['👥 Siblings'];
        $buttons[] = ['✅ Done Selecting Heirs'];
        
        return $this->keyboard($buttons);
    }
    
    /**
     * Heir selection menu
     */
    private function heirSelectionMenu($category, $gender)
    {
        $buttons = [];
        
        switch ($category) {
            case '🤵 Husband':
                $buttons = [['Husband']];
                break;
                
            case '👰 Wife':
                $buttons = [['Wife']];
                break;
                
            case '👨‍👩‍👧 Parents':
                $buttons = [
                    ['Father', 'Mother']
                ];
                break;
                
            case '👦👧 Children':
                $buttons = [
                    ['Son', 'Daughter']
                ];
                break;
                
            case '👴👵 Grandparents':
                $buttons = [
                    ['Grandfather'],
                    ['Grandmother (Father Side)', 'Grandmother (Mother Side)']
                ];
                break;
                
            case '👥 Siblings':
                $buttons = [
                    ['Brother', 'Sister'],
                    ['Half-Brother (Paternal)', 'Half-Brother (Maternal)'],
                    ['Half-Sister (Paternal)', 'Half-Sister (Maternal)'],
                    ['Paternal Uncle', 'Male Cousin']
                ];
                break;
        }
        
        $buttons[] = ['⬅ Back to Categories'];
        return $this->keyboard($buttons);
    }
    
    /**
     * Heir count keyboard
     */
    private function heirCountKeyboard($heir)
    {
        switch ($heir) {
            case 'Husband':
                return $this->keyboard([['0', '1']]);
                
            case 'Wife':
                return $this->keyboard([['0', '1', '2', '3', '4']]);
                
            case 'Father':
            case 'Mother':
            case 'Grandfather':
            case 'Grandmother (Father Side)':
            case 'Grandmother (Mother Side)':
                return $this->keyboard([['0', '1']]);
                
            default:
                return $this->keyboard([['0', '1', '2', '3', '4', '5']]);
        }
    }
    
    /**
     * Get heir key
     */
    private function heirKey($displayName)
    {
        $map = [
            'Husband' => 'husband',
            'Wife' => 'wife',
            'Son' => 'son',
            'Daughter' => 'daughter',
            'Father' => 'father',
            'Mother' => 'mother',
            'Grandfather' => 'grandfather',
            'Grandmother (Father Side)' => 'grandmother_father',
            'Grandmother (Mother Side)' => 'grandmother_mother',
            'Brother' => 'brother',
            'Sister' => 'sister',
            'Half-Brother (Paternal)' => 'half_brother_paternal',
            'Half-Brother (Maternal)' => 'half_brother_maternal',
            'Half-Sister (Paternal)' => 'half_sister_paternal',
            'Half-Sister (Maternal)' => 'half_sister_maternal',
            'Paternal Uncle' => 'paternal_uncle',
            'Male Cousin' => 'male_cousin'
        ];
        
        return $map[$displayName] ?? strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $displayName));
    }
    
    /**
     * Show asset summary
     */
    private function showAssetSummary($chatId, $session)
    {
        $assets = $session['data']['assets'];
        
        if (empty($assets)) {
            $this->sendMessage($chatId,
                "📊 <b>ASSET SUMMARY</b>\n\n" .
                "No assets added yet.\n\n" .
                "Add your first asset to continue.",
                $this->keyboard([
                    ['➕ Add Another Asset'],
                    ['📜 Calculate Inheritance']
                ])
            );
            return;
        }
        
        $message = "📊 <b>ASSET SUMMARY</b>\n\n";
        $total = 0;
        
        foreach ($assets as $index => $asset) {
            $message .= "<b>" . ($index + 1) . ". {$asset['type']}</b>\n";
            $message .= "{$asset['desc']}\n";
            $message .= "<code>RM " . number_format($asset['value'], 2) . "</code>\n\n";
            $total += $asset['value'];
        }
        
        $message .= str_repeat("─", 30) . "\n";
        $message .= "<b>Total Estate Value:</b>\n";
        $message .= "<code>RM " . number_format($total, 2) . "</code>\n\n";
        
        $this->sendMessage($chatId, $message,
            $this->keyboard([
                ['➕ Add Another Asset'],
                ['📜 Calculate Inheritance']
            ])
        );
    }
    
    /**
     * Show calculation results
     */
    private function showResult($chatId, $data, $telegramUser = [])
    {
        // Calculate total estate
        $totalEstate = 0;
        foreach ($data['assets'] as $asset) {
            $totalEstate += $asset['value'];
        }
        
        if ($totalEstate <= 0) {
            $this->sendMessage($chatId,
                "⚠️ <b>No Assets Found!</b>\n\n" .
                "Total estate value is RM 0.00\n\n" .
                "Please add assets to calculate inheritance distribution.",
                $this->removeKeyboard()
            );
            return;
        }
        
        $this->sendMessage($chatId, "🧮 Calculating inheritance distribution...");
        
        // Apply Mahjub rules
        $heirs = $this->applyMahjub($data['heirs']);
        
        // Calculate shares
        $distribution = $this->calculateInheritance($heirs, $totalEstate, $data['gender']);
        
        // Generate result message
        $message = "📜 <b>FARAID INHERITANCE RESULTS</b>\n\n";
        $message .= "👤 <b>Deceased:</b> {$data['name']}\n";
        $message .= "📅 " . $this->formatDateDisplay($data['date']) . "\n";
        $message .= "⚧ " . ucfirst($data['gender']) . " | 💍 " . ucfirst($data['marital']) . "\n";
        $message .= "💰 <b>Total Estate:</b> RM " . number_format($totalEstate, 2) . "\n\n";
        
        $message .= "👨‍👩‍👧 <b>FAMILY STRUCTURE</b>\n";
        $message .= $this->generateFamilyTree($data) . "\n";
        
        $message .= "📊 <b>INHERITANCE DISTRIBUTION</b>\n\n";
        
        $totalPercentage = 0;
        $totalAmount = 0;
        $distributionData = [];
        
        foreach ($distribution as $item) {
            $message .= $item['display'] . "\n";
            $totalPercentage += $item['percentage'];
            $totalAmount += $item['amount'];
            
            // Prepare data for database
            $distributionData[] = [
                'heir' => $item['heir'],
                'relationship' => $item['relationship'],
                'share' => $item['share'],
                'fraction' => $item['fraction'],
                'amount' => $item['amount'],
                'percentage' => $item['percentage'],
                'status' => $item['status'],
                'type' => $item['type'] ?? 'Unknown'
            ];
        }
        
        $message .= "<b>TOTALS</b>\n";
        $message .= "Percentage: " . round($totalPercentage, 2) . "%\n";
        $message .= "Amount: RM " . number_format($totalAmount, 2) . "\n\n";
        
        // Add Quranic reference
        $message .= "📖 <i>Quran 4:11-12</i>\n";
        $message .= "<i>\"Allah instructs you concerning your children...\"</i>\n\n";
        
        // Send results
        $this->sendMessage($chatId, $message);
        
        // Save to database
        $this->sendMessage($chatId, "💾 Saving calculation to database...");
        
        $calculationId = $this->saveCalculationToDatabase($data, $telegramUser, $totalEstate, $distributionData, $chatId);
        
        if ($calculationId) {
            $this->sendMessage($chatId,
                "✅ Calculation saved successfully!\n" .
                "📋 ID: #{$calculationId}\n" .
                "🤖 Method: telegram_bot_polling\n\n" .
                "Type /calculate to start a new calculation.",
                $this->removeKeyboard()
            );
        } else {
            $this->sendMessage($chatId, 
                "⚠️ <b>Calculation complete but save failed.</b>\n\n" .
                "You can start a new calculation with /calculate",
                $this->removeKeyboard()
            );
        }
    }
    
    /**
     * Apply Mahjub (blocking) rules
     */
    private function applyMahjub($heirs)
    {
        $blockedHeirs = $heirs;
        
        if ($blockedHeirs['son'] > 0) {
            $blockedHeirs['brother'] = 0;
            $blockedHeirs['sister'] = 0;
            $blockedHeirs['half_brother_paternal'] = 0;
            $blockedHeirs['half_brother_maternal'] = 0;
            $blockedHeirs['half_sister_paternal'] = 0;
            $blockedHeirs['half_sister_maternal'] = 0;
            $blockedHeirs['grandfather'] = 0;
            $blockedHeirs['grandmother_father'] = 0;
            $blockedHeirs['grandmother_mother'] = 0;
            $blockedHeirs['paternal_uncle'] = 0;
            $blockedHeirs['male_cousin'] = 0;
        }
        
        if ($blockedHeirs['father'] > 0) {
            $blockedHeirs['brother'] = 0;
            $blockedHeirs['sister'] = 0;
            $blockedHeirs['half_brother_paternal'] = 0;
            $blockedHeirs['half_sister_paternal'] = 0;
            $blockedHeirs['grandfather'] = 0;
        }
        
        if ($blockedHeirs['grandfather'] > 0) {
            $blockedHeirs['paternal_uncle'] = 0;
        }
        
        return $blockedHeirs;
    }
    
    /**
     * Calculate inheritance distribution
     */
    private function calculateInheritance($heirs, $estate, $gender)
    {
        $distribution = [];
        $totalShares = 0;
        
        $addDistribution = function($heirName, $share, $status, $type = 'Unknown') use (&$distribution, $estate) {
            $percentage = $share * 100;
            $amount = $estate * $share;
            
            $relationship = 'Unknown';
            if (strpos($heirName, 'Husband') !== false || strpos($heirName, 'Wife') !== false) {
                $relationship = 'Spouse';
            } elseif (strpos($heirName, 'Father') !== false || strpos($heirName, 'Mother') !== false) {
                $relationship = 'Parent';
            } elseif (strpos($heirName, 'Son') !== false || strpos($heirName, 'Daughter') !== false) {
                $relationship = 'Child';
            } elseif (strpos($heirName, 'Brother') !== false || strpos($heirName, 'Sister') !== false) {
                $relationship = 'Sibling';
            } elseif (strpos($heirName, 'Baitulmal') !== false) {
                $relationship = 'State Treasury';
            }
            
            $distribution[] = [
                'heir' => $heirName,
                'relationship' => $relationship,
                'share' => $this->formatFraction($share),
                'fraction' => $share,
                'amount' => $amount,
                'percentage' => $percentage,
                'status' => $status,
                'type' => $type,
                'display' => "👥 <b>{$heirName}</b> ({$relationship})\n" .
                            "Share: " . $this->formatFraction($share) . " (" . round($percentage, 2) . "%)\n" .
                            "Amount: RM " . number_format($amount, 2) . "\n" .
                            "Status: {$status}\n" .
                            str_repeat("─", 30)
            ];
        };
        
        // SPOUSE SHARES
        if ($heirs['husband'] > 0) {
            if ($this->hasChildren($heirs)) {
                $share = 1/4;
            } else {
                $share = 1/2;
            }
            $addDistribution('Husband', $share, 'Fixed Share', 'Fixed Share');
            $totalShares += $share;
        }
        
        if ($heirs['wife'] > 0) {
            if ($this->hasChildren($heirs)) {
                $share = 1/8;
            } else {
                $share = 1/4;
            }
            
            $sharePerWife = $share / $heirs['wife'];
            if ($heirs['wife'] == 1) {
                $addDistribution('Wife', $share, 'Fixed Share', 'Fixed Share');
            } else {
                for ($i = 1; $i <= $heirs['wife']; $i++) {
                    $addDistribution("Wife {$i}", $sharePerWife, 'Fixed Share', 'Fixed Share');
                }
            }
            $totalShares += $share;
        }
        
        // PARENTS SHARES
        if ($heirs['mother'] > 0) {
            if ($this->hasChildren($heirs)) {
                $share = 1/6;
            } else {
                $share = 1/3;
            }
            $addDistribution('Mother', $share, 'Fixed Share', 'Fixed Share');
            $totalShares += $share;
        }
        
        if ($heirs['father'] > 0) {
            if ($this->hasChildren($heirs)) {
                $share = 1/6;
            } else {
                $share = 1 - $totalShares;
            }
            if ($share > 0) {
                $addDistribution('Father', $share, 'Fixed Share', 'Fixed Share');
                $totalShares += $share;
            }
        }
        
        // CHILDREN SHARES
        if ($this->hasChildren($heirs)) {
            $remaining = 1 - $totalShares;
            
            if ($remaining > 0) {
                $sonCount = $heirs['son'];
                $daughterCount = $heirs['daughter'];
                
                if ($sonCount > 0 && $daughterCount == 0) {
                    $sharePerSon = $remaining / $sonCount;
                    if ($sonCount == 1) {
                        $addDistribution('Son', $sharePerSon, 'Residuary', 'Asabah');
                    } else {
                        for ($i = 1; $i <= $sonCount; $i++) {
                            $addDistribution("Son {$i}", $sharePerSon, 'Residuary', 'Asabah');
                        }
                    }
                } elseif ($daughterCount > 0 && $sonCount == 0) {
                    if ($daughterCount == 1) {
                        $addDistribution('Daughter', 1/2, 'Fixed Share', 'Fixed Share');
                        if ($remaining > 1/2) {
                            $addDistribution('Baitulmal', $remaining - 1/2, 'State Treasury', 'Baitulmal');
                        }
                    } else {
                        $share = 2/3 / $daughterCount;
                        for ($i = 1; $i <= $daughterCount; $i++) {
                            $addDistribution("Daughter {$i}", $share, 'Fixed Share', 'Fixed Share');
                        }
                        $remainingAfterDaughters = $remaining - (2/3);
                        if ($remainingAfterDaughters > 0) {
                            $addDistribution('Baitulmal', $remainingAfterDaughters, 'State Treasury', 'Baitulmal');
                        }
                    }
                } elseif ($sonCount > 0 && $daughterCount > 0) {
                    $totalUnits = ($sonCount * 2) + $daughterCount;
                    $unitValue = $remaining / $totalUnits;
                    
                    for ($i = 1; $i <= $sonCount; $i++) {
                        $addDistribution("Son {$i}", $unitValue * 2, 'Residuary', 'Asabah');
                    }
                    
                    for ($i = 1; $i <= $daughterCount; $i++) {
                        $addDistribution("Daughter {$i}", $unitValue, 'Residuary', 'Asabah');
                    }
                }
                $totalShares = 1;
            }
        }
        
        // IF NO CHILDREN
        if (!$this->hasChildren($heirs) && $totalShares < 1) {
            $remaining = 1 - $totalShares;
            
            $hasSiblings = $heirs['brother'] > 0 || $heirs['sister'] > 0 || 
                          $heirs['half_brother_paternal'] > 0 || $heirs['half_brother_maternal'] > 0 ||
                          $heirs['half_sister_paternal'] > 0 || $heirs['half_sister_maternal'] > 0;
            
            if ($hasSiblings && $remaining > 0) {
                if ($heirs['brother'] > 0 || $heirs['sister'] > 0) {
                    $brotherCount = $heirs['brother'];
                    $sisterCount = $heirs['sister'];
                    $totalUnits = ($brotherCount * 2) + $sisterCount;
                    $unitValue = $remaining / $totalUnits;
                    
                    for ($i = 1; $i <= $brotherCount; $i++) {
                        $addDistribution("Brother {$i}", $unitValue * 2, 'Residuary', 'Asabah');
                    }
                    
                    for ($i = 1; $i <= $sisterCount; $i++) {
                        $addDistribution("Sister {$i}", $unitValue, 'Residuary', 'Asabah');
                    }
                    $remaining = 0;
                }
            }
            
            if ($remaining > 0) {
                $addDistribution('Baitulmal', $remaining, 'State Treasury', 'Baitulmal');
            }
        }
        
        // IF NO HEIRS AT ALL
        if ($totalShares == 0) {
            $addDistribution('Baitulmal', 1, 'State Treasury (No Heirs)', 'Baitulmal');
        }
        
        return $distribution;
    }
    
    /**
     * Check if deceased has children
     */
    private function hasChildren($heirs)
    {
        return ($heirs['son'] > 0 || $heirs['daughter'] > 0);
    }
    
    /**
     * Format fraction
     */
    private function formatFraction($decimal)
    {
        $fractions = [
            1 => '1',
            1/2 => '½',
            1/3 => '⅓',
            2/3 => '⅔',
            1/4 => '¼',
            3/4 => '¾',
            1/6 => '⅙',
            5/6 => '⅚',
            1/8 => '⅛',
            3/8 => '⅜',
            5/8 => '⅝',
            7/8 => '⅞'
        ];
        
        foreach ($fractions as $dec => $frac) {
            if (abs($decimal - $dec) < 0.0001) {
                return $frac;
            }
        }
        
        for ($denominator = 2; $denominator <= 100; $denominator++) {
            $numerator = round($decimal * $denominator);
            if (abs($decimal - ($numerator / $denominator)) < 0.0001) {
                return "{$numerator}/{$denominator}";
            }
        }
        
        return round($decimal, 4);
    }
    
    /**
     * Generate family tree text
     */
    private function generateFamilyTree($data)
    {
        $heirs = $data['heirs'];
        $tree = "";
        
        // Spouse
        $spouseCount = $heirs['husband'] + $heirs['wife'];
        if ($spouseCount > 0) {
            $tree .= "<b>SPOUSE</b>\n";
            if ($heirs['husband'] > 0) $tree .= "├─ 🤵 Husband ({$heirs['husband']})\n";
            if ($heirs['wife'] > 0) $tree .= "├─ 👰 Wife" . ($heirs['wife'] > 1 ? "s" : "") . " ({$heirs['wife']})\n";
            $tree .= "\n";
        }
        
        // Children
        $childrenCount = $heirs['son'] + $heirs['daughter'];
        if ($childrenCount > 0) {
            $tree .= "<b>CHILDREN</b>\n";
            if ($heirs['son'] > 0) $tree .= "├─ 👦 Son" . ($heirs['son'] > 1 ? "s" : "") . " ({$heirs['son']})\n";
            if ($heirs['daughter'] > 0) $tree .= "├─ 👧 Daughter" . ($heirs['daughter'] > 1 ? "s" : "") . " ({$heirs['daughter']})\n";
            $tree .= "\n";
        }
        
        // Parents
        $parentsCount = $heirs['father'] + $heirs['mother'];
        if ($parentsCount > 0) {
            $tree .= "<b>PARENTS</b>\n";
            if ($heirs['father'] > 0) $tree .= "├─ 👴 Father ({$heirs['father']})\n";
            if ($heirs['mother'] > 0) $tree .= "├─ 👵 Mother ({$heirs['mother']})\n";
            $tree .= "\n";
        }
        
        // Grandparents
        $grandparentsCount = $heirs['grandfather'] + $heirs['grandmother_father'] + $heirs['grandmother_mother'];
        if ($grandparentsCount > 0) {
            $tree .= "<b>GRANDPARENTS</b>\n";
            if ($heirs['grandfather'] > 0) $tree .= "├─ 👴 Grandfather ({$heirs['grandfather']})\n";
            if ($heirs['grandmother_father'] > 0) $tree .= "├─ 👵 Grandmother (Father) ({$heirs['grandmother_father']})\n";
            if ($heirs['grandmother_mother'] > 0) $tree .= "├─ 👵 Grandmother (Mother) ({$heirs['grandmother_mother']})\n";
            $tree .= "\n";
        }
        
        // Siblings
        $siblingsCount = $heirs['brother'] + $heirs['sister'] + 
                        $heirs['half_brother_paternal'] + $heirs['half_brother_maternal'] + 
                        $heirs['half_sister_paternal'] + $heirs['half_sister_maternal'];
        
        if ($siblingsCount > 0) {
            $tree .= "<b>SIBLINGS</b>\n";
            if ($heirs['brother'] > 0) $tree .= "├─ 👦 Brother" . ($heirs['brother'] > 1 ? "s" : "") . " ({$heirs['brother']})\n";
            if ($heirs['sister'] > 0) $tree .= "├─ 👧 Sister" . ($heirs['sister'] > 1 ? "s" : "") . " ({$heirs['sister']})\n";
            if ($heirs['half_brother_paternal'] > 0) $tree .= "├─ 👦 Half-Brother (Paternal) ({$heirs['half_brother_paternal']})\n";
            if ($heirs['half_brother_maternal'] > 0) $tree .= "├─ 👦 Half-Brother (Maternal) ({$heirs['half_brother_maternal']})\n";
            if ($heirs['half_sister_paternal'] > 0) $tree .= "├─ 👧 Half-Sister (Paternal) ({$heirs['half_sister_paternal']})\n";
            if ($heirs['half_sister_maternal'] > 0) $tree .= "├─ 👧 Half-Sister (Maternal) ({$heirs['half_sister_maternal']})\n";
            $tree .= "\n";
        }
        
        return $tree;
    }
    
    /**
     * Save calculation to database using Laravel DB facade
     */
    private function saveCalculationToDatabase($data, $telegramUser, $totalEstate, $distributionData, $chatId)
    {
        try {
            // Convert date
            $dateObj = \DateTime::createFromFormat('d-m-Y', $data['date']);
            $dateOfDeath = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');
            
            $heirs = $data['heirs'];
            $assets = $data['assets'];
            
            $calculationHash = 'CALC_TELEGRAM_POLL_' . time() . '_' . bin2hex(random_bytes(8));
            
            // Count total heirs
            $totalHeirs = 0;
            foreach ($heirs as $count) {
                $totalHeirs += $count;
            }
            
            // Count eligible heirs
            $eligibleHeirs = 0;
            foreach ($distributionData as $item) {
                if ($item['amount'] > 0 && stripos($item['heir'], 'Baitulmal') === false) {
                    $eligibleHeirs++;
                }
            }
            
            // Determine scenario
            $scenarioNumber = $this->determineScenario($heirs);
            $scenarioDescription = $this->getScenarioDescription($scenarioNumber);
            
            // Insert using Laravel DB facade
            $calculationId = DB::table('calculations')->insertGetId([
                'deceased_name' => $data['name'],
                'deceased_gender' => $data['gender'],
                'date_of_death' => $dateOfDeath,
                'marital_status' => in_array($data['marital'], ['single', 'married']) ? $data['marital'] : 'single',
                'wife_count' => $heirs['wife'] ?? 0,
                'husband_count' => $heirs['husband'] ?? 0,
                'father_status' => ($heirs['father'] ?? 0) > 0 ? 'alive' : 'none',
                'mother_status' => ($heirs['mother'] ?? 0) > 0 ? 'alive' : 'none',
                'son_count' => $heirs['son'] ?? 0,
                'daughter_count' => $heirs['daughter'] ?? 0,
                'full_brother_count' => $heirs['brother'] ?? 0,
                'full_sister_count' => $heirs['sister'] ?? 0,
                'paternal_brother_count' => $heirs['half_brother_paternal'] ?? 0,
                'paternal_sister_count' => $heirs['half_sister_paternal'] ?? 0,
                'maternal_brother_count' => $heirs['half_brother_maternal'] ?? 0,
                'maternal_sister_count' => $heirs['half_sister_maternal'] ?? 0,
                'total_assets' => $totalEstate,
                'net_assets' => $totalEstate,
                'total_heirs' => $totalHeirs,
                'eligible_heirs_count' => $eligibleHeirs,
                'distribution_summary' => json_encode($distributionData, JSON_UNESCAPED_UNICODE),
                'scenario_number' => $scenarioNumber,
                'scenario_description' => $scenarioDescription,
                'heirs_data' => json_encode($heirs, JSON_UNESCAPED_UNICODE),
                'assets_data' => json_encode($assets, JSON_UNESCAPED_UNICODE),
                'calculation_data' => json_encode([
                    'estate' => $totalEstate,
                    'distribution' => $distributionData,
                    'heirs' => $heirs
                ], JSON_UNESCAPED_UNICODE),
                'calculation_hash' => $calculationHash,
                'calculation_method' => 'telegram_bot_polling',
                'telegram_chat_id' => $chatId,
                'telegram_user_info' => json_encode($telegramUser, JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->logMessage("✅ Calculation saved successfully with ID: {$calculationId} for chat {$chatId}");
            
            return $calculationId;
            
        } catch (\Exception $e) {
            $this->logMessage("❌ Database save error: " . $e->getMessage());
            $this->logMessage("Error details: " . json_encode([
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]));
            
            return false;
        }
    }
    
    /**
     * Determine inheritance scenario number
     */
    private function determineScenario($heirs)
    {
        $hasSpouse = ($heirs['husband'] > 0 || $heirs['wife'] > 0);
        $hasChildren = ($heirs['son'] > 0 || $heirs['daughter'] > 0);
        $hasParents = ($heirs['father'] > 0 || $heirs['mother'] > 0);
        $hasSiblings = ($heirs['brother'] > 0 || $heirs['sister'] > 0 || 
                       $heirs['half_brother_paternal'] > 0 || $heirs['half_brother_maternal'] > 0 ||
                       $heirs['half_sister_paternal'] > 0 || $heirs['half_sister_maternal'] > 0);
        
        if ($hasSpouse && !$hasChildren && !$hasParents && !$hasSiblings) return 1;
        if ($hasSpouse && $hasParents && !$hasChildren) return 2;
        if ($hasSpouse && $hasChildren && !$hasParents) return 3;
        if ($hasChildren && !$hasSpouse && !$hasParents) return 4;
        if ($hasParents && $hasChildren && !$hasSpouse) return 5;
        if ($hasParents && !$hasSpouse && !$hasChildren) return 6;
        if ($hasSpouse && $hasParents && $hasChildren) return 7;
        if ($hasSiblings && !$hasSpouse && !$hasParents && !$hasChildren) return 8;
        if (($heirs['half_brother_maternal'] > 0 || $heirs['half_sister_maternal'] > 0) && 
            !$hasSpouse && !$hasParents && !$hasChildren) return 9;
        
        if ($heirs['son'] > 0) return 10;
        
        return 15;
    }
    
    /**
     * Get scenario description
     */
    private function getScenarioDescription($scenarioNumber)
    {
        $descriptions = [
            1 => "Scenario 1: Spouse Only",
            2 => "Scenario 2: Spouse and Parents (No Children)",
            3 => "Scenario 3: Spouse and Children",
            4 => "Scenario 4: Children Only",
            5 => "Scenario 5: Parents and Children",
            6 => "Scenario 6: Parents Only (No Children)",
            7 => "Scenario 7: Spouse, Parents and Children",
            8 => "Scenario 8: Siblings Only",
            9 => "Scenario 9: Maternal and Half Siblings",
            10 => "Scenario 10: Blocking (Mahjub)",
            11 => "Scenario 11: Extended Heirs (Asabah)",
            12 => "Scenario 12: Awl (Over-Subscription)",
            13 => "Scenario 13: Surplus Estate",
            14 => "Scenario 14: Multiple Wives",
            15 => "Standard Faraid Inheritance"
        ];
        
        return $descriptions[$scenarioNumber] ?? "Standard Faraid Inheritance";
    }
    
    /**
     * Log message
     */
    private function logMessage($message)
    {
        $logFile = $this->logDir . 'polling_bot.log';
        file_put_contents($logFile, 
            date('Y-m-d H:i:s') . " - " . $message . "\n", 
            FILE_APPEND
        );
        
        // Also log to Laravel log
        Log::channel('single')->info($message);
    }
}