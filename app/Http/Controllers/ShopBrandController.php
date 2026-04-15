<?php

namespace App\Http\Controllers;

use App\Models\ShopBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopBrandController extends Controller
{
    public function index()
    {
        $marche = ShopBrand::orderBy('ordine')->orderBy('nome')->get();
        return view('admin.shop.marche.index', compact('marche'));
    }

    public function create()
    {
        return view('admin.shop.marche.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'        => 'required|string|max:255',
            'foto'        => 'nullable|string|max:1000',
            'descrizione' => 'nullable|string',
            'visibile'    => 'boolean',
        ]);

        $slug = Str::slug($request->nome);
        $original = $slug;
        $count = 1;
        while (ShopBrand::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        ShopBrand::create([
            'nome'        => $request->nome,
            'slug'        => $slug,
            'foto'        => $request->foto,
            'descrizione' => $request->descrizione,
            'visibile'    => $request->has('visibile'),
            'ordine'      => $request->input('ordine', 0),
        ]);

        return redirect()->route('admin.shop.marche.index')->with('success', 'Marca creata con successo.');
    }

    public function edit(ShopBrand $marca)
    {
        return view('admin.shop.marche.edit', compact('marca'));
    }

    public function update(Request $request, ShopBrand $marca)
    {
        $request->validate([
            'nome'        => 'required|string|max:255',
            'foto'        => 'nullable|string|max:1000',
            'descrizione' => 'nullable|string',
            'visibile'    => 'boolean',
        ]);

        $slug = Str::slug($request->nome);
        $original = $slug;
        $count = 1;
        while (ShopBrand::where('slug', $slug)->where('id', '!=', $marca->id)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        $marca->update([
            'nome'        => $request->nome,
            'slug'        => $slug,
            'foto'        => $request->foto,
            'descrizione' => $request->descrizione,
            'visibile'    => $request->has('visibile'),
            'ordine'      => $request->input('ordine', 0),
        ]);

        return redirect()->route('admin.shop.marche.index')->with('success', 'Marca aggiornata con successo.');
    }

    public function destroy(ShopBrand $marca)
    {
        $marca->delete();
        return redirect()->route('admin.shop.marche.index')->with('success', 'Marca eliminata con successo.');
    }
}
