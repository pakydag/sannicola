<?php

namespace App\Http\Controllers\Admin\B2b;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2bProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = \App\Models\B2bProduct::with('brand', 'variants');
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('brand', function($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $products = $query->get();
        return view('admin.b2b.products.index', compact('products', 'search'));
    }

    public function create()
    {
        $brands = \App\Models\B2bBrand::all();
        return view('admin.b2b.products.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'b2b_brand_id' => 'required|exists:b2b_brands,id',
            'season' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'has_stock' => 'boolean',
            'variants' => 'nullable|array',
        ]);

        $product = \App\Models\B2bProduct::create($request->only(['name', 'b2b_brand_id', 'season', 'description', 'has_stock', 'price']));

        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['size']) || !empty($variantData['color'])) {
                    $product->variants()->create($variantData);
                }
            }
        }

        return redirect()->route('admin.b2b.products.index')->with('success', 'Prodotto B2B creato con successo.');
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
    public function edit(\App\Models\B2bProduct $product)
    {
        $brands = \App\Models\B2bBrand::all();
        $product->load('variants');
        return view('admin.b2b.products.edit', compact('product', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\B2bProduct $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'b2b_brand_id' => 'required|exists:b2b_brands,id',
            'season' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'has_stock' => 'boolean',
            'variants' => 'nullable|array',
        ]);

        $product->update($request->only(['name', 'b2b_brand_id', 'season', 'description', 'has_stock', 'price']));

        // Update Variants: simple way is delete and recreate for this MVP
        // OR better: handle them more carefully. For now, let's sync.
        $product->variants()->delete();
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['size']) || !empty($variantData['color'])) {
                    $product->variants()->create($variantData);
                }
            }
        }

        return redirect()->route('admin.b2b.products.index')->with('success', 'Prodotto B2B aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\B2bProduct $product)
    {
        $product->delete();
        return redirect()->route('admin.b2b.products.index')->with('success', 'Prodotto rimosso dall\'inventario.');
    }
}
