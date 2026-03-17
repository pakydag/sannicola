<?php

namespace App\Http\Controllers\Admin\B2b;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2bCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = \App\Models\B2bCustomer::with('paymentCondition')->get();
        return view('admin.b2b.customers.index', compact('customers'));
    }

    public function create()
    {
        $conditions = \App\Models\B2bPaymentCondition::all();
        return view('admin.b2b.customers.create', compact('conditions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'vat_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'payment_condition_id' => 'nullable|exists:b2b_payment_conditions,id',
        ]);

        \App\Models\B2bCustomer::create($request->all());

        return redirect()->route('admin.b2b.customers.index')->with('success', 'Cliente B2B creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\B2bCustomer $customer)
    {
        $conditions = \App\Models\B2bPaymentCondition::all();
        return view('admin.b2b.customers.edit', compact('customer', 'conditions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\B2bCustomer $customer)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'vat_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'payment_condition_id' => 'nullable|exists:b2b_payment_conditions,id',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.b2b.customers.index')->with('success', 'Cliente B2B aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\B2bCustomer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.b2b.customers.index')->with('success', 'Cliente B2B eliminato con successo.');
    }
}
