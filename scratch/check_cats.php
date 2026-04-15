<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ShopProduct;
use App\Models\ShopCategory;

echo "--- CATEGORIES ---\n";
foreach(ShopCategory::all() as $c) {
    echo "ID: {$c->id} | Name: {$c->nome} | Parent: {$c->parent_id}\n";
}

echo "\n--- PRODUCTS ---\n";
foreach(ShopProduct::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->nome} | Cat ID: {$p->shop_category_id}\n";
}
