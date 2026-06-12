<?php

/**
 * Laravel CLI Mode Helpers
 * Prevents UrlGenerator errors when running Artisan commands
 */

if (!function_exists('safe_route')) {
    /**
     * Generate a URL for a named route safely without throwing exceptions in CLI
     */
    function safe_route($name, $parameters = [], $absolute = true)
    {
        if (app()->runningInConsole()) {
            return '/';
        }
        
        try {
            return route($name, $parameters, $absolute);
        } catch (\Exception $e) {
            return '/';
        }
    }
}

if (!function_exists('safe_url')) {
    /**
     * Generate a URL safely
     */
    function safe_url($path = null, $parameters = [], $secure = null)
    {
        if (app()->runningInConsole()) {
            return '/' . ltrim($path ?? '', '/');
        }
        
        try {
            return url($path, $parameters, $secure);
        } catch (\Exception $e) {
            return '/' . ltrim($path ?? '', '/');
        }
    }
}

if (!function_exists('safe_asset')) {
    /**
     * Generate an asset path safely
     */
    function safe_asset($path, $secure = null)
    {
        if (app()->runningInConsole()) {
            return '/' . ltrim($path, '/');
        }
        
        try {
            return asset($path, $secure);
        } catch (\Exception $e) {
            return '/' . ltrim($path, '/');
        }
    }
}

if (!function_exists('is_artisan')) {
    /**
     * Check if running in Artisan console
     */
    function is_artisan()
    {
        return app()->runningInConsole();
    }
}