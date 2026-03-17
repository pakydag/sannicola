<?php

namespace App\Http\Controllers\Admin\B2b;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2bBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = \App\Models\B2bBrand::all();
        return view('admin.b2b.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.b2b.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        \App\Models\B2bBrand::create($request->all());

        return redirect()->route('admin.b2b.brands.index')->with('success', 'Marchio creato con successo.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\B2bBrand $brand)
    {
        $brand->delete();

        return redirect()->route('admin.b2b.brands.index')->with('success', 'Marchio eliminato con successo.');
    }
}
