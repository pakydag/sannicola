<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiTicket;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VapiWebhookController extends Controller
{
    /**
     * Handle webhook from Vapi.ai
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        $messageType = $payload['message']['type'] ?? null;
        
        Log::info('Vapi Webhook received:', ['type' => $messageType, 'callId' => $payload['call']['id'] ?? null]);

        // 1. GESTIONE PERSONALIZZAZIONE CHIAMATA (ASSISTANT REQUEST)
        if ($messageType === 'assistant-request') {
            return $this->handleAssistantRequest($payload);
        }

        // 2. GESTIONE TOOL CALLS (SAVE TICKET / GET DEPARTMENTS)
        if ($messageType === 'tool-calls' || $messageType === 'function-call') {
            return $this->handleToolCalls($payload);
        }

        // 3. GESTIONE FINE CHIAMATA (END OF CALL REPORT)
        if ($messageType === 'end-of-call-report') {
            return $this->handleEndOfCall($payload);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Personalizza la risposta iniziale in base al numero di telefono
     */
    private function handleAssistantRequest($payload)
    {
        $customerPhone = $payload['message']['call']['customer']['number'] ?? ($payload['call']['customer']['number'] ?? null);
        
        if (!$customerPhone) {
            return response()->json([]); // Nessuna modifica, usa default
        }

        // Cerca contatto nel CRM
        $contact = Contact::where('phone', 'like', "%{$customerPhone}%")
            ->orWhere('mobile', 'like', "%{$customerPhone}%")
            ->first();

        if ($contact) {
            Log::info("Vapi: riconosciuto contatto #{$contact->id} ({$contact->first_name})");

            // Cerca ticket aperti
            $openTicket = AiTicket::where('status', 'open')
                ->where(function($query) use ($contact, $customerPhone) {
                    $query->where('contact_id', $contact->id)
                          ->orWhere('phone', 'like', "%{$customerPhone}%");
                })->first();

            if ($openTicket) {
                // Aggiungi sollecito al ticket
                $sollecito = "\n⚠️ SOLLECITO TELEFONICO (" . now()->format('d/m/Y H:i') . ")";
                $openTicket->update([
                    'description' => $openTicket->description . $sollecito
                ]);
                Log::info("Vapi: aggiunto sollecito a ticket #{$openTicket->id}");

                $msg = "Ciao " . $contact->first_name . ", ti informo che il tuo ticket relativo a '" . $openTicket->assistance_type . "' è in fase di risoluzione e sarai ricontattato a breve. Oltre a questo, c'è altro per cui posso aiutarti oggi?";
            } else {
                $msg = "Ciao " . $contact->first_name . ", benvenuto! Come posso aiutarti oggi?";
            }

            return response()->json([
                'assistant' => [
                    'firstMessage' => $msg
                ]
            ]);
        }

        return response()->json([]); // Nessun contatto trovato, usa default
    }

    /**
     * Gestisce le chiamate ai tool
     */
    private function handleToolCalls($payload)
    {
        $toolCalls = $payload['message']['toolCalls'] ?? [];
        if (isset($payload['message']['functionCall'])) {
            $toolCalls = [$payload['message']['functionCall']];
        }

        $results = [];

        foreach ($toolCalls as $toolCall) {
            $functionName = $toolCall['function']['name'] ?? '';
            $args = $toolCall['function']['arguments'] ?? [];
            if (is_string($args)) $args = json_decode($args, true);

            if ($functionName === 'save_ticket') {
                $results[] = $this->saveTicketTool($toolCall, $args, $payload);
            }

            if ($functionName === 'get_assistance_types') {
                $results[] = $this->getAssistanceTypesTool($toolCall);
            }
        }

        return response()->json(['results' => $results]);
    }

    private function saveTicketTool($toolCall, $args, $payload)
    {
        $callId = $payload['call']['id'] ?? ($payload['message']['callId'] ?? null);
        // Estrazione telefono più robusta
        $phone = $args['phone'] ?? ($payload['message']['call']['customer']['number'] ?? ($payload['call']['customer']['number'] ?? null));
        
        $contactId = null;

        if ($phone) {
            $contact = Contact::where('phone', 'like', "%$phone%")->orWhere('mobile', 'like', "%$phone%")->first();
            if ($contact) {
                $contactId = $contact->id;
                if (isset($args['email']) && empty($contact->email)) $contact->update(['email' => $args['email']]);
                if (empty($contact->phone)) $contact->update(['phone' => $phone]);
            }
        }

        // Creazione lead se non trovato
        if (!$contactId && isset($args['customer_name'])) {
            $nameParts = explode(' ', $args['customer_name'], 2);
            $newContact = Contact::create([
                'first_name'   => $nameParts[0],
                'last_name'    => $nameParts[1] ?? '',
                'company_name' => $args['company_name'] ?? null,
                'email'        => $args['email'] ?? null,
                'phone'        => $phone,
                'is_vapi_lead' => true,
                'is_active'    => true,
            ]);
            $contactId = $newContact->id;
        }

        $assistanceType = $args['assistance_type'] ?? '';
        $department = Department::where('is_active', true)->whereRaw('LOWER(name) = ?', [strtolower($assistanceType)])->first();

        $ticket = AiTicket::create([
            'contact_id'      => $contactId,
            'call_id'         => $callId,
            'department_id'   => $department ? $department->id : null,
            'assistance_type' => $args['assistance_type'] ?? 'Generico',
            'company_name'    => $args['company_name'] ?? 'N/A',
            'customer_name'   => $args['customer_name'] ?? 'N/A',
            'email'           => $args['email'] ?? null,
            'phone'           => $phone,
            'description'     => $args['description'] ?? 'N/D',
            'status'          => 'open',
        ]);

        // Email Notification
        $targetEmail = $department ? $department->email : Setting::where('key', 'mail_from_address')->value('value');
        if ($targetEmail) {
            try {
                Mail::to($targetEmail)->send(new \App\Mail\AiTicketCreated($ticket));
            } catch (\Exception $e) { Log::error("Mail error: " . $e->getMessage()); }
        }

        return [
            'toolCallId' => $toolCall['id'] ?? 'default',
            'result'     => "Ticket #" . $ticket->id . " salvato e reparto notificato."
        ];
    }

    private function getAssistanceTypesTool($toolCall)
    {
        $types = Department::where('is_active', true)->pluck('name')->toArray();
        return [
            'toolCallId' => $toolCall['id'] ?? 'default',
            'result'     => ['available_types' => !empty($types) ? $types : ['Generico']]
        ];
    }

    /**
     * Aggiorna il ticket con trascrizione e audio a fine chiamata
     */
    private function handleEndOfCall($payload)
    {
        $callId = $payload['call']['id'] ?? ($payload['message']['callId'] ?? null);
        $ticket = AiTicket::where('call_id', $callId)->first();
        if ($ticket) {
            $ticket->update([
                'transcription' => $payload['message']['transcript'] ?? null,
                'audio_url'     => $payload['message']['recordingUrl'] ?? null,
            ]);
        }
        return response()->json(['status' => 'ok']);
    }
}
