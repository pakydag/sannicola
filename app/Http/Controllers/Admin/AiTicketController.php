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
    public function index()
    {
        $tickets = AiTicket::latest()->paginate(20);
        return view('admin.vapi.tickets', compact('tickets'));
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
