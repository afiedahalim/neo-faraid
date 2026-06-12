<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Configuration
    |--------------------------------------------------------------------------
    */
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN', '8039580931:AAHJhyuPoNnXPbIW_S-DMEj6nf2vUfvlyFQ'),
        'bot_username' => env('TELEGRAM_BOT_USERNAME', 'FaraidCalculatorBot'),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET', 'neo_faraid_secret_2024_!@#'),
        'webhook_url' => env('TELEGRAM_WEBHOOK_URL', 'http://neo-faraid.test/telegram/webhook'),
    ],

    /*
    |--------------------------------------------------------------------------
    | OCR.Space Cloud API Configuration
    |--------------------------------------------------------------------------
    */
    'ocr' => [
        'api_key' => env('OCR_SPACE_API_KEY', ''), // Leave empty to use local Tesseract
        'api_url' => env('OCR_API_URL', 'https://api.ocr.space/parse/image'),
        'timeout' => env('OCR_TIMEOUT', 60),
        'language' => env('OCR_LANGUAGE', 'msa'),
        'engine' => env('OCR_ENGINE', 2),
        'fallback_to_tesseract' => env('OCR_FALLBACK_TO_TESSERACT', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Local Tesseract OCR Configuration
    |--------------------------------------------------------------------------
    */
    'tesseract' => [
        // Windows paths
        'path' => env('TESSERACT_PATH', 'C:\Program Files\Tesseract-OCR\tesseract.exe'),
        
        // Linux/Mac paths (uncomment if needed)
        // 'path' => env('TESSERACT_PATH', '/usr/bin/tesseract'),
        
        'language' => env('TESSERACT_LANGUAGE', 'msa+eng'), // Malay + English
        'psm' => env('TESSERACT_PSM', 3), // Page segmentation mode (3=automatic)
        'oem' => env('TESSERACT_OEM', 3), // OCR Engine mode (3=default)
        
        // Additional Tesseract options
        'options' => [
            'preserve_interword_spaces' => true,
            'output_format' => 'txt',
            'tessdata_dir' => env('TESSERACT_TESSDATA_DIR', ''),
        ],
        
        // Character whitelist/blacklist (optional)
        'whitelist' => env('TESSERACT_WHITELIST', ''),
        'blacklist' => env('TESSERACT_BLACKLIST', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | ImageMagick Configuration for Image Preprocessing
    |--------------------------------------------------------------------------
    */
    'imagick' => [
        // Windows paths
        'path' => env('IMAGEMAGICK_PATH', 'C:\Program Files\ImageMagick-7.1.2-Q16-HDRI\magick.exe'),
        
        // Linux/Mac paths (uncomment if needed)
        // 'path' => env('IMAGEMAGICK_PATH', '/usr/bin/magick'),
        
        // Alternative Windows path (older versions)
        // 'path' => env('IMAGEMAGICK_PATH', 'C:\Program Files\ImageMagick-7.1.2-Q16\magick.exe'),
        
        'enabled' => env('IMAGE_PREPROCESSING_ENABLED', true),
        'dpi' => env('IMAGE_PREPROCESSING_DPI', 300),
        'deskew' => env('IMAGE_PREPROCESSING_DESKEW', true),
        'contrast' => env('IMAGE_PREPROCESSING_CONTRAST', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    */
    'whatsapp' => [
        'api_key' => env('WHATSAPP_API_KEY'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'base_url' => env('WHATSAPP_BASE_URL', 'https://graph.facebook.com/v17.0'),
        'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'neo_faraid_whatsapp_2024'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'driver' => env('SMS_DRIVER', 'log'),
        'api_key' => env('SMS_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID', 'NEOFARAID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    */
    'payment' => [
        'driver' => env('PAYMENT_DRIVER', 'dummy'),
        'public_key' => env('PAYMENT_PUBLIC_KEY'),
        'secret_key' => env('PAYMENT_SECRET_KEY'),
        'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Login Configurations
    |--------------------------------------------------------------------------
    */
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/auth/google/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', env('APP_URL') . '/auth/facebook/callback'),
    ],

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    */
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'enabled' => env('RECAPTCHA_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Meilisearch Configuration (Scout Driver)
    |--------------------------------------------------------------------------
    */
    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
        'key' => env('MEILISEARCH_KEY'),
        'index_prefix' => env('MEILISEARCH_INDEX_PREFIX', 'neo_faraid_'),
    ],

    /*
    |--------------------------------------------------------------------------
    | External APIs
    |--------------------------------------------------------------------------
    */
    'calculation_api' => [
        'url' => env('CALCULATION_API_URL'),
        'key' => env('CALCULATION_API_KEY'),
    ],

    'weather' => [
        'key' => env('WEATHER_API_KEY'),
    ],

    'maps' => [
        'key' => env('MAP_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | CDN Configuration
    |--------------------------------------------------------------------------
    */
    'cdn' => [
        'enabled' => env('CDN_ENABLED', false),
        'url' => env('CDN_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Service Configuration
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'enabled' => env('BACKUP_ENABLED', false),
        'schedule' => env('BACKUP_SCHEDULE', 'daily'),
        'disk' => env('BACKUP_DISK', 'local'),
        'keep_days' => env('BACKUP_KEEP_DAYS', 7),
    ],

    /*
    |--------------------------------------------------------------------------
    | JWT Configuration
    |--------------------------------------------------------------------------
    */
    'jwt' => [
        'secret' => env('JWT_SECRET'),
        'ttl' => env('JWT_TTL', 60),
        'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
        'algo' => env('JWT_ALGO', 'HS256'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags Configuration
    |--------------------------------------------------------------------------
    */
    'features' => [
        'telegram_bot' => env('FEATURE_TELEGRAM_BOT', true),
        'whatsapp_bot' => env('FEATURE_WHATSAPP_BOT', false),
        'sms_notification' => env('FEATURE_SMS_NOTIFICATION', false),
        'email_notification' => env('FEATURE_EMAIL_NOTIFICATION', true),
        'report_export' => env('FEATURE_REPORT_EXPORT', true),
        'multi_currency' => env('FEATURE_MULTI_CURRENCY', false),
        'ai_recommendations' => env('FEATURE_AI_RECOMMENDATIONS', false),
        'instant_estate' => env('FEATURE_INSTANT_ESTATE', true),
        'local_ocr' => env('FEATURE_LOCAL_OCR', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | SQLYOQ Integration (Estate Plan Activation) - FIXED
    |--------------------------------------------------------------------------
    */
    'slyqoiq' => [
        // FIXED: Use env() instead of route() helper - route() cannot be used in config files
        'activation_url' => env('SLYQOIQ_ACTIVATION_URL', '/estate-setup/activated'),
        'api_key' => env('SLYQOIQ_API_KEY'),
        'webhook_secret' => env('SLYQOIQ_WEBHOOK_SECRET'),
        'enabled' => env('SLYQOIQ_ENABLED', false),
    ],

];