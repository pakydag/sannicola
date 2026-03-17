<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\B2bProduct;
use App\Models\B2bOrder;
use App\Models\B2bCustomer;
use App\Models\B2bBrand;

class AgentPortalController extends Controller
{
    public function dashboard()
    {
        $agent = auth()->user();
        $stats = [
            'orders_count' => B2bOrder::where('agent_id', $agent->id)->count(),
            'pending_orders' => B2bOrder::where('agent_id', $agent->id)->where('status', 'pending')->count(),
            'total_volume' => B2bOrder::where('agent_id', $agent->id)->where('status', 'confirmed')->sum('total_amount'),
        ];
        
        $recent_orders = B2bOrder::where('agent_id', $agent->id)->with('customer')->latest()->take(5)->get();
        
        return view('agent.dashboard', compact('stats', 'recent_orders'));
    }

    public function catalog(Request $request)
    {
        $agent = auth()->user()->load('b2bBrands');
        $brandIds = $agent->b2bBrands->pluck('id');
        
        $query = B2bProduct::whereIn('b2b_brand_id', $brandIds)->where('is_active', true);
        
        if ($request->filled('brand')) {
            $query->where('b2b_brand_id', $request->brand);
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->with('brand', 'variants')->get();
        $authorizedBrands = $agent->b2bBrands;
        
        return view('agent.catalog', compact('products', 'authorizedBrands'));
    }

    public function product(B2bProduct $product)
    {
        $agent = auth()->user();
        // Verifica se l'agente può vedere questo prodotto
        if (!$agent->b2bBrands->contains($product->b2b_brand_id)) {
            abort(403);
        }
        
        $product->load('brand', 'variants');
        return view('agent.product', compact('product'));
    }

    public function cart()
    {
        $cart = session()->get('b2b_cart', []);
        $agent = auth()->user();
        $customers = $agent->b2bCustomers()->orderBy('business_name')->get();
        return view('agent.cart', compact('cart', 'customers'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'variants' => 'required|array',
            'variants.*.id' => 'required|exists:b2b_product_variants,id',
            'variants.*.quantity' => 'required|integer|min:0',
        ]);

        $cart = session()->get('b2b_cart', []);
        $addedCount = 0;

        foreach ($request->variants as $variantInput) {
            $qty = (int)$variantInput['quantity'];
            if ($qty <= 0) continue;

            $variantId = $variantInput['id'];
            $variant = \App\Models\B2bProductVariant::with('product.brand')->find($variantId);

            // Verifica se la variante è già nel carrello
            $found = false;
            foreach ($cart as $index => $item) {
                if ($item['variant_id'] == $variantId) {
                    $cart[$index]['quantity'] += $qty;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cart[] = [
                    'variant_id' => $variant->id,
                    'product_id' => $variant->b2b_product_id,
                    'name' => $variant->product->name,
                    'brand' => $variant->product->brand->name,
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'price' => $variant->product->price ?? 0,
                    'quantity' => $qty,
                ];
            }
            $addedCount++;
        }

        if ($addedCount > 0) {
            session()->put('b2b_cart', $cart);
            return redirect()->route('agent.cart')->with('success', 'Prodotti aggiunti al carrello.');
        }

        return redirect()->back()->with('error', 'Nessuna quantità inserita.');
    }

    public function removeFromCart($index)
    {
        $cart = session()->get('b2b_cart', []);
        if (isset($cart[$index])) {
            unset($cart[$index]);
            session()->put('b2b_cart', array_values($cart));
        }
        return redirect()->back()->with('success', 'Rimosso dal carrello.');
    }

    public function checkout()
    {
        $cart = session()->get('b2b_cart', []);
        if (empty($cart)) {
            return redirect()->route('agent.catalog')->with('error', 'Il carrello è vuoto.');
        }
        
        $customers = B2bCustomer::all();
        return view('agent.checkout', compact('cart', 'customers'));
    }

    public function processCheckout(Request $request)
    {
        $agent = auth()->user();
        $request->validate([
            'b2b_customer_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('agent_customer', 'b2b_customer_id')->where('user_id', $agent->id)
            ],
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('b2b_cart', []);
        if (empty($cart)) return redirect()->route('agent.catalog');

        $order = B2bOrder::create([
            'agent_id' => auth()->id(),
            'b2b_customer_id' => $request->b2b_customer_id,
            'status' => 'pending',
            'notes' => $request->notes,
            'total_amount' => 0, // Calcolato sotto
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $order->items()->create([
                'b2b_product_id' => $item['product_id'],
                'b2b_product_variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            $total += $item['price'] * $item['quantity'];
        }

        $order->update(['total_amount' => $total]);

        session()->forget('b2b_cart');

        return redirect()->route('agent.orders')->with('success', 'Ordine #' . $order->id . ' inviato correttamente all\'amministrazione.');
    }

    public function orders()
    {
        $orders = B2bOrder::where('agent_id', auth()->id())->with('customer')->latest()->get();
        return view('agent.orders', compact('orders'));
    }

    public function orderDetail(B2bOrder $order)
    {
        if ($order->agent_id !== auth()->id()) abort(403);
        $order->load('customer', 'items.product', 'items.variant');
        return view('agent.order_detail', compact('order'));
    }

    public function profile()
    {
        return view('agent.profile', ['user' => auth()->user()]);
    }
}
