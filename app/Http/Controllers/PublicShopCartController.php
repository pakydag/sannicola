<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicShopCartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('public.shop.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:shop_variants,id',
            'quantita' => 'required|integer|min:1'
        ]);

        $variant = \App\Models\ShopVariant::with('product')->findOrFail($request->variant_id);
        
        $cart = session()->get('cart', []);
        
        $cartKey = $variant->id;
        
        $qtyRichiesta = $request->quantita;
        if (isset($cart[$cartKey])) {
            $qtyRichiesta += $cart[$cartKey]['quantita'];
        }

        $settings = \App\Models\Setting::pluck('value', 'key')->all();
        $isInfinite = ($settings['shop_stock_infinite'] ?? '0') == '1';

        if (!$isInfinite && $qtyRichiesta > $variant->quantita) {
             return redirect()->back()->with('error', 'Quantità richiesta superiore alla disponibilità in magazzino.');
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantita'] += $request->quantita;
        } else {
            $prezzo = $variant->prezzo_scontato && $variant->prezzo_scontato > 0 ? $variant->prezzo_scontato : $variant->prezzo;
            $foto = $variant->foto ?? ($variant->product->foto_aggiuntive[0] ?? null);
            
            $cart[$cartKey] = [
                'variant_id' => $variant->id,
                'prodotto_nome' => $variant->product->nome,
                'colore' => $variant->colore,
                'taglia' => $variant->taglia,
                'prezzo' => $prezzo,
                'quantita' => $request->quantita,
                'foto' => $foto
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Prodotto aggiunto al carrello!');
    }

    public function remove($variant_id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$variant_id])) {
            unset($cart[$variant_id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Prodotto rimosso dal carrello.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('public.shop.index')->with('error', 'Il tuo carrello è vuoto.');
        }

        $shippingCosts = \App\Models\ShopShippingCost::orderBy('nazione')->get();
        $freeThreshold = \App\Models\Setting::where('key', 'shop_free_shipping_threshold')->value('value') ?? 0;

        return view('public.shop.checkout', compact('cart', 'shippingCosts', 'freeThreshold'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('public.shop.index')->with('error', 'Il tuo carrello è vuoto.');
        }

        // 1. Validation for the different fields depending on checkout mode
        $rules = [
            'checkout_mode' => 'required|in:guest,register,login',
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:50',
            'indirizzo' => 'required|string|max:255',
            'citta' => 'required|string|max:255',
            'cap' => 'required|string|max:20',
            'nazione' => 'required|string|max:100',
        ];

        // Se richiede la fattura come azienda
        if ($request->is_azienda) {
            $rules['ragione_sociale'] = 'required|string|max:255';
            $rules['partita_iva'] = 'required|string|max:50';
            // SDI e PEC sono solitamente opzionali
        }

        // Se sceglie di registrarsi
        if ($request->checkout_mode === 'register') {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['email'] .= '|unique:users,email'; // Unico nella tabella users
        }

        $request->validate($rules);

        // 2. Handle User Creation if 'register'
        if ($request->checkout_mode === 'register') {
            $user = \App\Models\User::create([
                'name' => $request->nome . ' ' . $request->cognome,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'user' // Default B2C role
            ]);
            // Log in the user automatically
            \Illuminate\Support\Facades\Auth::login($user);
        }

        // 3. Create or update the Customer record (B2C & Billing profile)
        $customer = \App\Models\Customer::where('email', $request->email)->first();
        if (!$customer) {
            $customer = new \App\Models\Customer();
            $customer->email = $request->email;
            // Genera una password casuale se è ospite e non ha scelto di registrarsi (richiesto da schema DB)
            $customer->password = \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(12));
        }

        $customer->nome = $request->nome;
        $customer->cognome = $request->cognome;
        $customer->telefono = $request->telefono;
        $customer->indirizzo = $request->indirizzo;
        $customer->citta = $request->citta;
        $customer->cap = $request->cap;
        $customer->nazione = $request->nazione;
        
        if ($request->is_azienda) {
            $customer->ragione_sociale = $request->ragione_sociale;
            $customer->partita_iva = $request->partita_iva;
            $customer->sdi = $request->sdi;
            $customer->pec = $request->pec;
        }

        $customer->save();

        // 4. Calculate Totals
        $totaleImponibile = 0;
        foreach ($cart as $item) {
            $totaleImponibile += $item['prezzo'] * $item['quantita'];
        }

        // Calcolo Dinamico Spedizione
        $costoSpedizione = \App\Models\ShopShippingCost::where('nazione', $request->nazione)->value('costo') ?? 5.00;
        $freeThreshold = \App\Models\Setting::where('key', 'shop_free_shipping_threshold')->value('value') ?? 0;

        if ($freeThreshold > 0 && $totaleImponibile >= $freeThreshold) {
            $costoSpedizione = 0;
        }

        $totaleOrdine = $totaleImponibile + $costoSpedizione;

        // 5. Create the Order record
        $order = new \App\Models\ShopOrder();
        $order->customer_id = $customer->id;
        $order->numero_ordine = 'ORD-' . strtoupper(uniqid());
        $order->stato = 'nuovo';
        $order->stato_pagamento = 'attesa';
        $order->metodo_pagamento = $request->payment_method ?? 'bonifico';
        $order->note_cliente = $request->note_ordine;
        
        $order->totale_imponibile = $totaleImponibile;
        $order->totale_iva = 0; // Se c'è IVA specifica da calcolare, aggiungerla
        $order->totale_ordine = $totaleOrdine;
        $order->costo_spedizione = $costoSpedizione;

        $order->spedizione_nome = $request->nome . ' ' . $request->cognome;
        $order->spedizione_indirizzo = $request->indirizzo;
        $order->spedizione_cap = $request->cap;
        $order->spedizione_citta = $request->citta;
        $order->spedizione_provincia = $request->provincia; // Opzionale
        $order->spedizione_nazione = $request->nazione;

        // 5b. Sync with Unified CRM Contact
        $contact = \App\Models\Contact::where('email', $customer->email)->first();
        if (!$contact) {
            $contact = new \App\Models\Contact();
            $contact->email = $customer->email;
            $contact->password = $customer->password;
        }
        $contact->first_name = $customer->nome;
        $contact->last_name = $customer->cognome;
        $contact->phone = $customer->telefono;
        $contact->address = $customer->indirizzo;
        $contact->city = $customer->citta;
        $contact->zip_code = $customer->cap;
        $contact->country = $customer->nazione;
        $contact->company_name = $customer->ragione_sociale;
        $contact->vat_number = $customer->partita_iva;
        $contact->sdi_code = $customer->sdi;
        $contact->pec = $customer->pec;
        $contact->is_shop_customer = true;
        // Tags are synced automatically by the model's booted event
        $contact->save();

        $order->contact_id = $contact->id;
        $order->save();

        // 6. Create Order Items and decrease stock
        foreach ($cart as $variant_id => $item) {
            $variant = \App\Models\ShopVariant::find($variant_id);

            \App\Models\ShopOrderItem::create([
                'shop_order_id' => $order->id,
                'shop_variant_id' => $variant ? $variant->id : null,
                'nome_prodotto' => $item['prodotto_nome'],
                'sku' => $variant ? $variant->sku : null,
                'ean' => $variant ? $variant->ean : null,
                'colore' => $item['colore'],
                'taglia' => $item['taglia'],
                'prezzo_unitario' => $item['prezzo'],
                'quantita' => $item['quantita'],
                'subtotale' => $item['prezzo'] * $item['quantita'],
            ]);

            // Aggiorna Magazzino
            if ($variant) {
                $isInfinite = \App\Models\Setting::where('key', 'shop_stock_infinite')->value('value') == '1';
                if (!$isInfinite) {
                    // Previene che la quantità vada sotto 0 se possibile
                    $variant->quantita = max(0, $variant->quantita - $item['quantita']);
                    $variant->save();
                }
            }
        }

        // 7. Handle Payments and Post-order actions
        if ($order->metodo_pagamento === 'bonifico' || $order->metodo_pagamento === 'contrassegno') {
            try {
                \Illuminate\Support\Facades\Mail::to($customer->email)
                    ->bcc(config('mail.from.address')) // Manda copia anche all'admin
                    ->send(new \App\Mail\OrderConfirmed($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Errore invio email ordine (' . $order->metodo_pagamento . '): ' . $e->getMessage());
            }
        }

        if ($order->metodo_pagamento === 'stripe') {
            try {
                $stripeSecret = \App\Models\Setting::where('key', 'stripe_secret')->value('value');
                \Stripe\Stripe::setApiKey($stripeSecret);

                $line_items = [];
                foreach ($cart as $item) {
                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $item['prodotto_nome'] . ($item['taglia'] ? ' - ' . $item['taglia'] : '') . ($item['colore'] ? ' - ' . $item['colore'] : ''),
                            ],
                            'unit_amount' => round($item['prezzo'] * 100),
                        ],
                        'quantity' => $item['quantita'],
                    ];
                }
                
                if ($costoSpedizione > 0) {
                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Spedizione',
                            ],
                            'unit_amount' => round($costoSpedizione * 100),
                        ],
                        'quantity' => 1,
                    ];
                }

                $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => $line_items,
                    'mode' => 'payment',
                    'success_url' => route('public.shop.cart.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('public.shop.cart.stripe.cancel') . '?order_id=' . $order->id,
                    'client_reference_id' => $order->id,
                    'customer_email' => $customer->email,
                ]);

                return redirect($checkout_session->url);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Stripe session creation error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Errore durante la connessione a Stripe. Riprova più tardi. Dettaglio: ' . $e->getMessage());
            }
        }

        if ($order->metodo_pagamento === 'paypal') {
            try {
                $clientId = \App\Models\Setting::where('key', 'paypal_client_id')->value('value');
                $secret = \App\Models\Setting::where('key', 'paypal_secret')->value('value');
                $mode = \App\Models\Setting::where('key', 'paypal_mode')->value('value') ?: 'sandbox';
                $baseUrl = ($mode === 'live') ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

                // 1. Get Access Token
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v1/oauth2/token');
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
                $tokenResult = curl_exec($ch);
                $tokenJson = json_decode($tokenResult);
                
                if (!$tokenJson || !isset($tokenJson->access_token)) {
                    \Illuminate\Support\Facades\Log::error('PayPal Auth Error: ' . $tokenResult);
                    throw new \Exception('Impossibile autenticarsi con PayPal. Controlla le credenziali.');
                }
                
                $accessToken = $tokenJson->access_token;
                curl_close($ch);

                // 2. Create Order
                $orderData = [
                    "intent" => "CAPTURE",
                    "purchase_units" => [[
                        "reference_id" => (string)$order->id,
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($order->totale_ordine, 2, '.', '')
                        ]
                    ]],
                    "application_context" => [
                        "return_url" => route('public.shop.cart.paypal.success'),
                        "cancel_url" => route('public.shop.cart.paypal.cancel') . '?order_id=' . $order->id
                    ]
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v2/checkout/orders');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $accessToken
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
                $orderResult = curl_exec($ch);
                $paypalOrder = json_decode($orderResult);
                
                if (!$paypalOrder || !isset($paypalOrder->id)) {
                    \Illuminate\Support\Facades\Log::error('PayPal Order Error: ' . $orderResult);
                    throw new \Exception('Impossibile creare l\'ordine su PayPal.');
                }
                
                curl_close($ch);

                if (isset($paypalOrder->links)) {
                    foreach ($paypalOrder->links as $link) {
                        if ($link->rel === 'approve') {
                            return redirect($link->href);
                        }
                    }
                }

                throw new \Exception('PayPal approve link not found.');

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('PayPal order creation error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Errore durante la connessione a PayPal. Riprova più tardi. Dettaglio: ' . $e->getMessage());
            }
        }

        // 8. Clear Cart & Redirect to success
        session()->forget('cart');

        return redirect()->route('public.shop.cart.success')->with('order_number', $order->numero_ordine);
    }

    public function success()
    {
        $order_number = session('order_number');
        if (!$order_number) {
            return redirect()->route('public.home');
        }
        return view('public.shop.checkout_success', compact('order_number'));
    }

    public function stripeSuccess(Request $request)
    {
        $session_id = $request->get('session_id');
        if (!$session_id) {
            return redirect()->route('public.home');
        }

        try {
            $stripeSecret = \App\Models\Setting::where('key', 'stripe_secret')->value('value');
            \Stripe\Stripe::setApiKey($stripeSecret);
            $session = \Stripe\Checkout\Session::retrieve($session_id);

            if ($session->payment_status === 'paid') {
                $order = \App\Models\ShopOrder::find($session->client_reference_id);
                if ($order && $order->stato_pagamento !== 'pagato') {
                    $order->load('customer');
                    $order->stato_pagamento = 'pagato';
                    $order->save();
                    
                    try {
                        \Illuminate\Support\Facades\Mail::to($order->customer->email)
                            ->bcc(config('mail.from.address'))
                            ->send(new \App\Mail\OrderConfirmed($order));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Errore invio email ordine (stripe): ' . $e->getMessage());
                    }

                    session()->forget('cart');
                    return redirect()->route('public.shop.cart.success')->with('order_number', $order->numero_ordine);
                } else if ($order && $order->stato_pagamento === 'pagato') {
                    // Già pagato (refresh pagina)
                    session()->forget('cart');
                    return redirect()->route('public.shop.cart.success')->with('order_number', $order->numero_ordine);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Stripe check error: ' . $e->getMessage());
        }

        return redirect()->route('public.shop.index')->with('error', 'Si è verificato un errore durante la verifica del pagamento.');
    }

    public function stripeCancel(Request $request)
    {
        $order_id = $request->get('order_id');
        if ($order_id) {
            $order = \App\Models\ShopOrder::find($order_id);
            if ($order && $order->stato_pagamento === 'attesa') {
                $order->stato = 'annullato'; // o mark as unpaid/cart
                $order->save();
                
                // Ripristino magazzino (optional)
                foreach($order->items as $item) {
                     if($item->shop_variant_id) {
                         $variant = \App\Models\ShopVariant::find($item->shop_variant_id);
                         if($variant) {
                             $variant->quantita += $item->quantita;
                             $variant->save();
                         }
                     }
                }
            }
        }
        
        return redirect()->route('public.shop.cart.checkout')->with('error', 'Hai annullato il pagamento. L\'ordine non è stato completato.');
    }

    public function paypalSuccess(Request $request)
    {
        $paypalOrderId = $request->get('token');
        if (!$paypalOrderId) {
            return redirect()->route('public.home');
        }

        try {
            $clientId = \App\Models\Setting::where('key', 'paypal_client_id')->value('value');
            $secret = \App\Models\Setting::where('key', 'paypal_secret')->value('value');
            $mode = \App\Models\Setting::where('key', 'paypal_mode')->value('value') ?: 'sandbox';
            $baseUrl = ($mode === 'live') ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

            // 1. Get Access Token Again
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v1/oauth2/token');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $accessToken = json_decode($result)->access_token;
            curl_close($ch);

            // 2. Capture Order
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v2/checkout/orders/' . $paypalOrderId . '/capture');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ]);
            $result = curl_exec($ch);
            $capture = json_decode($result);
            curl_close($ch);

            if (isset($capture->status) && $capture->status === 'COMPLETED') {
                $dbOrderId = $capture->purchase_units[0]->reference_id;
                $order = \App\Models\ShopOrder::find($dbOrderId);
                
                if ($order && $order->stato_pagamento !== 'pagato') {
                    $order->load('customer');
                    $order->stato_pagamento = 'pagato';
                    $order->save();
                    
                    try {
                        \Illuminate\Support\Facades\Mail::to($order->customer->email)
                            ->bcc(config('mail.from.address'))
                            ->send(new \App\Mail\OrderConfirmed($order));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Errore invio email ordine (paypal): ' . $e->getMessage());
                    }

                    session()->forget('cart');
                    return redirect()->route('public.shop.cart.success')->with('order_number', $order->numero_ordine);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PayPal capture error: ' . $e->getMessage());
        }

        return redirect()->route('public.shop.index')->with('error', 'Si è verificato un errore durante la cattura del pagamento PayPal.');
    }

    public function paypalCancel(Request $request)
    {
        return $this->stripeCancel($request); // Reuse same logic for stock restoration and redirect
    }
}
