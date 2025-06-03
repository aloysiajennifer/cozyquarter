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
                'stock' => 15
            ],
            [
                'name' => 'Matcha',
                'price' => 32000,
                'image' => 'images/beverages/Matcha.png',
                'stock' => 10
            ],
            [
                'name' => 'Strawberry Milkshake',
                'price' => 35000,
                'image' => 'images/beverages/StrawberryMilkshake.png',
                'stock' => 0
            ],
            [
                'name' => 'Orange Juice',
                'price' => 16000,
                'image' => 'images/beverages/OrangeJuice.png',
                'stock' => 0
            ],
            [
                'name' => 'Boba',
                'price' => 30000,
                'image' => 'images/beverages/Boba.png',
                'stock' => 8
            ],
            [
                'name' => 'Hot Chocolate',
                'price' => 22000,
                'image' => 'images/beverages/HotChoco.png',
                'stock' => 12
            ],
        ];

        foreach ($data as $item) {
            $beverage = new Beverages();
            $beverage->name = $item['name'];
            $beverage->price = $item['price'];
            $beverage->image = $item['image'];
            $beverage->stock = $item['stock'];
            $beverage->save();
        }
    }
}
