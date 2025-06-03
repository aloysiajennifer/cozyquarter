<?php

namespace Database\Seeders;

use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Schedule;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua data cwspace & time
        $cwspaces =  Cwspace::where('status_cwspace', 0)->get(); // Ambil cwspace yg open aja
        $times = Time::all();
        $operationalDays = OperationalDay::all();


        foreach ($cwspaces as $cwspace) {
            foreach ($times as $time) {
                foreach ($operationalDays as $operationalDay) {
                    Schedule::create([
                        'id_operational_day' => $operationalDay->id,
                        'id_cwspace' => $cwspace->id,
                        'id_time' => $time->id,
                        'status_schedule' => 1, // 1 = available (belum dipesan)
                    ]);
                }
            }
        }

        $firstSchedule = Schedule::first();
        if ($firstSchedule) {
            $firstSchedule->update([
                'id_reservation' => 1,
                'status_schedule' => 0 //ubah jadi closed
            ]);
        }
    }
}
