<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    protected $fillable = ['booking_service_category_id', 'nome', 'icona', 'ordine'];

    // Questa relazione è deprecata dopo la migrazione alla struttura piatta, 
    // ma la manteniamo per evitare errori con vecchie chiamate find() se attive
    public function category()
    {
        return $this->belongsTo(BookingServiceCategory::class, 'booking_service_category_id');
    }

    public function structures()
    {
        return $this->belongsToMany(BookingStructure::class, 'booking_structure_services');
    }
}
