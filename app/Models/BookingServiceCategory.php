<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingServiceCategory extends Model
{
    protected $fillable = ['nome', 'icona', 'ordine'];

    public function services()
    {
        return $this->hasMany(BookingService::class)->orderBy('ordine');
    }
}
