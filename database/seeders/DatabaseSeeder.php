<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
                //UserSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeederForRolePermission::class,
            RolePermissionSeeder::class
        ]);

        $orders = Order::factory(5)->create();
        $customers = Customer::factory(5)
            ->recycle($orders)
            ->create();


        $purchases = Purchase::factory(5)->create();
        $suppliers = Supplier::factory(5)->create();

        $users = User::factory(5)
            ->recycle($suppliers)
            ->recycle($purchases)
            ->create();

        // $admin = User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@admin.com'
        // ]);

        /*
        for ($i=0; $i < 10; $i++) {
            Product::factory()->create([
                'product_code' => IdGenerator::generate([
                    'table' => 'products',
                    'field' => 'product_code',
                    'length' => 4,
                    'prefix' => 'PC'
                ]),
            ]);
        }
        */

    }
}
