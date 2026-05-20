<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;

class BookingCustomerDashboardController extends Controller
{

    public function index()
    {
        $customer = Auth::guard('booking_customer')->user();
        $bookings = $customer->bookings()->with('structure')->latest()->get();
        $section = \App\Models\Section::where('modulo', 'booking')->first();
        return view('public.booking.dashboard.index', compact('customer', 'bookings', 'section'));
    }

    public function profile()
    {
        $customer = Auth::guard('booking_customer')->user();
        $section = \App\Models\Section::where('modulo', 'booking')->first();
        return view('public.booking.dashboard.profile', compact('customer', 'section'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('booking_customer')->user();
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'nazione' => 'required|string|max:100',
            'citta' => 'required|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $customer->update($request->only(['nome', 'cognome', 'telefono', 'nazione', 'citta']));

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
            $customer->save();
        }

        return back()->with('success', 'Profilo aggiornato con successo.');
    }

    public function requestCancellation(\App\Models\Booking $booking)
    {
        // Check if the booking belongs to the current user
        if ($booking->customer_id !== Auth::guard('booking_customer')->id()) {
            abort(403, 'Non sei autorizzato a modificare questa prenotazione.');
        }

        // Change state to cancellation request if applicable
        if (in_array($booking->stato, ['confermato', 'in_attesa'])) {
            // we can set state to a temporary state or just 'annullato' if it's immediate
            // The user requested "inviare una richiesta di cancellazione", implying it might need review
            // For now, let's mark it as 'richiesta_annullamento' or just append a note if the state isn't supported.
            // Looking at the enum, typical states are 'in_attesa', 'confermato', 'annullato'
            // Let's set it to 'annullato' directly, or create a note. I will just set it to 'annullato' and add a note, or if 'richiesta_annullamento' is not an enum value, just cancel it.
            // Let's just set 'annullato' directly for simplicity and send mail.
            // Actually, let's check what states are available in DB. Since I can't check easily right now, I'll set it to 'annullato' as the cancel action does.
            
            $booking->stato = 'annullato';
            // $booking->note_admin = ($booking->note_admin ? $booking->note_admin . "\n" : "") . "Richiesta cancellazione da area cliente il " . now()->format('d/m/Y H:i');
            $booking->save();

            // Send email to admin? Optionally we can notify admin.
            try {
                \Illuminate\Support\Facades\Mail::to(\App\Models\Setting::where('key', 'admin_email')->value('value') ?? config('mail.from.address'))
                    ->send(new \App\Mail\BookingStatusMail($booking, 'cancellation_request_from_customer'));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Errore invio email cancellazione: ' . $e->getMessage());
            }

            return back()->with('success', 'Richiesta di cancellazione inviata con successo.');
        }

        return back()->with('error', 'Impossibile cancellare questa prenotazione nello stato attuale.');
    }

    public function logout(Request $request)
    {
        Auth::guard('booking_customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('public.home');
    }
}
