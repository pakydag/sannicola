<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ShopOrder;
use Illuminate\Support\Facades\Auth;

class CustomerAreaController extends Controller
{
    /**
     * Mostra la dashboard del cliente con lo storico ordini.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Recupera gli ordini associati all'email dell'utente, 
        // a prescindere se il cliente ha o meno un record Customer associato (tramite relazione)
        // oppure possiamo cercare tramite il Customer associato a questa email
        $customer = Customer::where('email', $user->email)->first();
        
        $orders = collect();
        if ($customer) {
            $orders = ShopOrder::where('customer_id', $customer->id)->latest()->get();
        }

        return view('public.customer.dashboard', compact('user', 'customer', 'orders'));
    }

    /**
     * Mostra il form per modificare i dati personali e di fatturazione.
     */
    public function profile()
    {
        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->first();

        // Se l'utente non ha ancora un record customer, creiamo un oggetto vuoto per la vista
        if (!$customer) {
            $customer = new Customer();
            $customer->email = $user->email;
        }

        return view('public.customer.profile', compact('user', 'customer'));
    }

    /**
     * Aggiorna i dati personali e di fatturazione del cliente.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'indirizzo' => 'required|string|max:255',
            'citta' => 'required|string|max:255',
            'cap' => 'required|string|max:20',
            'nazione' => 'required|string|max:100',
            'is_azienda' => 'nullable|boolean',
            'ragione_sociale' => 'nullable|required_if:is_azienda,1|string|max:255',
            'partita_iva' => 'nullable|required_if:is_azienda,1|string|max:50',
            'sdi' => 'nullable|string|max:50',
            'pec' => 'nullable|email|max:255',
            'codice_fiscale' => 'nullable|string|max:50',
            'metodo_pagamento_preferito' => 'nullable|string|in:stripe,paypal,bonifico,contrassegno',
        ]);

        // Aggiorna il nome nell'auth basic
        $user->name = $request->nome . ' ' . $request->cognome;
        $user->save();

        // Cerca o crea il profilo cliente per lo shop B2C
        $customer = Customer::where('email', $user->email)->first();
        if (!$customer) {
            $customer = new Customer();
            $customer->email = $user->email;
            $customer->password = \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(12));
        }

        $customer->nome = $request->nome;
        $customer->cognome = $request->cognome;
        $customer->telefono = $request->telefono;
        $customer->cellulare = $request->telefono; // Backup
        $customer->indirizzo = $request->indirizzo;
        $customer->citta = $request->citta;
        $customer->cap = $request->cap;
        $customer->nazione = $request->nazione;
        $customer->codice_fiscale = $request->codice_fiscale;
        $customer->metodo_pagamento_preferito = $request->metodo_pagamento_preferito;

        // Gestione Dati Aziendali
        if ($request->has('is_azienda') && $request->is_azienda) {
            $customer->ragione_sociale = $request->ragione_sociale;
            $customer->partita_iva = $request->partita_iva;
            $customer->sdi = $request->sdi;
            $customer->pec = $request->pec;
        } else {
            // Pulisci i dati aziendali se l'utente deseleziona l'opzione
            $customer->ragione_sociale = null;
            $customer->partita_iva = null;
            $customer->sdi = null;
            $customer->pec = null;
        }

        $customer->save();

        return redirect()->route('public.account.profile')->with('success', 'Profilo aggiornato con successo.');
    }
}
