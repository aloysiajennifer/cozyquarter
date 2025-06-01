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
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate schedule for each operational day';

    /**
     * Execute the console command.
     */
    public function handle() // jalanin pakai -> php artisan generate:schedule

    {
        $days = OperationalDay::all();
        $times = Time::all();
        $cwspaces = Cwspace::all();

        foreach ($days as $day) {
            $carbonDate = Carbon::parse($day->date);
            $dayOfWeek = $carbonDate->dayOfWeek;

            foreach ($times as $time) {
                foreach ($cwspaces as $space) {
                    $status = 0;

                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        // Senin - Jumat: semua jam aktif
                        $status = 1;
                    } elseif ($dayOfWeek === 6) {
                        // Sabtu: hanya jam 08:00–14:00 yang aktif
                        if ($time->start_time >= '08:00:00' && $time->end_time <= '14:00:00') {
                            $status = 1;
                        }
                    }
                    // Minggu: semua status = 0 (closed)

                    // Cek apakah sudah ada schedule untuk kombinasi hari + waktu + space
                    $existing = Schedule::where([
                        'id_operational_day' => $day->id,
                        'id_time' => $time->id,
                        'id_cwspace' => $space->id,
                    ])->first();

                    if (!$existing) {
                        // Jika belum ada, buat baru dengan default status otomatis
                        Schedule::updateOrCreate(
                            [
                                'id_operational_day' => $day->id,
                                'id_time' => $time->id,
                                'id_cwspace' => $space->id,
                            ],
                            [
                                'status_schedule' => $status,
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }
        }
    }
}
