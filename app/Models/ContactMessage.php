<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'contact_request_id',
        'sender',
        'message',
        'attachment_path',
        'attachment_name',
    ];

    public function contactRequest()
    {
        return $this->belongsTo(ContactRequest::class);
    }
}
