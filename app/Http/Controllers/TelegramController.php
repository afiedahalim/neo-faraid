<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TelegramController extends Controller
{
    private $botToken;
    private $apiUrl;
    
    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}/";
    }
    
    /**
     * Set webhook for Telegram bot
     */
    public function setWebhook()
    {
        $webhookUrl = env('TELEGRAM_WEBHOOK_URL', url('/telegram/webhook'));
        
        Log::info('Setting webhook', ['url' => $webhookUrl]);
        
        try {
            $response = Http::post($this->apiUrl . 'setWebhook', [
                'url' => $webhookUrl,
                'drop_pending_updates' => true,
                'allowed_updates' => ['message', 'callback_query']
            ]);
            
            $result = $response->json();
            
            if ($result['ok'] ?? false) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Webhook set successfully!',
                    'url' => $webhookUrl,
                    'response' => $result
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to set webhook',
                    'response' => $result
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Webhook setup failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get webhook info
     */
    public function getWebhookInfo()
    {
        try {
            $response = Http::get($this->apiUrl . 'getWebhookInfo');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Delete webhook
     */
    public function deleteWebhook()
    {
        try {
            $response = Http::post($this->apiUrl . 'deleteWebhook');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle incoming webhook from Telegram
     */
    public function webhook(Request $request)
    {
        Log::info('Telegram webhook received', $request->all());
        
        $update = $request->all();
        
        try {
            if (isset($update['message'])) {
                $this->processMessage($update['message']);
            } elseif (isset($update['callback_query'])) {
                $this->processCallback($update['callback_query']);
            }
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return response()->json(['ok' => true]);
    }
    
    /**
     * Process callback query
     */
    private function processCallback($callbackQuery)
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $data = $callbackQuery['data'];
        
        Http::post($this->apiUrl . 'answerCallbackQuery', [
            'callback_query_id' => $callbackQuery['id']
        ]);
        
        $this->processMessage(['chat' => ['id' => $chatId], 'text' => $data, 'from' => $callbackQuery['from']]);
    }
    
    /**
     * Process incoming message
     */
    private function processMessage($message)
    {
        $chatId = $message['chat']['id'];
        $userId = $message['from']['id'];
        $name = $message['from']['first_name'] ?? 'User';
        $text = trim($message['text'] ?? '');
        
        Log::info("Message from {$name} ({$chatId}): {$text}");
        
        $session = $this->getSession($chatId);
        
        if (strpos($text, '/') === 0) {
            $this->handleCommand($chatId, $text, $name, $session);
            return;
        }
        
        $this->processFlow($chatId, $text, $session);
    }
    
    /**
     * Handle bot commands
     */
    private function handleCommand($chatId, $command, $name, &$session)
    {
        switch ($command) {
            case '/start':
                $session = $this->initSession();
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, 
                    "👋 <b>Assalamualaikum {$name}!</b>\n\n" .
                    "Welcome to <b>Neo Faraid Calculator 🤖</b>\n" .
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
                    "   • Choose from categories\n" .
                    "   • Enter count for each heir\n\n" .
                    "4️⃣ <b>Add Assets</b>\n" .
                    "   • Asset type\n" .
                    "   • Description\n" .
                    "   • Value (RM)\n\n" .
                    "5️⃣ <b>View Results</b>\n" .
                    "   • Inheritance distribution\n" .
                    "   • Family tree\n" .
                    "   • Quranic references"
                );
                break;
                
            case '/about':
                $this->sendMessage($chatId,
                    "🤖 <b>NEO FARAID CALCULATOR</b>\n\n" .
                    "Developer: Afieda Halim\n" .
                    "Platform: Telegram Bot\n" .
                    "Purpose: Academic Islamic Inheritance\n\n" .
                    "✨ <b>Features:</b>\n" .
                    "• Complete Faraid calculation\n" .
                    "• 14 inheritance scenarios\n" .
                    "• Mahjub (Blocking) rules\n" .
                    "• Awl (Over-subscription)\n" .
                    "• Multiple wives support\n" .
                    "• Asset management\n\n" .
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
     * Process conversation flow
     */
    private function processFlow($chatId, $text, &$session)
    {
        $step = $session['step'] ?? 'idle';
        
        switch ($step) {
            case 'idle':
                $this->sendMessage($chatId, "Send /calculate to start or /help for commands");
                break;
                
            case 'name':
                if (empty(trim($text))) {
                    $this->sendMessage($chatId, "❌ Name cannot be empty. Please enter deceased name:");
                    return;
                }
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
                if (!$this->validateDate($text)) {
                    $this->sendMessage($chatId,
                        "❌ <b>Invalid date format!</b>\n\n" .
                        "Please use: <code>DD-MM-YYYY</code>\n" .
                        "Example: 15-01-2024"
                    );
                    return;
                }
                $session['data']['date'] = $text;
                $session['step'] = 'gender';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "⚧ <b>Step 3 of 5:</b> Select gender",
                    $this->createKeyboard([['Male', 'Female']])
                );
                break;
                
            case 'gender':
                $gender = strtolower(trim($text));
                if (!in_array($gender, ['male', 'female'])) {
                    $this->sendMessage($chatId, 
                        "❌ Please select either <b>Male</b> or <b>Female</b>",
                        $this->createKeyboard([['Male', 'Female']])
                    );
                    return;
                }
                $session['data']['gender'] = $gender;
                $session['step'] = 'marital';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId,
                    "💍 <b>Step 4 of 5:</b> Select marital status",
                    $this->createKeyboard([
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
                        $this->createKeyboard([
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
                            "Please select at least one heir before continuing.",
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
                    $this->sendMessage($chatId, "❌ Number cannot be negative.");
                    return;
                }
                
                if ($heir === 'Husband' && $count > 1) {
                    $this->sendMessage($chatId, "❌ Maximum 1 husband allowed.");
                    return;
                }
                
                if ($heir === 'Wife' && $count > 4) {
                    $this->sendMessage($chatId, "❌ Maximum 4 wives allowed in Islam.");
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
                if (empty(trim($text))) {
                    $this->sendMessage($chatId, "❌ Asset type cannot be empty. Please enter asset type:");
                    return;
                }
                $session['data']['current_asset']['type'] = htmlspecialchars(trim($text));
                $session['step'] = 'asset_desc';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, "📝 Enter asset description:");
                break;
                
            case 'asset_desc':
                if (empty(trim($text))) {
                    $this->sendMessage($chatId, "❌ Description cannot be empty. Please enter asset description:");
                    return;
                }
                $session['data']['current_asset']['desc'] = htmlspecialchars(trim($text));
                $session['step'] = 'asset_value';
                $this->saveSession($chatId, $session);
                $this->sendMessage($chatId, "💰 Enter asset value in RM:");
                break;
                
            case 'asset_value':
                $value = str_replace([',', ' ', 'RM', 'rm'], '', trim($text));
                
                if (!is_numeric($value) || $value <= 0) {
                    $this->sendMessage($chatId,
                        "❌ Invalid amount!\n" .
                        "Please enter a valid number greater than 0.\n" .
                        "Example: 10000 or 10,000"
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
                    $this->createKeyboard([
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
                        $this->sendMessage($chatId, "📦 Enter next asset type:");
                        break;
                        
                    case '📊 View Asset Summary':
                        $this->showAssetSummary($chatId, $session);
                        break;
                        
                    case '📜 Calculate Inheritance':
                        $this->showResult($chatId, $session['data'], $session['telegram_user'] ?? []);
                        $this->clearSession($chatId);
                        break;
                        
                    default:
                        $this->sendMessage($chatId,
                            "Please select an option:",
                            $this->createKeyboard([
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
     * Show asset summary
     */
    private function showAssetSummary($chatId, $session)
    {
        $assets = $session['data']['assets'];
        
        if (empty($assets)) {
            $this->sendMessage($chatId, "📦 No assets added yet.");
            return;
        }
        
        $message = "📊 <b>ASSET SUMMARY</b>\n";
        $message .= str_repeat("─", 25) . "\n\n";
        $total = 0;
        
        foreach ($assets as $index => $asset) {
            $message .= "<b>" . ($index + 1) . ". {$asset['type']}</b>\n";
            $message .= "📝 {$asset['desc']}\n";
            $message .= "💰 RM " . number_format($asset['value'], 2) . "\n\n";
            $total += $asset['value'];
        }
        
        $message .= str_repeat("─", 25) . "\n";
        $message .= "<b>💰 TOTAL: RM " . number_format($total, 2) . "</b>";
        
        $this->sendMessage($chatId, $message);
    }
    
    /**
     * Calculate and show inheritance results
     */
    private function showResult($chatId, $data, $telegramUser = [])
    {
        $totalEstate = 0;
        foreach ($data['assets'] as $asset) {
            $totalEstate += $asset['value'];
        }
        
        if ($totalEstate <= 0) {
            $this->sendMessage($chatId,
                "⚠️ <b>No Assets Found!</b>\n\n" .
                "Please add assets to calculate inheritance.",
                $this->removeKeyboard()
            );
            return;
        }
        
        $this->sendMessage($chatId, "🧮 Calculating inheritance distribution...");
        
        $heirs = $this->applyMahjub($data['heirs']);
        $distribution = $this->calculateInheritance($heirs, $totalEstate);
        
        $message = "📜 <b>FARAID INHERITANCE RESULTS</b>\n\n";
        $message .= "👤 <b>Deceased:</b> {$data['name']}\n";
        $message .= "📅 " . $this->formatDate($data['date']) . "\n";
        $message .= "⚧ " . ucfirst($data['gender']) . " | 💍 " . ucfirst($data['marital']) . "\n";
        $message .= "💰 <b>Total Estate:</b> RM " . number_format($totalEstate, 2) . "\n\n";
        
        $message .= "👨‍👩‍👧 <b>FAMILY STRUCTURE</b>\n";
        $message .= $this->generateFamilyTreeText($data) . "\n";
        
        $message .= "📊 <b>INHERITANCE DISTRIBUTION</b>\n";
        $message .= str_repeat("─", 30) . "\n\n";
        
        foreach ($distribution['shares'] as $item) {
            $message .= $item['display'] . "\n";
        }
        
        if ($distribution['awl_applied']) {
            $message .= "\n⚠️ <b>Awl (Adjustment) Applied:</b>\n";
            $message .= "Total shares exceeded 1, adjusted proportionally.\n";
        }
        
        $message .= "\n📖 <i>Quran 4:11-12</i>\n";
        $message .= "<i>\"Allah instructs you concerning your children...\"</i>";
        
        $this->sendMessage($chatId, $message);
        
        // Generate and send family tree image
        $this->sendMessage($chatId, "🌳 Generating family tree visualization...");
        
        $familyTreeImage = $this->generateFamilyTreeImage($data, $chatId);
        
        if ($familyTreeImage && file_exists($familyTreeImage)) {
            $this->sendPhoto($chatId, $familyTreeImage, "🌳 <b>Family Tree Visualization</b>\n👤 Deceased: {$data['name']}");
            Log::info("Family tree image sent to chat {$chatId}");
        } else {
            $this->sendMessage($chatId, "⚠️ Could not generate family tree image. Text version shown above.");
        }
        
        // Save to database
        $this->saveToDatabase($chatId, $data, $totalEstate, $distribution['shares'], $telegramUser);
        
        $this->sendMessage($chatId,
            "✅ Calculation complete!\n\nType /calculate to start a new calculation.",
            $this->removeKeyboard()
        );
    }
    
    /**
     * Generate family tree text representation
     */
    private function generateFamilyTreeText($data)
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
     * Generate family tree image
     */
    private function generateFamilyTreeImage($data, $chatId)
    {
        $heirs = $data['heirs'];
        $name = $data['name'];
        $gender = $data['gender'];
        $marital = $data['marital'];
        
        // Check if GD is available
        if (!extension_loaded('gd')) {
            Log::warning('GD extension not loaded, cannot generate family tree image');
            return null;
        }
        
        // Image dimensions
        $width = 1000;
        $height = 800;
        
        // Create image
        $image = imagecreatetruecolor($width, $height);
        
        // Colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $blue = imagecolorallocate($image, 0, 100, 200);
        $green = imagecolorallocate($image, 0, 150, 0);
        $red = imagecolorallocate($image, 200, 0, 0);
        $gray = imagecolorallocate($image, 100, 100, 100);
        $lightBlue = imagecolorallocate($image, 200, 230, 255);
        $lightGreen = imagecolorallocate($image, 220, 255, 220);
        $lightYellow = imagecolorallocate($image, 255, 255, 200);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $white);
        
        // Font settings (using built-in GD font)
        $font = 5;
        $titleFont = 5;
        
        // Title
        $title = "FAMILY TREE - {$name}";
        $titleWidth = strlen($title) * imagefontwidth($titleFont);
        imagestring($image, $titleFont, ($width - $titleWidth) / 2, 20, $title, $black);
        
        $subtitle = "(" . ucfirst($gender) . " | " . ucfirst($marital) . ")";
        $subtitleWidth = strlen($subtitle) * imagefontwidth($font);
        imagestring($image, $font, ($width - $subtitleWidth) / 2, 45, $subtitle, $gray);
        
        // Draw deceased at center
        $centerX = $width / 2;
        $centerY = 150;
        
        // Draw deceased rectangle
        $deceasedColor = ($gender === 'male') ? $lightBlue : $lightGreen;
        imagefilledrectangle($image, $centerX - 100, $centerY - 30, $centerX + 100, $centerY + 30, $deceasedColor);
        imagerectangle($image, $centerX - 100, $centerY - 30, $centerX + 100, $centerY + 30, $black);
        
        // Deceased text
        imagestring($image, $font, $centerX - 90, $centerY - 20, "DECEASED", $red);
        imagestring($image, $font, $centerX - 90, $centerY - 5, "Name: {$name}", $black);
        $genderSymbol = ($gender === 'male') ? '♂' : '♀';
        imagestring($image, $font, $centerX - 90, $centerY + 10, "Gender: {$genderSymbol}", $black);
        
        // Start Y position for heirs
        $yPosition = 250;
        $xPosition = 50;
        
        // SPOUSE SECTION
        if ($heirs['husband'] > 0 || $heirs['wife'] > 0) {
            imagestring($image, $font, $xPosition, $yPosition - 20, "SPOUSE", $blue);
            
            if ($heirs['husband'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Husband", $heirs['husband'], $lightBlue, $font);
                $xPosition += 180;
            }
            
            if ($heirs['wife'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Wife", $heirs['wife'], $lightGreen, $font);
                $xPosition += 180;
            }
            
            $yPosition += 100;
            $xPosition = 50;
        }
        
        // CHILDREN SECTION
        if ($heirs['son'] > 0 || $heirs['daughter'] > 0) {
            imagestring($image, $font, $xPosition, $yPosition - 20, "CHILDREN", $blue);
            
            if ($heirs['son'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Son", $heirs['son'], $lightBlue, $font);
                $xPosition += 180;
            }
            
            if ($heirs['daughter'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Daughter", $heirs['daughter'], $lightGreen, $font);
                $xPosition += 180;
            }
            
            $yPosition += 100;
            $xPosition = 50;
        }
        
        // PARENTS SECTION
        if ($heirs['father'] > 0 || $heirs['mother'] > 0) {
            imagestring($image, $font, $xPosition, $yPosition - 20, "PARENTS", $blue);
            
            if ($heirs['father'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Father", $heirs['father'], $lightBlue, $font);
                $xPosition += 180;
            }
            
            if ($heirs['mother'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Mother", $heirs['mother'], $lightGreen, $font);
                $xPosition += 180;
            }
            
            $yPosition += 100;
            $xPosition = 50;
        }
        
        // GRANDPARENTS SECTION
        if ($heirs['grandfather'] > 0 || $heirs['grandmother_father'] > 0 || $heirs['grandmother_mother'] > 0) {
            imagestring($image, $font, $xPosition, $yPosition - 20, "GRANDPARENTS", $blue);
            
            if ($heirs['grandfather'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Grandfather", $heirs['grandfather'], $lightBlue, $font);
                $xPosition += 180;
            }
            
            if ($heirs['grandmother_father'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Grandmother (F)", $heirs['grandmother_father'], $lightGreen, $font);
                $xPosition += 180;
            }
            
            if ($heirs['grandmother_mother'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Grandmother (M)", $heirs['grandmother_mother'], $lightGreen, $font);
                $xPosition += 180;
            }
            
            $yPosition += 100;
            $xPosition = 50;
        }
        
        // SIBLINGS SECTION
        $hasSiblings = $heirs['brother'] > 0 || $heirs['sister'] > 0 || 
                       $heirs['half_brother_paternal'] > 0 || $heirs['half_brother_maternal'] > 0 ||
                       $heirs['half_sister_paternal'] > 0 || $heirs['half_sister_maternal'] > 0;
        
        if ($hasSiblings && $yPosition < $height - 150) {
            imagestring($image, $font, $xPosition, $yPosition - 20, "SIBLINGS", $blue);
            
            if ($heirs['brother'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Brother", $heirs['brother'], $lightBlue, $font);
                $xPosition += 180;
            }
            
            if ($heirs['sister'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Sister", $heirs['sister'], $lightGreen, $font);
                $xPosition += 180;
            }
            
            if ($xPosition > 800) {
                $yPosition += 100;
                $xPosition = 50;
            }
            
            if ($heirs['half_brother_paternal'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Half-Brother (P)", $heirs['half_brother_paternal'], $lightBlue, $font);
                $xPosition += 180;
            }
            
            if ($heirs['half_sister_paternal'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Half-Sister (P)", $heirs['half_sister_paternal'], $lightGreen, $font);
                $xPosition += 180;
            }
            
            if ($xPosition > 800) {
                $yPosition += 100;
                $xPosition = 50;
            }
            
            if ($heirs['half_brother_maternal'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Half-Brother (M)", $heirs['half_brother_maternal'], $lightYellow, $font);
                $xPosition += 180;
            }
            
            if ($heirs['half_sister_maternal'] > 0) {
                $this->drawHeirBox($image, $xPosition, $yPosition, "Half-Sister (M)", $heirs['half_sister_maternal'], $lightYellow, $font);
                $xPosition += 180;
            }
        }
        
        // Draw connecting lines from deceased to sections
        $lineY = 180;
        if ($heirs['husband'] > 0 || $heirs['wife'] > 0) {
            imageline($image, $centerX, $lineY, $centerX, 230, $black);
            imageline($image, $centerX, 230, 100, 230, $black);
            imageline($image, 100, 230, 100, 250, $black);
        }
        
        // Footer
        $footerText = "Generated by Neo Faraid Calculator | " . date('Y-m-d H:i:s');
        $footerWidth = strlen($footerText) * imagefontwidth($font);
        imagestring($image, $font, ($width - $footerWidth) / 2, $height - 30, $footerText, $gray);
        
        // Save image
        $imageDir = storage_path('app/public/family_trees');
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }
        
        $filename = $imageDir . '/family_tree_' . $chatId . '_' . time() . '.png';
        imagepng($image, $filename);
        imagedestroy($image);
        
        return $filename;
    }
    
    /**
     * Draw heir box on image
     */
    private function drawHeirBox($image, $x, $y, $label, $count, $color, $font)
    {
        $black = imagecolorallocate($image, 0, 0, 0);
        $blue = imagecolorallocate($image, 0, 0, 200);
        $pink = imagecolorallocate($image, 200, 0, 100);
        
        // Draw box
        imagefilledrectangle($image, $x, $y, $x + 150, $y + 60, $color);
        imagerectangle($image, $x, $y, $x + 150, $y + 60, $black);
        
        // Draw text
        imagestring($image, $font, $x + 10, $y + 10, $label, $black);
        imagestring($image, $font, $x + 10, $y + 30, "Count: " . $count, $black);
        
        // Add icon based on gender
        $maleKeywords = ['Husband', 'Father', 'Son', 'Brother', 'Grandfather', 'Half-Brother', 'Paternal Uncle'];
        $femaleKeywords = ['Wife', 'Mother', 'Daughter', 'Sister', 'Grandmother', 'Half-Sister'];
        
        $isMale = false;
        $isFemale = false;
        
        foreach ($maleKeywords as $keyword) {
            if (strpos($label, $keyword) !== false) {
                $isMale = true;
                break;
            }
        }
        
        foreach ($femaleKeywords as $keyword) {
            if (strpos($label, $keyword) !== false) {
                $isFemale = true;
                break;
            }
        }
        
        if ($isMale) {
            imagestring($image, $font, $x + 120, $y + 10, "♂", $blue);
        } elseif ($isFemale) {
            imagestring($image, $font, $x + 120, $y + 10, "♀", $pink);
        }
    }
    
    /**
     * Calculate inheritance shares with Awl handling
     */
    private function calculateInheritance($heirs, $estate)
    {
        $shares = [];
        $totalShares = 0;
        
        // Husband share
        if ($heirs['husband'] > 0) {
            $share = $this->hasChildren($heirs) ? 1/4 : 1/2;
            $shares[] = $this->createDistributionItem('Husband', $share, $estate);
            $totalShares += $share;
        }
        
        // Wife/Wives share
        if ($heirs['wife'] > 0) {
            $share = $this->hasChildren($heirs) ? 1/8 : 1/4;
            if ($heirs['wife'] == 1) {
                $shares[] = $this->createDistributionItem('Wife', $share, $estate);
            } else {
                $sharePerWife = $share / $heirs['wife'];
                for ($i = 1; $i <= $heirs['wife']; $i++) {
                    $shares[] = $this->createDistributionItem("Wife {$i}", $sharePerWife, $estate);
                }
            }
            $totalShares += $share;
        }
        
        // Mother share
        if ($heirs['mother'] > 0) {
            if ($this->hasChildren($heirs) || $heirs['brother'] > 0 || $heirs['sister'] > 0) {
                $share = 1/6;
            } else {
                $share = 1/3;
            }
            $shares[] = $this->createDistributionItem('Mother', $share, $estate);
            $totalShares += $share;
        }
        
        // Father share
        if ($heirs['father'] > 0) {
            if ($this->hasChildren($heirs)) {
                $share = 1/6;
                $shares[] = $this->createDistributionItem('Father', $share, $estate);
                $totalShares += $share;
            } elseif (!$this->hasChildren($heirs)) {
                $remaining = 1 - $totalShares;
                if ($remaining > 0) {
                    $shares[] = $this->createDistributionItem('Father', $remaining, $estate);
                    $totalShares += $remaining;
                }
            }
        }
        
        // Grandfather (paternal) - inherits if no father
        if ($heirs['grandfather'] > 0 && $heirs['father'] == 0) {
            if ($this->hasChildren($heirs)) {
                $share = 1/6;
                $shares[] = $this->createDistributionItem('Grandfather', $share, $estate);
                $totalShares += $share;
            }
        }
        
        // Grandmothers
        if ($heirs['grandmother_father'] > 0 && $heirs['mother'] == 0) {
            $share = 1/6;
            $shares[] = $this->createDistributionItem('Grandmother (Father Side)', $share, $estate);
            $totalShares += $share;
        }
        
        if ($heirs['grandmother_mother'] > 0 && $heirs['mother'] == 0) {
            $share = 1/6;
            $shares[] = $this->createDistributionItem('Grandmother (Mother Side)', $share, $estate);
            $totalShares += $share;
        }
        
        // Children shares (Asabah)
        if ($this->hasChildren($heirs)) {
            $remaining = 1 - $totalShares;
            if ($remaining > 0) {
                $totalUnits = ($heirs['son'] * 2) + $heirs['daughter'];
                if ($totalUnits > 0) {
                    $unitValue = $remaining / $totalUnits;
                    
                    for ($i = 1; $i <= $heirs['son']; $i++) {
                        $shares[] = $this->createDistributionItem("Son {$i}", $unitValue * 2, $estate);
                    }
                    for ($i = 1; $i <= $heirs['daughter']; $i++) {
                        $shares[] = $this->createDistributionItem("Daughter {$i}", $unitValue, $estate);
                    }
                    $totalShares += $remaining;
                }
            }
        }
        
        // Siblings as residuaries (if no children and no parents)
        if (!$this->hasChildren($heirs) && $heirs['father'] == 0 && $heirs['mother'] == 0) {
            $remaining = 1 - $totalShares;
            if ($remaining > 0) {
                $totalUnits = ($heirs['brother'] * 2) + $heirs['sister'];
                if ($totalUnits > 0) {
                    $unitValue = $remaining / $totalUnits;
                    
                    for ($i = 1; $i <= $heirs['brother']; $i++) {
                        $shares[] = $this->createDistributionItem("Brother {$i}", $unitValue * 2, $estate);
                    }
                    for ($i = 1; $i <= $heirs['sister']; $i++) {
                        $shares[] = $this->createDistributionItem("Sister {$i}", $unitValue, $estate);
                    }
                    $totalShares += $remaining;
                }
            }
        }
        
        // Check for Awl (over-subscription)
        $awlApplied = false;
        if ($totalShares > 1) {
            $awlApplied = true;
            $adjustmentFactor = 1 / $totalShares;
            foreach ($shares as &$item) {
                $adjustedFraction = $item['fraction'] * $adjustmentFactor;
                $item['amount'] = $estate * $adjustedFraction;
                $item['percentage'] = $adjustedFraction * 100;
                $item['display'] = "👥 <b>{$item['heir']}</b>\n" .
                                  "Share: " . $this->formatFraction($adjustedFraction) . 
                                  " (" . round($adjustedFraction * 100, 2) . "%)\n" .
                                  "Amount: RM " . number_format($item['amount'], 2);
            }
        }
        
        // If no heirs, estate goes to Baitulmal
        if (empty($shares)) {
            $shares[] = $this->createDistributionItem('Baitulmal (State Treasury)', 1, $estate);
            $totalShares = 1;
        }
        
        return [
            'shares' => $shares,
            'awl_applied' => $awlApplied,
            'total_shares' => $totalShares
        ];
    }
    
    /**
     * Create distribution item
     */
    private function createDistributionItem($heir, $fraction, $estate)
    {
        $amount = $estate * $fraction;
        $percentage = $fraction * 100;
        
        return [
            'heir' => $heir,
            'fraction' => $fraction,
            'amount' => $amount,
            'percentage' => $percentage,
            'display' => "👥 <b>{$heir}</b>\n" .
                        "Share: " . $this->formatFraction($fraction) . 
                        " (" . round($percentage, 2) . "%)\n" .
                        "Amount: RM " . number_format($amount, 2)
        ];
    }
    
    /**
     * Apply Mahjub (blocking) rules
     */
    private function applyMahjub($heirs)
    {
        $blocked = $heirs;
        
        // Son blocks brothers, sisters, and grandfather
        if ($blocked['son'] > 0) {
            $blocked['brother'] = 0;
            $blocked['sister'] = 0;
            $blocked['grandfather'] = 0;
            $blocked['half_brother_paternal'] = 0;
            $blocked['half_sister_paternal'] = 0;
            $blocked['half_brother_maternal'] = 0;
            $blocked['half_sister_maternal'] = 0;
        }
        
        // Father blocks brothers and sisters
        if ($blocked['father'] > 0) {
            $blocked['brother'] = 0;
            $blocked['sister'] = 0;
            $blocked['half_brother_paternal'] = 0;
            $blocked['half_sister_paternal'] = 0;
        }
        
        // Grandfather blocks brothers if no father
        if ($blocked['grandfather'] > 0 && $blocked['father'] == 0) {
            $blocked['brother'] = 0;
            $blocked['sister'] = 0;
        }
        
        // Mother blocks grandmothers
        if ($blocked['mother'] > 0) {
            $blocked['grandmother_father'] = 0;
            $blocked['grandmother_mother'] = 0;
        }
        
        return $blocked;
    }
    
    /**
     * Check if deceased has children
     */
    private function hasChildren($heirs)
    {
        return ($heirs['son'] > 0 || $heirs['daughter'] > 0);
    }
    
    /**
     * Format fraction for display
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
        
        return round($decimal, 4);
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
        
        return $this->createKeyboard($buttons);
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
                $buttons = [['Father', 'Mother']];
                break;
            case '👦👧 Children':
                $buttons = [['Son', 'Daughter']];
                break;
            case '👴👵 Grandparents':
                $buttons = [['Grandfather'], ['Grandmother (Father Side)', 'Grandmother (Mother Side)']];
                break;
            case '👥 Siblings':
                $buttons = [
                    ['Brother', 'Sister'],
                    ['Half-Brother (Paternal)', 'Half-Brother (Maternal)'],
                    ['Half-Sister (Paternal)', 'Half-Sister (Maternal)']
                ];
                break;
        }
        
        $buttons[] = ['⬅ Back to Categories'];
        return $this->createKeyboard($buttons);
    }
    
    /**
     * Heir count keyboard
     */
    private function heirCountKeyboard($heir)
    {
        if ($heir === 'Husband') {
            return $this->createKeyboard([['0', '1']]);
        }
        if ($heir === 'Wife') {
            return $this->createKeyboard([['0', '1', '2', '3', '4']]);
        }
        return $this->createKeyboard([['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']]);
    }
    
    /**
     * Convert heir display name to key
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
            'Half-Sister (Maternal)' => 'half_sister_maternal'
        ];
        
        return $map[$displayName] ?? strtolower(str_replace(' ', '_', $displayName));
    }
    
    /**
     * Validate date format
     */
    private function validateDate($date)
    {
        $d = Carbon::createFromFormat('d-m-Y', $date);
        return $d && $d->format('d-m-Y') === $date;
    }
    
    /**
     * Format date for display
     */
    private function formatDate($date)
    {
        $d = Carbon::createFromFormat('d-m-Y', $date);
        return $d ? $d->format('j F Y') : $date;
    }
    
    /**
     * Send message to Telegram
     */
    private function sendMessage($chatId, $text, $keyboard = null)
    {
        try {
            $payload = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ];
            
            if ($keyboard) {
                $payload['reply_markup'] = json_encode($keyboard);
            }
            
            $response = Http::timeout(30)->post($this->apiUrl . 'sendMessage', $payload);
            
            if (!$response->successful()) {
                Log::error('Failed to send message', [
                    'chat_id' => $chatId,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send message', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Send photo to Telegram
     */
    private function sendPhoto($chatId, $photoPath, $caption = '')
    {
        try {
            $response = Http::attach(
                'photo', file_get_contents($photoPath), basename($photoPath)
            )->post($this->apiUrl . 'sendPhoto', [
                'chat_id' => $chatId,
                'caption' => $caption,
                'parse_mode' => 'HTML'
            ]);
            
            if (!$response->successful()) {
                Log::error('Failed to send photo', [
                    'chat_id' => $chatId,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send photo', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Create custom keyboard
     */
    private function createKeyboard($buttons)
    {
        return [
            'keyboard' => $buttons,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ];
    }
    
    /**
     * Remove keyboard
     */
    private function removeKeyboard()
    {
        return ['remove_keyboard' => true];
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
                    'half_sister_maternal' => 0
                ],
                'current_heir' => '', 
                'current_category' => ''
            ],
            'telegram_user' => []
        ];
    }
    
    /**
     * Get session from cache
     */
    private function getSession($chatId)
    {
        $session = Cache::get("telegram_session_{$chatId}");
        return $session ?: $this->initSession();
    }
    
    /**
     * Save session to cache
     */
    private function saveSession($chatId, $session)
    {
        Cache::put("telegram_session_{$chatId}", $session, 3600);
    }
    
    /**
     * Clear session
     */
    private function clearSession($chatId)
    {
        Cache::forget("telegram_session_{$chatId}");
    }
    
    /**
     * Save calculation to database
     */
    private function saveToDatabase($chatId, $data, $totalEstate, $distribution, $telegramUser = [])
    {
        try {
            $dateObj = Carbon::createFromFormat('d-m-Y', $data['date']);
            $dateOfDeath = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');
            
            $calculationHash = 'CALC_TELEGRAM_' . time() . '_' . bin2hex(random_bytes(8));
            
            $heirs = $data['heirs'];
            $assets = $data['assets'];
            
            $calculationId = DB::table('calculations')->insertGetId([
                'user_id' => null,
                'telegram_chat_id' => $chatId,
                'telegram_user_info' => json_encode($telegramUser),
                'deceased_name' => $data['name'],
                'deceased_gender' => $data['gender'],
                'date_of_death' => $dateOfDeath,
                'marital_status' => $data['marital'],
                'wife_count' => $heirs['wife'] ?? 0,
                'husband_count' => $heirs['husband'] ?? 0,
                'son_count' => $heirs['son'] ?? 0,
                'daughter_count' => $heirs['daughter'] ?? 0,
                'father_status' => ($heirs['father'] ?? 0) > 0 ? 'alive' : 'none',
                'mother_status' => ($heirs['mother'] ?? 0) > 0 ? 'alive' : 'none',
                'full_brother_count' => $heirs['brother'] ?? 0,
                'full_sister_count' => $heirs['sister'] ?? 0,
                'paternal_brother_count' => $heirs['half_brother_paternal'] ?? 0,
                'paternal_sister_count' => $heirs['half_sister_paternal'] ?? 0,
                'maternal_brother_count' => $heirs['half_brother_maternal'] ?? 0,
                'maternal_sister_count' => $heirs['half_sister_maternal'] ?? 0,
                'grandfather_status' => ($heirs['grandfather'] ?? 0) > 0 ? 'alive' : 'none',
                'grandmother_father_status' => ($heirs['grandmother_father'] ?? 0) > 0 ? 'alive' : 'none',
                'grandmother_mother_status' => ($heirs['grandmother_mother'] ?? 0) > 0 ? 'alive' : 'none',
                'total_assets' => $totalEstate,
                'net_assets' => $totalEstate,
                'total_heirs' => array_sum($heirs),
                'eligible_heirs_count' => count(array_filter($distribution, function($item) {
                    return $item['amount'] > 0 && stripos($item['heir'], 'Baitulmal') === false;
                })),
                'distribution_summary' => json_encode($distribution, JSON_UNESCAPED_UNICODE),
                'heirs_data' => json_encode($heirs, JSON_UNESCAPED_UNICODE),
                'assets_data' => json_encode($assets, JSON_UNESCAPED_UNICODE),
                'calculation_data' => json_encode([
                    'estate' => $totalEstate,
                    'distribution' => $distribution,
                    'heirs' => $heirs
                ], JSON_UNESCAPED_UNICODE),
                'calculation_hash' => $calculationHash,
                'calculation_method' => 'telegram_bot',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            Log::info("Calculation saved successfully with ID: {$calculationId} for chat {$chatId}");
            
            $this->sendMessage($chatId,
                "✅ <b>Calculation saved successfully!</b>\n" .
                "📋 ID: #{$calculationId}\n\n" .
                "Type /calculate to start a new calculation."
            );
            
        } catch (\Exception $e) {
            Log::error("Failed to save calculation", [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            
            $this->sendMessage($chatId,
                "⚠️ <b>Calculation complete but save failed.</b>\n\n" .
                "You can still view the calculation above.\n\n" .
                "Type /calculate to start a new calculation."
            );
        }
    }
    
    /**
     * Test bot functionality
     */
    public function testBot()
    {
        try {
            $response = Http::get($this->apiUrl . 'getMe');
            $botInfo = $response->json();
            
            $webhookResponse = Http::get($this->apiUrl . 'getWebhookInfo');
            $webhookInfo = $webhookResponse->json();
            
            return response()->json([
                'status' => 'success',
                'bot_info' => $botInfo['result'] ?? null,
                'webhook_info' => $webhookInfo['result'] ?? null,
                'bot_token_prefix' => substr($this->botToken, 0, 10) . '...',
                'environment' => app()->environment()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get bot info (alias for testBot)
     */
    public function getBotInfo()
    {
        return $this->testBot();
    }
}
