<?php
$assistantId = '5a05fa0e-87e8-43cc-959e-4c5c469670de'; // From screenshot URL
$apiKey = '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7'; // From VapiController.php

$ch = curl_init("https://api.vapi.ai/assistant/{$assistantId}");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$apiKey}",
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo "ASSISTANT INFO:\n";
echo $response . "\n\n";

// Also list tools
$ch = curl_init("https://api.vapi.ai/tool");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$apiKey}",
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo "TOOLS LIST:\n";
echo $response . "\n";
