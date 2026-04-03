<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\VapiService;

$vapi = new VapiService();
$success = $vapi->syncAssistantConfig();

if ($success) {
    echo "Sincronizzazione completata con successo! Il nuovo tool 'get_customer_context' è attivo.";
} else {
    echo "Errore durante la sincronizzazione.";
}
