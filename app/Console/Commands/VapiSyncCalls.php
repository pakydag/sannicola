<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VapiSyncCalls extends Command
{
    protected $signature = 'vapi:sync-calls';
    protected $description = 'Sync recent calls from Vapi to local tickets';

    public function handle()
    {
        $apiKey = '02d6eef9-2b56-4db7-b4cc-162cbad6b2c7';
        $baseUrl = 'https://api.vapi.ai';

        $this->info("Fetching last 50 calls from Vapi...");
        
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->get("{$baseUrl}/call", [
            'limit' => 50
        ]);

        if ($response->failed()) {
            $this->error("Error: " . $response->body());
            return 1;
        }

        $calls = $response->json();
        $tickets = \App\Models\AiTicket::whereNull('transcription')->get();

        foreach ($tickets as $ticket) {
            $this->line("--------------------------------------------------");
            $this->info("Ticket #{$ticket->id}: {$ticket->customer_name} ({$ticket->company_name}) - " . $ticket->created_at->format('d/m H:i'));
            
            $bestMatch = null;
            $minDiff = 5; // Max 5 minutes difference

            foreach ($calls as $call) {
                // Try phone matching first if possible
                $callPhone = $call['customer']['number'] ?? null;
                $ticketPhone = $ticket->contact->phone ?? ($ticket->contact->mobile ?? null);
                
                $callTime = \Carbon\Carbon::parse($call['startedAt'])->setTimezone('Europe/Rome');
                $diff = abs($ticket->created_at->diffInMinutes($callTime));
                
                // If phone matches exactly and time is close, it's a strong match
                if ($callPhone && $ticketPhone && str_contains($callPhone, substr($ticketPhone, -8)) && $diff < 15) {
                    $bestMatch = $call;
                    $minDiff = $diff;
                    break; 
                }

                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $bestMatch = $call;
                }
            }

            if (!$bestMatch) {
                $this->warn("No match found for Ticket #{$ticket->id}.");
                continue;
            }

            $callTime = \Carbon\Carbon::parse($bestMatch['startedAt'])->setTimezone('Europe/Rome');
            $this->info("Auto-linking Ticket #{$ticket->id} to Call ID: {$bestMatch['id']} (Diff: {$minDiff} min)");
            
            $ticket->update([
                'call_id'       => $bestMatch['id'],
                'transcription' => $bestMatch['transcript'] ?? null,
                'audio_url'     => $bestMatch['recordingUrl'] ?? null,
            ]);
            $this->info("Updated Ticket #{$ticket->id}!");
        }

        return 0;
    }
}
