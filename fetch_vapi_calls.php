<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$apiKey = '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
$baseUrl = 'https://api.vapi.ai';

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
])->get("{$baseUrl}/call", [
    'limit' => 20
]);

if ($response->failed()) {
    echo "Error: " . $response->body() . "\n";
    exit(1);
}

$calls = $response->json();

foreach ($calls as $call) {
    echo "ID: " . $call['id'] . "\n";
    echo "Started At: " . $call['startedAt'] . "\n";
    echo "Customer Name: " . ($call['customer']['name'] ?? 'N/A') . "\n";
    echo "Transcript: " . (isset($call['transcript']) ? 'Yes' : 'No') . "\n";
    echo "Recording: " . (isset($call['recordingUrl']) ? 'Yes' : 'No') . "\n";
    echo "-----------------------------------\n";
}
