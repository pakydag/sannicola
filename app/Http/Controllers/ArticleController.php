<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('titolo', 'like', '%' . $request->search . '%')
                  ->orWhere('sottotitolo', 'like', '%' . $request->search . '%');
        }

        if ($request->has('section_id') && $request->section_id != '') {
            $query->where('section_id', $request->section_id);
        }

        $articoli = $query->orderBy('ordine')->get();
        $sezioni = Section::orderBy('ordine')->get();

        return view('admin.articoli.index', compact('articoli', 'sezioni'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sezioni = Section::orderBy('ordine')->get();
        return view('admin.articoli.create', compact('sezioni'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (empty($request->slug)) {
            $request->merge(['slug' => null]);
        }

        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'titolo' => 'required|string|max:255',
            'sottotitolo' => 'nullable|string|max:255',
            'descrizione' => 'required|string',
            'link' => 'nullable|url|max:255',
            'foto' => 'nullable|string', // string for URL from File Manager
            'allegato' => 'nullable|string', // string for URL from File Manager
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'visibile' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image' => 'nullable|string',
            'has_contact_form' => 'boolean',
        ]);

        $validated['visibile'] = $request->has('visibile');
        $validated['has_contact_form'] = $request->has('has_contact_form');

        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $articolo = Article::create($validated);

        if (empty($articolo->slug)) {
            $articolo->update(['slug' => $articolo->id . '-it']);
        }

        if ($request->filled('foto')) {
            try {
                $articolo->addMediaFromUrl($request->foto)->toMediaCollection('foto');
            } catch (\Exception $e) {
                // Ignore download errors on locahost or save manually
            }
        }

        if ($request->filled('allegato')) {
            try {
                $articolo->addMediaFromUrl($request->allegato)->toMediaCollection('allegati');
            } catch (\Exception $e) {}
        }

        return redirect()->route('admin.articoli.index')->with('success', 'Articolo creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $articoli)
    {
        // Di solito non serve nel pannello admin se si ha l'edit
        return view('admin.articoli.show', compact('articoli'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $articoli) // Laravel route model binding usa il singolare o quello definito nella rotta
    {
        $sezioni = Section::orderBy('ordine')->get();
        return view('admin.articoli.edit', ['articolo' => $articoli, 'sezioni' => $sezioni]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $articoli)
    {
        if (empty($request->slug)) {
            $request->merge(['slug' => null]);
        }

        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'titolo' => 'required|string|max:255',
            'sottotitolo' => 'nullable|string|max:255',
            'descrizione' => 'required|string',
            'link' => 'nullable|url|max:255',
            'foto' => 'nullable|string',
            'allegato' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $articoli->id,
            'visibile' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image' => 'nullable|string',
            'has_contact_form' => 'boolean',
        ]);

        $validated['visibile'] = $request->has('visibile');
        $validated['has_contact_form'] = $request->has('has_contact_form');

        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $articoli->update($validated);

        if (empty($articoli->slug)) {
            $articoli->update(['slug' => $articoli->id . '-it']);
        }

        if ($request->filled('foto')) {
            try {
                $articoli->clearMediaCollection('foto');
                $articoli->addMediaFromUrl($request->foto)->toMediaCollection('foto');
            } catch (\Exception $e) {}
        }

        if ($request->filled('allegato')) {
            try {
                $articoli->clearMediaCollection('allegati');
                $articoli->addMediaFromUrl($request->allegato)->toMediaCollection('allegati');
            } catch (\Exception $e) {}
        }

        return redirect()->route('admin.articoli.index')->with('success', 'Articolo aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $articoli)
    {
        // Spatie will automatically clean up the files thanks to the database foreign key / events
        // we just delete the model.

        $articoli->delete();

        return redirect()->route('admin.articoli.index')->with('success', 'Articolo eliminato con successo.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:articles,id',
            'order.*.position' => 'required|integer'
        ]);

        foreach ($request->order as $item) {
            Article::where('id', $item['id'])->update(['ordine' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }
}
