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

            // User dummy
            ['name' => 'User1', 'phone' => '08123456789', 'email' => 'user1@gmail.com', 'username' => 'user1', 'password' => 'user1', 'role_id'=>'1'],
            ['name' => 'User2', 'phone' => '08123456789', 'email' => 'user2@gmail.com', 'username' => 'user2', 'password' => 'user2', 'role_id'=>'1'],
            ['name' => 'User3', 'phone' => '08123456789', 'email' => 'user3@gmail.com', 'username' => 'user3', 'password' => 'user3', 'role_id'=>'1'],
            ['name' => 'User4', 'phone' => '08123456789', 'email' => 'user4@gmail.com', 'username' => 'user4', 'password' => 'user4', 'role_id'=>'1'],
            ['name' => 'User5', 'phone' => '08123456789', 'email' => 'user5@gmail.com', 'username' => 'user5', 'password' => 'user', 'role_id'=>'1'],
            ['name' => 'User6', 'phone' => '08123456789', 'email' => 'user6@gmail.com', 'username' => 'user6', 'password' => 'user', 'role_id'=>'1'],
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
