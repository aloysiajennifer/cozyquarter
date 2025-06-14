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
use Illuminate\Support\Facades\Artisan;

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
        ]);

        // Artisan::call('operational:update');
        // $this->command->info('Membuat jadwal operasional untuk 2 minggu ke depan...');
        Artisan::call('schedule:generate');
        $this->command->info('Membuat jadwal cwspace untuk 2 minggu ke depan...');

        $this->call([
            ReservationSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
