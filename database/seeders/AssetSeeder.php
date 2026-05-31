<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $departments = Department::all();
        $users = User::where('role', 'staff')->get();

        $assets = [
            [
                'name' => 'Dell Latitude 5520 Laptop',
                'serial_number' => 'DL5520-2024-001',
                'status' => 'assigned',
                'purchase_cost' => 45000.00,
                'manufacturer' => 'Dell',
                'model' => 'Latitude 5520',
            ],
            [
                'name' => 'HP ProDesk 400 G9 Desktop',
                'serial_number' => 'HP400G9-2024-002',
                'status' => 'available',
                'purchase_cost' => 35000.00,
                'manufacturer' => 'HP',
                'model' => 'ProDesk 400 G9',
            ],
            [
                'name' => 'Canon imageCLASS MF644Cdw',
                'serial_number' => 'CNM644-2024-003',
                'status' => 'assigned',
                'purchase_cost' => 28000.00,
                'manufacturer' => 'Canon',
                'model' => 'imageCLASS MF644Cdw',
            ],
            [
                'name' => 'Toyota Hilux Pickup',
                'serial_number' => 'TYH-2024-001',
                'status' => 'available',
                'purchase_cost' => 1200000.00,
                'manufacturer' => 'Toyota',
                'model' => 'Hilux',
            ],
            [
                'name' => 'Sony A7 IV Camera',
                'serial_number' => 'SNA7IV-2024-001',
                'status' => 'maintenance',
                'purchase_cost' => 185000.00,
                'manufacturer' => 'Sony',
                'model' => 'A7 IV',
            ],
            [
                'name' => 'Executive Office Chair',
                'serial_number' => 'CHR-2024-001',
                'status' => 'available',
                'purchase_cost' => 8500.00,
                'manufacturer' => 'Herman Miller',
                'model' => 'Embody',
            ],
            [
                'name' => 'Cisco Catalyst 9300 Switch',
                'serial_number' => 'CSC9300-2024-001',
                'status' => 'assigned',
                'purchase_cost' => 95000.00,
                'manufacturer' => 'Cisco',
                'model' => 'Catalyst 9300',
            ],
            [
                'name' => 'iPhone 15 Pro Max',
                'serial_number' => 'APL-IP15P-2024-001',
                'status' => 'assigned',
                'purchase_cost' => 75000.00,
                'manufacturer' => 'Apple',
                'model' => 'iPhone 15 Pro Max',
            ],
        ];

        foreach ($assets as $index => $assetData) {
            $category = $categories->random();
            $department = $departments->random();
            $assignedTo = $assetData['status'] === 'assigned' ? ($users->isNotEmpty() ? $users->random()->id : null) : null;

            Asset::updateOrCreate(
                ['serial_number' => $assetData['serial_number']],
                [
                    'name' => $assetData['name'],
                    'asset_tag' => Asset::generateAssetTag(),
                    'category_id' => $category->id,
                    'department_id' => $department->id,
                    'assigned_to' => $assignedTo,
                    'purchase_date' => now()->subDays(rand(30, 365)),
                    'purchase_cost' => $assetData['purchase_cost'],
                    'status' => $assetData['status'],
                    'location' => $department->location,
                    'description' => 'Sample asset for testing purposes',
                    'warranty_expiry' => now()->addDays(rand(180, 730)),
                    'manufacturer' => $assetData['manufacturer'],
                    'model' => $assetData['model'],
                ]
            );
        }
    }
}
