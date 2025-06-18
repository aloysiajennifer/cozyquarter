<?php

use App\Models\OperationalDay;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Ini hanya digunakan untuk command artisan custom
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ✅ Penjadwalan harus diletakkan dalam closure yang dieksekusi oleh Laravel
return function (Schedule $schedule) {
    $schedule->command('reservations:update-statuses')->dailyAt('00:00');
};