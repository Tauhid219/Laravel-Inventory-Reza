<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create role',
            'view role',
            'update role',
            'delete role',
            'create permission',
            'view permission',
            'update permission',
            'delete permission',
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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
