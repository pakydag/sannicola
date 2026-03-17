<?php

namespace App\Http\Controllers\Admin\B2b;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2bOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = \App\Models\B2bOrder::with('agent', 'customer')->latest()->get();
        return view('admin.b2b.orders.index', compact('orders'));
    }

    public function edit(\App\Models\B2bOrder $order)
    {
        $order->load('agent', 'customer', 'items.product', 'items.variant');
        return view('admin.b2b.orders.edit', compact('order'));
    }

    public function update(Request $request, \App\Models\B2bOrder $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'payment_method' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.id' => 'required|exists:b2b_order_items,id',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $order->update([
            'status' => $request->status,
            'payment_method' => $request->payment_method
        ]);

        if ($request->has('items')) {
            $total = 0;
            foreach ($request->items as $itemData) {
                $item = $order->items()->find($itemData['id']);
                if ($item) {
                    $item->update([
                        'quantity' => $itemData['quantity'],
                        'price' => $itemData['price']
                    ]);
                    $total += $itemData['quantity'] * $itemData['price'];
                }
            }
            $order->update(['total_amount' => $total]);
        }

        return redirect()->route('admin.b2b.orders.edit', $order)->with('success', 'Ordine aggiornato con successo.');
    }

    public function sendOrderCopy(Request $request, \App\Models\B2bOrder $order)
    {
        $request->validate([
            'payment_method' => 'nullable|in:stripe,paypal,bonifico,none'
        ]);

        $paymentMethod = $request->payment_method ?: 'none';
        $paymentLink = null;

        if ($paymentMethod === 'stripe') {
            try {
                $stripeSecret = \App\Models\Setting::where('key', 'stripe_secret')->value('value');
                \Stripe\Stripe::setApiKey($stripeSecret);

                $line_items = [];
                foreach ($order->items as $item) {
                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $item->product->name . ' (' . $item->variant->size . ' / ' . ($item->variant->color ?? 'Unico') . ')',
                            ],
                            'unit_amount' => round($item->price * 100),
                        ],
                        'quantity' => $item->quantity,
                    ];
                }

                $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => $line_items,
                    'mode' => 'payment',
                    'success_url' => route('admin.b2b.dashboard') . '?success=payment', 
                    'cancel_url' => route('admin.b2b.dashboard') . '?cancel=payment',
                    'client_reference_id' => 'B2B-' . $order->id,
                    'customer_email' => $order->customer->email,
                ]);

                $paymentLink = $checkout_session->url;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Errore Stripe: ' . $e->getMessage());
            }
        } elseif ($paymentMethod === 'paypal') {
            try {
                $clientId = \App\Models\Setting::where('key', 'paypal_client_id')->value('value');
                $secret = \App\Models\Setting::where('key', 'paypal_secret')->value('value');
                $mode = \App\Models\Setting::where('key', 'paypal_mode')->value('value') ?: 'sandbox';
                $baseUrl = ($mode === 'live') ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v1/oauth2/token');
                curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $tokenResult = curl_exec($ch);
                $accessToken = json_decode($tokenResult)->access_token;
                curl_close($ch);

                $orderData = [
                    "intent" => "CAPTURE",
                    "purchase_units" => [[
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($order->total_amount, 2, '.', '')
                        ]
                    ]],
                    "application_context" => [
                        "return_url" => route('admin.b2b.dashboard'),
                        "cancel_url" => route('admin.b2b.dashboard')
                    ]
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v2/checkout/orders');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $accessToken]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $orderResult = json_decode(curl_exec($ch));
                curl_close($ch);

                if (isset($orderResult->links)) {
                    foreach ($orderResult->links as $link) {
                        if ($link->rel === 'approve') {
                            $paymentLink = $link->href;
                        }
                    }
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Errore PayPal: ' . $e->getMessage());
            }
        }

        try {
            \Illuminate\Support\Facades\Mail::to($order->agent->email)
                ->send(new \App\Mail\B2bOrderCopy($order, $paymentLink, $paymentMethod));
            
            return redirect()->back()->with('success', 'Email inviata correttamente all\'agente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Errore invio email: ' . $e->getMessage());
        }
    }

}
