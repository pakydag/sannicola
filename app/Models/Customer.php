<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome', 'cognome', 'ragione_sociale', 'email', 'password',
        'indirizzo', 'cap', 'citta', 'provincia', 'nazione',
        'codice_fiscale', 'partita_iva', 'telefono', 'cellulare',
        'sdi', 'pec', 'attivo', 'metodo_pagamento_preferito'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'attivo' => 'boolean',
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
