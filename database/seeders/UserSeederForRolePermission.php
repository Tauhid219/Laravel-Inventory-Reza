<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeederForRolePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'ahmad@gmail.com')->exists()) {
            $user = User::create([
                'name' => 'Ahmad',
                'username' => 'ahmad',
                'email' => 'ahmad@gmail.com',
                'password' => bcrypt('12345678'),
            ]);

            $role = Role::where('name', 'super-admin')->first();
            $user->assignRole($role);
        }
    }
}
