<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FuelEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fuel_type' => $this->faker->randomElement(['gas_oil', 'gasoline']),
            'amount' => $this->faker->numberBetween(2000, 10000)
        ];
    }
}
