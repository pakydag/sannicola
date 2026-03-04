<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BookingCustomer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'booking_customers';

    protected $fillable = [
        'nome', 'cognome', 'email', 'password',
        'telefono', 'nazione', 'citta', 'attivo'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'attivo' => 'boolean',
        'password' => 'hashed',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }
}
