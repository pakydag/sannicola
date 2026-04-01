<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Services\VapiService;
use Illuminate\Support\Facades\Http;

$welcome = "Sono Vanessa, come posso aiutarla?";
$prompt = "Posso aiutarla ad aprire un ticket nei seguenti reparti - chiama 'get_assistance_types'\n\nSuccessivamente chiedi Azienda, Nome, Email, Problema. Usa save_ticket.\n\nRELAZIONE CAMPI: Non confondere MAI il nome dell'utente con il suo numero di telefono. Se non conosci il numero di telefono (ad esempio in una chat), DEVI chiederlo all'utente prima di salvare il ticket.\n\nISTRUZIONE OBBLIGATORIA: Prima di salvare un ticket con 'save_ticket', devi SEMPRE chiamare 'get_assistance_types' per conoscere i reparti disponibili. Se l'utente ha un ticket aperto, informa che è in risoluzione e chiedi se ha bisogno di altro.";

Setting::updateOrCreate(['key' => 'vapi_prompt'], ['value' => $prompt]);
Setting::updateOrCreate(['key' => 'vapi_welcome_message'], ['value' => $welcome]);

$vapiService = app(VapiService::class);
$success = $vapiService->syncAssistantConfig($prompt, $welcome);

if ($success) {
    echo "DB settings updated and Vapi.ai synchronized successfully.\n";
} else {
    echo "DB settings updated, but Vapi.ai synchronization failed.\n";
}
