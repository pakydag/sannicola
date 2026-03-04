<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPrice extends Model
{
    protected $fillable = [
        'booking_structure_id', 'booking_variant_id', 'tipo', 'start_date', 'end_date', 'prezzo'
    ];

    public function structure()
    {
        return $this->belongsTo(BookingStructure::class, 'booking_structure_id');
    }

    public function variant()
    {
        return $this->belongsTo(BookingVariant::class, 'booking_variant_id');
    }
}
