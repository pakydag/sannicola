<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = \App\Models\Booking::with(['customer', 'structure'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.booking.index', compact('bookings'));
    }

    public function calendar()
    {
        $bookings = \App\Models\Booking::with('structure')->get();
        // Format for FullCalendar
        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->structure->nome . ' - ' . ($booking->customer->nome ?? 'Cliente'),
                'start' => $booking->start_date,
                'end' => date('Y-m-d', strtotime($booking->end_date . ' +1 day')), // FullCalendar end date is exclusive
                'color' => $this->getStatusColor($booking->stato),
                'url' => route('admin.booking.bookings.show', $booking->id),
            ];
        });

        return view('admin.booking.calendar', compact('events'));
    }

    public function show(\App\Models\Booking $booking)
    {
        $booking->load(['customer', 'structure']);
        return view('admin.booking.show', compact('booking'));
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            'confermato' => '#10b981', // green
            'annullato' => '#ef4444', // red
            default => '#f59e0b', // amber/pending
        };
    }
}
