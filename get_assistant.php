<?php
$ch = curl_init('https://api.vapi.ai/assistant/5a05fa0e-87e8-43cc-959e-4c5c469670de');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer 57da6830-4e31-4828-897c-9b1959714a66',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo $response;
