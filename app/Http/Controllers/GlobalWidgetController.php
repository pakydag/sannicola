<?php

namespace App\Http\Controllers;

use App\Models\GlobalWidget;
use App\Models\ShopCollection;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use App\Models\ShopBrand;
use App\Models\Setting;
use Illuminate\Http\Request;

class GlobalWidgetController extends Controller
{
    public function index()
    {
        $widgets = GlobalWidget::orderBy('id', 'desc')->paginate(20);
        return view('admin.global_widgets.index', compact('widgets'));
    }

    public function create()
    {
        $shop_enabled = Setting::where('key', 'shop_enabled')->value('value') == '1';
        $shop_collections = $shop_enabled ? ShopCollection::orderBy('nome')->get() : collect();
        $shop_categories = $shop_enabled ? ShopCategory::with('children')->whereNull('parent_id')->orderBy('nome')->get() : collect();
        $shop_products = $shop_enabled ? ShopProduct::orderBy('nome')->get() : collect();
        $shop_brands = $shop_enabled ? ShopBrand::orderBy('nome')->get() : collect();

        return view('admin.global_widgets.create', compact('shop_collections', 'shop_categories', 'shop_products', 'shop_brands', 'shop_enabled'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titolo' => 'required|string|max:255',
            'titolo_en' => 'nullable|string|max:255',
            'tipo' => 'required|string|in:gallery,video,mirror_blocks,single_block,section_grid,image_text_image,booking_search,info_blocks,booking_structures,map,shop_collection,shop_featured_products,shop_brands,top_announcement',
            'data' => 'nullable|array',
        ]);

        $data = $this->sanitizeData($validated['data'] ?? []);

        GlobalWidget::create([
            'titolo' => $validated['titolo'],
            'titolo_en' => $validated['titolo_en'] ?? null,
            'tipo' => $validated['tipo'],
            'data' => $data,
        ]);

        return redirect()->route('admin.global-widgets.index')->with('success', 'Widget Globale creato con successo.');
    }

    public function edit(GlobalWidget $globalWidget)
    {
        $shop_enabled = Setting::where('key', 'shop_enabled')->value('value') == '1';
        $shop_collections = $shop_enabled ? ShopCollection::orderBy('nome')->get() : collect();
        $shop_categories = $shop_enabled ? ShopCategory::with('children')->whereNull('parent_id')->orderBy('nome')->get() : collect();
        $shop_products = $shop_enabled ? ShopProduct::orderBy('nome')->get() : collect();
        $shop_brands = $shop_enabled ? ShopBrand::orderBy('nome')->get() : collect();

        return view('admin.global_widgets.edit', compact('globalWidget', 'shop_collections', 'shop_categories', 'shop_products', 'shop_brands', 'shop_enabled'));
    }

    public function update(Request $request, GlobalWidget $globalWidget)
    {
        $validated = $request->validate([
            'titolo' => 'required|string|max:255',
            'titolo_en' => 'nullable|string|max:255',
            'data' => 'nullable|array',
        ]);

        $data = $this->sanitizeData($validated['data'] ?? []);

        $globalWidget->update([
            'titolo' => $validated['titolo'],
            'titolo_en' => $validated['titolo_en'] ?? null,
            'data' => $data,
        ]);

        return redirect()->route('admin.global-widgets.index')->with('success', 'Widget Globale aggiornato con successo.');
    }

    private function sanitizeData(array $data)
    {
        array_walk_recursive($data, function (&$value) {
            if (is_string($value) && str_contains($value, '/storage/')) {
                // Rimuove protocollo e dominio se presenti
                $value = preg_replace('/^https?:\/\/[^\/]+/', '', $value);
            }
        });
        return $data;
    }

    public function destroy(GlobalWidget $globalWidget)
    {
        $globalWidget->delete();
        return redirect()->route('admin.global-widgets.index')->with('success', 'Widget Globale eliminato.');
    }
}
