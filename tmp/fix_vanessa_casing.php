<?php
$file = 'c:\xampp\htdocs\baseweb\app\Http\Controllers\Api\VapiWebhookController.php';
$content = file_get_contents($file);

// Case insensitive fix for checkAvailabilityTool and bookAppointmentTool
$content = str_replace(
    "->where('name', \$deptName)->first();",
    "->whereRaw('LOWER(name) = ?', [strtolower(\$deptName)])->first();",
    $content
);

file_put_contents($file, $content);
echo "Casing sensitivity fixed in VapiWebhookController.\n";
