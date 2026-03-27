<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpokiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = \App\Models\Setting::where('key', 'spoki_key')->value('value') ?: config('services.spoki.key');
        $this->baseUrl = config('services.spoki.url');
    }

    /**
     * Create or update a contact in Spoki
     * Reference: https://api.spoki.com/api/1/contacts/
     */
    public function syncContact(Contact $contact)
    {
        if (!$this->apiKey) {
            Log::warning('Spoki API Key non configurata. Salto la sincronizzazione.');
            return false;
        }

        // Spoki matches contacts by phone number. 
        // We ensure it's in a consistent format (e.g. +39...) 
        // For now we use the raw phone/mobile field.
        $phone = $contact->phone ?: $contact->mobile;

        if (!$phone) {
            Log::info("Contatto #{$contact->id} senza telefono. Salto Spoki.");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-Spoki-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'contacts/sync/', [
                'phone' => $phone,
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'email' => $contact->email,
                'tags' => $contact->tags ?? [],
                'custom_fields' => [
                    'company' => $contact->company_name,
                    'city' => $contact->city,
                ]
            ]);

            if ($response->successful()) {
                Log::info("Contatto #{$contact->id} sincronizzato con Spoki.");
                return true;
            } else {
                Log::error("Errore sincronizzazione Spoki #{$contact->id}: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Eccezione durante sincronizzazione Spoki #{$contact->id}: " . $e->getMessage());
            return false;
        }
    }
}
