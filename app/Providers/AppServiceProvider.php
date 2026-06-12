<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Fix the URL generator for console commands
        if ($this->app->runningInConsole()) {
            $this->app->bind('url', function ($app) {
                $routes = $app['router']->getRoutes();
                
                // Create a fake request for CLI mode
                $request = Request::create(
                    $_SERVER['REQUEST_URI'] ?? '/',
                    $_SERVER['REQUEST_METHOD'] ?? 'GET',
                    [],
                    [],
                    [],
                    $_SERVER
                );
                
                return new UrlGenerator(
                    $routes,
                    $request,
                    $app['config']['app.asset_url'] ?? null
                );
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL
        Schema::defaultStringLength(191);
        
        // Only force HTTPS in production and not in console
        if ($this->app->environment('production') && !$this->app->runningInConsole()) {
            URL::forceScheme('https');
        }
        
        // Share background type with all views
        view()->share('backgroundType', 'animated');
        
        // Register a Blade directive
        Blade::directive('background', function ($expression) {
            // Remove quotes
            $type = trim($expression, " '\"");
            return "<?php echo safeGenerateBackground($expression); ?>";
        });
    }
}