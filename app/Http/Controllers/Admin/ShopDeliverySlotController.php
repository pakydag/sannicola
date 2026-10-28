<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopDeliverySlot;
use App\Models\Setting;
use Illuminate\Http\Request;

class ShopDeliverySlotController extends Controller
{
    public function index()
    {
        $slots = ShopDeliverySlot::orderBy('orario_consegna')->get();
        
        $deliveryDaysJson = Setting::where('key', 'shop_delivery_days')->value('value');
        $deliveryDaysRaw = $deliveryDaysJson ? json_decode($deliveryDaysJson, true) : null;
        
        $deliveryDays = [];
        for ($d = 1; $d <= 7; $d++) {
            $dStr = (string)$d;
            if (is_array($deliveryDaysRaw)) {
                if (isset($deliveryDaysRaw[$dStr]) && is_array($deliveryDaysRaw[$dStr])) {
                    $deliveryDays[$dStr] = [
                        'mattina' => !empty($deliveryDaysRaw[$dStr]['mattina']),
                        'pomeriggio' => !empty($deliveryDaysRaw[$dStr]['pomeriggio']),
                    ];
                } elseif (in_array($dStr, $deliveryDaysRaw)) {
                    $deliveryDays[$dStr] = ['mattina' => true, 'pomeriggio' => true];
                } else {
                    $deliveryDays[$dStr] = ['mattina' => false, 'pomeriggio' => false];
                }
            } else {
                // Default: Lun-Sab mattina e pomeriggio
                $deliveryDays[$dStr] = [
                    'mattina' => $d <= 6,
                    'pomeriggio' => $d <= 6,
                ];
            }
        }
        
        $maxDays = Setting::where('key', 'shop_delivery_max_days')->value('value') ?? 7;

        return view('admin.shop.delivery_slots.index', compact('slots', 'deliveryDays', 'maxDays'));
    }

    public function storeSlot(Request $request)
    {
        if ($request->has('costo') && is_string($request->costo)) {
            $request->merge(['costo' => str_replace(',', '.', $request->costo)]);
        }

        $request->validate([
            'orario_consegna' => 'required|string|max:20',
            'orario_limite' => 'required|string|max:20',
            'periodo' => 'required|in:mattina,pomeriggio',
            'costo' => 'nullable|numeric|min:0',
        ]);

        $orarioConsegna = \Carbon\Carbon::parse($request->orario_consegna)->format('H:i');
        $orarioLimite = \Carbon\Carbon::parse($request->orario_limite)->format('H:i');

        ShopDeliverySlot::create([
            'orario_consegna' => $orarioConsegna,
            'orario_limite' => $orarioLimite,
            'periodo' => $request->periodo ?: ($orarioConsegna >= '14:00' ? 'pomeriggio' : 'mattina'),
            'costo' => (float)($request->costo ?? 0),
            'attivo' => true,
            'ordine' => 0
        ]);

        return redirect()->back()->with('success', 'Fascia oraria di consegna aggiunta con successo.');
    }

    public function updateSlot(Request $request, ShopDeliverySlot $slot)
    {
        if ($request->has('costo') && is_string($request->costo)) {
            $request->merge(['costo' => str_replace(',', '.', $request->costo)]);
        }

        $request->validate([
            'orario_consegna' => 'required|string|max:20',
            'orario_limite' => 'required|string|max:20',
            'periodo' => 'required|in:mattina,pomeriggio',
            'costo' => 'nullable|numeric|min:0',
        ]);

        $orarioConsegna = \Carbon\Carbon::parse($request->orario_consegna)->format('H:i');
        $orarioLimite = \Carbon\Carbon::parse($request->orario_limite)->format('H:i');

        $slotData = [
            'orario_consegna' => $orarioConsegna,
            'orario_limite' => $orarioLimite,
            'periodo' => $request->periodo,
            'costo' => (float)($request->costo ?? 0),
        ];

        if ($request->has('has_attivo_field')) {
            $slotData['attivo'] = $request->boolean('attivo');
        }

        $slot->update($slotData);

        return redirect()->back()->with('success', 'Fascia oraria aggiornata con successo.');
    }

    public function toggleSlot(ShopDeliverySlot $slot)
    {
        $slot->update(['attivo' => !$slot->attivo]);
        $status = $slot->attivo ? 'attivata' : 'disattivata';
        return redirect()->back()->with('success', "Fascia oraria {$slot->orario_consegna} {$status}.");
    }

    public function destroySlot(ShopDeliverySlot $slot)
    {
        $slot->delete();
        return redirect()->back()->with('success', 'Fascia oraria rimossa con successo.');
    }

    public function updateDays(Request $request)
    {
        $request->validate([
            'delivery_days' => 'nullable|array',
            'max_days' => 'required|integer|min:1|max:60'
        ]);

        $processedDays = [];
        for ($d = 1; $d <= 7; $d++) {
            $dStr = (string)$d;
            $mattina = !empty($request->input("delivery_days.{$dStr}.mattina"));
            $pomeriggio = !empty($request->input("delivery_days.{$dStr}.pomeriggio"));
            $processedDays[$dStr] = [
                'mattina' => $mattina,
                'pomeriggio' => $pomeriggio
            ];
        }

        Setting::updateOrCreate(
            ['key' => 'shop_delivery_days'],
            ['value' => json_encode($processedDays)]
        );

        Setting::updateOrCreate(
            ['key' => 'shop_delivery_max_days'],
            ['value' => $request->max_days]
        );

        return redirect()->back()->with('success', 'Giorni e periodi di consegna aggiornati con successo.');
    }
}
