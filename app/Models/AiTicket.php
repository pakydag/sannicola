<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiTicket extends Model
{
    protected $fillable = [
        'contact_id',
        'call_id',
        'assistance_type',
        'company_name',
        'customer_name',
        'email',
        'phone',
        'description',
        'comments',
        'transcription',
        'audio_url',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
