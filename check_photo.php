<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\BookingPhoto::where('booking_structure_id', 6)->first();
echo "Photo: " . ($p ? $p->path : 'NO_PHOTO') . "\n";
