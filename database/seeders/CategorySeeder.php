<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // name_category
         $data = [
            ['name_category' => 'drama'],
            ['name_category' => 'romance'],
            ['name_category' => 'thriller'],
            ['name_category' => 'fantasy'],
            ['name_category' => 'horror'],
            ['name_category' => 'mystery'],
            ['name_category' => 'sci-fi'],
            ['name_category' => 'comic'],
            ['name_category' => 'poetry'],
            ['name_category' => 'academic'],
            ['name_category' => 'biography'],
            ['name_category' => 'motivation'],
            ['name_category' => 'business'],
            ['name_category' => 'historical'],
            ['name_category' => 'religious'],
            ['name_category' => 'hobby & lifestyle'],
            ['name_category' => 'psychology & health'],
            ['name_category' => 'politic / social / law'],
            
        ];

        foreach ($data as $item) {
            $category = new Category();
            $category->name_category = $item['name_category'];
            $category->save();
        }

    }
}
