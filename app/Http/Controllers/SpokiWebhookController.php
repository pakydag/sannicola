<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpokiWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Spoki Webhook Ricevuto', $request->all());

        $payload = $request->all();
        $event = $payload['event'] ?? '';

        // Based on Spoki documentation, we look for contact data
        $contactData = $payload['contact'] ?? $payload;

        $phone = $contactData['phone'] ?? null;

        if (!$phone) {
            Log::warning('Spoki Webhook: Nessun numero di telefono nel payload.');
            return response()->json(['status' => 'ignored', 'reason' => 'no_phone'], 200);
        }

        // Normalize phone for comparison (remove extras like + or spaces)
        $normalizedPhone = preg_replace('/[^0-9]/', '', $phone);

        // Find or create the contact by phone (normalized search)
        $contact = Contact::whereRaw("REPLACE(REPLACE(phone, '+', ''), ' ', '') = ?", [$normalizedPhone])
            ->orWhereRaw("REPLACE(REPLACE(mobile, '+', ''), ' ', '') = ?", [$normalizedPhone])
            ->first();

        if (!$contact) {
            $contact = new Contact();
            $contact->phone = $phone;
            $contact->is_lead = true; // Mark as lead if it's external coming from Spoki
        }

        // Update fields if provided
        if (isset($contactData['first_name'])) {
            $contact->first_name = $contactData['first_name'];
        }
        if (isset($contactData['last_name'])) {
            $contact->last_name = $contactData['last_name'];
        }
        if (isset($contactData['email'])) {
            $contact->email = $contactData['email'];
        }

        // Sync tags if provided
        if (isset($contactData['tags']) && is_array($contactData['tags'])) {
            $existingTags = $contact->tags ?? [];
            // Merge tags to avoid losing system tags (Shop, Booking, etc.)
            $contact->tags = array_values(array_unique(array_merge($existingTags, $contactData['tags'])));
        }

        $contact->skipSpokiSync = true;
        $contact->save();

        Log::info("Contatto #{$contact->id} aggiornato/creato tramite Webhook Spoki.");

        return response()->json(['status' => 'success', 'contact_id' => $contact->id], 200);
    }
}
