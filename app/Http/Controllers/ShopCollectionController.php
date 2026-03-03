<?php

namespace App\Http\Controllers;

use App\Models\ShopCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopCollectionController extends Controller
{
    public function index()
    {
        $collezioni = ShopCollection::orderBy('ordine')->get();
        return view('admin.shop.collezioni.index', compact('collezioni'));
    }

    public function create()
    {
        $tags = \App\Models\Tag::orderBy('nome')->get();
        return view('admin.shop.collezioni.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\ShopCollection::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shop_collections,slug',
            'foto' => 'nullable|string',
            'visibile' => 'boolean',
        ]);
        $validated['visibile'] = $request->has('visibile');
        
        $collezione = ShopCollection::create($validated);
        
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
        $collezione->tags()->sync($tagIds);
        
        return redirect()->route('admin.shop.collezioni.index')->with('success', 'Collezione creata con successo.');
    }

    public function edit(ShopCollection $collezione)
    {
        $tags = \App\Models\Tag::orderBy('nome')->get();
        return view('admin.shop.collezioni.edit', compact('collezione', 'tags'));
    }

    public function update(Request $request, ShopCollection $collezione)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\ShopCollection::where('slug', $slug)->where('id', '!=', $collezione->id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:shop_collections,slug,' . $collezione->id,
            'foto' => 'nullable|string',
            'visibile' => 'boolean',
        ]);
        $validated['visibile'] = $request->has('visibile');
        
        $collezione->update($validated);
        
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
        $collezione->tags()->sync($tagIds);
        
        return redirect()->route('admin.shop.collezioni.index')->with('success', 'Collezione aggiornata con successo.');
    }

    public function destroy(ShopCollection $collezione)
    {
        $collezione->delete();
        return redirect()->route('admin.shop.collezioni.index')->with('success', 'Collezione eliminata con successo.');
    }
}
