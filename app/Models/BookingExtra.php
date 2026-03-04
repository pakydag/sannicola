<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingExtra extends Model
{
    protected $fillable = [
        'nome',
        'prezzo',
        'ordine',
    ];

    public function structures()
    {
        return $this->belongsToMany(BookingStructure::class, 'booking_structure_extras', 'booking_extra_id', 'booking_structure_id');
    }
}
