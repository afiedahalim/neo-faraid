<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\TestFamilyTree::class,
        Commands\GenerateAllFamilyTrees::class,
        //Commands\TelegramSetup::class,
        //Commands\TelegramPoll::class,
        //Commands\TelegramSendMessage::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

    /**
     * Bootstrap the application for console commands.
     * Override to fix URL generator issue in CLI mode.
     */
    public function bootstrap()
    {
        // Fix for URL Generator in CLI mode
        if (php_sapi_name() === 'cli') {
            // Create a fake request if none exists
            if (!request() && !$this->app->bound('request')) {
                $request = Request::create('/', 'GET', [], [], [], [
                    'REQUEST_URI' => '/',
                    'HTTP_HOST' => parse_url(env('APP_URL', 'http://neo-faraid.test'), PHP_URL_HOST) ?: 'neo-faraid.test',
                    'SERVER_NAME' => 'neo-faraid.test',
                    'SERVER_PORT' => 80,
                    'REMOTE_ADDR' => '127.0.0.1',
                ]);
                $this->app->instance('request', $request);
            }
        }
        
        parent::bootstrap();
    }
}