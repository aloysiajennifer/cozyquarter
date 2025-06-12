<?php

namespace Database\Seeders;

use App\Models\Cwspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CwspaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dummy data for cwspace
        $items= [
            ['code_cwspace' => 'A',
            'capacity_cwspace' => 3,
            'status_cwspace' => 1],

            ['code_cwspace' => 'B',
            'capacity_cwspace' => 5,
            'status_cwspace' => 0],

            ['code_cwspace' => 'C',
            'capacity_cwspace' => 10,
            'status_cwspace' => 1],
        ];

        foreach($items as $item){
            Cwspace::create($item);
        }

    }
}
