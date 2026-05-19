<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class TransferRequestController extends Controller
{
    public function index()
    {
        $requests = \App\Models\TransferRequest::latest()->paginate(15);
        return view('admin.transfers.index', compact('requests'));
    }

    public function show(TransferRequest $transfer)
    {
        if (!$transfer->letto) {
            $transfer->update(['letto' => true]);
        }
        $transfer->load('messages');
        return view('admin.transfers.show', ['request' => $transfer]);
    }

    public function reply(Request $request, TransferRequest $transfer)
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

        \App\Models\TransferMessage::create([
            'transfer_request_id' => $transfer->id,
            'sender' => 'admin',
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        // Invia email di notifica al cliente
        try {
            \Illuminate\Support\Facades\Mail::to($transfer->email)->send(
                new \App\Mail\AdminTransferRepliedMail($transfer, $request->message, $attachmentPath, $attachmentName)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send admin reply email to transfer client: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Risposta inviata con successo al cliente.');
    }

    public function destroy(TransferRequest $transfer)
    {
        $transfer->delete();
        return redirect()->route('admin.transfers.index')->with('success', 'Richiesta transfer eliminata con successo.');
    }
}
