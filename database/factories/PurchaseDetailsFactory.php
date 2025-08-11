<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseDetails>
 */
class PurchaseDetailsFactory extends Factory
{
    protected $model = \App\Models\PurchaseDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_id' => Purchase::factory(),  // Creating a related purchase
            'product_id' => Product::factory(),    // Creating a related product
            'quantity' => $this->faker->numberBetween(1, 100),
            'unitcost' => $this->faker->randomFloat(2, 10, 100),
            'total' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
