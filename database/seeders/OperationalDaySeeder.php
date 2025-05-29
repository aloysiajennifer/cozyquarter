<?php

namespace Database\Seeders;

use App\Models\OperationalDay;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperationalDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'date' => Carbon::today()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'date' => '2025-02-14',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($items as $item) {
            OperationalDay::create($item);
        }
    }
}
