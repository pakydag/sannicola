<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ShopDeliverySlot extends Model
{
    protected $table = 'shop_delivery_slots';

    protected $fillable = [
        'orario_consegna',
        'orario_limite',
        'periodo',
        'costo',
        'attivo',
        'ordine'
    ];

    protected $casts = [
        'attivo' => 'boolean',
        'ordine' => 'integer',
        'costo' => 'decimal:2',
    ];

    /**
     * Controlla se la fascia oraria è disponibile per la data specificata
     */
    public function isAvailableForDate(string|Carbon $date): bool
    {
        if (!$this->attivo) {
            return false;
        }

        $dateCarbon = is_string($date) ? Carbon::parse($date)->startOfDay() : $date->copy()->startOfDay();
        $today = Carbon::today();

        // Se la data è nel passato
        if ($dateCarbon->lt($today)) {
            return false;
        }

        // Controlla se per il giorno della settimana è attivo questo periodo (mattina / pomeriggio)
        $dayOfWeek = (string)$dateCarbon->isoFormat('E');
        $deliveryDaysJson = Setting::where('key', 'shop_delivery_days')->value('value');
        if ($deliveryDaysJson) {
            $deliveryDays = json_decode($deliveryDaysJson, true);
            if (isset($deliveryDays[$dayOfWeek])) {
                $dayConfig = $deliveryDays[$dayOfWeek];
                if (is_array($dayConfig)) {
                    $periodKey = strtolower($this->periodo ?: 'mattina');
                    if (empty($dayConfig[$periodKey])) {
                        return false;
                    }
                }
            } elseif (!in_array($dayOfWeek, $deliveryDays)) {
                return false;
            }
        }

        // Se la data è oggi, controlla che l'ora attuale sia prima dell'orario limite
        if ($dateCarbon->equalTo($today)) {
            $currentTime = Carbon::now()->format('H:i');
            $limitTime = Carbon::parse($this->orario_limite)->format('H:i');
            return $currentTime < $limitTime;
        }

        // Se è una data futura, è prenotabile
        return true;
    }
}
