<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'IT Department responsible for all technology infrastructure',
                'location' => 'Building A, 2nd Floor',
                'manager' => 'John Smith',
                'email' => 'it@bpa.com',
                'phone' => '+63 912 345 6780',
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'HR Department managing employee relations and recruitment',
                'location' => 'Building A, 1st Floor',
                'manager' => 'Maria Garcia',
                'email' => 'hr@bpa.com',
                'phone' => '+63 912 345 6781',
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Finance Department handling all financial operations',
                'location' => 'Building B, 3rd Floor',
                'manager' => 'Robert Johnson',
                'email' => 'finance@bpa.com',
                'phone' => '+63 912 345 6782',
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
                'description' => 'Operations Department managing daily business activities',
                'location' => 'Building B, 1st Floor',
                'manager' => 'Sarah Williams',
                'email' => 'ops@bpa.com',
                'phone' => '+63 912 345 6783',
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Marketing Department for brand and communications',
                'location' => 'Building C, 2nd Floor',
                'manager' => 'David Brown',
                'email' => 'marketing@bpa.com',
                'phone' => '+63 912 345 6784',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
