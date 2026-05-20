<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingServiceCategory;
use App\Models\BookingService;
use Illuminate\Http\Request;

class BookingServiceController extends Controller
{
    public function index()
    {
        $services = BookingService::orderBy('ordine')->get();
        return view('admin.booking.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'nome_en' => 'nullable|string|max:255',
            'icona' => 'required|string|max:10',
        ]);

        $maxOrdine = BookingService::max('ordine') ?? 0;

        BookingService::create([
            'nome' => $request->nome,
            'nome_en' => $request->nome_en,
            'icona' => $request->icona,
            'ordine' => $maxOrdine + 1,
            'booking_service_category_id' => null // Deprecated field
        ]);

        return back()->with('success', 'Servizio creato con successo.');
    }

    public function update(Request $request, BookingService $service)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'nome_en' => 'nullable|string|max:255',
            'icona' => 'required|string|max:10',
        ]);

        $service->update([
            'nome' => $request->nome,
            'nome_en' => $request->nome_en,
            'icona' => $request->icona,
        ]);

        return back()->with('success', 'Servizio aggiornato con successo.');
    }

    public function destroy(BookingService $service)
    {
        $service->delete();
        return back()->with('success', 'Servizio eliminato con successo.');
    }
}
