<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all user data, including penalty_counter and is_blacklisted (if it existed, it's now removed by migration)
        $data = [
            ['name' => 'Jocelyn Carissa', 'phone' => '081247582912', 'email' => 'jocelyncj@gmail.com', 'username' => 'jocelyncarissaa', 'password' => 'c14230185', 'role_id'=>'2', 'penalty_counter' => 0],
            ['name' => 'Aloysia Jennifer', 'phone' => '081463728109', 'email' => 'aloysiajh@gmail.com', 'username' => '_a.jennifer', 'password' => 'c14230191', 'role_id'=>'2', 'penalty_counter' => 0],
            ['name' => 'Clarisa Estelina', 'phone' => '081375829015', 'email' => 'clarisae@gmail.com', 'username' => 'clarisaestln', 'password' => 'c14230199', 'role_id'=>'2', 'penalty_counter' => 0],
            ['name' => 'Valerie Alexia', 'phone' => '081465829301', 'email' => 'valerieaa@gmail.com', 'username' => 'valexia.jpg', 'password' => 'c14230205', 'role_id'=>'2', 'penalty_counter' => 0],
            ['name' => 'Felicia Audrey', 'phone' => '081463709817', 'email' => 'feliciaa@gmail.com', 'username' => 'feliverie', 'password' => 'c14230207', 'role_id'=>'2', 'penalty_counter' => 0],
            ['name' => 'Fiora Agnesia', 'phone' => '081563892028', 'email' => 'fioraaw@gmail.com', 'username' => 'fioraagnesia', 'password' => 'c14230218', 'role_id'=>'2', 'penalty_counter' => 0],
            ['name' => 'dummyA', 'phone' => '012345678922', 'email' => 'dummyA@gmail.com', 'username' => 'dummyA', 'password' => '123456789', 'role_id'=>'1'],
            ['name' => 'dummyB', 'phone' => '081493920124', 'email' => 'dummyB@gmail.com', 'username' => 'dummyB', 'password' => '123456789', 'role_id'=>'1'],
            ['name' => 'dummyC', 'phone' => '012345678923', 'email' => 'dummyC@gmail.com', 'username' => 'dummyC', 'password' => '123456789', 'role_id'=>'1'],
            ['name' => 'dummyD', 'phone' => '012345678949', 'email' => 'dummyD@gmail.com', 'username' => 'dummyD', 'password' => '123456789', 'role_id'=>'1'],
            ['name' => 'dummyE', 'phone' => '012345678905', 'email' => 'dummyE@gmail.com', 'username' => 'dummyE', 'password' => '123456789', 'role_id'=>'1'],
            ['name' => 'dummy1', 'phone' => '0123456789', 'email' => 'dummy1@gmail.com', 'username' => 'dummy1', 'password' => '123456789', 'role_id'=>'1', 'penalty_counter' => 0],
            ['name' => 'dummyBlacklist', 'phone' => '0123456789', 'email' => 'dummy2@gmail.com', 'username' => 'dummyB', 'password' => '123456789', 'role_id'=>'1', 'penalty_counter' => 0], 
        ];

        foreach ($data as $item) {
            $user = new User();
            $user->name = $item['name'];
            $user->phone = $item['phone'];
            $user->email = $item['email'];
            $user->username = $item['username'];
            $user->password = Hash::make($item['password']);
            $user->role_id = $item['role_id'];
            $user->save();
        }
    }
}