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

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }
    }
}
