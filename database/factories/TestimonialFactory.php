<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'city' => $this->faker->city(),
            'quote' => rtrim($this->faker->sentence(12), '.').'.',
            'rating' => $this->faker->numberBetween(4, 5),
            'is_active' => true,
        ];
    }
}
