<?php

/*
|--------------------------------------------------------------------------
| FIX: Prevent URL Generator Error During CLI/Artisan Commands
|--------------------------------------------------------------------------
*/

// Create a fake request for CLI mode first thing
if (php_sapi_name() === 'cli') {
    // Set fake server variables
    $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/';
    $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'neo-faraid.test';
    $_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? 'neo-faraid.test';
    $_SERVER['SERVER_PORT'] = $_SERVER['SERVER_PORT'] ?? 80;
    $_SERVER['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
    // Create a fake request and bind it to the container before anything else
    $request = \Illuminate\Http\Request::create('/', 'GET');
    \Illuminate\Support\Facades\Facade::clearResolvedInstances();
    
    // Alternative: Use the container to bind the request
    if (!isset($app)) {
        // We'll bind it after creating the app
    }
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Add custom binding for URL generator to use a fake request in CLI
$app->bind('url', function ($app) {
    $routes = $app['router']->getRoutes();
    
    // Create a request from the global server variables
    $request = \Illuminate\Http\Request::capture();
    
    return new \Illuminate\Routing\UrlGenerator(
        $routes,
        $request,
        $app['config']['app.asset_url'] ?? null
    );
});

// Add a binding for the request in CLI mode
if (php_sapi_name() === 'cli') {
    $app->bind('request', function ($app) {
        return \Illuminate\Http\Request::create('/', 'GET', [], [], [], $_SERVER);
    });
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
*/

return $app;