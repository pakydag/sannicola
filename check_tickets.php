<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AiTicket;

$count = AiTicket::count();
echo "Total tickets: $count\n";
foreach (AiTicket::latest()->take(5)->get() as $ticket) {
    echo "ID: {$ticket->id}, Company: {$ticket->company_name}, Created: {$ticket->created_at}\n";
}
