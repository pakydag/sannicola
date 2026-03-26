<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Services\SpokiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncContactToSpoki implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contact;

    /**
     * Create a new job instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     */
    public function handle(SpokiService $spokiService)
    {
        Log::info("Esecuzione Job Spoki per contatto #{$this->contact->id}");
        $spokiService->syncContact($this->contact);
    }
}
