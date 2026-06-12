<?php
/**
 * Neo Faraid Webhook - Telegram Bot with Database Integration
 * FIXED VERSION - Working with Hostinger Database Structure
 * Bot Token: 8039580931:AAHJhyuPoNnXPbIW_S-DMEj6nf2vUfvlyFQ
 * Webhook URL: https://rosybrown-skunk-415825.hostingersite.com/neofaraid-webhook.php
 */

// Enable error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/telegram_errors.log');
date_default_timezone_set('Asia/Kuala_Lumpur');

// Database configuration (Hostinger MySQL)
define('DB_HOST', 'localhost');
define('DB_NAME', 'u700912713_NeoFaraid');
define('DB_USER', 'u700912713_NeoFaraid');
define('DB_PASS', 'Neofaraid123');

// Telegram Bot Token
define('BOT_TOKEN', '8039580931:AAHJhyuPoNnXPbIW_S-DMEj6nf2vUfvlyFQ');
define('BOT_API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

// Set headers
header('Content-Type: application/json');
header('X-Robots-Tag: noindex, nofollow');

// Log directory
$logDir = __DIR__ . '/logs/';
if (!is_dir($logDir)) mkdir($logDir, 0755, true);

// Session file for storing user states
$sessionFile = $logDir . 'telegram_sessions.json';

// ===================== INITIALIZATION =====================

// Get input from Telegram
$input = file_get_contents('php://input');
$update = json_decode($input, true);

// Log the request
file_put_contents($logDir . 'webhook.log', 
    date('Y-m-d H:i:s') . " - Input: " . substr($input, 0, 500) . "\n", 
    FILE_APPEND
);

// Check if valid Telegram update
if (!$update || !isset($update['update_id'])) {
    // If it's a setup request
    if (isset($_GET['action'])) {
        handleSetupRequest();
        exit;
    }
    
    // Return OK to Telegram
    echo json_encode(['ok' => true]);
    exit;
}

// Process update
if (isset($update['message'])) {
    processMessage($update['message']);
} else {
    // Unknown update type
    logMessage("Unknown update type received");
}

// Always return OK to Telegram
echo json_encode(['ok' => true]);

// ===================== MAIN FUNCTIONS =====================

/**
 * Process incoming message
 */
function processMessage($message) {
    // Check if message has required fields
    if (!isset($message['chat']['id']) || !isset($message['from']['id'])) {
        logMessage("Invalid message format");
        return;
    }
    
    $chatId = $message['chat']['id'];
    $userId = $message['from']['id'];
    $name = $message['from']['first_name'] ?? 'User';
    $text = isset($message['text']) ? trim($message['text']) : '';
    
    // Log message
    logMessage("From {$name} ({$chatId}): {$text}");
    
    // Get or initialize session
    $session = getSession($chatId);
    
    // Store current message in global for later use
    $GLOBALS['current_message'] = $message;
    
    // Handle commands (start with /)
    if (strpos($text, '/') === 0) {
        handleCommand($chatId, $text, $name, $session);
        return;
    }
    
    // Handle regular message flow
    processFlow($chatId, $text, $session);
}

/**
 * Handle commands
 */
function handleCommand($chatId, $text, $name, &$session) {
    switch ($text) {
        case '/start':
            $session = initSession();
            saveSession($chatId, $session);
            sendMessage($chatId,
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
            sendMessage($chatId,
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
            sendMessage($chatId,
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
                "• Family tree display\n" .
                "• Asset management\n" .
                "📚 Based on Quranic verses 4:11-12"
            );
            break;
            
        case '/cancel':
            clearSession($chatId);
            sendMessage($chatId, 
                "✅ <b>Process cancelled.</b>\n\n" .
                "You can start a new calculation with /calculate",
                removeKeyboard()
            );
            break;
            
        case '/calculate':
            $session = initSession();
            $session['step'] = 'name';
            saveSession($chatId, $session);
            sendMessage($chatId, "👤 <b>Step 1 of 5:</b> Enter deceased name:");
            break;
            
        default:
            sendMessage($chatId, 
                "❓ <b>Unknown command.</b>\n" .
                "Type /help for available commands."
            );
    }
}

/**
 * Main flow processor
 */
function processFlow($chatId, $text, &$session) {
    $step = $session['step'] ?? 'idle';
    
    switch ($step) {
        case 'idle':
            // Do nothing, wait for command
            break;
            
        case 'name':
            $session['data']['name'] = htmlspecialchars(trim($text));
            $session['step'] = 'date';
            saveSession($chatId, $session);
            sendMessage($chatId, 
                "📅 <b>Step 2 of 5:</b> Enter date of death\n" .
                "Format: <code>DD-MM-YYYY</code>\n" .
                "Example: 15-01-2024"
            );
            break;
            
        case 'date':
            if (!validateDateDDMMYYYY($text)) {
                sendMessage($chatId,
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
            saveSession($chatId, $session);
            sendMessage($chatId,
                "⚧ <b>Step 3 of 5:</b> Select gender",
                keyboard([['Male', 'Female']])
            );
            break;
            
        case 'gender':
            $gender = strtolower(trim($text));
            if (!in_array($gender, ['male', 'female'])) {
                sendMessage($chatId, 
                    "❌ Please select either <b>Male</b> or <b>Female</b>",
                    keyboard([['Male', 'Female']])
                );
                return;
            }
            $session['data']['gender'] = $gender;
            $session['step'] = 'marital';
            saveSession($chatId, $session);
            sendMessage($chatId,
                "💍 <b>Step 4 of 5:</b> Select marital status",
                keyboard([
                    ['Single', 'Married'],
                    ['Divorced', 'Widowed']
                ])
            );
            break;
            
        case 'marital':
            $marital = strtolower(trim($text));
            $validStatus = ['single', 'married', 'divorced', 'widowed'];
            
            if (!in_array($marital, $validStatus)) {
                sendMessage($chatId,
                    "❌ Please select a valid marital status",
                    keyboard([
                        ['Single', 'Married'],
                        ['Divorced', 'Widowed']
                    ])
                );
                return;
            }
            
            $session['data']['marital'] = $marital;
            $session['step'] = 'heir_category';
            saveSession($chatId, $session);
            sendMessage($chatId,
                "👨‍👩‍👧 <b>Step 5 of 5:</b> Select heir category",
                heirCategoryMenu($session['data']['gender'])
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
                    sendMessage($chatId,
                        "⚠️ <b>No heirs selected!</b>\n\n" .
                        "Please select at least one heir before continuing.\n" .
                        "If no heirs exist, the estate goes to Baitulmal.",
                        heirCategoryMenu($session['data']['gender'])
                    );
                    return;
                }
                
                $session['step'] = 'asset_type';
                saveSession($chatId, $session);
                sendMessage($chatId,
                    "📦 <b>ASSET ENTRY</b>\n\n" .
                    "Enter asset type:\n" .
                    "(e.g., House, Cash, Land, Gold, Investments)"
                );
                return;
            }
            
            $session['data']['current_category'] = $text;
            $session['step'] = 'heir_selection';
            saveSession($chatId, $session);
            sendMessage($chatId,
                "👥 Select heirs from <b>{$text}</b>:",
                heirSelectionMenu($text, $session['data']['gender'])
            );
            break;
            
        case 'heir_selection':
            if ($text === '⬅ Back to Categories') {
                $session['step'] = 'heir_category';
                saveSession($chatId, $session);
                sendMessage($chatId,
                    "👨‍👩‍👧 Select heir category:",
                    heirCategoryMenu($session['data']['gender'])
                );
                return;
            }
            
            $session['data']['current_heir'] = $text;
            $session['step'] = 'heir_count';
            saveSession($chatId, $session);
            sendMessage($chatId,
                "🔢 Enter number of <b>{$text}</b>:",
                heirCountKeyboard($text)
            );
            break;
            
        case 'heir_count':
            $heir = $session['data']['current_heir'];
            $count = intval(trim($text));
            
            // Validate count
            if ($count < 0) {
                sendMessage($chatId,
                    "❌ Number cannot be negative.\n" .
                    "Please enter 0 or more.",
                    heirCountKeyboard($heir)
                );
                return;
            }
            
            // Special limits
            if ($heir === 'Husband' && $count > 1) {
                sendMessage($chatId,
                    "❌ Maximum 1 husband allowed.\n" .
                    "Please enter 0 or 1.",
                    keyboard([['0', '1']])
                );
                return;
            }
            
            if ($heir === 'Wife' && $count > 4) {
                sendMessage($chatId,
                    "❌ Maximum 4 wives allowed in Islam.\n" .
                    "Please enter 0-4.",
                    keyboard([['0', '1', '2', '3', '4']])
                );
                return;
            }
            
            $key = heirKey($heir);
            $session['data']['heirs'][$key] = $count;
            
            $session['step'] = 'heir_category';
            saveSession($chatId, $session);
            sendMessage($chatId,
                "✅ <b>{$heir}</b> saved: <b>{$count}</b>\n\n" .
                "Select more heirs or click 'Done':",
                heirCategoryMenu($session['data']['gender'])
            );
            break;
            
        case 'asset_type':
            $session['data']['current_asset']['type'] = htmlspecialchars(trim($text));
            $session['step'] = 'asset_desc';
            saveSession($chatId, $session);
            sendMessage($chatId, "📝 Enter asset description:");
            break;
            
        case 'asset_desc':
            $session['data']['current_asset']['desc'] = htmlspecialchars(trim($text));
            $session['step'] = 'asset_value';
            saveSession($chatId, $session);
            sendMessage($chatId, "💰 Enter asset value in RM:");
            break;
            
        case 'asset_value':
            $value = str_replace([',', ' '], '', trim($text));
            
            if (!is_numeric($value) || $value <= 0) {
                sendMessage($chatId,
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
            saveSession($chatId, $session);
            sendMessage($chatId,
                "✅ Asset added successfully!\n\n" .
                "What would you like to do next?",
                keyboard([
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
                    saveSession($chatId, $session);
                    sendMessage($chatId,
                        "📦 Enter next asset type:\n" .
                        "(e.g., House, Cash, Land, Gold)"
                    );
                    break;
                    
                case '📊 View Asset Summary':
                    showAssetSummary($chatId, $session);
                    break;
                    
                case '📜 Calculate Inheritance':
                    // Store Telegram user info
                    $message = $GLOBALS['current_message'] ?? null;
                    if ($message && isset($message['from'])) {
                        $session['telegram_user'] = [
                            'id' => $message['from']['id'] ?? 0,
                            'first_name' => $message['from']['first_name'] ?? '',
                            'last_name' => $message['from']['last_name'] ?? '',
                            'username' => $message['from']['username'] ?? '',
                            'chat_id' => $chatId
                        ];
                    }
                    
                    // Calculate and show results
                    showResult($chatId, $session['data'], $session['telegram_user']);
                    
                    // Clear session after calculation
                    clearSession($chatId);
                    break;
                    
                default:
                    sendMessage($chatId,
                        "Please select an option:",
                        keyboard([
                            ['➕ Add Another Asset'],
                            ['📊 View Asset Summary'],
                            ['📜 Calculate Inheritance']
                        ])
                    );
            }
            break;
            
        default:
            // Invalid step, reset
            $session['step'] = 'idle';
            saveSession($chatId, $session);
            sendMessage($chatId, "Something went wrong. Please start over with /calculate");
    }
}

// ===================== TELEGRAM API FUNCTIONS =====================

/**
 * Send message to Telegram
 */
function sendMessage($chatId, $text, $keyboard = null) {
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ];
    
    if ($keyboard) {
        $data['reply_markup'] = json_encode($keyboard);
    }
    
    $url = BOT_API_URL . 'sendMessage?' . http_build_query($data);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        logMessage("CURL Error sending to {$chatId}: {$error}");
    }
    
    return $result;
}

/**
 * Send photo to Telegram
 */
function sendPhoto($chatId, $photoPath, $caption = '') {
    $url = BOT_API_URL . 'sendPhoto';
    
    $post_fields = [
        'chat_id' => $chatId,
        'photo' => new CURLFile(realpath($photoPath)),
        'caption' => $caption,
        'parse_mode' => 'HTML'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        logMessage("CURL Error sending photo to {$chatId}: {$error}");
    }
    
    return $result;
}

/**
 * Create keyboard
 */
function keyboard($buttons, $one_time = true) {
    return [
        'keyboard' => $buttons,
        'resize_keyboard' => true,
        'one_time_keyboard' => $one_time
    ];
}

/**
 * Remove keyboard
 */
function removeKeyboard() {
    return [
        'remove_keyboard' => true
    ];
}

// ===================== SESSION MANAGEMENT =====================

function initSession() {
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

function getSession($chatId) {
    global $sessionFile;
    
    if (!file_exists($sessionFile)) {
        file_put_contents($sessionFile, json_encode([]));
        return initSession();
    }
    
    $sessions = json_decode(file_get_contents($sessionFile), true) ?: [];
    
    if (!isset($sessions[$chatId])) {
        return initSession();
    }
    
    // Check if session is expired (1 hour)
    if (isset($sessions[$chatId]['timestamp']) && 
        (time() - $sessions[$chatId]['timestamp']) > 3600) {
        unset($sessions[$chatId]);
        file_put_contents($sessionFile, json_encode($sessions));
        return initSession();
    }
    
    return $sessions[$chatId];
}

function saveSession($chatId, $session) {
    global $sessionFile;
    
    $sessions = [];
    if (file_exists($sessionFile)) {
        $sessions = json_decode(file_get_contents($sessionFile), true) ?: [];
    }
    
    $session['timestamp'] = time();
    $sessions[$chatId] = $session;
    
    file_put_contents($sessionFile, json_encode($sessions));
}

function clearSession($chatId) {
    global $sessionFile;
    
    if (file_exists($sessionFile)) {
        $sessions = json_decode(file_get_contents($sessionFile), true) ?: [];
        unset($sessions[$chatId]);
        file_put_contents($sessionFile, json_encode($sessions));
    }
}

// ===================== VALIDATION FUNCTIONS =====================

function validateDateDDMMYYYY($date) {
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

function formatDateDisplay($date) {
    if (validateDateDDMMYYYY($date)) {
        $dateObj = DateTime::createFromFormat('d-m-Y', $date);
        return $dateObj->format('j F Y');
    }
    return $date;
}

// ===================== HEIR MENU FUNCTIONS =====================

function heirCategoryMenu($gender) {
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
    
    return keyboard($buttons);
}

function heirSelectionMenu($category, $gender) {
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
    return keyboard($buttons);
}

function heirCountKeyboard($heir) {
    switch ($heir) {
        case 'Husband':
            return keyboard([['0', '1']]);
            
        case 'Wife':
            return keyboard([['0', '1', '2', '3', '4']]);
            
        case 'Father':
        case 'Mother':
        case 'Grandfather':
        case 'Grandmother (Father Side)':
        case 'Grandmother (Mother Side)':
            return keyboard([['0', '1']]);
            
        default:
            return keyboard([['0', '1', '2', '3', '4', '5']]);
    }
}

function heirKey($displayName) {
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

// ===================== ASSET FUNCTIONS =====================

function showAssetSummary($chatId, $session) {
    $assets = $session['data']['assets'];
    
    if (empty($assets)) {
        sendMessage($chatId,
            "📊 <b>ASSET SUMMARY</b>\n\n" .
            "No assets added yet.\n\n" .
            "Add your first asset to continue.",
            keyboard([
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
    
    sendMessage($chatId, $message,
        keyboard([
            ['➕ Add Another Asset'],
            ['📜 Calculate Inheritance']
        ])
    );
}

// ===================== FARAID CALCULATION FUNCTIONS =====================

function showResult($chatId, $data, $telegramUser = []) {
    // Calculate total estate
    $totalEstate = 0;
    foreach ($data['assets'] as $asset) {
        $totalEstate += $asset['value'];
    }
    
    if ($totalEstate <= 0) {
        sendMessage($chatId,
            "⚠️ <b>No Assets Found!</b>\n\n" .
            "Total estate value is RM 0.00\n\n" .
            "Please add assets to calculate inheritance distribution.",
            removeKeyboard()
        );
        return;
    }
    
    sendMessage($chatId, "🧮 Calculating inheritance distribution...");
    
    // Apply Mahjub rules
    $heirs = applyMahjub($data['heirs']);
    
    // Calculate shares
    $distribution = calculateInheritance($heirs, $totalEstate, $data['gender']);
    
    // Generate result message
    $message = "📜 <b>FARAID INHERITANCE RESULTS</b>\n\n";
    $message .= "👤 <b>Deceased:</b> {$data['name']}\n";
    $message .= "📅 " . formatDateDisplay($data['date']) . "\n";
    $message .= "⚧ " . ucfirst($data['gender']) . " | 💍 " . ucfirst($data['marital']) . "\n";
    $message .= "💰 <b>Total Estate:</b> RM " . number_format($totalEstate, 2) . "\n\n";
    
    $message .= "👨‍👩‍👧 <b>FAMILY STRUCTURE</b>\n";
    $message .= generateFamilyTree($data) . "\n";
    
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
    
    // Send initial results
    sendMessage($chatId, $message);
    
    // Generate and send family tree image
    sendMessage($chatId, "🌳 Generating family tree visualization...");
    
    $familyTreeImage = generateFamilyTreeImage($data, $chatId);
    
    if ($familyTreeImage && file_exists($familyTreeImage)) {
        sendPhoto($chatId, $familyTreeImage, "🌳 <b>Family Tree Visualization</b>\n👤 Deceased: {$data['name']}");
        logMessage("✅ Family tree image sent to chat {$chatId}");
    } else {
        sendMessage($chatId, "⚠️ Could not generate family tree image. Text version shown above.");
        logMessage("❌ Failed to generate family tree image for chat {$chatId}");
    }
    
    // Save to database
    sendMessage($chatId, "💾 Saving calculation to database...");
    
    $calculationId = saveCalculationToDatabase($data, $telegramUser, $totalEstate, $distributionData, $chatId);
    
    if ($calculationId) {
        sendMessage($chatId,
            "✅ Calculation saved successfully!\n" .
            "📋 ID: #{$calculationId}\n" .
            "🤖 Method: telegram_bot\n\n" .
            "Type /calculate to start a new calculation.",
            removeKeyboard()
        );
    } else {
        // Error message will be sent from within saveCalculationToDatabase()
        sendMessage($chatId, 
            "⚠️ <b>Calculation complete but save failed.</b>\n\n" .
            "You can start a new calculation with /calculate",
            removeKeyboard()
        );
    }
}

// ===================== FAMILY TREE IMAGE GENERATION =====================

function generateFamilyTreeImage($data, $chatId) {
    $heirs = $data['heirs'];
    $name = $data['name'];
    $gender = $data['gender'];
    $marital = $data['marital'];
    
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
    
    // Fill background
    imagefilledrectangle($image, 0, 0, $width, $height, $white);
    
    // Load font (using built-in GD font)
    $font = 5; // Built-in GD font
    $titleFont = 5;
    
    // Title
    imagestring($image, $titleFont, $width/2 - 100, 20, "FAMILY TREE - {$name}", $black);
    imagestring($image, $font, $width/2 - 80, 45, "(" . ucfirst($gender) . " | " . ucfirst($marital) . ")", $gray);
    
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
            drawHeirBox($image, $xPosition, $yPosition, "Husband", $heirs['husband'], $lightBlue, $font);
            $xPosition += 180;
        }
        
        if ($heirs['wife'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Wife", $heirs['wife'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        $yPosition += 100;
        $xPosition = 50;
    }
    
    // CHILDREN SECTION
    if ($heirs['son'] > 0 || $heirs['daughter'] > 0) {
        imagestring($image, $font, $xPosition, $yPosition - 20, "CHILDREN", $blue);
        
        if ($heirs['son'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Son", $heirs['son'], $lightBlue, $font);
            $xPosition += 180;
        }
        
        if ($heirs['daughter'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Daughter", $heirs['daughter'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        $yPosition += 100;
        $xPosition = 50;
    }
    
    // PARENTS SECTION
    if ($heirs['father'] > 0 || $heirs['mother'] > 0) {
        imagestring($image, $font, $xPosition, $yPosition - 20, "PARENTS", $blue);
        
        if ($heirs['father'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Father", $heirs['father'], $lightBlue, $font);
            $xPosition += 180;
        }
        
        if ($heirs['mother'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Mother", $heirs['mother'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        $yPosition += 100;
        $xPosition = 50;
    }
    
    // SIBLINGS SECTION
    $hasSiblings = $heirs['brother'] > 0 || $heirs['sister'] > 0 || 
                   $heirs['half_brother_paternal'] > 0 || $heirs['half_brother_maternal'] > 0 ||
                   $heirs['half_sister_paternal'] > 0 || $heirs['half_sister_maternal'] > 0;
    
    if ($hasSiblings) {
        imagestring($image, $font, $xPosition, $yPosition - 20, "SIBLINGS", $blue);
        
        if ($heirs['brother'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Brother", $heirs['brother'], $lightBlue, $font);
            $xPosition += 180;
        }
        
        if ($heirs['sister'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Sister", $heirs['sister'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        if ($heirs['half_brother_paternal'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Half-Brother (P)", $heirs['half_brother_paternal'], $lightBlue, $font);
            $xPosition += 180;
        }
        
        if ($heirs['half_sister_paternal'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Half-Sister (P)", $heirs['half_sister_paternal'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        if ($xPosition > 800) {
            $yPosition += 100;
            $xPosition = 50;
        }
        
        if ($heirs['half_brother_maternal'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Half-Brother (M)", $heirs['half_brother_maternal'], $lightBlue, $font);
            $xPosition += 180;
        }
        
        if ($heirs['half_sister_maternal'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Half-Sister (M)", $heirs['half_sister_maternal'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        $yPosition += 100;
        $xPosition = 50;
    }
    
    // GRANDPARENTS SECTION
    if ($heirs['grandfather'] > 0 || $heirs['grandmother_father'] > 0 || $heirs['grandmother_mother'] > 0) {
        imagestring($image, $font, $xPosition, $yPosition - 20, "GRANDPARENTS", $blue);
        
        if ($heirs['grandfather'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Grandfather", $heirs['grandfather'], $lightBlue, $font);
            $xPosition += 180;
        }
        
        if ($heirs['grandmother_father'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Grandmother (F)", $heirs['grandmother_father'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        if ($heirs['grandmother_mother'] > 0) {
            drawHeirBox($image, $xPosition, $yPosition, "Grandmother (M)", $heirs['grandmother_mother'], $lightGreen, $font);
            $xPosition += 180;
        }
        
        $yPosition += 100;
    }
    
    // Draw connecting lines from deceased to sections
    $lineY = 180;
    if ($heirs['husband'] > 0 || $heirs['wife'] > 0) {
        imageline($image, $centerX, $lineY, $centerX, 230, $black);
        imageline($image, $centerX, 230, 100, 230, $black);
        imageline($image, 100, 230, 100, 250, $black);
    }
    
    // Footer
    imagestring($image, $font, 20, $height - 30, "Generated by Neo Faraid Calculator", $gray);
    imagestring($image, $font, $width - 200, $height - 30, date('Y-m-d H:i:s'), $gray);
    
    // Save image
    $imageDir = __DIR__ . '/family_trees/';
    if (!is_dir($imageDir)) mkdir($imageDir, 0755, true);
    
    $filename = $imageDir . 'family_tree_' . $chatId . '_' . time() . '.png';
    imagepng($image, $filename);
    imagedestroy($image);
    
    return $filename;
}

function drawHeirBox($image, $x, $y, $label, $count, $color, $font) {
    $black = imagecolorallocate($image, 0, 0, 0);
    
    // Draw box
    imagefilledrectangle($image, $x, $y, $x + 150, $y + 60, $color);
    imagerectangle($image, $x, $y, $x + 150, $y + 60, $black);
    
    // Draw text
    imagestring($image, $font, $x + 10, $y + 10, $label, $black);
    imagestring($image, $font, $x + 10, $y + 30, "Count: " . $count, $black);
    
    // Add icon based on gender
    if (strpos($label, 'Husband') !== false || strpos($label, 'Father') !== false || 
        strpos($label, 'Son') !== false || strpos($label, 'Brother') !== false ||
        strpos($label, 'Grandfather') !== false) {
        imagestring($image, $font, $x + 120, $y + 10, "♂", imagecolorallocate($image, 0, 0, 200));
    } elseif (strpos($label, 'Wife') !== false || strpos($label, 'Mother') !== false || 
              strpos($label, 'Daughter') !== false || strpos($label, 'Sister') !== false ||
              strpos($label, 'Grandmother') !== false) {
        imagestring($image, $font, $x + 120, $y + 10, "♀", imagecolorallocate($image, 200, 0, 100));
    }
}

function applyMahjub($heirs) {
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

function calculateInheritance($heirs, $estate, $gender) {
    $distribution = [];
    $totalShares = 0;
    
    $addDistribution = function($heirName, $share, $status, $type = 'Unknown') use (&$distribution, $estate, $gender) {
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
            'share' => formatFraction($share),
            'fraction' => $share,
            'amount' => $amount,
            'percentage' => $percentage,
            'status' => $status,
            'type' => $type,
            'display' => "👥 <b>{$heirName}</b> ({$relationship})\n" .
                        "Share: " . formatFraction($share) . " (" . round($percentage, 2) . "%)\n" .
                        "Amount: RM " . number_format($amount, 2) . "\n" .
                        "Status: {$status}\n" .
                        str_repeat("─", 30)
        ];
    };
    
    // SPOUSE SHARES
    if ($heirs['husband'] > 0) {
        if (hasChildren($heirs)) {
            $share = 1/4;
        } else {
            $share = 1/2;
        }
        $addDistribution('Husband', $share, 'Fixed Share', 'Fixed Share');
        $totalShares += $share;
    }
    
    if ($heirs['wife'] > 0) {
        if (hasChildren($heirs)) {
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
        if (hasChildren($heirs)) {
            $share = 1/6;
        } else {
            $share = 1/3;
        }
        $addDistribution('Mother', $share, 'Fixed Share', 'Fixed Share');
        $totalShares += $share;
    }
    
    if ($heirs['father'] > 0) {
        if (hasChildren($heirs)) {
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
    if (hasChildren($heirs)) {
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
    if (!hasChildren($heirs) && $totalShares < 1) {
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

function hasChildren($heirs) {
    return ($heirs['son'] > 0 || $heirs['daughter'] > 0);
}

function formatFraction($decimal) {
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

function generateFamilyTree($data) {
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

// ===================== DATABASE FUNCTIONS =====================

/**
 * SAVE CALCULATION TO DATABASE - FIXED VERSION WITH ALL FIXES INCLUDING FOREIGN KEY FIX
 */
function saveCalculationToDatabase($data, $telegramUser, $totalEstate, $distributionData, $chatId)
{
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

        // Convert date DD-MM-YYYY → YYYY-MM-DD
        $dateObj = DateTime::createFromFormat('d-m-Y', $data['date']);
        $dateOfDeath = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');

        $heirs = $data['heirs'];
        $assets = $data['assets'];

        $calculationHash = 'CALC_TELEGRAM_' . time() . '_' . bin2hex(random_bytes(8));

        // Determine scenario
        $scenarioNumber = determineScenario($heirs);
        $scenarioDescription = getScenarioDescription($scenarioNumber);
        
        // Count eligible heirs (excluding Baitulmal)
        $eligibleHeirs = 0;
        foreach ($distributionData as $item) {
            if ($item['amount'] > 0 && stripos($item['heir'], 'Baitulmal') === false) {
                $eligibleHeirs++;
            }
        }
        
        // Count total heirs
        $totalHeirs = 0;
        foreach ($heirs as $count) {
            $totalHeirs += $count;
        }
        
        // ============================================================
        // FIX 4: Handle foreign key constraint issue
        // ============================================================
        // Option 1: Create a Telegram user in users table
        // Option 2: Use NULL for user_id (if column is nullable)
        // Option 3: Check if user exists first
        
        $telegramUserId = $telegramUser['id'] ?? 0;
        $userIdToSave = NULL; // Default to NULL
        
        if ($telegramUserId > 0) {
            // Try to find or create a user for Telegram
            $userIdToSave = findOrCreateTelegramUser($pdo, $telegramUser, $chatId);
        }
        
        // If we still don't have a valid user_id, use NULL
        if (!$userIdToSave) {
            $userIdToSave = NULL;
        }
        
        // Prepare SQL statement
        $sql = "
            INSERT INTO calculations (
                user_id,
                deceased_name,
                deceased_gender,
                date_of_death,
                marital_status,
                wife_count,
                husband_count,
                father_status,
                mother_status,
                son_count,
                daughter_count,
                full_brother_count,
                full_sister_count,
                paternal_brother_count,
                paternal_sister_count,
                maternal_brother_count,
                maternal_sister_count,
                total_assets,
                net_assets,
                total_heirs,
                eligible_heirs_count,
                distribution_summary,
                scenario_number,
                scenario_description,
                heirs_data,
                assets_data,
                calculation_data,
                calculation_hash,
                calculation_method,
                telegram_chat_id,
                telegram_user_info,
                created_at,
                updated_at
            ) VALUES (
                :user_id,
                :deceased_name,
                :deceased_gender,
                :date_of_death,
                :marital_status,
                :wife_count,
                :husband_count,
                :father_status,
                :mother_status,
                :son_count,
                :daughter_count,
                :full_brother_count,
                :full_sister_count,
                :paternal_brother_count,
                :paternal_sister_count,
                :maternal_brother_count,
                :maternal_sister_count,
                :total_assets,
                :net_assets,
                :total_heirs,
                :eligible_heirs_count,
                :distribution_summary,
                :scenario_number,
                :scenario_description,
                :heirs_data,
                :assets_data,
                :calculation_data,
                :calculation_hash,
                :calculation_method,
                :telegram_chat_id,
                :telegram_user_info,
                NOW(),
                NOW()
            )
        ";

        $stmt = $pdo->prepare($sql);
        
        // Execute with parameters
        $params = [
            ':user_id' => $userIdToSave, // FIX 4: Use NULL or valid user_id
            ':deceased_name' => $data['name'],
            ':deceased_gender' => $data['gender'],
            ':date_of_death' => $dateOfDeath,
            ':marital_status' => in_array($data['marital'], ['single','married']) ? $data['marital'] : 'single', // FIX 2
            ':wife_count' => $heirs['wife'] ?? 0,
            ':husband_count' => $heirs['husband'] ?? 0,
            ':father_status' => ($heirs['father'] ?? 0) > 0 ? 'alive' : 'none', // FIX 1
            ':mother_status' => ($heirs['mother'] ?? 0) > 0 ? 'alive' : 'none', // FIX 1
            ':son_count' => $heirs['son'] ?? 0,
            ':daughter_count' => $heirs['daughter'] ?? 0,
            ':full_brother_count' => $heirs['brother'] ?? 0,
            ':full_sister_count' => $heirs['sister'] ?? 0,
            ':paternal_brother_count' => $heirs['half_brother_paternal'] ?? 0,
            ':paternal_sister_count' => $heirs['half_sister_paternal'] ?? 0,
            ':maternal_brother_count' => $heirs['half_brother_maternal'] ?? 0,
            ':maternal_sister_count' => $heirs['half_sister_maternal'] ?? 0,
            ':total_assets' => $totalEstate,
            ':net_assets' => $totalEstate,
            ':total_heirs' => $totalHeirs,
            ':eligible_heirs_count' => $eligibleHeirs,
            ':distribution_summary' => json_encode($distributionData, JSON_UNESCAPED_UNICODE),
            ':scenario_number' => $scenarioNumber,
            ':scenario_description' => $scenarioDescription,
            ':heirs_data' => json_encode($heirs, JSON_UNESCAPED_UNICODE),
            ':assets_data' => json_encode($assets, JSON_UNESCAPED_UNICODE),
            ':calculation_data' => json_encode([
                'estate' => $totalEstate,
                'distribution' => $distributionData,
                'heirs' => $heirs
            ], JSON_UNESCAPED_UNICODE),
            ':calculation_hash' => $calculationHash,
            ':calculation_method' => 'telegram_bot',
            ':telegram_chat_id' => $chatId,
            ':telegram_user_info' => json_encode($telegramUser, JSON_UNESCAPED_UNICODE)
        ];
        
        $stmt->execute($params);

        $calculationId = $pdo->lastInsertId();
        
        logMessage("✅ Calculation saved successfully with ID: {$calculationId} for chat {$chatId}");
        logMessage("📊 User ID used: " . ($userIdToSave ?? 'NULL'));
        
        return $calculationId;

    } catch (PDOException $e) {
        // FIX 3: Temporary error visibility
        $errorMessage = $e->getMessage();
        file_put_contents(
            __DIR__ . '/logs/REAL_DB_ERROR.log',
            date('Y-m-d H:i:s') . "\n" .
            "User ID Attempted: " . ($telegramUserId ?? 'NULL') . "\n" .
            "Error: " . $errorMessage . "\n\n",
            FILE_APPEND
        );

        sendMessage(
            $chatId,
            "❌ <b>DATABASE ERROR</b>\n\n" .
            "Foreign key constraint violation.\n\n" .
            "Trying alternative method..."
        );
        
        // Try alternative: Insert with NULL user_id
        return tryAlternativeSave($data, $telegramUser, $totalEstate, $distributionData, $chatId, $errorMessage);
    }
}

/**
 * FIX 4A: Find or create Telegram user in users table
 */
function findOrCreateTelegramUser($pdo, $telegramUser, $chatId) {
    $telegramUserId = $telegramUser['id'] ?? 0;
    
    if ($telegramUserId <= 0) {
        return NULL;
    }
    
    try {
        // First, try to find existing user with this telegram_id
        $stmt = $pdo->prepare("SELECT id FROM users WHERE telegram_id = :telegram_id OR email = :telegram_email LIMIT 1");
        
        // Create a unique email for Telegram user
        $telegramEmail = "telegram_" . $telegramUserId . "@telegram.bot";
        
        $stmt->execute([
            ':telegram_id' => $telegramUserId,
            ':telegram_email' => $telegramEmail
        ]);
        
        $existingUser = $stmt->fetch();
        
        if ($existingUser && isset($existingUser['id'])) {
            return $existingUser['id'];
        }
        
        // No existing user found, create one
        $username = $telegramUser['username'] ?? 'telegram_user_' . $telegramUserId;
        $firstName = $telegramUser['first_name'] ?? '';
        $lastName = $telegramUser['last_name'] ?? '';
        $fullName = trim($firstName . ' ' . $lastName);
        if (empty($fullName)) $fullName = 'Telegram User';
        
        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO users (
                name, email, password, role, telegram_id, telegram_username, 
                telegram_chat_id, created_at, updated_at
            ) VALUES (
                :name, :email, :password, :role, :telegram_id, :telegram_username,
                :telegram_chat_id, NOW(), NOW()
            )
        ");
        
        $stmt->execute([
            ':name' => $fullName,
            ':email' => $telegramEmail,
            ':password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
            ':role' => 'telegram_user',
            ':telegram_id' => $telegramUserId,
            ':telegram_username' => $telegramUser['username'] ?? '',
            ':telegram_chat_id' => $chatId
        ]);
        
        $newUserId = $pdo->lastInsertId();
        
        logMessage("✅ Created new Telegram user with ID: {$newUserId} for Telegram ID: {$telegramUserId}");
        return $newUserId;
        
    } catch (PDOException $e) {
        logMessage("❌ Failed to create Telegram user: " . $e->getMessage());
        return NULL;
    }
}

/**
 * FIX 4B: Alternative save method if main one fails
 */
function tryAlternativeSave($data, $telegramUser, $totalEstate, $distributionData, $chatId, $originalError) {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

        // Convert date
        $dateObj = DateTime::createFromFormat('d-m-Y', $data['date']);
        $dateOfDeath = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');

        $heirs = $data['heirs'];
        $assets = $data['assets'];
        $calculationHash = 'CALC_TELEGRAM_' . time() . '_' . bin2hex(random_bytes(8));
        
        // SIMPLIFIED INSERT - Just the essentials
        $sql = "
            INSERT INTO calculations (
                deceased_name,
                deceased_gender,
                date_of_death,
                marital_status,
                total_assets,
                net_assets,
                heirs_data,
                assets_data,
                distribution_summary,
                calculation_hash,
                calculation_method,
                telegram_chat_id,
                telegram_user_info,
                created_at
            ) VALUES (
                :deceased_name,
                :deceased_gender,
                :date_of_death,
                :marital_status,
                :total_assets,
                :net_assets,
                :heirs_data,
                :assets_data,
                :distribution_summary,
                :calculation_hash,
                :calculation_method,
                :telegram_chat_id,
                :telegram_user_info,
                NOW()
            )
        ";

        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':deceased_name' => $data['name'],
            ':deceased_gender' => $data['gender'],
            ':date_of_death' => $dateOfDeath,
            ':marital_status' => in_array($data['marital'], ['single','married']) ? $data['marital'] : 'single',
            ':total_assets' => $totalEstate,
            ':net_assets' => $totalEstate,
            ':heirs_data' => json_encode($heirs, JSON_UNESCAPED_UNICODE),
            ':assets_data' => json_encode($assets, JSON_UNESCAPED_UNICODE),
            ':distribution_summary' => json_encode($distributionData, JSON_UNESCAPED_UNICODE),
            ':calculation_hash' => $calculationHash,
            ':calculation_method' => 'telegram_bot',
            ':telegram_chat_id' => $chatId,
            ':telegram_user_info' => json_encode($telegramUser, JSON_UNESCAPED_UNICODE)
        ]);

        $calculationId = $pdo->lastInsertId();
        
        logMessage("✅ Alternative save successful! ID: {$calculationId}");
        logMessage("📝 Original error was: {$originalError}");
        
        sendMessage(
            $chatId,
            "✅ <b>Calculation saved successfully!</b>\n" .
            "📋 ID: #{$calculationId}\n" .
            "📊 Used simplified save method."
        );
        
        return $calculationId;
        
    } catch (PDOException $e) {
        $finalError = "Alternative save also failed: " . $e->getMessage();
        
        file_put_contents(
            __DIR__ . '/logs/REAL_DB_ERROR.log',
            date('Y-m-d H:i:s') . "\n" .
            "ALTERNATIVE SAVE FAILED:\n" .
            "Original Error: {$originalError}\n" .
            "Alternative Error: " . $e->getMessage() . "\n\n",
            FILE_APPEND
        );

        sendMessage(
            $chatId,
            "❌ <b>DATABASE SAVE FAILED COMPLETELY</b>\n\n" .
            "Both methods failed.\n\n" .
            "Please contact administrator.\n\n" .
            "You can still view the calculation above."
        );
        
        return false;
    }
}

function determineScenario($heirs) {
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
    
    // Check for blocking
    if ($heirs['son'] > 0) return 10; // Blocking scenario
    
    return 15; // Default scenario
}

function getScenarioDescription($scenarioNumber) {
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

// ===================== UTILITY FUNCTIONS =====================

function logMessage($message) {
    global $logDir;
    
    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
    
    $logFile = $logDir . 'telegram_bot.log';
    file_put_contents($logFile, 
        date('Y-m-d H:i:s') . " - " . $message . "\n", 
        FILE_APPEND
    );
    
    // Also log to error log for debugging
    error_log("Telegram Bot: " . $message);
}

function handleSetupRequest() {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'setwebhook':
            setWebhook();
            break;
            
        case 'info':
            getBotInfo();
            break;
            
        case 'test':
            testConnection();
            break;
            
        default:
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid action',
                'available_actions' => ['setwebhook', 'info', 'test']
            ]);
    }
}

function setWebhook() {
    $webhookUrl = 'https://rosybrown-skunk-415825.hostingersite.com/neofaraid-webhook.php';
    $url = BOT_API_URL . 'setWebhook?url=' . urlencode($webhookUrl) . '&drop_pending_updates=true';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($result, true);
    
    if ($response['ok'] ?? false) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Webhook set successfully!',
            'url' => $webhookUrl,
            'telegram_response' => $response
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to set webhook',
            'telegram_response' => $response
        ]);
    }
}

function getBotInfo() {
    $url = BOT_API_URL . 'getMe';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($result, true);
    
    if ($response['ok'] ?? false) {
        echo json_encode([
            'status' => 'success',
            'bot_info' => $response['result'],
            'webhook_url' => 'https://rosybrown-skunk-415825.hostingersite.com/neofaraid-webhook.php',
            'bot_token' => substr(BOT_TOKEN, 0, 10) . '...' . substr(BOT_TOKEN, -4)
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to get bot info',
            'response' => $response
        ]);
    }
}

function testConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Database connection successful!',
            'database' => DB_NAME,
            'host' => DB_HOST
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage()
        ]);
    }
}
?>