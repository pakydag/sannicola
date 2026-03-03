<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStructure extends Model
{
    protected $fillable = [
        'nome', 'descrizione', 'bagni', 'camere_letto', 'posti_totali', 'costo_al_giorno', 'attivo'
    ];

    public function photos()
    {
        return $this->hasMany(BookingPhoto::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
