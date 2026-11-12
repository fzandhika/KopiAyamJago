<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_variant_id' => null,
            'product_name' => 'Kopi '.$this->faker->word(),
            'weight_gram' => $this->faker->randomElement([100, 250, 500, 1000]),
            'grind_type' => $this->faker->randomElement(array_keys(OrderItem::GRIND_TYPES)),
            'qty' => $this->faker->numberBetween(1, 3),
            'price' => $this->faker->numberBetween(15_000, 300_000),
        ];
    }
}
