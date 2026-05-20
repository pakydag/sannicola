<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferMessage extends Model
{
    protected $fillable = [
        'transfer_request_id',
        'sender',
        'message',
        'attachment_path',
        'attachment_name',
    ];

    public function transferRequest()
    {
        return $this->belongsTo(TransferRequest::class);
    }
}
