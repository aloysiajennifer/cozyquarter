<?php

namespace Database\Seeders;

use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Reservation;
use App\Models\Role;
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
            BorrowingSeeder::class,
            TimeSeeder::class,
            CwspaceSeeder::class,
            //Cara bikin operational sm schedule otomatis untuk 2 minggu kedepan
            OperationalDaySeeder::class, // hbs jalanin seeder ketik -> php artisan operational:update
            ReservationSeeder::class,
            ScheduleSeeder::class, // hbs jalanin seeder ketik -> php artisan generate:schedule
            OrderSeeder::class,
        ]);
    }
}
