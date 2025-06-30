<?php

namespace Database\Factories;

use App\Enums\TaxType;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name), // ✅ slug তৈরি name থেকে
            'code' => 'PC' . strtoupper(Str::random(6)), // ✅ কোড ইউনিক
            'category_id' => \App\Models\Category::factory(),
            'unit_id' => \App\Models\Unit::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'buying_price' => $this->faker->numberBetween(100, 1000),
            'selling_price' => $this->faker->numberBetween(1000, 2000),
            'quantity_alert' => $this->faker->randomElement([5, 10, 15]),
            'tax' => $this->faker->randomElement([5, 10, 15, 20, 25]),
            'tax_type' => $this->faker->randomElement([
                TaxType::EXCLUSIVE->value,
                TaxType::INCLUSIVE->value,
            ]),
        ];
    }
}
