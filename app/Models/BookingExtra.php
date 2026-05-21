<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingExtra extends Model
{
    protected $fillable = [
        'nome',
        'nome_en',
        'prezzo',
        'tipo_calcolo',
        'ordine',
    ];

    public function structures()
    {
        return $this->belongsToMany(BookingStructure::class, 'booking_structure_extras', 'booking_extra_id', 'booking_structure_id');
    }

    public function getNomeAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->nome_en)) {
            return $this->nome_en;
        }
        return $value;
    }
}
