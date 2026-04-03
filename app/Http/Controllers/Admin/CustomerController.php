<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email'        => 'nullable|email|unique:contacts,email',
            'phone'        => 'nullable|string|max:20',
            'mobile'       => 'nullable|string|max:20',
        ]);

        $customer = Contact::create(array_merge($validated, [
            'is_active' => true,
        ]));

        return redirect()->route('admin.customers.index')->with('success', 'Contatto creato correttamente.');
    }

    public function show($id)
    {
        $customer = Contact::with(['shopOrders', 'bookings.structure', 'b2bOrders'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function addTag(Request $request, $id)
    {
        $customer = Contact::findOrFail($id);
        
        $request->validate([
            'tag' => 'required|string|max:50',
        ]);

        $tags = $customer->tags ?? [];
        $newTag = trim($request->tag);
        
        if ($newTag && !in_array($newTag, $tags)) {
            $tags[] = $newTag;
            $customer->update(['tags' => array_values($tags)]);
        }

        return back()->with('success', 'Tag aggiunto correttamente.');
    }

    public function removeTag(Request $request, $id)
    {
        $customer = Contact::findOrFail($id);
        
        $request->validate([
            'tag' => 'required|string',
        ]);

        $tags = $customer->tags ?? [];
        $tagToRemove = $request->tag;

        $tags = array_filter($tags, function($t) use ($tagToRemove) {
            return $t !== $tagToRemove;
        });

        $customer->update(['tags' => array_values($tags)]);

        return back()->with('success', 'Tag rimosso.');
    }

    public function toggleFeature(Request $request, $id)
    {
        $customer = Contact::findOrFail($id);
        
        $feature = $request->get('feature');
        $allowedFeatures = ['is_shop_customer', 'is_booking_customer', 'is_b2b_customer', 'is_spoki_customer'];
        
        if (in_array($feature, $allowedFeatures)) {
            $customer->$feature = !$customer->$feature;
            $customer->save();
            
            $status = $customer->$feature ? 'abilitata' : 'disabilitata';
            return back()->with('success', "Funzione " . str_replace('is_', '', $feature) . " $status per il contatto.");
        }

        return back()->with('error', 'Funzione non valida.');
    }
}
