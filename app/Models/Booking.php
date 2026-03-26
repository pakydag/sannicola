<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id', 'booking_structure_id', 'start_date', 'end_date',
        'adulti', 'bambini', 'ospiti_dettaglio', 'extra_dettaglio', 'totale_prezzo', 'stato', 'stato_pagamento', 'metodo_pagamento'
    ];

    protected $casts = [
        'ospiti_dettaglio' => 'array',
        'extra_dettaglio' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(BookingCustomer::class, 'customer_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function structure()
    {
        return $this->belongsTo(BookingStructure::class, 'booking_structure_id');
    }
}
