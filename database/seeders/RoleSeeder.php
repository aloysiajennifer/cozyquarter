<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'type' => 'user',
            ],
            [
                'type' => 'admin',
            ],
        ];

        foreach ($data as $item) {
            $role = new Role();
            $role->type = $item['type'];
            $role->save();
        }
    }
}
