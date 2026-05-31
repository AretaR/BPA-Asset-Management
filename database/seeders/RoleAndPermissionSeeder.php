<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Permissions ─────────────────────────────────────────────────────
        $permissions = [
            // Users
            ['name' => 'View Users', 'slug' => 'users.view', 'description' => 'View user list and details'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'description' => 'Edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'description' => 'Delete users'],

            // Roles
            ['name' => 'View Roles', 'slug' => 'roles.view', 'description' => 'View roles list and details'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'description' => 'Create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'description' => 'Edit existing roles'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'description' => 'Delete roles'],

            // Permissions
            ['name' => 'View Permissions', 'slug' => 'permissions.view', 'description' => 'View permissions list'],
            ['name' => 'Assign Permissions', 'slug' => 'permissions.assign', 'description' => 'Assign permissions to roles'],

            // Assets
            ['name' => 'View Assets', 'slug' => 'assets.view', 'description' => 'View asset list and details'],
            ['name' => 'Create Assets', 'slug' => 'assets.create', 'description' => 'Create new assets'],
            ['name' => 'Edit Assets', 'slug' => 'assets.edit', 'description' => 'Edit existing assets'],
            ['name' => 'Delete Assets', 'slug' => 'assets.delete', 'description' => 'Delete assets'],
            ['name' => 'Checkout Assets', 'slug' => 'assets.checkout', 'description' => 'Check out assets to users'],
            ['name' => 'Checkin Assets', 'slug' => 'assets.checkin', 'description' => 'Check in assets from users'],

            // Categories
            ['name' => 'View Categories', 'slug' => 'categories.view', 'description' => 'View category list'],
            ['name' => 'Create Categories', 'slug' => 'categories.create', 'description' => 'Create new categories'],
            ['name' => 'Edit Categories', 'slug' => 'categories.edit', 'description' => 'Edit existing categories'],
            ['name' => 'Delete Categories', 'slug' => 'categories.delete', 'description' => 'Delete categories'],

            // Departments
            ['name' => 'View Departments', 'slug' => 'departments.view', 'description' => 'View department list'],
            ['name' => 'Create Departments', 'slug' => 'departments.create', 'description' => 'Create new departments'],
            ['name' => 'Edit Departments', 'slug' => 'departments.edit', 'description' => 'Edit existing departments'],
            ['name' => 'Delete Departments', 'slug' => 'departments.delete', 'description' => 'Delete departments'],

            // Reports
            ['name' => 'View Reports', 'slug' => 'reports.view', 'description' => 'View and generate reports'],

            // Settings
            ['name' => 'Manage Settings', 'slug' => 'settings.manage', 'description' => 'Access and update system settings'],

            // Scanner
            ['name' => 'Access Scanner', 'slug' => 'scanner.access', 'description' => 'Use the QR scanner'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $perm) {
            $createdPermissions[$perm['slug']] = Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        // ─── Roles ────────────────────────────────────────────────────────────
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full system access with all privileges',
            ]
        );

        $admin = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Administrative access with most privileges',
            ]
        );

        $staff = Role::firstOrCreate(
            ['slug' => 'staff'],
            [
                'name' => 'Staff',
                'description' => 'Basic staff access',
            ]
        );

        // ─── Assign all permissions to Super Admin ───────────────────────────
        $superAdmin->permissions()->sync(
            collect($createdPermissions)->pluck('id')->toArray()
        );

        // ─── Assign permissions to Admin (exclude role/permission management) ─
        $adminPermissions = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'assets.view', 'assets.create', 'assets.edit', 'assets.delete',
            'assets.checkout', 'assets.checkin',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'departments.view', 'departments.create', 'departments.edit', 'departments.delete',
            'reports.view',
            'settings.manage',
            'scanner.access',
        ];
        $admin->permissions()->sync(
            collect($adminPermissions)->map(fn ($slug) => $createdPermissions[$slug]->id)->toArray()
        );

        // ─── Assign permissions to Staff ─────────────────────────────────────
        $staffPermissions = [
            'assets.view',
            'categories.view',
            'departments.view',
            'reports.view',
            'scanner.access',
        ];
        $staff->permissions()->sync(
            collect($staffPermissions)->map(fn ($slug) => $createdPermissions[$slug]->id)->toArray()
        );
    }
}
