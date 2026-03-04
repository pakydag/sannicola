<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiTicket extends Model
{
    protected $fillable = [
        'assistance_type',
        'company_name',
        'customer_name',
        'description',
        'status',
    ];
}
