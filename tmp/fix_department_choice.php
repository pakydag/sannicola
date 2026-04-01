<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Services\VapiService;

$welcome = "Sono Vanessa, come posso aiutarla?";
$prompt = "Posso aiutarla ad aprire un ticket nei seguenti reparti - chiama 'get_assistance_types'.\n\n" .
          "REGOLA FONDAMENTALE: Dopo aver elencato i reparti, DEVI chiedere all'utente di scegliere esplicitamente a quale reparto desidera rivolgersi. NON procedere al salvataggio finché l'utente non ha effettuato questa scelta.\n\n" .
          "Successivamente chiedi Azienda, Nome, Email, Problema, Telefono (se non noto dal Caller ID). Solo alla fine usa save_ticket.\n\n" .
          "RELAZIONE CAMPI: Non confondere MAI il nome dell'utente con il suo numero di telefono. Se il numero non è noto, DEVI chiederlo.\n\n" .
          "ISTRUZIONE OBBLIGATORIA: Prima di salvare un ticket con 'save_ticket', devi SEMPRE chiamare 'get_assistance_types' per conoscere i reparti disponibili e farli SCEGLIERE all'utente.";

Setting::updateOrCreate(['key' => 'vapi_prompt'], ['value' => $prompt]);
Setting::updateOrCreate(['key' => 'vapi_welcome_message'], ['value' => $welcome]);

$vapiService = app(VapiService::class);
$vapiService->syncAssistantConfig($prompt, $welcome);

echo "Prompt aggiornato con istruzioni di scelta obbligatoria del reparto.\n";
