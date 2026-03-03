<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingStructureController extends Controller
{
    public function index()
    {
        $structures = \App\Models\BookingStructure::with('photos')->get();
        return view('admin.booking.structures.index', compact('structures'));
    }

    public function create()
    {
        return view('admin.booking.structures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'bagni' => 'required|integer|min:1',
            'camere_letto' => 'required|integer|min:1',
            'posti_totali' => 'required|integer|min:1',
            'costo_al_giorno' => 'required|numeric|min:0',
            'attivo' => 'boolean',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|string',
        ]);

        $validated['attivo'] = $request->has('attivo');

        $structure = \App\Models\BookingStructure::create($validated);

        if ($request->has('photos')) {
            foreach ($request->photos as $photoPath) {
                if ($photoPath) {
                    $structure->photos()->create(['path' => $photoPath]);
                }
            }
        }

        return redirect()->route('admin.booking.structures.index')->with('success', 'Struttura creata con successo.');
    }

    public function edit(\App\Models\BookingStructure $structure)
    {
        $structure->load('photos');
        return view('admin.booking.structures.edit', compact('structure'));
    }

    public function update(Request $request, \App\Models\BookingStructure $structure)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'bagni' => 'required|integer|min:1',
            'camere_letto' => 'required|integer|min:1',
            'posti_totali' => 'required|integer|min:1',
            'costo_al_giorno' => 'required|numeric|min:0',
            'attivo' => 'boolean',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|string',
        ]);

        $validated['attivo'] = $request->has('attivo');

        $structure->update($validated);

        // Simple photo management: Sync by removing old and adding new
        // Better would be to manage individual photo deletion
        if ($request->has('photos')) {
            $structure->photos()->delete();
            foreach ($request->photos as $photoPath) {
                if ($photoPath) {
                    $structure->photos()->create(['path' => $photoPath]);
                }
            }
        }

        return redirect()->route('admin.booking.structures.index')->with('success', 'Struttura aggiornata con successo.');
    }

    public function destroy(\App\Models\BookingStructure $structure)
    {
        $structure->delete();
        return redirect()->route('admin.booking.structures.index')->with('success', 'Struttura eliminata con successo.');
    }
}
