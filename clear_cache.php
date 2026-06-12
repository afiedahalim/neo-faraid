<?php
// clear_cache.php - Run with: php clear_cache.php

echo "=== FIXING LARAVEL CACHE ===\n\n";

// Function to recursively delete files
function deleteFiles($path, $pattern = '*') {
    if (!is_dir($path)) return 0;
    $count = 0;
    $files = glob($path . '/' . $pattern);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            echo "Deleted: " . basename($file) . "\n";
            $count++;
        }
    }
    return $count;
}

// Function to delete directory contents
function clearDirectory($path) {
    if (!is_dir($path)) return 0;
    $count = 0;
    $files = glob($path . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        } elseif (is_dir($file) && basename($file) !== '.gitignore') {
            clearDirectory($file);
            rmdir($file);
        }
    }
    return $count;
}

// Delete bootstrap cache
$bootstrapCache = __DIR__ . '/bootstrap/cache';
if (is_dir($bootstrapCache)) {
    $files = ['config.php', 'routes.php', 'services.php', 'packages.php'];
    foreach ($files as $file) {
        $path = $bootstrapCache . '/' . $file;
        if (file_exists($path)) {
            unlink($path);
            echo "Deleted: bootstrap/cache/$file\n";
        }
    }
}

// Delete compiled views
$viewCount = deleteFiles(__DIR__ . '/storage/framework/views', '*.php');
echo "Deleted $viewCount view files\n";

// Delete cache data
$cacheCount = clearDirectory(__DIR__ . '/storage/framework/cache/data');
echo "Deleted $cacheCount cache files\n";

// Delete session files
$sessionCount = deleteFiles(__DIR__ . '/storage/framework/sessions', '*');
echo "Deleted $sessionCount session files\n";

// Delete compiled Blade templates
$bladeCount = deleteFiles(__DIR__ . '/storage/framework/views', '*.blade.php');
echo "Deleted $bladeCount blade cache files\n";

echo "\n=== CACHE CLEARED ===\n";
echo "Now run: php artisan config:clear\n";
echo "Then: php artisan optimize\n";