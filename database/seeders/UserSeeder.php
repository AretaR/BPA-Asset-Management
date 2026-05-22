<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bpa.com',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP001',
            'phone' => '+63 912 345 6789',
            'position' => 'System Administrator',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Smith',
            'email' => 'john@bpa.com',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP002',
            'phone' => '+63 923 456 7890',
            'position' => 'IT Manager',
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@bpa.com',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP003',
            'phone' => '+63 934 567 8901',
            'position' => 'Asset Coordinator',
            'role' => 'staff',
        ]);
    }
}
