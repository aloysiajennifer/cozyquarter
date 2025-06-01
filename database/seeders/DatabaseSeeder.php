<?php

namespace Database\Seeders;

use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Reservation;
use App\Models\Time;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        // Jalanin seeder otomatis
         $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            BeverageSeeder::class,
            CategorySeeder::class,
            ShelfSeeder::class,
            BookSeeder::class,
            TimeSeeder::class,
            CwspaceSeeder::class,
            OperationalDaySeeder::class,
            ScheduleSeeder::class,
            ReservationSeeder::class,
            OrderSeeder::class,
        ]);

    }
}
