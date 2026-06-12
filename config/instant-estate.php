<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */
    'max_file_size' => env('INSTANT_ESTATE_MAX_FILE_SIZE', 10), // MB
    'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png', 'webp'],
    'allowed_mime_types' => [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/webp',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Session Settings
    |--------------------------------------------------------------------------
    */
    'session_expiry_hours' => env('INSTANT_ESTATE_SESSION_EXPIRY', 24),
    'session_cleanup_days' => env('INSTANT_ESTATE_CLEANUP_DAYS', 7),
    
    /*
    |--------------------------------------------------------------------------
    | OCR Settings
    |--------------------------------------------------------------------------
    */
    'ocr' => [
        'api_key' => env('OCR_SPACE_API_KEY'),
        'api_url' => env('OCR_SPACE_API_URL', 'https://api.ocr.space/parse/image'),
        'language' => env('OCR_LANGUAGE', 'msa'),
        'timeout' => env('OCR_TIMEOUT', 60),
        'fallback_to_tesseract' => env('OCR_FALLBACK_TO_TESSERACT', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Processing Settings
    |--------------------------------------------------------------------------
    */
    'processing' => [
        'queue' => env('INSTANT_ESTATE_QUEUE', 'default'),
        'retry_attempts' => env('INSTANT_ESTATE_RETRY_ATTEMPTS', 3),
        'retry_delay_seconds' => env('INSTANT_ESTATE_RETRY_DELAY', 60),
        'timeout_seconds' => env('INSTANT_ESTATE_TIMEOUT', 300),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => env('INSTANT_ESTATE_NOTIFICATIONS', true),
        'beneficiary_link_expiry_days' => env('BENEFICIARY_LINK_EXPIRY_DAYS', 30),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'delete_files_after_days' => env('INSTANT_ESTATE_DELETE_FILES_AFTER', 7),
        'encrypt_stored_data' => env('INSTANT_ESTATE_ENCRYPT_DATA', true),
    ],
];