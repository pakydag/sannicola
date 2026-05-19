<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarRentalMessage extends Model
{
    protected $fillable = [
        'car_rental_request_id',
        'sender',
        'message',
        'attachment_path',
        'attachment_name',
    ];

    public function carRentalRequest()
    {
        return $this->belongsTo(CarRentalRequest::class);
    }
}
