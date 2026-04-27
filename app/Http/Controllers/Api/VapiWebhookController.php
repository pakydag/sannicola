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
        $callId = $this->getCallIdFromPayload($payload);
        $customerPhone = $payload['message']['call']['customer']['number'] ?? ($payload['call']['customer']['number'] ?? null);
        
        // --- IDENTIFICAZIONE CLIENTE (LAST 10 DIGITS) ---
        $contact = null;
        if ($customerPhone) {
            $cleanPhone = substr(preg_replace('/[^0-9]/', '', $customerPhone), -10);
            $contact = Contact::where('phone', 'like', "%{$cleanPhone}")
                ->orWhere('mobile', 'like', "%{$cleanPhone}")
                ->first();

            // LOG DI DEBUG
            $debugMsg = "\n[" . now()->format('Y-m-d H:i:s') . "] --- VAPI CALL START ---\n";
            $debugMsg .= "RAW PHONE: '{$customerPhone}' | CLEAN: '{$cleanPhone}'\n";
            $debugMsg .= "MESSAGE TYPE: '{$messageType}'\n";
            if ($contact) {
                $debugMsg .= "RESULT: MATCH FOUND (#{$contact->id} - {$contact->first_name})\n";
            } else {
                $debugMsg .= "RESULT: NO MATCH\n";
            }
            $debugMsg .= "-----------------------------\n";
            file_put_contents(storage_path('logs/vapi_caller_debug.log'), $debugMsg, FILE_APPEND);
        }
        // ------------------------------------------------

        Log::info('Vapi Webhook received:', ['type' => $messageType, 'callId' => $callId]);

        // 1. GESTIONE PERSONALIZZAZIONE CHIAMATA (ASSISTANT REQUEST)
        if ($messageType === 'assistant-request' || $messageType === 'assistant.started') {
            return $this->handleAssistantRequest($payload, $contact);
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
    private function handleAssistantRequest($payload, $contact = null)
    {
        if ($contact) {
            Log::info("Vapi AssistantRequest: riconosciuto contatto #{$contact->id} ({$contact->first_name})");
            
            return response()->json([
                'assistant' => [
                    'firstMessage' => "Ciao " . $contact->first_name . ", come posso aiutarti?"
                ]
            ]);
        }

        // Fallback per numeri non riconosciuti
        return response()->json([
            'assistant' => [
                'firstMessage' => "Cèdam srl, sono Vanessa, come ti chiami?"
            ]
        ]);
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

            if ($functionName === 'get_customer_context') {
                $results[] = $this->getCustomerContextTool($toolCall, $args, $payload);
            } elseif ($functionName === 'update_contact_info') {
                $results[] = $this->updateContactInfoTool($toolCall, $args, $payload);
            } elseif ($functionName === 'cancel_appointment') {
                $results[] = $this->cancelAppointmentTool($toolCall, $args, $payload);
            }

            if ($functionName === 'save_ticket') {
                Log::info("Vapi Tool Call: save_ticket invoked", ['args' => $args, 'payload_call_id' => $this->getCallIdFromPayload($payload)]);
                $results[] = $this->saveTicketTool($toolCall, $args, $payload);
            }

            if ($functionName === 'get_assistance_types') {
                $results[] = $this->getAssistanceTypesTool($toolCall);
            }

            if ($functionName === 'check_availability') {
                $results[] = $this->checkAvailabilityTool($toolCall, $args);
            }

            if ($functionName === 'book_appointment') {
                Log::info("Vapi Tool Call: book_appointment invoked", ['args' => $args, 'payload_call_id' => $this->getCallIdFromPayload($payload)]);
                $results[] = $this->bookAppointmentTool($toolCall, $args, $payload);
            }

            if ($functionName === 'register_sms') {
                Log::info("Vapi Tool Call: register_sms invoked", ['args' => $args]);
                $results[] = $this->registerSmsTool($toolCall, $args, $payload);
            }
        }

        return response()->json(['results' => $results]);
    }

    private function registerSmsTool($toolCall, $args, $payload)
    {
        $content = $args['content'] ?? '';
        $phone = $args['phone'] ?? '';
        
        if ($content && $phone) {
            $cleanPhone = substr(preg_replace('/[^0-9]/', '', $phone), -10);
            $contact = Contact::where('phone', 'like', "%{$cleanPhone}")
                ->orWhere('mobile', 'like', "%{$cleanPhone}")
                ->first();
                
            \App\Models\VapiSms::create([
                'vapi_id' => $this->getCallIdFromPayload($payload),
                'phone_number' => $phone,
                'content' => $content,
                'received_at' => now(),
                'contact_id' => $contact ? $contact->id : null,
            ]);

            Log::info("Vapi SMS Logged: from {$phone}");
        }

        return [
            'toolCallId' => $toolCall['id'] ?? 'default',
            'result'     => "SMS archiviato."
        ];
    }

    private function getCustomerContextTool($toolCall, $args, $payload)
    {
        $phone = $args['phone'] ?? ($payload['message']['call']['customer']['number'] ?? ($payload['call']['customer']['number'] ?? null));
        
        if (!$phone) {
            return ['toolCallId' => $toolCall['id'] ?? 'default', 'result' => "Nessun numero di telefono fornito."];
        }

        $cleanPhone = substr(preg_replace('/[^0-9]/', '', $phone), -10);
        $contact = Contact::where('phone', 'like', "%{$cleanPhone}")
            ->orWhere('mobile', 'like', "%{$cleanPhone}")
            ->first();
        
        if (!$contact) {
            return ['toolCallId' => $toolCall['id'] ?? 'default', 'result' => "Cliente non trovato nel CRM."];
        }

        // 1. Ticket Aperti
        $openTicket = AiTicket::where('status', 'open')->where('contact_id', $contact->id)->first();
        
        // 2. Prossimo Appuntamento
        $futureApp = \App\Models\Appointment::where('status', 'confirmed')
            ->where('start_time', '>', now())
            ->where('contact_id', $contact->id)
            ->orderBy('start_time', 'asc')
            ->first();

        $context = [
            'customer_name' => $contact->first_name,
            'is_known' => true,
            'has_open_ticket' => !empty($openTicket),
            'ticket_details' => $openTicket ? "Ticket #{$openTicket->id} ({$openTicket->assistance_type}) aperto il " . $openTicket->created_at->format('d/m/Y') : null,
            'has_appointment' => !empty($futureApp),
            'appointment_details' => $futureApp ? "Appuntamento il " . date('d/m/Y H:i', strtotime($futureApp->start_time)) : null,
        ];

        return [
            'toolCallId' => $toolCall['id'] ?? 'default',
            'result'     => $context
        ];
    }

    private function saveTicketTool($toolCall, $args, $payload)
    {
        $callId = $this->getCallIdFromPayload($payload);
        
        // Estrazione telefono
        $phone = $args['phone'] ?? ($payload['message']['call']['customer']['number'] ?? ($payload['call']['customer']['number'] ?? null));
        
        // VALIDAZIONE: Se il telefono sembra un nome (contiene troppe lettere o è uguale al nome)
        // Lo rifiutiamo in modo che l'AI debba chiederlo di nuovo.
        if ($phone && (preg_match('/[a-zA-Z]{5,}/', $phone) || $phone === ($args['customer_name'] ?? ''))) {
             Log::warning("Vapi: nome rilevato nel campo telefono ('{$phone}'). Rifiuto il tool call.");
             return [
                'toolCallId' => $toolCall['id'] ?? 'default',
                'result'     => "Errore: Il numero di telefono non è valido. Per favore, chiedi ALL'UTENTE di fornirti il suo NUMERO DI TELEFONO NUMERICO (senza nomi)."
            ];
        }

        $contactId = null;

        if ($phone) {
            $cleanPhone = substr(preg_replace('/[^0-9]/', '', $phone), -10);
            $contact = Contact::where('phone', 'like', "%{$cleanPhone}")
                ->orWhere('mobile', 'like', "%{$cleanPhone}")
                ->first();
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
            'vapi_call_id'    => $callId,
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

            private function checkAvailabilityTool($toolCall, $args)
    {
        $deptName = $args['department_name'] ?? '';
        $date = $args['date'] ?? now()->toDateString();
        
        Log::info("Vanessa: Checking availability for $deptName on $date");
        
        $department = Department::where('is_active', true)
            ->whereRaw('LOWER(name) = ?', [strtolower(trim($deptName))])
            ->first();

        if (!$department || !$department->working_hours) {
            Log::warning("Vanessa: Department not found or no working hours for $deptName");
            return [
                'toolCallId' => $toolCall['id'] ?? 'default',
                'result'     => "Spiacente, non ho informazioni sugli orari per il reparto $deptName."
            ];
        }

        $hours = $department->working_hours;
        $dayOfWeek = date('w', strtotime($date));
        $isWorkDay = false;
        $allowedDays = $hours['days'] ?? [];
        
        if (in_array('1', $allowedDays) && $dayOfWeek >= 1 && $dayOfWeek <= 5) $isWorkDay = true;
        if (in_array('6', $allowedDays) && $dayOfWeek == 6) $isWorkDay = true;

        if (!$isWorkDay) {
            Log::info("Vanessa: $date ($dayOfWeek) is not a work day for $deptName");
            return [
                'toolCallId' => $toolCall['id'] ?? 'default',
                'result'     => "Il reparto $deptName è chiuso il giorno selezionato ($date)."
            ];
        }

        $startStr = $hours['start'] ?? '09:00';
        $endStr = $hours['end'] ?? '18:00';
        $duration = $department->appointment_duration ?? 30;

        $existingAppointments = \App\Models\Appointment::where('department_id', $department->id)
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $availableSlots = [];
        $startTime = \Carbon\Carbon::parse("$date $startStr");
        $endTime = \Carbon\Carbon::parse("$date $endStr");
        $now = now();

        Log::info("Vanessa: Generating slots from $startTime to $endTime with duration $duration");

        $current = $startTime->copy();
        while ($current->copy()->addMinutes($duration)->lte($endTime)) {
            $slotStart = $current->copy();
            $slotEnd = $current->copy()->addMinutes($duration);

            $isOccupied = $existingAppointments->contains(function($app) use ($slotStart, $slotEnd) {
                return $slotStart->lt($app->end_time) && $slotEnd->gt($app->start_time);
            });

            if (!$isOccupied && $slotStart->gt($now->subMinutes(5))) {
                $availableSlots[] = $current->format('H:i');
            } else {
                // Log why it was skipped
                Log::debug("Vanessa: Skipping slot {$current->format('H:i')} - Occupied: ".($isOccupied?'YES':'NO')." - Past: ".($slotStart->lte($now)?'YES':'NO'));
            }
            $current->addMinutes($duration);
        }

        Log::info("Vanessa: Found ".count($availableSlots)." slots for $deptName on $date");

        if (empty($availableSlots)) {
            return [
                'toolCallId' => $toolCall['id'] ?? 'default',
                'result'     => "Non ci sono orari disponibili per $deptName il giorno $date."
            ];
        }

        return [
            'toolCallId' => $toolCall['id'] ?? 'default',
            'result'     => [
                'date' => $date,
                'available_slots' => $availableSlots,
                'message' => "Ecco gli orari disponibili per il reparto $deptName: " . implode(', ', $availableSlots)
            ]
        ];
    }

    private function bookAppointmentTool($toolCall, $args, $payload)
    {
        $deptName = $args['department_name'] ?? '';
        $date = $args['date'] ?? '';
        $time = $args['time'] ?? '';
        $phone = $args['phone'] ?? ($payload['message']['call']['customer']['number'] ?? ($payload['call']['customer']['number'] ?? null));

        $department = Department::where('is_active', true)->whereRaw('LOWER(name) = ?', [strtolower($deptName)])->first();
        if (!$department) {
            return ['toolCallId' => $toolCall['id'] ?? 'default', 'result' => "Reparto non trovato."];
        }

        // Cerca o crea contatto (confronto sulle ultime 10 cifre)
        $cleanPhone = substr(preg_replace('/[^0-9]/', '', $phone), -10);
        $contact = Contact::where('phone', 'like', "%{$cleanPhone}")
            ->orWhere('mobile', 'like', "%{$cleanPhone}")
            ->first();

        $customerName = $args['customer_name'] ?? ($contact ? $contact->full_name : 'Cliente');

        if (!$contact && isset($args['customer_name'])) {
            $nameParts = explode(' ', $args['customer_name'], 2);
            $contact = Contact::create([
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'company_name' => $args['company_name'] ?? null,
                'phone' => $phone,
                'is_vapi_lead' => true,
                'is_active' => true,
            ]);
        }

        $startTime = date('Y-m-d H:i:s', strtotime("$date $time"));
        $endTime = date('Y-m-d H:i:s', strtotime("$startTime + {$department->appointment_duration} minutes"));
        $callId = $this->getCallIdFromPayload($payload);

        $appointment = \App\Models\Appointment::create([
            'contact_id' => $contact ? $contact->id : null,
            'department_id' => $department->id,
            'vapi_call_id' => $callId,
            'title' => ($department->name ?? 'Reparto') . ": " . ($customerName),
            'description' => $args['reason'] ?? ($args['description'] ?? 'N/D'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'confirmed'
        ]);

        // INVIO EMAIL AL REPARTO (Stile Ticket)
        if ($department && $department->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($department->email)->send(new \App\Mail\AppointmentBooked($appointment));
                Log::info("Email appuntamento inviata al reparto {$department->name} ({$department->email})");
            } catch (\Exception $e) {
                Log::error("Errore invio email appuntamento: " . $e->getMessage());
            }
        }

        return [
            'toolCallId' => $toolCall['id'] ?? 'default',
            'result' => "Appuntamento confermato per il $date alle $time. Ti comunico che ho segnato l'impegno in agenda."
        ];
    }

    /**
     * Aggiorna ticket e appuntamenti con costi, durata e registrazione a fine chiamata
     */
    private function handleEndOfCall($payload)
    {
        $callId = $this->getCallIdFromPayload($payload);
        $callData = $payload['call'] ?? ($payload['message']['call'] ?? []);
        
        // LOG PER DEBUG: Vediamo esattamente cosa arriva
        Log::info("Vapi EndOfCall Payload for #{$callId}:", [
            'call_data_keys' => array_keys($callData),
            'message_keys' => array_keys($payload['message'] ?? []),
            'full_payload' => $payload // Questo scriverà tutto nel file laravel.log
        ]);

        // Estrazione costo ultra-robusta (cerchiamo ovunque)
        $cost = $callData['cost'] ?? 
                ($callData['totalCost'] ?? 
                ($callData['total_cost'] ?? 
                ($payload['message']['cost'] ?? 
                ($payload['cost'] ?? 0))));
        
        // Estrazione durata ultra-robusta
        $duration = $callData['duration'] ?? 
                    ($payload['message']['duration'] ?? 
                    ($payload['duration'] ?? 0));
                    
        if (!$duration && isset($callData['startedAt']) && isset($callData['endedAt'])) {
            $start = strtotime($callData['startedAt']);
            $end = strtotime($callData['endedAt']);
            $duration = $end - $start;
        }

        $recordingUrl = $callData['recordingUrl'] ?? 
                        ($payload['message']['recordingUrl'] ?? 
                        ($callData['recording_url'] ?? 
                        ($payload['recordingUrl'] ?? null)));

        $transcript = $payload['message']['transcript'] ?? 
                      ($payload['transcript'] ?? 
                      ($callData['transcript'] ?? 
                      ($callData['fullTranscript'] ?? null)));

        Log::info("Vapi Data Extracted:", [
            'callId' => $callId,
            'cost' => $cost,
            'duration' => $duration,
            'has_recording' => !empty($recordingUrl),
            'has_transcript' => !empty($transcript)
        ]);

        Log::info("Vapi End of Call: updating records for Call #{$callId}", [
            'cost' => $cost,
            'duration' => $duration
        ]);

        // 1. Aggiorna Ticket AI associati (cerchiamo con entrambi i campi per sicurezza)
        $tickets = AiTicket::where('vapi_call_id', $callId)
            ->orWhere('call_id', $callId)
            ->get();
            
        foreach ($tickets as $ticket) {
            $ticket->update([
                'vapi_call_id'  => $callId, // Assicuriamo che il nuovo campo sia popolato
                'cost'          => $cost,
                'duration'      => $duration,
                'recording_url' => $recordingUrl,
                'audio_url'     => $recordingUrl, // Feedback al vecchio campo per retrocompatibilità
                'transcription' => $transcript ?? $ticket->transcription,
            ]);
        }

        // 2. Aggiorna Appuntamenti associati
        $appointments = \App\Models\Appointment::where('vapi_call_id', $callId)->get();
        foreach ($appointments as $appointment) {
            $appointment->update([
                'cost' => $cost,
                'duration' => $duration,
                'recording_url' => $recordingUrl,
                'transcription' => $transcript ?? $appointment->transcription,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Utility per estrarre l'ID chiamata in modo robusto da vari formati di payload Vapi
     */
    private function getCallIdFromPayload($payload)
    {
        // 1. Dalla radice 'call'
        if (isset($payload['call']['id'])) return $payload['call']['id'];

        // 2. Dal messaggio nidificato
        if (isset($payload['message']['call']['id'])) return $payload['message']['call']['id'];
        
        // 3. Da message.callId (comune in alcuni eventi report)
        if (isset($payload['message']['callId'])) return $payload['message']['callId'];
        
        // 4. Da message.call.id se nidificato diversamente
        if (isset($payload['message']['call']->id)) return $payload['message']['call']->id;
        
        return null;
    }

    /**
     * Tool: Aggiorna informazioni contatto
     */
    private function updateContactInfoTool($toolCall, $args, $payload)
    {
        $customerPhone = $this->getCallIdFromPayload($payload); // Metafora: recuperiamo il numero dalla chiamata
        $phone = $args['phone'] ?? null;
        
        // Cerchiamo il contatto
        $contact = \App\Models\Contact::where('phone', 'like', "%{$phone}%")->first();
        if (!$contact && isset($payload['message']['call']['customer']['number'])) {
            $rawPhone = $payload['message']['call']['customer']['number'];
            $cleanPhone = substr(preg_replace('/[^0-9]/', '', $rawPhone), -10);
            $contact = \App\Models\Contact::where('phone', 'like', "%{$cleanPhone}%")->first();
        }

        if ($contact) {
            $contact->update(array_filter([
                'first_name'   => $args['first_name'] ?? null,
                'last_name'    => $args['last_name'] ?? null,
                'email'         => $args['email'] ?? null,
                'company_name'  => $args['company_name'] ?? null,
                'address'       => $args['address'] ?? null,
                'city'          => $args['city'] ?? null,
            ]));

            return [
                'toolCallId' => $toolCall['id'] ?? ($toolCall['toolCallId'] ?? ''),
                'result' => "Informazioni di {$contact->first_name} aggiornate correttamente nel CRM."
            ];
        }

        return [
            'toolCallId' => $toolCall['id'] ?? ($toolCall['toolCallId'] ?? ''),
            'result' => "Errore: Contatto non trovato nel sistema per l'aggiornamento."
        ];
    }

    /**
     * Tool: Annulla appuntamento
     */
    private function cancelAppointmentTool($toolCall, $args, $payload)
    {
        $rawPhone = $payload['message']['call']['customer']['number'] ?? null;
        $cleanPhone = $rawPhone ? substr(preg_replace('/[^0-9]/', '', $rawPhone), -10) : null;
        
        $contact = $cleanPhone ? \App\Models\Contact::where('phone', 'like', "%{$cleanPhone}%")->first() : null;

        if ($contact) {
            $appointment = \App\Models\Appointment::where('contact_id', $contact->id)
                ->where('status', 'confirmed')
                ->where('start_time', '>', now())
                ->orderBy('start_time', 'asc')
                ->first();

            if ($appointment) {
                $appointment->update(['status' => 'cancelled']);
                return [
                    'toolCallId' => $toolCall['id'] ?? ($toolCall['toolCallId'] ?? ''),
                    'result' => "L'appuntamento del " . date('d/m/Y H:i', strtotime($appointment->start_time)) . " è stato annullato."
                ];
            }
        }

        return [
            'toolCallId' => $toolCall['id'] ?? ($toolCall['toolCallId'] ?? ''),
            'result' => "Non ho trovato appuntamenti futuri confermati da annullare."
        ];
    }
}
