<?php

namespace Database\Seeders;

use App\Models\Cwspace;
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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        // Dummy data for time
        Time::insert([
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
            'end_time' => '10:00:00'],
        ]);

        // Dummy data for cwspace
        Cwspace::insert([
            ['code_cwspace' => 'A',
            'capacity_cwspace' => 3,
            'status_cwspace' => 0],

            ['code_cwspace' => 'B',
            'capacity_cwspace' => 5,
            'status_cwspace' => 0],

            ['code_cwspace' => 'C',
            'capacity_cwspace' => 10,
            'status_cwspace' => 0],
        ]);

    }
}
