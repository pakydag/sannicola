<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPhoto extends Model
{
    protected $fillable = ['booking_structure_id', 'path', 'ordine'];

    public function structure()
    {
        return $this->belongsTo(BookingStructure::class, 'booking_structure_id');
    }
}
