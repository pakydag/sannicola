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
        $structure->load(['photos', 'prices', 'variants', 'services', 'extras']);
        $serviceCategories = \App\Models\BookingServiceCategory::with('services')->orderBy('ordine')->get();
        $availableExtras = \App\Models\BookingExtra::orderBy('ordine')->get();
        return view('admin.booking.structures.edit', compact('structure', 'serviceCategories', 'availableExtras'));
    }

    public function update(Request $request, \App\Models\BookingStructure $structure)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'bagni' => 'required|integer|min:1',
            'camere_letto' => 'required|integer|min:1',
            'posti_totali' => 'required|integer|min:1',
            'tipo_prezzo' => 'required|string|in:fisso,persona',
            'attivo' => 'boolean',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|string',
            'varianti' => 'nullable|array',
            'varianti.*.id' => 'required',
            'varianti.*.nome' => 'required|string|max:255',
            'prezzi' => 'nullable|array',
            'prezzi.*.variant_temp_id' => 'nullable', // Used for mapping in persona mode
            'prezzi.*.start_date' => 'required|date',
            'prezzi.*.end_date' => 'required|date|after_or_equal:prezzi.*.start_date',
            'prezzi.*.prezzo' => 'required|numeric|min:0',
        ]);

        $validated['attivo'] = $request->has('attivo');
        $structure->update($validated);

        // 1. Sync Variants
        $variantMap = [];
        $keepVariantIds = [];
        if ($request->tipo_prezzo === 'persona' && $request->has('varianti')) {
            foreach ($request->varianti as $vData) {
                $vid = $vData['id'] ?? null;
                if (!$vid) continue;

                if (str_starts_with((string)$vid, 'new_')) {
                    $variant = $structure->variants()->create(['nome' => $vData['nome']]);
                    $variantMap[(string)$vid] = $variant->id;
                    $keepVariantIds[] = $variant->id;
                } else {
                    $variant = $structure->variants()->find($vid);
                    if ($variant) {
                        $variant->update(['nome' => $vData['nome']]);
                        $keepVariantIds[] = $variant->id;
                        $variantMap[(string)$vid] = $variant->id;
                        $variantMap[(string)$variant->id] = $variant->id; // Direct map too
                    }
                }
            }
        }
        $structure->variants()->whereNotIn('id', $keepVariantIds)->delete();

        // 2. Validation: Overlapping dates for the same variant
        if ($request->has('prezzi')) {
            $prezzi = $request->prezzi;
            foreach ($prezzi as $i => $p1) {
                foreach ($prezzi as $j => $p2) {
                    if ($i !== $j) {
                        $v1 = (string)($p1['variant_temp_id'] ?? 'fisso');
                        $v2 = (string)($p2['variant_temp_id'] ?? 'fisso');

                        if ($v1 === $v2) {
                            $start1 = strtotime($p1['start_date']);
                            $end1 = strtotime($p1['end_date']);
                            $start2 = strtotime($p2['start_date']);
                            $end2 = strtotime($p2['end_date']);

                            if ($start1 < $end2 && $end1 > $start2) {
                                return back()->withErrors(['prezzi' => 'Attenzione: Ci sono periodi che si sovrappongono per la stessa categoria.'])->withInput();
                            }
                        }
                    }
                }
            }
        }

        // 3. Sync Seasonal Prices
        $structure->prices()->delete();
        if ($request->has('prezzi')) {
            foreach ($request->prezzi as $p) {
                $vTempId = isset($p['variant_temp_id']) ? (string)$p['variant_temp_id'] : null;
                $finalVariantId = null;
                
                if ($request->tipo_prezzo === 'persona' && $vTempId) {
                    // 1. Try mapping the temp ID (works for "new_..." and stringified DB IDs)
                    if (isset($variantMap[$vTempId])) {
                        $finalVariantId = $variantMap[$vTempId];
                    } 
                    // 2. Try direct lookup in case it's a raw numeric ID
                    elseif (is_numeric($vTempId)) {
                        $finalVariantId = (int)$vTempId;
                    }
                }

                $priceData = [
                    'start_date' => $p['start_date'],
                    'end_date' => $p['end_date'],
                    'prezzo' => $p['prezzo'],
                    'tipo' => $request->tipo_prezzo === 'fisso' ? 'fisso' : 'persona',
                    'booking_variant_id' => $finalVariantId
                ];
                $structure->prices()->create($priceData);
            }
        }

        // 4. Sync Services
        $structure->services()->sync($request->input('services', []));

        // 5. Sync Extras
        $structure->extras()->sync($request->input('extras', []));

        return back()->with('success', 'Struttura aggiornata con successo.');
    }

    public function destroy(\App\Models\BookingStructure $structure)
    {
        $structure->delete();
        return redirect()->route('admin.booking.structures.index')->with('success', 'Struttura eliminata con successo.');
    }
}
