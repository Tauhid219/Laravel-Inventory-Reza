<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $permissions = Permission::all();

        // Assign all permissions to super-admin role
        $superAdminRole->syncPermissions($permissions);

        // Get the 'admin' role from the roles table.
        $adminRole = Role::where('name', 'admin')->first();

        // Get specific permissions for the 'admin' role (change these to your desired permissions)
        $adminPermissions = Permission::whereIn('name', [
            'view role',
            'update role',
            'view permission',

            'create user',
            'view user',
            'update user',
            'delete user',

            'create product',
            'view product',
            'update product',
            'delete product',

            'create purchase',
            'view purchase',
            'update purchase',
            'delete purchase',

            'create order',
            'view order',
            'update order',
            'delete order',

            'create category',
            'view category',
            'update category',
            'delete category',

            'create subcategory',
            'view subcategory',
            'update subcategory',
            'delete subcategory',

            'create customer',
            'view customer',
            'update customer',
            'delete customer',

            'create supplier',
            'view supplier',
            'update supplier',

            'create unit',
            'view unit',
            'update unit',
            'delete unit',

            'create quotation',
            'view quotation',
            'update quotation',
            'delete quotation',
        ])->get();

        // Assign only the specific permissions to the 'admin' role.
        $adminRole->syncPermissions($adminPermissions);

        // Get the 'demo-admin' role from the roles table.
        $demoAdminRole = Role::where('name', 'demo-admin')->first();

        // Restrict demo access to read-only business visibility.
        $demoAdminPermissions = Permission::whereIn('name', [
            'view role',
            'view permission',
            'view user',
            'view product',
            'view purchase',
            'view order',
            'view category',
            'view subcategory',
            'view customer',
            'view supplier',
            'view unit',
            'view quotation',
        ])->get();

        $demoAdminRole?->syncPermissions($demoAdminPermissions);
    }
}
