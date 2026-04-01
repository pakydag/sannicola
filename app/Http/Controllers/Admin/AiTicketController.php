<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiTicket;
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
     * Remove the specified ticket from storage.
     */
    public function destroy(AiTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.vapi.tickets.index')->with('success', 'Ticket eliminato con successo.');
    }
}
