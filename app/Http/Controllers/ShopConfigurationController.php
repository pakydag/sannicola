<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ShopConfigurationController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.shop.configuration', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = [
            'shop_stock_infinite' => $request->has('shop_stock_infinite') ? '1' : '0',
            'shop_price_preview' => $request->has('shop_price_preview') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Configurazione aggiornata con successo.');
    }
}
