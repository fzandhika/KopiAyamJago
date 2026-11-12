<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_code' => Order::generateCode(),
            'customer_name' => $this->faker->name(),
            'phone' => '08'.$this->faker->numerify('##########'),
            'address' => $this->faker->address(),
            'status' => 'pending',
            'total' => $this->faker->numberBetween(50_000, 500_000),
            'payment_method' => $this->faker->randomElement(['whatsapp', 'transfer', 'qris']),
            'notes' => null,
        ];
    }
}
