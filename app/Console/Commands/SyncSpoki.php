<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Services\SpokiService;
use Illuminate\Console\Command;

class SyncSpoki extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-spoki';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizza tutti i contatti del CRM con la piattaforma Spoki';

    /**
     * Execute the console command.
     */
    public function handle(SpokiService $spokiService)
    {
        $contacts = Contact::all();
        $total = $contacts->count();
        
        $this->info("Inizio sincronizzazione di {$total} contatti con Spoki...");
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($contacts as $contact) {
            $spokiService->syncContact($contact);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Sincronizzazione completata!");
    }
}
