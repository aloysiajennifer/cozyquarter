<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Time; // Import the Time model

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeSlots = [
            ['start' => '09:00:00', 'end' => '10:00:00'],
            ['start' => '10:00:00', 'end' => '11:00:00'],
            ['start' => '11:00:00', 'end' => '12:00:00'],
            ['start' => '12:00:00', 'end' => '13:00:00'],
            ['start' => '13:00:00', 'end' => '14:00:00'],
            ['start' => '14:00:00', 'end' => '15:00:00'],
            ['start' => '15:00:00', 'end' => '16:00:00'],
            ['start' => '16:00:00', 'end' => '17:00:00'],
            ['start' => '17:00:00', 'end' => '18:00:00'],
            ['start' => '18:00:00', 'end' => '19:00:00'],
            ['start' => '19:00:00', 'end' => '20:00:00'],
        ];

        foreach ($timeSlots as $slot) {
            Time::firstOrCreate(
                ['start_time' => $slot['start']], // Use 'start_time' as unique identifier
                ['end_time' => $slot['end']]
            );
        }
    }
}