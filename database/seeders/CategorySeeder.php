<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect([
            [
                'id' => 1,
                'name' => 'Passive',
                'slug' => 'passive',
                'created_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Printer',
                'slug' => 'printer',
                'created_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Scanner',
                'slug' => 'scanner',
                'created_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'Server',
                'slug' => 'server',
                'created_at' => now()
            ],
            [
                'id' => 5,
                'name' => 'Storage',
                'slug' => 'storage',
                'created_at' => now()
            ],
            [
                'id' => 6,
                'name' => 'Server Accessories',
                'slug' => 'server-accessories',
                'created_at' => now()
            ],
            [
                'id' => 7,
                'name' => 'Network Wired',
                'slug' => 'network-wired',
                'created_at' => now()
            ],
            [
                'id' => 8,
                'name' => 'Network Tools',
                'slug' => 'network-tools',
                'created_at' => now()
            ],
            [
                'id' => 9,
                'name' => 'Network Wireless',
                'slug' => 'network-wireless',
                'created_at' => now()
            ],
            [
                'id' => 10,
                'name' => 'Network Security',
                'slug' => 'network-security',
                'created_at' => now()
            ],
            [
                'id' => 11,
                'name' => 'General Others',
                'slug' => 'general-others',
                'created_at' => now()
            ],
            [
                'id' => 12,
                'name' => 'Office Accessories',
                'slug' => 'office-accessories',
                'created_at' => now()
            ],
            [
                'id' => 13,
                'name' => 'Office Stationeries',
                'slug' => 'office-stationeries',
                'created_at' => now()
            ],
        ]);

        $categories->each(function ($category) {
            Category::insert($category);
        });
    }
}
