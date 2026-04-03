<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Services\VapiService;

// 1. Imposta l'URL pubblico del Webhook
$publicUrl = 'https://web.eyukka.it/api/vapi/webhook';
Setting::updateOrCreate(['key' => 'vapi_webhook_url'], ['value' => $publicUrl]);
echo "Webhook URL aggiornato a: $publicUrl\n";

// 2. Forza la sincronizzazione con Vapi.ai (Riley)
$vapi = new VapiService();
$success = $vapi->syncAssistantConfig();

if ($success) {
    echo "Sincronizzazione di Riley completata con successo! Ora Riley chiamerà il server pubblico.\n";
} else {
    echo "Errore durante la sincronizzazione.\n";
}
