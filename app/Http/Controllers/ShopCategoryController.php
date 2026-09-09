<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    public function index()
    {
        $categorie = ShopCategory::with('parent')->orderBy('ordine')->get();
        return view('admin.shop.categorie.index', compact('categorie'));
    }

    public function create()
    {
        $categorie_padre = ShopCategory::with('parent')->orderBy('ordine')->get();
        return view('admin.shop.categorie.create', compact('categorie_padre'));
    }

    public function store(Request $request)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\ShopCategory::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shop_categories,slug',
            'parent_id' => 'nullable|exists:shop_categories,id',
            'visibile' => 'boolean',
        ]);
        $validated['visibile'] = $request->has('visibile');
        $validated['parent_id'] = $request->filled('parent_id') ? $request->parent_id : null;
        
        ShopCategory::create($validated);
        
        return redirect()->route('admin.shop.categorie.index')->with('success', 'Categoria creata con successo.');
    }

    public function edit(ShopCategory $categoria)
    {
        $excludeIds = array_merge([$categoria->id], $categoria->getAllDescendantIds());
        $categorie_padre = ShopCategory::whereNotIn('id', $excludeIds)->with('parent')->orderBy('ordine')->get();
        return view('admin.shop.categorie.edit', compact('categoria', 'categorie_padre'));
    }

    public function update(Request $request, ShopCategory $categoria)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\ShopCategory::where('slug', $slug)->where('id', '!=', $categoria->id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $excludeIds = array_merge([$categoria->id], $categoria->getAllDescendantIds());

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shop_categories,slug,' . $categoria->id,
            'parent_id' => [
                'nullable',
                'exists:shop_categories,id',
                \Illuminate\Validation\Rule::notIn($excludeIds),
            ],
            'visibile' => 'boolean',
        ]);
        $validated['visibile'] = $request->has('visibile');
        $validated['parent_id'] = $request->filled('parent_id') ? $request->parent_id : null;
        
        $categoria->update($validated);
        
        return redirect()->route('admin.shop.categorie.index')->with('success', 'Categoria aggiornata con successo.');
    }

    public function destroy(ShopCategory $categoria)
    {
        $categoria->delete();
        return redirect()->route('admin.shop.categorie.index')->with('success', 'Categoria eliminata con successo.');
    }
}
