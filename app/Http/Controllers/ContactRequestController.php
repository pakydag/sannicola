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
        $contatti->load('messages');
        return view('admin.contatti.show', ['contatto' => $contatti]);
    }

    public function reply(Request $request, ContactRequest $contatto)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB limit
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            $attachmentName = $request->file('attachment')->getClientOriginalName();
        }

        \App\Models\ContactMessage::create([
            'contact_request_id' => $contatto->id,
            'sender' => 'admin',
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        // Invia email di notifica al cliente
        try {
            \Illuminate\Support\Facades\Mail::to($contatto->email)->send(
                new \App\Mail\AdminRepliedMail($contatto, $request->message, $attachmentPath, $attachmentName)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send admin reply email to client: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Risposta inviata con successo al cliente.');
    }

    public function destroy(ContactRequest $contatti)
    {
        $contatti->delete();
        return redirect()->route('admin.contatti.index')->with('success', 'Richiesta eliminata con successo.');
    }
}
