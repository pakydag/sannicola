<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::where('is_active', true)->get();
        $selected_department_id = $request->get('department_id');

        return view('admin.appointments.index', compact('departments', 'selected_department_id'));
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $department_id = $request->get('department_id');

        $query = Appointment::with(['contact', 'department'])
            ->whereBetween('start_time', [$start, $end]);

        if ($department_id) {
            $query->where('department_id', $department_id);
        }

        $appointments = $query->get();

        $events = $appointments->map(function ($appointment) {
            $clientLabel = $appointment->contact ? $appointment->contact->name : 'Anonimo';
            if ($appointment->contact && $appointment->contact->company_name) {
                $clientLabel .= " ({$appointment->contact->company_name})";
            }

            return [
                'id' => $appointment->id,
                'title' => ($appointment->department->name ?? 'Reparto') . ': ' . $clientLabel,
                'start' => $appointment->start_time->toIso8601String(),
                'end' => $appointment->end_time->toIso8601String(),
                'description' => $appointment->description,
                'backgroundColor' => $appointment->status === 'cancelled' ? '#ef4444' : '#4f46e5',
                'borderColor' => $appointment->status === 'cancelled' ? '#ef4444' : '#4f46e5',
                'extendedProps' => [
                    'status' => $appointment->status,
                    'contact' => $clientLabel,
                    'phone' => $appointment->contact->phone ?? ($appointment->contact->mobile ?? 'N/A'),
                    'department' => $appointment->department->name ?? 'N/A',
                    'cost' => $appointment->cost ? '$' . number_format($appointment->cost, 2) : 'N/A',
                    'duration' => $appointment->duration ? floor($appointment->duration / 60) . ':' . str_pad($appointment->duration % 60, 2, '0', STR_PAD_LEFT) : 'N/A',
                    'recording_url' => $appointment->recording_url,
                ]
            ];
        });

        return response()->json($events);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->back()->with('success', 'Appuntamento rimosso con successo.');
    }
    
    public function cancel(Appointment $appointment)
    {
        $appointment->status = 'cancelled';
        $appointment->save();
        return redirect()->back()->with('success', 'Appuntamento annullato.');
    }
}
