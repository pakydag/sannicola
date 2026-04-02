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
    public function index(Request $request)
    {
        $query = AiTicket::with(['contact', 'department'])->latest();

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        $tickets = $query->paginate(20);
        $departments = \App\Models\Department::where('is_active', true)->get();

        return view('admin.vapi.tickets', compact('tickets', 'departments'));
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
     * Synchronize call data from Vapi.ai API
     */
    public function syncCall(AiTicket $ticket, VapiService $vapiService)
    {
        $callId = $ticket->vapi_call_id ?: $ticket->call_id;

        if (!$callId) {
            return back()->withErrors(['error' => 'Nessun ID chiamata registrato per questo ticket.']);
        }

        $callDetails = $vapiService->getCallDetails($callId);

        if (!$callDetails) {
            return back()->withErrors(['error' => 'Impossibile recuperare i dati da Vapi.ai. Verifica la connessione o l\'ID.']);
        }

        // Estrazione dati (stessa logica robusta del webhook)
        $cost = $callDetails['cost'] ?? ($callDetails['totalCost'] ?? ($callDetails['total_cost'] ?? 0));
        $duration = $callDetails['duration'] ?? 0;
        
        if (!$duration && isset($callDetails['startedAt']) && isset($callDetails['endedAt'])) {
            $start = strtotime($callDetails['startedAt']);
            $end = strtotime($callDetails['endedAt']);
            $duration = $end - $start;
        }

        $recordingUrl = $callDetails['recordingUrl'] ?? ($callDetails['recording_url'] ?? null);
        $transcript = $callDetails['transcript'] ?? ($callDetails['fullTranscript'] ?? null);

        $ticket->update([
            'cost' => $cost,
            'duration' => $duration,
            'recording_url' => $recordingUrl ?: $ticket->recording_url,
            'audio_url' => $recordingUrl ?: $ticket->audio_url,
            'transcription' => $transcript ?: $ticket->transcription,
        ]);

        return back()->with('success', 'Dati sincronizzati correttamente da Vapi.ai.');
    }
}
