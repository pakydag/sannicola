<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingExtra;
use Illuminate\Http\Request;

class BookingExtraController extends Controller
{
    public function index()
    {
        $extras = BookingExtra::orderBy('ordine')->get();
        return view('admin.booking.extras.index', compact('extras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'nome_en' => 'nullable|string|max:255',
            'prezzo' => 'required|numeric|min:0',
            'tipo_calcolo' => 'required|string|in:giornaliero,una_tantum,giornaliero_persona,una_tantum_persona',
            'ordine' => 'nullable|integer',
        ]);

        BookingExtra::create($validated);

        return back()->with('success', 'Servizio extra aggiunto con successo.');
    }

    public function update(Request $request, BookingExtra $extra)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'nome_en' => 'nullable|string|max:255',
            'prezzo' => 'required|numeric|min:0',
            'tipo_calcolo' => 'required|string|in:giornaliero,una_tantum,giornaliero_persona,una_tantum_persona',
            'ordine' => 'nullable|integer',
        ]);

        $extra->update($validated);

        return back()->with('success', 'Servizio extra aggiornato con successo.');
    }

    public function destroy(BookingExtra $extra)
    {
        $extra->delete();
        return back()->with('success', 'Servizio extra eliminato con successo.');
    }
}
