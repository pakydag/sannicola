<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopShippingCost;
use App\Models\Setting;
use Illuminate\Http\Request;

class ShopShippingCostController extends Controller
{
    public function index()
    {
        $costs = ShopShippingCost::orderBy('nazione')->get();
        $freeThreshold = Setting::where('key', 'shop_free_shipping_threshold')->value('value') ?? 0;
        
        return view('admin.shop.shipping_costs.index', compact('costs', 'freeThreshold'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nazione' => 'required|string|unique:shop_shipping_costs,nazione',
            'costo' => 'required|numeric|min:0'
        ]);

        ShopShippingCost::create($request->only('nazione', 'costo'));

        return redirect()->back()->with('success', 'Nazione aggiunta con successo.');
    }

    public function updateThreshold(Request $request)
    {
        $request->validate([
            'shop_free_shipping_threshold' => 'nullable|numeric|min:0'
        ]);

        Setting::updateOrCreate(
            ['key' => 'shop_free_shipping_threshold'],
            ['value' => $request->shop_free_shipping_threshold ?? 0]
        );

        return redirect()->back()->with('success', 'Soglia spedizione gratuita aggiornata.');
    }

    public function destroy(ShopShippingCost $shippingCost)
    {
        $shippingCost->delete();
        return redirect()->back()->with('success', 'Nazione rimossa con successo.');
    }
}
