<?php

namespace App\Http\Controllers;

use App\Models\ShopOrder;
use Illuminate\Http\Request;

class ShopOrderController extends Controller
{
    public function index()
    {
        $ordini = ShopOrder::with('customer')->orderBy('created_at', 'desc')->get();
        return view('admin.shop.ordini.index', compact('ordini'));
    }

    public function edit(ShopOrder $ordine)
    {
        $ordine->load(['customer', 'items.variant.product']);
        return view('admin.shop.ordini.edit', compact('ordine'));
    }

    public function update(Request $request, ShopOrder $ordine)
    {
        $validated = $request->validate([
            'stato' => 'required|string',
            'stato_pagamento' => 'required|string',
            'note_admin' => 'nullable|string',
        ]);

        // Controlliamo se lo stato di spedizione è cambiato
        $old_stato = $ordine->stato;
        
        $ordine->update($validated);
        
        // Se lo stato spedizione è cambiato ed è diverso da "nuovo"
        if ($old_stato !== $ordine->stato && $ordine->stato !== 'nuovo') {
            try {
                \Illuminate\Support\Facades\Mail::to($ordine->customer->email)
                    ->send(new \App\Mail\OrderStatusChanged($ordine));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Errore invio email cambio stato: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.shop.ordini.index')->with('success', 'Ordine aggiornato con successo.');
    }
    
    public function destroy(ShopOrder $ordine)
    {
        $ordine->delete();
        return redirect()->route('admin.shop.ordini.index')->with('success', 'Ordine eliminato con successo.');
    }
}
