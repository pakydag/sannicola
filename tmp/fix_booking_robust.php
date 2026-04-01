<?php
$file = 'c:\xampp\htdocs\baseweb\app\Http\Controllers\Api\VapiWebhookController.php';
$content = file_get_contents($file);

// Improved checkAvailabilityTool logic
$newCheckAvailability = <<<'PHP'
    private function checkAvailabilityTool($toolCall, $args)
    {
        $deptName = $args['department_name'] ?? '';
        $date = $args['date'] ?? now()->toDateString();
        
        $department = Department::where('is_active', true)
            ->whereRaw('LOWER(name) = ?', [strtolower(trim($deptName))])
            ->first();

        if (!$department || !$department->working_hours) {
            return [
                'toolCallId' => $toolCall['id'] ?? 'default',
                'result'     => "Spiacente, non ho informazioni sugli orari per il reparto $deptName."
            ];
        }

        $hours = $department->working_hours;
        $dayOfWeek = date('w', strtotime($date));
        $isWorkDay = false;
        $allowedDays = $hours['days'] ?? [];
        if (in_array('1', $allowedDays) && $dayOfWeek >= 1 && $dayOfWeek <= 5) $isWorkDay = true;
        if (in_array('6', $allowedDays) && $dayOfWeek == 6) $isWorkDay = true;

        if (!$isWorkDay) {
            return [
                'toolCallId' => $toolCall['id'] ?? 'default',
                'result'     => "Il reparto $deptName è chiuso il giorno selezionato ($date)."
            ];
        }

        $startStr = $hours['start'] ?? '09:00';
        $endStr = $hours['end'] ?? '18:00';
        $duration = $department->appointment_duration ?? 30;

        $existingAppointments = \App\Models\Appointment::where('department_id', $department->id)
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $availableSlots = [];
        $startTime = \Carbon\Carbon::parse("$date $startStr");
        $endTime = \Carbon\Carbon::parse("$date $endStr");
        $now = now();

        $current = $startTime->copy();
        while ($current->copy()->addMinutes($duration)->lte($endTime)) {
            $slotStart = $current->copy();
            $slotEnd = $current->copy()->addMinutes($duration);

            $isOccupied = $existingAppointments->contains(function($app) use ($slotStart, $slotEnd) {
                // Check for overlap: (StartA < EndB) and (EndA > StartB)
                return $slotStart->lt($app->end_time) && $slotEnd->gt($app->start_time);
            });

            if (!$isOccupied && $slotStart->gt($now->subMinutes(5))) {
                $availableSlots[] = $current->format('H:i');
            }
            $current->addMinutes($duration);
        }

        if (empty($availableSlots)) {
            return [
                'toolCallId' => $toolCall['id'] ?? 'default',
                'result'     => "Non ci sono orari disponibili per $deptName il giorno $date."
            ];
        }

        return [
            'toolCallId' => $toolCall['id'] ?? 'default',
            'result'     => [
                'date' => $date,
                'available_slots' => $availableSlots,
                'message' => "Ecco gli orari disponibili per il reparto $deptName: " . implode(', ', $availableSlots)
            ]
        ];
    }
PHP;

// Find and replace the old function with the new one
$pattern = '/private function checkAvailabilityTool.*?\}\n\n/s';
$content = preg_replace($pattern, $newCheckAvailability . "\n\n", $content);

file_put_contents($file, $content);
echo "Robust booking logic implemented.\n";
