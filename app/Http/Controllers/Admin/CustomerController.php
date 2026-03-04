<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Customer::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('cognome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = \App\Models\Customer::with(['bookings.structure', 'orders'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }
}
