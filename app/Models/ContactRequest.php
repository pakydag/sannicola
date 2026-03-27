<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;

class ContactRequest extends Model
{
    protected $fillable = [
        'ragione_sociale',
        'nome',
        'cognome',
        'telefono',
        'email',
        'richiesta',
        'letto',
    ];

    protected static function booted()
    {
        static::created(function ($contactRequest) {
            // Create or update the Contact in CRM
            Contact::updateOrCreate(
                ['email' => $contactRequest->email],
                [
                    'first_name' => $contactRequest->nome,
                    'last_name' => $contactRequest->cognome,
                    'company_name' => $contactRequest->ragione_sociale,
                    'phone' => $contactRequest->telefono,
                    'is_lead' => true,
                ]
            );
        });
    }
}
