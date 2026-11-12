<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weight = $this->faker->randomElement([100, 250, 500, 1000]);

        return [
            'product_id' => Product::factory(),
            'weight_gram' => $weight,
            'price' => (int) round($weight * $this->faker->numberBetween(120, 260) / 50) * 50,
            'stock' => $this->faker->numberBetween(0, 30),
        ];
    }
}
