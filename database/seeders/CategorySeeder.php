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
            ['name_category' => 'Drama'],
            ['name_category' => 'Romance'],
            ['name_category' => 'Thriller'],
            ['name_category' => 'Fantasy'],
            ['name_category' => 'Horror'],
            ['name_category' => 'Mystery'],
            ['name_category' => 'Dci-fi'],
            ['name_category' => 'Comic'],
            ['name_category' => 'Poetry'],
            ['name_category' => 'Academic'],
            ['name_category' => 'Biography'],
            ['name_category' => 'Motivation'],
            ['name_category' => 'Business'],
            ['name_category' => 'Historical'],
            ['name_category' => 'Religious'],
            ['name_category' => 'Hobby & Lifestyle'],
            ['name_category' => 'Psychology & Health'],
            ['name_category' => 'Politic / Social / Law'],
            
        ];

        foreach ($data as $item) {
            $category = new Category();
            $category->name_category = $item['name_category'];
            $category->save();
        }

    }
}
