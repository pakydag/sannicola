<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
