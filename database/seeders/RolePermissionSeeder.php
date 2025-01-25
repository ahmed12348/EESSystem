<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'view users', 'edit users', 'delete users', 'create users',
            'view roles', 'edit roles', 'create roles', 'delete roles',
            'view products', 'view dashboard', 'view orders',
            'view vendors', 'view customers',
        ];

        // Create permissions in the database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        // Assign permissions to roles
        $adminRole->syncPermissions($permissions);

        $vendorPermissions = [
            'view products', 'view orders', 'view dashboard',
        ];
        $vendorRole->syncPermissions($vendorPermissions);

        // Create an admin user
        $adminUser = User::firstOrCreate([
            'email' => 'admin@ees.com',
        ], [
            'name' => 'Admin User',
            'phone' => '1111',
            'is_verified' => 'yes',
            'status' => 'Active',
            'password' => Hash::make('password'), // Use Hash::make for security
        ]);
        $adminUser->assignRole('admin');

        // Create a vendor user
        $vendorUser = User::firstOrCreate([
            'email' => 'vendor@ees.com',
        ], [
            'name' => 'Vendor User 1',
            'phone' => '2222',
            'password' => Hash::make('password'), // Use Hash::make for security
        ]);
        $vendorUser->assignRole('vendor');

        // Link the vendor user to the Vendor table
        $vendor = Vendor::firstOrCreate([
            'user_id' => $vendorUser->id, // Link the user to vendor
            'business_name' => 'Vendor Business',
            'business_number' => '123456789', // Add a business number
            'city' => 'City Name',
            'state' => 'State Name',
            'zone' => 'Zone Name',
        
        ]);
        $customerPermissions = [
            'view products', 'view orders', 'view dashboard',
        ];
        $customerRole->syncPermissions($customerPermissions);
        $customerUser = User::firstOrCreate([
            'email' => 'customer@ees.com',
        ], [
            'name' => 'customer User 1',
            'phone' => '3333',
            'password' => Hash::make('password'), // Use Hash::make for security
        ]);
        $customerUser->assignRole('customer');
        // Optionally, you can assign additional roles or permissions to the vendor if needed
        // $vendor->assignRole('vendor'); // This line is redundant since the user already has the 'vendor' role

        // If you want to update vendor info later, you can use this:
        // $vendor->update([
        //     'status' => 'active', // For example, after admin approval
        // ]);
    }
}
