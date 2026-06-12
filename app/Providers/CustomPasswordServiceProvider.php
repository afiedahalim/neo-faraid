<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CustomPasswordBroker;
use Illuminate\Auth\Passwords\PasswordBrokerManager;

class CustomPasswordServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Extend the password broker
        $this->app->extend('auth.password.broker', function ($broker, $app) {
            return new CustomPasswordBroker(
                $app['auth.password']->createTokenRepository($app['config']['auth.passwords.users']),
                $app['auth']->createUserProvider('users')
            );
        });
    }
}