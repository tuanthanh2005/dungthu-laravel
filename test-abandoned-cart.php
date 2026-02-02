<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AbandonedCart;

$now = now();
echo "Current Time: " . $now . "\n\n";

$carts = AbandonedCart::all();

echo "Found " . $carts->count() . " abandoned carts total\n\n";

foreach ($carts as $cart) {
    echo "ID: {$cart->id}\n";
    echo "  Email: {$cart->email}\n";
    echo "  Stage: {$cart->reminder_stage}\n";
    echo "  Last Activity: {$cart->last_activity_at}\n";
    echo "  Last Reminder: {$cart->last_reminder_at}\n";
    echo "\n";
}
