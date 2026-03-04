<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingCustomer;

class BookingCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingCustomer::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('cognome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20);
        return view('admin.booking.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = BookingCustomer::with(['bookings.structure'])->findOrFail($id);
        return view('admin.booking.customers.show', compact('customer'));
    }
}
