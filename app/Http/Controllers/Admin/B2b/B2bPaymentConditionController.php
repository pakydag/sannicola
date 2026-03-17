<?php

namespace App\Http\Controllers\Admin\B2b;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2bPaymentConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conditions = \App\Models\B2bPaymentCondition::all();
        return view('admin.b2b.payment-conditions.index', compact('conditions'));
    }

    public function create()
    {
        return view('admin.b2b.payment-conditions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\B2bPaymentCondition::create($request->all());

        return redirect()->route('admin.b2b.payment-conditions.index')->with('success', 'Condizione di pagamento creata con successo.');
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
    public function edit(\App\Models\B2bPaymentCondition $paymentCondition)
    {
        return view('admin.b2b.payment-conditions.edit', compact('paymentCondition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\B2bPaymentCondition $paymentCondition)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $paymentCondition->update($request->all());

        return redirect()->route('admin.b2b.payment-conditions.index')->with('success', 'Condizione di pagamento aggiornata con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\B2bPaymentCondition $paymentCondition)
    {
        $paymentCondition->delete();

        return redirect()->route('admin.b2b.payment-conditions.index')->with('success', 'Condizione di pagamento eliminata con successo.');
    }
}
