<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shelf;

class ShelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // code_shelf
        $data = [
            ['code_shelf' => '1A'],
            ['code_shelf' => '1B'],
            ['code_shelf' => '1C'],
            ['code_shelf' => '1D'],
            ['code_shelf' => '2A'],
            ['code_shelf' => '2B'],
            ['code_shelf' => '2C'],
            ['code_shelf' => '2D'],
            ['code_shelf' => '3A'],
            ['code_shelf' => '3B'],
        ];

        foreach ($data as $item) {
            $shelf = new Shelf();
            $shelf->code_shelf = $item['code_shelf'];
            $shelf->save();
        }
    }
}
