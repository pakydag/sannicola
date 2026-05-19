<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarRentalRequest;
use Illuminate\Http\Request;

class CarRentalRequestController extends Controller
{
    public function index()
    {
        $requests = \App\Models\CarRentalRequest::latest()->paginate(15);
        return view('admin.car_rentals.index', compact('requests'));
    }

    public function show(\App\Models\CarRentalRequest $car_rental)
    {
        if (!$car_rental->letto) {
            $car_rental->update(['letto' => true]);
        }
        $car_rental->load('messages');
        return view('admin.car_rentals.show', ['request' => $car_rental]);
    }

    public function reply(Request $request, \App\Models\CarRentalRequest $car_rental)
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

        \App\Models\CarRentalMessage::create([
            'car_rental_request_id' => $car_rental->id,
            'sender' => 'admin',
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        // Invia email di notifica al cliente
        try {
            \Illuminate\Support\Facades\Mail::to($car_rental->email)->send(
                new \App\Mail\AdminCarRentalRepliedMail($car_rental, $request->message, $attachmentPath, $attachmentName)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send admin reply email to car rental client: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Risposta inviata con successo al cliente.');
    }

    public function destroy(\App\Models\CarRentalRequest $car_rental)
    {
        $car_rental->delete();
        return redirect()->route('admin.car-rentals.index')->with('success', 'Richiesta noleggio auto eliminata con successo.');
    }
}
