<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

echo "vapi_webhook_url: '" . Setting::where('key', 'vapi_webhook_url')->value('value') . "'\n";
echo "APP_URL: '" . config('app.url') . "'\n";
