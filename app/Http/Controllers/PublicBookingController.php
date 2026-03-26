<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    public function index()
    {
        $structures = \App\Models\BookingStructure::where('attivo', true)->with('photos')->get();
        return view('public.booking.index', compact('structures'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'adulti' => 'required|integer|min:1',
            'bambini' => 'required|integer|min:0',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $totalGuests = $request->adulti + $request->bambini;

        // 1. Trova tutte le strutture attive con capienza sufficiente
        $structures = \App\Models\BookingStructure::where('attivo', true)
                        ->where('posti_totali', '>=', $totalGuests)
                        ->with('photos', 'prices')
                        ->get();

        $availableStructures = collect();

        // 2. Filtra quelle già prenotate nelle date richieste
        foreach ($structures as $structure) {
            $exists = \App\Models\Booking::where('booking_structure_id', $structure->id)
                ->where('stato', '!=', 'annullato')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })->exists();

            if (!$exists) {
                // Calcolo preventivo veloce (opzionale - usa adulto generico se tipo_prezzo non fisso)
                $priceData = $this->calculateQuickPrice($structure, $startDate, $endDate, $totalGuests);
                $structure->preventivo = $priceData;
                $availableStructures->push($structure);
            }
        }

        return view('public.booking.search_results', [
            'structures' => $availableStructures,
            'searchParams' => $request->all()
        ]);
    }

    private function calculateQuickPrice($structure, $start, $end, $totalGuests)
    {
        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $total = 0;

        $seasonalPrices = $structure->prices;

        for ($date = clone $startDate; $date < $endDate; $date->modify('+1 day')) {
            $currentDateStr = $date->format('Y-m-d');
            $dayPrice = 0;

            if ($structure->tipo_prezzo === 'fisso') {
                $match = $seasonalPrices->where('tipo', 'fisso')
                    ->where('start_date', '<=', $currentDateStr)
                    ->where('end_date', '>=', $currentDateStr)
                    ->first();
                $dayPrice = $match ? $match->prezzo : 0;
            } else {
                // Per un calcolo rapido preventivo generico, prendiamo il prezzo del primo variant "Adulto" o variante base
                $baseVariantMatch = $seasonalPrices->where('tipo', 'per_persona')
                    ->where('start_date', '<=', $currentDateStr)
                    ->where('end_date', '>=', $currentDateStr)
                    ->first();
                // Moltiplichiamo il prezzo trovato (o 0 se non c'è config) per il numero totale degli ospiti
                $vPrice = $baseVariantMatch ? $baseVariantMatch->prezzo : 0; 
                $dayPrice = ($vPrice * $totalGuests);
            }

            $total += $dayPrice;
        }

        return $total;
    }

    public function show($id)
    {
        $structure = \App\Models\BookingStructure::where('attivo', true)->with(['photos', 'variants', 'prices', 'services.category', 'extras'])->findOrFail($id);
        
        // Get existing bookings for the calendar
        $bookings = \App\Models\Booking::where('booking_structure_id', $id)
            ->where('stato', '!=', 'annullato')
            ->get(['start_date', 'end_date']);

        $bookedDates = [];
        foreach($bookings as $b) {
            $period = new \DatePeriod(
                new \DateTime($b->start_date),
                new \DateInterval('P1D'),
                (new \DateTime($b->end_date))->modify('+1 day')
            );
            foreach($period as $date) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }

        return view('public.booking.show', compact('structure', 'bookedDates'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'structure_id' => 'required|exists:booking_structures,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'ospiti' => 'nullable|array',
        ]);

        $exists = \App\Models\Booking::where('booking_structure_id', $request->structure_id)
            ->where('stato', '!=', 'annullato')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })->exists();

        $structure = \App\Models\BookingStructure::with(['prices', 'variants'])->findOrFail($request->structure_id);
    
        // Validate guest capacity
        $totalGuests = array_sum($request->ospiti ?: []);
        if ($totalGuests > $structure->posti_totali) {
            return response()->json([
                'available' => false,
                'error' => "Il numero totale di ospiti ($totalGuests) supera la capacità della struttura ({$structure->posti_totali}).",
                'total_price' => 0,
                'details' => []
            ]);
        }

        $priceData = $this->calculatePrice($structure, $request->start_date, $request->end_date, $request->ospiti ?: [], $request->extras ?: []);

        return response()->json([
            'available' => !$exists,
            'total_price' => number_format($priceData['total'], 2, '.', ''),
            'details' => $priceData['details']
        ]);
    }

    private function calculatePrice($structure, $start, $end, $ospiti, $extraIds = [])
    {
        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $total = 0;
        $details = [];

        $seasonalPrices = $structure->prices;
        
        // Load extras to get their prices
        $selectedExtras = \App\Models\BookingExtra::whereIn('id', $extraIds)->get();
        $extrasDailyCost = $selectedExtras->sum('prezzo');

        for ($date = clone $startDate; $date < $endDate; $date->modify('+1 day')) {
            $currentDateStr = $date->format('Y-m-d');
            $dayPrice = 0;

            if ($structure->tipo_prezzo === 'fisso') {
                $match = $seasonalPrices->where('tipo', 'fisso')
                    ->where('start_date', '<=', $currentDateStr)
                    ->where('end_date', '>=', $currentDateStr)
                    ->first();
                $dayPrice = $match ? $match->prezzo : 0;
            } else {
                // Costo per Persona based on variants
                foreach ($ospiti as $variantId => $count) {
                    if ($count <= 0) continue;
                    
                    $priceMatch = $seasonalPrices->where('booking_variant_id', $variantId)
                        ->where('start_date', '<=', $currentDateStr)
                        ->where('end_date', '>=', $currentDateStr)
                        ->first();
                        
                    $vPrice = $priceMatch ? $priceMatch->prezzo : 0; 
                    $dayPrice += ($vPrice * $count);
                }
            }
            
            // Add Extras daily cost
            $dayPrice += $extrasDailyCost;

            $total += $dayPrice;
            $details[] = ['date' => $currentDateStr, 'price' => $dayPrice];
        }

        return ['total' => $total, 'details' => $details, 'extras' => $selectedExtras];
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'structure_id' => 'required|exists:booking_structures,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'ospiti' => 'nullable|array',
            'extras' => 'nullable|array',
            'totale' => 'required|numeric'
        ]);

        // Secondary availability check
        $exists = \App\Models\Booking::where('booking_structure_id', $request->structure_id)
            ->where('stato', '!=', 'annullato')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Le date selezionate non sono più disponibili.');
        }

        $structure = \App\Models\BookingStructure::findOrFail($request->structure_id);

    // Validate guest capacity
    $totalGuests = array_sum($request->ospiti ?: []);
    if ($totalGuests > $structure->posti_totali) {
        return redirect()->back()->with('error', "Il numero totale di ospiti ($totalGuests) supera la capacità della struttura ({$structure->posti_totali}).");
    }

    // Store reservation data in session for checkout
        session()->put('pending_booking', [
            'booking_structure_id' => $request->structure_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'ospiti_dettaglio' => $request->ospiti,
            'extra_dettaglio' => $request->extras,
            'totale_prezzo' => $request->totale
        ]);

        return redirect()->route('public.booking.checkout');
    }

    public function checkout()
    {
        $pending = session()->get('pending_booking');
        if (!$pending) {
            return redirect()->route('public.booking.index');
        }

        $structure = \App\Models\BookingStructure::with('variants')->findOrFail($pending['booking_structure_id']);
        return view('public.booking.checkout', compact('pending', 'structure'));
    }

    public function processCheckout(Request $request)
    {
        $pending = session()->get('pending_booking');
        if (!$pending) {
            return redirect()->route('public.booking.index');
        }

        $isLoggedIn = \Illuminate\Support\Facades\Auth::guard('booking_customer')->check();
        
        $rules = [
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:50',
            'nazione' => 'required|string|max:100',
            'citta' => 'required|string|max:100',
            'payment_method' => 'required|string'
        ];

        // Se non è loggato e non è un utente esistente (password fittizia), validiamo la password
        if (!$isLoggedIn && $request->password !== 'existing_customer') {
            $rules['password'] = [
                'required', 'string', 'min:8', 'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ];
        }

        $request->validate($rules);

        // 1. Handle Customer in dedicated archive
        if ($isLoggedIn) {
            $customer = \Illuminate\Support\Facades\Auth::guard('booking_customer')->user();
        } else {
            $customer = \App\Models\BookingCustomer::where('email', $request->email)->first();
        }

        $isNewCustomer = false;
        if (!$customer) {
            $customer = new \App\Models\BookingCustomer();
            $customer->email = $request->email;
            $isNewCustomer = true;
        }
        
        $customer->nome = $request->nome;
        $customer->cognome = $request->cognome;
        $customer->telefono = $request->telefono;
        $customer->nazione = $request->nazione;
        $customer->citta = $request->citta;
        
        if ($request->password !== 'existing_customer') {
            $customer->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        $customer->save();

        // Se non era loggato, lo logghiamo ora (opzionale, ma utile per l'esperienza utente)
        if (!$isLoggedIn) {
            \Illuminate\Support\Facades\Auth::guard('booking_customer')->login($customer);
        }

        if ($isNewCustomer) {
            try {
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\WelcomeCustomerMail($customer));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Errore invio email benvenuto: ' . $e->getMessage());
            }
        }

        // 2. Create Booking
        $booking = new \App\Models\Booking();
        $booking->customer_id = $customer->id;
        $booking->booking_structure_id = $pending['booking_structure_id'];
        $booking->start_date = $pending['start_date'];
        $booking->end_date = $pending['end_date'];
        $booking->ospiti_dettaglio = $pending['ospiti_dettaglio'];
        $booking->extra_dettaglio = $pending['extra_dettaglio'] ?? [];
        // Sum total people for legacy columns
        $totalPeople = array_sum($pending['ospiti_dettaglio'] ?: []);
        $booking->adulti = $totalPeople ?: 1; 
        $booking->bambini = 0;
        $booking->totale_prezzo = $pending['totale_prezzo'];
        $booking->stato = 'in_attesa';
        $booking->stato_pagamento = 'non_pagato';
        $booking->metodo_pagamento = $request->payment_method;
        
        // 2b. Sync with Unified CRM Contact
        $contact = \App\Models\Contact::where('email', $customer->email)->first();
        if (!$contact) {
            $contact = new \App\Models\Contact();
            $contact->email = $customer->email;
            $contact->password = $customer->password;
        }
        $contact->first_name = $customer->nome;
        $contact->last_name = $customer->cognome;
        $contact->phone = $customer->telefono;
        $contact->country = $customer->nazione;
        $contact->city = $customer->citta;
        $contact->is_booking_customer = true;
        // Tags are synced automatically by the model's booted event
        $contact->save();
        
        $booking->contact_id = $contact->id;
        $booking->save();

        // Send email based on payment method
        if ($booking->metodo_pagamento === 'bonifico') {
            $this->sendBookingConfirmation($booking, true);
        }

        // 3. Handle Payment
        if ($booking->metodo_pagamento === 'stripe') {
            return $this->handleStripe($booking);
        } elseif ($booking->metodo_pagamento === 'paypal') {
            return $this->handlePaypal($booking);
        } else {
            // Bonifico / Contrassegno (manual)
            session()->forget('pending_booking');
            return redirect()->route('public.booking.success', ['id' => $booking->id]);
        }
    }

    private function sendBookingConfirmation($booking, $isPending = false)
    {
        try {
            \Illuminate\Support\Facades\Mail::to($booking->customer->email)->send(new \App\Mail\BookingConfirmationMail($booking, $isPending));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Errore invio conferma prenotazione: ' . $e->getMessage());
        }
    }

    private function handleStripe($booking)
    {
        $stripeSecret = \App\Models\Setting::where('key', 'stripe_secret')->value('value');
        \Stripe\Stripe::setApiKey($stripeSecret);

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Prenotazione: ' . $booking->structure->nome,
                        'description' => 'Dal ' . $booking->start_date . ' al ' . $booking->end_date,
                    ],
                    'unit_amount' => round($booking->totale_prezzo * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('public.booking.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('public.booking.checkout') . '?error=payment_cancelled',
            'client_reference_id' => $booking->id,
            'customer_email' => $booking->customer->email,
        ]);

        return redirect($checkout_session->url);
    }

    private function handlePaypal($booking)
    {
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
        $accessToken = $tokenJson->access_token;
        curl_close($ch);

        // 2. Create Order
        $orderData = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => (string)$booking->id,
                "amount" => [
                    "currency_code" => "EUR",
                    "value" => number_format($booking->totale_prezzo, 2, '.', '')
                ],
                "description" => "Prenotazione: " . $booking->structure->nome
            ]],
            "application_context" => [
                "return_url" => route('public.booking.paypal.success'),
                "cancel_url" => route('public.booking.checkout') . '?error=payment_cancelled'
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
        curl_close($ch);

        if (isset($paypalOrder->links)) {
            foreach ($paypalOrder->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect($link->href);
                }
            }
        }

        return redirect()->route('public.booking.checkout')->with('error', 'Errore durante la creazione dell\'ordine PayPal.');
    }

    public function stripeSuccess(Request $request)
    {
        $session_id = $request->get('session_id');
        $stripeSecret = \App\Models\Setting::where('key', 'stripe_secret')->value('value');
        \Stripe\Stripe::setApiKey($stripeSecret);
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        if ($session->payment_status === 'paid') {
            $booking = \App\Models\Booking::find($session->client_reference_id);
            if ($booking) {
                \Illuminate\Support\Facades\Log::info('Stripe Success: Marking booking #' . $booking->id . ' as paid.');
                $booking->stato_pagamento = 'pagato';
                $booking->stato = 'confermato';
                $booking->save();
                
                $this->sendBookingConfirmation($booking, false);
                session()->forget('pending_booking');
                return redirect()->route('public.booking.success', ['id' => $booking->id]);
            }
        }
        return redirect()->route('public.booking.index')->with('error', 'Il pagamento non è andato a buon fine.');
    }

    public function paypalSuccess(Request $request)
    {
        $paypalOrderId = $request->get('token');
        $clientId = \App\Models\Setting::where('key', 'paypal_client_id')->value('value');
        $secret = \App\Models\Setting::where('key', 'paypal_secret')->value('value');
        $mode = \App\Models\Setting::where('key', 'paypal_mode')->value('value') ?: 'sandbox';
        $baseUrl = ($mode === 'live') ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

        // 1. Get Access Token
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
            $bookingId = $capture->purchase_units[0]->reference_id;
            $booking = \App\Models\Booking::find($bookingId);
            if ($booking) {
                $booking->stato_pagamento = 'pagato';
                $booking->stato = 'confermato';
                $booking->save();
                $this->sendBookingConfirmation($booking, false);
                session()->forget('pending_booking');
                return redirect()->route('public.booking.success', ['id' => $booking->id]);
            }
        }
        return redirect()->route('public.booking.index')->with('error', 'Pagamento PayPal fallito.');
    }

    public function success($id)
    {
        $booking = \App\Models\Booking::with('structure')->findOrFail($id);
        return view('public.booking.success', compact('booking'));
    }

    public function loginCheckout(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (\Illuminate\Support\Facades\Auth::guard('booking_customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Credenziali non valide.'], 401);
    }
}
