<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
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
        
        $query = ShopProduct::where('visibile', true)
            ->where(function($q) use ($collezione, $tagIds) {
                $q->where('shop_collection_id', $collezione->id);
                if ($tagIds->count() > 0) {
                    $q->orWhereHas('tags', function($q2) use ($tagIds) {
                        $q2->whereIn('tags.id', $tagIds);
                    });
                }
            })
            ->with(['variants', 'collection'])
            ->orderBy('ordine');
            
        $prodotti = $query->get();

        return view('public.shop.collezione', compact('collezione', 'prodotti'));
    }

    public function categoria($slug)
    {
        $categoria = ShopCategory::where('slug', $slug)->where('visibile', true)->firstOrFail();
        
        // Get all products in this category AND its subcategories
        $categoryIds = [$categoria->id];
        $childrenIds = $categoria->children()->pluck('id')->toArray();
        $categoryIds = array_merge($categoryIds, $childrenIds);
        
        foreach ($categoria->children as $child) {
            $categoryIds = array_merge($categoryIds, $child->children()->pluck('id')->toArray());
        }

        $categoryIds = array_unique($categoryIds);
        
        \Illuminate\Support\Facades\Log::info("Category IDs: " . implode(',', $categoryIds));

        $prodotti = ShopProduct::where('visibile', true)
            ->whereIn('shop_category_id', $categoryIds)
            ->with(['variants', 'collection'])
            ->orderBy('ordine')
            ->get();

        \Illuminate\Support\Facades\Log::info("Products Count: " . $prodotti->count());

        return view('public.shop.categoria', compact('categoria', 'prodotti'));
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
