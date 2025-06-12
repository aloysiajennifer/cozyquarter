<?php

namespace App\Console\Commands;

use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Schedule;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateSchedule extends Command
{
    protected $signature = 'schedule:generate'; // Nama signature diubah agar lebih jelas
    protected $description = 'Generates a rolling 14-day schedule and cleans up old entries.';

    public function handle()
    {
        $this->info('Starting smart schedule generation...');

        $now = Carbon::now()->startOfDay();
        $times = Time::all();
        $cwspaces = Cwspace::where('status_cwspace', 1)->get();

        // --- LANGKAH 1: Membersihkan jadwal lama yang tidak terpakai ---
        $this->info('-> Cleaning up old, unreserved schedules...');
        $yesterday = Carbon::yesterday()->toDateString();
        $oldDayIds = OperationalDay::where('date', '<', $yesterday)->pluck('id');
        $deletedCount = Schedule::whereIn('id_operational_day', $oldDayIds)
                                ->where('status_schedule', '!=', 2)
                                ->delete();
        $this->info("   - Cleanup complete. Deleted {$deletedCount} old schedule entries.");

        // --- LANGKAH 2: Membuat dan memvalidasi jadwal untuk 15 hari ke depan ---
        $this->info('-> Generating and validating schedules for the next 15 days...');
        $startDate = $now->copy();
        $endDate = $now->copy()->addDays(14); // Menyiapkan jadwal untuk 15 hari (hari ini + 14 hari ke depan)

        // Loop cerdas dari hari ini sampai 14 hari ke depan
        for ($currentDate = $startDate; $currentDate->lte($endDate); $currentDate->addDay()) {
            
            // KUNCI UTAMA: Cari atau BUAT OperationalDay. Menggantikan command terpisah Anda.
            $day = OperationalDay::firstOrCreate(['date' => $currentDate->toDateString()]);

            $dayOfWeek = $currentDate->dayOfWeek;
            $this->line("   - Processing date: {$day->date}");

            foreach ($times as $time) {
                foreach ($cwspaces as $space) {
                    
                    // Menentukan status default (available/closed)
                    $defaultStatusForDayTime = 0; // Default closed
                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) { // Senin - Jumat
                        $defaultStatusForDayTime = 1;
                    } elseif ($dayOfWeek === 6) { // Sabtu
                        if ($time->start_time >= '08:00:00' && $time->end_time <= '14:00:00') {
                            $defaultStatusForDayTime = 1;
                        }
                    }

                    // Cari jadwal yang sudah ada
                    $existingSchedule = Schedule::where([
                        'id_operational_day' => $day->id, 'id_time' => $time->id, 'id_cwspace' => $space->id,
                    ])->first();

                    if (!$existingSchedule) {
                        // Jika tidak ada, buat baru
                        Schedule::create([
                            'id_operational_day' => $day->id, 'id_time' => $time->id, 'id_cwspace' => $space->id,
                            'status_schedule' => $defaultStatusForDayTime,
                        ]);
                    } else {
                        // Jika ada, terapkan logika perlindungan reservasi
                        $safeLimitDate = Carbon::now()->addDays(14); 
                        if ($existingSchedule->status_schedule == 2 && $currentDate->isFuture() && $currentDate->lte($safeLimitDate)) {
                            // Ini adalah reservasi aktif, jangan diubah
                            continue;
                        }

                        // Jika statusnya tidak cocok dengan default (termasuk reservasi lama), perbarui
                        if ($existingSchedule->status_schedule != $defaultStatusForDayTime) {
                            $existingSchedule->status_schedule = $defaultStatusForDayTime;
                            $existingSchedule->id_reservation = null;
                            $existingSchedule->save();
                        }
                    }
                }
            }
        }

        $this->info('Schedule generation complete.');
        return 0;
    }
}