<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Kopi '.Str::title($this->faker->unique()->words(2, true));

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 9999),
            'origin' => $this->faker->randomElement(['Toraja', 'Lampung', 'Bali', 'Mandheling', 'Sidikalang']),
            'type' => $this->faker->randomElement(['Robusta', 'Arabika', 'Luwak']),
            'roast_level' => $this->faker->randomElement(['Light', 'Medium', 'Medium-Dark', 'Dark']),
            'description' => $this->faker->paragraph(3),
            'flavor_notes' => [
                'bitterness' => $this->faker->numberBetween(1, 5),
                'acidity' => $this->faker->numberBetween(1, 5),
                'aroma' => $this->faker->numberBetween(1, 5),
                'body' => $this->faker->numberBetween(1, 5),
                'notes' => $this->faker->words(3, true),
            ],
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
