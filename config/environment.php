<?php
// config/environment.php - Environment validation helper

return [
    'required_variables' => [
        'APP_NAME',
        'APP_ENV',
        'APP_KEY',
        'APP_URL',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_DATABASE',
        'DB_USERNAME',
    ],
    
    'validation_rules' => [
        'APP_ENV' => ['local', 'production', 'staging', 'testing'],
        'APP_DEBUG' => ['true', 'false'],
        'DB_CONNECTION' => ['mysql', 'pgsql', 'sqlite'],
        'LOG_CHANNEL' => ['stack', 'single', 'daily', 'slack'],
    ],
    
    'defaults' => [
        'APP_TIMEZONE' => 'UTC',
        'APP_LOCALE' => 'en',
        'SESSION_DRIVER' => 'file',
        'CACHE_DRIVER' => 'file',
    ],
    
    'production_overrides' => [
        'APP_DEBUG' => false,
        'LOG_LEVEL' => 'error',
        'SESSION_SECURE_COOKIE' => true,
        'DEBUG_BAR_ENABLED' => false,
        'CLOCKWORK_ENABLED' => false,
    ],
];