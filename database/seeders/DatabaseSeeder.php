<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            DepartmentSeeder::class,
            AssetSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
