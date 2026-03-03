<?php

return [
    'accepted' => 'Il campo :attribute deve essere accettato.',
    'active_url' => 'Il campo :attribute non è un URL valido.',
    'after' => 'Il campo :attribute deve essere una data successiva a :date.',
    'alpha' => 'Il campo :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il campo :attribute può contenere solo lettere, numeri, trattini e underscore.',
    'alpha_num' => 'Il campo :attribute può contenere solo lettere e numeri.',
    'array' => 'Il campo :attribute deve essere un array.',
    'before' => 'Il campo :attribute deve essere una data precedente a :date.',
    'between' => [
        'numeric' => 'Il campo :attribute deve essere compreso tra :min e :max.',
        'file' => 'Il campo :attribute deve essere compreso tra :min e :max kilobyte.',
        'string' => 'Il campo :attribute deve essere compreso tra :min e :max caratteri.',
        'array' => 'Il campo :attribute deve avere tra :min e :max elementi.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'confirmed' => 'La conferma del campo :attribute non corrisponde.',
    'date' => 'Il campo :attribute non è una data valida.',
    'email' => 'Il campo :attribute deve essere un indirizzo email valido.',
    'exists' => 'Il campo :attribute selezionato non è valido.',
    'file' => 'Il campo :attribute deve essere un file.',
    'image' => 'Il campo :attribute deve essere un\'immagine.',
    'in' => 'Il campo :attribute selezionato non è valido.',
    'integer' => 'Il campo :attribute deve essere un intero.',
    'max' => [
        'numeric' => 'Il campo :attribute non può essere superiore a :max.',
        'file' => 'Il campo :attribute non può essere superiore a :max kilobyte.',
        'string' => 'Il campo :attribute non può superare :max caratteri.',
        'array' => 'Il campo :attribute non può avere più di :max elementi.',
    ],
    'mimes' => 'Il campo :attribute deve essere un file di tipo: :values.',
    'min' => [
        'numeric' => 'Il campo :attribute deve essere almeno re :min.',
        'file' => 'Il campo :attribute deve essere almeno re :min kilobyte.',
        'string' => 'Il campo :attribute deve essere di almeno :min caratteri.',
        'array' => 'Il campo :attribute deve avere almeno :min elementi.',
    ],
    'numeric' => 'Il campo :attribute deve essere un numero.',
    'password' => 'La password è errata.',
    'required' => 'Il campo :attribute è obbligatorio.',
    'string' => 'Il campo :attribute deve essere una stringa.',
    'unique' => 'Il campo :attribute è già stato preso.',
    
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'attributes' => [
        'name' => 'nome',
        'email' => 'indirizzo email',
        'password' => 'password',
    ],
];
