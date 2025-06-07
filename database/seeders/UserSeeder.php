<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // name     phone   email	username    password	penalty_counter	
        $data = [
            ['name' => 'Jocelyn Carissa', 'phone' => '081247582912', 'email' => 'jocelyncj@gmail.com', 'username' => 'jocelyncarissaa', 'password' => 'c14230185', 'role_id'=>'2'],
            ['name' => 'Aloysia Jennifer', 'phone' => '081463728109', 'email' => 'aloysiajh@gmail.com', 'username' => '_a.jennifer', 'password' => 'c14230191', 'role_id'=>'2'],
            ['name' => 'Clarisa Estelina', 'phone' => '081375829015', 'email' => 'clarisae@gmail.com', 'username' => 'clarisaestln', 'password' => 'c14230199', 'role_id'=>'2'],
            ['name' => 'Valerie Alexia', 'phone' => '081465829301', 'email' => 'valerieaa@gmail.com', 'username' => 'valexia.jpg', 'password' => 'c14230205', 'role_id'=>'2'],
            ['name' => 'Felicia Audrey', 'phone' => '081463709817', 'email' => 'feliciaa@gmail.com', 'username' => 'feliverie', 'password' => 'c14230207', 'role_id'=>'2'],
            ['name' => 'Fiora Agnesia', 'phone' => '081563892028', 'email' => 'fioraaw@gmail.com', 'username' => 'fioraagnesia', 'password' => 'c14230218', 'role_id'=>'2'],
        ];

        foreach ($data as $item) {
            $user = new User();
            $user->name = $item['name'];
            $user->phone = $item['phone'];
            $user->email = $item['email'];
            $user->username = $item['username'];
            $user->password = $item['password'];
            $user->role_id = $item['role_id'];
            $user->save();
        }
    }
}
