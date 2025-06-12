<?php

namespace Database\Seeders;

use App\Models\Time;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               // Dummy data for time
        $items = [
            ['start_time' => '08:00:00',
            'end_time' => '09:00:00'],
            
            ['start_time' => '09:00:00',
            'end_time' => '10:00:00'],

            ['start_time' => '10:00:00',
            'end_time' => '11:00:00'],

            ['start_time' => '11:00:00',
            'end_time' => '12:00:00'],

            ['start_time' => '12:00:00',
            'end_time' => '13:00:00'],

            ['start_time' => '13:00:00',
            'end_time' => '14:00:00'],

            ['start_time' => '14:00:00',
            'end_time' => '15:00:00'],

            ['start_time' => '15:00:00',
            'end_time' => '16:00:00'],

            ['start_time' => '16:00:00',
            'end_time' => '17:00:00'],

            ['start_time' => '17:00:00',
            'end_time' => '18:00:00'],

            ['start_time' => '18:00:00',
            'end_time' => '19:00:00'],

            ['start_time' => '19:00:00',
            'end_time' => '20:00:00'],
        ];

        foreach ($items as $item){
            Time::create($item);
        }
    }
}
