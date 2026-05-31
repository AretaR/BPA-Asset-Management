<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate([
            'email' => 'superadmin@bpa.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP000',
            'phone' => '+63 900 000 0000',
            'position' => 'Super Administrator',
            'role' => 'super_admin',
        ]);

        $superAdminRole = \App\Models\Role::where('slug', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        $admin = User::updateOrCreate([
            'email' => 'admin@bpa.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP001',
            'phone' => '+63 912 345 6789',
            'position' => 'System Administrator',
            'role' => 'admin',
        ]);

        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        $staffRole = \App\Models\Role::where('slug', 'staff')->first();

        $john = User::updateOrCreate([
            'email' => 'john@bpa.com',
        ], [
            'name' => 'John Smith',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP002',
            'phone' => '+63 923 456 7890',
            'position' => 'IT Manager',
            'role' => 'staff',
        ]);

        if ($staffRole) {
            $john->roles()->syncWithoutDetaching([$staffRole->id]);
        }

        $jane = User::updateOrCreate([
            'email' => 'jane@bpa.com',
        ], [
            'name' => 'Jane Doe',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP003',
            'phone' => '+63 934 567 8901',
            'position' => 'Asset Coordinator',
            'role' => 'staff',
        ]);

        if ($staffRole) {
            $jane->roles()->syncWithoutDetaching([$staffRole->id]);
        }
    }
}
