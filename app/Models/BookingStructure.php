<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStructure extends Model
{
    protected $fillable = [
        'nome', 'nome_en', 'descrizione', 'descrizione_en', 'bagni', 'camere_letto', 'posti_totali', 'costo_al_giorno', 'attivo', 'prenotabile', 'tipo_prezzo', 'seo_title', 'seo_description', 'seo_image'
    ];

    public function photos()
    {
        return $this->hasMany(BookingPhoto::class);
    }

    public function prices()
    {
        return $this->hasMany(BookingPrice::class);
    }

    public function variants()
    {
        return $this->hasMany(BookingVariant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function services()
    {
        return $this->belongsToMany(BookingService::class, 'booking_structure_services');
    }

    public function extras()
    {
        return $this->belongsToMany(BookingExtra::class, 'booking_structure_extras', 'booking_structure_id', 'booking_extra_id');
    }

    public function getNomeAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->nome_en)) {
            return $this->nome_en;
        }
        return $value;
    }

    public function getDescrizioneAttribute($value)
    {
        if (app()->getLocale() === 'en' && !empty($this->descrizione_en)) {
            return $this->descrizione_en;
        }
        return $value;
    }
}
