<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ContactRequest;

class ContactRequestController extends Controller
{
    public function index()
    {
        $contactRequests = ContactRequest::latest()->paginate(15);
        return view('admin.contatti.index', compact('contactRequests'));
    }

    public function show(ContactRequest $contatti)
    {
        if (!$contatti->letto) {
            $contatti->update(['letto' => true]);
        }
        return view('admin.contatti.show', ['contatto' => $contatti]);
    }

    public function destroy(ContactRequest $contatti)
    {
        $contatti->delete();
        return redirect()->route('admin.contatti.index')->with('success', 'Richiesta eliminata con successo.');
    }
}
