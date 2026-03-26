<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiTicket extends Model
{
    protected $fillable = [
        'contact_id',
        'assistance_type',
        'company_name',
        'customer_name',
        'description',
        'status',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
