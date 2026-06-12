<?php
// run_artisan.php - Run Artisan commands with proper request context
// Usage: php run_artisan.php [command] [arguments]

// Set fake server variables for CLI mode BEFORE anything else
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '/';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'neo-faraid.test';
$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? 'neo-faraid.test';
$_SERVER['SERVER_PORT'] = $_SERVER['SERVER_PORT'] ?? 80;
$_SERVER['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$_SERVER['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $_SERVER['SCRIPT_FILENAME'] ?? __DIR__ . '/public/index.php';

// Define a constant to indicate we're in CLI mode with fake request
define('ARTISAN_WITH_FAKE_REQUEST', true);

// Load Composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Create the Laravel application
$app = require __DIR__ . '/bootstrap/app.php';

// Create a fake request and bind it to the container
$request = \Illuminate\Http\Request::create('/', 'GET', [], [], [], $_SERVER);
$app->instance('request', $request);

// Also bind to the request facade
\Illuminate\Support\Facades\Request::swap($request);

// Get the console kernel
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Bootstrap the kernel
$kernel->bootstrap();

// Get the command from command line arguments
$command = $argv[1] ?? 'list';
$parameters = array_slice($argv, 2);

// Run the command
$status = $kernel->call($command, $parameters);

// Output the result
echo $kernel->output();

// Terminate the kernel
$kernel->terminate($command, $status);

exit($status);