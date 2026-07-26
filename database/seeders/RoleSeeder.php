<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'description' => 'Administrator with full access'],
            ['name' => 'User', 'description' => 'Regular user with limited access'],
            ['name' => 'Guest', 'description' => 'Guest user with minimal access'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
