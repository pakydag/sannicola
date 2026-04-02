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

            if ($functionName === 'check_availability') {
                $results[] = $this->checkAvailabilityTool($toolCall, $args);
            }

            if ($functionName === 'book_appointment') {
                $results[] = $this->bookAppointmentTool($toolCall, $args, $payload);
            }
        }

        return response()->json(['results' => $results]);
    }

    private function saveTicketTool($toolCall, $args, $payload)
    {
        $callId = $payload['call']['id'] ?? ($payload['message']['callId'] ?? null);
        
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

        // Cerca o crea contatto
        $contact = Contact::where('phone', 'like', "%$phone%")->orWhere('mobile', 'like', "%$phone%")->first();
        $customerName = $args['customer_name'] ?? ($contact ? $contact->name : 'Cliente');

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

        $callId = $payload['call']['id'] ?? ($payload['message']['callId'] ?? null);

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
        $callData = $payload['call'] ?? ($payload['message']['call'] ?? []);
        $callId = $callData['id'] ?? ($payload['message']['callId'] ?? null);
        
        $cost = $callData['cost'] ?? 0;
        $duration = 0;
        
        // Calcolo durata se presente
        if (isset($callData['startedAt']) && isset($callData['endedAt'])) {
            $start = strtotime($callData['startedAt']);
            $end = strtotime($callData['endedAt']);
            $duration = $end - $start;
        }

        $recordingUrl = $callData['recordingUrl'] ?? ($payload['message']['recordingUrl'] ?? null);
        $transcript = $payload['message']['transcript'] ?? ($payload['transcript'] ?? null);

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
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
