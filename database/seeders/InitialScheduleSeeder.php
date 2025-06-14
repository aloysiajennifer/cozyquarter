<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Time;
use App\Models\Schedule;
use Carbon\Carbon;

class InitialScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure Times are seeded first (this seeder relies on Time data)
        $this->call(TimeSeeder::class);
        // Assuming Cwspace data already exists or is seeded by your friend's code
        // If not, you might need to add $this->call(CwspaceSeeder::class); here.

        $cwspaces = Cwspace::all();
        $times = Time::all(); // Fetch Time records which now have start_time and end_time

        if ($cwspaces->isEmpty() || $times->isEmpty()) {
            $this->command->info('No Cwspaces or Times found. Please ensure they are seeded.');
            return;
        }

        // Seed schedules for the next 7 weekdays
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i)->toDateString();

            // Skip weekends (Sunday=0, Saturday=6)
            if (Carbon::parse($date)->isWeekend()) {
                continue;
            }

            // Get or create the OperationalDay for this date
            // This is important because Schedule entries link to OperationalDay IDs.
            $operationalDay = OperationalDay::firstOrCreate(['date' => $date]);

            foreach ($cwspaces as $cwspace) {
                foreach ($times as $time) {
                    // Create an available schedule entry for each room, day, and time slot.
                    // 'firstOrCreate' prevents duplicate entries if you run the seeder multiple times.
                    Schedule::firstOrCreate(
                        [
                            'id_operational_day' => $operationalDay->id,
                            'id_time' => $time->id, // Use the ID of the Time record
                            'id_cwspace' => $cwspace->id,
                        ],
                        [
                            'status_schedule' => 2, // 2 = Available (as per your ScheduleController's update method)
                            'id_reservation' => null, // No reservation yet
                        ]
                    );
                }
            }
        }

        $this->command->info('Initial schedules seeded successfully for the next 7 weekdays.');
    }
}