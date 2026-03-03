<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $clienti = Customer::orderBy('cognome')->orderBy('nome')->get();
        return view('admin.shop.clienti.index', compact('clienti'));
    }

    public function create()
    {
        return view('admin.shop.clienti.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'ragione_sociale' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
            'indirizzo' => 'nullable|string|max:255',
            'cap' => 'nullable|string|max:20',
            'citta' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'nazione' => 'nullable|string|max:255',
            'codice_fiscale' => 'nullable|string|max:50',
            'partita_iva' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'cellulare' => 'nullable|string|max:50',
            'sdi' => 'nullable|string|max:7',
            'pec' => 'nullable|string|email|max:255',
            'attivo' => 'boolean',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['attivo'] = $request->has('attivo');

        Customer::create($validated);
        return redirect()->route('admin.shop.clienti.index')->with('success', 'Cliente creato con successo.');
    }

    public function edit(Customer $cliente)
    {
        return view('admin.shop.clienti.edit', compact('cliente'));
    }

    public function update(Request $request, Customer $cliente)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'ragione_sociale' => 'nullable|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers')->ignore($cliente->id)],
            'password' => 'nullable|string|min:8',
            'indirizzo' => 'nullable|string|max:255',
            'cap' => 'nullable|string|max:20',
            'citta' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'nazione' => 'nullable|string|max:255',
            'codice_fiscale' => 'nullable|string|max:50',
            'partita_iva' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'cellulare' => 'nullable|string|max:50',
            'sdi' => 'nullable|string|max:7',
            'pec' => 'nullable|string|email|max:255',
            'attivo' => 'boolean',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['attivo'] = $request->has('attivo');

        $cliente->update($validated);
        return redirect()->route('admin.shop.clienti.index')->with('success', 'Cliente aggiornato con successo.');
    }

    public function destroy(Customer $cliente)
    {
        $cliente->delete();
        return redirect()->route('admin.shop.clienti.index')->with('success', 'Cliente eliminato con successo.');
    }
}
