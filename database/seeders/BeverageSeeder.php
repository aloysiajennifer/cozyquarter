<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Beverages; 
class BeverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Iced Coffee', 
                'price' => 20000, 
                'image' => 'images/beverages/Coffee.png', 
                'availability' => 1
            ],
            [
                'name' => 'Matcha', 
                'price' => 32000, 
                'image' => 'images/beverages/Matcha.png', 
                'availability' => 1
            ],
            [
                'name' => 'Strawberry Milkshake', 
                'price' => 35000, 
                'image' => 'images/beverages/StrawberryMilkshake.png', 
                'availability' => 0
            ],
            [
                'name' => 'Orange Juice', 
                'price' => 16000, 
                'image' => 'images/beverages/OrangeJuice.png', 
                'availability' => 0
            ],
            [
                'name' => 'Boba', 
                'price' => 30000, 
                'image' => 'images/beverages/Boba.png', 
                'availability' => 1
            ],
            [
                'name' => 'Hot Chocolate', 
                'price' => 22000, 
                'image' => 'images/beverages/HotChoco.png', 
                'availability' => 1
            ],
        ];

        foreach ($data as $item) {
            $beverage = new Beverages(); 
            $beverage->name = $item['name'];
            $beverage->price = $item['price'];
            $beverage->image = $item['image'];
            $beverage->availability = $item['availability'];
            $beverage->save();
        }
    }
}
