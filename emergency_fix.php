<?php
// emergency_fix.php - Emergency fix to clear all caches manually

echo "=== EMERGENCY CACHE CLEAR ===\n\n";

// Function to delete files recursively
function deleteFiles($path, $pattern = '*') {
    if (!is_dir($path)) return 0;
    $count = 0;
    $files = glob($path . '/' . $pattern);
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== '.gitignore') {
            unlink($file);
            echo "Deleted: " . basename($file) . "\n";
            $count++;
        }
    }
    return $count;
}

// Delete bootstrap cache files
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
$cacheCount = deleteFiles(__DIR__ . '/storage/framework/cache/data', '*');
echo "Deleted $cacheCount cache files\n";

// Delete session files
$sessionCount = deleteFiles(__DIR__ . '/storage/framework/sessions', '*');
echo "Deleted $sessionCount session files\n";

// Make sure directories are writable
$directories = [
    __DIR__ . '/bootstrap/cache',
    __DIR__ . '/storage/framework/cache',
    __DIR__ . '/storage/framework/cache/data',
    __DIR__ . '/storage/framework/sessions',
    __DIR__ . '/storage/framework/views',
    __DIR__ . '/storage/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "Created directory: " . str_replace(__DIR__, '', $dir) . "\n";
    }
}

echo "\n=== ALL CACHES CLEARED ===\n";
echo "Now try: php run_artisan.php about\n";