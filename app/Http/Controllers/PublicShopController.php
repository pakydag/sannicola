<?php

namespace App\Http\Controllers;

use App\Models\ShopCategory;
use App\Models\ShopCollection;
use App\Models\ShopProduct;
use Illuminate\Http\Request;

class PublicShopController extends Controller
{
    protected $settings;

    public function __construct()
    {
        $this->settings = \App\Models\Setting::pluck('value', 'key')->all();
        $this->global_seo = [
            'home_seo_title' => $this->settings['home_seo_title'] ?? null,
            'home_seo_description' => $this->settings['home_seo_description'] ?? null,
            'home_seo_image' => $this->settings['home_seo_image'] ?? null,
        ];
    }

    public function index(Request $request)
    {
        $section = \App\Models\Section::where('modulo', 'shop')->first();
        $settings = $this->settings;
        
        $marca_slug = $request->get('marca');
        $categoria_slug = $request->get('categoria');
        
        $prodotti = collect();
        $marca = null;
        $categoria = null;
        $filtered = false;
        
        $seoTitle = $section->seo_title ?: 'Shop';
        $seoDesc = $section->seo_description ?: ($this->global_seo['home_seo_description'] ?? 'Esplora il nostro shop online.');
        $seoImage = ($section->seo_image ?: ($this->global_seo['home_seo_image'] ?? 'img/default-share.jpg'));

        if ($marca_slug) {
            $marca = \App\Models\ShopBrand::where('slug', $marca_slug)->firstOrFail();
            $prodotti = ShopProduct::where('shop_brand_id', $marca->id)
                        ->where('visibile', true)
                        ->with(['variants', 'collection'])
                        ->orderBy('ordine')
                        ->get();
            $filtered = true;
            $seoTitle = $marca->nome . ' - ' . $seoTitle;
            if($marca->foto) $seoImage = $marca->foto;
        } elseif ($categoria_slug) {
            $categoria = ShopCategory::where('slug', $categoria_slug)->where('visibile', true)->firstOrFail();
            
            // Reusing recursive logic for categories
            $categoryIds = [$categoria->id];
            $childrenIds = $categoria->children()->pluck('id')->toArray();
            $categoryIds = array_merge($categoryIds, $childrenIds);
            foreach ($categoria->children as $child) {
                $categoryIds = array_merge($categoryIds, $child->children()->pluck('id')->toArray());
            }
            $categoryIds = array_unique($categoryIds);
            
            $prodotti = ShopProduct::where('visibile', true)
                ->whereIn('shop_category_id', $categoryIds)
                ->with(['variants', 'collection'])
                ->orderBy('ordine')
                ->get();
            $filtered = true;
            $seoTitle = $categoria->nome . ' - ' . $seoTitle;
        }

        $seo = [
            'title' => $seoTitle . ' - ' . ($this->global_seo['home_seo_title'] ?: config('app.name')),
            'description' => $seoDesc,
            'image' => $seoImage,
            'url' => url()->current()
        ];

        $collezioni = ShopCollection::where('visibile', true)->orderBy('ordine')->get();
        return view('public.shop.index', compact('collezioni', 'prodotti', 'marca', 'categoria', 'filtered', 'seo', 'settings'));
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
        
        $seo = [
            'title' => ($collezione->seo_title ?: $collezione->nome) . ' - ' . ($this->global_seo['home_seo_title'] ?: config('app.name')),
            'description' => $collezione->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($collezione->descrizione), 160),
            'image' => $collezione->seo_image ?: ($collezione->foto ?: ($this->global_seo['home_seo_image'] ?? 'img/default-share.jpg')),
            'url' => url()->current()
        ];

        $settings = $this->settings;
        return view('public.shop.collezione', compact('collezione', 'prodotti', 'seo', 'settings'));
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

        $seo = [
            'title' => ($categoria->seo_title ?: $categoria->nome) . ' - ' . ($this->global_seo['home_seo_title'] ?: config('app.name')),
            'description' => $categoria->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($categoria->descrizione), 160),
            'image' => $categoria->seo_image ?: ($categoria->foto ?: ($this->global_seo['home_seo_image'] ?? 'img/default-share.jpg')),
            'url' => url()->current()
        ];

        $settings = $this->settings;
        return view('public.shop.categoria', compact('categoria', 'prodotti', 'seo', 'settings'));
    }

    public function prodotto($collezione_slug, $prodotto_slug)
    {
        $prodotto = ShopProduct::with(['variants'])
            ->where('slug', $prodotto_slug)
            ->where('visibile', true)
            ->firstOrFail();

        $collezione = ShopCollection::where('slug', $collezione_slug)->first();

        $seo = [
            'title' => ($prodotto->seo_title ?: $prodotto->nome) . ' - ' . ($this->global_seo['home_seo_title'] ?: config('app.name')),
            'description' => $prodotto->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($prodotto->descrizione), 160),
            'image' => $prodotto->seo_image ?: ($prodotto->foto ?: ($this->global_seo['home_seo_image'] ?? 'img/default-share.jpg')),
            'url' => url()->current()
        ];

        $settings = $this->settings;
        return view('public.shop.prodotto', compact('prodotto', 'collezione', 'seo', 'settings'));
    }
}
