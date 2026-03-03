<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('nome')->get();
        return view('admin.shop.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.shop.tags.create');
    }

    public function store(Request $request)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (Tag::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
        ]);

        Tag::create($validated);
        
        return redirect()->route('admin.shop.tags.index')->with('success', 'Tag creato con successo.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.shop.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $slug = empty($request->slug) ? Str::slug($request->nome) : Str::slug($request->slug);
        $originalSlug = $slug;
        $count = 1;
        while (Tag::where('slug', $slug)->where('id', '!=', $tag->id)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $tag->id,
        ]);

        $tag->update($validated);
        
        return redirect()->route('admin.shop.tags.index')->with('success', 'Tag aggiornato con successo.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.shop.tags.index')->with('success', 'Tag eliminato con successo.');
    }
}
