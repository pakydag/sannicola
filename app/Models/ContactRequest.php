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
        'secure_token',
    ];

    public function messages()
    {
        return $this->hasMany(ContactMessage::class)->orderBy('created_at', 'asc');
    }

    protected static function booted()
    {
        static::creating(function ($contactRequest) {
            if (empty($contactRequest->secure_token)) {
                $contactRequest->secure_token = \Illuminate\Support\Str::random(32);
            }
        });

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
