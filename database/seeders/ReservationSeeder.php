<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use Illuminate\Support\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::create([
            'status_reservation' => 0,
            'check_in_time' => Carbon::now()->addDay(), 
            'timestamp_reservation' => Carbon::now(),
            'id_user' => 7, 
        ]);
    }
}
