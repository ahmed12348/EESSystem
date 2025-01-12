<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions (Add all the necessary permissions you want)
        $permissions = [
            'view users',
            'view products',
            'view dashboard',
            'view orders'
           
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $vendorRole = Role::create(['name' => 'vendor']);

        // Assign permissions to admin role (Admin has full control)
        $adminRole->givePermissionTo($permissions);

        // Assign permissions to vendor role (Vendor has restricted permissions)
        $vendorPermissions = [
            'view products', 
            'view dashboard', 
            'view orders'
        ];
        $vendorRole->givePermissionTo($vendorPermissions);

        // Create users and assign roles
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ees.com',
            'password' => bcrypt('password')
        ]);
        $adminUser->assignRole('admin');

        $vendorUser1 = User::create([
            'name' => 'Vendor User 1',
            'email' => 'vendor1@ees.com',
            'password' => bcrypt('password')
        ]);
        $vendorUser1->assignRole('vendor');

        $vendorUser2 = User::create([
            'name' => 'Vendor User 2',
            'email' => 'vendor2@ees.com',
            'password' => bcrypt('password')
        ]);
        $vendorUser2->assignRole('vendor');
    }
}
