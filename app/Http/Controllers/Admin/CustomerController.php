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
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
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
}
