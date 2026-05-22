<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'name' => fake()->productName() ?? fake()->word() . ' ' . fake()->randomElement(['Pro', 'Plus', ' Elite']),
            'asset_tag' => Asset::generateAssetTag(),
            'serial_number' => strtoupper(fake()->unique()->lexify('???')) . '-' . fake()->randomNumber(6),
            'category_id' => Category::factory(),
            'department_id' => Department::factory(),
            'assigned_to' => null,
            'purchase_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'purchase_cost' => fake()->randomFloat(2, 1000, 100000),
            'status' => fake()->randomElement(['available', 'assigned', 'maintenance', 'retired']),
            'location' => fake()->buildingNumber() . ' ' . fake()->streetName(),
            'description' => fake()->paragraph(),
            'warranty_expiry' => fake()->dateTimeBetween('now', '+2 years'),
            'manufacturer' => fake()->company(),
            'model' => fake()->randomElement(['Model A', 'Model B', 'Model C']) . '-' . fake()->randomNumber(3),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
