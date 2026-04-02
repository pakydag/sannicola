<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiTicket;
use App\Services\VapiService;
use Illuminate\Http\Request;

class AiTicketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index(Request $request, VapiService $vapiService)
    {
        // 1. AUTO-SYNC: Sincronizziamo gli ultimi 5 ticket caricati senza costo (massimo 5 per non rallentare troppo)
        $unsynced = AiTicket::where(function($q) {
                $q->whereNull('cost')->orWhere('cost', 0);
            })
            ->whereNotNull('vapi_call_id')
            ->where('created_at', '>', now()->subDays(7))
            ->latest()
            ->limit(5)
            ->get();

        foreach ($unsynced as $ticket) {
            $this->performSync($ticket, $vapiService);
        }

        // 2. Query normale per l'elenco
        $query = AiTicket::with(['contact', 'department'])->latest();

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        $tickets = $query->paginate(20);
        $departments = \App\Models\Department::where('is_active', true)->get();

        return view('admin.vapi.tickets', compact('tickets', 'departments'));
    }

    /**
     * Sincronizza tutto ciò che manca degli ultimi 30 giorni
     */
    public function bulkSync(VapiService $vapiService)
    {
        $unsynced = AiTicket::where(function($q) {
                $q->whereNull('cost')->orWhere('cost', 0);
            })
            ->whereNotNull('vapi_call_id')
            ->where('created_at', '>', now()->subDays(30))
            ->get();

        $count = 0;
        foreach ($unsynced as $ticket) {
            if ($this->performSync($ticket, $vapiService)) {
                $count++;
            }
        }

        return back()->with('success', "Sincronizzazione completata: aggiornati $count ticket.");
    }

    /**
     * Helper interno per eseguire la sincronizzazione singola
     */
    private function performSync(AiTicket $ticket, VapiService $vapiService)
    {
        $callId = $ticket->vapi_call_id ?: $ticket->call_id;
        if (!$callId) return false;

        $callDetails = $vapiService->getCallDetails($callId);
        if (!$callDetails) return false;

        $cost = $callDetails['cost'] ?? ($callDetails['totalCost'] ?? ($callDetails['total_cost'] ?? 0));
        $duration = $callDetails['duration'] ?? 0;
        
        if (!$duration && isset($callDetails['startedAt']) && isset($callDetails['endedAt'])) {
            $start = strtotime($callDetails['startedAt']);
            $end = strtotime($callDetails['endedAt']);
            $duration = $end - $start;
        }

        $recordingUrl = $callDetails['recordingUrl'] ?? ($callDetails['recording_url'] ?? null);
        $transcript = $callDetails['transcript'] ?? ($callDetails['fullTranscript'] ?? null);

        return $ticket->update([
            'cost' => $cost,
            'duration' => $duration,
            'recording_url' => $recordingUrl ?: $ticket->recording_url,
            'audio_url' => $recordingUrl ?: $ticket->audio_url,
            'transcription' => $transcript ?: $ticket->transcription,
        ]);
    }

    /**
     * Display the specified ticket.
     */
    public function show(AiTicket $ticket)
    {
        $ticket->load('contact');
        return view('admin.vapi.show', compact('ticket'));
    }

    /**
     * Mark the ticket as closed.
     */
    public function close(AiTicket $ticket)
    {
        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('admin.vapi.tickets.index')->with('success', 'Ticket chiuso con successo.');
    }

    /**
     * Update ticket comments.
     */
    public function updateComments(Request $request, AiTicket $ticket)
    {
        $ticket->update([
            'comments' => $request->comments,
        ]);

        return back()->with('success', 'Commenti salvati con successo.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(AiTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.vapi.tickets.index')->with('success', 'Ticket eliminato con successo.');
    }

    /**
     * Synchronize call data from Vapi.ai API
     */
    public function syncCall(AiTicket $ticket, VapiService $vapiService)
    {
        if ($this->performSync($ticket, $vapiService)) {
            return back()->with('success', 'Dati sincronizzati correttamente da Vapi.ai.');
        }
        return back()->withErrors(['error' => 'Impossibile sincronizzare i dati.']);
    }
}
