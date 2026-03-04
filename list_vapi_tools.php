<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey     = '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
$baseUrl    = 'https://api.vapi.ai';

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
])->get("{$baseUrl}/tool");

if ($response->successful()) {
    echo json_encode($response->json(), JSON_PRETTY_PRINT);
} else {
    echo "Error: " . $response->body();
}
