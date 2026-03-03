<?php

namespace App\Http\Controllers;

use App\Models\ShopCollection;
use App\Models\ShopProduct;
use Illuminate\Http\Request;

class PublicShopController extends Controller
{
    public function index()
    {
        $collezioni = ShopCollection::where('visibile', true)->orderBy('ordine')->get();
        return view('public.shop.index', compact('collezioni'));
    }

    public function collezione($slug)
    {
        $collezione = ShopCollection::where('slug', $slug)->where('visibile', true)->firstOrFail();
        
        $tagIds = $collezione->tags->pluck('id');
        
        if ($tagIds->count() > 0) {
            $prodotti = ShopProduct::where('visibile', true)
                ->whereHas('tags', function($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                })
                ->orderBy('ordine')
                ->get();
        } else {
            $prodotti = collect(); // Se la collezione non ha tag, non mostra prodotti per ora
        }

        return view('public.shop.collezione', compact('collezione', 'prodotti'));
    }

    public function prodotto($collezione_slug, $prodotto_slug)
    {
        $prodotto = ShopProduct::with(['variants'])
            ->where('slug', $prodotto_slug)
            ->where('visibile', true)
            ->firstOrFail();

        $collezione = ShopCollection::where('slug', $collezione_slug)->first();

        return view('public.shop.prodotto', compact('prodotto', 'collezione'));
    }
}
