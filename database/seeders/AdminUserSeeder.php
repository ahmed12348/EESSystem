<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Define permissions
        $permissions = [
            'ads' => ['view', 'create', 'edit', 'delete'],
            'carts' => ['view', 'delete', 'create', 'edit'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'discounts' => ['view', 'create', 'edit', 'delete'],
            'orders' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'settings' => ['view', 'edit'],
            'top_products_table' => ['view'],
            'searches' => ['view'],
            'products' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],
            'users' => ['view', 'create', 'edit', 'delete','approve', 'reject'],
            'vendors' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],
            'customers' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],
           
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module-$action"]);
            }
        }

        // Create admin user
        $admin = User::updateOrCreate([
            'email' => 'admin@ees.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'type' => 'admin',
            'status' => 'approved',
           
            
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // Assign admin role
        $admin->assignRole($adminRole);
        
        // // Create vendor entry for admin
        // Vendor::updateOrCreate([
        //     'user_id' => $admin->id,
        // ], [
        //     'business_name' => 'Admin Business',
        //     'zone' => 'zone_1',
        //     'tax_id' => '123456789',
        //     'photo' => null,
        //     'business_type' => 'Admin Type',
        // ]);
    }
}

