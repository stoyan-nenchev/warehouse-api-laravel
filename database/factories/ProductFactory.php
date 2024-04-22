<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buying_price = $this->faker->numberBetween(1,20);
        $type = $this->faker->randomElement(['Office supplies', 'Building materials']);

        return [
            'name' => $this->faker->words(rand(1, 3), true),
            'buying_price' => $this->faker->numberBetween(1,20),
            'selling_price' => $this->faker->numberBetween($buying_price,40),
            'quantity' => $this->faker->numberBetween(1,1000),
            'type' => $type,
            'code' => $this->faker->unique()->uuid()
        ];
    }
}
