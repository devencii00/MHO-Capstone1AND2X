<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;
use App\Models\Appointment;
use App\Models\Notification;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('appointments:send-doctor-reminders', function () {
    $windowStart = Carbon::now()->startOfMinute();
    $windowEnd = $windowStart->copy()->addMinute();

    $appointments = Appointment::query()
        ->with('patient')
        ->where('appointment_type', 'scheduled')
        ->where('status', 'confirmed')
        ->whereNotNull('appointment_datetime')
        ->where('appointment_datetime', '>=', $windowStart)
        ->where('appointment_datetime', '<', $windowEnd)
        ->get();

    $sent = 0;

    foreach ($appointments as $appointment) {
        $doctorId = (int) ($appointment->doctor_id ?? 0);
        if ($doctorId < 1) {
            continue;
        }

        $scheduledAt = $appointment->appointment_datetime instanceof Carbon
            ? $appointment->appointment_datetime->copy()
            : Carbon::parse((string) $appointment->appointment_datetime);

        $cacheKey = 'appointment-reminder:'.$appointment->appointment_id.':'.$scheduledAt->format('YmdHi');
        if (! Cache::store('file')->add($cacheKey, true, now()->addHours(6))) {
            continue;
        }

        $patientName = trim(implode(' ', array_filter([
            $appointment->patient?->firstname,
            $appointment->patient?->middlename,
            $appointment->patient?->lastname,
        ])));
        if ($patientName === '') {
            $patientName = 'Patient #'.((int) ($appointment->patient_id ?? 0));
        }

        Notification::notifyUsers(
            [$doctorId],
            '[Appointment Reminder] You have an appointment with '.$patientName.' at '.$scheduledAt->format('g:i A').'.',
            'appointment',
            'Appointment Reminder',
            $appointment->appointment_id,
            'appointments'
        );

        $sent++;
    }

    $this->info("Sent {$sent} doctor appointment reminder(s).");
})->purpose('Notify doctors when a scheduled appointment time starts');

Schedule::command('appointments:auto-no-show')->everyMinute();
Schedule::command('appointments:send-doctor-reminders')->everyMinute();
