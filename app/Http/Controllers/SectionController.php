<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function index()
    {
        $sezioni = Section::orderBy('ordine')->get();
        return view('admin.sezioni.index', compact('sezioni'));
    }

    public function create()
    {
        return view('admin.sezioni.create');
    }

    public function store(Request $request)
    {
        if (empty($request->slug)) {
            $request->merge(['slug' => null]);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'contenuto' => 'nullable|string',
            'ordine' => 'required|integer',
            'visibile' => 'boolean',
            'tipo' => 'required|in:pagina,archivio',
            'menu_a_tendina' => 'boolean',
            'colonne_griglia' => 'required|integer|min:1|max:6',
            'slug' => 'nullable|string|max:255|unique:sections,slug',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image' => 'nullable|string',
        ]);

        $validated['visibile'] = $request->has('visibile');
        $validated['menu_a_tendina'] = $request->has('menu_a_tendina');

        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $sezione = Section::create($validated);

        if (empty($sezione->slug)) {
            $sezione->update(['slug' => $sezione->id . '-it']);
        }

        return redirect()->route('admin.sezioni.index')->with('success', 'Sezione creata con successo.');
    }

    public function edit(Section $sezioni)
    {
        return view('admin.sezioni.edit', ['sezione' => $sezioni]);
    }

    public function update(Request $request, Section $sezioni)
    {
        if (empty($request->slug)) {
            $request->merge(['slug' => null]);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'contenuto' => 'nullable|string',
            'ordine' => 'required|integer',
            'tipo' => 'required|in:pagina,archivio',
            'menu_a_tendina' => 'boolean',
            'colonne_griglia' => 'required|integer|min:1|max:6',
            'slug' => 'nullable|string|max:255|unique:sections,slug,' . $sezioni->id,
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image' => 'nullable|string',
        ]);

        $validated['visibile'] = $request->has('visibile');
        $validated['menu_a_tendina'] = $request->has('menu_a_tendina');

        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $sezioni->update($validated);

        if (empty($sezioni->slug)) {
            $sezioni->update(['slug' => $sezioni->id . '-it']);
        }

        return redirect()->route('admin.sezioni.index')->with('success', 'Sezione aggiornata con successo.');
    }

    public function destroy(Section $sezioni)
    {
        $sezioni->delete();
        return redirect()->route('admin.sezioni.index')->with('success', 'Sezione eliminata con successo.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:sections,id',
            'order.*.position' => 'required|integer'
        ]);

        foreach ($request->order as $item) {
            Section::where('id', $item['id'])->update(['ordine' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }
}
