<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define permissions dynamically for each module
        $permissions = [
            'ads' => ['view', 'create', 'edit', 'delete'],
            'carts' => ['view', 'delete', 'create', 'edit'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'cities' => ['view'],
            'discounts' => ['view', 'create', 'edit', 'delete'],
            'orders' => ['view', 'create', 'edit', 'delete'],
            'products' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],
            'regions' => ['view'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'searches' => ['view'],
            'settings' => ['view', 'edit'],
            'users' => ['view', 'create', 'edit', 'delete'],
            'vendors' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],
        ];

        // Create permissions dynamically
        foreach ($permissions as $table => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$table}"]);
            }
        }

        // Fetch all permissions
        $allPermissions = Permission::all();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Assign permissions correctly
        $adminRole->syncPermissions($allPermissions);

        $vendorPermissions = Permission::whereIn('name', [
            'view_products', 'create_products', 'edit_products', 'delete_products',
            'view_orders', 'create_orders', 'edit_orders', 'delete_orders',
            'view_discounts', 'create_discounts', 'edit_discounts', 'delete_discounts'
        ])->get();
        $vendorRole->syncPermissions($vendorPermissions);

        $customerPermissions = Permission::whereIn('name', [
            'view_products',
            'view_orders',
            'view_carts'
        ])->get();
        $customerRole->syncPermissions($customerPermissions);

        // Create default users and assign roles
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@ees.com'],
            [
                'name' => 'Admin User',
                'phone' => '1111',
                'status' => 'active',
                'password' => bcrypt('password')
            ]
        );
        $adminUser->assignRole('admin');

        $vendorUser = User::firstOrCreate(
            ['email' => 'vendor@ees.com'],
            [
                'name' => 'Vendor User',
                'phone' => '2222',
                'password' => bcrypt('password')
            ]
        );
        $vendorUser->assignRole('vendor');

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@ees.com'],
            [
                'name' => 'Customer User',
                'phone' => '3333',
                'password' => bcrypt('password')
            ]
        );
        $customerUser->assignRole('customer');

        echo "Permissions and Roles Seeded Successfully!";
    }
}
