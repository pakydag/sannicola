<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VapiWebhookController extends Controller
{
    /**
     * Handle webhook from Vapi.ai
     */
    public function handle(Request $request)
    {
        Log::info('Vapi Webhook raw content:', ['content' => $request->getContent()]);
        $payload = $request->all();
        
        // Log payload for debugging
        Log::info('Vapi Webhook JSON payload:', $payload);

        $callId = $payload['call']['id'] ?? ($payload['message']['callId'] ?? null);
        if (isset($payload['message']['type']) && ($payload['message']['type'] === 'tool-calls' || $payload['message']['type'] === 'function-call')) {
            $toolCalls = $payload['message']['toolCalls'] ?? [];
            
            // Handle single function-call (legacy/alternative)
            if (isset($payload['message']['functionCall'])) {
                $toolCalls = [$payload['message']['functionCall']];
            }

            foreach ($toolCalls as $toolCall) {
                $functionName = $toolCall['function']['name'] ?? '';
                
                if ($functionName === 'save_ticket') {
                    $args = $toolCall['function']['arguments'] ?? [];
                    
                    if (is_string($args)) {
                        $args = json_decode($args, true);
                    }

                    Log::info('Saving ticket with args:', $args);

                    // Try to find a contact to link
                    $phone = $payload['call']['customer']['number'] ?? null;
                    $contactId = null;

                    if ($phone) {
                        // Normalize phone (strip + etc if needed, or search exactly)
                        $contact = \App\Models\Contact::where('phone', 'like', "%$phone%")
                            ->orWhere('mobile', 'like', "%$phone%")
                            ->first();
                        
                        if ($contact) {
                            $contactId = $contact->id;
                            // Update contact with email if provided and missing
                            if (isset($args['email']) && empty($contact->email)) {
                                $contact->update(['email' => $args['email']]);
                                Log::info("Updated contact #{$contactId} with email: {$args['email']}");
                            }
                            // Update phone if missing
                            if (empty($contact->phone)) {
                                $contact->update(['phone' => $phone]);
                            }
                        }
                    }

                    // Fallback to name search if phone not found
                    if (!$contactId && isset($args['customer_name'])) {
                        $contact = \App\Models\Contact::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$args['customer_name']}%"])
                            ->first();
                        if ($contact) {
                            $contactId = $contact->id;
                        }
                    }

                    // Create contact if NOT found
                    if (!$contactId && isset($args['customer_name'])) {
                        $nameParts = explode(' ', $args['customer_name'], 2);
                        $firstName = $nameParts[0];
                        $lastName = $nameParts[1] ?? '';

                        $newContact = \App\Models\Contact::create([
                            'first_name'   => $firstName,
                            'last_name'    => $lastName,
                            'company_name' => $args['company_name'] ?? null,
                            'email'        => $args['email'] ?? null,
                            'phone'        => $phone,
                            'is_vapi_lead' => true,
                            'is_active'    => true,
                        ]);

                        if ($newContact) {
                            $contactId = $newContact->id;
                            Log::info('Created new lead contact from Vapi call, ID: ' . $contactId);
                        }
                    }

                    try {
                        // Save ticket to database
                        $ticket = AiTicket::create([
                            'contact_id'      => $contactId,
                            'call_id'         => $callId,
                            'assistance_type' => $args['assistance_type'] ?? 'Generico',
                            'company_name'    => $args['company_name'] ?? 'N/A',
                            'customer_name'   => $args['customer_name'] ?? 'N/A',
                            'email'           => $args['email'] ?? null,
                            'phone'           => $phone,
                            'description'     => $args['description'] ?? 'N/D',
                            'status'          => 'open',
                        ]);

                        Log::info('Ticket saved successfully, ID: ' . $ticket->id);

                        return response()->json([
                            'results' => [
                                [
                                    'toolCallId' => $toolCall['id'] ?? 'default',
                                    'result'     => "Ticket salvato con successo con ID #" . $ticket->id,
                                ]
                            ]
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error saving ticket: ' . $e->getMessage());
                        return response()->json(['error' => $e->getMessage()], 500);
                    }
                }
            }
        }

        // Handle end-of-call-report
        if (isset($payload['message']['type']) && $payload['message']['type'] === 'end-of-call-report') {
            Log::info('Handling end-of-call-report for call: ' . $callId);
            
            $ticket = AiTicket::where('call_id', $callId)->first();
            
            if ($ticket) {
                $ticket->update([
                    'transcription' => $payload['message']['transcript'] ?? null,
                    'audio_url'     => $payload['message']['recordingUrl'] ?? null,
                ]);
                Log::info('Ticket updated with transcription and audio.');
            } else {
                Log::warning('No ticket found for call_id: ' . $callId);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
