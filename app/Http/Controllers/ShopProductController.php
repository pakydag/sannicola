<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Models\ShopCategory;
use App\Models\ShopCollection;
use App\Models\ShopBrand;
use App\Models\ShopVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopProductController extends Controller
{
    public function index(Request $request)
    {
        $query = ShopProduct::with(['category', 'collection'])->orderBy('ordine');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('sku_padre', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q2) use ($search) {
                      $q2->where('nome', 'like', "%{$search}%");
                  });
            });
        }
        
        $prodotti = $query->paginate(20)->withQueryString();
        return view('admin.shop.prodotti.index', compact('prodotti'));
    }

    public function create()
    {
        $categorie = ShopCategory::with(['children', 'parent'])->orderBy('ordine')->get();
        $collezioni = ShopCollection::orderBy('ordine')->get();
        $marche = ShopBrand::where('visibile', true)->orderBy('ordine')->orderBy('nome')->get();
        $tags = \App\Models\Tag::orderBy('nome')->get();
        return view('admin.shop.prodotti.create', compact('categorie', 'collezioni', 'marche', 'tags'));
    }

    public function store(Request $request)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\ShopProduct::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shop_products,slug',
            'shop_category_id' => 'nullable|exists:shop_categories,id',
            'shop_collection_id' => 'nullable|exists:shop_collections,id',
            'shop_brand_id' => 'nullable|exists:shop_brands,id',
            'marca' => 'nullable|string|max:255',
            'descrizione' => 'nullable|string',
            'sku_padre' => 'nullable|string|max:255',
            'visibile' => 'boolean',
            'foto_aggiuntive' => 'nullable|array',
            'variants' => 'nullable|array',
        ]);
        
        $validated['visibile'] = $request->has('visibile');
        
        $prodotto = ShopProduct::create($validated);

        $tagIds = $request->input('tags', []);
        if ($request->has('tags_string') && !empty(trim($request->tags_string))) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->tags_string)));
            foreach ($tagNames as $nome) {
                if (!empty($nome)) {
                    $slug = \Illuminate\Support\Str::slug($nome);
                    $originalSlug = $slug;
                    $count = 1;
                    while (\App\Models\Tag::where('slug', $slug)->where('nome', '!=', $nome)->exists()) {
                        $slug = "{$originalSlug}-{$count}";
                        $count++;
                    }
                    $tag = \App\Models\Tag::firstOrCreate(['nome' => $nome], ['slug' => $slug]);
                    $tagIds[] = $tag->id;
                }
            }
        }
        $prodotto->tags()->sync($tagIds);

        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variantData) {
                if (empty($variantData['taglia']) && empty($variantData['colore']) && empty($variantData['prezzo'])) {
                    continue;
                }
                $prodotto->variants()->create([
                    'sku' => $variantData['sku'] ?? null,
                    'ean' => $variantData['ean'] ?? null,
                    'colore' => $variantData['colore'] ?? null,
                    'taglia' => $variantData['taglia'] ?? null,
                    'prezzo' => $variantData['prezzo'] ?? 0,
                    'prezzo_scontato' => $variantData['prezzo_scontato'] ?? null,
                    'quantita' => $variantData['quantita'] ?? 0,
                    'foto' => $variantData['foto'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.shop.prodotti.edit', $prodotto)->with('success', 'Prodotto creato con successo.');
    }

    public function edit(ShopProduct $prodotto)
    {
        $categorie = ShopCategory::with(['children', 'parent'])->orderBy('ordine')->get();
        $collezioni = ShopCollection::orderBy('ordine')->get();
        $marche = ShopBrand::orderBy('ordine')->orderBy('nome')->get();
        $tags = \App\Models\Tag::orderBy('nome')->get();
        $prodotto->load('variants', 'tags');
        return view('admin.shop.prodotti.edit', compact('prodotto', 'categorie', 'collezioni', 'marche', 'tags'));
    }

    public function update(Request $request, ShopProduct $prodotto)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\ShopProduct::where('slug', $slug)->where('id', '!=', $prodotto->id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shop_products,slug,' . $prodotto->id,
            'shop_category_id' => 'nullable|exists:shop_categories,id',
            'shop_collection_id' => 'nullable|exists:shop_collections,id',
            'shop_brand_id' => 'nullable|exists:shop_brands,id',
            'marca' => 'nullable|string|max:255',
            'descrizione' => 'nullable|string',
            'sku_padre' => 'nullable|string|max:255',
            'visibile' => 'boolean',
            'foto_aggiuntive' => 'nullable|array',
            'variants' => 'nullable|array',
        ]);
        
        $validated['visibile'] = $request->has('visibile');
        
        $prodotto->update($validated);

        $tagIds = $request->input('tags', []);
        if ($request->has('tags_string') && !empty(trim($request->tags_string))) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->tags_string)));
            foreach ($tagNames as $nome) {
                if (!empty($nome)) {
                    $slug = \Illuminate\Support\Str::slug($nome);
                    $originalSlug = $slug;
                    $count = 1;
                    while (\App\Models\Tag::where('slug', $slug)->where('nome', '!=', $nome)->exists()) {
                        $slug = "{$originalSlug}-{$count}";
                        $count++;
                    }
                    $tag = \App\Models\Tag::firstOrCreate(['nome' => $nome], ['slug' => $slug]);
                    $tagIds[] = $tag->id;
                }
            }
        }
        $prodotto->tags()->sync($tagIds);

        $keptVariantIds = [];
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variantData) {
                if (empty($variantData['taglia']) && empty($variantData['colore']) && empty($variantData['prezzo'])) {
                    continue;
                }
                if (!empty($variantData['id'])) {
                    $variant = ShopVariant::find($variantData['id']);
                    if ($variant && $variant->shop_product_id == $prodotto->id) {
                        $variant->update($variantData);
                        $keptVariantIds[] = $variant->id;
                    }
                } else {
                    $newVariant = $prodotto->variants()->create([
                        'sku' => $variantData['sku'] ?? null,
                        'ean' => $variantData['ean'] ?? null,
                        'colore' => $variantData['colore'] ?? null,
                        'taglia' => $variantData['taglia'] ?? null,
                        'prezzo' => $variantData['prezzo'] ?? 0,
                        'prezzo_scontato' => $variantData['prezzo_scontato'] ?? null,
                        'quantita' => $variantData['quantita'] ?? 0,
                        'foto' => $variantData['foto'] ?? null,
                    ]);
                    $keptVariantIds[] = $newVariant->id;
                }
            }
        }
        $prodotto->variants()->whereNotIn('id', $keptVariantIds)->delete();

        return redirect()->route('admin.shop.prodotti.edit', $prodotto)->with('success', 'Prodotto aggiornato con successo.');
    }

    public function destroy(ShopProduct $prodotto)
    {
        $prodotto->delete();
        return redirect()->route('admin.shop.prodotti.index')->with('success', 'Prodotto eliminato con successo.');
    }
}
