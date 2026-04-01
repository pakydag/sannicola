<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use App\Models\Setting;

$apiKey = Setting::where('key', 'vapi_key')->value('value');
$assistantId = Setting::where('key', 'vapi_assistant_id')->value('value');
$res = Http::withHeaders(['Authorization' => 'Bearer ' . $apiKey])->get('https://api.vapi.ai/assistant/' . $assistantId);

echo "Assistant ID: " . $assistantId . "\n\n";
echo "PROMPT:\n";
echo $res->json()['model']['messages'][0]['content'] ?? '(no prompt)';
echo "\n\n";
