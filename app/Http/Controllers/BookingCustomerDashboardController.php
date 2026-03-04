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
        return view('public.booking.dashboard.index', compact('customer', 'bookings'));
    }

    public function profile()
    {
        $customer = Auth::guard('booking_customer')->user();
        return view('public.booking.dashboard.profile', compact('customer'));
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

    public function logout(Request $request)
    {
        Auth::guard('booking_customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('public.home');
    }
}
