<?php
// config/estate.php

return [
    /*
    |--------------------------------------------------------------------------
    | Access Link Expiry Days
    |--------------------------------------------------------------------------
    */
    'access_link_expiry_days' => env('ESTATE_ACCESS_LINK_EXPIRY_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Maximum Wasiyyah Percentage (1/3 of estate)
    |--------------------------------------------------------------------------
    */
    'max_wasiyyah_percentage' => 33.33,

    /*
    |--------------------------------------------------------------------------
    | Email Settings for Notifications
    |--------------------------------------------------------------------------
    */
    'email' => [
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@neofaraid.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Neo Faraid'),
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Settings
    |--------------------------------------------------------------------------
    */
    'pdf' => [
        'paper_size' => 'a4',
        'orientation' => 'portrait',
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Settings
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'connection' => env('ESTATE_QUEUE_CONNECTION', 'database'),
        'notifications_queue' => 'notifications',
    ],
];