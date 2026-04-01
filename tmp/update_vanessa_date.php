<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Services\VapiService;

// Ottieni il prompt attuale
$currentPrompt = Setting::where('key', 'vapi_prompt')->value('value');
$welcome = Setting::where('key', 'vapi_welcome_message')->value('value');

// Aggiungi la data dinamica (Vapi la popolerà al momento della chiamata se usiamo il suo formato, 
// o la iniettiamo noi ora per il test)
$dateInfo = "\n\nDATA ATTUALE: " . now()->timezone('Europe/Rome')->format('l, d F Y, H:i') . "\n";

if (strpos($currentPrompt, 'DATA ATTUALE') === false) {
    $currentPrompt = $dateInfo . $currentPrompt;
}

Setting::updateOrCreate(['key' => 'vapi_prompt'], ['value' => $currentPrompt]);

$vapiService = app(VapiService::class);
$vapiService->syncAssistantConfig($currentPrompt, $welcome);

echo "Prompt aggiornato con data e ora correnti e sincronizzato con Vapi.\n";
