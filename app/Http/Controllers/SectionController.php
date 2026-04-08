<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    private function stripDomain($url)
    {
        if (empty($url)) return $url;
        $baseUrl = config('app.url');
        return str_replace($baseUrl, '', $url);
    }

    public function index()
    {
        $this->syncModules();
        $sezioni = Section::orderBy('ordine')->get();
        return view('admin.sezioni.index', compact('sezioni'));
    }

    private function syncModules()
    {
        $modules = [
            'shop' => [
                'setting' => 'shop_enabled',
                'name' => 'Shop',
                'slug' => 'shop'
            ],
            'booking' => [
                'setting' => 'booking_enabled',
                'name' => 'Booking',
                'slug' => 'booking'
            ],
            'b2b' => [
                'setting' => 'shop_enabled', // Using shop_enabled for B2B as well or assuming it's available
                'name' => 'Shop B2B',
                'slug' => 'b2b-shop'
            ],
        ];

        foreach ($modules as $key => $mod) {
            $isEnabled = \App\Models\Setting::where('key', $mod['setting'])->value('value') == '1';
            
            if ($isEnabled) {
                Section::firstOrCreate(
                    ['modulo' => $key],
                    [
                        'nome' => $mod['name'],
                        'slug' => $mod['slug'],
                        'tipo' => 'pagina',
                        'visibile' => true,
                        'mostra_nel_menu' => true,
                        'ordine' => Section::max('ordine') + 1,
                        'colonne_griglia' => 3
                    ]
                );
            }
        }
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
            'modulo' => 'nullable|string|max:50',
            'menu_a_tendina' => 'boolean',
            'mostra_nel_menu' => 'boolean',
            'mostra_nel_footer' => 'boolean',
            'colonne_griglia' => 'required|integer|min:1|max:6',
            'slug' => 'nullable|string|max:255|unique:sections,slug',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image' => 'nullable|string',
        ]);

        $validated['seo_image'] = $this->stripDomain($validated['seo_image']);

        $validated['visibile'] = $request->has('visibile');
        $validated['menu_a_tendina'] = $request->has('menu_a_tendina');
        $validated['mostra_nel_menu'] = $request->has('mostra_nel_menu');
        $validated['mostra_nel_footer'] = $request->has('mostra_nel_footer');

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
            'modulo' => 'nullable|string|max:50',
            'menu_a_tendina' => 'boolean',
            'mostra_nel_menu' => 'boolean',
            'mostra_nel_footer' => 'boolean',
            'colonne_griglia' => 'required|integer|min:1|max:6',
            'slug' => 'nullable|string|max:255|unique:sections,slug,' . $sezioni->id,
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image' => 'nullable|string',
        ]);

        $validated['seo_image'] = $this->stripDomain($validated['seo_image']);

        $validated['visibile'] = $request->has('visibile');
        $validated['menu_a_tendina'] = $request->has('menu_a_tendina');
        $validated['mostra_nel_menu'] = $request->has('mostra_nel_menu');
        $validated['mostra_nel_footer'] = $request->has('mostra_nel_footer');

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
        if ($sezioni->modulo) {
            return redirect()->back()->with('error', 'Non è possibile eliminare sezioni di sistema. Puoi però nasconderle disattivando il flag "Visibile".');
        }
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
