<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Appointment;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('appointments:auto-no-show', function () {
    $count = Appointment::where('appointment_type', 'scheduled')
        ->where('status', 'confirmed')
        ->where('appointment_datetime', '<', Carbon::now())
        ->update(['status' => 'no_show']);

    $this->info("Auto-marked {$count} past confirmed appointment(s) as no_show.");
})->purpose('Auto-mark past confirmed scheduled appointments as No Show');

Schedule::command('appointments:auto-no-show')->everyMinute();
