<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Models\Product::find(13);

if (!$product) {
    echo "Product 13 not found\n";
    exit;
}

echo "Product ID: " . $product->id . "\n";
echo "Name: " . $product->name . "\n";
echo "Category: " . $product->category . "\n";
echo "File Path: " . ($product->file_path ?? 'NULL') . "\n";
echo "File Type: " . ($product->file_type ?? 'NULL') . "\n";
echo "File Size: " . ($product->file_size ?? 'NULL') . " KB\n";

if ($product->file_path) {
    $fullPath = storage_path('app/private/' . $product->file_path);
    echo "\nFull Path: " . $fullPath . "\n";
    echo "File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    
    if (file_exists($fullPath)) {
        echo "Actual File Size: " . round(filesize($fullPath) / 1024) . " KB\n";
    }
}

echo "\nChecking storage directory:\n";
$privateDir = storage_path('app/private');
echo "Private Dir: " . $privateDir . "\n";
echo "Dir Exists: " . (is_dir($privateDir) ? 'YES' : 'NO') . "\n";

if (is_dir($privateDir)) {
    $files = scandir($privateDir);
    echo "Files in private:\n";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - " . $file . "\n";
        }
    }
}
