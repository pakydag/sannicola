<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id', 'booking_structure_id', 'start_date', 'end_date',
        'adulti', 'bambini', 'totale_prezzo', 'stato', 'stato_pagamento', 'metodo_pagamento'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function structure()
    {
        return $this->belongsTo(BookingStructure::class, 'booking_structure_id');
    }
}
