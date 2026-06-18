<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo 'Quote-only with price: ' . Product::quoteOnly()->whereNotNull('price')->count() . PHP_EOL;
echo 'Quote-only with compare_at_price: ' . Product::quoteOnly()->whereNotNull('compare_at_price')->count() . PHP_EOL;
echo 'Quote-only with stock_quantity: ' . Product::quoteOnly()->whereNotNull('stock_quantity')->count() . PHP_EOL;
echo 'Quote-only with low_stock_threshold: ' . Product::quoteOnly()->whereNotNull('low_stock_threshold')->count() . PHP_EOL;
echo 'Quote-only with track_stock true: ' . Product::quoteOnly()->where('track_stock', true)->count() . PHP_EOL;

$p = Product::where('sku', 'TEST-BUYABLE-001')->first();

if ($p) {
    echo PHP_EOL . 'TEST-BUYABLE-001:' . PHP_EOL;
    echo 'type=' . $p->product_type . PHP_EOL;
    echo 'price=' . var_export($p->price, true) . PHP_EOL;
    echo 'compare_at_price=' . var_export($p->compare_at_price, true) . PHP_EOL;
    echo 'stock_quantity=' . var_export($p->stock_quantity, true) . PHP_EOL;
    echo 'low_stock_threshold=' . var_export($p->low_stock_threshold, true) . PHP_EOL;
    echo 'track_stock=' . var_export((bool) $p->track_stock, true) . PHP_EOL;
}