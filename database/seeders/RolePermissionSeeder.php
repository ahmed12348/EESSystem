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
            'edit users',
            'delete users',
            'create users',
            'view roles',
            'edit roles',
            'create roles',
            'delete roles',

            'view products',
            'view dashboard',
            'view orders',
            'view vendors',
            'view customers',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $vendorRole = Role::create(['name' => 'vendor']);
        $customerRole = Role::create(['name' => 'customer']);

        // Assign permissions to admin role (Admin has full control)
        $adminRole->givePermissionTo($permissions);

        // Assign permissions to vendor role (Vendor has restricted permissions)
        $vendorPermissions = [
            'view products',
            'view orders',
            'view dashboard',
        ];
        $vendorRole->givePermissionTo($vendorPermissions);

        // Assign permissions to customer role (Customer has minimal permissions)
        $customerPermissions = [
            'view products',
            'view orders',
        ];
        $customerRole->givePermissionTo($customerPermissions);

        // Create users and assign roles
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ees.com',
            'password' => bcrypt('password')
        ]);
        $adminUser->assignRole('admin');

        $vendorUser = User::create([
            'name' => 'Vendor User 1',
            'email' => 'vendor@ees.com',
            'password' => bcrypt('password')
        ]);
        $vendorUser->assignRole('vendor');

        $customerUser = User::create([
            'name' => 'Customer User 1',
            'email' => 'customer@ees.com',
            'password' => bcrypt('password')
        ]);
        $customerUser->assignRole('customer');
    }
}
