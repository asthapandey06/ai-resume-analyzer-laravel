<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create an admin user with the role, email of defined in environment variable/config ADMIN_ROLE_NAME
        $adminRoleName = config('app.admin_role_name', 'Admin');
       //pick role from db defined already in Table
        $adminRole = \App\Models\Role::where('name', $adminRoleName)->first();

        if (!$adminRole) {
            $this->command->error("Admin role '{$adminRoleName}' not found. Please run the RoleSeeder first.");
            return;
        }

        \App\Models\User::create([
            'name' => config('app.admin_seeder.name', 'Admin User'),
            'email' => config('app.admin_seeder.email', 'admin@example.com'),
            'password' => bcrypt(config('app.admin_seeder.password', 'password')),
            'role_id' => $adminRole->id,
        ]);
    }
}
