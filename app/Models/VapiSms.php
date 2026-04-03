<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VapiSms extends Model
{
    protected $table = 'vapi_sms';

    protected $fillable = [
        'vapi_id',
        'phone_number',
        'content',
        'received_at',
        'contact_id',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
