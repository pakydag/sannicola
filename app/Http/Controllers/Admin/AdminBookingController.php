<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Booking::with(['customer', 'structure'])
            ->where('end_date', '>=', now()->toDateString());
            
        $this->applyFilters($query, $request);

        $bookings = $query->orderBy('start_date', 'asc')->paginate(20);
        $structures = \App\Models\BookingStructure::all();
        $isArchive = false;
        
        return view('admin.booking.index', compact('bookings', 'structures', 'isArchive'));
    }

    public function archive(Request $request)
    {
        $query = \App\Models\Booking::with(['customer', 'structure'])
            ->where('end_date', '<', now()->toDateString());
            
        $this->applyFilters($query, $request);

        $bookings = $query->orderBy('start_date', 'desc')->paginate(20);
        $structures = \App\Models\BookingStructure::all();
        $isArchive = true;
        
        return view('admin.booking.index', compact('bookings', 'structures', 'isArchive'));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }
        if ($request->filled('structure_id')) {
            $query->where('booking_structure_id', $request->structure_id);
        }
    }

    public function calendar()
    {
        $bookings = \App\Models\Booking::with('structure')->get();
        // Format for FullCalendar
        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->structure->nome . ' - ' . ($booking->customer->nome ?? 'Cliente'),
                'start' => $booking->start_date,
                'end' => date('Y-m-d', strtotime($booking->end_date . ' +1 day')), // FullCalendar end date is exclusive
                'color' => $this->getStatusColor($booking->stato),
                'url' => route('admin.booking.bookings.show', $booking->id),
            ];
        });

        return view('admin.booking.calendar', compact('events'));
    }

    public function show(\App\Models\Booking $booking)
    {
        $booking->load(['customer', 'structure']);
        return view('admin.booking.show', compact('booking'));
    }

    public function block(Request $request)
    {
        $structures = \App\Models\BookingStructure::all();
        $customers = \App\Models\BookingCustomer::all();
        $selected_id = $request->get('structure_id');
        
        // Pre-fetch occupied dates for the selected structure if any
        $occupied_dates = [];
        if ($selected_id) {
            $occupied_dates = \App\Models\Booking::where('booking_structure_id', $selected_id)
                ->where('stato', '!=', 'annullato')
                ->where('end_date', '>=', now())
                ->get(['start_date', 'end_date'])
                ->map(function($b) {
                    return [
                        'start' => $b->start_date,
                        'end' => $b->end_date
                    ];
                });
        }

        return view('admin.booking.block', compact('structures', 'selected_id', 'customers', 'occupied_dates'));
    }

    public function getOccupiedDates($id)
    {
        $dates = \App\Models\Booking::where('booking_structure_id', $id)
            ->where('stato', '!=', 'annullato')
            ->where('end_date', '>=', now()->subMonths(1))
            ->get(['start_date', 'end_date'])
            ->map(function($b) {
                return [
                    'start' => $b->start_date,
                    'end' => $b->end_date
                ];
            });

        return response()->json($dates);
    }

    public function storeBlock(Request $request)
    {
        $request->validate([
            'booking_structure_id' => 'required|exists:booking_structures,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:50',
            'nazione' => 'nullable|string|max:100',
            'citta' => 'nullable|string|max:100',
            'stato_pagamento' => 'required|string|in:pagato,paga_in_struttura,attesa',
            'metodo_attesa' => 'nullable|string|in:stripe,bonifico',
            'note' => 'nullable|string'
        ]);

        // Check if already blocked
        $exists = \App\Models\Booking::where('booking_structure_id', $request->booking_structure_id)
            ->where('stato', '!=', 'annullato')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Alcune date nel periodo selezionato sono già occupate.');
        }

        // 1. Handle Customer
        $customer = \App\Models\Customer::where('email', $request->email)->first();
        $isNewCustomer = false;
        $password = null;
        // 1. Create/Find Customer in independent archive
        $customer = \App\Models\BookingCustomer::where('email', $request->email)->first();
        if (!$customer) {
            $customer = \App\Models\BookingCustomer::create([
                'nome' => $request->nome,
                'cognome' => $request->cognome,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'nazione' => $request->nazione,
                'citta' => $request->citta,
                'password' => \Illuminate\Support\Facades\Hash::make('Password123!'), // Generic, can change via "forgot password"
            ]);

            try {
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\WelcomeCustomerMail($customer));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Admin - Errore invio email benvenuto: ' . $e->getMessage());
            }
        }

        // 2. Calculate Price
        $structure = \App\Models\BookingStructure::findOrFail($request->booking_structure_id);
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);
        if ($days <= 0) $days = 1;
        $totalPrice = $structure->costo_al_giorno * $days;

        // 3. Create Booking
        $booking = new \App\Models\Booking();
        $booking->customer_id = $customer->id;
        $booking->booking_structure_id = $request->booking_structure_id;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->adulti = 0; // Manual block
        $booking->bambini = 0;
        $booking->totale_prezzo = $totalPrice;
        $booking->stato = 'confermato';
        $booking->stato_pagamento = $request->stato_pagamento == 'attesa' ? 'non_pagato' : ($request->stato_pagamento == 'paga_in_struttura' ? 'in_struttura' : 'pagato');
        $booking->metodo_pagamento = $request->stato_pagamento == 'attesa' ? ($request->metodo_attesa ?? 'manuale') : 'manuale';
        $booking->note_admin = $request->note ?? 'Blocco manuale';
        
        $booking->save();

        // 4. Optional Stripe Link (Needs ID for success URL)
        if ($request->stato_pagamento == 'attesa' && $request->metodo_attesa == 'stripe') {
            try {
                $booking->stripe_payment_link = $this->generateStripeLink($booking);
                $booking->save(); // Save again with the link
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Stripe Link Generation Failed: ' . $e->getMessage());
            }
        }

        // Send Confirmation
        try {
            if ($request->stato_pagamento == 'attesa' && $request->metodo_attesa == 'stripe') {
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\BookingStatusMail($booking, 'stripe_link'));
            } elseif ($request->stato_pagamento == 'attesa' && $request->metodo_attesa == 'bonifico') {
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\BookingStatusMail($booking, 'bank_details'));
            } elseif ($request->stato_pagamento == 'paga_in_struttura') {
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\BookingStatusMail($booking, 'paga_in_struttura'));
            } else {
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\BookingConfirmationMail($booking));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin - Errore invio conferma prenotazione: ' . $e->getMessage());
        }

        return redirect()->route('admin.booking.calendar')->with('success', 'Prenotazione manuale registrata con successo.');
    }

    public function markAsPaid(\App\Models\Booking $booking)
    {
        $booking->stato_pagamento = 'pagato';
        $booking->save();

        try {
            \Illuminate\Support\Facades\Mail::to($booking->customer->email)->send(new \App\Mail\BookingStatusMail($booking, 'paid'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin - Errore invio email pagato: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Prenotazione segnata come pagata e cliente notificato.');
    }

    public function cancel(\App\Models\Booking $booking)
    {
        $booking->stato = 'annullato';
        $booking->save();

        try {
            \Illuminate\Support\Facades\Mail::to($booking->customer->email)->send(new \App\Mail\BookingStatusMail($booking, 'cancelled'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin - Errore invio email annullamento: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Prenotazione annullata e cliente notificato.');
    }

    private function generateStripeLink($booking)
    {
        $stripeSecret = \App\Models\Setting::where('key', 'stripe_secret')->value('value');
        if (!$stripeSecret) return null;

        \Stripe\Stripe::setApiKey($stripeSecret);

        // Price might be 0 for manual blocks, but Stripe requires unit_amount > 0. 
        // We'll use a placeholder or the actual structure price if needed.
        // For simplicity, let's assume admin sets a price if they want a link.
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Saldo Prenotazione #' . $booking->id . ': ' . $booking->structure->nome,
                    ],
                    'unit_amount' => round($booking->totale_prezzo * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('public.booking.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('admin.booking.calendar'),
            'client_reference_id' => $booking->id,
            'customer_email' => $booking->customer->email,
        ]);

        return $checkout_session->url;
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'confermato' => '#10b981', // green
            'annullato' => '#ef4444', // red
            default => '#f59e0b', // amber/pending
        };
    }
}
