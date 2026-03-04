<?php
$apiKey = '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
$ch = curl_init('https://api.vapi.ai/assistant');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$apiKey}",
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$assistants = json_decode($response, true);
foreach ($assistants as $a) {
    echo "ID: {$a['id']}, Name: {$a['name']}\n";
}
