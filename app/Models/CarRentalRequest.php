<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Contact;

class CarRentalRequest extends Model
{
    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'telefono',
        'data_ritiro',
        'orario_ritiro',
        'data_riconsegna',
        'orario_riconsegna',
        'numero_posti',
        'messaggio',
        'letto',
        'secure_token',
    ];

    public function messages()
    {
        return $this->hasMany(CarRentalMessage::class)->orderBy('created_at', 'asc');
    }

    protected static function booted()
    {
        static::creating(function ($request) {
            if (empty($request->secure_token)) {
                $request->secure_token = \Illuminate\Support\Str::random(32);
            }
        });

        static::created(function ($request) {
            // Create or update the Contact in CRM
            Contact::updateOrCreate(
                ['email' => $request->email],
                [
                    'first_name' => $request->nome,
                    'last_name' => $request->cognome,
                    'phone' => $request->telefono,
                    'is_lead' => true,
                ]
            );
        });
    }
}
