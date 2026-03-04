<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingVariant extends Model
{
    protected $fillable = ['booking_structure_id', 'nome'];

    public function structure()
    {
        return $this->belongsTo(BookingStructure::class, 'booking_structure_id');
    }

    public function prices()
    {
        return $this->hasMany(BookingPrice::class);
    }
}
