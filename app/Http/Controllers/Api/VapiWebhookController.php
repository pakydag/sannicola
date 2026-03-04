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

        // Check for tool-calls
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

                    try {
                        // Save ticket to database
                        $ticket = AiTicket::create([
                            'assistance_type' => $args['assistance_type'] ?? 'Generico',
                            'company_name'    => $args['company_name'] ?? 'N/A',
                            'customer_name'   => $args['customer_name'] ?? 'N/A',
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

        return response()->json(['status' => 'ok']);
    }
}
