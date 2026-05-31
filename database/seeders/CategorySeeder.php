<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'IT Equipment', 'description' => 'Computers, laptops, monitors, and other IT hardware'],
            ['name' => 'Office Furniture', 'description' => 'Desks, chairs, cabinets, and office furniture'],
            ['name' => 'Vehicles', 'description' => 'Company vehicles and transportation equipment'],
            ['name' => 'Communication Equipment', 'description' => 'Phones, radios, and communication devices'],
            ['name' => 'Audio/Video Equipment', 'description' => 'Cameras, microphones, speakers, and AV equipment'],
            ['name' => 'Software Licenses', 'description' => 'Software and digital licenses'],
            ['name' => 'Printers & Scanners', 'description' => 'Printers, scanners, and multifunction devices'],
            ['name' => 'Networking Equipment', 'description' => 'Routers, switches, and network hardware'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
